@if (session('message'))
    <div class="alert alert-{{ Session::get('status') }}  bg-{{ Session::get('status') }}-lt border-1 alert-important status-box alert-dismissible fade show" role="alert">
        <a href="#" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="close"><span
                class="sr-only">Close</span></a>
        {{ session('message') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success bg-success-lt border-1 alert-important alert-dismissible fade show" role="alert">
        <a href="#" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="close"></a>
        <h4><i class="icon fa fa-check fa-fw" aria-hidden="true"></i> Success</h4>
        {{ session('success') }}
    </div>
@endif

@if (session('subscription_limit'))
    <div class="alert alert-danger  bg-danger-lt border-1 alert-important alert-dismissible fade show alert-important  bg-danger-lt border-1" role="alert">
        <a href="#" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="close"></a>
        <h3 class="mb-1"><i class="ti ti-alert-triangle-filled me-1" aria-hidden="true"></i> Upgrade subscription</h3>
        {{ session('subscription_limit') }}
    </div>
@endif

@if(session()->has('status'))
    @if(session()->get('status') == 'wrong')
        <div class="alert alert-danger  bg-danger-lt border-1 alert-important status-box alert-dismissible fade show" role="alert">
            <a href="#" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="close"></a>
            {{ session('message') }}
        </div>
    @endif
@endif

@if (session('error'))
    <div class="alert alert-danger  bg-danger-lt border-1 alert-important alert-dismissible fade show" role="alert">
        <a href="#" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="close"></a>
        <h4>
            <i class="icon fa fa-warning fa-fw" aria-hidden="true"></i>
            Error
        </h4>
        {{ session('error') }}
    </div>
@endif
@if (session('warning'))
    <div class="alert alert-warning  bg-warning-lt border-1 alert-important alert-dismissible fade show" role="alert">
        <a href="#" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="close"></a>
        <h4 class="mb-1"><i class="icon fa fa-warning fa-fw" aria-hidden="true"></i>
            Warning
        </h4>
        {{ session('warning') }}
    </div>
@endif


@if (session('errors') && count($errors) > 0)
    <div class="alert alert-danger   bg-danger-lt border-1 alert-important alert-dismissible fade show" role="alert">
        <a href="#" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="close"></a>
        <h4>
            <i class="icon fa fa-warning fa-fw" aria-hidden="true"></i>
            <strong>{{ Lang::get('auth.whoops') }}</strong> {{ Lang::get('auth.someProblems') }}
        </h4>
        <ul>
            @php $i=0; @endphp
            @foreach ($errors->all() as $error)
                @if($i>10)
                    <li>And {{$errors->count()-10}} more lines</li>
                    @break
                @else
                    <li>{{ $error }}</li>
                @endif
                @php $i++; @endphp
            @endforeach
        </ul>
    </div>
@endif
