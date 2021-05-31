<template>
    <div class="index-wrapper">
        <Splide class="banner" :options="bannerOptions" v-if="bannerData.length !== 0">
        <SplideSlide class="puhey-banner">
            <img :src="'/images/index-banner-d.png'" class="hidden-desktop img-fluid" />
            <img :src="'/images/index-banner-m.png'" class="hidden-phone img-fluid" />
            <img :src="'/images/diagram-d.svg'" class="diagram hidden-desktop" />
            <img :src="'/images/diagram-m.svg'" class="diagram hidden-phone" />
            <div class="content">
                <p>最貼近年輕人的金融科技平台</p>
                <span>普匯．你的手機ATM</span>
                <div class="box">
                    <a
                        class="loan"
                        href="/investLink"
                        target="_blank"
                        onClick="ga('send', 'event', 'Click', 'Nav Click', 'investLink','10')"
                    ><img src="../asset/images/light-b.svg" class="img-fluid" />
                    <div class="text">立即投資</div></a
                    >
                    <a
                        class="borrow"
                        href="/borrowLink"
                        target="_blank"
                        onClick="ga('send', 'event', 'Click', 'Nav Click', 'borrowLink','10')"
                    ><img src="../asset/images/light-y.svg" class="img-fluid" />
                    <div class="text">立即借款</div></a
                    >
                </div>
            </div>
        </SplideSlide>
        <SplideSlide v-for="(item, index) in bannerData" class="puhey-banner" :key="index">
            <a :href="item.link" target="_blank">
            <img :src="`/upload/banner/${item.desktop}`" class="hidden-desktop img-fluid" />
            <img :src="`/upload/banner/${item.mobile}`" class="hidden-phone img-fluid" />
            </a>
        </SplideSlide>
        </Splide>
        <div class="banner" v-else>
            <div class="puhey-banner">
                <img :src="'/images/index-banner-d.png'" class="hidden-desktop img-fluid" />
                <img :src="'/images/index-banner-m.png'" class="hidden-phone img-fluid" />
                <img :src="'/images/diagram-d.svg'" class="diagram hidden-desktop" />
                <img :src="'/images/diagram-m.svg'" class="diagram hidden-phone" />
                <div class="content">
                    <p>最貼近年輕人的金融科技平台</p>
                    <span>普匯．你的手機ATM</span>
                    <div class="box">
                        <a
                        class="loan"
                        href="https://event.influxfin.com/r/iurl?p=webinvest"
                        target="_blank"
                        onClick="ga('send', 'event', 'Click', 'Nav Click', 'invest','10');"
                        ><img src="../asset/images/light-b.svg" class="img-fluid" />
                        <div class="text">立即投資</div></a
                        >
                        <a
                        class="borrow"
                        href="https://event.influxfin.com/R/url?p=webbanner"
                        target="_blank"
                        onClick="ga('send', 'event', 'Click', 'Nav Click', 'borrow','10');"
                        ><img src="../asset/images/light-y.svg" class="img-fluid" />
                        <div class="text">立即借款</div></a
                        >
                    </div>
                </div>
            </div>
        </div>

        <!-- 開頭計數器 -->
        <div class="alesis-counters">
            <div class="wrapper">
                <index-counter image="/images/alesis-registered.svg" header="累積註冊用戶" number="60000" unit="人"></index-counter>
                <index-counter image="/images/alesis-totalmoney.svg" header="累積放款金額" number="15857" unit="萬"></index-counter>
                <index-counter image="/images/alesis-totalapproved.svg" header="累積成交筆數" number="37390" unit="筆"></index-counter>
            </div>
        </div>
        <!-- / 開頭計數器 -->

        <!-- 我們的服務 -->
        <div class="alesis-our-service">
            <index-section header="我們的服務" :secondary="true">
                <img class="image" src="/images/p2p.svg" />
                <div class="actions">
                    <div class="left">
                        <a href="#!" class="action">立即投資</a>
                        <a href="#!" class="action">了解更多</a>
                    </div>
                    <div class="right">
                        <a href="#!" class="action">立即借款</a>
                        <a href="#!" class="action">了解更多</a>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 我們的服務 -->

        <!-- 公司簡介 -->
        <div class="alesis-company-introduction">
            <index-section header="公司簡介">
                <div class="carousel">
                    <div class="entity">
                        <div class="left">
                            <img src="/images/sshot-1559.png" alt="">
                        </div>
                        <div class="lightbox">
                            <img class="image" src="/images/sshot-1566.png" />
                        </div>
                        <div class="right">
                            <img src="/images/sshot-1558.png" alt="">
                        </div>
                    </div>
                </div>
                <div class="introduction">
                    <div class="showcase">
                        <img src="/images/借款額度-------.png" alt="">
                    </div>
                    <div class="items">
                        <index-bullet class="item" image="/images/alesis-ift.svg" header="普匯•你的手機ATM" description="快速申貸、債權投資/轉讓，活用資金，一機搞定"></index-bullet>
                        <index-bullet class="item" image="/images/alesis-brain.svg" header="“高端AI科技，FinTech專家”" description="普匯不是銀行，我們是FinTech金融科技專家，運用AI智能科技與安全風控模組，全程無人為干擾!"></index-bullet>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 公司簡介 -->

        <!-- 產品特色 -->
        <div class="alesis-feature">
            <index-section header="產品特色" :secondary="true">
                <div class="wrapper">
                    <div class="showcase">
                        <img class="image" src="/images/步驟1-01.png" alt="">
                    </div>
                    <div class="content">
                        <index-bullet class="bullet" image="/images/alesis-stonk.svg" header="“以「普惠金融」為志業”" description="透過P2P、區塊鏈技術，進行大量、小額、分散借貸投資及債權轉讓，深受學生、社會新鮮人、中小企業及眾多投資人喜愛。"></index-bullet>
                        <div class="moons">
                            <div class="item">
                                <index-moon class="moon" header="簡單" level="1"></index-moon>
                                <div class="content">
                                    AWS安全系統為架構，<br>
                                    輔以簡潔的操作界面。
                                </div>
                            </div>
                            <div class="item">
                                <index-moon class="moon" header="快速" level="2"></index-moon>
                                <div class="content">
                                    10分鐘快速填寫申請資料，<br>
                                    全程線上操作，無人干擾
                                </div>
                            </div>
                            <div class="item">
                                <index-moon class="moon" header="安全" level="3"></index-moon>
                                <div class="content">
                                    資訊傳輸由HTTPS方式利用SSL/TLS<br>
                                    加密，儲存於亞馬遜AWS雲端伺服器
                                </div>
                            </div>
                            <div class="item">
                                <index-moon class="moon" header="隱私" level="4"></index-moon>
                                <div class="content">
                                    全台唯一無人化借貸平台，<br>
                                    讓手機成為您專屬的ATM
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slogan">假日放款全年無休</div>
            </index-section>
        </div>
        <!-- / 產品特色 -->

        <!-- 普匯債權快速變現 -->
        <div class="alesis-fast-transfer">
            <index-section header="普匯債權快速變現" multiline="全台唯一債權轉讓功能">
                <div class="wrapper">
                    <div class="graph">
                        <img class="image" src="/images/alesis-transfer-graph.svg" alt="">
                    </div>
                    <div class="moons">
                        <index-moon class="moon" header="債權轉讓" multiline="快速變現" level="3"></index-moon>
                        <index-moon class="moon" header="小額資金" multiline="分散風險" level="2"></index-moon>
                        <index-moon class="moon" header="智能投資" multiline="自動下標" level="1"></index-moon>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 普匯債權快速變現 -->

        <!-- 產品方案 -->
        <div class="alesis-solutions">
            <index-section header="產品方案" :secondary="true">
                <div class="wrapper">
                    <index-plan class="plan" :header="item.header" :targets="item.targets" :image="item.image" :action="item.action" :features="item.features" :unready="item.unready" v-for="(item, index) in plans" :key="index"></index-plan>
                </div>
            </index-section>
        </div>
        <!-- / 產品方案 -->

        <!-- 普匯推薦 銀行速貸好條件 -->
        <div class="alesis-recommend-banks">
            <index-section header="普匯推薦 銀行速貸好條件" multiline="透過普匯結盟夥伴，可享最高額度300萬，超低利率！">
                <div class="wrapper">
                    <div class="box">
                        <img class="logo" src="/images/shanghai.png" alt="">
                        <div class="divider"></div>
                        <div class="form" v-if="isForm">
                            <input class="input" type="text" placeholder="姓名">
                            <input class="input" type="text" placeholder="*手機">
                            <input class="input" type="text" placeholder="*E-mail">
                            <a href="#!" class="button">前往優惠</a>
                            <div class="description">
                                信用貸款總費用年百分率說明：貸款總費用年百分率約2.62%~7.72%。例如：貸款金額：30萬元，貸款期間：5年，貸款年利率：前三個月1.88% (固定利率)，第四個月起2.25%~7.92%，開辦費為新臺幣3,000元，其總費用年百分率約為2.62%~7.72%。(1)本廣告揭露之年百分率係按主管機關備查之標準計算範例予以計算，實際貸款條件，以本行提供之產品為準，且每一顧客實際之年百分率仍以其個別貸款產品及授信條件而有所不同。(2)總費用年百分率不等於貸款利率。(3)本總費用年百分率之計算基準日係依據活動專案適用起日之本行貸款定儲指數(G)調整日期訂定之(當期利率請至本行網站查詢)，請詳閱本行官網定儲指數說明。例如：110年04月23日之貸款定儲指數(G)為0.80%。(4)上海商銀保有依申貸人資信條件核定貸款條件、實際核貸金額以及最終核准貸款與否之權利。
                            </div>
                        </div>
                        <template v-if="!isForm">
                            <div class="list">
                                <div class="item">1日快速核准</div>
                                <div class="item">前3個月利率1.88%起</div>
                                <div class="item">額度最高達300萬，期間最長7年</div>
                            </div>
                            <div class="buttons">
                                <a class="button" href="#!"><index-button>了解更多</index-button></a>
                                <a class="button" href="#!" @click="() => isForm = true"><index-button>取得資格</index-button></a>
                            </div>
                        </template>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 普匯推薦 銀行速貸好條件 -->

        <!-- 分期還款沒壓力 -->
        <div class="alesis-calculator">
            <index-section header="分期還款沒壓力" :secondary="true">
                <div class="wrapper">
                    <div class="calculator">
                        <creditBoard amount="200000" v-on:update-calculator="updateCalculator"/>
                    </div>
                    <div class="inputs">
                        <div class="row">
                            <div class="text">申請</div>
                            <input type="text" class="input" v-model="amountCount">
                            <div class="text">元</div>
                        </div>
                        <div class="row">
                            <div class="text">分期償還</div>
                            <input type="text" class="input" v-model="period">
                            <div class="text">期</div>
                        </div>
                        <div class="row">
                            <div class="text">每月僅需約</div>
                            <input type="text" class="input" v-model="pmt">
                            <div class="text">元</div>
                        </div>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 分期還款沒壓力 -->

        <!-- 看看他們怎麼說 -->
        <div class="alesis-humans">
            <index-section header="看看他們怎麼說">
                <global-carousel>
                    <div class="set">
                        <div class="item">
                            <index-human></index-human>
                        </div>
                        <div class="item">
                            <index-human></index-human>
                        </div>
                    </div>
                    <div class="set">
                        <div class="item">
                            A
                        </div>
                        <div class="item">
                            B
                        </div>
                    </div>
                </global-carousel>
            </index-section>
        </div>
        <!-- / 看看他們怎麼說 -->

        <!-- 媒體報導支持 -->
        <div class="alesis-news">
            <index-section class="header" header="媒體報導支持" :outlined="true" :nospace="true"></index-section>
            <div class="wrapper">
                <div class="logos">
                    <img class="item" src="/images/a645f762048f5f425d0f0a8486f34612.png" alt="">
                    <img class="item" src="/images/1JHY1QOLYPF0.png" alt="">
                    <img class="item" src="/images/Now_News_logo.png" alt="">
                    <img class="item" src="/images/logo-chinatimes2019-1200x635.png" alt="">
                </div>
                <div class="logos -three">
                    <img class="item" src="/images/logo-cn-yes-1.png" alt="">
                    <img class="item" src="/images/logo-red.png" alt="">
                    <img class="item" src="/images/logo300_90.png" alt="">
                </div>
            </div>
        </div>
        <!-- / 媒體報導支持 -->

        <float />
    </div>
