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
    <meta name="keywords" content="普匯、手機ATM、分期、網路借貸、貸款、借貸平台、學生貸款、社團、投資、利率、複利、債權投資、金融科技、打工、P2P">
    <meta name="description" content="普匯．你的手機ATM 幫助年輕人完成夢想 協助打造溫暖家庭小窩 解決社會大眾生活急需">
    <meta name="site_name" content="inFlux普匯金融科技">
    <meta name="title" content="inFlux普匯金融科技">
    <meta name="image" content="{{ asset('images/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property=”fb:app_id” content=”2194926914163491”>
    <title>inFlux普匯金融科技</title>
    <link rel="icon" href="{{ asset('images/site_icon.png') }}">

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
    </script>
</head>

<body>
    <noscript>
        please turn on your jacascript
    </noscript>
    <div id="web_index" @mousemove="clicked">
        <nav class="page-header navbar navbar-expand-lg sticky">
            <div class="web-logo">
                <router-link to="index"><img src=" {{ asset('images/logo_new.png') }}" class="img-fluid"></router-link>
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
                        <p class="nav-link l" href="#" @click="openLoginModal()"><i class="fas fa-user"></i>SING IN</p>
                    </li>
                    <li v-if="Object.keys(userData).length !== 0" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">您好 @{{userData.name}}</a>
                        <ul class="dropdown-menu" style="min-width: 5rem;">
                            <li v-if="isInvestor == 0">
                                <router-link class="dropdown-item loan-link" to="/loannotification">借款端</router-link>
                            </li>
                            <li v-else>
                                <router-link class="dropdown-item invest-link" to="/investnotification">投資專區</router-link>
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
            <div class="btm-line"></div>
            <div class="top-content">
                <div class="desc-card">
                    <div class="d-a">
                        <H2>About</H2>
                        <div class="img"><img src="{{ asset('images/footer.png') }}" class="img-fluid"></div>
                    </div>
                    <div class="cd">
                        <div class="img"><img class="img-fluid" src="/images/ah-pu.svg"></div>
                        <div class="cnt">
                            <p class="c-s">「普匯．你的手機ATM」</p>
                            <p class="c-s">inFlux普匯金融科技，以普惠金融為志業，希望落實傳統銀行無法提供的金融服務。「金融專業」為核心，「高端科技」為輔具，提供「最有溫度」的社群服務，拉近人與人的距離，讓金融年輕化。</p>

                        </div>
                    </div>
                </div>
                <div class="loan-card">
                    <H2>Product</H2>
                    <ul class="list-column">
                        <li>
                            <router-link to="/collegeLoan">學生貸款</router-link>
                        </li>
                        <li>
                            <router-link to="/freshGraduateLoan">上班族貸款</router-link>
                        </li>
                        <li>
                            <router-link to="/engineerLoan">資訊工程師專案</router-link>
                        </li>
                        <li>
                            <router-link to="">外匯車貸</router-link>
                        </li>
                        <li>
                            <router-link to="">新創企業主貸</router-link>
                        </li>
                        <li>
                            <router-link to="/mobileLoan">手機分期</router-link>
                        </li>
                    </ul>
                </div>
                <div class="invest-card">
                    <H2>Investment</H2>
                    <ul class="list-column">
                        <li>
                            <router-link to="/invest">債權投資</router-link>
                        </li>
                        <li>
                            <router-link to="/transfer">債權轉讓</router-link>
                        </li>
                    </ul>
                </div>
                <div class="support-card">
                    <H2>About Us</H2>
                    <ul class="list-column">
                        <li>
                            <router-link to="/company">關於我們</router-link>
                        </li>
                        <li>
                            <router-link to="/news">最新消息</router-link>
                        </li>
                        <li>
                            <router-link to="/blog">AI金融科技新知</router-link>
                        </li>
                    </ul>
                </div>
                <div class="about-card">
                    <H2>Follow Us</H2>
                    <div class="text">
                        <p><a href="tel:+886225079990" target="_blank"><span><i class="fas fa-phone"></i>&ensp;02-2507-9990</span></a></p>
                        <p><a href="mailto:service@influxfin.com" target="_blank"><span><i class="far fa-envelope"></i>&ensp;service@influxfin.com</span></a></p>
                        <p><a href="https://goo.gl/maps/5J27gauTT5Fw87PD8" target="_blank"><span><i class="fas fa-map-marker-alt"></i>&ensp;台北市松江路111號11樓-2</span></a></p>
                    </div>
                    <div class="community">
                        <a target="_blank" href="https://m.facebook.com/inFluxtw/"><i class="fab fa-facebook"></i></a>
                        <a target="_blank" href="https://line.me/R/ti/p/%40kvd1654s"><i class="fab fa-line"></i></a>
                        <a target="_blank" href="https://www.instagram.com/pop.finance/"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr />
            <div class="bottom-content">
                <div class="contact">
                    <router-link to="/recruiting">徵才服務</router-link>|
                    <router-link to="/campuspartner">校園大使</router-link>|
                    <router-link to="/clubcooperation">社團合作</router-link>|
                    <router-link to="/firmcooperation">商行合作</router-link>|
                    <router-link to="/companycooperation">企業合作</router-link>
                </div>
                <div class="license">
                    <p>Copyright ©2020 普匯金融科技股份有限公司 All rights reserved. </p>
                    <p>
                        <router-link to="/userTerms">使用者條款</router-link>|
                        <router-link to="/privacyTerms">隱私條款政策</router-link>|
                        <router-link to="/loanerTerms">借款人服務條款</router-link>
                    </p>
                </div>
            </div>
        </div>

        <a class="back-top" @click="backtotop"><img src="{{ asset('images/top.svg') }}" class="img-fluid" /></a>
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
                        <div class="login-logo"><img src="/images/logo_puhey.svg" class="img-fluid"></div>
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
                        <div class="input-group">
                        </div>
                    </div>
                    <div class="alert alert-danger" v-if="message">@{{message}}</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-submit" @click="doLogin">登入</button>
                        <router-link class="btn btn-register" @click.native="hideLoginModal()" to="/register">會員註冊</router-link>
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
<script type="text/javascript" src="{{ asset('js/package/particles.min.js') }}"></script>

<!-- local -->
<script type="text/javascript" src="{{ asset('js/web.js') }}"></script>

</html>