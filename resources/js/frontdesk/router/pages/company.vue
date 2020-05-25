<template>
  <div class="company-wrapper">
    <div class="company-header">
      <div class="header-title">
        <img :src="'./Image/child_banner.jpg'" />
        <div class="title">公司介紹</div>
      </div>
      <div class="header-banner">
        <img :src="'./Image/company_banner.png'" />
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
          <img :src="item.imageSrc" />
          <h5>{{item.title}}</h5>
          <p>{{item.text}}</p>
        </div>
      </div>
    </div>
    <join href="./Image/child_banner.jpg" :isShowLoan="true" subTitle="加入普匯完成你的財富目標吧！"></join>
    <div class="company-background">
      <h3>我們的成就</h3>
      <div id="cd-timeline" class="cd-container">
        <div v-for="(item,index) in milestone" class="cd-timeline-block" :key="index">
          <div class="cd-timeline-img cd-icon" v-html="item.dateTime"></div>
          <div class="cd-timeline-content">
            <h2 v-html="item.title"></h2>
            <p>{{item.content}}</p>
          </div>
        </div>
      </div>
    </div>
    <div class="company-background media-content">
      <h3>媒體報導支持</h3>
      <div class="media-slick" ref="media_slick" data-aos="zoom-in">
        <div v-for="(item,index) in media" class="slick-item" :key="index">
          <a :href="item.link" target="_blank">
            <img :src="item.imageSrc" />
          </a>
          <p @click="openModel(item)">{{item.title}}</p>
        </div>
      </div>
    </div>
    <div class="company-background partner-content">
      <h3>合作夥伴</h3>
      <div class="block" v-for="(item,index) in partner" :key="index">
        <div class="partner-photo">
          <img :src="item.imageSrc" class="img-fluid" />
        </div>
        <div class="partner-card">
          <h2>{{item.title}}</h2>
          <p>{{item.subTitle}}</p>
          <hr />
          <p v-html="item.text"></p>
        </div>
      </div>
    </div>
    <div
      class="modal fade"
      ref="newsModal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-icon" data-dismiss="modal">
            <i class="far fa-times-circle"></i>
          </div>
          <div class="modal-body">
            <h4 class="report-title" v-html="reportData.title"></h4>
            <p calss="report-date">{{reportData.datetime}}</p>
            <div class="report-contert" v-html="reportData.content"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import joinComponent from "./component/joinComponent";

