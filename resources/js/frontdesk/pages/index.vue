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
    <div class="alesis-counters">
      <div class="wrapper">
        <index-counter image="/images/累積放款金額-01.svg" header="累積註冊用戶" number="60000" unit="人"></index-counter>
        <index-counter image="/images/累積放款金額-01.svg" header="累積放款金額" number="15857" unit="萬"></index-counter>
        <index-counter image="/images/累積放款金額-01.svg" header="累積成交筆數" number="37390" unit="筆"></index-counter>
      </div>
    </div>

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
            <index-bullet class="item" image="/images/累積放款金額-01.svg" header="普匯•你的手機ATM" description="快速申貸、債權投資/轉讓，活用資金，一機搞定"></index-bullet>
            <index-bullet class="item" image="/images/累積放款金額-01.svg" header="“高端AI科技，FinTech專家”" description="普匯不是銀行，我們是FinTech金融科技專家，運用AI智能科技與安全風控模組，全程無人為干擾!"></index-bullet>
          </div>
        </div>
      </index-section>
    </div>

    <div class="alesis-feature">
      <index-section header="產品特色" :secondary="true">
        <div class="wrapper">
            <div class="showcase">
                <img class="image" src="/images/步驟1-01.png" alt="">
            </div>
            <div class="content">
                <index-bullet class="bullet" image="/images/累積放款金額-01.svg" header="“以「普惠金融」為志業”" description="透過P2P、區塊鏈技術，進行大量、小額、分散借貸投資及債權轉讓，深受學生、社會新鮮人、中小企業及眾多投資人喜愛。"></index-bullet>
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

    <div class="alesis-fast-transfer">
      <index-section header="普匯債權快速變現" multiline="全台唯一債權轉讓功能">
          <div class="wrapper">
              <div class="graph">
                  <img class="image" src="/images/sshot-1569.png" alt="">
              </div>
              <div class="moons">
                    <index-moon class="moon" header="債權轉讓" multiline="快速變現" level="3"></index-moon>
                    <index-moon class="moon" header="小額資金" multiline="分散風險" level="2"></index-moon>
                    <index-moon class="moon" header="智能投資" multiline="自動下標" level="1"></index-moon>
              </div>
          </div>
      </index-section>
    </div>

    <div class="alesis-solutions">
      <index-section header="產品方案" :secondary="true">
          <div class="wrapper">
              <index-plan class="plan" :header="item.header" :targets="item.targets" :action="item.action" :features="item.features" :unready="item.unready" v-for="(item, index) in plans" :key="index"></index-plan>
          </div>
      </index-section>
    </div>

    <div class="alesis-recommend-banks">
      <index-section header="普匯推薦 銀行速貸好條件" multiline="透過普匯結盟夥伴，可享最高額度300萬，超低利率！">
          <div class="wrapper">
                <div class="box">
                    <img class="logo" src="/images/shanghai.png" alt="">
                    <div class="divider"></div>
                    <div class="list">
                        <div class="item">1日快速核准</div>
                        <div class="item">前3個月利率1.88%起</div>
                        <div class="item">額度最高達300萬，期間最長7年</div>
                    </div>
                    <div class="buttons">
                        <a class="button" href="#!">了解更多</a>
                        <a class="button" href="#!">取得資格</a>
                    </div>
                </div>
          </div>
      </index-section>
    </div>

    <div class="alesis-solutions">
      <index-section header="分期還款沒壓力" :secondary="true">
          <p>&nbsp;</p>
          <p>&nbsp;</p>
        <div class="credit-slider">
            <creditBoard amount="200000" />
        </div>
        <p>&nbsp;</p>
        <p>&nbsp;</p>
      </index-section>
    </div>

    <!--<div class="game-card">
      <div class="t-c"><h2>還款試算表</h2></div>
      <div class="hr"></div>
      <h5>分期還款，輕鬆無負擔！</h5>
      <div class="credit-slider">
        <creditBoard amount="200000" />
      </div>
    </div>-->

    <div class="alesis-humans">
      <index-section header="看看他們怎麼說">
          <div class="carousel">
                <div class="left">
                    <img src="/images/sshot-1559.png" alt="">
                </div>
                <div class="lightbox">
                    <index-human></index-human>
                    <index-human></index-human>
                </div>
                <div class="right">
                    <img src="/images/sshot-1558.png" alt="">
                </div>
          </div>
      </index-section>
    </div>

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

    <float />
  </div>
