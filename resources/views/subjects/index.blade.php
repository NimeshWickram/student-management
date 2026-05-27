@extends('layouts.layout')
@section('title', 'Subjects — EduManager')
@section('page-title', 'Subjects')
@section('breadcrumb')<a href="{{ route('dashboard') }}">Home</a> / Subjects @endsection

@section('styles')
<style>
.page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.75rem;flex-wrap:wrap;gap:1rem}
.page-header h1{font-size:1.5rem;font-weight:800;letter-spacing:-.03em}
.page-header p{font-size:.85rem;color:var(--g500);margin-top:.25rem}
.btn-add{display:inline-flex;align-items:center;gap:.5rem;padding:.65rem 1.25rem;font-family:inherit;font-size:.8rem;font-weight:600;color:var(--white);background:var(--black);border:2px solid var(--black);border-radius:var(--radius-sm);cursor:pointer;transition:all var(--tr)}
.btn-add:hover{background:var(--white);color:var(--black)}
.btn-add svg{width:16px;height:16px}
.search-bar{display:flex;gap:.75rem;margin-bottom:1.5rem}.search-input-wrap{flex:1;min-width:240px;position:relative}
.search-input-wrap svg.si{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);width:18px;height:18px;color:var(--g400);pointer-events:none}
.search-input-wrap input{width:100%;padding:.7rem .85rem .7rem 2.75rem;font-family:inherit;font-size:.875rem;border:1.5px solid var(--g200);border-radius:var(--radius-sm);outline:none;transition:border-color var(--tr),box-shadow var(--tr);background:var(--white)}
.search-input-wrap input:focus{border-color:var(--black);box-shadow:0 0 0 3px rgba(10,10,10,.08)}
.btn-clear{position:absolute;right:.6rem;top:50%;transform:translateY(-50%);padding:.3rem .6rem;font-size:.75rem;background:var(--g100);border:1px solid var(--g200);border-radius:20px;cursor:pointer}
.suggestions-dropdown{position:absolute;top:100%;left:0;right:0;background:var(--white);border:1.5px solid var(--g200);border-top:none;border-radius:0 0 var(--radius-sm) var(--radius-sm);box-shadow:0 8px 32px rgba(0,0,0,.1);z-index:200;max-height:320px;overflow-y:auto;display:none}
.suggestions-dropdown.active{display:block}
.sg-item{display:flex;align-items:center;gap:.75rem;padding:.7rem 1rem;cursor:pointer;transition:background var(--tr);border-bottom:1px solid var(--g100);font-size:.85rem;color:var(--g700)}
.sg-item:last-child{border-bottom:none}
.sg-item:hover,.sg-item.active{background:var(--g50)}
.sg-item .sg-icon{flex-shrink:0;width:32px;height:32px;border-radius:50%;background:var(--g100);display:flex;align-items:center;justify-content:center}
.sg-item .sg-icon svg{width:15px;height:15px;stroke:var(--g500);fill:none;stroke-width:2}
.sg-item .sg-info{flex:1;min-width:0}
.sg-item .sg-name{font-weight:600;color:var(--black);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sg-item .sg-meta{font-size:.75rem;color:var(--g400);margin-top:.1rem}
.sg-item .sg-field{display:inline-block;padding:.1rem .45rem;font-size:.65rem;font-weight:600;background:rgba(245,158,11,.1);border-radius:10px;color:#d97706;margin-left:auto;flex-shrink:0;text-transform:uppercase;letter-spacing:.03em}
.sg-empty{padding:1.25rem;text-align:center;font-size:.8rem;color:var(--g400)}
.sg-loading{padding:1.25rem;text-align:center;font-size:.8rem;color:var(--g400)}
.table-wrap{background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-lg);overflow:hidden}
table{width:100%;border-collapse:collapse}
thead{background:var(--black);color:var(--white)}
thead th{padding:.85rem 1rem;font-size:.75rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;text-align:left}
tbody tr{border-bottom:1px solid var(--g200);transition:background var(--tr)}
tbody tr:last-child{border-bottom:none}
tbody tr:hover{background:var(--g50)}
tbody td{padding:.8rem 1rem;font-size:.85rem;color:var(--g700)}
.s-name{font-weight:600;color:var(--black)}
.badge-code{display:inline-block;padding:.2rem .6rem;font-size:.7rem;font-weight:700;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.2);border-radius:20px;color:#d97706;font-family:monospace;letter-spacing:.03em}
.badge-credits{display:inline-block;padding:.2rem .5rem;font-size:.7rem;font-weight:600;background:var(--g100);border-radius:20px;color:var(--g600)}
.desc-cell{max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.actions{display:flex;gap:.4rem}
.btn-edit,.btn-delete{display:inline-flex;align-items:center;gap:.3rem;padding:.35rem .7rem;font-family:inherit;font-size:.75rem;font-weight:600;border-radius:6px;cursor:pointer;border:1.5px solid transparent;transition:all var(--tr)}
.btn-edit{background:var(--g100);color:var(--g700);border-color:var(--g200)}
.btn-edit:hover{background:var(--g800);color:var(--white)}
.btn-delete{background:var(--white);color:var(--g600);border-color:var(--g200)}
.btn-delete:hover{background:var(--black);color:var(--white)}
.btn-edit svg,.btn-delete svg{width:13px;height:13px}
.count-badge{display:inline-flex;align-items:center;min-width:28px;height:28px;padding:0 .5rem;background:var(--g200);border-radius:20px;font-size:.75rem;font-weight:700;color:var(--g700);margin-left:.5rem}
.empty-state{text-align:center;padding:4rem 2rem}
.empty-state svg{width:56px;height:56px;color:var(--g300);margin-bottom:1rem}
.empty-state h3{font-size:1.1rem;font-weight:700;margin-bottom:.35rem}
.empty-state p{font-size:.85rem;color:var(--g500)}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);z-index:1000;display:flex;align-items:center;justify-content:center;opacity:0;visibility:hidden;transition:opacity .3s,visibility .3s;padding:1rem}
.modal-overlay.active{opacity:1;visibility:visible}
.modal-container{width:100%;max-width:520px;background:var(--white);border-radius:var(--radius);box-shadow:0 24px 80px rgba(0,0,0,.2);transform:translateY(24px) scale(.97);transition:transform .35s cubic-bezier(.4,0,.2,1);max-height:90vh;overflow-y:auto}
.modal-overlay.active .modal-container{transform:translateY(0) scale(1)}
.modal-header{text-align:center;padding:2rem 2rem 1rem;position:relative}
.modal-header h2{font-size:1.35rem;font-weight:800}
.modal-header p{margin-top:.4rem;font-size:.85rem;color:var(--g500)}
.modal-icon{display:inline-flex;align-items:center;justify-content:center;width:52px;height:52px;border-radius:50%;background:#f59e0b;margin-bottom:.75rem}
.modal-icon svg{width:26px;height:26px}
.modal-close{position:absolute;top:1rem;right:1rem;width:32px;height:32px;border:none;background:var(--g100);border-radius:50%;font-size:1.25rem;color:var(--g500);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all var(--tr)}
.modal-close:hover{background:var(--black);color:var(--white)}
.modal-body{padding:0 2rem}
.modal-body .form-group{margin-bottom:1.1rem}
.modal-body .form-group label{display:block;font-size:.8rem;font-weight:600;color:var(--g700);margin-bottom:.35rem}
.modal-body .input-wrapper{position:relative}
.modal-body .input-wrapper .fi{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);width:18px;height:18px;color:var(--g400);pointer-events:none}
.modal-body .input-wrapper input,.modal-body .input-wrapper select,.modal-body .input-wrapper textarea{width:100%;padding:.7rem .85rem .7rem 2.75rem;font-family:inherit;font-size:.875rem;border:1.5px solid var(--g200);border-radius:var(--radius-sm);outline:none;transition:border-color var(--tr);-webkit-appearance:none;appearance:none}
.modal-body .input-wrapper textarea{min-height:80px;resize:vertical;padding-top:.75rem}
.modal-body .input-wrapper input:focus,.modal-body .input-wrapper select:focus,.modal-body .input-wrapper textarea:focus{border-color:var(--black);box-shadow:0 0 0 3px rgba(10,10,10,.08)}
.modal-body .input-wrapper input::placeholder,.modal-body .input-wrapper textarea::placeholder{color:var(--g400)}
.modal-body .form-row{display:flex;gap:1rem}
.modal-body .form-row .form-group{flex:1}
.modal-footer{display:flex;gap:.75rem;padding:1.25rem 2rem 2rem;border-top:1px solid var(--g200);margin-top:.5rem}
.modal-footer .btn-reset{display:flex;align-items:center;gap:.5rem;padding:.8rem 1.1rem;font-family:inherit;font-size:.85rem;font-weight:500;color:var(--g600);background:var(--white);border:1.5px solid var(--g200);border-radius:var(--radius-sm);cursor:pointer;transition:all var(--tr)}
.modal-footer .btn-reset svg{width:16px;height:16px}
.modal-footer .btn-submit{display:flex;align-items:center;gap:.6rem;flex:1;padding:.8rem 1.25rem;font-family:inherit;font-size:.85rem;font-weight:600;color:var(--white);background:var(--black);border:2px solid var(--black);border-radius:var(--radius-sm);cursor:pointer;transition:all var(--tr);justify-content:center}
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
@media(max-width:640px){.page-header{flex-direction:column;align-items:flex-start}.table-wrap{overflow-x:auto}table{min-width:600px}.modal-body .form-row{flex-direction:column;gap:0}.modal-body{padding:0 1.25rem}.modal-footer{flex-direction:column}.toolbar-btn{flex:1;justify-content:center;font-size:.72rem;padding:.45rem .6rem}}
</style>
@endsection

