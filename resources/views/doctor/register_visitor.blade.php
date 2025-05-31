<!doctype html>
<html lang="ar" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
<head>
    <meta charset="utf-8" />
    <title>دخول الأطباء | بوابة النظام</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="النقابة العامة للاطباء - ليبيا - بوابة النظام" name="description" />
    <meta content="النقابة العامة للاطباء - ليبيا" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/logo-primary.png">
    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.5/css/selectize.bootstrap5.min.css" integrity="sha512-w4sRMMxzHUVAyYk5ozDG+OAyOJqWAA+9sySOBWxiltj63A8co6YMESLeucKwQ5Sv7G4wycDPOmlHxkOhPW7LRg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- import font awesome --}}
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
      /* Extended Background Colors */
   
      .bg-secondary {
          background-color: #6c757d !important; /* Gray */
      }
      .bg-success {
          background-color: #28a745 !important; /* Green */
      }
      .bg-danger {
          background-color: #dc3545 !important; /* Red */
      }
      .bg-warning {
          background-color: #ffc107 !important; /* Yellow */
      }
      .bg-info {
          background-color: #17a2b8 !important; /* Light Blue */
      }
      .bg-light {
          background-color: #f8f9fa !important; /* Light Gray */
      }
      .bg-dark {
          background-color: #343a40 !important; /* Dark Gray */
      }
      .bg-white {
          background-color: #ffffff !important; /* White */
      }
      .bg-transparent {
          background-color: transparent !important;
      }
      .bg-pink {
          background-color: #ff69b4 !important; /* Pink */
      }
      .bg-purple {
          background-color: #6f42c1 !important; /* Purple */
      }
      .bg-teal {
          background-color: #20c997 !important; /* Teal */
      }
      .bg-orange {
          background-color: #fd7e14 !important; /* Orange */
      }
      .bg-brown {
          background-color: #8b4513 !important; /* Brown */
      }
      .bg-lime {
          background-color: #cddc39 !important; /* Lime */
      }
      .bg-cyan {
          background-color: #00bcd4 !important; /* Cyan */
      }
      /* Gradient Backgrounds */
      .bg-gradient-primary {
          background: linear-gradient(to right, #007bff, #00aaff) !important; /* Blue Gradient */
      }
      .bg-gradient-secondary {
          background: linear-gradient(to right, #6c757d, #495057) !important; /* Gray Gradient */
      }
      .bg-gradient-success {
          background: linear-gradient(to right, #28a745, #218838) !important; /* Green Gradient */
      }
      .bg-gradient-danger {
          background: linear-gradient(to right, #dc3545, #c82333) !important; /* Red Gradient */
      }
      .bg-gradient-warning {
          background: linear-gradient(to right, #ffc107, #e0a800) !important; /* Yellow Gradient */
      }
      .bg-gradient-info {
          background: linear-gradient(to right, #17a2b8, #138496) !important; /* Light Blue Gradient */
      }
      .bg-gradient-light {
          background: linear-gradient(to right, #f8f9fa, #e2e6ea) !important; /* Light Gray Gradient */
      }
      .bg-gradient-dark {
          background: linear-gradient(to right, #343a40, #23272b) !important; /* Dark Gray Gradient */
      }
      .bg-gradient-pink {
          background: linear-gradient(to right, #ff69b4, #ff1493) !important; /* Pink Gradient */
      }
      .bg-gradient-purple {
          background: linear-gradient(to right, #6f42c1, #5a2a9c) !important; /* Purple Gradient */
      }
      .bg-gradient-teal {
          background: linear-gradient(to right, #20c997, #17a2b8) !important; /* Teal Gradient */
      }
      .bg-gradient-orange {
          background: linear-gradient(to right, #fd7e14, #e76f00) !important; /* Orange Gradient */
      }
      .bg-gradient-brown {
          background: linear-gradient(to right, #8b4513, #7a3e2a) !important; /* Brown Gradient */
      }
      .bg-gradient-lime {
          background: linear-gradient(to right, #cddc39, #8bc34a) !important; /* Lime Gradient */
      }
      .bg-gradient-cyan {
          background: linear-gradient(to right, #00bcd4, #009688) !important; /* Cyan Gradient */
      }
      /* Light Text Colors */
      .text-light-primary {
          color: rgba(0, 123, 255, 0.7) !important; /* Light Blue */
      }
      .text-light-secondary {
          color: rgba(108, 117, 125, 0.7) !important; /* Light Gray */
      }
      .text-light-success {
          color: rgba(40, 167, 69, 0.7) !important; /* Light Green */
      }
      .text-light-danger {
          color: rgba(220, 53, 69, 0.7) !important; /* Light Red */
      }
      .text-light-warning {
          color: rgba(255, 193, 7, 0.7) !important; /* Light Yellow */
      }
      .text-light-info {
          color: rgba(23, 162, 184, 0.7) !important; /* Light Blue */
      }
      .text-light-dark {
          color: rgba(52, 58, 64, 0.7) !important; /* Light Dark Gray */
      }
      .text-light-white {
          color: rgba(255, 255, 255, 0.7) !important; /* Light White */
      }
      .text-light-pink {
          color: rgba(255, 105, 180, 0.7) !important; /* Light Pink */
      }
      .text-light-purple {
          color: rgba(111, 66, 193, 0.7) !important; /* Light Purple */
      }
      .text-light-teal {
          color: rgba(32, 201, 151, 0.7) !important; /* Light Teal */
      }
      .text-light-orange {
          color: rgba(253, 126, 20, 0.7) !important; /* Light Orange */
      }
      .text-light-brown {
          color: rgba(139, 69, 19, 0.7) !important; /* Light Brown */
      }
      .text-light-lime {
          color: rgba(205, 220, 57, 0.7) !important; /* Light Lime */
      }
      .text-light-cyan {
          color: rgba(0, 188, 212, 0.7) !important; /* Light Cyan */
      
      /* Light Borders */
      .border-light-primary {
          border: 1px solid rgba(0, 123, 255, 0.7) !important; /* Light Blue */
      }
      .border-light-secondary {
          border: 1px solid rgba(108, 117, 125, 0.7) !important; /* Light Gray */
      }
      .border-light-success {
          border: 1px solid rgba(40, 167, 69, 0.7) !important; /* Light Green */
      }
      .border-light-danger {
          border: 1px solid rgba(220, 53, 69, 0.7) !important; /* Light Red */
      }
      .border-light-warning {
          border: 1px solid rgba(255, 193, 7, 0.7) !important; /* Light Yellow */
      }
      .border-light-info {
          border: 1px solid rgba(23, 162, 184, 0.7) !important; /* Light Blue */
      }
      .border-light-dark {
          border: 1px solid rgba(52, 58, 64, 0.7) !important; /* Light Dark Gray */
      }
      .border-light-white {
          border: 1px solid rgba(255, 255, 255, 0.7) !important; /* Light White */
      }
      .border-light-pink {
          border: 1px solid rgba(255, 105, 180, 0.7) !important; /* Light Pink */
      }
      .border-light-purple {
          border: 1px solid rgba(111, 66, 193, 0.7) !important; /* Light Purple */
      }
      .border-light-teal {
          border: 1px solid rgba(32, 201, 151, 0.7) !important; /* Light Teal */
      }
      .border-light-orange {
          border: 1px solid rgba(253, 126, 20, 0.7) !important; /* Light Orange */
      }
      .border-light-brown {
          border: 1px solid rgba(139, 69, 19, 0.7) !important; /* Light Brown */
      }
      .border-light-lime {
          border: 1px solid rgba(205, 220, 57, 0.7) !important; /* Light Lime */
      }
      .border-light-cyan {
          border: 1px solid rgba(0, 188, 212, 0.7) !important; /* Light Cyan */
      }
      </style>

      <style>
          /* Department Badges */
          .bg-finance {
              background-color: #1abc9c; /* Turquoise */
              color: #ffffff; /* White */
          }

          .bg-operation {
              background-color: #3498db; /* Blue */
              color: #ffffff; /* White */
          }

          .bg-management {
              background-color: #f1c40f; /* Yellow */
              color: #ffffff; /* White */
          }

          .bg-it {
              background-color: #9b59b6; /* Purple */
              color: #ffffff; /* White */
          }

          /* Category Badges */
          .bg-question {
              background-color: #2980b9; /* Dark Blue */
              color: #ffffff; /* White */
          }

          .bg-suggestion {
              background-color: #2ecc71; /* Green */
              color: #ffffff; /* White */
          }

          .bg-complaint {
              background-color: #e74c3c; /* Red */
              color: #ffffff; /* White */
          }

          /* Status Badges */
          .bg-new {
              background-color: #3498db; /* Blue */
              color: #ffffff; /* White */
          }

          .bg-pending {
              background-color: #f39c12; /* Orange */
              color: #ffffff; /* White */
          }

          .bg-customer-reply {
              background-color: #8e44ad; /* Dark Purple */
              color: #ffffff; /* White */
          }

          .bg-complete {
              background-color: #2ecc71; /* Green */
              color: #ffffff; /* White */
          }

          .bg-user-reply {
              background-color: #e67e22; /* Carrot */
              color: #ffffff; /* White */
          }

          /* Priority Badges */
          .bg-low {
              background-color: #95a5a6; /* Gray */
              color: #ffffff; /* White */
          }

          .bg-medium {
              background-color: #f1c40f; /* Yellow */
              color: #ffffff; /* White */
          }

          .bg-high {
              background-color: #e74c3c; /* Red */
              color: #ffffff; /* White */
          }

          /* Optional: Styling for the badge appearance */
          .badge {
              display: inline-block;
              padding: 0.25em 0.75em;
              border-radius: 9999px;
              font-size: 0.75rem;
              font-weight: 500;
              text-align: center;
              white-space: nowrap;
          }


          /* Priority Badges */

/* Low Priority */
.bg-low {
  background-color: #95a5a6; /* Gray */
  color: #ffffff; /* White text for contrast */
}

/* Medium Priority */
.bg-medium {
  background-color: #f1c40f; /* Yellow */
  color: #ffffff;
}

/* High Priority */
.bg-high {
  background-color: #e74c3c; /* Red */
  color: #ffffff;
}

/* Badge Base Style (optional if you're using Bootstrap's .badge) */
.badge {
  display: inline-block;
  padding: 0.3rem 0.6rem;
  border-radius: 9999px; /* Fully rounded corners */
  font-size: 0.75rem;
  font-weight: 500;
  text-align: center;
  white-space: nowrap;
}


</style>

</head>
<body>
    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12">
                        <div class="card overflow-hidden">
                            <div class="row g-0">
                                <!-- end col -->
                                <div class="col-lg-12">
                                 <div class="logo d-flex justify-content-center ">
                                       <img class="logo-dark" src="{{ asset('/assets/images/lgmc-dark.png?v=44') }}" srcset="{{ asset('/assets/images/lgmc-dark.png?v=44') }} 2x" width="300" alt="" />
                                 </div>



                                 <div class="container p-3">
                                    @include('layouts.messages')
                                 </div>

                                    <div class="p-lg-5 p-4">
                                        <div style="text-align: right!important;">
                                            <h5 class="text-primary text-right"> طلب عضوية جديدة   </h5>
                                            <p class="text-muted">تسجيل الدخول للمتابعة إلى بوابة النظام.</p>
                                        </div>
                                        <div class="mt-4">
                                         


                                             <form method="POST" dir="rtl" enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')

                                                   <div class="col-md-12 text-primary">
                                                      <h4 class="text-primary mb-3">البيانات الشخصية   </h4>
                                                   </div>
                                                   
                                                      <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="">الاسم بالكامل</label>
                                                            <input type="text" required name="name" value="{{old('name')}}"  id="" class="form-control">
                                                            <input type="hidden" name="type" value="{{request('type')}}">
                                                        </div>
                              
                    
                    
                                                        <div class="col-md-6">
                                                            <label for="">  الجنسية  </label>
                                                            <select name="country_id" required id="country_id" class="form-control selectize" 
                                                            @if(request('type') == "libyan" || request('type') == "palestinian") disabled @endif>
                                                            <option value="">حدد دوله من القائمة</option>
                                                            @foreach ($countries as $country)
                                                                  @if ($country->id != 1 && $country->id != 2)
                                                                      <option value="{{$country->id}}" {{old('country_id') == $country->id ? "selected" : ""}}>{{$country->nationality_name_ar}}</option>
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


                                                    <hr>

                                                    <div class="row mb-4">


                                                      <div class="col-md-12 text-primary">
                                                         <h4 class="text-primary mb-3">بيانات الاتصال </h4>
                                                      </div>
                                                      
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

                                                          {{-- email input --}}
                                                          <div class="col-md-6">
                                                            <label for="">البريد الالكتروني  </label>
                                                            <input type="email"  name="email" value="{{old('email')}}" id="email" class="form-control">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for=""> كلمة المرور </label>
                                                            <input type="password"   name="password" value="{{old('password')}}" required class="form-control">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for=""> تأكيد كلمة المرور  </label>
                                                            <input type="password"  name="password_confirmation" required value="{{old('password_confirmation')}}" id="" class="form-control">
                                                        </div>
                                                      
                                                    </div> 


                                                    <hr> 



                                                        <h4 class="card-title mt-2 mb-3 "> <h4 class="text-primary">بكالوريس</h4>    </h4>
                                                        <div class="row mt-2 mb-4">
                                                            <div class="col-md-6">
                                                                <label for=""> جهة التخرج </label>
                                                                <select name="hand_graduation_id" id="" class="form-control selectize">
                                                                    <option value="">حدد جهة التخرج </option>
                                                                    @foreach ($universities as $university)
                                                                        <option value="{{$university->id}}" {{old('hand_graduation_id') == $university->id ? "selected" : ""}}>{{$university->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="graduation_date">تاريخ الحصول عليها</label>
                                                                <select name="graduation_date" id="graduation_date" class="form-control select2" required>
                                                                    @php
                                                                        $currentYear = date('Y');
                                                                        $selectedYear = old('graduation_date', $currentYear);
                                                                    @endphp
                                                                    @for($year = $currentYear; $year >= 1950; $year--)
                                                                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            
                                                        </div>

                                                        <hr>

                                                        <h4 class="text-primary">الامتياز</h4>
                                                        <div class="row mt-2 mb-4">
                                                          <div class="col-md-6">
                                                              <label for=""> جهة الحصول على الامتياز </label>
                                                              <select name="qualification_university_id"   id="" class="form-control form-control selectize">
                                                                  <option value="">حدد جهة  </option>
                                                                  @foreach ($universities as $university)
                                                                      <option value="{{$university->id}}" {{old('qualification_university_id') == $university->id ? "selected" : ""}}>{{$university->name}}</option>
                                                                  @endforeach
                                                              </select>
                                                          </div>
                                                          <div class="col-md-6">
                                                              <label for="certificate_of_excellence_date">تاريخ الحصول عليها</label>
                                                              <select name="certificate_of_excellence_date" id="certificate_of_excellence_date" class="form-control select2">
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
      
                                                       <hr>


    

                                                      <hr>



                                                      <h4 class="text-primary">
                                                        بيانات العمل الحالي
                                                      </h4>
                                                  <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">الصفة</label>
                                                        <select name="doctor_rank_id" id="doctor_rank_id" class="form-control select2">
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
                                                    
                                                     <div class="col-md-12 mt-2 mb-3">
                                                         <div class="row">
                                                      
                                                            <div class="col-md-12 mt-1 mb-2">
                                                                <label for="">حدد فرع (الاقرب) </label>
                                                                <select name="branch_id" id="" class="form-control select2">
                                                                    <option value="">حدد فرع</option>
                                                                    @foreach (App\Models\Branch::all() as $branch)
                                                                     @if ($branch->id == 1)
                                                                        <option value="{{$branch->id}}">{{$branch->name}} (العامة) </option>
                                                                        @else 
                                                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                                     @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                             <div class="col-md-6">
                                                               <label for=""> تخصص اول (ان وجد) </label>
                                                               <select name="specialty_1_id"  class="form-control selectize">
                                                                   <option value="">حدد تخصص اول</option>
                                                                   @foreach ($specialties as $specialty)
                                                                       <option value="{{$specialty->id}}" {{old('specialty_1_id') == $specialty->id ? "selected" : ""}}>{{$specialty->name}}</option>
                                                                   @endforeach
                                                               </select>
                                                           </div>
                                                           <div class="col-md-6" id="specialty_2_container">
                                                            <label for="specialty_2">تخصص ثاني (إن وجد) </label>
                                                            <input type="text" name="specialty_2" id="specialty_2" value="{{ old('specialty_2') }}" class="form-control" autocomplete="off">
                                                        </div>                                    
                                                
                                                         </div>
                                                     </div>
                                                 </div>



                                                @if (request('type') == "libyan")
                                                    <div class="text-primary mt-2 mb-2">بيانات العمل السابق</div>
                                                        <div class="col-md-12">
                                                        <label for="">جهات العمل السابقة</label>
                                                        <select name="ex_medical_facilities[]" multiple id="" class="selectize form-control">
                                                            <option value="-">---</option>
                                                            @foreach ($medicalFacilities as $item)
                                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                  

                                                  @if (request('type') == "libyan")
                                                  <div class="col-md-12 mt-2">
                                                    <label for=""> سنوات الخبره  </label>
                                                    <input name="experience" id="" type="number" class="form-control"></textarea>
                                                </div>

                                                  @endif



{{-- 
                                               <div class="form-group">
                                                {!! NoCaptcha::display() !!}
                                                @error('g-recaptcha-response')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div> --}}


                                                   <div class="mt-4">
                                                      <button type="submit" class="btn btn-primary w-100">تسجيل</button>
                                                  </div>

                                          </form>


                                          {!! NoCaptcha::renderJs() !!}


                                      </div>
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                            <!-- end row -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->
        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0">&copy;
                                <script>document.write(new Date().getFullYear())</script> النقابة العامة للاطباء - ليبيا. جميع الحقوق محفوظة.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.5/js/standalone/selectize.min.js" integrity="sha512-JFjt3Gb92wFay5Pu6b0UCH9JIOkOGEfjIi7yykNWUwj55DBBp79VIJ9EPUzNimZ6FvX41jlTHpWFUQjog8P/sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>

        $(".selectize").selectize({
            dir: "rtl",
            width: "100%",
            placeholder: "اختر",
            allowClear: true,
        });
    </script>

</body>
</html>