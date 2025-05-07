@extends('layouts.doctor')

@push('styles')
<style>
.switch { position: relative; display: inline-block; width: 50px; height: 24px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 24px; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
.switch input:checked + .slider { background-color: #28a745; }
.switch input:focus + .slider { box-shadow: 0 0 1px #28a745; }
.switch input:checked + .slider:before { transform: translateX(26px); }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div>
            <div class="d-flex">
                <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link fs-14 {{ request('overview') ? 'active' : '' }}" href="{{ route('doctor.dashboard', ['overview' => 1]) }}">
                            <i class="ri-airplay-fill d-inline-block d-m"></i> <span class="d-md-inline-block">بياناتي الآساسية</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-14 {{ request('licences') ? 'active' : '' }}" href="{{ route('doctor.dashboard', ['licences' => 1]) }}">
                            <i class="ri-list-unordered d-inline-block d-m"></i> <span class="d-md-inline-block">أذونات المزاولة</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-14 {{ request('facilities') ? 'active' : '' }}" href="{{ route('doctor.dashboard', ['facilities' => 1]) }}">
                            <i class="fa fa-hospital d-inline-block d-m"></i> <span class="d-md-inline-block">منشآتي الطبية</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-14 {{ request('requests') ? 'active' : '' }}" href="{{ route('doctor.dashboard', ['requests' => 1]) }}">
                            <i class="ri-folder-4-line d-inline-block d-m"></i> <span class="d-md-inline-block">اوراق الخارج</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-14 {{ request('invoices') ? 'active' : '' }}" href="{{ route('doctor.dashboard', ['invoices' => 1]) }}">
                            <i class="fa fa-file d-inline-block d-m"></i> <span class="d-md-inline-block">الفواتير</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-14 {{ request('change-password') ? 'active' : '' }}" href="{{ route('doctor.dashboard', ['change-password' => 1]) }}">
                            <i class="ri-lock-password-line d-inline-block d-m"></i> <span class="d-md-inline-block">تغيير كلمة المرور</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fs-14" href="{{ route('doctor.logout') }}">
                            <i class="ri-logout-box-line d-inline-block d-m"></i> <span class="d-md-inline-block">تسجيل خروج</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="tab-content pt-4 text-muted">
                <div class="tab-pane active">
                    @yield('dashboard-content')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
