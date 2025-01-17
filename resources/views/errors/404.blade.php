<!doctype html>
<html lang="ar" dir="rtl" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

    <meta charset="utf-8" />
    <title>نقابة الاطباء | صفحة غير موجودة</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="قالب لوحة تحكم وإدارة متعددة الاستخدامات" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-primary.png') }}">

    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet" />

    <!-- Layout config Js -->
    <script src="{{ asset('assets/js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('assets/css/custom.min.css') }}" rel="stylesheet" type="text/css" />

</head>

<body>

    <!-- auth-page wrapper -->
    <div class="auth-page-wrapper py-5 d-flex justify-content-center align-items-center min-vh-100">

        <!-- auth-page content -->
        <div class="auth-page-content overflow-hidden p-0">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-8">
                        <div class="text-center">
                            <!-- Animated 404 Image -->
                            <img src="{{ asset('assets/images/404.jpg') }}" alt="صورة خطأ" class="img-fluid animate__animated animate__fadeInDown">
                            
                            <!-- Animated Text -->
                            <div class="mt-3">
                                <h3 class="text-uppercase animate__animated animate__fadeInUp">عذراً، الصفحة غير موجودة</h3>
                                <p class="text-muted mb-4 animate__animated animate__fadeInUp animate__delay-1s">الصفحة التي تبحث عنها غير متوفرة!</p>
                                
                                <!-- Animated Button -->
                                <a href="{{ route('admin.home') }}" class="btn btn-primary animate__animated animate__bounceIn">
                                    <i class="mdi mdi-home me-1"></i>العودة إلى الصفحة الرئيسية
                                </a>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth-page content -->
    </div>
    <!-- end auth-page-wrapper -->

</body>

</html>
