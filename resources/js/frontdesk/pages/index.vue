<template>
  <div class="index-wrapper">
    <Splide class="banner" :options="bannerOptions">
      <SplideSlide>
        <div class="puhey-banner">
          <img
            :src="'/images/index-banner-d.png'"
            class="hidden-desktop img-fluid"
          />
          <img
            :src="'/images/index-banner-m.png'"
            class="hidden-phone img-fluid"
          />
          <img :src="'/images/diagram-d.svg'" class="diagram hidden-desktop" />
          <img :src="'/images/diagram-m.svg'" class="diagram hidden-phone" />
          <div class="content">
            <p>最貼近年輕人的金融科技平台</p>
            <span>普匯，你的手機ATM</span>
            <div class="box">
              <a
                class="loan"
                href="https://event.influxfin.com/r/iurl?p=webinvest"
                target="_blank"
                ><img src="../asset/images/light-b.svg" class="img-fluid" />
                <div class="text">立即投資</div></a
              >
              <a
                class="borrow"
                href="https://event.influxfin.com/R/url?p=webbanner"
                target="_blank"
                ><img src="../asset/images/light-y.svg" class="img-fluid" />
                <div class="text">立即借款</div></a
              >
            </div>
          </div>
        </div></SplideSlide
      >
      <SplideSlide>
        <shanghuiBanner />
      </SplideSlide>
    </Splide>
    <div class="intro-card">
      <div class="t-c"><h2>公司簡介</h2></div>
      <div class="hr"></div>
      <p class="i-t">
        普匯以P2P借貸平台為主體，運用科技技術導入小額借貸服務，<br />
        撮合借貸雙方普匯發展獨特「AＩ審核無人化融資系統」，經營團隊擁有近二十年兩岸金融經驗，<br />
        平台長期接受「金管會」監理沙盒輔導，並經「金融總會」評鑑為市值破億的新創公司，<br />
        現今已是全台最受年輕人信賴的線上借貸平台。
      </p>
    </div>
    <div class="histroy-card">
      <div class="t-c"><h2>歷史沿革</h2></div>
      <div class="hr"></div>
      <div class="h-c">
        <div>
          <histroyDot
            :content="{
              year1: '2017',
              date1: '12.08',
              title1: '公司成立',
              desc1: '普匯金融科技股份有限公司',
              year2: '2017',
              date2: '12.08',
              title2: '公司成立',
              desc2: '普匯金融科技股份有限公司',
            }"
          />
        </div>
        <div>
          <histroyDot
            :content="{
              year1: '2017',
              date1: '12.08',
              title1: '公司成立',
              desc1: '普匯金融科技股份有限公司',
              year2: '2017',
              date2: '12.08',
              title2: '公司成立',
              desc2: '普匯金融科技股份有限公司',
            }"
          />
        </div>
        <div>
          <histroyDot
            :content="{
              year1: '2017',
              date1: '12.08',
              title1: '公司成立',
              desc1: '普匯金融科技股份有限公司',
              year2: '2017',
              date2: '12.08',
              title2: '公司成立',
              desc2: '普匯金融科技股份有限公司',
            }"
          />
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
      <div class="person">
        <div class="t-c"><h2>產品介紹</h2></div>
        <div class="hr"></div>
        <h5>年滿25～30歲的您 均可申請個人信貸服務喔！</h5>
        <div class="typ">
          <div class="t">
            <div class="img">
              <img class="img-fluid" src="../asset/images/icon-dot.svg" />
            </div>
            <h3>個人信貸</h3>
          </div>
          <div class="v">
            <div class="b">
              <img
                class="img-fluid"
                src="../asset/images/working-from-home.png"
              />
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
        <Splide class="product" :options="personOptions">
          <SplideSlide class="each">
            <div class="i">
              <img
                class="img-fluid"
                src="../asset/images/0_YabILwq8vGX1rj_S.png"
              />
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
        </Splide>
      </div>
      <div class="group">
        <h5>企業款服務即將上線</h5>
        <div class="typ">
          <div class="t">
            <div class="img">
              <img class="img-fluid" src="../asset/images/icon-dot.svg" />
            </div>
            <h3>企業信貸</h3>
          </div>
          <div class="v">
            <div class="b">
              <img
                class="img-fluid"
                src="../asset/images/business-work-place.png"
              />
            </div>
          </div>
        </div>
        <Splide class="product" :options="groupOptions">
          <SplideSlide class="each">
            <div class="i">
              <img
                class="img-fluid"
                src="../asset/images/0_YabILwq8vGX1rj_S.png"
              />
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
    <div class="advantage-card">
      <div class="adv-cnt">
        <div class="header">
          <div class="slg">
            <div data-aos="fade-up" data-aos-duration="700">
              <p class="title">
                以金融為核心，以科技為輔具，
                <br />普匯給您前所未有的最佳使用體驗APP
              </p>
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
              >普匯擁有完善的金融科技技術，包含:
              反詐欺反洗錢系統、競標即時撮合系統、
              風控信評/線上對保、自動撥貸/貸後管理、
              分秒計息等，不斷與時俱進迭代優化。</span
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
              >視覺化簡潔好用的操作介面，運用先進科技與AWS
              安全系統，保護您的個資絕不外洩，讓您在步入圓夢捷徑的同時，安全又放心。</span
            >
          </SplideSlide>
        </Splide>
      </div>
    </div>
    <div class="slogan-card">
      <div class="cnt">
        <div class="cnt-l">
          <h3>普匯金融科技</h3>
          <div>全台唯一無人化借貸平台 操作簡單 快速到款</div>
          <div>只要3分鐘，就能擁有自己的手機ATM</div>
          <div>依照個別身分，提供最適合您的貸款服務</div>
          <div>
            快速拿到您的款項，隨到隨審<br />最快1小時媒合完成，絕不耽誤您圓夢的時間
          </div>
        </div>
        <div class="cnt-r">
          <Splide
            class="c-s"
            :options="csOptions"
            @splide:moved="onMoved($event)"
          >
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
      <div class="t-c"><h2>試算您可借額度</h2></div>
      <div class="hr"></div>
      <h5>分期還款，輕鬆無負擔！</h5>
      <div class="cnt">
        <div class="amount-chart"><amountChart :count="count" /></div>
        <div class="aa-as">
          <div class="option">
            <div
              class="item"
              data-aos="fade-down"
              :data-aos-delay="100 * index"
              v-for="(item, index) in creditRatingItem"
              :key="index"
            >
              <div
                :class="['circle', { gary: !item.checked }]"
                @click="
                  item.checked = !item.checked;
                  changeCredit(index);
                "
              >
                <img :src="item.img" class="img-fluid" />
              </div>
              <span>{{ item.text }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <experience :experiences="experiences" title="用戶回饋" />
    <div class="information-card">
      <div style="z-index: 2; position: relative">
        <ul class="nav" role="tablist">
          <li class="nav-item">
            <a
              class="nav-link active"
              data-toggle="tab"
              href="#news"
              @click="reSlick('news_slick')"
              >最新消息</a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              data-toggle="tab"
              href="#knowledge"
              @click="reSlick('knowledge_slick')"
              >AI金融科技新知</a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              data-toggle="tab"
              href="#video"
              @click="reSlick('video_slick')"
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
            <Splide
              class="knowledge-slick"
              ref="knowledge_slick"
              :options="infoOptions"
            >
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
            <Splide
              class="video-slick"
              ref="video_slick"
              :options="infoOptions"
            >
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
    <div
      class="comfirm-modal modal fade"
      ref="comfirmModal"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" data-dismiss="modal">
        <div class="modal-content">
          <div class="modal-body">
            <h4>您的身分是：</h4>
            <div class="flex">
              <router-link to="collegeLoan" class="i hvr-shadow"
                ><p>學生</p>
                <img :src="'/images/college_loan.png'" class="img-fluid"
              /></router-link>
              <router-link to="freshGraduateLoan" class="i hvr-shadow"
                ><p>上班族</p>
                <img :src="'/images/office_loan.png'" class="img-fluid"
              /></router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Splide, SplideSlide } from "@splidejs/vue-splide";
