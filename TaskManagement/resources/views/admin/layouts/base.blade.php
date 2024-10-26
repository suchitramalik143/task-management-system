<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('template_title')
            @yield('template_title')
            |
        @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
    <meta name="description" content="">
    <meta name="author" content="Madgeek Pvt. Ltd.">
    <link rel="shortcut icon" href="/favicon.ico">

    {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{-- Fonts --}}
    @yield('fonts')

    {{-- Styles --}}
    <link href="{{ mix('/admin/css/app.css') }}" rel="stylesheet">

    @yield('style')


    @yield('head')

</head>
<body class="layout-fluid ">

<div id="app" class="full-height">
    @yield('base_content')
</div>


{{-- Scripts --}}
<script src="{{ mix('/admin/js/app.js') }}"></script>
@include('scripts.delete-modal-script')
@include('scripts.save-modal-script')
@include('scripts.tooltips')
@include('scripts.ajaxModel-script')


@yield('additional_scripts')
@yield('footer_scripts')


</body>
</html>
