<template>
  <div class="company-wrapper">
    <banner :data="this.bannerData"></banner>
    <div class="text-card">
      <div class="a-hr">
        <div class="a-s">
          <!-- <h3>金融服務「普」及大眾，人才「匯」流。</h3> -->
          <p>
            普匯金融科技不是銀行，我們是專業的Fintech金融科技顧問，由具備深厚的風險管控、金融產品設計經驗的金融團隊組成，致力提供互信互利的平台，將借款人與投資者聯繫起來，共創雙贏機會。
          </p>
          <p>
            運用AI智能科技與安全風控模組，將專業金融產品與線上簡易方式搭起投資人與借款人的橋梁。以「金融專業」為核心，「科技工具」為輔助，提供「最有溫度」的社群服務，拉近人與人的距離，讓金融年輕化並串起社會閒置資源，幫助年輕人完成自我學習成長與創業夢想！
          </p>
        </div>
      </div>
    </div>
    <div class="child-bg">
      <div class="advantage-card">
        <h2>我們堅持</h2>
        <div class="hr"></div>
        <div class="advantage-cnt" ref="advantage_slick">
          <div
            v-for="(item, index) in regulations"
            class="item hvr-bob"
            :key="index"
          >
            <div class="img">
              <img class="img-fluid" :src="item.imageSrc" />
            </div>
            <h5>{{ item.title }}</h5>
            <p>{{ item.text }}</p>
          </div>
        </div>
      </div>
      <div class="partner-card">
        <h2>合作夥伴</h2>
        <div class="hr"></div>
        <div class="list">
          <div
            class="item"
            data-aos="fade-down"
            :data-aos-delay="100 * index"
            data-aos-duration="500"
            v-for="(item, index) in partner.slice(0, 6)"
            :key="index"
          >
            <div class="photo hvr-bob">
              <img
                :src="item.imageSrc"
                class="img-fluid"
                @mouseenter="
                  isShow = true;
                  showPartner(index, $event);
                "
                @mouseleave="
                  isShow = false;
                  partnerData = {};
                "
              />
            </div>
            <p>{{ item.name }}</p>
          </div>
        </div>
        <div class="list">
          <div
            class="item"
            data-aos="fade-down"
            :data-aos-delay="100 * (index + 6)"
            data-aos-duration="500"
            v-for="(item, index) in partner.slice(6)"
            :key="index"
          >
            <div class="photo hvr-bob">
              <img
                :src="item.imageSrc"
                class="img-fluid"
                @mouseenter="
                  isShow = true;
                  showPartner(index + 6, $event);
                "
                @mouseleave="
                  isShow = false;
                  partnerData = {};
                "
              />
            </div>
            <p>{{ item.name }}</p>
          </div>
        </div>
        <div
          v-if="Object.keys(partnerData).length !== 0 && isShow"
          class="content"
        >
          <h5>{{ partnerData.title }}</h5>
          <p>{{ partnerData.subTitle }}</p>
          <hr />
          <p v-html="partnerData.text"></p>
        </div>
      </div>
      <div class="media-card">
        <h2>媒體報導支持</h2>
        <div class="hr"></div>
        <div class="title">
          <div class="date">日期</div>
          <div class="news-title">標題</div>
          <div class="press">媒體</div>
        </div>
        <div class="list">
          <div style="height: 325px; overflow: auto">
            <div v-for="(item, index) in media" class="report-row" :key="index">
              <div class="date">{{ item.date }}</div>
              <div class="news-title" @click="openModel(item)">
                <p>{{ item.title }}</p>
              </div>
              <div class="press">
                <a :href="item.link" target="_blank">
                  {{ item.media }}
                  <i class="fas fa-external-link-alt"></i>
                </a>
              </div>
            </div>
          </div>
          <div class="cover"></div>
        </div>
      </div>
    </div>
    <div class="milestone-card">
      <h2>普匯編年史</h2>
      <div class="hr"></div>
      <div class="timeline">
        <div
          v-for="(item, index) in milestone"
          class="block"
          data-aos="fade-up"
          data-aos-duration="500"
          :key="index"
        >
          <label class="icon"></label>
          <div class="event">
            <span class="date">{{ item.hook_date }}</span>
            <h3 class="title">{{ item.title }}</h3>
            <div class="content">{{ item.content }}</div>
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
            <p calss="report-date">{{ reportData.date }}</p>
            <div class="report-contert" v-html="reportData.content"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import bannerComponent from "../component/bannerComponent";

