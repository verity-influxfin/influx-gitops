<template>
  <div class="mobile-wrapper">
    <div class="search-line">
      <div class="input-custom">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" v-model="filter" />
        <i class="fas fa-times" v-if="filter" @click="filter = ''"></i>
      </div>
    </div>
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
    <div class="goods-card">
      <a
        href="https://event.influxfin.com/R/url?p=webbanner"
        target="_blank"
        class="item"
        v-for="(item,index) in this.filterMobileData"
        :key="index"
      >
        <div class="img">
          <img :src="item.phone_img" class="img-fluid" />
        </div>
        <h4>{{item.name}}</h4>
        <span>空機價 ${{format(item.price)}}</span>
      </a>
    </div>
    <div class="applyFlow-card">
      <h2>「不知道該如何申貸嗎？」</h2>
      <div class="flow">
        <div class="step">
          <span>step1.</span>
          <hr />
          <p>進入分期超市</p>
        </div>
        <div class="next">
          <img :src="'./images/next.svg'" class="img-fluid" />
        </div>
        <div class="step">
          <span>step2.</span>
          <hr />
          <p>選擇借款的身分</p>
        </div>
        <div class="next">
          <img :src="'./images/next.svg'" class="img-fluid" />
        </div>
        <div class="step">
          <span>step3.</span>
          <hr />
          <p>選擇手機型號廠牌</p>
        </div>
        <div class="next">
          <img :src="'./images/next.svg'" class="img-fluid" />
        </div>
        <div class="step">
          <span>step4.</span>
          <hr />
          <p>等待系統驗證</p>
        </div>
        <div class="next">
          <img :src="'./images/next.svg'" class="img-fluid" />
        </div>
        <div class="step">
          <span>step5.</span>
          <hr />
          <p>申請成功</p>
        </div>
      </div>
    </div>
    <div class="recommend-card">
      <div class="banner-text">優良店家推薦</div>
      <div class="mobile-footer">
        <img :src="'./images/mobile_banner_web.jpg'" class="img-fluid desktop" />
        <img :src="'./images/mobile_banner_mobile.jpg'" class="img-fluid mobile" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    filter: "",
    filterMobileData: [],
    mobileData: []
  }),
  created() {
    this.getMobileData();
    $("title").text(`手機分期 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      $(this.$root.$refs.banner).hide();
      this.$root.pageHeaderOffsetTop = 0;
      AOS.init();
    });
  },
  watch: {
    filter(newVal) {
      this.filterMobileData = [];
      this.mobileData.forEach((row, index) => {
        if (row.name.toLowerCase().indexOf(newVal.toLowerCase()) !== -1) {
          this.filterMobileData.push(row);
        }
      });
    }
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    getMobileData() {
      axios.post("getMobileData").then(res => {
        this.mobileData = res.data;
        this.filterMobileData = this.mobileData;
      });
    }
  }
};
</script>

<style lang="scss">
.mobile-wrapper {
  width: 100%;
  overflow: auto;

  .progress {
    height: 4px;
    width: 80%;
    margin: 0px auto;
  }

  .search-line {
    width: 80%;
    margin: 10px auto;
    position: relative;
    overflow: hidden;
    height: 40px;

    .input-custom {
      width: 300px;
      position: absolute;
      top: 0;
      right: 0;

      .form-control {
        padding: 5px 35px;
      }

      %iStyle {
        position: absolute;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 20px;
        color: #002bff;
        text-shadow: 0 0 4px #002bff;
      }

      .fa-search {
        @extend %iStyle;
        left: 10px;
      }

      .fa-times {
        @extend %iStyle;
        right: 10px;
        cursor: pointer;
      }
    }
  }

  .goods-card {
    width: 80%;
    margin: 0px auto;
    overflow: hidden;

    .item {
      width: 31%;
      float: left;
      border: 1px solid #2098d1;
      padding: 10px;
      margin: 10px;
      transition-duration: 0.5s;

      &:hover {
        color: #000000;
        text-decoration: none;
        box-shadow: inset 0 0 7px 3px #2098d1;
      }

      .img {
        width: 300px;
        height: 300px;
        margin: 0px auto;
        line-height: 300px;
      }
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
        line-height: 117px;
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

  .recommend-card {
    .banner-text {
      font-size: 25px;
      font-weight: bolder;
      text-align: center;
      padding: 30px 0px;
      background-color: #f7f7f7;
    }

    .mobile-footer {
      img {
        min-width: 100%;
      }
    }
  }

  @media (max-width: 767px) {
    .goods-card {
      width: 100%;

      .item {
        width: 96%;
        margin: 10px auto;
        float: none;
        display: block;
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

    .banner-text {
      font-size: 16px;
    }

    .desktop {
      display: none;
    }
  }

  @media (min-width: 767px) {
    .mobile {
      display: none;
    }
  }
}
</style>
