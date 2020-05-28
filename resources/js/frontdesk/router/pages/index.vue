<template>
  <div>
    <div class="banner">
      <img :src="'./Image/index-banner.jpg'" class="banner-img" />
      <div class="cover"></div>
      <p class="heading-title">我們成功幫助了{{count}}筆交易</p>
      <p class="heading-description" ref="description"></p>
      <div class="heading-container">
        <a
          class="btn btn-loan"
          href="https://event.influxfin.com/R/url?p=webbanner"
          target="_blank"
        >立即借款</a>
        <a
          class="btn btn-invest"
          href="https://event.influxfin.com/r/iurl?p=webinvest"
          target="_blank"
        >立即投資</a>
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
        <div v-for="(item,index) in this.services" class="slick-item" :key="index">
          <router-link :to="item.link">
            <img :src="item.imageSrc" />
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
      <div class="profession-title">
        <p>以金融為核心，以科技為輔具，普匯給您前所未有的專業金融APP</p>
        <span>為什麼選擇普匯金融科技?</span>
      </div>
      <div class="profession-content">
        <div class="profession-item">
          <img
            :src="'./Image/best1.png'"
            class="img-fluid"
            @mouseover="transform($event)"
            @mouseleave="recovery($event)"
          />
          <p>最專業的金融專家</p>
          <span>普匯擁有近20年金融專業經驗，深度理解各類金融產品、相關金融法規、財稅務、金流邏輯...等。能針對不同產業產品與市場，設計出更適合用戶需求的金融服務。</span>
        </div>
        <div class="profession-item">
          <img
            :src="'./Image/best2.png'"
            class="img-fluid"
            @mouseover="transform($event)"
            @mouseleave="recovery($event)"
          />
          <p>最先進的AI科技系統</p>
          <span>普匯擁有完善的金融科技技術，包含: 反詐欺反洗錢系統、競標即時撮合系統、 風控信評/線上對保、自動撥貸/貸後管理、 分秒計息等，不斷與時俱進迭代優化。</span>
        </div>
        <div class="profession-item">
          <img
            :src="'./Image/best3.png'"
            class="img-fluid"
            @mouseover="transform($event)"
            @mouseleave="recovery($event)"
          />
          <p>簡單、快速、安全、隱私</p>
          <span>視覺化簡潔好用的操作介面，運用先進科技與AWS 安全系統，保護您的個資絕不外洩，讓您在步入圓夢捷徑的同時，安全又放心。</span>
        </div>
      </div>
      <div class="profession-footer" data-aos="fade-right">
        <div class="profession-slick" ref="profession_slick">
          <div v-for="(item,index) in dossales" class="slick-item" :key="index">
            <img :src="item.imageSrc" class="img-fluid" />
          </div>
        </div>
      </div>
    </div>
    <div class="message-wrapper">
      <h2>inFlux金融科技最新知識訊息</h2>
      <div class="message-content" data-aos="fade-right">
        <div v-for="(item,index) in knowledge" class="content-row" :key="index">
          <router-link :to="item.link">
            <img :src="item.imageSrc" class="img-fluid" />
          </router-link>
          <div>
            <p>{{item.title}}</p>
            <span>{{item.content}}</span>
            <br />
            <router-link :to="item.link" class="btn btn-primary">閱讀更多</router-link>
          </div>
        </div>
      </div>
    </div>
    <videoShare ref="videoShare" title="普匯生活分享" :data="this.shares"></videoShare>
    <div class="news-wrapper">
      <h2>普匯最新消息</h2>
      <div class="news-slick" ref="news_slick">
        <div v-for="(item,index) in news" class="slick-item" :key="index">
          <router-link :to="item.link">
            <img :src="item.imageSrc" class="img-fluid" />
            <p>{{item.title}}</p>
          </router-link>
        </div>
      </div>
    </div>
    <join href="./Image/child_banner.jpg" :isShowAll="true"></join>
  </div>
</template>

<script>
import videoShareComponent from "./component/videoShareComponent";
import experienceComponent from "./component/experienceComponent";
import joinComponent from "./component/joinComponent";

