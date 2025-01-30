<!doctype html>
<html lang="ar" dir="rtl" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">


<head>

    <meta charset="utf-8" />
    <title>@yield('title') | Libyan General Medical Council </title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Libyan General Medical Council " name="description" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('/assets/images/lgmc-dark.png?v=44')}}">

    <!-- Layout config Js -->
    <script src="{{asset('/assets/js/layout.js')}}"></script>
    <link href="{{asset('/css/app.css?v=34')}}" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai&family=Lato:wght@700&display=swap" rel="stylesheet">
    <!-- Bootstrap Css -->
    <link href="{{asset('/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.5/css/selectize.bootstrap5.min.css" integrity="sha512-w4sRMMxzHUVAyYk5ozDG+OAyOJqWAA+9sySOBWxiltj63A8co6YMESLeucKwQ5Sv7G4wycDPOmlHxkOhPW7LRg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Icons Css -->
    <link href="{{asset('/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('/assets/css/app-rtl.min.css?v=34')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        /* Extended Background Colors */
     
        .bg-secondary {
            background-color: #6c757d !important; /* Gray */
        }
        .bg-success {
            background-color: #28a745 !important; /* Green */
        }
        .bg-danger {
            background-color: #dc3545 !important; /* Red */
        }
        .bg-warning {
            background-color: #ffc107 !important; /* Yellow */
        }
        .bg-info {
            background-color: #17a2b8 !important; /* Light Blue */
        }
        .bg-light {
            background-color: #f8f9fa !important; /* Light Gray */
        }
        .bg-dark {
            background-color: #343a40 !important; /* Dark Gray */
        }
        .bg-white {
            background-color: #ffffff !important; /* White */
        }
        .bg-transparent {
            background-color: transparent !important;
        }
        .bg-pink {
            background-color: #ff69b4 !important; /* Pink */
        }
        .bg-purple {
            background-color: #6f42c1 !important; /* Purple */
        }
        .bg-teal {
            background-color: #20c997 !important; /* Teal */
        }
        .bg-orange {
            background-color: #fd7e14 !important; /* Orange */
        }
        .bg-brown {
            background-color: #8b4513 !important; /* Brown */
        }
        .bg-lime {
            background-color: #cddc39 !important; /* Lime */
        }
        .bg-cyan {
            background-color: #00bcd4 !important; /* Cyan */
        }
        /* Gradient Backgrounds */
        .bg-gradient-primary {
            background: linear-gradient(to right, #007bff, #00aaff) !important; /* Blue Gradient */
        }
        .bg-gradient-secondary {
            background: linear-gradient(to right, #6c757d, #495057) !important; /* Gray Gradient */
        }
        .bg-gradient-success {
            background: linear-gradient(to right, #28a745, #218838) !important; /* Green Gradient */
        }
        .bg-gradient-danger {
            background: linear-gradient(to right, #dc3545, #c82333) !important; /* Red Gradient */
        }
        .bg-gradient-warning {
            background: linear-gradient(to right, #ffc107, #e0a800) !important; /* Yellow Gradient */
        }
        .bg-gradient-info {
            background: linear-gradient(to right, #17a2b8, #138496) !important; /* Light Blue Gradient */
        }
        .bg-gradient-light {
            background: linear-gradient(to right, #f8f9fa, #e2e6ea) !important; /* Light Gray Gradient */
        }
        .bg-gradient-dark {
            background: linear-gradient(to right, #343a40, #23272b) !important; /* Dark Gray Gradient */
        }
        .bg-gradient-pink {
            background: linear-gradient(to right, #ff69b4, #ff1493) !important; /* Pink Gradient */
        }
        .bg-gradient-purple {
            background: linear-gradient(to right, #6f42c1, #5a2a9c) !important; /* Purple Gradient */
        }
        .bg-gradient-teal {
            background: linear-gradient(to right, #20c997, #17a2b8) !important; /* Teal Gradient */
        }
        .bg-gradient-orange {
            background: linear-gradient(to right, #fd7e14, #e76f00) !important; /* Orange Gradient */
        }
        .bg-gradient-brown {
            background: linear-gradient(to right, #8b4513, #7a3e2a) !important; /* Brown Gradient */
        }
        .bg-gradient-lime {
            background: linear-gradient(to right, #cddc39, #8bc34a) !important; /* Lime Gradient */
        }
        .bg-gradient-cyan {
            background: linear-gradient(to right, #00bcd4, #009688) !important; /* Cyan Gradient */
        }
        /* Light Text Colors */
        .text-light-primary {
            color: rgba(0, 123, 255, 0.7) !important; /* Light Blue */
        }
        .text-light-secondary {
            color: rgba(108, 117, 125, 0.7) !important; /* Light Gray */
        }
        .text-light-success {
            color: rgba(40, 167, 69, 0.7) !important; /* Light Green */
        }
        .text-light-danger {
            color: rgba(220, 53, 69, 0.7) !important; /* Light Red */
        }
        .text-light-warning {
            color: rgba(255, 193, 7, 0.7) !important; /* Light Yellow */
        }
        .text-light-info {
            color: rgba(23, 162, 184, 0.7) !important; /* Light Blue */
        }
        .text-light-dark {
            color: rgba(52, 58, 64, 0.7) !important; /* Light Dark Gray */
        }
        .text-light-white {
            color: rgba(255, 255, 255, 0.7) !important; /* Light White */
        }
        .text-light-pink {
            color: rgba(255, 105, 180, 0.7) !important; /* Light Pink */
        }
        .text-light-purple {
            color: rgba(111, 66, 193, 0.7) !important; /* Light Purple */
        }
        .text-light-teal {
            color: rgba(32, 201, 151, 0.7) !important; /* Light Teal */
        }
        .text-light-orange {
            color: rgba(253, 126, 20, 0.7) !important; /* Light Orange */
        }
        .text-light-brown {
            color: rgba(139, 69, 19, 0.7) !important; /* Light Brown */
        }
        .text-light-lime {
            color: rgba(205, 220, 57, 0.7) !important; /* Light Lime */
        }
        .text-light-cyan {
            color: rgba(0, 188, 212, 0.7) !important; /* Light Cyan */
        
        /* Light Borders */
        .border-light-primary {
            border: 1px solid rgba(0, 123, 255, 0.7) !important; /* Light Blue */
        }
        .border-light-secondary {
            border: 1px solid rgba(108, 117, 125, 0.7) !important; /* Light Gray */
        }
        .border-light-success {
            border: 1px solid rgba(40, 167, 69, 0.7) !important; /* Light Green */
        }
        .border-light-danger {
            border: 1px solid rgba(220, 53, 69, 0.7) !important; /* Light Red */
        }
        .border-light-warning {
            border: 1px solid rgba(255, 193, 7, 0.7) !important; /* Light Yellow */
        }
        .border-light-info {
            border: 1px solid rgba(23, 162, 184, 0.7) !important; /* Light Blue */
        }
        .border-light-dark {
            border: 1px solid rgba(52, 58, 64, 0.7) !important; /* Light Dark Gray */
        }
        .border-light-white {
            border: 1px solid rgba(255, 255, 255, 0.7) !important; /* Light White */
        }
        .border-light-pink {
            border: 1px solid rgba(255, 105, 180, 0.7) !important; /* Light Pink */
        }
        .border-light-purple {
            border: 1px solid rgba(111, 66, 193, 0.7) !important; /* Light Purple */
        }
        .border-light-teal {
            border: 1px solid rgba(32, 201, 151, 0.7) !important; /* Light Teal */
        }
        .border-light-orange {
            border: 1px solid rgba(253, 126, 20, 0.7) !important; /* Light Orange */
        }
        .border-light-brown {
            border: 1px solid rgba(139, 69, 19, 0.7) !important; /* Light Brown */
        }
        .border-light-lime {
            border: 1px solid rgba(205, 220, 57, 0.7) !important; /* Light Lime */
        }
        .border-light-cyan {
            border: 1px solid rgba(0, 188, 212, 0.7) !important; /* Light Cyan */
        }
        </style>
        

        <style>
            /* Extended Background Colors */
         
            .bg-secondary {
                background-color: #6c757d !important; /* Gray */
            }
            .bg-success {
                background-color: #28a745 !important; /* Green */
            }
            .bg-danger {
                background-color: #dc3545 !important; /* Red */
            }
            .bg-warning {
                background-color: #ffc107 !important; /* Yellow */
            }
            .bg-info {
                background-color: #17a2b8 !important; /* Light Blue */
            }
            .bg-light {
                background-color: #f8f9fa !important; /* Light Gray */
            }
            .bg-dark {
                background-color: #343a40 !important; /* Dark Gray */
            }
            .bg-white {
                background-color: #ffffff !important; /* White */
            }
            .bg-transparent {
                background-color: transparent !important;
            }
            .bg-pink {
                background-color: #ff69b4 !important; /* Pink */
            }
            .bg-purple {
                background-color: #6f42c1 !important; /* Purple */
            }
            .bg-teal {
                background-color: #20c997 !important; /* Teal */
            }
            .bg-orange {
                background-color: #fd7e14 !important; /* Orange */
            }
            .bg-brown {
                background-color: #8b4513 !important; /* Brown */
            }
            .bg-lime {
                background-color: #cddc39 !important; /* Lime */
            }
            .bg-cyan {
                background-color: #00bcd4 !important; /* Cyan */
            }
            /* Gradient Backgrounds */
            .bg-gradient-primary {
                background: linear-gradient(to right, #007bff, #00aaff) !important; /* Blue Gradient */
            }
            .bg-gradient-secondary {
                background: linear-gradient(to right, #6c757d, #495057) !important; /* Gray Gradient */
            }
            .bg-gradient-success {
                background: linear-gradient(to right, #28a745, #218838) !important; /* Green Gradient */
            }
            .bg-gradient-danger {
                background: linear-gradient(to right, #dc3545, #c82333) !important; /* Red Gradient */
            }
            .bg-gradient-warning {
                background: linear-gradient(to right, #ffc107, #e0a800) !important; /* Yellow Gradient */
            }
            .bg-gradient-info {
                background: linear-gradient(to right, #17a2b8, #138496) !important; /* Light Blue Gradient */
            }
            .bg-gradient-light {
                background: linear-gradient(to right, #f8f9fa, #e2e6ea) !important; /* Light Gray Gradient */
            }
            .bg-gradient-dark {
                background: linear-gradient(to right, #343a40, #23272b) !important; /* Dark Gray Gradient */
            }
            .bg-gradient-pink {
                background: linear-gradient(to right, #ff69b4, #ff1493) !important; /* Pink Gradient */
            }
            .bg-gradient-purple {
                background: linear-gradient(to right, #6f42c1, #5a2a9c) !important; /* Purple Gradient */
            }
            .bg-gradient-teal {
                background: linear-gradient(to right, #20c997, #17a2b8) !important; /* Teal Gradient */
            }
            .bg-gradient-orange {
                background: linear-gradient(to right, #fd7e14, #e76f00) !important; /* Orange Gradient */
            }
            .bg-gradient-brown {
                background: linear-gradient(to right, #8b4513, #7a3e2a) !important; /* Brown Gradient */
            }
            .bg-gradient-lime {
                background: linear-gradient(to right, #cddc39, #8bc34a) !important; /* Lime Gradient */
            }
            .bg-gradient-cyan {
                background: linear-gradient(to right, #00bcd4, #009688) !important; /* Cyan Gradient */
            }
            /* Light Text Colors */
            .text-light-primary {
                color: rgba(0, 123, 255, 0.7) !important; /* Light Blue */
            }
            .text-light-secondary {
                color: rgba(108, 117, 125, 0.7) !important; /* Light Gray */
            }
            .text-light-success {
                color: rgba(40, 167, 69, 0.7) !important; /* Light Green */
            }
            .text-light-danger {
                color: rgba(220, 53, 69, 0.7) !important; /* Light Red */
            }
            .text-light-warning {
                color: rgba(255, 193, 7, 0.7) !important; /* Light Yellow */
            }
            .text-light-info {
                color: rgba(23, 162, 184, 0.7) !important; /* Light Blue */
            }
            .text-light-dark {
                color: rgba(52, 58, 64, 0.7) !important; /* Light Dark Gray */
            }
            .text-light-white {
                color: rgba(255, 255, 255, 0.7) !important; /* Light White */
            }
            .text-light-pink {
                color: rgba(255, 105, 180, 0.7) !important; /* Light Pink */
            }
            .text-light-purple {
                color: rgba(111, 66, 193, 0.7) !important; /* Light Purple */
            }
            .text-light-teal {
                color: rgba(32, 201, 151, 0.7) !important; /* Light Teal */
            }
            .text-light-orange {
                color: rgba(253, 126, 20, 0.7) !important; /* Light Orange */
            }
            .text-light-brown {
                color: rgba(139, 69, 19, 0.7) !important; /* Light Brown */
            }
            .text-light-lime {
                color: rgba(205, 220, 57, 0.7) !important; /* Light Lime */
            }
            .text-light-cyan {
                color: rgba(0, 188, 212, 0.7) !important; /* Light Cyan */
            
            /* Light Borders */
            .border-light-primary {
                border: 1px solid rgba(0, 123, 255, 0.7) !important; /* Light Blue */
            }
            .border-light-secondary {
                border: 1px solid rgba(108, 117, 125, 0.7) !important; /* Light Gray */
            }
            .border-light-success {
                border: 1px solid rgba(40, 167, 69, 0.7) !important; /* Light Green */
            }
            .border-light-danger {
                border: 1px solid rgba(220, 53, 69, 0.7) !important; /* Light Red */
            }
            .border-light-warning {
                border: 1px solid rgba(255, 193, 7, 0.7) !important; /* Light Yellow */
            }
            .border-light-info {
                border: 1px solid rgba(23, 162, 184, 0.7) !important; /* Light Blue */
            }
            .border-light-dark {
                border: 1px solid rgba(52, 58, 64, 0.7) !important; /* Light Dark Gray */
            }
            .border-light-white {
                border: 1px solid rgba(255, 255, 255, 0.7) !important; /* Light White */
            }
            .border-light-pink {
                border: 1px solid rgba(255, 105, 180, 0.7) !important; /* Light Pink */
            }
            .border-light-purple {
                border: 1px solid rgba(111, 66, 193, 0.7) !important; /* Light Purple */
            }
            .border-light-teal {
                border: 1px solid rgba(32, 201, 151, 0.7) !important; /* Light Teal */
            }
            .border-light-orange {
                border: 1px solid rgba(253, 126, 20, 0.7) !important; /* Light Orange */
            }
            .border-light-brown {
                border: 1px solid rgba(139, 69, 19, 0.7) !important; /* Light Brown */
            }
            .border-light-lime {
                border: 1px solid rgba(205, 220, 57, 0.7) !important; /* Light Lime */
            }
            .border-light-cyan {
                border: 1px solid rgba(0, 188, 212, 0.7) !important; /* Light Cyan */
            }
            </style>
            
            <style>
                        /* Department Badges */
                .bg-finance {
                    background-color: #1abc9c; /* Turquoise */
                    color: #ffffff; /* White */
                }
    
                .bg-operation {
                    background-color: #3498db; /* Blue */
                    color: #ffffff; /* White */
                }
    
                .bg-management {
                    background-color: #f1c40f; /* Yellow */
                    color: #ffffff; /* White */
                }
    
                .bg-it {
                    background-color: #9b59b6; /* Purple */
                    color: #ffffff; /* White */
                }
    
                /* Category Badges */
                .bg-question {
                    background-color: #2980b9; /* Dark Blue */
                    color: #ffffff; /* White */
                }
    
                .bg-suggestion {
                    background-color: #2ecc71; /* Green */
                    color: #ffffff; /* White */
                }
    
                .bg-complaint {
                    background-color: #e74c3c; /* Red */
                    color: #ffffff; /* White */
                }
    
                /* Status Badges */
                .bg-new {
                    background-color: #3498db; /* Blue */
                    color: #ffffff; /* White */
                }
    
                .bg-pending {
                    background-color: #f39c12; /* Orange */
                    color: #ffffff; /* White */
                }
    
                .bg-customer-reply {
                    background-color: #8e44ad; /* Dark Purple */
                    color: #ffffff; /* White */
                }
    
                .bg-complete {
                    background-color: #2ecc71; /* Green */
                    color: #ffffff; /* White */
                }
    
                .bg-user-reply {
                    background-color: #e67e22; /* Carrot */
                    color: #ffffff; /* White */
                }
    
                /* Priority Badges */
                .bg-low {
                    background-color: #95a5a6; /* Gray */
                    color: #ffffff; /* White */
                }
    
                .bg-medium {
                    background-color: #f1c40f; /* Yellow */
                    color: #ffffff; /* White */
                }
    
                .bg-high {
                    background-color: #e74c3c; /* Red */
                    color: #ffffff; /* White */
                }
    
                /* Optional: Styling for the badge appearance */
                .badge {
                    display: inline-block;
                    padding: 0.25em 0.75em;
                    border-radius: 9999px;
                    font-size: 0.75rem;
                    font-weight: 500;
                    text-align: center;
                    white-space: nowrap;
                }
    
    
    
                /* Priority Badges */
    
    /* Low Priority */
    .bg-low {
        background-color: #95a5a6; /* Gray */
        color: #ffffff; /* White text for contrast */
    }
    
    /* Medium Priority */
    .bg-medium {
        background-color: #f1c40f; /* Yellow */
        color: #ffffff;
    }
    
    /* High Priority */
    .bg-high {
        background-color: #e74c3c; /* Red */
        color: #ffffff;
    }
    
    /* Badge Base Style (optional if you're using Bootstrap's .badge) */
    .badge {
        display: inline-block;
        padding: 0.3rem 0.6rem;
        border-radius: 9999px; /* Fully rounded corners */
        font-size: 0.75rem;
        font-weight: 500;
        text-align: center;
        white-space: nowrap;
    }
    
    
            </style>
        
        let csrfToken = '{{csrf_token()}}'
    </script>

    @if (request()->cookie('ast'))
    <meta name="ast" content="{{ request()->cookie('ast') }}" />
    @else
    <script>
        setTimeout(() => {
            document.getElementById('logout-form').submit();
        }, 200);
    </script>
    @endif
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <div class="navbar-brand-box horizontal-logo">
                            <a   class="logo lgmc-dark">
                                <span class="logo-sm">
                                    <img src="{{asset('/assets/images/lgmc-dark.png?v=44')}}" alt="" height="50">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('/assets/images/logo-primary`.png')}}" alt="" height="17">
                                </span>
                            </a>

                            <a href="{{route('sections')}}"   class="logo logo-light">
                                <span class="favicon">
                                    <img src="{{{asset('/assets/images/lgmc-dark.png?v=44')}}}" alt="" height="50">
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
                                            <input type="text" class="form-control" placeholder="Search ..." aria-label="Consignee's username">
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
                                <!-- item-->
                                <h6 class="dropdown-header">مرحبـاً {{Auth::user()->name}} !</h6>
                                @if (auth()->user()->admin)
                                <a class="dropdown-item" href="{{route(get_area_name().'.index')}}" ><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> لوحة مدير النظام</a>
                                @endif
                                @if (auth()->user()->seller)
                                <a class="dropdown-item" href="{{route('seller.index')}}" ><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> لوحة مندوب المبيعات</a>
                                @endif
                                @if (auth()->user()->supplier)
                                <a class="dropdown-item" href="{{route('supplier.index')}}" ><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> لوحة المورد</a>
                                @endif
                                <a class="dropdown-item" href="#" onclick="document.getElementById('logout-form').submit()"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">تسجيل الخــروج</span></a>

                                <form action="{{route('logout')}}" method="GET" id="logout-form">
                                    @csrf

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">

                   <!-- Dark Logo-->
                <a   class="logo lgmc-dark">
                    <span class="logo-sm">
                        <img src="{{asset('/assets/images/lgmc-dark.png?v=44')}}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('/assets/images/lgmc-light.png?v=2')}}" alt="" height="35">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="{{route('sections')}}"   class="logo logo-light">
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

                     
                  <form  action="{{route('change_branch')}}" method="GET" class="p-3">
                     <select name="branch_id" id="branch_id" class="form-control" onchange="this.form.submit()">
                         <option value="">الفرع الرئيسي</option>
                         @foreach (auth()->user()->branches as $branch)
                             <option value="{{ $branch->id }}" {{ auth()->user()->branch_id == $branch->id ? 'selected' : '' }}>
                                 {{ $branch->name }}
                             </option>
                         @endforeach
                     </select>
                 </form>
                 

                    <ul class="navbar-nav" id="navbar-nav">

                        <li class="nav-item mt-3">
                            <a class="nav-link menu-link" href="{{route(get_area_name().'.home')}}">
                                <i class="ri-dashboard-line"></i> <span>الرئيسية</span>
                            </a>
                        </li>

                        @include('layouts.menus.finance')

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#" onclick="document.getElementById('logout-form').submit()">
                                <i class="ri-logout-circle-line"></i> <span>تسجيـل الخـروج</span>
                            </a>
                        </li>

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">  @yield('title') </h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">@yield('area')</a></li>
                                        <!-- <li class="breadcrumb-item active">Starter</li> -->
                                        @yield('breadcrumb')
                                    </ol>
                                </div>

                            </div>
                        </div>

                    </div>
                    <!-- end page title -->

                    <!-- content -->
                    <div class="row" id="app">
                        <div class="col-12 mt-1">
                            <div class="my-2"> @include('layouts.messages')</div>

                            @yield('content')
                        </div>
                    </div>
                    <!-- content -->

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

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
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->
    <!-- Theme Settings here -->


    <!-- Theme Settings -->


    <!-- JAVASCRIPT -->

    <script src="{{asset('/js/app.js?v=2')}}"></script>
    <script src="{{asset('/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('/assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('/assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('/assets/js/plugins.js')}}"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    @yield('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js" integrity="sha512-6+YN/9o9BWrk6wSfGxQGpt3EUK6XeHi6yeHV+TYD2GR0Sj/cggRpXr1BrAQf0as6XslxomMUxXp2vIl+fv0QRA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.5/js/standalone/selectize.min.js" integrity="sha512-JFjt3Gb92wFay5Pu6b0UCH9JIOkOGEfjIi7yykNWUwj55DBBp79VIJ9EPUzNimZ6FvX41jlTHpWFUQjog8P/sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/4.17.2/standard-all/translations/ar.js"></script>

    <script src="{{asset('/assets/js/app.js?v=2')}}"></script>
    <script src="{{asset('/assets/js/custom.js')}}"></script>

    @if (session()->get("modal") && $errors->any())
    <script type="text/javascript">
        let modalId = '{{session()->get("modal")}}';
        var myModal = new bootstrap.Modal(document.getElementById(modalId), {})
        myModal.show();
        errorSound.play();
    </script>
    @endif
    @if (session()->has('success'))
    <script type="text/javascript">
        successSound.play();
    </script>
    @endif

    <script>
            document.addEventListener('DOMContentLoaded', function () {
                CKEDITOR.replace('editor', {
                    language: 'ar'
                });
            });
    </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[name="passport_number"]').forEach(function (input) {
            input.addEventListener('input', function () {
                this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            });
        });
    });
    </script>
    
</body>


</html>