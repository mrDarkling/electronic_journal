<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- isAdmin flag -->


    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- VENDOR CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/linearicons/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/chartist/css/chartist-custom.css') }}">
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <!-- ICONS -->
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('img/favicon.png') }}">

    <meta name="api-token" content="{!!auth()->user() ? auth()->user()->api_token : null !!}">
    <script>
        window.api = {
            token: "{!! auth()->user() ? auth()->user()->api_token : null !!}",
            url: "{{ $apiUrl ?? '' }}",
            apiHeader:{
                Authorization: 'Bearer {!! auth()->user() ? auth()->user()->api_token : null !!}',
            },
            isAdmin: "{{ \Illuminate\Support\Facades\Auth::user()->isAdmin() }}",
        }
        window.currentUser = JSON.parse('{!!auth()->user()->toJson() !!}');

    </script>
</head>
<body>
<!-- Fixed navbar -->
    <div id="app">
        <Navigation url="{{ url('/logout') }}"></Navigation>

        <!-- WRAPPER -->
        <div id="wrapper">
            <vue-snotify url="{{ route('logout') }}"></vue-snotify>

            <!-- MAIN CONTENT -->
            <div class="main-content">
                <div class="container-fluid">
                    <!-- CONTENT -->
                    @yield('content')
                    <!-- END CONTENT -->
                </div>
            </div>
            <!-- END MAIN CONTENT -->

            <!-- END MAIN -->
            <div class="clearfix"></div>

            <footer>
                <div class="container-fluid">
                    <p class="copyright">&copy; 2020 <a href="https://kubstu.ru/" target="_blank">
                            Кубанский государственный технологический университет
                        </a>.
                        Все права защищены.
                    </p>
                </div>
            </footer>
        </div>

    </div> <!-- end of #app -->
</body>
</html>


