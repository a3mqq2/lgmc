<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نموذج طلب انتساب - {{ $doctor->name }}</title>
    <style>
        @media print {
            html, body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            @page {
                size: A4;
                margin: 0;
            }
            .page-wrapper {
                width: 210mm;
                height: 297mm;
                margin: 0;
                box-shadow: none;
            }
            .print-btn {
                display: none !important;
            }
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: 'Cairo', sans-serif;
            direction: rtl;
            background: #ffffff;
            font-size: 14px;
            line-height: 1.4;
        }

        .page-wrapper {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: white;
            position: relative;
            overflow: hidden;
        }

        .form-container {
            padding: 10mm 10mm 10mm 10mm;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }

        .header .logo-box {
            width: 45mm;
            height: auto;
        }

        .header .logo-box img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        .header .title-box {
            text-align: center;
            flex: 1;
        }

        .header .title-box h1 {
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }

        .header .title-box h2 {
            font-size: 20px;
            margin: 3px 0;
        }

        .header .photo-box {
            width: 30mm;
            height: 40mm;
            border: 1px solid #000;
            overflow: hidden;
            background: #f9f9f9;
        }

        .header .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .header .photo-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #666;
            text-align: center;
        }

        .form-content {
            margin-top: 8px;
            flex: 1;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 8px 0 5px 0;
            color: #333;
        }

        .two-column {
            display: flex;
            gap: 15px;
            margin-bottom: 8px;
        }

        .column {
            flex: 1;
        }

        .form-row {
            display: flex;
            margin-bottom: 6px;
            align-items: center;
            min-height: 22px;
        }

        .form-label {
            font-weight: bold;
            width: 120px;
            flex-shrink: 0;
            font-size: 13px;
        }

        .form-value {
            flex: 1;
            padding: 2px 8px;
            border-bottom: 1px dotted #666;
            min-height: 18px;
            font-size: 13px;
        }

        .form-value:empty::before {
            content: '\00a0';
        }

        .column .form-label {
            width: 90px;
            font-size: 12px;
        }

        .column .form-value {
            font-size: 12px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            gap: 15px;
            font-size: 12px;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .checkbox-box {
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .checkbox-box.checked::after {
            content: '✓';
            font-weight: bold;
            font-size: 10px;
        }

        .info-box {
            border: 1px solid #000;
            padding: 10px;
            margin: 10px 0;
            background: #f5f5f5;
            text-align: justify;
        }

        .info-box h3 {
            margin: 0 0 5px 0;
            text-align: center;
            font-size: 15px;
        }

        .info-box p {
            margin: 0;
            line-height: 1.5;
            font-size: 12px;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            margin-top: 15px;
        }

        .signature-box {
            flex: 1;
            text-align: center;
        }

        .signature-box p {
            margin: 3px 0;
            font-size: 12px;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            margin: 25px 0 3px 0;
        }

        .print-btn {
            position: fixed;
            top: 10px;
            left: 10px;
            padding: 8px 16px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            z-index: 1000;
        }

        .print-btn:hover {
            background: #0056b3;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">طباعة</button>

    <div class="page-wrapper">
        <div class="form-container">
            <div class="header">
                <div class="logo-box">
                    <img src="/assets/images/lgmc-dark.png" alt="شعار">
                </div>
                <div class="title-box">
                    <h1>نقابة أطباء {{ $doctor->branch?->name }}</h1>
                    <h2>( طلب انتساب )</h2>
                </div>
                <div class="photo-box">
                    @if($doctor->files->first())
                        <img src="{{ Storage::url($doctor->files->first()->file_path) }}" alt="صورة">
                    @else
                        <div class="photo-placeholder">صورة<br>شخصية</div>
                    @endif
                </div>
            </div>

            <div class="form-content">
                <div class="section-title">البيانات الشخصية</div>
                <div class="two-column">
                    <div class="column">
                        <div class="form-row">
                            <span class="form-label">الرقم الوطني:</span>
                            <span class="form-value">{{ $doctor->national_number ?? '' }}</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">رقم جواز السفر:</span>
                            <span class="form-value">{{ $doctor->passport_number ?? '' }}</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">الحالة الاجتماعية:</span>
                            @if ($doctor->marital_status)
                                <span class="form-value">{{ $doctor->marital_status?->value == 'single' ? 'أعزب' : ($doctor->marital_status?->value == 'married' ? 'متزوج' : '') }}</span>
                                @else 
                                <span class="form-value"></span>
                            @endif
                        </div>
                    </div>
                    <div class="column">
                        <div class="form-row">
                            <span class="form-label">كود الطبيب:</span>
                            <span class="form-value">{{ $doctor->code }}</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">تاريخ الميلاد:</span>
                            <span class="form-value">{{ $doctor->date_of_birth ? date('Y/m/d', strtotime($doctor->date_of_birth)) : '' }}</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">الجنس:</span>
                            <span class="form-value">{{ $doctor->gender?->value == 'male' ? 'ذكر' : 'أنثى' }}</span>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <span class="form-label">الاسم الكامل:</span>
                    <span class="form-value">{{ $doctor->name }}</span>
                </div>

                <div class="form-row">
                    <span class="form-label">الاسم بالإنجليزية:</span>
                    <span class="form-value">{{ $doctor->name_en ?? '' }}</span>
                </div>

                @if($doctor->type->value === 'libyan' && $doctor->mother_name)
                <div class="form-row">
                    <span class="form-label">اسم الأم:</span>
                    <span class="form-value">{{ $doctor->mother_name }}</span>
                </div>
                @endif

                <div class="section-title">البيانات الأكاديمية</div>
                <div class="two-column">
                    <div class="column">
                        <div class="form-row">
                            <span class="form-label">جامعة البكالوريوس:</span>
                            <span class="form-value">{{ $doctor->handGraduation?->name ?? '' }}</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">تاريخ التخرج:</span>
                            <span class="form-value">{{ $doctor->graduation_date ?? '' }}</span>
                        </div>
                    </div>
                    <div class="column">
                        <div class="form-row">
                            <span class="form-label">جامعة الامتياز:</span>
                            <span class="form-value">{{ $doctor->qualificationUniversity?->name ?? '' }}</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">انتهاء الامتياز:</span>
                            <span class="form-value">{{ $doctor->internership_complete ?? '' }}</span>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <span class="form-label">الدرجة العلمية:</span>
                    <span class="form-value">{{ $doctor->academicDegree?->name ?? '' }}</span>
                </div>

                <div class="section-title">البيانات المهنية</div>
                <div class="checkbox-group">
                    <span class="form-label">الصفة المهنية:</span>
                    <span class="checkbox-container">
                        <span class="checkbox-box {{ $doctor->doctor_rank?->name == 'ممارس عام' ? 'checked' : '' }}"></span>
                        <span>ممارس عام</span>
                    </span>
                    <span class="checkbox-container">
                        <span class="checkbox-box {{ $doctor->doctor_rank?->name == 'طبيب ممارس' ? 'checked' : '' }}"></span>
                        <span>طبيب ممارس</span>
                    </span>
                    <span class="checkbox-container">
                        <span class="checkbox-box {{ $doctor->doctor_rank?->name == 'أخصائي اول ' ? 'checked' : '' }}"></span>
                        <span>أخصائي أول</span>
                    </span>
                    <span class="checkbox-container">
                        <span class="checkbox-box {{ $doctor->doctor_rank?->name == 'أخصائي ثاني' ? 'checked' : '' }}"></span>
                        <span>أخصائي ثاني</span>
                    </span>
                    <span class="checkbox-container">
                        <span class="checkbox-box {{ $doctor->doctor_rank?->name == 'استشاري اول' ? 'checked' : '' }}"></span>
                        <span>استشاري أول</span>
                    </span>
                    <span class="checkbox-container">
                        <span class="checkbox-box {{ $doctor->doctor_rank?->name == 'استشاري ثاني' ? 'checked' : '' }}"></span>
                        <span>استشاري ثاني</span>
                    </span>
                </div>

                <div class="form-row">
                    <span class="form-label">التخصص:</span>
                    <span class="form-value">{{ $doctor->specialization ?? $doctor->specialty1?->name ?? '' }}</span>
                </div>

                <div class="form-row">
                    <span class="form-label">مكان العمل:</span>
                    <span class="form-value">{{ $doctor->institutionObj?->name ?? '' }}</span>
                </div>

                <div class="form-row">
                    <span class="form-label">تاريخ الانتساب:</span>
                    <span class="form-value">{{ $doctor->registered_at ? date('Y/m/d', strtotime($doctor->registered_at)) : '' }}</span>
                </div>

                <div class="section-title">بيانات الاتصال</div>
                <div class="two-column">
                    <div class="column">
                        <div class="form-row">
                            <span class="form-label">رقم الهاتف:</span>
                            <span class="form-value">{{ $doctor->phone }}</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">البريد الإلكتروني:</span>
                            <span class="form-value">{{ $doctor->email }}</span>
                        </div>
                    </div>
                    <div class="column">
                        <div class="form-row">
                            <span class="form-label">رقم الواتساب:</span>
                            <span class="form-value">{{ $doctor->phone_2 ?? '' }}</span>
                        </div>
                        <div class="form-row">
                            <span class="form-label">العنوان:</span>
                            <span class="form-value">{{ $doctor->address ?? '' }}</span>
                        </div>
                    </div>
                </div>

                <div class="info-box">
                    <h3>أقسم بالله العظيم</h3>
                    <p>أن أؤدي عملي بالأمانة والصدق والشرف وأن أحافظ على سر المهنة وأن أحترم تقاليدها وأن أرعى في مهنتي وأن أصون حياة الإنسان في كافة أدوارها وتحت كل الظروف والأحوال باذلا ما في وسعي لاستنقاذها من الهلاك والمرض والألم والقلق وأن أحفظ للناس كرامتهم وأستر عورتهم وأكتم سرهم وأن أكون على الدوام من وسائل رحمة الله باذلا رعايتي للقريب والبعيد والصالح والطالح والصديق والعدو وأن أثابر على طلب العلم وأسخره لنفع الناس لا لأضررهم وأن أوقر من علمني وأن أكون أخا لكل زميل في المهنة.</p>
                </div>

                <div class="signature-section">
                    <div class="signature-box">
                        <p><strong>والله على ما أقول شهيد</strong></p>
                        <div class="signature-line"></div>
                        <p>توقيع مقدم الطلب</p>
                    </div>
                    <div class="signature-box">
                        <p>اسم الموظف المختص</p>
                        <div class="signature-line"></div>
                        <p>التوقيع والختم</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
