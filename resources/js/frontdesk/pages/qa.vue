<template>
  <div class="qaPage-wrapper">
    <div class="header">
      <h3 class="title">對平台有任何疑問嗎？ 這裡為你解答</h3>
      <p class="sub-title">請輸入問題關鍵字</p>
      <div class="input-custom">
        <i class="fas fa-search"></i>
        <input type="text" class="form-control" placeholder="ask something..." autocomplete="off" v-model="filter" />
        <i class="fas fa-times" v-if="filter" @click="filter = ''"></i>
      </div>
      <ul class="nav" role="tablist">
        <li class="nav-item">
          <a class="btn nav-link active" data-toggle="tab" href="#loan">借款相關</a>
        </li>
        <li class="nav-item">
          <a class="btn nav-link" data-toggle="tab" href="#invest">投資相關</a>
        </li>
        <li class="nav-item">
          <a class="btn nav-link" data-toggle="tab" href="#afterLoan">還款相關</a>
        </li>
      </ul>
    </div>
    <div class="tab-content">
      <template v-if="filter">
        <template
          v-if="this.borrow.length === 0 && this.invest.length === 0 && this.default.length === 0"
        >
          <div class="empty">
            <div class="empty-img">
              <img src="../asset/images/empty.svg" class="img-fluid" />
            </div>
            <h3>沒有結果</h3>
            <p>根據您的搜索，我們似乎找不到結果</p>
          </div>
        </template>
        <template v-else>
          <div id="loan" class="qa-wrapper">
            <h2>借款相關</h2>
            <div class="qa-accordion" id="qa_contentloanData">
              <div class="card" v-for="(item,index) in borrow" :key="index">
                <div
                  class="card-header collapsed"
                  data-toggle="collapse"
                  :data-target="`#collapseloanData${index}`"
                  aria-expanded="true"
                >
                  <span class="accicon">
                    <i class="fas fa-angle-down rotate-icon"></i>
                  </span>
                  <span class="title">Q{{index+1}}：{{item.title}}</span>
                </div>
                <div
                  :id="`collapseloanData${index}`"
                  class="collapse"
                  data-parent="#qa_contentloanData"
                >
                  <div class="card-body">
                    <div>
                      A{{index+1}}：
                      <br />
                      <p v-html="item.content"></p>
                    </div>
                    <img
                      v-for="(src,index) in item.imgSrc"
                      :src="src"
                      :key="index"
                      :width="1/item.imgSrc.length*75+'%'"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="invest" class="qa-wrapper">
            <h2>投資相關</h2>
            <div class="qa-accordion" id="qa_contentinvestData">
              <div class="card" v-for="(item,index) in invest" :key="index">
                <div
                  class="card-header collapsed"
                  data-toggle="collapse"
                  :data-target="`#collapseinvestData${index}`"
                  aria-expanded="true"
                >
                  <span class="accicon">
                    <i class="fas fa-angle-down rotate-icon"></i>
                  </span>
                  <span class="title">Q{{index+1}}：{{item.title}}</span>
                </div>
                <div
                  :id="`collapseinvestData${index}`"
                  class="collapse"
                  data-parent="#qa_contentinvestData"
                >
                  <div class="card-body">
                    <div>
                      A{{index+1}}：
                      <br />
                      <p v-html="item.content"></p>
                    </div>
                    <img
                      v-for="(src,index) in item.imgSrc"
                      :src="src"
                      :key="index"
                      :width="1/item.imgSrc.length*75+'%'"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="afterLoan" class="qa-wrapper">
            <h2>還款相關</h2>
            <div class="qa-accordion" id="qa_contentafterLoanDataData">
              <div class="card" v-for="(item,index) in this.default" :key="index">
                <div
                  class="card-header collapsed"
                  data-toggle="collapse"
                  :data-target="`#collapseafterLoanDataData${index}`"
                  aria-expanded="true"
                >
                  <span class="accicon">
                    <i class="fas fa-angle-down rotate-icon"></i>
                  </span>
                  <span class="title">Q{{index+1}}：{{item.title}}</span>
                </div>
                <div
                  :id="`collapseafterLoanDataData${index}`"
                  class="collapse"
                  data-parent="#qa_contentafterLoanDataData"
                >
                  <div class="card-body">
                    <div>
                      A{{index+1}}：
                      <br />
                      <p v-html="item.content"></p>
                    </div>
                    <img
                      v-for="(src,index) in item.imgSrc"
                      :src="src"
                      :key="index"
                      :width="1/item.imgSrc.length*75+'%'"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </template>
      </template>
      <template v-else>
        <div id="loan" class="qa-wrapper tab-pane fade active show">
          <h2>借款相關</h2>
          <div class="qa-accordion" id="qa_contentloanData">
            <div class="card" v-for="(item,index) in borrow" :key="index">
              <div
                class="card-header collapsed"
                data-toggle="collapse"
                :data-target="`#collapseloanData${index}`"
                aria-expanded="true"
              >
                <span class="accicon">
                  <i class="fas fa-angle-down rotate-icon"></i>
                </span>
                <span class="title">Q{{index+1}}：{{item.title}}</span>
              </div>
              <div
                :id="`collapseloanData${index}`"
                class="collapse"
                data-parent="#qa_contentloanData"
              >
                <div class="card-body">
                  <div>
                    A{{index+1}}：
                    <br />
                    <p v-html="item.content"></p>
                  </div>
                  <img
                    v-for="(src,index) in item.imgSrc"
                    :src="src"
                    :key="index"
                    :width="1/item.imgSrc.length*75+'%'"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="invest" class="qa-wrapper tab-pane fade">
          <h2>投資相關</h2>
          <div class="qa-accordion" id="qa_contentinvestData">
            <div class="card" v-for="(item,index) in invest" :key="index">
              <div
                class="card-header collapsed"
                data-toggle="collapse"
                :data-target="`#collapseinvestData${index}`"
                aria-expanded="true"
              >
                <span class="accicon">
                  <i class="fas fa-angle-down rotate-icon"></i>
                </span>
                <span class="title">Q{{index+1}}：{{item.title}}</span>
              </div>
              <div
                :id="`collapseinvestData${index}`"
                class="collapse"
                data-parent="#qa_contentinvestData"
              >
                <div class="card-body">
                  <div>
                    A{{index+1}}：
                    <br />
                    <p v-html="item.content"></p>
                  </div>
                  <img
                    v-for="(src,index) in item.imgSrc"
                    :src="src"
                    :key="index"
                    :width="1/item.imgSrc.length*75+'%'"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div id="afterLoan" class="qa-wrapper tab-pane fade">
          <h2>還款相關</h2>
          <div class="qa-accordion" id="qa_contentafterLoanDataData">
            <div class="card" v-for="(item,index) in this.default" :key="index">
              <div
                class="card-header collapsed"
                data-toggle="collapse"
                :data-target="`#collapseafterLoanDataData${index}`"
                aria-expanded="true"
              >
                <span class="accicon">
                  <i class="fas fa-angle-down rotate-icon"></i>
                </span>
                <span class="title">Q{{index+1}}：{{item.title}}</span>
              </div>
              <div
                :id="`collapseafterLoanDataData${index}`"
                class="collapse"
                data-parent="#qa_contentafterLoanDataData"
              >
                <div class="card-body">
                  <div>
                    A{{index+1}}：
                    <br />
                    <p v-html="item.content"></p>
                  </div>
                  <img
                    v-for="(src,index) in item.imgSrc"
                    :src="src"
                    :key="index"
                    :width="1/item.imgSrc.length*75+'%'"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    filter: "",
    qaData: [],
    borrow: [],
    invest: [],
    default: [],
  }),
  created() {
    this.getQaData();
    $("title").text(`常見問題 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {});
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
      newVal = newVal.toLowerCase();
      this.qaData.forEach((row, index) => {
        row.title = row.title.toLowerCase();
        row.content = row.content.toLowerCase();
        if ( row.title.indexOf(newVal) !== -1 || row.content.indexOf(newVal) !== -1 ) {
          var tempData = $.extend(true, {}, row);
          if(newVal != "" ){
            tempData.content = tempData.content.replaceAll(new RegExp(newVal, 'g'),"<span class='highlight'>" + newVal + "</span>");
          }
          if (tempData.type === "borrow") {
            this.borrow.push(tempData);
          } else if (tempData.type === "invest") {
            this.invest.push(tempData);
          } else if (tempData.type === "default") {
            this.default.push(tempData);
          }
        }
      });
      $('.qaPage-wrapper .collapse').addClass('show');
    },
  },
  methods: {
    getQaData() {
      axios
        .get(
          "https://cors-anywhere.herokuapp.com/https://d3imllwf4as09k.cloudfront.net/json/qa.json"
        )
        .then((res) => {
          this.qaData = res.data.QA;
        });
    },
  },
};
</script>

<style lang="scss">
.qaPage-wrapper {
  overflow: hidden;
  position: relative;
  width: 100%;

  .header {
    padding: 4rem 0px;
    background-image: url("../asset/images/13887.png");
    background-position: 0 0;
    background-repeat: no-repeat;
    background-size: 100% 100%;

    .title {
      text-align: center;
      color: #ffffff;
      font-weight: bold;
      font-size: 2.5rem;
    }

    .sub-title {
      text-align: center;
      margin: 2rem auto;
      color: #ffffff;
      font-size: 1.5rem;
    }

    .input-custom {
      width: 60%;
      position: relative;
      margin: 2rem auto;

      .form-control {
        padding: 5px 40px;
        height: 50px;
        border-radius: 30px;
        box-shadow: 0 0 10px 0px #000000c9;
      }

      %iStyle {
        position: absolute;
        top: 50%;
        transform: translate(0, -50%);
        font-size: 20px;
        color: #083a6e;
      }

      .fa-search {
        @extend %iStyle;
        left: 15px;
      }

      .fa-times {
        @extend %iStyle;
        right: 15px;
        cursor: pointer;
      }
    }

    .nav {
      margin: 10px auto;
      display: flex;
      width: fit-content;

      a {
        margin: 10px;
        font-weight: bold;
        font-size: 20px;
        transition-duration: 0.5s;
        background: #ffffff;
        box-shadow: 0 0 3px 0px #000000ed;

        &:hover {
          color: #ffffff;
          background: #000000;
        }
      }
    }
  }

  .qa-wrapper {
    padding: 2%;

    h2 {
      font-weight: bolder;
      text-align: center;
      color: #083a6e;
    }

    .qa-accordion {
      padding-bottom: 10px;
      width: 50%;
      margin: 0px auto;

      .card {
        border: 0px solid #ffffff;
        margin: 20px auto;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);

        .card-header {
          cursor: pointer;
          border-bottom: none;
          color: #ffffff;
          background: #005ec1;

          .title {
            font-size: 17px;
            margin-right: 10px;
            font-weight: bolder;
          }

          .accicon {
            float: right;
            font-size: 20px;
            width: 1.2em;
          }

          &:not(.collapsed) {
            .rotate-icon {
              transform: rotate(180deg);
            }
          }

          &:hover {
            color: #000000;
          }
        }

        .card-body {
          border-top: 1px solid #ddd;

          img {
            max-height: 400px;
          }
        }
      }
    }
  }

  .empty {
    text-align: center;
    margin: 30px auto;

    .empty-img {
      width: 200px;
      margin: 20px auto;
    }

    h3 {
      font-weight: bold;
    }
  }

  @media screen and (max-width: 767px) {
    .header {
      padding: 1rem 0px;

      .title {
        display: none;
      }

      .sub-title {
        margin: 1rem auto;
      }

      .input-custom {
        width: 90%;
        margin: 1rem auto;
      }

      .nav {
        a {
          margin: 5px;
          padding: 5px 10px;
        }
      }
    }

    .qa-wrapper {
      .qa-accordion {
        width: 100%;
      }
    }
  }
}
</style>





