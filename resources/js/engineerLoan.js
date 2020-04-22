import videoShareComponent from './component/videoShareComponent.vue';
import bannerComponent from './component/bannerComponent.vue';
import joinComponent from './component/joinComponent.vue';
import applyDescribeComponent from './component/applyDescribeComponent.vue';
import qaComponent from './component/qaComponent.vue';

export default {
    template:`
        <div class="engineer-wrapper">
            <banner :data="this.getBannerData()"  :isShowLoan="true"></banner>
            <div class="engineer-slick" ref="engineer_slick" data-aos="zoom-in">
                <div v-for="item in dossales" class="slick-item">
                    <img :src="item.imageSrc" class="img-fluid">
                </div>
            </div>
            <applyDescribe :data="this.getApplydata()" ref="apply"></applyDescribe>
            <join href="./image/child_banner.jpg" :isShowLoan="true"></join>
            <qa :data="this.getQaData()" title="常見問題"></qa>
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
        dossales:[
            {"imageSrc":"./image/dossal1.png"},
            {"imageSrc":"./image/engineer_slick2.png"},
            {"imageSrc":"./image/dossal3.png"},
            {"imageSrc":"./image/dossal4.png"}
        ]
    }),
    created(){
        console.log('engineerLoan');
    },
    mounted(){
        this.createSlick();
        $(this.$refs.apply.$refs.apply_slick).attr('data-aos','fade-up');
        AOS.init();
    },
    methods:{
        getBannerData:()=>({
            title:"資訊工程師專案",
            bgHref:"./image/child_banner.jpg",
            bannerHref:"./image/engineer_loan_banner.png",
            info:['if(“普匯理財 ”= =true)','“缺錢 ”=false','編譯你的程式，也編譯你的財務狀況'],
            description:"免出門 不需排隊 全線上申請<br>3分鐘上傳資料 30分鐘AI審核<br>年化利率最低5%起 最高額度20萬",
            btnText:"立即借款"
        }),
        getApplydata:()=>({
            title:"誰可以申請資訊工程師專案呢？",
            subTitle:"年滿20歲相關系所大學以上在校生,或相關職業工程師",
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
                    text:"學歷證明",
                    memo:""
                },{
                    imgSrc:"./image/license.png",
                    text:"工程師專業證照",
                    memo:""
                },{
                    imgSrc:"./image/extra.png",
                    text:"上班族需另提供",
                    memo:"1.聯徵報告<br>2.工作證明"
                },
            ]
        }),
        getQaData:()=>(
            [
                {
                    title:'工程師優惠專案的申請資格？',
                    content:'學生 : 年滿20歲資訊軟體相關系所的大學以上在校生。<br>上班族：年滿20以上大學以上，需資訊軟體相關系所畢業上班族，並從事資訊科技類相關的從業人員。',
                    imgSrc:['./image/engineer_qa1.png']
                },{
                    title:'工程師們有甚麼好康？',
                    content:'普匯針對擁有專業技能的Techi們提供更優惠的專案，借款額度高達20萬、利率減免優惠5%起，且超快速半小時審核過件。',
                    imgSrc:[]
                },{
                    title:'工程師優惠申請金額有上限嗎？',
                    content:'有的，學生申請上限為12萬，上班族為20萬。',
                    imgSrc:['./image/engineer_qa3-1.png','./image/engineer_qa3-2.png']
                },{
                    title:'借款手續費怎麼算？',
                    content:'平台僅收取本金的3%，最低為500元，作為平台服務費。<br>Ex：若您申請20000元，服務費即為600元。',
                    imgSrc:[]
                },
            ]
        ),
        createSlick(){
            $(this.$refs.engineer_slick).slick({
                infinite: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                dots:true,
                dotsClass:'slick-custom-dots',
                customPaging(slider, i) {
                    return '<i class="fas fa-circle"></i>';
                },
                prevArrow:'<i class="fas fa-chevron-left arrow-left"></i>',
                nextArrow:'<i class="fas fa-chevron-right arrow-right"></i>',
                responsive: [
                    {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                    }
                ]
            });
        }
    }

};