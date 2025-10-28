<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="{{ asset('css/app.css') }}" type="text/css" rel="stylesheet" />
    <script src="{{ asset('js/helpers.js') }}"></script>
    <script src="{{ asset('js/ui.js') }}"></script>
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="{{ asset('js/product.js') }}"></script>
    <script src="{{ asset('js/login.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>-->
    <title>@yield('title') - {{ getenv('APP_NAME') }}</title>
</head>

<body data-component='main' class="sm:m-8 font-['Ysabeau']">
    @include('header')
    <main>
        <div class="render-surface will-change-contents sm:p-[30px] ">
            @yield('content')
        </div>
    </main>
</body>

</html>
