@extends('layouts.'.get_area_name())
@section('title','الصفحة الرئيسية')
@section('styles')
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

<!-- Statistics Section -->
<div class="container-fluid px-0">
    <h2 class="section-title text-dark">إحصائيات العضويات</h2>
    
    <div class="stats-grid">
        @php
            $libyanStatuses = [
                ['key'=>'under_approve', 'label'=>'طلبات الموقع', 'gradient'=>'card-gradient-primary', 'icon'=>'fa-clock'],
                ['key'=>'under_edit', 'label'=>'عضويات قيد التعديل', 'gradient'=>'card-gradient-secondary', 'icon'=>'fa-edit'],
                ['key'=>'under_payment', 'label'=>'عضويات قيد الدفع', 'gradient'=>'card-gradient-danger', 'icon'=>'fa-credit-card'],
                ['key'=>'active', 'label'=>'عضويات مفعله', 'gradient'=>'card-gradient-success', 'icon'=>'fa-check-circle'],
            ];
        @endphp

        @foreach($libyanStatuses as $stat)
            <a href="{{ route(get_area_name().'.doctors.index', ['type'=>'libyan','membership_status'=>$stat['key']]) }}" class="text-decoration-none">
                <div class="card stat-card {{ $stat['gradient'] }} h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <p class="stat-label mb-2">{{ $stat['label'] }}</p>
                                <h2 class="stat-count text-light">
                                    {{ \App\Models\Doctor::where('branch_id', auth()->user()->branch_id)->where('membership_status',$stat['key'])->where('type','libyan')->count() }}
                                </h2>
                                <div class="stat-change mt-3">
                                    <i class="fas fa-arrow-up"></i>
                                    <span>عرض التفاصيل</span>
                                </div>
                            </div>
                            <div class="stat-icon">
                                <i class="fas {{ $stat['icon'] }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>

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
@endsection