</template>

<script>
import { Splide, SplideSlide } from "@splidejs/vue-splide";
//
import creditBoard from "../component/svg/creditBoardComponent";
import float from "../component/floatComponent";
// Alesis components
import indexCounter from "../component/alesis/index/counter";
import indexSection from "../component/alesis/index/section";
import indexBullet from "../component/alesis/index/bullet";
import indexMoon from "../component/alesis/index/moon";
import indexPlan from "../component/alesis/index/plan";
import indexHuman from "../component/alesis/index/human";
import indexButton from "../component/alesis/index/button";
import globalCarousel from "../component/alesis/global-carousel";

export default {
  components: {
    float,
    creditBoard,
    Splide,
    SplideSlide,

    //
    indexCounter,
    indexSection,
    indexBullet,
    indexMoon,
    indexPlan,
    indexHuman,
    indexButton,
    globalCarousel
  },
  data: () => ({
    amountCount: 5000,
    rateCount: 5,
    pmt: 0,
    tweenedPmt: 0,
    plans: [
        {
            header: "學生貸",
            unready: false,
            image: "/images/alesis-student.svg",
            targets: [
                "大學、碩博士在學生",
                "20-35歲中華民國國民"
            ],
            action: "開始試算",
            features: [
                "Instagram / Dcard 大學生最推薦資金媒合平台",
                "備妥雙證件即可，不打擾親友",
                "額度高達12萬",
                "3-24期分期方式選擇多元",
                "隱私安全、全線上申請、無人照會",
            ]
        },
        {
            header: "上班族貸",
            unready: false,
            image: "/images/alesis-work.svg",
            targets: [
                "社會新鮮人、金融小白",
                "20-35歲中華民國國民"
            ],
            action: "開始試算",
            features: [
                "超過200家上市櫃公司、千大企業員工最佳選擇",
                "申請紀錄不上聯徵，不影響未來銀行貸款額度",
                "可貸額度1萬-20萬",
                "3-24期分期方式選擇多元",
                "隱私安全、全線上申請、無人照會",
            ]
        },
        {
            header: "中小企業融資",
            unready: false,
            image: "/images/alesis-companies.svg",
            targets: [],
            action: "敬請期待",
            features: [
                "信保微企貸",
                "申請速度最快，核准機率最高",
            ]
        },
        {
            header: "車輛融資",
            unready: true,
            image: "/images/alesis-car.svg",
            targets: [],
            action: "敬請期待",
            features: []
        }
    ],
    isForm: false,
    isDesktop: window.innerWidth > 767 ? true : false,
    load2: false,
    load3: false,
    routeIndex: {
      start: 0,
      end: 0,
    },
    csKey: 0,
    pmt: 0,
    member: 0,
    period: 3,
    tweenedMember: 0,
    transaction: 0,
    tweenedtransaction: 0,
    bannerData: [],
    milestone: [],
    routeData: [],
    shares: [],
    creditRatingItem: [
      {
        text: "實名認證",
        img: "/images/icon_cert_rev2_identity.svg",
        checked: true,
      },
      {
        text: "社交帳號",
        img: "/images/icon_cert_rev2_social.svg",
        checked: false,
      },
      {
        text: "電子信箱",
        img: "/images/icon_cert_rev2_mail.svg",
        checked: true,
      },
      {
        text: "學生身分",
        img: "/images/icon_cert_rev2_student.svg",
        checked: true,
      },
      {
        text: "最高學歷",
        img: "/images/icon_cert_rev2_diploma.svg",
        checked: false,
      },
      {
        text: "工作認證",
        img: "/images/icon_cert_rev2_worker.svg",
        checked: false,
      },
    ],
    services: [],
    bannerOptions: {
      type: "loop",
      autoplay: true,
      perPage: 1,
      perMove: 1,
      pagination: false,
    },
    avgOptions: {
      type: "loop",
      autoplay: false,
      perPage: 3,
      arrows: false,
      pagination: false,
      gap: "1.5rem",
      breakpoints: {
        767: { autoplay: false, perPage: 1 },
      },
    },
    infoOptions: {
      type: "loop",
      autoplay: true,
      arrows: true,
      perPage: 3,
      perMove: 1,
      pagination: false,
      gap: ".5rem",
      breakpoints: {
        767: { perPage: 1 },
      },
    },
    productOptions: {
      type: "loop",
      autoplay: true,
      direction: window.innerWidth > 767 ? "ltr" : "ttb",
      height: window.innerWidth > 767 ? "auto" : 290,
      drag: window.innerWidth > 767 ? true : false,
      perPage: 2,
      perMove: 1,
      arrows: false,
      pagination: false,
      gap: "1rem",
      breakpoints: {
        767: { autoplay: true, perPage: 2 },
      },
    },
    videoOptions: {
      type: "loop",
      autoplay: false,
      perPage: 4,
      perMove: 1,
      arrows: false,
      pagination: false,
      gap: "1rem",
      breakpoints: {
        767: { autoplay: true, perPage: 1 },
      },
    },
    csOptions: {
      type: "loop",
      autoplay: true,
      perPage: 1,
      perMove: 1,
      speed: 200,
      arrows: window.innerWidth > 767 ? false : true,
      pagination: false,
      gap: "1rem",
    },
  }),
  computed: {
    experiences() {
      return this.$store.getters.ExperiencesData;
    },
    video() {
      return this.$store.getters.VideoData.slice(0, 8);
    },
    knowledge() {
      let $this = this;
      $.each($this.$store.getters.KnowledgeData, (index, row) => {
        $this.$store.getters.KnowledgeData[
          index
        ].post_content = `${row.post_content
          .replace(/(<([^>]+)>)/gi, "")
          .substr(0, 80)}...`;
      });
      return $this.$store.getters.KnowledgeData.slice(0, 8);
    },
    news() {
      return this.$store.getters.NewsData.slice(0, 8);
    },
  },
  created() {
    this.getServiceData();
    this.getMilestoneData();
    this.getIndexBanner();
    this.getCount();
    $("title").text(`首頁 - inFlux普匯金融科技`);
  },
  mounted() {
    AOS.init();
    window.addEventListener("scroll", this.handleScroll, true);
  },
  watch: {
    "routeIndex.start"() {
      this.routeData = [];
      this.milestone.forEach((item, index) => {
        if (this.routeIndex.start <= index && this.routeIndex.end >= index) {
          this.routeData.push(item);
        }
      });
    },
    news() {
      this.$nextTick(() => {
        this.$refs.news_slick.remount();
      });
    },
    knowledge() {
      this.$nextTick(() => {
        this.$refs.knowledge_slick.remount();
      });
    },
    video() {
      this.$nextTick(() => {
        this.$refs.video_slick.remount();
      });
    },
  },
  methods: {
    updateCalculator(e) {
      console.log(e)
      this.pmt = e.pmt
      switch (e.key) {
        case 0:
          this.period = 3
          break
        case 1:
          this.period = 6
          break
        case 2:
          this.period = 12
          break
        case 3:
          this.period = 18
          break
        case 4:
          this.period = 24
          break
      }
      this.rateCount = e.rateCount
      this.amountCount = e.amountCount
    },
    handleScroll() {
      let gap = window.innerWidth > 767 ? 400 : 300;
      if (
        window.scrollY >= gap &&
        this.tweenedMember === 0 &&
        this.tweenedtransaction === 0
      ) {
        gsap.to(this.$data, { duration: 1, tweenedMember: this.member });
        gsap.to(this.$data, {
          duration: 1,
          tweenedtransaction: this.transaction,
        });
      }

      let gap2 = window.innerWidth > 767 ? 1000 : 750;
      if (
        !this.load2 &&
        window.scrollY >= gap2 &&
        this.knowledge.length === 0 &&
        this.news.length === 0 &&
        this.video.length === 0
      ) {
        this.$store.dispatch("getKnowledgeData");
        this.$store.dispatch("getNewsData");
        this.$store.dispatch("getVideoData", { category: "share" });
        this.load2 = true;
      }

      let gap3 = window.innerWidth > 767 ? 800 : 600;
      if (!this.load3 && window.scrollY >= gap3 && this.experiences.length === 0) {
        this.$store.dispatch("getExperiencesData");
        this.load3 = true;
      }
    },
    format(data) {
      data = parseInt(data);
      if (!isNaN(data)) {
        let l10nEN = new Intl.NumberFormat("en-US");
        return l10nEN.format(data.toFixed(0));
      }
      return 0;
    },
    async getBannerPic() {
      let res = await axios.get(`${location.origin}/getBannerPic`);
      this.bannerPic = res.data;
    },
    async getCount() {
      let res = await axios.get("getCount");

      this.member = res.data[0].memberCount;
      this.transaction = res.data[0].transactionCount;
    },
    getServiceData() {
      axios.post(`${location.origin}/getServiceData`).then((res) => {
        this.services = res.data;
      });
    },
    async getIndexBanner() {
      let res = await axios.post(`${location.origin}/getIndexBanner`);

      this.bannerData = res.data;
    },
    async getMilestoneData() {
      let res = await axios.post(`${location.origin}/getMilestoneData`);

      this.milestone = res.data.reverse();

      this.routeIndex.end = this.milestone.length - 1;
      this.routeIndex.start =
        window.innerWidth > 767 ? this.milestone.length - 6 : this.milestone.length - 4;

      this.milestone.forEach((item, index) => {
        if (this.routeIndex.start <= index && this.routeIndex.end >= index) {
          this.routeData.push(item);
        }
      });
    },
    changeCredit(index) {
      this.count = 0;
      this.creditRatingItem.forEach((item) => {
        if (item.checked) {
          this.count++;
        }
      });
    },
    reSlick() {
      setTimeout(() => {
        $("html").animate({ scrollTop: window.scrollY - 1 });
      }, 300);
    },
    onMoved($event) {
      this.csKey = $event.index;
    },
    pre() {
      if (this.routeIndex.start > 0) {
        this.routeIndex.start--;
        this.routeIndex.end--;
      }
    },
    next() {
      if (this.routeIndex.end < this.milestone.length - 1) {
        this.routeIndex.start++;
        this.routeIndex.end++;
      }
    },
    checkLogin() {
      if(localStorage.getItem("flag") != "login"){
        $("#loginModal").attr("data-type","cardgame");
        $("p.nav-link.l").click();
      }
      else {
        if(!this.process) {
          this.process = true;
          let data = {
            user_id: localStorage.getItem("userData") ? JSON.parse(localStorage.getItem("userData"))["id"] : {},
          };
          axios
              .post("/getData", data)
              .then((res) => {
                this.process = false;
                if (!res.data) {
                  location.replace('/cardgame');
                } else {
                  alert('您已參加過遊戲囉!!');
                }
              })
              .catch((err) => {
                console.error(err);
              });
        }else{
          console.log('duplicate!!');
        }
      }
    },
  },
};
</script>



