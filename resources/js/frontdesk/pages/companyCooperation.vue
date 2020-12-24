<template>
  <div class="companycooperation-wrapper">
    <banner :data="this.bannerData"></banner>
    <div class="content">
      <h4>《企業合作》</h4>
      <div class="text-card">
        <p>inFlux 你的手機ATM，你的事業好夥伴！誠意邀請線下零售、線上電商攜手合作，幫助您的客戶分期消費、加速成交，支付沒煩惱，與您共創銷售佳績！</p>
      </div>
      <h4>《合作方式》</h4>
      <div class="text-card">
        <ul>
          <li>企業提出合作申請，企業直接向學生推行普匯借貸方案</li>
          <li>平台提供企業相關金融科技產品服務</li>
          <li>相關行銷渠道相互曝光，拓展原有客群以外市場</li>
        </ul>
      </div>
      <h4>《合作效益》</h4>
      <div class="text-card">
        <ul>
          <li>提供企業用戶多一重支付模式</li>
          <li>協助商家拓展更多年輕客源</li>
        </ul>
      </div>
      <h4>《報名方式》</h4>
      <inputFrom @submit="submitData" :errorMessage="errorMessage" :key="componentKey" />
    </div>
  </div>
</template>

<script>
import bannerComponent from "../component/bannerComponent";
import inputFromComponent from "../component/inputFromComponent";

export default {
  components: {
    banner: bannerComponent,
    inputFrom: inputFromComponent,
  },
  data: () => ({
    componentKey: 0,
    bannerData: "",
    errorMessage: "",
  }),
  created() {
    this.getBannerData();
    $("title").text(`企業合作 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {});
  },
  methods: {
    getBannerData() {
      axios
        .post(`${location.origin}/getBannerData`, { filter: "companycooperation" })
        .then((res) => {
          this.bannerData = res.data;
        });
    },
    submitData(data) {
      data["type"] = "company";

      axios
        .post(`${location.origin}/action`, data)
        .then((res) => {
          this.componentKey += 1;
          this.errorMessage = "";
          alert("登錄成功，敬請等待專員連繫您！");
        })
        .catch((error) => {
          let errorsData = error.response.data;
          let messages = [];

          $.each(errorsData.errors, (key, item) => {
            item.forEach((message, k) => {
              messages.push(message);
            });
          });

          this.errorMessage = messages.join("、");
        });
    },
  },
};
</script>

<style lang="scss">
.companycooperation-wrapper {
  .product-banner {
    z-index: -1;
  }

  .content {
    padding: 30px;
    width: 80%;
    margin: -32rem auto 1rem auto;
    box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
    background: #ffffff;

    h4 {
      font-weight: bolder;
    }

    .text-card {
      padding: 10px 30px;
    }
  }

  @media screen and (max-width: 767px) {
    .content {
      padding: 10px;
      margin: 1rem auto;
      width: 95%;

      .text-card {
        padding: 10px;
      }
    }
  }
}
</style>