//
import investUp from "../component/svg/investDotUpComponent";
import investDown from "../component/svg/investDotDownComponent";
import amountChart from "../component/svg/amountChartComponent";
import histroyDot from "../component/svg/histroyDotComponent";
import experience from "../component/experienceComponent";
//banner
import shanghuiBanner from "../component/banner/shanghuiBanner";

export default {
  components: {
    investUp,
    investDown,
    amountChart,
    histroyDot,
    experience,
    Splide,
    SplideSlide,
    //banner
    shanghuiBanner,
  },
  data: () => ({
    count: 3,
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
      autoplay: true,
      perPage: 3,
      arrows: false,
      pagination: false,
      gap: "1.5rem",
      breakpoints: {
        767: { perPage: 1 },
      },
    },
    infoOptions: {
      type: "loop",
      autoplay: true,
      perPage: 3,
      perMove: 1,
      pagination: false,
      gap: ".5rem",
      breakpoints: {
        767: { perPage: 1 },
      },
    },
    personOptions: {
      type: "loop",
      autoplay: true,
      perPage: 3,
      perMove: 1,
      arrows: false,
      pagination: false,
      gap: "1rem",
      breakpoints: {
        767: { perPage: 1 },
      },
    },
    groupOptions: {
      type: "loop",
      autoplay: true,
      perPage: 2,
      perMove: 1,
      arrows: false,
      pagination: false,
      gap: "1rem",
      breakpoints: {
        767: { perPage: 1 },
      },
    },
    csOptions: {
      type: "loop",
      autoplay: true,
      perPage: 1,
      perMove: 1,
      speed: 200,
      arrows: false,
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
    this.$store.dispatch("getExperiencesData");
    this.$store.dispatch("getKnowledgeData");
    this.$store.dispatch("getNewsData");
    this.$store.dispatch("getVideoData", { category: "share" });
    this.getServiceData();
    $("title").text(`首頁 - inFlux普匯金融科技`);
  },
  mounted() {
    AOS.init();
  },
  watch: {
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
    openDialog() {
      $(this.$refs.comfirmModal).modal("show");
    },
    async getBannerPic() {
      let res = await axios.get(`${location.origin}/getBannerPic`);
      this.bannerPic = res.data;
    },
    getServiceData() {
      axios.post(`${location.origin}/getServiceData`).then((res) => {
        this.services = res.data;
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
    pmt(pv, rate, per) {
      let m_rate = rate / 1200;
      let nper = Math.pow(m_rate + 1, -per);
      let _pmt = Math.ceil((pv * m_rate) / (1 - nper));
      return _pmt;
    },
    onMoved($event) {
      $(".cnt-l").find("div").removeClass("ad-b");
      $($(".cnt-l").find("div")[$event.index]).addClass("ad-b");
    },
  },
};
</script>

<style lang="scss">
.index-wrapper {
  width: 100%;

  .t-c {
    background-image: linear-gradient(
      to right,
      #1e2973 0%,
      #319acf 50%,
      #1e2973 75%
    );
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

  .intro-card {
    padding: 30px 0px;

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

      div {
        width: calc(100% / 3);
        margin: 0px -7px;
      }
    }
  }
  .product-card {
    h5 {
      font-size: 25px;
      font-weight: 600;
      line-height: 1.47;
      text-align: center;
      color: #ffc236;
      margin: 2rem auto;
    }

    .t-c {
      background-image: linear-gradient(
        to right,
        #306fca 0%,
        #09d4f6 50%,
        #306fca 75%
      );
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

    .person {
      background: #153a71;
      padding: 30px 0px;

      .product {
        width: 80%;
      }
    }

    .group {
      background: #ffffff;
      padding: 30px 0px;

      .product {
        width: 55%;
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
            right: 0px;
            bottom: 0px;

            &:hover {
              text-decoration: none;
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

        h3 {
          padding: 0 10px;
          border-radius: 8px;
          background-color: #18447c;
          font-size: 24px;
          font-weight: 500;
          line-height: 1.72;
          color: #ffffff;
          width: fit-content;
        }

        div {
          margin: 15px 0px;
          font-size: 22px;
          font-weight: 700;
          line-height: 1.45;
          text-align: left;
          color: #d4d4d4;
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
          z-index: 1;
        }

        .c-s {
          width: 300px;
          padding: 13px 18px;
          position: absolute;
          top: 0;
          left: 0;
          z-index: 0;

          .c-e {
            overflow: hidden;
            border-radius: 15px;

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
      background-image: linear-gradient(
        to right,
        #306fca 0%,
        #09d4f6 50%,
        #306fca 75%
      );
    }

    .cnt {
      display: flex;
      width: 80%;
      margin: 20px auto;

      .amount-chart {
        width: 60%;
      }

      .aa-as {
        position: relative;
        width: 40%;

        .option {
          width: fit-content;
          height: fit-content;
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          .item {
            margin: 5px;
            width: calc(100% / 3 - 10px);
            float: left;
            color: #ffffff;
            text-align: center;

            .circle {
              border-radius: 50%;
              width: 80px;
              height: 80px;
              position: relative;
              border: 2px solid #048dbd;
              margin: 5px auto;
              cursor: pointer;
              overflow: hidden;

              img {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 70%;
              }
            }

            .gary {
              border-color: #f1f1f1;
            }

            span {
              font-size: 14px;
            }
          }
        }
      }
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

          .title {
            color: #ffffff;
            margin: 35px auto;
            width: max-content;
            text-align: center;
            font-size: 48px;
            font-weight: bolder;
          }

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
            font-size: 29px;
            text-align: center;
            font-weight: 700;
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

      .slick-arrow {
        position: absolute;
        top: 50%;
        transform: translatey(-50%);
        font-size: 23px;
        z-index: 1;
        cursor: pointer;
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
          top: -4%;
          left: 10px;
          width: 63%;
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

    .product-card {
      background-size: initial;
      padding: 0px;

      .pattern {
        width: 100%;
        margin: 10px 0px;
        overflow: hidden;

        .circle {
          top: 37%;

          %invest-dot {
            display: none;
          }

          .cnt {
            height: 100px;
            width: 100px;
            padding: 8px 0px;
          }
        }

        %p-bg {
          width: calc(50% - 15px);

          .imagery {
            display: block;
            padding: 10px;
            margin-top: -70px;
            position: relative;
            z-index: -1;

            .img {
              width: 100%;
              position: initial;

              img {
                width: 65%;
                left: 50%;
                transform: translateX(-50%);
                bottom: 10px;
              }
            }

            span {
              display: none;
            }
          }

          p {
            color: #ffffff;
          }
        }

        .p-i {
          @extend %p-bg;
          margin-right: 5px;
          margin-left: 10px;
        }

        .p-l {
          @extend %p-bg;
          margin-left: 5px;
          margin-right: 10px;
        }
      }

      .product {
        width: initial;

        .product-list {
          padding: 10px;
          .i {
            width: calc(100%);

            .card-item {
              margin: 5px 0px;

              .img {
                width: 120px;

                img {
                  left: 5px;
                }
              }

              .cv {
                padding: 10px;
                width: calc(100% - 120px);
              }
            }
          }
        }
      }
    }

    .game-card {
      %layout {
        text-align: center;
        background-color: #ffffff;
        padding: 20px 10px;
        width: initial;
        margin: 10px;
      }

      h5 {
        font-size: 16px;
      }

      .loan-game {
        .option {
          display: block;
          overflow: hidden;

          .item {
            float: left;
            width: calc(100% / 4 - 10px);

            .circle {
              width: 70px;
              height: 70px;
              margin: 5px auto;
            }
          }
        }

        .credit-num {
          margin: 10px 0px;

          .circle {
            width: initial;

            .total {
              left: 50%;
              padding: 0px 10px;

              p {
                margin-top: 15px;
              }
            }
          }
        }
      }

      .invest-game {
        padding: 10px;
        .chart {
          height: 300px;
          margin: 0px;
        }

        .option {
          .item {
            float: initial;
            width: calc(100% - 10px);
          }
        }
      }
    }

    .advantage-card {
      .header {
        padding: 10px;
        min-height: auto;

        .slg {
          position: relative;
          transform: initial;
          top: 0%;
          left: 0%;
          margin: 35px 10px;

          .title {
            font-size: 22px;
          }
        }
      }

      .content {
        .item {
          padding: 1rem;
          height: 315px;
          p {
            font-size: 28px;
          }
        }
      }
    }

    .information-card {
      .nav {
        width: 100%;
      }

      .tab-content {
        .btn-to {
          width: 70%;
        }
      }
    }

    .slogan-card {
      overflow: hidden;
      padding: 25px 10px;

      .content {
        width: 75%;
        text-align: justify;
      }

      .slogan {
        width: 80%;
      }

      .img {
        display: none;
      }
    }

    .download-card {
      background-size: auto 100%;

      .d-desc {
        width: 100%;
        padding: 2rem;
      }

      .img {
        display: none;
      }

      .d-apps {
        width: 100%;

        %font {
          width: 100%;

          .d-link {
            width: 80%;
            margin: 10px auto;
          }
        }
      }
    }
  }
}
</style>