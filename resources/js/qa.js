import qaComponent from './component/qaComponent.vue';

export default {
    template:`
        <div class="qaPage-wrapper" id="qaPage">
            <div class="qaPage-header">
                <div class="header-title">
                    <img src="./image/child_banner.jpg">
                    <div class="title">常見問題</div>
                    <div class="qa-btn-row">
                        <a href="#loan">借款常見問題</a>
                        <a href="#invest">投資常見問題</a>
                        <a href="#afterLoanData">貸後常見問題</a>
                    </div>
                </div>
            </div>
            <div class="qaPage-content">
                <div id="loan"><qa :data="this.getQaData('loanData')" category="loanData" title="借款常見問題" :hideLink="true"></qa></div>
                <div id="invest"><qa :data="this.getQaData('investData')" category="invest" title="投資常見問題" :hideLink="true"></qa></div>
                <div id="afterLoanData"><qa :data="this.getQaData('afterLoanData')" category="afterLoanData" title="貸後常見問題" :hideLink="true"></qa></div>
            </div>
            <a class="back-top" href="#qaPage"><i class="fas fa-arrow-left"></i></a>
        </div>
    `,
    components:{
        'qa':qaComponent
    },
    data:()=>({
        qaData:{
            'loanData':[{
                'title':'我該如何申請？',
                'content':'請從「普匯inFlux」App中的首頁申請所需要的產品。'
            },{
                'title':'多久才會獲得借款？',
                'content':'最快核貸未一小時，通常1～3天，實際時間需視投資人的認購意願。'
            },{
                'title':'信用評級是什麼？',
                'content':'這是普匯平台設定的風險管理指標。<br><br>依照借款人提供的訊息、資料以及外部第三方公司提供的訊息，對借款人進行信用評估，會影響到您的借款利率和信用額度，建議提供完整的資料，將有助於提升信用評級。'
            },{
                'title':'我提供的資料安全嗎？',
                'content':'普匯嚴格遵守「個人資料保護法」，未經同意及授權，絕不提供他人使用。資料均存於全球最大的亞馬遜AWS雲端伺服器。'
            }],
            'investData':[{
                'title':'我該如何申請？',
                'content':'請從「普匯inFlux」App中的首頁申請所需要的產品。'
            },{
                'title':'我該如何投資？',
                'content':'請在「最新標的」中選擇標的後，即可設定下標金額，最低一千元即可投資，再匯入虛擬帳號即可。'
            },{
                'title':'信用評級是什麼？',
                'content':'這是普匯平台設定的風險管理指標。依照借款人提供的訊息、資料以及外部第三方公司提供的訊息，對借款人進行的信用評估。'
            },{
                'title':'借款人逾期還款該怎麼辦？',
                'content':'平台會協助執行相關催理工作（包含電子催收、存證信函寄發、支付命令申請寄發等），以保障您的債權。'
            },],
            'afterLoanData':[{
                'title':'寬限期是什麼？',
                'content':'每個月10日為約定之還款日，超過即屬逾期，但平台投資人為借款人考量諸多可能因素，而釋放七天寬限期給借款人，即17日(含)以前還款，尚可被接受，但日後於平台上之信用分數將受影響；若超過18日(含)，即需全額清償或執行產品轉換，且信評調降，還款利率調高，影響往後金融機構信用，提醒謹慎維護己身信用。'
            },{
                'title':'未於寬限期內繳款，會怎樣？',
                'content':'續上述，逾期依規定即需「全額清償」，其中包括尚欠本金、利息、違約金、延滯息及其他相關費用，不僅信用評等受嚴重影響，無法再新貸，更會面臨法律責任，影響未來金融及其他信用評價。'
            },{
                'title':'借款逾期未清償，將面臨哪些法律責任？',
                'content':'因未履行借貸約定，投資人依約可進行相關催理作業，包括不限於電催、存證信函、以及支付命令通知，最終進行查封查扣薪資收入、財產等等法律動作。'
            },{
                'title':'產品轉換是什麼？',
                'content':'給予借款人申請「展延或協商」清償債務之功能；協助債務人陳述擬定還款計劃，以提高投資人信心，甚至獲取新的投資人支持，可避免遭受法律求償，造成困擾。'
            },{
                'title':'如果還款遇到困難要怎麼辦？',
                'content':'若因個人因素導致無法如期還款，儘速電洽本平台，若不便電聯，請務必加入LINE真人客服(點選右下LINE客服按鈕>加入普匯小幫手>在功能選單中點選真人客服) 聯繫，讓平台協助您擬訂清償方案，避免直接遭受法律催收。'
            }]
        }
    }),
    created(){
        console.log('qa');
    },
    methods:{
        getQaData(filter){
            return this.qaData[filter];
        }
    }
}




