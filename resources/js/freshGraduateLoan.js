import videoShareComponent from './component/videoShareComponent.vue';
import bannerComponent from './component/bannerComponent.vue';
import joinComponent from './component/joinComponent.vue';
import applyDescribeComponent from './component/applyDescribeComponent.vue';
import qaComponent from './component/qaComponent.vue';

export default {
    template:`
        <div class="freshGraduate-wrapper">
            <banner :data="this.getBannerData()"  :isShowLoan="true"></banner>
            <div class="img-wrapper">
                <img src="./image/worker_web.jpg" class="img-fluid desktop">
                <img src="./image/worker_mobile.jpg" class="img-fluid mobile">
            </div>
            <applyDescribe :data="this.getApplydata()" ref="apply"></applyDescribe>
            <join href="./image/child_banner.jpg" :isShowLoan="true"></join>
            <qa :data="this.getQaData()" title="常見問題"></qa>
            <videoShare ref="videoShare" title="借款人怎麼說？" :data="this.videoData"></videoShare>
        </div>
    `,
    components:{
        'videoShare':videoShareComponent,
        'banner':bannerComponent,
        'join':joinComponent,
        'applyDescribe':applyDescribeComponent,
        'qa':qaComponent
    },
    data:()=>({
        videoData:[
            {
                'title':"【借款人專訪】",
                'subTitle':'公部門　陳先生',
                'detail':'整個貸款辦理過程都很快速，對於整個流程都很滿意。',
                'videoLink':'https://www.youtube.com/embed/VTJB7c1fS-4',
                'href':'#'
            },{
                'title':"【借款人專訪】",
                'subTitle':'公部門　陳先生',
                'detail':'整個貸款辦理過程都很快速，對於整個流程都很滿意。',
                'videoLink':'https://www.youtube.com/embed/VTJB7c1fS-4',
                'href':'#'
            },{
                'title':"【借款人專訪】",
                'subTitle':'公部門　陳先生',
                'detail':'整個貸款辦理過程都很快速，對於整個流程都很滿意。',
                'videoLink':'https://www.youtube.com/embed/VTJB7c1fS-4',
                'href':'#'
            },{
                'title':"【借款人專訪】",
                'subTitle':'公部門　陳先生',
                'detail':'整個貸款辦理過程都很快速，對於整個流程都很滿意。',
                'videoLink':'https://www.youtube.com/embed/VTJB7c1fS-4',
                'href':'#'
            },
        ],
    }),
    created(){
        console.log('workerLoan');
    },
    mounted(){
        $(this.$refs.apply.$refs.apply_slick).attr('data-aos','fade-up');
        $(this.$refs.videoShare.$refs.share_content).attr('data-aos','fade-left');
        AOS.init();
    },
    methods:{
        getBannerData:()=>({
            title:"上班族信貸",
            bgHref:"./image/child_banner.jpg",
            bannerHref:"./image/worker_loan_banner.png",
            info:['憑勞保','免聯徵','速送速審'],
            description:"最高額度20萬<br>最低年化利率5%起<br>自由選擇3-24期<br>最快一小時到帳",
            btnText:"試算可貸額度"
        }),
        getApplydata:()=>({
            title:"誰可以申請普匯上班族信貸呢？",
            subTitle:"年滿20歲，即可申貸",
            requiredDocuments:[
                {
                    imgSrc:"./image/identity.png",
                    text:"身分證件",
                    memo:""
                },{
                    imgSrc:"./image/social_media.png",
                    text:"常用社群",
                    memo:""
                },{
                    imgSrc:"./image/employment.png",
                    text:"工作證明",
                    memo:""
                },{
                    imgSrc:"./image/account.png",
                    text:"金融帳號",
                    memo:""
                },
            ]
        }),
        getQaData:()=>(
            [
                {
                    title:'聯徵資料如何取得完成驗證？',
                    content:'申請聯徵有下列三種方式：<br><br>1.使用自然人憑證線上申請最快<br>直接下載自己的聯徵報告<br>在APP聯徵驗證區上傳聯徵封面<br>完整PDF檔Mail至 普匯金融科技:credit@influxfin.com<br>即可快速完成驗證<br><br>2.聯徵中心現場申請紙本聯徵報告<br>可當場索取,再拍封面上傳APP聯徵驗證區<br>同時將完整紙本寄送到 普匯金融科技: 台北市松江路111號11樓-2<br><br>3.郵局臨櫃申請<br>現場收據拍照上傳APP聯徵驗證區<br>紙本約7個工作天,可請郵局直接寄送至 普匯金融科技: 台北市松江路111號11樓-2<br>普匯收到紙本後即可完成驗證',
                    imgSrc:['./image/worker_qa1.png']
                },{
                    title:'工作驗證需甚麼資料？',
                    content:'工作驗證必備資料如下<br>1. 近半年勞保異動明細<br>勞保異動明細可親洽勞保局申請，也可使用自然人憑證下載電子檔<br>2.近三個月薪資證明<br薪資證明可提供存摺封面與近三個月薪資轉入的存摺內頁<br>或是網銀包含個人姓名與帳號的畫面,以及近三個月薪資轉入的畫面<br>3.其他選填資料',
                    imgSrc:['./image/worker_qa2.png']
                },{
                    title:'畢業證書遺失怎麼驗證學歷？',
                    content:'1.拍攝畢業證書正本<br>2.拍攝在學期間成績單<br>3.提供在學期間SIP資訊',
                    imgSrc:[]
                },{
                    title:'上班族貸的申請條件是？',
                    content:'年滿20歲35歲以下，學士學位以上之畢業證明文件均可申請。',
                    imgSrc:['./image/worker_qa4.png']
                },{
                    title:'借款手續費怎麼算？',
                    content:'平台僅收取本金的3%，最低為500元，作為平台服務費。<br>Ex：若您申請20000元，服務費即為600元。',
                    imgSrc:[]
                },
            ]
        )
    }

};