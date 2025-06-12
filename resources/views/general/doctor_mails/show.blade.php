@extends('layouts.'.get_area_name())
@section('title', 'تفاصيل طلب أوراق الخارج')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="fas fa-file-alt text-primary me-2"></i>
                    تفاصيل الطلب #{{ str_pad($doctorMail->id, 6, '0', STR_PAD_LEFT) }}
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mt-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">الرئيسية</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.doctor-mails.index') }}">طلبات أوراق الخارج</a></li>
                        <li class="breadcrumb-item active">تفاصيل الطلب</li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto">
                <div class="btn-group">
                    <a href="{{ route('admin.doctor-mails.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>
                        رجوع
                    </a>
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
            <!-- Doctor Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0">
                    <h5 class="mb-0">
                        <i class="fas fa-user-md me-2"></i>
                        معلومات الطبيب
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            @if($doctorMail->doctor->photo)
                                <img src="{{ asset($doctorMail->doctor->photo) }}" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2rem;">
                                    {{ substr($doctorMail->doctor->name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-10">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-muted small">اسم الطبيب</label>
                                    <p class="fw-bold">
                                        <a href="{{route(get_area_name().'.doctors.show', $doctorMail->doctor)}}">{{ $doctorMail->doctor->name }}</a>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">كود الطبيب</label>
                                    <p class="fw-bold">{{ $doctorMail->doctor->code }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">رقم الهاتف</label>
                                    <p class="fw-bold">{{ $doctorMail->doctor->phone }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-muted small">نوع الطبيب</label>
                                    <p class="fw-bold">{{ $doctorMail->doctor->type->value == "libyan" ? "ليبي" : "اجنبي" }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Card -->
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
                                    <th>الإجراءات</th>
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
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ Storage::url($service->file_path) }}" target="_blank" class="btn btn-outline-primary" title="عرض الملف">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ Storage::url($service->file_path) }}" download="{{ $service->file_name ?? 'attachment' }}" class="btn btn-outline-success" title="تحميل الملف">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                @if(get_area_name() == "admin")
                                                <button type="button" class="btn btn-outline-info" onclick="saveToDoctor({{ $service->id }}, '{{ $service->file_path }}', '{{ $service->file_name ?? $service->service_name }}')" title="حفظ في ملف الطبيب">
                                                    <i class="fas fa-save"></i>
                                                </button>
                                                @endif
                                            </div>
                                            <small class="d-block text-muted mt-1">{{ $service->file_name ?? 'ملف مرفق' }}</small>
                                        @else
                                            <span class="text-muted">لا يوجد مرفق</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(get_area_name() == "admin" && $doctorMail->status == 'under_proccess')
                                            <!-- أزرار الإجراءات الأخرى إن وجدت -->
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

            <!-- Modal لحفظ الملف في ملف الطبيب -->
            <div class="modal fade" id="saveFileModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="saveFileForm" action="{{ route('admin.doctor-mails.save-file-to-doctor', $doctorMail) }}" method="POST">
                            @csrf
                            <input type="hidden" name="service_id" id="saveFileServiceId">
                            <input type="hidden" name="file_path" id="saveFileFilePath">
                            
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title text-light">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ الملف في ملف الطبيب
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">اسم الملف <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="file_name" id="saveFileFileName" required>
                                    <small class="text-muted">سيتم حفظ الملف بهذا الاسم في ملف الطبيب</small>
                                </div>
                            </div>
                            
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ الملف
                                </button>
                            </div>
                        </form>
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

<!-- Edit Request Modal -->
<div class="modal fade" id="editRequestModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.doctor-mails.reject', $doctorMail) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="under_edit">
                
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>
                        إرجاع الطلب للتعديل
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        سيتم إرسال إشعار للطبيب بضرورة تعديل الطلب
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">ملاحظات التعديل <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="edit_note" rows="4" required 
                                  placeholder="اكتب ملاحظاتك هنا... مثل: يرجى إرفاق صورة واضحة للشهادة">{{ old('edit_note') }}</textarea>
                        <small class="text-muted">هذه الملاحظات سيراها الطبيب عند محاولة تعديل الطلب</small>
                    </div>
                </div>
                
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane me-2"></i>
                        إرسال للتعديل
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Document Preparation Modals -->

<!-- Good Standing Modal -->
<div class="modal fade" id="goodStandingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="goodStandingForm" onsubmit="prepareDocument(event, 'good_standing')">
                @csrf
                <input type="hidden" name="document_type" value="good_standing">
                <input type="hidden" name="service_id" id="goodStandingServiceId">
                
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-certificate me-2"></i>
                        إعداد شهادة حسن السيرة والسلوك
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-4x text-success"></i>
                    </div>
                    <h4 class="mb-3">هل تريد إعداد المستند؟</h4>
                    <p class="text-muted">سيتم إعداد شهادة حسن السيرة والسلوك للطبيب</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-cog me-2"></i>
                        إعداد المستند
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Specialist Modal -->
<div class="modal fade" id="specialistModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="specialistForm" onsubmit="prepareDocument(event, 'specialist')">
                @csrf
                <input type="hidden" name="document_type" value="specialist">
                <input type="hidden" name="service_id" id="specialistServiceId">
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-user-md me-2"></i>
                        إعداد شهادة الاختصاص
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">نوع الطبيب <span class="text-danger">*</span></label>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="doctor_type" id="specialist" value="specialist">
                                    <label class="form-check-label" for="specialist">
                                        <i class="fas fa-user-md me-2"></i>
                                        اختصاصي
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="doctor_type" id="consultant" value="consultant">
                                    <label class="form-check-label" for="consultant">
                                        <i class="fas fa-user-tie me-2"></i>
                                        استشاري
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cog me-2"></i>
                        إعداد المستند
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Approve Request Form (Hidden) -->
<form id="approveForm" action="{{ route('admin.doctor-mails.approve', $doctorMail) }}" method="POST" class="d-none">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="under_payment">
</form>
<form id="completeForm" action="{{ route('admin.doctor-mails.complete', $doctorMail) }}" method="POST" class="d-none">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="done">
</form>
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

// وظيفة حفظ الملف في ملف الطبيب
function saveToDoctor(serviceId, filePath, fileName) {
    // تعيين القيم في النموذج
    document.getElementById('saveFileServiceId').value = serviceId;
    document.getElementById('saveFileFilePath').value = filePath;
    document.getElementById('saveFileFileName').value = fileName;
    
    // فتح النافذة المنبثقة
    var modal = new bootstrap.Modal(document.getElementById('saveFileModal'));
    modal.show();
}

// معالج إرسال نموذج حفظ الملف
document.getElementById('saveFileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // إظهار رسالة التحميل
    Swal.fire({
        title: 'جاري الحفظ...',
        text: 'يتم حفظ الملف في ملف الطبيب',
        icon: 'info',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading()
        }
    });
    
    // إرسال النموذج باستخدام AJAX
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'تم الحفظ بنجاح',
                text: data.message || 'تم حفظ الملف في ملف الطبيب بنجاح',
                confirmButtonText: 'حسناً'
            });
            
            // إغلاق النافذة المنبثقة
            bootstrap.Modal.getInstance(document.getElementById('saveFileModal')).hide();
            
            // تحديث الزر ليظهر أنه تم الحفظ
            updateSaveButton(data.service_id);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: data.message || 'حدث خطأ أثناء حفظ الملف',
                confirmButtonText: 'حسناً'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: 'حدث خطأ في الاتصال',
            confirmButtonText: 'حسناً'
        });
    });
});

// تحديث زر الحفظ بعد النجاح
function updateSaveButton(serviceId) {
    const button = document.querySelector(`button[onclick*="saveToDoctor(${serviceId}"]`);
    if (button) {
        button.classList.remove('btn-outline-info');
        button.classList.add('btn-success');
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.setAttribute('title', 'تم الحفظ');
        button.disabled = true;
    }
}
</script>
@endsection