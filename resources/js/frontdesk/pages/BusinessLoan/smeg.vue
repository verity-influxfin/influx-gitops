<template>
  <main>
    <section class="banner">
      <div class="block-content">
        <div class="banner-text">
          <h1 class="h1 d-none">信保融資專案</h1>
          <div class="links">
            <a href="/borrowLink" target="_blank">
              <button class="btn btn-smeg-primary">立即申辦</button>
            </a>
            <button class="btn btn-smeg-secondary">上傳資料(已申請)</button>
          </div>
        </div>
        <div class="banner-img"></div>
      </div>
    </section>
    <section class="suit">
      <h1 class="h1">適用對象</h1>
      <h2 class="h2">攜手建立事業版圖 貸您邁向事業成功</h2>
      <div class="block-content">
        <img
          src="@/asset/images/bussinessLoan/smeg-suit-1.png"
          class="suit-1-img"
        />
        <img
          src="@/asset/images/bussinessLoan/smeg-suit-2.png"
          class="suit-2-img"
        />
        <img
          src="@/asset/images/bussinessLoan/smeg-suit-3.png"
          class="suit-3-img"
        />
      </div>
    </section>
    <section class="suit-persons">
      <div class="block-content">
        <smeg-suit />
      </div>
    </section>
    <section class="info">
      <div class="block-content">
        <h1 class="h1">產品資訊與服務說明</h1>
        <h2 class="h2">讓普匯協助您 一次申請多家銀行 取得資金</h2>
        <div class="features">
          <div>
            <div class="feature-slogan-1">
              <div>Product</div>
              <div>Information</div>
            </div>
            <div class="feature-slogan-2">Open Banking API</div>
          </div>
          <div class="feature-items">
            <div class="item">
              •資格：有合法
              <span class="text-color-dark em">公司或商業登記</span>
            </div>
            <div class="item">
              •用途：營運所需 <span class="text-color-dark em">資金周轉</span>
            </div>
            <div class="item">
              •貸款額度 最高 <span class="text-color-orange em">600萬</span>
            </div>
            <div class="item">
              •貸款期間 最長 <span class="text-color-dark em">5年</span>
            </div>
            <div class="item">
              •普匯平台不收取客戶費用
              <span class="text-color-orange em">0</span> 手續費 ,
              <span class="text-color-orange em">0</span> 服務費
            </div>
            <div class="item">
              •國家級 <span class="text-color-dark em">信保基金</span> 為您擔保
              , <span class="text-color-dark em">免抵押品</span>
            </div>
          </div>
        </div>
        <div class="feature-graph-group">
          <div class="feature-graph-center">
            <div class="feature-graph-center-logo">
              <img src="@/asset/images/logo_puhey_v2.png" class="img-fluid" />
            </div>
            <div class="feature-graph-center-circle-1">
              <img
                src="@/asset/images/bussinessLoan/smeg-feature-graph-center-circle-1.svg"
                class="img-fluid"
              />
            </div>
            <div class="feature-graph-center-circle-2">
              <img
                src="@/asset/images/bussinessLoan/smeg-feature-graph-center-circle-2.svg"
                class="img-fluid"
              />
            </div>
          </div>
          <div class="feature-graph-item feature-graph-item-1">
            <div class="feature-graph-item-title">•創新又快速</div>
            <div class="feature-graph-item-divider"></div>
            <div class="feature-graph-item-info">
              <div>全線上金融科技與銀行合作，</div>
              <div>政府信保基金來保證</div>
            </div>
          </div>
          <div class="feature-graph-item feature-graph-item-2">
            <div class="feature-graph-item-title">•簡單又環保</div>
            <div class="feature-graph-item-divider"></div>
            <div class="feature-graph-item-info">
              <div>一次送案多家銀行，</div>
              <div>節省時間又環保</div>
            </div>
          </div>
          <div class="feature-graph-item feature-graph-item-3">
            <div class="feature-graph-item-title">•隱私又安全</div>
            <div class="feature-graph-item-divider"></div>
            <div class="feature-graph-item-info">
              <div>一支手機搞定貸款、全線上無人化，</div>
              <div>不怕洩漏個資</div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="require-data">
      <h1 class="h1">申請需準備什麼資料呢？</h1>
      <h2 class="h2">提供負責人與公司相關資料，即時為您送件</h2>
      <div class="block-content">
        <smegRequireData />
      </div>
    </section>
    <section class="step">
      <h1 class="h1">申貸簡單四步驟</h1>
      <h2 class="h2">
        <div>全線上申請 x 手機上傳資料 x 5天內銀行放款</div>
        <div>貸你解決企業資金周轉難題</div>
      </h2>
      <div class="block-content">
        <stepGroup :steps="steps" content-class="content-group" swiper-mode />
      </div>
    </section>
    <section class="advantage">
      <div class="advantage-title-group">
        <div class="advantage-title-1">
          <span class="pos-1">全台唯一</span>
          <span class="em">銀行信保資金媒合</span>
        </div>
        <div class="advantage-title-2">
          金融科技
          <img src="@/asset/images/coin.png" class="img-fluid" />
          銀行資金
          <img src="@/asset/images/coin.png" class="img-fluid" />
          信保保證
        </div>
      </div>
      <div class="advantage-rows">
        <div class="advantage-row advantage-row-1">
          <div class="advantage-row-icon">
            <img
              src="@/asset/images/bussinessLoan/smeg-advantage-1.svg"
              class="img-fluid"
              alt=""
            />
          </div>
          <div class="advantage-row-title">真正普匯金融</div>
          <div class="advantage-row-text">
            <div>•麵攤老闆、一人公司</div>
            <div>•有統編，通通可以申請！</div>
          </div>
          <div class="advantage-row-bg">ADVANTAGE 01</div>
        </div>
        <div class="advantage-row">
          <div class="advantage-row-icon">
            <img
              src="@/asset/images/bussinessLoan/smeg-advantage-2.svg"
              class="img-fluid"
              alt=""
            />
          </div>
          <div class="advantage-row-title">數位金融第一品牌</div>
          <div class="advantage-row-text">
            <div>•近100萬筆申貸資料</div>
            <div>•全台最強AI智能</div>
          </div>
          <div class="advantage-row-bg">ADVANTAGE 02</div>
        </div>
        <div class="advantage-row">
          <div class="advantage-row-icon">
            <img
              src="@/asset/images/bussinessLoan/smeg-advantage-3.svg"
              class="img-fluid"
            />
          </div>
          <div class="advantage-row-title">多家金融機構合作</div>
          <div class="advantage-row-text">
            <div>•普匯審核、銀行放款</div>
            <div>•加速資金取得</div>
          </div>
          <div class="advantage-row-bg">ADVANTAGE 03</div>
        </div>
        <div class="advantage-row">
          <div class="advantage-row-icon">
            <img
              src="@/asset/images/bussinessLoan/smeg-advantage-4.svg"
              class="img-fluid"
            />
          </div>
          <div class="advantage-row-title">結合信保基金</div>
          <div class="advantage-row-text">
            <div>•政策扶持，信保機制</div>
            <div>•中小微企業融資最強擔保</div>
          </div>
          <div class="advantage-row-bg">ADVANTAGE 04</div>
        </div>
        <div class="advantage-row">
          <div class="advantage-row-icon">
            <img
              src="@/asset/images/bussinessLoan/smeg-advantage-5.svg"
              class="img-fluid"
            />
          </div>
          <div class="advantage-row-title">最靈活融資管道</div>
          <div class="advantage-row-text">
            •滿足1萬元的急用週轉，到600萬的資金需求
          </div>
          <div class="advantage-row-bg">ADVANTAGE 05</div>
        </div>
      </div>
    </section>
    <section class="consultation">
      <h1 class="h1">需要專人服務嗎？</h1>
      <h2 class="h2">請留下簡單訊息，將由融資專人為您解惑！</h2>
      <div class="block-content">
        <form
          ref="contactForm"
          @submit.prevent="doContact"
          class="row no-gutters justify-content-center"
        >
          <div class="col-md col-12">
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*姓名：</div>
              <input
                type="text"
                name="name"
                class="form-item-input col"
                maxlength="20"
                required
              />
            </div>
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*身分性別：</div>
              <div class="radio-custom">
                <label class="form-item-label">
                  <input
                    type="radio"
                    name="gender"
                    class="radio-inline"
                    value="1"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>男
                </label>
                <label class="form-item-label">
                  <input
                    type="radio"
                    name="gender"
                    class="radio-inline"
                    value="2"
                    required
                  />
                  <span class="outside"><span class="inside"></span></span>女
                </label>
              </div>
            </div>
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*公司名稱：</div>
              <input
                type="text"
                name="company_name"
                maxlength="10"
                class="form-item-input col"
              />
            </div>
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*電子信箱：</div>
              <input
                type="text"
                name="email"
                maxlength="40"
                required
                class="form-item-input col"
              />
            </div>
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*聯絡時間：</div>
              <select
                type="text"
                class="form-item-input col"
                name="contact_time"
                required
              >
                <option selected disabled value="">-請選擇-</option>
                <option value="1">隨時</option>
                <option value="2">上午(09:00 ~ 12:00)</option>
                <option value="3">下午(13:30 ~ 18:00)</option>
              </select>
            </div>
            <div class="row no-gutters mb-3">
              <div class="form-item-text">*需求原因：</div>
              <textarea
                type="text"
                name="reason"
                style="min-height: 10vh"
                class="form-item-input col"
                maxlength="40"
                required
              ></textarea>
            </div>
            <div class="row no-gutters">
              <div class="form-item-text d-md-block d-none"></div>
              <button class="btn btn-block btn-form-submit col" type="submit">
                立即諮詢
              </button>
            </div>
          </div>
          <div class="col-md-6 col-12">
            <img
              src="@/asset/images/bussinessLoan/smeg-form-cover.png"
              class="img-fluid form-cover"
            />
          </div>
        </form>
      </div>
    </section>
    <section class="question">
      <h1 class="h1">申請普匯中小企業融資專案FAQ</h1>
      <h2 class="h2">
        <div>您是否也有其他申請人常遇到的問題？</div>
        <div>您想知道的，這裡通通有！</div>
      </h2>
      <div class="question-rows">
        <faq-row title="• 普匯中小企業融資專案貸款的申請資格？" bg-text="ISSUE 01">
          <div class="faq-content">
            <div>1. 具有合法公司登記或商業登記</div>
            <div>2. 非金融及保險業、宗教職業及類似組織、特殊娛樂業</div>
            <div>3. 實收資本額小於1億元</div>
            <div>4. 員工人數少於200人</div>
            <div>5. 企業與關係人票債信正常</div>
          </div>
        </faq-row>
        <faq-row
          title="• 普匯中小企業融資專案貸款額度與借款期間？"
          bg-text="ISSUE 02"
        >
          <div class="faq-content">
            <div>申請額度：最高可貸款到600萬</div>
            <div>借款期間：最長可以攤分5年還款</div>
          </div>
        </faq-row>
        <faq-row title="• 還款方式與利率？" bg-text="ISSUE 03">
          <div class="faq-content">
            <div>還款方式：每月平均攤還本息</div>
            <div>銀行利率：3.8%起</div>
          </div>
        </faq-row>
        <faq-row title="• 服務費、手續費的收費方式？" bg-text="ISSUE 04">
          <div class="faq-content">
            <div>普匯平台不另外向您收取任何費用，<em>0服務費、0手續費</em></div>
          </div>
        </faq-row>
        <faq-row
          title="• 申請到撥款，有哪些流程？大概多久？"
          bg-text="ISSUE 05"
        >
          <div class="faq-content">
            <div>
              普匯中小企業融資專案貸款，全線上無人化系統審核，所有申請流程均透過一支手機完成
            </div>
            <div>最快不到2個工作日，即可核准</div>
            <div>銀行端簽約對保並撥款，資料齊備且送達最快3個工作日撥款</div>
          </div>
        </faq-row>
      </div>
      <div class="row">
        <div class="col"></div>
        <a href="/borrowLink" target="_blank">
          <button class="btn btn-smeg-outline-primary">立即申辦</button>
        </a>
        <div class="col"></div>
      </div>
    </section>
  </main>
