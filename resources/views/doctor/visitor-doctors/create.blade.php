@extends('layouts.doctor')

@section('styles')
<style>
/* ===== Custom Styles for Form ===== */
.form-section { 
    background: #f8f9fa; 
    padding: 20px; 
    border-radius: 10px; 
    margin-bottom: 20px; 
}
.form-section h4 { 
    border-bottom: 2px solid #007bff; 
    padding-bottom: 10px; 
}
.required-field::after {
    content: " *";
    color: red;
}

/* Alert Styles for Missing Reports */
.alert-missing-reports {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    border: none;
    color: white;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(238,90,111,0.3);
    padding: 20px 25px;
    font-weight: 500;
    margin-bottom: 30px;
}

.alert-missing-reports h5 {
    color: white;
    font-weight: 600;
    margin-bottom: 15px;
}

.missing-doctor-item {
    background: rgba(255,255,255,0.1);
    border-radius: 8px;
    padding: 10px 15px;
    margin-bottom: 10px;
    border-left: 4px solid white;
}

.missing-doctor-item:last-child {
    margin-bottom: 0;
}

.missing-doctor-name {
    font-weight: 600;
    margin-bottom: 5px;
}

.missing-doctor-details {
    font-size: 0.9rem;
    opacity: 0.9;
}

.warning-icon {
    font-size: 1.5rem;
    margin-left: 10px;
}

.alert-info-custom {
    background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
    border: none;
    color: white;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(23,162,184,0.3);
    padding: 20px 25px;
    font-weight: 500;
    margin-bottom: 30px;
}

