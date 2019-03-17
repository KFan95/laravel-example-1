<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
@yield('content')

<script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
</body>
</html>
