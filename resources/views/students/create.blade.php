<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration — Student Management</title>
    <meta name="description" content="Register a new student in the Student Management System.">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ══════════════════════════════════════════════════
           Reset & Base
           ══════════════════════════════════════════════════ */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --white:   #ffffff;
            --black:   #0a0a0a;
            --gray-50: #fafafa;
            --gray-100:#f5f5f5;
            --gray-200:#e5e5e5;
            --gray-300:#d4d4d4;
            --gray-400:#a3a3a3;
            --gray-500:#737373;
            --gray-600:#525252;
            --gray-700:#404040;
            --gray-800:#262626;
            --gray-900:#171717;
            --radius:  12px;
            --radius-sm: 8px;
            --shadow-sm: 0 1px 2px rgba(0,0,0,.05);
            --shadow:    0 4px 24px rgba(0,0,0,.08);
            --shadow-lg: 0 12px 48px rgba(0,0,0,.12);
            --transition: 0.25s cubic-bezier(.4,0,.2,1);
        }

        html {
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--gray-100);
            color: var(--black);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /*
           Card Container
           */
        .card {
            width: 100%;
            max-width: 540px;
            background: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            padding: 2.75rem 2.25rem 2.25rem;
            position: relative;
            overflow: hidden;
        }

        /* Top accent bar */
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--black);
        }

        /*
           Header
           */
        .card-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .card-header .icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--black);
            margin-bottom: 1rem;
        }

        .card-header .icon svg {
            width: 28px;
            height: 28px;
            fill: var(--white);
        }

        .card-header h1 {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.2;
            color: var(--black);
        }

        .card-header p {
            margin-top: 0.5rem;
            font-size: 0.875rem;
            font-weight: 400;
            color: var(--gray-500);
            line-height: 1.5;
        }

        /*
           Success Message*/
        .alert-success {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--gray-900);
            color: var(--white);
            padding: 1rem 1.25rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            animation: slideDown 0.4s var(--transition);
        }

        .alert-success .check-icon {
            flex-shrink: 0;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-success .check-icon svg {
            width: 12px;
            height: 12px;
            stroke: var(--black);
            stroke-width: 3;
            fill: none;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /*
           Validation Errors
            */
        .alert-errors {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-left: 4px solid var(--black);
            border-radius: var(--radius-sm);
            padding: 1rem 1.25rem;
            margin-bottom: 1.75rem;
            animation: slideDown 0.4s var(--transition);
        }

        .alert-errors .errors-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--black);
            margin-bottom: 0.6rem;
        }

        .alert-errors ul {
            list-style: none;
        }

        .alert-errors ul li {
            font-size: 0.825rem;
            color: var(--gray-700);
            padding: 0.3rem 0;
            padding-left: 1rem;
            position: relative;
        }

        .alert-errors ul li::before {
            content: '—';
            position: absolute;
            left: 0;
            color: var(--gray-400);
        }

        /* ══════════════════════════════════════════════════
           Form Elements
           ══════════════════════════════════════════════════ */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.4rem;
            letter-spacing: 0.01em;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .field-icon {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--gray-400);
            pointer-events: none;
            transition: color var(--transition);
        }

        .input-wrapper input,
        .input-wrapper select {
            width: 100%;
            padding: 0.75rem 0.85rem 0.75rem 2.75rem;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 400;
            color: var(--black);
            background: var(--white);
            border: 1.5px solid var(--gray-200);
            border-radius: var(--radius-sm);
            outline: none;
            transition: border-color var(--transition), box-shadow var(--transition);
            -webkit-appearance: none;
            appearance: none;
        }

        .input-wrapper input::placeholder {
            color: var(--gray-400);
            font-weight: 300;
        }

        .input-wrapper input:hover,
        .input-wrapper select:hover {
            border-color: var(--gray-300);
        }

        .input-wrapper input:focus,
        .input-wrapper select:focus {
            border-color: var(--black);
            box-shadow: 0 0 0 3px rgba(10,10,10,0.08);
        }

        .input-wrapper:focus-within .field-icon {
            color: var(--black);
        }

        /* Select dropdown arrow */
        .select-wrapper::after {
            content: '';
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 6px solid var(--gray-400);
            pointer-events: none;
            transition: border-color var(--transition);
        }

        .select-wrapper:focus-within::after {
            border-top-color: var(--black);
        }

        /* ── Row (two-column) ──────────────────────────── */
        .form-row {
            display: flex;
            gap: 1rem;
        }

        .form-row .form-group {
            flex: 1;
        }

        /* ══════════════════════════════════════════════════
           Divider
           ══════════════════════════════════════════════════ */
        .divider {
            height: 1px;
            background: var(--gray-200);
            margin: 0.5rem 0 1.5rem;
        }

        /*
           Submit Button
         */
        .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            width: 100%;
            padding: 0.9rem 1.5rem;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: var(--white);
            background: var(--black);
            border: 2px solid var(--black);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--white);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.35s cubic-bezier(.4,0,.2,1);
            z-index: 0;
        }

        .btn-submit:hover::before {
            transform: scaleX(1);
            transform-origin: left;
        }

        .btn-submit span,
        .btn-submit svg {
            position: relative;
            z-index: 1;
            transition: color var(--transition);
        }

        .btn-submit:hover span {
            color: var(--black);
        }

        .btn-submit:hover svg {
            color: var(--black);
            transform: translateX(3px);
        }

        .btn-submit svg {
            width: 18px;
            height: 18px;
            transition: color var(--transition), transform var(--transition);
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        /* ══════════════════════════════════════════════════
           Footer
           ══════════════════════════════════════════════════ */
        .card-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--gray-200);
            font-size: 0.75rem;
            color: var(--gray-400);
            font-weight: 400;
            letter-spacing: 0.02em;
        }

        /* ══════════════════════════════════════════════════
           Responsive
           ══════════════════════════════════════════════════ */
        @media (max-width: 480px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }

            .card {
                padding: 2rem 1.25rem 1.75rem;
            }

            .card-header h1 {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>

    <div class="card">

        {{-- ── Header ──────────────────────────────────── --}}
        <div class="card-header">
            <div class="icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v1.2c0 .7.5 1.2 1.2 1.2h16.8c.7 0 1.2-.5 1.2-1.2v-1.2c0-3.2-6.4-4.8-9.6-4.8z"/>
                </svg>
            </div>
            <h1>Student Registration</h1>
            <p>Enter the student's details below to create a new record.</p>
        </div>

        {{-- ── Success Message ─────────────────────────── --}}
        @if (session('success'))
            <div class="alert-success" role="alert">
                <div class="check-icon">
                    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </div>
                {{ session('success') }}
            </div>
        @endif

        {{-- ── Validation Errors ───────────────────────── --}}
        @if ($errors->any())
            <div class="alert-errors" role="alert">
                <div class="errors-title">Please fix the following errors</div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ── Registration Form ───────────────────────── --}}
        <form action="{{ route('students.store') }}" method="POST" autocomplete="off">
            @csrf

            {{-- Name Row --}}
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <div class="input-wrapper">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Nimesh" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <div class="input-wrapper">
                        <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Tharusha" required>
                    </div>
                </div>
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrapper">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nimeshtharusha@@example.com" required>
                </div>
            </div>

            {{-- Phone --}}
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <div class="input-wrapper">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.13.81.36 1.6.68 2.34a2 2 0 01-.45 2.11L8.09 9.41a16 16 0 006.5 6.5l1.24-1.24a2 2 0 012.11-.45c.74.32 1.53.55 2.34.68a2 2 0 011.72 2.02z"/></svg>
                    <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="+94 77 123 4567" required>
                </div>
            </div>

            {{-- Course --}}
            <div class="form-group">
                <label for="course">Course</label>
                <div class="input-wrapper select-wrapper">
                    <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                    <select id="course" name="course" required>
                        <option value="" disabled {{ old('course') ? '' : 'selected' }}>Select a course…</option>
                        <option value="Computer Science" {{ old('course') == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                        <option value="Information Technology" {{ old('course') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                        <option value="Software Engineering" {{ old('course') == 'Software Engineering' ? 'selected' : '' }}>Software Engineering</option>
                        <option value="Data Science" {{ old('course') == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                        <option value="Cyber Security" {{ old('course') == 'Cyber Security' ? 'selected' : '' }}>Cyber Security</option>
                        <option value="Business Administration" {{ old('course') == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                    </select>
                </div>
            </div>

            <div class="divider"></div>

            {{-- Submit --}}
            <button type="submit" class="btn-submit" id="submit-btn">
                <span>Register Student</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </button>
        </form>

        <div class="card-footer">
            Student Management System &copy; {{ date('Y') }}
        </div>

    </div>

</body>
</html>
