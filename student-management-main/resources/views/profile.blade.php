@extends('layouts.layout')

@section('title', 'My Profile — CodeXpress')
@section('page-title', 'My Account Profile')

@section('content')
<div style="display: grid; grid-template-columns: 1fr; gap: 2rem; max-width: 800px; margin: 0 auto;">
    
    <!-- Profile Info Card -->
    <div class="card" style="background: var(--white); border: 1px solid var(--g200); border-radius: var(--radius); padding: 2rem; display: flex; flex-direction: column; gap: 1.5rem; box-shadow: var(--shadow-lg);">
        <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            <!-- Avatar Icon -->
            <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--accent); color: var(--white); display: flex; align-items: center; justify-content: center; font-size: 1.35rem; font-weight: 800; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2); flex-shrink: 0;">
                {{ substr(strtoupper($admin->name), 0, 1) }}
            </div>
            <div>
                <span class="badge" style="background: var(--accent-glow); color: var(--accent); border: 1px solid rgba(79, 70, 229, 0.15); padding: 0.25rem 0.75rem; border-radius: 30px; font-size: 0.75rem; font-weight: 700; display: inline-block; margin-bottom: 0.4rem;">
                    Administrator Portal
                </span>
                <h2 style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em; color: var(--black); margin: 0;">{{ $admin->name }}</h2>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; border-top: 1px solid var(--g200); padding-top: 1.25rem;">
            <div>
                <span style="font-size: 0.75rem; color: var(--g500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Full Name</span>
                <div style="font-size: 0.95rem; font-weight: 600; color: var(--black); margin-top: 0.15rem;">{{ $admin->name }}</div>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--g500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Registered Email</span>
                <div style="font-size: 0.95rem; font-weight: 600; color: var(--black); margin-top: 0.15rem; word-break: break-all;">{{ $admin->email }}</div>
            </div>
            <div>
                <span style="font-size: 0.75rem; color: var(--g500); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">Account Type</span>
                <div style="font-size: 0.95rem; font-weight: 600; color: var(--black); margin-top: 0.15rem;">System Administrator</div>
            </div>
        </div>
    </div>

    <!-- Password Change Card -->
    <div class="card" style="background: var(--white); border: 1px solid var(--g200); border-radius: var(--radius); padding: 2rem; box-shadow: var(--shadow-lg);">
        <h3 style="font-size: 1.15rem; font-weight: 800; letter-spacing: -0.01em; margin-bottom: 0.5rem; color: var(--black); margin-top: 0;">Update Security Password</h3>
        <p style="font-size: 0.8rem; color: var(--g500); margin-bottom: 1.5rem; margin-top: 0;">Keep your administrator portal workspace safe by choosing a secure password.</p>

        <form action="{{ route('admin.profile.password') }}" method="POST">
            @csrf

            <!-- Current Password -->
            <div style="margin-bottom: 1.25rem;">
                <label for="current_password" style="font-size: 0.75rem; color: var(--g600); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">Current Password</label>
                <div style="position: relative; display: flex; align-items: center; width: 100%;">
                    <input type="password" name="current_password" id="current_password" required style="width: 100%; padding: 0.65rem 2.75rem 0.65rem 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--g200); font-size: 0.9rem; background: var(--g50); color: var(--black); outline: none; transition: border-color var(--tr);">
                    <button type="button" onclick="togglePasswordVisibility('current_password', this)" style="position: absolute; right: 0.75rem; background: none; border: none; cursor: pointer; color: var(--g500); display: flex; align-items: center; justify-content: center; padding: 0.25rem;">
                        <svg class="eye-open" style="width: 18px; height: 18px; display: block;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" style="width: 18px; height: 18px; display: none;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div style="margin-bottom: 1.25rem;">
                <label for="new_password" style="font-size: 0.75rem; color: var(--g600); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">New Password</label>
                <div style="position: relative; display: flex; align-items: center; width: 100%;">
                    <input type="password" name="new_password" id="new_password" required style="width: 100%; padding: 0.65rem 2.75rem 0.65rem 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--g200); font-size: 0.9rem; background: var(--g50); color: var(--black); outline: none; transition: border-color var(--tr);">
                    <button type="button" onclick="togglePasswordVisibility('new_password', this)" style="position: absolute; right: 0.75rem; background: none; border: none; cursor: pointer; color: var(--g500); display: flex; align-items: center; justify-content: center; padding: 0.25rem;">
                        <svg class="eye-open" style="width: 18px; height: 18px; display: block;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" style="width: 18px; height: 18px; display: none;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <!-- Confirm New Password -->
            <div style="margin-bottom: 1.75rem;">
                <label for="new_password_confirmation" style="font-size: 0.75rem; color: var(--g600); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">Confirm New Password</label>
                <div style="position: relative; display: flex; align-items: center; width: 100%;">
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" required style="width: 100%; padding: 0.65rem 2.75rem 0.65rem 0.85rem; border-radius: var(--radius-sm); border: 1px solid var(--g200); font-size: 0.9rem; background: var(--g50); color: var(--black); outline: none; transition: border-color var(--tr);">
                    <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', this)" style="position: absolute; right: 0.75rem; background: none; border: none; cursor: pointer; color: var(--g500); display: flex; align-items: center; justify-content: center; padding: 0.25rem;">
                        <svg class="eye-open" style="width: 18px; height: 18px; display: block;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" style="width: 18px; height: 18px; display: none;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" style="background: var(--black); color: var(--white); border: none; padding: 0.75rem 1.5rem; border-radius: var(--radius-sm); font-family: inherit; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: background var(--tr); display: flex; align-items: center; gap: 0.5rem;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.style.display = 'none';
            eyeClosed.style.display = 'block';
        } else {
            input.type = 'password';
            eyeOpen.style.display = 'block';
            eyeClosed.style.display = 'none';
        }
    }
</script>
@endsection
