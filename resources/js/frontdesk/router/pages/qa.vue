<template>
  <div class="qaPage-wrapper">
    <div class="header">
      <h3 class="title">對平台有任何疑問嗎？ 這裡為你解答</h3>
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
      <div class="input-custom">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" v-model="filter" />
        <i class="fas fa-times" v-if="filter" @click="filter = ''"></i>
      </div>
      <div class="switch">
        <a href="#loan">借款常見問題</a>
        <a href="#invest">投資常見問題</a>
        <a href="#afterLoanData">貸後常見問題</a>
      </div>
    </div>
    <div class="content">
      <div id="loan">
        <qa :data="borrow" category="loanData" title="借款常見問題" :hideLink="true"></qa>
      </div>
      <div id="invest">
        <qa :data="invest" category="invest" title="投資常見問題" :hideLink="true"></qa>
      </div>
      <div id="afterLoanData">
        <qa :data="this.default" category="afterLoanData" title="貸後常見問題" :hideLink="true"></qa>
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
    filter: "",
    qaData: [],
    borrow: [],
    invest: [],
    default: []
  }),
  created() {
    this.getQaData();
    $("title").text(`常見問題 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      $(this.$root.$refs.banner).hide();
      this.$root.pageHeaderOffsetTop = 0;
    });
  },
  watch: {
    qaData(newVal) {
      newVal.forEach((row, index) => {
        if (row.type === "borrow") {
          this.borrow.push(row);
        } else if (row.type === "invest") {
          this.invest.push(row);
        } else if (row.type === "default") {
          this.default.push(row);
        }
      });
    },
    filter(newVal) {
      this.borrow = [];
      this.invest = [];
      this.default = [];

      this.qaData.forEach((row, index) => {
        if (row.title.toLowerCase().indexOf(newVal.toLowerCase()) !== -1 || row.content.toLowerCase().indexOf(newVal.toLowerCase()) !== -1) {
          if (row.type === "borrow") {
            this.borrow.push(row);
          } else if (row.type === "invest") {
            this.invest.push(row);
          } else if (row.type === "default") {
            this.default.push(row);
          }
        }
      });
    }
  },
  methods: {
    getQaData() {
      axios
        .get(
          "https://cors-anywhere.herokuapp.com/https://d3imllwf4as09k.cloudfront.net/json/qa.json"
        )
        .then(res => {
          this.qaData = res.data.QA;
        });
    }
  }
};
</script>

<style lang="scss">
.qaPage-wrapper {
  padding: 30px;
  overflow: hidden;
  position: relative;

  .progress {
    height: 4px;
  }

  .header {
    position: relative;
    overflow: hidden;
    width: 75%;
    margin: 10px auto;

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

    .switch {
      text-align: center;
      padding: 10px;
      margin: 10px 10px 0px 10px;

      a {
        padding: 10px 15px;
        border: 2px solid #000000;
        margin: 0px 20px;
        font-size: 20px;
        transition-duration: 0.5s;

        &:hover {
          text-decoration: none;
          background: #000000;
          color: #ffffff;
        }
      }
    }
  }

  .content {
    width: 75%;
    margin: 0px auto;
    overflow: hidden;
  }
}
</style>





