<template>
  <div class="business-loan">
    <div class="header row">
      <div class="header-col col">
        <div class="header-title">微企e秒貸</div>
        <div class="header-text">
          <div v-for="text in headerText" :key="text">{{ text }}</div>
        </div>
        <div class="get-start">
          <router-link :to="{ name: 'sme-apply' }" target="_blank">
            <button class="btn">
              <img
                src="../../asset/images/sme-apply-btn.png"
                alt="立即申辦"
                class="btn-img"
              />
            </button>
          </router-link>
          <router-link :to="{ name: 'sme-consult' }" target="_blank">
            <button class="btn">
              <img
                class="btn-img"
                src="../../asset/images/sme-consult-btn.png"
                alt="我要諮詢"
              />
            </button>
          </router-link>
        </div>
      </div>
    </div>
    <title-dots :no_color="true">
        <div class="title">不一樣的企業貸款</div>
    </title-dots>
    <div class="features-row">
      <div class="feature" v-for="(feature, i) in features" :key="i">
        <div class="icon">
          <!-- <img src="" class="icon-img" /> -->
          <img :src="feature.img" class="icon-img" />
        </div>
        <div class="feature-title" v-if="feature.title">
          {{ feature.title }}
        </div>
        <ol class="feature-text">
          <li v-for="text in feature.text" :key="text">{{ text }}</li>
        </ol>
      </div>
    </div>
    <div class="suitable-row">
      <title-dots>
        <div class="title">適用對象</div>
      </title-dots>
      <div class="content-row row">
        <div class="col-sm-auto col-12">
          <div class="suit-item">
            <div class="suit-title">普惠金融擴大服務</div>
            <div class="suit-sub-title"></div>
            <div class="suit-text">
              <div>百萬微企融資</div>
              <div>為新創快速注入資金</div>
              <div>創新金融科技服務模式</div>
              <div>簡單快速便利•普惠金融</div>
            </div>
          </div>
        </div>
        <div class="col mt-4 mt-sm-0">
          <div class="row no-gutters flex-nowrap">
            <div class="suit-item line col-sm-auto">
              <div class="line-col">
                <div class="line-group">
                  <div class="circle"></div>
                  <div class="line"></div>
                </div>
              </div>
              <div class="feature-content">
                <div class="suit-title">新創微型企業</div>
                <div class="suit-sub-title">New Ventures</div>
                <div class="suit-text">
                  <div>Start up</div>
                  <div>育成中心團隊</div>
                  <div>新創微型企業</div>
                </div>
              </div>
            </div>
            <div class="suit-cover item-1 suit-col col-sm col-auto">
              <img src="../../asset/images/suit-1.png" class="suit-img" />
            </div>
          </div>
          <div class="row no-gutters flex-nowrap">
            <div class="suit-item line">
              <div class="line-col">
                <div class="line-group">
                  <div class="circle"></div>
                  <div class="line"></div>
                </div>
              </div>
              <div class="feature-content">
                <div class="suit-title">電子商務</div>
                <div class="suit-sub-title">E-commerce</div>
                <div class="suit-text">
                  <div>新型態商業模式</div>
                  <div>KOL網紅經濟</div>
                  <div>直播業者</div>
                </div>
              </div>
            </div>
            <div class="suit-cover item-2 suit-col col-auto">
              <img src="../../asset/images/suit-2.png" class="suit-img" />
            </div>
          </div>
          <div class="row no-gutters flex-nowrap">
            <div class="suit-item line">
              <div class="line-col">
                <div class="line-group">
                  <div class="circle"></div>
                  <div class="line"></div>
                </div>
              </div>
              <div class="feature-content">
                <div class="suit-title">中小企業(主)</div>
                <div class="suit-sub-title">Small and medium enterprise</div>
                <div class="suit-text">
                  <div>文創業者</div>
                  <div>商店小老闆</div>
                  <div>一般公司行號</div>
                </div>
              </div>
            </div>
            <div class="suit-cover item-3 suit-col col-sm col-auto">
              <img src="../../asset/images/suit-3.png" class="suit-img" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="apply-row">
        <div class="">
          <div class="apply-qualify">
            <div class="qualify-title">申請資格</div>
            <ul class="qualify-text">
              <li v-for="text in applyQualify" :key="text">{{ text }}</li>
            </ul>
            <div class="qualify-line"></div>
          </div>
          <div class="apply-require">
            <div class="require-title">申請條件</div>
            <ul class="require-text">
              <li v-for="text in applyRequire" :key="text">{{ text }}</li>
            </ul>
            <div class="require-line"></div>
          </div>
          <div class="apply-process">
            <div class="process-title">申請流程</div>
            <div class="apply-processes">
              <div class="process-item item-1">
                <div>下載</div>
                <div>⌜普匯inFlux⌟APP</div>
                <div class="item-1-line">
                  <div class="line"></div>
                  <div class="circle"></div>
                </div>
              </div>
              <div class="process-item item-2">
                <div>申請</div>
                <div>⌜公司行號/微企e秒貸⌟</div>
                <div class="item-2-line">
                  <div class="line"></div>
                  <div class="circle"></div>
                </div>
              </div>
              <div class="process-item item-3">
                <div>註冊登入</div>
                <ul class="item-highlight">
                  <li>負責人</li>
                  <li>公司行號</li>
                </ul>
                <div class="item-3-line">
                  <div class="line"></div>
                  <div class="circle"></div>
                </div>
              </div>
              <div class="process-item item-4">
                <div>資料提供</div>
                <ul class="item-highlight">
                  <li>負責人、配偶、公司基本資料</li>
                  <li>聯合徵信中心報告</li>
                  <li>勞動保險變動明細</li>
                  <li>財務及收入證明</li>
                  <li>其他佐證資料</li>
                </ul>
                <div class="item-4-line">
                  <div class="line"></div>
                  <div class="circle"></div>
                </div>
              </div>
              <div class="process-item item-5">
                <div>等待系統審核</div>
                <div>媒合資金方</div>
                <div class="coop-title">目前資金合作方：</div>
                <a class="coop-link" href="https://www.skbank.com.tw/df0df61416.html?" target="_blank">新光銀行（OU微企貸）</a>
                <div class="item-5-line">
                  <div class="line"></div>
                  <div class="circle"></div>
                </div>
              </div>
              <div class="process-item item-6">
                <div>對保簽約後</div>
                <div>立即撥款</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="step-row">
      <title-dots>
        <div class="title">三步驟•普匯微企e秒貸</div>
      </title-dots>
      <div class="swiper">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
          <!-- Slides -->
          <div class="swiper-slide" v-for="(item, index) in steps" :key="index">
            <div class="step-content">
              <div class="col-auto get-app">
                <router-link to="/borrowLink" target="_blank">
                  <img
                    class="get-app-img"
                    src="../../asset/images/get-on-apple.png"
                    alt="download app"
                  />
                </router-link>
                <router-link to="/borrowLink" target="_blank">
                  <img
                    src="../../asset/images/get-on-google.png"
                    class="get-app-img"
                    alt="download app"
                  />
                </router-link>
              </div>
              <div class="col-auto">
                <img :src="item.img" class="step-img" alt="" />
              </div>
              <div class="col-auto">
                <div class="step-title">
                  {{ item.stepTitle }}
                </div>
                <div class="step-text">
                  {{ item.stepText }}
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- If we need navigation buttons -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
      </div>
    </div>
    <div class="question-row">
      <title-dots class="title-dots" :reverse="true">
        <div class="title">常見問題</div>
      </title-dots>
      <div class="questions">
        <div class="item" v-for="(item, index) in questions" :key="index">
          <div class="question no-gutters" @click="item.active = !item.active">
            <div class="col-auto">Q{{ index + 1 }}：</div>
            <div class="col">{{ item.question }}</div>
            <img
              class="chevron col-auto"
              :class="{ active: item.active }"
              src="/images/alesis-chevron-down.svg"
            />
          </div>
          <div class="answer no-gutters" v-show="item.active">
            <div class="col-auto">A{{ index + 1 }}：</div>
            <div class="col" v-html="item.answer"></div>
          </div>
        </div>
      </div>
    </div>
    <float />
  </div>
