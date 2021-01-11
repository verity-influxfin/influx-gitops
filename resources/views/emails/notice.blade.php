<html>

<head>
    <style>
        body {
            background-color: #eaeced;
            font-weight: 600;
            font-family: "微軟正黑體";
            color: #888;
        }

        .mail-cnt {
            min-width: 320px;
            width: 600px;
            margin: 2rem auto;
            background: #ffffff;
            padding: 1rem;
        }

        .img {
            display: flex;
            justify-content: space-between;
            margin: 2rem 0px;
        }

        p {
            margin: 0px;
        }

        img {
            width: 100%;
            margin: 0px auto;
        }

        a {
            display: block;
            width: 40%;
        }
    </style>
</head>

<body>
    <div class="mail-cnt">
        <div class="img"><img style="width: 290px;" src="{{asset('/images/logo.png')}}" /></div>
        <p>
            {{ $params['name'] }}同學您好：<br><br><br>
            您已成功報名【普匯校園大使計畫】<br>
            我們將於 2/2 (二) 寄送初選通過者面試通知／未錄取感謝信，屆時請同學特別留意您的 email 信箱，若針對【普匯校園大使計畫】有其他疑問，請回覆本郵件或電洽普匯金融科技。<br><br><br>
            另外，同學可立即下載「普匯inFlux APP 」、「普匯投資」註冊成為會員，裡面有豐富的金融科技知識，讓你更了解普匯金融科技運作內容，幫助面試更加分喔!<br><br><br>
            *註: 採多人組隊報名者，所有隊員皆會收到本報名成功 email 通知<br><br><br><br>
            祝 順心
        </p>
        <div class="img">
            <a href="https://play.google.com/store/apps/details?id=com.influxfin.borrow&hl=zh_TW" target="_blank"><img src="/images/google.png" width="200" alt="https://play.google.com/store/apps/details?id=com.influxfin.borrow&hl=zh_TW" /></a>
            <a href="https://apps.apple.com/tw/app/%E6%99%AE%E5%8C%AFinflux/id1463581445" target="_blank"><img src="/images/apple.png" width="200" alt="https://apps.apple.com/tw/app/%E6%99%AE%E5%8C%AFinflux/id1463581445" /></a>
        </div>
        <p>
            -----------------------------<br>
            普匯校園大使計畫專案小組<br>
            藍翊嘉 | Irene Lan<br>
            service@influxfin.com<br>
            02-2507-9990<br>
        </p>
    </div>
</body>

</html>