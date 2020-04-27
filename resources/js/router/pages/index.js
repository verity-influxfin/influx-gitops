import videoShareComponent from './component/videoShareComponent';
import experienceComponent from './component/experienceComponent';
import joinComponent from './component/joinComponent';

export default {
    template: `
        <div>
            <div class="banner">
                <img src="./image/banner.jpg" class="img-responsive">
                <div class="cover"></div>
                <p class="heading-title">我們成功幫助了{{count}}筆交易</p>
                <p class="heading-description" ref="description"></p>
                <div class="heading-container">
                    <a class="btn btn-loan" href="https://event.influxfin.com/R/url?p=webbanner" target="_blank">立即借款</a>
                    <a class="btn btn-invest" href="https://event.influxfin.com/r/iurl?p=webinvest" target="_blank">立即投資</a>
                </div>
                <div class="heading-info">
                    <p>幫助年輕人完成夢想</p>
                    <p>協助打造溫暖家庭小窩</p>
                    <p>解決社會大眾生活急需</p>
                </div>
            </div>
            <experience ref="experience" title="用戶心得分享" :data="this.experiences"></experience>
            <div class="service-items-wrapper" data-aos="flip-left" data-aos-duration="1000">
                <div class="service-slick" ref="service_slick">
                    <div v-for="item in this.services" class="slick-item">
                        <router-link :to="item.link">
                            <img :src="item.imageSrc">
                            <div>
                                <h2>{{item.title}}</h2>
                                <p>{{item.eTitle}}</p>
                                <span v-if="!item.isActive">(Coming soon)</span>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="profession-wrapper">
                <div class="profession-title"><p>以金融為核心，以科技為輔具，普匯給您前所未有的專業金融APP</p><span>為什麼選擇普匯金融科技?</span></div>
                <div class="profession-content">
                    <div>
                        <img src="./image/best1.png" class="img-fluid" @mouseover="transform($event)" @mouseleave="recovery($event)">
                        <p>最專業的金融專家</p>
                        <span>普匯擁有近20年金融專業經驗，深度理解各類金融產品、相關金融法規、財稅務、金流邏輯...等。能針對不同產業產品與市場，設計出更適合用戶需求的金融服務。</span>
                    </div>
                    <div>
                        <img src="./image/best2.png" class="img-fluid" @mouseover="transform($event)" @mouseleave="recovery($event)">
                        <p>最先進的AI科技系統</p>
                        <span>普匯擁有完善的金融科技技術，包含: 反詐欺反洗錢系統、競標即時撮合系統、 風控信評/線上對保、自動撥貸/貸後管理、 分秒計息等，不斷與時俱進迭代優化。</span>
                    </div>
                    <div>
                        <img src="./image/best3.png" class="img-fluid" @mouseover="transform($event)" @mouseleave="recovery($event)">
                        <p>簡單、快速、安全、隱私</p>
                        <span>視覺化簡潔好用的操作介面，運用先進科技與AWS 安全系統，保護您的個資絕不外洩，讓您在步入圓夢捷徑的同時，安全又放心。</span>
                    </div>
                </div>
                <div class="profession-footer" data-aos="fade-right">
                    <div class="profession-slick" ref="profession_slick">
                        <div v-for="item in dossales" class="slick-item">
                            <img :src="item.imageSrc" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
            <div class="message-wrapper">
                <h2>inFlux金融科技最新知識訊息</h2>
                <div class="message-content" data-aos="fade-right">
                    <div v-for="item in knowledge" class="content-row">
                        <router-link :to="item.link"><img :src="item.imageSrc" class="img-fluid"></router-link>
                        <div>
                            <p>{{item.title}}</p>
                            <span>{{item.detail}}</span><br>
                            <router-link :to="item.link" class="btn btn-danger">閱讀更多</router-link>
                        </div>
                    </div>
                </div>
            </div>
            <videoShare ref="videoShare" title="普匯生活分享" :data="this.shares"></videoShare>
            <div class="news-wrapper">
                <h2>普匯最新消息</h2>
                <div class="news-slick" ref="news_slick">
                    <div v-for="item in news" class="slick-item">
                        <router-link :to="item.link">
                            <img :src="item.imageSrc" class="img-fluid">
                            <p>{{item.title}}</p>
                        </router-link>
                    </div>
                </div>
            </div>
            <join href="./image/child_banner.jpg" :isShowAll="true"></join>
        </div>
    `,
    components: {
        'videoShare': videoShareComponent,
        'experience': experienceComponent,
        'join': joinComponent
    },
    data: () => ({
        description: '普匯．你的手機ATM',
        timeLineMax: '',
        dossales: [
            { "imageSrc": "./image/dossal1.png" },
            { "imageSrc": "./image/dossal2.png" },
            { "imageSrc": "./image/dossal3.png" },
            { "imageSrc": "./image/dossal4.png" }
        ],
        services: [],
        textIndex: 0,
        data: 13268
    }),
    computed: {
        count() {
            let l10nEN = new Intl.NumberFormat("en-US");
            return l10nEN.format(this.data);
        },
        experiences(){
            return this.$store.getters.ExperiencesData;
        },
        shares(){
            return this.$store.getters.SharesData;
        },
        knowledge(){
            return this.$store.getters.KnowledgeData;
        },
        news(){
            return this.$store.getters.NewsData;
        },
    },
    created() {
        this.$store.dispatch('getExperiencesData');
        this.$store.dispatch('getKnowledgeData');
        this.$store.dispatch('getNewsData');
        this.$store.dispatch('getSharesData',{category:'share'});
        this.getServiceData();
        console.log('index');
    },
    mounted() {
        this.typing();
        this.interval();
        this.createSlick();
        this.$refs.experience.createSlick();
        $(this.$refs.videoShare.$refs.share_content).attr('data-aos', 'fade-left');
        $(this.$refs.experience.$refs.experience_slick).attr('data-aos', 'zoom-in');
        AOS.init();
    },
    watch:{
        news(){
            this.$nextTick(()=>{
                $(this.$refs.news_slick).slick('refresh');
                $(this.$refs.news_slick).slick('slickSetOption', 'slidesToShow', 3);
            });
        },
        services(){
            this.$nextTick(()=>{
                $(this.$refs.service_slick).slick('refresh');
                $(this.$refs.service_slick).slick('slickSetOption', 'slidesToShow', 6);
            });
        }
    },
    methods: {
        getServiceData(){
            const $this = this;
            $.ajax({
                url:'getServiceData',
                type:'POST',
                dataType:'json',
                success(data){
                    $this.services = data;
                }
            });
        },
        typing() {
            if (this.textIndex < this.description.length) {
                $(this.$refs.description).append(this.description[this.textIndex]);
                this.textIndex++;
                setTimeout(() => {
                    this.typing();
                }, 300);
            }
        },
        interval() {
            setInterval(() => {
                $(this.$refs.description).text('');
                this.textIndex = 0;
                this.typing();
            }, 5000);
        },
        createSlick() {
            $(this.$refs.service_slick).slick({
                infinite: true,
                slidesToShow: 6,
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

            $(this.$refs.news_slick).slick({
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

            $(this.$refs.profession_slick).slick({
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
        },
        transform($event) {
            this.timeLineMax = new TimelineMax({ paused: true, reversed: true });
            this.timeLineMax.to($event.target, { 'max-width': '90%' });
            this.timeLineMax.play();
        },
        recovery($event) {
            this.timeLineMax.reverse();
        }
    }
};