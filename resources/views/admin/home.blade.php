@extends('layouts.'.get_area_name())
@section('title','الصفحه الرئيسيه')
@section('content')


<div class="row">
    {{-- <div class="col-xl-4 col-md-6">
        <a href="{{route(get_area_name().'.tickets.index', ['my' => '1'])}}">
            <div class="card bg-gradient-primary card-height-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-light text-white rounded-2 fs-2">
                                <i class="bx bx-file"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-white mb-3"> التذاكر المحالة لي </p>
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Ticket::where('assigned_user_id', auth()->id())->where('department', 'management')->count()}}">{{App\Models\Ticket::where('assigned_user_id', auth()->id())->where('department', 'management')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>


    <div class="col-xl-4 col-md-6">
        <a href="{{route(get_area_name().'.tickets.index', ['my' => '1', 'status' => 'new'])}}">
            <div class="card bg-gradient-danger card-height-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-light text-white rounded-2 fs-2">
                                <i class="bx bx-file"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-white mb-3"> التذاكر المحالة لي - الجديدة </p>
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Ticket::where('assigned_user_id', auth()->id())->where('department', 'management')->where('status','new')->count()}}">{{App\Models\Ticket::where('assigned_user_id', auth()->id())->where('department', 'management')->where('status','new')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>


    <div class="col-xl-4 col-md-6">
        <a href="{{route(get_area_name().'.tickets.index', ['my' => '1', 'status' => 'complete'])}}">
            <div class="card bg-gradient-info card-height-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-light text-white rounded-2 fs-2">
                                <i class="bx bx-file"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-white mb-3"> التذاكر المحالة لي - المكتملة </p>
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Ticket::where('assigned_user_id', auth()->id())->where('status','complete')->where('department', 'management')->count()}}">{{App\Models\Ticket::where('assigned_user_id', auth()->id())->where('status','complete')->where('department', 'management')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div> --}}

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
                                <span class="counter-value" data-target="{{App\Models\Doctor::count()}}">
                                    {{App\Models\Doctor::count()}}
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

    <!-- Libyan Doctors -->
    <div class="col-xl-3 col-md-6">
        <a href="{{route(get_area_name().'.doctors.index', ['type' => 'libyan'])}}">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">الأطباء الليبيين</p>
                            <h2 class="mt-4 ff-secondary fw-semibold">
                                <span class="counter-value" data-target="{{App\Models\Doctor::where('type', 'libyan')->count()}}">
                                    {{App\Models\Doctor::where('type', 'libyan')->count()}}
                                </span>
                            </h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                    <i class="fa fa-flag text-info"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </a>
    </div>

    <!-- Palestinian Doctors -->
    <div class="col-xl-3 col-md-6">
        <a href="{{route(get_area_name().'.doctors.index', ['type' => 'palestinian'])}}">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">الأطباء الفلسطينيين</p>
                            <h2 class="mt-4 ff-secondary fw-semibold">
                                <span class="counter-value" data-target="{{App\Models\Doctor::where('type', 'palestinian')->count()}}">
                                    {{App\Models\Doctor::where('type', 'palestinian')->count()}}
                                </span>
                            </h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-success rounded-circle fs-2">
                                    <i class="fa fa-flag text-success"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </a>
    </div>

    <!-- Foreign Doctors -->
    <div class="col-xl-3 col-md-6">
        <a href="{{route(get_area_name().'.doctors.index', ['type' => 'foreign'])}}">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">الأطباء الأجانب</p>
                            <h2 class="mt-4 ff-secondary fw-semibold">
                                <span class="counter-value" data-target="{{App\Models\Doctor::where('type', 'foreign')->count()}}">
                                    {{App\Models\Doctor::where('type', 'foreign')->count()}}
                                </span>
                            </h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-warning rounded-circle fs-2">
                                    <i class="fa fa-globe text-warning"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </a>
    </div>

    <!-- Visitor Doctors -->
    <div class="col-xl-3 col-md-6">
        <a href="{{route(get_area_name().'.doctors.index', ['type' => 'visitor'])}}">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">الأطباء الزائرين</p>
                            <h2 class="mt-4 ff-secondary fw-semibold">
                                <span class="counter-value" data-target="{{App\Models\Doctor::where('type', 'visitor')->count()}}">
                                    {{App\Models\Doctor::where('type', 'visitor')->count()}}
                                </span>
                            </h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-danger rounded-circle fs-2">
                                    <i class="fa fa-user-clock text-danger"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </a>
    </div>
    <div class="col-xl-3 col-md-6">
        <a href="{{route(get_area_name().'.users.index')}}">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">الموظفين</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{App\Models\User::count()}}">{{App\Models\User::count()}}</span></h2>
                        </div>
                        <div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-soft-info rounded-circle fs-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users text-info"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div> 
        </a>
    </div>
    <div class="col-xl-3 col-md-6">
        <a href="{{route(get_area_name().'.doctors.index')}}">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">الاطباء</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{App\Models\Doctor::count()}}">{{App\Models\Doctor::count()}}</span></h2>
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
    @endif

    @if (auth()->user()->permissions->where('name','manage-medical-facilities')->count())
    <div class="col-xl-3 col-md-6">
        <a href="{{route(get_area_name().'.medical-facilities.index')}}">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="fw-medium text-muted mb-0">المنشآت الطبية</p>
                            <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{App\Models\MedicalFacility::count()}}">{{App\Models\MedicalFacility::count()}}</span></h2>
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
    @endif


    @if (auth()->user()->permissions->where('name','manage-doctor-permits')->count())
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
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('licensable_type', 'App\Models\Doctor')->where('status','active')->count()}}">{{App\Models\Licence::where('licensable_type', 'App\Models\Doctor')->where('status','active')->count()}}</span></h4>
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
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('licensable_type', 'App\Models\Doctor')->where('status','under_approve_branch')->count()}}">{{App\Models\Licence::where('licensable_type', 'App\Models\Doctor')->where('status','under_approve_branch')->count()}}</span></h4>
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
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('licensable_type', 'App\Models\Doctor')->where('status','under_approve_admin')->count()}}">{{App\Models\Licence::where('licensable_type', 'App\Models\Doctor')->where('status','under_approve_admin')->count()}}</span></h4>
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
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('licensable_type', 'App\Models\Doctor')->where('status','under_payment')->count()}}">{{App\Models\Licence::where('licensable_type', 'App\Models\Doctor')->where('status','under_payment')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>
    @endif
    
    @if (auth()->user()->permissions->where('name','manage-medical-facilities')->count())

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
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('licensable_type', 'App\Models\MedicalFacility')->where('status','active')->count()}}">{{App\Models\Licence::where('licensable_type', 'App\Models\MedicalFacility')->where('status','active')->count()}}</span></h4>
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
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('licensable_type', 'App\Models\MedicalFacility')->where('status','under_approve_branch')->count()}}">{{App\Models\Licence::where('licensable_type', 'App\Models\MedicalFacility')->where('status','under_approve_branch')->count()}}</span></h4>
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
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('licensable_type', 'App\Models\MedicalFacility')->where('status','under_approve_admin')->count()}}">{{App\Models\Licence::where('licensable_type', 'App\Models\MedicalFacility')->where('status','under_approve_admin')->count()}}</span></h4>
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
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Licence::where('licensable_type', 'App\Models\MedicalFacility')->where('status','under_payment')->count()}}">{{App\Models\Licence::where('licensable_type', 'App\Models\MedicalFacility')->where('status','under_payment')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>

    @endif
</div>




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