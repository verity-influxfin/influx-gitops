<template>
  <div class="firmcooperation-wrapper">
    <banner :data="this.bannerData"></banner>
    <div class="content">
      <h4>《商行合作》</h4>
      <div class="text-card">
        <p>普匯歡迎全國通訊設備零售商店一起攜手合作，提供普匯優質低利申貸方案,解決商家顧客因無卡或無法即時付款而漏失的商機。全程專人協助，合作手續簡便，系統服務快速，是您業務推廣好夥伴。歡迎立即來訊申請共創佳績！</p>
      </div>
      <h4>《合作方式》</h4>
      <div class="text-card">
        <ul>
          <li>普匯專員協助推廣教學</li>
          <li>提供推廣文宣</li>
          <li>每月結算推廣獎金（詳情請留言洽詢）</li>
        </ul>
      </div>
      <h4>《合作效益》</h4>
      <div class="text-card">
        <ul>
          <li>協助商家拓展更多客源</li>
          <li>幫助商家分擔收款風險</li>
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
    $("title").text(`商行合作 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {});
  },
  methods: {
    getBannerData() {
      axios.post("getBannerData", { filter: "firmcooperation" }).then((res) => {
        this.bannerData = res.data;
      });
    },
    submitData(data) {
      data["type"] = "firm";

      axios
        .post("action", data)
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
.firmcooperation-wrapper {
  .product-banner {
    z-index: -1;
  }

  .content {
    padding: 30px;
    width: 80%;
    margin: -35% auto 15px auto;
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
      margin: -36rem auto 0px auto;
      width: 95%;

      .text-card {
        padding: 10px;
      }
    }
  }
}
</style>