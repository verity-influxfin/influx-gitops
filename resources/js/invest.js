import bannerComponent from './component/bannerComponent.vue';
import joinComponent from './component/joinComponent.vue';
import qaComponent from './component/qaComponent.vue';
import videoShareComponent from './component/videoShareComponent.vue';

export default {
    template:`
        <div class="invest-wrapper">
            <banner :data="this.getBannerData()" :isShowInvest="true"></banner>
            <div class="compare-wrapper" data-aos="zoom-in">
                <img src="./image/invest_web.png" class="img-fluid desktop">
                <div class="invest-slick mobile" ref="invest_slick">
                    <div v-for="imgSrc in this.investCategory" class="slick-item">
                        <img :src="imgSrc" class="img-fluid">
                    </div>
                </div>
            </div>
            <join href="./image/child_banner.jpg" :isShowInvest="true"  subTitle="加入普匯完成你的財富目標吧！"></join>
            <qa :data="this.getQaData()" title="常見問題"></qa>
            <videoShare ref="videoShare" title="聽聽投資人怎麼說" :data="this.getVideoData()"></videoShare>
        </div>
    `,
    components:{
        'videoShare':videoShareComponent,
        'banner':bannerComponent,
        'join':joinComponent,
        'qa':qaComponent
    },
    data:()=>({
        investCategory:['./image/invest_puhey.png','./image/invest_fund.png','./image/invest_stock.png']
    }),
    created(){
        console.log('invest');
    },
    mounted(){
        this.createSlick();
        $(this.$refs.videoShare.$refs.share_content).attr('data-aos','fade-left');
        AOS.init();
    },
    methods:{
        createSlick(){
            $(this.$refs.invest_slick).slick({
                infinite: true,
                slidesToShow: 3,
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
        },
        getBannerData:()=>({
            title:"債權投資",
            bgHref:"./image/child_banner.jpg",
            bannerHref:"./image/engineer_loan_banner.png",
            info:['小額分散','穩定報酬首選','1,000元開始投資','創造你的被動收入'],
            description:"年化報酬率5~20% 穩定獲利低風險",
            btnText:"立即投資"
        }),
        getQaData:()=>(
            [
                {
                    title:'投資標的逾期該怎麼辦？',
                    content:'若您的債權逾期了，普匯平台會啟動催收機制，協助解決催理困擾；建議同步嘗試使用債權/折價轉讓功能，將原有債權轉讓出售。                    ',
                    imgSrc:['./image/invest_qa1-1.png','./image/invest_qa1-2.png']
                },{
                    title:'債權投資的利率是公開的嗎？',
                    content:'普匯債權投資的利率完全公開透明，於投資時即可觀看每項產品的利率,還款期數,金額與收益等資訊,再依個人需求,自由選擇您要的標的。',
                    imgSrc:[]
                },{
                    title:'我所投資的對象是誰？',
                    content:'平台的借款人都是經由AI風險控管的案件，對象為年滿20歲以上的公民，包括： 學生、上班族 、企業主。您可以於投標前選擇您想投資的對象。',
                    imgSrc:['./image/invest_qa3-1.png','./image/invest_qa3-2.png']
                }
            ]
        ),
        getVideoData:()=>(
            [
                {
                    'title':"【投資人專訪】",
                    'subTitle':'台北 投資理財顧問 葉先生',
                    'detail':'普匯跟其他保本型的商品比起來，投報率真的相對較高，也比較不需要花太多時間去關注，就可以獲得被動收入。',
                    'videoLink':'https://www.youtube.com/embed/6DbX2r2cR-o',
                    'href':'#'
                },{
                    'title':"【投資人專訪】",
                    'subTitle':'台北 投資理財顧問 葉先生',
                    'detail':'普匯跟其他保本型的商品比起來，投報率真的相對較高，也比較不需要花太多時間去關注，就可以獲得被動收入。',
                    'videoLink':'https://www.youtube.com/embed/6DbX2r2cR-o',
                    'href':'#'
                },{
                    'title':"【投資人專訪】",
                    'subTitle':'台北 投資理財顧問 葉先生',
                    'detail':'普匯跟其他保本型的商品比起來，投報率真的相對較高，也比較不需要花太多時間去關注，就可以獲得被動收入。',
                    'videoLink':'https://www.youtube.com/embed/6DbX2r2cR-o',
                    'href':'#'
                },{
                    'title':"【投資人專訪】",
                    'subTitle':'台北 投資理財顧問 葉先生',
                    'detail':'普匯跟其他保本型的商品比起來，投報率真的相對較高，也比較不需要花太多時間去關注，就可以獲得被動收入。',
                    'videoLink':'https://www.youtube.com/embed/6DbX2r2cR-o',
                    'href':'#'
                },
            ]
        )
    }
}