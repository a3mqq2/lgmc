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
                                                <div class="row mt-3">
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


                                                      <h4 class="card-title mt-3"> بيانات الاتصال والدخول </h4>
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



                                                   <h4 class="card-title mt-3"> الدرجة العلمية   </h4>
                                                   
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
                                                      <div class="col-md-4">
                                                          <label for=""> تاريخ انتهاء الامتياز   </label>
                                                          <input type="date" name="internership_complete" value="{{old('internership_complete')}}" id="" class="form-control">
                                                      </div>
                                                  </div>


                                                   <h4 class="mb-2 mt-2">📑 المستندات المطلوبة</h4>

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


                                                   <div class=" text-primary mt-2  mb-2">بيانات العمل الحالي</div>
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
                                                              <div class="col-md-12 mt-1 mb-2">
                                                                  <label for="">حدد فرع</label>
                                                                  <select name="branch_id" id="" class="form-control select2">
                                                                      <option value="">حدد فرع</option>
                                                                      @foreach (App\Models\Branch::all() as $branch)
                                                                      <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                                      @endforeach
                                                                  </select>
                                                              </div>
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


                                                   <div class="text-primary mt-2 mb-2">بيانات العمل السابق</div>
                                                   <div class="col-md-12">
                                                      <label for="">جهات العمل السابقة</label>
                                                      <textarea name="ex_medical_facilities" id="" cols="30" rows="4" class="form-control"></textarea>
                                                  </div>
                                                  <div class="col-md-12 mt-2">
                                                      <label for=""> سنوات الخبره  </label>
                                                      <input name="experience" id="" type="number" class="form-control"></textarea>
                                                  </div>

                                                  <div class="col-md-12 mt-3">
                                                   <label for=""> بيانات اضافيه</label>
                                                   <textarea name="notes" id="" cols="30" rows="4" class="form-control"></textarea>
                                               </div>


                                                   <div class="mt-4">
                                                      <button type="submit" class="btn btn-primary w-100">تسجيل</button>
                                                  </div>

                                          </form>
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
    <!-- end auth-page-wrapper -->
    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap
::contentReference[oaicite:0]{index=0}
 
