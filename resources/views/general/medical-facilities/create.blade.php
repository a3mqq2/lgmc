@extends('layouts.' . get_area_name())
@section('title', 'إنشاء منشأة طبية جديدة')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-12">
            <!-- Progress Steps -->
            <div class="card shadow-sm mb-4">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="step-item active" id="step-indicator-1">
                            <div class="step-circle">
                                <i class="fas fa-hospital-alt"></i>
                            </div>
                            <span class="step-label">نوع المنشأة</span>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-item" id="step-indicator-3">
                            <div class="step-circle">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <span class="step-label">البيانات الأساسية</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <form action="{{ route(get_area_name().'.medical-facilities.store') }}" method="POST" enctype="multipart/form-data" id="medicalFacilityForm">
                @csrf
                
                <!-- Step 1: Medical Facility Type Selection -->
                <div class="step-content" id="step-1">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-primary text-white text-center py-4">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-hospital-alt me-2"></i>
                                اختر نوع المنشأة الطبية
                            </h4>
                            <p class="mb-0 mt-2 opacity-75">حدد نوع المنشأة التي تريد إنشاؤها</p>
                        </div>
                        <div class="card-body p-5">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="facility-type-card" data-type="private_clinic">
                                        <div class="card h-100 border-2 facility-option">
                                            <div class="card-body text-center p-4">
                                                <div class="facility-icon mb-3">
                                                    <i class="fas fa-stethoscope"></i>
                                                </div>
                                                <h5 class="card-title">عيادة خاصة</h5>
                                                <p class="card-text text-muted">
                                                    للأطباء الذين يرغبون في فتح عيادة طبية خاصة
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="facility-type-card" data-type="medical_services">
                                        <div class="card h-100 border-2 facility-option">
                                            <div class="card-body text-center p-4">
                                                <div class="facility-icon mb-3">
                                                    <i class="fas fa-briefcase-medical"></i>
                                                </div>
                                                <h5 class="card-title">خدمات طبية</h5>
                                                <p class="card-text text-muted">
                                                    للشركات التي تقدم خدمات طبية متخصصة
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="type" id="selected_facility_type">
                        </div>
                    </div>
                </div>

                <!-- Step 3: Medical Facility Information -->
                <div class="step-content d-none" id="step-2">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-success text-white text-center py-4">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                <span id="facility-info-title">بيانات المنشأة الطبية</span>
                            </h4>
                            <p class="mb-0 mt-2 opacity-75">أدخل البيانات الأساسية للمنشأة</p>
                        </div>
                        <div class="card-body p-5">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="facility_name" name="name" placeholder="اسم المنشأة" required>
                                        <label for="facility_name" id="facility_name_label">اسم المنشأة</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="commercial_number" name="commercial_number" placeholder="سجل تجاري" required>
                                        <label for="commercial_number" id="commercial_number_label"> سجل تجاري  </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="address" name="address" placeholder="الموقع">
                                        <label for="address">الموقع</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="رقم الهاتف" maxlength="10" required>
                                        <label for="phone_number">رقم الهاتف</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="last_issued_date" name="last_issued_date" placeholder="تاريخ اخر تجديد ">
                                        <label for="last_issued_date"> تاريخ اخر تجديد  </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">اسم الطبيب</label>
                                    <select name="manager_id" id="manager_select" class="form-select select2" 
                                        @if (get_area_name() == "user") required @endif>
                                        <option value="">حدد اسم الطبيب</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary btn-lg" id="prevBtn" style="display: none;">
                        <i class="fas fa-arrow-right me-2"></i>السابق
                    </button>
                    <button type="button" class="btn btn-primary btn-lg" id="nextBtn">
                        التالي<i class="fas fa-arrow-left ms-2"></i>
                    </button>
                    <button type="submit" class="btn btn-success btn-lg" id="submitBtn" style="display: none;">
                        <i class="fas fa-check me-2"></i>إنشاء المنشأة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Include Select2 CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<style>
/* Progress Steps Styling */
.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
    position: relative;
}

.step-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #6c757d;
    transition: all 0.3s ease;
    margin-bottom: 8px;
}

.step-item.active .step-circle {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    transform: scale(1.1);
}

