@extends('layouts.'.get_area_name())
@section('title','الصفحه الرئيسيه')
@section('content')

{{-- التحقق من وجود المخصصات المالية السنوية --}}
@if($systemStarted && !$hasYearlyTransfer && $userHasBranch && $requiredYear)
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <div class="flex-shrink-0">
                    <i class="ri-error-warning-line display-6 text-danger"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="alert-heading mb-1">تنبيه: المخصصات المالية السنوية لعام {{ $requiredYear }}</h5>
                    <p class="mb-0">لم يتم تحويل المخصصات المالية للسنة السابقة ({{ $requiredYear }}). يجب إتمام هذا الإجراء لضمان عمل النظام المالي بكفاءة.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- بطاقة إجراء التحويل --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-danger">
                <div class="card-body text-center py-5">
                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                            <i class="ri-funds-box-line display-5"></i>
                        </div>
                    </div>
                    
                    <h4 class="card-title mb-3">المخصصات المالية لعام {{ $requiredYear }} غير مكتملة</h4>
                    <p class="text-muted mb-4">يتطلب النظام المالي تحويل نسبة من إيرادات عام {{ $requiredYear }} إلى الخزينة الرئيسية<br>لضمان استمرارية العمليات المالية بشكل سليم</p>
                    
                    <a href="{{ route('finance.vault-transfers.create') }}" class="btn btn-danger btn-lg">
                        <i class="ri-exchange-funds-line me-2"></i>
                        إجراء التحويل المالي الآن
                    </a>
                </div>
            </div>
        </div>
    </div>
@endif



@endsection

@section('scripts')
<!-- apexcharts -->
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Vector map-->
<script src="/assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
<script src="/assets/libs/jsvectormap/maps/world-merc.js"></script>

<!-- Counter -->
<script src="/assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
<script src="/assets/libs/jquery.counterup/jquery.counterup.min.js"></script>

<script>
    // Counter animation
    $('[data-target]').counterUp({
        delay: 10,
        time: 1000
    });

    // Revenue vs Expense Chart
    var revenueExpenseOptions = {
        chart: {
            height: 350,
            type: 'area',
            toolbar: {
                show: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        series: [{
            name: 'الإيرادات',
            data: [31, 40, 28, 51, 42, 109, 100]
        }, {
            name: 'المصروفات',
            data: [11, 32, 45, 32, 34, 52, 41]
        }],
        xaxis: {
            categories: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو']
        },
        colors: ['#10b981', '#f59e0b'],
        fill: {
            opacity: 0.06
        }
    };
    
    var revenueExpenseChart = new ApexCharts(document.querySelector("#revenue_expense_chart"), revenueExpenseOptions);
    revenueExpenseChart.render();

    // Expense Distribution Chart
    var expenseDistOptions = {
        chart: {
            height: 320,
            type: 'donut',
        },
        series: [44, 55, 13, 33],
        labels: ['رواتب', 'مصروفات تشغيلية', 'صيانة', 'أخرى'],
        colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            dropShadow: {
                enabled: false
            }
        }
    };
    
    var expenseDistChart = new ApexCharts(document.querySelector("#expense_distribution_chart"), expenseDistOptions);
    expenseDistChart.render();
</script>

{{-- تنبيه إضافي باستخدام SweetAlert2 --}}
@if($systemStarted && !$hasYearlyTransfer && $userHasBranch && $requiredYear)
<script>
    // إذا كان لديك SweetAlert2
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'warning',
            title: 'المخصصات المالية لعام {{ $requiredYear }}',
            text: 'يجب تحويل المخصصات المالية لعام {{ $requiredYear }} للخزينة الرئيسية لضمان عمل النظام بكفاءة',
            confirmButtonText: 'حسناً',
            confirmButtonColor: '#dc3545',
            showCancelButton: true,
            cancelButtonText: 'إجراء التحويل',
            cancelButtonColor: '#198754'
        }).then((result) => {
            if (!result.isConfirmed) {
                window.location.href = "{{ route('finance.vault-transfers.create') }}";
            }
        });
    }
</script>
@endif
@endsection