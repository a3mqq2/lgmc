@extends('layouts.'.get_area_name())
@section('title','الصفحه الرئيسيه')
@section('styles')
<style>
    /* ---------- Page Background ---------- */
    body {
        background: radial-gradient(circle at top left, #f5f7ff 0%, #ffffff 70%);
    }

    /* ---------- Cards ---------- */
    .card-animate {
        transition: transform .35s ease, box-shadow .35s ease;
    }
    .card-animate:hover {
        transform: translateY(-8px);
        box-shadow: 0 16px 32px rgba(0, 0, 0, .15);
    }

    .card-gradient-primary {
        background: linear-gradient(135deg, #7467F0, #5E9FF2);
        color: #fff;
    }
    .card-gradient-secondary {
        background: linear-gradient(135deg, #4A5568, #8E9AAF);
        color: #fff;
    }

    .card-gradient-basic
    {
      background: linear-gradient(135deg, #c11e12, #bc1d1a);
      color:#fff;
    }

    /* ---------- Typography ---------- */
    .section-heading {
        font-size: 1.4rem;
        font-weight: 700;
        letter-spacing: .5px;
    }
    .stat-count {
        font-size: 2.3rem;
        font-weight: 700;
    }

    /* ---------- Icons ---------- */
    .avatar-title {
        background: rgba(255,255,255,.20);
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    /* ---------- Tables ---------- */
    .table thead th {
        vertical-align: middle;
        background: #f0f3ff;
    }
</style>
@endsection
@section('content')


<div class="row g-3 mb-4">
   @php
       $libyanStatuses = [
           ['key'=>'under_approve', 'label'=>'طلبات الموقع'],
           ['key'=>'under_edit', 'label'=>'أطباء قيد التعديل'],
           ['key'=>'under_payment', 'label'=>'أطباء قيد الدفع'],
           ['key'=>'active', 'label'=>'أطباء  مفعليين'],
       ];
   @endphp

   @foreach($libyanStatuses as $stat)
       <div class="col-xl-3 col-md-6">
           <a href="{{ route(get_area_name().'.doctors.index', ['type'=>'libyan','membership_status'=>$stat['key']]) }}">
               <div class="card card-animate card-gradient-basic h-100">
                   <div class="card-body">
                       <div class="d-flex justify-content-between align-items-center">
                           <div>
                               <p class="mb-0" style="font-size: 17px!important;">{{ $stat['label'] }}</p>
                               <h2 class="stat-count mt-3">
                                   {{ \App\Models\Doctor::where('membership_status',$stat['key'])->where('type','libyan')->count() }}
                               </h2>
                           </div>
                           <span class="avatar-title fs-3"><i class="fa fa-user-doctor text-light"></i></span>
                       </div>
                   </div>
               </div>
           </a>
       </div>
   @endforeach
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