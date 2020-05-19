<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('includes.head')
</head>
<body>
<div class="container">

    <header class="">
        @include('includes.header')
    </header>

    <div id="main" class="content">

        @yield('content')

    </div>

    <footer class="flex-center">
        @include('includes.footer')
    </footer>

</div>
</body>
</html>
