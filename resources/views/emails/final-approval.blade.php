<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الموافقة النهائية</title>
    <style>
        body {
            font-family: 'Tajawal', Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            direction: rtl;
            padding: 30px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            color: #007bff;
            font-size: 22px;
            font-weight: bold;
        }
        p {
            font-size: 16px;
            color: #555;
            margin: 10px 0;
            line-height: 1.7;
        }
        .btn {
            background: #007bff;
            color: #fff;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="header">تهانينا د. {{ $doctor->name }}</h2>
        <p>لقد تمت الموافقة النهائية على تسجيلك بنجاح في نقابة الأطباء الليبية.</p>
        <p>يمكنك الآن تسجيل الدخول إلى حسابك والبدء في الاستفادة من خدمات النظام.</p>
        <p>انت الان مسجل تحت رقم العضوية :  {{ $doctor->code }}  </p>
        
        <a href="{{ route('doctor-login') }}" class="btn">تسجيل الدخول</a>

        <p class="footer">إذا كنت بحاجة إلى مساعدة، يرجى التواصل معنا عبر البريد الإلكتروني:  
            <a href="mailto:info@lgmc.ly">info@lgmc.ly</a>
        </p>

        <p class="footer">© {{ date('Y') }} جميع الحقوق محفوظة - نقابة الأطباء الليبية</p>
    </div>
</body>
</html>
