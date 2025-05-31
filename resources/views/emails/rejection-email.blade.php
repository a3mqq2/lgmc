<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رفض طلب التسجيل</title>
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
            color: #dc3545;
            font-size: 22px;
            font-weight: bold;
        }
        p {
            font-size: 16px;
            color: #555;
            margin: 10px 0;
            line-height: 1.7;
        }
        .reason {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            font-size: 16px;
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
        <h2 class="header">عزيزي د. {{ $doctor->name }}, نأسف لإبلاغك</h2>
        <p>بعد مراجعة طلب تسجيلك، نود إبلاغك بأنه لم يتم قبوله للأسباب التالية:</p>

        <div class="reason">
            <strong>سبب الرفض:</strong> {{ $reason }}
        </div>

        <p>إذا كنت تعتقد أن هذا القرار قد تم عن طريق الخطأ، يمكنك التواصل مع إدارة النقابة لمزيد من المعلومات.</p>

        <p class="footer">إذا كنت بحاجة إلى مساعدة، يرجى التواصل معنا عبر البريد الإلكتروني:  
            <a href="mailto:support@example.com">support@example.com</a>
        </p>

        <p class="footer">© {{ date('Y') }} جميع الحقوق محفوظة - النقابة العامة للاطباء - ليبيا</p>
    </div>
</body>
</html>
