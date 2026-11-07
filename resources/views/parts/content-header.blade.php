<!--begin::App Content Header-->
<div class="app-content-header mb-3">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row align-items-center">
            <div class="col-sm-6">
                @hasSection('page-title')
                    <h1 class="mb-0 fs-3">@yield('page-title')</h1>
                @endif

                @isset($breadcrumbs)
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 mt-1">
                            @foreach ($breadcrumbs as $breadcrumb)
                                <li class="breadcrumb-item">
                                    <a href="{{ $breadcrumb['route'] }}">{{ $breadcrumb['label'] }}</a>
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                @endisset
            </div>

            {{-- Usamos d-flex justify-content-sm-end para empurrar os botões flex para a direita --}}
            <div class="col-sm-6 d-flex justify-content-sm-end align-items-center mt-2 mt-sm-0">
                @yield('page-actions')
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->