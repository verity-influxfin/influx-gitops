<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns:og="http://ogp.me/ns#"
    xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '<?php echo env('APP_ENV ') == 'production ' ? 'GTM-5Z439PW' : 'GTM-589Z9H6'; ?>');
    </script>
    <!-- End Google Tag Manager -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />
    <meta name="keywords" content="新北產業發展論壇、一金二造三區域、信貸、小額貸款、房貸試算、裝潢貸款、車貸試算、投資、債務整合、p2p金融科技、智慧製造、中小企業、中小企業融資">
    <meta property="og:description"
        content="{{ $meta_data['meta_og_description'] ?? '普匯金融科技擁有全台首創風控審核無人化融資系統。普匯提供小額信用貸款申貸服務，資金用途涵蓋購房、購車，或是房屋裝修潢。您可在普匯官網取得貸款額度試算結果！現在就來體驗最新的p2p金融科技吧！除了個人信貸，普匯也提供中小企業融資，幫助業主轉型智慧製造。' }}">
    <meta name="description"
        content="{{ $meta_data['meta_description'] ?? '普匯金融科技擁有全台首創風控審核無人化融資系統。普匯提供小額信用貸款申貸服務，資金用途涵蓋購房、購車，或是房屋裝修潢。您可在普匯官網取得貸款額度試算結果！現在就來體驗最新的p2p金融科技吧！除了個人信貸，普匯也提供中小企業融資，幫助業主轉型智慧製造。' }}">
    <meta property="og:site_name" content="inFlux普匯金融科技">
    <meta property="og:title" content="{{ $meta_data['meta_og_title'] ?? 'inFlux普匯金融科技' }}">
    <meta name="google-site-verification" content="2arsm3rXMMsobi4wX5akzPpQO6-Q6zgUjqwIT0P9UKo" />
    <meta property="og:image" content="{{ $meta_data['meta_og_image'] ?? asset('images/site_icon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="fb:app_id" content="2194926914163491">
    <title>{{ $article->post_title . ' - inFlux普匯金融科技' ?? 'inFlux普匯金融科技' }}</title>
    <link rel="icon" href="{{ asset('images/site_icon.png') }}">
    <!-- package -->
    @if (isset($meta_data['link']))
        <link rel="canonical" href="{{ $meta_data['link'] }}" />
    @endif
    <link rel="stylesheet" href="{{ asset('css/package/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/pagination.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/package/hover-min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans+TC">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- local -->
    <link rel="stylesheet" href="{{ mix('css/web.css') }}">
    <link rel="stylesheet" href="{{ mix('css/articlepage.css') }}">

    <!--Facebook Comments-->
    <script>
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src =
                "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.8&appId=2194926914163491&autoLogAppEvents=1&d=" +
                new Date().getTime();
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '2521369841313676');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1"
            src="https://www.facebook.com/tr?id=2521369841313676&ev=PageView
    &noscript=1" />
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
            setTimeout(function() {
                document.querySelectorAll("a[href*='investLink']").forEach(function(e) {
                    e.addEventListener('click', function() {
                        gtag('event', 'conversion', {
                            'send_to': 'AW-692812197/vcdCCJyj_ZkCEKXzrcoC'
                        });
                    });
                });

                document.querySelectorAll("a[href*='borrowLink']").forEach(function(e) {
                    e.addEventListener('click', function() {
                        gtag('event', 'conversion', {
                            'send_to': 'AW-692812197/WE5GCNWzgpoCEKXzrcoC'
                        });
                    });
                });
            }, 2000)
        });
    </script>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe
            src="https://www.googletagmanager.com/ns.html?id={{ env('APP_ENV') == 'production' ? 'GTM-5Z439PW' : 'GTM-589Z9H6' }}"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <noscript>
        please turn on your jacascript
    </noscript>
    <div id="web_index" @mousemove="clicked">
        <div class="header-container">
            <div class="row no-gutters">
                <div class="col-auto alesis-header">
                    <div class="logo">
                        <a href="/"><img src="/images/logo.png" class="image"></a>
                    </div>
                    <div class="d-sm-flex d-none no-gutters w-100">
                        <div class="functions col-auto">
                            <div class="function-item">
                                <div class="function-title">個人貸款</div>
                                <div class="function-list row no-gutters">
                                    <div class="col-auto d-flex no-gutters">
                                        <div class="function-list-content product">
                                            <div class="function-list-items">
                                                <div class="link-title">AI風控 助你圓夢</div>
                                                <div class="link-item">
                                                    <a href="/workLoan" class="link-text">上班族貸</a>
                                                </div>
                                                <div class="link-item">
                                                    <a href="/collegeLoan" class="link-text">學生貸</a>
                                                </div>
                                                <div class="link-item">
                                                    <a href="/engineerLoan" class="link-text">資訊工程師貸</a>
                                                </div>
                                                <div class="link-item">
                                                    <a href="/houseLoan" class="link-text">房屋貸款</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="function-item">
                                <div class="function-title">中小企業貸款</div>
                                <div class="function-list row no-gutters">
                                    <div class="col-auto d-flex no-gutters">
                                        <div class="function-list-content enterprise">
                                            <div class="function-list-items ">
                                                <div class="link-title">普惠金融 貸你滿足</div>
                                                {{-- <div class="link-item">
                                                    <a href="/business-loan/smeg" class="link-text">
                                                        <div>信保專案</div>
                                                    </a>
                                                </div> --}}
                                                <div class="link-item">
                                                    <div class="link-text">
                                                        <div>信保專案</div>
                                                        <div class="link-coming-soon">(coming soon)</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="function-item d-none">
                                <div class="function-title">分期超市</div>
                                <div class="function-list row no-gutters">
                                    <div class="col-auto d-flex no-gutters">
                                        <div class="function-list-content project">
                                            <div class="function-list-items">
                                                <div class="link-title">體驗金融科技帶來的便利</div>
                                                <div class="link-item">
                                                    <div class="link-text">
                                                        <div>美賣店商</div>
                                                        <div class="link-coming-soon">(coming soon)</div>
                                                    </div>
                                                </div>
                                                <div class="link-item">
                                                    <div class="link-text">
                                                        <div>醫美分期</div>
                                                        <div class="link-coming-soon">(coming soon)</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="function-item">
                                <div class="function-title">慈善公益</div>
                                <div class="function-list row no-gutters">
                                    <div class="col-auto d-flex no-gutters">
                                        <div class="function-list-content charitable">
                                            <div class="function-list-items">
                                                <div class="link-title">永續經營 幸福無所不在</div>
                                                <div class="link-item">
                                                    <a href="/charitable" class="link-text">台大兒醫</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="function-item">
                                <div class="function-title">投資專區</div>
                                <div class="function-list row no-gutters">
                                    <div class="col-auto d-flex no-gutters">
                                        <div class="function-list-content invest">
                                            <div class="function-list-items">
                                                <div class="link-title">小額分散 複利滾投</div>
                                                <div class="link-item">
                                                    <a href="/investment" class="link-text">債權投資</a>
                                                </div>
                                                <div class="link-item">
                                                    <a href="/transfer" class="link-text">債權轉讓</a>
                                                </div>
                                                <div class="link-item">
                                                    <a href="/risk" class="link-text">風險報告書</a>
                                                </div>
                                                <div class="link-item">
                                                    <a href="/projects" class="link-text">查看案件</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="function-item">
                                <div class="function-title">關於普匯</div>
                                <div class="function-list row no-gutters">
                                    <div class="col-auto d-flex no-gutters">
                                        <div class="function-list-content about">
                                            <div class="function-list-items">
                                                <div class="link-title">關於普匯</div>
                                                <div class="link-item">
                                                    <a href="/company" class="link-text">關於我們</a>
                                                </div>
                                                <div class="link-item">
                                                    <a href="/news" class="link-text">最新消息</a>
                                                </div>
                                                <div class="link-item">
                                                    <a href="/faq" class="link-text">瞭解更多</a>
                                                </div>
                                            </div>
                                            <div class="function-list-items">
                                                <div class="link-title">合作洽談</div>
                                                <div class="link-item">
                                                    <a href="/recruiting" class="link-text">普匯徵才</a>
                                                </div>
                                                <div class="link-item">
                                                    <a href="/clubcooperation" class="link-text">社團贊助</a>
                                                </div>
                                                <div class="link-item">
                                                    <a href="/companycooperation" class="link-text">業務合作</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="function-item">
                                <a href="/blog" class="function-title">小學堂</a>
                                <div class="function-list row no-gutters">
                                    <div class="col-auto d-flex no-gutters">
                                        <div class="function-list-content article">
                                            <div class="function-list-items">
                                                <div class="link-title">普匯小學堂 最新文章</div>
                                                <div class="link-item">
                                                    <a class="link-text" href="/vlog?q=share">小學堂影音</a>
                                                </div>
                                                @foreach ($latestArticles as $latestArticle)
                                                <div class="link-item">
                                                    @if (!empty($latestArticle->path))
                                                    <a class="link-text" href="/articlepage/{{ $latestArticle->path }}">
                                                        @else
                                                        <a class="link-text"
                                                            href="/articlepage?q=knowledge-{{ $latestArticle->ID }}">
                                                            @endif
                                                            {{ $latestArticle->post_title }}
                                                        </a>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col"></div>
                        <div class="col-auto d-flex">
                            <div class="search-icon" v-show="!inputing" @click="clickSearch">
                                <img src="/images/search-icon-blue.svg" alt="">
                            </div>
                            <div class="search-group" :class="{ inputing }">
                                <input class="search-input" ref="search" placeholder="搜尋..." v-model="searchText"
                                    @keyup.enter="doSearch" />
                                <div class="clear-icon" @click="doClear">x</div>
                            </div>
                            <div class="item ml-3 d-flex align-items-center">
                                <button @click="openLoginModal" class="login-btn btn d-none"
                                    :class="{ 'd-block': !flag || flag === 'logout' }">註冊/登入<i
                                        class="ml-2 fa fa-arrow-right"></i></button>
                                <div class="nav-item dropdown d-none"
                                    :class="{ 'd-block': Object.keys(userData).length !== 0 }">
                                    <a class="nav-link dropdown-toggle" style="color: #036EB7;" href="#"
                                        data-toggle="dropdown">您好 @{{ userData.name }}</a>
                                    <ul class="dropdown-menu" style="min-width: 5rem;">
                                        <li v-if="isInvestor == 0">
                                            <router-link class="dropdown-item loan-link"
                                                to="/loannotification">借款人</router-link>
                                        </li>
                                        <li v-else>
                                            <router-link class="dropdown-item invest-link"
                                                to="/investnotification">投資人</router-link>
                                        </li>
                                        <li v-if="flag === 'login'">
                                            <p class="dropdown-item" @click="logout">登出</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex d-sm-none">
                        <div class="search-icon mr-3" v-show="!inputing" @click="clickSearch">
                            <img src="/images/search-icon-blue.svg" alt="">
                        </div>
                        <div class="search-group" :class="{ inputing }">
                            <input class="search-input" ref="search" placeholder="搜尋..." v-model="searchText"
                                @keyup.enter="doSearch" />
                            <div class="clear-icon" @click="doClear">x</div>
                        </div>
                        <div class="bar icon-hamburger">
                            <i class="fa fa-bars"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="rwd-list">
                <div class="item -dropdown">
                    <div class="text">我要借款</div>
                    <div class="sub">
                        <div class="sub-title">
                            <div class="sub-title-icon">
                                <img src="/images/personal-header-icon.svg" alt="">
                            </div>
                            <div>個人融資</div>
                        </div>
                        <a href="/collegeLoan" class="item">學生貸款</a>
                        <a href="/workLoan" class="item">上班族貸款</a>
                        <a href="/engineerLoan" class="item">資訊工程師專案</a>
                        <div class="sub-title">
                            <div class="sub-title-icon">
                                <img src="/images/business-header-icon.svg" alt="">
                            </div>
                            <a href="/business-loan">企業融資</a>
                        </div>
                        {{-- <a href="/business-loan/smeg" class="item">中小企業融資(信保)</a> --}}
                        <div class="item -disabled">中小企業融資(信保) (coming soon)</div>
                        <div class="item -disabled">企業主速貸 (coming soon)</div>
                        <div class="item -disabled">中小企業信貸 (coming soon)</div>
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
                    <div class="text">關於我們</div>
                    <div class="sub">
                        <a href="/company" class="item">公司介紹</a>
                        <a href="/news" class="item">最新消息</a>
                    </div>
                </div>
                <div class="item -dropdown">
                    <div class="text">小學堂金融科技</div>
                    <div class="sub">
                        <a href="/blog" class="item">小學堂文章列表</a>
                        <a href="/vlog?q=share" class="item">小學堂影音</a>
                        @foreach ($latestArticles as $latestArticle)
                        <a class="item" href="/articlepage?q=knowledge-{{ $latestArticle->ID }}">
                            {{ $latestArticle->post_title }}
                        </a>
                        @endforeach
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
                <a href="/charitable" class="item">慈善公益</a>
                <div class="item">
                    <button v-if="!flag || flag === 'logout'" @click="openLoginModal" class="login-btn btn">註冊/登入<i
                            class="ml-2 fa fa-arrow-right"></i></button>
                    <div v-if="Object.keys(userData).length !== 0" class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">您好
                            @{{ userData.name }}</a>
                        <ul class="dropdown-menu" style="min-width: 5rem;">
                            <li v-if="isInvestor == 0">
                                <router-link class="dropdown-item loan-link" to="/loannotification">借款人</router-link>
                            </li>
                            <li v-else>
                                <router-link class="dropdown-item invest-link"
                                    to="/investnotification">投資人</router-link>
                            </li>
                            <li v-if="flag === 'login'">
                                <p class="dropdown-item" @click="logout">登出</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-wrapper">
            <section class="banner">
            </section>
            @if ($type == 'knowledge')
            <div class="knowledge-wrapper container">
                <div class="row">
                    <div class="col-12 col-lg-9 mb-3">
                        <article class="article">
                            <h1 class="title post_title">{{ $article->post_title }}</h1>
                            <div class="info">
                                <span class="date">{{ $article->post_date }}</span>
                            </div>
                            <img class="cover" src="{{ asset($article->media_link) }}" alt="<?php
                                    if (empty($article->media_alt)) {
                                        preg_match('/\/([^\/]+)$/', $article->media_link, $matches);
                                        $filename = explode('.', $matches[1]);
                                        echo $filename[0];
                                    } else {
                                        echo $article->media_alt;
                                    } ?>" />
                            <div class="content">
                                {!! $article->post_content !!}
                            </div>
                        </article>
                        <section class="advertise" style="display: flex; justify-content: center;">
                            <img class="cover adv_img" src=" /{{ $adv->img_url }}" style="max-width: 100%;" />
                        </section>
                        <section class="adv_sec" style="margin: 30px 0;">
                            @if ($adv->type == 'student')
                            <button class="adv_btn" id="stduent_loan_btn">
                                立即了解更多
                            </button>
                            @elseif ($adv->type == 'office')
                            <button class="adv_btn" id="office_loan_btn">
                                立即了解更多
                            </button>
                            @elseif ($adv->type == 'enterprise')
                            <button class="adv_btn" id="enterprise_loan_btn">
                                立即了解更多
                            </button>
                            @elseif ($adv->type == 'invest')
                            <button class="adv_btn" id="invest_btn">
                                立即了解更多
                            </button>
                            @endif
                        </section>
                        <div class="row share">
                            <div class="col">
                                <span class="title">分享：</span>
                                <button class="btn btn_link link" @click="addToFB">
                                    <img :src="'/images/facebook.svg'" class="img-fluid" />
                                </button>
                                <button class="btn btn_link link" @click="addToLINE">
                                    <img :src="'/images/line.png'" class="img-fluid" />
                                </button>
                                <button class="btn btn_link link" @click="copyLink">
                                    <img :src="'/images/link_grey.svg'" class="img-fluid" />
                                </button>
                                <span v-if="copied">網址複製成功 !</span>
                            </div>
                        </div>
                        <div class="row mt-4 no-gutters">
                            <button @click="returnToBlog" class="btn login-btn"><i
                                    class="mr-2 fa fa-arrow-left"></i>返回列表</button>
                        </div>
                    </div>
                    <div class="col-lg-3 ">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row mb-3">
                                    <div class="col">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col latest-article">
                                        <h3 class="section-title">熱門文章</h3>
                                        @foreach ($latestArticles as $latestArticle)
                                        <div class="list-group list-group-flush">
                                            @if (!empty($latestArticle->path))
                                            <a class="list-group-item list-group-item-action"
                                                href="/articlepage/{{ $latestArticle->path }}">
                                                @else
                                                <a class="list-group-item list-group-item-action"
                                                    href="/articlepage?q=knowledge-{{ $latestArticle->ID }}">
                                                    @endif
                                                    <h5 class="title"> {{ $latestArticle->post_title }} </h5>
                                                    <small class="date">
                                                        {{ $latestArticle->post_date }}
                                                    </small>
                                                </a>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @elseif ($type == 'news')
            <div class="article-wrapper">
                <div class='news-view'>
                    <h3 class="title">{{ $article->post_title }}</h3>
                    <div class="info">
                        <span class="date">發布日期：{{ $article->post_date }}</span>
                    </div>
                    <div class="contenier">
                        <div class="title-img">
                            <img src="{{ 'https://influx-website.s3.ap-northeast-1.amazonaws.com' . $article->image_url }}" class="img-fluid" />
                        </div>
                        <div class="main-content">
                            {!! $article->post_content !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="row no-gutters" style="background: #153a71;">
            <div class="alesis-footer">
                <div class="line"></div>
                <div class="main">
                    <div class="introduction">
                        <div class="brand">
                            <img class="image" src="/images/footer@2x.png">
                        </div>
                        <div class="paragraphy">
                            inFlux普匯金融科技，以普惠金融為志業，希望落實傳統銀行無法提供的金融服務。「金融專業」為核心，「高端科技」為輔具，提供「最有溫度」的社群服務，拉近人與人的距離，讓金融年輕化。
                        </div>
                        <div class="paragraphy">
                            「繳稅警語」<br>
                            普匯溫馨提醒所有用戶，在普匯的相關利息收入與支出，都應主動申報綜合所得稅，以善盡繳稅義務，避免遭稅捐機關開罰。
                        </div>
                    </div>
                    <div class="information">
                        <div class="item">
                            <div class="header">個人融資</div>
                            <div class="list">
                                <a href="/collegeLoan">學生貸款</a>
                                <a href="/engineerLoan">資訊工程師專案</a>
                                <a href="/workLoan">上班族貸款</a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="header">企業融資</div>
                            <div class="list">
                                {{-- <a href="/business-loan/smeg" class="text-white">中小企業融資(信保)</a> --}}
                                <div class="text-white">中小企業融資(信保)</div>
                                <div class="text-white">企業主速貸</div>
                                <div class="text-white">中小企業信貸</div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="header">汽車貸款</div>
                            <div class="list">
                                <div class="text-white">我是消費者</div>
                                <div class="text-white">我是車商</div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="header">慈善公益</div>
                            <div class="list">
                                <a href="/charitable" class="text-white">台大兒醫</a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="header">投資專區</div>
                            <div class="list">
                                <a href="/investment">債權投資</a>
                                <a href="/transfer">債權轉讓</a>
                                <a href="/risk" class="text-white">風險報告書</a>
                                <a href="/projects" class="text-white">案件呈現</a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="header">關於普匯</div>
                            <div class="list">
                                <a href="/company" class="text-white">關於我們</a>
                                <a href="/news" class="text-white">最新消息</a>
                                <a href="/blog" class="text-white">普匯小學堂</a>
                                <a href="/vlog" class="text-white">小學堂影音</a>
                            </div>
                        </div>
                        <div class="item">
                            <div class="header">合作洽談</div>
                            <div class="list">
                                <a href="/recruiting" class="text-white">徵才</a>
                                <a href="/promote-code-intro" class="text-white">分享QR，賺外快</a>
                                <a href="/clubcooperation" class="text-white">社團贊助</a>
                                <a href="/companycooperation" class="text-white">業務合作</a>
                                <div class="text-white">我們的夥伴</div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="header">聯絡我們</div>
                            <div class="meta">
                                <div class="icon"><img src="/images/alesis-clock.svg"></div>
                                <div class="text">服務時間 9:00 AM - 6:00 PM</div>

                                <div class="icon"><img src="/images/alesis-phone.svg"></div>
                                <div class="text">
                                    <a href="tel:+886225079990" target="_blank" class="mr-2">02-2507-9990</a>
                                    <a href="tel:+886225082897" target="_blank">02-2508-2897</a>
                                </div>
                                <div class="icon"><img src="/images/alesis-email.svg"></div>
                                <div class="text"><a href="mailto:service@influxfin.com"
                                        target="_blank">service@influxfin.com</a></div>

                                <div class="icon"><img src="/images/alesis-address.svg"></div>
                                <div class="text"><a href="https://goo.gl/maps/5J27gauTT5Fw87PD8"
                                        target="_blank">台北市中山區松江路111號11樓之1</a></div>
                            </div>
                            <div class="socials">
                                <a href="https://m.facebook.com/inFluxtw/" target="_blank" class="item"><img
                                        src="/images/alesis-facebook.svg"></a>
                                <a href="https://line.me/R/ti/p/%40kvd1654s" target="_blank" class="item"><img
                                        src="/images/alesis-line.svg"></a>
                                <a href="https://www.instagram.com/pop.finance/" target="_blank" class="item"><img
                                        src="/images/alesis-instagram.svg"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="line"></div>
                <div class="secondary">
                    <div class="copyright">
                        Copyright ©2020 普匯金融科技股份有限公司 All rights reserved.
                        <div class="links">
                            <a href="/userTerms" class="item">使用者條款</a> |
                            <a href="/privacyTerms" class="item">隱私權條款</a> |
                            <a href="/loanerTerms" class="item">借款人服務條款</a> |
                            <a href="/lenderTerms" class="item">貸款人服務條款</a> |
                            <a href="/transferTerms" class="item">債權受讓人服務條款</a> |
                            <a href="/overdueDebtTerms" class="item">逾期債權處理條款</a>
                        </div>
                    </div>
                    <div class="links">
                        <a href="/recruiting" class="item">徵才服務</a> |
                        <a href="/clubcooperation" class="item">社團合作</a> |
                        <a href="/firmcooperation" class="item">商行合作</a> |
                        <a href="/companycooperation" class="item">企業合作</a> |
                        <a href="/promote-code-intro" class="item">推薦有賞</a>
                    </div>
                    <div class="externals" v-if="isBussinessPage">
                        <a class="item" href="/borrowLink">我想申貸</a>
                        <a class="item -invest" href="/investLink">我想投資</a>
                    </div>
                    <div class="externals" v-else>
                        <a class="item" href="/business-loan/sme/apply">立即申辦</a>
                        <a class="item -invest" href="/business-loan/sme/consult">我要諮詢</a>
                    </div>
                </div>
            </div>
        </div>
        <!--<a class="back-top" @click="backtotop"><img src="{{ asset('images/top.svg') }}" class="img-fluid" /></a>-->
        <div id="loginForm" class="modal fade" ref="loginForm" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
            aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div v-if="isReset" id="forgetPwdModal" class="modal-content">
                    <div class="modal-header">
                        <div type="button" class="back" @click="switchForm">＜</div>
                        <div class="reset-title">重設密碼</div>
                    </div>
                    <div class="modal-body">
                        <div class="input-group">
                            <span class="input-group-addon label-text">手機：</span>
                            <input type="text" class="form-control label-input" placeholder="請輸入綁定之手機號碼" v-model="phone"
                                maxlength="10">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text">新密碼：</span>
                            <input type="password" class="form-control label-input" placeholder="請輸入新密碼"
                                v-model="newPassword" maxlength="50">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text">再次輸入新密碼：</span>
                            <input type="password" class="form-control label-input" placeholder="請再次輸入新密碼"
                                v-model="confirmPassword" maxlength="50">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text">驗證碼：</span>
                            <div class="captcha-row">
                                <input type="text" class="form-control label-input" placeholder="請輸入6位數驗證碼"
                                    v-model="code" maxlength="6">
                                <button class="btn btn-captcha" @click="getCaptcha('smsloginphone')"
                                    v-if="!isSended">取得驗證碼</button>
                                <div class="btn btn-disable" v-if="isSended">@{{ counter }}S有效</div>
                                <span class="tip" v-if="isSended">驗證碼已寄出</span>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-danger" v-if="pwdMessage">@{{ pwdMessage }}</div>
                    <div class="modal-footer">
                        <div v-if="(phone && newPassword && confirmPassword && code) ? false : true"
                            class="btn btn-disable">送出</div>
                        <button type="button" v-else class="btn btn-submit" @click="submit">送出</button>
                    </div>
                </div>
                <div v-if="!isReset" id="loginModal" class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"
                            @click="clearInterval(timer);">✕</button>
                        <div class="login-logo"><img src="/images/logo_puhey.svg" class="img-fluid"></div>
                    </div>
                    <div class="modal-body">
                        <div class="input-group" v-if="!loginHideOption">
                            <button type="button" :class="['btn', 'btn-switch', { checked: !isCompany }]"
                                @click="switchTag($event)">個人登入</button>
                            <button type="button" :class="['btn', 'btn-switch', { checked: isCompany }]"
                                @click="switchTag($event)">企業登入</button>
                        </div>
                        <div class="input-group" v-if="isCompany">
                            <span class="input-group-addon label-text">公司統編：</span>
                            <input type="text" class="form-control label-input" placeholder="請輸入統一編號" autocomplete="off"
                                v-model="businessNum" maxlength="8">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text">帳號：</span>
                            <input type="text" class="form-control label-input" placeholder="10位數手機號碼"
                                autocomplete="off" v-model="account" maxlength="10">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon label-text">密碼：</span>
                            <input type="password" class="form-control label-input" placeholder="請輸入密碼"
                                autocomplete="off" v-model="password" maxlength="50">
                        </div>
                        <div class="input-group" v-if="!loginHideOption">
                            <span class="input-group-addon label-text"></span>
                            <div class="radio-custom">
                                <label><input type="radio" name="investor" class="radio-inline" value="0"
                                        v-model="investor"><span class="outside"><span
                                            class="inside"></span></span>借款人</label>
                                <label><input type="radio" name="investor" class="radio-inline" value="1"
                                        v-model="investor" checked><span class="outside"><span
                                            class="inside"></span></span>投資人</label>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="chiller_cb">
                                <input id="rememberAccount" type="checkbox" @click="setAccount"
                                    :checked="isRememberAccount">
                                <label for="rememberAccount">記住帳號</label>
                                <span></span>
                            </div>
                            <button type="button" :class="['btn', 'btn-password']" @click="switchForm">忘記密碼?</button>
                        </div>
                        <div class="input-group">
                        </div>
                    </div>
                    <div class="alert alert-danger" v-if="message">@{{ message }}</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-submit" @click="doLogin">登入</button>
                        <a v-if="!isCompany" class="btn btn-register" href="/register">會員註冊</a>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script type="text/javascript" src="{{ asset('js/package/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/package/slick.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/package/axios.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/package/vue-cookies.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/package/aos.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/package/pagination.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/package/echarts.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/package/ecStat.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/package/dataTool.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/package/particles.min.js') }}"></script>

<!-- local -->
<script src="/js/manifest.js"></script>
<script src="/js/vendor.js"></script>
<script type="text/javascript" src="{{ mix('js/articlepage.js') }}"></script>

<script>
    $(document).ready(function() {
        $("#stduent_loan_btn").on("click", function() {
            location.href = '/collegeLoan'
        })
        $('#office_loan_btn').on('click', function() {
            location.href = '/workLoan'
        })
        $('#enterprise_loan_btn').on('click', function() {
            // location.href = '/business-loan/smeg'
        })
        $('#invest_btn').on('click', function() {
            location.href = '/investment'
        })
    });
</script>

</script>
<style>
    .banner {
        background-size: cover;
        background-position: center;
    }

    .adv_sec {
        display: flex;
        justify-content: center;
    }

    .adv_btn {
        background: #F29500;
        border-radius: 12px;
        border-color: #F29500;
        height: 50px;
        width: 700px;
        color: white;
        font-weight: 600;
        font-size: 26px;
    }

    @media only screen and (max-width: 768px) {
        .banner {
            background-position: center;
        }

        .post_title {
            font-weight: 600 !important;
            font-size: 20px !important;
            line-height: 1.2;
            letter-spacing: 0.04em;
        }

        .knowledge-wrapper .article .info .date {
            font-weight: 350;
            font-size: 16px;
            line-height: 23px;
            letter-spacing: 0.04em;
        }

        .knowledge-wrapper .article .content p {
            font-weight: 350;
            font-size: 14px;
        }

        .adv_img {
            width: 100%;
        }

        .adv_btn {
            background: #F29500;
            border-radius: 12px;
            border-color: #F29500;
            height: 35px;
            width: 700px;
            color: white;
            font-weight: 500;
            font-size: 16px;
            line-height: 23px;
        }
    }
</style>

</html>
