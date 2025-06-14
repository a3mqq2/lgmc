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
    <!-- /header -->
    <section class="wrapper bg-dark angled lower-start" style="background:rgb(204 1 0) !important">
      <div class="container pt-7 pt-md-11 pb-8">
        <div class="row gx-0 gy-10 align-items-center" id="hero">
          <div class="col-lg-6" data-cues="slideInDown" data-group="page-title" data-delay="600">
            <h3 class="display-2 text-white text-center mb-4">
              مرحبا بكم <br> في الموقع الرسمي النقابة العامة للأطباء
              <br />
              {{-- <span class="typer text-primary text-nowrap" data-delay="100" data-words="تعزيز الرعاية الصحية,دعم الأطباء,تطوير المهنة"></span> --}}
              {{-- <span class="cursor text-primary" data-owner="typer"></span> --}}
            </h3>            
            <p class="lead text-center fs-20 lh-sm text-white mb-7 pe-md-18 pe-lg-0 pe-xxl-15">
              نحن نراجع حلولنا بدقة لضمان دعم جميع مراحل تطورك المهني كطبيب. نسعى لتوفير الموارد والأدوات التي تساعدك في تطوير مهاراتك وتعزيز مسيرتك المهنية.
            </p>
            <div class="text-center m-auto">
              <a href="/register" class="btn btn-lg btn-primary rounded text-center" style="
              background: #9e0302;
              border: 2px solid #890403;
          "> طلب عضوية جديدة    </a>

          <a  href="/doc-login" class="btn btn-lg btn-primary rounded text-center" style="
          background: #1d1111;
          border: 2px solid #890403;
          ">  تسجيل دخول        </a>

            </div>
          </div>
          <!-- /column -->
          <div class="col-lg-5 offset-lg-1 mb-n18 d-flex justify-content-center" data-cues="slideInDown">
            <div class="position-relative">
              <figure class="rounded shadow-lg"><img src="{{ asset('assets/images/hero.jpg') }}" srcset="{{ asset('assets/images/hero.jpg') }} 2x" alt=""></figure>
            </div>
            <!-- /div -->
          </div>
          <!-- /column -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container -->
    </section>




    <section class="wrapper " dir="rtl" style="margin-top: 120px !important;">
      <div class="container py-14 pt-md-17 ">
          <div class="row">
            <div class="col-md-6 text-center">
               <a href="{{route('doctors.index')}}">
                <div class="card border">
                  <div class="card-body">
                      <div class="">
                        <img src="{{asset('assets/stethoscope.png')}}" alt="">
                      </div>
                      <h3 class="text-center"> دليل الأطباء </h3>
                      <p class="text-muted m-0">دليل شامل لجميع اطباء ليبيا وبياناتهم</p>
                  </div>
                 </div>
               </a>
            </div>
            <div class="col-md-6 text-center">
              <a href="{{route('facilities.directory')}}">
                <div class="card border">
                  <div class="card-body">
                      <div class="">
                        <img src="{{asset('assets/hospital-logo.png')}}" alt="">
                      </div>
                      <h3 class="text-center text-primary"> دليل المنشأت الطبية </h3>
                      <p class="text-muted m-0">دليل شامل لجميع  المنشأت الطبية   وبياناتهم</p>
                  </div>
                 </div>
              </a>
           </div>
          </div>
      </div>
    </section>


    <div class="warpper" style="margin-top: 40px;">
      <div class="container pt-7  pb-8" id="about">
          <div class="row gy-10 gy-sm-13 gx-lg-3 align-items-center">
            {{-- <div class="col-md-8 col-lg-6 offset-lg-1 order-lg-2 position-relative">
              <div class="shape rounded-circle bg-line primary rellax w-18 h-18" data-rellax-speed="1" style="top: -2rem; right: -1.9rem;"></div>
              <div class="shape rounded bg-soft-primary rellax d-md-block" data-rellax-speed="0" style="bottom: -1.8rem; left: -1.5rem; width: 85%; height: 90%; "></div>
              <figure class="rounded"><img src="{{ asset('assets/images/img1.jpg') }}" srcset="{{ asset('assets/images/img1.jpg') }} 2x" alt=""></figure>
            </div> --}}
            <!--/column -->
            <div class="col-lg-12" style="text-align: right !important;">
              <h2 class="fs-16 text-uppercase text-line text-primary mb-3">عن النقابة</h2>
              <h3 class="display-4 mb-7">أعرف عن النقابة اكثر</h3>
              <div class="accordion accordion-wrapper" id="accordionExample">
                <div class="card plain accordion-item">
                  <div class="card-header" id="headingOne">
                    <button class="accordion-button text-right" style="text-align: right!important;color:#9e0302" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> تأسيس النقابة ودورها في المجتمع</button>
                  </div>
                  <!--/.card-header -->
                  <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="card-body">
                      <p>النقابة العامة للأطباء هي إحدى مؤسسات المجتمع المدني، تأسست عام 1976م بموجب القانون الليبي، وتضم الأطباء المشتغلين في مجالات الطب البشري وما يتعلق بها. تهدف النقابة إلى حماية أعضائها والدفاع عن حقوقهم المهنية، والمحافظة على شرف المهنة وآدابها، والرفع من الكفاءة المهنية للأعضاء من خلال التدريب وبرامج التعليم الطبي المستمر. 
                      </p>
                    </div>
                    <!--/.card-body -->
                  </div>
                  <!--/.accordion-collapse -->
                </div>
                <!--/.accordion-item -->
                <div class="card plain accordion-item">
                  <div class="card-header" id="headingTwo">
                    <button class="collapsed text-right" style="text-align: right!important;color:#9e0302" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> أهداف النقابة ومهامها الرئيسية </button>
                  </div>
                  <!--/.card-header -->
                  <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="card-body">
                      <p>
                        تسعى النقابة إلى تحقيق عدة أهداف، منها: حماية مصالح الأعضاء والدفاع عن حقوقهم المهنية، رفع الكفاءة المهنية للأعضاء والارتقاء بمستواهم الفني والثقافي، المشاركة مع الجهات المختصة في وضع مشروعات القوانين أو القرارات ذات العلاقة بالمهنة الطبية، تطوير العلاقات مع النقابات الطبية الأخرى المحلية والعربية والدولية، والمساهمة الإيجابية في نشر الوعي الصحي والارتقاء بصحة المجتمع وقايةً وعلاجًا. 
                      </p>
                    </div>
                    <!--/.card-body -->
                  </div>
                  <!--/.accordion-collapse -->
                </div>
              </div>
              <!--/.accordion -->
            </div>
            <!--/column -->
          </div>
          {{-- <div class="row gy-10 gy-sm-13 gx-lg-3 mb-16 mb-md-18 align-items-center" style="margin-top: 50px;">
            <div class="col-md-8 col-lg-6 position-relative" id="goals">
              <div class="shape bg-dot primary rellax w-17 h-21" data-rellax-speed="1" style="top: -2rem; left: -1.9rem;"></div>
              <div class="shape rounded bg-soft-primary rellax d-md-block" data-rellax-speed="0" style="bottom: -1.8rem; right: -1.5rem; width: 85%; height: 90%; "></div>
              <figure class="rounded"><img src="{{ asset('assets/images/img2.jpg') }}" srcset="{{ asset('assets/images/img2.jpg') }} 2x" alt="" /></figure>
            </div> --}}
            <!--/column -->
            <div class="col-lg-12 col-xl-12 text-right mt-3" dir="rtl">
              <h2 class="fs-16 text-uppercase text-line text-primary mb-3" style="text-align: right!important;">أهداف النقابة</h2>
              <p class="mb-7 text-right" style="text-align: right!important;">تسعى النقابة العامة للأطباء في ليبيا إلى تحقيق مجموعة من الأهداف التي تهدف إلى تعزيز مهنة الطب والارتقاء بها، وتشمل:

              </p>
              <div class="d-flex flex-row mb-6">
                <div>
                  <span class="icon btn btn-block btn-soft-primary pe-none" style="margin-left: 20px;
    background: #ffdcdc;
    border: 1px solid #ffb4b4;
    color: #cc0100;"><span class="number fs-18">1</span></span>
                </div>
                <div>
                  <h4 class="mb-1">حماية مصالح الأعضاء</h4>
                  <p class="mb-0"> الدفاع عن حقوق الأطباء المهنية وضمان بيئة عمل مناسبة لهم.</p>
                </div>
              </div>
              <div class="d-flex flex-row mb-6">
                <div>
                  <span class="icon btn btn-block btn-soft-primary pe-none" style="margin-left: 20px;
    background: #ffdcdc;
    border: 1px solid #ffb4b4;
    color: #cc0100;"><span class="number fs-18">2</span></span>
                </div>
                <div>
                  <h4 class="mb-1">رفع الكفاءة المهنية</h4>
                  <p class="mb-0">
                    تنظيم دورات تدريبية وبرامج تعليم طبي مستمر لتطوير مهارات الأطباء.
                  </p>
                </div>
              </div>
              <div class="d-flex flex-row">
                <div>
                  <span class="icon btn btn-block btn-soft-primary pe-none" style="margin-left: 20px;
    background: #ffdcdc;
    border: 1px solid #ffb4b4;
    color: #cc0100;"><span class="number fs-18">3</span></span>
                </div>
                <div>
                  <h4 class="mb-1">المشاركة في التشريعات الصحية</h4>
                  <p class="mb-0">
                    المساهمة مع الجهات المختصة في وضع مشروعات القوانين والقرارات ذات العلاقة بالمهنة.
                  </p>
                </div>
              </div>
            </div>
            <!--/column -->
          </div>
        </div>
    </div>
    <!-- /section -->


    
    <!-- /section -->
    <section class="wrapper image-wrapper" style="background: #cc0100;">
      <div class="container py-18">
        
        <div class="row">
          <div class="col-lg-12 text-center">
            <h2 class="fs-16 text-uppercase text-line text-white mb-3">انضم إلى مجتمعنا الطبي</h2>
            <h3 class="display-4 mb-6 text-white pe-xxl-18">نحن ملتزمون بدعم الأطباء في ليبيا. انضم إلينا عبر بوابة الدخول الخاصة بالأطباء.</h3>
            <a href="/doc-login" class="btn btn-white rounded mb-0 text-nowrap">دخول الأطباء</a>
          </div>
          <!-- /column -->
        </div>

        
        <!-- /.row -->
      </div>
      <!-- /.container -->
    </section>
    <!-- /section -->


    


    <section class="wrapper bg-soft-primary" dir="rtl">
      <div class="container py-14 pt-md-17 ">
        <div class="row gx-lg-8 gx-xl-12 gy-10 gy-lg-0 mb-2 align-items-end">
          <div class="col-lg-4">
            <h2 class="fs-16 text-uppercase text-line text-primary mb-3">إحصائيات القطاع الصحي</h2>
            <h3 class="display-4 mb-0 text-right">نفتخر بإنجازاتنا</h3>
          </div>
          <!-- /column -->
          <div class="col-lg-8 mt-lg-2">
            <div class="row align-items-center counter-wrapper gy-6 text-center">
              <div class="col-md-4">
                <h3 class="counter counter-lg">{{\App\Models\Doctor::count()}} + </h3>
                <p>عدد الأطباء في ليبيا</p>
              </div>
              <!--/column -->
              <div class="col-md-4">
                <h3 class="counter counter-lg">{{\App\Models\MedicalFacility::count()}} + </h3>
                <p>عدد المنشأت الطبية   </p>
              </div>
              <!--/column -->
            </div>
            <!--/.row -->
          </div>
          <!-- /column -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container -->
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
