<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Teacher Workspace — CodeXpress')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --white: #ffffff;
            --black: #09090b;
            --g50: #fafafa;
            --g100: #f4f4f5;
            --g200: #e4e4e7;
            --g300: #d4d4d8;
            --g400: #a1a1aa;
            --g500: #71717a;
            --g600: #52525b;
            --g700: #3f3f46;
            --g800: #27272a;
            --g900: #18181b;
            --radius: 16px;
            --radius-sm: 10px;
            --shadow-lg: 0 10px 30px rgba(0,0,0,.08);
            --tr: 0.25s cubic-bezier(.4,0,.2,1);
            --sidebar-w: 265px;
            --sidebar-collapsed-w: 76px;
            --topbar-h: 70px;
            --accent: #4f46e5;
            --accent-light: #6366f1;
            --accent-glow: rgba(79, 70, 229, 0.08);
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--g100);
            color: var(--black);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
            position: relative;
        }

        /* Subtle grid background pattern */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(79,70,229,0.01) 1px, transparent 1px),
                linear-gradient(90deg, rgba(79,70,229,0.01) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 0;
            pointer-events: none;
        }

        /* Ambient Glow */
        .ambient-glow {
            position: fixed;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(79,70,229,0.05) 0%, rgba(79,70,229,0) 70%);
            filter: blur(100px);
            top: -200px;
            right: -200px;
            z-index: 0;
            pointer-events: none;
        }

        /* ═══════════════════════════════════════
           SIDEBAR
           ═══════════════════════════════════════ */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--g900);
            border-right: 1px solid rgba(255,255,255,0.06);
            display: flex; flex-direction: column;
            z-index: 200;
            transition: transform .3s cubic-bezier(.4,0,.2,1), width .3s cubic-bezier(.4,0,.2,1);
            box-shadow: 5px 0 30px rgba(0,0,0,.15);
            overflow: hidden;
        }
        .sidebar-brand {
            display: flex; align-items: center; gap: .75rem;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            text-decoration: none; color: var(--white);
            min-height: var(--topbar-h);
            flex-shrink: 0;
        }
        .sidebar-brand .brand-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-icon svg { width: 20px; height: 20px; fill: var(--white); }
        .sidebar-brand .brand-text { font-size: .95rem; font-weight: 800; letter-spacing: -.02em; white-space: nowrap; }
        .sidebar-brand .brand-sub { font-size: .65rem; color: var(--accent-light); font-weight: 600; display: block; margin-top: .1rem; }

        .sidebar-nav { flex: 1; padding: 0.5rem 0; overflow-y: auto; overflow-x: hidden; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--g800); border-radius: 4px; }

        .nav-section { padding: 0 1rem; margin-bottom: 1rem; }
        .nav-section-title {
            font-size: .65rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .08em; color: var(--g500);
            padding: .5rem .5rem .4rem; white-space: nowrap;
        }

        .nav-item {
            display: flex; align-items: center; gap: .75rem;
            padding: .75rem 1rem; margin-bottom: 0.25rem;
            font-size: .85rem; font-weight: 500;
            color: var(--g400); text-decoration: none;
            border-radius: var(--radius-sm);
            transition: all var(--tr);
            white-space: nowrap;
            position: relative;
        }
        .nav-item:hover { color: var(--white); background: rgba(255, 255, 255, 0.05); }
        .nav-item.active { color: var(--white); background: rgba(79, 70, 229, 0.15); font-weight: 600; }
        .nav-item.active::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 60%; background: var(--accent); border-radius: 0 4px 4px 0;
        }
        .nav-item svg { width: 20px; height: 20px; flex-shrink: 0; opacity: .7; transition: opacity var(--tr); }
        .nav-item:hover svg { opacity: 1; }
        .nav-item.active svg { opacity: 1; }
        
        .sidebar-footer {
            padding: 1.25rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.06);
            font-size: .7rem; color: var(--g500); white-space: nowrap;
            flex-shrink: 0;
        }

        /* ═══════════════════════════════════════
           MAIN AREA
           ═══════════════════════════════════════ */
        .main-wrapper {
            flex: 1; margin-left: var(--sidebar-w);
            display: flex; flex-direction: column; min-height: 100vh;
            transition: margin-left .3s cubic-bezier(.4,0,.2,1);
            position: relative; z-index: 10;
        }

        /* Top Bar */
        .top-bar {
            position: sticky; top: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            height: var(--topbar-h);
            padding: 0 2.5rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--g200);
        }
        .top-bar-left { display: flex; align-items: center; gap: 1rem; }
        .top-bar-title { font-size: 1.1rem; font-weight: 800; letter-spacing: -.02em; color: var(--black); }
        
        /* Mobile menu toggle */
        .sidebar-toggle {
            display: none; background: none; border: 1px solid var(--g200); cursor: pointer;
            padding: .5rem; border-radius: var(--radius-sm); transition: all var(--tr);
            color: var(--black);
        }
        .sidebar-toggle:hover { background: rgba(0, 0, 0, 0.05); }
        .sidebar-toggle svg { width: 20px; height: 20px; }

        .top-bar-right { display: flex; align-items: center; gap: 1rem; }
        
        .campus-badge {
            display: flex; align-items: center; gap: 0.4rem;
            background: var(--accent-glow);
            border: 1px solid rgba(79, 70, 229, 0.15);
            padding: 0.4rem 0.85rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--accent);
        }
        .campus-badge svg { width: 14px; height: 14px; color: var(--accent); fill: none; }

        /* Content */
        .main-content { flex: 1; padding: 2.5rem; max-width: 1200px; width: 100%; margin: 0 auto; }

        /* Footer */
        .page-footer { text-align: center; padding: 1.75rem 1rem; font-size: .78rem; color: var(--g400); border-top: 1px solid var(--g200); background: var(--white); }

        /* Tables (Responsive) */
        .table-responsive{width:100%;margin-bottom:1rem;border-radius:var(--radius-sm);border:1px solid var(--g200);background:var(--white);}
        .table-responsive table{width:100%;min-width:800px;border-collapse:collapse;margin:0;}
        @media(max-width:768px){ 
            .table-responsive{border:none;background:transparent;}
            .table-responsive table{min-width:100%;}
            .table-responsive table thead{display:none;}
            .table-responsive table tbody tr{display:block;border:1px solid var(--g200);border-radius:var(--radius-sm);margin-bottom:1rem;background:var(--white);box-shadow:0 2px 4px rgba(0,0,0,0.02);padding:0.5rem 0;}
            .table-responsive table tbody td{display:flex;justify-content:space-between;align-items:center;padding:0.5rem 1rem;text-align:right;border-bottom:1px solid var(--g100);}
            .table-responsive table tbody td:last-child{border-bottom:none;}
            .table-responsive table tbody td::before{content:attr(data-label);font-weight:700;color:var(--g500);text-transform:uppercase;font-size:0.7rem;letter-spacing:0.04em;text-align:left;flex:1;padding-right:1rem;}
            .table-responsive table tbody td > .actions { justify-content: flex-end; width: 100%; }
        }

        /* Mobile overlay */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4);
            z-index: 199; opacity: 0; transition: opacity .3s ease;
        }
        .sidebar-overlay.active { display: block; opacity: 1; }

        /* ── Tablet ── */
        @media(max-width: 1024px) {
            .sidebar { width: var(--sidebar-collapsed-w); }
            .sidebar .brand-text, .sidebar .nav-section-title, .sidebar .nav-label, .sidebar .teacher-profile-widget, .sidebar .sidebar-footer span {
                display: none;
            }
            .sidebar .nav-item { justify-content: center; padding: .75rem; }
            .sidebar .sidebar-brand { justify-content: center; padding: 1.5rem .5rem; }
            .sidebar .sidebar-footer { text-align: center; padding: .75rem .5rem; }
            .main-wrapper { margin-left: var(--sidebar-collapsed-w); }
        }

        /* ── Mobile ── */
        @media(max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: var(--sidebar-w); }
            .sidebar.open { transform: translateX(0); }
            .sidebar .brand-text, .sidebar .nav-section-title, .sidebar .nav-label, .sidebar .teacher-profile-widget, .sidebar .sidebar-footer span {
                display: initial;
            }
            .sidebar .nav-item { justify-content: flex-start; padding: .75rem 1rem; }
            .sidebar .sidebar-brand { justify-content: flex-start; padding: 1.5rem; }
            .sidebar .sidebar-footer { text-align: left; padding: 1.25rem 1.5rem; }
            .main-wrapper { margin-left: 0; }
            .sidebar-toggle { display: flex; }
            .top-bar { padding: 0 1.5rem; }
            .main-content { padding: 2rem 1.5rem; }
        }

        /* Responsive Layout Cards */
        .card {
            background: var(--white);
            border: 1px solid var(--g200);
            border-radius: var(--radius);
            padding: 1.75rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: border-color var(--tr), transform var(--tr), box-shadow var(--tr);
        }
        .card:hover {
            border-color: var(--g300);
            box-shadow: var(--shadow-lg);
        }

        /* B&W Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.6rem 1.2rem; font-family: inherit; font-size: 0.85rem;
            font-weight: 600; border-radius: var(--radius-sm); cursor: pointer;
            transition: all var(--tr); text-decoration: none;
        }
        .btn-primary {
            background: var(--accent); color: var(--white);
            border: 2px solid var(--accent);
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
        }
        .btn-primary:hover {
            background: var(--accent-light); border-color: var(--accent-light); color: var(--white);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.35);
        }
        .btn-secondary {
            background: var(--white); color: var(--g800);
            border: 2px solid var(--g200);
        }
        .btn-secondary:hover {
            border-color: var(--black);
            background: var(--g50);
            color: var(--black);
        }

        /* Grayscale Inputs */
        .input-group {
            display: flex; flex-direction: column; gap: 0.4rem; margin-bottom: 1.25rem;
        }
        .label {
            font-size: 0.75rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.05em; color: var(--g500);
        }
        .input {
            width: 100%; padding: 0.65rem 0.9rem;
            background: var(--white);
            border: 1px solid var(--g200);
            border-radius: var(--radius-sm);
            color: var(--black); font-family: inherit; font-size: 0.9rem;
            outline: none; transition: all var(--tr);
        }
        .input:focus {
            background: var(--white);
            border-color: var(--accent-light);
            box-shadow: 0 0 0 3px var(--accent-glow);
        }

        /* Tables */
        .table-container {
            width: 100%; overflow-x: auto;
            border: 1px solid var(--g200);
            border-radius: var(--radius);
            background: var(--white);
        }
        .table {
            width: 100%; border-collapse: collapse; text-align: left;
            font-size: 0.85rem;
        }
        .table th {
            background: var(--g50);
            padding: 1rem 1.25rem; font-weight: 700;
            color: var(--g500); border-bottom: 1px solid var(--g200);
            text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;
        }
        .table td {
            padding: 1rem 1.25rem; border-bottom: 1px solid var(--g200);
            color: var(--g700); transition: background var(--tr);
        }
        .table tr:last-child td { border-bottom: none; }
        .table tr:hover td { background: var(--g50); }

        /* Badges */
        .badge {
            display: inline-flex; align-items: center; padding: 0.25rem 0.6rem;
            border-radius: 20px; font-size: 0.7rem; font-weight: 700;
            letter-spacing: 0.02em; text-transform: uppercase;
        }
        .badge-white { background: var(--accent-glow); color: var(--accent); border: 1px solid rgba(79, 70, 229, 0.15); }
        .badge-outline { border: 1px solid var(--g200); color: var(--g500); }

        /* Page headings */
        .page-header-block {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.75rem; gap: 1rem; flex-wrap: wrap;
        }
        .page-header-title {
            font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em; color: var(--black);
        }
        .page-header-subtitle {
            font-size: 0.85rem; color: var(--g500); margin-top: 0.25rem;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="ambient-glow"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('teacher.dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24"><path d="M20 17a2 2 0 002-2V4a2 2 0 00-2-2H9.46c.35.61.54 1.3.54 2h10v11h-9v2m4-10H9.46c.35.61.54 1.3.54 2H15v-2M6 7a3 3 0 01-3 3 3 3 0 013 3 3 3 0 01-3-3M2 22h8v-1a4 4 0 00-8 0v1z"/></svg>
            </div>
            <div class="brand-text">
                CodeXpress
                <span class="brand-sub">Teacher Workspace</span>
            </div>
        </a>

        @php
            $authTeacher = Auth::guard('teacher')->user();
            $initials = '';
            if($authTeacher) {
                $initials = strtoupper(substr($authTeacher->first_name, 0, 1) . substr($authTeacher->last_name, 0, 1));
            }
        @endphp

        @if($authTeacher)
        <div class="teacher-profile-widget" style="padding: 1.25rem; margin: 1rem 1rem 0.5rem; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: var(--radius-sm); display: flex; align-items: center; gap: 0.75rem;">
            <div class="teacher-avatar" style="width: 40px; height: 40px; border-radius: 50%; background: var(--accent); color: var(--white); font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; border: 2px solid rgba(255,255,255,0.1); flex-shrink: 0;">{{ $initials }}</div>
            <div class="teacher-info" style="overflow: hidden; display: flex; flex-direction: column;">
                <span class="teacher-name" style="font-size: 0.825rem; font-weight: 600; color: var(--white); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $authTeacher->salutation ? $authTeacher->salutation . ' ' : '' }}{{ $authTeacher->last_name }}</span>
                <span class="teacher-subject" style="font-size: 0.68rem; color: var(--g400); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 0.1rem;">{{ $authTeacher->subject }}</span>
            </div>
        </div>
        @endif

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <a href="{{ route('teacher.dashboard') }}" class="nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    <span class="nav-label">Dashboard</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Workplace</div>
                <a href="{{ route('teacher.results.index') }}" class="nav-item {{ request()->routeIs('teacher.results.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    <span class="nav-label">Quiz Results</span>
                </a>
                <a href="{{ route('teacher.students.index') }}" class="nav-item {{ request()->routeIs('teacher.students.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    <span class="nav-label">My Students</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Account</div>
                <a href="{{ route('teacher.profile') }}" class="nav-item {{ request()->routeIs('teacher.profile') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="nav-label">My Profile</span>
                </a>
                <a href="#" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    <span class="nav-label">Log Out</span>
                </a>
                <form id="logout-form" action="{{ route('teacher.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </nav>

        <div class="sidebar-footer">
            <span>© {{ date('Y') }} CodeXpress</span>
        </div>
    </aside>

    <!-- Sidebar overlay for mobile -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Bar -->
        <header class="top-bar">
            <div class="top-bar-left">
                <button class="sidebar-toggle" id="sidebar-toggle" aria-label="Toggle sidebar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div class="top-bar-title">@yield('page-title', 'Teacher Dashboard')</div>
            </div>

            <div class="top-bar-right">
                <div class="campus-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    <span>{{ Auth::guard('teacher')->user()->tenant->name ?? 'Campus' }}</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>

        <footer class="page-footer">CodeXpress — Teacher Workspace © {{ date('Y') }}</footer>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Sidebar toggle (mobile)
        (function() {
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebar-overlay');
            var toggle = document.getElementById('sidebar-toggle');

            toggle.addEventListener('click', function() {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            });
            overlay.addEventListener('click', function() {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            });
        })();

        // Toast configs
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#18181b',
            color: '#ffffff'
        });

        // Flash success
        @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: @json(session('success'))
        });
        @endif

        // Flash errors
        @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Action Failed',
            html: {!! json_encode(implode("<br>", $errors->all())) !!},
            confirmButtonColor: '#4f46e5',
            background: '#18181b',
            color: '#ffffff'
        });
        @endif
    </script>
    @yield('scripts')
</body>
</html>
