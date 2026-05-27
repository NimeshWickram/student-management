<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Management')</title>
    <meta name="description" content="@yield('description', 'Student Management System')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{
            --white:#fff;--black:#0a0a0a;
            --g50:#fafafa;--g100:#f5f5f5;--g200:#e5e5e5;--g300:#d4d4d4;
            --g400:#a3a3a3;--g500:#737373;--g600:#525252;--g700:#404040;
            --g800:#262626;--g900:#171717;
            --radius:12px;--radius-sm:8px;
            --shadow-lg:0 12px 48px rgba(0,0,0,.12);
            --tr:0.25s cubic-bezier(.4,0,.2,1);
            --sidebar-w:260px;
            --sidebar-collapsed-w:72px;
            --topbar-h:60px;
            --accent:#6366f1;
            --accent-light:#818cf8;
        }
        html{font-size:16px;-webkit-font-smoothing:antialiased}
        body{font-family:'Inter',system-ui,sans-serif;background:var(--g100);color:var(--black);min-height:100vh;display:flex}

        /* ═══════════════════════════════════════
           SIDEBAR
           ═══════════════════════════════════════ */
        .sidebar{
            position:fixed;top:0;left:0;bottom:0;
            width:var(--sidebar-w);
            background:var(--g900);
            color:var(--white);
            display:flex;flex-direction:column;
            z-index:200;
            transition:transform .3s cubic-bezier(.4,0,.2,1),width .3s cubic-bezier(.4,0,.2,1);
            box-shadow:4px 0 24px rgba(0,0,0,.15);
            overflow:hidden;
        }
        .sidebar-brand{
            display:flex;align-items:center;gap:.75rem;
            padding:1.25rem 1.25rem;
            border-bottom:1px solid rgba(255,255,255,.06);
            text-decoration:none;color:var(--white);
            min-height:var(--topbar-h);
            flex-shrink:0;
        }
        .sidebar-brand .brand-icon{
            width:38px;height:38px;border-radius:10px;
            background:linear-gradient(135deg,var(--accent),var(--accent-light));
            display:flex;align-items:center;justify-content:center;
            flex-shrink:0;
        }
        .sidebar-brand .brand-icon svg{width:20px;height:20px;fill:var(--white)}
        .sidebar-brand .brand-text{font-size:.95rem;font-weight:700;letter-spacing:-.02em;white-space:nowrap;overflow:hidden}
        .sidebar-brand .brand-sub{font-size:.65rem;color:var(--g400);font-weight:400;display:block;letter-spacing:.02em;margin-top:.1rem}

        .sidebar-nav{flex:1;padding:.75rem 0;overflow-y:auto;overflow-x:hidden}
        .sidebar-nav::-webkit-scrollbar{width:4px}
        .sidebar-nav::-webkit-scrollbar-thumb{background:var(--g700);border-radius:4px}

        .nav-section{padding:0 1rem;margin-bottom:.25rem}
        .nav-section-title{
            font-size:.65rem;font-weight:600;text-transform:uppercase;
            letter-spacing:.08em;color:var(--g500);
            padding:.75rem .25rem .4rem;white-space:nowrap;overflow:hidden;
        }

        .nav-item{
            display:flex;align-items:center;gap:.75rem;
            padding:.7rem 1rem;margin:0 .5rem;
            font-size:.85rem;font-weight:500;
            color:var(--g400);text-decoration:none;
            border-radius:var(--radius-sm);
            transition:all var(--tr);
            white-space:nowrap;overflow:hidden;
            position:relative;
        }
        .nav-item:hover{color:var(--white);background:rgba(255,255,255,.06)}
        .nav-item.active{color:var(--white);background:rgba(99,102,241,.2)}
        .nav-item.active::before{
            content:'';position:absolute;left:0;top:50%;transform:translateY(-50%);
            width:3px;height:60%;background:var(--accent);border-radius:0 4px 4px 0;
        }
        .nav-item svg{width:20px;height:20px;flex-shrink:0;opacity:.7;transition:opacity var(--tr)}
        .nav-item:hover svg,.nav-item.active svg{opacity:1}
        .nav-item .nav-label{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .nav-item .nav-badge{
            margin-left:auto;
            padding:.1rem .55rem;font-size:.65rem;font-weight:700;
            background:rgba(255,255,255,.1);border-radius:20px;color:var(--g300);
            flex-shrink:0;
        }
        .nav-item.active .nav-badge{background:rgba(99,102,241,.3);color:var(--accent-light)}

        .sidebar-footer{
            padding:1rem 1.25rem;border-top:1px solid rgba(255,255,255,.06);
            font-size:.7rem;color:var(--g600);white-space:nowrap;overflow:hidden;
            flex-shrink:0;
        }

        /* ═══════════════════════════════════════
           MAIN AREA
           ═══════════════════════════════════════ */
        .main-wrapper{
            flex:1;margin-left:var(--sidebar-w);
            display:flex;flex-direction:column;min-height:100vh;
            transition:margin-left .3s cubic-bezier(.4,0,.2,1);
        }

        /* Top Bar */
        .top-bar{
            position:sticky;top:0;z-index:100;
            display:flex;align-items:center;justify-content:space-between;
            height:var(--topbar-h);
            padding:0 2rem;
            background:rgba(255,255,255,.85);
            backdrop-filter:blur(12px);
            border-bottom:1px solid var(--g200);
        }
        .top-bar-left{display:flex;align-items:center;gap:1rem}
        .top-bar-title{font-size:1.05rem;font-weight:700;letter-spacing:-.02em;color:var(--black)}
        .breadcrumb{font-size:.8rem;color:var(--g500)}
        .breadcrumb a{color:var(--g400);text-decoration:none;transition:color var(--tr)}
        .breadcrumb a:hover{color:var(--black)}

        /* Mobile menu toggle */
        .sidebar-toggle{
            display:none;background:none;border:none;cursor:pointer;
            padding:.5rem;border-radius:var(--radius-sm);transition:background var(--tr);
        }
        .sidebar-toggle:hover{background:var(--g100)}
        .sidebar-toggle svg{width:22px;height:22px;color:var(--g700)}

        /* Content */
        .main-content{flex:1;padding:2rem 2rem 3rem;max-width:1200px;width:100%;margin:0 auto}

        /* Flash — hidden by default, shown via SweetAlert */
        .alert-success{display:none}
        .alert-errors{background:var(--g50);border:1px solid var(--g200);border-left:4px solid var(--black);border-radius:var(--radius-sm);padding:1rem 1.25rem;margin-bottom:1.5rem;animation:sd .4s var(--tr)}
        .alert-errors .et{font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:var(--black);margin-bottom:.6rem}
        .alert-errors ul{list-style:none}
        .alert-errors ul li{font-size:.825rem;color:var(--g700);padding:.3rem 0 .3rem 1rem;position:relative}
        .alert-errors ul li::before{content:'—';position:absolute;left:0;color:var(--g400)}
        @keyframes sd{from{opacity:0;transform:translateY(-12px)}to{opacity:1;transform:translateY(0)}}

        /* Footer */
        .page-footer{text-align:center;padding:1.5rem 1rem;font-size:.75rem;color:var(--g400);border-top:1px solid var(--g200)}

        /* ═══════════════════════════════════════
           PAGINATION
           ═══════════════════════════════════════ */
        .pagination-nav{display:flex;align-items:center;justify-content:space-between;margin-top:1.25rem;flex-wrap:wrap;gap:.75rem}
        .pagination-info{font-size:.8rem;color:var(--g500)}
        .pagination-info strong{color:var(--g700);font-weight:600}
        .pagination-links{display:flex;align-items:center;gap:.35rem}
        .page-btn{display:inline-flex;align-items:center;gap:.3rem;padding:.4rem .7rem;font-family:inherit;font-size:.8rem;font-weight:500;color:var(--g600);background:var(--white);border:1px solid var(--g200);border-radius:6px;cursor:pointer;transition:all .2s ease;text-decoration:none;line-height:1.2}
        .page-btn svg{width:14px;height:14px;flex-shrink:0}
        .page-btn:hover{background:var(--g50);border-color:var(--g300);color:var(--black)}
        .page-btn.active{background:var(--black);color:var(--white);border-color:var(--black);font-weight:600}
        .page-btn.disabled{opacity:.4;cursor:not-allowed;pointer-events:none}
        .page-btn.dots{border:none;background:none;padding:.4rem .3rem;cursor:default}
        @media(max-width:640px){
            .pagination-nav{flex-direction:column;align-items:center}
            .pagination-links{flex-wrap:wrap;justify-content:center}
        }

        /* Mobile overlay */
        .sidebar-overlay{
            display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);
            z-index:199;opacity:0;transition:opacity .3s ease;
        }
        .sidebar-overlay.active{display:block;opacity:1}

        /* ── Tablet ── */
        @media(max-width:1024px){
            .sidebar{width:var(--sidebar-collapsed-w)}
            .sidebar .brand-text,.sidebar .nav-section-title,.sidebar .nav-label,.sidebar .nav-badge,.sidebar .sidebar-footer span{
                display:none;
            }
            .sidebar .nav-item{justify-content:center;padding:.7rem;margin:0 .35rem}
            .sidebar .sidebar-brand{justify-content:center;padding:1.25rem .5rem}
            .sidebar .sidebar-footer{text-align:center;padding:.75rem .5rem}
            .main-wrapper{margin-left:var(--sidebar-collapsed-w)}
        }

        /* ── Mobile ── */
        @media(max-width:768px){
            .sidebar{transform:translateX(-100%);width:var(--sidebar-w)}
            .sidebar.open{transform:translateX(0)}
            .sidebar .brand-text,.sidebar .nav-section-title,.sidebar .nav-label,.sidebar .nav-badge,.sidebar .sidebar-footer span{
                display:initial;
            }
            .sidebar .nav-item{justify-content:flex-start;padding:.7rem 1rem;margin:0 .5rem}
            .sidebar .sidebar-brand{justify-content:flex-start;padding:1.25rem 1.25rem}
            .sidebar .sidebar-footer{text-align:left;padding:1rem 1.25rem}
            .main-wrapper{margin-left:0}
            .sidebar-toggle{display:flex}
            .top-bar{padding:0 1rem}
            .main-content{padding:1.5rem 1rem 2rem}
        }

        @media(max-width:480px){
            .main-content{padding:1rem .75rem 1.5rem}
            .top-bar-title{font-size:.9rem}
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
            </div>
            <div class="brand-text">
                EduManager
                <span class="brand-sub">Management System</span>
            </div>
        </a>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" id="nav-dashboard">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    <span class="nav-label">Dashboard</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Management</div>
                <a href="{{ route('students.index') }}" class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}" id="nav-students">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    <span class="nav-label">Students</span>
                    <span class="nav-badge">{{ \App\Models\Student::where('tenant_id', $activeTenantId)->count() }}</span>
                </a>
                <a href="{{ route('teachers.index') }}" class="nav-item {{ request()->routeIs('teachers.*') ? 'active' : '' }}" id="nav-teachers">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="nav-label">Teachers</span>
                    <span class="nav-badge">{{ \App\Models\Teacher::where('tenant_id', $activeTenantId)->count() }}</span>
                </a>
                <a href="{{ route('subjects.index') }}" class="nav-item {{ request()->routeIs('subjects.*') ? 'active' : '' }}" id="nav-subjects">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                    <span class="nav-label">Subjects</span>
                    <span class="nav-badge">{{ \App\Models\Subject::where('tenant_id', $activeTenantId)->count() }}</span>
                </a>
                <a href="{{ route('quizzes.index') }}" class="nav-item {{ request()->routeIs('quizzes.*') ? 'active' : '' }}" id="nav-quizzes">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    <span class="nav-label">Quizzes</span>
                    <span class="nav-badge">{{ \App\Models\Quiz::where('tenant_id', $activeTenantId)->count() }}</span>
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <span>© {{ date('Y') }} EduManager</span>
        </div>
    </aside>

    <!-- Sidebar overlay for mobile -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Main wrapper -->
    <div class="main-wrapper">
        <!-- Top Bar -->
        <header class="top-bar">
            <div class="top-bar-left">
                <button class="sidebar-toggle" id="sidebar-toggle" aria-label="Toggle sidebar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div>
                    <div class="top-bar-title">@yield('page-title', 'Dashboard')</div>
                    <div class="breadcrumb">@yield('breadcrumb')</div>
                </div>
            </div>

            <!-- Tenant Switcher Dropdown -->
            <div class="top-bar-right" style="display: flex; align-items: center;">
                <form action="{{ route('change.tenant') }}" method="POST" id="tenant-form" style="display: flex; align-items: center; gap: 0.5rem; background: var(--g50); padding: 0.35rem 0.75rem; border-radius: 8px; border: 1px solid var(--g200);">
                    @csrf
                    <label for="tenant-select" style="font-size: 0.75rem; font-weight: 700; color: var(--g600); display: flex; align-items: center; gap: 0.3rem; text-transform: uppercase; letter-spacing: 0.05em;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; color: var(--accent);"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Campus:
                    </label>
                    <select name="tenant_id" id="tenant-select" onchange="document.getElementById('tenant-form').submit()" style="padding: 0.25rem 1.75rem 0.25rem 0.5rem; font-family: inherit; font-size: 0.8rem; font-weight: 700; border: none; background: transparent; cursor: pointer; color: var(--black); outline: none; appearance: none; -webkit-appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22 fill=%22none%22 stroke=%22%23111827%22 stroke-width=%222.5%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22><polyline points=%226 9 12 15 18 9%22></polyline></svg>'); background-repeat: no-repeat; background-position: right 0.1rem center; background-size: 0.85rem;">
                        @foreach($tenants as $tenant)
                            <option value="{{ $tenant->id }}" {{ $tenant->id == $activeTenantId ? 'selected' : '' }}>
                                {{ $tenant->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            @if($errors->any())
            <div class="alert-errors" role="alert">
                <div class="et">Please fix the following errors</div>
                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            @yield('content')
        </main>

        <footer class="page-footer">EduManager — Student Management System © {{ date('Y') }}</footer>
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

        // Flash success via SweetAlert
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: @json(session('success')),
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            customClass: {
                popup: 'swal-toast-custom'
            }
        });
        @endif

        // Flash errors via SweetAlert
        @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: {!! json_encode(implode("<br>", $errors->all())) !!},
            confirmButtonColor: '#0a0a0a',
            confirmButtonText: 'Got it'
        });
        @endif
    </script>
    </script>

    <!-- AI Chat Widget -->
    <style>
        .chat-widget {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 1000;
            font-family: 'Inter', system-ui, sans-serif;
        }
        .chat-toggle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--accent);
            color: var(--white);
            border: none;
            box-shadow: var(--shadow-lg);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--tr);
        }
        .chat-toggle:hover {
            transform: scale(1.05);
            background: var(--accent-light);
        }
        .chat-toggle svg {
            width: 28px;
            height: 28px;
        }
        .chat-window {
            position: absolute;
            bottom: 76px;
            right: 0;
            width: 350px;
            height: 500px;
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--g200);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px) scale(0.95);
            transform-origin: bottom right;
            transition: all 0.3s cubic-bezier(.4,0,.2,1);
        }
        .chat-widget.open .chat-window {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }
        .chat-header {
            background: var(--black);
            color: var(--white);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .chat-header h3 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .chat-header h3 svg { width: 18px; height: 18px; color: var(--accent); }
        .chat-close {
            background: none;
            border: none;
            color: var(--g400);
            cursor: pointer;
            padding: 0;
            display: flex;
        }
        .chat-close:hover { color: var(--white); }
        .chat-messages {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
            background: var(--g50);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .chat-message {
            max-width: 85%;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            line-height: 1.5;
        }
        .chat-message.bot {
            background: var(--white);
            border: 1px solid var(--g200);
            align-self: flex-start;
            border-bottom-left-radius: 4px;
        }
        .chat-message.user {
            background: var(--accent);
            color: var(--white);
            align-self: flex-end;
            border-bottom-right-radius: 4px;
        }
        .chat-input-area {
            padding: 1rem;
            background: var(--white);
            border-top: 1px solid var(--g200);
            display: flex;
            gap: 0.5rem;
        }
        .chat-input {
            flex: 1;
            padding: 0.6rem 0.8rem;
            border: 1.5px solid var(--g200);
            border-radius: 20px;
            font-family: inherit;
            font-size: 0.85rem;
            outline: none;
            transition: border-color var(--tr);
        }
        .chat-input:focus { border-color: var(--black); }
        .chat-send {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--black);
            color: var(--white);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background var(--tr);
        }
        .chat-send:hover { background: var(--g800); }
        .chat-send svg { width: 16px; height: 16px; margin-left: 2px; }
        .chat-send:disabled { background: var(--g300); cursor: not-allowed; }
        .typing-indicator {
            display: none;
            align-self: flex-start;
            background: var(--white);
            border: 1px solid var(--g200);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            gap: 4px;
        }
        .typing-indicator.active { display: flex; align-items: center; }
        .typing-dot {
            width: 6px;
            height: 6px;
            background: var(--g400);
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out both;
        }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typing {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
        @media(max-width:480px){
            .chat-window {
                width: calc(100vw - 32px);
                right: -8px;
            }
        }
    </style>

    <div class="chat-widget" id="chatWidget">
        <button class="chat-toggle" id="chatToggle" aria-label="Toggle chat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
        </button>

        <div class="chat-window">
            <div class="chat-header">
                <h3>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/><line x1="8" y1="16" x2="8" y2="16"/><line x1="16" y1="16" x2="16" y2="16"/></svg>
                    EduBot AI
                </h3>
                <button class="chat-close" id="chatClose">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            
            <div class="chat-messages" id="chatMessages">
                <div class="chat-message bot">
                    Hello! I'm EduBot. I can answer questions about the current students, teachers, and subjects in the system. How can I help you today?
                </div>
                <div class="typing-indicator" id="typingIndicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>

            <form class="chat-input-area" id="chatForm">
                <input type="text" class="chat-input" id="chatInput" placeholder="Ask a question..." autocomplete="off">
                <button type="submit" class="chat-send" id="chatSend">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const widget = document.getElementById('chatWidget');
            const toggle = document.getElementById('chatToggle');
            const close = document.getElementById('chatClose');
            const form = document.getElementById('chatForm');
            const input = document.getElementById('chatInput');
            const messages = document.getElementById('chatMessages');
            const typingIndicator = document.getElementById('typingIndicator');
            const sendBtn = document.getElementById('chatSend');

            // Toggle window
            toggle.addEventListener('click', () => {
                widget.classList.toggle('open');
                if (widget.classList.contains('open')) input.focus();
            });
            close.addEventListener('click', () => widget.classList.remove('open'));

            // Auto scroll to bottom
            function scrollToBottom() {
                messages.scrollTop = messages.scrollHeight;
            }

            function addMessage(text, sender) {
                const div = document.createElement('div');
                div.className = `chat-message ${sender}`;
                // Basic markdown to HTML (handle line breaks and basic bolding)
                let html = text.replace(/\n/g, '<br>');
                html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                div.innerHTML = html;
                
                // Insert before the typing indicator
                messages.insertBefore(div, typingIndicator);
                scrollToBottom();
            }

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                const msg = input.value.trim();
                if (!msg) return;

                // Add user message
                addMessage(msg, 'user');
                input.value = '';
                input.disabled = true;
                sendBtn.disabled = true;

                // Show typing indicator
                typingIndicator.classList.add('active');
                scrollToBottom();

                try {
                    const response = await fetch('/bot/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ message: msg })
                    });

                    const data = await response.json();
                    
                    typingIndicator.classList.remove('active');
                    
                    if (response.ok) {
                        addMessage(data.reply, 'bot');
                    } else {
                        addMessage('Error: ' + (data.reply || 'Something went wrong.'), 'bot');
                    }

                } catch (error) {
                    typingIndicator.classList.remove('active');
                    addMessage('Sorry, I am having trouble connecting to the server.', 'bot');
                } finally {
                    input.disabled = false;
                    sendBtn.disabled = false;
                    input.focus();
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
