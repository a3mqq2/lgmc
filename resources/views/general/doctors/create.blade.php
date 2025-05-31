@extends('layouts.' . get_area_name())

@section('title', 'إضافة عضوية جديدة')

@section('styles')
@endsection

@section('content')
@php
    // نوع الطبيب: libyan | palestinian | visitor
    $type = request('type', 'libyan');
@endphp

<form action="{{ route(get_area_name().'.doctors.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

{{-- قسم رقم العضوية وتاريخ التجديد - يظهر فقط لفرع معين --}}
@if(auth('doctor')->user()->branch_id == 1)
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <label for="">رقم العضوية</label>
                <input type="number" name="index" value="{{auth('doctor')->user()->max('index') + 1}}" id="" class="form-control" required>
            </div>
            
            {{-- خيار الاشتراك الساري --}}
            <div class="col-md-12 mt-2">
                <label for="">هل له اشتراك ساري؟</label>
                <select name="has_active_subscription" id="has_active_subscription" class="form-control" required>
                    <option value="">اختر</option>
                    <option value="yes" {{ old('has_active_subscription') == 'yes' ? 'selected' : '' }}>نعم</option>
                    <option value="no" {{ old('has_active_subscription') == 'no' ? 'selected' : '' }}>لا</option>
                </select>
            </div>

            {{-- حقول تظهر عند اختيار "نعم" للاشتراك الساري --}}
            <div id="subscription_fields" style="display: none;">
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <label for="">تاريخ آخر تجديد</label>
                        <input type="date" name="last_issued_date" value="{{ old('last_issued_date') }}" class="form-control">
                    </div>
                    
                    <div class="col-md-6 mt-2">
                        <label for="">رقم الإذن</label>
                        <input type="text" name="license_number" value="{{ old('license_number') }}" class="form-control" placeholder="أدخل رقم الإذن">
                    </div>
                </div>
            </div>

            {{-- رسالة توضيحية عند اختيار "لا" --}}
            <div id="no_subscription_message" class="col-md-12 mt-2" style="display: none;">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    لا يوجد اشتراك ساري للطبيب حالياً سيتم وضح حالة الاشتراك قيد الموافقة
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript للتحكم في إظهار/إخفاء الحقول --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subscriptionSelect = document.getElementById('has_active_subscription');
    const subscriptionFields = document.getElementById('subscription_fields');
    const noSubscriptionMessage = document.getElementById('no_subscription_message');
    const lastIssuedDateInput = document.querySelector('input[name="last_issued_date"]');
    const licenseNumberInput = document.querySelector('input[name="license_number"]');

    function toggleFields() {
        const value = subscriptionSelect.value;
        
        if (value === 'yes') {
            subscriptionFields.style.display = 'block';
            noSubscriptionMessage.style.display = 'none';
            // جعل الحقول مطلوبة
            lastIssuedDateInput.required = true;
            licenseNumberInput.required = true;
        } else if (value === 'no') {
            subscriptionFields.style.display = 'none';
            noSubscriptionMessage.style.display = 'block';
            // إلغاء كون الحقول مطلوبة
            lastIssuedDateInput.required = false;
            licenseNumberInput.required = false;
            // مسح القيم
            lastIssuedDateInput.value = '';
            licenseNumberInput.value = '';
        } else {
            subscriptionFields.style.display = 'none';
            noSubscriptionMessage.style.display = 'none';
            lastIssuedDateInput.required = false;
            licenseNumberInput.required = false;
        }
    }

    // تشغيل الدالة عند تغيير الاختيار
    subscriptionSelect.addEventListener('change', toggleFields);
    
    // تشغيل الدالة عند تحميل الصفحة للحفاظ على الحالة في حالة old input
    toggleFields();
});
</script>
@endif
    <div class="card">
        <div class="card-header bg-primary text-light">
            <h3 class="card-title text-light mb-0">إضافة عضوية جديدة</h3>
        </div>

        <div class="card-body">

            {{-- المعلومات الشخصية --}}
            <h5 class="text-primary mb-3">المعلومات الشخصية</h5>
            <div class="row">
                <div class="col-md-6">
                    <label>الاسم بالكامل</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-control">
                </div>

                @if ($type == 'libyan')
                    <div class="col-md-6">
                        <label>الاسم بالكامل باللغه الانجليزيه</label>
                        <input type="text" name="name_en" value="{{ old('name_en') }}" required class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>الرقم الوطني</label>
                        <input type="number" name="national_number" value="{{ old('national_number') }}" required class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>اسم الام</label>
                        <input type="text" name="mother_name" value="{{ old('mother_name') }}" required class="form-control">
                    </div>
                @endif

                {{-- الجنسية --}}
                <div class="col-md-6 mt-2">
                    <label>الجنسية</label>
                    <select name="country_id" id="country_id" class="form-control"
                        @if(in_array($type, ['libyan','palestinian'])) disabled @endif required>
                        <option value="">حدد دوله من القائمة</option>
                        @foreach ($countries as $country)
                            @if ($type == 'visitor' && in_array($country->id,[1,2])) @continue @endif
                            <option value="{{ $country->id }}"
                                {{ old('country_id') == $country->id ? 'selected' : '' }}
                                @if($type=='libyan' && $country->id==1) selected @endif
                                @if($type=='palestinian' && $country->id==2) selected @endif>
                                {{ $country->nationality_name_ar }}
                            </option>
                        @endforeach
                    </select>
                    {{-- hidden override for libyan | palestinian --}}
                    @if($type=='palestinian')
                        <input type="hidden" name="country_id" value="2">
                    @elseif($type=='libyan')
                        <input type="hidden" name="country_id" value="1">
                    @endif
                </div>

                @if ($type == 'libyan')
                    <div class="col-md-6 mt-2">
                        <label>تاريخ الميلاد</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>الحالة الاجتماعية</label>
                        <select name="marital_status" class="form-control" required>
                            <option value="single"  {{ old('marital_status')=='single'  ? 'selected':'' }}>أعزب</option>
                            <option value="married" {{ old('marital_status')=='married' ? 'selected':'' }}>متزوج</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>النوع</label>
                        <select name="gender" id="gender" class="form-control" required>
                            <option value="male"   {{ old('gender')=='male'   ? 'selected':'' }}>ذكر</option>
                            <option value="female" {{ old('gender')=='female' ? 'selected':'' }}>أنثى</option>
                        </select>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>رقم جواز السفر</label>
                        <input type="text" name="passport_number" pattern="[A-Z0-9]+" value="{{ old('passport_number') }}" required class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>تاريخ انتهاء صلاحية الجواز</label>
                        <input type="date" name="passport_expiration" value="{{ old('passport_expiration') }}" required class="form-control">
                    </div>
                @endif

                @if ($type == 'visitor')
                    <div class="col-md-6 mt-2">
                        <label>الشركة المستضيفة (المتعاقدة)</label>
                        <select name="medical_facility_id" class="form-control select2" required>
                            <option value="">-</option>
                            @foreach ($medicalFacilities as $mf)
                                <option value="{{ $mf->id }}" {{ old('medical_facility_id')==$mf->id?'selected':'' }}>{{ $mf->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>تاريخ الزيارة من</label>
                        <input type="date" name="visit_from" value="{{ old('visit_from') }}" required class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>تاريخ الزيارة إلى</label>
                        <input type="date" name="visit_to" value="{{ old('visit_to') }}" required class="form-control">
                    </div>
                @endif
            </div>

            <hr>

            {{-- بيانات الاتصال --}}
            <h5 class="text-primary mb-3">بيانات الاتصال والدخول</h5>
            <div class="row">
                <div class="col-md-6">
                    <label>رقم الهاتف</label>
                    <input type="tel" name="phone" value="{{ old('phone') }}" maxlength="10" required class="form-control">
                </div>

                <div class="col-md-6">
                    <label>رقم الواتساب</label>
                    <input type="tel" name="phone_2" value="{{ old('phone_2') }}" maxlength="10" class="form-control">
                </div>

                @if ($type == 'libyan')
                    <div class="col-md-6 mt-2">
                        <label>العنوان</label>
                        <input type="text" name="address" value="{{ old('address') }}" required class="form-control">
                    </div>
                @endif

                <div class="col-md-6 mt-2">
                    <label>البريد الإلكتروني الشخصي</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                </div>
            </div>

            <hr>

            {{-- بكالوريوس --}}
            <h5 class="text-primary mb-3">بكالوريوس</h5>
            <div class="row">
                @if ($type == 'visitor')
                    <div class="col-md-4">
                        <label>دولة التخرج</label>
                        <select name="country_graduation_id" class="form-control select2" required>
                            <option value="">حدد الدولة</option>
                            @foreach ($countries as $c)
                                <option value="{{ $c->id }}" {{ old('country_graduation_id')==$c->id?'selected':'' }}>{{ $c->nationality_name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-6">
                    <label>جهة التخرج</label>
                    <select name="hand_graduation_id" class="form-control select2" required>
                        <option value="">حدد الجهة</option>
                        @foreach ($universities as $u)
                            <option value="{{ $u->id }}" {{ old('hand_graduation_id')==$u->id?'selected':'' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                @if ($type == 'libyan')
                    <div class="col-md-6">
                        <label>سنة التخرج</label>
                        <select name="graduation_date" class="form-control select2">
                            <option value="">حدد السنة</option>
                            @for ($year = date('Y'); $year >= 1950; $year--)
                                <option value="{{ $year }}" {{ old('graduation_date')==$year?'selected':'' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                @else
                    <div class="col-md-6">
                        <label>تاريخ الحصول عليها</label>
                        <input type="date" name="graduation_date" value="{{ old('graduation_date') }}" class="form-control">
                    </div>
                @endif
            </div>

            {{-- الامتياز --}}
            @if ($type !== 'visitor')
                <hr>
                <h5 class="text-primary mb-3">الامتياز</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label>جهة الحصول على الامتياز</label>
                        <select name="qualification_university_id" class="form-control select2">
                            <option value="">حدد الجهة</option>
                            @foreach ($universities as $u)
                                <option value="{{ $u->id }}" {{ old('qualification_university_id')==$u->id?'selected':'' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($type == 'libyan')
                        <div class="col-md-6">
                            <label>سنة الحصول عليها</label>
                            <select name="internership_complete" class="form-control select2">
                                <option value="">حدد السنة</option>
                                @for ($year = date('Y'); $year >= 1950; $year--)
                                    <option value="{{ $year }}" {{ old('internership_complete')==$year?'selected':'' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    @else
                        <div class="col-md-6">
                            <label>تاريخ الحصول عليها</label>
                            <input type="date" name="internership_complete" value="{{ old('internership_complete') }}" class="form-control">
                        </div>
                    @endif
                </div>
            @endif

            {{-- الدرجة العلمية --}}
            @if ($type == 'libyan')
                <hr>
                <h5 class="text-primary mb-3">الدرجة العلمية</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label>الدرجة العلمية</label>
                        <select name="academic_degree_id" class="form-control select2">
                            <option value="">حدد الدرجة</option>
                            @foreach ($academicDegrees as $d)
                                <option value="{{ $d->id }}" {{ old('academic_degree_id')==$d->id?'selected':'' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label>سنة الحصول عليها</label>
                        <select name="certificate_of_excellence_date" class="form-control select2">
                            <option value="">حدد السنة</option>
                            @for ($year = date('Y'); $year >= 1950; $year--)
                                <option value="{{ $year }}" {{ old('certificate_of_excellence_date')==$year?'selected':'' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-12 mt-2">
                        <label>الجهة</label>
                        <select name="academic_degree_univeristy_id" class="form-control select2">
                            <option value="">حدد الجهة</option>
                            @foreach ($universities as $u)
                                <option value="{{ $u->id }}" {{ old('academic_degree_univeristy_id')==$u->id?'selected':'' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            {{-- بيانات العمل الحالي --}}
            <hr>
            <h5 class="text-primary mb-3">بيانات العمل الحالي</h5>
            <div class="row">
                @if ($type !== 'libyan')
                    <div class="col-md-6">
                        <label>تاريخ الانتساب للنقابة</label>
                        <input type="date" name="registered_at" value="{{ old('registered_at') }}" class="form-control">
                    </div>
                @endif

                <div class="col-md-6">
                    <label>المستشفى</label>
                    <input type="text" name="institution" value="{{ old('institution') }}" class="form-control">
                </div>

                <div class="col-md-6">
                    <label>الصفة</label>
                    <select name="doctor_rank_id" class="form-control select2" required>
                        <option value="">حدد الصفة</option>
                        @foreach (App\Models\DoctorRank::where('doctor_type', $type)->get() as $rank)
                            <option value="{{ $rank->id }}" {{ old('doctor_rank_id')==$rank->id?'selected':'' }}>{{ $rank->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-12">
                    <label>تخصص الطبيب</label>
                    <select name="specialty_1_id" class="form-control select2">
                        <option value="">حدد التخصص</option>
                        @foreach ($specialties as $s)
                            <option value="{{ $s->id }}" {{ old('specialty_1_id')==$s->id?'selected':'' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- بيانات أخرى --}}
            <hr>
            <h5 class="text-primary mb-3">بيانات أخرى</h5>
            <div class="row">
                <div class="col-md-12">
                    <label>بيانات إضافية</label>
                    <textarea name="notes" rows="4" class="form-control">{{ old('notes') }}</textarea>
                </div>
            </div>
            <input type="hidden" name="type" value="{{request("type")}}">

        </div>

        <div class="card-footer text-end">
            <button class="btn btn-primary">حفظ</button>
        </div>
    </div>
</form>
@endsection