.step-item.completed .step-circle {
    background: linear-gradient(45deg, #28a745, #1e7e34);
    color: white;
}

.step-label {
    font-size: 14px;
    font-weight: 500;
    color: #6c757d;
    text-align: center;
}

.step-item.active .step-label,
.step-item.completed .step-label {
    color: #495057;
    font-weight: 600;
}

.step-line {
    height: 2px;
    background: #e9ecef;
    flex: 1;
    margin: 0 20px;
    margin-top: -25px;
    position: relative;
    z-index: -1;
}

/* Facility Type Cards */
.facility-option {
    cursor: pointer;
    transition: all 0.3s ease;
    border-color: #dee2e6 !important;
}

.facility-option:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: #007bff !important;
}

.facility-option.selected {
    border-color: #007bff !important;
    background: linear-gradient(45deg, #f8f9ff, #e3f2fd);
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,123,255,0.2);
}

.facility-icon {
    font-size: 3rem;
    color: #007bff;
    margin-bottom: 1rem;
}

.facility-option.selected .facility-icon {
    color: #0056b3;
    animation: pulse 2s infinite;
}

/* Branch Cards */
.branch-option {
    cursor: pointer;
    transition: all 0.3s ease;
    border-color: #dee2e6 !important;
}

.branch-option:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: #17a2b8 !important;
}

.branch-option.selected {
    border-color: #17a2b8 !important;
    background: linear-gradient(45deg, #f0fdff, #e0f7fa);
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(23,162,184,0.2);
}

.branch-icon {
    font-size: 2.5rem;
    color: #17a2b8;
    margin-bottom: 1rem;
}

.branch-option.selected .branch-icon {
    color: #138496;
    animation: pulse 2s infinite;
}

/* Selected Branch Display */
.selected-branch-display .branch-icon {
    font-size: 4rem;
    color: #17a2b8;
}

/* Gradient Headers */
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3) !important;
}

.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #138496) !important;
}

.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #1e7e34) !important;
}

/* Form Styling */
.form-floating > .form-control,
.form-floating > .form-select {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    min-height: 58px;
}

.form-floating > .form-control:focus,
.form-floating > .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
}

/* Custom Select Styling */
.custom-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m1 6 7 7 7-7'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: left 0.75rem center;
    background-size: 16px 12px;
    padding-left: 2.25rem;
}

/* Select2 Custom Styling for Floating Labels */
.form-floating .select2-container {
    width: 100% !important;
}

.form-floating .select2-container .select2-selection--single {
    height: 58px !important;
    border: 2px solid #e9ecef !important;
    border-radius: 0.375rem !important;
    padding-top: 1.625rem !important;
    padding-bottom: 0.625rem !important;
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
    transition: all 0.3s ease;
}

.form-floating .select2-container .select2-selection--single:focus-within,
.form-floating .select2-container.select2-container--open .select2-selection--single {
    border-color: #007bff !important;
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25) !important;
}

.form-floating .select2-container .select2-selection__rendered {
    color: #495057;
    padding: 0 !important;
    line-height: 1.25 !important;
    font-size: 1rem;
}

.form-floating .select2-container .select2-selection__arrow {
    height: 54px !important;
    right: 0.75rem !important;
}

/* Select2 placeholder styling */
.form-floating .select2-container .select2-selection__placeholder {
    color: transparent !important;
}

/* Floating label active state for Select2 */
.form-floating label.active,
.form-floating .select2-container .select2-selection--single:focus-within + label,
.form-floating .select2-container.select2-container--open .select2-selection--single + label,
.form-floating .select2-container .select2-selection__rendered:not(.select2-selection__placeholder) + label {
    opacity: 0.65;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
}

/* Select2 dropdown styling */
.select2-dropdown {
    border: 2px solid #007bff !important;
    border-radius: 0.375rem !important;
    box-shadow: 0 0.5rem 1rem rgba(0,123,255,0.15) !important;
}

.select2-results__option {
    padding: 0.75rem !important;
    font-size: 1rem !important;
}

.select2-results__option--highlighted {
    background-color: #007bff !important;
}

/* Animations */
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.step-content {
    animation: fadeInUp 0.5s ease;
}

