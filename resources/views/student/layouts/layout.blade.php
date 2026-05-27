<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Panel — EduManager')</title>
    <meta name="description" content="Student Portal for EduManager">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{
            --white:#fff;--black:#09090b;
            --g50:#fafafa;--g100:#f4f4f5;--g200:#e4e4e7;--g300:#d4d4d8;
            --g400:#a1a1aa;--g500:#71717a;--g600:#52525b;--g700:#3f3f46;
            --g800:#27272a;--g900:#18181b;
            --radius:16px;--radius-sm:10px;
            --shadow-lg:0 10px 30px rgba(0,0,0,.08);
            --tr:0.25s cubic-bezier(.4,0,.2,1);
            --sidebar-w:265px;
            --sidebar-collapsed-w:76px;
            --topbar-h:70px;
            --accent:#4f46e5;
            --accent-light:#6366f1;
            --accent-glow:rgba(79, 70, 229, 0.08);
        }
        html{font-size:16px;-webkit-font-smoothing:antialiased}
        body{font-family:'Inter',system-ui,sans-serif;background:var(--g100);color:var(--black);min-height:100vh;display:flex}

        /* ═══════════════════════════════════════
           SIDEBAR (Student Themed)
           ═══════════════════════════════════════ */
        .sidebar{
            position:fixed;top:0;left:0;bottom:0;
            width:var(--sidebar-w);
            background:var(--g900);
            color:var(--white);
            display:flex;flex-direction:column;
            z-index:200;
            transition:transform .3s cubic-bezier(.4,0,.2,1),width .3s cubic-bezier(.4,0,.2,1);
            box-shadow:5px 0 30px rgba(0,0,0,.15);
            overflow:hidden;
        }
        .sidebar-brand{
            display:flex;align-items:center;gap:.75rem;
            padding:1.5rem 1.5rem;
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
        .sidebar-brand .brand-sub{font-size:.65rem;color:var(--accent-light);font-weight:600;display:block;letter-spacing:.02em;margin-top:.1rem}

        .student-profile-widget {
            padding: 1.25rem;
            margin: 1rem 1rem 0.5rem;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent);
            color: var(--white);
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            border: 2px solid rgba(255,255,255,0.1);
        }
        .student-info {
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .student-name {
            font-size: 0.825rem;
            font-weight: 600;
            color: var(--white);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .student-course {
            font-size: 0.68rem;
            color: var(--g400);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 0.1rem;
        }

        .sidebar-nav{flex:1;padding:.5rem 0;overflow-y:auto;overflow-x:hidden}
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
            padding:.75rem 1rem;margin:0 .5rem;
            font-size:.85rem;font-weight:500;
            color:var(--g400);text-decoration:none;
            border-radius:var(--radius-sm);
            transition:all var(--tr);
            white-space:nowrap;overflow:hidden;
            position:relative;
            cursor: pointer;
        }
        .nav-item:hover{color:var(--white);background:rgba(255,255,255,.05)}
        .nav-item.active{color:var(--white);background:rgba(79, 70, 229, 0.15)}
        .nav-item.active::before{
            content:'';position:absolute;left:0;top:50%;transform:translateY(-50%);
            width:3px;height:60%;background:var(--accent);border-radius:0 4px 4px 0;
        }
        .nav-item svg{width:20px;height:20px;flex-shrink:0;opacity:.7;transition:opacity var(--tr)}
        .nav-item:hover svg,.nav-item.active svg{opacity:1}
        .nav-item .nav-label{white-space:nowrap;overflow:hidden;text-overflow:ellipsis}

        .sidebar-footer{
            padding:1.25rem 1.5rem;border-top:1px solid rgba(255,255,255,.06);
            font-size:.72rem;color:var(--g500);white-space:nowrap;overflow:hidden;
            flex-shrink:0;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
            padding:0 2.5rem;
            background:rgba(255,255,255,.8);
            backdrop-filter:blur(16px);
            -webkit-backdrop-filter:blur(16px);
            border-bottom:1px solid var(--g200);
        }
        .top-bar-left{display:flex;align-items:center;gap:1rem}
        .top-bar-title{font-size:1.1rem;font-weight:800;letter-spacing:-.02em;color:var(--black)}
        .breadcrumb{font-size:.78rem;color:var(--g500);font-weight: 500;}
        .breadcrumb a{color:var(--g400);text-decoration:none;transition:color var(--tr)}
        .breadcrumb a:hover{color:var(--black)}

        /* Logout form support */
        .logout-btn {
            background: none;
            border: none;
            color: inherit;
            cursor: pointer;
            width: 100%;
            text-align: left;
            font-family: inherit;
            font-size: inherit;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        /* Mobile menu toggle */
        .sidebar-toggle{
            display:none;background:none;border:none;cursor:pointer;
            padding:.5rem;border-radius:var(--radius-sm);transition:background var(--tr);
        }
        .sidebar-toggle:hover{background:var(--g100)}
        .sidebar-toggle svg{width:22px;height:22px;color:var(--g700)}

        /* Content */
        .main-content{flex:1;padding:2.5rem 2.5rem 4rem;max-width:1200px;width:100%;margin:0 auto}

        .alert-errors{background:rgba(239, 68, 68, 0.05);border:1px solid rgba(239, 68, 68, 0.15);border-left:4px solid #ef4444;border-radius:var(--radius-sm);padding:1rem 1.25rem;margin-bottom:1.5rem;animation:sd .4s var(--tr)}
        .alert-errors .et{font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.04em;color:#b91c1c;margin-bottom:.6rem}
        .alert-errors ul{list-style:none}
        .alert-errors ul li{font-size:.825rem;color:#7f1d1d;padding:.3rem 0 .3rem 1rem;position:relative}
        .alert-errors ul li::before{content:'—';position:absolute;left:0;color:rgba(239, 68, 68, 0.4)}
        @keyframes sd{from{opacity:0;transform:translateY(-12px)}to{opacity:1;transform:translateY(0)}}

        /* Footer */
        .page-footer{text-align:center;padding:1.75rem 1rem;font-size:.78rem;color:var(--g400);border-top:1px solid var(--g200);background: var(--white);}

        /* Mobile overlay */
        .sidebar-overlay{
            display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);
            z-index:199;opacity:0;transition:opacity .3s ease;
        }
        .sidebar-overlay.active{display:block;opacity:1}

        /* ── Tablet ── */
        @media(max-width:1024px){
            .sidebar{width:var(--sidebar-collapsed-w)}
            .sidebar .brand-text,.sidebar .nav-section-title,.sidebar .nav-label,.sidebar .student-profile-widget,.sidebar .sidebar-footer span{
                display:none;
            }
            .sidebar .nav-item{justify-content:center;padding:.75rem;margin:0 .35rem}
            .sidebar .sidebar-brand{justify-content:center;padding:1.5rem .5rem}
            .sidebar .sidebar-footer{text-align:center;padding:.75rem .5rem;justify-content:center}
            .main-wrapper{margin-left:var(--sidebar-collapsed-w)}
        }

        /* ── Mobile ── */
        @media(max-width:768px){
            .sidebar{transform:translateX(-100%);width:var(--sidebar-w)}
            .sidebar.open{transform:translateX(0)}
            .sidebar .brand-text,.sidebar .nav-section-title,.sidebar .nav-label,.sidebar .student-profile-widget,.sidebar .sidebar-footer span{
                display:initial;
            }
            .sidebar .nav-item{justify-content:flex-start;padding:.75rem 1rem;margin:0 .5rem}
            .sidebar .sidebar-brand{justify-content:flex-start;padding:1.5rem 1.25rem}
            .sidebar .student-profile-widget{display:flex}
            .sidebar .sidebar-footer{justify-content:space-between;padding:1.25rem 1.5rem}
            .main-wrapper{margin-left:0}
            .sidebar-toggle{display:flex}
            .top-bar{padding:0 1.5rem}
            .main-content{padding:2rem 1.5rem 3rem}
        }

        @media(max-width:480px){
            .main-content{padding:1.25rem 1rem 2.5rem}
            .top-bar-title{font-size:.95rem}
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('student.dashboard') }}" class="sidebar-brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
            </div>
            <div class="brand-text">
                EduManager
                <span class="brand-sub">Student Portal</span>
            </div>
        </a>

        @php
            $authStudent = Auth::guard('student')->user();
            $initials = '';
            if($authStudent) {
                $initials = strtoupper(substr($authStudent->first_name, 0, 1) . substr($authStudent->last_name, 0, 1));
            }
        @endphp

        @if($authStudent)
        <div class="student-profile-widget">
            <div class="student-avatar">{{ $initials }}</div>
            <div class="student-info">
                <span class="student-name">{{ $authStudent->first_name }} {{ $authStudent->last_name }}</span>
                <span class="student-course">{{ $authStudent->course }}</span>
            </div>
        </div>
        @endif

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Academic Portal</div>
                <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" id="nav-dashboard">
                    <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    <span class="nav-label">Portal Dashboard</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Sign Out</div>
                <div class="nav-item">
                    <form action="{{ route('student.logout') }}" method="POST" id="logout-form" style="width: 100%;">
                        @csrf
                        <button type="submit" class="logout-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                            <span class="nav-label">Logout</span>
                        </button>
                    </form>
                </div>
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
                    <svg fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
                <div>
                    <div class="top-bar-title">@yield('page-title', 'Student Dashboard')</div>
                    <div class="breadcrumb">@yield('breadcrumb')</div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            @if($errors->any())
            <div class="alert-errors" role="alert">
                <div class="et">Please address the following requirements</div>
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

        // Logout Confirmation
        document.getElementById('logout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: 'Sign Out?',
                text: 'Are you sure you want to end your current session?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#71717a',
                confirmButtonText: 'Yes, Sign Out'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Flash success via SweetAlert
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Welcome!',
            text: @json(session('success')),
            timer: 3500,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
        });
        @endif
    </script>

    @yield('scripts')
</body>
</html>
