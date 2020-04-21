import schoolComponent from './component/schoolComponent.vue';
import experienceComponent from './component/experienceComponent.vue';
import bannerComponent from './component/bannerComponent.vue';
import joinComponent from './component/joinComponent.vue';
import applyDescribeComponent from './component/applyDescribeComponent.vue';
import qaComponent from './component/qaComponent.vue';

export default {
    template:`
        <div>
            <banner :data="this.createBannerAttr()"></banner>
            <experience ref="experience" title="聽聽大家怎麼說"></experience>
            <applyDescribe :data="this.createApplyrAttr()" ref="apply"></applyDescribe>
            <join href="./image/child_banner.jpg" :isShowLoan="true"></join>
            <qa></qa>
            <school ref="school" title="Follow普匯小學堂\n\r增進科普金融知識"></school>
        </div>
    `,
    components:{
        'school':schoolComponent,
        'experience':experienceComponent,
        'banner':bannerComponent,
        'join':joinComponent,
        'applyDescribe':applyDescribeComponent,
        'qa':qaComponent
    },
    created(){
        console.log('college');
        console.log(this);
    },
    mounted(){
        $(this.$refs.school.$refs.share_content).attr('data-aos','fade-left');
        $(this.$refs.experience.$refs.experience_slick).attr('data-aos','zoom-in');
        $(this.$refs.apply.$refs.apply_slick).attr('data-aos','fade-up');
        AOS.init();
    },
    methods:{
        createBannerAttr:()=>({
            title:"學生貸款",
            bgHref:"./image/child_banner.jpg",
            bannerHref:"./image/college_loan_banner.png",
            info:['Dcard 熱搜','大學生最愛','線上借貸平台'],
            description:"投資人運用普匯平台24小時不間斷支援同學們生活急需與達成夢想",
            btnText:"立即借款"
        }),
        createApplyrAttr:()=>({
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
        })
    }

};