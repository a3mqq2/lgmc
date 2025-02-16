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

    <style>
      <style>
    .otp-input {
        width: 30px !important;
        height: 50px;
        text-align: center;
        font-size: 20px;
        border: 2px solid #007bff;
        border-radius: 8px;
        margin: 0 5px;
        outline: none;
        font-weight: bold;
    }
    .otp-input:focus {
        border-color: #0056b3;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
</style>

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
                                <form action="{{route('otp.check')}}" method="POST">
                                 @csrf
                                 @method('POST')
                                 <div class="col-lg-12">
                                    <div class="p-lg-5 p-4">
                                        <div style="text-align: center!important;">
                                            <h5 class="text-primary text-center">دخول الأطباء</h5>
                                        </div>

                                        @include('layouts.messages')

                                        <div class="mt-4 text-center">
                                          <p class="text-muted">الرجاء إدخال رمز التحقق المرسل إلى بريدك الإلكتروني:</p>
                                      
                                          <div class="d-flex justify-content-center">
                                              <input type="number" name="otp[]" maxlength="1" class="otp-input form-control p-2 text-center m-2" oninput="moveNext(this, 1)" required>
                                              <input type="number" name="otp[]" maxlength="1" class="otp-input form-control p-2 text-center m-2" oninput="moveNext(this, 2)" required>
                                              <input type="number" name="otp[]" maxlength="1" class="otp-input form-control p-2 text-center m-2" oninput="moveNext(this, 3)" required>
                                              <input type="number" name="otp[]" maxlength="1" class="otp-input form-control p-2 text-center m-2" oninput="moveNext(this, 4)" required>
                                              <input type="number" name="otp[]" maxlength="1" class="otp-input form-control p-2 text-center m-2" oninput="moveNext(this, 5)" required>
                                              <input type="number" name="otp[]" maxlength="1" class="otp-input form-control p-2 text-center m-2" oninput="moveNext(this, 6)" required>
                                          </div>
                                      
                                          <input type="hidden" name="otp_code" id="otp_code">
                                          <input type="hidden" name="email" value="{{request('email')}}">
                                          <button class="btn btn-primary w-100 mt-3" type="submit" onclick="combineOtp()">تأكيد</button>
                                      </div>
                                      
                                    </div>
                                </div>
                                </form>
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

    <script>
      function moveNext(current, nextIndex) {
          let value = current.value;
          if (value.length === 1) {
              let next = document.getElementsByName('otp[]')[nextIndex];
              if (next) next.focus();
          }
          combineOtp();
      }
  
      function combineOtp() {
          let otpArray = document.getElementsByName('otp[]');
          let otpCode = "";
          for (let i = 0; i < otpArray.length; i++) {
              otpCode += otpArray[i].value;
          }
          document.getElementById('otp_code').value = otpCode;
      }
  </script>
  
</body>
</html>