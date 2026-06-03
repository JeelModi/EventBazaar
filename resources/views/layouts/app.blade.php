<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: bold; }
        .sidebar { min-height: 100vh; background: #343a40; padding-top: 20px; }
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 10px 20px; }
        .sidebar a:hover { color: #fff; background: #495057; }
        .sidebar a.active { color: #fff; background: #0d6efd; }
        .main-content { padding: 30px; }
    </style>
</head>
<body>

{{-- Top Navbar --}}
<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand">
        <i class="bi bi-calendar-event"></i> EventBazaar
    </span>
    <div class="d-flex align-items-center text-white gap-3">
        <span><i class="bi bi-person-circle"></i> {{ Session::get('user_name') }}</span>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button class="btn btn-sm btn-outline-light">Logout</button>
        </form>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">

        {{-- Sidebar --}}
        <div class="col-md-2 sidebar p-0">
            @if(Session::get('user_role') === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('admin.events.create') }}">
                    <i class="bi bi-plus-circle"></i> Create Event
                </a>
            @else
                <a href="{{ route('events.index') }}"
                class="{{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i> All Events
                </a>
                <a href="{{ route('bookings.my') }}"
                class="{{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                    <i class="bi bi-ticket"></i> My Bookings
                </a>
            @endif
        </div>

        {{-- Main Content --}}
        <div class="col-md-10 main-content">
            {{-- Success/Error messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Page content goes here --}}
            @yield('content')
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>