<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://app.tmc.org.ly/css/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {margin:0;padding:0;box-sizing:border-box;}

        body{font-family:'Cairo',sans-serif;margin:0;padding:0;}

        .print-card{
            width:8.5cm;
            height:5.37cm;
            margin:0;
            padding:0;
            overflow:hidden;
            background:#fff;
            position:relative;
            display:flex;
            flex-direction:row-reverse;      /* الصورة جهة اليسار دائماً للبطاقة العربية */
        }
        /* البطاقة الإنجليزية تبدأ من اليسار إلى اليمين */
        .ltr-card{
            direction:ltr;
            flex-direction:row;              /* الصورة جهة اليسار */
        }

        /* يسار البطاقة – الصورة أو الـ QR والخلفية الحمراء */
        .photo-section{
            width:30%;
            background:#dc2626;
            display:flex;
            flex-direction:column;
            padding:0;
            margin:0;
        }
        .photo-container{
            flex:1;
            padding:10px;
            display:flex;
            align-items:center;
            justify-content:center;
        }
        .photo-frame{
            width:90px;
            height:90px;
            border-radius:6px;
            overflow:hidden;
            border:3px solid #fff;
            background:#fff;
            box-shadow:0 2px 8px rgba(0,0,0,.2);
        }
        .photo-frame img{width:100%;height:100%;object-fit:cover;display:block;}
        .code-section{
            background:rgba(0,0,0,.2);
            color:#fff;
            text-align:center;
            padding:6px 4px;
            font-weight:700;
            font-size:11px;
            letter-spacing:.5px;
            white-space:nowrap;
        }

        /* يمين البطاقة – البيانات */
        .data-section{
            width:70%;
            padding:12px 16px;
            display:flex;
            flex-direction:column;
            background:#fef8f8;
            position:relative;
        }

        /* الشعارات */
        .header-section{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:10px;
            height:40px;
        }
        .logo-ar{height:40px;width:auto;}
        .logo-en{height:35px;width:auto;}

        /* أقسام المعلومات */
        .info-section{
            flex:1;
            display:flex;
            flex-direction:column;
            justify-content:space-around;
        }

        /* سطر كامل (Label فوق Value) */
        .field-section{margin-bottom:6px;}
        .field-label{
            color:#dc2626;
            font-size:11px;
            font-weight:700;
            margin-bottom:2px;
        }
        .field-value{
            font-size:13px;
            font-weight:700;
            color:#374151;
            line-height:1.2;
            overflow:hidden;
            text-overflow:ellipsis;
            white-space:nowrap;
        }

        /* الاسم (نفس تنسيق السطر الكامل) */
        .name-section{margin-bottom:6px;}
        .name-label{
            color:#dc2626;
            font-size:11px;
            font-weight:700;
            margin-bottom:2px;
        }
        .name-value{
            font-size:16px;
            font-weight:700;
            color:#1f2937;
            line-height:1.2;
        }

        .date-value{font-family:'Courier New',monospace;letter-spacing:.5px;}

        /* صف مزدوج لرقم الجواز وتاريخ الانتهاء */
        .dual-field-row{
            display:flex;
            justify-content:space-between;
            gap:8px;
        }
        .dual-field-row .field-section{
            flex:1;
            margin-bottom:0;
        }

        /* طباعة */
        @media print{
            @page{margin:0;size:8.5cm 5.37cm;}
            body{margin:0;padding:0;}
            .print-card{-webkit-print-color-adjust:exact;print-color-adjust:exact;}
            .no-print{display:none!important;}
            .photo-section{-webkit-print-color-adjust:exact;print-color-adjust:exact;}
        }

        /* للعرض على الشاشة فقط */
        @media screen{
            body{background:#f5f5f5;padding:20px;}
            .print-card{border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,.1);}
            .print-button{
                margin-top:20px;
                background:#dc2626;
                color:#fff;
                padding:10px 24px;
                border:none;
                border-radius:6px;
                font-weight:700;
                font-size:14px;
                cursor:pointer;
                display:inline-flex;
                align-items:center;
                gap:8px;
                transition:all .2s;
            }
            .print-button:hover{background:#b91c1c;transform:translateY(-1px);}
        }

        .specialization-text{line-height:1.3;font-size:11px;font-weight:700;}
        .print-card *{max-width:100%;}
    </style>
</head>
<body>

    <!-- البطاقة العربية -->
    <div class="print-card">
        <!-- صورة وكود -->
        <div class="photo-section">
            <div class="photo-container">
                <div class="photo-frame">
                    <img src="{{ Storage::url($doctor->files->first()?->file_path) }}" alt="Doctor Photo">
                </div>
            </div>
            <div class="code-section">{{ $doctor->code }}</div>
        </div>

        <!-- بيانات -->
        <div class="data-section">
            <!-- شعارات -->
            <div class="header-section">
                <img src="{{ asset('/assets/images/lgmc-dark.png?v=44') }}" class="logo-ar" alt="LGMC">
                <div style="text-align:left;"></div>
            </div>

            <!-- معلومات -->
            <div class="info-section">

                <!-- الاسم -->
                <div class="name-section">
                    <div class="name-label">الاسم</div>
                    <div class="name-value">{{ $doctor->name }}</div>
                </div>

                <!-- الصفة المهنية -->
                <div class="field-section">
                    <div class="field-label">الصفة المهنية</div>
                    <div class="field-value specialization-text">
                        {{ $doctor->doctor_rank->name }} / {{ $doctor->specialization }}
                    </div>
                </div>

                <!-- رقم الجواز وتاريخ الانتهاء في نفس السطر -->
                <div class="dual-field-row">
                    <div class="field-section">
                        <div class="field-label">رقم جواز السفر</div>
                        <div class="field-value">{{ $doctor->passport_number }}</div>
                    </div>
                    <div class="field-section">
                        <div class="field-label">صالحة إلى</div>
                        <div class="field-value date-value">
                            {{ date('Y-m-d', strtotime($doctor->membership_expiration_date)) }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- البطاقة الإنجليزية -->
    <div class="print-card ltr-card">
        <!-- QR وكود -->
        <div class="photo-section">
            <div class="photo-container">
                <div class="">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=wwe.example.com" alt="QR Code">
                </div>
            </div>
            <div class="code-section">{{ $doctor->code }}</div>
        </div>

        <!-- بيانات -->
        <div class="data-section">
            <!-- شعارات -->
            <div class="header-section">
                <img src="{{ asset('/assets/images/lgmc-dark.png?v=44') }}" class="logo-en" alt="LGMC">
                <div style="text-align:right;"></div>
            </div>

            <!-- معلومات -->
            <div class="info-section">

                <!-- الاسم -->
                <div class="name-section">
                    <div class="name-label">Name</div>
                    <div class="name-value">{{ $doctor->name_en }}</div>
                </div>

                <!-- الصفة والتخصص -->
                <div class="field-section">
                    <div class="field-label">Profession</div>
                    <div class="field-value specialization-text">
                        {{ $doctor->doctor_rank->name_en }} / {{ $doctor->specialty1?->name_en }}
                    </div>
                </div>

                <!-- Passport No and Valid to date on same line -->
                <div class="dual-field-row">
                    <div class="field-section">
                        <div class="field-label">Passport No</div>
                        <div class="field-value">{{ $doctor->passport_number }}</div>
                    </div>
                    <div class="field-section">
                        <div class="field-label">Valid to date</div>
                        <div class="field-value date-value">
                            {{ date('Y-m-d', strtotime($doctor->membership_expiration_date)) }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- زر الطباعة -->
    <button class="print-button no-print" onClick="window.print()">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"/>
        </svg>
        طباعة البطاقة
    </button>

</body>
</html>
