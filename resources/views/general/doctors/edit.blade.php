@extends('layouts.' . get_area_name())

@section('title', 'تعديل بيانات طبيب')

@section('styles')
@endsection

@section('content')
<form action="{{ route(get_area_name().'.doctors.update', $doctor) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-header bg-primary text-light">
            <h3 class="card-title text-light mb-0">تعديل بيانات طبيب</h3>
        </div>

        <div class="card-body">

            {{-- المعلومات الشخصية --}}
            <h5 class="text-primary mb-3">المعلومات الشخصية</h5>
            <div class="row">
                <div class="col-md-6">
                    <label>الاسم بالكامل</label>
                    <input type="text" name="name" required value="{{ old('name', $doctor->name) }}" class="form-control">
                </div>

                @if ($doctor->type->value == 'libyan')
                    <div class="col-md-6">
                        <label>الاسم بالكامل باللغه الانجليزيه</label>
                        <input type="text" name="name_en" required value="{{ old('name_en', $doctor->name_en) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>الرقم الوطني</label>
                        <input type="number" name="national_number" required value="{{ old('national_number', $doctor->national_number) }}" class="form-control">
                    </div>

                    <div class="col-md-6 mt-2">
                        <label>اسم الام</label>
                        <input type="text" name="mother_name" required value="{{ old('mother_name', $doctor->mother_name) }}" class="form-control">
                    </div>
                @endif

                <div class="col-md-6 mt-2">
                    <label>الجنسية</label>
                    <select name="country_id" id="country_id" class="form-control"
                        @if(in_array($doctor->type->value, ['libyan','palestinian'])) disabled @endif required>
                        <option value="">حدد دوله من القائمة</option>
                        @foreach ($countries as $country)
                            @if ($doctor->type->value == 'visitor' && in_array($country->id,[1,2])) @continue @endif
                            <option value="{{ $country->id }}"
                                {{ old('country_id', $doctor->country_id) == $country->id ? 'selected' : '' }}
                                @if($doctor->type->value == 'libyan' && $country->id == 1) selected @endif
                                @if($doctor->type->value == 'palestinian' && $country->id == 2) selected @endif>
                                {{ $country->nationality_name_ar }}
                            </option>
                        @endforeach
                    </select>
                    @if($doctor->type->value == 'palestinian')
                        <input type="hidden" name="country_id" value="2">
                    @elseif($doctor->type->value == 'libyan')
                        <input type="hidden" name="country_id" value="1">
                    @endif
                </div>



                @if ($doctor->type->value == 'libyan')

                <div class="col-md-6 mt-2">
                    <label>     تاريخ الميلاد    </label>
                    <input type="date" required name="date_of_birth" value="{{old('date_of_birth', $doctor->date_of_birth)}}" class="form-control">
              </div>

                    <div class="col-md-6 mt-2">
                        <label>الحالة الاجتماعية</label>
                        <select name="marital_status" required class="form-control">
                            <option value="single" {{ old('marital_status',$doctor->marital_status)=='single'?'selected':'' }}>اعزب</option>
                            <option value="married" {{ old('marital_status',$doctor->marital_status)=='married'?'selected':'' }}>متزوج</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label>النوع</label>
                        <select name="gender" id="gender" required class="form-control">
                            <option value="male" {{ old('gender',$doctor->gender)=='male'?'selected':'' }}>ذكر</option>
                            <option value="female" {{ old('gender',$doctor->gender)=='female'?'selected':'' }}>انثى</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label>رقم جواز السفر</label>
                        <input type="text" name="passport_number" pattern="[A-Z0-9]+" required value="{{ old('passport_number',$doctor->passport_number) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label>تاريخ انتهاء صلاحية الجواز</label>
                        <input type="date" name="passport_expiration" required value="{{ $doctor->passport_expiration ? date('Y-m-d', strtotime($doctor->passport_expiration)) : '' }}" class="form-control">
                    </div>
                @endif

                @if ($doctor->type->value == 'visitor')
                    <div class="col-md-6 mt-2">
                        <label>الشركة المستضيفه (المتعاقده)</label>
                        <select name="medical_facility_id" required class="form-control select2">
                            <option value="">-</option>
                            @foreach ($medicalFacilities as $mf)
                                <option value="{{ $mf->id }}" {{ $doctor->medical_facility_id==$mf->id?'selected':'' }}>{{ $mf->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label>تاريخ الزيارة من</label>
                        <input type="date" name="visit_from" required value="{{ old('visit_from',$doctor->visit_from) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label>تاريخ الزيارة الى</label>
                        <input type="date" name="visit_to" required value="{{ old('visit_to',$doctor->visit_to) }}" class="form-control">
                    </div>
                @endif
            </div>

            <hr>

            {{-- بيانات الاتصال والدخول --}}
            <h5 class="text-primary mb-3">بيانات الاتصال والدخول</h5>
            <div class="row">
                <div class="col-md-6">
                    <label>رقم الهاتف</label>
                    <input type="phone" name="phone" required maxlength="10" value="{{ old('phone',$doctor->phone) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>رقم الواتساب</label>
                    <input type="phone" name="phone_2" maxlength="10" value="{{ old('phone_2',$doctor->phone_2) }}" class="form-control">
                </div>
                @if ($doctor->type->value == 'libyan')
                    <div class="col-md-6 mt-2">
                        <label>العنوان</label>
                        <input type="text" name="address" required value="{{ old('address',$doctor->address) }}" class="form-control">
                    </div>
                @endif
                <div class="col-md-6 mt-2">
                    <label>البريد الالكتروني الشخصي</label>
                    <input type="email" name="email" value="{{ old('email',$doctor->email) }}" class="form-control">
                </div>
            </div>

            <hr>

            {{-- بكالوريس --}}
            <h5 class="text-primary mb-3">بكالوريس</h5>
            <div class="row">
                @if ($doctor->type->value == 'visitor')
                    <div class="col-md-4">
                        <label>دولة التخرج</label>
                        <select name="country_graduation_id" required class="form-control select2">
                            <option value="">حدد دولة التخرج</option>
                            @foreach ($countries as $c)
                                <option value="{{ $c->id }}" {{ old('country_graduation_id',$doctor->country_graduation_id)==$c->id?'selected':'' }}>{{ $c->nationality_name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-6">
                    <label>جهة التخرج</label>
                    <select name="hand_graduation_id" required class="form-control select2">
                        <option value="">حدد جهة التخرج</option>
                        @foreach ($universities as $u)
                            <option value="{{ $u->id }}" {{ old('hand_graduation_id',$doctor->hand_graduation_id)==$u->id?'selected':'' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
              

                @if ($doctor->type->value == 'libyan')
                    <div class="col-md-6">
                        <label>سنة التخرج</label>
                        <select name="graduation_date" class="form-control selectize select2">
                        @php
                            $currentYear = date('Y');
                            $years = range($currentYear, 1950);
                        @endphp
                        <option value="">حدد السنة</option>
                        @foreach ($years as $year)
                            <option value="{{$year}}" {{old('graduation_date', $doctor->graduation_date)==$year?"selected":""}}>{{$year}}</option>
                        @endforeach
                    </select>
                    </div>
                    @else 
                    <div class="col-md-6">
                        <label>تاريخ الحصول عليها</label>
                        <input type="date" name="graduation_date" value="{{ $doctor->graduation_date ? date('Y-m-d', strtotime($doctor->graduation_date)) : '' }}" class="form-control">
                    </div>
                @endif


            </div>

            @if ($doctor->type->value != 'visitor')
                <hr>
                {{-- الامتياز --}}
                <h5 class="text-primary mb-3">الامتياز</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label>جهة الحصول على الامتياز</label>
                        <select name="qualification_university_id" class="form-control select2">
                            <option value="">حدد جهة</option>
                            @foreach ($universities as $u)
                                <option value="{{ $u->id }}" {{ old('qualification_university_id',$doctor->qualification_university_id)==$u->id?'selected':'' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($doctor->type->value == 'libyan')
                    <div class="col-md-6">
                        <label> سنة الحصول عليها </label>
                        <select name="internership_complete" id="internership_complete" class="form-control selectize select2">
                            @php
                                $currentYear = date('Y');
                                $years = range($currentYear, 1950);
                            @endphp
                            <option value="">حدد السنة</option>
                            @foreach ($years as $year)
                                <option value="{{$year}}" {{old('internership_complete', $doctor->internership_complete)==$year?"selected":""}}>{{$year}}</option>
                            @endforeach
                        </select>
                    </div>
                    @else 
                    <div class="col-md-6">
                        <label>تاريخ الحصول عليها</label>
                        <input type="date" name="internership_complete" value="{{ $doctor->internership_complete ? date('Y-m-d', strtotime($doctor->internership_complete)) : '' }}" class="form-control">
                    </div>
                    @endif
                </div>
            @endif

            @if ($doctor->type->value == 'libyan')
                <hr>
                {{-- الدرجة العلمية --}}
                <h5 class="text-primary mb-3">الدرجة العلمية</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label>الدرجة العلمية</label>
                        <select name="academic_degree_id" class="form-control select2">
                            <option value="">حدد درجة علمية</option>
                            @foreach ($academicDegrees as $d)
                                <option value="{{ $d->id }}" {{ old('academic_degree_id',$doctor->academic_degree_id)==$d->id?'selected':'' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($doctor->type->value == "libyan")
                        <div class="col-md-6">
                            <label> سنة الحصول عليها </label>
                            <select name="certificate_of_excellence_date" id="certificate_of_excellence_date" class="form-control selectize select2">
                                @php
                                    $currentYear = date('Y');
                                    $years = range($currentYear, 1950);
                                @endphp
                                <option value="">حدد السنة</option>
                                @foreach ($years as $year)
                                    <option value="{{$year}}" {{old('certificate_of_excellence_date',$doctor->certificate_of_excellence_date)==$year?"selected":""}}>{{$year}}</option>
                                @endforeach
                            </select>
                        </div>
                        @else 
                        <div class="col-md-6">
                            <label>تاريخ الحصول عليها</label>
                            <select name="certificate_of_excellence_date" class="form-control form-control selectize select2 select2" required>
                                @php $currentYear = now()->year; @endphp
                                @for ($year=$currentYear; $year>=1950; $year--)
                                    <option value="{{ $year }}" {{ (int)$year==(int)old('certificate_of_excellence_date',optional($doctor->certificate_of_excellence_date)->format('Y')??$currentYear)?'selected':'' }}>{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    @endif

                    
                    <div class="col-md-12 mt-2">
                        <label>الجهة</label>
                        <select name="qualification_university_id" class="form-control select2">
                            <option value="">حدد جهة</option>
                            @foreach ($universities as $u)
                                <option value="{{ $u->id }}" {{ old('qualification_university_id',$doctor->qualification_university_id)==$u->id?'selected':'' }}>{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <hr>

            <h5 class="text-primary mb-3">بيانات العمل الحالي</h5>
            <div class="row">
                @if ($doctor->type->value != "libyan")
                <div class="col-md-6">
                    <label>تاريخ الانتساب للنقابة</label>
                    <input type="date" name="registered_at" value="{{ date('Y-m-d', strtotime($doctor->registered_at)) }}" class="form-control">
                </div>
                @endif
                <div class="col-md-6">
                    <label class="form-label">الجهة العامة/ المستشفى</label>
                    <select name="institution_id" id="" class="form-control select2 selectize">
                        <option value="">حدد مستشفى</option>
                        @foreach ($institutions as $institution)
                            <option value="{{$institution->id}}" {{$institution->id == $doctor->institution_id ? "selected" : ""}} >{{$institution->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>الصفة</label>
                    <select name="doctor_rank_id" required class="form-control select2">
                        <option value="">حدد الصفة</option>
                        @php $ranks = App\Models\DoctorRank::where('doctor_type',$doctor->type->value)->get(); @endphp
                        @foreach ($ranks as $rank)
                            <option value="{{ $rank->id }}" {{ old('doctor_rank_id',$doctor->doctor_rank_id)==$rank->id?'selected':'' }}>{{ $rank->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <label>تخصص الطبيب</label>
                    <select name="specialty_1_id" class="form-control select2">
                        <option value="">حدد تخصص</option>
                        @foreach ($specialties as $s)
                            <option value="{{ $s->id }}" {{ old('specialty_1_id',$doctor->specialty_1_id)==$s->id?'selected':'' }}>{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr>



            {{-- بيانات اخرى --}}
            <h5 class="text-primary mb-3">بيانات اخرى</h5>
            <div class="row">
                <div class="col-md-12">
                    <label>بيانات اضافيه</label>
                    <textarea name="notes" rows="4" class="form-control">{{ old('notes',$doctor->notes) }}</textarea>
                </div>
            </div>

        </div>

        <div class="card-footer text-end">
            <button class="btn btn-primary">حفظ</button>
        </div>
    </div>
</form>
@endsection
