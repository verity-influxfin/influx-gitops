<template>
  <div class="college-wrapper">
    <banner :data="this.bannerData"></banner>
    <div class="text-card">
      <h2>「夢想與生活的資金需求，普匯投資借給你！」</h2>
      <p>集結了各大學校友、老師，專門投資借貸同學在學期間的資金需求。</p>
      <p>不論是夢想實現，還是生活急需，只要下載普匯App，「3分鐘申請，10分鐘核准，1小時到帳」，全程AI線上媒合為你快速找到投資人！</p>
      <p>超過50000人都在使用普匯完成夢想、解決生活資金問題，同學都只找「普匯．你的手機ATM 」，提供簡單快速、隱私又安全的急用資金！</p>
    </div>
    <div class="experience-card">
      <h2>聽聽其他同學的聲音</h2>
      <div class="progress">
        <div
          class="progress-bar"
          role="progressbar"
          style="width: 75%"
          aria-valuenow="75"
          aria-valuemin="0"
          aria-valuemax="100"
        ></div>
      </div>
      <button class="btn btn-info comment"  @click="$root.goFeedback">
        <i class="fas fa-comments"></i>我要回饋
      </button>
      <div class="items">
        <div class="entry" v-for="(item,index) in experiences" :key="index">
          <div class="img">
            <img :src="item.imageSrc" class="img-fluid" />
          </div>
          <div class="member">
            <label>{{item.name}}/{{item.unit}}</label>
          </div>
          <div class="memo">{{item.memo}}</div>
        </div>
      </div>
      <p>我們服務了超過140所學校的同學</p>
    </div>
    <!-- <div class="partner-card">
      <h2>「最貼近年輕人的普匯金融科技」</h2>
      <p>我們服務了超過140所學校的同學</p>
      <div class="partner-row">
        <div class="item hvr-grow-shadow" v-for="(item,index) in partner" :key="index">
          <img class="img-fluid" :src="item.imageSrc" />
        </div>
      </div>
    </div>-->
    <div class="credit-card">
      <h2>「額度最高12萬，那利率呢？」</h2>
      <p>利率由AI為您量身打造的信用等級決定!</p>
      <div class="table">
        <div
          :class="['item',item.class]"
          :style="{ transform: rotate(index,'circle') }"
          v-for="(item,index) in creditList"
          @click="turn(index)"
          @mouseover="transform($event)"
          @mouseleave="recovery($event)"
          :key="index"
        >
          <div :style="{ transform: rotate(index,'text') }">{{item.level}}</div>
        </div>
        <div class="press">
          <div class="pointer">
            <img :src="'./Images/pointer.svg'" class="img-fluid" />
          </div>
          <div class="credit">{{credit}}</div>
        </div>
      </div>
      <span class="license">*利率會依每位用戶個別狀況而有所不同</span>
    </div>
    <div class="applyFlow-card">
      <h2>「不知道該如何申貸嗎？」</h2>
      <div class="flow">
        <div class="step">
          <h5>下載普匯influx | 選擇額度</h5>
          <hr />
          <p>1.註冊下載APP上傳申貸資料</p>
          <span>提供完整資訊，有助提高額度</span>
        </div>
        <div class="next">
          <img :src="'./Images/next.svg'" class="img-fluid" />
        </div>
        <div class="step">
          <h5>提供資訊 | 線上AI | 立即審核</h5>
          <hr />
          <p>2.上傳資料AI自動審核</p>
          <span>AI數據分析審核，全程無人打擾</span>
        </div>
        <div class="next">
          <img :src="'./Images/next.svg'" class="img-fluid" />
        </div>
        <div class="step">
          <h5>線上媒合投資人</h5>
          <hr />
          <p>3.線上供投資人認購</p>
          <span>審核成功後，立即上架幫您媒合投資人，最快15分鐘完成媒合</span>
        </div>
        <div class="next">
          <img :src="'./Images/next.svg'" class="img-fluid" />
        </div>
        <div class="step">
          <h5>媒合成功 | 資金到手</h5>
          <hr />
          <p>4.媒合成功、執行撥款</p>
          <span>線上媒合成功後，24小時款項收到款項，幫助優質新創企業成長茁壯</span>
        </div>
      </div>
      <div class="tips">
        <p>提醒您，先把下列資料準備好可以加快申貸速度喔！</p>
        <div class="required">
          <div class="item" v-for="(item,index) in applyData.requiredDocuments" :key="index">
            <div class="img">
              <img class="img-fluid" :src="item.imgSrc" />
            </div>
            <p>{{item.text}}</p>
            <span v-if="item.memo" v-html="item.memo"></span>
          </div>
        </div>
      </div>
    </div>
    <div class="download-card" :style="`background-image: url('./Images/19366.jpg')`">
      <div>
        <h2 style="text-align: center;">「最貼近年輕人的普匯金融科技」</h2>
        <img :src="'./Images/flow.png'" class="img-fluid" />
      </div>
    </div>
    <div class="qa-card">
      <h2>還有其他問題嗎?</h2>
      <div class="qa-row">
        <div class="qa-item" v-for="(item,index) in qaData.slice(0, 3)" :key="index">
          <p>{{item.title}}</p>
          <hr />
          <span v-html="item.content"></span>
        </div>
      </div>
      <div class="qa-row">
        <div class="qa-item" v-for="(item,index) in qaData.slice(3)" :key="index">
          <p>Q：{{item.title}}</p>
          <hr />
          <span v-html="item.content"></span>
        </div>
      </div>
      <div class="qa-row">
        <router-link class="btn link" style="margin:0px auto;" to="qa">
          更多問題
          <i class="fas fa-angle-double-right" />
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import bannerComponent from "../component/bannerComponent";

