<!doctype html>
<html lang="ar" dir="rtl" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">


<!-- Mirrored from themesbrand.com/Hululit/html/default/pages-profile.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 13 May 2022 22:33:04 GMT -->
<head>

    <meta charset="utf-8" />
    <title>لوحة الطبيب | النقابة العامة للآطباء</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">

    <!-- swiper css -->
    <link rel="stylesheet" href="{{asset('assets/libs/swiper/swiper-bundle.min.css')}}">

    <!-- Layout config Js -->
    <script src="{{asset('assets/js/layout.js')}}"></script>
    <!-- Bootstrap Css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{asset('assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />

    {{-- fontawesome import --}}
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

      {{-- import vite --}}
        @vite(['resources/js/app.js'])
    
        {{-- import vue --}}

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

   <div id="layout-wrapper">

      <div class="main-content" style="margin: 30px; !important;padding:0!important;">

         <div class="page-content p-3" id="app">
             <div class="container-fluid">
                 <div class="profile-foreground position-relative mx-n4 mt-n4">
                     <div class="profile-wid-bg">
                         <img src="/assets/images/hospital.jpg" alt="" class="profile-wid-img" />
                     </div>
                 </div>
                 <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
                    <div class="row">
                        <div class="col-12">
              <img class="logo-light" src="{{ asset('/assets/images/lgmc-light.png?v=2') }}" srcset="{{ asset('/assets/images/lgmc-light.png?v=2') }} 2x" width="200" alt="" />

                        </div>
                    </div>
                     <div class="row g-4">
                         <!--end col-->
                         <div class="col">
                             <div class="p-2">
                                 <h3 class="text-white mb-1">{{auth('doctor')->user()->name}}</h3>
                                 <p class="text-white-75">
                                    {{auth('doctor')->user()->specialization}}
                                 </p>
                                 <div class="hstack text-white-50 gap-1">
                                     <div class="me-2"><i class="ri-map-pin-user-line me-1 text-white-75 fs-16 align-middle"></i>{{auth('doctor')->user()->branch?->name}}</div>
                                 </div>
                                 @if ($errors->any())
                                 @foreach ($errors->all() as $errorMessage)
                                 <div class="alert alert-danger" role="alert">
                                    <small><i class="mx-2 fa fa-exclamation-circle mr-2 "></i> {{$errorMessage}} </small>
                                 </div>
                                 @endforeach
                                 @endif


                                 @if (session()->has('success'))
                                 <div class="alert alert-success" role="alert">
                                    <small><i class="mx-2 fa fa-check-circle mr-2 "></i> {{session()->get('success')}} </small>
                                 </div>
                                 @endif
                             </div>
                         </div>
                      
                         <!--end col-->

                     </div>
                     <!--end row-->
                 </div>
                 @yield('content')


               </div>
            </div>

            
        </div>







      <footer class="footer">
         <div class="container-fluid">
             <div class="row">
                 <div class="col-sm-6">
                     <script>document.write(new Date().getFullYear())</script> © Hululit.
                 </div>
                 <div class="col-sm-6">
                     <div class="text-sm-end d-none d-sm-block">
                         Design & Develop by Themesbrand
                     </div>
                 </div>
             </div>
         </div>
     </footer>

    </div>


    
    
<!-- JAVASCRIPT -->
<script src="{{ asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('assets/js/plugins.js') }}"></script>

<!-- swiper js -->
<script src="{{ asset('assets/libs/swiper/swiper-bundle.min.js') }}"></script>

<!-- profile init js -->
<script src="{{ asset('assets/js/pages/profile.init.js') }}"></script>

<!-- App js -->
<script src="{{ asset('assets/js/app.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[name="passport_number"]').forEach(function (input) {
            input.addEventListener('input', function () {
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            });
        });
    });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[type="date"]').forEach(function (input) {
                input.addEventListener('input', function () {
                    let dateParts = this.value.split('-'); // تقسيم القيمة إلى [السنة، الشهر، اليوم]
                    if (dateParts.length === 3) {
                        this.value = `0000-${dateParts[1]}-${dateParts[2]}`;
                    }
                });
            });
        });
        </script>
        
</body>


<!-- Mirrored from themesbrand.com/Hululit/html/default/pages-profile.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 13 May 2022 22:33:06 GMT -->
</html>