@section('content')
<div class="page-header">
    <div><h1>Subjects <span class="count-badge">{{ $subjects->total() }}</span></h1><p>Manage all courses and subjects.</p></div>
    <button type="button" class="btn-add" onclick="document.getElementById('add-subject-modal').classList.add('active')">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Add Subject
    </button>
</div>

<form action="{{ route('subjects.index') }}" method="GET" class="search-bar" id="search-form">
    <div class="search-input-wrap">
        <svg class="si" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" name="search" id="search-input" value="{{ request('search') }}" placeholder="Search subjects…" autocomplete="off">
        <span class="btn-clear" id="btn-clear" style="display:none">&times;</span>
        <div class="suggestions-dropdown" id="suggestions-dropdown"></div>
    </div>
</form>

<div class="action-toolbar">
    <a href="{{ route('subjects.export.excel', ['search' => request('search')]) }}" class="toolbar-btn btn-excel" id="btn-export-excel">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Excel
    </a>
    <a href="{{ route('subjects.export.pdf', ['search' => request('search')]) }}" class="toolbar-btn btn-pdf" id="btn-export-pdf">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        PDF
    </a>
    <button type="button" class="toolbar-btn btn-import" id="btn-import-trigger" onclick="openImportModal()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        Import
    </button>
</div>

