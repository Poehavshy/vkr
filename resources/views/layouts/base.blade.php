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

    <div id="main" class="content full-height bg-white shadow-sm marg-tb p-3">

        @yield('content')

    </div>

    <footer class="flex-center bg-white shadow-sm">
        @include('includes.footer')
    </footer>

</div>
</body>
</html>
