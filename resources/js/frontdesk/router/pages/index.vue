<template>
  <div class="index-wrapper">
    <div class="product-card">
      <h2>產品列表</h2>
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
      <router-link
        v-for="(item,index) in this.services"
        :to="item.link"
        :class="['card-item',{'hvr-sweep-to-left':item.isActive,'off':!item.isActive}]"
        :key="index"
      >
        <img :src="item.imageSrc" />
        <div class="cv">
          <h3>{{item.title}}</h3>
          <hr />
          <p v-html="item.desc"></p>
        </div>
        <div class="cover" v-if="!item.isActive">
          <span>coming soon</span>
        </div>
      </router-link>
    </div>
    <div class="game-card">
      <div class="loan-game">
        <h2>額度試算</h2>
        <div class="option">
          <div class="item" v-for="(item,index) in creditRatingItem" :key="index">
            <div class="circle" @click="item.checked = !item.checked;changeCredit(index);">
              <img :src="item.img" :class="['img-fluid',{'gary':!item.checked} ]" />
            </div>
            <span>{{item.text}}</span>
          </div>
        </div>
        <div class="creditNum">
          <div class="circle">
            <div class="total">
              <p>最高可達：</p>
              <strong>5,000～</strong>
              <span :class="moneyClass">{{format(tweenedMoney)}}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="invest-game">
        <h2>投報試算</h2>
        <div class="option">
          <div class="item">
            <label>期初投入金額：{{format(parseInt(principal))}}</label>
            <div>
              <i
                class="fas fa-minus-circle pointer"
                @click="principal > 0 ? principal -= 5000 : ''"
              ></i>
              <input
                type="range"
                step="5000"
                min="0"
                max="5000000"
                class="slider"
                v-model="principal"
              />
              <i
                class="fas fa-plus-circle pointer"
                @click="principal < 5000000 ? principal -= -5000 : ''"
              ></i>
            </div>
          </div>
          <div class="item">
            <label>每期{{amount >= 0 ? `投入` :`領出`}}金額：{{format(parseInt(amount))}}</label>
            <div>
              <i
                class="fas fa-minus-circle pointer"
                @click="amount > -100000 ? amount -= 1000 : ''"
              ></i>
              <input
                type="range"
                step="1000"
                min="-100000"
                max="100000"
                class="slider"
                v-model="amount"
              />
              <i class="fas fa-plus-circle pointer" @click="amount < 100000 ? amount -= -1000 :''"></i>
            </div>
          </div>
          <div class="item">
            <label>投資操作時間(年)：{{format(parseInt(time))}}</label>
            <div>
              <i class="fas fa-minus-circle pointer" @click="time > 1 ? time -= 1 : ''"></i>
              <input type="range" step="1" min="1" max="20" class="slider" v-model="time" />
              <i class="fas fa-plus-circle pointer" @click="time < 20 ? time -= -1 : ''"></i>
            </div>
          </div>
          <div class="item">
            <label>標的期數：{{format(parseInt(instalment))}}</label>
            <div>
              <i class="fas fa-minus-circle pointer" @click="instalment > 3 ? instalment -= 3 :''"></i>
              <input type="range" step="3" min="3" max="24" class="slider" v-model="instalment" />
              <i class="fas fa-plus-circle pointer" @click="instalment < 24 ? instalment -= -3 :''"></i>
            </div>
          </div>
          <div class="item">
            <label>標的年利率：{{format(parseInt(rate))}}%</label>
            <div>
              <i class="fas fa-minus-circle pointer" @click="rate > 2 ? rate -= 1 : ''"></i>
              <input type="range" step="1" min="2" max="20" class="slider" v-model="rate" />
              <i class="fas fa-plus-circle pointer" @click="rate < 20 ? rate -= -1 : ''"></i>
            </div>
          </div>
          <div class="item">
            <label>平台回款手續費：{{format(parseInt(handlingFee))}}%</label>
            <div>
              <i class="fas fa-minus-circle pointer" @click="handlingFee> 0? handlingFee -= 1 : ''"></i>
              <input type="range" step="1" min="0" max="3" class="slider" v-model="handlingFee" />
              <i class="fas fa-plus-circle pointer" @click="handlingFee< 3 ? handlingFee -= -1 :''"></i>
            </div>
          </div>
        </div>
        <div class="chart">
          <div class="invest-chart" ref="investChart"></div>
        </div>
      </div>
    </div>
    <div class="advantage-card">
      <div class="title">以金融為核心，以科技為輔具，普匯給您前所未有的專業金融APP</div>
      <div class="content">
        <div class="item" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="400">
          <div class="desc">
            <p>最專業的金融專家</p>
            <div class="img">
              <img :src="'./Image/best1.png'" class="img-fluid" />
            </div>
            <span>普匯擁有近20年金融專業經驗，深度理解各類金融產品、相關金融法規、財稅務、金流邏輯...等。能針對不同產業產品與市場，設計出更適合用戶需求的金融服務。</span>
          </div>
        </div>
        <div class="item" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="800">
          <div class="desc">
            <p>最先進的AI科技系統</p>
            <span>普匯擁有完善的金融科技技術，包含: 反詐欺反洗錢系統、競標即時撮合系統、 風控信評/線上對保、自動撥貸/貸後管理、 分秒計息等，不斷與時俱進迭代優化。</span>
            <div class="img">
              <img :src="'./Image/best2.png'" class="img-fluid" />
            </div>
          </div>
        </div>
        <div class="item" data-aos="fade-up" data-aos-duration="1500" data-aos-delay="1200">
          <div class="desc">
            <p>簡單、快速、安全、隱私</p>
            <div class="img">
              <img :src="'./Image/best3.png'" class="img-fluid" />
            </div>
            <span>視覺化簡潔好用的操作介面，運用先進科技與AWS 安全系統，保護您的個資絕不外洩，讓您在步入圓夢捷徑的同時，安全又放心。</span>
          </div>
        </div>
      </div>
    </div>
    <div class="experience-card">
      <h2>用戶回饋</h2>
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
      <button class="btn btn-info comment">
        <i class="fas fa-comments"></i>我要回饋
      </button>
      <div class="items">
        <div class="entry" v-for="(item,index) in experiences" :key="index">
          <div class="member">
            <div class="img">
              <img :src="item.imageSrc" class="img-fluid" />
            </div>
            <label>
              {{item.name}}
              <br />
              {{item.unit}}
            </label>
          </div>
          <div class="memo">{{item.memo}}</div>
        </div>
      </div>
    </div>
    <div class="information-card">
      <ul class="nav" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#news">最新消息</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#knowledge">小學堂</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#video">小學堂影音</a>
        </li>
      </ul>
      <div class="tab-content">
        <div id="news" class="tab-pane fade active show">
          <div class="news-slick" ref="news_slick">
            <router-link
              class="slick-item hvr-float-shadow"
              v-for="(item,index) in news"
              :key="index"
              :to="item.link"
            >
              <img :src="item.media_link" class="img-fluid" style="width: 362px;height:180px" />
              <span>{{item.post_date}}</span>
              <hr />
              <p>{{item.post_title}}</p>
            </router-link>
          </div>
          <router-link class="btn btn-secondary btn-lg" to="news" style="width: 50%;">
            最新消息&ensp;
            <i class="fas fa-external-link-alt"></i>
          </router-link>
        </div>
        <div id="knowledge" class="tab-pane fade">
          <div class="knowledge-slick" ref="knowledge_slick">
            <router-link
              class="slick-item hvr-float-shadow"
              v-for="(item,index) in knowledge"
              :key="index"
              :to="item.link"
            >
              <img :src="item.media_link" class="img-fluid" style="width: 362px;height:180px" />
              <span>{{item.post_date}}</span>
              <hr />
              <p>{{item.post_title}}</p>
            </router-link>
          </div>
          <router-link class="btn btn-secondary btn-lg" to="blog" style="width: 50%;">
            小學堂&ensp;
            <i class="fas fa-external-link-alt"></i>
          </router-link>
        </div>
        <div id="video" class="tab-pane fade">
          <div class="video-slick" ref="video_slick">
            <router-link
              class="slick-item hvr-float-shadow"
              v-for="(item,index) in video"
              :to="item.link"
              :key="index"
            >
            <div style="width:fit-content;margin:0px auto">
              <iframe
                :src="item.video_link"
                frameborder="0"
                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen
                style="height:180px;"
              ></iframe>
            </div>
              <span>{{item.post_date}}</span>
              <hr />
              <p>{{item.post_title}}</p>
            </router-link>
          </div>
          <router-link class="btn btn-secondary btn-lg" to="vlog/share" style="width: 50%;">
            小學堂影音&ensp;
            <i class="fas fa-external-link-alt"></i>
          </router-link>
        </div>
      </div>
    </div>
    <div class="slogan-card">
      <div class="logo">
        <img :src="'./Image/logo_.png'" class="img-fluid" />
      </div>
      <div class="slogan">
        <div class="content">
          <h4>普匯相信每個年輕人，我們致力幫助他們完成人生的夢想</h4>
          <h4>以金融為核心，以科技為輔具，只為給您最好的體驗</h4>
          <h4>最貼近年輕人的金融科技平台</h4>
          <h4>普匯．你的手機ATM</h4>
        </div>
      </div>
    </div>
    <div class="download-card" :style="`background-image: url('./Image/19366.jpg')`"></div>
  </div>
