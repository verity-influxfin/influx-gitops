<template>
  <div class="company-wrapper">
    <div class="text-card">
      <img class="img-fluid ib" src="../asset/images/sd6v16.svg" />
      <div class="cnt">
        <h2>普匯相信每個年輕人，我們致力幫助他們完成人生的夢想</h2>
        <div class="hr-l"></div>
        <p>
          普匯金融科技不是銀行，我們是Fintech金融科技專家，由具備深厚的風險管控、金融產品設計經驗的團隊組成，<br />運用AI智能科技與安全風控模組，搭起投資人與借款人的橋樑。
        </p>
        <p>
          以「金融專業」為核心，「科技工具」為輔助，提供「最有溫度」的社群服務，拉近人與人的距離，<br />讓金融年輕化並串起社會閒置資源，幫助年輕人完成自我學習成長與創業夢想！
        </p>
      </div>
    </div>
    <div class="partner-card">
      <div class="t-c"><h2>合作夥伴</h2></div>
      <div class="hr"></div>
      <div class="list">
        <div
          class="item"
          data-aos="fade-down"
          :data-aos-delay="100 * index"
          data-aos-duration="500"
          v-for="(item, index) in partner"
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
      <div v-if="Object.keys(partnerData).length !== 0 && isShow" class="content">
        <h5>{{ partnerData.title }}</h5>
        <p>{{ partnerData.subTitle }}</p>
        <hr />
        <p v-html="partnerData.text"></p>
      </div>
    </div>
    <!-- <div class="milestone-card">
      <div class="t-c"><h2>普匯編年史</h2></div>
      <div class="hr"></div>
      <div class="scroll left" @click="scroll('left')">
        <img src="/images/n_a_pre.svg" class="img-fluid" />
      </div>
      <div class="chunk" ref="chunk">
        <div class="timeline">
          <div v-for="(item, index) in milestone" class="block" :key="index">
            <div class="po">
              <div
                v-if="index % 2 === 0"
                :class="[
                  'text',
                  { start: index === 0 },
                  { end: index === milestone.length - 1 },
                ]"
              >
                <div>
                  <h6>{{ item.hook_date.substr(0, 7) }}</h6>
                  <p>{{ item.title }}</p>
                </div>
              </div>
              <div
                v-else
                :class="[
                  'text',
                  { start: index === 0 },
                  { end: index === milestone.length - 1 },
                ]"
              >
                <div>
                  <h6>{{ item.hook_date.substr(0, 7) }}</h6>
                  <p>{{ item.title }}</p>
                </div>
              </div>

              <routeStart v-if="index === 0" :num="index + 1" :img="item.icon" />
              <routeEnd
                v-else-if="index === milestone.length - 1"
                :num="index + 1"
                :img="item.icon"
              />
              <routeDot v-else :num="index + 1" :img="item.icon" />
            </div>
          </div>
        </div>
      </div>
      <div class="scroll right" @click="scroll('right')">
        <img src="/images/n_a_next.svg" class="img-fluid" />
      </div>
    </div> -->
    <div class="media-card">
      <div class="t-c"><h2>媒體報導支持</h2></div>
      <div class="hr"></div>
      <div class="list">
        <div v-for="(item, index) in media" class="report-row" :key="index">
          <div class="press">
            <a :href="item.link" target="_blank">
              <img :src="`/images/${item.imgSrc}`" />
            </a>
          </div>
          <div class="news-title" @click="openModel(item)">
            <p>{{ item.title }}</p>
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
import banner from "../component/bannerComponent";
import routeStart from "../component/svg/routeStartComponent";
import routeDot from "../component/svg/routeDotComponent";
import routeEnd from "../component/svg/routeEndComponent";

