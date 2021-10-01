@php
function image_url(string $filename) {
    return '/images/campaigns/2021-campus-ambassador/' . $filename;
}
@endphp

@extends('campaigns.layout', [
    'page' => [
        'title'         => '普匯校園大使 - inFlux普匯金融科技',
        'description'   => '普匯校園大使首屆擴大招募，不只法商文組，連資訊理工科系學生也搶破頭想進入的Fintech產業，歡迎你來大展身手！從教育訓練、CEO面對面提案，一直到執行專案、成果檢視，不限科系，只要你充滿熱情、勇於挑戰，即獲取報名門票。',
        'cover_image'   => url('/images/campaigns/2021-campus-ambassador/banner.jpg'),
        'site_url'      => url()->current(),
        'meta_keywords' => [
            '2021普匯校園大使',
            '金融科技'
        ],
        'GTM' => env('APP_ENV') == 'production' ? 'GTM-5Z439PW' : 'GTM-589Z9H6'
    ],
    'event' => [
        'name'        => '2021普匯校園大使徵選開跑',
        'description' => '成為普匯校園大使，完成屬於自己大學生涯的校園專案，不限科系，只要你充滿熱情、勇於挑戰，即獲取報名門票！',
        'start_date'  => '2021-09-01',
        'end_date'    => '2021-09-28',
        'signup_url'  => url('/campaign/2021-campus-ambassador/signup'),
    ]
])

