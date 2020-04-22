import qaComponent from './component/qaComponent.vue';

export default {
    template:`
        <div class="transfer-wrapper">
            <div class="transfer-header">
                <div class="header-title">
                    <img src="./image/child_banner.jpg">
                    <div class="title">債權轉讓</div>
                </div>
                <div class="header-img">
                    <img src="./image/transfer_banner_web.jpg" class="img-fluid desktop">
                    <img src="./image/transfer_banner_mobile.jpg" class="img-fluid mobile">
                </div>
                <div class="header-footer">
                    <p>滾石不生苔 , 隨時靈活轉換您的資金</p>
                    <h2>如何使用債權轉讓?</h2>
                </div>
            </div>
            <div class="transfer-content" data-aos="zoom-in">
                <img src="./image/transfer_web.png" class="img-fluid desktop">
                <div class="transfer-slick mobile" ref="transfer_slick">
                    <div v-for="imgSrc in this.transferFlow" class="slick-item">
                        <img :src="imgSrc" class="img-fluid">
                    </div>
                </div>
            </div>
            <qa :data="this.getQaData()"></qa>
            <div class="transfer-footer">
                <h2>投資理財大補帖</h2>
                <div class="info-slick" ref="info_slick" data-aos="flip-down">
                    <div v-for="(item,index) in this.articles" class="content-row" :key="index">
                        <img :src="item.imgSrc">
                        <p>【普匯觀點】</p>
                        <p>{{item.title}}</p>
                        <br>
                        <a :href="item.link" class="btn btn-danger">觀看大補帖</a>
                    </div>
                </div>
            </div>
        </div>
    `,
    components:{
        'qa':qaComponent
    },
    data:()=>({
        transferFlow:['./image/transfer_flow1.png','./image/transfer_flow2.png','./image/transfer_flow3.png','./image/transfer_flow4.png','./image/transfer_flow5.png','./image/transfer_flow6.png'],
        articles:[
            {
                'title':'年輕人也要投資理財–提早享受退休人生',
                'link':'#',
                'imgSrc':'./image/thumbnail.jpg'
            },{
                'title':'年輕人也要投資理財–提早享受退休人生',
                'link':'#',
                'imgSrc':'./image/thumbnail.jpg'
            },{
                'title':'年輕人也要投資理財–提早享受退休人生',
                'link':'#',
                'imgSrc':'./image/thumbnail.jpg'
            },{
                'title':'年輕人也要投資理財–提早享受退休人生',
                'link':'#',
                'imgSrc':'./image/thumbnail.jpg'
            },{
                'title':'年輕人也要投資理財–提早享受退休人生',
                'link':'#',
                'imgSrc':'./image/thumbnail.jpg'
            },{
                'title':'年輕人也要投資理財–提早享受退休人生',
                'link':'#',
                'imgSrc':'./image/thumbnail.jpg'
            },
        ]
    }),
    created(){
        console.log('transfer');
    },
    mounted(){
        this.createSlick();
        AOS.init();
    },
    methods:{
        createSlick(){
            $(this.$refs.transfer_slick).slick({
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

            $(this.$refs.info_slick).slick({
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                // autoplay: true,
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
        getQaData:()=>(
            [
                {
                    title:'什麼是債權轉讓？',
                    content:'當您需將手上債權變現時，可使用債權轉讓功能，將手上債權拋轉上架 供其他投資人認購，提早回流資金，自由運用。',
                    imgSrc:[]
                },{
                    title:'債權已經逾期，還能夠轉讓嗎？',
                    content:'可以的，無論是正常還款中，或是逾期的債權，都可以做轉讓喔。可選擇折價轉讓，快速資金回流。',
                    imgSrc:[]
                },{
                    title:'債權轉讓可以轉讓給指定的投資人嗎？如何同時轉讓多筆債權？',
                    content:'可以的，您可以選擇批次轉讓之打包功能，並設定密碼，提供予特定投資人進行轉讓。',
                    imgSrc:[]
                }
            ]
        ),
    }
}