@extends('layouts.doctor')

@section('styles')
<style>
/* ===== Enhanced Form Styles ===== */
.form-container {
    background: #f8f9fa;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.form-header {
    background: linear-gradient(135deg, #662b27 0%, #883138 100% 100%);
    color: white;
    padding: 30px;
    margin: -20px -20px 30px -20px;
}

.form-header h3 {
    margin: 0;
    font-weight: 600;
}

/* Tab Navigation Styles */
.nav-tabs {
    border: none;
    background: #fff;
    padding: 15px 20px 0;
    margin: 0 -20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.nav-tabs .nav-link {
    border: none;
    padding: 15px 30px;
    color: #6c757d;
    font-weight: 500;
    border-radius: 10px 10px 0 0;
    margin-right: 5px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    position: relative;
    font-size: 1.1rem;
}

.nav-tabs .nav-link:hover {
    background: #e9ecef;
    color: #495057;
}

.nav-tabs .nav-link.active {
    background: #fff;
    color: #007bff;
    box-shadow: 0 -3px 8px rgba(0,0,0,0.05);
}

.nav-tabs .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 3px;
    background: #007bff;
}

.nav-tabs .nav-link i {
    margin-left: 8px;
}

/* Tab Content */
.tab-content {
    background: #fff;
    padding: 30px 20px;
    margin: 0 -20px -20px;
}

/* Form Section Styles */
.form-section { 
    background: #f8f9fa; 
    padding: 25px; 
    border-radius: 12px; 
    margin-bottom: 25px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-section:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.form-section h4 { 
    color: #007bff;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid #007bff;
}

.form-section h5 {
    color: #6c757d;
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 15px;
}

/* Input Styles */
.form-control, .selectize-control .selectize-input {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 10px 15px;
    transition: all 0.3s ease;
}

.form-control:focus, .selectize-control .selectize-input.focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
}

label {
    font-weight: 500;
    color: #495057;
    margin-bottom: 8px;
}

.required-field::after {
    content: " *";
    color: #dc3545;
}

/* Alert Styles */
.custom-alert {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    border: none;
    color: white;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(238,90,111,0.3);
    padding: 20px 25px;
    font-weight: 500;
    margin-bottom: 30px;
}

.custom-alert i {
    font-size: 1.5rem;
}

/* File Upload Styles */
.file-upload-section {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.file-upload-item {
    background: #fff;
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.file-upload-item:hover {
    border-color: #007bff;
    box-shadow: 0 5px 20px rgba(0,123,255,0.1);
}

.file-upload-item h5 {
    color: #495057;
    margin-bottom: 15px;
    font-weight: 600;
}

.btn-file-upload {
    position: relative;
    overflow: hidden;
    display: inline-block;
}

.btn-file-upload input[type=file] {
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
}

.file-name-display {
    color: #28a745;
    font-size: 0.9rem;
    margin-top: 10px;
    display: none;
    font-weight: 500;
}

/* Button Styles */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 15px 40px;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(102,126,234,0.3);
}

.btn-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border: none;
    color: white;
    font-weight: 500;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(245,87,108,0.3);
}

/* Progress Indicator - Simplified for 2 tabs */
.form-progress {
    display: flex;
    justify-content: center;
    gap: 100px;
    margin-bottom: 30px;
    padding: 0 20px;
}

.progress-step {
    text-align: center;
    position: relative;
}

.progress-step::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 50%;
    width: 100px;
    height: 2px;
    background: #dee2e6;
    z-index: 1;
}

.progress-step:last-child::before {
    display: none;
}

.progress-step.active::before {
    background: #007bff;
}

.progress-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #dee2e6;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 10px;
    position: relative;
    z-index: 2;
    font-weight: 600;
    transition: all 0.3s ease;
}

.progress-step.active .progress-number {
    background: #007bff;
    color: white;
    box-shadow: 0 0 0 5px rgba(0,123,255,0.2);
}

.progress-step.completed .progress-number {
    background: #28a745;
    color: white;
}

.progress-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.progress-step.active .progress-label {
    color: #007bff;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .nav-tabs .nav-link {
        padding: 12px 20px;
        font-size: 1rem;
    }
    
    .form-section {
        padding: 20px 15px;
    }
    
    .file-upload-section {
        grid-template-columns: 1fr;
    }
    
    .form-progress {
        gap: 50px;
    }

    .input-space
    {
        padding: 15px;
        font-size: 0.9rem;
        border: 2px dashed #277bff !important;
    }
}
</style>
<style>
    .input-space
    {
        padding: 15px;
        font-size: 0.9rem;
        border: 2px dashed #277bff !important;
    }
</style>
@endsection

@section('content')
@php
    $is_libyan = $doctor->type == "libyan";
    $is_visitor = $doctor->type == "visitor";
    $is_palestinian = $doctor->type == "palestinian";
@endphp

@php
    $endDate = \Carbon\Carbon::parse($doctor->visit_to);
    $isActive = $endDate->isFuture();
    $daysLeft = $endDate->diffInDays(now(), false);
    
    // Check if doctor needs report upload
    $needsReport = $doctor->membership_status->value == 'expired' || $endDate->isPast();
    
    // Check if report already exists (assuming file_type_id 54 for doctor report)
    $hasReport = $doctor->files->where('file_type_id', 54)->count() > 0;
    
    $reportRequired = $needsReport && !$hasReport;
