<!DOCTYPE html>
<html lang="ar">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="النقابة العامة للأطباء في ليبيا هي الجهة المسؤولة عن تنظيم شؤون الأطباء وتعزيز مهنة الطب، وتقديم الخدمات والدعم للأطباء الليبيين لضمان أفضل مستوى للرعاية الصحية في البلاد.">
  <meta name="keywords" content="النقابة العامة للأطباء, الأطباء في ليبيا, نقابة الأطباء ليبيا, الخدمات الطبية, الرعاية الصحية, نظام الأطباء, الأطباء الليبيون, ليبيا, الطب في ليبيا, نقابة طبية, الصحة في ليبيا">
  <meta name="author" content="Hulul Information Technology">
  <title>موقع النقابة العامة للآطباء</title>
  <link rel="shortcut icon" href="{{ asset('assets/images/logo-primary.png') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/colors/aqua.css') }}">
  <link rel="preload" href="{{ asset('assets/css/fonts/thicccboi.css') }}" as="style" onload="this.rel='stylesheet'">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

<style>
  body 
  {
    font-family: "Cairo", serif !important;
  }
</style>

</head>

<body>
  <div class="content-wrapper">
    <header class="wrapper bg-dark">
      <nav class="navbar navbar-expand-lg center-nav transparent navbar-dark" dir="rtl">
        <div class="container flex-lg-row flex-nowrap align-items-center">
          <div class="navbar-brand w-100">
            <a href="/">
              <img class="logo-dark" src="{{ asset('/assets/images/lgmc-dark.png?v=44') }}" srcset="{{ asset('/assets/images/lgmc-dark.png?v=44') }} 2x" width="200" alt="" />
              <img class="logo-light" src="{{ asset('/assets/images/lgmc-light.png?v=2') }}" srcset="{{ asset('/assets/images/lgmc-light.png?v=2') }} 2x" width="200" alt="" />
            </a>
          </div>
          <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
            <div class="offcanvas-header d-lg-none">
              <h3 class="text-white fs-30 mb-0">النقابة العامة للآطباء</h3>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body ms-lg-auto d-flex flex-column h-100">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link" href="#hero">الصفحة الرئيسية</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#about"> عن النقابة </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#goals"> اهداف النقابة </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#services">  الخدمات المقدمة  </a>
                </li>
              </ul>
              <!-- /.navbar-nav -->
              <div class="offcanvas-footer d-lg-none">
                <div>
                  <a href="mailto:first.last@email.com" class="link-inverse">info.lgmc.ly</a>
                  <br /> 0912128066 <br />
                  <nav class="nav social social-white mt-4">
                    <a href="#"><i class="uil uil-facebook-f"></i></a>
                  </nav>
                  <!-- /.social -->
                </div>
              </div>
              <!-- /.offcanvas-footer -->
            </div>
            <!-- /.offcanvas-body -->
          </div>
          <!-- /.navbar-collapse -->
          <div class="navbar-other w-100 d-flex" style="flex-direction: row-reverse!important;">
            <ul class="navbar-nav flex-row align-items-center">
              <li class="nav-item d-none d-md-block">
                <a href="{{ url('https://www.facebook.com/libyandoctorssyndicate?locale=ar_AR') }}" class="btn btn-sm btn-primary rounded"
                 style="    background: #fff;
                 color: #000;
                 border: cornsilk;">تواصل معنا</a>
              </li>
              <li class="nav-item d-lg-none">
                <button class="hamburger offcanvas-nav-btn"><span></span></button>
              </li>
            </ul>
            <!-- /.navbar-nav -->
          </div>
          <!-- /.navbar-other -->
        </div>
        <!-- /.container -->
      </nav>
      <!-- /.navbar -->
    </header>





    <section class="wrapper" dir="rtl">
      <div class="container py-14 pt-md-17">
        <div class="text-center mb-5">
          <img src="{{asset('/assets/images/shield.png')}}" width="80" alt="">
          <h2 class="fs-16 text-primary mb-1">تحقق من بيانات الطبيب</h2>
          <h3 class="display-4 text-danger">التحقق من طبيب</h3>
        </div>

        <form action="">
          <label for="">كود الطبيب</label>
          <input type="text" name="licence" value="{{ request('licence') }}" class="form-control" placeholder="أدخل كود الطبيب">
          <button class="btn btn-primary mt-3 text-light">تحقق</button>
        </form>

        @if (request('licence'))
          @if ($doctor)
            <div class="card mt-4">
              <div class="card-body">
                <table class="table table-bordered">
                  <tr>
                    <th><strong class="text-danger">الكود:</strong> {{ $doctor->code }}</th>
                  </tr>
                  <tr>
                    <th><strong class="text-danger">الطبيب:</strong> {{ $doctor->name }}</th>
                  </tr>
                  <tr>
                    <th><strong class="text-danger">الصفة:</strong> {{ $doctor->doctor_rank?->name ?? '-' }}</th>
                  </tr>
                  <tr>
                    <th><strong class="text-danger">التخصص:</strong> {{ $doctor->specialization ?? '-' }}</th>
                  </tr>
                  <tr>
                    <th><strong class="text-danger">حالة العضوية:</strong> 
                      <span class="badge {{ $doctor->membership_status->badgeClass() }}">
                        {{ $doctor->membership_status->label() }}
                      </span>
                    </th>
                  </tr>
                </table>
              </div>
            </div>

            <div class="card mt-4 p-3">
              <h5>آخر الأذونات السارية للطبيب:</h5>
          
              @php
                  $activeLicences = $doctor->licenses()
                      ->where('status', 'active')
                      ->where('expiry_date', '>=', now())
                      ->get();
              @endphp

              @if ($activeLicences->count())
                  <ul>
                      @foreach ($activeLicences as $item)
                          <li>
                              إذن رقم: <strong>{{ $item->code }}</strong> -
                              ساري من <strong>{{ $item->issued_date }}</strong> إلى <strong>{{ $item->expiry_date }}</strong>
                          </li>
                      @endforeach
                  </ul>
              @else
                  <p class="text-danger">لا يوجد أي إذن ساري لهذا الطبيب.</p>
              @endif
          </div>
          
          @else
            <h4 class="text-center text-danger mt-5">لم يتم العثور على طبيب بهذا الكود</h4>
          @endif
        @endif
      </div>


    </section>
    
    
    <!-- /section -->
    
    <!-- /section -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="bg-dark text-inverse" >
    <div class="container pt-17 pt-md-19 pb-13 pb-md-15">
      <div class="row gy-6 gy-lg-0">
        <div class="col-md-4 col-lg-3">
          <div class="widget">
            <img class="mb-4" src="{{ asset('/assets/images/lgmc-light.png?v=2') }}" srcset="{{ asset('/assets/images/lgmc-light.png?v=2') }} 2x" width="300" alt="شعار النقابة العامة للأطباء" />
            <p class="mb-4">© <script>document.write(new Date().getFullYear());</script> النقابة العامة للأطباء. <br class="d-none d-lg-block" />جميع الحقوق محفوظة.</p>
            <nav class="nav social social-white">
              <a href="https://www.facebook.com/libyandoctorssyndicate"><i class="uil uil-facebook-f"></i> صفحة الفيسبوك</a>
            </nav>
            <!-- /.social -->
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
        <div class="col-md-4 col-lg-3">
          <div class="widget">
            <h4 class="widget-title text-white mb-3">تواصل معنا</h4>
            <address class="pe-xl-15 pe-xxl-17">شارع جرابة، طرابلس، ليبيا</address>
            <a href="mailto:info@lgmc.ly">info@lgmc.ly</a><br /> هاتف: 0912128066 
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
        <div class="col-md-4 col-lg-3">
          <div class="widget">
            <h4 class="widget-title text-white mb-3">روابط سريعة</h4>
            <ul class="list-unstyled  mb-0">
              <li><a href="#about">عن النقابة</a></li>
              <li><a href="#services">الخدمات المقدمة</a></li>
              <li><a href="#contact">اتصل بنا</a></li>
            </ul>
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
        <div class="col-md-12 col-lg-3">
          <div class="widget">
            <div class="newsletter-wrapper">
              <!-- Begin Mailchimp Signup Form -->
              <div id="mc_embed_signup2">
                <form action="https://lgmc.us20.list-manage.com/subscribe/post?u=example&amp;id=example" method="post" id="mc-embedded-subscribe-form2" name="mc-embedded-subscribe-form" class="validate dark-fields" target="_blank" novalidate>
                  <div id="mc_embed_signup_scroll2">
                    <div id="mce-responses2" class="clear">
                      <div class="response" id="mce-error-response2" style="display:none"></div>
                      <div class="response" id="mce-success-response2" style="display:none"></div>
                    </div>
                    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_example_example" tabindex="-1" value=""></div>
                    <div class="clear"></div>
                  </div>
                </form>
              </div>
              <!--End mc_embed_signup-->
            </div>
            <!-- /.newsletter-wrapper -->
          </div>
          <!-- /.widget -->
        </div>
        <!-- /column -->
      </div>
      <!--/.row -->
    </div>
    <!-- /.container -->
  </footer>
  
  <div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
      <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
  </div>
  <script src="{{ asset('assets/js/plugins.js') }}"></script>
  <script src="{{ asset('assets/js/theme.js') }}"></script>
</body>

</html>
