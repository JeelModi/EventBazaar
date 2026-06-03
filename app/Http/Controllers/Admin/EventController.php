<?php
// app/Http/Controllers/Admin/EventController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Session;

class EventController extends Controller
{
    // Show all events in admin dashboard (GET)
    public function index()
    {
        // Get all events, newest first
        $events = Event::orderBy('created_at', 'desc')->get();
        return view('admin.events.index', compact('events'));
    }

    // Show create event form (GET)
    public function create()
    {
        return view('admin.events.create');
    }

    // Handle create event form submission (POST)
    public function store(Request $request)
    {
        // Validate inputs
        $request->validate([
            'title'      => 'required|min:3',
            'description'=> 'required',
            'event_date' => 'required|date|after:today',
            'capacity'   => 'required|integer|min:1',
            'price'      => 'required|numeric|min:0',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // max 2MB
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Store in storage/app/public/events/
            $imagePath = $request->file('image')->store('events', 'public');
        }

        // Create event using OOP
        Event::create([
            'admin_id'    => Session::get('user_id'),
            'title'       => $request->title,
            'description' => $request->description,
            'image_path'  => $imagePath,
            'event_date'  => $request->event_date,
            'capacity'    => $request->capacity,
            'price'       => $request->price,
            'status'      => 'active',
        ]);

        return redirect()->route('admin.events.index')
                         ->with('success', 'Event created successfully!');
    }

    // Show edit event form (GET)
    public function edit($id)
    {
        // Find event or fail with 404
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    // Handle edit form submission (POST)
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title'      => 'required|min:3',
            'description'=> 'required',
            'event_date' => 'required|date',
            'capacity'   => 'required|integer|min:1',
            'price'      => 'required|numeric|min:0',
            'status'     => 'required|in:active,cancelled,completed',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle new image upload
        $imagePath = $event->image_path; // keep old image by default
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        // Update event
        $event->update([
            'title'       => $request->title,
            'description' => $request->description,
            'image_path'  => $imagePath,
            'event_date'  => $request->event_date,
            'capacity'    => $request->capacity,
            'price'       => $request->price,
            'status'      => $request->status,
        ]);

        return redirect()->route('admin.events.index')
                         ->with('success', 'Event updated successfully!');
    }

    // Delete event (POST)
    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.events.index')
                         ->with('success', 'Event deleted successfully!');
    }
}