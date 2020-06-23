<template>
  <div class="company-wrapper">
    <banner :data="this.bannerData"></banner>
    <div class="profile-card">
      <div class="puhey">
        <div class="img">
          <img class="img-fluid" :src="'./Images/logo_.png'" />
        </div>
        <div class="text">
          <h3>金融服務「普」及大眾，人才「匯」流。</h3>
          <p>普匯金融科技不是銀行，我們是專業的Fintech金融科技顧問，由具備深厚的風險管控、金融產品設計經驗的金融團隊組成，致力提供互信互利的平台，將借款人與投資者聯繫起來，共創雙贏機會。運用AI智能科技與安全風控模組，將專業金融產品與線上簡易方式搭起投資人與借款人的橋梁。以「金融專業」為核心，「科技工具」為輔助，提供「最有溫度」的社群服務，拉近人與人的距離，讓金融年輕化並串起社會閒置資源，幫助年輕人完成自我學習成長與創業夢想！</p>
        </div>
      </div>
    </div>
    <div class="advantage-card">
      <h2>我們堅持</h2>
      <div class="content">
        <div v-for="(item,index) in regulations" class="item" :key="index">
          <div class="img">
            <img :src="item.imageSrc" />
          </div>
          <div class="text">
            <h3>{{item.title}}</h3>
            <hr />
            <span>{{item.text}}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="media-card">
      <div class="left_megaphone">
        <img class="img-fluid" :src="'./Images/left_megaphone.svg'" />
      </div>
      <div class="list">
        <div v-for="(item,index) in media" class="report-row" :key="index">
          <div @click="openModel(item)">{{item.datetime}}&emsp;{{item.title}}</div>
          <a :href="item.link" target="_blank">
            <i class="fas fa-external-link-alt"></i>
          </a>
        </div>
      </div>
      <div class="right_megaphone">
        <img class="img-fluid" :src="'./Images/right_megaphone.svg'" />
      </div>
    </div>
    <div class="partner-card">
      <h3>合作夥伴</h3>
      <div class="list">
        <div
          class="item"
          data-aos="fade-down"
          :data-aos-delay="300*index"
          data-aos-duration="500"
          v-for="(item,index) in partner"
          :key="index"
        >
          <div class="photo hvr-bob">
            <img :src="item.imageSrc" class="img-fluid" @click="showPartner(index,$event)" />
          </div>
        </div>
        <div v-if="Object.keys(partnerData).length !==0" class="content">
          <i class="far fa-times-circle" @click="partnerData = {}"></i>
          <h2>{{partnerData.title}}</h2>
          <p>{{partnerData.subTitle}}</p>
          <hr />
          <p v-html="partnerData.text"></p>
        </div>
      </div>
    </div>
    <div class="party-card">
      <div class="party" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="500">
        <div class="img">
          <img class="img-fluid" :src="'./Images/partner7.png'" />
        </div>
        <p>臺灣創新快製媒合中心(Taiwan Rapid Innovation Prototyping League for Entrepreneurs)，英文簡稱TRIPLE，結合SI/ODM、加速器業者與研究機構，期望以臺灣優質軟硬體整合、先進製造與管理能量，提供國內外創新事業快速試製以及其他在設計、行銷、研發等方面的加值服務，協助其加速落實創意，實現市場價值。快製中心以提供快速試製創新商機媒合服務為主，協助媒合新創事業與快製聯盟業者，促成雙方合作。</p>
      </div>
      <div class="party" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500">
        <div class="img">
          <img class="img-fluid" :src="'./Images/partner8.png'" />
        </div>
        <p>立勤國際法律事務所自2014年擴大營運至今，已經橫跨中國、台灣、菲律賓三地，並加入國際環太平洋律師組織IBPA，與各國律師建立國際聯繫。目前在台灣有台北、新竹、高雄三處共四間事務所，擠身台灣前十大事務所，提供全般法律、財務、會計、地政、智慧財產服務等方面的服務。</p>
      </div>
      <div class="party" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="500">
        <div class="img">
          <img class="img-fluid" :src="'./Images/partner9.png'" />
        </div>
        <p>
          碁元會計師事務所將每位客戶視為難能可貴的朋友般珍惜，秉持服務、信任、學習的精神為客戶處理大大小小的帳務及稅務問題，並深受客戶肯定與支持。
          <br />提供工商登記、代客記帳服務、財稅簽證、上市上櫃前置輔導、營業稅及所得稅代理申報、遺產及贈與稅、境外公司設立與規劃、財務顧問、企業評價及各種稅務之行政救濟等服務。
        </p>
      </div>
    </div>
    <div class="milestone-card">
      <h3>普匯編年史</h3>
      <div class="timeline">
        <div
          v-for="(item,index) in milestone"
          class="block"
          data-aos="fade-up"
          data-aos-duration="500"
          :key="index"
        >
          <label class="icon"></label>
          <div class="event">
            <span class="date" v-html="item.dateTime"></span>
            <h2 class="title" v-html="item.title"></h2>
            <div class="content">{{item.content}}</div>
          </div>
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
import bannerComponent from "./component/bannerComponent";

export default {
  components: {
    banner: bannerComponent
  },
  data: () => ({
    regulations: [
      {
        title: "簡單",
        text:
          "直覺化UIUX介面設計，讓操作使用更簡單便利，第一次開啟使用就能上手",
        imageSrc: "./Images/company_icon1.png"
      },
      {
        title: "快速",
        text:
          "全程手機線上申請，AI系統24小時不間斷驗證，提升作業速度與效率，加快用戶取得資金",
        imageSrc: "./Images/company_icon2.png"
      },
      {
        title: "安全",
        text: "使用 Amazon Web Services雲端服務平台，個資絕不外洩",
        imageSrc: "./Images/company_icon3.png"
      },
      {
        title: "隱私",
        text:
          "全程無人系統驗證操作，從申請到取得款項，資訊完全不外洩，保障投資人與借款人各資隱密與隱私",
        imageSrc: "./Images/company_icon4.png"
      },
      {
        title: "低風險高報酬",
        text: "小額分散、分期還款、降低風險、複利效果，創造最高報酬",
        imageSrc: "./Images/company_icon5.png"
      }
    ],
    media: [],
    partner: [],
    milestone: [],
    partnerData: {},
    bannerData: {},
    reportData: {}
  }),
  created() {
    this.getBannerData();
    this.getMilestoneData();
    this.getMediaData();
    this.getPartnerData();
    $("title").text(`公司介紹 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      $(this.$root.$refs.banner).hide();
      this.$root.pageHeaderOffsetTop = 0;
      AOS.init();
    });
  },
  watch: {
    milestone() {
      this.$nextTick(() => {
        this.timeline();
      });
    }
  },
  methods: {
    getBannerData() {
      axios.post("getBannerData", { filter: "company" }).then(res => {
        this.bannerData = res.data;
      });
    },
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
    showPartner(index, $event) {
      let $target = $($event.target);
      this.partnerData = this.partner[index];

      this.$nextTick(() => {
        $(".content")
          .css("top", $target.offset().top + $(".photo").outerHeight() + 30)
          .css(
            "left",
            $target.offset().left + $(".photo").outerWidth() / 2 - 160
          );
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
.company-wrapper {
  .profile-card {
    background: #006bda;
    padding: 30px;

    .puhey {
      display: flex;

      .img {
        width: 50%;
        filter: drop-shadow(0px 0px 10px white);
      }

      .text {
        padding: 20px;
        color: #ffffff;
        font-weight: bolder;

        h3 {
          font-weight: bolder;
          margin-bottom: 20px;
        }

        p {
          line-height: 50px;
        }
      }
    }
  }

  .party-card {
    display: flex;
    padding: 30px;
    background: #cacaca;

    .party {
      width: 32%;
      margin: 10px;
      padding: 10px;
      border-radius: 10px;
      background: #ffffff;
      box-shadow: 0 0 8px black;
    }
  }

  .advantage-card {
    padding: 30px;
    background: #f1f1f1;
    h2 {
      text-align: center;
      font-weight: bolder;
    }

    .content {
      width: 80%;
      margin: 0px auto;
      overflow: hidden;

      .item {
        float: left;
        padding: 10px;
        margin: 20px;
        border-radius: 20px;
        box-shadow: 0 0 10px black;
        display: flex;
        width: 46.5%;
        background: #ffffff;

        .text {
          border-left: 1px solid #cecece;
          padding: 10px;

          hr {
            border-top: 1px solid #cecece;
          }

          h3 {
            text-align: center;
          }
        }
      }
    }
  }

  .media-card {
    padding: 30px;
    border-top: 2px solid #5d5d5d;
    border-bottom: 2px solid #5d5d5d;
    background: #f0ffb2;
    position: relative;

    %img {
      position: absolute;
      bottom: 10%;
      width: 100px;
      filter: drop-shadow(2px 4px 6px black);
    }

    .left_megaphone {
      @extend %img;
      left: 15%;
    }

    .right_megaphone {
      @extend %img;
      right: 15%;
    }

    .list {
      width: fit-content;
      margin: 0px auto;
      overflow: hidden;
      background: #b36b12;
      border-radius: 15px;
      padding: 10px 50px;
      box-shadow: 3px 3px 10px black;

      .report-row {
        display: flex;
        background: #ffffff;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 0 6px #5170ff;
        margin: 10px;
        font-size: 18px;

        div {
          margin-right: 1em;
          cursor: pointer;

          &:hover {
            text-decoration: underline;
          }
        }
      }
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

  .partner-card {
    padding: 30px;
    overflow: hidden;
    text-align: center;
    font-weight: bolder;
    background: #006bda;

    h3 {
      color: #ffffff;
      font-weight: bolder;
    }

    .list {
      display: flex;
      padding: 10px;
      width: fit-content;
      margin: 0px auto;

      .item {
        .photo {
          width: 190px;
          height: 190px;
          background: #ffffff;
          padding: 10px;
          border-radius: 50%;
          margin: 10px 10px;
          overflow: hidden;
          box-shadow: 0 0 20px black;
          cursor: pointer;
        }
      }

      .content {
        position: absolute;
        width: 300px;
        z-index: 1;
        border-radius: 10px;
        background: #ffffff;
        filter: drop-shadow(0px 0px 9px black);
        padding: 10px;
        transition-duration: 0.5s;
        text-align: end;

        h2 {
          text-align: center;
        }

        p {
          text-align: justify;
        }

        &:before {
          content: "";
          width: 0px;
          height: 0px;
          border-bottom: 20px solid #ffffff;
          border-right: 15px solid #ffffff00;
          border-left: 15px solid #ffffff00;
          position: absolute;
          top: -20px;
          left: 50%;
          transform: translate(-50%, 0px);
        }
      }
    }
  }

  .milestone-card {
    padding: 30px;
    overflow: hidden;
    text-align: center;

    h3 {
      font-weight: bolder;
    }

    .timeline {
      .block {
        width: 60%;
        margin: 15px auto;
        position: relative;

        &:before {
          background-color: black;
          content: "";
          margin-left: -1px;
          position: absolute;
          top: 0;
          left: 2em;
          width: 2px;
          height: 100%;
        }

        .icon {
          transform: rotate(45deg);
          background-color: black;
          outline: 10px solid white;
          display: block;
          margin: 0.5em 0.5em 0.5em -0.5em;
          position: absolute;
          top: 0;
          left: 2em;
          width: 1em;
          height: 1em;
          transition-duration: 0.5s;
        }

        .event {
          padding: 2em;
          position: relative;
          top: -1.875em;
          left: 4em;
          text-align: start;

          .date {
            color: white;
            font-size: 0.75em;
            background-color: black;
            display: inline-block;
            margin-bottom: 1.2em;
            padding: 0.25em 1em 0.2em 1em;
            transition-duration: 0.5s;
          }
        }

        &:hover {
          .icon {
            transform: rotate(-45deg);
            background: #163a74;
          }

          .event {
            .date {
              background-color: red;
            }
          }
        }
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
</style>

