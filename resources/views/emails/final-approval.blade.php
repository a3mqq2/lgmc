<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الموافقة النهائية - النقابة العامة للأطباء</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap');

        body{
            font-family:'Tajawal',Arial,sans-serif;
            background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
            text-align:center;
            direction:rtl;
            padding:20px;
            margin:0;
            min-height:100vh
        }
        .email-wrapper{
            max-width:650px;
            margin:0 auto;
            background:#fff;
            border-radius:15px;
            box-shadow:0 15px 35px rgba(0,0,0,.1);
            overflow:hidden
        }
        .header-section{
            background:linear-gradient(45deg,#007bff,#0056b3);
            color:#fff;
            padding:30px 25px;
            position:relative
        }
        .header-section::before{
            content:'';
            position:absolute;
            top:0;left:0;right:0;bottom:0;
            background:url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="70" r="1" fill="rgba(255,255,255,0.1)"/></svg>')
        }
        .logo-container{margin:0 auto 15px;position:relative;z-index:1}
        .logo-image{
            width:80px;height:80px;
            background:#fff;border-radius:50%;
            margin:0 auto;
            display:flex;align-items:center;justify-content:center;
            box-shadow:0 4px 15px rgba(0,0,0,.1)
        }
        .logo-image img{width:60px;height:60px;object-fit:contain}
        .organization-name{font-size:18px;font-weight:500;margin:0;position:relative;z-index:1}
        .main-content{padding:40px 30px}
        .greeting{
            color:#2c3e50;font-size:24px;font-weight:600;
            margin-bottom:25px;
            display:flex;align-items:center;justify-content:center;gap:10px
        }
        .success-icon{
            width:30px;height:30px;background:#28a745;border-radius:50%;
            display:flex;align-items:center;justify-content:center;
            color:#fff;font-size:16px
        }
        .approval-notice{
            background:linear-gradient(135deg,#d4edda,#c3e6cb);
            border:2px solid #28a745;
            border-radius:10px;
            padding:20px;
            margin:25px 0;
            position:relative
        }
        .approval-notice::before{
            content:'✓';
            position:absolute;
            top:-10px;right:20px;
            background:#28a745;color:#fff;
            width:25px;height:25px;border-radius:50%;
            display:flex;align-items:center;justify-content:center;font-weight:bold
        }
        .approval-text{font-size:18px;font-weight:600;color:#155724;margin:0}
        .content-text{font-size:16px;color:#555;margin:20px 0;line-height:1.8}
        .membership-info{
            background:#e3f2fd;
            border-right:4px solid #2196f3;
            border-radius:10px;
            padding:20px;
            margin:25px 0
        }
        .membership-info h3{
            margin:0 0 10px 0;font-size:18px;color:#1976d2;font-weight:600
        }
        .membership-code{
            font-size:20px;font-weight:700;color:#dc3545;margin:0
        }
        .login-btn{
            background:#007bff;color:#fff;
            padding:12px 30px;
            font-size:18px;
            border-radius:5px;
            text-decoration:none;
            display:inline-block;
            margin:25px 0 15px 0
        }
        .login-btn:hover{background:#0056b3}
        .contact-section{
            background:#f8f9fa;
            padding:25px;
            border-top:1px solid #e9ecef;
            text-align:center
        }
        .contact-info{color:#6c757d;font-size:14px;margin:10px 0}
        .contact-link{color:#007bff;text-decoration:none;font-weight:500}
        .contact-link:hover{text-decoration:underline}
        .footer{
            background:#343a40;color:#fff;
            padding:20px;font-size:13px
        }
        @media(max-width:600px){
            body{padding:10px}
            .main-content{padding:25px 20px}
            .greeting{font-size:20px}
            .approval-text{font-size:16px}
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="header-section">
            <div class="logo-container">
                <div class="logo-image">
                    <img src="{{ asset('/assets/images/lgmc-dark.png') }}" alt="" height="50">
                </div>
            </div>
            <p class="organization-name">النقابة العامة للأطباء - ليبيا</p>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2 class="greeting">
                <span class="success-icon">✓</span>
                تهانينا د. {{ $doctor->name }}
            </h2>

            <div class="approval-notice">
                <p class="approval-text">تمت الموافقة النهائية على تسجيلك بنجاح</p>
            </div>

            <p class="content-text">
                يسعدنا إبلاغكم بأن تسجيلكم في النقابة العامة للأطباء قد اكتمل بنجاح. يمكنكم الآن تسجيل الدخول إلى حسابكم والاستفادة من كافة خدمات النظام.
            </p>

            <div class="membership-info">
                <h3>رقم العضوية الخاص بك</h3>
                <p class="membership-code">{{ $doctor->code }}</p>
            </div>

            <a href="{{ route('doctor-login') }}" class="login-btn">تسجيل الدخول</a>
        </div>

        <!-- Contact -->
        <div class="contact-section">
            <p class="contact-info">إذا كنت بحاجة إلى مساعدة، يرجى التواصل معنا عبر البريد الإلكتروني</p>
            <p class="contact-info">
                <a href="mailto:info@lgmc.ly" class="contact-link">info@lgmc.ly</a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} جميع الحقوق محفوظة - النقابة العامة للأطباء - ليبيا</p>
            <p>هذه رسالة تلقائية، يرجى عدم الرد عليها مباشرة.</p>
        </div>
    </div>
</body>
</html>
