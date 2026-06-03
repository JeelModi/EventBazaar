@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning">
                <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Event</h5>
            </div>
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.events.update', $event->id) }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Event Title *</label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $event->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control"
                                  rows="4" required>{{ old('description', $event->description) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Event Date & Time *</label>
                            <input type="datetime-local" name="event_date" class="form-control"
                                   value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}"
                                   required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Capacity *</label>
                            <input type="number" name="capacity" class="form-control"
                                   value="{{ old('capacity', $event->capacity) }}" min="1" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Price (₹) *</label>
                            <input type="number" name="price" class="form-control"
                                   value="{{ old('price', $event->price) }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select">
                                <option value="active"    {{ $event->status === 'active'    ? 'selected' : '' }}>Active</option>
                                <option value="cancelled" {{ $event->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="completed" {{ $event->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Change Image</label>
                            <input type="file" name="image" class="form-control"
                                   accept="image/jpg,image/jpeg,image/png">
                        </div>
                    </div>

                    {{-- Show current image --}}
                    @if($event->image_path)
                    <div class="mb-3">
                        <label class="form-label">Current Image</label><br>
                        <img src="{{ asset('storage/' . $event->image_path) }}"
                             height="100" style="border-radius:8px; border:1px solid #ddd">
                    </div>
                    @endif

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle"></i> Update Event
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection