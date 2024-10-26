<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @hasSection('template_title')
            @yield('template_title')
        @else
            Store for luxury jewellery
            - {{ config('app.name', Lang::get('titles.app')) }}
        @endif
    </title>

    @if(isset($metaData) || View::hasSection('meta'))
        @if(isset($metaData))
            @foreach($metaData as $key=>$value)
                <meta name="{{$key}}" content="{!! $value !!}">
            @endforeach
        @endif
        @yield('meta')
    @else
        <meta name="title" content="{{trans('seo.home.title')}}">
        <meta name="keywords" content="{{trans('seo.home.keywords')}}">
        <meta name="description" content="{{trans('seo.home.description')}}">
    @endif
    <meta name="author" content="Madgeek Pvt. Ltd.">
    <link rel="canonical" href="{{request()->fullUrl()}}">
    <link rel="shortcut icon" href="/favicon.ico">


    {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{-- Fonts --}}
    @yield('fonts')

    {{-- Styles --}}
    <link href="{{ mix('/frontend/css/app.css') }}" rel="stylesheet">

    @yield('style')


    <script>
        window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
    </script>

    @yield('head')

</head>
<body>

<div id="app" class="full-height">
    @yield('base_content')
</div>


{{-- Scripts --}}
<script src="{{ mix('/frontend/js/app.js') }}"></script>

@yield('additional_scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

@yield('script')


</body>
</html>
