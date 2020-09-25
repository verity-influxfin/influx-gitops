<template>
  <div class="clubcooperation-wrapper">
    <banner :data="this.bannerData"></banner>
    <div class="content">
      <h4>《inFlux 校園社團贊助方案》</h4>
      <div class="text-card">
        <p>inFlux普匯 與全台大學院校社團系所合作，協辦大型校際活動、提供校園贊助、金融科技專業議題講座等合作，是最挺年輕人的金融科技團隊。足跡以涉及銘傳熱舞社、全國學聯、北區熱舞…以及各院校財經系所講演等 都能看到普匯金融科技的投入。不論你是學會、球隊或是社團…，只要需要，寫下你們的社團需求與故事，普匯立即出現！</p>
      </div>
      <h4>《合作項目》</h4>
      <div class="text-card">
        <ul>
          <li>贊助合作</li>
          <li>活動協辦</li>
          <li>成為校園推廣單位</li>
          <li>邀約講演</li>
          <li>其他</li>
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
    $("title").text(`社團合作 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {});
  },
  methods: {
    getBannerData() {
      axios.post(`${location.origin}/getBannerData`, { filter: "clubcooperation" }).then((res) => {
        this.bannerData = res.data;
      });
    },
    submitData(data) {
      data["type"] = "club";

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
.clubcooperation-wrapper {
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