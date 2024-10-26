<aside class="navbar navbar-vertical navbar-expand navbar-light" data-bs-theme="light" id="adminSideMenu">
    <div class="container-fluid">
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="/">
                <img src="{{asset('images/logo_full.png')}}" alt="{{config('app.name')}}"
                    class="navbar-brand-image full-logo">
                <img src="{{asset('images/logo.png')}}" alt="{{config('app.name')}}"
                    class="navbar-brand-image small-logo">
            </a>
        </h1>
       
        <div class="collapse navbar-collapse" id="navbar-menu-vertical">
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item ">
                    <a class="nav-link" href="/">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-home i19 nav-link-icon"></i>
                        </span>
                        <span class="nav-link-title">
                            Home
                        </span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="{{ route('admin.task.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-home i19 nav-link-icon"></i>
                        </span>
                        <span class="nav-link-title">
                            Task
                        </span>
                    </a>
                </li>


             

            </ul>
        </div>


</aside>