export default {
  components: {
    videoShare: videoShareComponent,
    experience: experienceComponent,
    join: joinComponent
  },
  data: () => ({
    description: "普匯．你的手機ATM",
    timeLineMax: "",
    dossales: [
      { imageSrc: "./Image/dossal1.png" },
      { imageSrc: "./Image/dossal2.png" },
      { imageSrc: "./Image/dossal3.png" },
      { imageSrc: "./Image/dossal4.png" }
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
    experiences() {
      return this.$store.getters.ExperiencesData;
    },
    shares() {
      return this.$store.getters.SharesData;
    },
    knowledge() {
      let $this = this;
      $.each($this.$store.getters.KnowledgeData, (index, row) => {
        $this.$store.getters.KnowledgeData[
          index
        ].content = `${row.content.replace(/<[^>]*>/g, "").substr(0, 100)}...`;
      });
      return $this.$store.getters.KnowledgeData;
    },
    news() {
      return this.$store.getters.NewsData;
    }
  },
  created() {
    this.$store.dispatch("getExperiencesData");
    this.$store.dispatch("getKnowledgeData");
    this.$store.dispatch("getNewsData");
    this.$store.dispatch("getSharesData", { category: "share" });
    this.getServiceData();
    $("title").text(`首頁 - inFlux普匯金融科技`);
  },
  mounted() {
    this.typing();
    this.interval();
    this.createProfessionSlick();
    $(this.$refs.videoShare.$refs.share_content).attr("data-aos", "fade-left");
    $(this.$refs.experience.$refs.experience_slick).attr("data-aos", "zoom-in");
    AOS.init();
  },
  watch: {
    experiences() {
      this.$nextTick(() => {
        this.$refs.experience.createSlick();
      });
    },
    news() {
      this.$nextTick(() => {
        this.createNewsSlick();
      });
    },
    services() {
      this.$nextTick(() => {
        this.createServiceSlick();
      });
    }
  },
  methods: {
    getServiceData() {
      axios.post("getServiceData").then(res => {
        this.services = res.data;
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
        $(this.$refs.description).text("");
        this.textIndex = 0;
        this.typing();
      }, 5000);
    },
    createServiceSlick() {
      $(this.$refs.service_slick).slick({
        infinite: true,
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        dots: true,
        dotsClass: "slick-custom-dots",
        customPaging(slider, i) {
          return '<i class="fas fa-circle"></i>';
        },
        prevArrow: '<i class="fas fa-chevron-left arrow-left"></i>',
        nextArrow: '<i class="fas fa-chevron-right arrow-right"></i>',
        responsive: [
          {
            breakpoint: 1023,
            settings: {
              slidesToShow: 3,
              slidesToScroll: 1
            }
          },
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
    createNewsSlick() {
      $(this.$refs.news_slick).slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        dots: true,
        dotsClass: "slick-custom-dots",
        customPaging(slider, i) {
          return '<i class="fas fa-circle"></i>';
        },
        prevArrow: '<i class="fas fa-chevron-left arrow-left"></i>',
        nextArrow: '<i class="fas fa-chevron-right arrow-right"></i>',
        responsive: [
          {
            breakpoint: 1023,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      });
    },
    createProfessionSlick() {
      $(this.$refs.profession_slick).slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        dots: true,
        dotsClass: "slick-custom-dots",
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
      this.timeLineMax.to($event.target, { "max-width": "90%" });
      this.timeLineMax.play();
    },
    recovery($event) {
      this.timeLineMax.reverse();
    }
  }
};
</script>

<style lang="scss">
@import "./scss/slick";

.banner {
  position: relative;
  overflow: hidden;
  height: 670 * 0.9px;

  .banner-img {
    min-width: 100%;
    height: 670 * 0.9px;
  }

  .cover {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 670 * 0.9px;
    background-color: #00000021;
  }

  .heading-title {
    @extend %make-center;
    top: 22%;
    font-size: 42px;
    font-weight: 700;
    text-shadow: 0 0 7px black;
    width: fit-content;
  }

  .heading-description {
    @extend %make-center;
    top: 40%;
    font-size: 30px;
    font-weight: 600;
    width: fit-content;
  }

  .heading-container {
    @extend %make-center;
    width: fit-content;
    top: 58%;

    .btn-loan {
      @extend %headler-btn;
      background-color: #ffeb01;
      margin-right: 60px;
    }

    .btn-invest {
      @extend %headler-btn;
      background-color: #0419b2;
    }
  }

  .heading-info {
    @extend %make-center;
    top: 85%;
    text-align: center;
    width: fit-content;
    text-shadow: 0 0 3px black;
    font-weight: 600;
    font-size: 22px;
    color: #e0e0e0;
  }

  @media (max-width: 767px) {
    .banner-img {
      position: absolute;
      left: -71%;
    }

    .heading-title {
      font-size: 25px;
    }

    .heading-description {
      font-size: 23px;
      top: 34%;
    }
    .heading-container {
      top: 52%;
    }
    .heading-info {
      top: 78%;
    }
  }
}

.service-items-wrapper {
  width: 100%;
  margin: 50px 0px;
  min-height: 377px;
  overflow: hidden;

  .service-slick {
    @extend %slick-style;

    .slick-item {
      position: relative;
      img {
        width: 228px;
      }

      div {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 100%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #ffffff;
      }
    }

    @media (min-width: 767px) {
      .slick-custom-dots {
        display: none;
      }
    }

    @media (max-width: 767px) {
      .slick-item {
        img {
          margin: 0px auto;
        }
      }
    }
  }
}

.profession-wrapper {
  margin-top: 50px;
  overflow: hidden;

  .profession-title {
    background-color: #002e58;
    color: #ffffff;
    margin: 55px auto;
    padding: 20px 60px;
    width: fit-content;
    border-bottom-right-radius: 24px;
    text-align: center;

    p {
      font-size: 22px;
      font-weight: 700;
    }

    span {
      font-size: 18px;
    }
  }

  .profession-content {
    display: flex;

    .profession-item {
      text-align: center;
      box-shadow: 0 0 5px #293e5d;
      padding: 10px 50px;
      margin: 0px 50px;

      img {
        max-width: 81%;
        height: auto;
      }

      p {
        color: #002e58;
        font-size: 19px;
        text-align: center;
        font-weight: 700;
      }
    }
  }

  .profession-footer {
    .profession-slick {
      padding: 50px 0px;

      @extend %slick-style;

      .slick-item {
        margin: 0px 10px;
        cursor: default;
      }

      .slick-list {
        width: 100%;
      }

      @media (min-width: 767px) {
        .slick-custom-dots {
          display: none;
        }
      }

      @media (max-width: 1023px) {
        .slick-item {
          margin: 0px 3px;
        }
      }
    }
  }

  @media (max-width: 767px) {
    .profession-title {
      padding: 20px 15px;
    }

    .profession-content {
      display: block;

      div {
        margin: 25px 10px;
      }
    }

    .profession-footer {
      .profession-slick {
        display: block;
      }
    }
  }

  @media (max-width: 1023px) {
    .profession-content {
      div {
        margin: 10px;
      }
    }
  }
}

.message-wrapper {
  overflow: hidden;

  h2 {
    @extend %wrapper-title;
  }

  .message-content {
    display: flex;

    .content-row {
      width: 25%;
      margin: 10px;
      background: #f7f7f7;

      div {
        padding: 15px;
        text-align: justify;

        a {
          color: #ffffff;
          margin-top: 10px;
        }
      }
    }
  }

  @media (max-width: 1023px) {
    h2 {
      font-size: 26px;
    }

    .message-content {
      display: block;

      .content-row {
        width: 95%;
      }
    }
  }
}

.news-wrapper {
  width: 100%;
  margin: 50px 0px;
  min-height: 400px;
  overflow: hidden;

  h2 {
    @extend %wrapper-title;
  }

  .news-slick {
    .slick-item {
      margin: 0px 15px;

      a {
        color: #000000;
        font-weight: bolder;
        &:hover {
          text-decoration: none;
        }
      }
    }

    @extend %slick-style;
  }
}
</style>