</template>

<script>
export default {
  data: () => ({
    principal: 0,
    amount: 0,
    time: 1,
    instalment: 3,
    rate: 2,
    money: 0,
    tweenedMoney: 0,
    handlingFee: 1,
    moneyClass: "default",
    creditRatingItem: [
      {
        text: "實名認證",
        img: "./Image/realname.png",
        checked: false,
        money: 10000
      },
      {
        text: "金融帳號",
        img: "./Image/account_.png",
        checked: false,
        money: 10000
      },
      {
        text: "社交認證",
        img: "./Image/soucial.png",
        checked: false,
        money: 20000
      },
      {
        text: "電子信箱",
        img: "./Image/email.png",
        checked: false,
        money: 10000
      },
      {
        text: "財力證明",
        img: "./Image/financial.png",
        checked: false,
        money: 20000
      },
      {
        text: "學生身分",
        img: "./Image/student.png",
        checked: false,
        money: 40000
      },
      {
        text: "最高學歷",
        img: "./Image/education_.png",
        checked: false,
        money: 30000
      },
      {
        text: "工作認證",
        img: "./Image/work.png",
        checked: false,
        money: 40000
      },
      {
        text: "聯合徵信",
        img: "./Image/credit.png",
        checked: false,
        money: 50000
      }
    ],
    services: []
  }),
  computed: {
    experiences() {
      return this.$store.getters.ExperiencesData;
    },
    video() {
      return this.$store.getters.VideoData.slice(0, 8);
    },
    knowledge() {
      let $this = this;
      $.each($this.$store.getters.KnowledgeData, (index, row) => {
        $this.$store.getters.KnowledgeData[
          index
        ].post_content = `${row.post_content
          .replace(/(<([^>]+)>)/gi, "")
          .substr(0, 80)}...`;
      });
      return $this.$store.getters.KnowledgeData.slice(0, 8);
    },
    news() {
      return this.$store.getters.NewsData.slice(0, 8);
    }
  },
  created() {
    this.$store.dispatch("getExperiencesData");
    this.$store.dispatch("getKnowledgeData");
    this.$store.dispatch("getNewsData");
    this.$store.dispatch("getVideoData", { category: "share" });
    this.getServiceData();
    $("title").text(`首頁 - inFlux普匯金融科技`);
  },
  mounted() {
    this.$nextTick(() => {
      this.createChart();
    });
    AOS.init();
  },
  watch: {
    news(data) {
      this.$nextTick(() => {
        this.createSlick(this.$refs.news_slick, data);
      });
    },
    knowledge(data) {
      this.$nextTick(() => {
        this.createSlick(this.$refs.knowledge_slick, data);
      });
    },
    video(data) {
      this.$nextTick(() => {
        this.createSlick(this.$refs.video_slick, data);
      });
    },
    principal(data) {
      this.principal = parseInt(data);
      this.createChart();
    },
    amount(data) {
      this.amount = parseInt(data);
      this.createChart();
    },
    time(data) {
      this.time = parseInt(data);
      this.createChart();
    },
    instalment(data) {
      this.instalment = parseInt(data);
      this.createChart();
    },
    rate(data) {
      this.rate = parseInt(data);
      this.createChart();
    },
    handlingFee(data) {
      this.handlingFee = parseInt(data);
      this.createChart();
    },
    money(data) {
      if (data < 50000) {
        this.moneyClass = "defluat";
      } else if (50000 <= data && data < 100000) {
        this.moneyClass = "soso";
      } else if (100000 <= data && data < 150000) {
        this.moneyClass = "ordinary";
      } else if (150000 <= data && data < 200000) {
        this.moneyClass = "good";
      } else if (200000 <= data) {
        this.moneyClass = "awesome";
      }

      gsap.to(this.$data, { duration: 0.5, tweenedMoney: data });
    }
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    getServiceData() {
      axios.post("getServiceData").then(res => {
        this.services = res.data;
      });
    },
    createSlick(tar, data) {
      $(tar).slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        customPaging(slider, i) {
          return '<i class="fas fa-circle"></i>';
        },
        prevArrow: '<i class="fas fa-chevron-left arrow-left"></i>',
        nextArrow: '<i class="fas fa-chevron-right arrow-right"></i>',
        responsive: [
          {
            breakpoint: 1023,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1
            }
          },
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      });
    },
    changeCredit(index) {
      if (this.creditRatingItem[index].checked) {
        this.money += this.creditRatingItem[index].money;
      } else if (!this.creditRatingItem[index].checked && this.money > 0) {
        this.money -= this.creditRatingItem[index].money;
      }
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
        let _pv = 0 === i ? $this.principal : $this.amount;
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
          principleRemind: 0
        };
        for (k = 0; k < i; k++) {
          _currentRepamentSchedule.push(_p);
        }
        let _pmt = $this.pmt(_pv, $this.rate, $this.instalment);
        let _principleRemind = _pv;
        for (l = 0; l < $this.instalment; l++) {
          _intrest = Math.round(
            ((_principleRemind * $this.rate) / 100 / 360) * 30
          );
          _principle =
            l < $this.instalment ? _pmt - _intrest : _principleRemind;
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
            principleRemind: _principleRemind
          };
          _currentRepamentSchedule.push(_p);
        }
        _arrayAll.push(_currentRepamentSchedule);
      }

      let listPrinciple = [];
      let listIntrest = [];
      let listEveryFlow = [];
      let xAxisData = [];

      for (q = 0; q < _totalFlow.length; q++) {
        let temp = _totalFlow[q];
        xAxisData.push(
          q % 12 === 0
            ? `${Math.floor(q / 12) + 1}/1`
            : `${Math.floor(q / 12) + 1}/${(q % 12) + 1}`
        );
        listEveryFlow.push(q < _totalFlow.length - 1 ? $this.amount : 0);
        listPrinciple.push(
          Math.round((temp["principle"] * 1 * (100 - $this.handlingFee)) / 100)
        );
        listIntrest.push(
          Math.round((temp["intrest"] * 1 * (100 - $this.handlingFee)) / 100)
        );
      }

      let _totalIncome =
        listPrinciple[listPrinciple.length - 1] +
        listIntrest[listIntrest.length - 1];

      let _investAll =
        $this.principal +
        ($this.amount > 0 ? $this.amount * ($this.time * 12 - 1) : 0);

      let _returnAll = _totalIncome + _cashLast;

      let _valueTotal =
        _totalIncome +
        _cashLast -
        $this.principal -
        $this.amount * ($this.time * 12 - 1); //期末回款+本金餘額-期初投入-每期投入總額

      let _last2Return =
        listPrinciple[listPrinciple.length - 2] +
        listIntrest[listIntrest.length - 2] +
        $this.amount;

      let title =
        _totalIncome < 0 || _last2Return < 0 || _totalIncome + _cashLast < 0
          ? "領取金額超過每月複投金額"
          : `投資總額：${this.format(_investAll)} | 回款總額：${this.format(
              _returnAll
            )} = ${this.format(_totalIncome)}(期末回款) + ${this.format(
              _cashLast
            )}(本金餘額) | 預期獲利：${this.format(_valueTotal)}`;

      let line_chart = echarts.init($this.$refs.investChart);

      let option = {
        grid: {
          left: 30,
          top: 60,
          right: 30,
          bottom: 60
        },
        title: {
          subtext: title,
          subtextStyle: {
            color: "#000000",
            fontSize: 14
          },
          left: "center"
        },
        dataZoom: {
          type: "slider"
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
          }
        },
        xAxis: {
          type: "category",
          boundaryGap: false,
          data: xAxisData
        },
        yAxis: {
          type: "value",
          axisTick: {
            show: false
          },
          axisLabel: {
            show: false
          }
        },
        series: [
          {
            name: "本期回款本金",
            stack: "1",
            symbol: "circle",
            symbolSize: 1,
            itemStyle: {
              color: "orange"
            },
            lineStyle: {
              color: "orange"
            },
            hoverAnimation: false,
            legendHoverLink: false,
            legendHoverLink: false,
            data: listPrinciple,
            type: "line"
          },
          {
            name: "每期投入",
            stack: "1",
            symbol: "green",
            symbolSize: 1,
            itemStyle: {
              color: "green"
            },
            lineStyle: {
              color: "green"
            },
            hoverAnimation: false,
            legendHoverLink: false,
            legendHoverLink: false,
            data: listEveryFlow,
            type: "line"
          },
          {
            name: "本期回款利息",
            stack: "2",
            symbol: "circle",
            symbolSize: 1,
            itemStyle: {
              color: "lightblue"
            },
            lineStyle: {
              color: "lightblue"
            },
            hoverAnimation: false,
            legendHoverLink: false,
            legendHoverLink: false,
            data: listIntrest,
            type: "line"
          }
        ]
      };

      line_chart.setOption(option);
    }
  }
};
</script>

