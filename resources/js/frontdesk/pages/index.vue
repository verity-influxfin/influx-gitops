<template>
  <div class="index-wrapper">
    <div class="banner" ref="banner">
      <div class="puhey-banner">
        <img
          :src="'/images/index-banner-d.png'"
          style="width: 100%"
          class="hidden-desktop"
        />
        <img
          :src="'/images/index-banner-m.png'"
          style="width: 100%"
          class="hidden-phone"
        />
        <div class="content">
          <p>最貼近年輕人的金融科技平台</p>
          <p>普匯，你的手機ATM</p>
          <div class="box">
            <a
              class="loan"
              href="https://event.influxfin.com/r/iurl?p=webinvest"
              target="_blank"
              ><img src="../asset/images/loan.svg" class="img-fluid" /><span
                >我要投資</span
              ></a
            >
            <a
              class="borrow"
              href="https://event.influxfin.com/R/url?p=webbanner"
              target="_blank"
              ><img src="../asset/images/borrow.svg" class="img-fluid" /><span
                >我要借款</span
              ></a
            >
          </div>
        </div>
      </div>
      <shanghuiBanner />
    </div>
    <div class="count-card">
      <div class="content">
        <div class="item">
          <label>會員數：</label>
          <div class="count">{{ format(parseInt(tweenedMemberAmount)) }}</div>
        </div>
        <div class="item">
          <label>成功交易筆數：</label>
          <div class="count">
            {{ format(parseInt(tweenedTransactionCount)) }}
          </div>
        </div>
        <!-- <div class="item">
          <label>APP下載次數：</label>
          <div class="count">
            {{ format(parseInt(tweenedDownloadCount)) }}次
          </div>
        </div> -->
      </div>
    </div>
    <div class="product-card">
      <div class="pattern">
        <div class="p-i">
          <h5>投資</h5>
          <p>
            提供資金幫他人圓夢
            <br />並獲得合理報酬
          </p>
          <div class="imagery hvr-float">
            <div class="img">
              <img src="../asset/images/invest_pu.png" class="img-fluid" />
            </div>
            <span>
              投資人
              <br />每月定期回收本息
            </span>
          </div>
        </div>
        <div class="circle">
          <div class="cnt hvr-grow">
            <div class="invest-dot-up">
              <investUp />
            </div>
            <div class="l-img">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                version="1.1"
                viewBox="0 0 220 100"
              >
                <defs>
                  <linearGradient id="L" x1="0%" y1="50%" x2="100%" y2="50%">
                    <stop offset="0%" stop-color="#B3FAFF">
                      <animate
                        attributeName="stop-color"
                        values="#B3FAFF;#6ad1de;#2bafc3;#6ad1de;#B3FAFF;"
                        dur="5s"
                        repeatCount="indefinite"
                      />
                    </stop>
                    <stop offset="50%" stop-color="#6ad1de">
                      <animate
                        attributeName="stop-color"
                        values="#6ad1de;#2bafc3;#6ad1de;#B3FAFF;#6ad1de"
                        dur="2s"
                        repeatCount="indefinite"
                      />
                    </stop>
                    <stop offset="100%" stop-color="#2bafc3">
                      <animate
                        attributeName="stop-color"
                        values="#2bafc3;#6ad1de;#B3FAFF;#6ad1de;#2bafc3"
                        dur="2s"
                        repeatCount="indefinite"
                      />
                    </stop>
                  </linearGradient>
                </defs>
                <path
                  fill="url(#L)"
                  d="M 5 50 L 50 10 L 45 40 L 215 40 L 215 60 L 45 60 L 50 90 Z"
                />
              </svg>
            </div>
            <div class="pu-img">
              <img src="../asset/images/logo_puhey.svg" class="img-fluid" />
            </div>
            <div class="r-img">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                version="1.1"
                viewBox="0 0 220 100"
              >
                <defs>
                  <linearGradient id="R" x1="0%" y1="50%" x2="100%" y2="50%">
                    <stop offset="0%" stop-color="#2bafc3">
                      <animate
                        attributeName="stop-color"
                        values="#2bafc3;#6ad1de;#B3FAFF;#6ad1de;#2bafc3;"
                        dur="2s"
                        repeatCount="indefinite"
                      />
                    </stop>
                    <stop offset="50%" stop-color="#6ad1de">
                      <animate
                        attributeName="stop-color"
                        values="#6ad1de;#2bafc3;#6ad1de;#B3FAFF;#6ad1de;"
                        dur="2s"
                        repeatCount="indefinite"
                      />
                    </stop>
                    <stop offset="100%" stop-color="#B3FAFF">
                      <animate
                        attributeName="stop-color"
                        values="#B3FAFF;#6ad1de;#2bafc3;#6ad1de;#B3FAFF;"
                        dur="2s"
                        repeatCount="indefinite"
                      />
                    </stop>
                  </linearGradient>
                </defs>
                <path
                  fill="url(#R)"
                  d="M 215 50 L 170 90 L 175 60 L 5 60 L 5 40 L 175 40 L 170 10 Z"
                />
              </svg>
            </div>
            <div class="invest-dot-down">
              <investDown />
            </div>
          </div>
        </div>
        <div class="p-l">
          <h5>借款</h5>
          <p>
            取得投資人資金
            <br />解決生活所需及實夢想
          </p>
          <div class="imagery hvr-float">
            <span>
              借款人
              <br />每月定期還款
            </span>
            <div class="img">
              <img src="../asset/images/loan_pu.png" class="img-fluid" />
            </div>
          </div>
        </div>
      </div>
      <div class="product">
        <h2>貸款方案</h2>
        <div class="hr"></div>
        <div class="product-list">
          <router-link
            :class="[
              'card-item',
              { 'hvr-grow-shadow': item.isActive },
              { disable: !item.isActive },
            ]"
            v-for="(item, index) in this.services"
            :to="item.link"
            :key="index"
          >
            <div class="img">
              <img :src="item.imageSrc" />
            </div>
            <div class="cv">
              <h3>{{ item.title }}</h3>
              <p v-html="item.desc"></p>
            </div>
            <div class="cover" v-if="!item.isActive">
              <span>coming soon</span>
            </div>
          </router-link>
        </div>
      </div>
    </div>
    <div class="game-card" id="game-card">
      <div style="z-index: 2; position: relative">
        <h2>額度試算</h2>
        <div class="hr"></div>
        <div class="loan-game">
          <div class="option">
            <div
              class="item"
              data-aos="fade-down"
              :data-aos-delay="100 * index"
              v-for="(item, index) in creditRatingItem"
              :key="index"
            >
              <div
                :class="['circle', { gary: !item.checked }]"
                @click="
                  item.checked = !item.checked;
                  changeCredit(index);
                "
              >
                <img :src="item.img" class="img-fluid" />
              </div>
              <span>{{ item.text }}</span>
            </div>
          </div>
          <div class="credit-num">
            <div class="circle">
              <div class="total">
                <p>最高可達：</p>
                <strong>5,000～</strong>
                <span :class="moneyClass">{{ format(tweenedMoney) }}</span>
              </div>
            </div>
          </div>
        </div>
        <h2>投報試算</h2>
        <div class="hr"></div>
        <div class="invest-game">
          <div class="chart" ref="chart">
            <div class="invest-chart" ref="investChart"></div>
          </div>
          <div class="option">
            <div class="item">
              <label
                >每期{{ amount >= 0 ? `投入` : `領出` }}金額：{{
                  format(parseInt(amount))
                }}</label
              >
              <div>
                <i
                  class="fas fa-minus-circle pointer"
                  @click="amount > -100000 ? (amount -= 1000) : ''"
                ></i>
                <input
                  type="range"
                  step="1000"
                  min="-100000"
                  max="100000"
                  class="slider"
                  v-model="amount"
                />
                <i
                  class="fas fa-plus-circle pointer"
                  @click="amount < 100000 ? (amount -= -1000) : ''"
                ></i>
              </div>
            </div>
            <div class="item">
              <label>投資操作時間(年)：{{ format(parseInt(time)) }}</label>
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
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="advantage-card">
      <div class="header">
        <div class="slg">
          <p class="title">
            以金融為核心，以科技為輔具，
            <br />普匯給您前所未有的專業金融APP
          </p>
          <p class="l-btn">為什麼選擇普匯金融科技?</p>
        </div>
      </div>
      <div class="content" ref="advantage_slick">
        <div class="item">
          <div class="img">
            <img :src="'/images/expert.svg'" class="img-fluid" />
          </div>
          <p>最專業的金融專家</p>
          <span
            >普匯擁有近20年金融專業經驗，深度理解各類金融產品、相關金融法規、財稅務、金流邏輯...等。能針對不同產業產品與市場，設計出更適合用戶需求的金融服務。</span
          >
        </div>
        <div class="item">
          <div class="img">
            <img :src="'/images/ai.svg'" class="img-fluid" />
          </div>
          <p>最先進的AI科技系統</p>
          <span
            >普匯擁有完善的金融科技技術，包含:
            反詐欺反洗錢系統、競標即時撮合系統、
            風控信評/線上對保、自動撥貸/貸後管理、
            分秒計息等，不斷與時俱進迭代優化。</span
          >
        </div>
        <div class="item">
          <div class="img">
            <img :src="'/images/safe.svg'" class="img-fluid" />
          </div>
          <p>簡單、快速、安全、隱私</p>
          <span
            >視覺化簡潔好用的操作介面，運用先進科技與AWS
            安全系統，保護您的個資絕不外洩，讓您在步入圓夢捷徑的同時，安全又放心。</span
          >
        </div>
      </div>
    </div>
    <experience :experiences="experiences" />
    <div class="information-card">
      <ul class="nav" role="tablist">
        <li class="nav-item">
          <a
            class="nav-link active"
            data-toggle="tab"
            href="#news"
            @click="reSlick('news_slick')"
            >最新消息</a
          >
        </li>
        <li class="nav-item">
          <a
            class="nav-link"
            data-toggle="tab"
            href="#knowledge"
            @click="reSlick('knowledge_slick')"
            >小學堂</a
          >
        </li>
        <li class="nav-item">
          <a
            class="nav-link"
            data-toggle="tab"
            href="#video"
            @click="reSlick('video_slick')"
            >小學堂影音</a
          >
        </li>
      </ul>
      <div class="tab-content">
        <div id="news" class="tab-pane fade active show">
          <div class="news-slick" ref="news_slick">
            <a
              class="slick-item hvr-float-shadow"
              v-for="(item, index) in news"
              :href="
                item.url.indexOf('influxfin') !== -1 ? item.link : item.url
              "
              :key="index"
            >
              <div class="img">
                <img :src="item.image_url" class="img-fluid" />
              </div>
              <p>{{ item.title }}</p>
              <span>{{ item.updated_at }}</span>
            </a>
          </div>
          <router-link class="btn btn-warning btn-to" to="news">
            最新消息&ensp;
          </router-link>
        </div>
        <div id="knowledge" class="tab-pane fade">
          <div class="knowledge-slick" ref="knowledge_slick">
            <router-link
              class="slick-item hvr-float-shadow"
              v-for="(item, index) in knowledge"
              :key="index"
              :to="item.link"
            >
              <div class="img">
                <img :src="item.media_link" class="img-fluid" />
              </div>
              <p>{{ item.post_title }}</p>
              <span>{{ item.post_date }}</span>
            </router-link>
          </div>
          <router-link class="btn btn-warning btn-to" to="blog">
            小學堂&ensp;
          </router-link>
        </div>
        <div id="video" class="tab-pane fade">
          <div class="video-slick" ref="video_slick">
            <router-link
              class="slick-item hvr-float-shadow"
              v-for="(item, index) in video"
              :to="item.link"
              :key="index"
            >
              <div style="width: fit-content; margin: 0px auto">
                <iframe
                  :src="item.video_link"
                  frameborder="0"
                  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                ></iframe>
              </div>
              <p>{{ item.post_title }}</p>
              <span>{{ item.post_date }}</span>
            </router-link>
          </div>
          <router-link class="btn btn-warning btn-to" to="vlog/share">
            小學堂影音&ensp;
          </router-link>
        </div>
      </div>
    </div>
    <div class="slogan-card">
      <p class="content">
        普匯相信每個年輕人，我們致力幫助他們完成人生的夢想
        <br />以金融為核心，以科技為輔具，只為給您最好的體驗
        <br />最貼近年輕人的金融科技平台
      </p>
      <div class="slogan">普匯．你的手機ATM</div>
      <div class="img">
        <img :src="'/images/846.png'" class="img-fluid" />
      </div>
    </div>
    <download :isLoan="true" :isInvest="true" />
  </div>
