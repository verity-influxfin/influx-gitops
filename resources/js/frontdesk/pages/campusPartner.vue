<template>
  <div class="campuspartner-wrapper">
    <banner :data="this.bannerData"></banner>
    <div class="content">
      <h4>《什麼是校園大使 ? 》</h4>
      <div class="text-card">
        <p>你有天馬行空的行銷創意卻沒有舞台付諸行動嗎？</p>
        <p>你是熱愛與人交流、活力滿點的大學生嗎？</p>
        <p>你厭倦普通的打工實習生活了嗎？</p>
        <p>inFlux普匯校園大使計畫讓你換個方式磨練自我，替未來的履歷增添色彩，勇敢跨出舒適圈，打造更精彩豐富的大學生活。</p>
        <p>加入校園大使計劃，你可以：</p>
        <ul>
          <li>提出各種天馬行空的想法，只要你敢說，我們就敢做！</li>
          <li>認識公司裡來自各領域的奇人異士，有時候新的點子就是在各種對談碰撞中產生的！</li>
          <li>深入了解金融科技產業，團隊的豐富閱歷以及不一樣的人生觀點絕對讓你受益無窮！</li>
        </ul>
      </div>
      <h4>《報名條件》</h4>
      <div class="text-card">
        <p>只要你是在學大專院校學生，而且不怕挑戰配合度高、活潑外向願意接受人群、熱情且積極就可以申請擔任PuHey!的校園大使哦！</p>
        <ul>
          <li>加分項目：臉書、IG擁有高關注度、經營其他個人部落格、人際關係良好交際面廣。</li>
        </ul>
      </div>
      <h4>《工作內容》</h4>
      <div class="text-card">
        <ul>
          <li>宣傳並協辦校園巡迴講座。</li>
          <li>積極推廣inFlux平台與產品。</li>
        </ul>
      </div>
      <h4>《薪酬制度》</h4>
      <div class="text-card">
        <ul>
          <li>明確獎金制度論，請留訊洽詢。</li>
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
    $("title").text(`校園大使 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {});
  },
  methods: {
    getBannerData() {
      axios.post("getBannerData", { filter: "campuspartner" }).then((res) => {
        this.bannerData = res.data;
      });
    },
    submitData(data) {
      data["type"] = "campus";

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
.campuspartner-wrapper {
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