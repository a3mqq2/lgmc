<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> تمت الموافقه على طلبك  </title>
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
      
      {{-- تمت الموافقه على طلب الاوراق رقم كذا كذا  --}}
         <p>نود إبلاغك بآن تمت الموافقه على طلب الاوراق رقم  #{{$mail->id}}</p>

         <p>
            وتم اضافة فاتورة قيد الدفع على حسابك في النظام، يرجى التوجه إلى صفحة الفواتير الخاصة بك لدفع المبلغ المستحق. لاتمام عملية الطلب 
            <br>
         </p>

         <p>شكرا لك</p>



        <p class="footer">© {{ date('Y') }} جميع الحقوق محفوظة - النقابة العامة للاطباء - ليبيا</p>
    </div>
</body>
</html>
