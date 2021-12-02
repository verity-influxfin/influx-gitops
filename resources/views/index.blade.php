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
    <meta name="keywords" content="手機ATM、分期、網路借貸、貸款、借貸平台、學生貸款、社團、投資、 複利、債權投資、金融科技">
    <meta name="description" content="首創台灣「AI風控審核無人化融資系統」，利用高端科技，全程無人為干擾，一支手機完成借貸！">
    <meta name="site_name" content="inFlux普匯金融科技">
    <meta name="title" content="inFlux普匯金融科技">
    <meta name="google-site-verification" content="2arsm3rXMMsobi4wX5akzPpQO6-Q6zgUjqwIT0P9UKo" />
    <meta name="image" content="{{ asset('images/site_icon.png') }}">
    <meta name="og:image" content="{{ asset('images/site_icon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="fb:app_id" content="2194926914163491">
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
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />

    <!-- local -->
    <link rel="stylesheet" href="{{ asset('css/web.css?'.csrf_token()) }}">

    <!--Facebook Comments-->
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.8&appId=2194926914163491&autoLogAppEvents=1&d=" + new Date().getTime();
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window,document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
     fbq('init', '2521369841313676');
    fbq('track', 'PageView');
    </script>
    <noscript>
     <img height="1" width="1"
    src="https://www.facebook.com/tr?id=2521369841313676&ev=PageView
    &noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-117279688-9"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
		gtag('config', 'AW-692812197');
    </script>
	<!-- Event snippet for 借款、投資app下載 conversion page In your html page, add the snippet and call gtag_report_conversion when someone clicks on the chosen link or button. -->
	<!-- 借款：WE5GCNWzgpoCEKXzrcoC 投資：vcdCCJyj_ZkCEKXzrcoC -->
	<script>
		window.addEventListener("load", function(event) {
		  setTimeout(function(){
				document.querySelectorAll("a[href*='investLink']").forEach(function(e){
					e.addEventListener('click',function(){
						gtag('event', 'conversion', {'send_to': 'AW-692812197/vcdCCJyj_ZkCEKXzrcoC'});
		   		});
		   	});

		    document.querySelectorAll("a[href*='borrowLink']").forEach(function(e){
					e.addEventListener('click',function(){
						gtag('event', 'conversion', {'send_to': 'AW-692812197/WE5GCNWzgpoCEKXzrcoC'});
		   		});
		   	});
		  },2000)
	  });
	</script>
</head>

