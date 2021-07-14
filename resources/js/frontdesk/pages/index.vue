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
      <SplideSlide class="puhey-banner">
        <img :src="'/images/skBankIndex.jpg'" class="hidden-desktop img-fluid" />
        <img :src="'/images/skBankIndexM.jpg'" class="hidden-phone img-fluid" />
        <div class="content sk-bank-content">
          <div class="sk-bank">
            <a
                class="loan"
                href="/skbank"
                target="_blank"
            ><img src="../asset/images/skbankbuttom1.svg" class="img-fluid" /></a
            >
            <a
                class="borrow"
                href="https://reurl.cc/7r0XQ9"
                target="_blank" style="margin-left:10vw;"
            ><img src="../asset/images/skbankbuttom2.svg" class="img-fluid" /></a
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
    <div class="count-card" ref="count">
      <div class="item">
        <p>會員數</p>
        <div>{{ format(tweenedMember) }}</div>
      </div>
      <div class="lr"></div>
      <div class="item">
        <p>成交筆數</p>
        <div>{{ format(tweenedtransaction) }}</div>
      </div>
      <div class="pu"><img class="img-fluid" src="/images/pu.png" /></div>
    </div>
    <div class="intro-card">
      <div class="t-c"><h2>公司簡介</h2></div>
      <div class="hr"></div>
      <p class="i-t">
        inFlux普匯金融科技以「普惠金融」為志業，「金融專業」為核心，「高端AI科技」為輔助，提供「最有溫度」的金融服務！<br /><br />
        首創台灣「AI風控審核無人化融資系統」，利用高端科技，全程無人為干擾，一支手機完成借貸！<br /><br />
        透過P2P點對點技術，進行大量、小額、分散式借貸投資及債權轉讓，保護借款人資訊，保障投資人權益。<br /><br />
        具備金融專業知識與實務經驗團隊，建立堅強AI自動風控模組，流暢友善UI設計，深受年輕人喜愛，完美呈現AI綠色金融科技體驗環境。
      </p>
    </div>
    <div class="histroy-card">
      <div class="t-c"><h2>歷史沿革</h2></div>
      <div class="hr"></div>
      <div class="h-c">
        <div class="arrows al">
          <div @click="pre()">
            <img src="../asset/images/left-arrow.svg" class="img-fluid" />
          </div>
        </div>
        <routeMap v-if="isDesktop" :routeData="routeData" />
        <routeMapM v-else :routeData="routeData" />
        <div class="arrows ar">
          <div @click="next()">
            <img src="../asset/images/right-arrow.svg" class="img-fluid" />
          </div>
        </div>
      </div>
    </div>
    <div class="p2p-card">
      <div class="t-c"><h2>我們的服務</h2></div>
      <div class="hr"></div>
      <div class="img">
        <img class="img-fluid" src="/images/p2p.svg" />
      </div>
    </div>
    <div class="product-card">
      <div class="box">
        <div class="person">
          <h5>年滿20歲 即可申請個人信貸服務喔！</h5>
          <div class="typ">
            <div class="t">
              <div class="img">
                <img class="img-fluid" src="../asset/images/icon-dot.svg" />
              </div>
              <h3>個人信貸</h3>
            </div>
            <div class="v">
              <div class="b">
                <img class="img-fluid" src="../asset/images/working-from-home.png" />
              </div>
              <div class="c">
                <h4>全線上申請，無人打擾</h4>
                <p>
                  最高額度12-20萬元<br />
                  3-24期，償還期限選擇多元<br />
                  最低利率5％
                </p>
              </div>
            </div>
          </div>
          <Splide class="product" :options="productOptions">
            <SplideSlide class="each">
              <div class="i">
                <img class="img-fluid" src="../asset/images/0_YabILwq8vGX1rj_S.png" />
              </div>
              <div class="ccs">
                <div>
                  學生貸<br />
                  資金小幫手生活超easy
                </div>
                <p>全台大學生與碩博士均可申請</p>
                <router-link to="collegeLoan">了解更多</router-link>
              </div>
            </SplideSlide>
            <SplideSlide class="each">
              <div class="i">
                <img
                  class="img-fluid"
                  src="../asset/images/431fd666-9725-4d0c-beec-defca3ded18c_rw_3840.png"
                />
              </div>
              <div class="ccs">
                <div>
                  上班族貸<br />
                  隨時線上照顧您的需求
                </div>
                <p>年滿20歲的工作人士均可申請</p>
                <router-link to="freshGraduateLoan">了解更多</router-link>
              </div>
            </SplideSlide>
            <SplideSlide class="each">
              <div class="i">
                <img
                  class="img-fluid"
                  src="../asset/images/avel-chuklanov-DUmFLtMeAbQ-unsplash-2-1-.png"
                />
              </div>
              <div class="ccs">
                <div>
                  資訊工程師專案<br />
                  為您的金錢問題debug
                </div>
                <p>資訊相關學生或職場專業人員均可申請</p>
                <router-link to="engineerLoan">了解更多</router-link>
              </div>
            </SplideSlide>
          </Splide>
        </div>
        <div class="group">
          <h5>企業融資服務即將上線</h5>
          <div class="typ">
            <div class="t">
              <div class="img">
                <img class="img-fluid" src="../asset/images/icon-dot.svg" />
              </div>
              <h3>企業融資</h3>
            </div>
            <div class="v">
              <div class="b">
                <img class="img-fluid" src="../asset/images/business-work-place.png" />
              </div>
            </div>
          </div>
          <Splide class="product" :options="productOptions">
            <SplideSlide class="each">
              <div class="i">
                <img class="img-fluid" src="../asset/images/0_YabILwq8vGX1rj_S.png" />
              </div>
              <div class="ccs">
                <div>車輛融資專案</div>
                <p>提供便利資金融通</p>
                <router-link to="">了解更多</router-link>
              </div>
            </SplideSlide>
            <SplideSlide class="each">
              <div class="i">
                <img
                  class="img-fluid"
                  src="../asset/images/avel-chuklanov-DUmFLtMeAbQ-unsplash-2-1-.png"
                />
              </div>
              <div class="ccs">
                <div>新創企業貸</div>
                <p>提供便利資金融通</p>
                <router-link to="">了解更多</router-link>
              </div>
            </SplideSlide>
          </Splide>
        </div>
      </div>
    </div>
    <div class="advantage-card">
      <div class="adv-cnt">
        <div class="header">
          <div class="slg">
            <div data-aos="fade-up" data-aos-duration="700">
              <p class="l-btn">為什麼選擇普匯金融科技?</p>
            </div>
          </div>
        </div>
        <Splide class="content" :options="avgOptions">
          <SplideSlide
            class="item"
            data-aos="fade-up"
            data-delay="900"
            data-aos-duration="500"
          >
            <div class="img">
              <img :src="'/images/expert.svg'" class="img-fluid" />
            </div>
            <p>最專業的金融專家</p>
            <span
              >普匯擁有超過20年金融專業經驗，深度理解各類金融產品、相關金融法規、財稅務、金流管理...等。能針對不同產業產品與市場，設計出更適合用戶需求的金融服務。</span
            >
          </SplideSlide>
          <SplideSlide
            class="item"
            data-aos="fade-up"
            data-delay="900"
            data-aos-duration="500"
          >
            <div class="img">
              <img :src="'/images/ai.svg'" class="img-fluid" />
            </div>
            <p>最先進的AI科技系統</p>
            <span
              >普匯擁有完善的金融科技技術，包含:反詐欺反洗錢系統、競標即時撮合系統、風控信評、線上對保、自動撥貸、貸後管理、分秒計息等，不斷與時俱進迭代優化。</span
            >
          </SplideSlide>
          <SplideSlide
            class="item"
            data-aos="fade-up"
            data-delay="900"
            data-aos-duration="500"
          >
            <div class="img">
              <img :src="'/images/safe.svg'" class="img-fluid" />
            </div>
            <p>簡單、快速、安全、隱私</p>
            <span
              >AWS安全系統為架構，輔以簡潔的操作介面，保護您的個資，讓您在申貸時，安全又放心。</span
            >
          </SplideSlide>
        </Splide>
      </div>
    </div>
    <div class="slogan-card">
      <div class="cnt">
        <div class="cnt-l">
          <template v-if="isDesktop">
            <div :class="['vz', { 'ad-b': csKey === 0 }]">
              全台唯一無人化借貸平台 操作簡單 快速到款
            </div>
            <div :class="['vz', { 'ad-b': csKey === 1 }]">
              申貸過程，無人干擾！<br />普匯．你的手機ATM
            </div>
            <div :class="['vz', { 'ad-b': csKey === 2 }]">
              依照個別身分，提供最適合您的貸款服務
            </div>
            <div :class="['vz', { 'ad-b': csKey === 3 }]">
              5分鐘申貸、10分鐘審核<br />快速1小時媒合放款，絕不耽誤您圓夢的時間
            </div>
          </template>
          <template v-else>
            <div v-if="csKey === 0" class="vz ad-b">
              全台唯一無人化借貸平台 操作簡單 快速到款
            </div>
            <div v-if="csKey === 1" class="vz ad-b">
              申貸過程，無人干擾！<br />普匯．你的手機ATM
            </div>
            <div v-if="csKey === 2" class="vz ad-b">
              依照個別身分，提供最適合您的貸款服務
            </div>
            <div v-if="csKey === 3" class="vz ad-b">
              5分鐘申貸、10分鐘審核<br />快速1小時媒合放款，絕不耽誤您圓夢的時間
            </div>
          </template>
        </div>
        <div class="cnt-r">
          <Splide class="c-s" :options="csOptions" @splide:moved="onMoved($event)">
            <SplideSlide class="c-e">
              <div class="img">
                <img class="img-fluid" src="../asset/images/zxc1.png" />
              </div>
            </SplideSlide>
            <SplideSlide class="c-e">
              <div class="img">
                <img class="img-fluid" src="../asset/images/zxc2.png" />
              </div>
            </SplideSlide>
            <SplideSlide class="c-e">
              <div class="img">
                <img class="img-fluid" src="../asset/images/zxc3.png" />
              </div>
            </SplideSlide>
            <SplideSlide class="c-e">
              <div class="img">
                <img class="img-fluid" src="../asset/images/zxc4.png" />
              </div>
            </SplideSlide>
          </Splide>
          <div class="cover">
            <img class="img-fluid" src="../asset/images/mobile-line.svg" />
          </div>
        </div>
      </div>
    </div>
    <div class="game-card">
      <div class="t-c"><h2>還款試算表</h2></div>
      <div class="hr"></div>
      <h5>分期還款，輕鬆無負擔！</h5>
      <div class="credit-slider">
        <creditBoard amount="200000" />
      </div>
    </div>
    <experience :experiences="experiences" title="用戶回饋" />
    <div class="information-card">
      <div style="z-index: 2; position: relative">
        <ul class="nav" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#news" @click="reSlick()"
              >最新消息</a
            >
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#knowledge" @click="reSlick()"
              >AI金融科技新知</a
            >
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#video" @click="reSlick()"
              >影音專區</a
            >
          </li>
        </ul>
        <div class="tab-content">
          <div id="news" class="tab-pane fade active show">
            <Splide class="news-slick" ref="news_slick" :options="infoOptions">
              <SplideSlide
                class="slick-item hvr-float-shadow"
                v-for="(item, index) in news"
                :key="index"
              >
                <router-link :to="item.link">
                  <div class="img">
                    <img :src="item.image_url" class="img-fluid" />
                  </div>
                  <p>{{ item.post_title }}</p>
                  <span>{{ item.post_date.substr(0, 10) }}</span>
                </router-link>
              </SplideSlide>
            </Splide>
            <router-link class="btn btn-warning btn-to" to="news">
              最新消息&ensp;
            </router-link>
          </div>
          <div id="knowledge" class="tab-pane fade">
            <Splide class="knowledge-slick" ref="knowledge_slick" :options="infoOptions">
              <SplideSlide
                class="slick-item hvr-float-shadow"
                v-for="(item, index) in knowledge"
                :key="index"
              >
                <router-link :to="item.link">
                  <div class="img">
                    <img :src="item.media_link" class="img-fluid" />
                  </div>
                  <p>{{ item.post_title }}</p>
                  <span>{{ item.post_date }}</span>
                </router-link>
              </SplideSlide>
            </Splide>
            <router-link class="btn btn-warning btn-to" to="blog">
              AI金融科技新知&ensp;
            </router-link>
          </div>
          <div id="video" class="tab-pane fade">
            <Splide class="video-slick" ref="video_slick" :options="infoOptions">
              <SplideSlide
                class="slick-item hvr-float-shadow"
                v-for="(item, index) in video"
                :key="index"
              >
                <router-link :to="item.link">
                  <div style="width: fit-content; margin: 0px auto">
                    <iframe
                      :src="item.video_link"
                      frameborder="0"
                      allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                      allowfullscreen
                    ></iframe>
                  </div>
                  <p>{{ item.post_title }}</p>
                  <span>{{ item.post_date }}</span>
                </router-link>
              </SplideSlide>
            </Splide>
            <router-link class="btn btn-warning btn-to" to="vlog/share">
              小學堂影音&ensp;
            </router-link>
          </div>
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
    //banner
    // shanghuiBanner,
  },
  data: () => ({
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
.sk-bank-content {
  left:30% !important;
}
.sk-bank {
  display:flex;
  margin-top:24vw;
  a {
      width:15vw;
  }
}
@media screen and (max-width: 767px) {
    .sk-bank-content {
      left:65vw !important;
    }
    .sk-bank {
      margin-top: -18vw;
        a {
        width:30vw;
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
