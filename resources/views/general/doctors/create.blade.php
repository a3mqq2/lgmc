@extends('layouts.' . get_area_name())
@section('title', ' اضافه طبيب جديد ')

@section('content')
<form action="{{route('user.doctors.store')}}" method="POST" enctype="multipart/form-data">
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
                                </div>
                                <div class="col-md-6">
                                    <label for="">الاسم بالكامل باللغه الانجليزيه</label>
                                    <input type="text" required name="name_en" value="{{old('name_en')}}"  id="" class="form-control">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="">الرقم الوطني</label>
                                    <input type="number" required name="national_number" value="{{old('national_number')}}" id="" class="form-control">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for=""> اسم الام </label>
                                    <input type="text" required name="mother_name" value="{{old('mother_name')}}" id="" class="form-control">
                                </div>
                                <div class="col-md-6 mt-2">
                                    <label for="">  الجنسية  </label>
                                    <select name="country_id" required id="country_id" class="form-control">
                                        <option value="">حدد دوله من القائمة </option>
                                        @foreach ($countries as $country)
                                            <option value="{{$country->id}}" {{old('country_id') || 1 == $country->id ? "selected" : ""}}  >{{$country->name}}</option>
                                        @endforeach
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
                            <h4 class="card-title"> بيانات الاتصال  </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">رقم الهاتف</label>
                                    <input type="number" required name="phone" value="{{old('phone')}}" id="" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="">رقم الهاتف 2</label>
                                    <input type="number" name="phone_2" value="{{old('phone_2')}}" id="" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="">العنوان</label>
                                    <input type="text" name="address" value="{{old('address')}}" id="" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="">البريد الالكتروني</label>
                                    <input type="email" name="email" value="{{old('email')}}" id="" class="form-control">
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
                                <div class="col-md-6">
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
                                    <input type="date" name="internership_complete" value="{{old('internership_complete',date('Y-m-d'))}}" id="" class="form-control">
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
                            <select name="academic_degree_id" id="" class="form-control">
                                <option value="">حدد درجة علمية</option>
                                @foreach ($academicDegrees as $academicDegree)
                                    <option value="{{$academicDegree->id}}" {{old('academic_degree_id') == $academicDegree->id ? "selected" : ""}}>{{$academicDegree->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for=""> تاريخ الحصول عليها </label>
                            <input type="date" name="qualification_date" value="{{old('certificate_of_excellence_date', date('Y-m-d'))}}" id="" class="form-control">
                        </div>
                        <div class="col-md-12">
                            <label for=""> الجهة  </label>
                            <select name="qualification_university_id" id="" class="form-control">
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
            <div class="card">
                <div class="card-header bg-primary text-light">بيانات العمل الحالي</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">الصفة</label>
                            <select name="capacity_id" id="" class="form-control">
                                <option value="">حدد الصفة</option>
                                @foreach ($capacities as $capacity)
                                    <option value="{{$capacity->id}}" {{old('capacity_id') == $capacity->id ? "selected" : ""}}>{{$capacity->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for=""> المنشآت الطبية </label>
                            <select name="medical_facilities[]" multiple id="" class="form-control select2">
                                <option value="">حدد منشات طبية </option>
                                @foreach ($medicalFacilities as $medical_facility)
                                    <option value="{{$medical_facility->id}}">{{$medical_facility->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="row">
                                @if (get_area_name() == "admin")
                                <div class="col-md-12 mt-1 mb-2">
                                    <label for="">حدد فرع</label>
                                    <select name="branch_id" id="" class="form-control">
                                        <option value="">حدد فرع</option>
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
                            <textarea name="ex_medical_facilities" id="" cols="30" rows="4" class="form-control"></textarea>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label for=""> سنوات الخبره  </label>
                            <input name="experience" id="" type="number" class="form-control"></textarea>
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
                            <textarea name="notes" id="" cols="30" rows="4" class="form-control"></textarea>
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

@endsection