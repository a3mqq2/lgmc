<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>قبول تسجيلك وإصدار فاتورة</title>
  <style>
    body { font-family: 'Tajawal', Arial, sans-serif; background:#f8f9fa; direction:rtl; text-align:center; padding:30px; }
    .container { max-width:600px; margin:0 auto; background:#fff; padding:25px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
    .header { color:#28a745; font-size:22px; font-weight:bold; }
    p { font-size:16px; color:#555; margin:10px 0; line-height:1.7; }
    .invoice { background:#d4edda; color:#155724; padding:15px; border-radius:8px; margin:15px 0; }
    .btn { display:inline-block; padding:10px 20px; background:#28a745; color:#fff; border-radius:5px; text-decoration:none; margin-top:10px; }
    .footer { margin-top:20px; font-size:14px; color:#777; }
  </style>
</head>
<body>
  <div class="container">
    <h2 class="header">عزيزي د. {{ $doctor->name }}</h2>
    <p>نشكرك على إكمال خطوات التسجيل، ويسرّنا إبلاغك بأن طلبك قد تم قبوله بنجاح.</p>


    <p class="footer">© {{ date('Y') }} النقابة العامة للاطباء - ليبيا. جميع الحقوق محفوظة.</p>
  </div>
</body>
</html>
