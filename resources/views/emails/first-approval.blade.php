<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الموافقة المبدئية - النقابة العامة للأطباء</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap');
        
        body {
            font-family: 'Tajawal', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            text-align: center;
            direction: rtl;
            padding: 20px;
            margin: 0;
            min-height: 100vh;
        }
        
        .email-wrapper {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header-section {
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            padding: 30px 25px;
            position: relative;
        }
        
        .header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="70" r="1" fill="rgba(255,255,255,0.1)"/></svg>');
        }
        
        .logo-container {
            margin: 0 auto 15px;
            position: relative;
            z-index: 1;
        }
        
        .logo-image {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .logo-image img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        
        .organization-name {
            font-size: 18px;
            font-weight: 500;
            margin: 0;
            position: relative;
            z-index: 1;
        }
        
        .main-content {
            padding: 40px 30px;
        }
        
        .greeting {
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .success-icon {
            width: 30px;
            height: 30px;
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }
        
        .approval-notice {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            position: relative;
        }
        
        .approval-notice::before {
            content: '✓';
            position: absolute;
            top: -10px;
            right: 20px;
            background: #28a745;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .approval-text {
            font-size: 18px;
            font-weight: 600;
            color: #155724;
            margin: 0;
        }
        
        .content-text {
            font-size: 16px;
            color: #555;
            margin: 20px 0;
            line-height: 1.8;
        }
        
        .important-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            border-right: 4px solid #f39c12;
        }
        
        .important-notice h3 {
            color: #856404;
            font-size: 18px;
            margin: 0 0 15px 0;
            font-weight: 600;
        }
        
        .requirements-list {
            text-align: right;
            color: #856404;
            font-size: 15px;
            line-height: 1.6;
        }
        
        .requirements-list li {
            margin: 8px 0;
            padding-right: 20px;
            position: relative;
        }
        
        .requirements-list li::before {
            content: '📋';
            position: absolute;
            right: 0;
            top: 0;
        }
        
        .visit-info {
            background: #e3f2fd;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            border-right: 4px solid #2196f3;
        }
        
        .visit-date {
            font-size: 20px;
            font-weight: 600;
            color: #1976d2;
            margin: 10px 0;
        }
        
        .contact-section {
            background: #f8f9fa;
            padding: 25px;
            border-top: 1px solid #e9ecef;
            text-align: center;
        }
        
        .contact-info {
            color: #6c757d;
            font-size: 14px;
            margin: 10px 0;
        }
        
        .contact-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        
        .contact-link:hover {
            text-decoration: underline;
        }
        
        .footer {
            background: #343a40;
            color: #ffffff;
            padding: 20px;
            font-size: 13px;
        }
        
        .divider {
            height: 2px;
            background: linear-gradient(to left, #007bff, #0056b3);
            margin: 30px 0;
            border-radius: 1px;
        }
        
        @media (max-width: 600px) {
            body { padding: 10px; }
            .main-content { padding: 25px 20px; }
            .greeting { font-size: 20px; }
            .approval-text { font-size: 16px; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header Section -->
        <div class="header-section">
            <div class="logo-container">
                <div class="logo-image">
                    <img src="{{asset('/assets/images/lgmc-dark.png')}}" alt="" height="50">
                </div>
            </div>
            <p class="organization-name">النقابة العامة للأطباء - ليبيا</p>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2 class="greeting">
                <span class="success-icon">✓</span>
                مرحبًا د. {{ $doctor->name }}
            </h2>

            <div class="approval-notice">
                <p class="approval-text">تمت الموافقة على طلبك بشكل مبدئي</p>
            </div>

            <p class="content-text">
                يسعدنا إبلاغكم بأن طلب التسجيل المقدم من قبلكم قد حصل على الموافقة المبدئية من النقابة العامة للأطباء في ليبيا.
            </p>

            <div class="divider"></div>

            <div class="visit-info">
                <h3 style="color: #dc3545; margin: 0 0 15px 0; font-size: 18px;">المبلغ المطلوب للدفع</h3>
                <p style="color: #dc3545; font-weight: 600; font-size: 16px; margin: 15px 0;">
                    إجمالي المبلغ المطلوب: {{ number_format($invoice->amount, 2) }} دينار ليبي
                </p>
                <p style="color: #555; margin: 15px 0; font-size: 14px;">يرجى الحضور إلى الفرع المسجل به لاستكمال إجراءات التسجيل والدفع</p>
            </div>

            <div class="important-notice">
                <h3>المستندات المطلوبة للإحضار</h3>
                <ul class="requirements-list">
                    <li>الهوية الشخصية الأصلية وصورة منها</li>
                    <li>شهادة التخرج الأصلية ومصدقة</li>
                    <li>شهادة الخبرة العملية (إن وجدت)</li>
                    <li>صور شخصية حديثة (4 صور)</li>
                    <li>إيصال دفع الرسوم المقررة (حسب الفواتير المستحقة أعلاه)</li>
                    <li>أي مستندات إضافية حسب الشروط المحددة</li>
                </ul>
            </div>

            <p class="content-text" style="color: #dc3545; font-weight: 500;">
                <strong>تنبيه مهم:</strong> يجب دفع المبالغ المستحقة المذكورة أعلاه وإحضار جميع المستندات المطلوبة لتجنب تأخير عملية التسجيل.
            </p>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <p class="contact-info">
                للاستفسارات أو المساعدة، يرجى التواصل معنا
            </p>
            <p class="contact-info">
                البريد الإلكتروني: <a href="mailto:info@lgmc.org.ly" class="contact-link">info@lgmc.org.ly</a>
            </p>
            <p class="contact-info">
                أو زيارة موقعنا الإلكتروني للمزيد من المعلومات
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} جميع الحقوق محفوظة - النقابة العامة للأطباء - ليبيا</p>
            <p>هذه رسالة تلقائية، يرجى عدم الرد عليها مباشرة</p>
        </div>
    </div>
</body>
</html>