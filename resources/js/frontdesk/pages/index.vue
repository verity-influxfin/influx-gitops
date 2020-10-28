<template>
  <div class="index-wrapper">
    <div class="banner" ref="banner">
      <div class="puhey-banner">
        <img
          :src="'/images/index-banner-d.png'"
          class="hidden-desktop img-fluid"
        />
        <img
          :src="'/images/index-banner-m.png'"
          class="hidden-phone img-fluid"
        />
        <img :src="'/images/diagram-d.svg'" class="diagram hidden-desktop" />
        <img :src="'/images/diagram-m.svg'" class="diagram hidden-phone" />
        <div class="content">
          <p>最貼近年輕人的金融科技平台</p>
          <span>普匯，你的手機ATM</span>
          <div class="box">
            <a
              class="loan"
              href="https://event.influxfin.com/r/iurl?p=webinvest"
              target="_blank"
              ><img src="../asset/images/light-b.svg" class="img-fluid" />
              <div class="text">立即投資</div></a
            >
            <a
              class="borrow"
              href="https://event.influxfin.com/R/url?p=webbanner"
              target="_blank"
              ><img src="../asset/images/light-y.svg" class="img-fluid" />
              <div class="text">立即借款</div></a
            >
          </div>
        </div>
      </div>
      <shanghuiBanner />
    </div>
    <div class="count-card">
      <div class="content">
        <div class="item">
          <div class="count">{{ format(parseInt(tweenedMemberAmount)) }}</div>
          <label>會員數</label>
        </div>
        <div class="lr"></div>
        <div class="item">
          <div class="count">
            {{ format(parseInt(tweenedTransactionCount)) }}
          </div>
          <label>成功交易筆數</label>
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
          <div
            class="i"
            data-aos="fade-up"
            :data-aos-duration="500 * index"
            v-for="(item, index) in this.services"
            :key="index"
          >
            <div
              :class="[
                'card-item',
                { 'hvr-grow-shadow': item.isActive },
                { disable: !item.isActive },
              ]"
            >
              <div class="img">
                <img :src="item.imageSrc" />
              </div>
              <div class="cv">
                <h3>{{ item.title }}</h3>
                <p v-html="item.desc"></p>
                <router-link v-if="item.isActive" class="link" :to="item.link"
                  >了解更多<i
                    class="fas fa-angle-right"
                    style="margin-left: 5px"
                  ></i
                ></router-link>
              </div>
              <div class="cover" v-if="!item.isActive">
                <span>coming soon</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="game-card" id="game-card">
      <div style="z-index: 2; position: relative">
        <h2>試算您可借額度</h2>
        <div class="hr"></div>
        <div class="loan-game">
          <h5>點點看～立馬可以試算你的信用額度喔!!</h5>
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
          <button class="btn btn-loan" @click="openDialog()">了解更多</button>
        </div>
        <h2>試算您投資收益</h2>
        <div class="hr"></div>
        <div class="invest-game">
          <h5>
            愛因斯坦說過:「複利的威力大於原子彈」<br />
            普匯平台上擁有8%~20%的債權，來試試看在<input
              v-model="rate"
              type="number"
              min="8"
              max="20"
              style="width: 50px"
            />%的利率下所創造的報酬吧
          </h5>
          <div></div>
          <p>
            每月投資{{ amount }}元，{{ time }}年後預計可回收<b
              style="color: #0005a7"
              >{{ format(tweenedReturnAll) }}</b
            >元
          </p>
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
          <div class="chart" ref="chart">
            <div class="invest-chart" ref="investChart"></div>
          </div>
          <router-link class="btn btn-invest" to="invest">了解更多</router-link>
          <div style="text-align: end; font-size: 13px">
            ＊複利滾投∶每月回款本金及利息，持續再投入借出，產生的投資報酬率。<br />
          </div>
        </div>
      </div>
    </div>
    <div class="advantage-card">
      <div class="header">
        <div class="slg">
          <div data-aos="fade-up" data-aos-duration="700">
            <p class="title">
              以金融為核心，以科技為輔具，
              <br />普匯給您前所未有的最佳使用體驗APP
            </p>
            <p class="l-btn">為什麼選擇普匯金融科技?</p>
          </div>
        </div>
      </div>
      <div class="content" ref="advantage_slick">
        <div
          class="item"
          data-aos="fade-up"
          data-delay="900"
          data-aos-duration="500"
        >
          <div class="img">
            <img :src="'/images/expert.svg'" class="img-fluid" />
          </div>
          <p>最專業的金融專家</p>
          <span
            >普匯擁有超過20年金融專業經驗，深度理解各類金融產品、相關金融法規、財稅務、金流管理...等。能針對不同產業產品與市場，設計出更適合用戶需求的金融服務。</span
          >
        </div>
        <div
          class="item"
          data-aos="fade-up"
          data-delay="900"
          data-aos-duration="500"
        >
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
        <div
          class="item"
          data-aos="fade-up"
          data-delay="900"
          data-aos-duration="500"
        >
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
    <experience :experiences="experiences" title="真實回饋" />
    <div class="information-card" id="information-card">
      <div style="z-index: 2; position: relative">
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
              >AI金融科技新知</a
            >
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
              data-toggle="tab"
              href="#video"
              @click="reSlick('video_slick')"
              >影音專區</a
            >
          </li>
        </ul>
        <div class="tab-content">
          <div id="news" class="tab-pane fade active show">
            <div class="news-slick" ref="news_slick">
              <a
                class="slick-item hvr-float-shadow"
                v-for="(item, index) in news"
                :href="item.link"
                :key="index"
              >
                <div class="img">
                  <img :src="item.image_url" class="img-fluid" />
                </div>
                <p>{{ item.post_title }}</p>
                <span>{{ item.post_date.substr(0, 10) }}</span>
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
              AI金融科技新知&ensp;
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
    </div>
    <div class="slogan-card">
      <p class="content">
        普匯相信每個年輕人，我們致力幫助他們完成人生的夢想
        <br />以金融為核心，以科技為輔具，只為給您最好的體驗
        <br />最貼近年輕人的金融科技平台
      </p>
      <div class="slogan">普匯．你的手機ATM</div>
    </div>
    <download :isLoan="true" :isInvest="true" />
    <div
      class="comfirm-modal modal fade"
      ref="comfirmModal"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" data-dismiss="modal">
        <div class="modal-content">
          <div class="modal-body">
            <h4>您的身分是：</h4>
            <div class="flex">
              <router-link to="collegeLoan" class="i hvr-shadow"
                ><p>學生</p>
                <img :src="'/images/college_loan.png'" class="img-fluid"
              /></router-link>
              <router-link to="freshGraduateLoan" class="i hvr-shadow"
                ><p>上班族</p>
                <img :src="'/images/office_loan.png'" class="img-fluid"
              /></router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
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
    returnAll: 0,
    tweenedReturnAll: 0,
    amount: 10000,
    time: 1,
    rate: 12,
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
        text: "銀行帳號",
        img: "/images/icon_cert_rev2_bank.svg",
        checked: false,
        money: 10000,
      },
      {
        text: "社交帳號",
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
      particlesJS.load("information-card", "data/game.json");
      this.createSlick(this.$refs.advantage_slick, 3, false);
      this.createSlick(this.$refs.banner, 1, true, true);
    });
    AOS.init();

    gsap.to(
      this.$data,
      {
        duration: 2,
        tweenedTransactionCount: 31559,
      },
      1
    );
    gsap.to(
      this.$data,
      {
        duration: 2,
        tweenedMemberAmount: 54188,
      },
      1
    );
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
    rate(data) {
      this.rate = parseInt(data);
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
    returnAll(newVal) {
      gsap.to(this.$data, { duration: 0.5, tweenedReturnAll: newVal });
    },
  },
  methods: {
    openDialog() {
      $(this.$refs.comfirmModal).modal("show");
    },
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
    createSlick(tar, toShow = 4, arrows = true, fade = false) {
      $(tar).slick({
        infinite: true,
        slidesToShow: toShow,
        slidesToScroll: 1,
        // autoplay: true,
        customPaging(slider, i) {
          return '<i class="fas fa-circle"></i>';
        },
        arrows,
        speed: 1000,
        prevArrow: '<i class="fas fa-chevron-left arrow-left"></i>',
        nextArrow: '<i class="fas fa-chevron-right arrow-right"></i>',
        fade,
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

      let listPrinciple = [];
      let listIntrest = [];
      let listEveryFlow = [];
      let xAxisData = [];

      for (q = 0; q < _totalFlow.length; q++) {
        let temp = _totalFlow[q];
        xAxisData.push(`${Math.floor(q / 12) + 1}/${(q % 12) + 1}`);
        listEveryFlow.push(q < _totalFlow.length - 1 ? $this.amount : 0);
        listPrinciple.push(Math.round((temp["principle"] * 1 * 100) / 100));
        listIntrest.push(Math.round((temp["intrest"] * 1 * 100) / 100));
      }

      let _totalIncome =
        listPrinciple[listPrinciple.length - 1] +
        listIntrest[listIntrest.length - 1];

      let _investAll =
        $this.amount > 0 ? $this.amount * ($this.time * 12 - 1) : 0;

      this.returnAll = _totalIncome + _cashLast;

      let _valueTotal =
        _totalIncome + _cashLast - $this.amount * ($this.time * 12 - 1); //期末回款+本金餘額-期初投入-每期投入總額

      let _last2Return =
        listPrinciple[listPrinciple.length - 2] +
        listIntrest[listIntrest.length - 2] +
        $this.amount;

      $($this.$refs.investChart)
        .css("width", `${$($this.$refs.chart).innerWidth()}px`)
        .css("height", `${$($this.$refs.chart).innerHeight()}px`);

      let line_chart = echarts.init($this.$refs.investChart);

      let option = {
        legend: {
          data: [
            { name: "本期回款本金", icon: "path://M19,12l-10,0l0,1l10,0z" },
            { name: "每期投入", icon: "path://M19,12l-10,0l0,1l10,0z" },
            { name: "本期回款利息", icon: "path://M19,12l-10,0l0,1l10,0z" },
          ],
          selectedMode: false,
          right: 10,
          bottom: 10,
        },
        grid: {
          left: 30,
          top: 10,
          right: 30,
          bottom: 100,
        },
        title: {
          subtextStyle: {
            color: "#083a6e",
            fontSize: 14,
          },
          left: "10",
          top: "0",
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
          boundaryGap: false,
          data: xAxisData,
          axisLine: {
            lineStyle: { color: "#ccc" },
          },
          splitLine: {
            show: true,
            lineStyle: { color: "#ccc" },
          },
          axisLabel: {
            color: "#083a6e",
          },
          axisTick: {
            show: false,
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
            lineStyle: { color: "#ccc" },
          },
          splitLine: { show: false },
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
              width: 4,
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
              width: 4,
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
              width: 4,
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

    %arrow {
      position: absolute;
      z-index: 1;
      top: 50%;
      font-size: 27px;
    }

    .arrow-left {
      @extend %arrow;
      left: 10px;
    }

    .arrow-right {
      @extend %arrow;
      right: 10px;
    }

    .puhey-banner {
      .diagram {
        position: absolute;
        top: 11%;
        left: 29%;
        width: 50%;
      }

      .content {
        position: absolute;
        top: 58%;
        left: 35%;
        transform: translate(-50%, -50%);
        text-align: center;

        p {
          text-shadow: 2px 6px 6px rgba(0, 0, 0, 0.75);
          font-family: NotoSansTC;
          font-size: 40px;
          letter-spacing: 2.4px;
          color: #ffffff;
        }

        span {
          text-shadow: 2px 6px 6px rgba(0, 0, 0, 0.75);
          font-size: 36px;
          letter-spacing: 1.8px;
          color: #f2e627;
          text-align: center;
        }
      }

      .box {
        display: flex;
        margin-top: 12rem;

        %block {
          width: 40%;
          margin: 0px auto;
          position: relative;

          :hover {
            color: #ffffff;
            text-decoration: none;
          }

          .text {
            color: #ffffff;
            position: absolute;
            top: 51%;
            left: 47%;
            transform: translate(-50%, -50%);
            font-size: 25px;
          }
        }

        .loan {
          @extend %block;
        }

        .borrow {
          @extend %block;
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
      margin: 10px auto;

      .lr {
        border: 1px solid #ffffff;
        margin: 10px auto;
      }

      .item {
        padding: 0px 20px;

        .count {
          font-size: 40px;
          font-weight: bold;
          line-height: 40px;
        }

        label {
          margin-top: 0.5rem;
          margin-bottom: 0px;
        }
      }
    }
  }

  .product-card {
    overflow: hidden;
    background-image: url("../asset/images/105.png");
    background-position: 0 0;
    background-repeat: no-repeat;
    background-size: cover;
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
          color: #ffffff;
        }

        p {
          font-weight: bold;
          color: #fbd900;
        }

        .imagery {
          display: flex;
          background: #ffffffb0;
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
            color: #083a6e;
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

        .i {
          $bgColor: #2e639b, #0d4c82, #3c73f5, #00b7cc, #1b558e;

          @for $i from 1 through 5 {
            &:nth-of-type(#{$i}) .card-item {
              background: nth($bgColor, $i);
            }
          }

          float: left;
          width: 50%;
          position: relative;

          &:hover {
            z-index: 2;
          }
        }

        .card-item {
          display: flex;
          color: #ffffff;
          height: 230px;
          overflow: hidden;

          .img {
            width: 150px;
            position: relative;
            z-index: -1;

            img {
              height: 200px;
              position: absolute;
              top: 172px;
              left: 9px;
              transform: translate(0px, -50%);
              filter: drop-shadow(3px 3px 3px black);
              opacity: 0.9;
            }
          }

          .cv {
            padding: 20px 20px;
            width: calc(100% - 150px);

            h3 {
              font-weight: bold;
              text-shadow: 0px 0px 4px black;
            }

            p {
              font-size: 17px;
              text-shadow: 0px 0px 4px black;

              b {
                font-size: 25px;
                padding: 0px 5px;
                color: #fbd900;
              }
            }

            .link {
              padding: 5px 10px;
              border: 1px solid;
              color: #ffffff;
              transition-duration: 0.5s;

              &:hover {
                background: #cf3819;
                text-decoration: none;
              }
            }
          }

          .cover {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(50%, 50%);

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
          cursor: default;
        }
      }
    }
  }

  .game-card {
    overflow: hidden;
    background: #2bb9c4;
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
      box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
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
            border: 7px solid #048dbd;
            margin: 5px;
            cursor: pointer;
            overflow: hidden;

            img {
              position: absolute;
              top: 50%;
              left: 50%;
              transform: translate(-50%, -50%);
              width: 70%;
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
          background-image: linear-gradient(129deg, #0057a2 3%, #08d6e0 102%);
          border-radius: 26px;
          width: 45%;
          height: 170px;
          position: relative;
          color: #ffffff;
          margin: 5px auto;

          .total {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;

            strong {
              font-size: 24px;
            }

            span {
              font-size: 65px;
              text-shadow: 1px 1px 2px white;
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
              color: #002dd0;
            }
          }
        }
      }
    }

    .invest-game {
      @extend %layout;
      width: 1018px;

      p {
        background-image: linear-gradient(129deg, #0057a2 3%, #08d6e0 102%);
        width: fit-content;
        margin: 0px auto;
        padding: 10px;
        border-radius: 10px;
        color: #ffffff;
      }

      .chart {
        color: #000000;
        margin: 10px 5px;
        height: 300px;

        .invest-chart {
          text-align: start;
        }
      }

      .option {
        overflow: auto;
        margin: 10px 0px;

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

    %btn {
      width: 150px;
      margin: 10px auto;
      padding: 5px;
      font-weight: bold;
      color: #ffffff;
      text-align: center;
      border-radius: 25px;
      box-shadow: 1px 2px 3px black;
      background: #104073;
    }

    .btn-loan {
      @extend %btn;
    }

    .btn-invest {
      @extend %btn;
    }
  }

  .advantage-card {
    background-image: url("../asset/images/91.png");
    background-repeat: no-repeat;
    background-size: cover;

    .header {
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
    background: #2bb9c4;
    position: relative;

    .particles-js-canvas-el {
      position: absolute;
      top: 0;
    }

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
          font-size: 14px;
          margin: 0.5rem 0px;
          pointer-events: none;
        }

        span {
          float: right;
          color: #8c8c8c;
          font-weight: 100;
          font-size: 13px;
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
    position: relative;
    background-image: url("../asset/images/phone_bg.png");
    background-repeat: no-repeat;
    background-size: cover;

    .content {
      width: fit-content;
      margin: 0px auto;
      text-align: center;
      color: #ffffff;
      font-weight: bold;
      line-height: 1.96;
    }

    .slogan {
      background-color: #fbd900;
      width: 25%;
      margin: 10px auto;
      padding: 10px;
      font-weight: bold;
      text-align: center;
      border-radius: 25px;
    }
  }

  .comfirm-modal {
    .modal-dialog {
      max-width: 290px !important;
    }
    h4 {
      text-align: center;
      font-weight: bolder;
      font-size: 20px;
      margin: 0px;
      color: #8c8c8c;
    }

    .flex {
      display: flex;

      .i {
        padding: 10px;
        margin: 5px;
        background: #c4dcff73;

        &:hover {
          text-decoration: none;
        }

        p {
          text-align: center;
          margin: 0px;
          font-weight: bold;
          font-size: 20px;
        }
      }
    }
  }

  @media screen and (max-width: 767px) {
    .banner {
      .puhey-banner {
        height: 77vh;

        .diagram {
          position: absolute;
          top: -4%;
          left: 10px;
          width: 63%;
        }

        img {
          height: inherit;
          width: initial;
        }

        .content {
          font-size: 16px;
          width: 100%;
          left: 50%;
          top: 50%;

          p {
            font-size: 25px;
            letter-spacing: 0px;
          }

          span {
            font-size: 22px;
            letter-spacing: 0px;
          }

          .box {
            margin-top: 25rem;

            %block {
              width: 45%;

              .text {
                font-size: 22px;
              }
            }
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

          p {
            color: #ffffff;
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
          .i {
            width: calc(100%);

            .card-item {
              margin: 5px 0px;

              .img {
                width: 120px;

                img {
                  left: 5px;
                }
              }

              .cv {
                padding: 10px;
                width: calc(100% - 120px);
              }
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

      h5 {
        font-size: 16px;
      }

      .loan-game {
        .option {
          display: block;
          overflow: hidden;

          .item {
            float: left;
            width: calc(100% / 4 - 10px);

            .circle {
              width: 70px;
              height: 70px;
              margin: 5px auto;
            }
          }
        }

        .credit-num {
          margin: 10px 0px;

          .circle {
            width: initial;

            .total {
              left: 50%;
              padding: 0px 10px;

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
        text-align: justify;
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