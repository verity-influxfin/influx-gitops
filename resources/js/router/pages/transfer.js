import qaComponent from './component/qaComponent';

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
            <qa :data="this.qaData" title="常見問題"></qa>
            <div class="transfer-footer">
                <h2>投資理財大補帖</h2>
                <div class="investTonic-slick" ref="investTonic_slick" data-aos="flip-left">
                    <div v-for="(item,index) in this.investTonic" class="content-row" :key="index">
                        <img :src="item.imgSrc">
                        <p>【普匯觀點】</p>
                        <p>{{item.title}}</p>
                        <br>
                        <router-link :to="item.link" class="btn btn-danger">觀看大補帖</ㄐ>
                    </div>
                </div>
            </div>
        </div>
    `,
    components:{
        'qa':qaComponent
    },
    data:()=>({
        qaData:[],
        transferFlow:['./image/transfer_flow1.png','./image/transfer_flow2.png','./image/transfer_flow3.png','./image/transfer_flow4.png','./image/transfer_flow5.png','./image/transfer_flow6.png'],
        investTonic:[]
    }),
    created(){
        this.getQaData();
        this.getInvestTonicData();
        console.log('transfer');
    },
    mounted(){
        this.createSlick();
        AOS.init();
    },
    watch:{
        investTonic(){
            this.$nextTick(()=>{
                $(this.$refs.investTonic_slick).slick('refresh');
                $(this.$refs.investTonic_slick).slick('slickSetOption', 'slidesToShow', 3);
            });
        }
    },
    methods:{
        getInvestTonicData(){
            const $this = this;
            $.ajax({
                url:'getInvestTonicData',
                type:'POST',
                dataType:'json',
                success(data){
                    data.forEach((item,key)=>{
                        data[key].link = `/articlepage/investtonic${item.id}`;
                    });
                    $this.investTonic = data;
                }
            });
        },
        getQaData(){
            const $this = this;
            $.ajax({
                url:'getQaData',
                type:'POST',
                data:{
                    filter:'freshgraduate'
                },
                dataType:'json',
                success(data){
                    $this.qaData = data;
                }
            });
        },
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

            $(this.$refs.investTonic_slick).slick({
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
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
}