<template>
  <main>
    <section class="banner">
      <div class="block-content">
        <div class="banner-text">
          <h1 class="h1 d-none">信保融資專案</h1>
          <div class="links">
            <button class="btn btn-smeg-primary">立即申辦</button>
            <button class="btn btn-smeg-secondary">上傳資料(已申請)</button>
          </div>
        </div>
        <div class="banner-img"></div>
      </div>
    </section>
    <section class="suit">
      <div class="block-content">
        <h1 class="h1">適用對象</h1>
        <h2 class="h2">攜手建立事業版圖 貸您邁向事業成功</h2>
      </div>
    </section>
    <section class="advantage">
      <div class="block-content">
        <h1 class="h1">產品資訊與服務說明</h1>
        <h2 class="h2">讓普匯協助您 一次申請多家銀行 取得資金</h2>
        <div class="features">
          <featureCard title="有營登即可申請" info="" :icon="ficon1">
            <div class="feature-info">有統編，無分行業皆可</div>
          </featureCard>
          <featureCard
            title="一支手機搞定"
            info="APP線上快速申請核准"
            :icon="ficon2"
          >
            <div class="feature-info">APP線上快速申請核准</div>
          </featureCard>
          <featureCard
            title="銀行快速撥款"
            info="直送多家合作銀行審查"
            :icon="ficon3"
          >
            <div class="feature-info">直送多家合作銀行審查</div>
          </featureCard>
          <featureCard
            title="0手續費，0服務費"
            info="普匯平台不收取客戶費用"
            :icon="ficon4"
          >
            <div class="feature-info">普匯平台不收取客戶費用</div>
          </featureCard>
        </div>
        <div class="advantage-title-group">
          <div class="advantage-title-1">
            <span class="pos-1">全台唯一</span>
            <span class="em">銀行信保資金媒合</span>
          </div>
          <div class="advantage-title-2">
            金融科技
            <img src="@/asset/images/coin.png" alt="" />
            銀行資金
            <img src="@/asset/images/coin.png" alt="" />
            信保保證
          </div>
        </div>
        <div class="advantage-rows">
          <div class="advantage-row advantage-row-1">
            <div class="advantage-row-title">真正普匯金融</div>
            <div class="advantage-row-text">
              <div>•麵攤老闆、一人公司</div>
              <div>•有統編，通通可以申請！</div>
            </div>
            <div class="advantage-row-bg">ADVANTAGE 01</div>
          </div>
          <div class="advantage-row">
            <div class="advantage-row-title">數位金融第一品牌</div>
            <div class="advantage-row-text">
              <div>•近100萬筆申貸資料</div>
              <div>•全台最強AI智能</div>
            </div>
            <div class="advantage-row-bg">ADVANTAGE 02</div>
          </div>
          <div class="advantage-row">
            <div class="advantage-row-title">多家金融機構合作</div>
            <div class="advantage-row-text">
              <div>•普匯審核、銀行放款</div>
              <div>•加速資金取得</div>
            </div>
            <div class="advantage-row-bg">ADVANTAGE 03</div>
          </div>
          <div class="advantage-row">
            <div class="advantage-row-title">結合信保基金</div>
            <div class="advantage-row-text">
              <div>•政策扶持，信保機制</div>
              <div>•中小微企業融資最強擔保</div>
            </div>
            <div class="advantage-row-bg">ADVANTAGE 04</div>
          </div>
          <div class="advantage-row">
            <div class="advantage-row-title">最靈活融資管道</div>
            <div class="advantage-row-text">
              •滿足1萬元的急用週轉，到600萬的資金需求
            </div>
            <div class="advantage-row-bg">ADVANTAGE 05</div>
          </div>
        </div>
      </div>
    </section>
    <section class="question">
      <h1 class="h1">申請普匯信保專案FAQ</h1>
      <h2 class="h2">
        <div>您是否也有其他申請人常遇到的問題？</div>
        <div>您想知道的，這裡通通有！</div>
      </h2>
      <div class="question-rows">
        <faq-row title="• 普匯信保融資專案貸款的申請資格？" bg-text="ISSUE 01">
          <div class="faq-content">
            <div>1. 具有合法公司登記或商業登記</div>
            <div>2. 非金融及保險業、宗教職業及類似組織、特殊娛樂業</div>
            <div>3. 實收資本額小於1億元</div>
            <div>4. 員工人數少於200人</div>
            <div>5. 企業與關係人票債信正常</div>
          </div>
        </faq-row>
        <faq-row
          title="• 普匯信保融資專案貸款額度與借款期間？"
          bg-text="ISSUE 02"
        ></faq-row>
        <faq-row title="• 還款方式與利率？" bg-text="ISSUE 03"></faq-row>
        <faq-row
          title="• 服務費、手續費的收費方式？"
          bg-text="ISSUE 04"
        ></faq-row>
        <faq-row
          title="• 申請到撥款，有哪些流程？大概多久？"
          bg-text="ISSUE 05"
        ></faq-row>
      </div>
      <div class="row">
        <div class="col"></div>
        <button class="btn btn-smeg-outline-primary">立即申辦</button>
        <div class="col"></div>
      </div>
    </section>
    <section class="require-data">
      <h1 class="h1">信保融資專案需準備什麼資料？</h1>
      <h2 class="h2">提供負責人與公司相關資料，即時為您送件</h2>
    </section>
    <section class="step">
      <h1 class="h1">申貸簡單四步驟</h1>
      <h2 class="h2">
        <div>全線上申請 x 手機上傳資料 x 5天內銀行放款</div>
        <div>貸你解決企業資金周轉難題</div>
      </h2>
      <div class="block-content">
        <stepGroup :steps="steps" content-class="content-group" />
      </div>
    </section>
    <section class="consultation">
      <h1 class="h1">需要專人服務嗎？</h1>
      <h2 class="h2">請留下簡單訊息，將由融資專人為您解惑！</h2>
    </section>
  </main>
