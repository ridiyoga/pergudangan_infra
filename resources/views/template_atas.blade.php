<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

</head>

<body class="bg-body-tertiary">
    <nav class="navbar navbar-light sticky-top bg-white border-bottom">
        <div class="container-fluid">
            <button class="navbar-toggler" id="sidebarToggle" type="button" aria-controls="sidebar"
                aria-expanded="true" aria-label="Tutup navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>

            <span class="ms-auto me-3">{{ session('username') }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i>Keluar
                </button>
            </form>
        </div>
    </nav>

    <div class="d-flex">
        <aside class="sidebar bg-white border-end" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
            <div class="d-flex align-items-center justify-content-between border-bottom p-3 d-lg-none">
                <h5 class="offcanvas-title" id="sidebarLabel">Navigasi</h5>
                <button type="button" class="btn-close" id="sidebarClose" aria-label="Tutup navigasi"></button>
            </div>
            <div class="p-3">
                <div class="sidebar-section-label text-uppercase mb-2 px-2">Menu utama</div>
                <nav class="nav flex-column gap-1 mb-2" aria-label="Menu utama">
                    <a @class([
                        'nav-link',
                        'sidebar-link',
                        'active' => request()->is('home'),
                    ]) href="{{ url('home') }}"
                        @if (request()->is('home')) aria-current="page" @endif>
                        <span class="sidebar-link-icon" aria-hidden="true">
                            <i class="bi bi-house-door-fill" aria-hidden="true"></i>
                        </span>
                        <span>Dashboard</span>
                        <i class="bi bi-chevron-right sidebar-link-arrow" aria-hidden="true"></i>
                    </a>
                </nav>
                {{-- @foreach ($menu as $mn)
                    <nav class="nav flex-column gap-1 mb-2" aria-label="Menu utama">
                        <a @class([
                            'nav-link',
                            'sidebar-link',
                            'active' => request()->is($mn->route) || request()->is($mn->route . '/*'),
                        ]) href="{{ route($mn->route, ['menu_id' => $mn->id]) }}"
                            @if (request()->is($mn->route) || request()->is($mn->route . '/*')) aria-current="page" @endif>
                            <span class="sidebar-link-icon" aria-hidden="true">
                                <i class="{{ $mn->icon }}" aria-hidden="true"></i>
                            </span>
                            <span>{{ $mn->nama_menu }}</span>
                            <i class="bi bi-chevron-right sidebar-link-arrow" aria-hidden="true"></i>
                        </a>
                    </nav>
                @endforeach --}}
                @foreach ($parent as $pr)
                    <nav class="nav flex-column gap-1 mb-2" aria-label="Menu utama">
                        <button class="nav-link sidebar-link sidebar-menu-toggle" type="button" aria-expanded="false"
                            aria-controls="menuSubmenu-{{ $loop->index }}">
                            <span class="sidebar-link-icon" aria-hidden="true">
                                <i class="{{ $pr->icon }}" aria-hidden="true"></i>
                            </span>
                            <span>{{ $pr->nama_menu }}</span>
                            <i class="bi bi-chevron-right sidebar-link-arrow" aria-hidden="true"></i>
                        </button>
                        <div class="sidebar-submenu" id="menuSubmenu-{{ $loop->index }}" style="display: none">
                            @foreach ($pr->Children as $mn)
                                <a @class([
                                    'nav-link',
                                    'sidebar-link',
                                    'active' => request()->is($mn->route) || request()->is($mn->route . '/*'),
                                ]) href="{{ route($mn->route, ['menu_id' => $mn->id]) }}">
                                    <i class="{{ $mn->icon }}" aria-hidden="true"></i>
                                    <span>{{ $mn->nama_menu }}</span>
                                </a>
                            @endforeach
                        </div>
                    </nav>
                @endforeach

            </div>
        </aside>