</template>

<script>
import faqRow from '@/component/faqRow';
import stepGroup from '@/component/index/stepGroup';
import smegRequireData from '@/component/smegRequireData';
import smegSuit from '@/component/smegSuit';
import Axios from 'axios'

export default {
  components: {
    faqRow,
    stepGroup,
    smegRequireData,
    smegSuit
  },
  data() {
    return {
      ficon1: require('@/asset/images/workloan/feature-icon-1.png'),
      ficon2: require('@/asset/images/workloan/feature-icon-2.png'),
      ficon3: require('@/asset/images/workloan/feature-icon-3.png'),
      ficon4: require('@/asset/images/workloan/feature-icon-4.png'),
      steps: [
        {
          img: require('@/asset/images/bussinessLoan/smeg-feature-step-1.png'),
          title: '步驟一',
          info: '<div>申請「中小企業融資專案」</div><div>完成法人註冊、負責人實名認證</div>'
        },
        {
          img: require('@/asset/images/bussinessLoan/smeg-feature-step-2.png'),
          title: '步驟二',
          info: '確認借款期間與額度，同意申請'
        },
        {
          img: require('@/asset/images/bussinessLoan/smeg-feature-step-3.png'),
          title: '步驟三',
          info: '<div>完成負責人、公司資料提供</div><div>(包含負責人配偶、新增保證人)</div>'
        },
        {
          img: require('@/asset/images/bussinessLoan/smeg-feature-step-4.png'),
          title: '步驟四',
          info: '<div>等待系統審核並媒合資金方(銀行)</div><div>銀行最終核准後、簽約對保、立即撥款</div>'
        }
      ]
    }
  },
  methods: {
    doContact() {
      const data = new FormData(this.$refs.contactForm)
      Axios({
        url: '/api/v1/business-loan/contact',
        method: 'POST',
        data,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
          'Accept': 'application/json',
        }
      }).then(({ data }) => {
        if (data.success) {
          this.$refs.contactForm.reset()
          alert(data.msg)
        }
      }).catch(({ response }) => {
        const d = response.data.data
        let errorMsg = ''
        for (const item in d) {
          errorMsg += d[item].toString() + '\n'

        }
        alert(errorMsg)
      })
    }
  },
}
</script>