@endphp

@if($doctor->membership_status->value == "under_edit")
<div class="container-fluid px-0">
    <div class="card form-container">
        <div class="card-body">
            
            <!-- Form Header -->
            <div class="form-header">
                <h3 class="text-center mb-3 text-light">
                    <i class="fas fa-user-edit me-2"></i>
                    تعديل بيانات الطبيب {{ $is_visitor ? 'الزائر' : '' }}
                </h3>
                <p class="text-center mb-0 opacity-75">
                    يرجى تحديث جميع البيانات المطلوبة وإعادة رفع المستندات
                </p>

            </div>

            <!-- Alert Message -->
            <div class="custom-alert d-flex align-items-center" role="alert">
                <i class="fa fa-exclamation-triangle me-3 flex-shrink-0"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-1">تم تغيير حالة عضوية الطبيب  إلى قيد التعديل</strong>
                    <span>السبب: {{ $doctor->edit_note }}</span>
                </div>
            </div>


@else
<!-- Read-Only View for Non-Edit Status -->
<div class="container-fluid px-0">
    <div class="card form-container">
        <div class="card-body">
            
            <!-- Header for Read-Only View -->
            <div class="form-header">
                <h3 class="text-center mb-3 text-light">
                    <i class="fas fa-user-circle me-2"></i>
                    بيانات الطبيب {{ $is_visitor ? 'الزائر' : '' }}
                </h3>
                <p class="text-center mb-0 opacity-75">
                   حالة العضوية : <span class="badge {{$doctor->membership_status->badgeClass()}} ">{{$doctor->membership_status->label()}}</span>
                </p>

                @if ($doctor->membership_status->value == "under_upload")
                <a href="{{route('doctor.visitor-doctors.upload-documents', $doctor)}}" class="btn btn-warning text-center">
                    <i class="fas fa-upload me-2"></i>استكمال رفع المستندات
                </a>
                @endif


                @if($reportRequired && in_array($doctor->membership_status->value, ['expired', 'active']))
                <button type="button" 
                        class="btn btn-primary btn-sm m-1  action-btn btn-upload-report missing-report-indicator" 
                        title="رفع تقرير الزيارة"
                        onclick="openUploadModal({{ $doctor->id }}, '{{ $doctor->name }}', '{{ date('Y-m-d',strtotime($doctor->visit_from)) }}', '{{ date('Y-m-d',strtotime($doctor->visit_to)) }}')">
                    <i class="fas fa-file-upload"></i> رفع تقرير الزيارة
                </button>
               @endif

                            <!-- استبدال الزر الحالي -->
                @if ($doctor->membership_status->value == "expired")
                <button type="button" 
                        class="btn btn-success" 
                        onclick="openRenewModal()">
                    تجديد اشتراك <i class="fa fa-redo"></i>
                </button>
                @endif

            </div>

            <!-- Status Alert -->
            @if($doctor->membership_status->value == "pending")
            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                <i class="fas fa-clock me-3 flex-shrink-0"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-1">طلبك قيد المراجعة</strong>
                    <span>سيتم إشعارك بنتيجة المراجعة قريباً</span>
                </div>
            </div>
            @elseif($doctor->membership_status->value == "approved")
            <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                <i class="fas fa-check-circle me-3 flex-shrink-0"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-1">تم اعتماد عضويتك</strong>
                    <span>مرحباً بك في نقابة الأطباء</span>
                </div>
            </div>
            @elseif($doctor->membership_status->value == "rejected")
            <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-3 flex-shrink-0"></i>
                <div class="flex-grow-1">
                    <strong class="d-block mb-1">تم رفض طلب العضوية</strong>
                    @if($doctor->edit_note)
                    <span>السبب: {{ $doctor->edit_note }}</span>
                    @endif
                </div>
            </div>
            @endif

            <!-- Progress Steps - Show completion status -->
            <div class="form-progress mb-4">
                <div class="progress-step completed" id="step-1">
                    <div class="progress-number"><i class="fas fa-check"></i></div>
                    <div class="progress-label">المعلومات</div>
                </div>
                <div class="progress-step completed" id="step-2">
                    <div class="progress-number"><i class="fas fa-check"></i></div>
                    <div class="progress-label">المستندات</div>
                </div>
            </div>

            <!-- Read-Only Tabs -->
            <ul class="nav nav-tabs" id="viewFormTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="view-information-tab" data-bs-toggle="tab" data-bs-target="#view-information" type="button" role="tab">
                        <i class="fas fa-user-circle"></i> المعلومات
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="view-documents-tab" data-bs-toggle="tab" data-bs-target="#view-documents" type="button" role="tab">
                        <i class="fas fa-file-alt"></i> المستندات
                    </button>
                </li>
            </ul>

            <!-- Read-Only Tab Content -->
            <div class="tab-content" id="viewFormTabContent">
                <!-- Information View Tab -->
                <div class="tab-pane fade show active" id="view-information" role="tabpanel">
                    
                    <!-- Personal Information Section -->
                    <div class="form-section">
                        <h4><i class="fas fa-info-circle me-2"></i>المعلومات الأساسية</h4>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>الاسم بالكامل</label>
                                <div class="input-space  bordered p-3 rounded">{{ $doctor->name ?: 'غير محدد' }}</div>
                            </div>

                            @if (!$is_libyan && !$is_palestinian)
                            <div class="col-md-6 mb-3">
                                <label>الجنسية</label>
                                <div class="input-space bordered p-3 rounded">
                                    {{ $doctor->country ? $doctor->country->nationality_name_ar : 'غير محدد' }}
                                </div>
                            </div>
                            @endif

                            <div class="col-md-6 mb-3">
                                <label>رقم الجواز</label>
                                <div class="input-space bordered p-3 rounded">{{ $doctor->passport_number ?: 'غير محدد' }}</div>
                            </div>

                            @if ($is_libyan && $doctor->passport_expiration)
                            <div class="col-md-6 mb-3">
                                <label>تاريخ انتهاء صلاحية جواز السفر</label>
                                <div class="input-space bordered p-3 rounded">{{ date('Y-m-d', strtotime($doctor->passport_expiration)) }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if ($is_visitor)
                    <div class="form-section">
                        <h4><i class="fas fa-calendar-alt me-2"></i>فترة الزيارة</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>تاريخ الزيارة من</label>
                                <div class="input-space bordered p-3 rounded">{{ $doctor->visit_from ?: 'غير محدد' }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>تاريخ الزيارة إلى</label>
                                <div class="input-space bordered p-3 rounded">{{ $doctor->visit_to ?: 'غير محدد' }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($is_libyan)
                    <div class="form-section">
                        <h4><i class="fas fa-id-card me-2"></i>معلومات إضافية</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>تاريخ الميلاد</label>
                                <div class="input-space bordered p-3 rounded">
                                    {{ $doctor->date_of_birth ? date('Y-m-d', strtotime($doctor->date_of_birth)) : 'غير محدد' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>الرقم الوطني</label>
                                <div class="input-space bordered p-3 rounded">{{ $doctor->national_number ?: 'غير محدد' }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>اسم الأم</label>
                                <div class="input-space bordered p-3 rounded">{{ $doctor->mother_name ?: 'غير محدد' }}</div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>الحالة الاجتماعية</label>
                                <div class="input-space bordered p-3 rounded">
                                    {{ $doctor->marital_status == 'single' ? 'أعزب' : ($doctor->marital_status == 'married' ? 'متزوج' : 'غير محدد') }}
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label>الجنس</label>
                                <div class="input-space bordered p-3 rounded">
                                    {{ $doctor->gender == 'male' ? 'ذكر' : ($doctor->gender == 'female' ? 'أنثى' : 'غير محدد') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Contact Information Section -->
                    <div class="form-section">
                        <h4><i class="fas fa-address-book me-2"></i>معلومات الاتصال</h4>
                        
                        <div class="row">
                            @if ($is_libyan)
                            <div class="col-md-12 mb-3">
                                <label>العنوان</label>
                                <div class="input-space bg-light p-3 rounded">{{ $doctor->address ?: 'غير محدد' }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>رقم الهاتف</label>
                                <div class="input-space bg-light p-3 rounded">{{ $doctor->phone ?: 'غير محدد' }}</div>
                            </div>
                            @endif

                            <div class="col-md-6 mb-3">
                                <label>رقم الواتساب</label>
                                <div class="input-space bg-light p-3 rounded">
                                    @if($doctor->phone_2)
                                        <i class="fab fa-whatsapp text-success me-2"></i>{{ $doctor->phone_2 }}
                                    @else
                                        غير محدد
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>البريد الإلكتروني</label>
                                <div class="input-space bg-light p-3 rounded">
                                    @if($doctor->email)
                                        <i class="fas fa-envelope text-primary me-2"></i>{{ $doctor->email }}
                                    @else
                                        غير محدد
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Education Section -->
                    <div class="form-section">
                        <h4><i class="fas fa-graduation-cap me-2"></i>المؤهلات العلمية</h4>
                        
                        <!-- Bachelor's Degree -->
                        <h5><i class="fas fa-user-graduate me-2"></i>بكالوريوس</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label>جهة التخرج</label>
                                <div class="input-space bg-light p-3 rounded">
                                    {{ $doctor->handGraduation ? $doctor->handGraduation->name : 'غير محدد' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>تاريخ الحصول عليها</label>
                                <div class="input-space bg-light p-3 rounded">{{ $doctor->graduation_date ?: 'غير محدد' }}</div>
                            </div>
                        </div>

                        <!-- Internship -->
                        <h5><i class="fas fa-stethoscope me-2"></i>الامتياز</h5>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label>جهة الحصول على الامتياز</label>
                                <div class="input-space bg-light p-3 rounded">
                                    {{ $doctor->qualificationUniversity ? $doctor->qualificationUniversity->name : 'غير محدد' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>تاريخ الحصول عليها</label>
                                <div class="input-space bg-light p-3 rounded">{{ $doctor->internership_complete ?: 'غير محدد' }}</div>
                            </div>
                        </div>

                        @if ($doctor->academic_degree_id)
                        <!-- Academic Degree -->
                        <h5><i class="fas fa-award me-2"></i>الدرجة العلمية الحالية</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>الدرجة العلمية</label>
                                <div class="input-space bg-light p-3 rounded">
                                    {{ $doctor->academicDegree ? $doctor->academicDegree->name : 'غير محدد' }}
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>سنة الحصول عليها</label>
                                <div class="input-space bg-light p-3 rounded">{{ $doctor->internership_complete ?: 'غير محدد' }}</div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>جهة الحصول عليها</label>
                                <div class="input-space bg-light p-3 rounded">
                                    {{ $doctor->academicDegreeUniversity ? $doctor->academicDegreeUniversity->name : 'غير محدد' }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Work Information Section -->
                    <div class="form-section">
                        <h4><i class="fas fa-briefcase me-2"></i>بيانات العمل</h4>
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>الصفة</label>
                                <div class="input-space bg-light p-3 rounded">
                                    {{ $doctor->doctorRank ? $doctor->doctorRank->name : 'غير محدد' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>تخصص أول</label>
                                <div class="input-space bg-light p-3 rounded">
                                    {{ $doctor->specialty1 ? $doctor->specialty1->name : 'غير محدد' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>تخصص دقيق</label>
                                <div class="input-space bg-light p-3 rounded">{{ $doctor->specialty_2 ?: 'غير محدد' }}</div>
                            </div>
                        </div>

                        @if ($is_libyan)
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>جهات العمل السابقة</label>
                                <div class="input-space bg-light p-3 rounded">
                                    @if($doctor->institutions && $doctor->institutions->count() > 0)
                                        @foreach($doctor->institutions as $institution)
                                            <span class="badge bg-primary me-1 mb-1">{{ $institution->name }}</span>
                                        @endforeach
                                    @else
                                        غير محدد
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>سنوات الخبرة</label>
                                <div class="input-space bg-light p-3 rounded">
                                    {{ $doctor->experience ? $doctor->experience . ' سنة' : 'غير محدد' }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                </div>

                <!-- Documents View Tab -->
                <div class="tab-pane fade" id="view-documents" role="tabpanel">
                    <div class="form-section">
                        <h4><i class="fas fa-folder-open me-2"></i>المستندات المرفقة</h4>
                        
                        <div class="file-upload-section">
                            @foreach($doctor->files as $document)
                            <div class="file-upload-item" style="border: 2px solid #28a745; background: #f8fff9;">
                                <i class="fas fa-file-pdf fa-3x text-success mb-3"></i>
                                <h5 class="mb-3">{{ $document->fileType->name }}</h5>
                                @if($document->renew_number)
                                                                <span class="badge bg-warning text-dark mt-1">
                                                                    <i class="fas fa-sync-alt me-1"></i>
                                                                    طلب تجديد #{{ $document->renew_number }}
                                                                </span>
                                                            @endif
                                @if($document->file_path)
                                <div class="mb-3">
                                    <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-success">
                                        <i class="fas fa-eye me-2"></i>عرض الملف
                                    </a>
                                </div>
                                @endif
                            </div>
                            @endforeach

                            @if($doctor->files->count() == 0)
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                <p class="text-muted">لا توجد مستندات مرفقة</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons for Read-Only View -->
            <div class="mt-4 text-center">
                <button type="button" class="btn btn-secondary btn-lg me-2" onclick="window.location.href='/doctor/my-facility?visitors=1'">
                    <i class="fas fa-arrow-left me-2"></i>العودة للرئيسية
                </button>
                @if($doctor->membership_status->value == "rejected")
                <button type="button" class="btn btn-warning btn-lg" onclick="window.location.href='/doctor/profile/edit'">
                    <i class="fas fa-edit me-2"></i>تعديل البيانات
                </button>
                @endif
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="uploadReportModal" tabindex="-1" aria-labelledby="uploadReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-light" id="uploadReportModalLabel">
                    <i class="fas fa-file-upload me-2"></i>
                    رفع تقرير زيارة الطبيب
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="uploadReportForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Doctor Info -->
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="fas fa-user-md me-3 fs-4"></i>
                        <div>
                            <h6 class="mb-1">معلومات الطبيب الزائر</h6>
                            <p class="mb-0" id="doctorInfo">اسم الطبيب</p>
                        </div>
                    </div>

                    <!-- Upload Area -->
                    <div class="upload-area" id="uploadArea">
                        <div class="upload-content text-center p-4">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                            <h5>اسحب وأفلت الملف هنا أو اضغط للاختيار</h5>
                            <p class="text-muted">صورة من التقرير بعد زيارة الطبيب الزائر</p>
                            <p class="small text-muted">الملفات المسموحة: PDF, JPG, PNG (الحجم الأقصى: 2MB)</p>
                            <input type="file" id="reportFile" name="report_file" accept=".pdf,.jpg,.jpeg,.png" hidden>
                            <input type="hidden" id="visitorId" name="visitor_id">
                        </div>
                    </div>

                    <!-- File Preview -->
                    <div id="filePreview" class="mt-3" style="display: none;">
                        <div class="selected-file p-3 border rounded bg-light">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file fa-2x text-success me-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1" id="fileName">اسم الملف</h6>
                                    <small class="text-muted" id="fileSize">حجم الملف</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div id="uploadProgress" class="mt-3" style="display: none;">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <small class="text-muted mt-1">جاري رفع الملف...</small>
                    </div>

                    <!-- Error Messages -->
                    <div id="uploadErrors" class="alert alert-danger mt-3" style="display: none;"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary" id="uploadBtn" disabled>
                        <i class="fas fa-upload me-2"></i>رفع التقرير
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endif
@if($doctor->membership_status->value == "under_edit")

         
            <form method="POST" action="{{ route('doctor.visitor-doctors.update', $doctor) }}" dir="rtl" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Tabs Navigation - Simplified to 2 tabs -->
                <ul class="nav nav-tabs" id="editFormTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="information-tab" data-bs-toggle="tab" data-bs-target="#information" type="button" role="tab">
                            <i class="fas fa-user-circle"></i> المعلومات
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                            <i class="fas fa-file-alt"></i> المستندات
                            <span class="badge bg-danger ms-2">مطلوب</span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="editFormTabContent">
                    <!-- Information Tab - Combines all personal, contact, education, and work info -->
                    <div class="tab-pane fade show active" id="information" role="tabpanel">
                        
                        <!-- Personal Information Section -->
                        <div class="form-section">
                            <h4><i class="fas fa-info-circle me-2"></i>المعلومات الأساسية</h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="required-field">الاسم بالكامل</label>
                                    <input type="text" required name="name" value="{{old('name', $doctor->name)}}" class="form-control">
                                    <input type="hidden" name="type" value="{{request('type', 'visitor')}}">
                                </div>
        
                                <div class="col-md-6 mb-3">
                                    <label class="required-field">الجنسية</label>
                                    <select name="country_id" required id="country_id" class="form-control select2" 
                                    @if(request('type') == "libyan" || request('type') == "palestinian") disabled @endif>
                                        <option value="">حدد دولة من القائمة</option>
                                        @foreach ($countries as $country)
                                            @if ($country->id != 1 && $country->id != 2)
                                                <option value="{{$country->id}}" {{old('country_id',$doctor->country_id) == $country->id ? "selected" : ""}}>
                                                    {{$country->nationality_name_ar}}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
        
        
                                <div class="col-md-6 mb-3">
                                    <label class="required-field">تاريخ الزيارة من</label>
                                    <input type="date" required name="visit_from" value="{{old('visit_from', $doctor->visit_from )}}" class="form-control">
                                </div>
        
                                <div class="col-md-6 mb-3">
                                    <label class="required-field">تاريخ الزيارة إلى</label>
                                    <input type="date" required name="visit_to" value="{{old('visit_to', $doctor->visit_to )}}" class="form-control">
                                </div>
                            </div>
                        </div>


                        <!-- Contact Information Section -->
                        <div class="form-section">
                            <h4><i class="fas fa-address-book me-2"></i>معلومات الاتصال</h4>
                            
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>رقم الواتساب</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                        <input type="tel" name="phone_2" value="{{ old('phone_2', $doctor->phone_2) }}" maxlength="10" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>البريد الإلكتروني</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" value="{{ old('email', $doctor->email) }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Education Section -->
                        <div class="form-section">
                            <h4><i class="fas fa-graduation-cap me-2"></i>المؤهلات العلمية</h4>
                            
                            <!-- Bachelor's Degree -->
                            <h5><i class="fas fa-user-graduate me-2"></i>بكالوريوس</h5>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label>جهة التخرج</label>
                                    <select name="hand_graduation_id" class="form-control select2">
                                        <option value="">حدد جهة التخرج</option>
                                        @foreach ($universities as $university)
                                            <option value="{{ $university->id }}" {{ old('hand_graduation_id', $doctor->hand_graduation_id) == $university->id ? "selected" : "" }}>
                                                {{ $university->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="required-field">تاريخ الحصول عليها</label>
                                    @if ($is_libyan)
                                    <select name="graduation_date" class="form-control select2">
                                        <option value="">حدد السنة</option>
                                        @php
                                            $currentYear = date('Y');
                                            $years = range($currentYear, 1950);
                                        @endphp
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}" {{ old('graduation_date', $doctor->graduation_date) == $year ? "selected" : "" }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @else
                                    <input type="date" name="graduation_date" value="{{ old('graduation_date', $doctor->graduation_date) }}" class="form-control">
                                    @endif
                                </div>
                            </div>

                            <!-- Internship -->
                            <h5><i class="fas fa-stethoscope me-2"></i>الامتياز</h5>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label>جهة الحصول على الامتياز</label>
                                    <select name="qualification_university_id" class="form-control select2">
                                        <option value="">حدد جهة</option>
                                        @foreach ($universities as $university)
                                            <option value="{{ $university->id }}" {{ old('qualification_university_id', $doctor->qualification_university_id) == $university->id ? "selected" : "" }}>
                                                {{ $university->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>تاريخ الحصول عليها</label>
                                    @if ($is_libyan)
                                    <select name="internership_complete" class="form-control select2">
                                        <option value="">حدد السنة</option>
                                        @php
                                            $currentYear = date('Y');
                                            $years = range($currentYear, 1950);
                                        @endphp
                                        @foreach ($years as $year)
                                            <option value="{{ $year }}" {{ old('internership_complete', $doctor->internership_complete) == $year ? "selected" : "" }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @else
                                    <input type="date" name="internership_complete" value="{{ old('internership_complete', $doctor->internership_complete) }}" class="form-control">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Work Information Section -->
                        <div class="form-section">
                            <h4><i class="fas fa-briefcase me-2"></i>بيانات العمل</h4>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label>الصفة</label>
                                    <select name="doctor_rank_id" class="form-control select2">
                                        <option value="">حدد الصفة</option>
                                        @foreach ($doctor_ranks as $doctor_rank)
                                            @if ($is_visitor && ($doctor_rank->id != 1 && $doctor_rank->id != 2))
                                                <option value="{{ $doctor_rank->id }}" {{ old('doctor_rank_id', $doctor->doctor_rank_id) == $doctor_rank->id ? "selected" : "" }}>
                                                    {{ $doctor_rank->name }}
                                                </option>
                                            @elseif (!$is_visitor)
                                                <option value="{{ $doctor_rank->id }}" {{ old('doctor_rank_id', $doctor->doctor_rank_id) == $doctor_rank->id ? "selected" : "" }}>
                                                    {{ $doctor_rank->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="required-field">تخصص أول</label>
                                    <select name="specialty_1_id" class="form-control select2" required>
                                        <option value="">حدد تخصص أول</option>
                                        @foreach ($specialties as $specialty)
                                            <option value="{{ $specialty->id }}" {{ old('specialty_1_id', $doctor->specialty_1_id) == $specialty->id ? "selected" : "" }}>
                                                {{ $specialty->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>تخصص دقيق (إن وجد)</label>
                                    <input type="text" name="specialty_2" value="{{ old('specialty_2', $doctor->specialty_2) }}" class="form-control" autocomplete="off" placeholder="أدخل التخصص الدقيق">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Documents Tab -->
                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <div class="form-section">
                            <h4><i class="fas fa-folder-open me-2"></i>المستندات المطلوبة</h4>
                            
                            <div class="alert alert-warning d-flex align-items-center mb-4">
                                <i class="fas fa-exclamation-triangle me-3 fs-4"></i>
                                <div>
                                    <strong>تنبيه مهم:</strong> يجب إعادة رفع جميع المستندات المطلوبة للتحقق من صحتها.
                                    <br>
                                    <small>الملفات المسموحة: PDF, JPG, PNG (الحجم الأقصى: 2MB لكل ملف)</small>
                                </div>
                            </div>
                            
                            <div class="file-upload-section">
                                @foreach($doctor->files as $document)
                                <div class="file-upload-item">
                                    <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                                    <h5 class="mb-3">{{ $document->fileType->name }}</h5>
                                    
                                    @if($document->file_path)
                                    <div class="mb-3">
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-2"></i>عرض الملف الحالي
                                        </a>
                                    </div>
                                    @endif
                                    
                                    <div class="btn-file-upload">
                                        <button type="button" class="btn btn-warning">
                                            <i class="fas fa-cloud-upload-alt me-2"></i>رفع ملف جديد
                                        </button>
                                        <input type="file" 
                                               name="reupload_files[{{ $document->id }}]" 
                                               accept=".pdf,.jpg,.jpeg,.png"
                                               onchange="updateFileName(this, {{ $document->id }})">
                                    </div>
                                    
                                    <div id="fileName_{{ $document->id }}" class="file-name-display"></div>
                                </div>
                                @endforeach

                                @if($doctor->files->count() == 0)
                                <div class="col-12 text-center py-5">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                    <p class="text-muted">لا توجد مستندات مطلوبة حالياً</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-4 text-center">
                    <button type="button" class="btn btn-secondary btn-lg me-2" onclick="window.location.href='/doctor/dashboard'">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </button>
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-paper-plane me-2"></i>حفظ التعديلات وإرسال للمراجعة
                    </button>
                </div>
            </form>

        </div>
    </div>
   
</div>

@endif

<div class="modal fade" id="renewSubscriptionModal" tabindex="-1" aria-labelledby="renewSubscriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-light" id="renewSubscriptionModalLabel">
                    <i class="fas fa-redo me-2"></i>
                    تجديد اشتراك الطبيب الزائر
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form method="POST" action="{{route('doctor.visitor-doctors.renew',$doctor)}}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="fas fa-user-md me-3 fs-4"></i>
                        <div>
                            <h6 class="mb-1">الطبيب: {{ $doctor->name }}</h6>
                            <p class="mb-0 small text-light">الزيارة السابقة: {{date('Y-m-d',strtotime($doctor->visit_from))}} - {{ date('Y-m-d',strtotime($doctor->visit_to)) }}</p>
                        </div>
                    </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="required-field">تاريخ الزيارة الجديدة من</label>
                                <input type="date" id="newVisitFrom" name="visit_from" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="required-field">تاريخ الزيارة الجديدة إلى</label>
                                <input type="date" id="newVisitTo" name="visit_to" class="form-control" required>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-success" >
                            <i class="fas fa-redo me-2"></i>تجديد الاشتراك
                        </button>

                    <div id="renewErrors" class="alert alert-danger mt-3" style="display: none;"></div>
                </div>
 
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Update file name display
function updateFileName(input, id) {
    const fileName = input.files[0]?.name || '';
    const fileNameDisplay = document.getElementById(`fileName_${id}`);
    
    if (fileName) {
        fileNameDisplay.innerHTML = `<i class="fas fa-check-circle me-1"></i> تم اختيار: ${fileName}`;
        fileNameDisplay.style.display = 'block';
        
        // Add success animation to the upload item
        const uploadItem = fileNameDisplay.closest('.file-upload-item');
        uploadItem.style.borderColor = '#28a745';
        uploadItem.style.backgroundColor = '#f8fff9';
    } else {
        fileNameDisplay.style.display = 'none';
    }
}

// Initialize plugins and tab functionality
$(document).ready(function() {
    // Initialize Selectize
    $('.selectize').selectize({
        create: false,
        sortField: 'text',
        placeholder: 'اختر من القائمة...'
    });
    
    // Initialize Select2
    $('.select2').select2({
        language: 'ar',
        dir: 'rtl',
        placeholder: 'اختر من القائمة...'
    });
    
    // Update progress indicator on tab change - Simplified for 2 tabs
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const targetId = $(e.target).attr('id');
        const stepMap = {
            'information-tab': 1,
            'documents-tab': 2
        };
        
        const currentStep = stepMap[targetId];
        
        // Update progress steps
        $('.progress-step').each(function(index) {
            const stepNumber = index + 1;
            if (stepNumber < currentStep) {
                $(this).addClass('completed').removeClass('active');
            } else if (stepNumber === currentStep) {
                $(this).addClass('active').removeClass('completed');
            } else {
                $(this).removeClass('active completed');
            }
        });
    });
    
    // Form validation feedback
    $('input[required], select[required]').on('blur', function() {
        if (!$(this).val()) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    // Smooth scroll to top when changing tabs
    $('button[data-bs-toggle="tab"]').on('click', function() {
        $('html, body').animate({ scrollTop: $('.form-container').offset().top - 20 }, 300);
    });
    
    // Add loading state to submit button
    $('form').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...');
    });
});

// Add keyboard navigation between tabs - Simplified for 2 tabs
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && (e.key === 'ArrowRight' || e.key === 'ArrowLeft')) {
        e.preventDefault();
        const activeTab = document.querySelector('.nav-tabs .nav-link.active');
        const allTabs = Array.from(document.querySelectorAll('.nav-tabs .nav-link'));
        const currentIndex = allTabs.indexOf(activeTab);
        
        let newIndex;
        if (e.key === 'ArrowRight' && currentIndex > 0) {
            newIndex = currentIndex - 1;
        } else if (e.key === 'ArrowLeft' && currentIndex < allTabs.length - 1) {
            newIndex = currentIndex + 1;
        }
        
        if (newIndex !== undefined) {
            allTabs[newIndex].click();
        }
    }
});
</script>
<script>
    // Global variables
    let currentVisitorId = null;
    
    // Open upload modal
    function openUploadModal(visitorId, visitorName, visitFrom, visitTo) {
        currentVisitorId = visitorId;
        
        // Update modal content
        document.getElementById('visitorId').value = visitorId;
        document.getElementById('doctorInfo').innerHTML = `
            <strong>${visitorName}</strong><br>
            <small class="text-muted">فترة الزيارة: ${visitFrom} إلى ${visitTo}</small>
        `;
        
        // Reset form
        resetUploadForm();
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('uploadReportModal'));
        modal.show();
    }
    
    // Reset upload form
    function resetUploadForm() {
        document.getElementById('uploadReportForm').reset();
        document.getElementById('filePreview').style.display = 'none';
        document.getElementById('uploadProgress').style.display = 'none';
        document.getElementById('uploadErrors').style.display = 'none';
        document.getElementById('uploadBtn').disabled = true;
        
        const uploadArea = document.getElementById('uploadArea');
        uploadArea.classList.remove('error', 'success', 'dragover');
    }
    
    // File selection handling
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('reportFile');
        const uploadForm = document.getElementById('uploadReportForm');
        
        // Click to select file
        uploadArea.addEventListener('click', () => fileInput.click());
        
        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileSelection();
            }
        });
        
        // File input change
        fileInput.addEventListener('change', handleFileSelection);
        
        // Form submission
        uploadForm.addEventListener('submit', handleFormSubmission);
    });
    
    // Handle file selection
    function handleFileSelection() {
        const fileInput = document.getElementById('reportFile');
        const file = fileInput.files[0];
        
        if (!file) return;
        
        // Validate file
        const validationResult = validateFile(file);
        if (!validationResult.valid) {
            showError(validationResult.message);
            return;
        }
        
        // Show file preview
        showFilePreview(file);
        document.getElementById('uploadBtn').disabled = false;
        document.getElementById('uploadArea').classList.add('success');
    }
    
    // Validate file
    function validateFile(file) {
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        
        if (file.size > maxSize) {
            return {
                valid: false,
                message: 'حجم الملف يجب أن يكون أقل من 2 ميجابايت'
            };
        }
        
        if (!allowedTypes.includes(file.type)) {
            return {
                valid: false,
                message: 'نوع الملف غير مدعوم. يرجى اختيار ملف PDF أو صورة (JPG, PNG)'
            };
        }
        
        return { valid: true };
    }
    
    // Show file preview
    function showFilePreview(file) {
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatFileSize(file.size);
        document.getElementById('filePreview').style.display = 'block';
        document.getElementById('uploadErrors').style.display = 'none';
    }
    
    // Remove file
    function removeFile() {
        document.getElementById('reportFile').value = '';
        document.getElementById('filePreview').style.display = 'none';
        document.getElementById('uploadBtn').disabled = true;
        document.getElementById('uploadArea').classList.remove('success', 'error');
    }
    
    // Handle form submission
    async function handleFormSubmission(e) {
        e.preventDefault();
        
        const formData = new FormData();
        const fileInput = document.getElementById('reportFile');
        const visitorId = document.getElementById('visitorId').value;
        
        if (!fileInput.files[0]) {
            showError('يرجى اختيار ملف للرفع');
            return;
        }
        
        formData.append('report_file', fileInput.files[0]);
        formData.append('visitor_id', visitorId);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        try {
            // Show progress
            showProgress();
            
            const response = await fetch(`/doctor/visitor-doctors/${visitorId}/upload-report`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (response.ok) {
                // Success
                showSuccess('تم رفع التقرير بنجاح');
                setTimeout(() => {
                    location.reload(); // Reload to update the interface
                }, 1500);
            } else {
                // Error
                showError(result.message || 'حدث خطأ أثناء رفع الملف');
            }
        } catch (error) {
            showError('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى');
        } finally {
            hideProgress();
        }
    }
    
    // Show progress
    function showProgress() {
        document.getElementById('uploadProgress').style.display = 'block';
        document.getElementById('uploadBtn').disabled = true;
        
        // Simulate progress
        let progress = 0;
        const progressBar = document.querySelector('#uploadProgress .progress-bar');
        const interval = setInterval(() => {
            progress += 10;
            progressBar.style.width = progress + '%';
            
            if (progress >= 90) {
                clearInterval(interval);
            }
        }, 100);
    }
    
    // Hide progress
    function hideProgress() {
        document.getElementById('uploadProgress').style.display = 'none';
        document.querySelector('#uploadProgress .progress-bar').style.width = '0%';
        document.getElementById('uploadBtn').disabled = false;
    }
    
    // Show error
    function showError(message) {
        const errorDiv = document.getElementById('uploadErrors');
        errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
        errorDiv.style.display = 'block';
        document.getElementById('uploadArea').classList.add('error');
    }
    
    // Show success
    function showSuccess(message) {
        const errorDiv = document.getElementById('uploadErrors');
        errorDiv.innerHTML = `<i class="fas fa-check-circle me-2"></i>${message}`;
        errorDiv.className = 'alert alert-success mt-3';
        errorDiv.style.display = 'block';
    }
    
    // Format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    </script>

 @section('scripts')
<script>
// فتح modal التجديد
function openRenewModal() {
    // تعيين التاريخ الافتراضي
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('newVisitFrom').value = today;
    
    // إظهار modal
    const modal = new bootstrap.Modal(document.getElementById('renewSubscriptionModal'));
    modal.show();
}

// معالجة form التجديد
document.addEventListener('DOMContentLoaded', function() {
    const renewForm = document.getElementById('renewSubscriptionForm');
    
    renewForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const visitFrom = document.getElementById('newVisitFrom').value;
        const visitTo = document.getElementById('newVisitTo').value;
        
        // التحقق من التواريخ
        if (!visitFrom || !visitTo) {
            showRenewError('يرجى إدخال جميع التواريخ المطلوبة');
            return;
        }
        
        if (new Date(visitFrom) >= new Date(visitTo)) {
            showRenewError('تاريخ البداية يجب أن يكون قبل تاريخ النهاية');
            return;
        }
        
        try {
            // تعطيل الزر أثناء الإرسال
            const renewBtn = document.getElementById('renewBtn');
            renewBtn.disabled = true;
            renewBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التجديد...';
            
            const formData = new FormData();
            formData.append('visit_from', visitFrom);
            formData.append('visit_to', visitTo);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            const response = await fetch(`/doctor/visitor-doctors/{{ $doctor->id }}/renew`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (response.ok) {
                // نجح التجديد - الانتقال لصفحة رفع المستندات
                window.location.href = result.redirect_url;
            } else {
                showRenewError(result.message || 'حدث خطأ أثناء التجديد');
                renewBtn.disabled = false;
                renewBtn.innerHTML = '<i class="fas fa-redo me-2"></i>تجديد الاشتراك';
            }
        } catch (error) {
            showRenewError('حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى');
            renewBtn.disabled = false;
            renewBtn.innerHTML = '<i class="fas fa-redo me-2"></i>تجديد الاشتراك';
        }
    });
});

// إظهار خطأ التجديد
function showRenewError(message) {
    const errorDiv = document.getElementById('renewErrors');
    errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
    errorDiv.style.display = 'block';
}
</script>
@endsection