/* Responsive */
@media (max-width: 768px) {
    .missing-doctor-item {
        padding: 8px 12px;
    }
    
    .warning-icon {
        font-size: 1.2rem;
        margin-left: 8px;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid px-0">
    <div class="card shadow-sm">
        <div class="card-body">
            
            <h3 class="fw-bold text-primary text-end mb-4">
                تسجيل طبيب زائر جديد
            </h3>

            @if ($errors->any())
                <div class="alert alert-danger" dir="rtl">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" dir="rtl">
                    {{ session('success') }}
                </div>
            @endif

            @php
                $currentMedicalFacility = auth('doctor')->user()->medicalFacility ?? null;
                $visitorsWithMissingReports = [];
                
                if ($currentMedicalFacility) {
                    $visitorsWithMissingReports = $currentMedicalFacility->getVisitorDoctorsWithExpiredMembershipMissingReportFile();
                }
            @endphp

            @if($visitorsWithMissingReports && $visitorsWithMissingReports->count() > 0)
                <div class="alert-missing-reports d-flex" role="alert" dir="rtl">
                    <div class="warning-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="mb-3">
                            <i class="fas fa-file-missing me-2"></i>
                            تنبيه: لا يمكن تسجيل طبيب زائر جديد
                        </h5>
                        <p class="mb-3">
                            <strong>يجب رفع "صورة من التقرير بعد زيارة الطبيب الزائر" للأطباء التالية أسماؤهم قبل تسجيل طبيب زائر جديد:</strong>
                        </p>
                        
                        <div class="missing-doctors-list">
                            @foreach($visitorsWithMissingReports as $doctor)
                                <div class="missing-doctor-item">
                                    <div class="missing-doctor-name">
                                        <i class="fas fa-user-md me-2"></i>
                                        {{ $doctor->name }}
                                    </div>
                                    <div class="missing-doctor-details">
                                        <i class="fas fa-passport me-1"></i>
                                        جواز السفر: {{ $doctor->passport_number ?? 'غير محدد' }}
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-calendar me-1"></i>
                                        فترة الزيارة: {{ date('Y-m-d', strtotime($doctor->visit_from) ) ?? 'غير محدد' }} - {{ date('Y-m-d', strtotime($doctor->visit_to)) ?? 'غير محدد' }}
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-flag me-1"></i>
                                        الجنسية: {{ $doctor->country->nationality_name_ar ?? 'غير محدد' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 text-center">
                     
                            <button type="button" class="btn btn-light btn-lg" onclick="window.location.href='{{ route('doctor.my-facility',['visitors' => 1]) }}'">
                                <i class="fas fa-arrow-left me-2"></i>العودة للرئيسية
                            </button>
                        </div>
                        
                        <div class="mt-3">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                يرجى رفع التقارير المطلوبة أولاً ثم العودة لتسجيل طبيب زائر جديد.
                            </small>
                        </div>
                    </div>
                </div>
            @else

                <form method="POST" action="{{ route('doctor.visitor-doctors.store') }}" dir="rtl" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <!-- البيانات الشخصية -->
                <div class="form-section">
                    <h4 class="text-primary mb-3">البيانات الشخصية</h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="required-field">الاسم بالكامل</label>
                            <input type="text" required name="name" value="{{old('name')}}" class="form-control">
                            <input type="hidden" name="type" value="{{request('type', 'visitor')}}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="required-field">الجنسية</label>
                            <select name="country_id" required id="country_id" class="form-control select2" 
                            @if(request('type') == "libyan" || request('type') == "palestinian") disabled @endif>
                                <option value="">حدد دولة من القائمة</option>
                                @foreach ($countries as $country)
                                    @if ($country->id != 1 && $country->id != 2)
                                        <option value="{{$country->id}}" {{old('country_id') == $country->id ? "selected" : ""}}>
                                            {{$country->nationality_name_ar}}
                                        </option>
                                    @endif
                                @endforeach
                            </select>

                            @if (request('type') == "palestinian")
                                <input type="hidden" name="country_id" value="2">
                            @endif

                            @if (request('type') == "libyan")
                                <input type="hidden" name="country_id" value="1">
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="required-field">رقم جواز السفر</label>
                            <input type="text" required name="passport_number" value="{{old('passport_number')}}" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="required-field">تاريخ انتهاء صلاحية جواز السفر</label>
                            <input type="date" required name="passport_expiration" value="{{old('passport_expiration')}}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="required-field">تاريخ الزيارة من</label>
                            <input type="date" required name="visit_from" value="{{old('visit_from', date('Y-m-d'))}}" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="required-field">تاريخ الزيارة إلى</label>
                            <input type="date" required name="visit_to" value="{{old('visit_to', Carbon\Carbon::now()->addMonth()->format('Y-m-d'))}}" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- بيانات الاتصال -->
                <div class="form-section">
                    <h4 class="text-primary mb-3">بيانات الاتصال</h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>رقم الواتساب</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                                <input type="tel" name="phone_2" value="{{old('phone_2')}}" maxlength="10" class="form-control" placeholder="9XXXXXXXX">
                            </div>
                            <small class="text-muted">مثال: 912345678</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" value="{{old('email')}}" class="form-control" placeholder="example@email.com">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- المؤهلات العلمية -->
                <div class="form-section">
                    <h4 class="text-primary mb-3">المؤهلات العلمية</h4>
                    
                    <!-- بكالوريوس -->
                    <h5 class="text-secondary mb-3">
                        <i class="fas fa-user-graduate me-2"></i>بكالوريوس
                    </h5>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label>جهة التخرج</label>
                            <select name="hand_graduation_id" class="form-control select2">
                                <option value="">حدد جهة التخرج</option>
                                @foreach ($universities as $university)
                                    <option value="{{$university->id}}" {{old('hand_graduation_id') == $university->id ? "selected" : ""}}>
                                        {{$university->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="required-field">تاريخ الحصول عليها</label>
                            <input type="date" name="graduation_date" value="{{old('graduation_date')}}" class="form-control">
                        </div>
                    </div>

                    <!-- الامتياز -->
                    <h5 class="text-secondary mb-3">
                        <i class="fas fa-stethoscope me-2"></i>الامتياز
                    </h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>جهة الحصول على الامتياز</label>
                            <select name="qualification_university_id" class="form-control select2">
                                <option value="">حدد جهة</option>
                                @foreach ($universities as $university)
                                    <option value="{{$university->id}}" {{old('qualification_university_id') == $university->id ? "selected" : ""}}>
                                        {{$university->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>تاريخ الحصول عليها</label>
                            <input type="date" name="internership_complete" value="{{old('internership_complete')}}" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- بيانات العمل -->
                <div class="form-section">
                    <h4 class="text-primary mb-3">بيانات العمل الحالي</h4>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>الصفة</label>
                            <select name="doctor_rank_id" class="form-control select2">
                                <option value="">حدد الصفة</option>
                                @foreach ($doctor_ranks as $doctor_rank)
                                    @if (request('type') == "visitor" && ($doctor_rank->id != 1 && $doctor_rank->id != 2))
                                        <option value="{{ $doctor_rank->id }}" {{ old('doctor_rank_id') == $doctor_rank->id ? "selected" : "" }}>
                                            {{ $doctor_rank->name }}
                                        </option>
                                    @elseif (request('type') != "visitor")
                                        <option value="{{ $doctor_rank->id }}" {{ old('doctor_rank_id') == $doctor_rank->id ? "selected" : "" }}>
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
                                    <option value="{{$specialty->id}}" {{old('specialty_1_id') == $specialty->id ? "selected" : ""}}>
                                        {{$specialty->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>تخصص دقيق (إن وجد)</label>
                            <input type="text" name="specialty_2" value="{{ old('specialty_2') }}" class="form-control" autocomplete="off" placeholder="أدخل التخصص الدقيق">
                        </div>
                    </div>

                    @if (request('type') == "libyan")
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h5 class="text-secondary">بيانات العمل السابق</h5>
                            <label>جهات العمل السابقة</label>
                            <select name="ex_medical_facilities[]" multiple class="select2 form-control">
                                <option value="-">---</option>
                                @foreach ($medicalFacilities as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">يمكنك اختيار أكثر من جهة</small>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>سنوات الخبرة</label>
                            <div class="input-group">
                                <input name="experience" type="number" class="form-control" value="{{old('experience')}}" min="0">
                                <span class="input-group-text">سنة</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Additional Information Section -->
                <div class="form-section">
                    <h4 class="text-primary mb-3">
                        <i class="fas fa-info-circle me-2"></i>معلومات إضافية
                    </h4>
                    
                    <div class="alert alert-info" role="alert" dir="rtl">
                        <h6 class="alert-heading">
                            <i class="fas fa-lightbulb me-2"></i>تذكير مهم
                        </h6>
                        <p class="mb-0">
                            بعد انتهاء فترة زيارة الطبيب، يجب رفع "صورة من التقرير بعد زيارة الطبيب الزائر" من خلال ملف الطبيب في النظام.
                            هذا التقرير مطلوب لاستكمال الإجراءات وتسجيل أطباء زوار جدد.
                        </p>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-user-plus me-2"></i>تسجيل الطبيب الزائر
                    </button>
                </div>
            </form>
            @endif

        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- select2 cdn --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

{{-- Font Awesome --}}
<script>
$(document).ready(function() {
    // Initialize select2
    $('.select2').select2({
        create: false,
        sortField: 'text',
        placeholder: 'اختر من القائمة...'
    });
    

    // Validate visit dates
    $('input[name="visit_from"], input[name="visit_to"]').on('change', function() {
        const visitFrom = $('input[name="visit_from"]').val();
        const visitTo = $('input[name="visit_to"]').val();
        
        if (visitFrom && visitTo) {
            const fromDate = new Date(visitFrom);
            const toDate = new Date(visitTo);
            const today = new Date();
            
  
       
        }
    });
    
    // Validate passport expiration

    
    // Form submission validation
    $('form').on('submit', function(e) {
        const visitFrom = $('input[name="visit_from"]').val();
        const visitTo = $('input[name="visit_to"]').val();
        
 
        
        // Show loading state
        $(this).find('button[type="submit"]').prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin me-2"></i>جاري التسجيل...');
    });
    
    // Auto-format phone number
    $('input[name="phone_2"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 0 && !value.startsWith('9')) {
            value = '9' + value.slice(0, 8);
        }
        if (value.length > 9) {
            value = value.slice(0, 9);
        }
        $(this).val(value);
    });
});
</script>
@endsection