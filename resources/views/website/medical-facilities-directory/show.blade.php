<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $facility->name }} - دليل المنشآت الطبية</title>
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
            position: relative;
            overflow: hidden;
        }
        
        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }
        
        .facility-image-large {
            width: 200px;
            height: 200px;
            border-radius: 15px;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
        }
        
        .facility-image-large:hover {
            transform: scale(1.05);
        }
        
        .no-image-placeholder {
            width: 200px;
            height: 200px;
            border-radius: 15px;
            border: 5px solid white;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
        
        .info-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-5px);
        }
        
        .info-card .card-header {
            background: linear-gradient(135deg, #cc0100 0%, #9e0302 100%);
            color: white;
            padding: 20px;
            border: none;
            position: relative;
        }
        
        .info-card .card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        }
        
        .info-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease;
        }
        
        .info-item:hover {
            background-color: #f8f9fa;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #cc0100;
            min-width: 140px;
            margin-left: 15px;
            display: flex;
            align-items: center;
        }
        
        .info-label i {
            margin-left: 8px;
            width: 16px;
        }
        
        .info-value {
            flex: 1;
            color: #333;
        }
        
        .badge-type {
            font-size: 0.9rem;
            padding: 8px 16px;
            border-radius: 25px;
            display: inline-flex;
            align-items: center;
        }
        
        .badge-type i {
            margin-left: 5px;
        }
        
        .contact-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-left: 4px solid #cc0100;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background: white;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .contact-item:hover {
            background: #cc0100;
            color: white;
            transform: translateX(5px);
        }
        
        .contact-item:hover a {
            color: white !important;
        }
        
        .contact-item:last-child {
            margin-bottom: 0;
        }
        
        .contact-item i {
            color: #cc0100;
            margin-left: 15px;
            width: 20px;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }
        
        .contact-item:hover i {
            color: white;
        }
        
        .working-doctors-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .doctor-item {
            padding: 20px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }
        
        .doctor-item:hover {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            transform: translateX(5px);
        }
        
        .doctor-item:last-child {
            border-bottom: none;
        }
        
        .doctor-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #cc0100;
            margin-left: 20px;
            transition: transform 0.3s ease;
        }
        
        .doctor-photo:hover {
            transform: scale(1.1);
        }
        
        .doctor-info {
            flex: 1;
        }
        
        .doctor-name {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 1.1rem;
        }
        
        .doctor-details {
            font-size: 0.9rem;
            color: #6c757d;
            line-height: 1.4;
        }
        
        .doctor-badge {
            background: #cc0100;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            margin-left: 5px;
        }
        
        .back-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(204, 1, 0, 0.3);
            border-radius: 50px;
            padding: 15px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(204, 1, 0, 0.4);
        }
        
        .gallery-section {
            margin-top: 30px;
        }
        
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .gallery-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .gallery-image:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        
        .feature-highlight {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-left: 4px solid #2196f3;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .feature-highlight h5 {
            color: #1976d2;
            margin-bottom: 10px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .stat-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #cc0100;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .profile-header {
                padding: 40px 0 30px;
            }
            
            .facility-image-large,
            .no-image-placeholder {
                width: 150px;
                height: 150px;
            }
            
            .info-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
            }
            
            .info-label {
                min-width: auto;
                margin-left: 0;
                margin-bottom: 8px;
            }
            
            .contact-item {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
            }
            
            .contact-item i {
                margin-left: 0;
                margin-bottom: 5px;
            }
            
            .doctor-item {
                flex-direction: column;
                text-align: center;
                padding: 20px;
            }
            
            .doctor-photo {
                margin-left: 0;
                margin-bottom: 15px;
            }
            
            .back-btn {
                bottom: 20px;
                right: 20px;
                padding: 12px 20px;
            }
            
            .gallery-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 10px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
                gap: 10px;
            }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cc0100;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #9e0302;
        }
        
        /* Loading animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        /* Custom type badges */
        .badge-private-clinic {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }
        
        .badge-medical-services {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
        }
        
        .badge-hospital {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        
        .badge-default {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
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
                            <a href="{{ route('facilities.directory') }}" class="btn btn-sm btn-outline-white rounded me-2">
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
    <section class="profile-header p-2">
        <div class="container">
            <div class="row align-items-center fade-in-up">
                <div class="col-md-12">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-5 mb-2">{{ $facility->name }}</h1>
                            @if($facility->name_en)
                                <h3 class="mb-3 opacity-75">{{ $facility->name_en }}</h3>
                            @endif
                            <div class="mb-3">
                                @if($facility->commercial_number)
                                    <span class="badge badge-type bg-white text-dark me-2">
                                        <i class="fas fa-certificate"></i>
                                        {{ $facility->commercial_number }}
                                    </span>
                                @endif
                                @php
                                    $typeClasses = [
                                        'private_clinic' => 'badge-private-clinic',
                                        'medical_services' => 'badge-medical-services', 
                                        'hospital' => 'badge-hospital'
                                    ];
                                    $typeLabels = [
                                        'private_clinic' => 'عيادة خاصة',
                                        'medical_services' => 'خدمات طبية',
                                        'hospital' => 'مستشفى',
                                        'medical_center' => 'مركز طبي',
                                        'laboratory' => 'مختبر',
                                        'pharmacy' => 'صيدلية',
                                        'radiology_center' => 'مركز أشعة'
                                    ];
                                @endphp
                                <span class="badge badge-type {{ $typeClasses[$facility->type] ?? 'badge-default' }}">
                                    <i class="fas fa-tag"></i>
                                    {{ $typeLabels[$facility->type] ?? $facility->type }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            @if($facility->manager)
                                <h4 class="mb-2">
                                    <i class="fas fa-user-md me-2"></i>
                                    {{ $facility->manager->name }}
                                </h4>
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-id-badge me-2"></i>
                                    {{ $facility->manager->code }}
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
                <div class="col-lg-6 fade-in-up">
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0 text-white">
                                <i class="fas fa-info-circle me-2"></i>
                                المعلومات الأساسية
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-hospital"></i>
                                    اسم المنشأة:
                                </div>
                                <div class="info-value">{{ $facility->name }}</div>
                            </div>
                            
                            @if($facility->name_en)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-globe"></i>
                                        الاسم بالإنجليزية:
                                    </div>
                                    <div class="info-value">{{ $facility->name_en }}</div>
                                </div>
                            @endif
                            
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-tag"></i>
                                    نوع المنشأة:
                                </div>
                                <div class="info-value">
                                    <span class="badge {{ $typeClasses[$facility->type] ?? 'badge-default' }}">
                                        {{ $typeLabels[$facility->type] ?? $facility->type }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($facility->commercial_number)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-certificate"></i>
                                        السجل التجاري:
                                    </div>
                                    <div class="info-value">{{ $facility->commercial_number }}</div>
                                </div>
                            @endif
                            
                            @if($facility->membership_expiration_date)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-file-contract"></i>
                                         حالة الترخيص :
                                    </div>
                                    <div class="info-value badge text-white {{$facility->membership_status->badgeClass()}} ">{{ $facility->membership_status->label() }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">
                                       <i class="fas fa-file-contract"></i>
                                       تاريخ الانتهاء
                                    </div>
                                    <div class="info-value badge text-dark  ">{{ $facility->membership_expiration_date->format('Y-m-d') }}</div>
                              </div>
                            @endif
                            
                            @if($facility->branch)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-map-marker-alt"></i>
                                        الفرع:
                                    </div>
                                    <div class="info-value">{{ $facility->branch->name }}</div>
                                </div>
                            @endif
                            
                            @if($facility->created_at)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-calendar-plus"></i>
                                        تاريخ التسجيل:
                                    </div>
                                    <div class="info-value">{{ $facility->created_at->format('Y-m-d') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-6 fade-in-up">
                    <div class="card info-card">
                        <div class="card-header text-white">
                            <h4 class="mb-0 text-white">
                                <i class="fas fa-address-book me-2"></i>
                                معلومات الاتصال
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="contact-section">
                                @if($facility->address)
                                    <div class="contact-item">
                                        <i class="fas fa-location-dot"></i>
                                        <div>
                                            <strong>العنوان:</strong><br>
                                            {{ $facility->address }}
                                        </div>
                                    </div>
                                @endif
                                
                                @if($facility->phone_number)
                                    <div class="contact-item">
                                        <i class="fas fa-phone"></i>
                                        <div>
                                            <strong>الهاتف:</strong><br>
                                            <a href="tel:{{ $facility->phone_number }}" class="text-decoration-none text-primary">
                                                {{ $facility->phone_number }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($facility->email)
                                    <div class="contact-item">
                                        <i class="fas fa-envelope"></i>
                                        <div>
                                            <strong>البريد الإلكتروني:</strong><br>
                                            <a href="mailto:{{ $facility->email }}" class="text-decoration-none text-primary">
                                                {{ $facility->email }}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($facility->website)
                                    <div class="contact-item">
                                        <i class="fas fa-globe"></i>
                                        <div>
                                            <strong>الموقع الإلكتروني:</strong><br>
                                            <a href="{{ $facility->website }}" target="_blank" class="text-decoration-none text-primary">
                                                {{ $facility->website }}
                                                <i class="fas fa-external-link-alt ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manager Information -->
            @if($facility->manager)
            <div class="row mt-4">
                <div class="col-12 fade-in-up">
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0 text-white">
                                <i class="fas fa-user-md me-2"></i>
                                الطبيب المسؤول
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="doctor-item">
                                @if($facility->manager->photo)
                                    <img src="{{ $facility->manager->photo }}" alt="{{ $facility->manager->name }}" class="doctor-photo">
                                @else
                                    <div class="doctor-photo d-flex align-items-center justify-content-center bg-light text-dark">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                @endif
                                
                                <div class="doctor-info">
                                    <div class="doctor-name">{{ $facility->manager->name }}</div>
                                    <div class="doctor-details">
                                        <span class="doctor-badge">{{ $facility->manager->code }}</span>
                                        @if($facility->manager->specialty1)
                                            <span class="doctor-badge">{{ $facility->manager->specialty1->name }}</span>
                                        @endif
                                        @if($facility->manager->doctorRank)
                                            <span class="doctor-badge">{{ $facility->manager->doctorRank->name }}</span>
                                        @endif
                                        @if($facility->manager->email)
                                            <div class="mt-1">
                                                <i class="fas fa-envelope text-primary me-1"></i>
                                                <a href="mailto:{{ $facility->manager->email }}" class="text-decoration-none">
                                                    {{ $facility->manager->email }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Working Doctors -->
            @if($facility->workingDoctors && $facility->workingDoctors->count() > 0)
            <div class="row mt-4">
                <div class="col-12 fade-in-up">
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-users me-2"></i>
                                الأطباء العاملون ({{ $facility->workingDoctors->count() }})
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            @foreach($facility->workingDoctors as $doctor)
                                <div class="doctor-item">
                                    @if($doctor->photo)
                                        <img src="{{ $doctor->photo }}" alt="{{ $doctor->name }}" class="doctor-photo">
                                    @else
                                        <div class="doctor-photo d-flex align-items-center justify-content-center bg-light text-dark">
                                            <i class="fas fa-user-md"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="doctor-info">
                                        <div class="doctor-name">{{ $doctor->name }}</div>
                                        <div class="doctor-details">
                                            <span class="doctor-badge">{{ $doctor->code }}</span>
                                            @if($doctor->specialty1)
                                                <span class="doctor-badge">{{ $doctor->specialty1->name }}</span>
                                            @endif
                                            @if($doctor->doctorRank)
                                                <span class="doctor-badge">{{ $doctor->doctorRank->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Services/Specialties -->
            @if($facility->services || $facility->description)
            <div class="row mt-4">
                <div class="col-12 fade-in-up">
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-stethoscope me-2"></i>
                                الخدمات والتخصصات
                            </h4>
                        </div>
                        <div class="card-body">
                            @if($facility->description)
                                <div class="feature-highlight">
                                    <h5><i class="fas fa-info-circle me-2"></i>نبذة عن المنشأة</h5>
                                    <p class="mb-0">{{ $facility->description }}</p>
                                </div>
                            @endif
                            
                            @if($facility->services)
                                <div class="feature-highlight">
                                    <h5><i class="fas fa-heartbeat me-2"></i>الخدمات المقدمة</h5>
                                    <p class="mb-0">{{ $facility->services }}</p>
                                </div>
                            @endif
                            
                            @if($facility->specialties)
                                <div class="feature-highlight">
                                    <h5><i class="fas fa-user-md me-2"></i>التخصصات المتوفرة</h5>
                                    <p class="mb-0">{{ $facility->specialties }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Working Hours & Additional Info -->
            @if($facility->working_hours || $facility->emergency_hours || $facility->notes)
            <div class="row mt-4">
                <div class="col-12 fade-in-up">
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-clock me-2"></i>
                                معلومات إضافية
                            </h4>
                        </div>
                        <div class="card-body p-0">
                            @if($facility->working_hours)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-business-time"></i>
                                        ساعات العمل:
                                    </div>
                                    <div class="info-value">{{ $facility->working_hours }}</div>
                                </div>
                            @endif
                            
                            @if($facility->emergency_hours)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-ambulance"></i>
                                        ساعات الطوارئ:
                                    </div>
                                    <div class="info-value">{{ $facility->emergency_hours }}</div>
                                </div>
                            @endif
                            
                            @if($facility->notes)
                                <div class="info-item">
                                    <div class="info-label">
                                        <i class="fas fa-sticky-note"></i>
                                        ملاحظات:
                                    </div>
                                    <div class="info-value">{{ $facility->notes }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Facility Statistics -->
            @if($facility->workingDoctors && $facility->workingDoctors->count() > 0)
            <div class="row mt-4">
                <div class="col-12 fade-in-up">
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                إحصائيات المنشأة
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-number">{{ $facility->workingDoctors->count() }}</div>
                                    <div class="stat-label">إجمالي الأطباء</div>
                                </div>
                                
                                @php
                                    $specialtiesCount = $facility->workingDoctors->pluck('specialty1_id')->filter()->unique()->count();
                                @endphp
                                @if($specialtiesCount > 0)
                                    <div class="stat-item">
                                        <div class="stat-number">{{ $specialtiesCount }}</div>
                                        <div class="stat-label">التخصصات المتاحة</div>
                                    </div>
                                @endif
                                
                                @php
                                    $experiencedDoctors = $facility->workingDoctors->where('experience', '>', 5)->count();
                                @endphp
                                @if($experiencedDoctors > 0)
                                    <div class="stat-item">
                                        <div class="stat-number">{{ $experiencedDoctors }}</div>
                                        <div class="stat-label">أطباء ذوو خبرة +5 سنوات</div>
                                    </div>
                                @endif
                                
                                @if($facility->created_at)
                                    @php
                                        $yearsOfService = now()->diffInYears($facility->created_at);
                                    @endphp
                                    <div class="stat-item">
                                        <div class="stat-number">{{ $yearsOfService }}</div>
                                        <div class="stat-label">سنوات الخدمة</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Gallery Section -->
            @if($facility->gallery && count($facility->gallery) > 0)
            <div class="row mt-4">
                <div class="col-12 fade-in-up">
                    <div class="card info-card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-images me-2"></i>
                                معرض الصور
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="gallery-grid">
                                @foreach($facility->gallery as $image)
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         alt="صورة من {{ $facility->name }}" 
                                         class="gallery-image"
                                         data-bs-toggle="modal" 
                                         data-bs-target="#imageModal"
                                         data-image-src="{{ asset('storage/' . $image) }}">
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Contact CTA Section -->
            <div class="row mt-4">
                <div class="col-12 fade-in-up">
                    <div class="card" style="background: linear-gradient(135deg, #cc0100 0%, #9e0302 100%); color: white;">
                        <div class="card-body text-center py-5">
                            <h3 class="mb-3 text-white">
                                <i class="fas fa-phone-alt me-2"></i>
                                تواصل مع {{ $facility->name }}
                            </h3>
                            <p class="lead mb-4">للحصول على الخدمات الطبية أو الاستفسار عن المواعيد</p>
                            <div class="row justify-content-center">
                                @if($facility->phone_number)
                                    <div class="col-md-4 mb-3">
                                        <a href="tel:{{ $facility->phone_number }}" class="btn btn-outline-light btn-lg w-100">
                                            <i class="fas fa-phone me-2"></i>
                                            اتصل الآن
                                        </a>
                                    </div>
                                @endif
                                
                                @if($facility->phone)
                                    <div class="col-md-4 mb-3">
                                        <a href="tell:{{ $facility->phone }}" class="btn btn-info text-dark btn-lg w-100" style="color: #0000!important;">
                                            <i class="fas fa-envelope me-2"></i>
                                            للإتصال انقر هنا 
                                        </a>
                                    </div>
                                @endif
                                
                                @if($facility->address)
                                    <div class="col-md-4 mb-3">
                                        <a href="https://maps.google.com/?q={{ urlencode($facility->address) }}" 
                                           target="_blank" class="btn btn-outline-light btn-lg w-100">
                                            <i class="fas fa-map-marker-alt me-2"></i>
                                            الموقع على الخريطة
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Back to Directory Button -->
    <a href="{{ route('facilities.directory') }}" class="btn btn-primary back-btn">
        <i class="fas fa-arrow-right me-2"></i>
        العودة للدليل
    </a>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $facility->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-inverse">
        <div class="container pt-10 pb-8">
            <div class="row text-center">
                <div class="col-12">
                    <img class="mb-4" src="{{ asset('/assets/images/lgmc-light.png?v=2') }}" width="200" alt="شعار النقابة العامة للأطباء" />
                    <p class="mb-4">© <script>document.write(new Date().getFullYear());</script> النقابة العامة للأطباء. جميع الحقوق محفوظة.</p>
                    <div class="text-center">
                        <a href="{{ route('facilities.directory') }}" class="text-decoration-none text-light me-3">
                            <i class="fas fa-hospital me-1"></i>
                            دليل المنشآت الطبية
                        </a>
                        <a href="{{ route('doctors.index') }}" class="text-decoration-none text-light me-3">
                            <i class="fas fa-user-md me-1"></i>
                            دليل الأطباء
                        </a>
                        <a href="/" class="text-decoration-none text-light">
                            <i class="fas fa-home me-1"></i>
                            الصفحة الرئيسية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image modal functionality
            const imageModal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            
            if (imageModal) {
                imageModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const imageSrc = button.getAttribute('data-image-src');
                    modalImage.src = imageSrc;
                });
            }
            
            // Smooth scrolling for anchor links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Add fade-in animation to cards on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in-up');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
            
            document.querySelectorAll('.info-card').forEach(card => {
                observer.observe(card);
            });
            
            // Copy to clipboard functionality for contact info
            function copyToClipboard(text, element) {
                navigator.clipboard.writeText(text).then(function() {
                    const originalTitle = element.title;
                    element.title = 'تم النسخ!';
                    setTimeout(() => {
                        element.title = originalTitle;
                    }, 2000);
                });
            }
            
            // Add click to copy for phone numbers
            document.querySelectorAll('a[href^="tel:"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (e.ctrlKey || e.metaKey) {
                        e.preventDefault();
                        const phoneNumber = this.href.replace('tel:', '');
                        copyToClipboard(phoneNumber, this);
                    }
                });
                
                link.title = 'اضغط للاتصال، Ctrl+Click للنسخ';
            });
            
            // Add click to copy for email addresses
            document.querySelectorAll('a[href^="mailto:"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    if (e.ctrlKey || e.metaKey) {
                        e.preventDefault();
                        const email = this.href.replace('mailto:', '');
                        copyToClipboard(email, this);
                    }
                });
                
                link.title = 'اضغط لإرسال رسالة، Ctrl+Click للنسخ';
            });
            
            // Add loading states to external links
            document.querySelectorAll('a[target="_blank"]').forEach(link => {
                link.addEventListener('click', function() {
                    const icon = this.querySelector('i');
                    if (icon) {
                        const originalClass = icon.className;
                        icon.className = 'fas fa-spinner fa-spin';
                        setTimeout(() => {
                            icon.className = originalClass;
                        }, 2000);
                    }
                });
            });
            
            // Add hover effects to stats
            document.querySelectorAll('.stat-item').forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px) scale(1.05)';
                });
                
                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(-5px) scale(1)';
                });
            });
            
            // Print functionality
            window.printFacilityInfo = function() {
                const printWindow = window.open('', '_blank');
                const facilityName = '{{ $facility->name }}';
                const facilityType = '{{ $typeLabels[$facility->type] ?? $facility->type }}';
                const facilityAddress = '{{ $facility->address ?? "غير محدد" }}';
                const facilityPhone = '{{ $facility->phone_number ?? "غير محدد" }}';
                const managerName = '{{ $facility->manager->name ?? "غير محدد" }}';
                
                const printContent = `
                    <!DOCTYPE html>
                    <html dir="rtl" lang="ar">
                    <head>
                        <meta charset="UTF-8">
                        <title>معلومات ${facilityName}</title>
                        <style>
                            body { font-family: Arial, sans-serif; direction: rtl; text-align: right; margin: 20px; }
                            .header { text-align: center; border-bottom: 2px solid #cc0100; padding-bottom: 20px; margin-bottom: 30px; }
                            .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #eee; }
                            .info-label { font-weight: bold; color: #666; width: 30%; }
                            .info-value { color: #333; width: 65%; }
                        </style>
                    </head>
                    <body>
                        <div class="header">
                            <h1>النقابة العامة للأطباء</h1>
                            <h2>معلومات المنشأة الطبية</h2>
                        </div>
                        <div class="info-row"><div class="info-label">اسم المنشأة:</div><div class="info-value">${facilityName}</div></div>
                        <div class="info-row"><div class="info-label">نوع المنشأة:</div><div class="info-value">${facilityType}</div></div>
                        <div class="info-row"><div class="info-label">العنوان:</div><div class="info-value">${facilityAddress}</div></div>
                        <div class="info-row"><div class="info-label">الهاتف:</div><div class="info-value">${facilityPhone}</div></div>
                        <div class="info-row"><div class="info-label">الطبيب المسؤول:</div><div class="info-value">${managerName}</div></div>
                    </body>
                    </html>
                `;
                
                printWindow.document.write(printContent);
                printWindow.document.close();
                printWindow.onload = function() {
                    printWindow.print();
                    printWindow.close();
                };
            };
        });
    </script>
</body>
</html>