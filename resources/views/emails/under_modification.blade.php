<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> طلبك تحت التعديل الان  </title>
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
        <h2 class="header">عزيزي د. {{ $mail->doctor->name }} نود إبلاغك</h2>
      
      
         <p>نود إبلاغك بآن طلبك رقم  #{{$mail->id}}يحتاج الى مراجعة من طرفكم  الان</p>
         <p>
            تم اضافة ملاحظات على الطلب من قبل قسم المراجعة، يرجى مراجعة الطلب والقيام بالتعديلات اللازمة.

            <br>
          </p>


            <p>يمكنك الدخول إلى حسابك في النظام لمراجعة الطلب والتعديلات المطلوبة.</p>

            <p>إذا كان لديك أي استفسارات، لا تتردد في التواصل معنا.</p>
            <br>
            <p>نحن هنا لمساعدتك في أي وقت.</p>
            <br>
            <p>يرجى ملاحظة أن الطلب لن يتم معالجته حتى يتم إجراء التعديلات المطلوبة.</p>

            
         <p>شكرا لك</p>



        <p class="footer">© {{ date('Y') }} جميع الحقوق محفوظة - النقابة العامة للاطباء - ليبيا</p>
    </div>
</body>
</html>
