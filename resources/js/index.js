

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
            <div class="experience-wrapper">
                <h2>用戶心得分享</h2>
                <div class="experience-slick" ref="experience_slick" data-aos="zoom-in">
                    <div v-for="item in userFeedback" class="slick-item">
                        <div class="slick-title">
                            <img :src="item.imageSrc">
                            <div>
                                <p>{{item.name}}</p>
                                <span>{{item.unit}}</span>
                            </div>
                        </div>
                        <div class="slick-content">
                            <div></div>
                            <p>{{item.memo}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="service-items-wrapper" data-aos="flip-left" data-aos-duration="1000">
                <div class="service-slick" ref="service_slick">
                    <div v-for="item in services" class="slick-item">
                        <a :href="'#' + item.link">
                            <img :src="item.imageSrc">
                            <div>
                                <h2>{{item.title}}</h2>
                                <p>{{item.eTitle}}</p>
                                <span v-if="!item.isActive">(Coming soon)</span>
                            </div>
                        </a>
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
                    <div v-for="item in cases" class="content-row">
                        <a :href="item.href"><img :src="item.imageSrc" class="img-fluid"></a>
                        <div>
                            <p>{{item.title}}</p>
                            <span>{{item.detail}}</span><br>
                            <a :href="item.href" class="btn btn-danger">閱讀更多</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="share-wrapper">
                <h2>普匯生活分享</h2>
                <div class="share-content" data-aos="fade-left">
                    <div v-for="item in eventShare" class="content-row">
                        <iframe :src="item.videoLink" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        <div>
                            <p>【普匯小學堂】</p>
                            <p>{{item.title}}</p>
                            <br>
                            <span>{{item.detail}}</span><br>
                            <a :href="item.link" class="btn btn-danger">閱讀更多</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="news-wrapper">
                <h2>普匯最新消息</h2>
                <div class="news-slick" ref="news_slick">
                    <div v-for="item in news" class="slick-item">
                        <a :href="item.link">
                            <img :src="item.imageSrc" class="img-fluid">
                            <p>{{item.title}}</p>
                        </a>
                    </div>
                </div>
            </div>
            <div class="join-wrapper">
                <img src="./image/child_banner.jpg">
                <h2>準備好完成夢想了嗎？</h2>
                <p>加入普匯完成你的目標吧！</p>
                <div class="btn-wrapper">
                    <a class="btn btn-loan" href="https://event.influxfin.com/R/url?p=webbanner" target="_blank">立即借款</a>
                    <a class="btn btn-invest" href="https://event.influxfin.com/r/iurl?p=webinvest" target="_blank">立即投資</a>
                <div>
            </div>
        </div>
    `,
    data:() => ({
        description : '普匯．你的手機ATM',
        timeLineMax:'',
        dossales:[
            {"imageSrc":"./image/dossal1.png"},
            {"imageSrc":"./image/dossal2.png"},
            {"imageSrc":"./image/dossal3.png"},
            {"imageSrc":"./image/dossal4.png"}
        ],
        userFeedback:[
            {
                'name':'羅同學',
                'unit':'國立台灣大學',
                'memo':'為了睡覺',
                'imageSrc':'./image/message_icon.png'
            },{
                'name':'羅同學',
                'unit':'國立台灣大學',
                'memo':'為了睡覺',
                'imageSrc':'./image/message_icon.png'
            },{
                'name':'羅同學',
                'unit':'國立台灣大學',
                'memo':'為了睡覺',
                'imageSrc':'./image/message_icon.png'
            },{
                'name':'羅同學',
                'unit':'國立台灣大學',
                'memo':'為了睡覺',
                'imageSrc':'./image/message_icon.png'
            },{
                'name':'羅同學',
                'unit':'國立台灣大學',
                'memo':'為了睡覺',
                'imageSrc':'./image/message_icon.png'
            },{
                'name':'羅同學',
                'unit':'國立台灣大學',
                'memo':'為了睡覺',
                'imageSrc':'./image/message_icon.png'
            }
        ],
        cases:[
            {
                "title":"資產翻倍術 - 普匯投資人實例分享",
                "detail":"王媽媽如何利用普匯投資賺得16%的年報酬率？退休小學教師——王媽媽的投資經驗，王媽媽從2019年8月開始使用普匯投資債權，第一個月投入20萬本金，分散成200筆1000元，每月回款穩定，並將收回的本利再繼續投資新債權，額外每月挹注資金，以錢滾錢，截至今天已累積517筆！投資標的信用評級約6～7級，年利率14~16%，這500多筆中逾期戶只有6位！風險比股票低數千倍！",
                "imageSrc":"https://www.influxfin.com/wp-content/uploads/2020/04/小學堂.jpg",
                "link":"#"
            },{
                "title":"資產翻倍術 - 普匯投資人實例分享",
                "detail":"王媽媽如何利用普匯投資賺得16%的年報酬率？退休小學教師——王媽媽的投資經驗，王媽媽從2019年8月開始使用普匯投資債權，第一個月投入20萬本金，分散成200筆1000元，每月回款穩定，並將收回的本利再繼續投資新債權，額外每月挹注資金，以錢滾錢，截至今天已累積517筆！投資標的信用評級約6～7級，年利率14~16%，這500多筆中逾期戶只有6位！風險比股票低數千倍！",
                "imageSrc":"https://www.influxfin.com/wp-content/uploads/2020/04/小學堂.jpg",
                "link":"#"
            },{
                "title":"資產翻倍術 - 普匯投資人實例分享",
                "detail":"王媽媽如何利用普匯投資賺得16%的年報酬率？退休小學教師——王媽媽的投資經驗，王媽媽從2019年8月開始使用普匯投資債權，第一個月投入20萬本金，分散成200筆1000元，每月回款穩定，並將收回的本利再繼續投資新債權，額外每月挹注資金，以錢滾錢，截至今天已累積517筆！投資標的信用評級約6～7級，年利率14~16%，這500多筆中逾期戶只有6位！風險比股票低數千倍！",
                "imageSrc":"https://www.influxfin.com/wp-content/uploads/2020/04/小學堂.jpg",
                "link":"#"
            },{
                "title":"資產翻倍術 - 普匯投資人實例分享",
                "detail":"王媽媽如何利用普匯投資賺得16%的年報酬率？退休小學教師——王媽媽的投資經驗，王媽媽從2019年8月開始使用普匯投資債權，第一個月投入20萬本金，分散成200筆1000元，每月回款穩定，並將收回的本利再繼續投資新債權，額外每月挹注資金，以錢滾錢，截至今天已累積517筆！投資標的信用評級約6～7級，年利率14~16%，這500多筆中逾期戶只有6位！風險比股票低數千倍！",
                "imageSrc":"https://www.influxfin.com/wp-content/uploads/2020/04/小學堂.jpg",
                "link":"#"
            }
        ],
        eventShare:[
            {
                'title':'普匯公司介紹',
                'detail':'這回就讓我們帶您一起來了解普匯到底在做什麼吧!!',
                'videoLink':'https://www.youtube.com/embed/sTqyd5mkjdI',
                'href':'#'
            },{
                'title':'普匯公司介紹',
                'detail':'這回就讓我們帶您一起來了解普匯到底在做什麼吧!!',
                'videoLink':'https://www.youtube.com/embed/sTqyd5mkjdI',
                'href':'#'
            },{
                'title':'普匯公司介紹',
                'detail':'這回就讓我們帶您一起來了解普匯到底在做什麼吧!!',
                'videoLink':'https://www.youtube.com/embed/sTqyd5mkjdI',
                'href':'#'
            },{
                'title':'普匯公司介紹',
                'detail':'這回就讓我們帶您一起來了解普匯到底在做什麼吧!!',
                'videoLink':'https://www.youtube.com/embed/sTqyd5mkjdI',
                'href':'#'
            },
        ],
        news:[
            {
                "link":'#',
                "title": "普匯金融科技股份有限公司聲明公告",
                "imageSrc": "./image/statement.png"
            },{
                "link":'#',
                "title": "普匯金融科技股份有限公司聲明公告",
                "imageSrc": "./image/statement.png"
            },{
                "link":'#',
                "title": "普匯金融科技股份有限公司聲明公告",
                "imageSrc": "./image/statement.png"
            },{
                "link":'#',
                "title": "普匯金融科技股份有限公司聲明公告",
                "imageSrc": "./image/statement.png"
            }
        ],
        services:[
            {
                title:"學生貸",
                eTitle:"College Loan",
                imageSrc:"./image/student_loan.jpg",
                isActive:true,
                link:"/CollegeLoan"
            },{
                title:"上班族貸",
                eTitle:"Fresh Graduate Loan",
                imageSrc:"./image/worker_loan.jpg",
                isActive:true,
                link:"/FreshGraduateLoan"
            },{
                title:"手機貸",
                eTitle:"Mobile Loan",
                imageSrc:"./image/phone_loan.jpg",
                isActive:true,
                link:"/MobileLoan"
            },{
                title:"工程師貸",
                eTitle:"Engineer Loan",
                imageSrc:"./image/rd_loan.jpg",
                isActive:true,
                link:"/EngineerLoan"
            },{
                title:"外匯車貸",
                eTitle:"Imported Car Loan",
                imageSrc:"./image/car_loan.jpg",
                isActive:false,
                link:""
            },{
                title:"新創企業貸",
                eTitle:"Star-up Loan",
                imageSrc:"./image/creation_loan.jpg",
                isActive:false,
                link:""
            },
        ],
        textIndex : 0,
        data : 13268
    }),
    computed:{
        count(){
            let l10nEN = new Intl.NumberFormat("en-US");
            return l10nEN.format(this.data);
        },
    },
    created(){
    },
    mounted(){
        this.typing();
        this.interval();
        this.createSlick();
    },
    methods:{
        typing(){
            if(this.textIndex < this.description.length){
                $(this.$refs.description).append(this.description[this.textIndex]);
                this.textIndex++;
                setTimeout(()=>{
                    this.typing();
                },300);
            }
        },
        interval(){
            setInterval(() => {
                $(this.$refs.description).text('');
                this.textIndex =0;
                this.typing();
            }, 5000);
        },
        createSlick(){
            $(this.$refs.service_slick).slick({
                infinite: true,
                slidesToShow: 6,
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

            $(this.$refs.experience_slick).slick({
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

            $(this.$refs.news_slick).slick({
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

            $(this.$refs.profession_slick).slick({
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
        },
        transform($event){
            this.timeLineMax = new TimelineMax({paused: true, reversed: true});
            this.timeLineMax.to($event.target, {'max-width': '90%'});
            this.timeLineMax.play();
        },
        recovery($event){
            this.timeLineMax.reverse();
        }
    }
};