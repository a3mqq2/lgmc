<!doctype html>
<html lang="ar" dir="rtl" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">
<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Libyan General Medical Council</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Libyan General Medical Council" name="description" />
    <link rel="shortcut icon" href="{{asset('/assets/images/lgmc-dark.png?v=44')}}">
    <script src="{{asset('/assets/js/layout.js')}}"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai&family=Lato:wght@700&display=swap" rel="stylesheet">
    <link href="{{asset('/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
    <link href="{{asset('/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('/assets/css/app-rtl.min.css?v=34')}}" rel="stylesheet" type="text/css" />
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        .cke_editable {
            font-family: 'Amiri', serif;
        }
    </style>
    @yield('styles')
    <link href="{{asset('/assets/css/custom.min.css?v=')}}" rel="stylesheet" type="text/css" />
    @include('layouts.styles')
    <meta content="ar" name="language" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .bg-secondary {background-color: #6c757d !important;}
        .bg-success {background-color: #28a745 !important;}
        .bg-danger {background-color: #dc3545 !important;}
        .bg-warning {background-color: #ffc107 !important;}
        .bg-info {background-color: #17a2b8 !important;}
        .bg-light {background-color: #f8f9fa !important;}
        .bg-dark {background-color: #343a40 !important;}
        .bg-white {background-color: #ffffff !important;}
        .bg-transparent {background-color: transparent !important;}
        .bg-pink {background-color: #ff69b4 !important;}
        .bg-purple {background-color: #6f42c1 !important;}
        .bg-teal {background-color: #20c997 !important;}
        .bg-orange {background-color: #fd7e14 !important;}
        .bg-brown {background-color: #8b4513 !important;}
        .bg-lime {background-color: #cddc39 !important;}
        .bg-cyan {background-color: #00bcd4 !important;}
        .bg-gradient-primary {background: linear-gradient(to right, #007bff, #00aaff) !important;}
        .bg-gradient-secondary {background: linear-gradient(to right, #6c757d, #495057) !important;}
        .bg-gradient-success {background: linear-gradient(to right, #28a745, #218838) !important;}
        .bg-gradient-danger {background: linear-gradient(to right, #dc3545, #c82333) !important;}
        .bg-gradient-warning {background: linear-gradient(to right, #ffc107, #e0a800) !important;}
        .bg-gradient-info {background: linear-gradient(to right, #17a2b8, #138496) !important;}
        .bg-gradient-light {background: linear-gradient(to right, #f8f9fa, #e2e6ea) !important;}
        .bg-gradient-dark {background: linear-gradient(to right, #343a40, #23272b) !important;}
        .bg-gradient-pink {background: linear-gradient(to right, #ff69b4, #ff1493) !important;}
        .bg-gradient-purple {background: linear-gradient(to right, #6f42c1, #5a2a9c) !important;}
        .bg-gradient-teal {background: linear-gradient(to right, #20c997, #17a2b8) !important;}
        .bg-gradient-orange {background: linear-gradient(to right, #fd7e14, #e76f00) !important;}
        .bg-gradient-brown {background: linear-gradient(to right, #8b4513, #7a3e2a) !important;}
        .bg-gradient-lime {background: linear-gradient(to right, #cddc39, #8bc34a) !important;}
        .bg-gradient-cyan {background: linear-gradient(to right, #00bcd4, #009688) !important;}
        .text-light-primary {color: rgba(0, 123, 255, 0.7) !important;}
        .text-light-secondary {color: rgba(108, 117, 125, 0.7) !important;}
        .text-light-success {color: rgba(40, 167, 69, 0.7) !important;}
        .text-light-danger {color: rgba(220, 53, 69, 0.7) !important;}
        .text-light-warning {color: rgba(255, 193, 7, 0.7) !important;}
        .text-light-info {color: rgba(23, 162, 184, 0.7) !important;}
        .text-light-dark {color: rgba(52, 58, 64, 0.7) !important;}
        .text-light-white {color: rgba(255, 255, 255, 0.7) !important;}
        .text-light-pink {color: rgba(255, 105, 180, 0.7) !important;}
        .text-light-purple {color: rgba(111, 66, 193, 0.7) !important;}
        .text-light-teal {color: rgba(32, 201, 151, 0.7) !important;}
        .text-light-orange {color: rgba(253, 126, 20, 0.7) !important;}
        .text-light-brown {color: rgba(139, 69, 19, 0.7) !important;}
        .text-light-lime {color: rgba(205, 220, 57, 0.7) !important;}
        .text-light-cyan {color: rgba(0, 188, 212, 0.7) !important;}
        .border-light-primary {border: 1px solid rgba(0, 123, 255, 0.7) !important;}
        .border-light-secondary {border: 1px solid rgba(108, 117, 125, 0.7) !important;}
        .border-light-success {border: 1px solid rgba(40, 167, 69, 0.7) !important;}
        .border-light-danger {border: 1px solid rgba(220, 53, 69, 0.7) !important;}
        .border-light-warning {border: 1px solid rgba(255, 193, 7, 0.7) !important;}
        .border-light-info {border: 1px solid rgba(23, 162, 184, 0.7) !important;}
        .border-light-dark {border: 1px solid rgba(52, 58, 64, 0.7) !important;}
        .border-light-white {border: 1px solid rgba(255, 255, 255, 0.7) !important;}
        .border-light-pink {border: 1px solid rgba(255, 105, 180, 0.7) !important;}
        .border-light-purple {border: 1px solid rgba(111, 66, 193, 0.7) !important;}
        .border-light-teal {border: 1px solid rgba(32, 201, 151, 0.7) !important;}
        .border-light-orange {border: 1px solid rgba(253, 126, 20, 0.7) !important;}
        .border-light-brown {border: 1px solid rgba(139, 69, 19, 0.7) !important;}
        .border-light-lime {border: 1px solid rgba(205, 220, 57, 0.7) !important;}
        .border-light-cyan {border: 1px solid rgba(0, 188, 212, 0.7) !important;}
    </style>
    <style>
        .bg-finance {background-color: #1abc9c; color: #ffffff;}
        .bg-operation {background-color: #3498db; color: #ffffff;}
        .bg-management {background-color: #f1c40f; color: #ffffff;}
        .bg-it {background-color: #9b59b6; color: #ffffff;}
        .bg-question {background-color: #2980b9; color: #ffffff;}
        .bg-suggestion {background-color: #2ecc71; color: #ffffff;}
        .bg-complaint {background-color: #e74c3c; color: #ffffff;}
        .bg-new {background-color: #3498db; color: #ffffff;}
        .bg-pending {background-color: #f39c12; color: #ffffff;}
        .bg-customer-reply {background-color: #8e44ad; color: #ffffff;}
        .bg-complete {background-color: #2ecc71; color: #ffffff;}
        .bg-user-reply {background-color: #e67e22; color: #ffffff;}
        .bg-low {background-color: #95a5a6; color: #ffffff;}
        .bg-medium {background-color: #f1c40f; color: #ffffff;}
        .bg-high {background-color: #e74c3c; color: #ffffff;}
        .badge {display: inline-block; padding: 0.3rem 0.6rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; text-align: center; white-space: nowrap;}
    </style>
</head>
<body>
    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <div class="navbar-brand-box horizontal-logo">
                            <a class="logo lgmc-dark">
                                <span class="logo-sm">
                                    <img src="{{asset('/assets/images/lgmc-dark.png?v=44')}}" alt="" height="50">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('/assets/images/logo-primary.png')}}" alt="" height="17">
                                </span>
                            </a>
                            <a href="{{route('sections')}}" class="logo logo-light">
                                <span class="favicon">
                                    <img src="{{asset('/assets/images/lgmc-dark.png?v=44')}}" alt="" height="50">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('/assets/images/lgmc-light.png?v=2')}}" alt="" height="17">
                                </span>
                            </a>
                        </div>
                        <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                            <span class="hamburger-icon open">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dropdown d-md-none topbar-head-dropdown header-item">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-search fs-22"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                                <form class="p-3">
                                    <div class="form-group m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search ...">
                                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button bg-white" class="btn" id="page-header-user-dropdown2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="#" alt="" width="70">
                            </button>
                            <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user" src="{{asset('https://ui-avatars.com/api/?name='.implode('+',explode(' ',Auth::user()->name)))}}&background=fcd106&color=fff" alt="Header Avatar">
                                    <span class="text-start ms-xl-2">
                                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{Auth::user()->name}}</span>
                                    </span>
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <h6 class="dropdown-header">مرحبـاً {{Auth::user()->name}} !</h6>
                                @if (auth()->user()->admin)
                                <a class="dropdown-item" href="{{route(get_area_name().'.index')}}"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> لوحة مدير النظام</a>
                                @endif
                                @if (auth()->user()->seller)
                                <a class="dropdown-item" href="{{route('seller.index')}}"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> لوحة مندوب المبيعات</a>
                                @endif
                                @if (auth()->user()->supplier)
                                <a class="dropdown-item" href="{{route('supplier.index')}}"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> لوحة المورد</a>
                                @endif
                                <a class="dropdown-item" href="#" onclick="document.getElementById('logout-form').submit()"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle">تسجيل الخــروج</span></a>
                                <form action="{{route('logout')}}" method="GET" id="logout-form">@csrf</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="app-menu navbar-menu">
            <div class="navbar-brand-box">
                <a class="logo lgmc-dark">
                    <span class="logo-sm">
                        <img src="{{asset('/assets/images/lgmc-dark.png?v=44')}}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('/assets/images/lgmc-light.png?v=2')}}" alt="" height="35">
                    </span>
                </a>
                <a href="{{route('sections')}}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{asset('/assets/images/lgmc-dark.png?v=44')}}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('/assets/images/lgmc-light.png?v=2')}}" alt="" height="80">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>
            <div id="scrollbar">
                <div class="container-fluid">
                    <div class="p-3">
                        <form action="{{ route(get_area_name().'.search') }}" method="GET">
                            <div class="input-group">
                                <input type="text" name="code" placeholder="ابحث بكود الطبيب" class="form-control">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fa fa-search"></i> بحث
                                </button>
                            </div>
                            <div class="input-group mt-2">
                                <input type="text" name="phone" placeholder="ابحث برقم هاتف الطبيب" class="form-control">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fa fa-search"></i> بحث
                                </button>
                            </div>
                        </form>
                    </div>
                    <div id="two-column-menu"></div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="nav-item mt-3">
                            <a class="nav-link menu-link" href="{{route(get_area_name().'.home')}}">
                                <i class="ri-dashboard-line"></i> <span>الرئيسية</span>
                            </a>
                        </li>
                        @include('layouts.menus.admin')
                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#" onclick="document.getElementById('logout-form').submit()">
                                <i class="ri-logout-circle-line"></i> <span>تسجيـل الخـروج</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="vertical-overlay"></div>
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">@yield('title')</h4>
                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">@yield('area')</a></li>
                                        @yield('breadcrumb')
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="app">
                        <div class="col-12 mt-1">
                            <div class="my-2">@include('layouts.messages')</div>
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 text-centeer">
                            {{__('Copyright © 2023 Libyan General Medical Council . All rights reserved')}}
                            <br>
                            <small>Development By <a href="https://hululit.ly">HULUL IT</a></small>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <script src="{{asset('/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('/assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('/assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('/assets/js/plugins.js')}}"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="{{asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    @yield('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.2/css/pikaday.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.8.2/pikaday.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
    <script src="{{asset('/assets/js/app.js?v=2')}}"></script>
    <script src="{{asset('/assets/js/custom.js')}}"></script>
    @if (session()->get("modal") && $errors->any())
    <script>
        let modalId = '{{session()->get("modal")}}';
        var myModal = new bootstrap.Modal(document.getElementById(modalId), {})
        myModal.show();
        errorSound.play();
    </script>
    @endif
    @if (session()->has('success'))
    <script>
        successSound.play();
    </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[name="passport_number"]').forEach(function (input) {
                input.addEventListener('input', function () {
                    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('input[type="date"], .date-picker').forEach(function(field) {
                let picker = new Litepicker({
                    element: field,
                    format: "YYYY-MM-DD",
                    singleMode: true,
                    autoApply: true,
                    allowRepick: true,
                    minDate: "1900-01-01",
                    lang: "en",
                    onSelect: function(date) {
                        let selectedDate = date.format("YYYY-MM-DD");
                        field.value = selectedDate;
                        field.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                });
                field.removeAttribute("placeholder");
                field.addEventListener("blur", function() {
                    let dateValue = field.value.trim();
                    if (dateValue) {
                        let parsedDate = new Date(dateValue);
                        let formattedDate = parsedDate.getFullYear() + '-' +
                                            String(parsedDate.getMonth() + 1).padStart(2, '0') + '-' +
                                            String(parsedDate.getDate()).padStart(2, '0');
                        field.value = formattedDate;
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select').select2({
                width: '100%'
            });
        });
    </script>
</body>
</html>
