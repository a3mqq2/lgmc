@extends('layouts.'.get_area_name())
@section('title', 'تفاصيل طلب أوراق الخارج')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4 mt-4" style="margin-top: 120px !important;">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-file-alt text-primary me-2"></i>
                    تفاصيل الطلب #{{ str_pad($doctorMail->id, 6, '0', STR_PAD_LEFT) }}
                </h2>
            </div>
            <div class="col-auto">
                <div class="btn-group">
                    <a href="{{ route('doctor.doctor-mails') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>
                        رجوع
                    </a>

                    @if ($doctorMail->status == "under_edit")
                    <a href="{{ route('doctor.doctor-mails.edit', $doctorMail) }}" class="btn btn-info">
                        <i class="fa fa-edit me-2"></i>
                        تعديل
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($doctorMail->status == 'under_proccess' && get_area_name() == "admin")
    <div class="card border-0 shadow-sm mb-4 border-success">
        <div class="card-header bg-success text-light bg-opacity-10 border-0">
            <h5 class="mb-0">
                <i class="fas fa-check-circle me-2"></i>
                إجراءات الإكمال
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">الطلب جاهز للإكمال</h6>
                    <p class="text-muted">يمكنك إكمال الطلب وتغيير حالته إلى "مكتمل" </p>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-success me-2" onclick="completeRequest()">
                        <i class="fas fa-check-circle me-2"></i>
                        إكمال الطلب
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Status and Actions Card (Show only if under_approve) -->
    @if($doctorMail->status == 'under_approve' && get_area_name() == "admin")
    <div class="card border-0 shadow-sm mb-4 border-warning">
        <div class="card-header bg-warning bg-opacity-10 border-0">
            <h5 class="mb-0">
                <i class="fas fa-tasks me-2"></i>
                إجراءات الموافقة
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">الطلب في انتظار الموافقة</h6>
                    <p class="text-muted">يمكنك الموافقة على الطلب أو إرجاعه للتعديل مع إضافة ملاحظات للطبيب.</p>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-success me-2" onclick="approveRequest()">
                        <i class="fas fa-check-circle me-2"></i>
                        الموافقة على الطلب
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editRequestModal">
                        <i class="fas fa-edit me-2"></i>
                        إرجاع للتعديل
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Row -->
    <div class="row">
        <!-- Left Column - Request Details -->
        @if ($doctorMail->status == "under_edit")
        <div class="col-md-12">
            <div class="alert alert-warning">
              <i class="fa fa-info-circle"></i>
              الطلب قيد التعديل وذلك لسبب : 
              {{$doctorMail->edit_note}}
            </div>
        </div>
        @endif

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        الخدمات المطلوبة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم الخدمة</th>
                                    <th>المبلغ</th>
                                    <th>ذكر جهة العمل</th>
                                    <th>الملف المطلوب</th>
                                    <th>المرفقات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($doctorMail->services as $index => $service)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $service->service_name }}</td>
                                    <td>{{ number_format($service->amount, 2) }} د.ل</td>
                                    <td>
                                        @if($service->work_mention)
                                            <span class="badge bg-info">{{ $service->work_mention == 'with' ? 'مع ذكر جهة العمل' : 'بدون ذكر جهة العمل' }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($service->file_required)
                                            <span class="badge bg-warning">مطلوب</span>
                                        @else
                                            <span class="badge bg-secondary">غير مطلوب</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($service->file_path)
                                            <a href="{{ Storage::url($service->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-download me-1"></i>
                                                {{ $service->file_name ?? 'تحميل الملف' }}
                                            </a>
                                        @else
                                            <span class="text-muted">لا يوجد مرفق</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="2" class="text-end fw-bold">المجموع:</td>
                                    <td class="fw-bold">{{ number_format($doctorMail->total_services_amount, 2) }} د.ل</td>
                                    <td colspan="4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Emails Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-envelope me-2"></i>
                        عناوين البريد الإلكتروني
                    </h5>
                    <span class="badge bg-primary">{{ $doctorMail->emails->count() }} بريد إلكتروني</span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($doctorMail->emails as $email)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-envelope text-primary me-3"></i>
                                <div>
                                    <div class="fw-medium">{{ $email->email_value }}</div>
                                    <small class="text-muted">
                                        @if($email->is_new)
                                            <span class="badge bg-success">جديد</span>
                                        @else
                                            <span class="badge bg-secondary">موجود مسبقاً</span>
                                        @endif
                                        - {{ number_format($email->unit_price, 2) }} د.ل
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">سعر البريد الواحد:</small>
                                <p class="fw-bold mb-0">{{ number_format(50, 2) }} د.ل</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <small class="text-muted">إجمالي تكلفة البريد:</small>
                                <p class="fw-bold mb-0">{{ number_format($doctorMail->emails->count()*50, 2) }} د.ل</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Countries Card (if exists) -->
            @if($doctorMail->countries->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-globe me-2"></i>
                        الدول المطلوبة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach($doctorMail->countries as $country)
                        <div class="col-md-4">
                            <div class="p-2 bg-light rounded text-center">
                                <i class="fas fa-flag text-primary me-2"></i>
                                {{ $country->country_value}}

                                @if($country->is_new)
                                    <span class="badge bg-success ms-2">جديد</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Additional Information Card -->
            @if($doctorMail->notes || $doctorMail->extracted_before)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات إضافية
                    </h5>
                </div>
                <div class="card-body">
                    @if($doctorMail->extracted_before)
                    <div class="mb-3">
                        <label class="text-muted small">هل تم الاستخراج من قبل؟</label>
                        <p class="fw-bold">
                            نعم، في عام {{ $doctorMail->last_extract_year }}
                        </p>
                    </div>
                    @endif
                    @if($doctorMail->notes)
                    <div>
                        <label class="text-muted small">ملاحظات</label>
                        <p class="fw-bold">{{ $doctorMail->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Summary and Status -->
        <div class="col-lg-4">
            <!-- Status Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        حالة الطلب
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $statusClasses = [
                            'under_approve' => 'warning',
                            'under_edit' => 'info',
                            'under_payment' => 'primary',
                            'under_proccess' => 'info',
                            'done' => 'success',
                            'canceled' => 'danger'
                        ];
                        $statusIcons = [
                            'under_approve' => 'clock',
                            'under_edit' => 'edit',
                            'under_payment' => 'credit-card',
                            'under_proccess' => 'spinner',
                            'done' => 'check-circle',
                            'canceled' => 'times-circle'
                        ];
                        $statusLabels = [
                            'under_approve' => 'قيد الموافقة',
                            'under_edit' => 'قيد التعديل',
                            'under_payment' => 'قيد الدفع',
                            'under_proccess' => 'قيد الإجراء',
                            'done' => 'مكتمل',
                            'canceled' => 'ملغي'
                        ];
                    @endphp
                    <div class="text-center py-3">
                        <div class="mb-3">
                            <i class="fas fa-{{ $statusIcons[$doctorMail->status] ?? 'question' }} fa-3x text-{{ $statusClasses[$doctorMail->status] ?? 'secondary' }}"></i>
                        </div>
                        <h4>
                            <span class="badge bg-{{ $statusClasses[$doctorMail->status] ?? 'secondary' }} px-4 py-2">
                                {{ $statusLabels[$doctorMail->status] ?? $doctorMail->status }}
                            </span>
                        </h4>
                    </div>
                    <hr>
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-2">
                            <span>تاريخ الإنشاء:</span>
                            <span>{{ $doctorMail->created_at->format('Y/m/d h:i A') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>آخر تحديث:</span>
                            <span>{{ $doctorMail->updated_at->format('Y/m/d h:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Summary Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        الملخص المالي
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">الخدمات:</span>
                            <span class="fw-bold">{{ number_format($doctorMail->total_services_amount, 2) }} د.ل</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">البريد الإلكتروني:</span>
                            <span class="fw-bold">{{ number_format($doctorMail->emails->count()*50, 2) }} د.ل</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">المجموع الكلي:</span>
                            <span class="h5 mb-0 text-primary">{{ number_format($doctorMail->grand_total, 2) }} د.ل</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Notes History (if any) -->
    @if($doctorMail->edit_notes && $doctorMail->edit_notes->count() > 0)
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-light border-0">
            <h5 class="mb-0">
                <i class="fas fa-history me-2"></i>
                سجل ملاحظات التعديل
            </h5>
        </div>
        <div class="card-body">
            <div class="timeline">
                @foreach($doctorMail->edit_notes as $note)
                <div class="timeline-item">
                    <div class="timeline-marker"></div>
                    <div class="timeline-content">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0">{{ $note->user->name }}</h6>
                            <small class="text-muted">{{ $note->created_at->format('Y/m/d h:i A') }}</small>
                        </div>
                        <p class="mb-0">{{ $note->note }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@section('styles')
<style>
    .page-header {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
    }
    
    .avatar-placeholder {
        width: 100px;
        height: 100px;
        font-size: 2.5rem;
    }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 9px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -21px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #6c757d;
        border: 2px solid #fff;
        box-shadow: 0 0 0 3px #e9ecef;
    }
    
    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }
    
    @media print {
        .btn-group, .modal, .alert {
            display: none !important;
        }
        
        .card {
            break-inside: avoid;
            border: 1px solid #dee2e6 !important;
        }
    }
    
    .badge {
        font-weight: 500;
        padding: 0.375rem 0.75rem;
    }
    
    .table th {
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
    }

    .btn-outline-success:hover {
        background: linear-gradient(135deg, #28a745, #20c997);
        border-color: #28a745;
        color: white;
    }

    .modal-header.bg-success,
    .modal-header.bg-primary,
    .modal-header.bg-warning,
    .modal-header.bg-info,
    .modal-header.bg-secondary {
        border-bottom: none;
    }

    .form-check-input:checked {
        background-color: #28a745;
        border-color: #28a745;
    }

    .table-responsive {
        border-radius: 8px;
    }

    #internshipTable th {
        background-color: #f8f9fa !important;
        border: 1px solid #dee2e6;
        font-weight: 600;
    }

    #internshipTable td {
        border: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .card.border-warning {
        border-color: #ffc107 !important;
    }
</style>
@endsection

@section('scripts')
{{-- swal cdn --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- Bootstrap JS --}}
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Approve request function
    function approveRequest() {
        Swal.fire({
            title: 'تأكيد الموافقة',
            text: 'هل أنت متأكد من الموافقة على هذا الطلب؟',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، موافق',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approveForm').submit();
            }
        });
    }
    
    // Complete request function
    function completeRequest() {
        Swal.fire({
            title: 'تأكيد الإكمال',
            text: 'هل أنت متأكد من إكمال هذا الطلب؟ سيتم تغيير حالته إلى "مكتمل".',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'نعم، إكمال',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'جاري الإكمال...',
                    text: 'يرجى الانتظار',
                    icon: 'info',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading()
                    }
                });
                
                document.getElementById('completeForm').submit();
            }
        });
    }

    // Document preparation functions
    let currentServiceId = null;
    let currentServiceIndex = null;



    // Print functionality
    window.addEventListener('beforeprint', function() {
        document.body.classList.add('printing');
    });
    
    window.addEventListener('afterprint', function() {
        document.body.classList.remove('printing');
    });
</script>
@endsection