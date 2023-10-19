<!doctype html>
<html lang="en" class="scroll-smooth light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Loggify</title>

    <link href="{{ asset('vendor/loggify/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('vendor/loggify/css/loggify.css') }}" rel="stylesheet"/>
</head>

<body data-bs-theme="light">

@include('loggify::components.navbar', ['information' => $information])

<div class="">
    @yield('main')
</div>

<script src="{{ asset('vendor/loggify/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/loggify/js/loggify.js')  }}"></script>
</body>
</html>
