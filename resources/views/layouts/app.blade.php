<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Event Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bs-dark: #212529;
            --bs-dark-rgb: 33, 37, 41;
            --bs-body-bg: #1a1a1a;
            --bs-body-color: #e5e7eb; /* light gray for high contrast */
            --bs-secondary-bg: #2d2d2d;
            --bs-border-color: #495057;
            --bs-link-color: #93c5fd; /* light blue links */
            --bs-link-hover-color: #bfdbfe;
            --bs-heading-color: #ffffff;
            --bs-secondary-color: #cbd5e1;
        }

        body {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            font-family: 'Figtree', sans-serif;
        }

        .navbar-dark {
            background-color: var(--bs-dark) !important;
        }

        .card {
            background-color: var(--bs-secondary-bg);
            border-color: var(--bs-border-color);
            color: var(--bs-body-color);
        }

        .card-header {
            background-color: var(--bs-dark);
            border-bottom-color: var(--bs-border-color);
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
        }

        /* High-contrast text utilities on dark */
        a, .nav-link { color: var(--bs-link-color); }
        a:hover, .nav-link:hover { color: var(--bs-link-hover-color); }
        .text-muted, .text-secondary, .card .text-muted { color: #b6c0cc !important; }
        .card-header, .card-title, h1, h2, h3, h4, h5, h6 { color: var(--bs-heading-color); }
        .dropdown-menu { background-color: var(--bs-secondary-bg); color: var(--bs-body-color); }
        .dropdown-item { color: var(--bs-body-color); }
        .dropdown-item:hover { background-color: #3a3f44; color: #ffffff; }

        /* Progress on dark */
        .progress { background-color: #3a3f44; }
        .progress-bar { color: #ffffff; }

        /* Pagination dark overrides */
        .pagination { --bs-pagination-bg: var(--bs-secondary-bg); }
        .page-link { color: var(--bs-link-color); background-color: transparent; border: none; padding: .375rem .5rem; }
        .page-link:hover { color: var(--bs-link-hover-color); background-color: #3a3f44; border-color: var(--bs-border-color); }
        .page-item.active .page-link { color: #93c5fd; background-color: transparent; font-weight: 600; }
        .page-item.disabled .page-link { color: #8892a0; background-color: transparent; }

        .table-dark {
            --bs-table-bg: var(--bs-secondary-bg);
            --bs-table-striped-bg: rgba(255, 255, 255, 0.05);
            --bs-table-hover-bg: rgba(255, 255, 255, 0.075);
        }

        .alert-success {
            background-color: rgba(25, 135, 84, 0.2);
            border-color: rgba(25, 135, 84, 0.3);
            color: #75b798;
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.2);
            border-color: rgba(220, 53, 69, 0.3);
            color: #f5c2c7;
        }

        .form-control {
            background-color: var(--bs-secondary-bg);
            border-color: var(--bs-border-color);
            color: var(--bs-body-color);
        }

        .form-control:focus {
            background-color: var(--bs-secondary-bg);
            border-color: #0d6efd;
            color: var(--bs-body-color);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .form-select {
            background-color: var(--bs-secondary-bg);
            border-color: var(--bs-border-color);
            color: var(--bs-body-color);
        }

        .form-select:focus {
            background-color: var(--bs-secondary-bg);
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('events.index') }}">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Event Management System
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('events.index') }}">
                                <i class="fas fa-list me-1"></i>Events
                            </a>
                        </li>
                        
                        @auth
                            @if(auth()->user()->isAttendee())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bookings.index') }}">
                                        <i class="fas fa-ticket-alt me-1"></i>My Bookings
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bookings.waitlist') }}">
                                        <i class="fas fa-clock me-1"></i>My Waitlist
                                    </a>
                                </li>
                            @elseif(auth()->user()->isOrganiser())
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('dashboard.index') }}">
                                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('events.create') }}">
                                        <i class="fas fa-plus me-1"></i>Create Event
                                    </a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i>Register
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                                    <span class="badge bg-secondary ms-1">{{ ucfirst(auth()->user()->user_type) }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="py-4">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    @yield('scripts')
</body>
</html>
