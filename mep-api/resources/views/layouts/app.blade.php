
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- FavIcons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('assets/favicon/safari-pinned-tab.svg') }}" color="#636362">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Stylesheet -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!--jQueryUI version 1.11.4 -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
    <link href="{{ asset('css/akq-styles.css') }}" rel="stylesheet" type="text/css" >

    @yield('header')

    <!-- JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script src="{{ asset('js/push.min.js') }}"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous">
    </script>
    <title>Welcome @yield('title')</title>
</head>

<body oncontextmenu="return false;">
<header>
</header>
<div id="page-container">
    <main id="content-wrap">
        <div id="showErrorMessage" style="display:none">
            <label id="errorMessage"></label>
        </div>

        {{-- page content below --}}
        @yield('content')
    </main>
</div>

<footer id="footer" class="d-flex flex-wrap justify-content-between align-items-center">
    <p class="col-md-4 mb-0" style="color: white;">&copy; 2023 akquinet</p>
    <ul class="nav col-md-4 justify-content-end">
      <li class="nav-item"><a href="#" class="nav-link px-2" style="color: white;">Changelog</a></li>
    </ul>
    @yield('footer')
</footer>
</body>
</html>
