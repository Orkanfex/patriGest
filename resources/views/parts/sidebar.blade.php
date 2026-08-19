<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
<!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="{{ route('home') }}" class="brand-link">
            <!--begin::Brand Image-->
            <img
                src="{{ Vite::asset('resources/images/AdminLTELogo.png') }}"
                alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">PatriGest</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2" aria-label="Main navigation">
        <!--begin::Sidebar Menu-->
        <ul
            class="nav sidebar-menu flex-column"
            data-lte-toggle="treeview"
            data-accordion="false"
            id="navigation"
        >
            <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link">
                    <i class="nav-icon bi bi-people"></i>
                    <p>Usuários</p>
                </a>
            </li>

            <li class="nav-header">GERENCIAMENTO</li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon bi bi-box-arrow-in-right"></i>
                    <p>
                        Salas
                        <i class="nav-arrow bi bi-chevron-right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @foreach (auth()->user()->accessible_environments as $environment)
                        <li>

                            <a href="{{ route('patrimonies.index', $environment->id) }}" 
                                class="nav-link"
                            >
                                <i class="bi bi-door-open-fill"></i>
                                <p>
                                    {{ $environment->name}}                                
                                    {{-- <i class="nav-arrow bi bi-chevron-right"></i> --}}
                                </p>
                            </a>
                            
                        </li>
                    @endforeach
                    <li class="nav-item">
                        <a href="./examples/lockscreen.html" class="nav-link">
                            <i class="nav-icon bi bi-circle"></i>
                            <p>Lockscreen</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-header">LABELS</li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon bi bi-circle text-danger"></i>
                    <p class="text">Important</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon bi bi-circle text-warning"></i>
                    <p>Warning</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon bi bi-circle text-info"></i>
                    <p>Informational</p>
                </a>
            </li>
        </ul>
        <!--end::Sidebar Menu-->

            <!-- Docs CTA (bottom of sidebar) -->
            <div class="p-3 mt-3 border-top border-secondary border-opacity-25">
                <a
                href="./docs/introduction.html"
                class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2"
                >
                    <i class="bi bi-book" aria-hidden="true"></i>
                    View documentation
                </a>
            </div>
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->