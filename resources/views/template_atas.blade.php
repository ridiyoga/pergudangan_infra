<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .sidebar {
            flex: 0 0 260px;
            width: 260px;
            overflow-x: hidden;
            transition: width 0.2s ease, flex-basis 0.2s ease, transform 0.2s ease;
        }

        .sidebar.is-collapsed {
            flex-basis: 0;
            width: 0;
            border: 0 !important;
        }

        .sidebar-section-label {
            color: var(--bs-secondary-color);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            min-height: 48px;
            padding: 0.55rem 0.75rem;
            border-radius: 0.75rem;
            color: var(--bs-secondary-color);
            font-weight: 500;
            transition: color 0.15s ease, background-color 0.15s ease, transform 0.15s ease;
        }

        .sidebar-link:hover {
            color: var(--bs-primary);
            background-color: var(--bs-primary-bg-subtle);
            transform: translateX(2px);
        }

        .sidebar-link.active {
            color: var(--bs-primary-text-emphasis);
            background: linear-gradient(110deg, var(--bs-primary-bg-subtle), rgba(var(--bs-primary-rgb), 0.06));
            box-shadow: inset 3px 0 0 var(--bs-primary);
        }

        .sidebar-link-icon {
            display: grid;
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            place-items: center;
            border-radius: 0.6rem;
            color: var(--bs-secondary-color);
            background-color: var(--bs-tertiary-bg);
        }

        .sidebar-link.active .sidebar-link-icon {
            color: var(--bs-primary);
            background-color: rgba(var(--bs-primary-rgb), 0.12);
        }

        .sidebar-link-arrow {
            margin-left: auto;
            color: var(--bs-primary);
            opacity: 0;
            transition: opacity 0.15s ease;
        }

        .sidebar-link:hover .sidebar-link-arrow,
        .sidebar-link.active .sidebar-link-arrow {
            opacity: 1;
        }

        .sidebar-menu-toggle {
            width: 100%;
            border: 0;
            background: transparent;
            text-align: left;
        }

        .sidebar-menu-toggle .sidebar-link-arrow {
            opacity: 1;
            transition: transform 0.2s ease;
        }

        .sidebar-menu-toggle[aria-expanded="true"] .sidebar-link-arrow {
            transform: rotate(90deg);
        }

        .sidebar-submenu {
            margin: 0.25rem 0 0.5rem 1.8rem;
            padding-left: 0.75rem;
            border-left: 1px solid var(--bs-border-color);
        }

        .sidebar-submenu-link {
            min-height: 40px;
            font-size: 0.9rem;
        }

        @media (min-width: 992px) {
            .sidebar {
                min-height: calc(100vh - 56px);
            }
        }

        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                z-index: 1045;
                top: 56px;
                bottom: 0;
                left: 0;
                transform: translateX(-100%);
            }

            .sidebar.is-open {
                transform: translateX(0);
            }

            .sidebar-backdrop {
                position: fixed;
                z-index: 1040;
                inset: 56px 0 0;
                background-color: rgba(0, 0, 0, 0.35);
            }
        }
    </style>
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
                    <a class="nav-link sidebar-link active" href="#" aria-current="page">
                        <span class="sidebar-link-icon" aria-hidden="true">
                            <i class="bi bi-house-door-fill" aria-hidden="true"></i>
                        </span>
                        <span>Dashboard</span>
                        <i class="bi bi-chevron-right sidebar-link-arrow" aria-hidden="true"></i>
                    </a>
                </nav>
                <nav class="nav flex-column gap-1 mb-2" aria-label="Menu utama">
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
                </nav>
            </div>
        </aside>
