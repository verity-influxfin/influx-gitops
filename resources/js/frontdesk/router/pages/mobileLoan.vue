<template>
  <div class="mobile-wrapper">
    <div class="mobile-header">
      <div class="header-title">
        <img :src="'./Image/child_banner.jpg'" />
        <div class="title">手機分期</div>
      </div>
      <div class="banner-text">選擇喜歡的手機，無卡也能分期支付，輕鬆購買</div>
      <div class="header-img">
        <img :src="'./Image/sop_web.jpg'" class="img-fluid desktop" />
        <img :src="'./Image/sop_mobile.jpg'" class="img-fluid mobile" />
      </div>
    </div>
    <div class="mobile-content">
      <div v-for="(item,index) in this.mobileData" :key="index">
        <img :src="item.imageSrc" class="img-fluid" />
        <h3>ASUS Zenfone Max pro 2019</h3>
        <p>空機價＄{{item.price}}</p>
        <a
          class="btn btn-loan"
          href="https://event.influxfin.com/R/url?p=webbanner"
          target="_blank"
        >立即申請分期</a>
      </div>
    </div>
    <div class="banner-text">優良店家推薦</div>
    <div>
      <img :src="'./Image/mobile_banner_web.jpg'" class="img-fluid desktop" />
      <img :src="'./Image/mobile_banner_mobile.jpg'" class="img-fluid mobile" />
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    mobileData: []
  }),
  created() {
    this.getMobileData();
    $("title").text(`手機分期 - inFlux普匯金融科技`);
  },
  methods: {
    getMobileData() {
      axios.post("getMobileData").then(res => {
        this.mobileData = res.data;
      });
    }
  }
};
</script>

<style lang="scss">
.mobile-wrapper {
  .mobile-header {
    .header-title {
      width: 100%;
      overflow: hidden;
      position: relative;
      height: 160px;

      img {
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

    .header-img {
      text-align: center;
    }
  }

  .mobile-content {
    overflow: auto;

    div {
      float: left;
      width: 32%;
      height: 500px;
      margin: 10px;
      text-align: center;
      padding: 60px;

      img {
        max-width: 300px;
        max-height: 300px;
      }

      .btn-loan {
        border-radius: 30px;
        font-weight: 600;
        color: #ffffff;
        opacity: 0.8;
        background-color: #ff6a00;
      }
    }

    @media (max-width: 1023px) {
      div {
        width: 45%;
        padding: 0px;
      }
    }

    @media (max-width: 767px) {
      div {
        width: 94%;
        margin-bottom: 30px;
        padding: 0px;
      }
    }
  }

  .banner-text {
    font-size: 25px;
    font-weight: bolder;
    text-align: center;
    padding: 30px 0px;
    background-color: #f7f7f7;
  }

  @media (max-width: 767px) {
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
