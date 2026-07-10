<div class="header-main px-3 px-lg-4">
    <a id="menuSidebar" href="#" class="menu-link me-3 me-lg-4"><i class="ri-menu-2-fill"></i></a>

    <div class="me-auto">
        <h5 class="mb-0 fw-semibold text-dark">@yield('page-title', 'Parja')</h5>
    </div>

    <div class="dropdown dropdown-profile ms-3 ms-xl-4">
        <a href="#" class="dropdown-link" data-bs-toggle="dropdown" data-bs-auto-close="outside">
            <div class="avatar avatar-online">
                <span class="avatar-initial rounded-circle bg-primary">{{ strtoupper(substr((auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? ''), 0, 1)) }}</span>
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end mt-10-f">
            <div class="dropdown-menu-body">
                <div class="avatar avatar-xl online mb-3">
                    <span class="avatar-initial rounded-circle bg-primary fs-4">{{ strtoupper(substr((auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? ''), 0, 1)) }}</span>
                </div>
                <h5 class="mb-1 text-dark fw-semibold">{{ (auth()->guard('keycloak-external')->user()?->name ?? auth()->guard('keycloak')->user()?->name ?? '') }}</h5>
                <p class="fs-sm text-secondary">{{ (auth()->guard('keycloak-external')->user()?->email ?? auth()->guard('keycloak')->user()?->email ?? '') }}</p>
                <hr>
                @include('partials.profile-roles')
                <hr>
                <nav class="nav">
                    <a href="{{ route('dashboard') }}"><i class="ri-apps-line"></i> Beranda Modul</a>
                </nav>
                <hr>
                <nav class="nav">
                    <form method="POST" action="{{ route('logout') }}" id="parja-header-logout-form">
                        @csrf
                    </form>
                    <a href="#" onclick="document.getElementById('parja-header-logout-form').submit();">
                        <i class="ri-logout-box-r-line"></i> Keluar
                    </a>
                </nav>
            </div>
        </div>
    </div>
</div>