export default {
  components: {
    banner: bannerComponent,
  },
  data: () => ({
    isShow: false,
    regulations: [
      {
        title: "簡單",
        text:
          "直覺化UIUX介面設計，讓操作使用更簡單便利，第一次開啟使用就能上手",
        imageSrc: "/images/easy.svg",
      },
      {
        title: "快速",
        text:
          "全程手機線上申請，AI系統24小時不間斷驗證，提升作業速度與效率，加快用戶取得資金",
        imageSrc: "/images/fast.svg",
      },
      {
        title: "安全",
        text: "使用 Amazon Web Services雲端服務平台，個資絕不外洩",
        imageSrc: "/images/security.svg",
      },
      {
        title: "隱私",
        text:
          "全程無人系統驗證操作，從申請到取得款項，資訊完全不外洩，保障投資人與借款人各資隱密與隱私",
        imageSrc: "/images/privacy.svg",
      },
      {
        title: "低風險高報酬",
        text: "小額分散、分期還款、降低風險、複利效果，創造最高報酬",
        imageSrc: "/images/reward.svg",
      },
    ],
    media: [],
    partner: [],
    milestone: [],
    partnerData: {},
    bannerData: {},
    reportData: {},
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
      AOS.init();
    });
  },
  watch: {
    milestone() {
      this.$nextTick(() => {
        this.createSlick();
        this.timeline();
      });
    },
  },
  methods: {
    getBannerData() {
      axios
        .post(`${location.origin}/getBannerData`, { filter: "company" })
        .then((res) => {
          this.bannerData = res.data;
        });
    },
    getMilestoneData() {
      axios.post(`${location.origin}/getMilestoneData`).then((res) => {
        this.milestone = res.data;
      });
    },
    getMediaData() {
      axios.post(`${location.origin}/getMediaData`).then((res) => {
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
      axios.post(`${location.origin}/getPartnerData`).then((res) => {
        this.partner = res.data;
      });
    },
    createSlick() {
      $(this.$refs.advantage_slick).slick({
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        dots: false,
        arrows: false,
        responsive: [
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            },
          },
        ],
      });
    },
    showPartner(index, $event) {
      let $target = $($event.target);
      this.partnerData = this.partner[index];

      this.$nextTick(() => {
        $(".content")
          .css("top", $target.offset().top + $(".photo").outerHeight() + 40)
          .css(
            "left",
            $target.offset().left + $(".photo").outerWidth() / 2 - 160
          );
      });
    },
    timeline() {
      let $timeline_block = $(".cd-timeline-block");

      $timeline_block.each(function () {
        if (
          $(this).offset().top >
          $(window).scrollTop() + $(window).height() * 0.75
        ) {
          $(this)
            .find(".cd-timeline-img, .cd-timeline-content")
            .addClass("is-hidden");
        }
      });

      $(window).on("scroll", function () {
        $timeline_block.each(function () {
          if (
            $(this).offset().top <=
              $(window).scrollTop() + $(window).height() * 0.75 &&
            $(this).find(".cd-timeline-img").hasClass("is-hidden")
          ) {
            $(this)
              .find(".cd-timeline-img, .cd-timeline-content")
              .removeClass("is-hidden")
              .addClass("bounce-in");
          }
        });
      });
    },
  },
};
</script>

