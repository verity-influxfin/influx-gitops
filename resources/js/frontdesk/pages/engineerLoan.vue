<template>
  <div class="engineer-wrapper">
    <banner :data="this.bannerData" :isInvest="false"></banner>
    <target
      :items="applyData.item"
      text="資訊/資工/資管相關科系的學生或從事相關職業，並提供專業技術證照，<br><br>馬上享有高額度、低利率的優惠！"
    ></target>
    <apply
      title="申貸簡便五步驟"
      :requiredDocuments="applyData.requiredDocuments"
      :step="applyData.step"
    />

    <div class="avg-card">
      <img src="../asset/images/circle.svg" class="img-fluid ib" />
      <div class="cnt">
        <div class="c-t">
          <div class="item">
            <div class="t-c"><h3>借款額度高達20萬</h3></div>
            <p>擴大額度，下載APP申請，24hr資金輕鬆到手！</p>
          </div>
          <div class="item">
            <div class="t-c"><h3>利率減免優惠5%起</h3></div>
            <p>在忙碌的上課、工作之餘，普匯讓您還款無負擔！</p>
          </div>
        </div>
        <div class="c-i">
          <div class="img">
            <img src="../asset/images/aaaaa.svg" class="img-fluid" />
          </div>
        </div>
        <div class="c-t">
          <div class="item">
            <div class="t-c"><h3>超快速5分鐘審核過件</h3></div>
            <p>
              全線上申請，拍照上傳，優先核准！<br />
              不耽誤你申請的時間，更不拖延您拿到資金的時間！
            </p>
          </div>
        </div>
      </div>
    </div>
    <credit
      :creditList="creditList"
      amount="200000"
      license="最高額度會根據您的申請身分而有所不同"
    />
    <experience :experiences="experiences" title="用戶回饋" />
    <download :isLoan="true" :isInvest="false" />
    <qa :qaData="qaData" />
  </div>
</template>

<script>
import banner from "../component/bannerComponent";
import download from "../component/downloadComponent";
import qa from "../component/qaComponent";
import apply from "../component/applyComponent";
import credit from "../component/creditComponent";
import target from "../component/targetComponent";
import experience from "../component/experienceComponent";

export default {
  components: {
    banner,
    download,
    qa,
    apply,
    target,
    credit,
    experience,
  },
  data: () => ({
    qaData: [],
    bannerData: {},
    applyData: {},
  }),
  computed: {
    experiences() {
      let $this = this;
      let data = [];
      $.each($this.$store.getters.ExperiencesData, (index, row) => {
        data.push(row);
      });
      return data;
    },
  },
  created() {
    this.$store.dispatch("getExperiencesData", "loan");
    this.getApplydata();
    this.getBannerData();
    this.getQaData();
    $("title").text(`資訊工程師專案 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
    });
  },
  methods: {
    getBannerData() {
      axios
        .post(`${location.origin}/getBannerData`, { filter: "engineer" })
        .then((res) => {
          this.bannerData = res.data;
        });
    },
    getApplydata() {
      axios
        .post(`${location.origin}/getApplydata`, { filter: "engineer" })
        .then((res) => {
          this.applyData = res.data;
        });
    },
    getQaData() {
      axios
        .post(`${location.origin}/getQaData`, { filter: "engineer" })
        .then((res) => {
          this.qaData = res.data;
        });
    },
    rotate(index, type) {
      let angle = window.outerWidth >= 767 ? 22.5 : 42.5;
      let distense = window.outerWidth >= 767 ? 200 : 100;
      let rotateFrom = (-angle * index) / 18;

      if (type === "circle") {
        return `rotate(${
          rotateFrom + index * angle - 167
        }deg) translate(${distense}px, 0px)`;
      } else {
        return `rotate(${(rotateFrom + index * angle - 167) * -1}deg)`;
      }
    },
    turn(index) {
      let angle = window.outerWidth >= 767 ? 22.5 : 40.5;
      let dir = index * angle - (window.outerWidth >= 767 ? 90 : 96);

      this.credit = this.creditList[index].rate;

      gsap.to(".pointer", 0.5, {
        rotate: `${dir}dge`,
      });
    },
    transform($event) {
      this.timeLineMax = new TimelineMax({ paused: true, reversed: true });
      this.timeLineMax.to($event.target, { scale: 1.2 });
      this.timeLineMax.play();
    },
    recovery($event) {
      this.timeLineMax.reverse();
    },
  },
};
</script>

<style lang="scss">
.engineer-wrapper {
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
    width: 130px;
    height: 2px;
    background-image: linear-gradient(to right, #0559ac, #ffffff);
    margin: 0px auto;
  }

  .avg-card {
    overflow: auto;
    position: relative;
    background-color: #ecedf1;

    .ib{
      width: 100%;
    }

    .cnt {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      display: flex;
      overflow: hidden;
      height: 687px;

      .c-t {
        width: 35%;
        overflow: hidden;
        padding: 20px;
        position: relative;
        height: fit-content;
        top: 50%;
        transform: translate(0px, -50%);

        .item {
          margin: 10rem 10px;

          .t-c {
            background-image: linear-gradient(
              to right,
              #306fca 0%,
              #09d4f6 50%,
              #306fca 75%
            );
            margin: 0px;
            h3 {
              font-size: 24px;
            }
          }

          p {
            font-size: 16px;
            font-weight: 500;
            line-height: 1.5;
            letter-spacing: 1px;
            text-align: left;
            color: #ffffff;
            height: 90px;
          }
        }
      }

      .c-i {
        width: 30%;
        margin: 2.5rem 0px;
        position: relative;

        .img {
          position: absolute;
          top: 45%;
          left: 40%;
          width: 100%;
          transform: translate(-50%, -50%);
          animation: i-c-float 2.5s ease-in-out infinite;
        }
      }
    }
  }

  @keyframes i-c-float {
    0% {
      transform: translate(-50%, -50%) translatey(-5px);
    }

    50% {
      transform: translate(-50%, -50%) translatey(5px);
    }

    100% {
      transform: translate(-50%, -50%) translatey(-5px);
    }
  }

  @media screen and (max-width: 767px) {
    h2 {
      font-size: 25px;
      margin-bottom: 20px;
    }

    .avg-card {
      background: #153a71;

      .ib {
        display: none;
      }

      .cnt {
        flex-direction: column;
        width: 100%;
        position: initial;
        transform: initial;
        padding: 10px;
        height: auto;

        .c-t {
          width: 100%;
          transform: initial;
          top: 0px;
          padding: 0px;

          .item {
            margin: 0px 10px 0rem 10px;

            p {
              height: auto;
            }
          }
        }

        .c-i {
          display: none;
        }
      }
    }
  }
}
</style>

