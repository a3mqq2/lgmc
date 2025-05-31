@extends('layouts.' . get_area_name())

@section('title', 'الصفحة الرئيسية')

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
@if(auth()->user()->permissions->where('name','doctor-foreign')->count())
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card text-center card-gradient-secondary">
                <div class="card-body py-3">
                    <p class="m-0 section-heading text-light">الأطـــــــباء الأجــــــــــــانب</p>
                </div>
            </div>
        </div>

        @php
            $foreignStatuses = [
                ['key'=>'under_approve', 'label'=>'طلبات الموقع'],
                ['key'=>'under_edit', 'label'=>'أطباء قيد التعديل'],
                ['key'=>'under_payment', 'label'=>'أطباء قيد الدفع'],
                ['key'=>'active', 'label'=>'أطباء  مفعليين'],
            ];
        @endphp

        @foreach($foreignStatuses as $stat)
            <div class="col-xl-3 col-md-6">
                <a href="{{ route(get_area_name().'.doctors.index', ['type'=>'foreign','membership_status'=>$stat['key']]) }}">
                    <div class="card card-animate card-gradient-secondary h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="mb-0" style="font-size: 17px!important;">{{ $stat['label'] }}</p>
                                    <h2 class="stat-count mt-3">
                                        {{ \App\Models\Doctor::where('membership_status',$stat['key'])->where('type','foreign')->count() }}
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
@endif

@if(auth()->user()->permissions->where('name','doctor-palestinian')->count())
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card text-center card-gradient-primary">
                <div class="card-body py-3">
                    <p class="m-0 section-heading text-light">الأطـــــــباء الفلســــطينيين</p>
                </div>
            </div>
        </div>

        {{-- تحت المراجعة --}}
        <div class="col-xl-3 col-md-6">
            <a href="{{ route(get_area_name().'.doctors.index', ['type'=>'palestinian','membership_status'=>'under_approve']) }}">
                <div class="card card-animate card-gradient-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0" style="font-size: 17px!important;">طلبات الموقع</p>
                                <h2 class="stat-count mt-3">
                                    {{ \App\Models\Doctor::where('membership_status','under_approve')->where('type','palestinian')->count() }}
                                </h2>
                            </div>
                            <span class="avatar-title fs-3"><i class="fa fa-user-doctor text-light"></i></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- قيد التعديل --}}
        <div class="col-xl-3 col-md-6">
            <a href="{{ route(get_area_name().'.doctors.index', ['type'=>'palestinian','membership_status'=>'under_edit']) }}">
                <div class="card card-animate card-gradient-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0" style="font-size: 17px!important;">أطباء قيد التعديل</p>
                                <h2 class="stat-count mt-3">
                                    {{ \App\Models\Doctor::where('membership_status','under_edit')->where('type','palestinian')->count() }}
                                </h2>
                            </div>
                            <span class="avatar-title fs-3"><i class="fa fa-user-doctor text-light"></i></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- قيد الدفع --}}
        <div class="col-xl-3 col-md-6">
            <a href="{{ route(get_area_name().'.doctors.index', ['type'=>'palestinian','membership_status'=>'under_payment']) }}">
                <div class="card card-animate card-gradient-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0" style="font-size: 17px!important;">أطباء قيد الدفع</p>
                                <h2 class="stat-count mt-3">
                                    {{ \App\Models\Doctor::where('membership_status','under_payment')->where('type','palestinian')->count() }}
                                </h2>
                            </div>
                            <span class="avatar-title fs-3"><i class="fa fa-user-doctor text-light"></i></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        {{-- منتهية --}}
        <div class="col-xl-3 col-md-6">
            <a href="{{ route(get_area_name().'.doctors.index', ['type'=>'palestinian','membership_status'=>'active']) }}">
                <div class="card card-animate card-gradient-primary h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0" style="font-size: 17px!important;">أطباء  مفعليين</p>
                                <h2 class="stat-count mt-3">
                                    {{ \App\Models\Doctor::where('membership_status','active')->where('type','palestinian')->count() }}
                                </h2>
                            </div>
                            <span class="avatar-title fs-3"><i class="fa fa-user-doctor text-light"></i></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        @if(auth()->user()->vault)
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">الحركات المالية اليوم</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-center align-middle">
                                <thead>
                                    <tr>
                                        <th>إشاري</th>
                                        <th>الخزينة</th>
                                        <th>المستخدم</th>
                                        <th>الوصف</th>
                                        <th class="bg-danger text-light">سحب</th>
                                        <th class="bg-success text-light">إيداع</th>
                                        <th>التاريخ والوقت</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(auth()->user()->vault->transactions()->whereDate('created_at', Carbon\Carbon::today())->get() as $transaction)
                                        <tr>
                                            <td>{{ $transaction->id }}</td>
                                            <td>{{ $transaction->vault->name }}</td>
                                            <td>{{ $transaction->user->name }}</td>
                                            <td>{{ $transaction->desc }}</td>
                                            <td>{{ $transaction->type === 'withdrawal' ? $transaction->amount . ' د.ل' : '' }}</td>
                                            <td>{{ $transaction->type === 'deposit' ? $transaction->amount . ' د.ل' : '' }}</td>
                                            <td>{{ $transaction->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif



<div class="row g-3">
    @if(auth()->user()->permissions->where('name','doctor-mail')->count())
        @php
            $mailStats = [
                ['status'=>'under_approve', 'label'=>'طلبات قيد الموافقة', 'icon'=>'fa-envelope-open-text', 'color'=>'warning'],
                ['status'=>'under_payment', 'label'=>'طلبات قيد الدفع', 'icon'=>'fa-dollar-sign', 'color'=>'info'],
                ['status'=>'under_approve', 'label'=>'طلبات قيد الموافقة', 'icon'=>'fa-envelope-open-text', 'color'=>'warning', 'counter'=>'under_proccess'],
                ['status'=>'done', 'label'=>'طلبات مكتملة', 'icon'=>'fa-check-circle', 'color'=>'success'],
            ];
        @endphp
        @foreach($mailStats as $mail)
            <div class="col-xl-3 col-md-6">
                <a href="{{ route(get_area_name().'.doctor-mails.index', ['status'=>$mail['status']]) }}">
                    <div class="card card-animate h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="fw-medium text-muted mb-0">{{ $mail['label'] }}</p>
                                    <h2 class="stat-count mt-3">
                                        {{ \App\Models\DoctorMail::where('status', $mail['counter'] ?? $mail['status'])->count() }}
                                    </h2>
                                </div>
                                <span class="avatar-title bg-soft-{{ $mail['color'] }} rounded-circle fs-3">
                                    <i class="fa {{ $mail['icon'] }} text-{{ $mail['color'] }}"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    @endif
</div>
@endsection

@section('scripts')
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="/assets/libs/jsvectormap/js/jsvectormap.min.js"></script>
<script src="/assets/libs/jsvectormap/maps/world-merc.js"></script>
<script src="/assets/js/pages/dashboard-analytics.init.js"></script>
@endsection
