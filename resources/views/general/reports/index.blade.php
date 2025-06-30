@extends('layouts.'.get_area_name())

@section('title', 'التقارير')

@section('styles')
<style>
    * {
        box-sizing: border-box;
    }
    
    .reports-container {
        background: #f8fafc;
        min-height: 100vh;
        padding: 2rem 0;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }
    
    .page-header {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }
    
    .page-title {
        color: #1e293b;
        font-size: 2rem;
        font-weight: 600;
        margin: 0 0 0.5rem 0;
        letter-spacing: -0.025em;
    }
    
    .page-subtitle {
        color: #64748b;
        font-size: 1rem;
        margin: 0;
        font-weight: 400;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    
    .stat-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        display: block;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
    }
    
    .report-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    
    .report-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 25px -6px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }
    
    .report-header {
        background: #bc312c;
        color: white !important;
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .report-title {
        font-size: 1.125rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color:#fff!important;
    }
    
    .report-icon {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .report-body {
        padding: 2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    
    .form-input,
    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.875rem;
        background: white;
        transition: all 0.2s ease;
    }
    
    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #d1d5db !important;
        border-radius: 8px !important;
        min-height: 46px !important;
        padding: 0.25rem !important;
    }
    
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }
    
    .btn-generate {
        width: 100%;
        background: #1e293b;
        color: white;
        border: none;
        padding: 0.875rem 1.5rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 1rem;
    }
    
    .btn-generate:hover {
        background: #334155;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);
    }
    
    .btn-generate:active {
        transform: translateY(0);
    }
    
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    
    .loading-content {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        max-width: 300px;
        margin: 1rem;
    }
    
    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #e5e7eb;
        border-top: 3px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 1rem;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .loading-text {
        color: #6b7280;
        font-size: 0.875rem;
        margin: 0;
    }
    
    /* Grid adjustments for mobile */
    @media (max-width: 768px) {
        .reports-container {
            padding: 1rem 0;
        }
        
        .page-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .stat-card {
            padding: 1rem;
        }
        
        .stat-number {
            font-size: 1.5rem;
        }
        
        .reports-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .report-body {
            padding: 1.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .page-title {
            font-size: 1.25rem;
        }
        
        .report-header {
            padding: 1rem;
        }
        
        .report-body {
            padding: 1rem;
        }
    }
    
    /* Smooth transitions */
    .form-input,
    .form-select,
    .btn-generate,
    .report-card,
    .stat-card {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Clean focus states */
    .form-input:focus,
    .form-select:focus {
        transform: translateY(-1px);
    }
    
    /* Subtle hover effects */
    .report-card:hover .report-header {
        background: #0f172a;
    }
</style>
@endsection

@section('content')
<div class="reports-container">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">التقارير</h1>
            <p class="page-subtitle">إنشاء وإدارة التقارير الطبية والإحصائيات</p>
        </div>

        <!-- Quick Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-number" id="total-doctors">---</span>
                <div class="stat-label">إجمالي الأطباء</div>
            </div>
            <div class="stat-card">
                <span class="stat-number" id="active-doctors">---</span>
                <div class="stat-label">الأطباء النشطون</div>
            </div>
            <div class="stat-card">
                <span class="stat-number" id="total-facilities">---</span>
                <div class="stat-label">المنشآت الطبية</div>
            </div>
            <div class="stat-card">
                <span class="stat-number" id="monthly-licenses">---</span>
                <div class="stat-label">الاذونات هذا الشهر</div>
            </div>
        </div>

        <!-- Reports Grid -->
        <div class="reports-grid">
            {{-- تقرير تسجيل الأطباء --}}
            <div class="report-card">
                <div class="report-header">
                    <h3 class="report-title">
                        <i class="fas fa-user-md report-icon"></i>
                        تقرير تسجيل الأطباء
                    </h3>
                </div>
                <div class="report-body">
                    <form action="{{ route(get_area_name().'.reports.doctors-registration') }}" method="GET" target="_blank" class="report-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">من تاريخ</label>
                                    <input type="date" name="from_date" class="form-input" value="{{ date('Y-m-01') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">إلى تاريخ</label>
                                    <input type="date" name="to_date" class="form-input" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">مكان العمل</label>
                                    <select name="work_places[]" class="form-select select2" multiple>
                                        <option value="">جميع الأماكن</option>
                                        @foreach(\App\Models\MedicalFacility::select('name')->distinct()->pluck('name') as $facility)
                                            <option value="{{ $facility }}">{{ $facility }}</option>
                                        @endforeach
                                        
                                        @if (get_area_name() == "user")
                                        @foreach(\App\Models\Institution::where('branch_id', auth()->user()->branch_id)->pluck('name', 'name') as $name => $institution)
                                            <option value="{{ $institution }}">{{ $institution }}</option>
                                        @endforeach
                                        @else 
                                        @foreach(\App\Models\Institution::pluck('name', 'name') as $name => $institution)
                                            <option value="{{ $institution }}">{{ $institution }}</option>
                                        @endforeach
                                        @endif

                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">المنشآت الطبية</label>
                                    <select name="medical_facilities[]" class="form-select select2" multiple>
                                        <option value="">جميع المنشآت</option>
                                        @foreach(\App\Models\MedicalFacility::all() as $facility)
                                            <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">الصفة</label>
                                    <select name="doctor_rank_id" class="form-select">
                                        <option value="">جميع الصفات</option>
                                        @foreach(\App\Models\DoctorRank::where('doctor_type', 'libyan')->get() as $rank)
                                            <option value="{{ $rank->id }}">{{ $rank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">التخصص</label>
                                    <select name="specialty_id" class="form-select">
                                        <option value="">جميع التخصصات</option>
                                        @foreach(\App\Models\Specialty::all() as $specialty)
                                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">حالة العضوية</label>
                                    <select name="membership_status" class="form-select">
                                        <option value="">جميع الحالات</option>
                                        <option value="active">نشط</option>
                                        <option value="expired">منتهي الصلاحية</option>
                                        <option value="suspended">معلق</option>
                                        <option value="banned">محظور</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">نوع الطبيب</label>
                                    <select name="doctor_type" class="form-select">
                                        <option value="">جميع الأنواع</option>
                                        <option value="libyan">ليبي</option>
                                        <option value="foreign">أجنبي</option>
                                        <option value="palestinian">فلسطيني</option>
                                        <option value="visitor">زائر</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-generate">
                            <i class="fas fa-file-pdf me-2"></i>
                            إنشاء التقرير
                        </button>
                    </form>
                </div>
            </div>

            {{-- تقرير أذونات المزاولة للأطباء --}}
            <div class="report-card">
                <div class="report-header">
                    <h3 class="report-title">
                        <i class="fas fa-certificate report-icon"></i>
                        تقرير أذونات المزاولة للأطباء
                    </h3>
                </div>
                <div class="report-body">
                    <form action="{{ route(get_area_name().'.reports.doctors-licenses') }}" method="GET" target="_blank" class="report-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">من تاريخ</label>
                                    <input type="date" name="from_date" class="form-input" value="{{ date('Y-m-01') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">إلى تاريخ</label>
                                    <input type="date" name="to_date" class="form-input" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">الصفة</label>
                                    <select name="doctor_rank_id" class="form-select">
                                        <option value="">جميع الصفات</option>
                                        @foreach(\App\Models\DoctorRank::where('doctor_type','libyan')->get() as $rank)
                                            <option value="{{ $rank->id }}">{{ $rank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">التخصص</label>
                                    <select name="specialty_id" class="form-select">
                                        <option value="">جميع التخصصات</option>
                                        @foreach(\App\Models\Specialty::all() as $specialty)
                                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">نوع الطبيب</label>
                                    <select name="doctor_type" class="form-select">
                                        <option value="">جميع الأنواع</option>
                                        <option value="libyan">ليبي</option>
                                        <option value="foreign">أجنبي</option>
                                        <option value="palestinian">فلسطيني</option>
                                        <option value="visitor">زائر</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-generate">
                            <i class="fas fa-file-pdf me-2"></i>
                            إنشاء التقرير
                        </button>
                    </form>
                </div>
            </div>

            {{-- تقرير تسجيل المنشآت الطبية --}}
            <div class="report-card">
                <div class="report-header">
                    <h3 class="report-title">
                        <i class="fas fa-hospital report-icon"></i>
                        تقرير تسجيل المنشآت الطبية
                    </h3>
                </div>
                <div class="report-body">
                    <form action="{{ route(get_area_name().'.reports.medical-facilities-registration') }}" method="GET" target="_blank" class="report-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">من تاريخ</label>
                                    <input type="date" name="from_date" class="form-input" value="{{ date('Y-m-01') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">إلى تاريخ</label>
                                    <input type="date" name="to_date" class="form-input" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">حالة المنشأة</label>
                                    <select name="facility_status" class="form-select">
                                        <option value="">جميع الحالات</option>
                                        <option value="active">نشطة</option>
                                        <option value="expired">منتهية الصلاحية</option>
                                        <option value="suspended">معلقة</option>
                                        <option value="under_approve">قيد الموافقة</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">نوع المنشأة</label>
                                    <select name="facility_type" class="form-select">
                                        <option value="">جميع الأنواع</option>
                                        @foreach(\App\Models\MedicalFacilityType::all() as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-generate">
                            <i class="fas fa-file-pdf me-2"></i>
                            إنشاء التقرير
                        </button>
                    </form>
                </div>
            </div>

            {{-- تقرير أذونات المنشآت الطبية --}}
            <div class="report-card">
                <div class="report-header">
                    <h3 class="report-title">
                        <i class="fas fa-certificate report-icon"></i>
                        تقرير أذونات المنشآت الطبية
                    </h3>
                </div>
                <div class="report-body">
                    <form action="{{ route(get_area_name().'.reports.medical-facilities-licenses') }}" method="GET" target="_blank" class="report-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">من تاريخ</label>
                                    <input type="date" name="from_date" class="form-input" value="{{ date('Y-m-01') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">إلى تاريخ</label>
                                    <input type="date" name="to_date" class="form-input" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">حالة الإذن</label>
                                    <select name="license_status" class="form-select">
                                        <option value="">جميع الحالات</option>
                                        <option value="active">نشط</option>
                                        <option value="expired">منتهي الصلاحية</option>
                                        <option value="suspended">معلق</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">نوع المنشأة</label>
                                    <select name="facility_type" class="form-select">
                                        <option value="">جميع الأنواع</option>
                                        @foreach(\App\Models\MedicalFacilityType::all() as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-generate">
                            <i class="fas fa-file-pdf me-2"></i>
                            إنشاء التقرير
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-content">
        <div class="spinner"></div>
        <p class="loading-text">جاري إنشاء التقرير...</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: "اختر من القائمة...",
        allowClear: true,
        dir: "rtl",
        width: '100%'
    });

    // Load quick stats
    loadQuickStats();

    // Form submission with loading
    $('.report-form').on('submit', function() {
        showLoading();
        setTimeout(hideLoading, 3000);
    });

    // Smooth focus animations
    $('.form-input, .form-select').on('focus', function() {
        $(this).closest('.form-group').addClass('focused');
    }).on('blur', function() {
        $(this).closest('.form-group').removeClass('focused');
    });
});

function loadQuickStats() {
    @if(Route::has(get_area_name().'.reports.quick-stats'))
        $.get('{{ route(get_area_name().".reports.quick-stats") }}', function(data) {
            $('#total-doctors').text(data.total_doctors || 0);
            $('#active-doctors').text(data.active_doctors || 0);
            $('#total-facilities').text(data.total_facilities || 0);
            $('#monthly-licenses').text(data.total_licenses_this_month || 0);
            animateNumbers();
        }).fail(function() {
            $('#total-doctors, #active-doctors, #total-facilities, #monthly-licenses').text('0');
        });
    @else
        $('#total-doctors, #active-doctors, #total-facilities, #monthly-licenses').text('0');
    @endif
}

function animateNumbers() {
    $('.stat-number').each(function() {
        let $this = $(this);
        let countTo = parseInt($this.text()) || 0;
        
        if (countTo > 0) {
            $({ countNum: 0 }).animate({
                countNum: countTo
            }, {
                duration: 1500,
                easing: 'swing',
                step: function() {
                    $this.text(Math.floor(this.countNum));
                },
                complete: function() {
                    $this.text(countTo);
                }
            });
        }
    });
}

function showLoading() {
    $('#loadingOverlay').fadeIn(200);
}

function hideLoading() {
    $('#loadingOverlay').fadeOut(200);
}

// Mobile responsive Select2
$(window).on('resize', function() {
    if ($(window).width() < 768) {
        $('.select2').select2('destroy').select2({
            placeholder: "اختر...",
            allowClear: true,
            dir: "rtl",
            width: '100%'
        });
    }
});
</script>
@endsection