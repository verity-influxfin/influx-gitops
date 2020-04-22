import videoShareComponent from './component/videoShareComponent.vue';
import experienceComponent from './component/experienceComponent.vue';
import bannerComponent from './component/bannerComponent.vue';
import joinComponent from './component/joinComponent.vue';
import applyDescribeComponent from './component/applyDescribeComponent.vue';
import qaComponent from './component/qaComponent.vue';

export default {
    template:`
        <div>
            <banner :data="this.getBannerData()"></banner>
            <experience ref="experience" title="聽聽大家怎麼說"></experience>
            <applyDescribe :data="this.getApplydata()" ref="apply"></applyDescribe>
            <join href="./image/child_banner.jpg" :isShowLoan="true"></join>
            <qa :data="this.getQaData()"></qa>
            <videoShare ref="videoShare" title="Follow普匯小學堂<br>增進科普金融知識" :data="this.videoData"></videoShare>
        </div>
    `,
    components:{
        'videoShare':videoShareComponent,
        'experience':experienceComponent,
        'banner':bannerComponent,
        'join':joinComponent,
        'applyDescribe':applyDescribeComponent,
        'qa':qaComponent
    },
    data:()=>({
        videoData:[
            {
                'title':"【普匯小學堂】",
                'subTitle':'普匯公司介紹',
                'detail':'這回就讓我們帶您一起來了解普匯到底在做什麼吧!!',
                'videoLink':'https://www.youtube.com/embed/sTqyd5mkjdI',
                'href':'#'
            },{
                'title':"【普匯小學堂】",
                'subTitle':'普匯公司介紹',
                'detail':'這回就讓我們帶您一起來了解普匯到底在做什麼吧!!',
                'videoLink':'https://www.youtube.com/embed/sTqyd5mkjdI',
                'href':'#'
            },{
                'title':"【普匯小學堂】",
                'subTitle':'普匯公司介紹',
                'detail':'這回就讓我們帶您一起來了解普匯到底在做什麼吧!!',
                'videoLink':'https://www.youtube.com/embed/sTqyd5mkjdI',
                'href':'#'
            },{
                'title':"【普匯小學堂】",
                'subTitle':'普匯公司介紹',
                'detail':'這回就讓我們帶您一起來了解普匯到底在做什麼吧!!',
                'videoLink':'https://www.youtube.com/embed/sTqyd5mkjdI',
                'href':'#'
            },
        ],
    }),
    created(){
        console.log('college');
    },
    mounted(){
        $(this.$refs.videoShare.$refs.share_content).attr('data-aos','fade-left');
        $(this.$refs.experience.$refs.experience_slick).attr('data-aos','zoom-in');
        $(this.$refs.apply.$refs.apply_slick).attr('data-aos','fade-up');
        AOS.init();
    },
    methods:{
        getBannerData:()=>({
            title:"學生貸款",
            bgHref:"./image/child_banner.jpg",
            bannerHref:"./image/college_loan_banner.png",
            info:['Dcard 熱搜','大學生最愛','線上借貸平台'],
            description:"投資人運用普匯平台24小時不間斷支援同學們生活急需與達成夢想",
            btnText:"立即借款"
        }),
        getApplydata:()=>({
            title:"誰可以申請普匯學生貸呢??",
            subTitle:"年滿20歲,大學以上在校生",
            requiredDocuments:[
                {
                    imgSrc:"./image/identity.png",
                    text:"身分證件",
                    memo:""
                },{
                    imgSrc:"./image/account.png",
                    text:"金融帳號",
                    memo:""
                },{
                    imgSrc:"./image/social_media.png",
                    text:"常用社群",
                    memo:""
                },{
                    imgSrc:"./image/education.png",
                    text:"學生身分",
                    memo:""
                },
            ]
        }),
        getQaData:()=>(
            [
                {
                    title:'誰可以使用學生貸？',
                    content:'年滿20歲之大學在學生。',
                    imgSrc:['./image/college_qa1.png']
                },{
                    title:'持證自拍不清晰？',
                    content:'1. 持證自拍時請留意證件與人臉都需清晰可辨識。<br>2. 證件不可擋到臉部，手也不可擋到證件。<br>3. 請盡量於背景單純明亮的拍攝，若有配戴眼鏡建議將眼鏡拿下。',
                    imgSrc:['./image/college_qa2.png']
                },{
                    title:'SIP是什麼？',
                    content:'SIP為登入學校線上系統使用的資訊。<br>如您的校方信箱無法順利收信，SIP資訊能協助您快速完成學生認證，請填寫正確資訊。',
                    imgSrc:['./image/college_qa3-1.png','./image/college_qa3-2.png']
                },{
                    title:'學生證遺失怎麼辦？',
                    content:'可使用校方開立並有校方戳章的在學證明代替。',
                    imgSrc:['./image/college_qa4.png']
                },{
                    title:'非中華民國國民或境外學校可以申請嗎？',
                    content:'目前尚未開放非中華民國國民及境外學校學生申請。',
                    imgSrc:[]
                },
            ]
        )
    }

};