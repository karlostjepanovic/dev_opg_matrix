<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>OPGmatrix</title>
    <meta charset="utf-8">
    <meta name="csrf-token" value="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/assets/app-logo.png">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="Karlo Stjepanović">
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
@yield('content')
</body>
</html>
