@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Create New Event</h5>
            </div>
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                {{-- enctype for file upload --}}
                <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Event Title *</label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-control"
                                  rows="4" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Event Date & Time *</label>
                            <input type="datetime-local" name="event_date"
                                   class="form-control" value="{{ old('event_date') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Capacity (seats) *</label>
                            <input type="number" name="capacity" class="form-control"
                                   value="{{ old('capacity', 100) }}" min="1" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Price (₹) *</label>
                            <input type="number" name="price" class="form-control"
                                   value="{{ old('price', 0) }}" min="0" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Event Image</label>
                            <input type="file" name="image" class="form-control"
                                   accept="image/jpg,image/jpeg,image/png">
                            <small class="text-muted">JPG/PNG, max 2MB</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Create Event
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