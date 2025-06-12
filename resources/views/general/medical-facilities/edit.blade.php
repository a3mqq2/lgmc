@extends('layouts.' . get_area_name())
@section('title', 'تعديل منشأة طبية')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-12">
            <!-- Progress Steps -->
            <div class="card shadow-sm mb-4">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="step-item active" id="step-indicator-1">
                            <div class="step-circle">
                                <i class="fas fa-edit"></i>
                            </div>
                            <span class="step-label">تعديل البيانات</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <form action="{{ route(get_area_name().'.medical-facilities.update', $medicalFacility->id) }}" method="POST" enctype="multipart/form-data" id="medicalFacilityForm">
                @csrf
                @method('PUT')
                
                <!-- Medical Facility Information -->
                <div class="step-content" id="step-1">
                    <div class="card shadow-lg border-0">
                        <div class="card-header bg-gradient-warning text-white text-center py-4">
                            <h4 class="card-title mb-0">
                                <i class="fas fa-edit me-2"></i>
                                تعديل بيانات المنشأة الطبية
                            </h4>
                            <p class="mb-0 mt-2 opacity-75">يمكنك تعديل البيانات الأساسية للمنشأة</p>
                        </div>
                        <div class="card-body p-5">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="facility_name" name="name" 
                                               placeholder="اسم المنشأة" value="{{ old('name', $medicalFacility->name) }}" required>
                                        <label for="facility_name">
                                            @if($medicalFacility->type == 'private_clinic')
                                                اسم العيادة
                                            @else
                                                اسم الشركة
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="facility_name" name="commercial_number" 
                                               placeholder="سجل تجاري" value="{{ old('commercial_number', $medicalFacility->commercial_number) }}" required>
                                        <label for="facility_name">
                                           سجل تجاري
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="address" name="address" 
                                               placeholder="الموقع" value="{{ old('address', $medicalFacility->address) }}">
                                        <label for="address">الموقع</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                               placeholder="رقم الهاتف" maxlength="10" value="{{ old('phone_number', $medicalFacility->phone_number) }}" required>
                                        <label for="phone_number">رقم الهاتف</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">اسم الطبيب</label>
                                    <select name="manager_id"  class="form-select select2" 
                                        @if (get_area_name() == "user") required @endif>
                                        <option value="">حدد اسم الطبيب</option>
                                        @foreach ($doctors as $doctor)
                                        @if($doctor->id != $medicalFacility->manager_id)
                                            <option value="{{ $doctor->id }}" {{ old('manager_id', $medicalFacility->manager_id) == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between mt-4">
                    <div>
                        <a href="{{ route(get_area_name().'.medical-facilities.index') }}" class="btn btn-outline-danger btn-lg">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                            <i class="fas fa-save me-2"></i>حفظ التعديلات
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Include SweetAlert2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

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

.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800) !important;
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
    // Setup Select2 for manager selection immediately
    function setupSelect2() {
        let branch_id = '{{ auth()->user()->branch_id ?? "" }}';
        
        $('#manager_select').select2({
            placeholder: 'ابحث عن اسم الطبيب...',
            allowClear: true,
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
            minimumInputLength: 2,
            // Keep existing options when initializing
            templateResult: function(option) {
                return option.text;
            },
            templateSelection: function(option) {
                return option.text;
            }
        });

        // Ensure the current selection is preserved
        var currentManagerId = '{{ $medicalFacility->manager_id ?? "" }}';
        if (currentManagerId) {
            $('#manager_select').val(currentManagerId).trigger('change');
        }
    }

    // Initialize Select2
    setupSelect2();

    // Form submission with confirmation
    $('#medicalFacilityForm').submit(function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'تأكيد التعديل',
            text: 'هل أنت متأكد من أنك تريد حفظ التعديلات؟',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'نعم، احفظ التعديلات',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form normally
                e.target.submit();
            }
        });
    });
});
</script>
@endsection