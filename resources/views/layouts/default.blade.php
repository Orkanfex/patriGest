<!doctype html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>PatriGest | Dashboard</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
        <meta name="color-scheme" content="light dark" />
        <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
        <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

        <meta name="title" content="AdminLTE v4 | Dashboard" />
        <meta name="author" content="ColorlibHQ" />
        <meta
        name="description"
        content="AdminLTE is a free Bootstrap 5 admin dashboard template with almost 50 example pages, built with vanilla JS and designed with accessibility in mind."
        />
        <meta
        name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel"
        />
    
        <meta name="supported-color-schemes" content="light dark" />
        @vite('resources/scss/app.scss')

    </head>
    <!--end::Head-->
    <!--begin::Body-->
    <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
        <!--begin::App Wrapper-->
        <div class="app-wrapper">
            @include('parts.header')
            @include('parts.sidebar')
            <!--begin::App Main-->
            <main class="app-main">
                @include('parts.content-header')
                <!--begin::App Content-->
                <div class="app-content">
                    <!--begin::Container-->
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::App Content-->
            </main>
            <!--end::App Main-->
            @include('parts.footer')
        </div>
        <!--end::App Wrapper-->
        @vite('resources/js/app.js')
    </body>
    <!--end::Body-->
</html>