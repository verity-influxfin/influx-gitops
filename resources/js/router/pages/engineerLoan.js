import videoShareComponent from './component/videoShareComponent';
import bannerComponent from './component/bannerComponent';
import joinComponent from './component/joinComponent';
import applyDescribeComponent from './component/applyDescribeComponent';
import qaComponent from './component/qaComponent';

export default {
    template: `
        <div class="engineer-wrapper">
            <banner :data="this.bannerData"  :isShowLoan="true"></banner>
            <div class="engineer-slick" ref="engineer_slick" data-aos="zoom-in">
                <div v-for="item in dossales" class="slick-item">
                    <img :src="item.imageSrc" class="img-fluid">
                </div>
            </div>
            <applyDescribe :data="this.applyData" ref="apply"></applyDescribe>
            <join href="./image/child_banner.jpg" :isShowLoan="true"></join>
            <qa :data="this.qaData" title="常見問題"></qa>
        </div>
    `,
    components: {
        'videoShare': videoShareComponent,
        'banner': bannerComponent,
        'join': joinComponent,
        'applyDescribe': applyDescribeComponent,
        'qa': qaComponent
    },
    data: () => ({
        qaData: [],
        bannerData: {},
        applyData: {},
        dossales: [
            { "imageSrc": "./image/dossal1.png" },
            { "imageSrc": "./image/engineer_slick2.png" },
            { "imageSrc": "./image/dossal3.png" },
            { "imageSrc": "./image/dossal4.png" }
        ]
    }),
    created() {
        this.getApplydata();
        this.getBannerData();
        this.getQaData();
        $('title').text(`工程師專案 - inFlux普匯金融科技`);
    },
    mounted() {
        this.createSlick();
        $(this.$refs.apply.$refs.apply_slick).attr('data-aos', 'fade-up');
        AOS.init();
    },
    methods: {
        getBannerData() {
            const $this = this;
            $.ajax({
                url: 'getBannerData',
                type: 'POST',
                dataType: 'json',
                data: {
                    filter: 'engineer'
                },
                success(data) {
                    $this.bannerData = data;
                }
            });
        },
        getApplydata() {
            const $this = this;
            $.ajax({
                url: 'getApplydata',
                type: 'POST',
                dataType: 'json',
                data: {
                    filter: 'engineer'
                },
                success(data) {
                    $this.applyData = data;
                    $this.$nextTick(() => {
                        $this.$refs.apply.createSlick();
                    });
                }
            });
        },
        getQaData() {
            const $this = this;
            $.ajax({
                url: 'getQaData',
                type: 'POST',
                data: {
                    filter: 'engineer'
                },
                dataType: 'json',
                success(data) {
                    $this.qaData = data;
                }
            });
        },
        createSlick() {
            $(this.$refs.engineer_slick).slick({
                infinite: true,
                slidesToShow: 4,
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
        }
    }

};