</template>

<script>
import { Splide, SplideSlide } from "@splidejs/vue-splide";
//
import investUp from "../component/svg/investDotUpComponent";
import investDown from "../component/svg/investDotDownComponent";
import amountChart from "../component/svg/amountChartComponent";
import creditBoard from "../component/svg/creditBoardComponent";
import histroyDot from "../component/svg/histroyDotComponent";
import experience from "../component/experienceComponent";
import float from "../component/floatComponent";
//svg
import routeMap from "../component/svg/routeMapComponent";
import routeMapM from "../component/svg/routeMapMComponent.vue";
//
import indexCounter from "../component/alesis/index/counter";
import indexSection from "../component/alesis/index/section";
import indexBullet from "../component/alesis/index/bullet";
import indexMoon from "../component/alesis/index/moon";
import indexPlan from "../component/alesis/index/plan";
import indexHuman from "../component/alesis/index/human";

//banner
// import shanghuiBanner from "../component/banner/shanghuiBanner";

export default {
  components: {
    float,
    investUp,
    investDown,
    amountChart,
    histroyDot,
    experience,
    creditBoard,
    Splide,
    SplideSlide,
    routeMap,
    routeMapM,

    //
    indexCounter,
    indexSection,
    indexBullet,
    indexMoon,
    indexPlan,
    indexHuman

    //banner
    // shanghuiBanner,
  },
  data: () => ({

    plans: [
        {
            header: "學生貸",
            unready: false,
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
            targets: [],
            action: "敬請期待",
            features: []
        }
    ],


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
                width: 640px;
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

.r-count-card {
    display: flex;
    justify-content: center;
    margin: 5rem auto;
    max-width: 1280px;
}

.r-count-card__item {
  flex: 1;
  text-align: center;
}

.r-count-card__item__wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
}

.r-count-card__item__symbol {
    margin-right: 1rem;
}

.r-count-card__item__content {
    display: flex;
    align-items: flex-start;
    flex-direction: column;
    justify-content: center;
}

.r-count-card__item__content__header {
    font-size: 1.2rem;
    font-weight: bold;
    color: #353482;
}

.r-count-card__item__content__counter {
    font-size: 1.2rem;
}

.r-count-card__item__content__counter__number {
      color: #646464;
}

.r-count-card__item__content__counter__unit {
    font-size: 1.2rem;
    font-weight: bold;
    color: #353482;
}


.r-introduction {
      max-width: 1280px;
    margin: 0 auto;
}

.r-introduction__header {
  position: relative;
  background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
    -webkit-background-clip: text;
    background-clip: text;
    width: -webkit-fit-content;
    width: -moz-fit-content;
    width: fit-content;
    color: #ffffff00;
    margin: 0px auto;
    font-weight: bolder;
    font-size: 2rem;
    line-height: 1.2;
}

.r-introduction__summary {
  display: flex;
}

.r-introduction__summary__demo {
  flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 9rem;
}

.r-introduction__summary__demo__image {

}

.r-introduction__summary__items {

}

.r-introduction__summary__items__item {
      display: flex;
    align-items: center;
    margin-top: 4rem;
}

.r-introduction__summary__items__item__image {
  margin-right: 2rem;
}

.r-introduction__summary__items__item__content {

}

.r-introduction__summary__items__item__content__header {
      font-size: 1.2rem;
    font-weight: bold;
    color: #353482;
}

.r-introduction__summary__items__item__content__description {
      font-size: 1.2rem;
    color: #646464;
}

.r-features {

}

.r-features__header {

}

.r-features__main {
  display: flex;
    max-width: 880px;
    margin: 0 auto;
}

.r-features__main__demo {

}

.r-features__main__demo__image {

}

.r-features__main__content {
  margin-left: 6rem;
}

.r-features__main__content__featured {
      display: flex;
    align-items: center;
}

.r-features__main__content__featured__image {
  margin-right: 2rem;
}

.r-features__main__content__featured__content {

}

.r-features__main__content__featured__content__header {
  font-size: 1.2rem;
    font-weight: bold;
    color: #353482;
}

.r-features__main__content__featured__content__description {
  font-size: 1.1rem;
    color: #646464;
}

.r-features__main__content__item {
    display: flex;
    margin-top: 3rem;
}

.r-features__main__content__item:nth-child(3) .r-features__main__content__item__line {
  width: 35px;
}

.r-features__main__content__item:nth-child(4) .r-features__main__content__item__line {
  width: 20px;
}

.r-features__main__content__item:nth-child(5) .r-features__main__content__item__line {
  width: 0px;
}

.r-features__main__content__item__symbol {
  display: flex;
  align-items: center;
  position: relative;
  margin-right: 3rem;
  justify-content: flex-end;
  min-width: 130px;
}

.r-features__main__content__item__line {
    height: 1px;
    width: 60px;
    margin-right: -16px;
    background: #000;
}

.r-features__main__content__item__circle {
    width: 60px;
    height: 30px;
    border-top-left-radius: 113px;
    border-top-right-radius: 110px;
    border: 3px solid black;
    transform: rotate(270deg);
    border-bottom: 0;
    left: 24px;
}

.r-features__main__content__item__text {
  margin-left: -16px;
  font-weight: bold;
  font-size: 1.2rem;
}

.r-features__main__content__item__content {
  font-size: 1.1rem;
    color: #646464;
}

.r-features__norest {
      text-align: center;
    color: #9aa313;
    font-weight: bolder;
    font-size: 1.68em;
    margin: 2rem 0 6rem;
}

.r-section-header {
  position: relative;
}

.r-section-header__wrapper {
  background: #FFF;
  position: relative;
  z-index: 1;
}

.r-section-header__text {
    position: relative;
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
    -webkit-background-clip: text;
    background-clip: text;
    width: -webkit-fit-content;
    width: -moz-fit-content;
    width: fit-content;
    color: #ffffff00;
    margin: 0px auto;
    font-weight: bolder;
    font-size: 2rem;
    line-height: 1.2;
}

.r-section-header__dots {
   position: absolute;
    left: 50%;
    top: 50%;
    z-index: 0;
}

.r-section-header__dots__wrapper {
   position: absolute;
    transform: translate(-50%, -50%);
}

.r-section-header__dots__dot {
    width: 8px;
    height: 8px;
    background: #3670d3;
    border-radius: 100rem;
    margin-bottom: .7rem;

    &:nth-child(1) {
      opacity: 0.2;
      width: 4px;
      height: 4px;
    }
    &:nth-child(2) {
      opacity: 0.4;
      width: 6px;
      height: 6px;
    }
    &:nth-child(3) {
      opacity: 0.6;
    }
    &:nth-child(4) {
      opacity: 0.75;
    }
    &:nth-child(5) {
      opacity: 0.75;
    }
    &:nth-child(6) {
      opacity: 0.6;
    }
    &:nth-child(7) {
      opacity: 0.4;
      width: 6px;
      height: 6px;
    }
    &:nth-child(8) {
      opacity: 0.2;
      width: 4px;
      height: 4px;
    }
}

.r-solutions {

}

.r-solutions__items {
  display: flex;
  margin: 8rem auto;
  justify-content: center;
  max-width: 1280px;
}

.r-solutions__items__item {
  padding: 1rem;
  flex: 1;
}

.r-solutions__items__item__box {
      padding: 1rem;
    border: 1px solid #000;
    border-radius: 24px;
    position: relative;
        height: 100%;
    display: flex;
    flex-direction: column;
}

.r-solutions__items__item__box__image {
      position: absolute;
    top: 0;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    background: #fff;
    border-radius: 3rem;
}

.r-solutions__items__item__box__feature {
  display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 3rem;
}

.r-solutions__items__item__box__feature__header {
  font-size: 1.2rem;
    font-weight: bold;
    color: #353482;
}

.r-solutions__items__item__box__feature__list {
  font-size: .9rem;
  min-height: 43px;
}

.r-solutions__items__item__box__feature__list__item {

}

.r-solutions__items__item__box__list {
      border-top: 1px solid #000;
    padding-top: 1rem;
    margin-top: 1rem;
        font-size: .9rem;
    line-height: 2;
    margin-bottom: 1.5rem;
}

.r-solutions__items__item__box__list__item {

}

.r-solutions__items__item__box__start {
      display: block;
    width: 100%;
    text-align: center;
    font-size: 1.2rem;
    font-weight: bold;
    color: #353482;
    border-radius: .4rem;
    padding: .5rem 0;
    border: 1px solid #000;
    margin-top: auto;
}

.r-recommended__content {

}

.r-recommended__content__box {

}

.r-recommended__content__box__logo {

}

.r-recommended__content__box__divider {

}

.r-recommended__content__box__list {

}

.r-recommended__content__box__list__item {

}

.r-recommended__content__buttons {

}

.r-recommended__content__buttons__button {

}

.r-recommended__content__buttons__button + .r-recommended__content__buttons__button {

}

.alesis-recommend-banks {
    .wrapper {
        display        : flex;
        align-items    : center;
        justify-content: center;
        margin         : 10rem 0;

        .box {
            display      : inline-block;
            border-radius: 17px;
            border       : 1px solid #2664a5;
            padding      : 1.5rem 1rem;

            .logo {

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
                margin-top: 2rem;
                display   : flex;

                .button {
                    display      : block;
                    flex         : 1;
                    text-align   : center;
                    padding      : .1rem 1rem;
                    border       : 1px solid #2664a5;
                    border-radius: .4rem;
                    width        : 100%;
                    font-size    : 1.2rem;
                    font-weight  : bold;
                    color        : #036eb7;
                    margin-right : 1rem;
                    line-height  : 1.4;

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
    }
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

.index-wrapper {
  width: 100%;

  .t-c {
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
    background-clip: text;
    width: fit-content;
    color: #ffffff00;
    margin: 0px auto;
    position: relative;

    h2 {
      font-weight: bolder;
    }
  }

  .hr {
    width: 260px;
    height: 1px;
    background-image: linear-gradient(to right, #0559ac, #ffffff);
    margin: 0px auto;
  }

  .banner {
    position: relative;
    overflow: hidden;

    %arrow {
      position: absolute;
      z-index: 1;
      top: 50%;
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
        top: 11%;
        left: 29%;
        width: 50%;
      }

      .content {
        position: absolute;
        top: 45%;
        left: 35%;
        transform: translate(-50%, -50%);
        text-align: center;

        p {
          text-shadow: 2px 6px 6px rgba(0, 0, 0, 0.75);
          font-family: NotoSansTC;
          font-size: 40px;
          letter-spacing: 2.4px;
          color: #ffffff;
        }

        span {
          text-shadow: 2px 6px 6px rgba(0, 0, 0, 0.75);
          font-size: 36px;
          letter-spacing: 1.8px;
          color: #f2e627;
          text-align: center;
        }
      }

      .box {
        display: flex;
        margin-top: 9rem;

        %block {
          width: 40%;
          margin: 0px auto;
          position: relative;

          :hover {
            color: #ffffff;
            text-decoration: none;
          }

          .text {
            color: #ffffff;
            position: absolute;
            top: 51%;
            left: 47%;
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

  .count-card {
    display: flex;
    justify-content: center;
    padding-bottom: 30px;
    padding-top: 15px;
    position: relative;
    overflow: hidden;

    .item {
      padding: 0px 10px;
      background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
      background-clip: text;
      width: fit-content;
      color: #ffffff00;

      p {
        margin-bottom: 5px;
        font-size: 24px;
      }

      div {
        font-size: 45px;
        font-weight: bold;
        color: #3b5795;
      }
    }

    .pu {
      width: 116px;
      position: absolute;
      right: 20%;
    }

    .lr {
      border-left: 2px solid #080080;
      margin: 0px 1rem;
    }
  }

  .intro-card {
    padding: 30px 0px;
    background-color: #ecedf1;

    .i-t {
      font-size: 18px;
      font-weight: bold;
      letter-spacing: normal;
      text-align: center;
      color: #1c2a54;
      width: fit-content;
      margin: 1.5rem auto;
    }
  }

  .p2p-card {
    background-color: #ecedf180;
    padding: 30px 0px;

    .img {
      width: 80%;
      margin: 1rem auto;
    }
  }

  .histroy-card {
    padding: 40px 30px;
    background-color: #ecedf1;

    .h-c {
      display: flex;

      .arrows {
        position: relative;
        width: 100px;
        z-index: 1;

        div {
          width: 100px;
          position: absolute;
          top: 50%;
          transform: translate(0px, -50%);
          cursor: pointer;
        }
      }
    }
  }

  .product-card {
    margin-bottom: 4rem;
    .title {
      background-image: linear-gradient(90deg, #153a71 50%, #ffffff 50%);
      padding: 2rem 0px;

      .t-c {
        background-image: linear-gradient(to right, #306fca 0%, #09d4f6 50%, #306fca 75%);
      }
    }

    .box {
      display: flex;
      .person {
        background: #153a71;
        padding: 30px 0px;
        width: 50%;

        .product {
          width: 90%;
        }
      }

      .group {
        background: #182461;
        padding: 30px 0px;
        width: 50%;

        .product {
          width: 90%;
        }
      }

      h5 {
        font-size: 25px;
        font-weight: 600;
        line-height: 1.47;
        text-align: center;
        color: #ffc236;
        margin: 2rem auto;
      }

      .typ {
        border-radius: 20px;
        box-shadow: 2px 4px 10px 0 rgba(0, 0, 0, 0.3);
        background-color: #ffffff;
        width: fit-content;
        margin: 0px auto;
        padding: 20px;

        .t {
          display: flex;
          width: fit-content;
          margin: 0px auto;

          h3 {
            font-weight: 900;
            line-height: 1.67;
            color: #1f55a0;
            margin: 0px;
          }

          .img {
            width: 38px;
            line-height: 46px;
            margin-right: 5px;
          }
        }

        .v {
          position: relative;
          margin-top: 10px;

          .b {
            width: 400px;
          }

          .c {
            position: absolute;
            top: 15px;
            left: 15px;

            h4 {
              text-shadow: 0 3px 6px #000000;
              font-size: 26px;
              text-align: center;
              color: #ffc236;
              font-weight: bold;
            }

            p {
              text-shadow: 0 3px 6px #000000;
              font-size: 18px;
              line-height: 1.64;
              color: #ffffff;
              margin: 0px;
            }
          }
        }
      }

      .product {
        margin: 20px auto;

        .splide__list {
          padding: 10px !important;
        }

        .each {
          display: flex;
          border-radius: 20px;
          box-shadow: 0 3px 3px 0 rgba(0, 0, 0, 0.3);
          background-color: #ffffff;
          padding: 10px;
          height: 140px;

          .i {
            width: 30%;
            margin-right: 10px;
          }

          .ccs {
            width: 70%;
            position: relative;

            div {
              font-size: 18px;
              font-weight: bold;
              line-height: 1.21;
              color: #000000;
            }

            p {
              font-size: 16px;
              color: #5d5555;
              margin: 0px;
            }

            a {
              padding: 3px 12px;
              border-radius: 20px;
              border: solid 1px #0fd9fb;
              background-color: #1f55a0;
              text-shadow: 0 3px 6px #000000;
              font-size: 15px;
              color: #ffffff;
              position: absolute;
              bottom: 0px;
              right: 0px;

              &:hover {
                text-decoration: none;
              }
            }
          }
        }
      }
    }
  }

  .slogan-card {
    padding: 40px 30px;
    background-color: #ecedf1;

    .cnt {
      display: flex;
      width: 90%;
      margin: 4rem auto;

      .cnt-l {
        display: flex;
        flex-direction: column;
        width: 60%;
        margin-right: 1rem;

        .vz {
          margin: 15px 0px;
          font-size: 22px;
          font-weight: 700;
          line-height: 1.45;
          text-align: left;
          color: #a5a5a5;
          width: fit-content;
          padding: 10px;
          transition-duration: 0.2s;
        }

        .ad-b {
          color: #153a71;
        }
      }

      .cnt-r {
        width: 40%;
        position: relative;

        .cover {
          width: 300px;
          position: relative;
          pointer-events: none;
        }

        .c-s {
          width: 300px;
          padding: 13px 18px;
          position: absolute;
          top: 0;
          left: 0;

          .splide__track {
            border-radius: 28px;
          }

          .c-e {
            overflow: hidden;
            border-radius: 28px;

            s.img {
              overflow: hidden;
              border-radius: 15px;
            }
          }
        }
      }
    }
  }

  .game-card {
    overflow: hidden;
    padding: 40px 30px;
    background-color: #153a71;

    h5 {
      font-size: 32px;
      font-weight: 600;
      line-height: 1.47;
      text-align: center;
      color: #ffc236;
    }

    .t-c {
      background-image: linear-gradient(to right, #306fca 0%, #09d4f6 50%, #306fca 75%);
    }

    .credit-slider {
      width: 800px;
      margin: 0px auto;
    }
  }

  #bg-card {
    background: #f5f5f5;
    position: relative;
    z-index: 1;

    .particles-js-canvas-el {
      position: absolute;
      top: 0;
      z-index: -1;
    }
  }

  .advantage-card {
    background-image: linear-gradient(to bottom, #ffffff 65%, #ecedf1 35%);

    .adv-cnt {
      background-image: url("../asset/images/91.png");
      background-repeat: no-repeat;
      background-size: cover;
      width: 90%;
      margin: 0px auto;

      .header {
        font-size: 17px;
        font-weight: bold;
        overflow: hidden;
        min-height: 350px;
        position: relative;

        .slg {
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);

          .l-btn {
            color: #ffc236;
            font-size: 48px;
            font-weight: bold;
            width: 100%;
            margin: 0px auto;
            text-align: center;
          }
        }
      }

      .content {
        padding: 4.5rem 1.5rem 1.5rem 1.5rem;
        overflow: hidden;

        .splide__track {
          overflow: unset;
        }

        .item {
          padding: 3rem;
          border-radius: 20px;
          background-color: #153a71b8;

          .img {
            margin: -100px auto 20px auto;
            border-radius: 20px;
            background-color: #a9c0dc;
            width: 110px;
            padding: 10px;
            text-align: center;

            img {
              height: 90px;
            }
          }

          p {
            color: #ffc236;
            font-size: 26px;
            text-align: center;
            font-weight: 700;
            word-break: keep-all;
          }

          span {
            color: #ffffff;
          }
        }
      }
    }
  }

  .information-card {
    overflow: hidden;
    background: linear-gradient(to bottom, #ffffff 65%, #153a71 35%);
    position: relative;

    .nav {
      margin: 0px auto;
      .nav-item {
        width: calc(100% / 3);
        text-align: center;
        font-weight: bolder;

        .nav-link {
          width: 100%;
          position: relative;
          background: #f6f6f6;
          color: #153a71;
          padding: 15px 0px;
          cursor: pointer;

          &.active {
            background: #153a71;
            color: #ffffff;
            border: 0px;

            &:after {
              content: "";
              height: 0;
              width: 0;
              border-top: 15px solid #153a71;
              border-left: 15px solid #ffffff00;
              border-right: 15px solid #ffffff00;
              position: absolute;
              bottom: -15px;
              left: 50%;
              transform: translate(-50%, 0);
            }
          }
        }
      }
    }

    .tab-content {
      margin: 20px;
      text-align: center;

      .splide__track {
        width: 90%;
        margin: 0px auto;
      }

      .news-slick,
      .knowledge-slick,
      .video-slick {
        margin: 25px auto;
        position: relative;
      }

      .btn-to {
        font-weight: bold;
        width: 30%;
      }

      .slick-item.is-visible {
        transform: scale(0.9);
      }

      .slick-item.is-visible + .slick-item.is-visible {
        transform: scale(1);
      }

      .slick-item.is-visible + .slick-item.is-visible + .slick-item.is-visible {
        transform: scale(0.9);
      }

      .slick-item {
        padding: 10px;
        text-align: initial;
        font-size: 16px;
        font-weight: bolder;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #ffffff;
        min-height: 274px;
        transform: scale(0.9);

        .img {
          width: 100%;
          height: 200px;
          margin: 0px auto;
          overflow: hidden;

          img {
            width: 100%;
          }
        }

        p {
          text-align: center;
          color: #083a6e;
          font-size: 16px;
          margin: 0.5rem 0px;
          pointer-events: none;
        }

        span {
          float: right;
          color: #8c8c8c;
          font-weight: 100;
          font-size: 13px;
        }

        &:hover {
          text-decoration: none;
        }

        iframe {
          width: 100%;
          height: 180px;
        }
      }
    }
  }

  .comfirm-modal {
    .modal-dialog {
      max-width: 290px !important;
    }
    h4 {
      text-align: center;
      font-weight: bolder;
      font-size: 20px;
      margin: 0px;
      color: #8c8c8c;
    }

    .flex {
      display: flex;

      .i {
        padding: 10px;
        margin: 5px;
        background: #c4dcff73;

        &:hover {
          text-decoration: none;
        }

        p {
          text-align: center;
          margin: 0px;
          font-weight: bold;
          font-size: 20px;
        }
      }
    }
  }

  @media screen and (max-width: 767px) {
    .banner {
      .puhey-banner {
        .diagram {
          position: absolute;
          top: 25%;
          left: 10px;
          width: 65%;
        }

        img {
          width: 100%;
        }

        .content {
          font-size: 16px;
          width: 100%;
          left: 50%;
          top: 50%;

          p {
            font-size: 25px;
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

    .count-card {
      padding: 15px 0px;
      justify-content: flex-start;

      .item {
        div {
          font-size: 36px;
          color: #3b5795;
        }
      }

      .pu {
        width: 70px;
        left: 19rem;
        z-index: -1;
      }
    }

    .intro-card {
      padding: 10px;

      .i-t {
        text-align: justify;
      }
    }

    .histroy-card {
      padding: 10px;

      .h-c {
        width: 100%;
        padding: 10px 0px;
        flex-direction: column;

        .al {
          div {
            right: 0px;
            transform: translate(0px, 50%) rotate(90deg);
          }
        }

        .ar {
          div {
            left: 0px;
            transform: translate(0px, -100%) rotate(90deg);
          }
        }

        .arrows {
          width: 100%;

          div {
            width: 70px;
          }
        }
      }
    }

    .p2p-card {
      padding: 10px 0px;

      .img {
        width: 100%;
        margin: 1rem auto;
      }
    }

    .product-card {
      margin: 0px;

      .box {
        flex-direction: column;

        h5 {
          font-size: 20px;
          margin: 1rem auto;
        }
        .typ {
          .v {
            .b {
              width: 100%;
            }
          }
        }

        .person {
          padding: 10px;
          width: 100%;

          .product {
            width: 100%;
            margin: 10px auto;

            .splide__list {
              padding: 10px 0px !important;
            }
          }
        }

        .group {
          padding: 10px;
          width: 100%;

          .product {
            width: 100%;
            margin: 10px auto;

            .splide__list {
              padding: 10px 0px !important;
            }
          }
        }
      }
    }

    .advantage-card {
      .adv-cnt {
        width: 95%;

        .header {
          margin: 10px;
          min-height: 80px;

          .slg {
            margin: 0px;
            width: 100%;

            .l-btn {
              font-size: 27px;
            }
          }
        }

        .content {
          padding: 0.5rem;

          .item {
            padding: 1rem;

            .img {
              margin: 0px auto;
              width: 75px;

              img {
                height: 50px;
              }
            }
          }
        }
      }
    }

    .slogan-card {
      padding: 20px 10px;

      .cnt {
        margin: 0px auto;
        width: 100%;
        flex-direction: column;

        .cnt-l {
          width: 100%;
          height: 70px;

          h3 {
            margin: 0px auto;
          }

          .vz {
            margin: 0px;
            font-size: 17px;
            width: 100%;
          }

          .ad-b {
            display: block;
            height: 70px;
          }
        }

        .cnt-r {
          width: 80%;
          margin: 0px auto;

          .c-s {
            width: 100%;

            .splide__arrow--prev {
              left: -2.5em;
            }

            .splide__arrow--next {
              right: -2.5em;
            }
          }

          .cover {
            width: 100%;
          }
        }
      }
    }

    .game-card {
      padding: 10px 0px;

      h5 {
        font-size: 20px;
        margin: 10px auto;
      }

      .credit-slider {
        width: 100%;
      }
    }

    .information-card {
      .nav {
        width: 100%;
      }

      .tab-content {
        .splide__arrow--prev {
          left: -1rem;
        }

        .splide__arrow--next {
          right: -1rem;
        }

        .slick-item {
          transform: scale(1);
        }

        .slick-item.is-visible {
          transform: scale(1);
        }

        .slick-item.is-visible + .slick-item.is-visible {
          transform: scale(1);
        }

        .slick-item.is-visible + .slick-item.is-visible + .slick-item.is-visible {
          transform: scale(1);
        }
      }
    }
  }
}
</style>
