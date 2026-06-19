@extends('layouts.layout')

@section('title', 'Register Student — Student Management')
@section('description', 'Register a new student in the Student Management System.')

@section('styles')
<style>
    .form-card{width:100%;max-width:540px;background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-lg);padding:2.75rem 2.25rem 2.25rem;position:relative;overflow:hidden;margin:0 auto}
    .form-card::before{content:'';position:absolute;top:0;left:0;right:0;height:4px;background:var(--black)}
    .card-header{text-align:center;margin-bottom:2rem}
    .card-header .icon{display:inline-flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:50%;background:var(--black);margin-bottom:1rem}
    .card-header .icon svg{width:28px;height:28px;fill:var(--white)}
    .card-header h1{font-size:1.5rem;font-weight:800;letter-spacing:-.03em;line-height:1.2}
    .card-header p{margin-top:.5rem;font-size:.875rem;color:var(--g500);line-height:1.5}
    .form-group{margin-bottom:1.25rem}
    .form-group label{display:block;font-size:.8rem;font-weight:600;color:var(--g700);margin-bottom:.4rem;letter-spacing:.01em}
    .input-wrapper{position:relative}
    .input-wrapper .fi{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);width:18px;height:18px;color:var(--g400);pointer-events:none;transition:color var(--tr)}
    .input-wrapper input,.input-wrapper select{width:100%;padding:.75rem .85rem .75rem 2.75rem;font-family:inherit;font-size:.9rem;color:var(--black);background:var(--white);border:1.5px solid var(--g200);border-radius:var(--radius-sm);outline:none;transition:border-color var(--tr),box-shadow var(--tr);-webkit-appearance:none;appearance:none}
    .input-wrapper input::placeholder{color:var(--g400);font-weight:300}
    .input-wrapper input:hover,.input-wrapper select:hover{border-color:var(--g300)}
    .input-wrapper input:focus,.input-wrapper select:focus{border-color:var(--black);box-shadow:0 0 0 3px rgba(10,10,10,.08)}
    .input-wrapper:focus-within .fi{color:var(--black)}
    .sw::after{content:'';position:absolute;right:1rem;top:50%;transform:translateY(-50%);width:0;height:0;border-left:5px solid transparent;border-right:5px solid transparent;border-top:6px solid var(--g400);pointer-events:none;transition:border-color var(--tr)}
    .sw:focus-within::after{border-top-color:var(--black)}
    .form-row{display:flex;gap:1rem}
    .form-row .form-group{flex:1}
    .divider{height:1px;background:var(--g200);margin:.5rem 0 1.5rem}
    .btn-row{display:flex;gap:.75rem}
    .btn-submit{display:flex;align-items:center;justify-content:center;gap:.6rem;flex:1;padding:.9rem 1.5rem;font-family:inherit;font-size:.875rem;font-weight:600;letter-spacing:.02em;color:var(--white);background:var(--black);border:2px solid var(--black);border-radius:var(--radius-sm);cursor:pointer;transition:all var(--tr);position:relative;overflow:hidden}
    .btn-submit::before{content:'';position:absolute;inset:0;background:var(--white);transform:scaleX(0);transform-origin:right;transition:transform .35s cubic-bezier(.4,0,.2,1);z-index:0}
    .btn-submit:hover::before{transform:scaleX(1);transform-origin:left}
    .btn-submit span,.btn-submit svg{position:relative;z-index:1;transition:color var(--tr)}
    .btn-submit:hover span{color:var(--black)}
    .btn-submit:hover svg{color:var(--black);transform:translateX(3px)}
    .btn-submit svg{width:18px;height:18px;transition:color var(--tr),transform var(--tr)}
    .btn-submit:active{transform:scale(.98)}
    .btn-reset{display:flex;align-items:center;justify-content:center;gap:.5rem;padding:.9rem 1.25rem;font-family:inherit;font-size:.875rem;font-weight:500;color:var(--g600);background:var(--white);border:1.5px solid var(--g200);border-radius:var(--radius-sm);cursor:pointer;transition:all var(--tr)}
    .btn-reset:hover{border-color:var(--g400);color:var(--black)}
    .btn-reset svg{width:16px;height:16px}
    .btn-reset:active{transform:scale(.98)}
    @media(max-width:480px){.form-row{flex-direction:column;gap:0}.form-card{padding:2rem 1.25rem 1.75rem}.card-header h1{font-size:1.25rem}.btn-row{flex-direction:column}}
</style>
@endsection

@section('content')
<div class="form-card">
    <div class="card-header">
        <div class="icon">
            <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v1.2c0 .7.5 1.2 1.2 1.2h16.8c.7 0 1.2-.5 1.2-1.2v-1.2c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
        </div>
        <h1>Student Registration</h1>
        <p>Enter the student's details below to create a new record.</p>
    </div>

    <form action="{{ route('students.store') }}" method="POST" autocomplete="off" id="create-form">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <div class="input-wrapper">
                    <svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="John" required>
                </div>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <div class="input-wrapper">
                    <svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" placeholder="Doe" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-wrapper">
                <svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="john.doe@example.com" required>
            </div>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number</label>
            <div class="input-wrapper">
                <svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.13.81.36 1.6.68 2.34a2 2 0 01-.45 2.11L8.09 9.41a16 16 0 006.5 6.5l1.24-1.24a2 2 0 012.11-.45c.74.32 1.53.55 2.34.68a2 2 0 011.72 2.02z"/></svg>
                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="0771234567" pattern="^(0[0-9]{9}|[1-9][0-9]{8})$" title="Phone number must be 10 digits if starting with 0, or 9 digits otherwise." required>
            </div>
        </div>

        <div class="form-group">
            <label for="course">Course</label>
            <div class="input-wrapper sw">
                <svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
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

        <div class="btn-row">
            <button type="button" class="btn-reset" id="btn-reset">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2.5 2v6h6"/><path d="M2.5 8A10 10 0 1 1 4.93 17"/></svg>
                Reset
            </button>
            <button type="submit" class="btn-submit" id="submit-btn">
                <span>Register Student</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Reset button with SweetAlert confirmation
    document.getElementById('btn-reset').addEventListener('click', function() {
        Swal.fire({
            title: 'Reset Form?',
            text: 'All entered data will be cleared.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0a0a0a',
            cancelButtonColor: '#a3a3a3',
            confirmButtonText: 'Yes, reset it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('create-form').reset();
                Swal.fire({
                    icon: 'info',
                    title: 'Form Reset',
                    text: 'All fields have been cleared.',
                    timer: 1500,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        });
    });

    // Submit confirmation
    document.getElementById('create-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Register Student?',
            text: 'Are you sure you want to register this student?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0a0a0a',
            cancelButtonColor: '#a3a3a3',
            confirmButtonText: 'Yes, register!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.removeEventListener('submit', arguments.callee);
                form.submit();
            }
        });
    });
</script>
@endsection