</template>

<script>
import featureCard from '../../component/index/featureCard'
import faqRow from '@/component/faqRow';
import stepGroup from '@/component/index/stepGroup';
export default {
  components: {
    featureCard,
    faqRow,
    stepGroup
  },
  data() {
    return {
      ficon1: require('@/asset/images/workloan/feature-icon-1.png'),
      ficon2: require('@/asset/images/workloan/feature-icon-2.png'),
      ficon3: require('@/asset/images/workloan/feature-icon-3.png'),
      ficon4: require('@/asset/images/workloan/feature-icon-4.png'),
      steps: [
        {
          img: require('@/asset/images/index/invest-step-1.png'),
          title: '步驟一',
          info: '<div>申請「信保專案融資」</div><div>完成法人註冊、負責人實名認證</div>'
        },
        {
          img: require('@/asset/images/index/invest-step-2.png'),
          title: '步驟二',
          info: '確認借款期間與額度，同意申請'
        },
        {
          img: require('@/asset/images/index/invest-step-3.png'),
          title: '步驟三',
          info: '<div>完成負責人、公司資料提供</div><div>(包含負責人配偶、新增保證人)</div>'
        },
        {
          img: require('@/asset/images/index/invest-step-4.png'),
          title: '步驟四',
          info: '<div>等待系統審核並媒合資金方(銀行)</div><div>銀行最終核准後、簽約對保、立即撥款</div>'
        }
      ]
    }
  },
}
</script>

<style lang="scss" scoped>
$color--primary: #036eb7;
$color__text--primary: #023d64;
$color__background--primary: #f3f9fc;
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
  &::before {
    content: '';
    position: absolute;
    top: -2.4px;
    left: -2.4px;
    bottom: -2.4px;
    right: -2px;
    background: linear-gradient(90deg, #3781c7 0%, #74bbe4 100%);
    border-radius: 12px;
    z-index: -1;
  }
  position: relative;
  padding: 10px 120px;
  color: $color--primary;
  background-color: #fff;
  font-style: normal;
  font-weight: 500;
  font-size: 24px;
  line-height: 1.4;
  text-align: center;
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
    background: url('~images/bussinessLoan/smeg-bannner-bg.png');
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
  padding: 100px;
  .h1 {
    color: $color--primary;
  }
  .h2 {
    color: $color--primary;
    opacity: 0.64;
    margin-top: 24px;
  }
}
.advantage {
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
  .features {
    display: grid;
    margin-top: 56px;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 48px;
    .feature-info {
      font-style: normal;
      font-weight: 500;
      font-size: 20px;
      line-height: 1.4;
      text-align: center;
      color: $color--primary;
      opacity: 0.64;
    }
  }
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
    padding: 36px 80px;
    max-width: 1100px;
    margin: 24px auto;
    background-color: #fff;
    box-shadow: 0px 4px 12px rgba(21, 91, 153, 0.16);
    border-radius: 16px;
    position: relative;
    &-1::after {
      display: block;
      position: absolute;
      background-image: url('~images/boss.png');
      content: '';
      width: 238px;
      height: 295px;
      right: 20px;
      bottom: 188px;
    }
    &:nth-child(even) {
      .advantage-row-title {
        color: $color__text--primary;
      }
    }
    &-title {
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      line-height: 1.4;
      color: $color--primary;
      margin-bottom: 9px;
    }
    &-text {
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
.question {
  padding: 80px;
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
  .question-rows {
    padding: 80px 0;
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

.consultation {
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
}
</style>
