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
            @can('list', App\Models\User::class)
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Usuários</p>
                    </a>
                </li>
            @endcan
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
                    
                    
                </ul>
            </li>
            @can('viewAny', App\Models\Environment::class)
                <li class="nav-item">
                    <a href="{{ route('environments.index') }}" class="nav-link">
                        <i class="nav-icon bi bi-gear-wide"></i>
                        <p>Gerenciar Ambientes</p>
                    </a>
                </li>
            @endcan
        </ul>
        <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->