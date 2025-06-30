@extends('layouts.'.get_area_name())
@section('title', 'تفاصيل طلب أوراق الخارج')
@section('styles')
<style>
/* Fix for Select2 dropdown z-index inside modal */
.select2-container--default .select2-dropdown {
    z-index: 9999 !important;
}

.modal .select2-container {
    z-index: 9999 !important;
}

.select2-container--open {
    z-index: 9999 !important;
}
</style>
@endsection
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
                                            @php
                                                $documentType = $service->pricing->document_type ?? null;
                                                $allowedTypes = ['certificate', 'good_standing', 'internship_second_year', 'license', 'specialist', 'university_letters', 'verification_work'];
                                            @endphp
                                            
                                            @if($documentType && in_array($documentType, $allowedTypes) && !$service->has_document_preparation)
                                                @if($documentType === 'good_standing')
                                                    <button type="button" class="btn btn-sm btn-success" 
                                                            onclick="showDocumentModalWithWorkMention('good_standing', {{ $service->id }}, '{{ $service->work_mention }}')">
                                                        <i class="fas fa-file-alt me-1"></i>
                                                        إعداد النموذج
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-primary" 
                                                            onclick="showDocumentModal('{{ $documentType }}', {{ $service->id }})">
                                                        <i class="fas fa-file-alt me-1"></i>
                                                        إعداد النموذج
                                                    </button>
                                                @endif
                                            @endif
                                    
                                            @if($documentType && in_array($documentType, $allowedTypes) && $service->has_document_preparation)
                                                <a target="_blank" href="{{route('admin.document-preparations.print', ['documentPreparation' => $service->documentPreparation->id, 'download' => 1] )}}" class="btn btn-success">تحميل النموذج</a>
                                            @endif
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

            <div class="modal fade" id="confirmDocumentModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form id="confirmDocumentForm" onsubmit="prepareDocument(event)">
                            @csrf
                            <input type="hidden" name="document_type" id="confirmDocumentType">
                            <input type="hidden" name="service_id" id="confirmServiceId">
                            
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="confirmDocumentTitle">
                                    <i class="fas fa-file-alt me-2"></i>
                                    إعداد المستند
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center py-5">
                                <div class="mb-4">
                                    <i class="fas fa-check-circle fa-4x text-primary"></i>
                                </div>
                                <h4 class="mb-3">هل تريد إعداد المستند؟</h4>
                                <p class="text-muted" id="confirmDocumentMessage">سيتم إعداد المستند للطبيب</p>
                            </div>
                            <div class="modal-footer border-0 justify-content-center">
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
            
            <!-- Internship Second Year Modal -->
            <div class="modal fade" id="internshipSecondYearModal" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <form id="internshipSecondYearForm" onsubmit="prepareDocument(event)">
                            @csrf
                            <input type="hidden" name="document_type" value="internship_second_year">
                            <input type="hidden" name="service_id" id="internshipServiceId">
                            
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title text-light">
                                    <i class="fas fa-hospital me-2"></i>
                                    إعداد شهادة التدريب - السنة الثانية
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="hasGapPeriod" name="has_gap_period" onchange="toggleGapFields()">
                                            <label class="form-check-label fw-bold text-info" for="hasGapPeriod">
                                                <i class="fas fa-calendar-alt me-2"></i>
                                                يوجد فترة فجوة (Gap Period)
                                            </label>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="row mb-4" id="gapPeriodFields" >
                                    <div class="col-12 mb-2">
                                        <small class="text-muted">فترة الفجوة التي سيتم ذكرها في الشهادة:</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gap_start" class="form-label">بداية الفترة</label>
                                        <input type="month" name="gap_start" id="gap_start" class="form-control">
                                        <small class="text-muted">مثال: 2018-07 (يوليو 2018)</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gap_end" class="form-label">نهاية الفترة</label>
                                        <input type="month" name="gap_end" id="gap_end" class="form-control">
                                        <small class="text-muted">مثال: 2019-07 (يوليو 2019)</small>
                                    </div>
                                </div>
            
                                <hr class="my-4">
            
                                <!-- Training Records Section -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-info mb-3">
                                            <i class="fas fa-table me-2"></i>
                                            سجلات التدريب
                                        </h6>
                                    </div>
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="internshipTable">
                                        <thead>
                                            <tr>
                                                <th>Hospital / Medical Center</th>
                                                <th>Starting Date</th>
                                                <th>Ending Date</th>
                                                <th>Specialty Course Type</th>
                                                <th style="width: 50px;">حذف</th>
                                            </tr>
                                        </thead>
                                        <tbody id="internshipTableBody">
                                            <tr>
                                                <td><input type="text" name="hospital[]" class="form-control" required></td>
                                                <td><input type="date" name="start_date[]" class="form-control" required></td>
                                                <td><input type="date" name="end_date[]" class="form-control" required></td>
                                                <td><input type="text" name="specialty[]" class="form-control" required></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)" disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-success btn-sm" onclick="addInternshipRow()">
                                        <i class="fas fa-plus me-2"></i>
                                        إضافة صف جديد
                                    </button>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-cog me-2"></i>
                                    إعداد المستند
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Verification Work Modal -->
            <div class="modal fade" id="verificationWorkModal" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <form id="verificationWorkForm" onsubmit="prepareDocument(event)">
                            @csrf
                            <input type="hidden" name="document_type" value="verification_work">
                            <input type="hidden" name="service_id" id="verificationServiceId">
                            
                            <div class="modal-header bg-warning text-dark">
                                <h5 class="modal-title text-light">
                                    <i class="fas fa-briefcase me-2"></i>
                                    إعداد شهادة التحقق من العمل
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="verificationTable">
                                        <thead>
                                            <tr>
                                                <th>Specialty</th>
                                                <th>Hospital</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th style="width: 50px;">حذف</th>
                                            </tr>
                                        </thead>
                                        <tbody id="verificationTableBody">
                                            <tr>
                                                <td><input type="text" name="work_specialty[]" class="form-control" required></td>
                                                <td><input type="text" name="work_hospital[]" class="form-control" required></td>
                                                <td><input type="date" name="work_from[]" class="form-control" required></td>
                                                <td><input type="date" name="work_to[]" class="form-control" required></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)" disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="btn btn-success btn-sm" onclick="addVerificationRow()">
                                        <i class="fas fa-plus me-2"></i>
                                        إضافة صف جديد
                                    </button>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-cog me-2"></i>
                                    إعداد المستند
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Add this JavaScript code to the scripts section -->
            <script>
            // Document modal management
            // Updated showDocumentModal function
            function showDocumentModal(documentType, serviceId, workMention = null) {
                switch(documentType) {
                    case 'specialist':
                    case 'license':
                    case 'university_letters':
                        showConfirmModal(documentType, serviceId);
                        break;
                    case 'good_standing':
                        // Check if this service has work_mention requirement
                        showDocumentModalWithWorkMention(documentType, serviceId, workMention);
                        break;
                    case 'certificate':
                        document.getElementById('certificateServiceId').value = serviceId;
                        new bootstrap.Modal(document.getElementById('certificateModal')).show();
                        break;
                    case 'internship_second_year':
                        document.getElementById('internshipServiceId').value = serviceId;
                        new bootstrap.Modal(document.getElementById('internshipSecondYearModal')).show();
                        break;
                    case 'verification_work':
                        document.getElementById('verificationServiceId').value = serviceId;
                        new bootstrap.Modal(document.getElementById('verificationWorkModal')).show();
                        break;
                }
            }

            // Function to show document modal with work mention parameter
            function showDocumentModalWithWorkMention(documentType, serviceId, workMention) {
                if (documentType === 'good_standing') {
                    // Set service ID
                    document.getElementById('goodStandingServiceId').value = serviceId;
                    document.getElementById('goodStandingWorkMention').value = workMention ? 'true' : 'false';
                    
                    // Show/hide work place field and work details section
                    const workDetailsSection = document.getElementById('workDetailsSection');
                    
                    if (workMention === 'with') {
                        workDetailsSection.style.display = 'block';
                        
                        // Make first row of work details required
                        const firstRowInputs = workDetailsSection.querySelectorAll('tbody tr:first-child input');
                        firstRowInputs.forEach(input => {
                            if (!input.name.includes('work_from') && !input.name.includes('work_to')) {
                                input.required = true;
                            }
                        });
                    } else {
                        workDetailsSection.style.display = 'none';
                        
                        // Clear and make work details not required
                        const workInputs = workDetailsSection.querySelectorAll('input');
                        workInputs.forEach(input => {
                            input.required = false;
                            input.value = '';
                        });
                    }
                    
                    // Show modal
                    new bootstrap.Modal(document.getElementById('goodStandingModal')).show();
                } else {
                    showDocumentModal(documentType, serviceId);
                }
            }
            function showConfirmModal(documentType, serviceId) {
                const titles = {
                    'certificate': 'شهادة',
                    'good_standing': 'شهادة حسن السيرة والسلوك',
                    'license': 'رخصة',
                    'specialist': 'شهادة الاختصاص',
                    'university_letters': 'خطابات جامعية'
                };
                
                document.getElementById('confirmDocumentType').value = documentType;
                document.getElementById('confirmServiceId').value = serviceId;
                document.getElementById('confirmDocumentTitle').innerHTML = `<i class="fas fa-file-alt me-2"></i> إعداد ${titles[documentType] || 'المستند'}`;
                document.getElementById('confirmDocumentMessage').textContent = `سيتم إعداد ${titles[documentType] || 'المستند'} للطبيب`;
                
                new bootstrap.Modal(document.getElementById('confirmDocumentModal')).show();
            }
            
            // Add row functions
            function addInternshipRow() {
                const tbody = document.getElementById('internshipTableBody');
                const newRow = tbody.rows[0].cloneNode(true);
                
                // Clear input values
                newRow.querySelectorAll('input').forEach(input => input.value = '');
                
                // Enable delete button
                const deleteBtn = newRow.querySelector('button');
                deleteBtn.disabled = false;
                
                tbody.appendChild(newRow);
            }
            
            function addVerificationRow() {
                const tbody = document.getElementById('verificationTableBody');
                const newRow = tbody.rows[0].cloneNode(true);
                
                // Clear input values
                newRow.querySelectorAll('input').forEach(input => input.value = '');
                
                // Enable delete button
                const deleteBtn = newRow.querySelector('button');
                deleteBtn.disabled = false;
                
                tbody.appendChild(newRow);
            }
            
            // Remove row function
            function removeRow(button) {
                const row = button.closest('tr');
                const tbody = row.parentElement;
                
                // Don't remove if it's the last row
                if (tbody.rows.length > 1) {
                    row.remove();
                }
            }
            
        
                            
                // Update the prepareDocument function to handle the certificate form data
                function prepareDocument(event) {
                    event.preventDefault();
                    
                    const form = event.target;
                    const formData = new FormData(form);
                    
                    // Handle country selection for certificate
                    if (formData.get('document_type') === 'certificate') {
                        const country = formData.get('country');
                        if (country === 'other') {
                            formData.set('country', formData.get('other_country'));
                        }
                        formData.delete('other_country');
                    }
                    
                    // Get the service ID from the form
                    const serviceId = formData.get('service_id');
                    
                    // Show loading
                    Swal.fire({
                        title: 'جاري إعداد المستند...',
                        text: 'يرجى الانتظار',
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading()
                        }
                    });
                    
                    // Build the URL with both parameters
                    const url = `{{ url('admin/doctor-mails') }}/{{ $doctorMail->id }}/services/${serviceId}/prepare-document`;
                    let csrfToken = '{{csrf_token()}}'
                    
                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم إعداد المستند بنجاح',
                                text: data.message || 'تم إعداد المستند بنجاح',
                                confirmButtonText: 'حسناً'
                            }).then(() => {
                                // Close modal
                                const modals = ['confirmDocumentModal', 'internshipSecondYearModal', 'verificationWorkModal', 'certificateModal', 'goodStandingModal'];
                                modals.forEach(modalId => {
                                    const modalEl = document.getElementById(modalId);
                                    const modal = bootstrap.Modal.getInstance(modalEl);
                                    if (modal) modal.hide();
                                });

                                // Reload the page to show updated data
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: data.message || 'حدث خطأ أثناء إعداد المستند',
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
                }
            </script>
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
<!-- Good Standing Modal -->
<div class="modal fade" id="goodStandingModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <form id="goodStandingForm" onsubmit="prepareDocument(event)">
                @csrf
                <input type="hidden" name="document_type" value="good_standing">
                <input type="hidden" name="service_id" id="goodStandingServiceId">
                <input type="hidden" name="work_mention_required" id="goodStandingWorkMention">
                
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title text-light">
                        <i class="fas fa-certificate me-2"></i>
                        إعداد شهادة حسن السيرة والسلوك
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Work Place Field (Optional) -->
                    <div class="row mb-4" id="goodStandingWorkPlaceField" style="display: none;">
                        <div class="col-12">
                           
                        </div>
                    </div>

                    <!-- Work Details Table (Only for work_mention) -->
                    <div id="workDetailsSection" style="display: none;">
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-success mb-3">
                                    <i class="fas fa-briefcase me-2"></i>
                                    تفاصيل العمل
                                </h6>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered" id="workDetailsTable">
                                <thead>
                                    <tr>
                                        <th>Work as</th>
                                        <th>Specialty</th>
                                        <th>Department</th>
                                        <th>Hospital</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th style="width: 50px;">حذف</th>
                                    </tr>
                                </thead>
                                <tbody id="workDetailsTableBody">
                                    <tr>
                                        <td><input type="text" name="work_as[]" class="form-control" placeholder="junior Doctor" required></td>
                                        <td><input type="text" name="work_specialty[]" class="form-control" placeholder="Internal Medicine" required></td>
                                        <td><input type="text" name="work_department[]" class="form-control" placeholder="Internal Medicine Dept." required></td>
                                        <td><input type="text" name="work_hospital[]" class="form-control" placeholder="Tripoli Medical Center" required></td>
                                        <td><input type="date" name="work_from[]" class="form-control" required></td>
                                        <td><input type="date" name="work_to[]" class="form-control" required></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeWorkRow(this)" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-success btn-sm" onclick="addWorkRow()">
                                <i class="fas fa-plus me-2"></i>
                                إضافة عمل جديد
                            </button>
                        </div>
                    </div>

                    <!-- Simple confirmation message (for without work mention) -->
                    <div id="simpleConfirmation" style="display: none;">
                        <div class="text-center py-4">
                            <div class="mb-4">
                                <i class="fas fa-check-circle fa-4x text-success"></i>
                            </div>
                            <h4 class="mb-3">هل تريد إعداد شهادة حسن السيرة والسلوك؟</h4>
                            <p class="text-muted">سيتم إعداد الشهادة بدون ذكر جهة العمل</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
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

<script>
// Function to add work row
function addWorkRow() {
    const tbody = document.getElementById('workDetailsTableBody');
    const newRow = tbody.rows[0].cloneNode(true);
    
    // Clear input values
    newRow.querySelectorAll('input').forEach(input => input.value = '');
    
    // Enable delete button
    const deleteBtn = newRow.querySelector('button');
    deleteBtn.disabled = false;
    
    tbody.appendChild(newRow);
}

// Function to remove work row
function removeWorkRow(button) {
    const row = button.closest('tr');
    const tbody = row.parentElement;
    
    // Don't remove if it's the last row
    if (tbody.rows.length > 1) {
        row.remove();
    }
}

// Function to show document modal with work mention parameter
function showDocumentModalWithWorkMention(documentType, serviceId, workMention) {
    if (documentType === 'good_standing') {
        // Set service ID
        document.getElementById('goodStandingServiceId').value = serviceId;
        document.getElementById('goodStandingWorkMention').value = workMention ? 'true' : 'false';
        
        // Show/hide work place field and work details section
        const workDetailsSection = document.getElementById('workDetailsSection');
        
        if (workMention === 'with') {
            workDetailsSection.style.display = 'block';
            
            // Make first row of work details required
            const firstRowInputs = workDetailsSection.querySelectorAll('tbody tr:first-child input');
            firstRowInputs.forEach(input => {
                input.required = true;
            });
        } else {
            workDetailsSection.style.display = 'none';
            
            // Clear and make work details not required
            const workInputs = workDetailsSection.querySelectorAll('input');
            workInputs.forEach(input => {
                input.required = false;
                input.value = '';
            });
        }
        
        // Show modal
        new bootstrap.Modal(document.getElementById('goodStandingModal')).show();
    } else {
        showDocumentModal(documentType, serviceId);
    }
}
</script>

<script>
// Function to add work row
function addWorkRow() {
    const tbody = document.getElementById('workDetailsTableBody');
    const newRow = tbody.rows[0].cloneNode(true);
    
    // Clear input values
    newRow.querySelectorAll('input').forEach(input => input.value = '');
    
    // Enable delete button
    const deleteBtn = newRow.querySelector('button');
    deleteBtn.disabled = false;
    
    tbody.appendChild(newRow);
}

// Function to remove work row
function removeWorkRow(button) {
    const row = button.closest('tr');
    const tbody = row.parentElement;
    
    // Don't remove if it's the last row
    if (tbody.rows.length > 1) {
        row.remove();
    }
}

// Update the showDocumentModal function to handle good_standing with work_mention
function showDocumentModalWithWorkMention(documentType, serviceId, workMention) {
    if (documentType === 'good_standing') {
        // Set service ID
        document.getElementById('goodStandingServiceId').value = serviceId;
        document.getElementById('goodStandingWorkMention').value = workMention ? 'true' : 'false';
        
        // Show/hide work place field and work details section
        const workDetailsSection = document.getElementById('workDetailsSection');
        
        if (workMention === 'with') {
            workDetailsSection.style.display = 'block';
            
            // Make work details required
            const workInputs = workDetailsSection.querySelectorAll('input[name^="work_"]');
            workInputs.forEach(input => {
                if (input.type !== 'date') {
                    input.required = true;
                }
            });
        } else {
            workDetailsSection.style.display = 'none';
            
            // Clear and make work details not required
            const workInputs = workDetailsSection.querySelectorAll('input');
            workInputs.forEach(input => {
                input.required = false;
                input.value = '';
            });
        }
        
        // Show modal
        new bootstrap.Modal(document.getElementById('goodStandingModal')).show();
    } else {
        showDocumentModal(documentType, serviceId);
    }
}
</script>

<!-- Edit Request Modal -->
<div class="modal fade" id="editRequestModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.doctor-mails.reject', $doctorMail) }}" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="under_edit">
                
                <div class="modal-header border-0">
                    <h5 class="modal-title text-light">
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


<!-- Good Standing Modal -->

<!-- Certificate Modal -->
<div class="modal fade" id="certificateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="certificateForm" onsubmit="prepareDocument(event)">
                @csrf
                <input type="hidden" name="document_type" value="certificate">
                <input type="hidden" name="service_id" id="certificateServiceId">
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-light">
                        <i class="fas fa-certificate me-2"></i>
                        إعداد الرخصة
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label">الدولة <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="country_id" id="certificateCountry" required>
                            <option value="">اختر الدولة...</option>
                            @foreach (\App\Models\Country::all() as $country)
                                <option value="{{$country->id}}">{{$country->country_name_ar}} - {{$country->country_name_en}}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">القسم / التخصص <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="department" id="certificateDepartment" 
                               placeholder="مثال: general practitioner department" required>
                        <small class="text-muted">اكتب القسم أو التخصص باللغة الإنجليزية</small>
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
<!-- Specialist Modal -->
<div class="modal fade" id="specialistModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="specialistForm" onsubmit="prepareDocument(event, 'specialist')">
                @csrf
                <input type="hidden" name="document_type" value="specialist">
                <input type="hidden" name="service_id" id="specialistServiceId">
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-light">
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


// Function to show document modal with work mention parameter
function showDocumentModalWithWorkMention(documentType, serviceId, workMention) {
    if (documentType === 'good_standing') {
        // Set service ID
        document.getElementById('goodStandingServiceId').value = serviceId;
        document.getElementById('goodStandingWorkMention').value = workMention ? 'true' : 'false';
        
        
        // Show modal
        new bootstrap.Modal(document.getElementById('goodStandingModal')).show();
    } else {
        showDocumentModal(documentType, serviceId);
    }
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
<script>
// Initialize Select2 when modal is shown
$('#certificateModal').on('shown.bs.modal', function () {
    $('#certificateCountry').select2({
        dropdownParent: $('#certificateModal'),
        width: '100%',
        placeholder: 'اختر الدولة...',
        allowClear: true,
        dir: 'rtl'
    });
});

// Destroy Select2 when modal is hidden
$('#certificateModal').on('hidden.bs.modal', function () {
    $('#certificateCountry').select2('destroy');
});
$('#certificateModal').removeAttr('tabindex');

</script>

<script>
function toggleGapFields() {
    const checkbox = document.getElementById('hasGapPeriod');
    const gapFields = document.getElementById('gapPeriodFields');
    const gapStart = document.getElementById('gap_start');
    const gapEnd = document.getElementById('gap_end');
    
    if (checkbox.checked) {
        gapFields.style.display = 'block';
        gapStart.required = true;
        gapEnd.required = true;
    } else {
        gapFields.style.display = 'none';
        gapStart.required = false;
        gapEnd.required = false;
        gapStart.value = '';
        gapEnd.value = '';
    }
}
</script>

<script>
    // Function to add work row
    function addWorkRow() {
        const tbody = document.getElementById('workDetailsTableBody');
        const newRow = tbody.rows[0].cloneNode(true);
        
        // Clear input values
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        
        // Enable delete button
        const deleteBtn = newRow.querySelector('button');
        deleteBtn.disabled = false;
        
        tbody.appendChild(newRow);
    }
    
    // Function to remove work row
    function removeWorkRow(button) {
        const row = button.closest('tr');
        const tbody = row.parentElement;
        
        // Don't remove if it's the last row
        if (tbody.rows.length > 1) {
            row.remove();
        }
    }
    
    // FIXED: Function to show document modal with work mention parameter
    function showDocumentModalWithWorkMention(documentType, serviceId, workMention) {
        console.log('showDocumentModalWithWorkMention called:', { documentType, serviceId, workMention });
        
        if (documentType === 'good_standing') {
            // Set service ID and work mention
            document.getElementById('goodStandingServiceId').value = serviceId;
            document.getElementById('goodStandingWorkMention').value = workMention;
            
            // Get all sections
            const workDetailsSection = document.getElementById('workDetailsSection');
            const simpleConfirmation = document.getElementById('simpleConfirmation');
            
            // Hide all sections first
            workDetailsSection.style.display = 'none';
            simpleConfirmation.style.display = 'none';
            
            if (workMention === 'with') {
                // Show work-related sections
                workDetailsSection.style.display = 'block';
                
                // Make first row of work details required
                const firstRowInputs = workDetailsSection.querySelectorAll('tbody tr:first-child input');
                firstRowInputs.forEach(input => {
                    input.required = true;
                });
                
                console.log('Work sections shown');
            } else {
                // Show simple confirmation
                simpleConfirmation.style.display = 'block';
                
                // Clear and make work details not required
                const workInputs = workDetailsSection.querySelectorAll('input');
                workInputs.forEach(input => {
                    input.required = false;
                    input.value = '';
                });
                
                console.log('Simple confirmation shown');
            }
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('goodStandingModal'));
            modal.show();
            
            console.log('Modal shown');
        } else {
            showDocumentModal(documentType, serviceId);
        }
    }
    
    // Override the existing function to ensure it works
    function showGoodStandingModal(serviceId, workMention) {
        showDocumentModalWithWorkMention('good_standing', serviceId, workMention);
    }
    </script>
    <script>
    // Add this to your scripts section for debugging
    console.log('Good Standing Modal script loaded');

    // Test function
    function testGoodStanding() {
        console.log('Testing Good Standing modal');
        showDocumentModalWithWorkMention('good_standing', 123, 'with');
    }

    // Alternative function name for testing
    window.showGoodStandingModal = function(serviceId, workMention) {
        console.log('showGoodStandingModal called with:', serviceId, workMention);
        showDocumentModalWithWorkMention('good_standing', serviceId, workMention);
    };

    // Debug the elements
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, checking elements:');
        console.log('workDetailsSection:', document.getElementById('workDetailsSection'));
        console.log('simpleConfirmation:', document.getElementById('simpleConfirmation'));
    });
    </script>
@endsection