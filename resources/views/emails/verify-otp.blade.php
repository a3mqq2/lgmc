<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق OTP</title>
    <style>
        body {
            font-family: 'Tajawal', Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            direction: rtl;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .logo {
            margin-bottom: 20px;
        }
        h2 {
            color: #333;
            font-size: 22px;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            color: #555;
            margin: 10px 0;
            line-height: 1.7;
        }
        .otp-box {
            font-size: 26px;
            font-weight: bold;
            background: #007bff;
            color: #ffffff;
            padding: 12px 25px;
            display: inline-block;
            margin: 15px 0;
            border-radius: 8px;
            letter-spacing: 4px;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
        .footer a {
            color: #007bff;
            text-decoration: none;
        }
        .contact {
            margin-top: 20px;
            font-size: 14px;
            color: #444;
        }
        .contact a {
            color: #28a745;
            font-weight: bold;
            text-decoration: none;
        }
        .footer-note {
            margin-top: 15px;
            font-size: 13px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Logo -->
        <div class="logo">
            <img src="{{ asset('/assets/images/lgmc-dark.png?v=44') }}" alt="LGMC" height="50">
        </div>

        <h2>مرحبًا بك في نظامنا</h2>
        <p>نشكرك على التسجيل! لاكمال عملية التسجيل، يرجى استخدام رمز التحقق التالي:</p>

        <div class="otp-box">{{ $otp }}</div>

        <p>هذا الرمز صالح لمدة <strong>30 دقائق</strong>. يرجى عدم مشاركته مع أي شخص.</p>

        <p class="contact">إذا لم تطلب هذا الرمز أو كنت بحاجة إلى مساعدة، يرجى التواصل معنا عبر البريد الإلكتروني:  
            <a href="mailto:info@lgmc.ly">info@lgmc.ly</a>
        </p>

        <p class="footer-note">© {{ date('Y') }} جميع الحقوق محفوظة - LGMC</p>
    </div>
</body>
</html>
