@extends('layouts.' . get_area_name())
@section('title', 'عرض تفاصيل المنشأة الطبية')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">{{ $medicalFacility->name }}</h2>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar-plus me-1"></i>
                        تم الإنشاء: {{ $medicalFacility->created_at->format('Y-m-d H:i') }}
                    </p>
                </div>
                <div>
                    <span class="badge {{ $medicalFacility->membership_status->badgeClass() }} fs-6 px-3 py-2">
                        <i class="fas fa-circle me-1"></i>
                        {{ $medicalFacility->membership_status->label() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-light text-primary p-3">
                    <ul class="nav nav-tabs card-header-tabs border-0">
                        <li class="nav-item">
                            <a class="nav-link active text-primary border-0" id="tab-details" data-bs-toggle="tab" href="#details" role="tab">
                                <i class="fas fa-info-circle me-2"></i>البيانات الأساسية
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-primary border-0" id="tab-files" data-bs-toggle="tab" href="#files" role="tab">
                                <i class="fas fa-folder-open me-2"></i>المستندات 
                                <span class="badge bg-light text-primary ms-1">{{ $medicalFacility->files->count() }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-primary border-0" id="tab-files" data-bs-toggle="tab" href="#invoices" role="tab">
                                <i class="fas fa-file me-2"></i> الفواتير  
                                <span class="badge bg-light text-primary ms-1">{{ $medicalFacility->invoices->count() }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content overflow-hidden">
                        {{-- البيانات الأساسية --}}
                        <div class="tab-pane fade show active" id="details" role="tabpanel">
                            <div class="row g-4">
                                <!-- معلومات المنشأة -->
                                <div class="col-lg-8">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-light border-0">
                                            <h5 class="card-title mb-0 text-primary">
                                                <i class="fas fa-building me-2"></i>معلومات المنشأة
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <td class="fw-bold text-muted" style="width: 30%;">
                                                                <i class="fas fa-hospital me-2 text-primary"></i>اسم المنشأة
                                                            </td>
                                                            <td class="fw-semibold">{{ $medicalFacility->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold text-muted">
                                                                <i class="fas fa-map-marker-alt me-2 text-danger"></i>الموقع
                                                            </td>
                                                            <td>{{ $medicalFacility->address ?? 'غير محدد' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold text-muted">
                                                                <i class="fas fa-phone me-2 text-success"></i>رقم الهاتف
                                                            </td>
                                                            <td>
                                                                @if($medicalFacility->phone_number)
                                                                    <a href="tel:{{ $medicalFacility->phone_number }}" class="text-decoration-none">
                                                                        {{ $medicalFacility->phone_number }}
                                                                    </a>
                                                                @else
                                                                    غير محدد
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold text-muted">
                                                                <i class="fas fa-user-md me-2 text-info"></i>الطبيب المسؤول
                                                            </td>
                                                            <td>
                                                                @if($medicalFacility->manager)
                                                                    <a href="{{ route(get_area_name().'.doctors.show', $medicalFacility->manager_id) }}" 
                                                                       class="text-decoration-none fw-semibold text-primary">
                                                                        <i class="fas fa-external-link-alt me-1"></i>
                                                                        {{ $medicalFacility->manager->name }}
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">غير محدد</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="fw-bold text-muted">
                                                                <i class="fas fa-tag me-2 text-warning"></i>نوع المنشأة
                                                            </td>
                                                            <td>
                                                                @if($medicalFacility->type == 'private_clinic')
                                                                    <span class="badge bg-primary">
                                                                        <i class="fas fa-stethoscope me-1"></i>عيادة خاصة
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-info">
                                                                        <i class="fas fa-briefcase-medical me-1"></i>خدمات طبية
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- معلومات الاشتراك والحالة -->
                                <div class="col-lg-4">
                                    <!-- بطاقة الإجراءات -->
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header bg-light text-primary border-0">
                                            <h5 class="card-title mb-0 text-primary">
                                                <i class="fas fa-cogs me-2"></i>الإجراءات
                                            </h5>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="d-grid gap-2">
                                                <!-- تعديل البيانات -->
                                                <a href="{{ route(get_area_name().'.medical-facilities.edit', $medicalFacility->id) }}" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit me-2"></i>تعديل البيانات
                                                </a>
                                                
                                                <!-- طباعة البيانات -->
                                                <button type="button" class="btn btn-outline-info btn-sm" onclick="printFacilityData()">
                                                    <i class="fas fa-print me-2"></i>طباعة البيانات
                                                </button>
                                                
                                                <!-- طباعة إذن المزاولة -->
                                                @if($medicalFacility->membership_status->value == 'active')
                                                <a 
                                                    href="{{ route(get_area_name().'.medical-facilities.print-license', $medicalFacility->id) }}"
                                                   target="_blank" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-file-medical me-2"></i>طباعة إذن المزاولة
                                                </a>
                                                @else
                                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="المنشأة غير نشطة">
                                                    <i class="fas fa-file-medical me-2"></i>طباعة إذن المزاولة
                                                </button>
                                                @endif
                                                
                                                <!-- حذف المنشأة -->
                                                <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDelete()">
                                                    <i class="fas fa-trash me-2"></i>حذف المنشأة
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- بطاقة معلومات الاشتراك -->
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-light border-0">
                                            <h5 class="card-title mb-0 text-primary">
                                                <i class="fas fa-calendar-check me-2"></i>معلومات الاشتراك
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-muted small">الحالة الحالية</label>
                                                <div>
                                                    <span class="badge {{ $medicalFacility->membership_status->badgeClass() }} fs-6 px-3 py-2">
                                                        <i class="fas fa-circle me-1"></i>
                                                        {{ $medicalFacility->membership_status->label() }}
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-muted small">تاريخ الإنشاء</label>
                                                <div class="text-dark">
                                                    <i class="fas fa-calendar-plus me-1 text-success"></i>
                                                    {{ $medicalFacility->created_at->format('Y-m-d H:i') }}
                                                </div>
                                            </div>

                                            @if($medicalFacility->updated_at != $medicalFacility->created_at)
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-muted small">آخر تحديث</label>
                                                <div class="text-dark">
                                                    <i class="fas fa-calendar-edit me-1 text-warning"></i>
                                                    {{ $medicalFacility->updated_at->format('Y-m-d H:i') }}
                                                </div>
                                            </div>
                                            @endif

                                            @if($medicalFacility->subscription_expiry_date)
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-muted small">تاريخ انتهاء الاشتراك</label>
                                                <div class="text-dark">
                                                    @php
                                                        $expiryDate = \Carbon\Carbon::parse($medicalFacility->subscription_expiry_date);
                                                        $daysLeft = $expiryDate->diffInDays(now(), false);
                                                        $isExpired = $daysLeft > 0;
                                                        $isExpiringSoon = $daysLeft >= -30 && $daysLeft < 0;
                                                    @endphp
                                                    
                                                    <i class="fas fa-calendar-times me-1 
                                                        {{ $isExpired ? 'text-danger' : ($isExpiringSoon ? 'text-warning' : 'text-success') }}"></i>
                                                    {{ $expiryDate->format('Y-m-d') }}
                                                    
                                                    @if($isExpired)
                                                        <div class="badge bg-danger mt-1">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            منتهي الصلاحية منذ {{ abs($daysLeft) }} يوم
                                                        </div>
                                                    @elseif($isExpiringSoon)
                                                        <div class="badge bg-warning text-dark mt-1">
                                                            <i class="fas fa-clock me-1"></i>
                                                            ينتهي خلال {{ abs($daysLeft) }} يوم
                                                        </div>
                                                    @else
                                                        <div class="badge bg-success mt-1">
                                                            <i class="fas fa-check me-1"></i>
                                                            صالح لمدة {{ abs($daysLeft) }} يوم
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif

                                            @if($medicalFacility->branch)
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-muted small">الفرع</label>
                                                <div class="text-dark">
                                                    <i class="fas fa-building me-1 text-info"></i>
                                                    {{ $medicalFacility->branch->name }}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- المستندات --}}
                        <div class="tab-pane fade" id="files" role="tabpanel">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 text-primary">
                                        <i class="fas fa-folder-open me-2"></i>المستندات المرفقة
                                    </h5>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDocumentModal">
                                        <i class="fas fa-plus me-1"></i>إضافة مستند
                                    </button>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width:5%;" class="text-center">#</th>
                                                    <th style="width:10%;" class="text-center">معاينة</th>
                                                    <th>اسم الملف</th>
                                                    <th>نوع الملف</th>
                                                    <th>وقت الرفع</th>
                                                    <th class="text-center" style="width:20%;">الإجراءات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($medicalFacility->files as $file)
                                                    @php
                                                        $url = Storage::url($file->file_path);
                                                        $ext = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
                                                        $isImage = in_array($ext, ['png','jpg','jpeg','gif','bmp','webp']);
                                                        $isPdf = $ext === 'pdf';
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center fw-bold text-muted">{{ $loop->iteration }}</td>
                                                        <td class="text-center">
                                                            @if($isImage)
                                                                <img src="{{ $url }}" class="img-thumbnail shadow-sm" style="height:50px; width:50px; object-fit: cover;" alt="preview">
                                                            @elseif($isPdf)
                                                                <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                            @else
                                                                <i class="fas fa-file fa-2x text-secondary"></i>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="fw-semibold">{{ $file->file_name }}</div>
                                                            @if($file->renew_number)
                                                                <span class="badge bg-warning text-dark mt-1">
                                                                    <i class="fas fa-sync-alt me-1"></i>
                                                                    طلب تجديد #{{ $file->renew_number }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($file->fileType)
                                                                <span class="badge bg-light text-dark">{{ $file->fileType->name }}</span>
                                                            @else
                                                                <span class="text-muted">غير محدد</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="text-muted small">
                                                                <i class="fas fa-clock me-1"></i>
                                                                {{ \Carbon\Carbon::parse($file->created_at)->format('Y-m-d H:i') }}
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="btn-group" role="group">
                                                                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-success" title="عرض">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a download href="{{ $url }}" class="btn btn-sm btn-outline-primary" title="تحميل">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                                    <!-- Replace the existing edit button in your table with this: -->
                                                                    <button type="button" 
                                                                            class="btn btn-sm btn-outline-warning" 
                                                                            title="تعديل"
                                                                            onclick="openEditModal(
                                                                                '{{ $file->id }}',
                                                                                '{{ $file->file_name }}',
                                                                                '{{ $file->file_type_id }}',
                                                                                '{{ $file->fileType ? $file->fileType->name : 'غير محدد' }}',
                                                                                '{{ $url }}',
                                                                                '{{ $ext }}'
                                                                            )">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                                                                                    {{-- destroy --}}
                                                                <form action="{{route(get_area_name().'.medical-facility-files.destroy', $file)}}"  method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا المستند؟')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted py-5">
                                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                                            <div class="h5">لا توجد مستندات مرفقة</div>
                                                            <p class="mb-0">لم يتم رفع أي مستندات لهذه المنشأة بعد.</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                       

                        <div class="tab-pane fade" id="invoices" role="tabpanel">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 text-primary">
                                        <i class="fas fa-folder-open me-2"></i>فواتير المنشأة الطبية 
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>رقم الفاتورة</th>
                                                    <th>الوصف</th>
                                                    <th>المستخدم</th>
                                                    <th>المبلغ</th>
                                                    <th>الحالة</th>
                                                    <th>تاريخ الإنشاء</th>
                                                    <th>اعدادات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($medicalFacility->invoices as $invoice)
                                                    <tr>
                                                        <td>{{ $invoice->id }}</td>
                                                        <td>{{ $invoice->id }}</td>
                                                        <td>{{ $invoice->description }}</td>
                                                        <td>{{ $invoice->user?->name ?? '-' }}</td>
                                                        <td>{{ number_format($invoice->amount, 2) }} د.ل</td>
                                                        <td>
                                                           <span class="badge {{$invoice->status->badgeClass()}}">
                                                                {{$invoice->status->label()}}
                                                           </span>
                                                        </td>
                                                        <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                                                        <td>
                                                                <a href="{{ route(get_area_name().'.invoices.print', $invoice->id) }}" class="btn btn-sm btn-secondary">
                                                                    طباعة
                                                                </a>
                                                            @if ($invoice->status->value == "unpaid" && auth()->user()->vault)
                                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#received_{{$invoice->id}}">
                                                                استلام القيمة <i class="fa fa-check"></i>
                                                            </button>
                                                            {{-- مودال استلام القيمة --}}
                                                            <div class="modal fade" id="received_{{$invoice->id}}" tabindex="-1" aria-labelledby="received_{{$invoice->id}}Label" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <form method="POST" action="{{ route(get_area_name() . '.invoices.received', ['invoice' => $invoice->id]) }}">
                                                                            @csrf
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="received_{{$invoice->id}}Label">تآكيد إستلام القيمة</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="mb-3">
                                                                                    <label for="notes" class="form-label">ملاحظات - اختياري</label>
                                                                                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                                                <button type="submit" class="btn btn-primary">موافقة</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center">لا توجد فواتير متاحة.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Change Form -->
    @if ($medicalFacility->membership_status->value == 'under_approve' || $medicalFacility->membership_status->value == 'under_renew')
    <div class="row mt-4">
        <div class="col-12">
            <form action="{{route(get_area_name().'.medical-facilities.change-status', $medicalFacility)}}" method="POST">
                @csrf
                @method('POST')
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-gradient-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-exchange-alt me-2"></i>تحويل حالة المنشأة
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="status-select" class="form-label fw-bold">اختر الحالة الجديدة</label>
                                <select id="status-select" name="status" class="form-select form-select-lg">
                                    <option value="active">
                                        <i class="fas fa-check-circle"></i> تمت الموافقة
                                    </option>
                                    <option value="under_edit">
                                        <i class="fas fa-edit"></i> قيد التعديل
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-12 d-none" id="reason-group">
                                <label for="reason" class="form-label fw-bold">سبب التعديل</label>
                                <textarea id="reason" name="edit_reason" class="form-control" rows="4" 
                                          placeholder="يرجى كتابة السبب التفصيلي للتعديل المطلوب..."></textarea>
                            </div>

                            <div class="col-md-12 d-none" id="paid-switch">
                                <div class="form-check form-switch">
                                    <input name="is_paid" class="form-check-input" type="checkbox" id="invoice-paid">
                                    <label class="form-check-label fw-bold" for="invoice-paid">
                                        <i class="fas fa-money-bill-wave me-1"></i>الفاتورة مدفوعة
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" id="save-status" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-save me-2"></i>حفظ التغيير
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<!-- Add Document Modal -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form  action="{{route(get_area_name().'.medical-facilities.upload-file', $medicalFacility)}}"   method="POST" enctype="multipart/form-data" id="uploadDocumentForm">
                @csrf
                <div class="modal-header bg-light text-primary">
                    <h5 class="modal-title text-primary" id="addDocumentModalLabel">
                        <i class="fas fa-upload me-2"></i>إضافة مستند جديد
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="document_type" class="form-label fw-bold">
                                <i class="fas fa-tag me-1"></i>نوع المستند <span class="text-danger">*</span>
                            </label>
                            <select name="file_type_id" id="document_type" class="form-select" required>
                                <option value="">اختر نوع المستند...</option>
                                @foreach($medicalFacilityFileTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">حدد نوع المستند المراد رفعه</div>
                        </div>


                        <!-- اختيار الملف -->
                        <div class="col-12">
                            <label for="document_file" class="form-label fw-bold">
                                <i class="fas fa-paperclip me-1"></i>الملف <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="file" id="document_file" class="form-control" 
                                   accept=".pdf,.jpg,.jpeg,.png,.gif,.bmp,.webp" required>
                            <div class="form-text">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    الملفات المدعومة: PDF, JPG, JPEG, PNG, GIF, BMP, WEBP
                                    <br>
                                    الحد الأقصى لحجم الملف: 10 ميجابايت
                                </small>
                            </div>
                        </div>

                        <!-- معاينة الملف -->
                        <div class="col-12" id="file_preview" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <h6 class="card-title">
                                        <i class="fas fa-eye me-1"></i>معاينة الملف
                                    </h6>
                                    <div id="preview_content"></div>
                                    <div id="file_info" class="mt-2 small text-muted"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn">
                        <i class="fas fa-upload me-1"></i>رفع المستند
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Document Modal -->
<div class="modal fade" id="editDocumentModal" tabindex="-1" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editDocumentForm"  method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light text-warning">
                    <h5 class="modal-title text-warning" id="editDocumentModalLabel">
                        <i class="fas fa-edit me-2"></i>تعديل المستند
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Current File Info -->
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="fas fa-info-circle me-1"></i>معلومات الملف الحالي
                                </h6>
                                <div id="current-file-info">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="mb-1"><strong>اسم الملف:</strong> <span id="current-file-name"></span></p>
                                            <p class="mb-0"><strong>نوع المستند:</strong> <span id="current-file-type"></span></p>
                                        </div>
                                        <div class="col-md-4 text-center">
                                            <div id="current-file-preview"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Type Selection -->
                        <div class="col-md-12">
                            <label for="edit_document_type" class="form-label fw-bold">
                                <i class="fas fa-tag me-1"></i>نوع المستند <span class="text-danger">*</span>
                            </label>
                            <select name="file_type_id" id="edit_document_type" class="form-select" required>
                                <option value="">اختر نوع المستند...</option>
                                @foreach($medicalFacilityFileTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">يمكنك تغيير نوع المستند</div>
                        </div>

                        <!-- New File Upload (Optional) -->
                        <div class="col-12">
                            <label for="edit_document_file" class="form-label fw-bold">
                                <i class="fas fa-paperclip me-1"></i>استبدال الملف (اختياري)
                            </label>
                            <input type="file" name="file" id="edit_document_file" class="form-control" 
                                   accept=".pdf,.jpg,.jpeg,.png,.gif,.bmp,.webp">
                            <div class="form-text">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    اترك هذا الحقل فارغاً إذا كنت لا تريد تغيير الملف
                                    <br>
                                    الملفات المدعومة: PDF, JPG, JPEG, PNG, GIF, BMP, WEBP
                                    <br>
                                    الحد الأقصى لحجم الملف: 10 ميجابايت
                                </small>
                            </div>
                        </div>

                        <!-- New File Preview -->
                        <div class="col-12" id="edit_file_preview" style="display: none;">
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <h6 class="card-title">
                                        <i class="fas fa-eye me-1"></i>معاينة الملف الجديد
                                    </h6>
                                    <div id="edit_preview_content"></div>
                                    <div id="edit_file_info" class="mt-2 small text-muted"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-warning" id="updateBtn">
                        <i class="fas fa-save me-1"></i>حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add this script section to your existing scripts -->

<style>
/* Custom Gradient Backgrounds */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%) !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%) !important;
}

/* Enhanced Tab Styling */
.nav-tabs .nav-link {
    border: none !important;
    transition: all 0.3s ease;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.nav-tabs .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

.nav-tabs .nav-link.active {
    background-color: rgba(255, 255, 255, 0.2) !important;
    font-weight: 600;
}

/* Enhanced Table Styling */
.table > tbody > tr > td {
    padding: 1rem 0.75rem;
    border-bottom: 1px solid #e9ecef;
}

.table > tbody > tr:hover {
    background-color: #f8f9fa;
}

/* Card Enhancements */
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

/* Badge Animations */
.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.05);
}

/* Button Group Enhancements */
.btn-group .btn {
    margin: 0 2px;
    border-radius: 0.375rem !important;
}

/* Actions Card Button Styling */
.card .btn-outline-primary:hover {
    background-color: #007bff;
    color: white;
    transform: translateY(-1px);
}

.card .btn-outline-info:hover {
    background-color: #17a2b8;
    color: white;
    transform: translateY(-1px);
}

.card .btn-outline-success:hover {
    background-color: #28a745;
    color: white;
    transform: translateY(-1px);
}

.card .btn-outline-danger:hover {
    background-color: #dc3545;
    color: white;
    transform: translateY(-1px);
}

.card .btn {
    transition: all 0.3s ease;
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }
    
    .print-only {
        display: block !important;
    }
    
    .card {
        border: 1px solid #333 !important;
        box-shadow: none !important;
    }
    
    .card-header {
        background-color: #f8f9fa !important;
        color: #333 !important;
    }
}

/* Status Indicators */
.text-success { color: #28a745 !important; }
.text-warning { color: #ffc107 !important; }
.text-danger { color: #dc3545 !important; }
.text-info { color: #17a2b8 !important; }
.text-primary { color: #007bff !important; }

/* Responsive Adjustments */
@media (max-width: 768px) {
    .card-body {
        padding: 1.5rem !important;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin: 2px 0;
        width: 100%;
    }
}
</style>
@endsection

@section('scripts')
<!-- Include SweetAlert2 for confirmations -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('status-select');
    const reasonGroup = document.getElementById('reason-group');
    const paidSwitch = document.getElementById('paid-switch');

    // Only run if elements exist (status change form is present)
    if (select) {
        function toggleFields() {
            if (select.value === 'under_edit') {
                reasonGroup.classList.remove('d-none');
                paidSwitch.classList.add('d-none');
            } else {
                reasonGroup.classList.add('d-none');
                paidSwitch.classList.remove('d-none');
            }
        }

        select.addEventListener('change', toggleFields);
        toggleFields(); // للتأكد من الحالة الافتراضية عند التحميل
    }

    // Add hover effects to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 0.5rem 1rem rgba(0, 0, 0, 0.15)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });
});

// Function to print facility data
function printFacilityData() {
    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    
    // Get the facility data
    const facilityName = '{{ $medicalFacility->name }}';
    const facilityAddress = '{{ $medicalFacility->address ?? "غير محدد" }}';
    const facilityPhone = '{{ $medicalFacility->phone_number ?? "غير محدد" }}';
    const facilityType = '{{ $medicalFacility->type == "private_clinic" ? "عيادة خاصة" : "خدمات طبية" }}';
    const managerName = '{{ $medicalFacility->manager->name ?? "غير محدد" }}';
    const createdDate = '{{ $medicalFacility->created_at->format("Y-m-d H:i") }}';
    const status = '{{ $medicalFacility->membership_status->label() }}';
    
    // Create the HTML content for printing
    const printContent = `
        <!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>بيانات المنشأة الطبية - ${facilityName}</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    direction: rtl;
                    text-align: right;
                    margin: 20px;
                    background: white;
                }
                .header {
                    text-align: center;
                    border-bottom: 2px solid #007bff;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .logo {
                    font-size: 24px;
                    font-weight: bold;
                    color: #007bff;
                    margin-bottom: 10px;
                }
                .title {
                    font-size: 20px;
                    font-weight: bold;
                    color: #333;
                }
                .info-section {
                    margin: 20px 0;
                }
                .info-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 10px 0;
                    border-bottom: 1px solid #eee;
                }
                .info-label {
                    font-weight: bold;
                    color: #666;
                    width: 30%;
                }
                .info-value {
                    color: #333;
                    width: 65%;
                }
                .footer {
                    margin-top: 40px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                    border-top: 1px solid #eee;
                    padding-top: 20px;
                }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="logo">النقابة العامة للاطباء</div>
                <div class="title">بيانات المنشأة الطبية</div>
            </div>
            
            <div class="info-section">
                <div class="info-row">
                    <div class="info-label">اسم المنشأة:</div>
                    <div class="info-value">${facilityName}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">نوع المنشأة:</div>
                    <div class="info-value">${facilityType}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">الموقع:</div>
                    <div class="info-value">${facilityAddress}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">رقم الهاتف:</div>
                    <div class="info-value">${facilityPhone}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">الطبيب المسؤول:</div>
                    <div class="info-value">${managerName}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">تاريخ الإنشاء:</div>
                    <div class="info-value">${createdDate}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">الحالة:</div>
                    <div class="info-value">${status}</div>
                </div>
            </div>
            
            <div class="footer">
                <p>تم طباعة هذا التقرير بتاريخ: ${new Date().toLocaleDateString('ar-SA')}</p>
            </div>
        </body>
        </html>
    `;
    
    // Write content to the new window and print
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Wait for content to load then print
    printWindow.onload = function() {
        printWindow.print();
        printWindow.close();
    };
}

// Function to confirm deletion
function confirmDelete() {
    Swal.fire({
        title: 'تأكيد الحذف',
        text: 'هل أنت متأكد من أنك تريد حذف هذه المنشأة الطبية؟ لا يمكن التراجع عن هذا الإجراء!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'نعم، احذف المنشأة',
        cancelButtonText: 'إلغاء',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Create and submit a delete form
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route(get_area_name().".medical-facilities.destroy", $medicalFacility->id) }}';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add DELETE method
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);
            
            // Add form to body and submit
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// File upload preview functionality
document.getElementById('document_file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('file_preview');
    const previewContent = document.getElementById('preview_content');
    const fileInfo = document.getElementById('file_info');
    
    if (file) {
        // Show preview section
        preview.style.display = 'block';
        
        // Display file info
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        fileInfo.innerHTML = `
            <strong>اسم الملف:</strong> ${file.name}<br>
            <strong>الحجم:</strong> ${fileSize} ميجابايت<br>
            <strong>النوع:</strong> ${file.type}
        `;
        
        // Check file size (10MB limit)
        if (file.size > 10 * 1024 * 1024) {
            Swal.fire({
                icon: 'error',
                title: 'حجم الملف كبير جداً',
                text: 'يجب أن يكون حجم الملف أقل من 10 ميجابايت',
                confirmButtonText: 'موافق'
            });
            e.target.value = '';
            preview.style.display = 'none';
            return;
        }
        
        // Preview based on file type
        const fileType = file.type.toLowerCase();
        
        if (fileType.startsWith('image/')) {
            // Image preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContent.innerHTML = `
                    <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px; max-width: 100%;" alt="معاينة الصورة">
                `;
            };
            reader.readAsDataURL(file);
        } else if (fileType === 'application/pdf') {
            // PDF preview
            previewContent.innerHTML = `
                <div class="text-center p-3">
                    <i class="fas fa-file-pdf fa-4x text-danger mb-2"></i>
                    <div>ملف PDF - ${file.name}</div>
                </div>
            `;
        } else {
            // Other file types
            previewContent.innerHTML = `
                <div class="text-center p-3">
                    <i class="fas fa-file fa-4x text-secondary mb-2"></i>
                    <div>ملف - ${file.name}</div>
                </div>
            `;
        }
        
        // Auto-fill document name if empty
        const documentNameField = document.getElementById('document_name');
        if (!documentNameField.value.trim()) {
            // Remove file extension for cleaner name
            const nameWithoutExt = file.name.replace(/\.[^/.]+$/, '');
            documentNameField.value = nameWithoutExt;
        }
    } else {
        preview.style.display = 'none';
    }
});

// Form submission with loading state
document.getElementById('uploadDocumentForm').addEventListener('submit', function(e) {
    const uploadBtn = document.getElementById('uploadBtn');
    const originalText = uploadBtn.innerHTML;
    
    // Show loading state
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري الرفع...';
    
    // Validate file size again before submission
    const fileInput = document.getElementById('document_file');
    if (fileInput.files[0] && fileInput.files[0].size > 10 * 1024 * 1024) {
        e.preventDefault();
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = originalText;
        
        Swal.fire({
            icon: 'error',
            title: 'حجم الملف كبير جداً',
            text: 'يجب أن يكون حجم الملف أقل من 10 ميجابايت',
            confirmButtonText: 'موافق'
        });
        return;
    }
    
    // Reset button state after a delay if form doesn't submit
    setTimeout(() => {
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = originalText;
    }, 10000);
});

// Reset modal when closed
document.getElementById('addDocumentModal').addEventListener('hidden.bs.modal', function() {
    // Reset form
    document.getElementById('uploadDocumentForm').reset();
    
    // Hide preview
    document.getElementById('file_preview').style.display = 'none';
    
    // Reset button
    const uploadBtn = document.getElementById('uploadBtn');
    uploadBtn.disabled = false;
    uploadBtn.innerHTML = '<i class="fas fa-upload me-1"></i>رفع المستند';
});
</script>
<!-- Add this script section to your existing scripts -->
<script>
    // Edit Document Modal Functionality
    let currentEditingFile = null;
    
    function openEditModal(fileId, fileName, fileTypeId, fileTypeName, fileUrl, fileExt) {
        currentEditingFile = {
            id: fileId,
            name: fileName,
            typeId: fileTypeId,
            typeName: fileTypeName,
            url: fileUrl,
            ext: fileExt
        };
        
        // Set form action
        const medicalFacilityId = {{ $medicalFacility->id }};
        const form = document.getElementById('editDocumentForm');
        form.action = `/{{ get_area_name() }}/medical-facilities/${medicalFacilityId}/files/${fileId}`;
        
        // Set current file info
        document.getElementById('current-file-name').textContent = fileName;
        document.getElementById('current-file-type').textContent = fileTypeName;
        
        // Set current file type in select
        document.getElementById('edit_document_type').value = fileTypeId;
        
        // Show current file preview
        const previewDiv = document.getElementById('current-file-preview');
        const isImage = ['png','jpg','jpeg','gif','bmp','webp'].includes(fileExt.toLowerCase());
        const isPdf = fileExt.toLowerCase() === 'pdf';
        
        if (isImage) {
            previewDiv.innerHTML = `<img src="${fileUrl}" class="img-thumbnail" style="height:60px; width:60px; object-fit: cover;" alt="preview">`;
        } else if (isPdf) {
            previewDiv.innerHTML = '<i class="fas fa-file-pdf fa-3x text-danger"></i>';
        } else {
            previewDiv.innerHTML = '<i class="fas fa-file fa-3x text-secondary"></i>';
        }
        
        // Show modal
        const editModal = new bootstrap.Modal(document.getElementById('editDocumentModal'));
        editModal.show();
    }
    
    // File preview for edit modal
    document.getElementById('edit_document_file').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('edit_file_preview');
        const previewContent = document.getElementById('edit_preview_content');
        const fileInfo = document.getElementById('edit_file_info');
        
        if (file) {
            // Show preview section
            preview.style.display = 'block';
            
            // Display file info
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            fileInfo.innerHTML = `
                <strong>اسم الملف:</strong> ${file.name}<br>
                <strong>الحجم:</strong> ${fileSize} ميجابايت<br>
                <strong>النوع:</strong> ${file.type}
            `;
            
            // Check file size (10MB limit)
            if (file.size > 10 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'حجم الملف كبير جداً',
                    text: 'يجب أن يكون حجم الملف أقل من 10 ميجابايت',
                    confirmButtonText: 'موافق'
                });
                e.target.value = '';
                preview.style.display = 'none';
                return;
            }
            
            // Preview based on file type
            const fileType = file.type.toLowerCase();
            
            if (fileType.startsWith('image/')) {
                // Image preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContent.innerHTML = `
                        <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px; max-width: 100%;" alt="معاينة الصورة">
                    `;
                };
                reader.readAsDataURL(file);
            } else if (fileType === 'application/pdf') {
                // PDF preview
                previewContent.innerHTML = `
                    <div class="text-center p-3">
                        <i class="fas fa-file-pdf fa-4x text-danger mb-2"></i>
                        <div>ملف PDF - ${file.name}</div>
                    </div>
                `;
            } else {
                // Other file types
                previewContent.innerHTML = `
                    <div class="text-center p-3">
                        <i class="fas fa-file fa-4x text-secondary mb-2"></i>
                        <div>ملف - ${file.name}</div>
                    </div>
                `;
            }
        } else {
            preview.style.display = 'none';
        }
    });
    
    // Form submission with loading state
    document.getElementById('editDocumentForm').addEventListener('submit', function(e) {
        const updateBtn = document.getElementById('updateBtn');
        const originalText = updateBtn.innerHTML;
        
        // Show loading state
        updateBtn.disabled = true;
        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>جاري التحديث...';
        
        // Validate file size if new file selected
        const fileInput = document.getElementById('edit_document_file');
        if (fileInput.files[0] && fileInput.files[0].size > 10 * 1024 * 1024) {
            e.preventDefault();
            updateBtn.disabled = false;
            updateBtn.innerHTML = originalText;
            
            Swal.fire({
                icon: 'error',
                title: 'حجم الملف كبير جداً',
                text: 'يجب أن يكون حجم الملف أقل من 10 ميجابايت',
                confirmButtonText: 'موافق'
            });
            return;
        }
        
        // Reset button state after a delay if form doesn't submit
        setTimeout(() => {
            updateBtn.disabled = false;
            updateBtn.innerHTML = originalText;
        }, 10000);
    });
    
    // Reset modal when closed
    document.getElementById('editDocumentModal').addEventListener('hidden.bs.modal', function() {
        // Reset form
        document.getElementById('editDocumentForm').reset();
        
        // Hide preview
        document.getElementById('edit_file_preview').style.display = 'none';
        
        // Reset button
        const updateBtn = document.getElementById('updateBtn');
        updateBtn.disabled = false;
        updateBtn.innerHTML = '<i class="fas fa-save me-1"></i>حفظ التعديلات';
        
        // Clear current editing file
        currentEditingFile = null;
    });
    </script>
@endsection