import bannerComponent from './component/bannerComponent';
import joinComponent from './component/joinComponent';
import qaComponent from './component/qaComponent';
import videoShareComponent from './component/videoShareComponent';

export default {
    template: `
        <div class="invest-wrapper">
            <banner :data="this.bannerData" :isShowInvest="true"></banner>
            <div class="compare-wrapper" data-aos="zoom-in">
                <img src="./image/invest_web.png" class="img-fluid desktop">
                <div class="invest-slick mobile" ref="invest_slick">
                    <div v-for="imgSrc in this.investCategory" class="slick-item">
                        <img :src="imgSrc" class="img-fluid">
                    </div>
                </div>
            </div>
            <join href="./image/child_banner.jpg" :isShowInvest="true"  subTitle="加入普匯完成你的財富目標吧！"></join>
            <qa :data="this.qaData" title="常見問題"></qa>
            <videoShare ref="videoShare" title="聽聽投資人怎麼說" :data="this.shares"></videoShare>
        </div>
    `,
    components: {
        'videoShare': videoShareComponent,
        'banner': bannerComponent,
        'join': joinComponent,
        'qa': qaComponent
    },
    data: () => ({
        qaData: [],
        bannerData: {},
        investCategory: ['./image/invest_puhey.png', './image/invest_fund.png', './image/invest_stock.png']
    }),
    computed: {
        shares() {
            return this.$store.getters.SharesData;
        }
    },
    created() {
        this.$store.dispatch('getSharesData', { category: 'invest' });
        this.getQaData();
        this.getBannerData();
        $('title').text(`債權投資 - inFlux普匯金融科技`);
    },
    mounted() {
        this.createSlick();
        $(this.$refs.videoShare.$refs.share_content).attr('data-aos', 'fade-left');
        AOS.init();
    },
    methods: {
        createSlick() {
            $(this.$refs.invest_slick).slick({
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                autoplay: true,
                dots: true,
                dotsClass: 'slick-custom-dots',
                customPaging(slider, i) {
                    return '<i class="fas fa-circle"></i>';
                },
                prevArrow: '<i class="fas fa-chevron-left arrow-left"></i>',
                nextArrow: '<i class="fas fa-chevron-right arrow-right"></i>',
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
        getBannerData() {
            const $this = this;
            $.ajax({
                url: 'getBannerData',
                type: 'POST',
                dataType: 'json',
                data: {
                    filter: 'invest'
                },
                success(data) {
                    $this.bannerData = data;
                }
            });
        },
        getQaData() {
            const $this = this;
            $.ajax({
                url: 'getQaData',
                type: 'POST',
                data: {
                    filter: 'invest'
                },
                dataType: 'json',
                success(data) {
                    $this.qaData = data;
                }
            });
        }
    }
}