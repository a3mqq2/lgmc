@extends('layouts.'.get_area_name())
@section('title','الصفحه الرئيسيه')
@section('content')



@if (auth()->user()->branch_id)
<div class="row">
    <div class="col-xl-12 col-md-12 ">
        <div class="card card-animate bg-orange text-light">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="h3 text-light mb-0">  رصيد الخزينة </p>
                        <h2 class="mt-4 text-light fw-semibold"><span class="counter-value" data-target="{{App\Models\Vault::where('branch_id', auth()->user()->branch_id)->first()->balance}}">{{number_format(App\Models\Vault::where('branch_id', auth()->user()->branch_id)->first()->balance,2)}} د.ل</span></h2>
                    </div>
                    <div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-light rounded-circle fs-2">
                                <i class="fa fa-vault text-light-pink"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endif


{{-- <div class="row">
    <div class="col-xl-4 col-md-6">
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
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Ticket::where('assigned_user_id', auth()->id())->where('status','new')->where('department', 'finance')->count()}}">{{App\Models\Ticket::where('assigned_user_id', auth()->id())->where('department', 'finance')->count()}}</span></h4>
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
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Ticket::where('assigned_user_id', auth()->id())->where('department', 'finance')->where('status','new')->count()}}">{{App\Models\Ticket::where('assigned_user_id', auth()->id())->where('department', 'finance')->where('status','new')->count()}}</span></h4>
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
                            <h4 class="fs-4 mb-3 text-white"><span class="counter-value" data-target="{{App\Models\Ticket::where('assigned_user_id', auth()->id())->where('status','complete')->where('department', 'finance')->count()}}">{{App\Models\Ticket::where('assigned_user_id', auth()->id())->where('status','complete')->where('department', 'finance')->count()}}</span></h4>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                        </div>
                    </div>
                </div><!-- end card body -->
            </div>
        </a>
    </div>

</div> --}}


@if (auth()->user()->permissions->where('name','financial-administration')->count() && !auth()->user()->branch_id)


<div class="row">
    <div class="col-xl-3 col-md-6">
       <a href="{{route(get_area_name().'.vaults.index')}}">
           <div class="card card-animate">
               <div class="card-body">
                   <div class="d-flex justify-content-between">
                       <div>
                           <p class="fw-medium text-muted mb-0"> الخزائن المالية </p>
                           <h2 class="mt-4 ff-secondary fw-semibold"><span class="counter-value" data-target="{{App\Models\Vault::count()}}">{{App\Models\Vault::count()}}</span></h2>
                       </div>
                       <div>
                           <div class="avatar-sm flex-shrink-0">
                               <span class="avatar-title bg-soft-success rounded-circle fs-2">
                                   <i class="fa fa-vault text-success"></i>
                               </span>
                           </div>
                       </div>
                   </div>
               </div>
           </div> 
       </a>
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

<!-- Dashboard init -->
<script src="/assets/js/pages/dashboard-analytics.init.js"></script>
@endsection