<style lang="scss">
.company-wrapper {
  width: 100%;

  h2 {
    font-weight: bolder;
    text-align: center;
    color: #061164;
  }

  .hr {
    width: 130px;
    height: 2px;
    background-image: linear-gradient(to right, #71008b, #ffffff);
    margin: 0px auto;
  }

  .child-bg {
    background-image: url("../asset/images/compony-bg.png");
    background-position: 0 0;
    background-repeat: no-repeat;
    background-size: 100% 100%;
  }

  .text-card {
    .a-hr {
      height: 125px;
      background-color: #94d6eb;
      position: relative;

      .a-s {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80%;
        background-color: #04769f;
        font-weight: bold;
        color: #ffffff;

        h3 {
          color: #ffffff;
          text-align: center;
          font-weight: bold;
          margin: 25px auto;
        }

        p {
          width: 80%;
          margin: 25px auto;
        }
      }
    }
  }

  .advantage-card {
    padding: 30px;
    .advantage-cnt {
      width: 80%;
      margin: 10px auto;
      overflow: hidden;

      .item {
        padding: 10px;
        margin: 10px;
        border-radius: 10px;
        box-shadow: 0 0 10px 0 #6ab0f2;
        background-color: #ffffff;

        &:nth-of-type(odd) {
          margin-top: 1rem;
        }
        &:nth-of-type(even) {
          margin-top: 5rem;
        }

        .img {
          width: 100%;
        }

        h5 {
          text-align: center;
          color: #8629a5;
          font-weight: bold;
          margin: 1rem 0px;
        }

        p {
          text-align: justify;
          color: #1f232c;
          font-weight: bold;
          margin: 0px;
        }
      }
    }
  }

  .media-card {
    padding: 30px;
    position: relative;
    overflow: hidden;

    %box {
      width: 80%;
      margin: 1.5rem auto;
      overflow-y: auto;
      border-radius: 10px;
      box-shadow: 0 0 10px 0 #6ab0f2;
      background-color: #ffffff;
    }

    %div {
      margin: 10px 0px;
      padding: 0px 10px;
    }

    .title {
      @extend %box;
      display: flex;
      color: #8629a5;

      div {
        @extend %div;
        text-align: center;
        font-weight: bold;
      }
    }

    .list {
      @extend %box;
      position: relative;
      height: 325px;

      .report-row {
        display: flex;
        font-size: 18px;

        div {
          @extend %div;
        }

        .news-title:hover {
          cursor: pointer;
          text-decoration: underline;
        }
      }

      .cover {
        z-index: 10;
        height: 14px;
        position: absolute;
        bottom: 0;
        width: 100%;
        background-image: linear-gradient(to top, #e5e5e5, #ededed);
        box-shadow: 0px 0px 10px 10px #ededed;
      }
    }

    .date {
      width: 12%;
      border-right: 1px solid #6ab0f2;
    }

    .news-title {
      width: 71%;
      border-right: 1px solid #6ab0f2;

      p {
        margin: 0px;
      }
    }

    .press {
      width: 17%;
    }
  }

  .partner-card {
    padding: 30px;

    .list {
      display: flex;
      padding: 10px;
      width: fit-content;
      margin: 10px auto;

      .slick-list {
        overflow: initial;
      }

      .item {
        margin: 0px 10px;
        text-align: center;
        width: 160px;

        .photo {
          width: 100px;
          height: 100px;
          background: #ffffff;
          padding: 10px;
          border-radius: 25%;
          overflow: hidden;
          box-shadow: 0 0 10px #6ab0f2;
          cursor: pointer;
          position: relative;
          margin-bottom: 10px;

          img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
          }
        }

        p {
          color: #828282;
          font-size: 13px;
        }
      }
    }

    .content {
      position: absolute;
      width: 300px;
      z-index: 1;
      border-radius: 10px;
      background: #ffffff;
      filter: drop-shadow(0px 0px 4px black);
      padding: 10px;
      transition-duration: 0.5s;
      text-align: end;

      h5 {
        font-weight: bold;
        color: #083a6e;
        text-align: center;
        font-size: 15px;
      }

      p {
        text-align: justify;
        font-size: 13px;
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

  .milestone-card {
    padding: 30px;
    overflow: hidden;
    text-align: center;

    .timeline {
      .block {
        width: 65%;
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

          h3 {
            font-weight: bold;
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

  @media screen and (max-width: 767px) {
    .child-bg {
      background-size: cover;
    }

    .text-card {
      display: none;
    }

    .advantage-card {
      padding: 10px;

      .advantage-cnt {
        width: 100%;

        .item {
          margin: 10px !important;
          box-shadow: 0 0 10px 0px #00000014;

          .img {
            img {
              margin: 0px auto;
            }
          }
        }
      }
    }

    .media-card {
      padding: 10px;

      %box {
        margin: 10px auto;
        width: 100%;
        box-shadow: 0 0 10px 0px #00000014;
      }

      .news-title {
        overflow: hidden;

        p {
          width: 400%;
        }
      }
      .press {
        width: 55%;
      }
      .date {
        display: none;
      }
    }

    .partner-card {
      padding: 10px;

      .list {
        display: block;
        width: 100%;
        overflow: hidden;

        .item {
          float: left;
          margin: 10px;
          width: calc(100% / 3 - 20px);

          .photo {
            box-shadow: 0 0 10px 0px #00000014;
          }

          p {
            display: none;
          }
        }
      }
    }

    .milestone-card {
      padding: 10px;

      .timeline {
        .block {
          width: 100%;

          .event {
            padding: 2rem 0rem;
            top: -1.5em;
            width: 65%;

            h3 {
              font-size: 20px;
            }
          }
        }
      }
    }
  }
}
</style>

