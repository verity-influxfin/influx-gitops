import joinComponent from './component/joinComponent.vue';

export default {
    template: `
        <div class="company-wrapper">
            <div class="company-header">
                <div class="header-title">
                    <img src="./image/child_banner.jpg">
                    <div class="title">公司介紹</div>
                </div>
                <div class="header-banner">
                    <img src="./image/company_banner.png">
                    <div>
                        <p>關於普匯</p>
                        <h3>普匯相信每個年輕人，我們致力幫助他們完成人生的夢想</h3>
                    </div>
                </div>
                <div class="header-footer">
                    <h5>金融服務「普」及大眾，人才「匯」流。</h5>
                    <p>普匯金融科技不是銀行，我們是專業的Fintech金融科技顧問，由具備深厚的風險管控、金融產品設計經驗的金融團隊組成，致力提供互信互利的平台，將借款人與投資者聯繫起來，共創雙贏機會。運用AI智能科技與安全風控模組，將專業金融產品與線上簡易方式搭起投資人與借款人的橋梁。以「金融專業」為核心，「科技工具」為輔助，提供「最有溫度」的社群服務，拉近人與人的距離，讓金融年輕化並串起社會閒置資源，幫助年輕人完成自我學習成長與創業夢想！</p>
                </div>
            </div>
            <div class="company-background company-content">
                <h3>我們堅持</h3>
                <div class="regulation-slick" ref="regulation_slick">
                    <div v-for="(item,index) in regulations" class="slick-item" :key="index">
                        <img :src="item.imageSrc">
                        <h5>{{item.title}}</h5>
                        <p>{{item.text}}</p>
                    </div>
                </div>
            </div>
            <join href="./image/child_banner.jpg" :isShowLoan="true"  subTitle="加入普匯完成你的財富目標吧！"></join>
            <div class="company-background">
                <h3>我們的成就</h3>
                <div id="cd-timeline" class="cd-container">
                    <div v-for="(item,index) in this.milestone" class="cd-timeline-block" :key="index">
                        <div class="cd-timeline-img cd-icon" v-html="item.dateTime"></div>
                        <div class="cd-timeline-content">
                            <h2>{{item.title}}</h2>
                            <p>{{item.content}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="company-background media-content">
                <h3>媒體報導支持</h3>
                <div class="media-slick" ref="media_slick" data-aos="zoom-in">
                    <div v-for="(item,index) in media" class="slick-item" :key="index">
                        <a :href="item.link" target="_blank"><img :src="item.imageSrc"></a>
                        <p>{{item.title}}</p>
                    </div>
                </div>
            </div>
            <div class="company-background partner-content">
                <h3>合作夥伴</h3>
                <div class="block" v-for="(item,index) in partner" :key="index">
                    <div class="partner-photo"><img :src="item.imageSrc" class="img-fluid"></div>
                    <div class="partner-card">
                        <h2>{{item.title}}</h2>
                        <p>{{item.subTitle}}</p>
                        <hr>
                        <p v-html="item.text"></p>
                    </div>
                </div>
            </div>
        </div>
    `,
    components: {
        'join': joinComponent
    },
    data: () => ({
        regulations: [
            {
                'title': '簡單',
                'text': '直覺化UIUX介面設計，讓操作使用更簡單便利，第一次開啟使用就能上手',
                'imageSrc': './image/company_icon1.png'
            }, {
                'title': '快速',
                'text': '全程手機線上申請，AI系統24小時不間斷驗證，提升作業速度與效率，加快用戶取得資金',
                'imageSrc': './image/company_icon2.png'
            }, {
                'title': '安全',
                'text': '使用 Amazon Web Services雲端服務平台，個資絕不外洩',
                'imageSrc': './image/company_icon3.png'
            }, {
                'title': '隱私',
                'text': '全程無人系統驗證操作，從申請到取得款項，資訊完全不外洩，保障投資人與借款人各資隱密與隱私',
                'imageSrc': './image/company_icon4.png'
            }, {
                'title': '低風險高報酬',
                'text': '小額分散、分期還款、降低風險、複利效果，創造最高報酬',
                'imageSrc': './image/company_icon5.png'
            }
        ],
        media: [
            {
                'title': '普匯金融科技 CEO：觀念、態度、努力 決定你的人生',
                'imageSrc': './image/media1.png',
                'link': 'https://news.cnyes.com/news/id/4267004'
            }, {
                'title': '小資金實現大夢想　InFlux普匯P2P借貸平台實現3小時核貸、1小時放款',
                'imageSrc': './image/media2.png',
                'link': 'https://act.chinatimes.com/market/content.aspx?AdID=6585&chdtv'
            }, {
                'title': '關注台灣年輕族群　InFlux普匯推出有溫度的P2P借貸平台',
                'imageSrc': './image/media3.png',
                'link': 'http://n.yam.com/Article/20180803346949'
            }, {
                'title': '金融科技創新園區9家廠商加入 企業實驗室首波六大主題公布',
                'imageSrc': './image/media4.png',
                'link': 'https://tw.money.yahoo.com/%E9%87%91%E8%9E%8D%E7%A7%91%E6%8A%80%E5%89%B5%E6%96%B0%E5%9C%92%E5%8D%809%E5%AE%B6%E5%BB%A0%E5%95%86%E5%8A%A0%E5%85%A5-%E4%BC%81%E6%A5%AD%E5%AF%A6%E9%A9%97%E5%AE%A4%E9%A6%96%E6%B3%A2%E5%85%AD%E5%A4%A7%E4%B8%BB%E9%A1%8C%E5%85%AC%E5%B8%83-083826449.html'
            }, {
                'title': '金融科技創新園區 六大企業實驗室出爐！',
                'imageSrc': './image/media5.png',
                'link': 'https://m.ctee.com.tw/livenews/aj/a95645002019042615564198'
            }, {
                'title': 'AI自動核貸無人化融資平台　打造金融科技新型態',
                'imageSrc': './image/media6.png',
                'link': 'https://www.nownews.com/news/20180807/2798010/'
            }, {
                'title': '金融科技創新園區9家廠商加入 企業實驗室首波六大主題公布',
                'imageSrc': './image/media7.png',
                'link': 'https://www.wealth.com.tw/home/articles/20567'
            },
        ],
        partner: [
            {
                'imageSrc': './image/partner1.png',
                'title': '國立台北商業大學',
                'subTitle': '協同學界，共同傳統金融轉型，共創金融科技創新。',
                'text': '為建立跨領域學生正確之財富管理觀念與知識，與普匯進行合作，透過財富管理模擬軟體，提升學生對財富管理的認知，並且一同完善金融科技項目，實現商轉價值。透過推動緊密產學合作以及使用者體驗設計程序，協助金融機構轉型思，推動金融科技的創新應用。'
            },
            {
                'imageSrc': './image/partner2.png',
                'title': '東吳大學',
                'subTitle': '東吳大學為結合金融科技學術理論與產業實務，與普匯合作培養金融科技人才並且推動SME中小企業金融服務。結合金融機構並同開發符合市場需求之金融科技產品。定期辦理座談會或論壇，邀請各界人士對談以利釐清事實或反映業者需求，善盡學界之社會良心責任並且積與業界溝通經驗。',
                'text': '為建立跨領域學生正確之財富管理觀念與知識，與普匯進行合作，透過財富管理模擬軟體，提升學生對財富管理的認知，並且一同完善金融科技項目，實現商轉價值。透過推動緊密產學合作以及使用者體驗設計程序，協助金融機構轉型思，推動金融科技的創新應用。'
            },
            {
                'imageSrc': './image/partner3.png',
                'title': '國立高雄應用科技大學',
                'subTitle': '與普匯共同推動南區校園金融科技生態系統(FinTech ecosystem)，提供南台灣35所夥伴學校資源共享服務。',
                'text': '為了開拓南臺灣金融科技的版圖，普匯與高科大攜手合作，聯手促進金融機構南向，打造南區金融科技生態圈。<br><br>普匯透過發起產學合作培育金融科技跨領域人才，協助學子順利進入職場，整合跨領域的基礎技術發展金融科技未來的商務應用。貢獻創新的思維模式，與中心共同開發金融科技產品項目，相互激勵促進正向能量的成長機會，增進金融科技交易與創新企業模式的能量。'
            },
            {
                'imageSrc': './image/partner4.png',
                'title': '國立臺北大學                ',
                'subTitle': '大數據與智慧城市研究中心、金融科技暨綠色金融研究中心',
                'text': '臺北大學承繼中興大學法商學院的優良傳統，持續開設多項專業與跨領域創新課程，與世界知名大學實質交流，成立「大數據與智慧城市研究中心」、「金融科技暨綠色金融研究中心」，肩負承擔促進社會對話、資源整合之任務，促進產業加值升級之責任與義務。'
            },
            {
                'imageSrc': './image/partner5.png',
                'title': '國立臺灣大學',
                'subTitle': '臺灣大學 金融科技研究中心',
                'text': '臺大是臺灣規模最大亦為最具影響力的學校，擁有頂尖人才，成立「金融科技研究中心」，透過跨領域產學合作，運用資訊科技創新服務、創新商業模式應用，以促進金融科技產業升級。'
            },
            {
                'imageSrc': './image/partner6.png',
                'title': '國立政治大學',
                'subTitle': '政治大學金融科技研究中心',
                'text': '本校於107年2月正式成立產創總中心，目前由會計系戚務君教授擔任營運長，並由戚教授兼任研創中心及育成中心主任。本中心設有「推動委員會」負責訂定總中心發展策略與目標，協助審查及輔導進駐企業、協助產學合作計畫之執行以及督導各中心營運。另設有「專家顧問諮詢委員會」負責提供總中心營運相關之建議、輔導與諮詢服務，或提供進駐企業專業諮詢，或協助企業營運。<br>本中心於107年於研創中心三樓開設政大創新園區，與台灣微軟共同協定為新創產業加速器，並與資策會「FinTech Space」共同結盟，提供新創基地給予金融科技產業及其他類型新創團隊，以期促進更多的產學合作及創新研發。'
            },
            {
                'imageSrc': './image/partner7.png',
                'title': '工研院臺灣創新快製媒合中心',
                'subTitle': '致力於推動台灣成為國際創新創業基地',
                'text': '臺灣創新快製媒合中心(Taiwan Rapid Innovation Prototyping League for Entrepreneurs)，英文簡稱TRIPLE，結合SI/ODM、加速器業者與研究機構，期望以臺灣優質軟硬體整合、先進製造與管理能量，提供國內外創新事業快速試製以及其他在設計、行銷、研發等方面的加值服務，協助其加速落實創意，實現市場價值。快製中心以提供快速試製創新商機媒合服務為主，協助媒合新創事業與快製聯盟業者，促成雙方合作。                '
            },
            {
                'imageSrc': './image/partner8.png',
                'title': '立勤國際律師事務所',
                'subTitle': '置備全般法律、財務、會計、地政、智慧財產服務的綜合性事務所',
                'text': '立勤國際法律事務所自2014年擴大營運至今，已經橫跨中國、台灣、菲律賓三地，並加入國際環太平洋律師組織IBPA，與各國律師建立國際聯繫。目前在台灣有台北、新竹、高雄三處共四間事務所，擠身台灣前十大事務所，提供全般法律、財務、會計、地政、智慧財產服務等方面的服務。'
            },
            {
                'imageSrc': './image/partner9.png',
                'title': '碁元會計師事務所',
                'subTitle': '各大企業專業財務顧問，堅守品質服務',
                'text': '碁元會計師事務所將每位客戶視為難能可貴的朋友般珍惜，秉持服務、信任、學習的精神為客戶處理大大小小的帳務及稅務問題，並深受客戶肯定與支持。<br>提供工商登記、代客記帳服務、財稅簽證、上市上櫃前置輔導、營業稅及所得稅代理申報、遺產及贈與稅、境外公司設立與規劃、財務顧問、企業評價及各種稅務之行政救濟等服務。'
            }
        ],
        milestone:[
            {
                'dateTime':'2020<br>11.11',
                'title':'普匯帶領【AI金融科技聯盟】第一屆創新創意競賽',
                'content':'將近200組團隊，1000多位同學的熱情參與，從晉級24強再拔擢出最優秀的12組團隊，為推動AI創新金融，培育校園創意人才邁出成功第一步'

            },{
                'dateTime':'2019<br>11.29',
                'title':'【台北金融科技展】普匯共襄盛舉',
                'content':'普匯獲邀參與一年一度的台北金融科技盛會，與國、內外金融科技產、官、學、研多方交流，共同見證AI金融科技的蓬勃發展'

            },{
                'dateTime':'2019<br>05.12',
                'title':'普匯會員數突破20,000人',
                'content':'普匯平台自2018年八月上線至今，會員數突破20,000人'
            },{
                'dateTime':'2019<br>02.15',
                'title':'【金管會】金融創新輔導團隊',
                'content':'普匯成功申請為「金管會金融創新輔導團隊」，為金管會監理沙盒正式輔導企業之一。'

            },{
                'dateTime':'2017<br>12.08',
                'title':'公司成立',
                'content':'普匯金融科技股份有限公司'

            },
        ]
    }),
    created(){
        console.log('company');
    },
    mounted() {
        this.createSlick();
        this.timeline();
        AOS.init();
    },
    methods: {
        createSlick() {
            $(this.$refs.regulation_slick).slick({
                infinite: true,
                slidesToShow: 5,
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

            $(this.$refs.media_slick).slick({
                infinite: true,
                slidesToShow: 5,
                slidesToScroll: 1,
                autoplay: true,
                prevArrow: '',
                nextArrow: '',
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
        timeline() {
            let $timeline_block = $('.cd-timeline-block');

            $timeline_block.each(function () {
                if ($(this).offset().top > $(window).scrollTop() + $(window).height() * 0.75) {
                    $(this).find('.cd-timeline-img, .cd-timeline-content').addClass('is-hidden');
                }
            });

            $(window).on('scroll', function () {
                $timeline_block.each(function () {
                    if ($(this).offset().top <= $(window).scrollTop() + $(window).height() * 0.75 && $(this).find('.cd-timeline-img').hasClass('is-hidden')) {
                        $(this).find('.cd-timeline-img, .cd-timeline-content').removeClass('is-hidden').addClass('bounce-in');
                    }
                });
            });
        }
    }
}