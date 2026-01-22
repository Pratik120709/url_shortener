<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'URL Shortener'))</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        :root {
            --sidebar-gradient: linear-gradient(
                90deg,
                #1A365D 0%,
                #2C7A7B 50%,
                #4FD1C5 100%
            );
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
        }

        .sidebar .nav-link {
            color: #1f2937;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: var(--sidebar-gradient);
            color: #ffffff;
        }

        .content-wrapper {
            flex: 1;
            background: #f8f9fa;
        }

        @media (max-width: 991px) {
            .sidebar {
                position: fixed;
                left: -260px;
                top: 0;
                z-index: 1040;
                transition: all 0.3s ease;
            }
            .sidebar.show {
                left: 0;
            }
        }
    </style>
</head>

<body>
<div class="d-flex">

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar p-3">
        <h5 class="text-white mb-4 text-center">
                <img src="{{ asset('/assets/img/url_shortener_logo.png') }}" alt="Logo" style="width:100%; height: 56px; object-fit: cover;">
        </h5>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('short-urls.index') ? 'active' : '' }}"
                   href="{{ route('short-urls.index') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            @can('create_short_url')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('short-urls.create') || request()->routeIs('short-urls') ? 'active' : '' }}"
                   href="{{ route('short-urls.create') }}">
                    <i class="bi bi-link-45deg me-2"></i> Create Short URL
                </a>
            </li>
                        {{-- <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('invitations.*') ? 'active' : '' }}"
                   href="{{ route('invitations.create') }}">
                    <i class="bi bi-person-plus me-2"></i> Invite Member
                </a>
            </li> --}}
            @endcan
@php
$canInvite = false;
    $user = Auth::user();
    if ($user->isSuperAdmin() || $user->isAdmin()) {
        $canInvite = true;
    }
@endphp
@if ($canInvite)
            {{-- @can('invite_admin') --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('invitations.*') ? 'active' : '' }}"
                   href="{{ route('invitations.create') }}">
                    <i class="bi bi-person-plus me-2"></i> Invite Member
                </a>
            </li>
            {{-- @endcan --}}
            @endif
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="content-wrapper">

        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">
            <button class="btn btn-outline-primary d-lg-none"
                    onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>

            <span class="navbar-brand ms-3">
                @yield('page-title', 'Dashboard')
            </span>

            <div class="ms-auto dropdown">
                @auth
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                    {{ Auth::user()->name }}
                    <small class="text-muted">
                        ({{ Auth::user()->getRoleNames()->first() }})
                    </small>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('short-urls.index') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
                @endauth
            </div>
        </nav>

        <!-- Page Content -->
        <main class="p-4">

            {{-- Alerts --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    @if(session('short_url'))
                        <br>
                        <strong>Short URL:</strong>
                        <a href="{{ session('short_url') }}" target="_blank">
                            {{ session('short_url') }}
                        </a>
                    @endif
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@stack('scripts')
</body>
</html>
