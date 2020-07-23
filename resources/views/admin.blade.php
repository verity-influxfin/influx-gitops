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
    <title></title>
    <link rel="icon" href="{{ asset('./images/site_icon.png') }}">

    <!-- package -->
    <link rel="stylesheet" href="{{ asset('css/package/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/pagination.min.css') }}">

    <!-- local -->
    <link rel="stylesheet" href="{{ asset('css/backend.css') }}">
</head>

<body>
    <div id="web_admin">
        <nav class="navbar navbar-expand navbar-dark">
            <router-link to="index" class="navbar-brand">後台系統</router-link>
            <button class="btn btn-light btn-sm float-right" @click="logout">登出</button>
        </nav>
        <router-view></router-view>
        <input type="hidden" ref="islogin" value="{{$isLogin}}">
    </div>

    <!-- package -->
    <script type="text/javascript" src="{{ asset('js/package/es6-promise.auto.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/jQuery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/axios.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue-cookies.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vuex.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue-router.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/pagination.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/v-calendar.umd.min.js') }}"></script>
    <!-- local -->
    <script type="text/javascript" src="{{ asset('js/backend.js') }}"></script>
</body>

</html>