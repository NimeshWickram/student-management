@extends('layouts.layout')

@section('title', 'Dashboard — CodeXpress')
@section('description', 'Overview of your educational management system.')
@section('page-title', 'Dashboard')
@section('breadcrumb')
<a href="{{ route('dashboard') }}">Home</a> / Overview
@endsection

@section('styles')
<style>
    .dash-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.25rem;margin-bottom:2rem}
    .stat-card{
        background:var(--white);border-radius:var(--radius);padding:1.75rem;
        box-shadow:0 2px 12px rgba(0,0,0,.06);
        display:flex;align-items:flex-start;justify-content:space-between;
        border:1px solid var(--g200);
        transition:all var(--tr);position:relative;overflow:hidden;
        text-decoration:none;color:inherit;
    }
    .stat-card:hover{transform:translateY(-3px);box-shadow:0 8px 32px rgba(0,0,0,.1);border-color:var(--g300)}
    .stat-card::after{
        content:'';position:absolute;bottom:0;left:0;right:0;height:3px;
        border-radius:0 0 var(--radius) var(--radius);
        transition:height .3s ease;
    }
    .stat-card:hover::after{height:4px}
    .stat-card.students::after{background:var(--accent)}
    .stat-card.teachers::after{background:#10b981}
    .stat-card.subjects::after{background:#f59e0b}
    .stat-info h3{font-size:.8rem;font-weight:600;color:var(--g500);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.5rem}
    .stat-info .stat-number{font-size:2.25rem;font-weight:800;letter-spacing:-.03em;line-height:1}
    .stat-info .stat-desc{font-size:.78rem;color:var(--g400);margin-top:.5rem}
    .stat-icon{
        width:48px;height:48px;border-radius:12px;
        display:flex;align-items:center;justify-content:center;flex-shrink:0;
    }
    .stat-icon svg{width:24px;height:24px}
    .stat-card.students .stat-icon{background:rgba(99,102,241,.1)}
    .stat-card.students .stat-icon svg{color:var(--accent)}
    .stat-card.teachers .stat-icon{background:rgba(16,185,129,.1)}
    .stat-card.teachers .stat-icon svg{color:#10b981}
    .stat-card.subjects .stat-icon{background:rgba(245,158,11,.1)}
    .stat-card.subjects .stat-icon svg{color:#f59e0b}

    .welcome-section{margin-bottom:2rem}
    .welcome-section h1{font-size:1.75rem;font-weight:800;letter-spacing:-.03em;margin-bottom:.35rem}
    .welcome-section p{color:var(--g500);font-size:.9rem;line-height:1.6}

    .quick-actions{display:flex;gap:.75rem;flex-wrap:wrap;margin-top:2rem}
    .qa-btn{
        display:inline-flex;align-items:center;gap:.5rem;
        padding:.7rem 1.25rem;font-family:inherit;font-size:.8rem;font-weight:600;
        background:var(--black);color:var(--white);border:2px solid var(--black);
        border-radius:var(--radius-sm);text-decoration:none;
        transition:all var(--tr);
    }
    .qa-btn:hover{background:var(--white);color:var(--black)}
    .qa-btn svg{width:16px;height:16px}
    .qa-btn.outline{background:var(--white);color:var(--black);border-color:var(--g200)}
    .qa-btn.outline:hover{border-color:var(--black)}

    @media(max-width:480px){
        .welcome-section h1{font-size:1.35rem}
        .dash-grid{grid-template-columns:1fr}
        .quick-actions{flex-direction:column}
        .qa-btn{justify-content:center}
    }
</style>
@endsection

@section('content')
    <div class="welcome-section">
        <h1>Welcome back! 👋</h1>
        <p>Here's an overview of your educational management system. Use the sidebar to navigate between sections.</p>
    </div>

    <div class="dash-grid">
        <a href="{{ route('students.index') }}" class="stat-card students" id="stat-students">
            <div class="stat-info">
                <h3>Total Students</h3>
                <div class="stat-number">{{ $stats['students'] }}</div>
                <div class="stat-desc">Registered students</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
        </a>
        <a href="{{ route('teachers.index') }}" class="stat-card teachers" id="stat-teachers">
            <div class="stat-info">
                <h3>Total Teachers</h3>
                <div class="stat-number">{{ $stats['teachers'] }}</div>
                <div class="stat-desc">Active teachers</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
        </a>
        <a href="{{ route('subjects.index') }}" class="stat-card subjects" id="stat-subjects">
            <div class="stat-info">
                <h3>Total Subjects</h3>
                <div class="stat-number">{{ $stats['subjects'] }}</div>
                <div class="stat-desc">Available subjects</div>
            </div>
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
            </div>
        </a>
    </div>

    <div class="quick-actions">
        <a href="{{ route('students.index') }}" class="qa-btn" id="qa-add-student">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Student
        </a>
        <a href="{{ route('teachers.index') }}" class="qa-btn outline" id="qa-add-teacher">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Teacher
        </a>
        <a href="{{ route('subjects.index') }}" class="qa-btn outline" id="qa-add-subject">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Subject
        </a>
    </div>
@endsection
