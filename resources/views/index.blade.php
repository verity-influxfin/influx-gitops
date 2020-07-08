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
    <link rel="icon" href="{{ asset('Images/site_icon.png') }}">

    <!-- package -->
    <link rel="stylesheet" href="{{ asset('css/package/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/pagination.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/hover-min.css') }}">

    <!-- local -->
    <link rel="stylesheet" href="{{ asset('css/web.css') }}">

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
    <noscript>
        please turn on your jacascript
    </noscript>
    <div id="web_index" @mousemove="clicked">
        <div class="blog-quiklink" ref="quiklink">
            <i class="far fa-times-circle" @click="$($refs.quiklink).remove()"></i>
            <router-link to="blog"><img src="{{ asset('Images/ah-pu.svg') }}"></router-link>
        </div>
        <div class="banner" ref="banner">
            <div class="puhey-banner">
                <img src="{{ asset('Images/index-banner.jpg') }}" style="width:100%" />
                <div class="content">
                    <p>全線上AI無人干擾，隨時滿足你的資金需求</p>
                    <span>普匯．你的手機ATM</span>
                </div>
                <div class="app-entrance">
                    <router-link class="btn btn-go float-left" to="freshGraduateLoan">我是上班族</router-link>
                    <router-link class="btn btn-go float-right" to="collegeLoan">我是大學生</router-link>
                </div>
            </div>
            <img src="{{ asset('Images/index-banner.jpg') }}" style="width:100%" />
        </div>
        <nav class="page-header navbar navbar-expand-lg">
            <div class="web-logo">
                <router-link to="/index"><img src=" {{ asset('Images/logo.png') }}" class="img-fluid"></router-link>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ml-auto">
                    <li v-for="item in menuList" class="nav-item dropdown">
                        <router-link v-if="item.subMenu.length === 0" class="nav-link" :to="item.href">@{{item.title}}</router-link>
                        <a v-else class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">@{{item.title}}</a>
                        <ul class="dropdown-menu" v-if="item.subMenu.length !== 0">
                            <li v-for="subItem in item.subMenu" :class="[(!subItem.isActive ? 'coming-soon' : '')]">
                                <router-link class="dropdown-item" :to="subItem.href">@{{subItem.name}}@{{!subItem.isActive ? '(coming soon)' : ''}}</router-link>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item" v-if="!flag || flag === 'logout'">
                        <p class="nav-link" href="#" @click="openLoginModal()">登入</p>
                    </li>
                    <li class="nav-item" v-if="!flag || flag === 'logout'">
                        <router-link class="nav-link" to="/register">註冊</router-link>
                    </li>
                    <li v-if="Object.keys(userData).length !== 0" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">您好 @{{userData.name}}</a>
                        <ul class="dropdown-menu" style="min-width: 5rem;">
                            <li v-if="isInvestor == 0">
                                <router-link class="dropdown-item loan-link" to="/myloan">借款端</router-link>
                            </li>
                            <li v-else>
                                <router-link class="dropdown-item invest-link" to="/myinvestment">投資專區</router-link>
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
            <div class="footer-content">
                <div class="list-card">
                    <h4>選單列表</h4>
                    <div class="card-content">
                        <ul v-for="row in menuList" class="list-column">
                            <template v-if="row.href">
                                <li>
                                    <router-link :to="row.href">@{{row.title}}</router-link>
                                </li>
                            </template>
                            <template v-else>
                                <li v-for="item in row.subMenu">
                                    <router-link :to="item.href">@{{item.name}}</router-link>
                                </li>
                            </template>
                        </ul>
                    </div>
                </div>
                <div class="cooperation-card">
                    <h4>我想合作</h4>
                    <div class="card-content">
                        <ul class="list-column">
                            <li v-for="item in actionList">
                                <router-link :to="item.href">@{{item.text}}</router-link>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="contact-card">
                    <h4>聯絡我們</h4>
                    <div class="card-content">
                        <div>
                            <a href="https://goo.gl/maps/5J27gauTT5Fw87PD8" target="_blank"><span><i class="fas fa-map-marker-alt"></i>&ensp;台北市松江路111號11樓-2</span></a>
                            <div class="horizontal-line"></div>
                            <span><i class="fas fa-phone"></i>&ensp;02-2507-9990</span>
                            <div class="horizontal-line"></div>
                            <span><i class="far fa-envelope"></i>&ensp;service@influxfin.com</span>
                        </div>
                    </div>
                </div>
                <div class="follow-card">
                    <h4>Follow us</h4>
                    <a class="quick-link" target="_blank" href="https://line.me/R/ti/p/%40kvd1654s"><i class="fab fa-line"></i></a>
                    <a class="quick-link" target="_blank" href="https://m.facebook.com/inFluxtw/"><i class="fab fa-facebook-square"></i></a>
                    <a class="quick-link" target="_blank" href="https://www.instagram.com/influxfin/"><i class="fab fa-instagram"></i></a>
                </div>
                <div class="download-card">
                    <div class="card-content">
                        <h5>申貸</h5>
                        <div class="down-pic">
                            <a href="https://event.influxfin.com/R/url?p=webbanner" target="_blank"><img src="{{ asset('Images/google-play-badge.png') }}" class="img-fluid"></a>
                            <a href="https://event.influxfin.com/R/url?p=webbanner" target="_blank"><img src="{{ asset('Images/app-store-badge.png') }}" class="img-fluid"></a>
                        </div>
                        <h5>投資</h5>
                        <div class="down-pic">
                            <a href="https://event.influxfin.com/r/iurl?p=webinvest" target="_blank"><img src="{{ asset('Images/google-play-badge.png') }}" class="img-fluid"></a>
                            <a href="https://event.influxfin.com/r/iurl?p=webinvest" target="_blank"><img src="{{ asset('Images/app-store-badge.png') }}" class="img-fluid"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="horizontal-line"></div>
            <div class="row footer-license">
                <p>Copyright ©2018 普匯金融科技股份有限公司</p>
                <p>
                    <router-link to="/userTerms">使用者條款</router-link><i class="gap fas fa-grip-lines-vertical"></i>
                    <router-link to="/privacyTerms">隱私條款政策</router-link><i class="gap fas fa-grip-lines-vertical"></i>
                    <router-link to="/loanerTerms">借款人服務條款</router-link>
                </p>
                <p>會員應依規定申報利息所得，投資人可考量借款人利息支出申報情況，視個人情況衡量申報金額多寡</p>
            </div>
        </div>
        <div class="afc_popup hidden-phone" ref="afc_popup">
            <div><img src="{{ asset('Images/message_icon.png') }}" class="img-fluid" @click="display"></div>
            <div><a target="_blank" href="https://line.me/R/ti/p/%40kvd1654s"><img src="{{ asset('Images/line_icon.png') }}" class="img-fluid"></a></div>
            <div><a target="_blank" href="https://event.influxfin.com/R/url?p=webbanner"><img src="{{ asset('Images/loan_icon.png') }}" class="img-fluid"></a></div>
            <div><a target="_blank" href="https://event.influxfin.com/r/iurl?p=webinvest"><img src="{{ asset('Images/invest_icon.png') }}" class="img-fluid"></a></div>
        </div>
        <div class="afc_popup hidden-desktop" style="bottom: 150px;">
            <div><a target="_blank" href="https://event.influxfin.com/R/url?p=webbanner"><img src="{{ asset('Images/loan_icon.png') }}" class="img-fluid"></a></div>
            <div><a target="_blank" href="https://event.influxfin.com/r/iurl?p=webinvest"><img src="{{ asset('Images/invest_icon.png') }}" class="img-fluid"></a></div>
            <div><a target="_blank" href="https://line.me/R/ti/p/%40kvd1654s"><img src="{{ asset('Images/line_icon.png') }}" class="img-fluid"></a></div>
        </div>
        <a class="back-top" @click="backtotop"><img src="{{ asset('Images/top.svg') }}" class="img-fluid" /></a>
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
                                <button class="btn btn-captcha" @click="getCaptcha('smsloginphone')" v-if="!isSended">取得驗證碼</button>
                                <div class="btn btn-disable" v-if="isSended">@{{counter}}S有效</div>
                                <span class="tip" v-if="isSended">驗證碼已寄出</span>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger" v-if="pwdMessage">@{{pwdMessage}}</div>
                    <div class="modal-footer">
                        <div v-if="(phone && newPassword && confirmPassword && code) ? false : true" class="btn btn-disable">送出</div>
                        <button type="button" v-else class="btn btn-submit" @click="submit">送出</button>
                    </div>
                </div>
                <div v-if="!isReset" id="loginModal" class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" @click="clearInterval(timer);">✕</button>
                        <div class="login-logo"><img src="./Images/logo_puhey.svg" class="img-fluid"></div>
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
                            <span class="input-group-addon label-text"></span>
                            <div class="radio-custom">
                                <label><input type="radio" name="investor" class="radio-inline" value="0" v-model="investor" checked><span class="outside"><span class="inside"></span></span>借款端</label>
                                <label><input type="radio" name="investor" class="radio-inline" value="1" v-model="investor"><span class="outside"><span class="inside"></span></span>投資端</label>
                            </div>
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
                    <div class="alert alert-danger" v-if="message">@{{message}}</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-submit" @click="doLogin">登入</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- recaptcha-->
<script src="https://www.google.com/recaptcha/api.js?render=6LfQla4ZAAAAAGrpdqaZYkJgo_0Ur0fkZHQEYKa3"></script>

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

</html>