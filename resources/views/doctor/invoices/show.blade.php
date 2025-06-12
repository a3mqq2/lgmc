@extends('layouts.'.get_area_name())
@section('title', 'فاتورة رقم ' . $invoice->id)

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid px-3">
    <!-- Header with Back Button -->
    <div class="row mb-3">
        <div class="col-12" style="margin-top: 50px;">
            <div class="d-flex align-items-center justify-content-between">
                <button onclick="history.back()" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-right me-1"></i>
                    العودة
                </button>
                <div class="text-muted small">
                    تاريخ الإنشاء: {{ $invoice->created_at->format('Y-m-d h:i A') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <!-- Invoice Header -->
            <div class="invoice-header text-center py-4 border-bottom">
                <h3 class="text-primary fw-bold mb-2">
                    فاتورة رقم {{ $invoice->id }}
                </h3>
                <div class="invoice-status">
                    @switch($invoice->status->value)
                        @case('paid')
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>مدفوعة
                            </span>
                            @break
                        @case('pending')
                            <span class="badge bg-warning fs-6 px-3 py-2">
                                <i class="fas fa-clock me-1"></i>معلقة
                            </span>
                            @break
                        @case('cancelled')
                            <span class="badge bg-danger fs-6 px-3 py-2">
                                <i class="fas fa-times-circle me-1"></i>ملغية
                            </span>
                            @break
                        @default
                            <span class="badge bg-secondary fs-6 px-3 py-2">
                                {{ $invoice->status }}
                            </span>
                    @endswitch
                </div>
            </div>

            <!-- Invoice Details -->
            <div class="invoice-details p-4">
                <div class="row g-3 mb-4">
                    <!-- Doctor Information -->
                    <div class="col-md-6">
                        <div class="info-card h-100">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user-md me-2"></i>
                                بيانات الطبيب
                            </h6>
                            <div class="row g-2">
                                <div class="col-4">
                                    <small class="text-muted">الاسم:</small>
                                </div>
                                <div class="col-8">
                                    <span class="fw-semibold">{{ $invoice->doctor->name }}</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">الكود:</small>
                                </div>
                                <div class="col-8">
                                    <span class="fw-semibold">{{ $invoice->doctor->code }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Information -->
                    <div class="col-md-6">
                        <div class="info-card h-100">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-user me-2"></i>
                                بيانات الاعداد
                            </h6>
                            <div class="row g-2">
                                <div class="col-4">
                                    <small class="text-muted">الاسم:</small>
                                </div>
                                <div class="col-8">
                                    <span class="fw-semibold">{{ $invoice->user?->name ?? 'غير محدد' }}</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">التاريخ:</small>
                                </div>
                                <div class="col-8">
                                    <span class="fw-semibold">
                                        {{ $invoice->created_at->format('Y-m-d') }}
                                        <br>
                                        <small class="text-muted">
                                            الساعة {{ $invoice->created_at->format('h:i A') }}
                                        </small>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if($invoice->description)
                <div class="description-card mb-4">
                    <h6 class="text-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        الوصف
                    </h6>
                    <div class="bg-light rounded p-3">
                        <p class="mb-0">{{ $invoice->description }}</p>
                    </div>
                </div>
                @endif

                <!-- Invoice Items -->
                <div class="items-section">
                    <h6 class="text-dark mb-3">
                        <i class="fas fa-list me-2"></i>
                        تفاصيل الفاتورة
                    </h6>
                    
                    @if($invoice->items->count() > 0)
                        <!-- Mobile View -->
                        <div class="d-md-none">
                            @php $idx = 1; @endphp
                            @foreach($invoice->items as $item)
                                <div class="item-card mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-primary rounded-circle me-2">{{ $idx++ }}</span>
                                                <strong>{{ $item->description }}</strong>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h6 class="text-success mb-0">
                                                {{ number_format($item->amount, 2) }} د.ل
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop View -->
                        <div class="d-none d-md-block">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="10%" class="text-center">#</th>
                                            <th width="60%">الوصف</th>
                                            <th width="30%" class="text-end">المبلغ (د.ل)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $idx = 1; @endphp
                                        @foreach($invoice->items as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <span class="badge bg-primary rounded-circle">{{ $idx++ }}</span>
                                                </td>
                                                <td>{{ $item->description }}</td>
                                                <td class="text-end fw-semibold">
                                                    {{ number_format($item->amount, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                            <p class="text-muted">لا توجد بنود في هذه الفاتورة</p>
                        </div>
                    @endif
                </div>

                <!-- Total Amount -->
                <div class="total-section mt-4">
                    <div class="card bg-primary bg-opacity-10 border-primary">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h5 class="mb-0 text-light">
                                        <i class="fas fa-calculator me-2"></i>
                                        إجمالي المبلغ
                                    </h5>
                                </div>
                                <div class="col-4 text-end">
                                    <h3 class="mb-0 text-light fw-bold">
                                        {{ number_format($invoice->amount, 2) }} د.ل
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Notice -->
                @if (auth()->user()->branch_id == null)
                <div class="payment-notice mt-4">
                    <div class="alert alert-info border-0">
                        <div class="d-flex">
                            <i class="fas fa-info-circle text-info me-3 mt-1"></i>
                            <div>
                                <h6 class="alert-heading mb-2">ملاحظة مهمة حول السداد</h6>
                                <p class="mb-0">
                                    يتم السداد كل يوم أحد وثلاثاء والاستلام يكون بعد تاريخ الدفع بأسبوع
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    :root {
        --primary-color: #0d6efd;
        --success-color: #198754;
        --info-color: #0dcaf0;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --light-color: #f8f9fa;
        --border-radius: 12px;
        --box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }

    body {
        background-color: #f5f7fa;
        font-family: 'Cairo', 'Segoe UI', sans-serif;
    }

    .card {
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        border: none;
    }

    .invoice-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 3px solid var(--primary-color) !important;
    }

    .logo-img {
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
    }

    .info-card {
        background: #f8f9fa;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        border: 1px solid #e9ecef;
    }

    .description-card {
        border-radius: var(--border-radius);
    }

    .item-card {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .item-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transform: translateY(-1px);
    }

    .table {
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .table th {
        background-color: #f8f9fa !important;
        font-weight: 600;
        color: #495057;
        border: none;
    }

    .table td {
        border-color: #e9ecef;
        vertical-align: middle;
    }

    .total-section .card {
        border-width: 2px !important;
    }

    .badge {
        font-weight: 500;
    }

    .badge.rounded-circle {
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .alert {
        border-radius: var(--border-radius);
        border: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .invoice-header {
            padding: 2rem 1rem !important;
        }

        .logo-img {
            max-width: 150px !important;
        }

        .info-card {
            padding: 1rem;
        }

        .item-card {
            padding: 0.75rem;
        }

        .badge.fs-6 {
            font-size: 0.8rem !important;
            padding: 0.4rem 0.8rem !important;
        }

        .total-section h3 {
            font-size: 1.5rem;
        }

        .action-buttons .btn {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
    }

    /* Print Styles */
    @media print {
        .container-fluid {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }

        .btn, .d-flex.justify-content-between {
            display: none !important;
        }

        .invoice-header {
            background: white !important;
            border-bottom: 2px solid #cc0100 !important;
        }

        body {
            background: white !important;
        }

        .info-card {
            border: 1px solid #ddd;
            background: #f9f9f9;
        }

        .total-section .card {
            background: #f0f8ff !important;
        }
    }

    /* Loading Animation */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .loading::after {
        content: "";
        display: inline-block;
        width: 16px;
        height: 16px;
        margin-left: 8px;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Smooth transitions */
    * {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all cards and items
    document.querySelectorAll('.info-card, .item-card, .total-section').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
});
</script>
@endsection