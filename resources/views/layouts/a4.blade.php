<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', 'Amiri', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
            background: white;
        }
        
        .container-fluid {
            max-width: 100%;
            margin: 0;
            padding: 20px;
        }
        
        /* تحسينات الطباعة */
        @media print {
            @page {
                size: A4;
                margin: 1cm;
            }
            
            body {
                font-size: 12px;
                line-height: 1.4;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .container-fluid {
                padding: 0;
                margin: 0;
                max-width: 100%;
            }
            
            .no-print {
                display: none !important;
            }
            
            .page-break {
                page-break-before: always;
            }
            
            .page-break-after {
                page-break-after: always;
            }
            
            .keep-together {
                page-break-inside: avoid;
            }
            
            /* تحسين عرض الجداول */
            table {
                width: 100% !important;
                border-collapse: collapse;
            }
            
            th, td {
                border: 1px solid #000 !important;
                padding: 4px !important;
                font-size: 10px !important;
            }
            
            thead {
                display: table-header-group;
            }
            
            tbody {
                display: table-row-group;
            }
            
            tfoot {
                display: table-footer-group;
            }
            
            /* تحسين الألوان للطباعة الأبيض والأسود */
            .text-success {
                color: #000 !important;
                font-weight: bold;
            }
            
            .text-danger {
                color: #000 !important;
                font-weight: bold;
            }
            
            .text-info {
                color: #000 !important;
            }
            
            .text-warning {
                color: #000 !important;
            }
            
            .bg-success {
                background-color: #f0f0f0 !important;
                border: 1px solid #000 !important;
            }
            
            .bg-danger {
                background-color: #f5f5f5 !important;
                border: 1px solid #000 !important;
            }
            
            .bg-info {
                background-color: #f8f8f8 !important;
                border: 1px solid #000 !important;
            }
            
            .bg-warning {
                background-color: #f9f9f9 !important;
                border: 1px solid #000 !important;
            }
            
            .table-dark {
                background-color: #666 !important;
                color: white !important;
            }
            
            .table-success {
                background-color: #f0f0f0 !important;
            }
            
            .table-danger {
                background-color: #f5f5f5 !important;
            }
            
            .table-info {
                background-color: #f8f8f8 !important;
            }
            
            /* إخفاء العناصر غير المرغوب فيها */
            .btn,
            .navbar,
            .sidebar,
            .footer,
            .modal,
            .tooltip,
            .popover {
                display: none !important;
            }
        }
        
        /* أنماط عامة للعرض */
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        
        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: right;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-end {
            text-align: left;
        }
        
        .text-start {
            text-align: right;
        }
        
        .fw-bold {
            font-weight: 700;
        }
        
        .small {
            font-size: 0.875em;
        }
        
        .text-muted {
            color: #6c757d;
        }
        
        .text-success {
            color: #198754;
        }
        
        .text-danger {
            color: #dc3545;
        }
        
        .text-info {
            color: #0dcaf0;
        }
        
        .text-warning {
            color: #ffc107;
        }
        
        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mb-5 { margin-bottom: 3rem; }
        
        .mt-0 { margin-top: 0; }
        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-3 { margin-top: 1rem; }
        .mt-4 { margin-top: 1.5rem; }
        .mt-5 { margin-top: 3rem; }
        
        .p-0 { padding: 0; }
        .p-1 { padding: 0.25rem; }
        .p-2 { padding: 0.5rem; }
        .p-3 { padding: 1rem; }
        .p-4 { padding: 1.5rem; }
        .p-5 { padding: 3rem; }
        
        .border {
            border: 1px solid #dee2e6;
        }
        
        .border-top {
            border-top: 1px solid #dee2e6;
        }
        
        .border-bottom {
            border-bottom: 1px solid #dee2e6;
        }
        
        .rounded {
            border-radius: 0.375rem;
        }
        
        /* Grid System */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }
        
        .col-1, .col-2, .col-3, .col-4, .col-5, .col-6,
        .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
            position: relative;
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }
        
        .col-1 { flex: 0 0 8.333333%; max-width: 8.333333%; }
        .col-2 { flex: 0 0 16.666667%; max-width: 16.666667%; }
        .col-3 { flex: 0 0 25%; max-width: 25%; }
        .col-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
        .col-5 { flex: 0 0 41.666667%; max-width: 41.666667%; }
        .col-6 { flex: 0 0 50%; max-width: 50%; }
        .col-7 { flex: 0 0 58.333333%; max-width: 58.333333%; }
        .col-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
        .col-9 { flex: 0 0 75%; max-width: 75%; }
        .col-10 { flex: 0 0 83.333333%; max-width: 83.333333%; }
        .col-11 { flex: 0 0 91.666667%; max-width: 91.666667%; }
        .col-12 { flex: 0 0 100%; max-width: 100%; }
        
        /* خطوط وألوان */
        hr {
            border: 0;
            border-top: 1px solid #dee2e6;
            margin: 1rem 0;
        }
        
        .bg-light {
            background-color: #f8f9fa;
        }
        
        .bg-dark {
            background-color: #212529;
            color: white;
        }
        
        /* تحسينات خاصة بالطباعة العربية */
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Cairo', sans-serif;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 0.5rem;
        }
        
        .arabic-numbers {
            font-family: 'Cairo', sans-serif;
        }
        
        /* تحسين عرض القائمة */
        .list-unstyled {
            list-style: none;
            padding-left: 0;
        }
        
        ul, ol {
            margin-bottom: 1rem;
        }
        
        li {
            margin-bottom: 0.25rem;
        }
    </style>
    

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/plugins/web-fonts/font-awesome/font-awesome.min.css') }}" rel="stylesheet" media="all">
    <link rel="stylesheet" href="{{ asset('assets/css/style-a4.css') }}" media="all">
    <title> LGMC | @yield('title')</title>


    @if (request()->input('landscape'))
        <style>
            @media print {
                @page {
                    size: landscape !important;
                }
            }
        </style>
    @endif

    @stack('styles')


</head>

<body onload="printDoc()">

    <page size="{{ request('size') ? request('size') : 'A4' }}" id="app"
        @if (request()->input('landscape')) layout="landscape" @endif>

        @if (request()->input('landscape'))
            <img src="{{ asset('assets/img/print/header-landscape.svg') }}" width="100%" class="a4-header">
        @else
            <img src="{{ asset('assets/images/lgmc-dark.png') }}"  width="300">
        @endif
        <img src="{{ asset('assets/images/lgmc-dark.png') }}" class="background-logo">
        <div class="content" width="100%">
            @yield('content')
        </div>
    </page>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        function printDoc() {
            window.print();
        }
    </script>
</body>

</html>