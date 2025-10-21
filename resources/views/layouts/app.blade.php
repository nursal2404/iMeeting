<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iMeeting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    @stack('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom px-3">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <img src="{{ asset('assets/logo_pln.png') }}" height="40"> iMeeting
            </a>
            
            <ul class="navbar-nav ms-auto d-flex flex-row align-items-center">                
                <li class="nav-item me-3">
                    <a class="nav-link" href="#">
                        <i class="bi bi-bell-fill fs-5"></i>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="userDropdown" data-bs-toggle="dropdown">
                        <img src="https://www.citypng.com/public/uploads/preview/hd-man-user-illustration-icon-transparent-png-701751694974843ybexneueic.png" alt="User" class="profile-pic">
                        {{ Auth::user()->name ?? 'John Doe' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="body-wrapper">
        
        <div class="sidebar" id="sidebar">

            <nav class="nav flex-column mt-3">
                
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" 
                   title="Dashboard" data-bs-toggle="tooltip" data-bs-placement="right">
                    <i class="bi bi-house-door"></i> 
                    <span class="link-text">Dashboard</span>
                </a>

                <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->is('bookings*') ? 'active' : '' }}"
                   title="Ruang Meeting" data-bs-toggle="tooltip" data-bs-placement="right">
                    <i class="bi bi-file-earmark"></i> 
                    <span class="link-text">Ruang Meeting</span>
                </a>

                @can('isSuperAdmin')
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}"
                    title="Manajemen User" data-bs-toggle="tooltip" data-bs-placement="right">
                        <i class="bi bi-people"></i> 
                    </a>
                @endcan
            </nav>
        </div>

        <div class="main-content">            
            <main class="page-content-card">                        
                <div class="content-body">
                    @yield('content')
                    </div>
            </main>
        </div>
    </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inisialisasi Tooltip Bootstrap (untuk nama ikon sidebar)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    @stack('scripts')
</body>
</html>