<body>
    <noscript>
        please turn on your jacascript
    </noscript>
    <div id="web_index" @mousemove="clicked">
        <div class="alesis-header">
            <div class="logo">
                <a href="/index"><img src="/images/logo_new.png" class="image"></a>
            </div>
            <div class="center">
                <div class="item">
                    我要借款
                    <div class="sub">
                        <a href="/collegeLoan" class="item">學生貸款</a>
                        <a href="/workLoan" class="item">上班族貸款</a>
                        <a href="/engineerLoan" class="item">資訊工程師專案</a>
                        <div class="item -disabled">外匯車貸(coming soon)</div>
                        <div class="item -disabled">新創企業主貸(coming soon)</div>
                    </div>
                </div>
                <div class="separator"><img src="/images/alesis-drop-separator.svg" class="image"></div>
                <div class="item">
                    我要投資
                    <div class="sub">
                        <a href="/investment" class="item">債權投資</a>
                        <a href="/transfer" class="item">債權轉讓</a>
                    </div>
                </div>
            </div>
            <div class="aside">
                <div class="search-group" :class="{inputing}">
                    <input class="search-input" ref="search" placeholder="搜尋..." v-model="searchText" @keyup.enter="doSearch" />
                    <div class="clear-icon" @click="doClear">x</div>
                </div>
                <div class="item" v-show="!inputing">
                    分期付款超市
                    <div class="sub">
                        <a href="/mobileLoan" class="item">手機分期</a>
                    </div>
                </div>
                <div class="item" v-show="!inputing">
                    關於我們
                    <div class="sub">
                        <a href="/company" class="item">公司介紹</a>
                        <a href="/news" class="item">最新消息</a>
                    </div>
                </div>
                <div class="item" v-show="!inputing">
                    小學堂金融科技
                    <div class="sub">
                        <a href="/blog" class="item">小學堂</a>
                        <a href="/vlog?q=share" class="item">小學堂影音</a>
                    </div>
                </div>
                <div class="item" v-show="!inputing">
                    了解更多
                    <div class="sub">
                        <a href="/faq" class="item">常見問題</a>
                        <a href="/risk" class="item">風險報告書</a>
                        <a href="/projects" class="item">查看案件</a>
                    </div>
                </div>
                <a href="/news" class="item" v-show="!inputing">平台公告</a>
                <a href="/borrowLink" target="_blank" class="item" v-show="!inputing">下載APP</a>
                <div class="item" v-show="!inputing" @click="clickSearch">
                    <img class="search-icon" src="/images/alesis-search-icon.svg">
                </div>
                <div class="item">
                    <div v-if="!flag || flag === 'logout'"  @click="openLoginModal" class="login nav-item">SIGN IN</div>
                    <div v-if="Object.keys(userData).length !== 0" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" style="color: #fff;" href="#" data-toggle="dropdown">您好 @{{userData.name}}</a>
                        <ul class="dropdown-menu" style="min-width: 5rem;">
                            <li v-if="isInvestor == 0">
                                <router-link class="dropdown-item loan-link" to="/loannotification">借款人</router-link>
                            </li>
                            <li v-else>
                                <router-link class="dropdown-item invest-link" to="/investnotification">投資人</router-link>
                            </li>
                            <li v-if="flag === 'login'">
                                <p class="dropdown-item" @click="logout">登出</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="item hamburger">
                    <img class="icon icon-search" v-show="!inputing"  @click="inputing=true" src="/images/alesis-search-icon.svg">
                    <img class="icon icon-hamburger" src="/images/alesis-hamburger.svg">
                </div>

            </div>
            <div class="rwd-list">
                <div class="item -dropdown">
                    <div class="text">我要借款</div>
                    <div class="sub">
                        <a href="/collegeLoan" class="item">學生貸款</a>
                        <a href="/workLoan" class="item">上班族貸款</a>
                        <a href="/engineerLoan" class="item">資訊工程師專案</a>
                        <div class="item -disabled">外匯車貸(coming soon)</div>
                        <div class="item -disabled">新創企業主貸(coming soon)</div>
                    </div>
                </div>
                <div class="item -dropdown">
                    <div class="text">我要投資</div>
                    <div class="sub">
                        <a href="/investment" class="item">債權投資</a>
                        <a href="/transfer" class="item">債權轉讓</a>
                    </div>
                </div>
                <div class="item -dropdown">
                    <div class="text">分期付款超市</div>
                    <div class="sub">
                        <a href="/mobileLoan" class="item">手機分期</a>
                    </div>
                </div>
                <div class="item -dropdown">
                    <div class="text">關於我們</div>
                    <div class="sub">
                        <a href="/company" class="item">公司介紹</a>
                        <a href="/news" class="item">最新消息</a>
                    </div>
                </div>
                <div class="item -dropdown">
                    <div class="text">小學堂金融科技</div>
                    <div class="sub">
                        <a href="/blog" class="item">小學堂</a>
                        <a href="/vlog?q=share" class="item">小學堂影音</a>
                    </div>
                </div>
                <div class="item -dropdown">
                    <div class="text">了解更多</div>
                    <div class="sub">
                        <a href="/faq" class="item">常見問題</a>
                        <a href="/risk" class="item">風險報告書</a>
                        <a href="/projects" class="item">查看案件</a>
                    </div>
                </div>
                <a href="/borrowLink" target="_blank" class="item">下載APP</a>
                <div class="item">
                    <div v-if="!flag || flag === 'logout'" @click="openLoginModal" class="login"><i class="fas fa-user"></i> SIGN IN</div>
                    <div v-if="Object.keys(userData).length !== 0" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" style="color: #fff;" href="#" data-toggle="dropdown">您好 @{{userData.name}}</a>
                        <ul class="dropdown-menu" style="min-width: 5rem;">
                            <li v-if="isInvestor == 0">
                                <router-link class="dropdown-item loan-link" to="/loannotification">借款人</router-link>
                            </li>
                            <li v-else>
                                <router-link class="dropdown-item invest-link" to="/investnotification">投資人</router-link>
                            </li>
                            <li v-if="flag === 'login'">
                                <p class="dropdown-item" @click="logout">登出</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            window.addEventListener('load', function() {
                document.querySelector(".icon-hamburger").addEventListener("click", () => {
                    document.querySelector(".rwd-list").classList.toggle("-active")
                })

                /*document.querySelector("body").addEventListener("click", (e) =>{
                    if (e.target.closest(".item") !== null) {
                        return
                    }
                        document.querySelectorAll(".aside > .item, .center > .item").forEach((v) => {
                            v.classList.remove("-active")
                        })
                })

                document.querySelectorAll(".aside > .item, .center > .item").forEach((v) => {
                    v.addEventListener("click", (e) => {
                        Array.prototype.filter.call(document.querySelectorAll(".aside .item , .center > .item"), (j) => {
                            return v !== j
                        }).forEach((v) =>{
                            v.classList.remove("-active")
                        })
                        v.classList.toggle("-active")
                    })
                })*/

                document.querySelectorAll(".rwd-list .item").forEach((v) => {
                    v.addEventListener("click", (e) => {
                        Array.prototype.filter.call(document.querySelectorAll(".rwd-list .item"), (j) => {
                            return v !== j
                        }).forEach((v) =>{
                            v.classList.remove("-active")
                        })
                        v.classList.toggle("-active")
                    })
                })
            })
        </script>

        <div class="content-wrapper">
            <router-view></router-view>
        </div>
        <div class="alesis-footer">
            <div class="line"></div>
            <div class="main">
                <div class="introduction">
                    <div class="brand">
                        <div class="text">認識</div>
                        <img class="image" src="/images/footer@2x.png">
                    </div>
                    <div class="paragraphy">
                        「普匯．你的手機ATM」<br>
                        inFlux普匯金融科技，以普惠金融為志業，希望落實傳統銀行無法提供的金融服務。「金融專業」為核心，「高端科技」為輔具，提供「最有溫度」的社群服務，拉近人與人的距離，讓金融年輕化。
                    </div>
                        <div class="paragraphy">
                        「繳稅警語」<br>
                        普匯溫馨提醒所有用戶，在普匯的相關利息收入與支出，都應主動申報綜合所得稅，以善盡繳稅義務，避免遭稅捐機關開罰。
                    </div>
                </div>
                <div class="information">
                    <div class="item">
                        <div class="header">我要申貸</div>
                        <div class="list">
                            <a href="/collegeLoan">學生貸款</a>
                            <a href="/workLoan">上班族貸款</a>
                            <a href="/engineerLoan">資訊工程師專案</a>
                            <a>車輛融資</a>
                            <a>企業融資</a>
                            <a href="/mobileLoan">手機分期</a>
                        </div>
                    </div>
                    <div class="item">
                        <div class="header">我要投資</div>
                        <div class="list">
                            <a href="/investment">債權投資</a>
                            <a href="/transfer">債權轉讓</a>
                        </div>
                    </div>
                    <div class="item">
                        <div class="header">關於我們</div>
                        <div class="list">
                            <a href="/company">關於我們</a>
                            <a href="/news">最新消息</a>
                            <a href="/blog">AI金融科技新知</a>
                        </div>
                    </div>
                    <div class="item">
                        <div class="header">聯絡我們</div>
                        <div class="meta">
                            <div class="icon"><img src="/images/alesis-clock.svg"></div>
                            <div class="text">服務時間  9:00 AM - 6:00 PM</div>

                            <div class="icon"><img src="/images/alesis-phone.svg"></div>
                            <div class="text"><a href="tel:+886225079990" target="_blank">02-2507-9990</a></div>

                            <div class="icon"><img src="/images/alesis-email.svg"></div>
                            <div class="text"><a href="mailto:service@influxfin.com" target="_blank">service@influxfin.com</a></div>

                            <div class="icon"><img src="/images/alesis-address.svg"></div>
                            <div class="text"><a href="https://goo.gl/maps/5J27gauTT5Fw87PD8" target="_blank">台北市中山區松江路111號11樓之1</a></div>
                        </div>
                        <div class="socials">
                            <a href="https://m.facebook.com/inFluxtw/" target="_blank" class="item"><img src="/images/alesis-facebook.svg"></a>
                            <a href="https://line.me/R/ti/p/%40kvd1654s" target="_blank" class="item"><img src="/images/alesis-line.svg"></a>
                            <a href="https://www.instagram.com/pop.finance/" target="_blank" class="item"><img src="/images/alesis-instagram.svg"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="secondary">
                <div class="copyright">
                    Copyright ©2020 普匯金融科技股份有限公司 All rights reserved.
                    <div class="links">
                        <a href="/userTerms" class="item">使用者條款</a> |
                        <a href="/privacyTerms" class="item">隱私權條款</a> |
                        <a href="/loanerTerms" class="item">借款人服務條款</a>
                    </div>
                </div>
                <div class="links">
                    <a href="/recruiting" class="item">徵才服務</a> |
                    <a href="/campaign/2021-campus-ambassador" class="item">校園大使</a> |
                    <a href="/clubcooperation" class="item">社團合作</a> |
                    <a href="/firmcooperation" class="item">商行合作</a> |
                    <a href="/companycooperation" class="item">企業合作</a> |
                    <a href="/promote-code-intro" class="item">推薦有賞</a>
                </div>
                <div class="externals">
                    <a class="item" href="/borrowLink">我想申貸</a>
                    <a class="item -invest" href="/investLink">我想投資</a>
                </div>
            </div>
        </div>

        <!--<a class="back-top" @click="backtotop"><img src="{{ asset('images/top.svg') }}" class="img-fluid" /></a>-->
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
                            <input type="text" class="form-control label-input" placeholder="請輸入統一編號" autocomplete="off" v-model="businessNum" maxlength="8">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text">帳號：</span>
                            <input type="text" class="form-control label-input" placeholder="10位數手機號碼" autocomplete="off" v-model="account" maxlength="10">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text">密碼：</span>
                            <input type="password" class="form-control label-input" placeholder="請輸入密碼" autocomplete="off" v-model="password" maxlength="50">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text"></span>
                            <div class="radio-custom">
                                <label><input type="radio" name="investor" class="radio-inline" value="0" v-model="investor" checked><span class="outside"><span class="inside"></span></span>借款人</label>
                                <label><input type="radio" name="investor" class="radio-inline" value="1" v-model="investor"><span class="outside"><span class="inside"></span></span>投資人</label>
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
                        <router-link v-if="!isCompany" class="btn btn-register" @click.native="hideLoginModal()" to="/register">會員註冊</router-link>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
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
<script type="text/javascript" src="{{ asset('js/web.js?'.csrf_token()) }}"></script>

</html>
