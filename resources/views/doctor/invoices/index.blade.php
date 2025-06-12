@extends('layouts.'.get_area_name())
@section('title', 'فواتيري')



@section('content')
<div class="container-fluid px-3">


    <!-- Filter Section -->
    <div class="card border-0 shadow-sm mb-4 mt-4" style="background:#9a3034!important;"> 
        <div class="card-body py-3">
            <h4 class="text-center text-white">الفواتير الخاصة بي</h4>
        </div>
    </div>

    <!-- Invoice Cards for Mobile -->
    <div class="invoice-list">
        @forelse(auth('doctor')->user()->invoices as $invoice)
            <div class="card border-0 shadow-sm mb-3 invoice-card" data-status="{{ $invoice->status }}">
                <div class="card-body p-3">
                    <!-- Header Row -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-1 fw-bold">فاتورة #{{ $invoice->id }}</h6>
                                <p class="text-muted mb-0 small">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $invoice->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-end">
                            @switch($invoice->status)
                                @case('paid')
                                    <span class="badge bg-success rounded-pill">
                                        <i class="fas fa-check me-1"></i>مدفوعة
                                    </span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning rounded-pill">
                                        <i class="fas fa-clock me-1"></i>معلقة
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger rounded-pill">
                                        <i class="fas fa-times me-1"></i>ملغية
                                    </span>
                                    @break
                                @default
                                    <span class="badge {{$invoice->status->badgeClass()}} rounded-pill">
                                        {{ $invoice->status->label() }}
                                    </span>
                            @endswitch
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <p class="text-dark mb-2">
                            <i class="fas fa-info-circle text-muted me-2"></i>
                            {{ $invoice->description ?: 'لا يوجد وصف' }}
                        </p>
                    </div>

                    <!-- Details Row -->
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="bg-light rounded p-2 text-center">
                                <small class="text-muted d-block">المعد</small>
                                <span class="fw-semibold small">{{ $invoice->user?->name ?? 'غير محدد' }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-info bg-opacity-10 rounded p-2 text-center">
                                <small class="text-muted d-block">البنود</small>
                                <span class="fw-semibold text-info">{{ $invoice->items->count() ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-primary bg-opacity-10 rounded p-2 text-center">
                                <small class="text-muted d-block">المبلغ</small>
                                <span class="fw-bold text-primary">{{ number_format($invoice->amount, 2) }} د.ل</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-primary btn-sm flex-fill" href="{{route('doctor.invoices.show', $invoice)}}">
                            <i class="fas fa-eye me-1"></i>عرض
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted mb-2">لا توجد فواتير</h5>
                    <p class="text-muted mb-4">لم يتم العثور على أي فواتير حتى الآن</p>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>إضافة فاتورة جديدة
                    </button>
                </div>
            </div>
        @endforelse
    </div>
</div>

@endsection

@section('styles')
<style>
    :root {
        --primary-color: #0d6efd;
        --success-color: #198754;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --info-color: #0dcaf0;
        --light-color: #f8f9fa;
        --border-radius: 12px;
        --box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    body {
        background-color: #f5f7fa;
        font-family: 'Cairo', 'Segoe UI', sans-serif;
    }

    .page-header {
        background: linear-gradient(135deg, var(--primary-color), #0056b3);
        color: white;
        padding: 1.5rem;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
        box-shadow: var(--box-shadow);
    }

    .page-header h4 {
        color: white !important;
        font-weight: 700;
    }

    .card {
        border-radius: var(--border-radius);
        transition: all 0.3s ease;
        border: none !important;
    }

    .invoice-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .invoice-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    .invoice-avatar {
        width: 45px;
        height: 45px;
        background: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .badge {
        font-weight: 500;
        font-size: 0.75rem;
        padding: 0.4rem 0.8rem;
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-sm {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        border-color: var(--primary-color);
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .text-primary {
        color: var(--primary-color) !important;
    }

    .empty-state {
        padding: 2rem;
    }

    .empty-state i {
        opacity: 0.5;
    }

    /* Mobile Optimizations */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        
        .page-header {
            padding: 1rem;
            text-align: center;
        }
        
        .page-header .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
        
        .invoice-card .card-body {
            padding: 1rem !important;
        }
        
        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
        }
        
        .modal-dialog {
            margin: 0.5rem;
        }
    }

    /* Status Colors */
    .bg-success.bg-opacity-10 {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    
    .bg-warning.bg-opacity-10 {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    
    .bg-danger.bg-opacity-10 {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }
    
    .bg-info.bg-opacity-10 {
        background-color: rgba(13, 202, 240, 0.1) !important;
    }
    
    .bg-primary.bg-opacity-10 {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    /* Loading Animation */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Smooth Animations */
    * {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Print Styles */
    @media print {
        .btn, .modal, .page-header .badge {
            display: none !important;
        }
        
        .invoice-card {
            break-inside: avoid;
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
        
        body {
            background: white !important;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection