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
    <title>後臺系統 - inFlux普匯金融科技</title>
    <link rel="icon" href="{{ asset('image/site_icon.png') }}">

    <!-- package -->
    <link rel="stylesheet" href="{{ asset('css/package/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/bootstrap.min.css') }}">

    <!-- local -->
    <link rel="stylesheet" href="{{ asset('css/backend.css') }}">

    <!-- package -->
    <script type="text/javascript" src="{{ asset('js/package/es6-promise.auto.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/jQuery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vuex.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue-router.min.js') }}"></script>

    <!-- local -->
    <script type="text/javascript" src="{{ asset('js/backend.js') }}"></script>
</head>

<body>
    <div id="web_admin">
        <nav class="navbar navbar-expand navbar-dark bg-dark">
            <router-link to="index" class="navbar-brand">回首頁</router-link>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <router-link to="" class="nav-item active nav-link">測</router-link>
                    <router-link to="" class="nav-item active nav-link">試</router-link>
                    <router-link to="" class="nav item active nav-link">後</router-link>
                    <router-link to="" class="nav item active nav-link">台</router-link>
                </ul>
            </div>
        </nav>
        <div class="dashbord">
            <h2>施工中</h2>
            <button @click="logout">登出</button>
        </div>
        <router-view></router-view>
        <input type="hidden" ref="islogin" value="{{$isLogin}}">
    </div>
</body>

</html>