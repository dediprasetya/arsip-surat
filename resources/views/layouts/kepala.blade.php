<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kepala Bidang - @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
        }

        #sidebar {
            width: 240px;
            transition: all 0.3s;
        }

        #sidebar.collapsed {
            width: 0;
            overflow: hidden;
        }

        #content {
            flex-grow: 1;
        }

        .sidebar-link {
            padding: 10px 20px;
            display: block;
            color: #333;
            text-decoration: none;
        }

        .sidebar-link:hover, .sidebar-link.active {
            background-color: #e9ecef;
        }

        .badge-notif {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar" class="bg-light border-end">
    <div class="text-center py-4 border-bottom">
        <h5 class="mb-0">Kepala Bidang</h5>
        <small>{{ Auth::user()->name }}</small>
    </div>
    <ul class="list-unstyled">
        <li>
            <a href="{{ route('kepala.dashboard') }}" class="sidebar-link {{ request()->is('kepala-bidang') ? 'active' : '' }}">
                <i class="bi bi-house"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('kepala.dashboard') }}" class="sidebar-link {{ request()->is('kepala-bidang/surat-masuk*') ? 'active' : '' }}">
                <i class="bi bi-envelope-open"></i> Surat Masuk
                @php
                    $jumlahSuratBelumDisposisi = \App\Models\Surat::whereNull('disposisi_oleh')->count();
                @endphp
                @if($jumlahSuratBelumDisposisi > 0)
                    <span class="badge bg-danger text-white badge-notif float-end">{{ $jumlahSuratBelumDisposisi }}</span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('kepala.surat-keluar') }}" class="sidebar-link {{ request()->is('kepala-bidang/surat-keluar*') ? 'active' : '' }}">
                <i class="bi bi-send"></i> Persetujuan Surat Keluar
                @php
                    $jumlahSuratKeluarPending = \App\Models\SuratKeluar::where('status', 'menunggu')->count();
                @endphp
                @if($jumlahSuratKeluarPending > 0)
                    <span class="badge bg-warning text-dark badge-notif float-end">{{ $jumlahSuratKeluarPending }}</span>
                @endif
            </a>
        </li>
        <li>
            <a href="{{ route('logout') }}"
               class="sidebar-link text-danger"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> Keluar
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>

<!-- Content Area -->
<div id="content" class="d-flex flex-column w-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand navbar-light bg-white border-bottom px-3">
        <button class="btn btn-outline-secondary" id="sidebarToggle"><i class="bi bi-list"></i></button>
        <span class="navbar-brand mb-0 h6 ms-3">Sistem Arsip Surat</span>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="dropdownUser" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Keluar
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="p-4">
        @yield('content')
    </main>
</div>

<!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('collapsed');
    });
</script>

@stack('scripts')

</body>
</html>