<form action="{{ route('subjects.import') }}" method="POST" enctype="multipart/form-data" id="import-form" style="display:none">
    @csrf
    <input type="file" name="file" id="import-file-input" accept=".csv,.txt,.xlsx,.xls">
</form>

<div class="table-wrap">
@if($subjects->count())
<table id="subjects-table"><thead><tr><th>#</th><th>Name</th><th>Code</th><th>Description</th><th>Credits</th><th>Added</th><th>Actions</th></tr></thead>
<tbody>
@foreach($subjects as $i => $s)
<tr>
    <td>{{ $subjects->firstItem()+$i }}</td>
    <td class="s-name">{{ $s->name }}</td>
    <td><span class="badge-code">{{ $s->code }}</span></td>
    <td class="desc-cell">{{ $s->description ?: '—' }}</td>
    <td><span class="badge-credits">{{ $s->credits }} cr</span></td>
    <td>{{ $s->created_at->format('d M Y') }}</td>
    <td><div class="actions">
        <button type="button" class="btn-edit" onclick="openEditSubject({{ $s->id }},{{ json_encode($s->name) }},{{ json_encode($s->code) }},{{ json_encode($s->description) }},{{ $s->credits }})"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>Edit</button>
        <form action="{{ route('subjects.destroy',$s) }}" method="POST" class="delete-form">@csrf @method('DELETE')<button type="submit" class="btn-delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>Delete</button></form>
    </div></td>
</tr>
@endforeach
</tbody></table>
@else
<div class="empty-state"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg><h3>No subjects found</h3><p>{{ request('search') ? 'Try adjusting your search.' : 'Add your first subject.' }}</p></div>
@endif
</div>

@if($subjects->hasPages())<div style="margin-top:1.25rem;display:flex;justify-content:center">{{ $subjects->appends(request()->query())->links() }}</div>@endif

{{-- Add Subject Modal --}}
<div class="modal-overlay" id="add-subject-modal">
<div class="modal-container"><div class="modal-header">
<div class="modal-icon"><svg viewBox="0 0 24 24" fill="none" stroke="var(--white)" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg></div>
<h2>Add Subject</h2><p>Enter subject details below.</p>
<button type="button" class="modal-close" onclick="closeModal('add-subject-modal')">&times;</button></div>
<form action="{{ route('subjects.store') }}" method="POST" autocomplete="off" id="add-subject-form">@csrf
<div class="modal-body">
<div class="form-row">
<div class="form-group"><label>Subject Name</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg><input type="text" name="name" placeholder="Computer Science" required></div></div>
<div class="form-group"><label>Code</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 18l6-6-6-6"/><path d="M8 6l-6 6 6 6"/></svg><input type="text" name="code" placeholder="CS101" required></div></div>
</div>
<div class="form-group"><label>Description</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="top:1rem;transform:none"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg><textarea name="description" placeholder="Brief description…"></textarea></div></div>
<div class="form-group"><label>Credits</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg><input type="number" name="credits" value="3" min="1" max="10" required></div></div>
</div>
<div class="modal-footer">
<button type="button" class="btn-reset" onclick="this.closest('form').reset()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2.5 2v6h6"/><path d="M2.5 8A10 10 0 1 1 4.93 17"/></svg>Reset</button>
<button type="submit" class="btn-submit"><span>Add Subject</span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></button>
</div></form></div></div>

