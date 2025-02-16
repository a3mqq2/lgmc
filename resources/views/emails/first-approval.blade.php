<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الموافقة المبدئية</title>
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
        <h2 class="header">مرحبًا د. {{ $doctor->name }}</h2>
        <p>نود إبلاغك بأن تسجيلك قد حصل على الموافقة المبدئية من نقابة الأطباء الليبية.</p>
        <p>
          نأمل منك الحضور يوم {{ $doctor->visiting_date }} للفرع المسجل به للإستكمال بيانات التسجيل 
        </p>


        <p class="footer">إذا كنت بحاجة إلى مساعدة، يرجى التواصل معنا عبر البريد الإلكتروني:  
            <a href="mailto:info@lgmc.ly">info@lgmc.ly</a>
        </p>

        <p class="footer">© {{ date('Y') }} جميع الحقوق محفوظة - نقابة الأطباء الليبية</p>
    </div>
</body>
</html>
