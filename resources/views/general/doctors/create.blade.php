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
                    <div class="col-md-12">
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
                                    
                                    @if (request('type') == "libyan")
                                    <div class="col-md-6">
                                        <label for="">الاسم بالكامل باللغه الانجليزيه</label>
                                        <input type="text" required name="name_en" value="{{old('name_en')}}"  id="" class="form-control">
                                    </div>
                                    @endif
                                    
                                    @if (request('type') == "libyan")
                                    <div class="col-md-6 mt-2">
                                        <label for="">الرقم الوطني</label>
                                        <input type="number" required name="national_number" value="{{old('national_number')}}" id="national_number" class="form-control">
                                    </div>
                                    @endif
                                 

                                    @if (request('type') == "libyan")
                                    <div class="col-md-6">
                                        <label for="">الرقم النقابي الأول</label>
                                        <input type="text" name="doctor_number"   value="{{old('doctor_number')}}"  id="" class="form-control">
                                    </div>
                                    @endif


                                    @if (request('type') == "libyan")
                                    <div class="col-md-6 mt-2">
                                        <label for=""> اسم الام </label>
                                        <input type="text" required name="mother_name" value="{{old('mother_name')}}" id="" class="form-control">
                                    </div>
                                    @endif

                                    <div class="col-md-6">
                                        <label for="">  الجنسية  </label>
                                        <select name="country_id" required id="country_id" class="form-control" 
                                        @if(request('type') == "libyan" || request('type') == "palestinian") disabled @endif>
                                        <option value="">حدد دوله من القائمة</option>
                                        @foreach ($countries as $country)
                                            @if (request('type') == "visitor" && ($country->id == 1 || $country->id == 2))
                                                @continue  
                                                @else 
                                                <option value="{{ $country->id }}"
                                                    {{ old('country_id') == $country->id ? 'selected' : '' }}
                                                    @if(request('type') == "libyan" && $country->id == 1) selected @endif
                                                    @if(request('type') == "palestinian" && $country->id == 2) selected @endif>
                                                    {{ $country->name }}
                                                </option>
                                            @endif
                                        @endforeach

                                        @if (request('type') == "palestinian")
                                            <input type="hidden" name="country_id" value="2" class="form-control">
                                        @endif

                                        @if (request('type') == "libyan")
                                            <input type="hidden" name="country_id" value="1" class="form-control">
                                        @endif

                                    </select>
                                    </div>
                                    @if (request('type') == "libyan")
                                    <div class="col-md-2 mt-2">
                                        <label for="birth_year">سنة الميلاد</label>
                                        <input type="text"  required name="birth_year" value="{{ old('birth_year') }}" id="birth_year" class="form-control" readonly>
                                    </div>
                                
                                    <!-- Month & Day -->
                                    <div class="col-md-2 mt-2">
                                        <label for="date_of_birth">الشهر </label>
                                        <select name="month" required id="" class="form-control">
                                            <option value=""> حدد </option>
                                            @foreach (range(1, 12) as $month)
                                                <option value="{{ $month }}" {{ old('month') == $month ? 'selected' : '' }}>
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
                                                <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>
                                                    {{ $day }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @else 
                                    @if (request('type') == "libyan")
                                    <div class="col-md-6 mt-2">
                                        <label for=""> تاريخ الميلاد </label>
                                        <input type="date" required name="date_of_birth" value="{{old('date_of_birth')}}" id="" class="form-control">
                                    </div>
                                    @endif
                                    @endif
                                    @if (request('type') == "libyan")
                                    <div class="col-md-6 mt-2">
                                        <label for="">  الحالة الاجتماعية  </label>
                                        <select name="marital_status"  required id="" class="form-control">
                                            <option value="single" {{old('marital_status') == "single" ? "selected" : ""}}>اعزب</option>
                                            <option value="married" {{old('marital_status') == "married" ? "selected" : ""}}>متزوج</option>
                                        </select>
                                    </div>
                                    @endif
                                    

                                    @if (request('type') == "libyan")
                                    <div class="col-md-6 mt-2">
                                        <label for="">  النوع   </label>
                                        <select name="gender" required id="gender" required  class="form-control"  >
                                            <option value="male" {{old('gender') == "male" ? "selected" : ""}}>ذكر</option>
                                            <option value="female" {{old('gender') == "female" ? "selected" : ""}}>انثى</option>
                                        </select>
                                    </div>
                                    @endif
                                   
                                    @if ( request('type') == "libyan")
                                    <div class="col-md-6 mt-2">
                                        <label for=""> رقم جواز السفر   </label>
                                        <input type="text"  name="passport_number" pattern="[A-Z0-9]+"  required value="{{old('passport_number')}}" id="" class="form-control">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for="">  تاريخ انتهاء صلاحية الجواز     </label>
                                        <input type="date" required name="passport_expiration" value="{{old('passport_expiration', date('Y-m-d'))}}" id="" class="form-control">
                                    </div>

                                    @endif

                                 
                                    @if (request('type') == "visitor")
                                    <div class="col-md-6 mt-2">
                                        <label for="">  الشركه المستضيفه (المتعاقده)   </label>
                                        <select name="medical_facility_id" id="" class="form-control select2" required>
                                                <option value="">-</option>
                                                @foreach ($medicalFacilities as $medical_facility)
                                                    <option value="{{$medical_facility->id}}">{{$medical_facility->name}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    @endif

                                    @if (request('type') == "visitor")
                                    <div class="col-md-6 mt-2">
                                        <label for=""> تاريخ الزيارة من  </label>
                                        <input type="date" required name="visit_from" value="{{old('visit_from', date('Y-m-d'))}}" id="" class="form-control">
                                    </div>
                                    <div class="col-md-6 mt-2">
                                        <label for=""> تاريخ الزيارة الى  </label>
                                        <input type="date" required name="visit_to" value="{{old('visit_to', date('Y-m-d'))}}" id="" class="form-control">
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
                                    <div class="col-md-12">
                                        <label for="">رقم الهاتف

                                            @if (request('type') == "visitor")
                                               - الشركه 
                                            @endif
                                        </label>
                                        <input type="phone" required name="phone" maxlength="10" value="{{old('phone')}}" id="" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for=""> رقم الواتساب </label>
                                        <input type="phone" name="phone_2" value="{{old('phone_2')}}" id="" maxlength="10" class="form-control">
                                    </div>
                                    @if (request('type')  ==  "libyan")
                                    <div class="col-md-6">
                                        <label for="">الاقامة</label>
                                        <input type="text" required name="address" value="{{old('address')}}" id="" class="form-control">
                                    </div>
                                    @endif
                                    <div class="col-md-6">
                                        <label for=""> كلمة المرور </label>
                                        <input type="password"   name="password" value="{{old('password')}}" id="" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for=""> تأكيد كلمة المرور  </label>
                                        <input type="password"  name="password_confirmation" value="{{old('password_confirmation')}}" id="" class="form-control">
                                    </div>
                                    {{-- email input --}}
                                    <div class="col-md-6">
                                        <label for="">البريد الالكتروني  </label>
                                        <input type="email"  name="email" value="{{old('email')}}" id="email" class="form-control">
                                    </div>
                                </div> 
                            </div>
                    
                        </div>
                        
                        @if (request('type') != "visitor")
                        <div class="card">
                            <div class="card-header bg-primary text-light">
                                <h4 class="card-title"> بكالوريس    </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if (request('type') == "visitor")
                                    <div class="col-md-4">
                                        <label for=""> دولة التخرج </label>
                                        <select name="country_graduation_id"   id="" class="form-control form-control select2">
                                            <option value="">حدد دولة التخرج </option>
                                            @foreach ($countries as $country)
                                                <option value="{{$country->id}}" {{old('country_graduation_id') == $country->id ? "selected" : ""}}>{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    <div class="col-md-6">
                                        <label for=""> جهة التخرج </label>
                                        <select name="hand_graduation_id"   id="" class="form-control form-control select2">
                                            <option value="">حدد جهة التخرج </option>
                                            @foreach ($universities as $university)
                                                <option value="{{$university->id}}" {{old('hand_graduation_id') == $university->id ? "selected" : ""}}>{{$university->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                              

                                    <div class="col-md-6">
                                        <label for="graduation_certificate">تاريخ الحصول عليها</label>
                                        <select name="graduation_certificate" id="graduation_certificate" class="form-control select2" >
                                            @php
                                                $currentYear = date('Y');
                                                $selectedYear = old('graduation_certificate', $currentYear);
                                            @endphp
                                            @for($year = $currentYear; $year >= 1950; $year--)
                                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>




                                </div>
                            </div>
                    
                        </div>

                        <div class="card">
                            <div class="card-header bg-primary text-light">
                                <h4 class="card-title"> الامتياز    </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for=""> جهة الحصول على الامتياز </label>
                                        <select name="qualification_university_id"   id="" class="form-control form-control select2">
                                            <option value="">حدد جهة  </option>
                                            @foreach ($universities as $university)
                                                <option value="{{$university->id}}" {{old('qualification_university_id') == $university->id ? "selected" : ""}}>{{$university->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="certificate_of_excellence_date">تاريخ الحصول عليها</label>
                                        <select name="certificate_of_excellence_date" id="certificate_of_excellence_date" class="form-control select2" >
                                            @php
                                                $currentYear = date('Y');
                                                $selectedYear = old('certificate_of_excellence_date', $currentYear);
                                            @endphp
                                            @for($year = $currentYear; $year >= 1950; $year--)
                                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                        @endif

                    </div>
                </div>
                <div class="row">
                    @if (request('type') == "libyan")
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-light">
                                <h4 class="card-title"> الدرجة العلمية   </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">الدرجة العلمية</label>
                                        <select name="academic_degree_id" id="" class="form-control select2" required>
                                            <option value="">حدد درجة علمية</option>
                                            @foreach ($academicDegrees as $academicDegree)
                                                <option value="{{$academicDegree->id}}" {{old('academic_degree_id') == $academicDegree->id ? "selected" : ""}}>{{$academicDegree->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="certificate_of_excellence_date">تاريخ الحصول عليها</label>
                                        <select name="certificate_of_excellence_date" id="certificate_of_excellence_date" class="form-control select2" required>
                                            @php
                                                $currentYear = date('Y');
                                                $selectedYear = old('certificate_of_excellence_date', $currentYear);
                                            @endphp
                                            @for($year = $currentYear; $year >= 1950; $year--)
                                                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <label for=""> الجهة  </label>
                                        <select name="qualification_university_id" required id="" class="form-control select2">
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
                    @endif
                </div>
            </div>
    


            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-primary text-light">بيانات العمل الحالي</div>
                    <div class="card-body">
                        <div class="row">
                            
                        


                                    <div class="col-md-12">
                                        <label for="">الصفة المهنية</label>
                                        <select name="doctor_rank_id" id="doctor_rank_id" required class="form-control select2">
                                            <option value="">حدد الصفة</option>

                                            @php
                                                $doctor_ranks = \App\Models\DoctorRank::where('doctor_type', request('type'))->get();
                                            @endphp

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
                                    
        
                                    <div class="col-md-6">
                                        <label for=""> تخصص اول (ان وجد) </label>
                                        <select name="specialty_1_id"  id="" class="form-control">
                                            <option value="">حدد تخصص اول</option>
                                            @foreach ($specialties as $specialty)
                                                <option value="{{$specialty->id}}" {{old('specialty_1_id') == $specialty->id ? "selected" : ""}}>{{$specialty->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6" id="specialty_2_container">
                                        <label for="specialty_2">تخصص دقيق (ان وجد) </label>
                                        <input type="text" name="specialty_2" id="specialty_2" value="{{ old('specialty_2') }}" class="form-control" autocomplete="off">
                                    </div>                                    
                                   


                                


                                    @if (request('type') != "visitor")
                                    <div class="col-md-12">
                                        <label for=""> تاريخ الانتساب للنقابة   </label>
                                        <input type="date" name="registered_at" value="{{date('Y-m-d')}}" id="" class="form-control">
                                    </div>
                                    @endif


                          @if (request('type') != "visitor")
                          <div class="col-md-12">
                            <label for="">جهة العمل</label>
                            <select name="institution_id" id="" class="form-control select2">
                                <option value="">حدد جهة العمل</option>
                                @foreach (\App\Models\Institution::where('branch_id', auth()->user()->branch_id)->get(); as $institution)
                                    <option value="{{$institution->id}}" {{old('institution_id') == $institution->id ? "selected" : ""}}>{{$institution->name}}</option>
                                @endforeach
                            </select>
                        </div>
                          @endif




                            <div class="col-md-12 mt-2">
                                <div class="row">
                                    @if (get_area_name() == "admin")
                                    <div class="col-md-12 mt-1 mb-2">
                                        <label for="">حدد فرع</label>
                                        <select name="branch_id" id="" required class="form-control select2">
                                            <option value="">حدد فرع</option>
                                            @foreach (App\Models\Branch::all() as $branch)
                                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               


                @if (request("type") == "libyan")
                <div class="card">
                    <div class="card-header bg-primary text-light">بيانات العمل السابق</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="">جهات العمل السابقة</label>
                                <select name="ex_medical_facilities[]" multiple id="" class="select2 form-control">
                                    <option value="-">---</option>
                                    @foreach ($institutions as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                           
                        </div>
                    </div>
                </div>
                @endif

          
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
        </div>
        <button class="btn btn-primary text-light mb-3">حفظ</button>
    </form>
@endif
@endsection