{{-- Edit Subject Modal --}}
<div class="modal-overlay" id="edit-subject-modal">
<div class="modal-container"><div class="modal-header">
<div class="modal-icon" style="background:var(--black)"><svg viewBox="0 0 24 24" fill="none" stroke="var(--white)" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
<h2>Edit Subject</h2><p>Update subject information.</p>
<button type="button" class="modal-close" onclick="closeModal('edit-subject-modal')">&times;</button></div>
<form method="POST" autocomplete="off" id="edit-subject-form">@csrf @method('PUT')
<div class="modal-body">
<div class="form-row">
<div class="form-group"><label>Subject Name</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg><input type="text" name="name" id="es_name" required></div></div>
<div class="form-group"><label>Code</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 18l6-6-6-6"/><path d="M8 6l-6 6 6 6"/></svg><input type="text" name="code" id="es_code" required></div></div>
</div>
<div class="form-group"><label>Description</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="top:1rem;transform:none"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg><textarea name="description" id="es_desc"></textarea></div></div>
<div class="form-group"><label>Credits</label><div class="input-wrapper"><svg class="fi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg><input type="number" name="credits" id="es_credits" min="1" max="10" required></div></div>
</div>
<div class="modal-footer">
<button type="button" class="btn-reset" onclick="closeModal('edit-subject-modal')"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>Cancel</button>
<button type="submit" class="btn-submit"><span>Update Subject</span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></button>
</div></form></div></div>
@endsection

