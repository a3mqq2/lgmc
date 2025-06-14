<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دليل الأطباء - النقابة العامة للأطباء</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-primary.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/colors/aqua.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body { font-family: "Cairo", serif !important; }
        .doctor-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .doctor-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #cc0100;
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
    </style>
</head>
<body>
    <!-- Header - يمكنك نسخه من الصفحة الرئيسية -->
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
                    <h1 class="display-4 text-white mb-3">دليل الأطباء</h1>
                    <p class="lead text-white mb-0">ابحث عن الأطباء المسجلين في النقابة العامة للأطباء</p>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control form-control-lg" 
                                   placeholder="ابحث بالاسم أو الرقم المهني..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <select name="type" class="form-select form-select-lg">
                                <option value="">جميع الأنواع</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->value }}" {{ request('type') == $type->value ? 'selected' : '' }}>
                                        {{ $type->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="specialty" class="form-select form-select-lg">
                                <option value="">جميع التخصصات</option>
                                @foreach($specialties as $specialty)
                                    <option value="{{ $specialty->id }}" {{ request('specialty') == $specialty->id ? 'selected' : '' }}>
                                        {{ $specialty->name }}
                                    </option>
                                @endforeach
                            </select>
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
                <div class="col-md-3">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-md fa-2x text-primary mb-3"></i>
                            <h3 class="mb-1">{{ number_format($stats['total']) }}</h3>
                            <p class="text-muted mb-0">إجمالي الأطباء</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-flag fa-2x text-success mb-3"></i>
                            <h3 class="mb-1">{{ number_format($stats['libyan']) }}</h3>
                            <p class="text-muted mb-0">أطباء ليبيون</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-globe fa-2x text-info mb-3"></i>
                            <h3 class="mb-1">{{ number_format($stats['palestinian'] + $stats['foreign']) }}</h3>
                            <p class="text-muted mb-0">أطباء أجانب</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stats-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-handshake fa-2x text-warning mb-3"></i>
                            <h3 class="mb-1">{{ number_format($stats['visitor']) }}</h3>
                            <p class="text-muted mb-0">أطباء زائرون</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Doctors List -->
    <section class="wrapper">
        <div class="container py-5">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>نتائج البحث ({{ $doctors->total() }} طبيب)</h3>
                        <div>
                            <a href="{{ route('doctors.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-refresh"></i> إعادة تعيين الفلاتر
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                @forelse($doctors as $doctor)
                    <div class="col-lg-4 col-md-6">
                        <div class="card doctor-card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-start mb-3">
                                    <img src="{{ $doctor->photo ?: asset('assets/images/default-doctor.png') }}" 
                                         alt="{{ $doctor->name }}" class="doctor-photo me-3">
                                    <div class="flex-grow-1">
                                        <h5 class="card-title mb-1">{{ $doctor->name }}</h5>
                                        @if($doctor->name_en)
                                            <p class="text-muted small mb-1">{{ $doctor->name_en }}</p>
                                        @endif
                                        <span class="badge bg-primary">{{ $doctor->code }}</span>
                                    </div>
                                </div>

                                <div class="doctor-info">
                                    @if($doctor->specialty1)
                                        <p class="mb-2">
                                            <i class="fas fa-stethoscope text-primary me-2"></i>
                                            <strong>التخصص:</strong> {{ $doctor->specialty1->name }}
                                        </p>
                                    @endif

                                    @if($doctor->doctorRank)
                                        <p class="mb-2">
                                            <i class="fas fa-medal text-warning me-2"></i>
                                            <strong>الصفة:</strong> {{ $doctor->doctorRank->name }}
                                        </p>
                                    @endif

                                    @if($doctor->branch && $doctor->type->value === 'libyan')
                                        <p class="mb-2">
                                            <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                            <strong>الفرع :</strong> {{ $doctor->branch->name }}
                                        </p>
                                    @endif

                                    @if($doctor->phone)
                                        <p class="mb-2">
                                            <i class="fas fa-phone text-success me-2"></i>
                                            {{ $doctor->phone }}
                                        </p>
                                    @endif
                                </div>

                                 <div class="mt-3">
                                    <a  href="{{route('doctors.show',$doctor)}}"
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
                            <p class="text-muted">لم يتم العثور على أطباء يطابقون معايير البحث</p>
                            <a href="{{ route('doctors.index') }}" class="btn btn-primary">
                                عرض جميع الأطباء
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($doctors->hasPages())
                <div class="row mt-5">
                    <div class="col-12">
                        {{ $doctors->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Footer - يمكنك نسخه من الصفحة الرئيسية -->
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