</template>

<script>
import titleDots from '../../component/titleDots.vue'
import float from "../../component/floatComponent";
import 'swiper/swiper.scss';
import "swiper/components/navigation/navigation.min.css"
import SwiperCore, {
  Navigation
} from 'swiper/core';
export default {
  components: {
    titleDots,
    float,
  },
  mounted() {
    SwiperCore.use([Navigation])
    const swiper = new Swiper('.swiper', {
      // Navigation arrows

      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    })
    $('title').text('普匯 微企e秒貸');
  },
  data() {
    return {
      headerText: [
        '中小企業都適合',
        '100萬信保融資，超低利率'
      ],
      features: [
        {
          title: '',
          text: ['普匯無人化APP申請', 'AI全線上化提供資料', '一支手機即可完成'],
          img: require('../../asset/images/sme-f1.png')
        },
        {
          title: '',
          text: ['免費快速媒合銀行資金', '審核流程快速', '兩天即免費幫您取得額度'],
          img: require('../../asset/images/sme-f2.png')
        },
        {
          title: '',
          text: ['銀行撥款、信保擔保', '移送政府信保基金保證', '協助中小企業加強擔保力'],
          img: require('../../asset/images/sme-f3.png')
        },
      ],
      applyQualify: [
        '具有公司登記或商業登記。',
        '不得為金融及保險業、宗教、職業及類似組織、特殊娛樂業。',
        '實收資本額不得超過一億。',
        '近一年平均投保員工人數不得大於200人。',
        '企業、負責人、配偶、保證人不得有任何信用瑕疵紀錄。'
      ],
      applyRequire: [
        '申請額度：100萬。',
        '借款期間：1~3年。',
        '還款方式：每月平均攤還本息。',
        '利 費 率：3.8%起。',
        '費率：普匯平台不收取任何費用，免費服務︒'
      ],
      questions: [
        {
          question: '額度申請上限？',
          answer: '微企e秒貸可申請的額度固定為新台幣100萬。',
          active: false,
        },
        {
          question: '什麼是信保？',
          answer: `信保是由政府及銀行共同捐助成立之公益財團法人，目的在透過信用保證，分擔銀行辦理中小企業貸款之信用風險。`,
          active: false,
        },
        {
          question: '如果信保額度已滿還可以再申請嗎？',
          answer: `若信保額度已滿，則無法再申請微企e秒貸產品，不過還是可以在平台申請一般中小企業融資喔。`,
          active: false,
        },
        {
          question: '申請會收取手續費嗎?',
          answer: '在普匯平台上申請皆是「免費用」喔。',
          active: false,
        },
        {
          question: '銀行員打電話來，後續條件如何洽談？',
          answer: '您透過普匯APP申請的【微企e秒貸】案件進入銀行端後，銀行會派專員與您聯繫進行對保動作，利率與實際核准額度將由銀行端與您接洽，在普匯APP上也會同步顯示出來。',
          active: false,
        },
      ],
      steps: [
        {
          img: require('../../asset/images/business-step1.png'),
          stepTitle: 'Step 1. 簡單下載註冊',
          stepText: '下載APP完成法人註冊\n申請⌜微企e秒貸⌟'

        },
        {
          img: require('../../asset/images/business-step2.png'),
          stepTitle: 'Step 2. 完成線上認證',
          stepText: '完成負責人/配偶/保證人\n公司企業資料驗證'
        },
        {
          img: require('../../asset/images/business-step3.png'),
          stepTitle: 'Step 3. 銀行對保撥款',
          stepText: '系統審核後\n立即對保撥款'
        },
      ]
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../../component/alesis/alesis";
@keyframes bouncing {
  from {
    transform: scale(0.8);
  }
  to {
    transform: scale(1.15);
  }
}

.business-loan {
  line-height: 1.25;
  .phone-show {
    display: none;
  }
  .phone-none {
    display: block;
  }
  .header {
    position: relative;
    height: 500px;
    min-width: 1450px;
    background-image: url("../../asset/images/sme-header.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center top;
    .header-col {
      padding-left: 36px;
      background-repeat: no-repeat;
    }
    .header-title {
      margin-top: 52px;
      text-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
      font-size: 40px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      letter-spacing: 2px;
      text-align: left;
      color: #f2e627;
    }
    .header-text {
      margin: 12px 0 24px;
      text-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
      font-size: 24px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 2;
      letter-spacing: 0.9px;
      text-align: left;
      color: #fff;
    }
    .get-start {
      display: grid;
      grid-template-columns: 180px 180px;
      gap: 15px;
      .btn-img {
        width: 180px;
      }
    }
  }
  .feature-title {
    text-align: center;
    padding: 30px;
    background: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
    background-clip: text;
    color: rgba(255, 255, 255, 0);
    font-size: 36px;
    line-height: 1.2;
  }
  .features-row {
    padding: 30px 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 34px;
    .feature {
      &.e-loan {
        transform: scale(1.05);
        .icon {
          animation: bouncing 0.5s infinite alternate;
        }
      }
      width: 320px;
      padding: 20px 5px;
      .icon {
        margin: 0 auto 10px;
        text-align: center;
        border-radius: 50%;
        padding: 25px;
      }
      .icon-img{
          width: 110px;
      }
      .feature-title {
        margin-bottom: 10px;
        font-size: 30px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        letter-spacing: 1.8px;
        text-align: center;
        color: #1e69aa;
      }
      .feature-text {
        font-size: 20px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        color: #8f8f8f;
      }
    }
  }
  .suitable-row {
    margin: -20px auto;
    background-color: #f5f6f8;
    .content-row {
      width: fit-content;
      margin: 62px auto 0;
      padding-bottom: 80px;
    }
    .suit-item {
      margin-right: 76px;
      &.line {
        display: flex;
        margin-right: 30px;
        height: 209px;
        width: 335px;
      }

      .suit-title {
        font-size: 32px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.28;
        letter-spacing: 1.8px;
        text-align: left;
        color: #1e69aa;
      }
      .suit-sub-title {
        height: 24px;
        margin-bottom: 8px;
        font-size: 20px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.8;
        letter-spacing: 1px;
        text-align: left;
        color: #1e69aa;
      }
      .suit-text {
        font-size: 24px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.5;
        letter-spacing: 1.2px;
        text-align: left;
        color: #8f8f8f;
      }
    }
    .line-col {
      padding-top: 20px;
      .line-group {
        display: flex;
        align-items: center;
        flex-direction: column;
        margin-right: 20px;
        .circle {
          width: 8px;
          height: 8px;
          border-radius: 50%;
          background-color: #1e69aa;
        }
        .line {
          margin: 8px 0;
          height: 185px;
          border-left: 2px solid #1ea7e7;
        }
      }
    }
    .suit-img {
      width: 300px;
    }
  }
  .apply-row {
    .apply-intro {
      max-width: 900px;
      margin: 62px auto;
      .apply-title {
        font-size: 52px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        letter-spacing: 2.6px;
        text-align: left;
        color: #1e69aa;
      }
      .apply-text {
        padding-left: 17px;
        font-size: 32px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        text-align: left;
        color: #8f8f8f;
      }
    }
    .apply-features {
      position: relative;
      width: fit-content;
      margin: 100px auto 0;
      display: grid;
      grid-template-columns: 420px 420px;
      grid-template-rows: 1fr 1fr;
      gap: 0 130px;
      .feature-order {
        position: absolute;
        display: block;
        z-index: 1;
        font-family: Arial;
        font-size: 180px;
        font-weight: 900;
        color: #dcdcdc;
        left: -70px;
        top: 0;
        &.order-2 {
          left: 475px;
          top: 55px;
          color: #1e69aa;
        }
        &.order-3 {
          top: 305px;
          left: -70px;
        }
        &.order-4 {
          left: 475px;
          top: 355px;
          color: #1e69aa;
        }
        &.item-2-img {
          left: 678px;
          top: -240px;
        }
      }
      .feature-item {
        &.item-1,
        &.item-3 {
          margin-bottom: 50px;
        }
        &.item-2,
        &.item-4 {
          margin-top: 50px;
          border: solid 2px #1e69aa;
        }
        position: relative;
        z-index: 2;
        padding: 30px 30px 20px 30px;
        border-radius: 20px;
        border: solid 2px #8f8f8f;
        background-color: #fff;
        .feature-title {
          font-size: 32px;
          font-weight: 500;
          font-stretch: normal;
          font-style: normal;
          text-align: left;
          color: #1e69aa;
        }
        .feature-text {
          font-size: 20px;
          font-weight: normal;
          font-stretch: normal;
          font-style: normal;
          line-height: 1.5;
          text-align: left;
          color: #8f8f8f;
        }
      }
    }

    .apply-qualify {
      max-width: 900px;
      margin: 50px auto 20px;
      .qualify-title {
        font-size: 30px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        text-align: left;
        color: #1e69aa;
      }
      .qualify-text {
        margin: 9px 0 25px 0;
        font-size: 20px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.5;
        text-align: left;
        color: #8f8f8f;
      }
      .qualify-line {
        width: 149px;
        border-bottom: 2px solid #dcdcdc;
      }
    }
    .apply-require {
      max-width: 900px;
      margin: 20px auto 20px;
      .require-title {
        font-size: 30px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.5;
        text-align: left;
        color: #1e69aa;
      }
      .require-text {
        margin: 9px 0 25px 0;
        font-size: 20px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        text-align: left;
        color: #8f8f8f;
      }
      .require-line {
        width: 149px;
        border-bottom: 2px solid #dcdcdc;
      }
    }
    .apply-process {
      width: fit-content;
      margin: 20px auto 60px;
      padding-bottom: 100px;
      .process-title {
        margin-left: 62px;
        margin-bottom: 25px;
        font-size: 30px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        text-align: left;
        color: #1e69aa;
      }
      .apply-processes {
        display: grid;
        grid-template-columns: 300px 300px 300px;
        grid-template-rows: 1fr 1fr;
        grid-template-areas:
          "p1 p2 p3"
          "p6 p5 p4";
        gap: 62px 62px;
        .process-item {
          position: relative;
          width: 300px;
          height: auto;
          padding: 15px 10px;
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          border-radius: 20px;
          border: solid 2px #1e69aa;
          background-color: #fff;
          font-size: 24px;
          font-weight: normal;
          font-stretch: normal;
          font-style: normal;
          line-height: 1.5;
          color: #5d5555;
          .coop-title{
            //   font-size: 18px;
            font-weight: bold;
          }
          .coop-link{
              color: #1e69aa;
              text-decoration: underline;
          }
          &.item-1 {
            grid-area: p1;
          }
          &.item-2 {
            grid-area: p2;
          }
          &.item-3 {
            grid-area: p3;
          }
          &.item-4 {
            grid-area: p4;
          }
          &.item-5 {
            grid-area: p5;
          }
          &.item-6 {
            grid-area: p6;
          }
          ul.item-highlight {
            color: #1e69aa;
            padding-left: 1.5em;
            list-style: decimal;
            font-size: 0.75em;
          }
        }
      }
      .circle {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #1e69aa;
      }
      .line {
        width: 32px;
        border-bottom: 2px solid #1ea7e7;
      }
      .item-1-line {
        display: flex;
        align-items: center;
        position: absolute;
        right: -41px;
      }
      .item-2-line {
        display: flex;
        align-items: center;
        position: absolute;
        right: -41px;
      }
      .item-3-line {
        display: flex;
        align-items: center;
        position: absolute;
        transform: rotate(90deg);
        bottom: -26px;
      }
      .item-4-line {
        display: flex;
        align-items: center;
        position: absolute;
        transform: rotate(180deg);
        left: -42px;
      }
      .item-5-line {
        display: flex;
        align-items: center;
        position: absolute;
        transform: rotate(180deg);
        left: -40px;
      }
    }
  }
  .apply-data-row {
    background-color: #f5f6f8;
    .apply-data-item-row {
      display: flex;
      justify-content: center;
      gap: 55px;
      padding: 60px 0 90px;
      margin: -20px 0;
      .apply-img-cover {
        padding-top: 150px;
      }
      .apply-title {
        margin-bottom: 22px;
        font-size: 30px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.28;
        letter-spacing: 1.8px;
        text-align: center;
        color: #1e69aa;
      }
      .data-item {
        padding: 14px 0;
        border-radius: 16px;
        border: solid 2px #dcdcdc;
        margin-bottom: 20px;
        width: 284px;
        font-size: 20px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        text-align: center;
        color: #5d5555;
      }
    }
  }
  .step-row {
    background-color: #f5f6f8;
    margin: -15px 0;
    .swiper {
      width: 1220px;
      height: 710px;
    }
    .step-content {
      display: flex;
      align-items: center;
      padding: 60px;
      font-size: 20px;
      font-weight: 500;
      font-stretch: normal;
      font-style: normal;
      text-align: left;
      color: #5d5555;
      white-space: pre-line;
      .step-title {
        font-size: 26px;
        color: #112e53;
      }
      .step-img {
        max-height: 578px;
      }
    }
  }
  .question-row {
    background-color: #fff;
    position: relative;
    .questions {
      padding-bottom: 30px;
      margin: 0 auto;
      max-width: 905px;
      .item {
        margin: 30px;
        .question {
          display: flex;
          border: 1px solid #036eb7;
          border-radius: 12px;
          padding: 0.5rem 1.5rem;
          font-size: 24px;
          font-weight: normal;
          font-stretch: normal;
          font-style: normal;
          text-align: left;
          color: #036eb7;
          .chevron {
            width: 20px;
            transform: rotate(180deg);
            &.active {
              transform: none;
            }
          }
        }
        .answer {
          display: flex;
          margin-top: 32px;
          word-break: break-all;
          border: 1px solid #5d5555;
          padding: 0.5rem 1.5rem 1rem;
          font-size: 1.2rem;
          color: #5d5555;
          border-radius: 12px;
          font-size: 24px;
          font-weight: normal;
          font-stretch: normal;
          font-style: normal;
          text-align: left;
        }
      }
    }
  }
}
@media screen and (max-width: 1900px) {
  .business-loan .header {
    height: 550px;
  }
}
@media screen and (max-width: 767px) {
  .business-loan {
    .phone-show {
      display: block;
    }
    .phone-none {
      display: none;
    }
    .header {
      min-width: 0;
      height: 675px;
      background-image: url("../../asset/images/sme-header-phone.jpg");
      .header-col {
        margin: 12px 15px;
        padding-left: 12px;
      }
      .header-title {
        font-size: 24px;
        margin: 0;
      }
      .header-text {
        margin: 5px 0 5px;
        font-size: 14px;
      }
      .get-start{
          grid-template-columns: 150px 150px;
          .btn-img{
              width: 150px;
          }
      }
    }
    .features-row {
      padding: 40px 0;
      .feature {
        max-width: 100%;
        width: 320px;
        padding: 20px 5px;
        .icon {
          margin: 0 auto 10px;
          padding: 17px;
        }
        .icon-img {
          width: 110px;
        }
        .feature-title {
          font-size: 28px;
        }
        .feature-text {
          font-size: 18px;
        }
      }
    }
    .suitable-row {
      margin: -15px auto;
      .content-row {
        margin: 40px auto 0;
        padding-bottom: 40px;
      }
      .suit-item {
        margin-right: 0;
        &.line {
          margin-right: 0px;
          height: 180px;
          width: 200px;
        }
        .suit-title {
          font-size: 26px;
        }
        .suit-sub-title {
          height: initial;
          margin-bottom: 8px;
          font-size: 18px;
        }
        .suit-text {
          font-size: 18px;
        }
      }
      .line-col {
        padding-top: 20px;
        .line-group {
          margin-right: 15px;
          .circle {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #1e69aa;
          }
          .line {
            margin: 8px 0;
            height: 150px;
            border-left: 2px solid #1ea7e7;
          }
        }
      }
      .suit-cover {
        padding-top: 35px;
      }
      .suit-img {
        width: 150px;
      }
    }
    .apply-row {
      .apply-intro {
        margin: 40px auto;
        .apply-title {
          margin: 0 auto;
          font-size: 32px;
          text-align: center;
        }
        .apply-text {
          .apply-icon {
            height: 22px;
            width: 22px;
          }
          padding-left: 0;
          font-size: 22px;
          text-align: center;
        }
      }
      .apply-features {
        margin: 200px auto 40px;
        padding-left: 55px;
        display: grid;
        grid-template-columns: 300px;
        grid-template-rows: 1fr 1fr 1fr 1fr;
        .feature-order {
          font-size: 145px;
          left: -3px;
          top: 0;
          &.order-2 {
            left: -5px;
            top: 225px;
          }
          &.order-3 {
            top: 445px;
            left: -5px;
          }
          &.order-4 {
            left: -5px;
            top: 675px;
          }
          &.item-2-img {
            left: 125px;
            top: -200px;
            height: 225px;
          }
        }
        .feature-item {
          &.item-1,
          &.item-2,
          &.item-3 {
            margin-bottom: 20px;
          }
          &.item-2,
          &.item-4 {
            margin-top: 0;
            border: solid 2px #1e69aa;
          }
          position: relative;
          z-index: 2;
          padding: 20px 15px;
          .feature-title {
            font-size: 26px;
            font-weight: 500;
            font-stretch: normal;
            font-style: normal;
            text-align: left;
            color: #1e69aa;
          }
          .feature-text {
            font-size: 16px;
            font-weight: normal;
            font-stretch: normal;
            font-style: normal;
            line-height: 1.5;
            text-align: left;
            color: #8f8f8f;
          }
        }
      }

      .apply-qualify {
        max-width: 900px;
        padding: 0 15px;
        margin: 50px auto 20px;
        .qualify-title {
          font-size: 26px;
        }
        .qualify-text {
          margin: 9px 0 20px 0;
          font-size: 16px;
          padding-inline: 25px;
        }
        .qualify-line {
          width: 125px;
        }
      }
      .apply-require {
        max-width: 900px;
        padding: 0 15px;
        margin: 20px auto 20px;
        .require-title {
          font-size: 26px;
        }
        .require-text {
          margin: 9px 0 20px 0;
          font-size: 16px;
          padding-inline: 25px;
        }
        .require-line {
          width: 125px;
        }
      }
      .apply-process {
        margin: 20px 15px 60px 15px;
        padding-bottom: 0;
        .process-title {
          margin-left: 0;
          margin-bottom: 10px;
          font-size: 26px;
        }
        .apply-processes {
          display: grid;
          grid-template-columns: 160px 160px;
          grid-template-rows: 1fr 1fr 1fr;
          grid-template-areas:
            "p1 p2"
            "p4 p3"
            "p5 p6";
          gap: 20px 20px;
          .process-item {
            position: relative;
            width: 160px;
            font-size: 14px;
          }
        }
        .circle {
          width: 6px;
          height: 6px;
        }
        .line {
          width: 10px;
          border-bottom: 2px solid #1ea7e7;
        }
        .item-1-line {
          right: -17px;
        }
        .item-2-line {
          right: 70px;
          bottom: -13px;
          transform: rotate(90deg);
        }
        .item-3-line {
          transform: rotate(180deg);
          bottom: 80px;
          left: -17px;
        }
        .item-4-line {
          transform: rotate(90deg);
          right: 70px;
          left: initial;
          bottom: -12px;
        }
        .item-5-line {
          transform: none;
          left: initial;
          right: -17px;
        }
      }
    }
    .apply-data-row {
      background-color: #f5f6f8;
      .col-text {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-template-areas: "title title";
        gap: 12px;
      }
      .apply-data-item-row {
        flex-wrap: wrap;
        padding: 40px 0 40px;
        margin: -20px 0;
        .apply-img-cover {
          padding-top: 0px;
        }
        .apply-title {
          grid-area: title;
          margin-bottom: 22px;
          font-size: 26px;
        }
        .data-item {
          padding: 14px 0;
          margin-bottom: 0;
          width: 150px;
          font-size: 14px;
        }
      }
    }
    .step-row {
      .swiper {
        padding: 15px 0;
        width: 100%;
        height: 620px;
      }
      .step-content {
        flex-direction: column-reverse;
        padding: 0px;
        font-size: 16px;
        .step-title {
          font-size: 24px;
          color: #112e53;
        }
        .step-img {
          margin: 20px 0;
          max-height: 378px;
        }
        .get-app {
          display: flex;
          gap: 20px;
          .get-app-img {
            width: 140px;
          }
        }
      }
    }
    .question-row {
      .questions {
        padding-bottom: 15px;
        .item {
          margin: 20px;
          .question {
            font-size: 16px;
            padding: 0.5rem 1rem;
            .chevron {
              width: 20px;
              transform: rotate(180deg);
              &.active {
                transform: none;
              }
            }
          }
          .answer {
            display: flex;
            margin-top: 20px;
            font-size: 14px;
          }
        }
      }
    }
  }
}
</style>
