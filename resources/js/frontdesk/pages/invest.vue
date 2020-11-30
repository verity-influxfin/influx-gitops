<template>
  <div class="invest-wrapper">
    <banner :data="bannerData" :isInvest="true"></banner>
    <div class="desc-card">
      <div class="t-c"><h2>投資介紹</h2></div>
      <div class="hr"></div>
      <div class="box">
        <p>
          「債權投資」是近年慢慢興起的投資方式，透過購買借款人的債權，獲取每期回款本息的收益，在金融科技產業發展越來越成熟下，已經成為許多人最信賴的投資標的。
        </p>
        <p>
          投資不外乎就是追求創造額外的穩定收入，並且不用承擔高風險與流動性問題，但許多人不敢玩股票、不懂怎麼選擇債券，更沒有時間研究複雜的投資標的，「普匯債權」一定會是你最好的投資選擇。
        </p>
      </div>
      <div class="box">
        <div class="t-c"><h2>普匯債權特性</h2></div>
        <div class="hr"></div>
        <div class="gb">
          <div class="item">
            <h5>低風險</h5>
            <ul class="t">
              <li>平台逾期率不到3%</li>
              <li>投資標的皆為信用良好的學生、上班族</li>
              <li>小額分散</li>
              <li>完善貸後管理</li>
            </ul>
          </div>

          <div class="item">
            <h5>穩定高報酬</h5>
            <ul class="t">
              <li>平均年化報酬率8%~15%</li>
              <li>複利滾投</li>
              <li>公開透明的回款資訊</li>
              <li>不受市場波動影響</li>
            </ul>
          </div>

          <div class="item">
            <h5>操作簡單</h5>
            <ul class="t">
              <li>拍照證件上傳即可投資</li>
              <li>無需每日盯盤</li>
              <li>清晰的投資介面輕鬆選擇標的</li>
              <li>1,000元即可投資</li>
            </ul>
          </div>

          <div class="item">
            <h5>安全性高</h5>
            <ul class="t">
              <li>法律合約明確</li>
              <li>帳務透明、每月提供帳單發票</li>
              <li>專屬帳戶代收付款、隨時可提領</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="step-card">
      <div class="t-c"><h2>投資方式</h2></div>
      <div class="hr"></div>
      <div class="cnt">
        <div class="tab-content">
          <div id="optional" class="tab-pane fade active show">
            <span class="d-h">
              依據信用等級、就讀學校、申貸原因、年化報酬率、期數，自由選擇您喜好的投資標的。
            </span>
            <div class="pic">
              <img
                src="../asset/images/optional_invset.png"
                class="img-fluid"
              />
            </div>
          </div>
          <div id="smart" class="tab-pane fade">
            <span class="d-h">
              設定您的偏好的信用等級、年化報酬率、期數等自動下標，24小時不錯失優質案件！
            </span>
            <div class="pic">
              <img src="../asset/images/smart_invest.png" class="img-fluid" />
            </div>
          </div>
          <div id="quick" class="tab-pane fade">
            <span class="d-h"
              >申請人無提供最高學歷，同時您可以獲得更好的報酬率。</span
            >
            <div class="pic">
              <img src="../asset/images/quick_invest.png" class="img-fluid" />
            </div>
          </div>
        </div>
        <ul class="nav" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#optional">
              <div class="img">
                <img
                  src="../asset/images/invest_choose_optional.svg"
                  class="img-fluid"
                />
              </div>
              <div>
                <p>自選標的</p>
                <span>
                  依據信用等級、就讀學校、申貸原因、年化報酬率、期數，自由選擇您喜好的投資標的。
                </span>
              </div>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#smart"
              ><div class="img">
                <img
                  src="../asset/images/invest_choose_smart.svg"
                  class="img-fluid"
                />
              </div>
              <div>
                <p>智能投資</p>
                <span>
                  設定您的偏好的信用等級、年化報酬率、期數等自動下標，24小時不錯失優質案件！
                </span>
              </div>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#quick"
              ><div class="img">
                <img
                  src="../asset/images/invest_choose_quick.svg"
                  class="img-fluid"
                />
              </div>
              <div>
                <p>檢審速貸</p>
                <span>申請人無提供最高學歷，同時您可以獲得更好的報酬率。</span>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="game-card">
      <div class="invest-game">
        <div class="chart" ref="chart">
          <div class="invest-chart" ref="investChart"></div>
        </div>
        <div class="i-g-r">
          <div class="t-c"><h2>試算您投資收益</h2></div>
          <div class="hr"></div>
          <h5>
            想要財富自由、提早退休，<br />
            來看看普匯債權「複利滾投」所創造的投資報酬吧！
          </h5>
          <div class="option">
            <div class="item">
              <label>設定每月投入金額</label>
              <div>
                <i
                  class="fas fa-minus-circle pointer"
                  @click="amount > 0 ? (amount -= 1000) : ''"
                ></i>
                <input
                  type="range"
                  step="1000"
                  min="0"
                  max="100000"
                  class="slider"
                  v-model="amount"
                />
                <i
                  class="fas fa-plus-circle pointer"
                  @click="amount < 100000 ? (amount -= -1000) : ''"
                ></i>
              </div>
              <label>金額：{{ format(parseInt(amount)) }}</label>
            </div>
            <div class="item">
              <label>設定預投資利率</label>
              <div>
                <i
                  class="fas fa-minus-circle pointer"
                  @click="rate > 8 ? (rate -= 1) : ''"
                ></i>
                <input
                  type="range"
                  step="1"
                  min="8"
                  max="15"
                  class="slider"
                  v-model="rate"
                />
                <i
                  class="fas fa-plus-circle pointer"
                  @click="rate < 15 ? (rate -= -1) : ''"
                ></i>
              </div>
              <label>利率：{{ rate }}% （平均8%~15%）</label>
            </div>
            <div class="item">
              <label>選擇預計投資期間</label>
              <div>
                <i
                  class="fas fa-minus-circle pointer"
                  @click="time > 1 ? (time -= 1) : ''"
                ></i>
                <input
                  type="range"
                  step="1"
                  min="1"
                  max="20"
                  class="slider"
                  v-model="time"
                />
                <i
                  class="fas fa-plus-circle pointer"
                  @click="time < 20 ? (time -= -1) : ''"
                ></i>
              </div>
              <label>{{ format(parseInt(time)) }}年</label>
            </div>
          </div>
        </div>
      </div>
    </div>
    <apply
      title="投資簡單四步驟"
      :requiredDocuments="applyData.requiredDocuments"
      :step="applyData.step"
    />
    <div class="advantage-card">
      <div class="t-c"><h2>投資工具比一比</h2></div>
      <div class="hr" />
      <div class="adv-box">
        <div class="adv-item">
          <img src="../asset/images/invest_chart_b.png" class="img-fluid" />
        </div>
        <div class="adv-item">
          <img src="../asset/images/invest_chart_a.png" class="img-fluid" />
        </div>
        <div class="adv-item">
          <img src="../asset/images/invest_chart_c.png" class="img-fluid" />
        </div>
      </div>
    </div>
    <experience :experiences="experiences" title="用戶回饋" />
    <download :isLoan="false" :isInvest="true" />
    <qa :qaData="qaData" />
  </div>
