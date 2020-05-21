<template>
  <div class="qaPage-wrapper">
    <div class="qaPage-header">
      <div class="header-title">
        <img :src="'./image/child_banner.jpg'" />
        <div class="title">常見問題</div>
        <div class="qa-btn-row">
          <a href="#loan">借款常見問題</a>
          <a href="#invest">投資常見問題</a>
          <a href="#afterLoanData">貸後常見問題</a>
        </div>
      </div>
    </div>
    <div class="qaPage-content">
      <div id="loan">
        <qa :data="this.qaData.loanData" category="loanData" title="借款常見問題" :hideLink="true"></qa>
      </div>
      <div id="invest">
        <qa :data="this.qaData.investData" category="invest" title="投資常見問題" :hideLink="true"></qa>
      </div>
      <div id="afterLoanData">
        <qa
          :data="this.qaData.afterLoanData"
          category="afterLoanData"
          title="貸後常見問題"
          :hideLink="true"
        ></qa>
      </div>
    </div>
  </div>
</template>

<script>
import qaComponent from "./component/qaComponent";

export default {
  components: {
    qa: qaComponent
  },
  data: () => ({
    qaData: {}
  }),
  created() {
    this.getQaData();
    $("title").text(`常見問題 - inFlux普匯金融科技`);
  },
  methods: {
    getQaData() {
      axios.post("getQaData",{filter: "qa"}).then(res => {
        this.qaData = res.data;
      });
    }
  }
};
</script>

<style lang="scss">
.qaPage-wrapper {
  .qaPage-header {
    .header-title {
      width: 100%;
      overflow: hidden;
      position: relative;
      height: 210px;

      img {
        position: absolute;
        height: 210%;
      }

      .title {
        position: absolute;
        top: 35%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: #ffffff;
        font-weight: bolder;
        font-size: 33px;
      }

      .qa-btn-row {
        position: absolute;
        left: 50%;
        top: 65%;
        transform: translate(-50%, -50%);
        width: fit-content;

        a {
          border: 2px solid #ffffff;
          padding: 10px 20px;
          margin: 0px 30px;
          color: #ffffff;

          &:hover {
            text-decoration: none;
          }
        }
      }

      @media (max-width: 767px) {
        height: 340px;

        .title {
          top: 15%;
        }

        .qa-btn-row {
          top: 60%;
          display: grid;

          a {
            margin: 20px 30px;
          }
        }
      }
    }
  }
}
</style>





