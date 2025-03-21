<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NSBM')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Add Noto Sans Japanese font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 260px;
            background-color: #000000;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 30;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar-text {
            transition: opacity 0.3s ease;
            white-space: nowrap;
            overflow: hidden;
        }
        .sidebar.collapsed .sidebar-text {
            opacity: 0;
            width: 0;
        }
        .content-wrapper {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        .content-wrapper.expanded {
            margin-left: 70px;
        }
        .content-wrapper.full-width {
            margin-left: 0;
        }
        .main-content {
            flex: 1;
            background-color: #f8f9fa;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: #e5e7eb;
            transition: background-color 0.2s;
        }
        .sidebar-link:hover {
            background-color: #1f1f1f;
        }
        .sidebar-link.active {
            background-color: #1f1f1f;
            border-left: 4px solid #ffb6c1;
        }
        .sidebar-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }
        .sidebar.collapsed .sidebar-link {
            padding: 0.75rem 0;
            justify-content: center;
        }
        .sidebar.collapsed .sidebar-link i {
            margin-right: 0;
        }
        .footer {
            color: black;
        }
        .page-title {
            font-family: 'Noto Sans JP', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            text-align: center;
            color: #080808;
            width: 100%;
            display: block;
            text-transform: uppercase;
            position: absolute;
            left: 0;
            right: 0;
            margin: auto;
        }
        /* Add custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 2px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: #111111;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #333333;
            border-radius: 10px;
        }
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #555555;
        }
    </style>
</head>
<body>
    @php
        $isSelectWardPage = request()->routeIs('ward.select');
    @endphp

    <!-- Sidebar - hidden on select ward page -->
    @if(!$isSelectWardPage)
    <div id="sidebar" class="sidebar">
        <div class="p-4 border-b border-gray-700 flex justify-between items-center">
            <h1 class="text-xl font-bold text-center sidebar-text">NSBM</h1>
            <button id="sidebarToggle" class="text-gray-400 hover:text-white">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>

        <nav class="py-4 overflow-y-auto" style="max-height: calc(100vh - 80px);">
            <a href="{{ request()->routeIs('emergency.dashboard') && session('selected_ward_name') === 'Emergency Department' ? route('emergency.dashboard') : route('dashboard') }}"
               class="sidebar-link {{ request()->routeIs('dashboard') || request()->routeIs('emergency.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> <span class="sidebar-text">Dashboard</span>
            </a>

            @php
                $isEmergencyDepartment = session('selected_ward_name') === 'Emergency Department';
                $hasMaternityAccess = Auth::user()->wards()->where('name', 'like', '%MATERNITY%')
                    ->orWhere('name', 'like', '%LABOUR%')
                    ->orWhere('name', 'like', '%DELIVERY%')
                    ->orWhere('name', 'like', '%OB-GYN%')
                    ->exists();
            @endphp

            @if(!$isEmergencyDepartment)
            <a href="{{ route('ward.entry.create') }}" class="sidebar-link {{ request()->routeIs('ward.entry.create') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list"></i> <span class="sidebar-text">Ward Entry</span>
            </a>
            <a href="{{ route('census.create') }}" class="sidebar-link {{ request()->routeIs('census.create') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> <span class="sidebar-text">24 Hours Census</span>
            </a>
            @endif

            @if($hasMaternityAccess)
            <a href="{{ route('delivery.index') }}" class="sidebar-link {{ request()->routeIs('delivery.*') ? 'active' : '' }}">
                <i class="fas fa-baby-carriage"></i> <span class="sidebar-text">Delivery</span>
            </a>
            @endif

            @if($isEmergencyDepartment)
            <a href="{{ route('infectious-diseases.index') }}" class="sidebar-link {{ request()->routeIs('infectious-diseases.*') ? 'active' : '' }}">
                <i class="fas fa-head-side-mask"></i> <span class="sidebar-text">Infectious Disease</span>
            </a>
            <a href="#" class="sidebar-link {{ request()->routeIs('emergency.bor') ? 'active' : '' }}">
                <i class="fas fa-hospital-user"></i> <span class="sidebar-text">Emergency Room BOR</span>
            </a>
            @else
            <a href="#" class="sidebar-link">
                <i class="fas fa-head-side-mask"></i> <span class="sidebar-text">Infectious Disease</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-hospital-user"></i> <span class="sidebar-text">Emergency Room BOR</span>
            </a>
            @endif
            <a href="#" class="sidebar-link">
                <i class="fas fa-bed"></i> <span class="sidebar-text">Bed Availability Status</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-chart-line"></i> <span class="sidebar-text">Daily Data</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-user-md"></i> <span class="sidebar-text">Daily Profit Center Census</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-user-nurse"></i> <span class="sidebar-text">Data Of Staff Admission</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-user-clock"></i> <span class="sidebar-text">Staff On EL/UPL</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-chart-bar"></i> <span class="sidebar-text">Reports</span>
            </a>
            <a href="{{ route('support.access-control') }}" class="sidebar-link {{ request()->routeIs('support.access-control') ? 'active' : '' }}">
                <i class="fas fa-shield-alt"></i> <span class="sidebar-text">Access Guide</span>
            </a>
            <a href="#" class="sidebar-link">
                <i class="fas fa-cog"></i> <span class="sidebar-text">Settings</span>
            </a>
        </nav>
    </div>
    @endif

    <!-- Content -->
    <div id="content" class="content-wrapper {{ $isSelectWardPage ? 'full-width' : '' }}">
        <!-- Header -->
        <header class="bg-white shadow-sm px-6 py-4 flex justify-between items-center relative">
            <div class="flex items-center z-10">
                @if(session('selected_ward_name') && !$isSelectWardPage)
                @endif
            </div>

            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <h2 class="page-title pointer-events-auto">
                    @if(session('selected_ward_name') && !$isSelectWardPage)
                    {{ session('selected_ward_name') }}
                    @else
                    @yield('header', 'Dashboard')
                    @endif
                </h2>
            </div>

            @auth
            <div class="flex items-center z-10">
                <a href="{{ route('logout') }}" class="text-gray-600 hover:text-gray-900 flex items-center">
                    <span class="sidebar-text">Welcome, {{ Auth::user()->username }}</span>
                    {{-- <span class="text-sm">Logout</span> --}}
                    <i class="fas fa-sign-out-alt ml-1"></i>
                </a>
            </div>
            @endauth
        </header>

        <!-- Page Content -->
        <main class="main-content p-6">
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer - hidden on select ward page -->
        @if(!$isSelectWardPage)
        <footer class="footer p-4 text-center text-xs">
            &copy; {{ date('Y') }} Nursing Service Bed Management. All rights reserved.
        </footer>
        @endif
    </div>

    <!-- JavaScript for sidebar toggle -->
    @if(!$isSelectWardPage)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const chevronIcon = sidebarToggle.querySelector('i');

            // Check if there's a saved state in localStorage
            const sidebarState = localStorage.getItem('sidebarState');
            if (sidebarState === 'collapsed') {
                sidebar.classList.add('collapsed');
                content.classList.add('expanded');
                chevronIcon.classList.remove('fa-chevron-right');
                chevronIcon.classList.add('fa-chevron-left');
            }

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');

                // Toggle chevron icon direction
                chevronIcon.classList.toggle('fa-chevron-right');
                chevronIcon.classList.toggle('fa-chevron-left');

                // Save state to localStorage
                if (sidebar.classList.contains('collapsed')) {
                    localStorage.setItem('sidebarState', 'collapsed');
                } else {
                    localStorage.setItem('sidebarState', 'expanded');
                }
            });
        });
    </script>
    @endif
</body>
</html>