export default {
  components: {
    banner: bannerComponent
  },
  data: () => ({
    credit: "--",
    timeLineMax: "",
    qaData: [],
    partner: [],
    bannerData: {},
    applyData: {},
    creditList: [
      { level: 9, rate: "13%", class: "gary" },
      { level: 8, rate: "12%", class: "gary" },
      { level: 7, rate: "11%", class: "gary" },
      { level: 6, rate: "10%", class: "yellow" },
      { level: 5, rate: "9%", class: "yellow" },
      { level: 4, rate: "8%", class: "yellow" },
      { level: 3, rate: "7%", class: "green" },
      { level: 2, rate: "6%", class: "green" },
      { level: 1, rate: "5%", class: "green" }
    ]
  }),
  computed: {
    experiences() {
      let $this = this;
      let data = [];
      $.each($this.$store.getters.ExperiencesData, (index, row) => {
        if (row.type === "college") {
          data.push(row);
        }
      });

      return data;
    },
    video() {
      return this.$store.getters.VideoData.slice(0, 4);
    }
  },
  created() {
    this.$store.dispatch("getExperiencesData");
    this.getBannerData();
    this.getPartnerData();
    this.getQaData();
    this.getApplydata();
    $("title").text(`學生貸款 - ${$("title").text()}`);
  },
  mounted() {
    this.$nextTick(() => {
      $(this.$root.$refs.banner).hide();
      this.$root.pageHeaderOffsetTop = 0;
      AOS.init();
    });
  },
  methods: {
    getBannerData() {
      axios.post("getBannerData", { filter: "college" }).then(res => {
        this.bannerData = res.data;
      });
    },
    getPartnerData() {
      axios.post("getPartnerData").then(res => {
        this.partner = res.data;
      });
    },
    getApplydata() {
      axios.post("getApplydata", { filter: "college" }).then(res => {
        this.applyData = res.data;
      });
    },
    getQaData() {
      axios.post("getQaData", { filter: "college" }).then(res => {
        this.qaData = res.data;
      });
    },
    rotate(index, type) {
      let angle = window.outerWidth >= 767 ? 22.5 : 42.5;
      let distense = window.outerWidth >= 767 ? 200 : 100;
      let rotateFrom = (-angle * index) / 18;

      if (type === "circle") {
        return `rotate(${rotateFrom +
          index * angle -
          167}deg) translate(${distense}px, 0px)`;
      } else {
        return `rotate(${(rotateFrom + index * angle - 167) * -1}deg)`;
      }
    },
    turn(index) {
      let angle = window.outerWidth >= 767 ? 22.5 : 40.5;
      let dir = index * angle - (window.outerWidth >= 767 ? 90 : 96);

      this.credit = this.creditList[index].rate;

      gsap.to(".pointer", 0.5, {
        rotate: `${dir}dge`
      });
    },
    transform($event) {
      this.timeLineMax = new TimelineMax({ paused: true, reversed: true });
      this.timeLineMax.to($event.target, { scale: 1.2 });
      this.timeLineMax.play();
    },
    recovery($event) {
      this.timeLineMax.reverse();
    }
  }
};
</script>

