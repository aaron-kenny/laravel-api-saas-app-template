<!doctype html>
<html lang="en">
<head>
    @if(App::environment() == "production")
        <!-- Google Tag Manager -->
        INSERT_GOOGLE_TAG_MANAGER_SCRIPT
        <!-- End Google Tag Manager -->
    @endif

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#171717">
    <meta name="msapplication-navbutton-color" content="#171717">
    <meta name="apple-mobile-web-app-status-bar-style" content="#171717">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('meta')

    <title>@yield('page-title')</title>

    <link rel="icon" href="INSERT_PATH_TO_FAVICON">

    <!-- AVOID FOUC -->
    <style>.hidden {display: none;}</style>
    <script>
        document.querySelector('html').classList.add('hidden');
        window.onload = function(event) {
            document.querySelector('html').classList.remove('hidden');
        };
    </script>
    <!-- END AVOID FOUC -->

    <!-- DARK MODE -->
    <script>if(localStorage.theme == 'dark'){document.querySelector('html').classList.add('dark');}</script>
    <!-- END DARK MODE -->

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="INSERT_PATH_TO_CSS" rel="stylesheet">

    <script src="INSERT_PATH_TO_JS" defer></script>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    INSERT_GOOGLE_TAG_MANAGER_NO_SCRIPT
    <!-- End Google Tag Manager (noscript) -->
    
    <!-- NAV OVERLAY -->
    <div class="nav-side-overlay" data-toggle-class="is-visible" data-target=".nav-side,.nav-side-overlay"></div>
    <!-- END NAV OVERLAY -->

    <!-- NAV DASHBOARD -->
    <div class="nav nav-side nav-dashboard bg-color-2">
        <div class="d-flex justify-content-between align-items-center py-4 px-6">
            <a href="{{ route('dashboard.index') }}"><img class="img-fluid mh-5" src="INSERT_PATH_TO_PRODUCT_LOGO"/></a>
            <button class="btn btn-close d-lg-none" data-toggle-class="is-visible" data-target=".nav-side,.nav-side-overlay"></button>
        </div>
        <nav>
            <a class="nav-link" href="{{ route('dashboard.index') }}">Dashboard</a>
            <a class="nav-link" href="{{ route('api.token.index') }}">API</a>
            <a class="nav-link" href="{{ route('settings.index') }}">Settings</a>
        </nav>
    </div>
    <!-- END NAV DASHBOARD -->

    <!-- MAIN SECTION -->
    <div class="ml-lg-16">

        <!-- NAVBAR -->
        <nav class="navbar">
            <div class="container-fluid justify-content-end">
                <button class="btn btn-theme-toggle mr-1" data-toggle-theme="dark"></button>
                <button class="btn btn-menu-toggle d-inline-block d-lg-none" data-toggle-class="is-visible" data-target=".nav-side,.nav-side-overlay"></button>
                <div class="dropdown">
                    <a href="#" data-toggle="dropdown">
                        <img class="rounded-circle mh-9" src="{{ request()->user()->gravatar }}">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ config('app.account_url') }}">Account</a>
                        <a class="dropdown-item" href="{{ config('app.logout_url') }}" onclick="event.preventDefault();document.getElementById('logoutForm').submit();">Log out</a>
                        <form id="logoutForm" class="d-none" action="{{ config('app.logout_url') }}" method="POST">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <!-- END NAVBAR -->

        @if (session('status'))
            <div class="container">
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        @yield('content')

    </div>
    <!-- END MAIN SECTION -->


    <!-- <script src="{{ mix('/js/app.js') }}"></script> -->
    @stack('scripts')
</body>
</html>
