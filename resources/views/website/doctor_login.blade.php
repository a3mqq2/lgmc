<!doctype html>
<html lang="ar" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
<head>
    <meta charset="utf-8" />
    <title>دخول الأطباء | بوابة النظام</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="النقابة العامة للاطباء - ليبيا - بوابة النظام" name="description" />
    <meta content="النقابة العامة للاطباء - ليبيا" name="author" />
    <!-- App favicon -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">

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
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4 auth-one-bg h-100">
                                        <div class="bg-overlay"></div>
                                        <div class="position-relative h-100 d-flex flex-column">
                                            <div class="mb-4">
                                                <a href="index.html" class="d-flex justify-content-center">
                                                    <img src="assets/images/logo-light.png" alt="شعار النقابة" height="120">
                                                </a>
                                            </div>
                                            <div class="mt-auto">
                                                <div id="quotesCarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        <button type="button" data-bs-target="#quotesCarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="الشريحة 1"></button>
                                                        <button type="button" data-bs-target="#quotesCarouselIndicators" data-bs-slide-to="1" aria-label="الشريحة 2"></button>
                                                        <button type="button" data-bs-target="#quotesCarouselIndicators" data-bs-slide-to="2" aria-label="الشريحة 3"></button>
                                                    </div>
                                                    <div class="carousel-inner text-center text-white-50 pb-5">
                                                        <div class="carousel-item active">
                                                            <p class="fs-15 fst-italic">"النقابة العامة للأطباء في ليبيا هي الداعم الرئيسي لنا في مسيرتنا المهنية."</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">"الخدمات المقدمة من النقابة تسهم بشكل كبير في تطوير مهاراتنا الطبية."</p>
                                                        </div>
                                                        <div class="carousel-item">
                                                            <p class="fs-15 fst-italic">"نفتخر بالانتماء إلى نقابة تسعى دائمًا إلى تحسين مستوى الرعاية الصحية في ليبيا."</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end carousel -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                                <div class="col-lg-6">
                                    <div class="p-lg-5 p-4">
                                        <div style="text-align: right!important;">
                                            <h5 class="text-primary text-right">دخول الأطباء</h5>
                                            <p class="text-muted">تسجيل الدخول للمتابعة إلى بوابة النظام.</p>
                                        </div>

                                        @include('layouts.messages')

                                        <div class="mt-4">
                                          <form action="{{ route('doctor-auth') }}" method="POST" dir="rtl">
                                              @csrf
                                              @method('POST')
                                              <div class="mb-3">
                                                  <label for="login" class="form-label">بريدك الآلكتروني او رقم الهاتف</label>
                                                  <input type="login" name="login" class="form-control @error('login') is-invalid @enderror" id="login" placeholder="ادخل بريدك الالكتروني" value="{{ old('login') }}">
                                                  @error('login')
                                                  <div class="invalid-feedback text-danger">
                                                      {{ $message }}
                                                  </div>
                                                  @enderror
                                              </div>
                                              <div class="mb-3">
                                                  <label class="form-label" for="password-input">كلمة المرور</label>
                                                  <div class="position-relative auth-pass-inputgroup mb-3">
                                                      <input type="password" name="password" class="form-control pe-5 @error('password') is-invalid @enderror" placeholder="أدخل كلمة المرور" id="password-input">
                                                      <button class="btn btn-link position-absolute start-0 top-0 text-decoration-none text-muted" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                                  </div>
                                                  @error('password')
                                                  <div class="invalid-feedback text-danger">
                                                      {{ $message }}
                                                  </div>
                                                  @enderror
                                              </div>

                                              <div class="form-check">
                                                  <input class="form-check-input" type="checkbox" name="remember" value="1" id="auth-remember-check">
                                                  <label class="form-check-label" for="auth-remember-check">تذكرني</label>
                                              </div>
                                              <div class="mt-4">
                                                  <button class="btn btn-primary w-100" type="submit">تسجيل الدخول</button>
                                              </div>
                                          </form>
                                      </div>
                                      
                                        <div class="mt-5 text-center">
                                            <p class="mb-0">في حال أنك طبيب وليس لديك عضوية، يمكنك <a href="/register" class="fw-semibold text-primary text-decoration-underline">طلب عضوية جديدة من هنا</a>.</p>
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
    <!-- end auth-page-wrapper -->
    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap
::contentReference[oaicite:0]{index=0}
 
