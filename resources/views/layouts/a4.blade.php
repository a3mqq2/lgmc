<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">


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
            <img src="{{ asset('assets/images/logo-dark.png') }}"  width="300">
        @endif
        <img src="{{ asset('assets/images/logo-dark.png') }}" class="background-logo">
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