export default {
  components: {
    banner,
    routeStart,
    routeDot,
    routeEnd,
  },
  data: () => ({
    isShow: false,
    media: [],
    partner: [],
    milestone: [],
    routeArr: [],
    partnerData: {},
    bannerData: {},
    reportData: {},
  }),
  created() {
    this.getMediaData();
    this.getPartnerData();
    $("title").text(`公司介紹 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
    });
  },
  methods: {
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
    showPartner(index, $event) {
      let $target = $($event.target);
      this.partnerData = this.partner[index];

      this.$nextTick(() => {
        $(".content")
          .css("top", $target.offset().top + $(".photo").outerHeight() + 40)
          .css("left", $target.offset().left + $(".photo").outerWidth() / 2 - 160);
      });
    },
    scroll(direction) {
      let scrollLeft = $(this.$refs.chunk).scrollLeft();
      if (direction === "left") {
        $(this.$refs.chunk).animate(
          {
            scrollLeft: scrollLeft - 200,
          },
          { duration: 1000, queue: false }
        );
      } else {
        $(this.$refs.chunk).animate(
          {
            scrollLeft: scrollLeft + 200,
          },
          { duration: 1000, queue: false }
        );
      }
    },
  },
};
</script>

<style lang="scss">
.company-wrapper {
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

  .text-card {
    position: relative;

    .ib {
      width: 100%;
    }

    h2 {
      color: #022564;
      font-weight: 700;
    }

    .cnt {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: fit-content;

      .hr-l {
        height: 1px;
        background-image: linear-gradient(to left, #16559b 0%, #319acf 50%, #16559b 0%);
        margin: 2rem auto;
        width: 400px;
      }

      p {
        font-size: 16px;
        font-weight: 600;
        text-align: center;
        color: #1c2a54;
      }
    }
  }

  .media-card {
    padding: 30px;
    position: relative;
    overflow: hidden;

    .list {
      width: 80%;
      margin: 1.5rem auto;
      overflow-y: auto;

      .report-row {
        float: left;
        width: calc(25% - 20px);
        margin: 10px;
        border-radius: 25px;
        background-image: linear-gradient(to bottom, #ffffff, #e4eeff);

        .press {
          border-radius: 20px;
          background: #ffffff;
          border: 1px solid #81c3f3;
          text-align: center;
          padding: 2.5rem 0px;

          img {
            height: 60px;
            width: auto;
          }
        }

        .news-title {
          font-size: 15px;
          font-weight: 700;
          text-align: center;
          color: #4a4a4a;
          margin: 15px 10px;

          &:hover {
            cursor: pointer;
            text-decoration: underline;
          }
        }
      }
    }
  }

  .partner-card {
    padding: 30px;

    .list {
      padding: 10px;
      margin: 10px auto;
      overflow: hidden;
      display: block;
      width: 930px;

      .slick-list {
        overflow: initial;
      }

      .item {
        margin: 0px 10px;
        text-align: center;
        width: 160px;
        float: left;

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
    padding: 30px 0px;
    overflow: hidden;
    text-align: center;
    position: relative;
    background-image: radial-gradient(circle at 50% 100%, #eef6ff, #ffffff 65%);

    .scroll {
      position: absolute;
      top: 50%;
      transform: translate(0px, -50%);
      z-index: 1;
      display: none;
    }

    .left {
      left: 10px;
    }

    .right {
      right: 10px;
    }

    .chunk {
      overflow-y: hidden;
      overflow-x: auto;
    }

    .timeline {
      display: flex;
      width: fit-content;
      margin-top: 20px;
      padding: 10px 20px;

      .block {
        &:nth-of-type(odd) {
          .po {
            .text {
              top: 16%;
            }
          }
        }

        &:nth-of-type(even) {
          .po {
            .text {
              bottom: -10%;

              div {
                position: absolute;
                bottom: 0;
                width: max-content;
              }
            }
          }
        }

        .po {
          width: 200px;
          position: relative;

          .start {
            transform: translate(-40%, -50%) !important;
          }

          .end {
            transform: translate(35px, -50%) !important;
          }

          .text {
            position: absolute;
            border-left: 1px solid #81c3f3;
            padding-left: 10px;
            left: 50%;
            transform: translate(0px, -50%);
            width: fit-content;
            height: 100px;

            h6 {
              font-size: 15px;
              color: #157efb;
              text-align: start;
            }

            p {
              text-align: start;
              font-size: 12px;
              color: #1c2a54;
              font-weight: 600;
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
    .text-card {
      padding: 20px 10px;

      h2 {
        font-size: 20px;
        text-align: center;
      }

      .ib {
        display: none;
      }

      .cnt {
        position: initial;
        transform: initial;
        width: 100%;

        .hr-l {
          margin: 1rem auto;
          width: 100%;
        }

        p {
          text-align: justify;
          font-size: 14px;
        }
      }
    }

    .partner-card {
      padding: 10px;

      .list {
        width: 100%;
        
        .item {
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
      .scroll {
        display: block;
        top: 55%;
      }
    }

    .media-card {
      padding: 10px;

      .list {
        width: 100%;
        margin: 10px 0px;

        .report-row {
          width: calc(50% - 10px);
          margin: 5px;

          .news-title {
            font-size: 13px;
            margin: 5px;
            height: 60px;

            P {
              margin: 0px;
            }
          }

          .press {
            padding: 1.5rem 0px;
            img {
              height: 35px;
            }
          }
        }
      }
    }
  }
}
</style>
