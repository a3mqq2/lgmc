@extends('layouts.doctor')
@section('content')

@section('styles')
<style>
    .header-sc
    {
        font-size: 17px;
        padding: 10px;
        border-radius: 10px;
        background: #ffdde2;
        color: #7b2d35 !important;
        border: 2px dashed;
        font-weight: bold !important;
    }
    .custom-alert{
            background:linear-gradient(135deg,#f0f9ff 0%,#e0f4ff 100%);
            border:1px solid #b6e0fe!important;
            color:#05668d;
            border-radius:0.75rem;
            box-shadow:0 4px 12px rgba(0,0,0,.05);
            padding:1rem 1.25rem;
            font-weight:500;
        }
</style>
@endsection

@php
    $doctor = auth('doctor')->user();
    $is_libyan = $doctor->type->value == "libyan";
@endphp
@if ($doctor->membership_status == \App\Enums\MembershipStatus::Active)
    
<div class="row">
    <div class="col-lg-12">
      
            <div class="tab-content pt-4 text-muted">
                     <div class="tab-pane {{(!request('licences') && !request('tickets') && !request('requests') && !request('invoices') && !request('change-password') ) ? "active" : ""}} " id="overview" role="tabpanel">
                         <div class="row">
                             <div class="col-md-12">
                                 <div class="card">
                                     <div class="card-body">
                                        <h4 class="font-weight-bold text-primary" style="font-weight: bold!important;">
                                            <i class="fas fa-user text-primary"></i> البيانات الشخصية
                                        </h4>
                                        <div class="list-group">
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-id-badge text-info"></i> نوع الطبيب</span>
                                                <span class="badge bg-primary">
                                                    {{ $doctor->type->label() }}
                                                </span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-barcode text-info"></i> كود الطبيب</span>
                                                <span>{{ $doctor->code }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-user text-info"></i> الاسم</span>
                                                <span>{{ $doctor->name }}</span>
                                            </div>
                                           
                                            @if (isset($doctor->name_en))
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                               <span><i class="fas fa-user-circle text-info"></i> الاسم بالإنجليزية</span>
                                               <span>{{ $doctor->name_en }}</span>
                                           </div>
                                            @endif


                                          



                                            @if ($is_libyan)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-id-card text-info"></i> الرقم الوطني</span>
                                                <span>{{ $doctor->national_number }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-female text-info"></i> اسم الأم</span>
                                                <span>{{ $doctor->mother_name }}</span>
                                            </div>
                                            @endif

                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-passport text-info"></i> رقم الجواز</span>
                                                <span>{{ $doctor->passport_number }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-passport text-info"></i>  تاريخ انتهاء صلاحية الجواز  </span>
                                                <span>{{ $doctor->passport_expiration }}</span>
                                            </div>

                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-flag text-info"></i> الجنسية </span>
                                                <span>{{ $doctor->country->nationality_name_ar ?? '-' }}</span>
                                            </div>


                                            @if ($is_libyan)
                                               <div class="list-group-item d-flex justify-content-between align-items-center">
                                                   <span><i class="fas fa-birthday-cake text-info"></i> تاريخ الميلاد</span>
                                                   <span>{{ $doctor->date_of_birth ? $doctor->date_of_birth : '-' }}</span>
                                               </div>
                                               <div class="list-group-item d-flex justify-content-between align-items-center">
                                                   <span><i class="fas fa-heart text-info"></i> الحالة الاجتماعية</span>
                                                   @if ($doctor->marital_status)
                                                   <span class="form-value">{{ $doctor->marital_status->value == 'single' ? 'أعزب' : ($doctor->marital_status->value == 'married' ? 'متزوج' : '') }}</span>
                                                   @else 
                                                   <span class="form-value"></span>
                                               @endif
                                               </div>
                                               <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fa fa-venus-mars text-info"></i>  الجنس  </span>
                                                <span>{{ $doctor->gender == "male" ? "ذكر" : "انثى" }}</span>
                                            </div>
                                            @endif


                                     
                                         
                                        </div>
                                        <h4 class="font-weight-bold text-primary mt-4" style="font-weight: bold!important;">
                                            <i class="fas fa-phone text-primary"></i>  بيانات التواصل  
                                        </h4>
                                        <div class="list-group">
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fas fa-phone text-info"></i> رقم الهاتف </span>
                                                <span>{{ $doctor->phone }}</span>
                                            </div>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fab fa-whatsapp-square text-info"></i> رقم  الواتساب  </span>
                                                <span>{{ $doctor->phone_2 }}</span>
                                            </div>
                                            {{-- email --}}
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><i class="fa fa-envelope text-info"></i>  البريد الالكتروني     </span>
                                                <span>{{ $doctor->email }}</span>
                                            </div>
                                        </div>
                                         <h4 class="font-weight-bold text-primary mt-4 ">
                                             <i class="fas fa-info-circle text-primary"></i> بيانات إضافية
                                         </h4>
                                         <div class="list-group">
                                        

                             
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-check-circle text-info"></i> الاشتراك السنوي</span>
                                                 <span class="badge {{$doctor->membership_status->badgeClass()}}">
                                                     {{ $doctor->membership_status->label() }}
                                                 </span>
                                             </div>
                                             <div class="list-group-item d-flex justify-content-between align-items-center">
                                                 <span><i class="fas fa-calendar-check text-info"></i> تاريخ انتهاء الاشتراك</span>
                                                 <span>{{ $doctor->membership_expiration_date ? $doctor->membership_expiration_date : '-' }}</span>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>


 
         </div>
         </div>
        
    </div>
 </div>
 @elseif($doctor->membership_status == \App\Enums\MembershipStatus::UnderApprove)

 @if (!request('edit_mode'))
     <div class="card">
         <div class="card-body text-center">
             <div class="image mb-4">
                 <img src="{{ asset('/assets/images/pending.jpg') }}" width="400" alt="">
             </div>
             <h1 class="mb-3"><strong>لم يتم تفعيل حسابك بعد</strong></h1>
             <p class="h3 mb-2">
                 نحن الآن في صدد مراجعة معلوماتك، سنعلمك قريباً بالتفاصيل عبر بريدك الإلكتروني والنظام.
             </p>
             <p class="h4 text-primary">
                 إن أردت مراجعة بياناتك المرسلة أو تعديلها
                 <a href="?edit_mode=1" class="text-info">اضغط هنا</a>
             </p>
         </div>
     </div>
 @else
     <div class="card">
         <div class="card-body">
             <a href="/doctor/dashboard" class="btn btn-dark mb-3">عودة</a>

             
             <div class="col-md-12 mt-3">
                 <div class="custom-alert d-flex align-items-center mb-4" role="alert">
                     <i class="fa fa-info mr-3 fs-4 me-2 flex-shrink-0"></i>
                     <span class="flex-grow-1">نحن الان في صدد مراجعة بياناتك سيتم اعلامك بالموافقة قريبا</span>
                 </div>
             </div>

             <form action="{{route('doctor.profile.update')}}"  method="POST" enctype="multipart/form-data" dir="rtl">
                @csrf
                @method('PUT')
    
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="editTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab" aria-controls="data" aria-selected="true">
                            البيانات
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="files-tab" data-bs-toggle="tab" data-bs-target="#files" type="button" role="tab" aria-controls="files" aria-selected="false">
                            المستندات
                        </button>
                    </li>
                </ul>
    
                <!-- Tab panes -->
                <div class="tab-content mt-4" id="editTabsContent">
                    <!-- بيانات -->
                    <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="data-tab">
                        <div class="personal-information">
                            <h3 class="bg-primary text-light header-sc">بياناتي الشخصية</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">الاسم كاملاََ</label>
                                    <input type="text" name="name" class="form-control" value="{{ $doctor->name }}" >
                                </div>
                                <div class="col-md-6">
                                    <label for="">الاسم باللغة الانجليزية</label>
                                    <input type="text" name="name_en" class="form-control" value="{{ $doctor->name_en }}" >
                                </div>
                                @if ($is_libyan)
                                    <div class="col-md-6">
                                        <label>     تاريخ الميلاد    </label>
                                        <input type="date" required name="date_of_birth" value="{{date('Y-m-d', strtotime($doctor->date_of_birth))}}" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label> الرقم الوطني    </label>
                                        <input type="text" required name="national_number" value="{{old('national_number',$doctor->national_number)}}" class="form-control">
                                </div>
    
                                <div class="col-md-6">
                                        <label> اسم الام      </label>
                                        <input type="text" required name="mother_name" value="{{old('mother_name',$doctor->mother_name)}}" class="form-control">
                                </div>
                                <div class="col-md-3">
                                        <label>       الحالة الاجتماعية    </label>
                                        <select name="marital_status" id="marital_status" class="form-control">
                                        <option value="single" {{$doctor->marital_status == "signle" ? "selected" : ""}} >اعزب</option>
                                        <option value="married" {{$doctor->marital_status == "married" ? "selected" : ""}}>متزوج</option>
                                        </select>
                                </div>
                                <div class="col-md-3">
                                    <label>       الجنس      </label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="male" {{$doctor->gender == "male" ? "selected" : ""}}>ذكر</option>
                                        <option value="female" {{$doctor->gender == "female" ? "selected" : ""}}>انثى</option>
                                    </select>
                                </div>

                                
                                @endif

                                
                                @if ($doctor->type->value != "libyan" && $doctor->type->value != "palestinian")
                                <div class="col-md-6">
                                    <label>الجنسية</label>
                                    <select name="country_id"  id="country_id" class="form-control selectize" @if(request('type')=="libyan"||request('type')=="palestinian")  @endif>
                                        <option value="">حدد دولة</option>
                                        @foreach ($countries as $country)
                                            @if ($country->id!=1 && $country->id!=2)
                                                <option value="{{$country->id}}" {{old('country_id', $doctor->country_id )==$country->id?"selected":""}}>{{$country->nationality_name_ar}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="col-md-6">
                                    <label for="">رقم الجواز</label>
                                    <input type="text" name="passport_number" class="form-control" value="{{ $doctor->passport_number }}" >
                                </div>
                                @if ($is_libyan)
                                <div class="col-md-6">
                                    <label> تاريخ انتهاء صلاحية جواز السفر    </label>
                                    <input type="date" required name="passport_expiration" value="{{old('passport_expiration', date('Y-m-d', strtotime($doctor->passport_expiration)) )}}" class="form-control">
                              </div>
                                @endif
                            </div>
                        </div>
                        <div class="contact-information mt-3">
                            <h3 class="bg-primary text-light header-sc">بيانات التواصل</h3>
                            <div class="row">
                                
                                @if ($is_libyan)
                                <div class="col-md-12">
                                    <label for="">  العنوان  </label>
                                    <input type="text" name="address" class="form-control" value="{{ $doctor->address }}" >
                                </div>
    
                                @endif
                                <div class="col-md-6">
                                    <label for=""> رقم الهاتف  </label>
                                    <input type="text" name="phone" class="form-control" value="{{ $doctor->phone }}" >
                                </div>
                                <div class="col-md-6">
                                    <label for="">  رقم واتساب </label>
                                    <input type="text" name="phone_2" class="form-control" value="{{ $doctor->phone_2 }}" >
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for=""> بريدي الالكتروني </label>
                                    <input type="email" name="email"  class="form-control" value="{{ $doctor->email }}" >
                                </div>
                            </div>
                        </div>
                        <div class="graduation-information mt-4">
                            <h3 class="bg-primary text-light header-sc">بيانات البكالوريس</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">جامعة التخرج</label>
                                    <select name="hand_graduation_id" id="hand_graduation_id" class="form-control selectize" >
                                        <option value="">حدد جامعة</option>
                                        @foreach ($universities as $university)
                                            <option value="{{$university->id}}" {{old('hand_graduation_id', $doctor->hand_graduation_id )==$university->id?"selected":""}}>{{$university->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($is_libyan)
                                <div class="col-md-6">
                                    <label>سنة التخرج</label>
                                    <select name="graduation_date" class="form-control selectize select2">
                                     <option value="">حدد  تاريخ</option>
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
                                    <label for="">  تاريخ التخرج  </label>
                                    <input type="date" name="graduation_date" value="{{$doctor->graduation_date}}" class="form-control">
                                </div>
                                @endif
                            </div>
                        </div>
                
                        <div class="graduation-information mt-4">
                            <h3 class="bg-primary text-light header-sc">بيانات الامتياز</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="">جهة الحصول على الامتياز </label>
                                    <select name="qualification_university_id" id="qualification_university_id" class="form-control selectize" >
                                        <option value="">حدد جامعة</option>
                                        @foreach ($universities as $university)
                                            <option value="{{$university->id}}" {{old('qualification_university_id', $doctor->qualification_university_id )==$university->id?"selected":""}}>{{$university->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
    
                                @if ($is_libyan)
                                <div class="col-md-6">
                                    <label> سنة الحصول عليها </label>
                                    <select name="internership_complete" id="internership_complete" class="form-control selectize select2">
                                        @php
                                            $currentYear = date('Y');
                                            $years = range($currentYear, 1950);
                                        @endphp
                                        <option value="">حدد السنة</option>
                                        @foreach ($years as $year)
                                            <option value="{{$year}}" {{old('internership_complete',$doctor->internership_complete)==$year?"selected":""}}>{{$year}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @else 
                                <div class="col-md-6">
                                    <label for="">  تاريخ الحصول عليها  </label>
                                    <input type="date" name="internership_complete" value="{{date('Y-m-d', strtotime($doctor->internership_complete))}}" class="form-control">
                                </div>
                                @endif
    
                                
                            </div>
                        </div>
    
    
                        @if ($doctor->academic_degree_id)
                        <div class="graduation-information mt-4">
                            <h3 class="bg-primary text-light header-sc"> الدرجة العلمية الحالية </h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="">الدرجة العلمية</label>
                                    <select name="academic_degree_id" id="" class="form-control select2">
                                        <option value="">حدد درجة علمية</option>
                                        @foreach ($academicDegrees as $academicDegree)
                                            <option value="{{$academicDegree->id}}" {{old('academic_degree_id', $doctor->academic_degree_id) == $academicDegree->id ? "selected" : ""}}>{{$academicDegree->name}}</option>
                                        @endforeach
                                    </select>
                                  </div>
                                  <div class="col-md-4">
                                      <label> سنة الحصول عليها </label>
                                      <select name="certificate_of_excellence_date" id="certificate_of_excellence_date" class="form-control selectize select2">
                                          @php
                                              $currentYear = date('Y');
                                              $years = range($currentYear, 1950);
                                          @endphp
                                          <option value="">حدد السنة</option>
                                          @foreach ($years as $year)
                                              <option value="{{$year}}" {{old('certificate_of_excellence_date', $doctor->certificate_of_excellence_date)==$year?"selected":""}}>{{$year}}</option>
                                          @endforeach
                                      </select>
                                  </div>
                                    <div class="col-md-4">
                                       <label>   جهة الحصول عليها  </label>
                                       <select name="academic_degree_univeristy_id" class="form-control selectize">
                                           <option value="">حدد الجهة</option>
                                           @foreach ($universities as $university)
                                               <option value="{{$university->id}}" {{old('academic_degree_university_id',$doctor->academic_degree_univeristy_id)==$university->id?"selected":""}}>{{$university->name}}</option>
                                           @endforeach
                                       </select>
                                 </div>
                            </div>
                        </div>
                        @endif
    
                      
    
    
                    </div>
    
                    <!-- المستندات -->
                    <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                        <h3 class="bg-primary text-light header-sc mb-3">المستندات المرفوعة</h3>
                        <div class="list-group">
                            @foreach($doctor->files as $document)
                                <div class="list-group-item d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                    <div class="flex-grow-1 mb-2 mb-sm-0">
                                        <strong>{{ $document->fileType->name }}</strong><br>
                                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="small text-decoration-none">
                                            عرض الملف الذي قمت برفعه <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <label class="btn btn-outline-secondary btn-sm mb-0 me-2">
                                            <i class="fa fa-upload"></i>  اضغط هنا للتعديل 
                                            <input
                                                type="file"
                                                name="reupload_files[{{ $document->id }}]"
                                                hidden
                                                onchange="updateFileName(this)"
                                            >
                                        </label>
                                        <span id="fileName_{{ $document->id }}" class="small text-truncate" style="max-width: 150px;">
    
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success">حفظ التعديلات</button>
                </div>
                </div>
            </form>
         </div>
     </div>
 @endif



 @elseif($doctor->membership_status == \App\Enums\MembershipStatus::under_edit)

 <div class="card">
    <div class="card-body">

        
        <div class="col-md-12 mt-3">
            <div class="custom-alert d-flex align-items-center mb-4" role="alert" style="background: #ff5f6f!important;">
                <i class="fa fa-info mr-3 fs-4 me-2 flex-shrink-0 text-light" style="margin-left: 19px!important;"></i>
                <span class="flex-grow-1 text-light font-weight-bold" style="font-weight: bold!important;">تم تغيير حالة عضويتك الى قيد التعديل وذلك لسبب : {{ $doctor->edit_note }} </span>
            </div>
        </div>

        <form action="{{route('doctor.profile.update')}}"  method="POST" enctype="multipart/form-data" dir="rtl">
            @csrf
            @method('PUT')

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="editTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab" aria-controls="data" aria-selected="true">
                        البيانات
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="files-tab" data-bs-toggle="tab" data-bs-target="#files" type="button" role="tab" aria-controls="files" aria-selected="false">
                        المستندات
                    </button>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content mt-4" id="editTabsContent">
                <!-- بيانات -->
                <div class="tab-pane fade show active" id="data" role="tabpanel" aria-labelledby="data-tab">
                    <div class="personal-information">
                        <h3 class="bg-primary text-light header-sc">بياناتي الشخصية</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">الاسم كاملاََ</label>
                                <input type="text" name="name" class="form-control" value="{{ $doctor->name }}" >
                            </div>
                            <div class="col-md-6">
                                <label for="">الاسم باللغة الانجليزية</label>
                                <input type="text" name="name_en" class="form-control" value="{{ $doctor->name_en }}" >
                            </div>
                            @if ($is_libyan)
                                <div class="col-md-6">
                                    <label>     تاريخ الميلاد    </label>
                                    <input type="date" required name="date_of_birth" value="{{date('Y-m-d', strtotime($doctor->date_of_birth))}}" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label> الرقم الوطني    </label>
                                    <input type="text" required name="national_number" value="{{old('national_number',$doctor->national_number)}}" class="form-control">
                            </div>

                            <div class="col-md-6">
                                    <label> اسم الام      </label>
                                    <input type="text" required name="mother_name" value="{{old('mother_name',$doctor->mother_name)}}" class="form-control">
                            </div>
                            <div class="col-md-3">
                                    <label>       الحالة الاجتماعية    </label>
                                    <select name="marital_status" id="marital_status" class="form-control">
                                    <option value="single" {{$doctor->marital_status == "signle" ? "selected" : ""}} >اعزب</option>
                                    <option value="married" {{$doctor->marital_status == "married" ? "selected" : ""}}>متزوج</option>
                                    </select>
                            </div>
                            <div class="col-md-3">
                                <label>       الجنس      </label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="male" {{$doctor->gender == "male" ? "selected" : ""}}>ذكر</option>
                                    <option value="female" {{$doctor->gender == "female" ? "selected" : ""}}>انثى</option>
                                </select>
                            </div>
                            @endif
                            @if ($doctor->type->value != "libyan" && $doctor->type->value != "palestinian")
                            <div class="col-md-6">
                                <label>الجنسية</label>
                                <select name="country_id"  id="country_id" class="form-control selectize" @if(request('type')=="libyan"||request('type')=="palestinian")  @endif>
                                    <option value="">حدد دولة</option>
                                    @foreach ($countries as $country)
                                        @if ($country->id!=1 && $country->id!=2)
                                            <option value="{{$country->id}}" {{old('country_id', $doctor->country_id )==$country->id?"selected":""}}>{{$country->nationality_name_ar}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <label for="">رقم الجواز</label>
                                <input type="text" name="passport_number" class="form-control" value="{{ $doctor->passport_number }}" >
                            </div>
                            @if ($is_libyan)
                            <div class="col-md-6">
                                <label> تاريخ انتهاء صلاحية جواز السفر    </label>
                                <input type="date" required name="passport_expiration" value="{{old('passport_expiration', date('Y-m-d', strtotime($doctor->passport_expiration)) )}}" class="form-control">
                          </div>
                            @endif
                        </div>
                    </div>
                    <div class="contact-information mt-3">
                        <h3 class="bg-primary text-light header-sc">بيانات التواصل</h3>
                        <div class="row">
                            
                            @if ($is_libyan)
                            <div class="col-md-12">
                                <label for="">  العنوان  </label>
                                <input type="text" name="address" class="form-control" value="{{ $doctor->address }}" >
                            </div>

                            @endif
                            <div class="col-md-6">
                                <label for=""> رقم الهاتف  </label>
                                <input type="text" name="phone" class="form-control" value="{{ $doctor->phone }}" >
                            </div>
                            <div class="col-md-6">
                                <label for="">  رقم واتساب </label>
                                <input type="text" name="phone_2" class="form-control" value="{{ $doctor->phone_2 }}" >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label for=""> بريدي الالكتروني </label>
                                <input type="email" name="email"  class="form-control" value="{{ $doctor->email }}" >
                            </div>
                        </div>
                    </div>
                    <div class="graduation-information mt-4">
                        <h3 class="bg-primary text-light header-sc">بيانات البكالوريس</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">جامعة التخرج</label>
                                <select name="hand_graduation_id" id="hand_graduation_id" class="form-control selectize" >
                                    <option value="">حدد جامعة</option>
                                    @foreach ($universities as $university)
                                        <option value="{{$university->id}}" {{old('hand_graduation_id', $doctor->hand_graduation_id )==$university->id?"selected":""}}>{{$university->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($is_libyan)
                            <div class="col-md-6">
                                <label>سنة التخرج</label>
                                <select name="graduation_date" class="form-control selectize select2">
                                 <option value="">حدد  تاريخ</option>
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
                                <label for="">  تاريخ التخرج  </label>
                                <input type="date" name="graduation_date" value="{{$doctor->graduation_date}}" class="form-control">
                            </div>
                            @endif
                        </div>
                    </div>
            
                    <div class="graduation-information mt-4">
                        <h3 class="bg-primary text-light header-sc">بيانات الامتياز</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">جهة الحصول على الامتياز </label>
                                <select name="qualification_university_id" id="qualification_university_id" class="form-control selectize" >
                                    <option value="">حدد جامعة</option>
                                    @foreach ($universities as $university)
                                        <option value="{{$university->id}}" {{old('qualification_university_id', $doctor->qualification_university_id )==$university->id?"selected":""}}>{{$university->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($is_libyan)
                            <div class="col-md-6">
                                <label> سنة الحصول عليها </label>
                                <select name="internership_complete" id="internership_complete" class="form-control selectize select2">
                                    @php
                                        $currentYear = date('Y');
                                        $years = range($currentYear, 1950);
                                    @endphp
                                    <option value="">حدد السنة</option>
                                    @foreach ($years as $year)
                                        <option value="{{$year}}" {{old('internership_complete',$doctor->internership_complete)==$year?"selected":""}}>{{$year}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @else 
                            <div class="col-md-6">
                                <label for="">  تاريخ الحصول عليها  </label>
                                <input type="date" name="internership_complete" value="{{date('Y-m-d', strtotime($doctor->internership_complete))}}" class="form-control">
                            </div>
                            @endif

                            
                        </div>
                    </div>


                    @if ($doctor->academic_degree_id)
                    <div class="graduation-information mt-4">
                        <h3 class="bg-primary text-light header-sc"> الدرجة العلمية الحالية </h3>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">الدرجة العلمية</label>
                                <select name="academic_degree_id" id="" class="form-control select2">
                                    <option value="">حدد درجة علمية</option>
                                    @foreach ($academicDegrees as $academicDegree)
                                        <option value="{{$academicDegree->id}}" {{old('academic_degree_id', $doctor->academic_degree_id) == $academicDegree->id ? "selected" : ""}}>{{$academicDegree->name}}</option>
                                    @endforeach
                                </select>
                              </div>
                              <div class="col-md-4">
                                  <label> سنة الحصول عليها </label>
                                  <select name="certificate_of_excellence_date" id="certificate_of_excellence_date" class="form-control selectize select2">
                                      @php
                                          $currentYear = date('Y');
                                          $years = range($currentYear, 1950);
                                      @endphp
                                      <option value="">حدد السنة</option>
                                      @foreach ($years as $year)
                                          <option value="{{$year}}" {{old('certificate_of_excellence_date', $doctor->certificate_of_excellence_date)==$year?"selected":""}}>{{$year}}</option>
                                      @endforeach
                                  </select>
                              </div>
                                <div class="col-md-4">
                                   <label>   جهة الحصول عليها  </label>
                                   <select name="academic_degree_univeristy_id" class="form-control selectize">
                                       <option value="">حدد الجهة</option>
                                       @foreach ($universities as $university)
                                           <option value="{{$university->id}}" {{old('academic_degree_university_id',$doctor->academic_degree_univeristy_id)==$university->id?"selected":""}}>{{$university->name}}</option>
                                       @endforeach
                                   </select>
                             </div>
                        </div>
                    </div>
                    @endif

                  


                </div>

                <!-- المستندات -->
                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                    <h3 class="bg-primary text-light header-sc mb-3">المستندات المرفوعة</h3>
                    <div class="list-group">
                        @foreach($doctor->files as $document)
                            <div class="list-group-item d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
                                <div class="flex-grow-1 mb-2 mb-sm-0">
                                    <strong>{{ $document->fileType->name }}</strong><br>
                                    <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="small text-decoration-none">
                                        عرض الملف الذي قمت برفعه <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                                <div class="d-flex align-items-center">
                                    <label class="btn btn-outline-secondary btn-sm mb-0 me-2">
                                        <i class="fa fa-upload"></i>  اضغط هنا للتعديل 
                                        <input
                                            type="file"
                                            name="reupload_files[{{ $document->id }}]"
                                            hidden
                                            onchange="updateFileName(this)"
                                        >
                                    </label>
                                    <span id="fileName_{{ $document->id }}" class="small text-truncate" style="max-width: 150px;">

                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-success">حفظ التعديلات</button>
            </div>
            </div>
        </form>
    </div>
</div>

@elseif( $doctor->membership_status == \App\Enums\MembershipStatus::InActive )
<div class="card">
   <div class="card-body">
       <div class="image d-flex justify-content-center">
           <img src="{{asset('/assets/images/inactive.jpg')}}" width="400" alt="">
       </div>
       <h1 class="text-center">
           <strong class="text-danger">عضويتك غير مفعله </strong>
       </h1>

       <p class="text-center h3"> يجب عليك زيارة الفرع الخاص بك لمزيد من التفاصيل </p>

       <div class="text-center">
           <a href="/logout" class="btn btn-primary text-light">تسجيل خروج</a>
       </div>
   </div>
</div>





@elseif( $doctor->membership_status == \App\Enums\MembershipStatus::UnderPayment )
<div class="card">
   <div class="card-body">
       <div class="image d-flex justify-content-center">
           <img src="{{asset('/assets/images/under_payment.png')}}" width="300" alt="">
       </div>
       <h1 class="text-center">
           <strong class="text-danger"> يجب استكمال سداد فواتيرك   </strong>
       </h1>

       <p class="text-center h3"> يجب عليك زيارة الفرع الخاص بك لسداد القيمة التي عليك من قيمة الاشتراك كي يتم تفعيل حسابك  </p>
       <p class="text-center h2 text-success mt-2">القيمة {{$doctor->invoices()->where('status','unpaid')->sum('amount')}}  دينار ليبي فقط </p>
       <div class="text-center">
           <a href="/logout" class="btn btn-primary text-light">تسجيل خروج</a>
       </div>
   </div>
</div>

</


@endif



@endsection

@section('scripts')
<script>
    function validatePasswords() {
        let newPassword = document.getElementById('password').value;
        let confirmPassword = document.getElementById('password_confirmation').value;
        if (newPassword !== confirmPassword) {
            alert('كلمة المرور الجديدة وتأكيد كلمة المرور غير متطابقين!');
            return false;
        }
        return true;
    }

    function togglePassword(fieldId) {
        let field = document.getElementById(fieldId);
        let eyeIcon = document.getElementById('eye_' + fieldId);
        if (field.type === "password") {
            field.type = "text";
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            field.type = "password";
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
<script>
    function updateFileName(input) {
        const match = input.name.match(/\[(\d+)\]/);
        if (!match) return;
        const id = match[1];
        const span = document.getElementById(`fileName_${id}`);
        span.textContent = input.files[0]?.name || 'لم يتم اختيار ملف';
    }
</script>
@endsection