</template>

<script>
import investUp from "../component/svg/investDotUpComponent";
import investDown from "../component/svg/investDotDownComponent";
import download from "../component/downloadComponent";
import experience from "../component/experienceComponent";
//banner
import shanghuiBanner from "../component/banner/shanghuiBanner";

export default {
  components: {
    download,
    experience,
    investUp,
    investDown,
    //banner
    shanghuiBanner,
  },
  data: () => ({
    amount: 10000,
    time: 1,
    money: 100000,
    tweenedMoney: 100000,
    tweenedTransactionCount: 0,
    tweenedMemberAmount: 0,
    tweenedDownloadCount: 0,
    moneyClass: "ordinary",
    creditRatingItem: [
      {
        text: "實名認證",
        img: "/images/icon_cert_rev2_identity.svg",
        checked: true,
        money: 10000,
      },
      {
        text: "金融帳號",
        img: "/images/icon_cert_rev2_bank.svg",
        checked: false,
        money: 10000,
      },
      {
        text: "社交認證",
        img: "/images/icon_cert_rev2_social.svg",
        checked: false,
        money: 20000,
      },
      {
        text: "緊急聯絡人",
        img: "/images/icon_cert_rev2_emergency.svg",
        checked: false,
        money: 10000,
      },
      {
        text: "電子信箱",
        img: "/images/icon_cert_rev2_mail.svg",
        checked: false,
        money: 10000,
      },
      {
        text: "財力證明",
        img: "/images/icon_cert_rev2_income.svg",
        checked: false,
        money: 20000,
      },
      {
        text: "學生身分",
        img: "/images/icon_cert_rev2_student.svg",
        checked: true,
        money: 40000,
      },
      {
        text: "最高學歷",
        img: "/images/icon_cert_rev2_diploma.svg",
        checked: false,
        money: 30000,
      },
      {
        text: "工作認證",
        img: "/images/icon_cert_rev2_worker.svg",
        checked: false,
        money: 40000,
      },
      {
        text: "聯合徵信",
        img: "/images/icon_cert_rev2_icri.svg",
        checked: true,
        money: 50000,
      },
    ],
    services: [],
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
    },
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
      particlesJS.load("game-card", "data/game.json");
      this.createSlick(this.$refs.advantage_slick, 3, false);
      this.createSlick(this.$refs.banner, 1, false);
    });
    AOS.init();

    gsap.to(this.$data, { duration: 0.5, tweenedTransactionCount: 31559 });
    gsap.to(this.$data, { duration: 0.5, tweenedMemberAmount: 54188 });
    // gsap.to(this.$data, { duration: 0.5, tweenedDownloadCount: 20000 });
  },
  watch: {
    news() {
      this.$nextTick(() => {
        this.createSlick(this.$refs.news_slick);
      });
    },
    knowledge() {
      this.$nextTick(() => {
        this.createSlick(this.$refs.knowledge_slick);
      });
    },
    video() {
      this.$nextTick(() => {
        this.createSlick(this.$refs.video_slick);
      });
    },
    amount(data) {
      this.amount = parseInt(data);
      this.createChart();
    },
    time(data) {
      this.time = parseInt(data);
      this.createChart();
    },
    money(data) {
      if (data < 50000) {
        this.moneyClass = "default";
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
    },
  },
  methods: {
    format(data) {
      let l10nEN = new Intl.NumberFormat("en-US");
      return l10nEN.format(data.toFixed(0));
    },
    async getBannerPic() {
      let res = await axios.get(`${location.origin}/getBannerPic`);
      this.bannerPic = res.data;
    },
    getServiceData() {
      axios.post(`${location.origin}/getServiceData`).then((res) => {
        this.services = res.data;
      });
    },
    createSlick(tar, toShow = 4, arrows = true) {
      $(tar).slick({
        infinite: true,
        slidesToShow: toShow,
        slidesToScroll: 1,
        autoplay: false,
        customPaging(slider, i) {
          return '<i class="fas fa-circle"></i>';
        },
        arrows,
        prevArrow: '<i class="fas fa-chevron-left arrow-left"></i>',
        nextArrow: '<i class="fas fa-chevron-right arrow-right"></i>',
        responsive: [
          {
            breakpoint: 1023,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1,
            },
          },
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
        let _pmt = $this.pmt(_pv, 12, 12);
        let _principleRemind = _pv;
        for (l = 0; l < 12; l++) {
          _intrest = Math.round(((_principleRemind * 12) / 100 / 360) * 30);
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
        listPrinciple.push(Math.round((temp["principle"] * 1 * 100) / 100));
        listIntrest.push(Math.round((temp["intrest"] * 1 * 100) / 100));
      }

      let _totalIncome =
        listPrinciple[listPrinciple.length - 1] +
        listIntrest[listIntrest.length - 1];

      let _investAll =
        $this.amount > 0 ? $this.amount * ($this.time * 12 - 1) : 0;

      let _returnAll = _totalIncome + _cashLast;

      let _valueTotal =
        _totalIncome + _cashLast - $this.amount * ($this.time * 12 - 1); //期末回款+本金餘額-期初投入-每期投入總額

      let _last2Return =
        listPrinciple[listPrinciple.length - 2] +
        listIntrest[listIntrest.length - 2] +
        $this.amount;

      let subtext =
        _totalIncome < 0 || _last2Return < 0 || _totalIncome + _cashLast < 0
          ? "領取金額超過每月複投金額"
          : `每期投資${$this.amount}元，${
              $this.time
            }年後預計可回收${$this.format(_returnAll)}元`;

      $($this.$refs.investChart)
        .css("width", `${$($this.$refs.chart).innerWidth()}px`)
        .css("height", `${$($this.$refs.chart).innerHeight()}px`);

      let line_chart = echarts.init($this.$refs.investChart);

      let option = {
        grid: {
          left: 30,
          top: 80,
          right: 30,
          bottom: 60,
        },
        title: {
          subtext,
          subtextStyle: {
            color: "#083a6e",
            fontSize: 14,
          },
          left: "10",
          top: "0",
        },
        dataZoom: {
          type: "slider",
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
          boundaryGap: false,
          data: xAxisData,
        },
        yAxis: {
          type: "value",
          axisTick: {
            show: false,
          },
          axisLabel: {
            show: false,
          },
        },
        series: [
          {
            name: "本期回款本金",
            stack: "1",
            symbol: "circle",
            symbolSize: 1,
            itemStyle: {
              color: "#9F9ADB",
            },
            lineStyle: {
              color: "#9F9ADB",
            },
            hoverAnimation: false,
            legendHoverLink: false,
            legendHoverLink: false,
            data: listPrinciple,
            type: "line",
          },
          {
            name: "每期投入",
            stack: "1",
            symbol: "green",
            symbolSize: 1,
            itemStyle: {
              color: "#98F0E2",
            },
            lineStyle: {
              color: "#98F0E2",
            },
            hoverAnimation: false,
            legendHoverLink: false,
            legendHoverLink: false,
            data: listEveryFlow,
            type: "line",
          },
          {
            name: "本期回款利息",
            stack: "2",
            symbol: "circle",
            symbolSize: 1,
            itemStyle: {
              color: "orange",
            },
            lineStyle: {
              color: "orange",
            },
            hoverAnimation: false,
            legendHoverLink: false,
            legendHoverLink: false,
            data: listIntrest,
            type: "line",
          },
        ],
      };

      line_chart.setOption(option);
    },
    reSlick(target) {
      this.$nextTick(() => {
        $(this.$refs[target]).slick("refresh");
      });
    },
  },
};
</script>

<style lang="scss">
.index-wrapper {
  width: 100%;

  h2 {
    font-weight: bolder;
  }

  .hr {
    width: 130px;
    height: 2px;
    background-image: linear-gradient(to right, #0559ac, #ffffff);
    margin: 0px auto;
  }

  .banner {
    position: relative;
    overflow: hidden;

    .puhey-banner {
      position: relative;
      height: 72vh;

      img {
        width: 100%;
      }

      .content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);

        p {
          font-size: 40px;
          font-weight: bold;
          color: #ffffff;
          text-align: center;
        }

        .box {
          display: flex;
          justify-content: space-around;
          margin-top: 5rem;

          %link {
            box-shadow: 0px 2px 3px black;
            border: 2px solid;
            width: 160px;
            text-align: center;
            color: #ffffff;
            background-image: linear-gradient(
              to bottom,
              rgba(0, 0, 0, 0.2),
              rgba(0, 0, 0, 0.2)
            );
            padding: 2.2rem;
            backdrop-filter: blur(5px);

            img {
              margin-bottom: 10px;
            }
          }

          .loan {
            @extend %link;
            border-image-source: linear-gradient(
              144deg,
              #f7eca8 1%,
              rgba(191, 148, 55, 0.7) 51%,
              #e7cc7e 100%
            );
            border-image-slice: 1;
            img {
              filter: drop-shadow(0px 0px 6px #f6d949);
            }
          }
          .borrow {
            @extend %link;
            border-image-source: linear-gradient(
              144deg,
              #65dab3 1%,
              rgba(21, 124, 80, 0.7) 48%,
              #65dab3 100%
            );
            border-image-slice: 1;
            img {
              filter: drop-shadow(0px 0px 6px #65dab3);
            }
          }
        }
      }
    }
  }

  .count-card {
    background-color: #083a6e;
    color: #ffffff;
    overflow: hidden;

    .content {
      display: flex;
      width: fit-content;
      margin: 20px auto;

      .item {
        padding: 0px 20px;

        &:not(:last-of-type) {
          border-right: 1px solid #ffffff;
        }

        .count {
          font-size: 26px;
          font-weight: bold;
          line-height: 25px;
        }
      }
    }
  }

  .product-card {
    overflow: hidden;
    background-image: url("../asset/images/105.png");
    background-position: 0 0;
    background-repeat: no-repeat;
    background-size: 100% 100%;
    padding: 70px 30px;

    .pattern {
      display: flex;
      width: 78%;
      margin: 0px auto 40px auto;
      color: #ffffff;
      position: relative;

      .circle {
        position: absolute;
        top: 61%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;

        %invest-dot {
          width: 400px;
          position: absolute;
          left: 50%;
          transform: translateX(-50%);
        }

        .invest-dot-up {
          @extend %invest-dot;
          top: -20%;
        }

        .invest-dot-down {
          @extend %invest-dot;
          bottom: -20%;
        }

        .cnt {
          background: #ffffff;
          border-radius: 50%;
          height: 250px;
          width: 250px;
          padding: 27px 0px;

          &:hover {
            filter: drop-shadow(0px 0px 8px black);
            transition-duration: 0.3s;
          }

          %img {
            width: 60%;
            text-align: center;
            margin: 0px auto;

            svg {
              width: 100%;
            }
          }

          .l-img {
            @extend %img;

            path {
              // animation: l-flash 4s ease-in-out infinite;

              @keyframes l-flash {
                0% {
                  fill: red;
                }
                50% {
                  fill: blue;
                }
                100% {
                  fill: red;
                }
              }
            }
          }
          .pu-img {
            @extend %img;
          }
          .r-img {
            @extend %img;

            path {
              // animation: r-flash 4s ease-in-out infinite;

              @keyframes r-flash {
                0% {
                  fill: blue;
                }
                50% {
                  fill: red;
                }
                100% {
                  fill: blue;
                }
              }
            }
          }
        }
      }

      %p-bg {
        width: 50%;
        text-align: center;
        z-index: 1;

        h5 {
          font-size: 24px;
          font-weight: bold;
        }

        p {
          font-weight: bold;
        }

        .imagery {
          display: flex;
          background: #083a6ec7;
          padding: 2rem;
          height: 345px;

          .img {
            width: 30%;
            position: relative;

            img {
              position: absolute;
              bottom: 0;
              left: 0;
              filter: drop-shadow(3px 3px 3px black);
            }
          }

          span {
            width: 70%;
            height: fit-content;
            position: relative;
            top: 50%;
            transform: translatey(-50%);
            font-weight: bold;
            padding: 0px 4rem;
            font-size: 20px;
          }
        }
      }

      .p-i {
        @extend %p-bg;
        margin-right: 5rem;
        span {
          text-align: left;
        }
      }

      .p-l {
        @extend %p-bg;
        margin-left: 5rem;
        span {
          text-align: right;
        }
      }
    }

    .product {
      width: 80%;
      margin: 40px auto;

      h2 {
        text-align: center;
      }

      .product-list {
        padding: 20px;

        .card-item {
          position: relative;
          float: left;
          width: 50%;
          display: flex;
          padding: 20px;
          color: #ffffff;

          $bgColor: #2e639b, #00def8, #3c73f5, #00b7cc, #1b558e;

          @for $i from 1 through 5 {
            &:nth-of-type(#{$i}) {
              background: nth($bgColor, $i);
            }
          }

          &:hover {
            z-index: 2;
          }

          .img {
            height: 190px;
            width: 150px;
            overflow: hidden;
            position: relative;

            img {
              height: 135px;
              position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%);
              filter: drop-shadow(3px 3px 3px black);
            }
          }

          .cv {
            padding: 0px 20px;
            width: calc(100% - 150px);

            p {
              font-size: 13px;
            }
          }

          .cover {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(50%, 50%);
            filter: invert(1);

            span {
              position: absolute;
              transform: translate(-50%, -50%) rotate(-15deg);
              top: 50%;
              left: 50%;
              font-size: 45px;
            }
          }

          &:hover {
            color: #ffffff;
            text-decoration: none;
          }
        }

        .disable {
          filter: contrast(0.5);
        }
      }
    }
  }

  .game-card {
    overflow: hidden;
    background: #e3e3e3;
    padding: 40px 0px;
    position: relative;

    .particles-js-canvas-el {
      position: absolute;
      top: 0;
    }

    h2 {
      text-align: center;
      color: #2b0c5a;
    }

    %layout {
      text-align: center;
      background-color: #ffffff;
      padding: 20px 10px;
      width: fit-content;
      margin: 20px auto 30px auto;
    }

    .loan-game {
      @extend %layout;

      .option {
        display: flex;
        justify-content: space-around;

        .item {
          margin: 5px 5px;

          .circle {
            background: #ffffff;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            position: relative;
            border: 10px solid #00d3dc;
            margin: 5px;
            cursor: pointer;

            img {
              position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%);
              border-radius: 50%;
              width: 85%;
            }
          }

          .gary {
            border-color: #f1f1f1;
          }

          span {
            font-size: 14px;
          }
        }
      }

      .credit-num {
        margin: 10px 20px;

        .circle {
          background-image: linear-gradient(129deg, #00aeff 3%, #00d9d5 102%);
          border-radius: 26px;
          width: 45%;
          height: 170px;
          position: relative;
          color: #ffffff;
          margin: 5px auto;

          .total {
            position: absolute;
            top: 50%;
            left: 45%;
            transform: translate(-50%, -50%);
            width: 100%;

            strong {
              font-size: 24px;
            }

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
      width: 66.5%;

      .chart {
        color: #000000;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 0 8px #ffffff;
        margin: 10px 5px;
        height: 350px;

        .invest-chart {
          text-align: start;
        }
      }

      .option {
        overflow: auto;

        .item {
          width: calc(50% - 10px);
          float: left;
          text-align: start;
          margin: 0px 5px;
          padding: 0px 30px;
          position: relative;

          div {
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
        }
      }
    }
  }

  .advantage-card {
    .header {
      background-image: url("../asset/images/why_bg.png");
      background-repeat: no-repeat;
      background-size: cover;
      font-size: 17px;
      font-weight: bold;
      overflow: hidden;
      min-height: 350px;
      position: relative;

      .slg {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);

        .title {
          color: #ffffff;
          margin: 35px auto;
          width: fit-content;
          text-align: center;
          font-size: 30px;
          font-weight: bolder;
        }

        .l-btn {
          background-color: #fbd900;
          color: #083a6e;
          font-size: 22px;
          font-weight: bold;
          width: 100%;
          margin: 0px auto;
          padding: 8px 15px;
          border-radius: 25px;
          text-align: center;
        }
      }
    }

    .content {
      .item {
        padding: 3rem;
        height: 380px;
        pointer-events: none;

        $bgColor: #005ec1, #16528f, #083a6e;

        @for $i from 1 through 3 {
          &:nth-of-type(#{$i}) {
            background: nth($bgColor, $i);
          }
        }

        .img {
          width: fit-content;
          margin: 20px auto;

          img {
            height: 90px;
          }
        }

        p {
          color: #fbd900;
          font-size: 29px;
          text-align: center;
          font-weight: 700;
        }

        span {
          color: #ffffff;
        }
      }
    }
  }

  .information-card {
    overflow: hidden;
    padding-bottom: 30px;

    .nav {
      width: 60%;
      margin: 0px auto;
      .nav-item {
        width: calc(100% / 3);
        text-align: center;
        font-weight: bolder;

        .nav-link {
          width: 100%;
          position: relative;
          background: #ffffff;
          padding: 15px 0px;
          cursor: pointer;

          &.active {
            background: #fbd900;
            border: 0px;

            &:after {
              content: "";
              height: 0;
              width: 0;
              border-top: 15px solid #fbd900;
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
    }

    .tab-content {
      margin: 20px;
      text-align: center;

      .news-slick,
      .knowledge-slick,
      .video-slick {
        margin: 25px 10px 10px 10px;
        position: relative;
      }

      .btn-to {
        font-weight: bold;
        width: 30%;
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
        padding: 10px;
        text-align: initial;
        font-size: 16px;
        font-weight: bolder;
        margin: 0px 10px;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #ffffff;
        min-height: 307px;

        .img {
          width: 100%;
          height: 200px;
          margin: 0px auto;
          overflow: hidden;
        }

        p {
          text-align: center;
          color: #083a6e;
          font-size: 15px;
          margin: 0.5rem 0px;
          pointer-events: none;
        }

        span {
          float: right;
          color: #8c8c8c;
          font-weight: 100;
        }

        &:hover {
          text-decoration: none;
        }

        iframe {
          width: 100%;
          height: 180px;
        }
      }
    }
  }

  .slogan-card {
    padding: 25px 0px;
    background: #0b58a8;
    position: relative;

    .content {
      width: fit-content;
      margin: 0px auto;
      text-align: center;
      color: #ffffff;
      font-weight: bold;
      line-height: 1.96;
    }

    .slogan {
      background-color: #1c0073;
      width: 25%;
      margin: 10px auto;
      padding: 5px;
      font-weight: bold;
      color: #ffffff;
      text-align: center;
      border-radius: 25px;
    }

    .img {
      position: absolute;
      bottom: 0px;
      right: 18%;
      width: 200px;
    }
  }

  @media screen and (max-width: 767px) {
    .banner {
      .puhey-banner {
        height: 77vh;
        
        img {
          height: inherit;
          width: initial;
        }

        .content {
          font-size: 16px;
          width: 100%;

          p {
            font-size: 25px;
          }

          .box {
            margin-top: 3rem;
          }
        }
      }
    }
    
    .count-card {
      display: none;
    }

    .product-card {
      background-size: initial;
      padding: 0px;

      .pattern {
        width: 100%;
        margin: 10px 0px;
        overflow: hidden;

        .circle {
          top: 37%;

          %invest-dot {
            display: none;
          }

          .cnt {
            height: 100px;
            width: 100px;
            padding: 8px 0px;
          }
        }

        %p-bg {
          width: calc(50% - 15px);

          .imagery {
            display: block;
            padding: 10px;
            margin-top: -70px;
            position: relative;
            z-index: -1;

            .img {
              width: 100%;
              position: initial;

              img {
                width: 65%;
                left: 50%;
                transform: translateX(-50%);
                bottom: 10px;
              }
            }

            span {
              display: none;
            }
          }
        }

        .p-i {
          @extend %p-bg;
          margin-right: 5px;
          margin-left: 10px;
        }

        .p-l {
          @extend %p-bg;
          margin-left: 5px;
          margin-right: 10px;
        }
      }

      .product {
        width: initial;

        .product-list {
          padding: 10px;
          .card-item {
            width: calc(100%);
            padding: 10px;
            margin: 5px 0px;

            .img {
              height: 160px;
              width: 120px;

              img {
                height: 100px;
              }
            }

            .cv {
              padding: 0px 10px;
              width: calc(100% - 120px);
            }
          }
        }
      }
    }

    .game-card {
      %layout {
        text-align: center;
        background-color: #ffffff;
        padding: 20px 10px;
        width: initial;
        margin: 10px;
      }

      .loan-game {
        .option {
          display: block;
          overflow: hidden;

          .item {
            width: calc(100% / 3 - 10px);
            float: left;

            .circle {
              margin: 5px auto;
            }
          }
        }

        .credit-num {
          .circle {
            width: initial;

            .total {
              left: 50%;

              p {
                margin-top: 15px;
              }
            }
          }
        }
      }

      .invest-game {
        padding: 10px;
        .chart {
          height: 300px;
          margin: 0px;
        }

        .option {
          .item {
            float: initial;
            width: calc(100% - 10px);
          }
        }
      }
    }

    .advantage-card {
      .header {
        padding: 10px;
        min-height: auto;

        .slg {
          position: relative;
          transform: initial;
          top: 0%;
          left: 0%;
          margin: 35px 10px;

          .title {
            font-size: 22px;
          }
        }
      }

      .content {
        .item {
          padding: 1rem;
          height: 315px;

          $bgColor: #005ec1, #16528f, #083a6e;

          @for $i from 1 through 3 {
            &:nth-of-type(#{$i + 1}) {
              background: nth($bgColor, $i);
            }
          }

          p {
            font-size: 28px;
          }
        }
      }
    }

    .information-card {
      .nav {
        width: 100%;
      }

      .tab-content {
        .btn-to {
          width: 70%;
        }
      }
    }

    .slogan-card {
      overflow: hidden;
      padding: 25px 10px;

      .content {
        width: 75%;
        text-align: left;
      }

      .slogan {
        width: 80%;
      }

      .img {
        display: none;
      }
    }

    .download-card {
      background-size: auto 100%;

      .d-desc {
        width: 100%;
        padding: 2rem;
      }

      .img {
        display: none;
      }

      .d-apps {
        width: 100%;

        %font {
          width: 100%;

          .d-link {
            width: 80%;
            margin: 10px auto;
          }
        }
      }
    }
  }
}
</style>