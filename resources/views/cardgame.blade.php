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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>牛年翻好運，轉出好運氣 - inFlux普匯金融科技</title>
    <link rel="icon" href="{{ asset('./images/site_icon.png') }}">

    <!-- package -->
    <link rel="stylesheet" href="{{ asset('css/package/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/bootstrap.min.css') }}">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-117279688-9"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
    </script>
</head>

<body>
    <div id="cardgame">
        <router-view></router-view>
    </div>

    <!-- package -->
    <script type="text/javascript" src="{{ asset('js/package/es6-promise.auto.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/jQuery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/axios.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue-router.min.js') }}"></script>

    <!-- local -->
    <script type="text/javascript" src="{{ asset('js/cardgame.js') }}"></script>
</body>

</html>