<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login — CodeXpress</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg-gradient: radial-gradient(circle at 50% 50%, #1e1b4b 0%, #09090b 100%);
            --accent: #6366f1;
            --accent-light: #818cf8;
            --accent-glow: rgba(99, 102, 241, 0.15);
            --white: #ffffff;
            --g200: #e5e5e5;
            --g400: #a3a3a3;
            --g800: #27272a;
            --g900: #09090b;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--bg-gradient);
            color: var(--white);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient glowing background circles (Matching Student Login) */
        .orb {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(99,102,241,0.1) 0%, rgba(99,102,241,0) 70%);
            filter: blur(40px);
            z-index: 1;
            pointer-events: none;
        }
        .orb-1 { top: -100px; left: -100px; }
        .orb-2 { bottom: -150px; right: -100px; }

        .login-container {
            width: 100%;
            max-width: 440px;
            z-index: 10;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);
        }
        .brand-icon svg { width: 28px; height: 28px; fill: var(--white); }
        .brand-title { font-size: 1.75rem; font-weight: 800; tracking: -0.03em; letter-spacing: -0.02em; }
        .brand-subtitle { font-size: 0.85rem; color: var(--g400); margin-top: 0.25rem; font-weight: 400; }

        .login-card {
            background: rgba(15, 15, 20, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 2.25rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
        }
        .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--g400);
        }
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-wrapper > svg.input-icon {
            position: absolute;
            left: 1rem;
            width: 18px;
            height: 18px;
            color: var(--g400);
            pointer-events: none;
            transition: color 0.25s ease;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: var(--white);
            font-family: inherit;
            font-size: 0.9rem;
            outline: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-input:focus {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--accent-light);
            box-shadow: 0 0 0 4px var(--accent-glow);
        }
        .form-input:focus + svg.input-icon {
            color: var(--accent-light);
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            cursor: pointer;
            color: var(--g400);
            transition: color 0.25s ease;
            background: none;
            border: none;
            outline: none;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .toggle-password:hover {
            color: var(--white);
        }
        .toggle-password svg {
            width: 18px;
            height: 18px;
        }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 1.5rem 0 1.25rem;
            font-size: 0.8rem;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            color: var(--g400);
            user-select: none;
        }
        .remember-me input {
            accent-color: var(--accent);
            cursor: pointer;
        }

        .submit-btn {
            width: 100%;
            padding: 0.85rem;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: var(--white);
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
            filter: brightness(1.08);
        }
        .submit-btn:active {
            transform: translateY(1px);
        }

        .demo-credentials {
            margin-top: 1.75rem;
            padding: 1rem;
            background: rgba(99, 102, 241, 0.06);
            border: 1px dashed rgba(99, 102, 241, 0.2);
            border-radius: 8px;
            font-size: 0.78rem;
            line-height: 1.45;
            color: var(--accent-light);
        }
        .demo-credentials strong { color: var(--white); }

        .portal-links {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8rem;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }
        .portal-links a {
            color: var(--g400);
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .portal-links a:hover {
            color: var(--white);
            text-decoration: underline;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>

    <div class="login-container">
        <div class="brand-header">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24"><path d="M20 17a2 2 0 002-2V4a2 2 0 00-2-2H9.46c.35.61.54 1.3.54 2h10v11h-9v2m4-10H9.46c.35.61.54 1.3.54 2H15v-2M6 7a3 3 0 01-3 3 3 3 0 013 3 3 3 0 01-3-3M2 22h8v-1a4 4 0 00-8 0v1z"/></svg>
            </div>
            <h1 class="brand-title">CodeXpress</h1>
            <p class="brand-subtitle">Teacher Portal Authentication</p>
        </div>

        <div class="login-card">
            <form action="{{ route('teacher.login') }}" method="POST">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email" class="form-label">Teacher Email Address</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        <input type="email" name="email" id="email" class="form-input" placeholder="teacher@school.lk" value="{{ old('email') }}" required autofocus autocomplete="email">
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input type="password" name="password" id="password" class="form-input" placeholder="Enter your password" required autocomplete="current-password" style="padding-right: 2.5rem;">
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('password', this)">
                            <svg class="eye-open" style="display: block;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-closed" style="display: none;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Footer options -->
                <div class="card-footer">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember">
                        Remember this device
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="submit-btn">
                    <span>Sign In to Teacher Portal</span>
                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </button>
            </form>

            <div class="demo-credentials">
                🔑 <strong>Quick Test Credentials:</strong><br>
                Use the email of any teacher in the system.<br>
                Password: <strong>teacher123</strong> (default)
            </div>
        </div>

        <div class="portal-links">
            <a href="{{ route('student.login') }}">Switch to Student Portal →</a>
            <a href="{{ route('admin.login') }}">Switch to Administrator Portal →</a>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Authentication Failed',
            text: @json($errors->first()),
            confirmButtonColor: '#6366f1',
            background: '#18181b',
            color: '#ffffff'
        });
        @endif

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
            timer: 2500,
            timerProgressBar: true,
            showConfirmButton: false,
            background: '#18181b',
            color: '#ffffff'
        });
        @endif

        function togglePasswordVisibility(fieldId, button) {
            const input = document.getElementById(fieldId);
            const openIcon = button.querySelector('.eye-open');
            const closedIcon = button.querySelector('.eye-closed');
            
            if (input.type === 'password') {
                input.type = 'text';
                openIcon.style.display = 'none';
                closedIcon.style.display = 'block';
            } else {
                input.type = 'password';
                openIcon.style.display = 'block';
                closedIcon.style.display = 'none';
            }
        }
    </script>
</body>
</html>
