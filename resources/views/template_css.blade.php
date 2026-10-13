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

    body {
        font-family: 'Open Sans', sans-serif;
    }

    button,
    input,
    select,
    textarea {
        font-family: inherit;
    }

    #tabel_menu {
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.9rem;
    }

    #tabel_menu thead th {
        padding: 0.9rem 1rem;
        border-bottom: 0;
        background-color: var(--bs-light);
        color: var(--bs-body-color);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        white-space: nowrap;
    }

    #tabel_menu tbody td {
        padding: 0.85rem 1rem;
        background-color: var(--bs-white);
        border-color: var(--bs-border-color-translucent);
        color: var(--bs-secondary-color);
        font-size: 0.825rem;
        vertical-align: middle;
        transition: background-color 0.15s ease;
    }

    #tabel_menu tbody td:first-child,
    #tabel_menu thead th:first-child,
    #tabel_menu tbody td:last-child,
    #tabel_menu thead th:last-child {
        text-align: center;
    }
</style>
