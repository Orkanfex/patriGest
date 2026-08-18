<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                @hasSection('page-title')
                    <h1 class="mb-0 fs-3">@yield('page-title')</h1>
                @endif

                @isset($breadcrumbs)
                    
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @foreach ( $breadcrumbs as $breadcrumb)
                                <li class="breadcrumb-item">
                                    <a href="{{ $breadcrumb['route'] }}">{{ $breadcrumb['label'] }}</a>
                                </li>
                            @endforeach
                        </ol>
                    </nav>
                @endisset
            </div>
            <div class="col-sm-6 text-end">
                @yield('page-actions')
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->