<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<table style="width:100%;height:100%">
    <tr>
        <td colspan="6" style="text-align:center;background-color:#00468C;color:#eee;">
            <h2>普匯金融科技 - 推薦有賞對帳單</h2>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <h3></h3>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <table>
                <tr>
                    <td>日期： {send_time}</td>
                </tr>
                <tr>
                    <td>親愛的 {company_name} 您好:</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="height:100%;">
        <td colspan="2" style="border:2px #7fb4d5 solid;">
            <table>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td>·會員編號：{company_user_id}</td>
                </tr>
                <tr>
                    <td>·公司名稱：{company_name}</td>
                </tr>
                <tr>
                    <td>·負責人：{responsible_name}</td>
                </tr>
                <tr>
                    <td>·統一編號：{tax}</td>
                </tr>
                <tr>
                    <td>·電話：{phone}</td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            </table>
        </td>
        <td colspan="2">
            <table>
                <tr>
                </tr>
            </table>
        </td>
        <td colspan="2" style="border:2px #7fb4d5 solid;">
            <table>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td>受款銀行帳戶資訊</td>
                </tr>
                <tr>
                    <td> ·銀行：{bank_name}</td>
                </tr>
                <tr>
                    <td> ·戶名：{bank_account_name}</td>
                </tr>
                <tr>
                    <td> ·帳號：{virtual_account}</td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="6">
        </td>
    </tr>
    <tr>
        <td colspan="6">
        </td>
    </tr>
    <tr>
        <td colspan="6">本期獎金：{start_time}~{end_time}</td>
    </tr>
    <tr>
        <td colspan="6">實領推薦獎金：新台幣 {reward_amount} 元整</td>
    </tr>
    <tr>
        <td colspan="6">
            <table style="width:100%;">
                <tr>
                    <th style="text-align:center;border: 1px solid #000000;">日期</th>
                    <th style="text-align:center;border: 1px solid #000000;" colspan="4">摘要</th>
                    <th style="text-align:center;border: 1px solid #000000;">獎金</th>
                </tr>
                {list}
                <tr>
                    <td style="border: 1px solid #000000;">{date}</td>
                    <td style="border: 1px solid #000000;" colspan="4">{summary}</td>
                    <td style="border: 1px solid #000000;">{amount}</td>
                </tr>
                {/list}
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="6">備註：上述受款銀行帳號資訊為受款人於普匯之代收代付帳戶。</td>
    </tr>
    <tr>
        <td colspan="6">
        </td>
    </tr>
    <tr>
        <td colspan="6" style="text-align:center;background-color:#00468C;color:#eee;">
            <h3>普匯金融科技 / 溫馨提示</h3>
        </td>
    </tr>
    <tr>
        <td colspan="6">
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align:center;">
            <img src="<?= FRONT_CDN_URL ?>public/logo.png" style="width:300px">
        </td>
        <td colspan="4" style="text-align:left;">
            <p>請妥善保管密碼等個人訊息，並確保在官方途徑使用。
                普匯無論何時都不會向您以任何形式詢問或確認您的個人資料。
                包括金融卡帳戶號碼，提領密碼，帳戶餘額，身分證字號等。
                感謝您對普匯的支持，我們將竭誠為您服務。
                更多訊息請登入我的 普匯inFlux 進行查詢</p>
            <p>Line@客服：Line@influxfin 歡迎登錄</p>
            <p>普匯網站了解更多最新消息：<a href="https://www.influxfin.com/">https://www.influxfin.com/</a></p>

        </td>
    </tr>
</table>
</body>
</html>