<template>
  <div class="recruiting-wrapper">
    <banner :data="this.bannerData"></banner>
    <div class="content">
      <h4>《人才招募》</h4>
      <div class="jobs-card">
        <div class="jobs-item">
          <div class="title">網路行銷企劃主管</div>
          <hr />
          <ul class="list">
            <li>線上運營業務拓展與績效管理</li>
            <li>強化品牌形象</li>
            <li>規劃行銷活動及效益分析</li>
            <li>線上通路渠道優化</li>
            <li>外部合作</li>
            <li>其他主管交辦</li>
          </ul>
        </div>
        <div class="jobs-item">
          <div class="title">網頁前端工程師</div>
          <hr />
          <ul class="list">
            <li>熟悉 Vue.js 者佳</li>
            <li>具備 HTML、CSS、Bootstrap、jQuery 技術</li>
            <li>具備 RWD與UX/UI觀念，以提升網站使用者體驗</li>
            <li>良好溝通能力，團隊配合度高，抗壓性高</li>
          </ul>
        </div>
      </div>
      <hr />
      <div class="contact">
        <h4>《聯絡方式》</h4>
        <p>聯絡人: 林小姐</p>
        <p>E-mail: mori@influxfin.com</p>
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
    bannerData: ""
  }),
  created() {
    this.getBannerData();
    $("title").text(`徵才服務 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      $(this.$root.$refs.banner).hide();
      this.$root.pageHeaderOffsetTop = 0;
    });
  },
  methods: {
    getBannerData() {
      axios.post("getBannerData", { filter: "recruiting" }).then(res => {
        this.bannerData = res.data;
      });
    }
  }
};
</script>

<style lang="scss">
.recruiting-wrapper {
  .product-banner {
    img {
      bottom: 0%;
    }
  }

  .content {
    padding: 30px;

    h4 {
      text-align: center;
    }

    .jobs-card {
      display: flex;

      hr {
        border-top: 2px solid #a5a5a5;
        margin: 5px 0px;
      }

      .jobs-item {
        background: #ffffff;
        margin: 10px;
        border: 2px solid #cdd2ff;
        width: 35%;
        padding: 30px;

        .title {
          font-size: 20px;
          font-weight: bolder;
        }

        .list li {
          list-style: decimal;
        }
      }
    }

    .contact {
      width: fit-content;
      padding: 10px;
      background: #ffffff;
      margin: 10px auto;
      text-align: center;
    }
  }

  @media screen and (max-width: 767px) {
    .content {
      padding: 10px;

      .jobs-card {
        flex-direction: column;

        .jobs-item {
          width: initial;
        }
      }
    }
  }
}
</style>