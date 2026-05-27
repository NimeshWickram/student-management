@extends('layouts.layout')
@section('title', 'Students — EduManager')
@section('page-title', 'Students')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Home</a> / Students @endsection

@section('styles')
<style>
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem}
.page-header h1{font-size:1.5rem;font-weight:800;letter-spacing:-.03em}
.page-header p{font-size:.85rem;color:var(--g500);margin-top:.25rem}
.btn-add{display:inline-flex;align-items:center;gap:.5rem;padding:.65rem 1.25rem;font-family:inherit;font-size:.8rem;font-weight:600;color:var(--white);background:var(--black);border:2px solid var(--black);border-radius:var(--radius-sm);cursor:pointer;transition:all var(--tr);letter-spacing:.01em}
.btn-add:hover{background:var(--white);color:var(--black)}
.btn-add svg{width:16px;height:16px}
.search-bar{display:flex;gap:.75rem;margin-bottom:1.5rem;flex-wrap:wrap}
.search-input-wrap{flex:1;min-width:240px;position:relative}
.search-input-wrap svg.si{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);width:18px;height:18px;color:var(--g400);pointer-events:none}
.search-input-wrap input{width:100%;padding:.7rem .85rem .7rem 2.75rem;font-family:inherit;font-size:.875rem;border:1.5px solid var(--g200);border-radius:var(--radius-sm);outline:none;transition:border-color var(--tr),box-shadow var(--tr);background:var(--white)}
.search-input-wrap input:focus{border-color:var(--black);box-shadow:0 0 0 3px rgba(10,10,10,.08)}
.btn-clear{position:absolute;right:.6rem;top:50%;transform:translateY(-50%);padding:.3rem .6rem;font-size:.75rem;background:var(--g100);border:1px solid var(--g200);border-radius:20px;cursor:pointer;transition:all var(--tr);z-index:10}
.btn-clear:hover{background:var(--g200)}
.table-wrap{background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-lg);overflow:hidden}
table{width:100%;border-collapse:collapse}
thead{background:var(--black);color:var(--white)}
thead th{padding:.85rem 1rem;font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;text-align:left}
tbody tr{border-bottom:1px solid var(--g200);transition:background var(--tr)}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:var(--g50)}
tbody td{padding:.8rem 1rem;font-size:.85rem;color:var(--g700);vertical-align:middle}
.student-name{font-weight:600;color:var(--black)}
.badge{display:inline-block;padding:.2rem .6rem;font-size:.7rem;font-weight:600;background:var(--g100);border:1px solid var(--g200);border-radius:20px;color:var(--g700)}
.actions{display:flex;gap:.4rem;align-items:center}
.btn-edit,.btn-delete{display:inline-flex;align-items:center;gap:.3rem;padding:.35rem .7rem;font-family:inherit;font-size:.75rem;font-weight:600;border-radius:6px;transition:all var(--tr);cursor:pointer;text-decoration:none;border:1.5px solid transparent}
.btn-edit{background:var(--g100);color:var(--g700);border-color:var(--g200)}
.btn-edit:hover{background:var(--g800);color:var(--white);border-color:var(--g800)}
.btn-delete{background:var(--white);color:var(--g600);border-color:var(--g200)}
.btn-delete:hover{background:var(--black);color:var(--white);border-color:var(--black)}
.btn-edit svg,.btn-delete svg{width:13px;height:13px}
.empty-state{text-align:center;padding:4rem 2rem}
.empty-state svg{width:56px;height:56px;color:var(--g300);margin-bottom:1rem}
.empty-state h3{font-size:1.1rem;font-weight:700;color:var(--g700);margin-bottom:.35rem}
.empty-state p{font-size:.85rem;color:var(--g500)}
.count-badge{display:inline-flex;align-items:center;justify-content:center;min-width:28px;height:28px;padding:0 .5rem;background:var(--g200);border-radius:20px;font-size:.75rem;font-weight:700;color:var(--g700);margin-left:.5rem}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);z-index:1000;display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:opacity .3s ease,visibility .3s ease;padding:1rem}
.modal-overlay.active{opacity:1;visibility:visible}
.modal-container{width:100%;max-width:520px;background:var(--white);border-radius:var(--radius);box-shadow:0 24px 80px rgba(0,0,0,.2);transform:translateY(24px) scale(.97);transition:transform .35s cubic-bezier(.4,0,.2,1);overflow:hidden;max-height:90vh;overflow-y:auto}
.modal-overlay.active .modal-container{transform:translateY(0) scale(1)}
.modal-header{text-align:center;padding:2rem 2rem 1rem;position:relative}
.modal-header h2{font-size:1.35rem;font-weight:800;letter-spacing:-.03em}
.modal-header p{margin-top:.4rem;font-size:.85rem;color:var(--g500)}
.modal-icon{display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:50%;background:var(--black);margin-bottom:.75rem}
.modal-icon svg{width:26px;height:26px}
.modal-close{position:absolute;top:1rem;right:1rem;width:32px;height:32px;border:none;background:var(--g100);border-radius:50%;font-size:1.25rem;color:var(--g500);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all var(--tr)}
.modal-close:hover{background:var(--black);color:var(--white)}
.modal-body{padding:0 2rem}
.modal-body .form-group{margin-bottom:1.1rem}
.modal-body .form-group label{display:block;font-size:.8rem;font-weight:600;color:var(--g700);margin-bottom:.35rem}
.modal-body .input-wrapper{position:relative}
.modal-body .input-wrapper .fi{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);width:18px;height:18px;color:var(--g400);pointer-events:none}
.modal-body .input-wrapper input,.modal-body .input-wrapper select{width:100%;padding:.7rem .85rem .7rem 2.75rem;font-family:inherit;font-size:.875rem;border:1.5px solid var(--g200);border-radius:var(--radius-sm);outline:none;transition:border-color var(--tr),box-shadow var(--tr);-webkit-appearance:none;appearance:none}
.modal-body .input-wrapper input:focus,.modal-body .input-wrapper select:focus{border-color:var(--black);box-shadow:0 0 0 3px rgba(10,10,10,.08)}
.modal-body .input-wrapper input::placeholder{color:var(--g400)}
.modal-body .sw::after{content:'';position:absolute;right:1rem;top:50%;transform:translateY(-50%);border-left:5px solid transparent;border-right:5px solid transparent;border-top:6px solid var(--g400);pointer-events:none}
.modal-body .form-row{display:flex;gap:1rem}
.modal-body .form-row .form-group{flex:1}
.modal-footer{display:flex;gap:.75rem;padding:1.25rem 2rem 2rem;border-top:1px solid var(--g200);margin-top:.5rem}
.modal-footer .btn-reset{display:flex;align-items:center;justify-content:center;gap:.5rem;padding:.8rem 1.1rem;font-family:inherit;font-size:.85rem;font-weight:500;color:var(--g600);background:var(--white);border:1.5px solid var(--g200);border-radius:var(--radius-sm);cursor:pointer;transition:all var(--tr)}
.modal-footer .btn-reset:hover{border-color:var(--g400);color:var(--black)}
.modal-footer .btn-reset svg{width:16px;height:16px}
.modal-footer .btn-submit{display:flex;align-items:center;justify-content:center;gap:.6rem;flex:1;padding:.8rem 1.25rem;font-family:inherit;font-size:.85rem;font-weight:600;color:var(--white);background:var(--black);border:2px solid var(--black);border-radius:var(--radius-sm);cursor:pointer;transition:all var(--tr);position:relative;overflow:hidden}
.modal-footer .btn-submit:hover{background:var(--g800)}
.modal-footer .btn-submit svg{width:18px;height:18px}
.action-toolbar{display:flex;align-items:center;justify-content:flex-end;margin-bottom:1.25rem;gap:.5rem;flex-wrap:wrap}
.toolbar-btn{display:inline-flex;align-items:center;gap:.4rem;padding:.5rem .9rem;font-family:inherit;font-size:.78rem;font-weight:600;border-radius:6px;cursor:pointer;transition:all var(--tr);text-decoration:none;border:1.5px solid transparent}
.toolbar-btn svg{width:15px;height:15px}
.btn-excel{background:#ecfdf5;color:#059669;border-color:#a7f3d0}
.btn-excel:hover{background:#059669;color:var(--white);border-color:#059669}
.btn-pdf{background:#fef2f2;color:#dc2626;border-color:#fecaca}
.btn-pdf:hover{background:#dc2626;color:var(--white);border-color:#dc2626}
.btn-import{background:#eff6ff;color:#2563eb;border-color:#bfdbfe}
.btn-import:hover{background:#2563eb;color:var(--white);border-color:#2563eb}
@media(max-width:640px){
.page-header{flex-direction:column;align-items:flex-start}
.table-wrap{overflow-x:auto}table{min-width:700px}
.modal-body .form-row{flex-direction:column;gap:0}
.modal-body{padding:0 1.25rem}.modal-header{padding:1.5rem 1.25rem .75rem}
.modal-footer{padding:1rem 1.25rem 1.5rem;flex-direction:column}
.toolbar-btn{flex:1;justify-content:center;font-size:.72rem;padding:.45rem .6rem}
}
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>Student Dashboard <span class="count-badge">{{ $students->total() }}</span></h1>
        <p>Manage and oversee all registered students.</p>
    </div>
    <button type="button" class="btn-add" id="btn-add-student" onclick="document.getElementById('add-student-modal').classList.add('active')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Student
    </button>
</div>

<form action="{{ route('students.index') }}" method="GET" class="search-bar" id="search-form">
    <div class="search-input-wrap">
        <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Search by name, email, phone, course…" autocomplete="off">
        <span class="btn-clear" id="btn-clear-search" style="display:none">&times;</span>
    </div>
</form>

<div class="action-toolbar">
    <a href="{{ route('students.export.excel', ['search' => request('search')]) }}" class="toolbar-btn btn-excel" id="btn-export-excel">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Excel
    </a>
    <a href="{{ route('students.export.pdf', ['search' => request('search')]) }}" class="toolbar-btn btn-pdf" id="btn-export-pdf">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        PDF
    </a>
    <button type="button" class="toolbar-btn btn-import" id="btn-import-trigger" onclick="openImportModal()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Import
    </button>
</div>

<form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" id="import-form" style="display:none">
    @csrf
    <input type="file" name="file" id="import-file-input" accept=".csv,.txt,.xlsx,.xls">
</form>


<div class="table-wrap">
    @if($students->count())
    <table id="students-table">
        <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Course</th><th>Grade</th><th>Registered</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($students as $index => $student)
            <tr id="student-row-{{ $student->id }}">
                <td>{{ $students->firstItem() + $index }}</td>
                <td class="student-name">{{ $student->first_name }} {{ $student->last_name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->phone_number }}</td>
                <td><span class="badge">{{ $student->course }}</span></td>
                <td><span class="badge" style="background:#f5f5f5;color:#171717;border:1px solid #e5e5e5;font-weight:600">{{ $student->grade }}</span></td>
                <td>{{ $student->created_at->format('d M Y') }}</td>
                <td>
                    <div class="actions">
                        <button type="button" class="btn-edit" onclick="openEditModal({{ $student->id }}, {{ json_encode($student->first_name) }}, {{ json_encode($student->last_name) }}, {{ json_encode($student->email) }}, {{ json_encode($student->phone_number) }}, {{ json_encode($student->course) }}, {{ json_encode($student->grade) }})">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            Edit
                        </button>
                        <form action="{{ route('students.destroy', $student) }}" method="POST" class="delete-form">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        <h3>No students found</h3>
        <p>{{ request('search') ? 'Try adjusting your search.' : 'Get started by adding your first student.' }}</p>
    </div>
    @endif
</div>

@if($students->hasPages())
<div style="margin-top:1.25rem;display:flex;justify-content:center">{{ $students->appends(request()->query())->links() }}</div>
@endif

{{-- Add Student Modal --}}
<div class="modal-overlay" id="add-student-modal">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-icon"><svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v1.2c0 .7.5 1.2 1.2 1.2h16.8c.7 0 1.2-.5 1.2-1.2v-1.2c0-3.2-6.4-4.8-9.6-4.8z" fill="var(--white)"/></svg></div>
            <h2>Student Registration</h2>
            <p>Enter the student's details below.</p>
            <button type="button" class="modal-close" onclick="closeModal('add-student-modal')">&times;</button>
        </div>
        <form action="{{ route('students.store') }}" method="POST" autocomplete="off" id="modal-create-form">
            @csrf
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group"><label>First Name</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><input type="text" name="first_name" placeholder="John" required></div></div>
                    <div class="form-group"><label>Last Name</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><input type="text" name="last_name" placeholder="Doe" required></div></div>
                </div>
                <div class="form-group"><label>Email</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg><input type="email" name="email" placeholder="john@example.com" required></div></div>
                <div class="form-group"><label>Phone</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.13.81.36 1.6.68 2.34a2 2 0 01-.45 2.11L8.09 9.41a16 16 0 006.5 6.5l1.24-1.24a2 2 0 012.11-.45c.74.32 1.53.55 2.34.68a2 2 0 011.72 2.02z"/></svg><input type="text" name="phone_number" placeholder="0771234567" pattern="^(0[0-9]{9}|[1-9][0-9]{8})$" title="Phone number must be 10 digits if starting with 0, or 9 digits otherwise." required></div></div>
                <div class="form-group"><label>Course</label><div class="input-wrapper sw"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg><select name="course" required><option value="" disabled selected>Select a course…</option><option>Primary Education</option><option>Junior Secondary</option><option>Ordinary Level</option></select></div></div>
                <div class="form-group"><label>Grade</label><div class="input-wrapper sw"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg><select name="grade" required><option value="" disabled selected>Select a grade…</option><option>Grade 1</option><option>Grade 2</option><option>Grade 3</option><option>Grade 4</option><option>Grade 5</option><option>Grade 6</option><option>Grade 7</option><option>Grade 8</option><option>Grade 9</option><option>Grade 10</option><option>Grade 11</option></select></div></div>
                <div class="form-group"><label>Password</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg><input type="text" name="password" placeholder="Leave blank to use default (student123)" minlength="6"></div></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-reset" onclick="this.closest('form').reset()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2.5 2v6h6"/><path d="M2.5 8A10 10 0 1 1 4.93 17"/></svg>Reset</button>
                <button type="submit" class="btn-submit"><span>Register Student</span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Student Modal --}}
<div class="modal-overlay" id="edit-student-modal">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-icon"><svg viewBox="0 0 24 24" fill="none" stroke="var(--white)" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
            <h2>Edit Student</h2>
            <p>Update the student's information.</p>
            <button type="button" class="modal-close" onclick="closeModal('edit-student-modal')">&times;</button>
        </div>
        <form method="POST" autocomplete="off" id="modal-edit-form">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group"><label>First Name</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><input type="text" name="first_name" id="edit_first_name" required></div></div>
                    <div class="form-group"><label>Last Name</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg><input type="text" name="last_name" id="edit_last_name" required></div></div>
                </div>
                <div class="form-group"><label>Email</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg><input type="email" name="email" id="edit_email" required></div></div>
                <div class="form-group"><label>Phone</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.13.81.36 1.6.68 2.34a2 2 0 01-.45 2.11L8.09 9.41a16 16 0 006.5 6.5l1.24-1.24a2 2 0 012.11-.45c.74.32 1.53.55 2.34.68a2 2 0 011.72 2.02z"/></svg><input type="text" name="phone_number" id="edit_phone" pattern="^(0[0-9]{9}|[1-9][0-9]{8})$" title="Phone number must be 10 digits if starting with 0, or 9 digits otherwise." required></div></div>
                <div class="form-group"><label>Course</label><div class="input-wrapper sw"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg><select name="course" id="edit_course" required><option value="" disabled>Select a course…</option><option>Primary Education</option><option>Junior Secondary</option><option>Ordinary Level</option></select></div></div>
                <div class="form-group"><label>Grade</label><div class="input-wrapper sw"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg><select name="grade" id="edit_grade" required><option value="" disabled>Select a grade…</option><option>Grade 1</option><option>Grade 2</option><option>Grade 3</option><option>Grade 4</option><option>Grade 5</option><option>Grade 6</option><option>Grade 7</option><option>Grade 8</option><option>Grade 9</option><option>Grade 10</option><option>Grade 11</option></select></div></div>
                <div class="form-group"><label>New Password</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg><input type="text" name="password" id="edit_password" placeholder="Leave blank to keep unchanged" minlength="6"></div></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-reset" onclick="closeModal('edit-student-modal')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>Cancel</button>
                <button type="submit" class="btn-submit"><span>Update Student</span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function closeModal(id){document.getElementById(id).classList.remove('active')}
function openEditModal(id,fn,ln,em,ph,co,gr){
    var f=document.getElementById('modal-edit-form');
    f.action='/students/'+id;
    document.getElementById('edit_first_name').value=fn;
    document.getElementById('edit_last_name').value=ln;
    document.getElementById('edit_email').value=em;
    document.getElementById('edit_phone').value=ph;
    document.getElementById('edit_course').value=co;
    document.getElementById('edit_grade').value=gr;
    document.getElementById('edit_password').value='';
    document.getElementById('edit-student-modal').classList.add('active');
}
// Close on overlay click
document.querySelectorAll('.modal-overlay').forEach(function(m){
    m.addEventListener('click',function(e){if(e.target===m)m.classList.remove('active')});
});
// ESC to close
document.addEventListener('keydown',function(e){if(e.key==='Escape')document.querySelectorAll('.modal-overlay.active').forEach(function(m){m.classList.remove('active')})});
// Auto-open if #add
if(window.location.hash==='#add')document.getElementById('add-student-modal').classList.add('active');
// Search filter
(function(){
    var si=document.getElementById('search-input'),cb=document.getElementById('btn-clear-search'),tb=document.querySelector('#students-table tbody'),sf=document.getElementById('search-form');
    function tc(){cb.style.display=si.value.trim().length>0?'inline-flex':'none'}tc();
    cb.addEventListener('click',function(){si.value='';tc();if(new URLSearchParams(window.location.search).has('search'))window.location.href='{{ route("students.index") }}';else if(tb)tb.querySelectorAll('tr').forEach(function(r){r.style.display=''});si.focus()});
    si.addEventListener('input',function(){var q=this.value.trim().toLowerCase();tc();if(tb)tb.querySelectorAll('tr').forEach(function(r){r.style.display=r.textContent.toLowerCase().includes(q)?'':'none'})});
    sf.addEventListener('submit',function(e){e.preventDefault()});
})();
// Delete confirm
document.querySelectorAll('.delete-form').forEach(function(f){f.addEventListener('submit',function(e){e.preventDefault();var df=this;Swal.fire({title:'Delete Student?',text:'This cannot be undone.',icon:'warning',showCancelButton:true,confirmButtonColor:'#0a0a0a',cancelButtonColor:'#a3a3a3',confirmButtonText:'Yes, delete!'}).then(function(r){if(r.isConfirmed)df.submit()})})});
// Submit confirms
document.getElementById('modal-create-form').addEventListener('submit',function(e){e.preventDefault();var f=this;Swal.fire({title:'Register Student?',icon:'question',showCancelButton:true,confirmButtonColor:'#0a0a0a',confirmButtonText:'Yes, register!'}).then(function(r){if(r.isConfirmed)f.submit()})});
document.getElementById('modal-edit-form').addEventListener('submit',function(e){e.preventDefault();var f=this;Swal.fire({title:'Update Student?',icon:'question',showCancelButton:true,confirmButtonColor:'#0a0a0a',confirmButtonText:'Yes, update!'}).then(function(r){if(r.isConfirmed)f.submit()})});

// Import modal via SweetAlert
function openImportModal(){
    Swal.fire({
        title:'Import Students',
        html:'<div style="text-align:left;font-size:.85rem;color:#525252;margin-bottom:1rem">' +
             '<p style="margin-bottom:.75rem">Upload a <strong>CSV file</strong> with the following columns:</p>' +
             '<div style="background:#f5f5f5;border:1px solid #e5e5e5;border-radius:8px;padding:.75rem 1rem;font-family:monospace;font-size:.78rem;color:#0a0a0a;margin-bottom:.75rem">' +
             'First Name, Last Name, Email, Phone Number, Course, Grade</div>' +
             '<p style="font-size:.78rem;color:#a3a3a3">• First row should be headers<br>• Duplicate emails will be skipped<br>• Grade should be from "Grade 1" to "Grade 11"<br>• Max file size: 10MB</p></div>' +
             '<input type="file" id="swal-import-file" accept=".csv,.txt,.xlsx,.xls" style="width:100%;padding:.6rem;border:1.5px dashed #d4d4d4;border-radius:8px;font-family:inherit;font-size:.85rem;cursor:pointer;background:#fafafa">',
        showCancelButton:true,
        confirmButtonText:'<svg style="width:16px;height:16px;margin-right:6px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> Import',
        cancelButtonText:'Cancel',
        confirmButtonColor:'#2563eb',
        cancelButtonColor:'#a3a3a3',
        customClass:{popup:'swal-import-popup'},
        preConfirm:function(){
            var fileInput=document.getElementById('swal-import-file');
            if(!fileInput.files.length){
                Swal.showValidationMessage('Please select a file to import');
                return false;
            }
            return fileInput.files[0];
        }
    }).then(function(result){
        if(result.isConfirmed && result.value){
            var dt=new DataTransfer();
            dt.items.add(result.value);
            document.getElementById('import-file-input').files=dt.files;
            document.getElementById('import-form').submit();
            Swal.fire({title:'Importing...',text:'Please wait while we process your file.',allowOutsideClick:false,showConfirmButton:false,didOpen:function(){Swal.showLoading()}});
        }
    });
}

// Update export link dynamically when search changes
(function(){
    var si=document.getElementById('search-input');
    var excelBtn=document.getElementById('btn-export-excel');
    var pdfBtn=document.getElementById('btn-export-pdf');
    var baseExcel='{{ route("students.export.excel") }}';
    var basePdf='{{ route("students.export.pdf") }}';

    function updateLinks(){
        var q=si.value.trim();
        excelBtn.href=q?baseExcel+'?search='+encodeURIComponent(q):baseExcel;
        pdfBtn.href=q?basePdf+'?search='+encodeURIComponent(q):basePdf;
    }
    si.addEventListener('input',updateLinks);
    updateLinks();
})();
</script>
@endsection