@section('scripts')
<script>
function closeModal(id){document.getElementById(id).classList.remove('active')}
function openEditSubject(id,name,code,desc,credits){
    document.getElementById('edit-subject-form').action='/subjects/'+id;
    document.getElementById('es_name').value=name;document.getElementById('es_code').value=code;
    document.getElementById('es_desc').value=desc||'';document.getElementById('es_credits').value=credits;
    document.getElementById('edit-subject-modal').classList.add('active');
}
document.querySelectorAll('.modal-overlay').forEach(function(m){m.addEventListener('click',function(e){if(e.target===m)m.classList.remove('active')})});
document.addEventListener('keydown',function(e){if(e.key==='Escape')document.querySelectorAll('.modal-overlay.active').forEach(function(m){m.classList.remove('active')})});
if(window.location.hash==='#add')document.getElementById('add-subject-modal').classList.add('active');
(function(){
    var si=document.getElementById('search-input'),cb=document.getElementById('btn-clear'),tb=document.querySelector('#subjects-table tbody'),sf=document.getElementById('search-form'),dd=document.getElementById('suggestions-dropdown');
    var debounce=null,activeIdx=-1;
    function tc(){cb.style.display=si.value.trim().length>0?'inline-flex':'none'}tc();
    function esc(t){var d=document.createElement('div');d.appendChild(document.createTextNode(t));return d.innerHTML}
    function closeSg(){dd.classList.remove('active');dd.innerHTML='';activeIdx=-1}
    cb.addEventListener('click',function(){si.value='';tc();closeSg();if(new URLSearchParams(window.location.search).has('search'))window.location.href='{{ route("subjects.index") }}';else if(tb)tb.querySelectorAll('tr').forEach(function(r){r.style.display=''});si.focus()});
    si.addEventListener('input',function(){var q=this.value.trim().toLowerCase();tc();clearTimeout(debounce);
        if(q.length<1){closeSg();if(tb)tb.querySelectorAll('tr').forEach(function(r){r.style.display=''});return;}
        if(tb)tb.querySelectorAll('tr').forEach(function(r){r.style.display=r.textContent.toLowerCase().includes(q)?'':'none'});
        debounce=setTimeout(function(){fetchSg(q)},300);
    });
    function fetchSg(q){
        dd.innerHTML='<div class="sg-loading">Searching…</div>';dd.classList.add('active');
        fetch('{{ route("subjects.search") }}?q='+encodeURIComponent(q),{headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
        .then(function(r){return r.json()}).then(function(data){
            activeIdx=-1;
            if(!data.length){dd.innerHTML='<div class="sg-empty">No results for "'+esc(q)+'"</div>';return;}
            dd.innerHTML=data.map(function(item){
                return '<div class="sg-item" data-search="'+esc(item.search_value)+'"><div class="sg-icon"><svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg></div><div class="sg-info"><div class="sg-name">'+esc(item.name)+'</div><div class="sg-meta">'+esc(item.detail)+'</div></div><span class="sg-field">'+esc(item.matched_field)+'</span></div>';
            }).join('');
            dd.querySelectorAll('.sg-item').forEach(function(el){el.addEventListener('click',function(){si.value=this.getAttribute('data-search');tc();closeSg();var v=si.value.trim().toLowerCase();if(tb)tb.querySelectorAll('tr').forEach(function(r){r.style.display=r.textContent.toLowerCase().includes(v)?'':'none'})})});
        }).catch(function(){dd.innerHTML='<div class="sg-empty">Search unavailable</div>'});
    }
    si.addEventListener('keydown',function(e){var items=dd.querySelectorAll('.sg-item');if(!items.length)return;if(e.key==='ArrowDown'){e.preventDefault();activeIdx=Math.min(activeIdx+1,items.length-1);items.forEach(function(it,i){it.classList.toggle('active',i===activeIdx)})}else if(e.key==='ArrowUp'){e.preventDefault();activeIdx=Math.max(activeIdx-1,0);items.forEach(function(it,i){it.classList.toggle('active',i===activeIdx)})}else if(e.key==='Enter'&&activeIdx>=0){e.preventDefault();items[activeIdx].click()}else if(e.key==='Escape'){closeSg()}});
    si.addEventListener('focus',function(){if(this.value.trim().length>=1&&!dd.classList.contains('active'))fetchSg(this.value.trim())});
    document.addEventListener('click',function(e){if(!si.contains(e.target)&&!dd.contains(e.target))closeSg()});
    sf.addEventListener('submit',function(e){e.preventDefault()});
})();
document.querySelectorAll('.delete-form').forEach(function(f){f.addEventListener('submit',function(e){e.preventDefault();var df=this;Swal.fire({title:'Delete Subject?',text:'This cannot be undone.',icon:'warning',showCancelButton:true,confirmButtonColor:'#0a0a0a',confirmButtonText:'Yes, delete!'}).then(function(r){if(r.isConfirmed)df.submit()})})});
document.getElementById('add-subject-form').addEventListener('submit',function(e){e.preventDefault();var f=this;Swal.fire({title:'Add Subject?',icon:'question',showCancelButton:true,confirmButtonColor:'#0a0a0a',confirmButtonText:'Yes, add!'}).then(function(r){if(r.isConfirmed)f.submit()})});
document.getElementById('edit-subject-form').addEventListener('submit',function(e){e.preventDefault();var f=this;Swal.fire({title:'Update Subject?',icon:'question',showCancelButton:true,confirmButtonColor:'#0a0a0a',confirmButtonText:'Yes, update!'}).then(function(r){if(r.isConfirmed)f.submit()})});

// Import modal via SweetAlert
function openImportModal(){
    Swal.fire({
        title:'Import Subjects',
        html:'<div style="text-align:left;font-size:.85rem;color:#525252;margin-bottom:1rem">' +
             '<p style="margin-bottom:.75rem">Upload a <strong>CSV file</strong> with the following columns:</p>' +
             '<div style="background:#f5f5f5;border:1px solid #e5e5e5;border-radius:8px;padding:.75rem 1rem;font-family:monospace;font-size:.78rem;color:#0a0a0a;margin-bottom:.75rem">' +
             'Name, Code, Description, Credits</div>' +
             '<p style="font-size:.78rem;color:#a3a3a3">• First row should be headers<br>• Duplicate codes will be skipped<br>• Max file size: 10MB</p></div>' +
             '<input type="file" id="swal-import-file" accept=".csv,.txt,.xlsx,.xls" style="width:100%;padding:.6rem;border:1.5px dashed #d4d4d4;border-radius:8px;font-family:inherit;font-size:.85rem;cursor:pointer;background:#fafafa">',
        showCancelButton:true,
        confirmButtonText:'<svg style="width:16px;height:16px;margin-right:6px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> Import',
        cancelButtonText:'Cancel',
        confirmButtonColor:'#2563eb',
        cancelButtonColor:'#a3a3a3',
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
    var baseExcel='{{ route("subjects.export.excel") }}';
    var basePdf='{{ route("subjects.export.pdf") }}';

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
