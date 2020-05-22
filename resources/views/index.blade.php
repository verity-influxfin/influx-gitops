<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">

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
    <meta property=”fb:app_id” content=”2194926914163491”>
    <title>inFlux普匯金融科技</title>
    <link rel="icon" href="{{ asset('image/site_icon.png') }}">

    <!-- package -->
    <link rel="stylesheet" href="{{ asset('css/package/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/pagination.min.css') }}">

    <!-- local -->
    <link rel="stylesheet" href="{{ asset('css/web.css') }}">

    <!-- package -->
    <script type="text/javascript" src="{{ asset('js/package/es6-promise.auto.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/jQuery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/jquery-ui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/gasp.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/slick.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/axios.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue-cookies.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vuex.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/vue-router.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/v-calendar.umd.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/aos.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/pagination.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/echarts.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/ecStat.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/package/dataTool.min.js') }}"></script>

    <!-- local -->
    <script type="text/javascript" src="{{ asset('js/web.js') }}"></script>

    <!--Facebook Comments-->
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.8&appId=2194926914163491";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-117279688-9"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'UA-117279688-9');
    </script>
</head>

<body>
    <div id="web_index" @mousemove="clicked">
        <nav class="page-header navbar navbar-expand-lg">
            <div class="web-logo">
                <router-link to="/index"><img src=" {{ asset('image/logo.png') }}" class="img-fluid"></router-link>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ml-auto">
                    <li v-for="item in menuList" class="nav-item dropdown">
                        <router-link v-if="item.subMenu.length === 0" class="nav-link" :to="item.href">${item.title}</router-link>
                        <a v-else class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">${item.title}</a>
                        <ul class="dropdown-menu" v-if="item.subMenu.length !== 0">
                            <li v-for="subItem in item.subMenu" :class="[(!subItem.isActive ? 'coming-soon' : '')]">
                                <router-link class="dropdown-item" :to="subItem.href">${subItem.name}${!subItem.isActive ? '(coming soon)' : ''}</router-link>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link invest-link" v-if="!flag || flag === 'logout'"@click="openLoginModal('請登入')">投資專區</a>
                        <router-link class="nav-link invest-link" to="/myinvestment"  v-else>投資專區</router-link>
                    </li>
                    <li class="nav-item" v-if="!flag || flag === 'logout'">
                        <p class="nav-link" href="#" @click="openLoginModal('')">登入</p>
                    </li>
                    <li class="nav-item" v-if="!flag || flag === 'logout'">
                        <router-link class="nav-link" to="/register">註冊</router-link>
                    </li>
                    <li v-if="Object.keys(userData).length !== 0" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">您好 ${userData.name}</a>
                        <ul class="dropdown-menu" style="min-width: 5rem;">
                            <li>
                                <router-link class="dropdown-item" to="/membercentre">會員中心</router-link>
                            </li>
                            <li v-if="flag === 'login'">
                                <p class="dropdown-item" @click="logout">登出</p>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="content-wrapper">
            <router-view></router-view>
        </div>
        <div class="page-footer">
            <div class="row footer-content" ref="footer_slick">
                <div class="col-lg col-md col-sm">
                    <p class="title"><img src=" {{ asset('image/footer_logo.png') }} " class="img-fluid"></p>
                    <p>inFlux普匯金融科技，以普惠金融為志業，希望落實傳統銀行無法提供的金融服務。「金融專業」為核心，「高端科技」為輔具，提供「最有溫度」的社群服務，拉近人與人的距離，讓金融年輕化，說聲Hey～普匯！</p>
                </div>
                <div class="col-lg col-md col-sm list-row">
                    <p class="title">選單列表</p>
                    <ul class="list-column">
                        <li v-for="item in infoList"><i class="fas fa-check"></i>
                            <router-link :to="item.href">${item.text}</router-link>
                        </li>
                    </ul>
                    <ul class="list-column">
                        <li v-for="item in actionList">
                            <i class="fas fa-check"></i>
                            <router-link :to="item.href">${item.text}</router-link>
                        </li>
                    </ul>
                </div>
                <div class="col-lg col-md col-sm contact-row">
                    <p class="title">聯絡我們</p>
                    <p class="contact-item"><i class="fas fa-map-marker-alt"></i>
                        <span>台北市松江路111號11樓-2<span>
                    </p>
                    <div class="horizontal-line"></div>
                    <p class="contact-item"><i class="fas fa-phone"></i>
                        <span>02-2507-9990<span>
                    </p>
                    <div class="horizontal-line"></div>
                    <p class="contact-item"><i class="far fa-envelope"></i>
                        <span>service@influxfin.com<span>
                    </p>
                    <div class="horizontal-line"></div>
                    <p class="contact-item"><i class="fas fa-globe"></i>
                        <span>www.influxfin.com<span>
                    </p>
                </div>
                <div class="col-lg col-md col-sm map-row">
                    <p class="title">公司地址</p><iframe class="google-map-iframe" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7228.974640579589!2d121.533252!3d25.051467!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xdc99c2d5e98dbe12!2z5pmu5Yyv6YeR6J6N56eR5oqA6IKh5Lu95pyJ6ZmQ5YWs5Y-4!5e0!3m2!1szh-TW!2sus!4v1586930878876!5m2!1szh-TW!2sus"></iframe></iframe>
                </div>
            </div>
            <div class="horizontal-line"></div>
            <div class="row footer-content">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <p>「會員應依規定申報利息所得，投資人可考量借款人利息支出申報情況，視個人情況衡量申報金額多寡」<br>Copyright ©2018 普匯金融科技股份有限公司</p>
                </div>
                <div class="icon-warpper col-lg-6 col-md-6 col-sm-6">
                    <a class="quick-link" target="_blank" href="https://line.me/R/ti/p/%40kvd1654s"><i class="fab fa-line"></i></a>
                    <a class="quick-link" target="_blank" href="https://m.facebook.com/inFluxtw/"><i class="fab fa-facebook"></i></a>
                    <a class="quick-link" target="_blank" href="https://www.instagram.com/influxfin/"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="afc_popup hidden-phone" ref="afc_popup">
            <div><img src="{{ asset('image/message_icon.png') }}" class="img-fluid" @click="display"></div>
            <div><a target="_blank" href="https://line.me/R/ti/p/%40kvd1654s"><img src="{{ asset('image/line_icon.png') }}" class="img-fluid"></a></div>
            <div><a target="_blank" href="https://event.influxfin.com/R/url?p=17K5591Q"><img src="{{ asset('image/loan_icon.png') }}" class="img-fluid"></a></div>
            <div><a target="_blank" href="https://event.influxfin.com/r/iurl?p=webinvest"><img src="{{ asset('image/invest_icon.png') }}" class="img-fluid"></a></div>
        </div>
        <div class="afc_popup hidden-desktop" style="bottom: 150px;">
            <div><a target="_blank" href="https://event.influxfin.com/R/url?p=17K5591Q"><img src="{{ asset('image/loan_icon.png') }}" class="img-fluid"></a></div>
            <div><a target="_blank" href="https://event.influxfin.com/r/iurl?p=webinvest"><img src="{{ asset('image/invest_icon.png') }}" class="img-fluid"></a></div>
            <div><a target="_blank" href="https://line.me/R/ti/p/%40kvd1654s"><img src="{{ asset('image/line_icon.png') }}" class="img-fluid"></a></div>
        </div>
        <a class="back-top" @click="backtotop"><i class="fas fa-arrow-left"></i></a>
        <div id="loginForm" class="modal fade" ref="loginForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div v-if="isReset" id="forgetPwdModal" class="modal-content">
                    <div class="modal-header">
                        <div type="button" class="back" @click="switchForm">＜</div>
                        <div class="reset-title">重設密碼</div>
                    </div>
                    <div class="modal-body">
                        <div class="input-group">
                            <span class="input-group-addon label-text">手機：</span>
                            <input type="text" class="form-control label-input" placeholder="請輸入綁定之手機號碼" v-model="phone" maxlength="10">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text">新密碼：</span>
                            <input type="password" class="form-control label-input" placeholder="請輸入新密碼" v-model="newPassword" maxlength="50">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text">再次輸入新密碼：</span>
                            <input type="password" class="form-control label-input" placeholder="請再次輸入新密碼" v-model="confirmPassword" maxlength="50">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text">驗證碼：</span>
                            <div class="captcha-row">
                                <input type="text" class="form-control label-input" placeholder="請輸入6位數驗證碼" v-model="code" maxlength="6">
                                <button class="btn btn-captcha" @click="getCaptcha('smsloginphone')" v-if="!IsSended">取得驗證碼</button>
                                <div class="btn btn-disable" v-if="IsSended">${counter}S有效</div>
                                <span class="tip" v-if="IsSended">驗證碼已寄出</span>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger" v-if="pwdMessage">${pwdMessage}</div>
                    <div class="modal-footer">
                        <div v-if="(phone && newPassword && confirmPassword && code) ? false : true" class="btn btn-disable">送出</div>
                        <button type="button" v-else class="btn btn-submit" @click="submit">送出</button>
                    </div>
                </div>
                <div v-if="!isReset" id="loginModal" class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" @click="clearInterval(timer);">✕</button>
                        <div class="login-logo"><img src="./Image/logo_puhey.svg" class="img-fluid"></div>
                    </div>
                    <div class="modal-body">
                        <div class="input-group">
                            <button type="button" :class="['btn','btn-switch',{checked:!isCompany}]" @click="switchTag($event)">自然人登入</button>
                            <button type="button" :class="['btn','btn-switch',{checked:isCompany}]" @click="switchTag($event)">法人登入</button>
                        </div>
                        <div class="input-group" v-if="isCompany">
                            <span class="input-group-addon label-text">公司統編：</span>
                            <input type="text" class="form-control label-input" placeholder="請輸入統一編號" v-model="businessNum" maxlength="8">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text">帳號：</span>
                            <input type="text" class="form-control label-input" placeholder="10位數手機號碼" maxlength="10" v-model="account" maxlength="10">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text">密碼：</span>
                            <input type="password" class="form-control label-input" placeholder="請輸入密碼" v-model="password" maxlength="50">
                        </div>
                        <div class="input-group">
                            <div class="chiller_cb">
                                <input id="rememberAccount" type="checkbox" @click="setAccount" :checked="isRememberAccount">
                                <label for="rememberAccount">記住帳號</label>
                                <span></span>
                            </div>
                            <button type="button" :class="['btn','btn-password']" @click="switchForm">忘記密碼?</button>
                        </div>
                    </div>
                    <div class="alert alert-danger" v-if="message">${message}</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-submit" @click="doLogin">登入</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>