</template>

<script>
import banner from "../component/bannerComponent";
import download from "../component/downloadComponent";
import experience from "../component/experienceComponent";
import qa from "../component/qaComponent";
import apply from "../component/applyComponent";

export default {
  components: {
    banner,
    experience,
    download,
    qa,
    apply,
  },
  data: () => ({
    tweenedReturnAll: 0,
    amount: 10000,
    time: 1,
    rate: 12,
    qaData: [],
    bannerData: {},
    applyData: {},
  }),
  computed: {
    experiences() {
      return this.$store.getters.ExperiencesData;
    },
    video() {
      return this.$store.getters.VideoData;
    },
  },
  created() {
    this.$store.dispatch("getExperiencesData", "invest");
    this.getQaData();
    this.getBannerData();
    this.getApplydata();
    $("title").text(`債權投資 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      AOS.init();
      this.createChart();
      this.createSlick(this.$refs.type_slick);
    });
  },
  watch: {
    amount(data) {
      this.amount = parseInt(data);
      this.createChart();
    },
    time(data) {
      this.time = parseInt(data);
      this.createChart();
    },
    rate(data) {
      this.rate = parseInt(data);
      this.createChart();
    },
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    createSlick(target) {
      $(target).slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        dots: false,
        arrows: false,
        speed: 1000,
        responsive: [
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            },
          },
        ],
      });
    },
    getBannerData() {
      axios
        .post("getBannerData", { filter: "invest" })
        .then((res) => {
          this.bannerData = res.data;
        })
        .catch((error) => {
          console.error("getBannerData 發生錯誤，請稍後再試");
        });
    },
    getApplydata() {
      axios
        .post(`${location.origin}/getApplydata`, { filter: "invest" })
        .then((res) => {
          this.applyData = res.data;
        });
    },
    getQaData() {
      axios
        .post(`${location.origin}/getQaData`, { filter: "invest" })
        .then((res) => {
          this.qaData = res.data;
        });
    },
    pmt(pv, rate, per) {
      let m_rate = rate / 1200;
      let nper = Math.pow(m_rate + 1, -per);
      let _pmt = Math.ceil((pv * m_rate) / (1 - nper));
      return _pmt;
    },
    createChart() {
      let $this = this;

      let perPeriod = [];
      let returnedPrincipal = [];
      let returnedinstalment = [];

      let _arrayAll = [];
      let _totalFlow = [];
      let _cashLast = 0;

      let i, j, k, l, q;
      for (i = 0; i < $this.time * 12; i++) {
        _totalFlow.push({ principle: 0, intrest: 0, principleRemind: 0 });
      }
      for (i = 0; i < $this.time * 12; i++) {
        let _pv = 0 === i ? 0 : $this.amount;
        for (j = 0; j < i; j++) {
          if (_arrayAll[j].length >= i) {
            _pv += _arrayAll[j][i - 1].amount;
          }
        }
        let _amount = 0;
        let _principle = 0;
        let _intrest = 0;
        let _currentRepamentSchedule = [];
        let _p = {
          amount: _amount,
          principle: _principle,
          intrest: _intrest,
          principleRemind: 0,
        };
        for (k = 0; k < i; k++) {
          _currentRepamentSchedule.push(_p);
        }
        let _pmt = $this.pmt(_pv, this.rate, 12);
        let _principleRemind = _pv;
        for (l = 0; l < 12; l++) {
          _intrest = Math.round(
            ((_principleRemind * this.rate) / 100 / 360) * 30
          );
          _principle = l < 12 ? _pmt - _intrest : _principleRemind;
          _amount = _principle + _intrest;
          _principleRemind -= _principle;
          if (_principleRemind <= 0) {
            _principleRemind = 0;
          }
          if (i + l < _totalFlow.length - 1) {
            _totalFlow[i + l].principle =
              _totalFlow[i + l].principle + _principle;
            _totalFlow[i + l].intrest = _totalFlow[i + l].intrest + _intrest;
            _totalFlow[i + l].principleRemind =
              _totalFlow[i + l].intrest + _principleRemind;
          } else if (i + l === _totalFlow.length - 1) {
            _totalFlow[i + l].principle =
              _totalFlow[i + l].principle + _principle;
            _totalFlow[i + l].intrest = _totalFlow[i + l].intrest + _intrest;
            _totalFlow[i + l].principleRemind =
              _totalFlow[i + l].intrest + _principleRemind;
            _cashLast += _principleRemind;
          }

          _p = {
            amount: _amount,
            principle: _principle,
            intrest: _intrest,
            principleRemind: _principleRemind,
          };
          _currentRepamentSchedule.push(_p);
        }
        _arrayAll.push(_currentRepamentSchedule);
      }

      let listRepayment = [];
      let listAmount = [];
      let listTotal = [];
      let xAxisData = [];
      let total = 0;
      let a = 0;

      for (q = 0; q < _totalFlow.length; q++) {
        let temp = _totalFlow[q];
        xAxisData.push(`${Math.floor(q / 12) + 1}/${(q % 12) + 1}`);
        listAmount.push((total += $this.amount));
        let t = 0;

        _arrayAll.forEach((item, index) => {
          if (item[q]) {
            t += item[q].amount;
          }
        });

        if (q === 0) {
          a += $this.pmt(t + $this.amount, $this.rate, 12) * 12;
        } else {
          a += $this.pmt(t + $this.amount, $this.rate, 12) * 12 - t;
        }
        listRepayment.push(t);
        listTotal.push(a);
      }

      $($this.$refs.investChart)
        .css("width", `${$($this.$refs.chart).innerWidth()}px`)
        .css("height", `${$($this.$refs.chart).innerHeight()}px`);

      let line_chart = echarts.init($this.$refs.investChart);

      let option = {
        legend: {
          data: [
            { name: "當期回款總額", icon: "path://M19,12l-10,0l0,1l10,0z" },
            { name: "累積投入金額", icon: "path://M19,12l-10,0l0,1l10,0z" },
            { name: "債權總額", icon: "path://M19,12l-10,0l0,1l10,0z" },
          ],
          selectedMode: false,
          right: window.outerWidth > 400 ? 0 : "auto",
          bottom: window.outerWidth > 400 ? 50 : "auto",
          textStyle: {
            color: "#fff",
          },
          width: window.outerWidth > 400 ? "50px" : "auto",
        },
        grid: {
          left: 30,
          top: window.outerWidth > 400 ? 10 : 30,
          right: window.outerWidth > 400 ? 130 : 30,
          bottom: 100,
        },
        title: {
          subtext: "( 您可回收的投資收益(本金+利息) )",
          subtextStyle: {
            color: "#ffffff",
            fontSize: 14,
          },
          right: window.outerWidth > 400 ? "auto" : "40%",
          bottom: "0",
        },
        dataZoom: {
          type: "slider",
          bottom: 40,
        },
        tooltip: {
          trigger: "axis",
          confine: true,
          formatter(params) {
            return `
              <div><span>${params[1].marker}${
              params[1].seriesName
            }：</span>${$this.format(params[1].value)}</div>
              <div><span>${params[0].marker}${
              params[0].seriesName
            }：</span>${$this.format(params[0].value)}</div>
              <div><span>${params[2].marker}${
              params[2].seriesName
            }：</span>${$this.format(params[2].value)}</div>
            `;
          },
        },
        xAxis: {
          type: "category",
          data: xAxisData,
          axisLine: {
            lineStyle: { color: "#ffffff" },
          },
          splitLine: {
            show: true,
            lineStyle: { color: "#ffffff" },
          },
          axisLabel: {
            color: "#ffffff",
          },
          axisTick: {
            show: false,
            alignWithLabel: false,
          },
        },
        yAxis: {
          type: "value",
          axisTick: {
            show: false,
          },
          axisLabel: {
            show: false,
          },
          axisLine: {
            lineStyle: { color: "#ffffff" },
          },
          splitLine: { show: false },
        },
        series: [
          {
            name: "當期回款總額",
            stack: "1",
            symbol: "circle",
            symbolSize: 1,
            itemStyle: {
              color: "#58a7f5",
            },
            lineStyle: {
              width: 2,
              color: "#58a7f5",
            },
            hoverAnimation: false,
            legendHoverLink: false,
            legendHoverLink: false,
            data: listRepayment,
            type: "line",
          },
          {
            name: "累積投入金額",
            stack: "1",
            symbol: "green",
            symbolSize: 1,
            itemStyle: {
              color: "#56a8fd",
            },
            barMaxWidth: "5",
            lineStyle: {
              width: 4,
              color: "#56a8fd",
            },
            hoverAnimation: false,
            legendHoverLink: false,
            legendHoverLink: false,
            data: listAmount,
            type: "bar",
          },
          {
            name: "債權總額",
            stack: "2",
            symbol: "circle",
            symbolSize: 1,
            itemStyle: {
              color: "#ffbe00",
            },
            lineStyle: {
              width: 2,
              color: "#ffbe00",
            },
            hoverAnimation: false,
            legendHoverLink: false,
            legendHoverLink: false,
            data: listTotal,
            type: "line",
          },
        ],
      };

      line_chart.setOption(option);
    },
  },
};
</script>

<style lang="scss">
.invest-wrapper {
  width: 100%;
  background: #fbfbfb;

  .t-c {
    background-image: linear-gradient(
      to right,
      #1e2973 0%,
      #319acf 50%,
      #1e2973 75%
    );
    background-clip: text;
    width: fit-content;
    color: #ffffff00;
    margin: 0px auto;

    h2 {
      font-weight: bolder;
    }
  }

  .hr {
    width: 130px;
    height: 2px;
    background-image: linear-gradient(to right, #0559ac, #ffffff);
    margin: 0px auto;
  }

  .desc-card {
    background-image: url("../asset/images/1s5vd1.svg");
    background-repeat: no-repeat;
    overflow: hidden;
    padding: 30px;

    .box {
      width: 1200px;
      margin: 1rem auto;
      overflow: auto;

      p {
        color: #8e8e8e;
        font-weight: bold;
      }

      .gb {
        display: flex;
        justify-content: center;
        margin: 1rem auto;
      }

      .item {
        width: 246px;
        float: left;
        margin: 10px;
        padding: 10px 0px;
        border-radius: 25px;
        border: solid 1px #1f55a0;
        background-color: #ffffff;

        h5 {
          text-align: center;
          font-weight: 700;
          color: #083a6e;
        }

        .t {
          padding-left: 20px;

          li {
            list-style: none;
            background-image: url("../asset/images/star.svg");
            background-position: 0 0;
            background-repeat: no-repeat;
            background-size: 20px 20px;
            padding-left: 20px;
            margin: 10px 0px;
          }
        }
      }
    }
  }

  .step-card {
    position: relative;
    padding: 30px;
    overflow: hidden;
    background-image: url("../asset/images/bg.svg");
    background-repeat: no-repeat;
    background-size: cover;

    .cnt {
      display: flex;
      width: fit-content;
      margin: 3rem auto;
    }

    .nav {
      display: flex;
      flex-direction: column;
      margin: 0px 1rem;

      .nav-link {
        display: flex;
        margin: 1.4rem 0px;

        .img {
          margin-right: 0.5rem;
          img {
            width: 76px;
            height: 76px;
          }
        }

        p {
          font-size: 24px;
          font-weight: 500;
          color: #157efb;
        }

        span {
          font-size: 16px;
          color: #032e4f;
        }
      }
    }

    .tab-pane.active {
      margin: 0px auto;
      width: fit-content;
      position: relative;

      .pic {
        width: 300px;
        animation: phone-waving 2s infinite alternate linear;
      }
    }

    .d-h {
      display: none;
    }

    .fade {
      transition-duration: 0.5s;
    }

    @keyframes phone-waving {
      0% {
        transform: translateY(-10px);
      }

      50% {
        transform: translateY(0px);
      }

      100% {
        transform: translateY(10px);
      }
    }
  }

  .game-card {
    overflow: hidden;
    padding: 40px 30px;
    background-image: linear-gradient(243deg, #002160 100%, #1f55a0 0%);

    .invest-game {
      margin: 20px auto;
      width: 1300px;
      display: flex;

      .chart {
        width: 56%;
        color: #000000;
        margin: 10px 5px;
        height: 500px;

        .invest-chart {
          text-align: start;
        }
      }

      .i-g-r {
        width: 44%;

        .t-c {
          margin: 0px;
          background-image: linear-gradient(
            to left,
            #3670d3 427%,
            #09d7f8 -3%,
            #2e84da -431%
          );
        }

        .hr {
          margin: 1rem 0px;
        }

        h5 {
          font-weight: 700;
          font-size: 20px;
          color: #ffffff;
        }

        .option {
          overflow: auto;
          margin: 10px 0px;

          .item {
            width: calc(100% - 10px);
            float: left;
            text-align: start;
            margin: 15px 0px;
            position: relative;
            display: flex;
            color: #ffffff;

            div {
              margin: 0px 15px;

              .pointer {
                cursor: pointer;
                color: #00d3dc;
              }

              .slider {
                width: calc(100% - 42px);
                height: 3px;
                outline: none;
                transition: opacity 0.2s;
                transform: translate(0%, -4px);
              }
            }

            label {
              &:nth-of-type(1) {
                width: 130px;
              }

              &:nth-of-type(2) {
                width: 215px;
              }
            }
          }
        }
      }
    }
  }

  .advantage-card {
    padding: 20px;
    overflow: hidden;

    .adv-box {
      display: flex;
      width: fit-content;
      margin: 0px auto;

      .adv-item {
        margin: 30px;
        &:nth-of-type(odd) {
          width: 30%;
        }

        &:nth-of-type(even) {
          width: 35%;
        }
      }
    }
  }

  [data-aos="fade-left"] {
    transform: translate3d(40px, 0, 0);
  }

  @media screen and (max-width: 767px) {
    h2 {
      font-size: 25px;
      margin-bottom: 20px;
    }

    .desc-card {
      padding: 10px;

      .box {
        width: 100%;
        margin: 1rem auto 0px auto;

        .gb {
          flex-direction: column;
        }

        .item {
          width: 100%;
          margin: 0px;
          padding: 10px;
          height: auto;
          border-radius: 0px;
          box-shadow: 0 0 black;
          border: 1px solid #dbdbdb;

          &:first-of-type {
            border-radius: 10px 10px 0px 0px;
          }

          &:last-of-type {
            border-radius: 0px 0px 10px 10px;
          }

          .img {
            width: 20%;
          }

          .i-c {
            width: 80%;

            .t {
              padding-left: 20px;
            }
          }
        }
      }
    }

    .step-card {
      padding: 10px 0px;

      .d-h {
        display: block;
        padding: 20px 10px;
      }

      .cnt {
        flex-direction: column;
        margin: 1rem auto;

        .nav {
          order: 1;
          flex-wrap: unset;
          flex-direction: row;
          margin: 0px;

          .nav-link {
            display: block;
            margin: 0px auto;
            padding: 5px;

            .img {
              margin: 0px auto;
              text-align: center;
            }

            p {
              text-align: center;
              font-size: 18px;
              margin: 0px;
            }

            span {
              display: none;
            }
          }

          .nav-item {
            width: 100%;
          }
        }

        .tab-content {
          order: 2;
        }

        .tab-pane.active {
          .pic {
            width: 75%;
            margin: 0px auto;
          }
        }
      }
    }

    .game-card {
      padding: 10px;

      .invest-game {
        width: 100%;
        flex-direction: column;
        margin: 0px auto;

        .i-g-r {
          width: 100%;
          order: 1;

          .t-c {
            margin: 0px auto;
          }

          .hr {
            margin: 1rem auto;
          }

          .option {
            .item {
              margin: 0px;
              width: 100%;

              div {
                margin: 0px;
              }
            }
          }
        }

        .chart {
          width: 100%;
          order: 2;
          margin: 0px;
          height: 300px;
        }
      }
    }

    .advantage-card {
      padding: 10px;

      .adv-box {
        flex-direction: column;

        .adv-item {
          width: 100% !important;
          margin: 10px 0px;

          &:nth-of-type(even) {
            order: 1;
          }

          &:nth-of-type(odd) {
            order: 2;
          }
        }
      }
    }
  }
}
</style>

