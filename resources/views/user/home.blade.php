@extends('layouts.'.get_area_name())
@section('title','الصفحة الرئيسية')
@section('styles')
<style>
    /* ===== CSS Variables ===== */
    :root {
        --primary-red: #dc2626;
        --primary-red-light: #ef4444;
        --primary-red-dark: #b91c1c;
        --neutral-50: #fafafa;
        --neutral-100: #f5f5f5;
        --neutral-200: #e5e5e5;
        --neutral-300: #d4d4d4;
        --neutral-400: #a3a3a3;
        --neutral-500: #737373;
        --neutral-600: #525252;
        --neutral-700: #404040;
        --neutral-800: #262626;
        --neutral-900: #171717;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ===== Page Background ===== */
    body {
        background: linear-gradient(135deg, #fafafa 0%, #f0f0f0 100%);
        font-family: 'Cairo', sans-serif;
        color: var(--neutral-700);
    }

    /* ===== Welcome Banner ===== */
    .welcome-banner {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--primary-red-dark) 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .welcome-banner::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(50px, -50px);
    }

    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(-30px, 30px);
    }

    .welcome-content {
        position: relative;
        z-index: 2;
    }

    .welcome-title {
        color: white;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.75rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .welcome-subtitle {
        color: rgba(255,255,255,0.9);
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
        font-weight: 400;
    }

    .welcome-stats {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .welcome-stat {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: white;
    }

    .welcome-stat-icon {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .welcome-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .welcome-stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    /* ===== Enhanced Cards - Minimal Design ===== */
    .card-minimal {
        background: white;
        border: 1px solid var(--neutral-200);
        border-radius: 16px;
        transition: var(--transition);
        overflow: hidden;
        position: relative;
        box-shadow: var(--shadow-sm);
    }

    .card-minimal:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--neutral-300);
    }

    .card-minimal .card-body {
        padding: 1.75rem;
        position: relative;
    }

    /* ===== Card Accent Colors ===== */
    .card-accent-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--primary-red);
    }

    .card-accent-neutral::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: var(--neutral-400);
    }

    /* ===== Section Headers ===== */
    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--neutral-100);
    }

    .section-icon {
        width: 40px;
        height: 40px;
        background: var(--primary-red);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #000 !important;
        margin: 0;
    }

    /* ===== Stats Display ===== */
    .stat-number {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--neutral-800);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.95rem;
        color: var(--neutral-600);
        font-weight: 500;
        line-height: 1.3;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        background: var(--neutral-100);
        color: var(--neutral-600);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    /* ===== Special Status Indicators ===== */
    .status-urgent .stat-icon {
        background: #fef2f2;
        color: var(--primary-red);
    }

    .status-urgent .stat-number {
        color: var(--primary-red);
    }

    .status-warning .stat-icon {
        background: #fffbeb;
        color: #d97706;
    }

    .status-warning .stat-number {
        color: #d97706;
    }

    .status-success .stat-icon {
        background: #f0fdf4;
        color: #16a34a;
    }

    .status-success .stat-number {
        color: #16a34a;
    }

    /* ===== Enhanced Tables ===== */
    .table-modern {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--neutral-200);
    }

    .table-modern thead th {
        background: var(--neutral-50);
        color: var(--neutral-700);
        font-weight: 600;
        padding: 1rem 1.25rem;
        border: none;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .table-modern tbody tr {
        border: none;
        transition: var(--transition);
    }

    .table-modern tbody tr:hover {
        background: var(--neutral-50);
    }

    .table-modern tbody td {
        padding: 1rem 1.25rem;
        border: none;
        color: var(--neutral-700);
        vertical-align: middle;
    }

    .table-modern tbody tr:not(:last-child) td {
        border-bottom: 1px solid var(--neutral-100);
    }

    /* ===== Badges ===== */
    .badge-modern {
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.8rem;
        border: 1px solid;
    }

    .badge-primary {
        background: #fef2f2;
        color: var(--primary-red);
        border-color: #fecaca;
    }

    .badge-success {
        background: #f0fdf4;
        color: #16a34a;
        border-color: #bbf7d0;
    }

    .badge-warning {
        background: #fffbeb;
        color: #d97706;
        border-color: #fed7aa;
    }

    .badge-neutral {
        background: var(--neutral-100);
        color: var(--neutral-600);
        border-color: var(--neutral-200);
    }

    /* ===== Links ===== */
    .card-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .card-link:hover {
        color: inherit;
        text-decoration: none;
    }

    /* ===== Empty State ===== */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--neutral-500);
    }

    .empty-state-icon {
        font-size: 3rem;
        color: var(--neutral-300);
        margin-bottom: 1rem;
    }

    /* ===== Responsive Design ===== */
    @media (max-width: 768px) {
        .welcome-banner {
            padding: 2rem 1.5rem;
            margin-bottom: 1.5rem;
        }

        .welcome-title {
            font-size: 1.75rem;
        }

        .welcome-stats {
            gap: 1rem;
        }

        .welcome-stat {
            flex: 1;
            min-width: 0;
        }

        .card-minimal .card-body {
            padding: 1.25rem;
        }

        .stat-number {
            font-size: 1.75rem;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }

    /* ===== Animation ===== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .stagger-1 { animation-delay: 0.1s; }
    .stagger-2 { animation-delay: 0.2s; }
    .stagger-3 { animation-delay: 0.3s; }
    .stagger-4 { animation-delay: 0.4s; }
</style>
<style>
    /* ---------- Page Background ---------- */
    body {
        min-height: 100vh;
        color: #f5f5f5;
    }

    /* ---------- Page Header ---------- */
    .page-header {
        background: linear-gradient(135deg, #8b0000 0%, #660000 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(139, 0, 0, 0.3);
        border: 1px solid rgba(139, 0, 0, 0.2);
    }
    .page-header h1 {
        color: #fff;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }
    .page-header p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        margin-bottom: 0;
    }

    /* ---------- Cards ---------- */
    .stat-card {
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: none;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        position: relative;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
        transform: translateX(-100%);
        transition: transform 0.6s;
    }
    .stat-card:hover::before {
        transform: translateX(100%);
    }
    .stat-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(139, 0, 0, 0.4);
    }
    .stat-card a {
        text-decoration: none;
    }

    /* تدرجات دموية متناسقة */
    .card-gradient-primary {
        background: linear-gradient(135deg, #8b0000 0%, #dc143c 100%);
        color: #fff;
    }
    .card-gradient-secondary {
        background: linear-gradient(135deg, #b22222 0%, #cd5c5c 100%);
        color: #fff;
    }
    .card-gradient-success {
        background: linear-gradient(135deg, #800020 0%, #a0293d 100%);
        color: #fff;
    }
    .card-gradient-danger {
        background: linear-gradient(135deg, #722f37 0%, #8b4513 100%);
        color: #fff;
    }

    /* ---------- Card Content ---------- */
    .stat-card .card-body {
        padding: 2rem;
        position: relative;
        z-index: 1;
    }
    .stat-label {
        font-size: 1rem;
        font-weight: 500;
        opacity: 0.95;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }
    .stat-count {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1;
        margin: 1rem 0;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }
    .stat-change {
        font-size: 0.875rem;
        opacity: 0.9;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* ---------- Icons ---------- */
    .stat-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    .stat-card:hover .stat-icon {
        transform: rotate(10deg) scale(1.1);
        background: rgba(255, 255, 255, 0.25);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    /* ---------- Add Button ---------- */
    .add-member-btn {
        background: linear-gradient(135deg, #8b0000 0%, #dc143c 100%);
        color: #fff;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        box-shadow: 0 10px 30px rgba(139, 0, 0, 0.4);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        position: relative;
        overflow: hidden;
    }
    .add-member-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    .add-member-btn:hover::before {
        width: 300px;
        height: 300px;
    }
    .add-member-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(139, 0, 0, 0.5);
        color: #fff;
    }
    .add-member-btn i {
        font-size: 1.2rem;
        position: relative;
        z-index: 1;
    }

    /* ---------- Stats Grid ---------- */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }

    /* ---------- Section Title ---------- */
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #f5f5f5;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 0.75rem;
    }
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, #8b0000 0%, #dc143c 100%);
        border-radius: 2px;
    }

    /* ---------- Quick Actions Cards ---------- */
    .quick-action-card {
        background: rgba(139, 0, 0, 0.1);
        border: 1px solid rgba(139, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    .quick-action-card:hover {
        background: rgba(139, 0, 0, 0.2);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(139, 0, 0, 0.3);
    }
    .quick-action-card .card-body {
        color: #000000;
    }
    .quick-action-card h5 {
        color: #fff;
        font-weight: 600;
    }
    .quick-action-card .text-muted {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    .quick-action-card .fas {
        color: #dc143c !important;
    }
    
    /* ---------- Buttons ---------- */
    .btn-outline-primary {
        color: #dc143c;
        border-color: #dc143c;
        background: transparent;
        transition: all 0.3s ease;
    }
    .btn-outline-primary:hover {
        background: #dc143c;
        border-color: #dc143c;
        color: #fff;
        box-shadow: 0 5px 15px rgba(220, 20, 60, 0.3);
    }
    .btn-outline-success {
        color: #a0293d;
        border-color: #a0293d;
        background: transparent;
    }
    .btn-outline-success:hover {
        background: #a0293d;
        border-color: #a0293d;
        color: #fff;
        box-shadow: 0 5px 15px rgba(160, 41, 61, 0.3);
    }
    .btn-outline-warning {
        color: #cd5c5c;
        border-color: #cd5c5c;
        background: transparent;
    }
    .btn-outline-warning:hover {
        background: #cd5c5c;
        border-color: #cd5c5c;
        color: #fff;
        box-shadow: 0 5px 15px rgba(205, 92, 92, 0.3);
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }
        .page-header h1 {
            font-size: 1.5rem;
        }
        .stat-count {
            font-size: 2.2rem;
        }
        .add-member-btn {
            padding: 0.875rem 2rem;
            font-size: 1rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h1>لوحة التحكم الرئيسية</h1>
            <p>اهلاََ وسهلاََ بيك {{auth()->user()->name}} نتمنى لك يوماََ سعيداََ </p>
        </div>
    </div>
</div>




@if(auth()->user()->permissions->where('name','doctor-visitor')->count())

<div class="section-header fade-in-up">
   <div class="section-icon">
       <i class="fas fa-user-md"></i>
   </div>
   <h2 class="section-title">الأطباء الليبيين</h2>
</div>

<div class="row g-4 mb-5">
   @php
       $foreignStatuses = [
           ['key'=>'under_approve', 'label'=>'طلبات الموقع', 'icon'=>'fa-user-clock', 'status'=>'warning'],
           ['key'=>'under_edit', 'label'=>'قيد التعديل', 'icon'=>'fa-user-edit', 'status'=>'neutral'],
           ['key'=>'under_payment', 'label'=>'قيد الدفع', 'icon'=>'fa-hand-holding-usd', 'status'=>'urgent'],
           ['key'=>'active', 'label'=>'مفعلين', 'icon'=>'fa-user-check', 'status'=>'success'],
       ];
   @endphp

   @foreach($foreignStatuses as $index => $stat)
       <div class="col-xl-3 col-md-6">
           <a href="{{ route(get_area_name().'.doctors.index', ['type'=>'libyan','membership_status'=>$stat['key']]) }}" 
              class="card-link">
               <div class="card card-minimal card-accent-{{ $stat['status'] == 'urgent' ? 'primary' : 'neutral' }} fade-in-up stagger-{{ ($index % 4) + 1 }} status-{{ $stat['status'] }}">
                   <div class="card-body">
                       <div class="stat-icon">
                           <i class="fas {{ $stat['icon'] }}"></i>
                       </div>
                       <div class="stat-number">
                           {{ \App\Models\Doctor::where('branch_id', auth()->user()->branch_id)->where('membership_status',$stat['key'])->where('type','libyan')->count() }}
                       </div>
                       <div class="stat-label">{{ $stat['label'] }}</div>
                   </div>
               </div>
           </a>
       </div>
   @endforeach
</div>

@endif


   {{-- Foreign Doctors Section --}}
   @if(auth()->user()->permissions->where('name','doctor-foreign')->count())
   <div class="section-header fade-in-up">
       <div class="section-icon">
           <i class="fas fa-user-md"></i>
       </div>
       <h2 class="section-title">الأطباء الأجانب</h2>
   </div>

   <div class="row g-4 mb-5">
       @php
           $foreignStatuses = [
               ['key'=>'under_approve', 'label'=>'طلبات الموقع', 'icon'=>'fa-user-clock', 'status'=>'warning'],
               ['key'=>'under_edit', 'label'=>'قيد التعديل', 'icon'=>'fa-user-edit', 'status'=>'neutral'],
               ['key'=>'under_payment', 'label'=>'قيد الدفع', 'icon'=>'fa-hand-holding-usd', 'status'=>'urgent'],
               ['key'=>'active', 'label'=>'مفعلين', 'icon'=>'fa-user-check', 'status'=>'success'],
           ];
       @endphp

       @foreach($foreignStatuses as $index => $stat)
           <div class="col-xl-3 col-md-6">
               <a href="{{ route(get_area_name().'.doctors.index', ['type'=>'foreign','membership_status'=>$stat['key']]) }}" 
                  class="card-link">
                   <div class="card card-minimal card-accent-{{ $stat['status'] == 'urgent' ? 'primary' : 'neutral' }} fade-in-up stagger-{{ ($index % 4) + 1 }} status-{{ $stat['status'] }}">
                       <div class="card-body">
                           <div class="stat-icon">
                               <i class="fas {{ $stat['icon'] }}"></i>
                           </div>
                           <div class="stat-number">
                               {{ \App\Models\Doctor::where('branch_id', auth()->user()->branch_id)->where('membership_status',$stat['key'])->where('type','foreign')->count() }}
                           </div>
                           <div class="stat-label">{{ $stat['label'] }}</div>
                       </div>
                   </div>
               </a>
           </div>
       @endforeach
   </div>
@endif


@if(auth()->user()->permissions->where('name','doctor-visitor')->count())

<div class="section-header fade-in-up">
   <div class="section-icon">
       <i class="fas fa-user-md"></i>
   </div>
   <h2 class="section-title">الأطباء الزوار</h2>
</div>

<div class="row g-4 mb-5">
   @php
       $foreignStatuses = [
           ['key'=>'under_approve', 'label'=>'طلبات الموقع', 'icon'=>'fa-user-clock', 'status'=>'warning'],
           ['key'=>'under_edit', 'label'=>'قيد التعديل', 'icon'=>'fa-user-edit', 'status'=>'neutral'],
           ['key'=>'under_payment', 'label'=>'قيد الدفع', 'icon'=>'fa-hand-holding-usd', 'status'=>'urgent'],
           ['key'=>'active', 'label'=>'مفعلين', 'icon'=>'fa-user-check', 'status'=>'success'],
       ];
   @endphp

   @foreach($foreignStatuses as $index => $stat)
       <div class="col-xl-3 col-md-6">
           <a href="{{ route(get_area_name().'.doctors.index', ['type'=>'visitor','membership_status'=>$stat['key']]) }}" 
              class="card-link">
               <div class="card card-minimal card-accent-{{ $stat['status'] == 'urgent' ? 'primary' : 'neutral' }} fade-in-up stagger-{{ ($index % 4) + 1 }} status-{{ $stat['status'] }}">
                   <div class="card-body">
                       <div class="stat-icon">
                           <i class="fas {{ $stat['icon'] }}"></i>
                       </div>
                       <div class="stat-number">
                           {{ \App\Models\Doctor::where('branch_id', auth()->user()->branch_id)->where('membership_status',$stat['key'])->where('type','visitor')->count() }}
                       </div>
                       <div class="stat-label">{{ $stat['label'] }}</div>
                   </div>
               </div>
           </a>
       </div>
   @endforeach
</div>

@endif
{{-- Palestinian Doctors Section --}}
@if(auth()->user()->permissions->where('name','doctor-palestinian')->count())
   <div class="section-header fade-in-up">
       <div class="section-icon">
           <i class="fas fa-user-md"></i>
       </div>
       <h2 class="section-title">الأطباء الفلسطينيين</h2>
   </div>

   <div class="row g-4 mb-5">
       @php
           $palestinianStatuses = [
               ['key'=>'under_approve', 'label'=>'طلبات الموقع', 'icon'=>'fa-user-clock', 'status'=>'warning'],
               ['key'=>'under_edit', 'label'=>'قيد التعديل', 'icon'=>'fa-user-edit', 'status'=>'neutral'],
               ['key'=>'under_payment', 'label'=>'قيد الدفع', 'icon'=>'fa-hand-holding-usd', 'status'=>'urgent'],
               ['key'=>'active', 'label'=>'مفعلين', 'icon'=>'fa-user-check', 'status'=>'success'],
           ];
       @endphp

       @foreach($palestinianStatuses as $index => $stat)
           <div class="col-xl-3 col-md-6">
               <a href="{{ route(get_area_name().'.doctors.index', ['type'=>'palestinian','membership_status'=>$stat['key']]) }}" 
                  class="card-link">
                   <div class="card card-minimal card-accent-{{ $stat['status'] == 'urgent' ? 'primary' : 'neutral' }} fade-in-up stagger-{{ ($index % 4) + 1 }} status-{{ $stat['status'] }}">
                       <div class="card-body">
                           <div class="stat-icon">
                               <i class="fas {{ $stat['icon'] }}"></i>
                           </div>
                           <div class="stat-number">
                               {{ \App\Models\Doctor::where('branch_id', auth()->user()->branch_id)->where('membership_status',$stat['key'])->where('type','palestinian')->count() }}
                           </div>
                           <div class="stat-label">{{ $stat['label'] }}</div>
                       </div>
                   </div>
               </a>
           </div>
       @endforeach
   </div>

   {{-- Financial Transactions Table --}}
   @if(auth()->user()->vault)
       <div class="section-header fade-in-up">
           <div class="section-icon">
               <i class="fas fa-money-bill-wave"></i>
           </div>
           <h2 class="section-title">الحركات المالية اليوم</h2>
       </div>

       <div class="card card-minimal fade-in-up">
           <div class="table-responsive">
               <table class="table table-modern mb-0">
                   <thead>
                       <tr>
                           <th>الرقم</th>
                           <th>الخزينة</th>
                           <th>المستخدم</th>
                           <th>الوصف</th>
                           <th>سحب</th>
                           <th>إيداع</th>
                           <th>التاريخ والوقت</th>
                       </tr>
                   </thead>
                   <tbody>
                       @forelse(auth()->user()->vault->transactions()->whereDate('created_at', Carbon\Carbon::today())->latest()->get() as $transaction)
                           <tr>
                               <td><strong>{{ $transaction->id }}</strong></td>
                               <td>{{ $transaction->vault->name }}</td>
                               <td>{{ $transaction->user->name }}</td>
                               <td>{{ $transaction->desc }}</td>
                               <td>
                                   @if($transaction->type === 'withdrawal')
                                       <span class="badge-modern badge-primary">
                                           {{ number_format($transaction->amount, 2) }} د.ل
                                       </span>
                                   @else
                                       <span class="text-muted">—</span>
                                   @endif
                               </td>
                               <td>
                                   @if($transaction->type === 'deposit')
                                       <span class="badge-modern badge-success">
                                           {{ number_format($transaction->amount, 2) }} د.ل
                                       </span>
                                   @else
                                       <span class="text-muted">—</span>
                                   @endif
                               </td>
                               <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                           </tr>
                       @empty
                           <tr>
                               <td colspan="7">
                                   <div class="empty-state">
                                       <div class="empty-state-icon">
                                           <i class="fas fa-inbox"></i>
                                       </div>
                                       <p>لا توجد حركات مالية اليوم</p>
                                   </div>
                               </td>
                           </tr>
                       @endforelse
                   </tbody>
                   @if(auth()->user()->vault->transactions()->whereDate('created_at', Carbon\Carbon::today())->count() > 0)
                       <tfoot>
                           <tr>
                               <td colspan="4" class="text-end"><strong>المجموع:</strong></td>
                               <td>
                                   <span class="badge-modern badge-primary">
                                       {{ number_format(auth()->user()->vault->transactions()->whereDate('created_at', Carbon\Carbon::today())->where('type', 'withdrawal')->sum('amount'), 2) }} د.ل
                                   </span>
                               </td>
                               <td>
                                   <span class="badge-modern badge-success">
                                       {{ number_format(auth()->user()->vault->transactions()->whereDate('created_at', Carbon\Carbon::today())->where('type', 'deposit')->sum('amount'), 2) }} د.ل
                                   </span>
                               </td>
                               <td></td>
                           </tr>
                       </tfoot>
                   @endif
               </table>
           </div>
       </div>
   @endif
@endif

@endsection

@section('scripts')
<!-- apexcharts -->
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Vector map-->
<script src="/assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
<script src="/assets/libs/jsvectormap/maps/world-merc.js"></script>

<!-- Dashboard init -->
<script src="/assets/js/pages/dashboard-analytics.init.js"></script>

<!-- Animations -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate cards on page load
    const cards = document.querySelectorAll('.stat-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Animate quick action cards
    const quickCards = document.querySelectorAll('.quick-action-card');
    quickCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, (cards.length + index) * 100);
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate numbers on page load
        const statNumbers = document.querySelectorAll('.stat-number');
        
        const animateNumber = (element) => {
            const finalValue = parseInt(element.textContent.replace(/,/g, ''));
            let currentValue = 0;
            const increment = Math.max(1, Math.ceil(finalValue / 30));
            const duration = 1000;
            const stepTime = duration / (finalValue / increment);
            
            const counter = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    currentValue = finalValue;
                    clearInterval(counter);
                }
                element.textContent = currentValue.toLocaleString('ar-SA');
            }, stepTime);
        };
    
        // Observe elements and animate when they come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateNumber(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
    
        statNumbers.forEach(stat => {
            observer.observe(stat);
        });
    
        // Add smooth scroll behavior to card links
        const cardLinks = document.querySelectorAll('.card-link');
        cardLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Add a subtle click animation
                const card = this.querySelector('.card');
                card.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 150);
            });
        });
    
        // Auto refresh financial data every 5 minutes if vault exists
        @if(auth()->user()->vault ?? false)
        const refreshInterval = setInterval(() => {
            // Only refresh if user is still active on page
            if (!document.hidden) {
                const currentTime = new Date().toLocaleString('ar-SA');
                console.log('تحديث البيانات المالية:', currentTime);
                // You can add AJAX call here to update only the financial section
                // For now, we'll just do a full page refresh
                location.reload();
            }
        }, 300000); // 5 minutes
    
        // Clear interval when page is hidden
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                clearInterval(refreshInterval);
            }
        });
        @endif
    
        // Add loading state to cards when clicked
        cardLinks.forEach(link => {
            link.addEventListener('click', function() {
                const card = this.querySelector('.card');
                const icon = card.querySelector('.stat-icon i');
                const originalClass = icon.className;
                
                icon.className = 'fas fa-spinner fa-spin';
                
                // Reset after navigation (this won't actually run due to page change, but good practice)
                setTimeout(() => {
                    icon.className = originalClass;
                }, 2000);
            });
        });
    
        // Add keyboard navigation support
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                // Enhance focus visibility for accessibility
                document.body.classList.add('keyboard-navigation');
            }
        });
    
        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });
    
        // Welcome banner animation delay
        const welcomeBanner = document.querySelector('.welcome-banner');
        if (welcomeBanner) {
            setTimeout(() => {
                welcomeBanner.style.opacity = '1';
                welcomeBanner.style.transform = 'translateY(0)';
            }, 100);
        }
    });
    
    // Add enhanced focus styles for accessibility
    const accessibilityStyles = `
        .keyboard-navigation .card-link:focus {
            outline: 3px solid var(--primary-red);
            outline-offset: 2px;
            border-radius: 16px;
        }
        
        .keyboard-navigation .card-link:focus .card {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        @media (prefers-reduced-motion: reduce) {
            .card-minimal,
            .fade-in-up,
            .stat-number {
                animation: none !important;
                transition: none !important;
            }
            
            .card-minimal:hover {
                transform: none !important;
            }
        }
        
        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .card-minimal {
                border-width: 2px;
                border-color: var(--neutral-800);
            }
            
            .stat-number {
                font-weight: 900;
            }
        }
        
        /* Print styles */
        @media print {
            .welcome-banner,
            .card-minimal {
                break-inside: avoid;
                box-shadow: none !important;
                border: 1px solid #000 !important;
            }
            
            .section-header {
                break-after: avoid;
            }
            
            .card-link {
                pointer-events: none;
            }
            
            .stat-icon {
                background: #f0f0f0 !important;
            }
        }
    `;
    
    // Inject accessibility styles
    const styleSheet = document.createElement('style');
    styleSheet.textContent = accessibilityStyles;
    document.head.appendChild(styleSheet);
    
    // Performance monitoring (optional)
    if ('performance' in window) {
        window.addEventListener('load', () => {
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            console.log(`تحميل الصفحة استغرق: ${loadTime}ms`);
            
            // Log any performance issues
            if (loadTime > 3000) {
                console.warn('تحذير: الصفحة تستغرق وقتاً طويلاً في التحميل');
            }
        });
    }
    </script>
    
@endsection