<style lang="scss">
.alesis-counters {
    margin         : 4rem auto 8rem;
    max-width      : 1280px;

    .wrapper {
      display        : flex;
      justify-content: center;
    }
}

.alesis-our-service {
    .image {
        width    : 100%;
        max-width: 920px;
        margin   : 0 auto 3rem;
        display  : block;
        z-index  : 0;
        position : relative;
    }

    .actions {
        position  : absolute;
        display   : flex;
        left      : 50%;
        transform : translateX(-50%);
        width     : 100%;
        max-width : 1200px;
        margin-top: -4rem;
        z-index   : 1;

        .left,
        .right {
            text-align: center;
            flex      : 1;
        }

        .left {
            margin-right: 4.5rem;
        }

        .right {
            margin-left: 4.5rem;
        }

        .action {
            border-radius: 1rem;
            padding      : 0.3rem 1.5rem;
            font-size    : 1.2rem;
            font-weight  : bolder;
            color        : #112e53;
            border       : 1px solid #1a5fa2;

            &:not(:first-child) {
                margin-left: 1rem;
            }
        }
    }
}

.alesis-company-introduction {
     .carousel {
        margin    : 0 auto 3rem;
        text-align: center;

        .entity {
            display    : inline-flex;
            align-items: center;

            .lightbox {
                .image {
                    max-width: 85%;
                }
            }
        }
    }

    .introduction {
        display  : flex;
        max-width: 1280px;
        margin   : 0 auto;

        .showcase {
            flex      : 1;
            text-align: center;
        }

        .items {
            flex           : 1;
            display        : flex;
            flex-direction : column;
            align-items    : flex-start;
            justify-content: center;

            .item {
                margin-top: 3rem;
            }
        }
    }
}

