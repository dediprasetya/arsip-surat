<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Arsip Surat')</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    
    @stack('styles')
</head>
<body class="hold-transition {{ request()->is('login') ? 'login-page' : 'sidebar-mini' }}">
    <div class="wrapper">

        @if (!request()->is('login')) 
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" id="toggle-sidebar" href="#" role="button">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto">
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                            <span class="dropdown-item-text"><strong>{{ Auth::user()->name }}</strong></span>
                            <span class="dropdown-item-text">{{ Auth::user()->email }}</span>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </li>
                    @endauth
                </ul>
            </nav>


            <!-- Sidebar -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <a href="#" class="brand-link">
                    <img src="{{ asset('images/logo_pemkab.png') }}" 
                        alt="Logo Kabupaten Semarang" 
                        class="brand-image img-circle elevation-3"
                        style="opacity: .8">
                    <span class="brand-text font-weight-light">Arsip Surat</span>
                </a>

                <div class="sidebar">
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link">
                                    <i class="nav-icon fas fa-home"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('klasifikasi.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-folder"></i>
                                    <p>Kategori Surat</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('surat.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-envelope"></i>
                                    <p>Surat Masuk</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('surat-keluar.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-envelope"></i>
                                    <p>Surat Keluar</p>
                                </a>
                            </li>
                    
                            <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                Agenda Surat
                                <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview ml-3">
                                <li class="nav-item">
                                    <a href="{{ route('agenda.index') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Agenda Surat Masuk</p>
                                    </a>
                                    </li>
                                    <li class="nav-item">
                                    <a href="{{ route('agenda.surat.keluar') }}" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Agenda Surat Keluar</p>
                                    </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Manajemen User</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="nav-link btn btn-link">
                                        <i class="nav-icon fas fa-sign-out-alt"></i>
                                        <p>Logout</p>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>
            <!-- /.sidebar -->
        @endif

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <h1>@yield('title')</h1>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->

        @if (!request()->is('login')) 
            <!-- Footer -->
            <footer class="main-footer text-center">
                <strong>Copyright &copy; 2025 Arsip Surat.</strong> All rights reserved.
            </footer>
        @endif

    </div>
    <!-- ./wrapper -->

    <!-- AdminLTE Scripts -->
    
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    
    
    <script>
        $(document).ready(function () {
            $('#toggle-sidebar').on('click', function (e) {
                e.preventDefault();
                $('body').toggleClass('sidebar-collapse');
            });
        });
    </script>
    
    @stack('scripts')
    @yield('scripts')
    
</body>
</html>
