<!doctype html>
<html  data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

    <meta charset="utf-8" />
    <title> الصفحة الرئيسية | نقابة الاطباء الالكتروني </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('/assets/images/lgmc-dark.png')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Layout config Js -->
    <script src="{{asset('/assets/js/layout.js')}}"></script>
    <link href="{{asset('/css/app.css')}}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Css -->
    <link href="{{asset('/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai&family=Lato:wght@700&display=swap" rel="stylesheet">
    @if(App::isLocale('ar'))
    <link href="{{asset('/assets/css/app-rtl.min.css')}}" rel="stylesheet" type="text/css" />
    @else
    <link href="{{asset('/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    @endif
    <!-- custom Css-->
    <link href="{{asset('/assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />


</head>


<body >
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100" dir="rtl">
        <div class="bg-overlay"></div>
        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden pt-lg-5">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-6">
                        <div class="card overflow-hidden">
                            <div class="row g-0 d-flex justify-content-center">
                                <!-- end col -->

                                <div class="col-lg-12">
                                    <div class="col-md-12 d-flex justify-content-center">
                                        <img src="{{asset('/assets/images/lgmc-dark.png')}}" style="width: 330px;margin: 20px auto;" alt="">
                                    </div>
                                    <div class="" style="padding:10px 71px;">
                                        <div>
                                            <h5 class="text-primary font-weight-bold">الأقسام</h5>
                                            <p class="text-muted">  قم بتحديد قسم للدخول له </p>
                                        </div>
                                        <div class="mt-4">
                                            <div class="row">
                                                @if (auth()->user()->hasRole('general_admin'))
                                                <div class="col-md-12  p-2" style="border-bottom:1px solid #ddd;">
                                                    <a href="{{route('admin.home')}}">
                                                     <div class=" d-flex align-items-center">
                                                         <div class="image mr-3">
                                                             <img src="{{ asset('/assets/images/sections/admin.svg') }}" width="60" class="mr-3" alt="">
                                                         </div>
                                                         <div class="text px-3">
                                                             <h5 class="font-weight-bold text-primary mr-3 mt-1"> الإدارة العامة  </h5>
                                                             <p class="font-weight-bold mr-3 text-dark"> GENERAL MANAGEMENT </p>
                                                         </div>
                                                     </div>
                                                    </a>
                                                 </div>
                                                @endif
                             
                                                 @foreach (auth()->user()->branches as $branch)
                                                 <div class="col-md-12  p-2 mt-2" style="border-bottom:1px solid #ddd;">
                                                    <a href="{{route('user.home', ['branch_id' => $branch->id])}}">
                                                     <div class=" d-flex align-items-center">
                                                         <div class="image mr-3">
                                                             <img src="{{ asset('/assets/images/sections/admin.svg') }}" width="60" class="mr-3" alt="">
                                                         </div>
                                                         <div class="text px-3">
                                                             <h5 class="font-weight-bold text-primary mr-3 mt-1">  {{$branch->name}}  </h5>
                                                             <p class="font-weight-bold mr-3 text-dark"> BRANCH OPERATION   </p>
                                                         </div>
                                                     </div>
                                                    </a>
                                                 </div>
                                                 @endforeach
                                                 


                                                 @if(auth()->user()->permissions()->where('name', 'financial-administration')->count() || auth()->user()->permissions()->where('name', 'financial-branch')->count())
                                                     
                                                 <div class="col-md-12  p-2 mt-2" style="border-bottom:1px solid #ddd;">
                                                    <a href="{{route('finance.home')}}">
                                                     <div class=" d-flex align-items-center">
                                                         <div class="image mr-3">
                                                             <img src="{{ asset('/assets/images/sections/admin.svg') }}" width="60" class="mr-3" alt="">
                                                         </div>
                                                         <div class="text px-3">
                                                             <h5 class="font-weight-bold text-primary mr-3 mt-1"> الإدارة المالية </h5>
                                                             <p class="font-weight-bold mr-3 text-dark">  FINANCE    </p>
                                                         </div>
                                                     </div>
                                                    </a>
                                                 </div>
                                                 @endif



                                                <div class="col-md-12  p-2 mt-3" style="border-bottom:1px solid #ddd;">
                                                    <a href="{{route('logout')}}">
                                                        <div class=" d-flex align-items-center">
                                                            <div class="image mr-3">
                                                                <img src="{{ asset('/assets/images/sections/logout.svg') }}" width="70" class="mr-3" alt="">
                                                            </div>
                                                            <div class="text px-3">
                                                                <h5 class="font-weight-bold text-primary mr-3 mt-1">تسجيل خروج</h5>
                                                                <p class="font-weight-bold mr-3 text-dark">LOGOUT</p>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
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
                                <script>document.write(new Date().getFullYear())</script> تمت برمجة النظام بواسطة  <a href="">شركة حلول لتقنية المعلومات </a>
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="{{asset('/js/app.js')}}"></script>

    <script src="{{asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('/assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('/assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('/assets/js/plugins.js')}}"></script>

    <!-- particles js -->
    <script src="{{asset('/assets/libs/particles.js/particles.js')}}"></script>
    <!-- particles app js -->
    <script src="{{asset('/assets/js/pages/particles.app.js')}}"></script>
    <!-- password-addon init -->
    <script src="{{asset('/assets/js/pages/password-addon.init.js')}}"></script>
</body>



</html>