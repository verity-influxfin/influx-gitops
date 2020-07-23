<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <title>404</title>

    <link rel="stylesheet" href="{{ asset('css/errors.css') }}">
</head>

<body>
    <div id="error" style="background-image: url('./images/404.jpg')">
    <a href="{{ asset('') }}"><< back</a>
</div>
</body>

</html>