@section('content')
<div class="container-fluid">
    <div class="modal fade" id="event_closed" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h2 class="mb-2">殘念...</h2>
                    <p class="mb-2">報名截止了 (扶額</p>
                    <a href="https://zh.wikipedia.org/wiki/%E5%BA%93%E4%BC%AF%E5%8B%92-%E7%BD%97%E4%B8%9D%E6%A8%A1%E5%9E%8B" target="_blank" style="text-decoration:none;font-size:.8em">😱😡🥺😭🙂</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">好喔</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <section class="hero">
            <div class="col">
                <div class="top">
                    <img class="left" src="{{image_url('hero_sign_top_left.svg')}}" alt="徵選開跑">
                    <img class="right" src="{{image_url('hero_sign_top_right.svg')}}" alt="報名時間: 即日起至 2021/09/28">
                </div>
                <div class="middle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="31">
                      <g transform="translate(-76.5 -159.5)">
                        <line x2="100%" transform="translate(76.5 160.5)" stroke="#153a71" stroke-width="2"/>
                        <line x2="100%" transform="translate(76.5 190.5)" stroke="#153a71" stroke-width="0.5"/>
                      </g>
                    </svg>

                    <div class="title">
                        <img class="d-none d-lg-inline" src="{{image_url('hero_middle_title.svg')}}" alt="2021普匯校園大使">
                        <img class="d-inline d-lg-none" src="{{image_url('hero_middle_title_m.svg')}}" alt="2021普匯校園大使">
                    </div>

                    <div class="hashtags">
                        <img class="d-none d-lg-inline" src="{{image_url('hero_middle_hashtags.svg')}}" alt="首屆擴大招募, 任務升級全新挑戰, AI金融科技協會助力">
                        <img class="d-inline d-lg-none" src="{{image_url('hero_middle_hashtags_m.svg')}}" alt="首屆擴大招募, 任務升級全新挑戰, AI金融科技協會助力">
                    </div>

                    <div class="phrase">
                        <img src="{{image_url('hero_middle_phrase.svg')}}" alt="下個校園菁英代言人就是你！">
                    </div>

                    <div class="signup">
                        <a href="javascript:;" class="button" data-bs-toggle="modal" data-bs-target="#event_closed">
                            <img src="{{image_url('hero_middle_button_text.svg')}}" alt="點我報名" />
                        </a>

                        <svg class="lines" xmlns="http://www.w3.org/2000/svg" width="100%" height="31">
                          <g transform="translate(-76.5 -159.5)">
                            <line x2="100%" transform="translate(76.5 160.5)" stroke="#153a71" stroke-width="0.5" stroke-dasharray="70%" stroke-dashoffset="35%"/>
                            <line x2="100%" transform="translate(76.5 190.5)" stroke="#153a71" stroke-width="4" stroke-dasharray="50%" stroke-dashoffset="5%"/>
                          </g>
                        </svg>
                    </div>
                </div>
                <div class="bottom">
                    <p>
                        成為普匯校園大使，完成屬於自己大學生涯的校園專案<br/>不限科系，只要你充滿熱情、勇於挑戰，即獲取報名門票！
                    </p>
                </div>
            </div>
        </section>
    </div>
    <div class="row">
        <section class="intro narrowed bg-grey">
            <h3 class="title">投資自己就趁現在</h3>
            <hr/>
            <p>
                在求學時期多累積經驗就是對自己投資<br/>
                但如果想在累積自己的過程中，更加認識投資理財<br/>
                那麼活潑創新又不失專業的普匯校園大使肯定會是你的首選啦！
            </p>
            <hr class="light"/>
            <p>
                不只法商文組，連資訊理工科系學生也搶破頭想進入的FinTech產業，歡迎你來大展身手<br/>
                從教育訓練、CEO面對面提案，一直到實際執行專案、成果檢視<br/>
                加入普匯校園大使，保證給你最接地氣的職場體驗
            </p>
            <img class="bg-image-object" src="{{image_url('bubbles.svg')}}">
        </section>
    </div>
    <div class="row">
        <section class="benefit narrowed">
            <h2 class="heading bg-blue">大使好康福利</h2>
            <div class="row my-3">
                <div class="col-xl-5 mb-4">
                    <div class="d-flex flex-row align-items-center">
                        <img class="icon" src="{{image_url('benefit_icon_1.svg')}}" alt="專業100培訓計畫">
                        <h4 class="title">專業100培訓計畫</h4>
                    </div>
                </div>
                <div class="col-xl-7 mb-3">
                    <p class="description">公司參訪與定期進度回報、提案回饋，並安排專業培訓課程，包含提案技巧與行銷企劃，以及學校不會教的FinTech產業新知，讓你充分接觸金融科技大趨勢。</p>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-xl-5 mb-4">
                    <div class="d-flex flex-row align-items-center">
                        <img class="icon" src="{{image_url('benefit_icon_2.svg')}}" alt="職涯加分加到爆">
                        <h4 class="title">職涯加分加到爆</h4>
                    </div>
                </div>
                <div class="col-xl-7 mb-3">
                    <p class="description">任期間累積的優秀人脈包你想到都會笑！大使期滿還會頒發外面買不到的「普匯校園大使」結業證書。期間表現優秀的同學還能優先獲取履歷健檢以及實習生與正職的面試機會哦～</p>
                </div>
            </div>
            <div class="row my-3">
                <div class="col-xl-5 mb-4">
                    <div class="d-flex flex-row align-items-center">
                        <img class="icon" src="{{image_url('benefit_icon_3.svg')}}" alt="該給的一定不會少">
                        <h4 class="title">該給的一定不會少</h4>
                    </div>
                </div>
                <div class="col-xl-7 mb-3">
                    <p class="description">給予AI金融科技協會優惠待遇及資源，並提供專案報酬，凡是有用生命在完成大使任務者，加碼表現績優獎金。還有定期舉辦的大使聚餐增進感情哦，來這裡絕對不會淪為免費勞工啦！</p>
                </div>
            </div>
        </section>
    </div>
    <div class="row">
        <section class="mission narrowed bg-blue text-white">
            <h2 class="heading bg-yellow">大使任務</h2>
            <div class="row">
                <div class="col-lg-6 column">
                    <h3 class="title">品牌與學生的專屬橋梁</h3>
                    <hr/>
                    <div class="list">
                        <div class="wrapper">
                            <p class="item">進行校園情報蒐集與分析</p>
                            <p class="item">普匯品牌線上線下推廣</p>
                            <p class="item">協助品牌活動宣傳執行</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 column">
                    <h3 class="title">校園專案規劃與執行</h3>
                    <hr/>
                    <div class="list">
                        <div class="wrapper">
                            <p class="item">合作進行校園專案發想與提案</p>
                            <p class="item">不限渠道不限形式，創意揮灑大空間</p>
                            <p class="item">執行進度回報，檢視自我成長</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <p class="remark">※獲選為普匯校園大使，完成指定任務以及校園專案執行，將可獲得額外的獎勵與報酬</p>
                </div>
            </div>
        </section>
    </div>
    <div class="row">
        <section class="how-to narrowed bg-grey">
            <h2 class="heading bg-white">徵選辦法</h2>
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">徵選時程</h3>
                            <ul>
                                <li>報名期間：2021/09/07-2021/09/28</li>
                                <li>徵選期間：2021/09/07-2021/10/15</li>
                                <li>大使任期：2021/10/22-2022/06/20</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">徵選資格</h3>
                            <ul>
                                <li>大二(含)以上至碩士在學生，不限校系科系</li>
                                <li>可單人或團體報名</li>
                                <li>團體一組至多三人，可跨系不可跨校</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body" style="min-height: calc(24em + 3px)">
                            <div class="signup">
                                <img class="bg-image-object" src="{{image_url('grey_arrow_right.svg')}}" />
                                <a href="javascript:;" class="button" data-bs-toggle="modal" data-bs-target="#event_closed">
                                    點我報名
                                </a>
                            </div>
                            <h3 class="card-title">報名辦法</h3>
                            <ol>
                                <li>進入報名頁面</li>
                                <li>填寫團隊名稱、團隊介紹及成員基本資料</li>
                                <li>一人以上報名可按「新增隊員」增加人數</li>
                                <li>評分標準：履歷內容(含加分項)80%，基本資料填寫完整度20%</li>
                                <li>上傳規定：上傳檔案皆須為PDF檔，履歷以兩頁為限，作品集大小不得超過5M，若加分項目資料較多建議統整為雲端連結，將連結貼在其他加分項表格</li>
                                <li>通過書審將於9/30通知進入大使神秘任務及第二階段面試</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="row">
        <section class="bonus narrowed bg-people">
            <h2 class="heading bg-blue">加分條件</h2>
            <div class="col">
                <ul class="list-group">
                    <li class="list-group-item">喜歡與人相處樂於分享者</li>
                    <li class="list-group-item">具號召力，有舉辦活動或社團幹部經驗者</li>
                    <li class="list-group-item">有經營自媒體經驗者</li>
                    <li class="list-group-item">有製作影音內容經驗者</li>
                    <li class="list-group-item">其他可以證明非你不可的加分項目</li>
                </ul>
            </div>
        </section>
    </div>
    <div class="row">
        <section class="workflow narrowed bg-grey">
            <h2 class="heading bg-blue">徵選流程</h2>
            <div class="row">
                <div class="col text-center">
                    <div class="flows">
                        <div class="step">
                            <div class="heading">
                                <h4 class="title">報名截止</h4>
                            </div>
                            <div class="description">
                                09/28（二）
                            </div>
                        </div>
                        <div class="step">
                            <div class="heading">
                                <h4 class="title">第一階入圍<br/>名單公布</h4>
                            </div>
                            <div class="description">
                                09/30（四）
                            </div>
                        </div>
                        <div class="step">
                            <div class="heading">
                                <h4 class="title">發布大使<br/>神秘任務</h4>
                            </div>
                            <div class="description">
                                10/01（五）
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-none d-sm-block">
                <div class="col text-center">
                    <div class="flows">
                        <div class="step">
                            <div class="heading">
                                <h4 class="title">神秘任務<br/>執行時間</h4>
                            </div>
                            <div class="description">
                                10/01（五）-<br/>10/05（二）
                            </div>
                        </div>
                        <div class="step">
                            <div class="heading">
                                <h4 class="title">第二階面試<br/>名單公布</h4>
                            </div>
                            <div class="description">
                                10/08（五）
                            </div>
                        </div>
                        <div class="step">
                            <div class="heading">
                                <h4 class="title">面試週</h4>
                            </div>
                            <div class="description">
                                10/11（一）-<br/>10/15（五）
                            </div>
                        </div>
                        <div class="step">
                            <div class="heading">
                                <h4 class="title">公布正式<br/>錄取名單</h4>
                            </div>
                            <div class="description">
                                10/22（五）
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-block d-sm-none reverse">
                <div class="col text-center">
                    <div class="flows">
                        <div class="step">
                            <div class="heading">
                                <h4 class="title">神秘任務<br/>執行時間</h4>
                            </div>
                            <div class="description">
                                10/01（五）-<br/>10/05（二）
                            </div>
                        </div>
                        <div class="step">
                            <div class="heading">
                                <h4 class="title">第二階面試<br/>名單公布</h4>
                            </div>
                            <div class="description">
                                10/08（五）
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col text-center">
                    <div class="flows">
                        <div class="step">
                            <div class="heading">
                                <h4 class="title">面試週</h4>
                            </div>
                            <div class="description">
                                10/11（一）-<br/>10/15（五）
                            </div>
                        </div>
                        <div class="step">
                            <div class="heading">
                                <h4 class="title">公布正式<br/>錄取名單</h4>
                            </div>
                            <div class="description">
                                10/22（五）
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <ul class="remark">
                        <li>主辦單位保留變更、修改本專案內容之權利</li>
                        <li>主辦單位有權就本專案作出解釋，任何人均不得異議</li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
    <div class="row">
        @include('campaigns.2021_campus_ambassador.footer')
    </div>
</div>
@endsection

@section('styles')
<style type="text/css">
    body {
        font-family: "NotoSansTC", "Microsoft JhengHei";
        font-size: 24px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.5;
        letter-spacing: 2.4px;
        color: #404040;
    }
    @media (max-width: 991.98px) {
        body {
            font-size: 18px;
        }
    }
    .bg-grey {
        background-image:url("{{image_url('grey_bg.jpg')}}");
        background-size: cover;
    }
    .bg-people {
        background-image:url("{{image_url('young_people_using_phone_bg.jpg')}}");
        background-size: cover;
        background-position: center bottom;
    }
    .bg-blue {
        background-color: #153a71;
        color: white;
    }
    .bg-yellow {
        background-color: #fdb409;
        color: white;
    }
    .bg-white {
        background-color: white;
        color: #153a71;
    }
    .bg-image-object {
        position: absolute;
        pointer-events: none;
        z-index: 0;
    }
    .container-fluid section {
        min-height: 200px;
    }
    section.narrowed {
        padding: 110px 12vw 180px;
        position: relative;
    }
    @media (max-width: 991.98px) {
        section.narrowed {
            padding: 110px 6vw 180px;
        }
    }
    section > .heading {
        min-width: 18vw;
        box-shadow: 3px 6px 6px 0 rgb(0 0 0 / 36%);
        font-size: 2em;
        font-weight: bold;
        display: inline-block;
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
        padding: 7px 40px;
        border-radius: 0.25em;
        white-space: nowrap;
    }
    section h2,
    section h3,
    section p,
    section hr {
        z-index: 10;
    }
    .hero {
        background-image: url("{{image_url('hero_bg.jpg')}}");
        background-size: cover;
        background-position: center bottom;
        min-height: calc(90vh - 20px);
        padding-top: 2vw;
    }
    .hero .top {
        display: inline-block;
        width: 100%;
        padding: .5em;
    }
    .hero .top img.left {
        height: 3em;
        float: left;
    }
    .hero .top img.right {
        height: 2em;
        float: right;
        margin-top: .5em;
        max-width: 100%;
    }
    .hero .middle {
        text-align: center;
    }
    .hero .middle .title img {
        width: 75vw;
        max-height: 14rem;
    }
    .hero .middle .hashtags img {
        width: 75vw;
        max-height: 4.5rem;
    }
    .hero .middle .phrase img {
        height: 4.6vw;
        max-height: 3.5rem;
        margin: 20px auto;
    }
    .hero .middle .signup {
        margin: 20px auto;
    }
    .hero .middle .signup svg.lines {
        position: relative;
        bottom: 70px;
        z-index: 1;
    }
    @media (max-width: 991.98px) {
        .hero .top {
            padding: 0;
        }
        .hero .top img.left {
            margin-top: 1em;
        }
        .hero .top img.right {
            float: left;
            clear: both;
        }
        .hero .middle .title img {
            width: 100%;
            max-height: none;
        }
        .hero .middle .hashtags img {
            width: 100%;
            max-height: none;
        }
        .hero .middle .phrase img {
            transform: scale(2.1);
            margin: 50px auto;
        }
        .hero .middle .signup {
            margin-bottom: 90px;
        }
        .hero .middle .signup svg.lines {
            bottom: -40px;
        }
        .hero .middle .signup a.button {
            transform: scale(0.9);
        }
    }
    .hero .middle .signup a.button {
        display: flex;
        width: 28vw;
        max-width: 280px;
        background-color: #fdb409;
        box-shadow: 0 3px 5px rgb(0 0 0 / 36%);
        border-radius: 2em;
        transition: box-shadow ease .3s;
        min-width: 14em;
        max-height: 5rem;
        justify-content: center;
        justify-items: center;
        vertical-align: middle;
        margin: 0 auto;
        position: relative;
        z-index: 10;
    }
    .hero .middle .signup a.button:hover {
        box-shadow: 3px 6px 6px rgba(0, 0, 0, 0.36);
    }
    .hero .middle .signup a.button img {
        transform: scale(1.15);
        width: 100%;
    }
    .hero .bottom {
        text-align: center;
        margin: 40px 0 80px;
    }
    .intro .title {
        font-size: 1.6em;
        color: #153a71;
        font-weight: bold;
    }
    .intro .title,
    .intro p {
        position: relative;
    }
    .intro hr {
        width: 10rem;
        height: 4px;
        background-color: #153a71;
        opacity: 1;
        margin: 2rem 0;
    }
    .intro hr.light {
        width: 6rem;
        height: 2px;
    }
    .intro img.bg-image-object {
        top: 130px;
        right: 1em;
        height: 7em;
    }
    .benefit > .row {

    }
    .benefit .title {
        color: #153a71;
        font-weight: bold;
        padding-left: 0.8em;
    }
    @media (min-width: 1400px) {
        .benefit .title {
            font-size: 1.2em;
        }
    }
    .benefit .description {
        color: #404040;
    }
    .mission .title {
        text-align: center;
        font-weight: bold;
        white-space: nowrap;
    }
    .mission hr {
        width: 8rem;
        height: 2px;
        opacity: 1;
        margin: 2rem auto;
    }
    .mission .column {
        margin-bottom: 8rem;
    }
    .mission .list {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .mission .list .wrapper {
        display: grid;
    }
    .mission .list .item {
        font-weight: normal;
        display: inline-block;
        margin-bottom: 1em;
        font-size: 0.9em;
        text-indent: -1.9em;
        margin-left: 1.9em;
    }
    .mission .list .item:before {
        content: "";
        background-image: url('{{image_url('yellow_circle_check.svg')}}');
        background-repeat: no-repeat;
        background-position: center;
        display: inline-block;
        width: 1.4em;
        height: 1.4em;
        background-size: cover;
        margin-bottom: -.3em;
        margin-right: .5em;
    }
    .mission .remark {
        font-weight: normal;
        font-size: 0.95em;
    }
    @media (max-width: 991.98px) {
        .mission .column {
            padding: 0;
            margin-bottom: 6rem;
        }
        .mission .title {
            font-size: 1.5em;
        }
        .mission .list .item {
            font-size: 1.2em;
        }
        .mission .remark {
            max-width: 30rem;
            margin: 0 auto;
        }
    }
    @media (max-width: 767.98px) {
        .mission .list-item {
            margin-left: 0;
        }
    }
    .how-to .card {
        border: 2px solid #707070;
        background-color: transparent;
        margin: 30px auto 50px;
        border-radius: 20px;
    }
    .how-to .card .card-title {
        font-weight: bold;
        font-size: 1.2em;
        color: #153a71;
        margin: 0.8em auto 1em 1em;
    }
    .how-to .card ul li {
        list-style-type: none;
        margin-left: -1em;
    }
    .how-to .card ul li:before {
        content: "\f272";
        font-family: bootstrap-icons !important;
        line-height: 1.5em;
        display: inline-block;
        width: 1em;
        height: 1.5em;
        margin-left: -.4em;
        margin-right: .3em;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    .how-to .card li {
        font-size: 1em;
        font-weight: normal;
        color: #707070;
        margin-bottom: 10px;
    }
    .how-to .card .signup {
        position: absolute;
        left: 9em;
        top: 1.5em;
        width: 10em;
    }
    .how-to .card .signup .bg-image-object {
        position: relative;
        top: 15px;
        right: 0.5em;
        width: 2.5em;
    }
    .how-to .card .signup a.button {
        position: relative;
        background-color: #fdb409;
        box-shadow: 0 3px 5px rgb(0 0 0 / 36%);
        border-radius: 9px;
        color: white;
        display: inline-block;
        padding: 8px 0.5em;
        text-decoration: none;
        font-size: 1.05em;
    }
    .bonus .list-group .list-group-item {
        margin: 1rem auto 2rem;
        min-width: 22em;
        text-align: center;
        padding: 1rem;
        border-radius: 15px;
        border: none;
        box-shadow: 3px 6px 6px rgb(0 0 0 / 16%);
    }
    @media (max-width:  1199.98px) {
        .bonus .list-group .list-group-item {
            min-width: unset;
            width: 100%;
        }
    }
    .bonus .list-group .list-group-item:nth-child(2n) {
        background-color: #e3e3e3;
        color: #153a71;
    }
    .bonus .list-group .list-group-item:nth-child(2n+1) {
        background-color: #2a68a1;
        color: white;
    }
    .workflow .flows {
        margin: 1em auto 2em;
        display: inline-block;
    }
    .workflow .step {
        float: left;
    }
    .workflow .step > .heading {
        width: 11em;
        height: 3.2em;
        margin: 0 -1.5em;
        background-size: auto 100%;
        background-position: center;
        background-repeat: no-repeat;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .workflow .step:nth-child(2n+1) > .heading {
        background-image: url('{{image_url('step_arrow_right_blue.svg')}}');
        color: white;
    }
    .workflow .step:nth-child(2n) > .heading {
        background-image: url('{{image_url('step_arrow_right_grey.svg')}}');
        color: #2a68a1;
    }
    .workflow .reverse .step:nth-child(2n) > .heading {
        background-image: url('{{image_url('step_arrow_right_blue.svg')}}');
        color: white;
    }
    .workflow .reverse .step:nth-child(2n+1) > .heading {
        background-image: url('{{image_url('step_arrow_right_grey.svg')}}');
        color: #2a68a1;
    }
    .workflow .step > .heading .title {
        font-weight: bold;
        white-space: nowrap;
        margin-bottom: 0;
    }
    .workflow .step > .description {
        color: #404040;
        text-align: center;
        margin: 2rem auto;
        font-size: 0.9em;
    }
    .workflow .remark {
        font-size: 0.95em;
        line-height: 2rem;
        color: #404040;
        max-width: 30em;
        text-align: left;
        margin: 20px auto;
    }
    @media (max-width:  767.98px) {
        .workflow .flows:nth-child(1) {
            transform: scale(0.94);
        }
        .workflow .flows {
            margin: 1em -45px 1em;
        }
        .workflow .step {
            transform: scale(0.87);
        }
        .workflow .step > .heading {
            width: 10em;
        }
        .workflow .step > .heading .title {
            font-size: 1.1em;
        }
    }
</style>
@endsection