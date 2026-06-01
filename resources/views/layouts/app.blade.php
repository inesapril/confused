@props(['title' => 'Dashboard - Alur Confused', 'icon' => '<i data-lucide="layout-dashboard" class="me-3" style="width: 32px; height: 32px;"></i> Dashboard'])
<!DOCTYPE html>
<html lang="id">

@props([
    'title' => 'Dashboard - Alur Confused',
    'icon' => '<i data-lucide="layout-dashboard" class="me-2"></i> Dashboard'
])


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
    <!-- jQuery (must be loaded first) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- dropdown -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--color-background);
            color: var(--color-foreground);
            -webkit-font-smoothing: antialiased;
        }

        /* ===== SIDEBAR (Carbon Steel Panel) ===== */
        .sidebar {
            width: 240px;
            background: var(--sidebar-bg);
            border-right: 1px solid #1e293b;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            padding: 20px 14px;
            z-index: 1000;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logo-text {
            font-size: 18px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 10px 12px 24px;
            color: #ffffff;
            border-bottom: 1px solid #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 6px;
        }

        .logo-text::after {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            background: var(--color-warning); /* Industrial amber dot */
            border-radius: 50%;
        }

        .sidebar .nav-link,
        .sidebar-dropdown-btn {
            color: var(--sidebar-text);
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            width: 100%;
            border: none;
            background: transparent;
        }

        .sidebar .nav-link:hover,
        .sidebar-dropdown-btn:hover {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
        }

        .sidebar .active {
            background: var(--sidebar-active-bg) !important;
            color: var(--sidebar-active-text) !important;
            font-weight: 600;
            border-left: 3px solid var(--color-warning) !important;
            border-radius: 0 6px 6px 0 !important;
        }

        .sidebar ul ul {
            margin-left: 12px !important;
            border-left: 1px solid #1e293b;
            padding-left: 8px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 240px;
            min-height: 100vh;
            background: var(--color-background);
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Desktop Sidebar Toggle states */
        @media(min-width: 769px) {
            body.sidebar-collapsed .sidebar {
                transform: translateX(-240px);
            }
            body.sidebar-collapsed .main-content {
                margin-left: 0;
            }
        }

        /* ===== TOP HEADER (Flat Minimal Panel) ===== */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #ffffff;
            border-bottom: 1px solid var(--border-color-light);
            padding: 14px 28px;
        }

        .topbar h5 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--color-foreground);
        }

        /* ===== PAGE CONTENT ===== */
        main {
            padding: 28px;
        }

        /* ===== CARDS OVERRIDE ===== */
        .card {
            border: 1px solid var(--border-color-light) !important;
            border-radius: 8px !important;
            box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.04) !important;
            background: #ffffff !important;
        }

        .card-body {
            padding: 24px !important;
        }

        /* ===== COMPACT TABLES ===== */
        .table {
            font-size: 13px !important;
            border-color: var(--border-color-light) !important;
        }

        .table th {
            padding: 12px 14px !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            background: #f8fafc !important; /* Concrete gray header */
            color: var(--color-secondary) !important;
            border-bottom: 2px solid var(--border-color) !important;
            white-space: nowrap;
        }

        .table td {
            padding: 12px 14px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid var(--border-color-light) !important;
        }

        /* ZEBRA STRIPING */
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #ffffff !important;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: #f8fafc !important;
        }

        .table-hover tbody tr:hover {
            background: #f1f5f9 !important; /* Cool slate hover */
            transition: background-color 0.15s ease;
        }

        /* ===== BUTTONS GLOBAL OVERRIDES ===== */
        .btn {
            border-radius: 6px !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            padding: 8px 14px !important;
            letter-spacing: 0.03em !important;
            text-transform: uppercase !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Add Item / Success / Save */
        .btn-primary,
        .btn-success {
            background: var(--color-primary) !important;
            border-color: var(--color-primary) !important;
            color: #ffffff !important;
        }

        .btn-primary:hover,
        .btn-success:hover {
            background: #1e293b !important;
            border-color: #1e293b !important;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.12) !important;
            transform: translateY(-1px);
        }

        /* Edit / Adjust */
        .btn-warning {
            background: var(--color-warning) !important;
            border-color: var(--color-warning) !important;
            color: #ffffff !important;
        }

        .btn-warning:hover {
            background: #b45309 !important;
            border-color: #b45309 !important;
            box-shadow: 0 4px 10px rgba(217, 119, 6, 0.15) !important;
            transform: translateY(-1px);
        }

        /* Delete / Warning Alert */
        .btn-danger {
            background: var(--color-danger) !important;
            border-color: var(--color-danger) !important;
            color: #ffffff !important;
        }

        .btn-danger:hover {
            background: #9f1239 !important;
            border-color: #9f1239 !important;
            box-shadow: 0 4px 10px rgba(190, 18, 60, 0.15) !important;
            transform: translateY(-1px);
        }

        /* Details / Blueprint Skyline */
        .btn-info {
            background: var(--color-info) !important;
            border-color: var(--color-info) !important;
            color: #ffffff !important;
        }

        .btn-info:hover {
            background: #0369a1 !important;
            border-color: #0369a1 !important;
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.15) !important;
            transform: translateY(-1px);
        }

        /* DATATABLE SEARCH */
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px !important;
            border: 1px solid var(--border-color) !important;
            padding: 8px 12px !important;
            font-size: 13px !important;
            background: #ffffff !important;
        }

        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--color-foreground) !important;
            box-shadow: 0 0 0 2px rgba(15, 23, 42, 0.08) !important;
            outline: none !important;
        }

        /* ===== MENU TOGGLE BUTTON (Always Visible) ===== */
        .menu-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            color: var(--color-foreground);
            padding: 8px;
            margin-right: 15px;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .menu-toggle:hover {
            background-color: #f1f5f9;
            color: var(--color-primary);
        }

        .menu-toggle i {
            width: 20px;
            height: 20px;
            pointer-events: none;
        }

        /* MOBILE SIDEBAR RESPONSIVE LAYOUT */
        @media(max-width:768px) {
            .table {
                font-size: 12px !important;
            }

            .table th,
            .table td {
                padding: 10px 8px !important;
            }

            .btn {
                font-size: 10px !important;
                padding: 6px 10px !important;
            }

            .sidebar {
                transform: translateX(-240px); /* Hidden by default */
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 4px 0 20px rgba(15, 23, 42, 0.15);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .menu-toggle {
                display: flex;
            }

            .topbar {
                padding: 14px 16px;
            }

            main {
                padding: 16px;
            }
        }
    </style>
    
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column align-items-center">
            <div class="logo-text">
                Alur Confused
            </div>

            <ul class="nav flex-column w-100 px-3">
                @if(Auth::user()->hasRole('Owner'))
                    <li class="nav-item my-0.5">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <p class="d-flex"><i data-lucide="layout-dashboard" class="me-3"></i> Dashboard</p>
                        </a>
                    </li>
                @endif

                    @if(Auth::user()->hasRole('Owner') || Auth::user()->hasRole('Admin'))

                    @php
                        $masterOpen =
                            request()->routeIs('barang.*') ||
                            request()->routeIs('supplier.*') ||
                            request()->routeIs('reseller.*') ||
                            request()->routeIs('toko.*');
                    @endphp

                    <li class="nav-item my-1">

                        <!-- Tombol Master -->
                        <button
                            type="button"
                            onclick="toggleMasterMenu()"
                            class="nav-link sidebar-dropdown-btn w-100 border-0 text-start d-flex justify-content-between align-items-center {{ $masterOpen ? 'active' : '' }}"
                        >

                            <span class="d-flex align-items-center">
                                <i data-lucide="boxes" class="me-3"></i>
                                Master
                            </span>

                            <i
                                data-lucide="chevron-down"
                                id="masterArrow"
                                style="
                                    width:18px;
                                    height:18px;
                                    transition:0.3s;
                                    transform: rotate({{ $masterOpen ? '180deg' : '0deg' }});
                                "
                            ></i>

                        </button>

                        <!-- Submenu -->
                        <ul
                            id="masterMenu"
                            class="nav flex-column ms-4 mt-2"
                            style="display: {{ $masterOpen ? 'block' : 'none' }};"
                        >

                            <!-- Barang -->
                            <li class="nav-item my-1">
                                <a
                                    href="{{ route('barang.index') }}"
                                    class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}"
                                >
                                    <p class="d-flex align-items-center mb-0">
                                        <i data-lucide="box" class="me-3"></i>
                                        Barang
                                    </p>
                                </a>
                            </li>

                            <!-- Supplier -->
                            <li class="nav-item my-1">
                                <a
                                    href="{{ route('supplier.index') }}"
                                    class="nav-link {{ request()->routeIs('supplier.*') ? 'active' : '' }}"
                                >
                                    <p class="d-flex align-items-center mb-0">
                                        <i data-lucide="truck" class="me-3"></i>
                                        Supplier
                                    </p>
                                </a>
                            </li>

                            <!-- Reseller -->
                            <li class="nav-item my-1">
                                <a
                                    href="{{ route('reseller.index') }}"
                                    class="nav-link {{ request()->routeIs('reseller.*') ? 'active' : '' }}"
                                >
                                    <p class="d-flex align-items-center mb-0">
                                        <i data-lucide="users" class="me-3"></i>
                                        Reseller
                                    </p>
                                </a>
                            </li>

                            <!-- Tokoo -->
                            <li class="nav-item my-1">
                                <a
                                    href="{{ route('toko.index') }}"
                                    class="nav-link {{ request()->routeIs('toko.*') ? 'active' : '' }}"
                                >
                                    <p class="d-flex align-items-center mb-0">
                                        <i data-lucide="users" class="me-3"></i>
                                        Toko
                                    </p>
                                </a>
                            </li>

                        </ul>

                    </li>

                @endif

                    @if(Auth::user()->hasRole('Owner') || Auth::user()->hasRole('Admin'))
                        <li class="nav-item my-0.5">
                            <a href="{{ route('persediaan.index') }}" class="nav-link {{ request()->routeIs('persediaan.*') ? 'active' : '' }}">
                                <p class="d-flex"><i data-lucide="layers" class="me-3"></i> Persediaan</p>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasRole('Owner') || Auth::user()->hasRole('Admin
                    '))
                        <li class="nav-item my-0.5">
                            <a href="{{ route('penyesuaian_persediaan.index') }}" class="nav-link {{ request()->routeIs('penyesuaian_persediaan.*') ? 'active' : '' }}">
                                <p class="d-flex"><i data-lucide="file-pen" class="me-3"></i>Stok Opname</p>
                            </a>
                        </li>
                    @endif

                    @if(Auth::user()->hasRole('Owner') || Auth::user()->hasRole('Admin'))

                    @php
                        $barangMasukOpen =
                            request()->routeIs('barang_masuk.*') ||
                            request()->routeIs('return_pesanan.*');
                    @endphp

                    <li class="nav-item my-1">

                        <!-- Tombol Barang Masuk -->
                        <button
                            type="button"
                            onclick="toggleBarangMasukMenu()"
                            class="nav-link sidebar-dropdown-btn w-100 border-0 text-start d-flex justify-content-between align-items-center {{ $barangMasukOpen ? 'active' : '' }}"
                        >

                            <span class="d-flex align-items-center">
                                <i data-lucide="package-plus" class="me-3"></i>
                                Barang Masuk
                            </span>

                            <i
                                data-lucide="chevron-down"
                                id="barangMasukArrow"
                                style="
                                    width:18px;
                                    height:18px;
                                    transition:0.3s;
                                    transform: rotate({{ $barangMasukOpen ? '180deg' : '0deg' }});
                                "
                            ></i>

                        </button>

                        <!-- Submenu -->
                        <ul
                            id="barangMasukMenu"
                            class="nav flex-column ms-4 mt-2"
                            style="display: {{ $barangMasukOpen ? 'block' : 'none' }};"
                        >

                            <!-- Supplier -->
                            <li class="nav-item my-1">
                                <a
                                    href="{{ route('barang_masuk.index') }}"
                                    class="nav-link {{ request()->routeIs('barang_masuk.*') ? 'active' : '' }}"
                                >
                                    <p class="d-flex align-items-center mb-0">
                                        <i data-lucide="truck" class="me-3"></i>
                                        Supplier
                                    </p>
                                </a>
                            </li>

                            <!-- Return -->
                            <li class="nav-item my-1">
                                <a
                                    href="{{ route('return_pesanan.index') }}"
                                    class="nav-link {{ request()->routeIs('return_pesanan.*') ? 'active' : '' }}"
                                >
                                    <p class="d-flex align-items-center mb-0">
                                        <i data-lucide="rotate-ccw" class="me-3"></i>
                                        Retur
                                    </p>
                                </a>
                            </li>

                        </ul>

                    </li>
                    @endif

                    @if(Auth::user()->hasRole('Owner') || Auth::user()->hasRole('Admin'))

                    @php
                        $barangKeluarOpen =
                            request()->routeIs('barang_keluar.*') ||
                            request()->routeIs('pesanan.*');
                    @endphp

                    <li class="nav-item my-1">

                        <!-- Tombol Barang Keluar -->
                        <button
                            type="button"
                            onclick="toggleBarangKeluarMenu()"
                            class="nav-link sidebar-dropdown-btn w-100 border-0 text-start d-flex justify-content-between align-items-center {{ $barangKeluarOpen ? 'active' : '' }}"
                        >

                            <span class="d-flex align-items-center">
                                <i data-lucide="package-minus" class="me-3"></i>
                                Barang Keluar
                            </span>

                            <i
                                data-lucide="chevron-down"
                                id="barangKeluarArrow"
                                style="
                                    width:18px;
                                    height:18px;
                                    transition:0.3s;
                                    transform: rotate({{ $barangKeluarOpen ? '180deg' : '0deg' }});
                                "
                            ></i>

                        </button>

                        <!-- Submenu -->
                        <ul
                            id="barangKeluarMenu"
                            class="nav flex-column ms-4 mt-2"
                            style="display: {{ $barangKeluarOpen ? 'block' : 'none' }};"
                        >

                            <!-- Reseller -->
                            <li class="nav-item my-1">
                                <a
                                    href="{{ route('barang_keluar.index') }}"
                                    class="nav-link {{ request()->routeIs('barang_keluar.*') ? 'active' : '' }}"
                                >
                                    <p class="d-flex align-items-center mb-0">
                                        <i data-lucide="truck" class="me-3"></i>
                                        Reseller
                                    </p>
                                </a>
                            </li>

                            <!-- Pesanan -->
                            <li class="nav-item my-1">
                                <a
                                    href="{{ route('pesanan.index') }}"
                                    class="nav-link {{ request()->routeIs('pesanan.*') ? 'active' : '' }}"
                                >
                                    <p class="d-flex align-items-center mb-0">
                                        <i data-lucide="shopping-cart" class="me-3"></i>
                                        Pesanan
                                    </p>
                                </a>
                            </li>

                        </ul>

                    </li>
                    
                    <li class="nav-item my-0.5">
                        <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                            <p class="d-flex"><i data-lucide="file-chart-column" class="me-3"></i>Laporan</p>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->hasRole('Owner'))
                        <li class="nav-item my-0.5">
                            <a href="{{ route('cashflow.index') }}" class="nav-link {{ request()->routeIs('cashflow.*') ? 'active' : '' }}">
                                <p class="d-flex align-items-center"><i data-lucide="wallet" class="me-3"></i>Cash Flow</p>
                            </a>
                        </li>

                        <li class="nav-item my-0.5">
                            <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                                <p class="d-flex"><i data-lucide="users" class="me-3"></i>User</p>
                            </a>
                        </li>
                    @endif

            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content w-100">
            <!-- Navbar Top -->
            <div class="topbar d-flex justify-content-between align-items-center">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i data-lucide="menu"></i>
                </button>
                <h5 class="mb-0">
                    <p class="d-flex align-items-center" style="font-size: 1.25rem;">{!! $icon !!}</p>
                </h5>

                <div class="d-flex align-items-center gap-4">

                    <!-- Notification -->
                    <div class="dropdown">
                        <button
                            class="btn border-0 p-0"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            style="position: relative; background:none;"
                        >
                            <i data-lucide="bell"
                            style="width:28px; height:28px; cursor:pointer; pointer-events: none;">
                            </i>

                            <span
                                id="notifBadge"
                                class="badge bg-danger"
                                style="
                                    position:absolute;
                                    top:-4px;
                                    right:-4px;
                                    border-radius:50px;
                                    padding: 3px 6px;
                                    font-size:9px;
                                    display:none;
                                    pointer-events: none;
                                "
                            >
                                0
                            </span>
                        </button>

                        <ul
                            class="dropdown-menu dropdown-menu-end shadow border-0"
                            style="width:360px; max-height:480px; overflow-y:auto; border-radius: 12px; padding: 0;"
                            id="notifDropdown"
                        >
                            <li class="dropdown-header d-flex justify-content-between align-items-center p-3 border-bottom" style="background-color: #f8f9fa; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                <span class="h6 mb-0 text-dark" style="font-weight: 600;">Notifikasi</span>
                                <a href="javascript:void(0)" onclick="markAllRead(event)" class="text-primary text-decoration-none small font-weight-bold">
                                    Tandai semua dibaca
                                </a>
                            </li>

                            <div id="notifList" style="padding: 0; max-height: 380px; overflow-y: auto;"></div>

                            <li class="text-center p-3 border-top" style="background-color: #f8f9fa; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                                <a href="{{ route('persediaan.index') }}" class="text-primary text-decoration-none small font-weight-bold d-block">
                                    Lihat Semua Persediaan <i data-lucide="arrow-right" class="ms-1" style="width: 14px; height: 14px; display: inline-block; vertical-align: -2px;"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- User -->
                    <div class="dropdown">
                        <button
                            class="btn border-0 p-0"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            style="background:none;"
                        >
                            <i data-lucide="circle-user-round"
                            style="width:32px;height:32px;cursor:pointer; pointer-events: none;">
                            </i>
                        </button>

                        <ul
                            class="dropdown-menu dropdown-menu-end shadow border-0"
                            style="width:260px; border-radius: 12px; padding: 0;"
                        >
                            <!-- Header Info -->
                            <li class="p-3 border-bottom text-center" style="background-color: #f8f9fa; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                <div class="badge bg-primary bg-opacity-10 text-primary p-2 rounded-circle mb-2 d-inline-flex">
                                    <i data-lucide="user" style="width: 24px; height: 24px;"></i>
                                </div>
                                <h6 class="mb-0 text-dark" style="font-weight: 600;">{{ Auth::user()->name }}</h6>
                                <small class="text-muted d-block text-truncate" style="font-size: 11px; max-width: 100%;">{{ Auth::user()->email }}</small>
                                <div class="mt-2">
                                    <span class="badge bg-secondary" style="font-size: 10px;">
                                        {{ Auth::user()->roles->pluck('name')->first() ?? 'User' }}
                                    </span>
                                </div>
                            </li>

                            <!-- Menu Items -->
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2 px-3 text-dark small" href="{{ route('profile.edit') }}">
                                    <i data-lucide="settings" class="me-2 text-muted" style="width: 16px; height: 16px;"></i>
                                    Pengaturan Profil
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider my-0">
                            </li>

                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center py-2 px-3 text-danger small border-0 w-100 text-start bg-transparent" onclick="confirmLogout()">
                                    <i data-lucide="log-out" class="me-2 text-danger" style="width: 16px; height: 16px;"></i>
                                    Logout
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <main class="p-4">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>

    <!-- Initialize Lucide Icons -->
    <script>
        // Initialize Lucide icons when page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    @stack('scripts')

    <script>
        function toggleMasterMenu() {
            const menu = document.getElementById('masterMenu');
            const arrow = document.getElementById('masterArrow');

            if (menu.style.display === 'none') {
                menu.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                menu.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    </script>

    <script>
        function toggleBarangMasukMenu() {
            const menu = document.getElementById('barangMasukMenu');
            const arrow = document.getElementById('barangMasukArrow');

            if (menu.style.display === 'none') {
                menu.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                menu.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    </script>

    <script>
        function toggleBarangKeluarMenu() {
            const menu = document.getElementById('barangKeluarMenu');
            const arrow = document.getElementById('barangKeluarArrow');

            if (menu.style.display === 'none') {
                menu.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
            } else {
                menu.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            }
        }
    </script>

    <script>
        function loadNotifications() {
            $.get('/notifications', function(res){

                let html = '';

                if(res.notifications.length === 0){
                    html = `
                        <div class="p-4 text-center text-muted">
                            <i data-lucide="check-circle" class="text-success mb-2" style="width: 24px; height: 24px;"></i>
                            <p class="mb-0 small">Semua stok aman! Tidak ada notifikasi baru.</p>
                        </div>
                    `;
                } else {

                    res.notifications.forEach(function(notif){
                        const isReadClass = notif.is_read ? 'text-muted opacity-75' : 'bg-light font-weight-bold';
                        const dotIndicator = notif.is_read ? '' : '<span class="badge bg-primary rounded-pill ms-2" style="font-size: 8px; padding: 3px 6px;">Baru</span>';
                        const itemBorder = notif.is_read ? 'border-bottom: 1px solid #f1f3f5;' : 'border-bottom: 1px solid #f1f3f5; border-left: 3px solid #ffc107;';
                        const clickHandler = notif.is_read ? '' : `onclick="markRead(${notif.id}, event)"`;
                        const pointerStyle = notif.is_read ? '' : 'cursor: pointer;';

                        html += `
                            <li>
                                <div class="dropdown-item d-flex align-items-start p-3 ${isReadClass}" style="${itemBorder} ${pointerStyle}" ${clickHandler}>
                                    <div class="me-3 mt-1">
                                        <span class="badge bg-${notif.color} bg-opacity-10 text-${notif.color} p-2 rounded-circle d-inline-flex">
                                            <i data-lucide="${notif.icon}" style="width: 16px; height: 16px; pointer-events: none;"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong class="text-dark">${notif.title}</strong>
                                            ${dotIndicator}
                                        </div>
                                        <div class="text-muted small mt-1" style="white-space: normal; line-height: 1.4;">${notif.message}</div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="text-muted" style="font-size: 11px;">
                                                <i data-lucide="clock" class="me-1" style="width: 11px; height: 11px; display: inline-block; vertical-align: -2px; pointer-events: none;"></i>
                                                ${notif.time_ago}
                                            </span>
                                            ${notif.is_read ? '' : `
                                                <span class="text-primary small font-weight-bold" style="font-size: 11px;">
                                                    Tandai dibaca
                                                </span>
                                            `}
                                        </div>
                                    </div>
                                </div>
                            </li>
                        `;
                    });

                }

                $('#notifList').html(html);

                if(res.unread_count > 0){
                    $('#notifBadge')
                        .text(res.unread_count)
                        .show();
                } else {
                    $('#notifBadge').hide();
                }

                // Re-initialize dynamic Lucide icons
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        }

        function markRead(id, event){
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            $.post(`/notifications/${id}/read`, {
                _token: '{{ csrf_token() }}'
            }, function(){
                loadNotifications();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Notifikasi ditandai dibaca',
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        }

        function markAllRead(event){
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            $.post('/notifications/read-all', {
                _token: '{{ csrf_token() }}'
            }, function(){
                loadNotifications();
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Semua notifikasi ditandai dibaca',
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        }

        setInterval(loadNotifications, 5000);

        $(document).ready(function(){
            loadNotifications();

            // Robust click handling to prevent conflicts and ensure dropdown works
            $(document).on('click', '.dropdown button[data-bs-toggle="dropdown"]', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                e.stopPropagation();
                
                var $el = $(this);
                var $menu = $el.siblings('.dropdown-menu');
                
                if ($menu.hasClass('show')) {
                    $menu.removeClass('show');
                    $el.attr('aria-expanded', 'false');
                } else {
                    $('.dropdown-menu').removeClass('show');
                    $('.dropdown button[data-bs-toggle="dropdown"]').attr('aria-expanded', 'false');
                    $menu.addClass('show');
                    $el.attr('aria-expanded', 'true');
                }
            });

            // Close dropdowns on clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                    $('.dropdown button[data-bs-toggle="dropdown"]').attr('aria-expanded', 'false');
                }
            });
        });
        </script>

        <script>
            function toggleSidebar() {
                if (window.innerWidth <= 768) {
                    document.querySelector('.sidebar').classList.toggle('show');
                } else {
                    document.body.classList.toggle('sidebar-collapsed');
                }
            }

            // Auto-close mobile sidebar when clicking outside
            $(document).on('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!$(e.target).closest('.sidebar').length && !$(e.target).closest('.menu-toggle').length) {
                        $('.sidebar').removeClass('show');
                    }
                }
            });
        </script>
        <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display:none;">
            @csrf
        </form>
    </body>
</html>