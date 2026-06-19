@extends('teacher.layouts.layout')

@section('title', 'My Profile — CodeXpress')
@section('page-title', 'My Account Profile')

@section('content')
<div style="display: grid; grid-template-columns: 1fr; gap: 2rem; max-width: 800px; margin: 0 auto;">
    
    <!-- Profile Info Card -->
    <div class="card" style="padding: 2rem; display: flex; flex-direction: column; gap: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
            <!-- Avatar Icon -->
            <div style="width: 56px; height: 56px; border-radius: 50%; background: var(--accent); color: var(--white); display: flex; align-items: center; justify-content: center; font-size: 1.35rem; font-weight: 800; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.2); flex-shrink: 0;">
                {{ substr($teacher->first_name, 0, 1) }}{{ substr($teacher->last_name, 0, 1) }}
            </div>
            <div>
                <span class="badge badge-white" style="margin-bottom: 0.4rem;">Academic Faculty</span>
                <h2 style="font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em; color: var(--black);">{{ $teacher->full_name }}</h2>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; border-top: 1px solid var(--g200); padding-top: 1.25rem;">
            <div>
                <span class="label">First Name</span>
                <div style="font-size: 0.95rem; font-weight: 600; color: var(--black); margin-top: 0.15rem;">{{ $teacher->first_name }}</div>
            </div>
            <div>
                <span class="label">Last Name</span>
                <div style="font-size: 0.95rem; font-weight: 600; color: var(--black); margin-top: 0.15rem;">{{ $teacher->last_name }}</div>
            </div>
            <div>
                <span class="label">Primary Subject</span>
                <div style="font-size: 0.95rem; font-weight: 600; color: var(--black); margin-top: 0.15rem;">{{ $teacher->subject ?? '—' }}</div>
            </div>
            <div>
                <span class="label">Teacher Email</span>
                <div style="font-size: 0.95rem; font-weight: 600; color: var(--black); margin-top: 0.15rem;">{{ $teacher->email }}</div>
            </div>
            <div>
                <span class="label">Phone Number</span>
                <div style="font-size: 0.95rem; font-weight: 600; color: var(--black); margin-top: 0.15rem;">{{ $teacher->phone_number ?? '—' }}</div>
            </div>
            <div>
                <span class="label">Active Campus</span>
                <div style="font-size: 0.95rem; font-weight: 600; color: var(--black); margin-top: 0.15rem;">{{ $teacher->tenant->name ?? '—' }}</div>
            </div>
        </div>
    </div>

    <!-- Password Change Card -->
    <div class="card" style="padding: 2rem;">
        <h3 style="font-size: 1.15rem; font-weight: 800; letter-spacing: -0.01em; margin-bottom: 0.5rem; color: var(--black);">Update Security Password</h3>
        <p style="font-size: 0.8rem; color: var(--g500); margin-bottom: 1.5rem;">Keep your academic workspace safe by choosing a secure password.</p>

        <form action="{{ route('teacher.profile.password') }}" method="POST">
            @csrf

            <!-- Current Password -->
            <div class="input-group">
                <label for="current_password" class="label">Current Password</label>
                <div style="position: relative; display: flex; align-items: center; width: 100%;">
                    <input type="password" name="current_password" id="current_password" class="input" placeholder="••••••••" required style="padding-right: 2.75rem; width: 100%;">
                    <button type="button" onclick="togglePasswordVisibility('current_password', this)" style="position: absolute; right: 0.75rem; background: none; border: none; cursor: pointer; color: var(--g500); display: flex; align-items: center; justify-content: center; padding: 0.25rem;">
                        <svg class="eye-open" style="width: 18px; height: 18px; display: block;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" style="width: 18px; height: 18px; display: none;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div class="input-group">
                <label for="new_password" class="label">New Password</label>
                <div style="position: relative; display: flex; align-items: center; width: 100%;">
                    <input type="password" name="new_password" id="new_password" class="input" placeholder="••••••••" required style="padding-right: 2.75rem; width: 100%;">
                    <button type="button" onclick="togglePasswordVisibility('new_password', this)" style="position: absolute; right: 0.75rem; background: none; border: none; cursor: pointer; color: var(--g500); display: flex; align-items: center; justify-content: center; padding: 0.25rem;">
                        <svg class="eye-open" style="width: 18px; height: 18px; display: block;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" style="width: 18px; height: 18px; display: none;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <!-- Confirm New Password -->
            <div class="input-group" style="margin-bottom: 1.75rem;">
                <label for="new_password_confirmation" class="label">Confirm New Password</label>
                <div style="position: relative; display: flex; align-items: center; width: 100%;">
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="input" placeholder="••••••••" required style="padding-right: 2.75rem; width: 100%;">
                    <button type="button" onclick="togglePasswordVisibility('new_password_confirmation', this)" style="position: absolute; right: 0.75rem; background: none; border: none; cursor: pointer; color: var(--g500); display: flex; align-items: center; justify-content: center; padding: 0.25rem;">
                        <svg class="eye-open" style="width: 18px; height: 18px; display: block;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-closed" style="width: 18px; height: 18px; display: none;" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="padding: 0.65rem 1.25rem;">
                    Update Workspace Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script>
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
@endsection
