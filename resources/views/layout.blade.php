<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NSBM')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Add Nunito font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /*
        * Color Scheme (60-30-10 Rule)
        *
        * Light Mode:
        * 60% - Primary: Light Gray (#F8F9FA) or Soft White (#FFFFFF)
        * 30% - Secondary: Muted Pink (#E57373) or Soft Coral (#F28E8E)
        * 10% - Highlight: Deep Burgundy (#8B0000)
        *
        * Dark Mode:
        * 60% - Primary: Charcoal Gray (#121212)
        * 30% - Secondary: Rose Gold (#D48B91) or Mauve (#C97B84)
        * 10% - Highlight: Bright Pink (#FF4081)
        */
        :root {
            /* Light Mode (Default) */
            --color-primary: #F8F9FA;
            --color-primary-light: #FFFFFF;
            --color-primary-dark: #E9ECEF;
            --color-secondary: #f78fa7;
            --color-secondary-light: #de8096;
            --color-secondary-dark:#c57285;
            --color-accent: #8B0000;
            --color-accent-light: #A52A2A;
            --color-accent-dark: #690000;

            /* System Colors */
            --color-success: #00796B;
            --color-success-light: #B2DFDB;
            --color-warning: #FF9800;
            --color-danger: #D32F2F;

            /* Text Colors */
            --color-text-primary: #333333;
            --color-text-secondary: #616161;
            --color-text-light: #9E9E9E;
            --color-text-on-secondary: #FFFFFF;
            --color-text-on-accent: #FFFFFF;

            /* Other UI Elements */
            --color-border: #E0E0E0;
            --color-input-bg: #F5F5F5;
            --color-card-bg: #FFFFFF;
            --color-sidebar: #F78FA7;
            --color-sidebar-hover: #E57373;
            --color-sidebar-active: #C95555;
            --color-sidebar-text: #FFFFFF;
            --color-table-header: #F78FA7;
            --color-table-stripe: #FFF5F5;

            /* Shadows */
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.05);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.05);
        }

        /* Dark Mode */
        [data-theme="dark"] {
            --color-primary: #121212;
            --color-primary-light: #1E1E1E;
            --color-primary-dark: #000000;
            --color-secondary: #D48B91;
            --color-secondary-light: #C97B84;
            --color-secondary-dark: #A35D64;
            --color-accent: #FF4081;
            --color-accent-light: #FF80AB;
            --color-accent-dark: #C51162;

            /* System Colors */
            --color-success: #4CAF50;
            --color-success-light: #81C784;
            --color-warning: #FFB74D;
            --color-danger: #F44336;

            /* Text Colors */
            --color-text-primary: #E0E0E0;
            --color-text-secondary: #B0B0B0;
            --color-text-light: #757575;
            --color-text-on-secondary: #FFFFFF;
            --color-text-on-accent: #FFFFFF;

            /* Other UI Elements */
            --color-border: #333333;
            --color-input-bg: #2D2D2D;
            --color-card-bg: #1E1E1E;
            --color-sidebar: #1E1E1E;
            --color-sidebar-hover: #D48B91;
            --color-sidebar-active: #C97B84;
            --color-sidebar-text: #E0E0E0;
            --color-table-header: #2D2D2D;
            --color-table-stripe: #1A1A1A;

            /* Shadows */
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.2);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.4);
        }

        body {
            display: flex;
            min-height: 100vh;
            font-family: 'Nunito', sans-serif;
            line-height: 1.5;
            color: var(--color-text-primary);
            background-color: var(--color-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar {
            width: 280px;
            background-color: var(--color-sidebar);
            color: var(--color-sidebar-text);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: all 0.3s ease;
            z-index: 30;
            box-shadow: var(--shadow-md);
        }

        .sidebar.collapsed {
            width: 80px;
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
            margin-left: 280px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .content-wrapper.expanded {
            margin-left: 80px;
        }

        .content-wrapper.full-width {
            margin-left: 0;
        }

        .main-content {
            flex: 1;
            background-color: var(--color-primary);
            padding: 1.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.9rem 1.5rem;
            color: var(--color-sidebar-text);
            transition: all 0.2s;
            margin: 0.35rem 0.6rem;
            border-radius: 0.5rem;
        }

        .sidebar-link:hover {
            background-color: var(--color-sidebar-hover);
            color: var(--color-text-on-secondary);
        }

        .sidebar-link.active {
            background-color: var(--color-sidebar-active);
            color: var(--color-text-on-secondary);
            font-weight: 600;
            border-left: 3px solid var(--color-accent-light);
        }

        .sidebar-link i {
            margin-right: 14px;
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar.collapsed .sidebar-link {
            padding: 0.9rem 0;
            justify-content: center;
            margin: 0.35rem 0.4rem;
        }

        .sidebar.collapsed .sidebar-link i {
            margin-right: 0;
            font-size: 1.2rem;
        }

        .footer {
            color: var(--color-text-secondary);
            font-size: 0.85rem;
            padding: 1rem;
            background-color: var(--color-card-bg);
            border-top: 1px solid var(--color-border);
        }

        .page-title {
            font-family: 'Nunito', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            text-align: center;
            color: var(--color-secondary);
            width: 100%;
            display: block;
            letter-spacing: 0.025em;
            position: absolute;
            left: 0;
            right: 0;
            margin: auto;
        }

        /* Add custom scrollbar for sidebar */
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: var(--color-sidebar-active);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Card styling */
        .dashboard-card {
            background-color: var(--color-card-bg);
            border-radius: 1rem;
            box-shadow: var(--shadow-md);
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid var(--color-border);
        }

        .dashboard-card:hover {
            box-shadow: var(--shadow-lg);
        }

        /* Table styling */
        .dashboard-table th {
            background-color: var(--color-table-header);
            color: var(--color-text-on-secondary);
            padding: 0.75rem 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.025em;
        }

        .dashboard-table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }

        .dashboard-table tr:nth-child(even) {
            background-color: var(--color-table-stripe);
        }

        .dashboard-table tr:hover {
            background-color: var(--color-primary-dark);
        }

        /* Button styling */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary {
            background-color: var(--color-accent);
            color: var(--color-text-on-accent);
        }

        .btn-primary:hover {
            background-color: var(--color-accent-dark);
        }

        .btn-secondary {
            background-color: var(--color-secondary);
            color: var(--color-text-on-secondary);
        }

        .btn-secondary:hover {
            background-color: var(--color-secondary-dark);
        }

        .btn-success {
            background-color: var(--color-success);
            color: white;
        }

        .btn-success:hover {
            background-color: var(--color-success-dark);
        }

        /* Theme toggle */
        .theme-toggle {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
            margin-right: 1rem;
            background-color: var(--color-primary-light);
            color: var(--color-text-primary);
            box-shadow: var(--shadow-sm);
        }

        .theme-toggle:hover {
            background-color: var(--color-primary-dark);
        }

        /* Alerts and notifications */
        .alert {
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left-width: 4px;
        }

        .alert-success {
            background-color: var(--color-success-light);
            border-left-color: var(--color-success);
            color: var(--color-success);
        }

        .alert-warning {
            background-color: #FFF3E0;
            border-left-color: var(--color-warning);
            color: #E65100;
        }

        .alert-danger {
            background-color: #FFEBEE;
            border-left-color: var(--color-danger);
            color: var(--color-danger);
        }

        /* Badge styling */
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
        }

        .badge-primary {
            background-color: var(--color-primary-dark);
            color: var(--color-text-primary);
        }

        .badge-secondary {
            background-color: var(--color-secondary-light);
            color: var(--color-text-on-secondary);
        }

        .badge-accent {
            background-color: var(--color-accent-light);
            color: var(--color-text-on-accent);
        }
    </style>
</head>
<body data-theme="light">
    @php
        $isSelectWardPage = request()->routeIs('ward.select');
    @endphp

    <!-- Sidebar - hidden on select ward page -->
    @if(!$isSelectWardPage)
    <div id="sidebar" class="sidebar">
        <div class="p-5 border-b border-opacity-30 flex justify-between items-center" style="border-color: var(--color-border);">
            <h1 class="text-xl font-bold text-center sidebar-text">NSBM</h1>
            <button id="sidebarToggle" class="text-opacity-80 hover:text-opacity-100 transition-opacity" style="color: var(--color-sidebar-text);">
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
            <a href="{{ route('emergency.bor.index') }}" class="sidebar-link {{ request()->routeIs('emergency.bor.*') ? 'active' : '' }}">
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
        <header class="px-6 py-5 flex justify-between items-center relative shadow-md" style="background-color: var(--color-card-bg); border-bottom: 1px solid var(--color-border);">
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
                <button id="themeToggle" class="theme-toggle" title="Toggle dark/light mode">
                    <i class="fas fa-moon dark-icon hidden"></i>
                    <i class="fas fa-sun light-icon"></i>
                </button>
                <a href="{{ route('logout') }}" class="flex items-center" style="color: var(--color-text-primary);">
                    <span class="text-sm font-medium mr-2">Welcome, {{ Auth::user()->username }}</span>
                    <span class="text-xs px-2 py-1 rounded transition" style="background-color: var(--color-primary-light); color: var(--color-text-primary);">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </span>
                </a>
            </div>
            @endauth
        </header>

        <!-- Page Content -->
        <main class="main-content p-6">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer - hidden on select ward page -->
        @if(!$isSelectWardPage)
        <footer class="footer p-5 text-center text-sm" style="background-color: var(--color-card-bg); border-color: var(--color-border);">
            &copy; {{ date('Y') }} Nursing Service Bed Management. All rights reserved.
        </footer>
        @endif
    </div>

    <!-- JavaScript for sidebar toggle and theme switching -->
    @if(!$isSelectWardPage)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle functionality
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

            // Theme toggle functionality
            const themeToggle = document.getElementById('themeToggle');
            const darkIcon = themeToggle.querySelector('.dark-icon');
            const lightIcon = themeToggle.querySelector('.light-icon');

            // Check for saved theme preference or prefer-color-scheme
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            // Set initial theme
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                document.body.setAttribute('data-theme', 'dark');
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }

            // Handle theme toggle click
            themeToggle.addEventListener('click', function() {
                const currentTheme = document.body.getAttribute('data-theme');
                if (currentTheme === 'light') {
                    document.body.setAttribute('data-theme', 'dark');
                    darkIcon.classList.remove('hidden');
                    lightIcon.classList.add('hidden');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.body.setAttribute('data-theme', 'light');
                    lightIcon.classList.remove('hidden');
                    darkIcon.classList.add('hidden');
                    localStorage.setItem('theme', 'light');
                }
            });
        });
    </script>
    @endif
</body>
</html>
