@extends('layouts.' . get_area_name())
@section('title', ' اضافه طبيب جديد ')

@section('content')
@if (!request('type'))
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="main-content-label">   حدد نوع الطبيب المراد اضافته   </h4>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <a href="{{route(get_area_name().'.doctors.create', ['type' => 'libyan' ])}}">
                                <div class="card {{App\Enums\DoctorType::Libyan->badgeClass()}} text-light text-center p-3 d-flex justify-content-center align-items-center" style="height: 100px;">
                                    <h5 class="text-center text-light">طبيب ليبي</h5>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{route(get_area_name().'.doctors.create', ['type' => 'palestinian' ])}}">
                                <div class="card {{App\Enums\DoctorType::Palestinian->badgeClass()}} text-light text-center p-3 d-flex justify-content-center align-items-center" style="height: 100px;">
                                    <h5 class="text-center text-light">طبيب فلسطيني</h5>
                                </div>
                            </a>
                        </div> 
                        <div class="col-md-3">
                            <a href="{{route(get_area_name().'.doctors.create', ['type' => 'foreign' ])}}">
                                <div class="card {{App\Enums\DoctorType::Foreign->badgeClass()}} text-dark text-center p-3 d-flex justify-content-center align-items-center" style="height: 100px;">
                                    <h5 class="text-center text-dark">طبيب اجنبي مقيم</h5>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{route(get_area_name().'.doctors.create', ['type' => 'visitor' ])}}">
                                <div class="card  {{App\Enums\DoctorType::Visitor->badgeClass()}} text-light text-center p-3 d-flex justify-content-center align-items-center" style="height: 100px;">
                                    <h5 class="text-center text-light">طبيب زائر</h5>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    @else  
    <form action="{{route(get_area_name().'.doctors.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-light">
                                <h4 class="card-title"> المعلومات الشخصية </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">الاسم بالكامل</label>
                                        <input type="text" required name="name" value="{{old('name')}}"  id="" class="form-control">
                                        <input type="hidden" name="type" value="{{request('type')}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">الاسم بالكامل باللغه الانجليزيه</label>
                                        <input type="text" required name="name_en" value="{{old('name_en')}}"  id="" class="form-control">
                                    </div>
                                    @if (request('type') == "libyan")
                                    <div class="col-md-6 mt-2">
                                        <label for="">الرقم الوطني</label>
                                        <input type="number" required name="national_number" value="{{old('national_number')}}" id="" class="form-control">
                                    </div>
                                    @endif
                                    <div class="col-md-6 mt-2">
                                        <label for=""> اسم الام </label>
                                        <input type="text" required name="mother_name" value="{{old('mother_name')}}" id="" class="form-control">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="">  الجنسية  </label>
                                        <select name="country_id" required id="country_id" class="form-control" 
                                        @if(request('type') == "libyan" || request('type') == "palestinian") disabled @endif>
                                        <option value="">حدد دوله من القائمة</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"
                                                {{ old('country_id') == $country->id ? 'selected' : '' }}
                                                @if(request('type') == "libyan" && $country->id == 1) selected @endif
                                                @if(request('type') == "palestinian" && $country->id == 2) selected @endif>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach

                                        @if (request('type') == "palestinian")
                                            <input type="hidden" name="country_id" value="2" class="form-control">
                                        @endif

                                        @if (request('type') == "libyan")
                                            <input type="hidden" name="country_id" value="1" class="form-control">
                                        @endif

                                    </select>
                                    
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for=""> تاريخ الميلاد  </label>
                                        <input type="date" required name="date_of_birth" value="{{old('date_of_birth', date('Y-m-d'))}}" id="" class="form-control">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="">  الحالة الاجتماعية  </label>
                                        <select name="marital_status"  required id="" class="form-control">
                                            <option value="single" {{old('marital_status') == "single" ? "selected" : ""}}>اعزب</option>
                                            <option value="married" {{old('marital_status') == "married" ? "selected" : ""}}>متزوج</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="">  النوع   </label>
                                        <select name="gender" required id="" class="form-control">
                                            <option value="male" {{old('gender') == "male" ? "selected" : ""}}>ذكر</option>
                                            <option value="female" {{old('gender') == "female" ? "selected" : ""}}>انثى</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for=""> رقم جواز السفر   </label>
                                        <input type="text" name="passport_number" required value="{{old('passport_number')}}" id="" class="form-control">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="">  تاريخ انتهاء صلاحية الجواز     </label>
                                        <input type="date" required name="passport_expiration" value="{{old('passport_expiration', date('Y-m-d'))}}" id="" class="form-control">
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-light">
                                <h4 class="card-title"> بيانات الاتصال والدخول </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">رقم الهاتف</label>
                                        <input type="phone" required name="phone" maxlength="10" value="{{old('phone')}}" id="" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">رقم الهاتف 2</label>
                                        <input type="phone" name="phone_2" value="{{old('phone_2')}}" id="" maxlength="10" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">العنوان</label>
                                        <input type="text" name="address" value="{{old('address')}}" id="" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for=""> كلمة المرور </label>
                                        <input type="password" name="password" value="{{old('password')}}" id="" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for=""> تأكيد كلمة المرور  </label>
                                        <input type="password" name="password_confirmation" value="{{old('password_confirmation')}}" id="" class="form-control">
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                        <div class="card">
                            <div class="card-header bg-primary text-light">
                                <h4 class="card-title"> بكالوريس    </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if (request('type') == "visitor")
                                    <div class="col-md-4">
                                        <label for=""> دولة التخرج </label>
                                        <select name="country_graduation_id" id="" class="form-control select2">
                                            <option value="">حدد دولة التخرج </option>
                                            @foreach ($countries as $country)
                                                <option value="{{$country->id}}" {{old('country_graduation_id') == $country->id ? "selected" : ""}}>{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    <div class="col-md-4">
                                        <label for=""> جهة التخرج </label>
                                        <select name="hand_graduation_id" id="" class="form-control">
                                            <option value="">حدد جهة التخرج </option>
                                            @foreach ($universities as $university)
                                                <option value="{{$university->id}}" {{old('hand_graduation_id') == $university->id ? "selected" : ""}}>{{$university->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for=""> تاريخ انتهاء الامتياز   </label>
                                        <input type="date" name="internership_complete" value="{{old('internership_complete')}}" id="" class="form-control">
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                    </div>
                </div>
               
           
            
    
                <div class="card">
                    <div class="card-header bg-primary text-light">
                        <h4 class="card-title"> الدرجة العلمية   </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">الدرجة العلمية</label>
                                <select name="academic_degree_id" id="" class="form-control select2">
                                    <option value="">حدد درجة علمية</option>
                                    @foreach ($academicDegrees as $academicDegree)
                                        <option value="{{$academicDegree->id}}" {{old('academic_degree_id') == $academicDegree->id ? "selected" : ""}}>{{$academicDegree->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for=""> تاريخ الحصول عليها </label>
                                <input type="date" name="certificate_of_excellence_date" value="{{old('certificate_of_excellence_date', date('Y-m-d'))}}" id="" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label for=""> الجهة  </label>
                                <select name="qualification_university_id" id="" class="form-control select2">
                                    <option value="">حدد جهة  </option>
                                    @foreach ($universities as $university)
                                        <option value="{{$university->id}}" {{old('qualification_university_id') == $university->id ? "selected" : ""}}>{{$university->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                        

                        </div>
                    </div>
            
                </div>
            </div>
    
            <div class="col-md-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">📑 المستندات المطلوبة</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($file_types as $file_type)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="document-card shadow-sm border rounded text-center p-3 position-relative">
                                        <div class="document-icon mb-3">
                                            <i class="fas fa-file-upload fa-3x text-primary"></i>
                                        </div>
                                        <h6 class="document-title mb-2">
                                            {{ $file_type->name }}
                                            @if ($file_type->is_required)
                                                <span class="text-danger">*</span>
                                            @endif
                                        </h6>
                                        <div class="custom-file">
                                            <input type="file" name="documents[{{ $file_type->id }}]" 
                                                   class="custom-file-input"
                                                   id="file_{{ $file_type->id }}"
                                                   @if($file_type->is_required) required @endif>
                                            <label class="custom-file-label" for="file_{{ $file_type->id }}">
                                                اختر ملف
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-2">
                                            الملف يجب أن يكون بصيغة <b>PDF</b> أو صورة
                                        </small>
                                        <div id="status_{{ $file_type->id }}" class="mt-2 text-muted">
                                            🔄 لم يتم الرفع بعد
                                        </div>
                                        @if ($file_type->is_required)
                                            <div class="alert alert-warning mt-3 p-2 text-center rounded-lg shadow-sm"
                                                 style="background: linear-gradient(135deg, #fff8e1, #ffe0b2); 
                                                        border-left: 5px solid #ff9800;
                                                        color: #5d4037;">
                                                <i class="fas fa-exclamation-circle"></i> 
                                                <strong>ملف إلزامي:</strong> يُرجى التأكد من رفع هذا الملف.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            
    
    
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-light">بيانات العمل الحالي</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">الرقم النقابي الأول</label>
                                <input type="text" name="doctor_number" value="{{old('doctor_number')}}"  id="" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label for="">الصفة</label>
                                <select name="doctor_rank_id" id="" class="form-control select2">
                                    <option value="">حدد الصفة</option>
                                    @foreach ($doctor_ranks as $doctor_rank)
                                        @if (request('type') == "visitor" && ($doctor_rank->id != 1 && $doctor_rank->id != 2))
                                            <option value="{{$doctor_rank->id}}" {{old('doctor_rank_id') == $doctor_rank->id ? "selected" : ""}}>{{$doctor_rank->name}}</option>
                                            @else 
                                                @if (request('type') != "visitor")
                                                        <option value="{{$doctor_rank->id}}" {{old('doctor_rank_id') == $doctor_rank->id ? "selected" : ""}}>{{$doctor_rank->name}}</option>
                                                @endif
                                            @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="row">
                                    @if (get_area_name() == "admin")
                                    <div class="col-md-12 mt-1 mb-2">
                                        <label for="">حدد فرع</label>
                                        <select name="branch_id" id="" class="form-control select2">
                                            <option value="">حدد فرع</option>
                                            @foreach (App\Models\Branch::all() as $branch)
                                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    <div class="col-md-4">
                                        <label for=""> تخصص اول</label>
                                        <select name="specialty_1_id" id="" class="form-control">
                                            <option value="">حدد تخصص اول</option>
                                            @foreach ($specialties as $specialty)
                                                <option value="{{$specialty->id}}" {{old('specialty_1_id') == $specialty->id ? "selected" : ""}}>{{$specialty->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for=""> تخصص ثاني</label>
                                        <select name="specialty_2_id" data-old="{{old('specialty_2_id')}}" id="" class="form-control">
                                            <option value="">حدد تخصص ثاني</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for=""> تخصص ثالث</label>
                                        <select name="specialty_3_id" data-old="{{old('specialty_3_id')}}" id="" class="form-control">
                                            <option value="">حدد تخصص ثالث</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-primary text-light">بيانات العمل السابق</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">جهات العمل السابقة</label>
                                <textarea name="ex_medical_facilities" id="" cols="30" rows="4" class="form-control">{{old('ex_medical_facilities')}}</textarea>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label for=""> سنوات الخبره  </label>
                                <input name="experience" id="" type="number" class="form-control" value="{{old('experience')}}" />
                            </div>
                        </div>
                    </div>
                </div>
    
                <div class="card">
                    <div class="card-header bg-primary text-light">بيانات اخرى   </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for=""> بيانات اضافيه</label>
                                <textarea name="notes" id="" cols="30" rows="4" class="form-control">{{old('notes')}}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary text-light mb-3">حفظ</button>
            </div>
        </div>
    </form>
@endif
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Function to show/hide tbody based on selected country
            function toggleTbody() {
                const selectedCountryId = $('#country_id').val();
                const libyanDoctorsTbody = $('#libyan_doctors');
                const foreignDoctorsTbody = $('#foreign_doctors');
                
                // Show Libyan doctors if selected country is Libya, otherwise show foreign doctors
                if (selectedCountryId === '1') {
                    libyanDoctorsTbody.show();
                    foreignDoctorsTbody.hide();
                } else {
                    libyanDoctorsTbody.hide();
                    foreignDoctorsTbody.show();
                }
            }
    
            // Call toggleTbody when the page loads
            toggleTbody();
    
            // Listen for changes in the selected country
            $('#country_id').change(function() {
                // Update the hidden input field with the selected country ID
                $('#selected_country_id').val($(this).val());
                // Call toggleTbody to show/hide tbody based on the selected country
                toggleTbody();
            });
        });
    </script>
<script>
    $(document).ready(function() {
        // Set data-old attribute for Specialty 2 and Specialty 3 selects
        $('select[name="specialty_2_id"]').attr('data-old', '{{ old("specialty_2_id") }}');
        $('select[name="specialty_3_id"]').attr('data-old', '{{ old("specialty_3_id") }}');

        // Initialize Specialty 1 selectize
        var selectizeSpecialty1 = $('select[name="specialty_1_id"]').selectize({
            onChange: function(value) {
                if (!value.length) return;
                // Clear existing options
                var selectizeSpecialty2 = selectizeSpecialty2Instance[0].selectize;
                selectizeSpecialty2.clearOptions();
                // Fetch data for specialty 2 based on selected value of specialty 1
                $.ajax({
                    url: '/api/get-sub-specialties/' + value,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $.each(response, function(index, specialty) {
                            selectizeSpecialty2.addOption({value: specialty.id, text: specialty.name});
                        });
                        // Restore old value for Specialty 2
                        selectizeSpecialty2.setValue($('select[name="specialty_2_id"]').data('old'));
                    }
                });
            }
        });

        // Trigger change event for Specialty 1 select to populate Specialty 2
        $('select[name="specialty_1_id"]').trigger('change');

        // Initialize Specialty 2 selectize
        var selectizeSpecialty2Instance = $('select[name="specialty_2_id"]').selectize({
            onChange: function(value) {
                if (!value.length) return;
                // Clear existing options
                var selectizeSpecialty3 = selectizeSpecialty3Instance[0].selectize;
                selectizeSpecialty3.clearOptions();
                // Fetch data for specialty 3 based on selected value of specialty 2
                $.ajax({
                    url: '/api/get-sub-specialties/' + value,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        $.each(response, function(index, specialty) {
                            selectizeSpecialty3.addOption({value: specialty.id, text: specialty.name});
                        });
                        // Restore old value for Specialty 3
                        selectizeSpecialty3.setValue($('select[name="specialty_3_id"]').data('old'));
                    }
                });
            }
        });

        // Initialize Specialty 3 selectize
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

        // تأكد من عدم عرض النص المكرر
        if (statusElement.hasClass('text-success')) {
            $(this).siblings('.file-name-display').remove();
        }
    });
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // التحقق من الاسم
        document.querySelector('input[name="name"]').addEventListener('input', function() {
            if (this.value.trim() === '') {
                showError(this, 'حقل الاسم مطلوب.');
            } else if (this.value.length > 255) {
                showError(this, 'حقل الاسم لا يجب أن يتجاوز 255 حرفاً.');
            } else {
                removeError(this);
            }
        });

        // التحقق من الاسم بالإنجليزية
        document.querySelector('input[name="name_en"]').addEventListener('input', function() {
            if (this.value.trim() === '') {
                showError(this, 'حقل الاسم باللغة الإنجليزية مطلوب.');
            } else if (this.value.length > 255) {
                showError(this, 'حقل الاسم باللغة الإنجليزية لا يجب أن يتجاوز 255 حرفاً.');
            } else {
                removeError(this);
            }
        });

        // التحقق من الرقم الوطني في حال كان الطبيب ليبي
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

        // التحقق من جميع الحقول التي تحتوي على تواريخ
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
                        showError(this, 'التاريخ يجب أن يكون بالصيغة YYYY-MM-DD.');
                    } else {
                        removeError(this);
                    }
                });
            }
        });

        // التحقق من كلمة المرور
        document.querySelector('input[name="password"]').addEventListener('input', function() {
            if (this.value.length < 6) {
                showError(this, 'يجب أن تكون كلمة المرور 6 أحرف على الأقل.');
            } else {
                removeError(this);
            }
        });

        // التحقق من تأكيد كلمة المرور
        document.querySelector('input[name="password_confirmation"]').addEventListener('input', function() {
            const password = document.querySelector('input[name="password"]').value;
            if (this.value !== password) {
                showError(this, 'كلمة المرور غير متطابقة.');
            } else {
                removeError(this);
            }
        });

        // دالة لإظهار الخطأ
        function showError(element, message) {
            removeError(element);
            const errorDiv = document.createElement('div');
            errorDiv.classList.add('text-danger', 'mt-1');
            errorDiv.innerText = message;
            element.classList.add('is-invalid');
            element.parentNode.appendChild(errorDiv);
        }

        // دالة لإزالة الخطأ
        function removeError(element) {
            element.classList.remove('is-invalid');
            const errorDiv = element.parentNode.querySelector('.text-danger');
            if (errorDiv) {
                errorDiv.remove();
            }
        }
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