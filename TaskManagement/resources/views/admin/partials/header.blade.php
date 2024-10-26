
<header class="navbar  navbar-light  d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="side-menu" data-bs-target="#adminSideMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-nav ">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <img class="avatar avatar-sm" src="{{asset('images/default_avatar.png')}}" alt="Image" />
                    <div class="d-none d-xl-block ps-2">
                        <div>Account</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="#" class="dropdown-item " onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>