.alesis-feature {
    .wrapper {
        display        : flex;
        justify-content: center;
        max-width      : 1280px;
        margin         : 0 auto;

        .showcase {
            flex       : 0.75;
            text-align : center;
            padding-top: 4rem;

            .image {
                width: 240px;
            }
        }
        .content {
            flex: 1;

            .bullet {
                margin-left: 2.3rem;
            }
        }

        .moons {
            > .item {
                display: flex;
                margin-top: 3.5rem;

                .moon {
                    margin-right   : 3rem;
                }
                .content {
                    color: #5d5555;
                }
            }
        }
    }

    .slogan {
        text-align : center;
        color      : #f29600;
        font-size  : 2rem;
        font-weight: bolder;
        margin-top : 4rem;
    }
}

.alesis-fast-transfer {
    .wrapper {
        display    : flex;
        margin     : 3rem auto 0;
        align-items: center;
        max-width  : 1000px;

        .graph {
            text-align: center;
            flex      : 1;

            .image {
                width: 590px;
            }
        }

        .moons {
            .moon {
                &:not(:first-child) {
                    margin-top: 5rem;
                }
            }
        }
    }
}

.alesis-solutions {
    .wrapper {
        display              : grid;
        grid-template-columns: repeat(4, 1fr);
        max-width            : 1100px;
        margin               : 3rem auto 0;
        gap                  : 1.5rem;

        .plan {
            padding: 1rem;
            flex   : 1;
        }
    }
}

