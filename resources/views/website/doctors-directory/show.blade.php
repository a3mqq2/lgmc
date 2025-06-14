<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $doctor->name }} - دليل الأطباء</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-primary.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/colors/aqua.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: "Cairo", serif !important; 
            background-color: #f8f9fa;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #cc0100 0%, #9e0302 100%);
            color: white;
            padding: 60px 0 40px;
        }
        
        .doctor-photo-large {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        
        .info-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .info-card .card-header {
            background: linear-gradient(135deg, #cc0100 0%, #9e0302 100%);
            color: white;
            padding: 20px;
            border: none;
        }
        
        .info-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #cc0100;
            min-width: 120px;
            margin-left: 15px;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }
        
        .badge-type {
            font-size: 0.9rem;
            padding: 8px 16px;
        }
        
        .license-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #cc0100;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .license-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .license-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .license-number {
            font-size: 1.2rem;
            font-weight: bold;
            color: #cc0100;
        }
        
        .license-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .license-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .license-detail {
            display: flex;
            align-items: center;
        }
        
        .license-detail i {
            color: #cc0100;
            margin-left: 10px;
            width: 16px;
        }
        
        .back-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(204, 1, 0, 0.3);
        }
        
        @media (max-width: 768px) {
            .profile-header {
                padding: 40px 0 30px;
            }
            
            .doctor-photo-large {
                width: 120px;
                height: 120px;
            }
            
            .info-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 12px 15px;
            }
            
            .info-label {
                min-width: auto;
                margin-left: 0;
                margin-bottom: 5px;
            }
            
            .license-info {
                grid-template-columns: 1fr;
                gap: 10px;
            }
            
            .back-btn {
                bottom: 20px;
                right: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="wrapper bg-dark">
        <nav class="navbar navbar-expand-lg center-nav transparent navbar-dark" dir="rtl">
            <div class="container flex-lg-row flex-nowrap align-items-center">
                <div class="navbar-brand w-100">
                    <a href="/">
                        <img class="logo-dark" src="{{ asset('/assets/images/lgmc-dark.png?v=44') }}" width="200" alt="" />
                        <img class="logo-light" src="{{ asset('/assets/images/lgmc-light.png?v=2') }}" width="200" alt="" />
                    </a>
                </div>
                <div class="navbar-other w-100 d-flex" style="flex-direction: row-reverse!important;">
                    <ul class="navbar-nav flex-row align-items-center">
                        <li class="nav-item">
                            <a href="{{ route('doctors.index') }}" class="btn btn-sm btn-outline-white rounded me-2">
                                <i class="fas fa-arrow-right"></i> العودة للدليل
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/" class="btn btn-sm btn-outline-white rounded">الرئيسية</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Profile Header -->
    <section class="profile-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3 text-center mb-4 mb-md-0">
                    @if($doctor->photo)
                        <img src="{{ $doctor->photo }}" alt="{{ $doctor->name }}" class="doctor-photo-large">
                    @else
                        <div class="doctor-photo-large d-flex align-items-center justify-content-center bg-light text-dark">
                            <i class="fas fa-user-md fa-3x"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-9 text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-5 mb-2 text-white">{{ $doctor->name }}</h1>
                            @if($doctor->name_en)
                                <h3 class="mb-3 opacity-75 text-white">{{ $doctor->name_en }}</h3>
                            @endif
                            <div class="mb-3">
                                <span class="badge badge-type bg-white text-dark me-2">{{ $doctor->code }}</span>
                                <span class="badge badge-type bg-primary">{{ $doctor->type->label() }}</span>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @if($doctor->specialty1)
                                <h4 class="mb-2 text-white">
                                    <i class="fas fa-stethoscope me-2"></i>
                                    {{ $doctor->specialty1->name }}
                                </h4>
                            @endif
                            @if($doctor->doctorRank)
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-medal me-2"></i>
                                    {{ $doctor->doctorRank->name }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Profile Details -->
    <section class="wrapper">
        <div class="container py-5">
            <div class="row">
                <!-- Basic Information -->
                <div class="col-lg-6">
                    <div class="card info-card text-white">
                        <div class="card-header">
                            <h4 class="mb-0 text-white">
                                <i class="fas fa-user me-2"></i>
                                المعلومات الأساسية
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="info-item">
                                <div class="info-label">الاسم:</div>
                                <div class="info-value">{{ $doctor->name }}</div>
                            </div>
                            
                            @if($doctor->name_en)
                                <div class="info-item">
                                    <div class="info-label">الاسم بالإنجليزية:</div>
                                    <div class="info-value">{{ $doctor->name_en }}</div>
                                </div>
                            @endif
                            
                            <div class="info-item">
                                <div class="info-label">الرقم المهني:</div>
                                <div class="info-value"><strong>{{ $doctor->code }}</strong></div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-label">الجنسية :</div>
                                <div class="info-value">
                                 {{ $doctor->country?->nationality_name_ar }}
                                </div>
                            </div>
                            
                            @if($doctor->branch && $doctor->type->value === 'libyan')
                                <div class="info-item">
                                    <div class="info-label">الفرع:</div>
                                    <div class="info-value">{{ $doctor->branch->name }}</div>
                                </div>
                            @endif
                            
                            
                            @if($doctor->email)
                                <div class="info-item">
                                    <div class="info-label">البريد الإلكتروني:</div>
                                    <div class="info-value">
                                        <a href="mailto:{{ $doctor->email }}" class="text-decoration-none">
                                            <i class="fas fa-envelope text-primary me-1"></i>
                                            {{ $doctor->email }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="col-lg-6">
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0 text-white">
                                <i class="fas fa-user-md me-2"></i>
                                المعلومات المهنية
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            @if($doctor->specialty1)
                                <div class="info-item">
                                    <div class="info-label">التخصص:</div>
                                    <div class="info-value">{{ $doctor->specialty1->name }}</div>
                                </div>
                            @endif
                            
                            @if($doctor->doctorRank)
                                <div class="info-item">
                                    <div class="info-label">الصفة المهنية:</div>
                                    <div class="info-value">{{ $doctor->doctorRank->name }}</div>
                                </div>
                            @endif
                            
                            @if($doctor->institutionObj)
                                <div class="info-item">
                                    <div class="info-label">جهة العمل:</div>
                                    <div class="info-value">{{ $doctor->institutionObj->name }}</div>
                                </div>
                            @endif
                            
                            @if($doctor->experience)
                                <div class="info-item">
                                    <div class="info-label">سنوات الخبرة:</div>
                                    <div class="info-value">{{ $doctor->experience }} سنة</div>
                                </div>
                            @endif
                            
                            @if($doctor->registered_at)
                                <div class="info-item">
                                    <div class="info-label">تاريخ الانتساب:</div>
                                    <div class="info-value">{{ date('Y-m-d', strtotime($doctor->registered_at)) }}</div>
                                </div>
                            @endif
                            
                            <div class="info-item">
                                <div class="info-label">حالة العضوية:</div>
                                <div class="info-value">
                                    <span class="badge {{ $doctor->membership_status->badgeClass() }}">
                                        {{ $doctor->membership_status->label() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Licenses Section -->
            @if($doctor->licenses && $doctor->licenses->count() > 0)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0 text-white">
                                <i class="fas fa-certificate me-2"></i>
                                أذونات المزاولة ({{ $doctor->licenses->count() }})
                            </h4>
                        </div>
                        <div class="card-body">
                            @foreach($doctor->licenses as $license)
                                <div class="license-card">
                                    <div class="license-header">
                                        <div class="license-number">
                                            <i class="fas fa-certificate me-2"></i>
                                            رقم الإذن: {{ $license->code }}
                                        </div>
                                        <span class="license-status badge {{ $license->status->badgeClass() }}">
                                            {{ $license->status->label() }}
                                        </span>
                                    </div>
                                    
                                    <div class="license-info">
                                        @if($license->workIn)
                                            <div class="license-detail">
                                                <i class="fas fa-hospital"></i>
                                                <span><strong>المنشأة الطبية:</strong> {{ $license->workIn->name }}</span>
                                            </div>
                                        @endif
                                        
                                        @if($license->issue_date)
                                            <div class="license-detail">
                                                <i class="fas fa-calendar"></i>
                                                <span><strong>تاريخ الإصدار:</strong> {{ date('Y-m-d', strtotime($license->issue_date)) }}</span>
                                            </div>
                                        @endif
                                        
                                        @if($license->expiry_date)
                                            <div class="license-detail">
                                                <i class="fas fa-calendar-times"></i>
                                                <span><strong>تاريخ الانتهاء:</strong> {{ date('Y-m-d', strtotime($license->expiry_date)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Additional Information if available -->
            @if($doctor->address || $doctor->notes)
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0 text-white">
                                <i class="fas fa-info-circle me-2"></i>
                                معلومات إضافية
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            @if($doctor->address)
                                <div class="info-item">
                                    <div class="info-label">العنوان:</div>
                                    <div class="info-value">{{ $doctor->address }}</div>
                                </div>
                            @endif
                            
                            @if($doctor->notes)
                                <div class="info-item">
                                    <div class="info-label">ملاحظات:</div>
                                    <div class="info-value">{{ $doctor->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- Back to Directory Button -->
    <a href="{{ route('doctors.index') }}" class="btn btn-primary back-btn">
        <i class="fas fa-arrow-right me-2"></i>
        العودة للدليل
    </a>

    <!-- Footer -->
    <footer class="bg-dark text-inverse">
        <div class="container pt-10 pb-8">
            <div class="row text-center">
                <div class="col-12">
                    <img class="mb-4" src="{{ asset('/assets/images/lgmc-light.png?v=2') }}" width="200" alt="شعار النقابة العامة للأطباء" />
                    <p class="mb-4">© <script>document.write(new Date().getFullYear());</script> النقابة العامة للأطباء. جميع الحقوق محفوظة.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
</body>
</html>