<style lang="scss">
.college-wrapper {
  width: 100%;

  .progress {
    height: 4px;
  }

  .link {
    display: block;
    background: #006bda;
    color: #ffffff;
    width: 20%;
    margin: 0px auto;
    font-weight: bolder;

    i {
      margin-left: 10px;
    }

    &:hover {
      border: 2px solid #006bda;
      background: #ffffff;
      color: #006bda;
    }
  }

  .text-card {
    background: #006bda;
    padding: 30px;
    color: #ffffff;
    text-align: center;
  }

  .experience-card {
    padding: 30px;
    overflow: hidden;
    position: relative;
    background: #f9f9f9;

    .comment {
      position: absolute;
      top: 30px;
      right: 30px;
    }

    .items {
      display: flex;
      align-items: center;
      width: fit-content;
      animation: collegePcCarouselAnim 60s infinite alternate linear;
    }

    @media only screen and (max-width: 767px) {
      .items {
        animation: collegeMobileCarouselAnim 45s infinite alternate linear;
      }

      @keyframes collegeMobileCarouselAnim {
        from {
          transform: translate(0, 0);
        }
        to {
          transform: translate(calc(-100% + (250px)));
        }
      }
    }

    @keyframes collegePcCarouselAnim {
      from {
        transform: translate(0, 0);
      }
      to {
        transform: translate(calc(-100% + (6 * 250px)));
      }
    }

    .entry {
      position: relative;
      width: 250px;
      height: 250px;
      background: #fff;
      margin: 65px 1em 1em;
      padding: 0.5em;
      border-top-right-radius: 50%;
      box-shadow: 4px 4px 5px 0px rgba(0, 0, 0, 0.5);

      .img {
        width: 100px;
        margin: 0px auto;
        border-radius: 50%;
        position: absolute;
        top: -50px;
        border: 2px solid #ffb700;

        img {
          border-radius: 50%;
        }
      }

      .member {
        text-align: center;
        padding: 5px;
        transform: translate(0px, 52px);

        label {
          font-weight: bolder;
        }
      }

      .memo {
        padding: 10px;
        background: #3b81ff;
        border-radius: 10px;
        position: relative;
        color: #ffffff;
        transform: translate(0px, 52px);
      }
    }

    p {
      text-align: center;
      margin: 10px 0px;
      font-size: 25px;
      font-weight: bolder;
    }
  }

  .partner-card {
    background: #006bda;
    padding: 30px;
    color: #ffffff;
    text-align: center;

    .partner-row {
      overflow: hidden;
      width: fit-content;
      margin: 0px auto;

      .item {
        float: left;
        width: 145px;
        margin: 10px 25px;
        border-radius: 50%;
        overflow: auto;
        padding: 10px;
        background: #ffffff;
      }
    }
  }

  .credit-card {
    padding: 30px;
    text-align: center;
    background: #efefef;

    h2 {
      color: #006bda;
    }

    .table {
      overflow: hidden;
      width: 45%;
      height: 350px;
      margin: 0px auto;
      box-shadow: 0px 0px 10px #3130ff;
      background: #7176ff;
      border-radius: 15px;
      position: relative;

      .press {
        position: absolute;
        left: 50%;
        bottom: 17%;
        transform: translate(-50%, 0%);
        width: 140px;
        height: 140px;
        filter: drop-shadow(0px 0px 4px black);
        pointer-events: none;

        .pointer {
          height: 140px;
          position: absolute;
          left: 50%;
          top: 45%;
          transform: translate(-50%, -50%) rotate(230deg);
          transform-origin: 50% 100%;

          img {
            transform: translate(0px, 55%);
          }
        }

        .credit {
          position: absolute;
          left: 50%;
          bottom: 0px;
          transform: translate(-50%, 40%);
          width: 80px;
          height: 80px;
          line-height: 76px;
          text-align: center;
          background: #ffffff;
          border-radius: 50%;
          border: 4px solid #ffa000;
          font-size: 30px;
        }
      }

      .item {
        float: left;
        margin: 20px;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        font-size: 24px;
        box-shadow: 0 0 7px black;
        position: absolute;
        top: 60%;
        left: 47%;
        transform-origin: 0 100%;
        cursor: pointer;

        &.hvr-radial-out:before {
          background: #5d5d5d;
        }

        hr {
          margin: 0px 10px;
          border-top: 1px solid;
        }

        div {
          font-size: 40px;
          pointer-events: none;
        }
      }

      .gary {
        border: 3px solid #828282;
        background: #ffffff;
      }
      .yellow {
        border: 3px solid #ffdf00;
        background: #ffffff;
      }
      .green {
        border: 3px solid #00ffff;
        background: #ffffff;
      }
    }

    .license {
      font-size: 10px;
      color: gray;
    }
  }

  .applyFlow-card {
    background: #f6f6f6f6;
    padding: 30px;
    text-align: center;
    
    .flow {
      display: flex;
      text-align: initial;
      width: fit-content;
      margin: 0px auto;

      .step {
        border-radius: 10px;
        background: #ffffff;
        box-shadow: 0 0 5px #0074ff;
        padding: 10px;
        margin: 10px;
      }

      .next {
        width: 40px;
        height: 40px;
        margin: 5px;
        line-height: 213px;
      }
    }

    .tips {
      width: fit-content;
      margin: 10px auto;
      padding: 10px;
      background: #ffffff;
      box-shadow: 2px 2px 4px black;

      .required {
        width: fit-content;
        display: flex;
        margin: 0px auto;
        .item {
          padding: 10px;
          margin: 10px;
          border-radius: 10px;
          box-shadow: 0 0 5px black;
          background: #ffffff;

          .img {
            width: 50px;
            margin: 0px auto;
          }
        }
      }
    }
  }

  .download-card {
    background-repeat: no-repeat;
    background-size: 100% 100%;
    overflow: hidden;

    div {
      width: 54%;
      margin: 10px auto;
    }
  }

  .qa-card {
    padding: 30px;
    background: #ececec;
    overflow: hidden;

    h2 {
      text-align: center;
      color: #006bda;
    }

    .qa-row {
      overflow: hidden;
      display: flex;

      .qa-item {
        background: #ffffff;
        padding: 10px;
        margin: 10px;
        border-radius: 10px;
        box-shadow: 0 0 5px #0069ff;
        width: 31.5%;

        p {
          color: #000000;
        }

        hr {
          color: #000000;
        }

        span {
          color: #000000;
        }
      }
    }
  }

  @media screen and (max-width: 767px) {
    .link {
      width: 50%;
    }

    .experience-card {
      padding: 10px;

      h2 {
        font-size: 25px;
        line-height: 40px;
      }

      .comment {
        top: 10px;
        right: 10px;
      }

      p {
        font-size: 20px;
      }
    }

    .credit-card {
      padding: 10px;
      
      h2 {
        word-break: keep-all;
        font-size: 25px;
      }

      .table {
        width: 100%;
        height: 370px;

        .item {
          top: 25%;
          left: 45.5%;
        }

        .press {
          bottom: 50%;
        }
      }
    }

    .applyFlow-card {
      padding: 10px;

      h2 {
        word-break: keep-all;
        font-size: 30px;
      }

      .flow {
        display: block;

        .next {
          line-height: initial;
          margin: 0px auto;
          transform: rotate(90deg);
        }
      }

      .tips {
        width: 100%;

        .required {
          .item {
            margin: 5px;
          }
        }
      }
    }

    .download-card {
      div {
        width: 90%;

        h2 {
          font-size: 22px;
        }
      }
    }

    .qa-card {
      padding: 10px;

      .qa-row {
        display: block;

        .qa-item {
          width: 98%;
          margin: 2px 2px 12px 2px;
        }
      }
    }
  }
}
</style>