/* Responsive Design */
@media (max-width: 768px) {
    .step-line {
        display: none;
    }
    
    .step-item {
        margin-bottom: 20px;
    }
    
    .facility-icon,
    .branch-icon {
        font-size: 2rem;
    }
}
</style>

<script>
$(document).ready(function() {
    let currentStep = 1;
    const totalSteps = 2;
    let selectedFacilityType = '';
    let selectedBranch = '';

    // Step navigation
    function showStep(step) {
        $('.step-content').addClass('d-none');
        $(`#step-${step}`).removeClass('d-none');
        
        // Update step indicators
        $('.step-item').removeClass('active completed');
        for(let i = 1; i <= step; i++) {
            if(i < step) {
                $(`#step-indicator-${i}`).addClass('completed');
            } else if(i === step) {
                $(`#step-indicator-${i}`).addClass('active');
            }
        }
        
        // Update navigation buttons
        $('#prevBtn').toggle(step > 1);
        $('#nextBtn').toggle(step < totalSteps);
        $('#submitBtn').toggle(step === totalSteps);
        
        currentStep = step;
    }

    // Facility type selection
    $('.facility-type-card').click(function() {
        $('.facility-option').removeClass('selected');
        $(this).find('.facility-option').addClass('selected');
        
        selectedFacilityType = $(this).data('type');
        $('#selected_facility_type').val(selectedFacilityType);
        
        // Update labels for step 3 based on selection
        if(selectedFacilityType === 'private_clinic') {
            $('#facility-info-title').text('بيانات العيادة الخاصة');
            $('#facility_name_label').text('اسم العيادة');
            $('#facility_name').attr('placeholder', 'اسم العيادة');
        } else if(selectedFacilityType === 'medical_services') {
            $('#facility-info-title').text('بيانات الشركة الطبية');
            $('#facility_name_label').text('اسم الشركة');
            $('#facility_name').attr('placeholder', 'اسم الشركة');
        }
        
        // Enable next button
        $('#nextBtn').prop('disabled', false);
    });

    // Branch selection (for admin users)
    $('.branch-card').click(function() {
        $('.branch-option').removeClass('selected');
        $(this).find('.branch-option').addClass('selected');
        
        selectedBranch = $(this).data('branch');
        $('#selected_branch').val(selectedBranch);
        
        // Enable next button
        $('#nextBtn').prop('disabled', false);
    });

    // Next button click
    $('#nextBtn').click(function() {
        if(validateCurrentStep()) {
            if(currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        }
    });

    // Previous button click
    $('#prevBtn').click(function() {
        if(currentStep > 1) {
            showStep(currentStep - 1);
        }
    });

    // Validation
    function validateCurrentStep() {
        if(currentStep === 1) {
            if(!selectedFacilityType) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: 'يرجى اختيار نوع المنشأة الطبية'
                });
                return false;
            }
        } else if(currentStep === 2) {
            @if (get_area_name() == "admin")
            if(!selectedBranch) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: 'يرجى اختيار الفرع'
                });
                return false;
            }
            @endif
        }
        return true;
    }

    // Setup Select2 for manager selection
    function setupSelect2() {
        let branch_id = '{{ auth()->user()->branch_id ?? "" }}';
        if(!branch_id && selectedBranch) {
            branch_id = selectedBranch;
        }
        
        $('#manager_select').select2({
            placeholder: 'ابحث عن اسم الطبيب...',
            ajax: {
                url: '/search-licensables?branch_id=' + branch_id + "&justactive=1",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { query: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data.map(function(item) {
                            return { id: item.id, text: item.name };
                        })
                    };
                },
                cache: true
            },
            minimumInputLength: 2
        });
    }

    // Initialize Select2 when reaching step 3
    $(document).on('click', '#nextBtn', function() {
        if(currentStep === 2) {
            setTimeout(setupSelect2, 100);
        }
    });

    // Form submission
    $('#medicalFacilityForm').submit(function(e) {
        if(!validateCurrentStep()) {
            e.preventDefault();
        }
    });

    // Initialize first step
    showStep(1);
    $('#nextBtn').prop('disabled', true);
});
</script>
@endsection