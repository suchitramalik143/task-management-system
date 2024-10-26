@extends('admin.layouts.base')
@section('base_content')

    <div class="page">


        @include('admin.partials.header')

        {{--Page content--}}
        <div class="page-wrapper pt-4">

            {{--Breadcrumb--}}
            @yield('page-header')
            @if(View::hasSection('breadcrumb')||View::hasSection('breadcrumb-action'))
                <div class="breadcrumb-container">
                    <div class="container-xl">
                        <div class="row align-items-center">
                            <div class="col">
                                @yield('breadcrumb')
                            </div>
                            <div class="col-auto col-md-auto ms-auto d-print-none">
                                @yield('breadcrumb-action')
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{--Page body--}}
            <div class="page-body">
            <div class="container-xl">
                    @include('admin.partials.form-status')

                </div>
                @yield('content')
            </div>


            {{--Page Footer--}}
            @hasSection('footer')
                @yield('footer')
            @else
                @include('frontend.partials.footer')
            @endif

            @if(auth()->check())
                <form id="accountLogoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
                    {{ csrf_field() }}
                </form>
            @endif
        </div>
    </div>
    @yield('popup')
@endsection

@section('additional_scripts')

    <script>
        $(document).ready(function () {

        })
    </script>
@endsection