<style lang="scss">
.radio-custom-work {
  display: contents;
  label {
    display: inline-block;
    position: relative;
    padding-left: 23px;
    cursor: pointer;
    width: auto;
    margin-right: 20px;
    line-height: 29px;
  }

  input {
    height: 1px;
    width: 1px;
    opacity: 0;
  }

  .work-input-outside {
    display: inline-block;
    position: absolute;
    left: 0%;
    top: 30%;
    margin-top: 0;
    width: 22px;
    height: 22px;
    border: 2px solid #036eb7;
    border-radius: 12px;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    background: #fff;
  }

  .work-input-inside {
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    display: inline-block;
    border-radius: 12px;
    width: 12px;
    height: 12px;
    background: #036eb7;
    left: 3px;
    top: 3px;
    -webkit-transform: scale(0, 0);
    transform: scale(0, 0);
  }
  .no-transforms .work-input-inside {
    left: auto;
    top: auto;
    width: 0;
    height: 0;
  }

  input:checked + .work-input-outside .work-input-inside {
    animation: radio-select 0.1s linear;
    transform: scale(1, 1);
  }
  .no-transforms input:checked + .work-input-outside .work-input-inside {
    width: 9px;
    height: 9px;
  }
}
@media screen and (max-width: 767px) {
  .radio-custom-work label {
    margin-right: 10px;
  }
  .radio-custom-work .work-input-outside {
    margin-top: -6px;
    width: 22px;
    height: 22px;
  }
  .radio-custom-work .work-input-inside {
    width: 16px;
    height: 16px;
    top: 1px;
    left: 1px;
  }
}
</style>
<style lang="scss" scoped>
$color--primary: #036eb7;
$color__text--primary: #023d64;
$color__background--primary: #f3f9fc;
@mixin animate-set {
  animation-duration: 12s;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
}
@keyframes rotate {
  from {
    transform: rotate(0);
  }
  to {
    transform: rotate(360deg);
  }
}
@keyframes rotate2 {
  from {
    transform: rotate(360deg);
  }
  to {
    transform: rotate(0deg);
  }
}
.btn-link {
  text-decoration: underline;
}
.h1 {
  text-align: center;
  font-style: normal;
  font-weight: 700;
  font-size: 56px;
  line-height: 1.4;
}
.h2 {
  text-align: center;
  font-style: normal;
  font-weight: 500;
  font-size: 40px;
  line-height: 1.4;
}
.block-content {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
}
.form-item-text {
  font-style: normal;
  font-weight: 500;
  font-size: 30px;
  line-height: 1.2;
  min-width: 225px;
  color: $color__text--primary;
}
.form-item-input {
  padding: 4px 12px;
  font-size: 24px;
  line-height: 1.2;
  border: 2px solid $color--primary;
  border-radius: 12px;
}
.form-item-label {
  font-size: 24px;
  line-height: 1.2;
  color: $color__text--primary;
  opacity: 0.7;
  width: fit-content;
  margin-right: 15px;
}
.btn-smeg-primary {
  padding: 8px 39px;
  background-color: $color--primary;
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 1.4;
  text-align: center;
  color: #ffffff;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  border-radius: 12px;
}
.btn-smeg-secondary {
  padding: 8px 39px;
  background-color: #f29500;
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 1.4;
  text-align: center;
  color: #ffffff;
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  border-radius: 12px;
}
.btn-smeg-outline-primary {
  position: relative;
  padding: 10px 120px;
  color: $color--primary;
  background-color: #fff;
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 1.4;
  border: 1.5px solid #036eb7;
  text-align: center;
  border-radius: 12px;
}
.btn-form-submit {
  font-style: normal;
  font-weight: 500;
  font-size: 30px;
  line-height: 1.4;
  color: #ffffff;
  background-color: $color--primary;
  border-radius: 12px;
}
.banner {
  background: url('~images/bussinessLoan/smeg-banner-bg-all.png');
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  height: 800px;
  position: relative;
  .block-content {
    background: url('~images/bussinessLoan/smeg-banner-bg.png');
    background-position: center;
    height: 100%;
    display: flex;
    justify-content: center;
  }
  .banner-text {
    position: absolute;
    bottom: 96px;
    .links {
      display: flex;
      gap: 550px;
      .btn-smeg-primary {
        padding: 12px 78px;
      }
      .btn-smeg-secondary {
        padding: 12px 32px;
      }
    }
  }
}
.suit {
  padding: 100px 0 10px;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .block-content {
    position: relative;
    width: fit-content;
    padding: 80px 0 160px;
    background-image: url('~images/bussinessLoan/smeg-robot-hand.png');
    background-repeat: no-repeat;
    background-position: 171px 380px;
  }
  &-1-img {
    position: relative;
    z-index: 2;
  }
  &-2-img {
    position: absolute;
    left: 236px;
    top: 114px;
    animation: rotate;
    @include animate-set;
    z-index: -1;
  }
  &-3-img {
    position: absolute;
    left: 272px;
    top: 151px;
    animation: rotate2;
    @include animate-set;
    z-index: -1;
  }
}
.suit-persons {
  background-image: url('~images/bussinessLoan/smeg-suit-person-bg.png');
  padding: 140px 0 100px;
  background-size: cover;
  background-repeat: no-repeat;
}
.info {
  padding: 80px;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .features {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 42px;
    padding: 84px 0;
    .feature-slogan {
      &-1 {
        font-family: 'Arial';
        font-style: italic;
        font-weight: 700;
        font-size: 96px;
        line-height: 110px;
        text-align: right;
        color: $color--primary;
        opacity: 0.1;
      }
      &-2 {
        margin-top: 42px;
        padding-left: 30px;
        font-family: 'Arial';
        font-style: italic;
        font-weight: 700;
        font-size: 36px;
        line-height: 41px;
        letter-spacing: 0.08em;
        color: #023d64;
        opacity: 0.4;
      }
    }
    .feature-items {
      font-size: 36px;
      position: relative;
      transform: skewX(-25deg);
      &::before {
        width: 3px;
        background: linear-gradient(180deg, #036eb7 0%, #ffffff 100%),
          linear-gradient(0deg, #afd6e2, #afd6e2);
        content: '';
        top: 10%;
        height: 80%;
        position: absolute;
      }
      .item {
        padding-left: 36px;
        margin-bottom: 12px;
        transform: skewX(25deg);
        font-style: normal;
        font-weight: 700;
        font-size: 36px;
        line-height: 1.4;
        color: $color--primary;
        .em {
          font-size: 40px;
        }
        .text-color-dark {
          color: $color__text--primary;
        }
        .text-color-orange {
          color: #f29500;
        }
      }
    }
  }
  .feature-graph-group {
    display: grid;
    grid-template-columns: 1fr 455px 1fr;
    grid-template-rows: 206px 152px auto;
    gap: 10px 0px;

    .feature-graph-center {
      grid-area: 1 / 2 / 4 / 3;
      position: relative;
      z-index: 5;
      &-logo {
        position: absolute;
        left: 22%;
        top: 140px;
      }
      &-circle-1 {
        position: absolute;
        left: 12%;
        top: 75px;
        animation-name: rotate;
        @include animate-set;
      }
      &-circle-2 {
        position: absolute;
        top: -10px;
        left: 3%;
        animation-name: rotate2;
        @include animate-set;
      }
    }
    .feature-graph-item {
      &-1 {
        grid-area: 1 / 3 / 2 / 4;
        position: relative;
        align-self: end;
        &::before {
          position: absolute;
          display: block;
          width: 115px;
          height: 27px;
          left: -110px;
          top: 20px;
          content: '';
          background-image: url('~images/bussinessLoan/smeg-feature-graph-item-1.png');
        }
      }
      &-2 {
        grid-area: 2 / 1 / 3 / 2;
        justify-self: right;
        padding-right: 35px;
        position: relative;
        &::before {
          position: absolute;
          display: block;
          width: 168px;
          height: 55px;
          right: -63px;
          top: -11px;
          content: '';
          background-image: url('~images/bussinessLoan/smeg-feature-graph-item-2.png');
        }
      }
      &-3 {
        grid-area: 3 / 3 / 4 / 4;
        position: relative;
        padding-left: 15px;
        &::before {
          position: absolute;
          display: block;
          width: 115px;
          height: 26px;
          left: -104px;
          top: -4px;
          content: '';
          background-image: url('~images/bussinessLoan/smeg-feature-graph-item-3.png');
        }
      }
      &-title {
        font-style: normal;
        font-weight: 700;
        font-size: 28px;
        line-height: 1.4;
        color: $color__text--primary;
      }
      &-divider {
        margin: 6px 0 6px 35px;
        width: 60px;
        border-top: 4px solid #393939;
        opacity: 0.25;
      }
      &-info {
        margin-left: 35px;
        font-style: normal;
        font-weight: 400;
        font-size: 24px;
        line-height: 1.4;
        color: $color--primary;
      }
    }
  }
}
.require-data {
  padding: 80px;
  background: $color__background--primary;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .block-content {
    margin-top: 84px;
    margin-bottom: 40px;
  }
}
.step {
  padding: 80px;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .block-content {
    width: 700px;
    &::v-deep .content-group {
      width: 365px;
    }
  }
}
.advantage {
  padding: 80px;
  background: url('~images/bussinessLoan/smeg-advantage-bg.png');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  .advantage-title-group {
    padding: 120px 0 60px;
    .advantage-title-1 {
      position: relative;
      font-style: normal;
      font-weight: 700;
      font-size: 40px;
      line-height: 1.4;
      margin-left: 25%;
      color: $color__text--primary;
      .em {
        color: $color--primary;
      }
      .pos-1 {
        position: relative;
        z-index: 3;
        &::before {
          bottom: -30px;
          right: -15px;
          z-index: -1;
          position: absolute;
          content: 'NO 1';
          font-style: normal;
          font-weight: 900;
          font-size: 128px;
          line-height: 1.4;
          text-align: center;
          color: #ffffff;
          width: 320px;
        }
      }
    }
    .advantage-title-2 {
      margin-top: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 5px;
      font-style: normal;
      font-weight: 500;
      font-size: 36px;
      line-height: 1.4;
      color: #707070;
    }
  }
  .advantage-row {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 10px 36px;
    grid-template-rows: auto auto;
    padding: 26px 80px;
    max-width: 1100px;
    margin: 24px auto;
    background-color: #fff;
    box-shadow: 0px 4px 12px rgba(21, 91, 153, 0.16);
    border-radius: 16px;
    position: relative;
    &::before {
      content: '';
      position: absolute;
      width: 170px;
      height: 170px;
      right: 115px;
      bottom: 45px;
      background-image: url('~images/2022_campus_ambassador/2022-campus-benefit-card-bg-1.png');
      background-repeat: no-repeat;
      transform: rotate(40deg);
      opacity: 0.9;
    }
    &-1::after {
      display: block;
      position: absolute;
      background-image: url('~images/boss.png');
      content: '';
      width: 238px;
      height: 295px;
      right: 20px;
      bottom: 203px;
    }
    &:nth-child(even) {
      .advantage-row-title {
        color: $color__text--primary;
      }
      .advantage-row-icon {
        border: 2px solid $color__text--primary;
      }
    }
    &-icon {
      grid-area: 1 / 1 / 3 / 2;
      border-radius: 50%;
      padding: 34px;
      border: 2px solid $color--primary;
    }
    &-title {
      grid-area: 1 / 2 / 2 / 3;
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      line-height: 1.4;
      color: $color--primary;
      margin-bottom: 9px;
    }
    &-text {
      grid-area: 2 / 2 / 3 / 3;
      font-style: normal;
      font-weight: 400;
      font-size: 20px;
      line-height: 1.4;
      letter-spacing: 0.04em;
      color: #707070;
    }
    &-bg {
      position: absolute;
      right: 0;
      bottom: 0;
      font-family: 'Arial';
      font-style: italic;
      font-weight: 700;
      font-size: 76px;
      line-height: 1;
      color: $color__text--primary;
      opacity: 0.05;
    }
  }
}
.consultation {
  padding: 80px;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .block-content {
    margin-top: 120px;
  }
  .form-cover {
    margin-left: 155px;
  }
}
.question {
  padding: 80px;
  background: $color__background--primary;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
  .question-rows {
    display: grid;
    grid-template-columns: 1fr;
    max-width: 1100px;
    margin: 0 auto;
    gap: 24px;
    padding: 80px 0;
  }
  .faq-content {
    font-style: normal;
    font-weight: 400;
    font-size: 20px;
    line-height: 2;
    letter-spacing: 0.04em;
    color: #707070;
  }
}
@media screen and (max-width: 767px) {
  .btn-link {
    text-decoration: underline;
  }
  .h1 {
    font-size: 28px;
  }
  .h2 {
    font-size: 18px;
  }
  .block-content {
    width: 100%;
    max-width: 1400px;
    margin: 0 15px;
  }
  .form-item-text {
    font-size: 16px;
    min-width: 85px;
  }
  .form-item-input {
    padding: 4px 12px;
    font-size: 14px;
  }
  .form-item-label {
    font-size: 14px;
    margin-right: 15px;
  }
  .btn-smeg-primary {
    padding: 12px 46px;
    font-size: 14px;
  }
  .btn-smeg-secondary {
    padding: 12px 46px;
    font-size: 14px;
  }
  .btn-smeg-outline-primary {
    padding: 12px 46px;
    font-size: 14px;
  }
  .btn-form-submit {
    font-size: 14px;
  }
  .banner {
    background: url('~images/bussinessLoan/smeg-banner-bg-phone.png');
    background-repeat: no-repeat;
    background-position: center center;
    background-size: cover;
    height: 760px;
    .block-content {
      background: none;
    }
    .banner-text {
      bottom: 366px;
      .links {
        gap: 24px;
        .btn-smeg-primary {
          padding: 12px 32px;
        }
        .btn-smeg-secondary {
          padding: 12px 32px;
        }
      }
    }
  }
  .suit {
    padding: 36px 0 10px;
    margin-bottom: -200px;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 8px;
    }
    .block-content {
      position: relative;
      left: -280px;
      top: -100px;
      transform: scale(0.39);
      width: fit-content;
      margin: -100px 15px;
      padding: 0 0 200px;
    }
    &-2-img {
      top: 6px;
    }
    &-3-img {
      top: 38px;
    }
  }
  .suit-persons {
    background-image: url('~images/bussinessLoan/smeg-suit-person-bg.png');
    padding: 80px 0 80px;
    .block-content {
      width: 90%;
    }
  }
  .info {
    padding: 36px 0;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 24px;
    }
    .features {
      display: flex;
      flex-direction: column;
      gap: 21px;
      padding: 36px 30px 0 15px;
      .feature-slogan {
        &-1 {
          font-size: 32px;
          text-align: left;
          line-height: 1.2;
          opacity: 0.1;
        }
        &-2 {
          margin-top: 15px;
          padding-left: 0;
          text-align: right;
          font-size: 20px;
          line-height: 1.05;
        }
      }
      .feature-items {
        font-size: 14px;
        position: relative;
        transform: none;
        &::before {
          width: 40px;
          content: '';
          top: -10px;
          left: 40%;
          height: 2px;
          position: absolute;
        }
        .item {
          padding-left: 0;
          margin-bottom: 7px;
          transform: none;
          font-size: 14px;
          .em {
            font-size: 16px;
          }
          .text-color-dark {
            color: $color__text--primary;
          }
          .text-color-orange {
            color: #f29500;
          }
        }
      }
    }
    .feature-graph-group {
      display: grid;
      position: relative;
      left: -35px;
      top: -20px;
      transform: scale(0.84);
      grid-template-columns: 1fr 160px 1fr;
      grid-template-rows: 147px 36px auto;
      gap: 10px 0px;
      .feature-graph-center {
        grid-area: 1 / 2 / 4 / 3;
        position: relative;
        z-index: 5;
        &-logo {
          position: absolute;
          left: 20%;
          height: 92px;
          width: 92px;
          top: 108px;
        }
        &-circle-1 {
          position: absolute;
          left: 6%;
          top: 82px;
          width: 141px;
          height: 141px;
          animation-name: rotate;
          @include animate-set;
        }
        &-circle-2 {
          position: absolute;
          top: 37px;
          left: -7%;
          width: 188px;
          animation-name: rotate2;
          @include animate-set;
        }
      }
      .feature-graph-item {
        &-1 {
          grid-area: 1 / 3 / 2 / 4;
          position: relative;
          align-self: end;
          &::before {
            left: -85px;
            top: 3px;
            content: '';
            transform: scale(0.45);
            background-image: url('~images/bussinessLoan/smeg-feature-graph-item-1.png');
          }
        }
        &-2 {
          grid-area: 2 / 1 / 3 / 2;
          justify-self: right;
          padding-right: 47px;
          position: relative;
          &::before {
            right: -63px;
            top: -11px;
            content: '';
            transform: scale(0.45);
            background-image: url('~images/bussinessLoan/smeg-feature-graph-item-2.png');
          }
        }
        &-3 {
          grid-area: 3 / 3 / 4 / 4;
          position: relative;
          padding-left: 15px;
          &::before {
            left: -70px;
            top: -8px;
            content: '';
            transform: scale(0.45);
            background-image: url('~images/bussinessLoan/smeg-feature-graph-item-3.png');
          }
        }
        &-title {
          font-size: 14px;
        }
        &-divider {
          margin: 6px 0 6px 15px;
          width: 30px;
        }
        &-info {
          margin-left: 15px;
          font-size: 12px;
          width: 84px;
        }
      }
    }
  }
  .require-data {
    padding: 80px 15px 80px 0;
    .h2 {
      margin-top: 8px;
    }
    .block-content {
      margin-top: 18px;
      margin-bottom: 15px;
    }
  }
  .step {
    padding: 80px 0;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      margin-top: 9px;
      margin-bottom: 25px;
    }
    .block-content {
      width: 90%;
      &::v-deep .content-group {
        width: 155px;
      }
    }
  }
  .advantage {
    padding: 90px 15px 60px;
    .advantage-title-group {
      padding: 0;
      .advantage-title-1 {
        font-size: 18px;
        margin-left: 15px;
        color: $color__text--primary;
        .em {
          color: $color--primary;
        }
        .pos-1 {
          &::before {
            bottom: 7px;
            right: -80px;
            content: 'NO 1';
            font-size: 64px;
            width: 160px;
          }
        }
      }
      .advantage-title-2 {
        margin-top: 10px;
        display: flex;
        align-items: center;
        margin-left: 15px;
        justify-content: flex-start;
        gap: 5px;
        height: 24px;
        font-size: 16px;
        .img-fluid {
          transform: scale(0.75);
        }
      }
    }
    .advantage-row {
      display: grid;
      grid-template-columns: 45px 1fr;
      gap: 4px 10px;
      grid-template-rows: auto auto;
      padding: 6px 24px 16px;
      margin: 12px 15px;
      &::before {
        right: 45px;
        bottom: 65px;
        opacity: 0.9;
        transform: scale(0.45) rotate(70deg);
      }
      &-1::after {
        content: '';
        transform: scale(0.5);
        right: -30px;
        bottom: 10px;
      }
      &:nth-child(even) {
        .advantage-row-title {
          color: $color__text--primary;
        }
        .advantage-row-icon {
          border: 2px solid $color__text--primary;
        }
      }
      &-icon {
        grid-area: 1 / 1 / 3 / 2;
        align-self: center;
        border-radius: 50%;
        padding: 8px;
        width: 45px;
        height: 45px;
        border: 1px solid $color--primary;
      }
      &-title {
        grid-area: 1 / 2 / 2 / 3;
        font-size: 14px;
        margin-bottom: 4px;
      }
      &-text {
        grid-area: 2 / 2 / 3 / 3;
        font-size: 12px;
      }
      &-bg {
        position: absolute;
        right: 15px;
        bottom: 0;
        font-family: 'Arial';
        font-style: italic;
        font-weight: 700;
        font-size: 18px;
        line-height: 1;
        color: $color__text--primary;
        opacity: 0.05;
      }
    }
  }
  .consultation {
    padding: 32px;
    .h2 {
      margin-top: 8px;
    }
    .block-content {
      margin-top: 30px;
    }
    .form-cover {
      margin-left: 0;
      margin-top: 20px;
    }
  }
  .question {
    padding: 36px 0;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      margin-top: 8px;
    }
    .question-rows {
      display: grid;
      grid-template-columns: 1fr;
      margin: 0 15px;
      gap: 24px;
      padding: 24px 0;
    }
    .faq-content {
      font-size: 12px;
    }
  }
}
</style>
