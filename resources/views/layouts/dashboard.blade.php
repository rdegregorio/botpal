<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/customcss.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #1a1a1a;
            --sidebar-text: #a3a3a3;
            --sidebar-text-hover: #ffffff;
            --sidebar-active-bg: rgba(255, 255, 255, 0.1);
        }

        body {
            background: var(--bg-cream);
            min-height: 100vh;
        }

        /* Dashboard Layout */
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .sidebar-logo-icon {
            width: 32px;
            height: 32px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--sidebar-bg);
            font-size: 14px;
        }

        .sidebar-logo-text {
            font-weight: 600;
            font-size: 18px;
            color: white;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .sidebar-section {
            margin-bottom: 24px;
        }

        .sidebar-section-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            padding: 0 12px;
            margin-bottom: 8px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 2px;
        }

        .sidebar-link:hover {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-text-hover);
        }

        .sidebar-link.active {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-text-hover);
        }

        .sidebar-link i {
            font-size: 18px;
            width: 20px;
            text-align: center;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .sidebar-user:hover {
            background: var(--sidebar-active-bg);
        }

        .sidebar-user-avatar {
            width: 36px;
            height: 36px;
            background: #3b82f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .sidebar-user-info {
            flex: 1;
            min-width: 0;
        }

        .sidebar-user-name {
            font-size: 14px;
            font-weight: 500;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-user-email {
            font-size: 12px;
            color: var(--sidebar-text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-header {
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .main-header-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }

        .main-body {
            flex: 1;
            padding: 32px;
        }

        /* Mobile Toggle */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 1001;
            width: 40px;
            height: 40px;
            background: var(--sidebar-bg);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar-overlay.show {
                display: block;
            }

            .main-content {
                margin-left: 0;
            }

            .main-header {
                padding-left: 72px;
            }

            .main-body {
                padding: 24px 16px;
            }
        }

        /* Card Styles for Dashboard */
        .dashboard-card {
            background: var(--bg-primary);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .dashboard-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .dashboard-card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
        }
    </style>
    @stack('head')
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="/" class="sidebar-logo">
                    <span class="sidebar-logo-icon">
                        <i class="bi bi-chat-dots-fill"></i>
                    </span>
                    <span class="sidebar-logo-text">BotPal</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <div class="sidebar-section">
                    <div class="sidebar-section-title">Menu</div>
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-robot"></i>
                        ChatBot Setup
                    </a>
                    <a href="{{ route('settings') }}" class="sidebar-link {{ request()->routeIs('settings') ? 'active' : '' }}">
                        <i class="bi bi-sliders"></i>
                        Settings
                    </a>
                    <a href="{{ route('knowledge') }}" class="sidebar-link {{ request()->routeIs('knowledge') ? 'active' : '' }}">
                        <i class="bi bi-book"></i>
                        Knowledge Base
                    </a>
                    <a href="{{ route('messages') }}" class="sidebar-link {{ request()->routeIs('messages') ? 'active' : '' }}">
                        <i class="bi bi-chat-left-text"></i>
                        Messages
                    </a>
                </div>

                <div class="sidebar-section">
                    <div class="sidebar-section-title">Account</div>
                    <a href="{{ route('account.index') }}" class="sidebar-link {{ request()->routeIs('account.*') ? 'active' : '' }}">
                        <i class="bi bi-person"></i>
                        My Account
                    </a>
                    <a href="{{ route('pricing') }}" class="sidebar-link">
                        <i class="bi bi-credit-card"></i>
                        Billing & Plans
                    </a>
                </div>

                <div class="sidebar-section">
                    <div class="sidebar-section-title">Resources</div>
                    <a href="{{ route('preview') }}" class="sidebar-link">
                        <i class="bi bi-eye"></i>
                        Preview ChatBot
                    </a>
                    <a href="{{ route('pages.contact') }}" class="sidebar-link">
                        <i class="bi bi-envelope"></i>
                        Contact Support
                    </a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user" data-bs-toggle="dropdown">
                    <div class="sidebar-user-avatar">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="sidebar-user-info">
                        <div class="sidebar-user-name">{{ Auth::user()->name ?? 'User' }}</div>
                        <div class="sidebar-user-email">{{ Auth::user()->email ?? '' }}</div>
                    </div>
                    <i class="bi bi-chevron-down" style="color: var(--sidebar-text); font-size: 12px;"></i>
                </div>
                <ul class="dropdown-menu dropdown-menu-dark" style="width: calc(var(--sidebar-width) - 24px);">
                    <li><a class="dropdown-item" href="{{ route('account.index') }}"><i class="bi bi-person me-2"></i>My Account</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Sign Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <h1 class="main-header-title">@yield('page-title', 'Dashboard')</h1>
                @yield('header-actions')
            </header>

            <div class="main-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
            document.querySelector('.sidebar-overlay').classList.toggle('show');
        }

        // Close sidebar when clicking a link on mobile
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    toggleSidebar();
                }
            });
        });
    </script>

    @stack('bottom')
</body>
</html>
