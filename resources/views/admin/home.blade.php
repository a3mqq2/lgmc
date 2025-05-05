@extends('layouts.'.get_area_name())
@section('title','الصفحه الرئيسيه')

@section('content')

<div class="row">
    @if(auth()->user()->permissions->where('name','doctor-mail')->count())
        <!-- طلبات قيد الموافقة -->
        <div class="col-xl-3 col-md-6">
            <a href="{{ route(get_area_name().'.doctor-mails.index', ['status' => 'under_approve']) }}">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">طلبات قيد الموافقة</p>
                                <h2 class="mt-4 ff-secondary fw-semibold">
                                    <span class="counter-value" data-target="{{ \App\Models\DoctorMail::where('status','under_approve')->count() }}">
                                        {{ \App\Models\DoctorMail::where('status','under_approve')->count() }}
                                    </span>
                                </h2>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-warning rounded-circle fs-2">
                                        <i class="fa fa-envelope-open-text text-warning"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- طلبات قيد الدفع -->
        <div class="col-xl-3 col-md-6">
            <a href="{{ route(get_area_name().'.doctor-mails.index', ['status' => 'under_payment']) }}">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">طلبات قيد الدفع</p>
                                <h2 class="mt-4 ff-secondary fw-semibold">
                                    <span class="counter-value" data-target="{{ \App\Models\DoctorMail::where('status','under_payment')->count() }}">
                                        {{ \App\Models\DoctorMail::where('status','under_payment')->count() }}
                                    </span>
                                </h2>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                        <i class="fa fa-dollar-sign text-info"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- طلبات مكتملة -->
        <div class="col-xl-3 col-md-6">
            <a href="{{ route(get_area_name().'.doctor-mails.index', ['status' => 'done']) }}">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">طلبات مكتملة</p>
                                <h2 class="mt-4 ff-secondary fw-semibold">
                                    <span class="counter-value" data-target="{{ \App\Models\DoctorMail::where('status','done')->count() }}">
                                        {{ \App\Models\DoctorMail::where('status','done')->count() }}
                                    </span>
                                </h2>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-success rounded-circle fs-2">
                                        <i class="fa fa-check-circle text-success"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
</div>

<div class="row">
    @if (auth()->user()->permissions->where('name','manage-doctors')->count())
        <!-- Total Doctors -->
        <div class="col-xl-3 col-md-6">
            <a href="{{route(get_area_name().'.doctors.index')}}">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">إجمالي الأطباء</p>
                                <h2 class="mt-4 ff-secondary fw-semibold">
                                    <span class="counter-value" data-target="{{\App\Models\Doctor::count()}}">
                                        {{\App\Models\Doctor::count()}}
                                    </span>
                                </h2>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-primary rounded-circle fs-2">
                                        <i class="fa fa-user-doctor text-primary"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- ... بقية ويدجات الأطباء والخيارات الأخرى ... -->
    @endif

    @if (auth()->user()->permissions->where('name','manage-medical-facilities')->count())
        <!-- Medical Facilities Widget -->
        <div class="col-xl-3 col-md-6">
            <a href="{{route(get_area_name().'.medical-facilities.index')}}">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-medium text-muted mb-0">المنشآت الطبية</p>
                                <h2 class="mt-4 ff-secondary fw-semibold">
                                    <span class="counter-value" data-target="{{\App\Models\MedicalFacility::count()}}">
                                        {{\App\Models\MedicalFacility::count()}}
                                    </span>
                                </h2>
                            </div>
                            <div>
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-warning rounded-circle fs-2">
                                        <i class="fa fa-hospital text-warning"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif

    @if (auth()->user()->permissions->where('name','manage-doctor-permits')->count())
        <!-- Doctor Licences Active -->
        <div class="col-xl-3 col-md-6">
            <a href="{{route(get_area_name().'.licences.index', ['type' => 'doctors', 'status' => 'active'])}}">
                <div class="card bg-success card-height-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-light text-white rounded-2 fs-2">
                                    <i class="bx bx-shopping-bag"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-uppercase fw-medium text-white mb-3">الأذونات السارية للأطباء</p>
                                <h4 class="fs-4 mb-3 text-white">
                                    <span class="counter-value" data-target="{{\App\Models\Licence::where('licensable_type','App\Models\Doctor')->where('status','active')->count()}}">
                                        {{\App\Models\Licence::where('licensable_type','App\Models\Doctor')->where('status','active')->count()}}
                                    </span>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- ... ويدجات أخرى للأذونات ... -->
    @endif

</div>

@endsection

@section('scripts')
<!-- apexcharts -->
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>
<!-- Vector map -->
<script src="/assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
<script src="/assets/libs/jsvectormap/maps/world-merc.js"></script>
<!-- Dashboard init -->
<script src="/assets/js/pages/dashboard-analytics.init.js"></script>
@endsection
