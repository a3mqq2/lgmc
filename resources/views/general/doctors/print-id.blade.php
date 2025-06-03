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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            margin: 0;
            padding: 0;
        }

        .print-card {
            width: 8.5cm;
            height: 5.37cm;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: white;
            position: relative;
            display: flex;
        }

        /* Left side - Photo and Code */
        .photo-section {
            width: 30%;
            background: #dc2626;
            display: flex;
            flex-direction: column;
            padding: 0;
            margin: 0;
        }

        .photo-container {
            flex: 1;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photo-frame {
            width: 70px;
            height: 85px;
            border-radius: 6px;
            overflow: hidden;
            border: 3px solid white;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .code-section {
            background: rgba(0, 0, 0, 0.2);
            color: white;
            text-align: center;
            padding: 6px 4px;
            font-weight: 700;
            font-size: 10px;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        /* Right side - Data */
        .data-section {
            width: 70%;
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
            background: #fef8f8;
            position: relative;
        }

        /* Logo header */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            height: 35px;
        }

        .logo-ar {
            height: 30px;
            width: auto;
        }

        .logo-en {
            height: 25px;
            width: auto;
        }

        /* Info section */
        .info-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
        }

        /* Name styling - Full width */
        .name-section {
            margin-bottom: 8px;
        }

        .name-label {
            color: #dc2626;
            font-size: 10px;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .name-value {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            line-height: 1.2;
        }

        /* Info items */
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 4px;
        }

        .info-label {
            color: #dc2626;
            font-size: 10px;
            font-weight: 500;
            width: 35%;
            flex-shrink: 0;
        }

        .info-value {
            font-size: 11px;
            font-weight: 600;
            color: #374151;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Date styling */
        .date-value {
            font-family: 'Courier New', monospace;
            letter-spacing: 0.5px;
        }

        /* Print specific styles */
        @media print {
            @page {
                margin: 0;
                size: 8.5cm 5.37cm;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .print-card {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .photo-section {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* Screen view only */
        @media screen {
            body {
                background-color: #f5f5f5;
                padding: 20px;
            }

            .print-card {
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }

            .print-button {
                margin-top: 20px;
                background: #dc2626;
                color: white;
                padding: 10px 24px;
                border: none;
                border-radius: 6px;
                font-weight: 600;
                font-size: 14px;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.2s;
            }

            .print-button:hover {
                background: #b91c1c;
                transform: translateY(-1px);
            }
        }

        /* Fine adjustments for text */
        .specialization-text {
            line-height: 1.3;
            font-size: 10px;
        }

        /* Ensure no overflow */
        .print-card * {
            max-width: 100%;
        }
    </style>
</head>
<body>
    <!-- بطاقة عربية -->
    <div class="print-card">
        <!-- الصورة والكود -->
        <div class="photo-section">
            <div class="photo-container">
                <div class="photo-frame">
                    <img src="{{ Storage::url($doctor->files->first()?->file_path) }}" alt="Doctor Photo">
                </div>
            </div>
            <div class="code-section">
                 {{ $doctor->code }}
            </div>
        </div>

        <!-- البيانات -->
        <div class="data-section">
            <!-- Header with logos -->
            <div class="header-section">
                <img src="{{ asset('/assets/images/lgmc-dark.png?v=44') }}" class="logo-ar" alt="LGMC">
                <div style="text-align: left;">
                    
                </div>
            </div>

            <!-- Info section -->
            <div class="info-section">
                <!-- Name - Full width -->
                <div class="name-section">
                    <div class="name-label">الاسم</div>
                    <div class="name-value">{{ $doctor->name }}</div>
                </div>

                <!-- Other info -->
                <div class="info-item">
                    <span class="info-label">الصفة المهنية</span>
                    <span class="info-value specialization-text">
                        {{ $doctor->doctor_rank->name }} {{ $doctor->specialization }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">رقم جواز السفر</span>
                    <span class="info-value">{{ $doctor->passport_number }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">صالحة إلى</span>
                    <span class="info-value date-value">{{ date('Y-m-d', strtotime($doctor->membership_expiration_date)) }}</span>
                </div>
            </div>
        </div>
    </div>


    <div class="print-card">
        <!-- الصورة والكود -->
        <div class="photo-section">
            <div class="photo-container">
                <div class="photo-frame">
                    <img src="{{ Storage::url($doctor->files->first()?->file_path) }}" alt="Doctor Photo">
                </div>
            </div>
            <div class="code-section">
                 {{ $doctor->code }}
            </div>
        </div>

        <!-- البيانات -->
        <div class="data-section">
            <!-- Header with logos -->
            <div class="header-section">
                <img src="{{ asset('/assets/images/lgmc-dark.png?v=44') }}" class="logo-ar" alt="LGMC">
                <div style="text-align: left;">
                    
                </div>
            </div>

            <!-- Info section -->
            <div class="info-section">
                <!-- Name - Full width -->
                <div class="name-section">
                    <div class="name-label">Name</div>
                    <div class="name-value">{{ $doctor->name_en }}</div>
                </div>

                <!-- Other info -->
                <div class="info-item">
                    <span class="info-label">Profession </span>
                    <span class="info-value specialization-text">
                        {{ $doctor->doctor_rank->name_en }} {{ $doctor->specialty1?->name_en }}
                    </span>
                </div>

                <div class="info-item">
                    <span class="info-label">Passport No</span>
                    <span class="info-value">{{ $doctor->passport_number }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Valid to date</span>
                    <span class="info-value date-value">{{ date('Y-m-d', strtotime($doctor->membership_expiration_date)) }}</span>
                </div>
            </div>
        </div>
    </div>

    <button class="print-button no-print" onClick="window.print()">
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z"/>
        </svg>
        طباعة البطاقة
    </button>
</body>
</html>