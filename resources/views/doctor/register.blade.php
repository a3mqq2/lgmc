<!doctype html>
<html lang="ar" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
<head>
    <meta charset="utf-8" />
    <title>دخول الأطباء | بوابة النظام</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="نقابة الأطباء الليبية - بوابة النظام" name="description" />
    <meta content="نقابة الأطباء الليبية" name="author" />
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
                                            <h5 class="text-primary text-right"> طلب عضوية جديدة  </h5>
                                            <p class="text-muted">تسجيل الدخول للمتابعة إلى بوابة النظام.</p>
                                        </div>
                                        <div class="mt-4">
                                         
                                               @if (!request('type'))
                                               <div class="row" dir="rtl">
                                                <div class="col-md-12">
                                                   <h3 class="text-center">حدد نوع العضوية</h3>
                                                </div>
                                               </div>
                                               <div class="row">
                                                <div class="row mt-3" dir="rtl">
                                                   <div class="col-md-6">
                                                       <a href="{{route('register', ['type' => 'libyan' ])}}">
                                                           <div class="card {{App\Enums\DoctorType::Libyan->badgeClass()}} text-light text-center p-3 d-flex justify-content-center align-items-center" style="height: 100px;">
                                                               <h5 class="text-center text-light">طبيب ليبي</h5>
                                                           </div>
                                                       </a>
                                                   </div>
                                                   <div class="col-md-6">
                                                       <a href="{{route('register', ['type' => 'palestinian' ])}}">
                                                           <div class="card {{App\Enums\DoctorType::Palestinian->badgeClass()}} text-light text-center p-3 d-flex justify-content-center align-items-center" style="height: 100px;">
                                                               <h5 class="text-center text-light">طبيب فلسطيني</h5>
                                                           </div>
                                                       </a>
                                                   </div> 
                                                   <div class="col-md-6">
                                                       <a href="{{route('register', ['type' => 'foreign' ])}}">
                                                           <div class="card {{App\Enums\DoctorType::Foreign->badgeClass()}} text-dark text-center p-3 d-flex justify-content-center align-items-center" style="height: 100px;">
                                                               <h5 class="text-center text-dark">طبيب اجنبي مقيم</h5>
                                                           </div>
                                                       </a>
                                                   </div>
                                                   <div class="col-md-6">
                                                       <a href="{{route('register', ['type' => 'visitor' ])}}">
                                                           <div class="card  {{App\Enums\DoctorType::Visitor->badgeClass()}} text-light text-center p-3 d-flex justify-content-center align-items-center" style="height: 100px;">
                                                               <h5 class="text-center text-light">طبيب زائر</h5>
                                                           </div>
                                                       </a>
                                                   </div>
                                               </div>
                                             </div>

                                             @else 


                                             <form method="POST" dir="rtl" enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                      <h4 class="card-title"> المعلومات الشخصية </h4>
                                                 
                                                      <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="">الاسم بالكامل</label>
                                                            <input type="text" required name="name" value="{{old('name')}}"  id="" class="form-control">
                                                            <input type="hidden" name="type" value="{{request('type')}}">
                                                        </div>
                                                        
                                                        @if (request('type') != "visitor" && request('type') != "foreign")
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
                                                     
                    
                                                        @if (request('type') != "visitor" && request('type') != "foreign")
                                                        <div class="col-md-6">
                                                            <label for="">الرقم النقابي الأول</label>
                                                            <input type="text" name="doctor_number"   value="{{old('doctor_number')}}"  id="" class="form-control">
                                                        </div>
                                                        @endif
                    
                    
                                                        @if (request('type') != "visitor" && request('type') != "foreign")
                                                        <div class="col-md-6 mt-2">
                                                            <label for=""> اسم الام </label>
                                                            <input type="text" required name="mother_name" value="{{old('mother_name')}}" id="" class="form-control">
                                                        </div>
                                                        @endif
                    
                                                        <div class="col-md-6 mt-2">
                                                            <label for="">  الجنسية  </label>
                                                            <select name="country_id" required id="country_id" class="form-control select2" 
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
                                                        @if (request('type') != "visitor" && request('type') != "foreign")
                                                        <div class="col-md-6 mt-2">
                                                            <label for=""> تاريخ الميلاد </label>
                                                            <input type="date" required name="date_of_birth" value="{{old('date_of_birth')}}" id="" class="form-control">
                                                        </div>
                                                        @endif
                                                        @endif
                                                        @if (request('type') != "visitor" && request('type') != "foreign")
                                                        <div class="col-md-6 mt-2">
                                                            <label for="">  الحالة الاجتماعية  </label>
                                                            <select name="marital_status"  required id="" class="form-control">
                                                                <option value="single" {{old('marital_status') == "single" ? "selected" : ""}}>اعزب</option>
                                                                <option value="married" {{old('marital_status') == "married" ? "selected" : ""}}>متزوج</option>
                                                            </select>
                                                        </div>
                                                        @endif
                                                        <div class="col-md-6 mt-2">
                                                            <label for="">  النوع   </label>
                                                            <select name="gender" required id="gender" required  class="form-control"  >
                                                                <option value="male" {{old('gender') == "male" ? "selected" : ""}}>ذكر</option>
                                                                <option value="female" {{old('gender') == "female" ? "selected" : ""}}>انثى</option>
                                                            </select>
                                                        </div>
                                                       
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


                                                    <hr>

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
                                                        @if (request('type') == "libyan")
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




                                                        <h4 class="card-title mt-2 mb-2"> بكالوريس    </h4>
                                                        <div class="row mt-2 mb-4">
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
                                                            <div class="col-md-8">
                                                                <label for="graduation_certificate">تاريخ الحصول عليها</label>
                                                                <select name="graduation_certificate" id="graduation_certificate" class="form-control select2" required>
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



                                                   @if (request('type') != "visitor")
                                                   <h4 class="card-title mt-2 mb-2"> الامتياز    </h4>
                                                   <div class="row mt-2 mb-4">
                                                     <div class="col-md-6">
                                                         <label for=""> جهة الحصول على الامتياز </label>
                                                         <select name="qualification_university_id"  required id="" class="form-control form-control select2">
                                                             <option value="">حدد جهة  </option>
                                                             @foreach ($universities as $university)
                                                                 <option value="{{$university->id}}" {{old('qualification_university_id') == $university->id ? "selected" : ""}}>{{$university->name}}</option>
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
                                                      
                                                  </div>
 
                                                   @endif
                                               


                                                   @if (request('type') != "visitor")

                                                   <h4 class="card-title mt-3"> الدرجة العلمية   </h4>
                                                   
                                                   <div class="row mb-3">
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
                                                      <div class="col-md-12">
                                                          <label for=""> الجهة  </label>
                                                          <select name="qualification_university_id" id="" class="form-control select2">
                                                              <option value="">حدد جهة  </option>
                                                              @foreach ($universities as $university)
                                                                  <option value="{{$university->id}}" {{old('qualification_university_id') == $university->id ? "selected" : ""}}>{{$university->name}}</option>
                                                              @endforeach
                                                          </select>
                                                      </div>
                                                  </div>


                                                   @endif




                                                  <div class=" text-primary mt-2  mb-2">بيانات العمل الحالي</div>
                                                  <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="">الصفة</label>
                                                        <select name="doctor_rank_id" id="doctor_rank_id" class="form-control select2">
                                                            <option value="">حدد الصفة</option>
                                                            @foreach ($doctor_ranks as $doctor_rank)
                                                                @if (request('type') == "visitor" && ($doctor_rank->id != 1 && $doctor_rank->id != 2))
                                                                    <option value="{{ $doctor_rank->id }}" {{ old('doctor_rank_id') == $doctor_rank->id ? "selected" : "" }}>
                                                                        @if ($doctor_rank->id == 6 && (request('type') == "visitor" || request('type') == "foreign"))
                                                                            استشاري تخصص دقيق
                                                                        @else
                                                                            {{ $doctor_rank->name }}
                                                                        @endif
                                                                    </option>
                                                                @elseif (request('type') != "visitor")
                                                                    <option value="{{ $doctor_rank->id }}" {{ old('doctor_rank_id') == $doctor_rank->id ? "selected" : "" }}>
                                                                        @if ($doctor_rank->id == 6 && (request('type') == "visitor" || request('type') == "foreign"))
                                                                            استشاري تخصص دقيق
                                                                        @else
                                                                            {{ $doctor_rank->name }}
                                                                        @endif
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
                                                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                             <div class="col-md-6">
                                                               <label for=""> تخصص اول</label>
                                                               <select name="specialty_1_id"  class="form-control">
                                                                   <option value="">حدد تخصص اول</option>
                                                                   @foreach ($specialties as $specialty)
                                                                       <option value="{{$specialty->id}}" {{old('specialty_1_id') == $specialty->id ? "selected" : ""}}>{{$specialty->name}}</option>
                                                                   @endforeach
                                                               </select>
                                                           </div>
                                                           <div class="col-md-6" id="specialty_2_container">
                                                            <label for="specialty_2">تخصص دقيق</label>
                                                            <input type="text" name="specialty_2" id="specialty_2" value="{{ old('specialty_2') }}" class="form-control" autocomplete="off">
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
                                                            <div class="row" id="documents_container"></div>
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

                                                  <div class="col-md-12 mt-3">
                                                   <label for=""> بيانات اضافيه</label>
                                                   <textarea name="notes" id="" cols="30" rows="4" class="form-control"></textarea>
                                               </div>


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


                                               @endif

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
                                <script>document.write(new Date().getFullYear())</script> نقابة الأطباء الليبية. جميع الحقوق محفوظة.
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
                    dateOfBirthInput.value = `${year}`; // Only the year for Flatpickr
                    genderSelect.value = gender;
                    genderInput.value = gender;
                } else {
                    // Clear inputs if the national number is not valid
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
 $(document).ready(function () {
        $("#doctor_rank_id").change(function () {
            var selectedRank = parseInt($(this).val());
            
            // حالة طبيب ممارس عام: إخفاء جميع التخصصات
            if (selectedRank === 1) {
                $("select[name='specialty_1_id']").parent().hide();
                $("select[name='specialty_2_id']").parent().hide();
                $("select[name='specialty_3_id']").parent().hide();
            }
            // حالة طبيب ممارس تخصصي - أخصائي أول - أخصائي ثاني: إظهار تخصص أول فقط
            else if ([2, 3, 4].includes(selectedRank)) {
                $("select[name='specialty_1_id']").parent().show();
                $("select[name='specialty_2_id']").parent().hide();
                $("select[name='specialty_3_id']").parent().hide();
            }
            // حالة استشاري أول - استشاري: إظهار تخصص أول وتخصص ثاني فقط
            else if ([5, 6].includes(selectedRank)) {
                $("select[name='specialty_1_id']").parent().show();
                $("select[name='specialty_2_id']").parent().show();
                $("select[name='specialty_3_id']").parent().hide();
            } else {
                // في حال عدم تحديد أي قيمة أو غيرها، يمكن إخفاء جميع الحقول
                $("select[name='specialty_1_id']").parent().hide();
                $("select[name='specialty_2_id']").parent().hide();
                $("select[name='specialty_3_id']").parent().hide();
            }
        });
    
        // تفعيل السكريبت عند تحميل الصفحة لضبط الحقول حسب القيمة القديمة
        $("#doctor_rank_id").trigger("change");
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
                            // showError(this, 'التاريخ يجب أن يكون بالصيغة الصحيحة (سنة-شهر-يوم).');
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
    
        <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Input elements
        const nationalNumberInput = document.getElementById('national_number');
        const birthYearInput = document.getElementById('birth_year');
        const monthSelect = document.querySelector('select[name="month"]');
        const daySelect = document.querySelector('select[name="day"]');
        const genderSelect = document.getElementById('gender');
        const genderInput = document.getElementById('gender_input');
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
                genderInput.value = gender;
    
                // Extract Birth Year, Month, and Day
                const year = nationalNumber.substring(1, 5); // 2nd to 5th digits are the year
                const month = parseInt(nationalNumber.substring(5, 7)); // 6th and 7th digits are the month
                const day = parseInt(nationalNumber.substring(7, 9)); // 8th and 9th digits are the day
    
                // Update inputs
                birthYearInput.value = year;
                monthSelect.value = month;
                daySelect.value = day;
    
            } else {
                // Clear inputs if the national number is invalid
                birthYearInput.value = '';
                monthSelect.value = '';
                daySelect.value = '';
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
        const monthSelect = document.querySelector('select[name="month"]');
        const daySelect = document.querySelector('select[name="day"]');
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
                monthSelect.value = month;
                daySelect.value = day;
            } else {
                // Clear inputs if the national number is invalid
                birthYearInput.value = '';
                monthSelect.value = '';
                daySelect.value = '';
                genderSelect.value = '';
            }
        });
    
   
    });
    
        </script>
    

    <script>
        $(function () {
            let doctorType = '{{ request("type") }}';
        
            function loadFileTypes(rankId = '') {
                $.get('/api/file-types', { doctor_type: doctorType, rank_id: rankId }, function (data) {
                    let html = '';
                    data.forEach(f => {
                        html += `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="document-card shadow-sm border rounded text-center p-3 position-relative">
                            <div class="document-icon mb-3">
                                <i class="fas fa-file-upload fa-3x text-primary"></i>
                            </div>
                            <h6 class="document-title mb-2">${f.name}${f.is_required ? '<span class="text-danger">*</span>' : ''}</h6>
                            <div class="custom-file">
                                <input type="file" name="documents[${f.id}]" class="custom-file-input" id="file_${f.id}" ${f.is_required ? 'required' : ''}>
                                <label class="custom-file-label" for="file_${f.id}">اختر ملف</label>
                            </div>
                            <small class="text-muted d-block mt-2">الملف يجب أن يكون بصيغة <b>PDF</b> أو صورة</small>
                            <div id="status_${f.id}" class="mt-2 text-muted">🔄 لم يتم الرفع بعد</div>
                        </div>
                    </div>`;
                    });
                    $('#documents_container').html(html);
                });
            }
        
            loadFileTypes();
        
            $('#doctor_rank_id').on('change', function () {
                loadFileTypes($(this).val());
            });
        
            $(document).on('change', '.custom-file-input', function () {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
                $('#status_' + $(this).attr('id').split('_')[1])
                    .html('✅ تم الرفع: ' + fileName)
                    .removeClass('text-muted')
                    .addClass('text-success');
            });
        });
        </script>
        
        
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Input elements
            const nationalNumberInput = document.getElementById('national_number');
            const birthYearInput = document.getElementById('birth_year');
            const monthSelect = document.querySelector('select[name="month"]');
            const daySelect = document.querySelector('select[name="day"]');
            const dateOfBirthInput = document.querySelector('input[name="date_of_birth"]'); // For non-Libyan
            const nameEnInput = document.querySelector('input[name="name_en"]');
            const emailInput = document.querySelector('input[name="email"]');
    
            // Check if request type is Libyan or not
            const isLibyan = "{{ request('type') }}" === "libyan";
    
            // Event listener for Libyan national number input
            if (isLibyan && nationalNumberInput) {
                nationalNumberInput.addEventListener('input', function () {
                    const nationalNumber = this.value;
    
                    if (nationalNumber.length === 12) {
                        // Extract Gender (optional if needed)
                        const genderDigit = parseInt(nationalNumber.charAt(0));
                        const gender = genderDigit === 1 ? 'male' : 'female';
    
                        // Extract Birth Year, Month, Day
                        const year = nationalNumber.substring(1, 5);
                        const month = parseInt(nationalNumber.substring(5, 7));
                        const day = parseInt(nationalNumber.substring(7, 9));
    
                        // Update fields
                        if (birthYearInput) birthYearInput.value = year;
                        if (monthSelect) monthSelect.value = month;
                        if (daySelect) daySelect.value = day;
                    } else {
                        // Clear fields if the national number is invalid
                        if (birthYearInput) birthYearInput.value = '';
                        if (monthSelect) monthSelect.value = '';
                        if (daySelect) daySelect.value = '';
                    }
                });
            }
    
            // Event listener for date of birth (non-Libyan)
            if (!isLibyan && dateOfBirthInput) {
                dateOfBirthInput.addEventListener('input', function () {
                    const dob = this.value; // Format: YYYY-MM-DD
                    if (dob) {
                        const [year, month, day] = dob.split('-');
                    } else {
                    }
                });
            }
    
            // Event listener for English name input
            nameEnInput?.addEventListener('input', function () {
                if (isLibyan) {
                    // Libyan: Regenerate email using year, month, day
                    const year = birthYearInput?.value || '';
                    const month = monthSelect?.value.padStart(2, '0') || '';
                    const day = daySelect?.value.padStart(2, '0') || '';
                    generateEmail(year, month, day);
                } else {
                    // Non-Libyan: Regenerate email using date_of_birth input
                    const dob = dateOfBirthInput?.value || '';
                    if (dob) {
                        const [year, month, day] = dob.split('-');
                        generateEmail(year, month, day);
                    } else {
                        emailInput.value = '';
                    }
                }
            });
    
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Find all input, select, and textarea fields with the "required" attribute
            const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
    
            requiredFields.forEach(function (field) {
                // Find the corresponding label for the field
                const label = field.closest('.form-group, .col-md-6, .col-md-4, .col-md-2, .col-md-12')?.querySelector('label');
    
                if (label && !label.querySelector('.required-asterisk')) {
                    // Append a red asterisk to the label
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

        $(".selectize").selectize({
            dir: "rtl",
            width: "100%",
            placeholder: "اختر",
            allowClear: true,
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

<script>
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });
</script>


<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />

</body>
</html>