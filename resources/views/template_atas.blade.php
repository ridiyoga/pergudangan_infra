<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</head>

<body class="bg-body-tertiary">
    <nav class="navbar navbar-light sticky-top bg-white border-bottom">
        <div class="container-fluid">
            <button class="navbar-toggler" id="sidebarToggle" type="button" aria-controls="sidebar"
                aria-expanded="true" aria-label="Tutup navigasi">
                <span class="navbar-toggler-icon"></span>
            </button>

            <button class="btn btn-link text-secondary ms-auto p-2" type="button" aria-label="Menu pengguna"
                title="Menu pengguna">
                <i class="bi bi-person-circle fs-4" aria-hidden="true"></i>
            </button>
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
                <nav class="nav flex-column gap-1 mb-2" aria-label="Menu utama">
                    <a @class([
                        'nav-link',
                        'sidebar-link',
                        'active' => request()->is('menu'),
                    ]) href="{{ url('menu') }}"
                        @if (request()->is('menu')) aria-current="page" @endif>
                        <span class="sidebar-link-icon" aria-hidden="true">
                            <i class="bi bi-house-door-fill" aria-hidden="true"></i>
                        </span>
                        <span>Master Menu</span>
                        <i class="bi bi-chevron-right sidebar-link-arrow" aria-hidden="true"></i>
                    </a>
                </nav>
                {{-- <nav class="nav flex-column gap-1 mb-2" aria-label="Menu utama">
                    <button class="nav-link sidebar-link sidebar-menu-toggle" type="button" aria-expanded="false"
                        aria-controls="menuSubmenu">
                        <span class="sidebar-link-icon" aria-hidden="true">
                            <i class="bi bi-folder2-open" aria-hidden="true"></i>
                        </span>
                        <span>Menu</span>
                        <i class="bi bi-chevron-right sidebar-link-arrow" aria-hidden="true"></i>
                    </button>
                    <div class="sidebar-submenu" id="menuSubmenu" style="display: none">
                        <a class="nav-link sidebar-link sidebar-submenu-link" href="#">
                            <i class="bi bi-dot" aria-hidden="true"></i>
                            <span>Submenu</span>
                        </a>
                    </div>
                </nav> --}}
            </div>
        </aside>
