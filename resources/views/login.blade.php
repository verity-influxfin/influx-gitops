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
    <title>管理員登入 - inFlux普匯金融科技</title>
    <link rel="icon" href="{{ asset('image/site_icon.png') }}">

    <!-- package -->
    <link rel="stylesheet" href="{{ asset('css/package/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/bootstrap.min.css') }}">

    <!-- local -->
    <link rel="stylesheet" href="{{ asset('css/backend.css') }}">
</head>

<body>
    <div id="login" class="login-bg" :style="`background-image: url('./Images/23832.jpg');`">
        <div class="login-form">
            <img src=" {{ asset('./Images/logo.png') }}" class="img-fluid" style="max-width: 60%;">
            <div class="input-group">
                <span class="input-group-addon login-text"><i class="fas fa-user"></i></span>
                <input type="text" class="form-control login-input" v-model="account" maxlength="10" placeholder="請輸入帳號">
            </div>
            <div class="input-group">
                <span class="input-group-addon login-text"><i class="fas fa-key"></i></span>
                <input type="password" class="form-control login-input" v-model="password" maxlength="50" placeholder="請輸入密碼">
            </div>
            <div v-if="message" class="alert alert-danger">@{{message}}</div>
            <button type="button" class="btn btn-login" @click="login">送出</button>
        </div>
    </div>

    <!-- package -->
    <script type="text/javascript" src="{{ asset('js/package/es6-promise.auto.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/jQuery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/axios.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vuex.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue-router.min.js') }}"></script>

    <!-- local -->
    <script type="text/javascript" src="{{ asset('js/backend.js') }}"></script>
</body>

</html>