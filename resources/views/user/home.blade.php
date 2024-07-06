@extends('layouts.'.get_area_name())
@section('title','الصفحه الرئيسيه')
@section('content')
<div class="row">
    @can('doctors')
    <div class="col-xl-4 col-md-6">
        <a href="{{route(get_area_name().'.doctors.index')}}">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">الاطباء</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{App\Models\Doctor::where('branch_id', auth()->user()->branch_id)->count()}}">{{App\Models\Doctor::where('branch_id', auth()->user()->branch_id)->count()}}</span></h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-danger rounded-circle fs-2">
                                    <i class="fa fa-user-doctor text-danger"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div> 
        </a>
    </div>
    @endcan

    @can('medical_facilties')
    <div class="col-xl-4 col-md-6">
        <a href="{{route(get_area_name().'.medical-facilities.index')}}">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">المنشآت الطبية</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{App\Models\MedicalFacility::where('branch_id', auth()->user()->branch_id)->count()}}">{{App\Models\MedicalFacility::where('branch_id', auth()->user()->branch_id)->count()}}</span></h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-warning rounded-circle fs-2">
                                    <i class="fa fa-hospital text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div> 
        </a>
    </div>
    @endcan

    @can('branch_accounting')
    <div class="col-xl-4 col-md-6">
        <a href="{{route(get_area_name().'.vaults.index')}}">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0"> الخزائن المالية </p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{App\Models\Vault::where('branch_id', auth()->user()->branch_id)->count()}}">{{App\Models\Vault::where('branch_id', auth()->user()->branch_id)->count()}}</span></h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-success rounded-circle fs-2">
                                    <i class="fa fa-vault text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div> 
        </a>
    </div>
    @endcan
</div>


@can('doctor_licences')
<div class="row">
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
                            <p class="text-uppercase fw-medium text-white mb-3"> الاذونات السارية للاطباء </p>
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\Doctor')->where('status','active')->count()}}">{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\Doctor')->where('status','active')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>

    <div class="col-xl-3  col-md-6">
        <a href="{{route(get_area_name().'.licences.index', ['type' => 'doctors', 'status' => 'under_approve_branch'])}}">
            <div class="card bg-primary card-height-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-light text-white rounded-2 fs-2">
                                <i class="bx bx-shopping-bag"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-white mb-3"> اذونات قيد مراجعة الفرع  للاطباء </p>
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\Doctor')->where('status','under_approve_branch')->count()}}">{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\Doctor')->where('status','under_approve_branch')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>

    <div class="col-xl-3  col-md-6">
        <a href="{{route(get_area_name().'.licences.index', ['type' => 'doctors', 'status' => 'under_approve_admin'])}}">
            <div class="card bg-secondary card-height-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-light text-white rounded-2 fs-2">
                                <i class="bx bx-shopping-bag"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-white mb-3"> اذونات قيد مراجعة الادارة  للاطباء </p>
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\Doctor')->where('status','under_approve_admin')->count()}}">{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\Doctor')->where('status','under_approve_admin')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>

    <div class="col-xl-3  col-md-6">
        <a href="{{route(get_area_name().'.licences.index', ['type' => 'doctors', 'status' => 'under_payment'])}}">
            <div class="card bg-danger card-height-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-light text-white rounded-2 fs-2">
                                <i class="bx bx-shopping-bag"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-white mb-3"> اذونات قيد مراجعة المالية  للاطباء </p>
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\Doctor')->where('status','under_payment')->count()}}">{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\Doctor')->where('status','under_payment')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>


</div>
@endcan



@can('medical_faciltiesـlicences')
<div class="row">
    <div class="col-xl-3 col-md-6">
        <a href="{{route(get_area_name().'.licences.index', ['type' => 'facilities', 'status' => 'active'])}}">
            <div class="card bg-primary card-height-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-light text-white rounded-2 fs-2">
                                <i class="bx bx-shopping-bag"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-white mb-3"> الاذونات السارية للمنشات الطبية </p>
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\MedicalFacility')->where('status','active')->count()}}">{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\MedicalFacility')->where('status','active')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>

    <div class="col-xl-3  col-md-6">
        <a href="{{route(get_area_name().'.licences.index', ['type' => 'facilities', 'status' => 'under_approve_branch'])}}">
            <div class="card bg-info card-height-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-light text-white rounded-2 fs-2">
                                <i class="bx bx-shopping-bag"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-white mb-3"> اذونات قيد مراجعة الفرع  للمنشات الطبية </p>
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\MedicalFacility')->where('status','under_approve_branch')->count()}}">{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\MedicalFacility')->where('status','under_approve_branch')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>

    <div class="col-xl-3  col-md-6">
        <a href="{{route(get_area_name().'.licences.index', ['type' => 'facilities', 'status' => 'under_approve_admin'])}}">
            <div class="card bg-success card-height-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-light text-white rounded-2 fs-2">
                                <i class="bx bx-shopping-bag"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-white mb-3"> اذونات قيد مراجعة الادارة  للمنشات الطبية </p>
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\MedicalFacility')->where('status','under_approve_admin')->count()}}">{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\MedicalFacility')->where('status','under_approve_admin')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>

    <div class="col-xl-3  col-md-6">
        <a href="{{route(get_area_name().'.licences.index', ['type' => 'facilities', 'status' => 'under_payment'])}}">
            <div class="card bg-warning card-height-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-light text-white rounded-2 fs-2">
                                <i class="bx bx-shopping-bag"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-white mb-3"> اذونات قيد مراجعة المالية  للمنشات الطبية </p>
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\MedicalFacility')->where('status','under_payment')->count()}}">{{App\Models\Licence::where('branch_id', auth()->user()->branch_id)->where('licensable_type', 'App\Models\MedicalFacility')->where('status','under_payment')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>


</div>
@endcan

@endsection

@section('scripts')
<!-- apexcharts -->
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Vector map-->
<script src="/assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
<script src="/assets/libs/jsvectormap/maps/world-merc.js"></script>

<!-- Dashboard init -->
<script src="/assets/js/pages/dashboard-analytics.init.js"></script>
@endsection