.alesis-recommend-banks {
    .wrapper {
        display        : flex;
        align-items    : center;
        justify-content: center;
        margin         : 3rem 0;

        .box {
            display      : inline-block;
            border-radius: 17px;
            border       : 1px solid #2664a5;
            padding      : 1.5rem 1rem;
            max-width    : 290px;
            text-align   : justify;

            .logo {
                margin : 0 auto;
                display: block;
            }

            .form {
                display       : flex;
                flex-direction: column;

                .input {
                    appearance   : none;
                    border       : 1px solid #326398;
                    border-radius: .4rem;
                    line-height  : 2.2;
                    padding      : 0 .5rem;
                }

                .button {
                    background   : #326398;
                    width        : 100%;
                    display      : block;
                    color        : #FFF;
                    padding      : .5rem;
                    text-align   : center;
                    border-radius: .4rem;
                }

                > *:not(:last-child) {
                    margin-bottom: 1rem;
                }

                .description {
                    font-size: .8rem;
                }
            }

            .divider {
                height    : 1px;
                background: #2664a5;
                margin    : 1rem 0;
            }

            .list {
                line-height: 2;
                margin-left: 1rem;

                .item {
                    display: list-item;
                }
            }

            .buttons {
                margin-top: 1rem;
                display   : flex;

                > .button {
                    flex         : 1;
                    margin-right : 1rem;

                    & + .button {
                        margin-right: 0;
                    }
                }
            }
        }
    }
}

