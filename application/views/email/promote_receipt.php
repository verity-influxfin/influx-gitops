<html>
<head>
    <title>Email</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style type="text/css">
        .main{
            border: 1px solid #000;
            margin: 10px;
        }
        .row{
            display: flex;
        }
        .space{
            height: 40px;
        }
        .title{
            font-size: 20px;
            line-height: 2;
            text-align: center;
            font-weight: bold;
        }
        .key{
            text-align: center;
            line-height: 2;
            color:#000080;
        }
        .value{
            text-align: center;
            line-height: 2;
            color: #000;
        }
        .text-left{
            text-align: left;
            padding-left: 15px;
        }
        .border{
            border: 1px solid #000;
        }
        .col-o1{
            width: 20%;
        }
        .col-1{
            width: 16.666667%;
        }
        .col-2{
            width: 33.333333%;
        }
        .col-3{
            width: 50%;
        }
        .col-4{
            width: 66.666667%;
        }
        .col-5{
            width: 83.333333%;
        }
        .col-6{
            width: 100%;
        }
    </style>
</head>
<body style="margin:0; padding:0;" bgcolor="#eaeced">
<table style="min-width:320px;" width="100%" cellspacing="0" cellpadding="0" bgcolor="#eaeced">
    <!-- fix for gmail -->
    <tr>
        <td class="hide">
            <table width="600" cellpadding="0" cellspacing="0" style="width:600px !important;">
                <tr>
                    <td style="min-width:600px; font-size:0; line-height:0;">www.inFluxfin.com</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="wrapper" style="padding:0 10px;">

            <!-- module 2 -->
            <div class="main">
                <div class="row border">
                    <div class="title" style="width: 100%;">普匯金融科技股份有限公司－勞務報酬單</div>
                </div>
                <div class="row space border"></div>
                <div class="row">
                    <div class="name key col-o1 border">姓 名</div>
                    <div class="name value col-1 border"><?= $name ?></div>
                    <div class="id-num key col-1 border">身分證字號</div>
                    <div class="id-num value col-1 border"><?= $id_number ?></div>
                    <div class="tel-num key col-1 border">電話</div>
                    <div class="tel-num value border" style="width: 13%;"><?= $phone ?></div>
                </div>
                <div class="row">
                    <div class="address key col-o1 border">戶籍地址</div>
                    <div class="address value border" style="width: 80%;"><?= $address ?></div>
                </div>
                <div class="row">
                    <div class="reason key col-o1 border">請款事由</div>
                    <div class="reason value border" style="width: 80%;">業務獎金</div>
                </div>
                <div class="row">
                    <div class="bank-info key border" style="width: 100%;">受款銀行帳戶資訊</div>
                </div>
                <div class="row">
                    <div class="date key col-o1 border">日期</div>
                    <div class="bank key col-1 border">銀行</div>
                    <div class="bank-holder key col-1 border">戶名</div>
                    <div class="bank-account key border" style="width: 46.6666%;">帳號</div>
                </div>
                <div class="row">
                    <div class="date key col-o1 border"><?= $time ?></div>
                    <div class="bank value col-1 border">國泰世華銀行</div>
                    <div class="bank-holder value col-1 border"><?= $name ?></div>
                    <div class="bank-account value border" style="width: 46.6666%;"><?= $bank_account ?></div>
                </div>
                <div class="row">
                    <div class="money col-o1 border"><span class="text-left key">請款金額</span></div>
                    <div class="money value col-1 border"><?= number_format($amount) ?></div>
                    <div class="tex key col-1 border">扣繳10％所得稅</div>
                    <div class="tex value col-1 border">NT$ <?=$income_tax == 0 ? "-" : number_format($income_tax) ?></div>
                    <div class="insure key col-1 border">扣繳2.11％健保費</div>
                    <div class="insure value border" style="width: 13%;">NT$ <?=$health_premium == 0 ? "-" : number_format($health_premium) ?></div>
                </div>
                <div class="row">
                    <div class="actual  col-o1 border">
                        <span class="text-left key">實領金額</span>
                    </div>
                    <div class="actual value border" style="width: 80%;">新台幣 <?=number_format($net_amount)?> 元整</div>
                </div>
                <div class="border">
                    <div class="row">
                        <div class="key text-left col-6">
                            備註：1、其他費用：依各類所得扣繳率標準第11條規定，超過二萬元須扣繳 10％ 所得稅。
                        </div>
                    </div>
                    <div class="row">
                        <div class="key col-6 text-left">
                            2、兼職所得、薪資：依健保法第31條規定，超過最低薪資須扣繳 2.11％ 健保費。
                        </div>
                    </div>
                    <div class="row ">
                        <div class="key col-6 text-left">
                            3、上述受款銀行帳號資訊為受款人於普匯之代收代付帳戶。
                        </div>
                    </div>
                </div>

            </div>

            <!-- module 7 -->
            <table data-module="module-7" data-thumb="thumbnails/07.png" width="100%" cellpadding="0" cellspacing="0" style="margin: 10px">
                <tr>
                    <td data-bgcolor="bg-module" bgcolor="#eaeced">
                        <table class="flexible" width="800" cellpadding="0"
                               cellspacing="0">
                            <tr>
                                <td class="footer" style="padding:0 0 10px;">
                                    <table width="100%" cellpadding="0" cellspacing="0">
                                        <tr class="table-holder">
                                            <th class="tfoot" width="600" align="left"
                                                style="vertical-align:top; padding:0;">
                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td data-color="text" data-link-color="link text color"
                                                            data-link-style="text-decoration:underline; color:#797c82;"
                                                            class="aligncenter"
                                                            style="font:12px/16px Arial, Helvetica, sans-serif; color:#797c82; padding:0 0 10px;">
                                                            此信件為系統自動發送，請勿直接回覆<br>
                                                            Copyright © <?= date('Y') ?> 普匯金融科技股份有限公司
                                                        </td>
                                                    </tr>
                                                </table>
                                            </th>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <!-- fix for gmail -->
    <tr>
        <td style="line-height:0;">
            <div style="display:none; white-space:nowrap; font:15px/1px courier;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            </div>
        </td>
    </tr>
</table>
</body>
</html>
