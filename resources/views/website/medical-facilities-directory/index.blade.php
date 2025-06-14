<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دليل المنشآت الطبية - النقابة العامة للأطباء</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-primary.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/colors/aqua.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { font-family: "Cairo", serif !important; }
        .facility-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        .facility-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .facility-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .facility-type-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        .filter-card {
            background: linear-gradient(135deg, #cc0100 0%, #9e0302 100%);
            color: white;
        }
        .stats-card {
            background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
            border-left: 4px solid #cc0100;
        }
        .search-section {
            background: linear-gradient(135deg, #cc0100 0%, #9e0302 100%);
            padding: 60px 0;
        }
        .btn-filter {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            backdrop-filter: blur(10px);
        }
        .btn-filter:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        .facility-info {
            padding: 20px;
        }
        .facility-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        .facility-detail {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .facility-detail i {
            width: 16px;
            margin-left: 8px;
            color: #cc0100;
        }
        .manager-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }
        .no-image-placeholder {
            height: 200px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
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
                            <a href="/" class="btn btn-sm btn-outline-white rounded">العودة للرئيسية</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Search Section -->
    <section class="search-section">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <h1 class="display-4 text-white mb-3">دليل المنشآت الطبية</h1>
                    <p class="lead text-white mb-0">ابحث عن المنشآت الطبية المرخصة والمسجلة لدى النقابة</p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control form-control-lg" 
                                   placeholder="ابحث بالاسم أو رقم الترخيص..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="type" class="form-select form-select-lg">
                                <option value="">جميع الأنواع</option>
                                <option value="private_clinic">عيادة خاصة</option>
                                <option value="medical_services">خدمات طبية</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="branch" class="form-select form-select-lg">
                                <option value="">جميع الفروع</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ request('branch') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="city" class="form-control form-control-lg" 
                                   placeholder="المدينة أو المنطقة..." 
                                   value="{{ request('city') }}">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-filter btn-lg w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="wrapper bg-light">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-hospital fa-2x text-primary mb-3"></i>
                            <h3 class="mb-1">{{ number_format($stats['total']) }}</h3>
                            <p class="text-muted mb-0">إجمالي المنشآت</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-stethoscope fa-2x text-success mb-3"></i>
                            <h3 class="mb-1">{{ number_format($stats['private_clinic']) }}</h3>
                            <p class="text-muted mb-0">عيادات خاصة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-briefcase-medical fa-2x text-info mb-3"></i>
                            <h3 class="mb-1">{{ number_format($stats['medical_services']) }}</h3>
                            <p class="text-muted mb-0">خدمات طبية</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities List -->
    <section class="wrapper">
        <div class="container py-5">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>نتائج البحث ({{ $facilities->total() }} منشأة)</h3>
                        <div>
                            <a href="{{ route('facilities.directory') }}" class="btn btn-outline-primary">
                                <i class="fas fa-refresh"></i> إعادة تعيين الفلاتر
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse($facilities as $facility)
                    <div class="col-lg-4 col-md-6">
                        <div class="card facility-card h-100">
                            <!-- Facility Info -->
                            <div class="facility-info">
                                <h5 class="facility-name">{{ $facility->name }}</h5>
                                
                                @if($facility->type)
                                    <div class="facility-detail">
                                        <i class="fas fa-tag"></i>
                                        <span>{{ $types[$facility->type] ?? $facility->type }}</span>
                                    </div>
                                @endif

                                @if($facility->branch)
                                    <div class="facility-detail">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $facility->branch->name }}</span>
                                    </div>
                                @endif

                                @if($facility->address)
                                    <div class="facility-detail">
                                        <i class="fas fa-location-dot"></i>
                                        <span>{{ Str::limit($facility->address, 50) }}</span>
                                    </div>
                                @endif

                                @if($facility->phone_number)
                                    <div class="facility-detail">
                                        <i class="fas fa-phone"></i>
                                        <span>{{ $facility->phone_number }}</span>
                                    </div>
                                @endif

                                @if($facility->commercial_number)
                                    <div class="facility-detail">
                                        <i class="fas fa-certificate"></i>
                                        <span>سجل تجاري : {{ $facility->commercial_number }}</span>
                                    </div>
                                @endif

                                <!-- Manager Info -->
                                @if($facility->manager)
                                    <div class="manager-info">
                                        <div class="facility-detail">
                                            <i class="fas fa-user-md"></i>
                                            <span><strong>الطبيب :</strong> {{ $facility->manager->name }}</span>
                                        </div>
                                        <div class="facility-detail">
                                            <i class="fas fa-id-badge"></i>
                                            <span>{{ $facility->manager->code }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- View Details Button -->
                                <div class="mt-3">
                                    <a href="{{ route('facilities.show', $facility->id) }}" 
                                       class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-eye me-1"></i> عرض التفاصيل
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">لا توجد نتائج</h4>
                            <p class="text-muted">لم يتم العثور على منشآت طبية تطابق معايير البحث</p>
                            <a href="{{ route('facilities.directory') }}" class="btn btn-primary">
                                عرض جميع المنشآت
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($facilities->hasPages())
                <div class="row mt-5">
                    <div class="col-12">
                        {{ $facilities->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-inverse">
        <div class="container pt-17 pt-md-19 pb-13 pb-md-15">
            <div class="row gy-6 gy-lg-0">
                <div class="col-md-4 col-lg-3">
                    <div class="widget">
                        <img class="mb-4" src="{{ asset('/assets/images/lgmc-light.png?v=2') }}" width="300" alt="شعار النقابة العامة للأطباء" />
                        <p class="mb-4">© <script>document.write(new Date().getFullYear());</script> النقابة العامة للأطباء. <br class="d-none d-lg-block" />جميع الحقوق محفوظة.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
</body>
</html>