.alesis-humans {
    .carousel {
        max-width  : 1280px;
        margin     : 2rem auto 0;
    }

    /*.carousel {
        max-width  : 1280px;
        margin     : 2rem auto 0;
        display    : flex;
        align-items: center;

        .left {

        }
        .lightbox {
            flex                 : 1;
            display              : grid;
            grid-template-columns: repeat(2, 1fr);
            gap                  : 2rem;
        }
        .right {

        }
    }*/
}

.alesis-news {
    .header {
        padding-bottom: 5rem;
    }

    .wrapper {
        background-image: linear-gradient(to top, #ffffff -18%, #c9d1d9 115%);
        padding         : .5rem 0;

        .logos {
            display              : grid;
            grid-template-columns: repeat(4, 1fr);
            max-width            : 1280px;
            margin               : 2rem auto;
            gap                  : 1rem;

            &.-three {
                grid-template-columns: repeat(3, 1fr);
            }

            .item {
                height: 60px;
                margin: 0 auto;
            }
        }
    }
}

.alesis-calculator {
    .wrapper {
        display        : flex;
        align-items    : center;
        justify-content: center;
        max-width      : 1280px;
        margin         : 0 auto;

        .calculator {
            flex           : 1;
            display        : flex;
            align-items    : center;
            justify-content: center;
            margin-right   : 7rem;

            > div {
                    width: 600px;
            }
        }

        .inputs {
            flex: 1;

            .row {
                    margin: 0;
                    margin-top: 1rem;

                .text {
                    text-align      : center;
                    background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
                    background-clip : text;
                    width           : fit-content;
                    color           : rgba(255, 255, 255, 0);
                    font-weight     : bolder;
                    font-size       : 2rem;
                    line-height     : 1.2;
                    white-space     : nowrap;
                }

                .input {
                    appearance    : none;
                    border        : 0;
                    border-bottom : 1px solid #2b8bc3;
                    padding       : .25rem 1rem;
                    box-sizing    : border-box;
                    background    : transparent;
                    color         : #f29600;
                    font-size     : 1.6rem;
                    line-height   : 1;
                    width         : 8rem;
                    text-align    : center;
                    outline       : none;
                    pointer-events: none;
                }
            }
        }
    }
}

.index-wrapper {
    width: 100%;


    .banner {
        position: relative;
        overflow: hidden;

        %arrow {
            position : absolute;
            z-index  : 1;
            top      : 50%;
            font-size: 27px;
        }

        .arrow-left {
            @extend %arrow;
            left: 10px;
        }

        .arrow-right {
            @extend %arrow;
            right: 10px;
        }

        .puhey-banner {
            img {
                width: 100%;
            }

            .diagram {
                position: absolute;
                top     : 11%;
                left    : 29%;
                width   : 50%;
            }

            .content {
                position  : absolute;
                top       : 45%;
                left      : 35%;
                transform : translate(-50%, -50%);
                text-align: center;

                p {
                    text-shadow   : 2px 6px 6px rgba(0, 0, 0, 0.75);
                    font-family   : NotoSansTC;
                    font-size     : 40px;
                    letter-spacing: 2.4px;
                    color         : #ffffff;
                }

                span {
                    text-shadow   : 2px 6px 6px rgba(0, 0, 0, 0.75);
                    font-size     : 36px;
                    letter-spacing: 1.8px;
                    color         : #f2e627;
                    text-align    : center;
                }
            }

            .box {
                display   : flex;
                margin-top: 9rem;

                %block {
                    width   : 40%;
                    margin  : 0px auto;
                    position: relative;

                    :hover {
                        color          : #ffffff;
                        text-decoration: none;
                    }

                    .text {
                        color    : #ffffff;
                        position : absolute;
                        top      : 51%;
                        left     : 47%;
                        transform: translate(-50%, -50%);
                        font-size: 25px;
                    }
                }

                .loan {
                    @extend %block;
                }

                .borrow {
                    @extend %block;
                }
            }
        }
    }


    @media screen and (max-width: 767px) {
        .banner {
            .puhey-banner {
                .diagram {
                    position: absolute;
                    top     : 25%;
                    left    : 10px;
                    width   : 65%;
                }

                img {
                    width: 100%;
                }

                .content {
                    font-size: 16px;
                    width    : 100%;
                    left     : 50%;
                    top      : 50%;

                    p {
                        font-size     : 25px;
                        letter-spacing: 0px;
                    }

                    span {
                        font-size: 22px;
                        letter-spacing: 0px;
                    }

                    .box {
                        margin-top: 25rem;

                        %block {
                            width: 170px;

                            .text {
                                font-size: 22px;
                            }
                        }
                    }
                }
            }
        }
    }
}
</style>