export default {
  components: {
    join: joinComponent
  },
  data: () => ({
    regulations: [
      {
        title: "簡單",
        text:
          "直覺化UIUX介面設計，讓操作使用更簡單便利，第一次開啟使用就能上手",
        imageSrc: "./Image/company_icon1.png"
      },
      {
        title: "快速",
        text:
          "全程手機線上申請，AI系統24小時不間斷驗證，提升作業速度與效率，加快用戶取得資金",
        imageSrc: "./Image/company_icon2.png"
      },
      {
        title: "安全",
        text: "使用 Amazon Web Services雲端服務平台，個資絕不外洩",
        imageSrc: "./Image/company_icon3.png"
      },
      {
        title: "隱私",
        text:
          "全程無人系統驗證操作，從申請到取得款項，資訊完全不外洩，保障投資人與借款人各資隱密與隱私",
        imageSrc: "./Image/company_icon4.png"
      },
      {
        title: "低風險高報酬",
        text: "小額分散、分期還款、降低風險、複利效果，創造最高報酬",
        imageSrc: "./Image/company_icon5.png"
      }
    ],
    media: [],
    partner: [],
    milestone: [],
    reportData: {}
  }),
  created() {
    this.getMilestoneData();
    this.getMediaData();
    this.getPartnerData();
    $("title").text(`公司介紹 - inFlux普匯金融科技`);
  },
  mounted() {
    this.createRegulationSlick();
    AOS.init();
  },
  watch: {
    media() {
      this.$nextTick(() => {
        this.createMediaSlick();
      });
    },
    milestone() {
      this.$nextTick(() => {
        this.timeline();
      });
    }
  },
  methods: {
    getMilestoneData() {
      axios.post("getMilestoneData").then(res => {
        this.milestone = res.data;
      });
    },
    getMediaData() {
      axios.post("getMediaData").then(res => {
        this.media = res.data;
      });
    },
    openModel(data) {
      this.reportData = data;
      this.$nextTick(() => {
        $(this.$refs.newsModal).modal("show");
      });
    },
    getPartnerData() {
      axios.post("getPartnerData").then(res => {
        this.partner = res.data;
      });
    },
    createRegulationSlick() {
      $(this.$refs.regulation_slick).slick({
        infinite: true,
        slidesToShow: 5,
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
              slidesToShow: 2,
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
    createMediaSlick() {
      $(this.$refs.media_slick).slick({
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        prevArrow: "",
        nextArrow: "",
        responsive: [
          {
            breakpoint: 1023,
            settings: {
              slidesToShow: 2,
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
    timeline() {
      let $timeline_block = $(".cd-timeline-block");

      $timeline_block.each(function() {
        if (
          $(this).offset().top >
          $(window).scrollTop() + $(window).height() * 0.75
        ) {
          $(this)
            .find(".cd-timeline-img, .cd-timeline-content")
            .addClass("is-hidden");
        }
      });

      $(window).on("scroll", function() {
        $timeline_block.each(function() {
          if (
            $(this).offset().top <=
              $(window).scrollTop() + $(window).height() * 0.75 &&
            $(this)
              .find(".cd-timeline-img")
              .hasClass("is-hidden")
          ) {
            $(this)
              .find(".cd-timeline-img, .cd-timeline-content")
              .removeClass("is-hidden")
              .addClass("bounce-in");
          }
        });
      });
    }
  }
};
</script>

<style lang="scss">
@import "./scss/slick";

.company-wrapper {
  .company-header {
    .header-title {
      width: 100%;
      overflow: hidden;
      position: relative;
      height: 160px;

      img {
        min-width: 100%;
        position: absolute;
        height: 210%;
      }

      .title {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #ffffff;
        font-weight: bolder;
        font-size: 33px;
      }
    }

    .header-banner {
      overflow: hidden;
      position: relative;
      height: 445px;

      img {
        width: 100%;
        position: absolute;
        top: -53%;
      }

      div {
        position: absolute;
        color: #ffffff;
        text-shadow: 0 0 14px black;
        font-weight: bolder;
        top: 50%;
        transform: translateY(-50%);
        margin: 0px 10%;

        p {
          font-size: 24px;
        }

        h2 {
          font-weight: bolder;
        }
      }

      @media (max-width: 1023px) {
        img {
          width: auto;
          top: 0%;
        }
      }

      @media (max-width: 767px) {
        img {
          width: auto;
          top: -16%;
          left: -100%;
        }
      }
    }

    .header-footer {
      background-color: #f7f7f7;
      padding: 30px 20%;

      h5 {
        font-size: 1.5rem;
        font-weight: bolder;
        margin-bottom: 25px;
      }

      p {
        font-size: 18px;
        text-align: justify;
      }

      @media (max-width: 767px) {
        padding: 30px 20px;
      }
    }
  }

  .company-background {
    padding-top: 60px;
    background-color: #f7f7f7;
    h3 {
      text-align: center;
      font-size: 2rem;
      font-weight: bolder;
    }
  }

  .company-content {
    .regulation-slick {
      h5 {
        text-align: center;
        font-weight: bolder;
        font-size: 1.5rem;
      }

      .slick-item {
        margin: 0px 10px;
      }

      @extend %slick-style;
    }
  }

  .media-content {
    .media-slick {
      h5 {
        text-align: center;
        font-weight: bolder;
        font-size: 1.5rem;
      }

      .slick-item {
        margin: 0px 10px;
      }

      .slick-list {
        width: 90%;
        margin: 0px auto;
      }

      .slick-arrow {
        position: absolute;
        top: 50%;
        transform: translatey(-50%);
        font-size: 23px;
        z-index: 1;
        cursor: pointer;
      }

      .arrow-left {
        left: 20px;
      }

      .arrow-right {
        right: 20px;
      }

      .slick-custom-dots {
        position: absolute;
        padding: 15px 0px;
        text-align: center;
        color: #a0a0a0;
        display: flex;
        list-style-type: none;
        left: 50%;
        transform: translateX(-50%);
        font-size: 13px;

        li {
          margin: 0px 3px;
        }

        .slick-active {
          color: #000000;
        }
      }
    }
  }

  .partner-content {
    .block {
      width: 70%;
      margin: 0px auto;
      border-top: 1px solid #000000;
      padding: 35px 0px;
      overflow: auto;

      .partner-photo {
        float: left;
        max-width: 346px;
        box-shadow: 0 0 5px black;
        margin: 22px;
      }

      .partner-card {
        display: flow-root;
        margin: 22px;
        padding: 25px;
        hr {
          border-top: 1px solid #7d7d7d;
        }

        p {
          color: #b1b1b1;
        }
      }
    }

    @media (max-width: 1023px) {
      .block {
        width: 95%;
      }
    }
  }

  .modal-dialog {
    .modal-icon {
      position: absolute;
      top: -15px;
      right: -10px;
      font-size: 33px;
      color: #464646;
      cursor: pointer;
      z-index: 1;

      i {
        background: white;
        border-radius: 50%;
      }
    }

    .report-title {
      font-weight: bolder;
    }
    .report-datetime {
      color: #919191;
    }
    .report-contert {
      color: #606060;
    }

    @media (min-width: 567px) {
      max-width: 800px;
    }
  }
}

@import "./scss/timeLine";
</style>