<style lang="scss">
.index-wrapper {
  h2 {
    font-weight: bolder;
  }

  .progress {
    height: 4px;
  }

  .product-card {
    overflow: auto;
    padding: 30px;
    background: #f5f5f5;

    .card-item {
      position: relative;
      float: left;
      width: 45%;
      margin: 30px;
      box-shadow: 0 0 6px black;
      display: flex;
      height: 200px;
      background: #ffffff;

      &.off {
        cursor: default;
        &:hover {
          color: #000000;
        }
      }

      .cv {
        padding: 10px;

        hr {
          border-top: 2px solid #465671;
        }
      }

      .cover {
        position: absolute;
        width: 100%;
        height: -webkit-fill-available;
        background: #7075afb3;

        span {
          position: absolute;
          transform: translate(-50%, -50%) rotate(-15deg);
          top: 50%;
          left: 50%;
          font-size: 45px;
        }
      }

      &:hover {
        text-decoration: none;
      }
    }
  }

  .game-card {
    overflow: auto;

    %layout {
      float: left;
      width: 50%;
      text-align: center;
      color: #ffffff;
      padding: 10px;
    }

    .loan-game {
      background: #3697c2;
      @extend %layout;

      .option {
        overflow: auto;

        .item {
          float: left;
          margin: 5px 10px;

          .circle {
            background: #ffffff;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            position: relative;
            box-shadow: 0 0 8px black;
            margin: 5px;
            cursor: pointer;

            .gary {
              filter: grayscale(1);
            }

            img {
              position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%);
              border-radius: 50%;
            }
          }
        }
      }

      .creditNum {
        margin: 10px 20px;

        .circle {
          background: #ffffff;
          border-radius: 26px;
          width: 500px;
          height: 170px;
          position: relative;
          box-shadow: 0 0 8px black;
          margin: 5px auto;
          color: #909090;

          .total {
            position: absolute;
            top: 50%;
            left: 45%;
            transform: translate(-50%, -50%);
            width: 100%;

            span {
              font-size: 65px;
              text-shadow: 3px 0px 3px darkgrey;
            }

            .default {
              color: #909090;
            }

            .soso {
              color: #ff7d45;
            }

            .ordinary {
              color: #fff436;
            }

            .good {
              color: #41ff38;
            }

            .awesome {
              color: #59ffea;
            }
          }
        }
      }
    }

    .invest-game {
      @extend %layout;
      background: #4056c2;

      .option {
        overflow: auto;

        .item {
          width: 48%;
          float: left;
          text-align: end;
          margin: 0px 5px;
          position: relative;

          label {
            text-align: initial;
            width: 200px;
            float: left;
          }

          div {
            position: absolute;
            right: 0;

            .pointer {
              cursor: pointer;
            }

            .slider {
              height: 3px;
              outline: none;
              transition: opacity 0.2s;
              transform: translate(0%, -4px);
            }
          }
        }
      }

      .chart {
        color: #000000;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 0 8px #ffffff;
        margin: 10px 5px;
        height: 302px;

        .invest-chart {
          width: 735px;
          height: 302px;
          text-align: start;
        }
      }
    }
  }

  .experience-card {
    padding: 30px;
    overflow: hidden;
    position: relative;
    background: #d6d6d6;

    .comment {
      position: absolute;
      top: 30px;
      right: 30px;
    }

    .items {
      display: flex;
      align-items: center;
      width: fit-content;
      animation: carouselAnim 60s infinite alternate linear;
    }

    .entry {
      display: flex;
      position: relative;
      width: 500px;
      background: #fff;
      margin: 1em;
      padding: 0.5em;
      border-radius: 10px;
      box-shadow: 4px 4px 5px 0px rgba(0, 0, 0, 0.5);

      .member {
        text-align: center;
        padding: 5px;
        width: 500px;

        label {
          font-weight: bolder;
        }

        .img {
          width: 100px;
          margin: 0px auto;
          border-radius: 50%;

          img {
            border-radius: 50%;
          }
        }
      }

      .memo {
        padding: 10px;
        background: #3b81ff;
        border-radius: 10px;
        position: relative;
        color: #ffffff;

        &:after {
          content: "";
          height: 0;
          width: 0;
          border-right: 15px solid #3b81ff;
          border-top: 10px solid #ffffff00;
          border-bottom: 10px solid #ffffff00;
          position: absolute;
          top: 50px;
          left: -15px;
        }
      }
    }

    @media only screen and (max-width: 768px) {
      .items {
        animation: carouselAnim 45s infinite alternate linear;
      }

      @keyframes carouselAnim {
        from {
          transform: translate(0, 0);
        }
        to {
          transform: translate(calc(-100% + (2 * 300px)));
        }
      }
    }

    @keyframes carouselAnim {
      from {
        transform: translate(0, 0);
      }
      to {
        transform: translate(calc(-100% + (5 * 300px)));
      }
    }
  }

  .information-card {
    padding: 10px 0px;
    background: #a7caff;

    .nav-item {
      width: 31.5%;
      margin: 0px 10px;
      text-align: center;
      font-weight: bolder;

      .nav-link {
        width: 100%;
        margin: 0px 10px;
        border-radius: 10px;
        position: relative;
        border: 1px solid #00548c75;
        background: #ffffff;

        &.active {
          background: #1547ff;
          color: #ffffff;
          border: 0px;

          &:after {
            content: "";
            height: 0;
            width: 0;
            border-top: 15px solid #1547ff;
            border-left: 15px solid #ffffff00;
            border-right: 15px solid #ffffff00;
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translate(-50%, 0);
          }
        }
      }
    }

    .tab-content {
      margin: 20px;
      text-align: center;

      .news-slick,
      .knowledge-slick,
      .video-slick {
        margin: 25px 10px;
        position: relative;
      }

      .slick-arrow {
        position: absolute;
        top: 50%;
        transform: translatey(-50%);
        font-size: 23px;
        z-index: 1;
        cursor: pointer;
      }

      .arrow-left {
        left: -15px;
      }

      .arrow-right {
        right: -15px;
      }

      .slick-track {
        padding: 10px 0px;
      }

      .slick-item {
        width: 25%;
        border: 1px solid #000000;
        padding: 10px;
        text-align: initial;
        font-size: 16px;
        font-weight: bolder;
        margin: 0px 10px;
        box-shadow: 0 0 4px black;
        background: #ffffff;

        &:hover {
          text-decoration: none;
        }

        hr {
          border-top: 1px solid #000000;
          margin: 5px 10px;
        }
      }
    }
  }

  .slogan-card {
    width: 100%;
    padding: 25px 15%;
    background: #2f82ff;
    display: flex;

    .logo {
      width: 40%;
      filter: drop-shadow(0px 0px 5px white);
    }

    .slogan {
      color: #ffffff;
      padding: 10px;
      position: relative;
      width: inherit;

      .content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: Arial, cursive, Helvetica, sans-serif;
        width: fit-content;

        h4 {
          margin-bottom: 20px;
        }
      }
    }
  }

  .download-card {
    background-repeat: no-repeat;
    background-size: 100%;
    height: 500px;
  }

  .advantage-card {
    background: #f5f5f5;
    padding: 15px 10px;

    .title {
      background-color: #002e58;
      color: #ffffff;
      margin: 10px auto;
      padding: 20px;
      width: fit-content;
      text-align: center;
      font-size: 22px;
      font-weight: bolder;
    }

    .content {
      display: flex;

      .item {
        box-shadow: 0 0 5px #293e5d;
        padding: 10px;
        margin: 10px;
        background: #ffffff;
        
        .img {
          width: 100%;
        }

        .desc {
          padding: 20px 9%;

          p {
            color: #002e58;
            font-size: 31px;
            text-align: center;
            font-weight: 700;
          }
        }
      }
    }
  }
}
</style>