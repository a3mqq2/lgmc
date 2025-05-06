@extends('layouts.' . get_area_name())
@section('title', 'تعديل بيانات طبيب')

@section('content')
<form action="{{route('user.doctors.update', $doctor)}}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-light">
                            <h4 class="card-title"> المعلومات الشخصية </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">الاسم بالكامل</label>
                                    <input type="text" required name="name" value="{{old('name', $doctor->name)}}"  id="" class="form-control">
                                </div>
                                
                                @if ($doctor->type->value != "visitor")
                                <div class="col-md-6">
                                    <label for="">الاسم بالكامل باللغه الانجليزيه</label>
                                    <input type="text" required name="name_en" value="{{old('name_en', $doctor->name_en)}}"  id="" class="form-control">
                                </div>
                                @endif
                                
                                @if ($doctor->type->value == "libyan")
                                <div class="col-md-6 mt-2">
                                    <label for="">الرقم الوطني</label>
                                    <input type="number" required name="national_number" value="{{old('national_number', $doctor->national_number)}}" id="national_number" class="form-control">
                                </div>
                                @endif
                             
                                @if ($doctor->type->value != "visitor")
                                <div class="col-md-6 mt-2">
                                    <label for=""> اسم الام </label>
                                    <input type="text" required name="mother_name" value="{{old('mother_name', $doctor->mother_name)}}" id="" class="form-control">
                                </div>
                                @endif

                                <div class="col-md-6 mt-2">
                                    <label for="">  الجنسية  </label>
                                    <select name="country_id" required id="country_id" class="form-control" 
                                    @if($doctor->type->value == "libyan" || $doctor->type->value == "palestinian") disabled @endif>
                                    <option value="">حدد دوله من القائمة</option>
                                    @foreach ($countries as $country)
                                        @if ($doctor->type->value == "visitor" && ($country->id == 1 || $country->id == 2))
                                            @continue  
                                            @else 
                                            <option value="{{ $country->id }}"
                                                {{ old('country_id', $doctor->country_id) == $country->id ? 'selected' : '' }}
                                                @if($doctor->type->value == "libyan" && $country->id == 1) selected @endif
                                                @if($doctor->type->value == "palestinian" && $country->id == 2) selected @endif>
                                                {{ $country->name }}
                                            </option>
                                        @endif
                                    @endforeach

                                    @if ($doctor->type->value == "palestinian")
                                        <input type="hidden" name="country_id" value="2" class="form-control">
                                    @endif

                                    @if ($doctor->type->value == "libyan")
                                        <input type="hidden" name="country_id" value="1" class="form-control">
                                    @endif

                                </select>
                                
                                </div>
                                @if ($doctor->type->value == "libyan")
                                <div class="col-md-2 mt-2">
                                    <label for="birth_year">سنة الميلاد</label>
                                    <input type="text"  required name="birth_year" value="{{ old('birth_year', date('Y', strtotime($doctor->date_of_birth)) ) }}" id="birth_year" class="form-control" readonly>
                                </div>
                            
                                <!-- Month & Day -->
                                <div class="col-md-2 mt-2">
                                    <label for="date_of_birth">الشهر </label>
                                    <select name="month" required id="" class="form-control">
                                        <option value=""> حدد </option>
                                        @foreach (range(1, 12) as $month)
                                            <option value="{{ $month }}" {{ old('month', date('m', strtotime($doctor->date_of_birth)) ) == $month ? 'selected' : '' }}>
                                                {{ $month }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mt-2">
                                    <label for="day"> اليوم </label>
                                    <select name="day" required id="" class="form-control">
                                        <option value=""> حدد </option>
                                        @foreach (range(1, 31) as $day)
                                            <option value="{{ $day }}" {{ old('day', date('d', strtotime($doctor->date_of_birth)) ) == $day ? 'selected' : '' }}>
                                                {{ $day }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @else 
                                @if ($doctor->type->value != "visitor")
                                <div class="col-md-6 mt-2">
                                    <label for=""> تاريخ الميلاد </label>
                                    <input type="date" required name="date_of_birth" value="{{old('date_of_birth', $doctor->date_of_birth )}}" id="" class="form-control">
                                </div>
                                @endif
                                @endif
                                @if ($doctor->type->value != "visitor")
                                <div class="col-md-6 mt-2">
                                    <label for="">  الحالة الاجتماعية  </label>
                                    <select name="marital_status"  required id="" class="form-control">
                                        <option value="single" {{old('marital_status', $doctor->marital_status) == "single" ? "selected" : ""}}>اعزب</option>
                                        <option value="married" {{old('marital_status', $doctor->marital_status) == "married" ? "selected" : ""}}>متزوج</option>
                                    </select>
                                </div>
                                @endif
                                <div class="col-md-6 mt-2">
                                    <label for="">  النوع   </label>
                                    <select name="gender" required id="gender" required  class="form-control"  >
                                        <option value="male" {{old('gender', $doctor->gender) == "male" ? "selected" : ""}}>ذكر</option>
                                        <option value="female" {{old('gender', $doctor->gender) == "female" ? "selected" : ""}}>انثى</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for=""> رقم جواز السفر   </label>
                                    <input type="text"  name="passport_number" pattern="[A-Z0-9]+"  required value="{{old('passport_number', $doctor->passport_number)}}" id="" class="form-control">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="">  تاريخ انتهاء صلاحية الجواز     </label>
                                    <input type="date" required name="passport_expiration" value="{{ $doctor->passport_expiration ? date('Y-m-d', strtotime($doctor->passport_expiration)) : "" }}" id="" class="form-control">
                                </div>

                             
                                @if ($doctor->type->value == "visitor")
                                <div class="col-md-6 mt-2">
                                    <label for="">  الشركه المستضيفه (المتعاقده)   </label>
                                    <select name="medical_facility_id" id="" class="form-control select2" required>
                                            <option value="">-</option>
                                            @foreach ($medicalFacilities as $medical_facility)
                                                <option value="{{$medical_facility->id}}" {{$doctor->medical_facility_id == $medical_facility->id ? "selected" : "" }} >{{$medical_facility->name}}</option>
                                            @endforeach
                                    </select>
                                </div>
                                @endif

                                @if ($doctor->type->value == "visitor")
                                <div class="col-md-6 mt-2">
                                    <label for=""> تاريخ الزيارة من  </label>
                                    <input type="date" required name="visit_from" value="{{ $doctor->visit_from ? old('visit_from', $doctor->visit_from) : ""}}" id="" class="form-control">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for=""> تاريخ الزيارة الى  </label>
                                    <input type="date" required name="visit_to" value="{{ $doctor->visit_to ? old('visit_to', $doctor->visit_to) : ""}}"  id="" class="form-control">
                                </div>
                                @endif


                            </div>
                        </div>
                
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-primary text-light">
                            <h4 class="card-title"> بيانات الاتصال والدخول </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">رقم الهاتف</label>
                                    <input type="phone" required name="phone" maxlength="10" value="{{old('phone', $doctor->phone)}}"  class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for=""> رقم الواتساب </label>
                                    <input type="phone" name="phone_2" value="{{old('phone_2', $doctor->phone_2)}}"  maxlength="10" class="form-control">
                                </div>
                                
                                @if ($doctor->type->value != "visitor")
                                <div class="col-md-6">
                                    <label for="">الاقامة</label>
                                    <input type="text" required name="address" value="{{old('address', $doctor->address)}}"  class="form-control">
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <label for=""> كلمة المرور (لا تغييرها الا اذا اردت ذلك) </label>
                                    <input type="password"   name="password" value="{{old('password')}}"  class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for=""> تأكيد كلمة المرور  </label>
                                    <input type="password"  name="password_confirmation" value="{{old('password_confirmation')}}"  class="form-control">
                                </div>
                                {{-- email input --}}
                                <div class="col-md-6">
                                    <label for="">البريد الالكتروني الشخصي</label>
                                    <input type="email"  name="email" value="{{old('email', $doctor->email)}}" id="email" class="form-control" >
                                </div>
                            </div>
                        </div>
                
                    </div>
                    

                    @if ($doctor->type->value != "visitor")
                    <div class="card">
                        <div class="card-header bg-primary text-light">
                            <h4 class="card-title"> بكالوريس    </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if ($doctor->type->value == "visitor")
                                <div class="col-md-4">
                                    <label for=""> دولة التخرج </label>
                                    <select name="country_graduation_id" required   class="form-control select2">
                                        <option value="">حدد دولة التخرج </option>
                                        @foreach ($countries as $country)
                                            <option value="{{$country->id}}" {{old('country_graduation_id', $doctor->country_graduation_id) == $country->id ? "selected" : ""}}>{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="col-md-4">
                                    <label for=""> جهة التخرج </label>
                                    <select name="hand_graduation_id"  required  class="form-control select2">
                                        <option value="">حدد جهة التخرج </option>
                                        @foreach ($universities as $university)
                                            <option value="{{$university->id}}" {{old('hand_graduation_id', $doctor->hand_graduation_id) == $university->id ? "selected" : ""}}>{{$university->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for=""> تاريخ انتهاء الامتياز   </label>
                                    <input type="date" name="internership_complete" required value="{{old('internership_complete', $doctor->internership_complete ? date('Y-m-d', strtotime($doctor->internership_complete)) : "")}}"  class="form-control">
                                </div>
                            </div>
                        </div>
                
                    </div>
                    @endif

                </div>
            </div>
           
       
            @if ($doctor->type->value != "visitor")


            <div class="card">
                <div class="card-header bg-primary text-light">
                    <h4 class="card-title"> الدرجة العلمية   </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="">الدرجة العلمية</label>
                            <select name="academic_degree_id"  class="form-control select2">
                                <option value="">حدد درجة علمية</option>
                                @foreach ($academicDegrees as $academicDegree)
                                    <option value="{{$academicDegree->id}}" {{old('academic_degree_id', $doctor->academic_degree_id) == $academicDegree->id ? "selected" : ""}}>{{$academicDegree->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="certificate_of_excellence_date">  تاريخ الحصول عليها
                            </label>
                            <select name="certificate_of_excellence_date" id="certificate_of_excellence_date" class="form-control select2" required>
                                @php
                                    $currentYear = now()->year; // Get the current year
                                    $selectedYear = old('certificate_of_excellence_date', optional($doctor->certificate_of_excellence_date)->format('Y') ?? $currentYear);
                                @endphp
                                @for ($year = $currentYear; $year >= 1950; $year--)
                                    <option value="{{ $year }}" {{ (int)$year === (int)$selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        
                        <div class="col-md-12">
                            <label for=""> الجهة  </label>
                            <select name="qualification_university_id"  class="form-control select2">
                                <option value="">حدد جهة  </option>
                                @foreach ($universities as $university)
                                    <option value="{{$university->id}}" {{old('qualification_university_id', $doctor->qualification_university_id) == $university->id ? "selected" : ""}}>{{$university->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
        
            </div>
            @endif
        


        </div>

        <div class="col-md-12">
            <div class="col-md-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center"><h4 class="mb-0">📑 المستندات المطلوبة</h4></div>
                    <div class="card-body"><div class="row" id="documents_container"></div></div>
                </div>
            </div>
        </div>
        
        


        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-light">بيانات العمل الحالي</div>
                <div class="card-body">
                    <div class="row">
                       

                        @if ($doctor->type->value != "visitor")
                        <div class="col-md-6">
                            <label for="">الرقم النقابي الأول</label>
                            <input type="text" name="doctor_number"   value="{{old('doctor_number', $doctor->doctor_number)}}"  id="" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label for=""> تاريخ الانتساب   </label>
                            <input type="date" name="registered_at" value="{{$doctor->registered_at ?? ''}}" id="" class="form-control">
                        </div>


                        <div class="col-md-6">
                            <label for="">جهة العمل</label>
                            <select name="institution_id" id="" class="form-control select2">
                                <option value="">حدد جهة العمل</option>
                                @foreach (\App\Models\Institution::where('branch_id', auth()->user()->branch_id)->get(); as $institution)
                                    <option value="{{$institution->id}}" {{old('institution_id') == $institution->id ? "selected" : ""}}>{{$institution->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif



                        <div class="col-md-4">
                            <label for="doctor_rank_id">الصفة</label>
                            <select name="doctor_rank_id" id="doctor_rank_id" required class="form-control select2">
                                <option value="">حدد الصفة</option>
                                @foreach ($doctor_ranks as $rank)
                                    @if($doctor->type->value === 'visitor')
                                        @if(! in_array($rank->id, [1, 2]))
                                            <option value="{{ $rank->id }}"
                                                {{ old('doctor_rank_id', $doctor->doctor_rank_id) == $rank->id ? 'selected' : '' }}>
                                                {{ ($rank->id === 6 && $doctor->type->value !== 'libyan') 
                                                    ? 'استشاري تخصص دقيق' 
                                                    : $rank->name }}
                                            </option>
                                        @endif
                                    @else
                                        <option value="{{ $rank->id }}"
                                            {{ old('doctor_rank_id', $doctor->doctor_rank_id) == $rank->id ? 'selected' : '' }}>
                                            {{ ($rank->id === 6 && $doctor->type->value !== 'libyan') 
                                                ? 'استشاري تخصص دقيق' 
                                                : $rank->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        

                        <div class="col-md-4">
                            <label for=""> تخصص اول</label>
                            <select name="specialty_1_id"  id="" class="form-control">
                                <option value="">حدد تخصص اول</option>
                                @foreach ($specialties as $specialty)
                                    <option value="{{$specialty->id}}" {{old('specialty_1_id',$doctor->specialty_1_id) == $specialty->id ? "selected" : ""}}>{{$specialty->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4" id="specialty_2_container" 
                        @if ($doctor->specialty_2)
                            style="display: block;"
                        @endif
                    >
                        <label for="specialty_2">تخصص دقيق</label>
                        <input type="text" name="specialty_2" id="specialty_2" value="{{ old('specialty_2', $doctor->specialty_2) }}" class="form-control" autocomplete="off">
                    </div>                                    
                  
                    @if ($doctor->type->value != "visitor")
                    <div class="col-md-4 mt-2">
                        <label for=""> سنوات الخبره  </label>
                        <input name="experience"  id="" type="number" class="form-control" value="{{old('experience')}}" />
                    </div>
                    @endif



                    </div>
                </div>
            </div>

            @if ($doctor->type->value != "visitor")
            <div class="card">
                <div class="card-header bg-primary text-light">بيانات العمل السابق</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="ex_medical_facilities">جهات العمل السابقة</label>
                            <select name="ex_medical_facilities[]" multiple id="ex_medical_facilities" class="select2 form-control">
                                <option value="-">---</option>
                                @foreach ($institutions as $item)
                                    <option 
                                        value="{{ $item->id }}" 
                                        {{ in_array($item->id, old('ex_medical_facilities', $doctor->institutions->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>                               
                        </div>
                  
                    </div>
                </div>
            </div>
            @endif


            <div class="card">
                <div class="card-header bg-primary text-light">بيانات اخرى   </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for=""> بيانات اضافيه</label>
                            <textarea name="notes"  cols="30" rows="4" class="form-control">{{old('notes', $doctor->notes)}}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <button class="btn btn-primary text-light mb-3">حفظ</button>
        </div>
    </div>
</form>
@endsection

@section('scripts')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>



    document.addEventListener('DOMContentLoaded', function () {
        const nationalNumberInput = document.getElementById('national_number');
        const birthYearInput = document.getElementById('birth_year');
        const dateOfBirthInput = document.getElementById('date_of_birth');
        const genderSelect = document.getElementById('gender');
        const genderInput = document.getElementById('gender_input');
        // Initialize Flatpickr for the date input
        flatpickr(dateOfBirthInput, {
            dateFormat: "Y-m", // Year and month only
            altInput: true,
            altFormat: "F Y", // Pretty format
            locale: "ar"
        });

        // Handle National Number Input
        nationalNumberInput.addEventListener('input', function () {
            const nationalNumber = this.value;
            console.log(nationalNumber);

            // Ensure the national number has 12 digits
            if (nationalNumber.length === 12) {
                // Extract Gender
                const genderDigit = parseInt(nationalNumber.substring(0, 1)); // The first digit determines gender
                const gender = (genderDigit === 1) ? 'male' : 'female';

                // Extract Year of Birth (next 4 digits)
                const year = nationalNumber.substring(1, 5);

                // Update Inputs
                birthYearInput.value = year;
                dateOfBirthInput.value = `${year}`; 
                genderSelect.value = gender;
                genderInput.value = gender;
            } else {
                birthYearInput.value = '';
                dateOfBirthInput.value = '';
                genderSelect.value = '';
                genderInput.value = '';
            }
        });

    });
</script>
    <script>
        $(document).ready(function() {
            function toggleTbody() {
                const selectedCountryId = $('#country_id').val();
                const libyanDoctorsTbody = $('#libyan_doctors');
                const foreignDoctorsTbody = $('#foreign_doctors');
                
                if (selectedCountryId === '1') {
                    libyanDoctorsTbody.show();
                    foreignDoctorsTbody.hide();
                } else {
                    libyanDoctorsTbody.hide();
                    foreignDoctorsTbody.show();
                }
            }
    
            toggleTbody();
    
            $('#country_id').change(function() {
                $('#selected_country_id').val($(this).val());
                toggleTbody();
            });
        });
    </script>
<script>
    $(document).ready(function() {

        $(".selectize").selectize();
        
        $('select[name="specialty_2_id"]').attr('data-old', '{{ old("specialty_2_id") }}');
        $('select[name="specialty_3_id"]').attr('data-old', '{{ old("specialty_3_id") }}');

        var selectizeSpecialty1 = $('select[name="specialty_1_id"]').selectize({
            onChange: function(value) {
                if (!value.length) return;
                var selectizeSpecialty2 = selectizeSpecialty2Instance[0].selectize;
                selectizeSpecialty2.clearOptions();
                $.ajax({
                    url: '/api/get-sub-specialties/' + value,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $.each(response, function(index, specialty) {
                            selectizeSpecialty2.addOption({value: specialty.id, text: specialty.name});
                        });
                        selectizeSpecialty2.setValue($('select[name="specialty_2_id"]').data('old'));
                    }
                });
            }
        });

        $('select[name="specialty_1_id"]').trigger('change');

        var selectizeSpecialty2Instance = $('select[name="specialty_2_id"]').selectize({
            onChange: function(value) {
                if (!value.length) return;
                var selectizeSpecialty3 = selectizeSpecialty3Instance[0].selectize;
                selectizeSpecialty3.clearOptions();
                $.ajax({
                    url: '/api/get-sub-specialties/' + value,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $.each(response, function(index, specialty) {
                            selectizeSpecialty3.addOption({value: specialty.id, text: specialty.name});
                        });
                        selectizeSpecialty3.setValue($('select[name="specialty_3_id"]').data('old'));
                    }
                });
            }
        });

        var selectizeSpecialty3Instance = $('select[name="specialty_3_id"]').selectize();
    });
</script>

<script>
$(document).ready(function () {
    $('.custom-file-input').on('change', function () {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);

        let fileId = $(this).attr('id').split('_')[1];
        let statusElement = $('#status_' + fileId);

        statusElement.html('✅ تم الرفع: ' + fileName)
                     .removeClass('text-muted')
                     .addClass('text-success');

        if (statusElement.hasClass('text-success')) {
            $(this).siblings('.file-name-display').remove();
        }
    });
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('input[name="name"]').addEventListener('input', function() {
            if (this.value.trim() === '') {
                showError(this, 'حقل الاسم مطلوب.');
            } else if (this.value.length > 255) {
                showError(this, 'حقل الاسم لا يجب أن يتجاوز 255 حرفاً.');
            } else {
                removeError(this);
            }
        });

        document.querySelector('input[name="name_en"]').addEventListener('input', function() {
            if (this.value.trim() === '') {
                showError(this, 'حقل الاسم باللغة الإنجليزية مطلوب.');
            } else if (this.value.length > 255) {
                showError(this, 'حقل الاسم باللغة الإنجليزية لا يجب أن يتجاوز 255 حرفاً.');
            } else {
                removeError(this);
            }
        });

        const nationalNumberInput = document.querySelector('input[name="national_number"]');
        if (nationalNumberInput) {
            nationalNumberInput.addEventListener('input', function() {
                const gender = document.querySelector('select[name="gender"]').value;
                if (this.value.length !== 12) {
                    showError(this, 'الرقم الوطني يجب أن يتكون من 12 رقمًا.');
                } else if (gender === 'male' && this.value[0] !== '1') {
                    showError(this, 'الرقم الوطني للذكور يجب أن يبدأ بالرقم 1.');
                } else if (gender === 'female' && this.value[0] !== '2') {
                    showError(this, 'الرقم الوطني للإناث يجب أن يبدأ بالرقم 2.');
                } else {
                    removeError(this);
                }
            });
        }

        const dateInputs = [
            'date_of_birth',
            'passport_expiration',
            'internership_complete',
            'certificate_of_excellence_date',
            'start_work_date'
        ];

        dateInputs.forEach(function(inputName) {
            const inputElement = document.querySelector(`input[name="${inputName}"]`);
            if (inputElement) {
                inputElement.addEventListener('input', function() {
                    const datePattern = /^\d{4}-\d{2}-\d{2}$/;
                    if (!datePattern.test(this.value)) {
                    } else {
                        removeError(this);
                    }
                });
            }
        });

        document.querySelector('input[name="password"]').addEventListener('input', function() {
            if (this.value.length < 6) {
                showError(this, 'يجب أن تكون كلمة المرور 6 أحرف على الأقل.');
            } else {
                removeError(this);
            }
        });

        document.querySelector('input[name="password_confirmation"]').addEventListener('input', function() {
            const password = document.querySelector('input[name="password"]').value;
            if (this.value !== password) {
                showError(this, 'كلمة المرور غير متطابقة.');
            } else {
                removeError(this);
            }
        });

        function showError(element, message) {
            removeError(element);
            const errorDiv = document.createElement('div');
            errorDiv.classList.add('text-danger', 'mt-1');
            errorDiv.innerText = message;
            element.classList.add('is-invalid');
            element.parentNode.appendChild(errorDiv);
        }

        function removeError(element) {
            element.classList.remove('is-invalid');
            const errorDiv = element.parentNode.querySelector('.text-danger');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
    });
</script>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const nationalNumberInput = document.getElementById('national_number');
    const birthYearInput = document.getElementById('birth_year');
    const genderSelect = document.getElementById('gender');
    const genderInput = document.getElementById('gender_input');
    const nameEnInput = document.querySelector('input[name="name_en"]');
    const emailInput = document.querySelector('input[name="email"]');

    nationalNumberInput.addEventListener('input', function () {
        const nationalNumber = this.value;
        if (nationalNumber.length === 12) {
            const genderDigit = parseInt(nationalNumber.charAt(0)); 
            const gender = genderDigit === 1 ? 'male' : 'female';
            genderSelect.value = gender;
            genderInput.value = gender;

            const year = nationalNumber.substring(1, 5); 
            const month = parseInt(nationalNumber.substring(5, 7)); 
            const day = parseInt(nationalNumber.substring(7, 9)); 

            birthYearInput.value = year;

        } else {
            birthYearInput.value = '';
            genderSelect.value = '';
            genderInput.value = '';
        }
    });
});

    </script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Input elements
    const nationalNumberInput = document.getElementById('national_number');
    const birthYearInput = document.getElementById('birth_year');
    const genderSelect = document.getElementById('gender');
    const nameEnInput = document.querySelector('input[name="name_en"]');
    const emailInput = document.querySelector('input[name="email"]');

    // Event listener for national number input
    nationalNumberInput.addEventListener('input', function () {
        const nationalNumber = this.value;

        // Validate the length of the national number
        if (nationalNumber.length === 12) {
            // Extract Gender
            const genderDigit = parseInt(nationalNumber.charAt(0)); // First digit determines gender
            const gender = genderDigit === 1 ? 'male' : 'female';
            genderSelect.value = gender;

            // Extract Birth Year, Month, and Day
            const year = nationalNumber.substring(1, 5); // 2nd to 5th digits are the year
            const month = parseInt(nationalNumber.substring(5, 7)); // 6th and 7th digits are the month
            const day = parseInt(nationalNumber.substring(7, 9)); // 8th and 9th digits are the day

            // Update inputs
            birthYearInput.value = year;

        } else {
            // Clear inputs if the national number is invalid
            birthYearInput.value = '';
            genderSelect.value = '';
        }
    });
});

    </script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nationalNumberInput = document.getElementById('national_number');
        const birthYearInput = document.getElementById('birth_year');
        const dateOfBirthInput = document.querySelector('input[name="date_of_birth"]'); 
        const nameEnInput = document.querySelector('input[name="name_en"]');
        const emailInput = document.querySelector('input[name="email"]');

        const isLibyan = "{{ $doctor->type->value }}" === "libyan";

        if (isLibyan && nationalNumberInput) {
            nationalNumberInput.addEventListener('input', function () {
                const nationalNumber = this.value;

                if (nationalNumber.length === 12) {
                    const genderDigit = parseInt(nationalNumber.charAt(0));
                    const gender = genderDigit === 1 ? 'male' : 'female';

                    const year = nationalNumber.substring(1, 5);
                    const month = parseInt(nationalNumber.substring(5, 7));
                    const day = parseInt(nationalNumber.substring(7, 9));

                    if (birthYearInput) birthYearInput.value = year;

                } else {
                    if (birthYearInput) birthYearInput.value = '';
                    emailInput.value = '';
                }
            });
        }

        if (!isLibyan && dateOfBirthInput) {
            dateOfBirthInput.addEventListener('input', function () {
                const dob = this.value; 
                if (dob) {
                    const [year, month, day] = dob.split('-');
                } else {
                    emailInput.value = '';
                }
            });
        }

        nameEnInput?.addEventListener('input', function () {
            if (isLibyan) {
                const year = birthYearInput?.value || '';
            } else {
                const dob = dateOfBirthInput?.value || '';
                if (dob) {
                    const [year, month, day] = dob.split('-');
                } else {
                    emailInput.value = '';
                }
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');

        requiredFields.forEach(function (field) {
            const label = field.closest('.form-group, .col-md-6, .col-md-4, .col-md-2, .col-md-12')?.querySelector('label');

            if (label && !label.querySelector('.required-asterisk')) {
                const asterisk = document.createElement('span');
                asterisk.classList.add('required-asterisk');
                asterisk.innerHTML = ' *';
                asterisk.style.color = 'red';
                label.appendChild(asterisk);
            }
        });
    });
</script>
<script>
    $(document).ready(function(){
        $("#doctor_rank_id").change(function(){
            var selectedRank = $(this).val();
            if ([5,6,"5","6"].includes(selectedRank)) {
                $("select[name='specialty_1_id']").parent().show();
                $("#specialty_2_container").show();
            } else {

                if(selectedRank == 1 || selectedRank == '') {
                    $("select[name='specialty_1_id']").parent().hide();
                    $("#specialty_2_container").hide();
                } else {
                    $("select[name='specialty_1_id']").parent().show();
                    $("#specialty_2_container").hide();
                }

               
            }
        });
        $("#doctor_rank_id").trigger("change");
    });
    </script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
var availableSpecialties = @json($specialties2); 
$("#specialty_2").autocomplete({
    source: availableSpecialties
});
</script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
$(function(){
    let doctorType='{{ $doctor->type->value }}';
    function loadFiles(rank=''){ $.get('/api/file-types',{doctor_type:doctorType,rank_id:rank},function(res){
            let html='';
            res.forEach(f=>{
                html+=`<div class="col-md-6 col-lg-4 mb-4">
                        <div class="document-card shadow-sm border rounded text-center p-3">
                            <i class="fas fa-file-upload fa-3x text-primary mb-2"></i>
                            <h6>${f.name}${f.is_required?'<span class="text-danger">*</span>':''}</h6>
                            <div class="custom-file">
                                <input type="file" name="documents[${f.id}]" id="file_${f.id}" class="custom-file-input">
                                <label class="custom-file-label" for="file_${f.id}">اختر ملف</label>
                            </div>
                            <small class="text-muted d-block mt-2">PDF أو صورة</small>
                        </div>
                    </div>`;
            });
            $('#documents_container').html(html);
        }); }
    loadFiles($('#doctor_rank_id').val());
    $('#doctor_rank_id').on('change',function(){ loadFiles($(this).val()); });
    $(document).on('change','.custom-file-input',function(){ $(this).next().html($(this).val().split('\\').pop()); });
});
</script>

@endsection
@section('styles')
    <style> 
        .document-card {
    background: #ffffff;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    text-align: center;
    padding: 15px;
    transition: box-shadow 0.3s ease;
}

.document-card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.document-icon i {
    color: #007bff;
}

    </style>
@endsection


@section('styles')
<style>
.document-card{background:#fff;border:1px solid #e3e6f0;border-radius:8px;text-align:center;padding:15px;transition:box-shadow .3s}
.document-card:hover{box-shadow:0 4px 8px rgba(0,0,0,.1)}
</style>
@endsection