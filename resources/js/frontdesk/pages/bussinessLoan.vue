<template>
  <div class="bussiness-loan">
    <div class="header row">
      <div class="header-col col">
        <div class="header-title">普匯金融 • 普惠金融</div>
        <div class="header-text">
          <div>• 企業主/創業夢想</div>
          <div>• 手機全線上無人化方便簡單快速安全</div>
          <div>• 全台第一政府支持信保保證銀行放款</div>
          <div>• 溫馨扶持背後金援</div>
        </div>
        <div class="header-slogan">
          <div class="yellow">FREE</div>
          <div class="cyan">免費資金媒合</div>
        </div>
        <div class="header-underline"></div>
        <div class="slogan-text">
          金融科技
          <img src="../asset/images/coin.png" class="apply-icon" /> 銀行資金
          <img src="../asset/images/coin.png" class="apply-icon" /> 信保保證
        </div>
        <div class="get-app">
          <router-link to="/borrowLink" target="_blank">
            <img
              class="get-apple-img"
              src="../asset/images/get-on-apple.png"
              alt=""
            />
          </router-link>
          <router-link to="/borrowLink" target="_blank">
            <img src="../asset/images/get-on-google.png" alt="" />
          </router-link>
        </div>
      </div>
    </div>
    <div class="features-row">
      <div class="feature">
        <div class="icon">
          <img src="../asset/images/loan-f1.png" class="icon-img" />
        </div>
        <div class="feature-title">信保融資專案</div>
        <div class="feature-text">
          <div>符合信保資格</div>
          <div>滿足不同資金額度需求</div>
          <div>普匯微企APP申請</div>
          <div>銀行快速額貸</div>
        </div>
      </div>
      <div class="feature">
        <div class="icon">
          <img src="../asset/images/loan-f2.png" class="icon-img" />
        </div>
        <div class="feature-title">微企e秒貸</div>
        <div class="feature-text">
          <div>中小微企業都適合</div>
          <div>100萬信保融資，超低利率</div>
          <div>普匯無人化APP申請、免費媒合</div>
          <div>銀行撥款、信保擔保</div>
        </div>
      </div>
      <div class="feature">
        <div class="icon">
          <img src="../asset/images/loan-f3.png" class="icon-img" />
        </div>
        <div class="feature-title">一般企業融資</div>
        <div class="feature-text">
          <div>中小企業融資好幫手</div>
          <div>只要有統編，資格就符合</div>
          <div>VIP專人服務</div>
          <div>多方資金媒合</div>
        </div>
      </div>
      <div class=""></div>
    </div>
    <div class="suitable-row">
      <title-dots>
        <div class="title">適用對象</div>
      </title-dots>
      <div class="content-row row">
        <div class="col-auto">
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
        <div class="col-auto line-col">
          <div class="line-group">
            <div class="circle"></div>
            <div class="line"></div>
          </div>
          <div class="line-group">
            <div class="circle"></div>
            <div class="line"></div>
          </div>
          <div class="line-group">
            <div class="circle"></div>
          </div>
        </div>
        <div class="col-auto">
          <div class="suit-item line">
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
          <div class="suit-item line">
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
          <div class="suit-item line">
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
        </div>
        <div class="col-auto suit-col">
          <div class="suit-cover item-1">
            <img src="../asset/images/suit-1.png" class="suit-img" />
          </div>

          <div class="suit-cover item-2">
            <img src="../asset/images/suit-2.png" class="suit-img" />
          </div>

          <div class="suit-cover item-3">
            <img src="../asset/images/suit-3.png" class="suit-img" />
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="apply-row">
        <title-dots :reverse="true">
          <div class="title">不一樣的企業貸款</div>
        </title-dots>
        <div class="apply-intro">
          <div class="apply-title">全台唯一 免費資金媒合</div>
          <div class="apply-text">
            金融科技
            <img src="../asset/images/coin.png" class="apply-icon" /> 銀行資金
            <img src="../asset/images/coin.png" class="apply-icon" /> 信保保證
          </div>
        </div>
        <div class="apply-features">
          <div class="feature-order order-1">1</div>
          <div class="feature-order order-2">2</div>
          <div class="feature-order order-3">3</div>
          <div class="feature-order order-4">4</div>
          <img
            class="feature-order item-2-img"
            src="../asset/images/boss.png"
            alt=""
          />
          <div class="feature-item item-1">
            <div class="feature-title">獲得主管機關支持同意</div>
            <div class="feature-text">
              <div>台灣唯一：全線上、無人化</div>
              <div>⌜中小微企業全線上信保融資平台⌟</div>
            </div>
          </div>
          <div class="feature-item item-2">
            <div class="feature-title">普匯金融AI科技</div>
            <div class="feature-text">
              <div>中小微企業APP全線上申貸</div>
              <div>AI智能風控系統 自動徵審核貸</div>
            </div>
          </div>
          <div class="feature-item item-3">
            <div class="feature-title">銀行提供資金</div>
            <div class="feature-text">
              <div>普匯平台API資料線上直串銀行</div>
              <div>銀行即時同步系統審核</div>
              <div>快速核決授信額度條件</div>
            </div>
          </div>
          <div class="feature-item item-4">
            <div class="feature-title">信保基金擔保保證</div>
            <div class="feature-text">
              <div>政府信保提供保證</div>
              <div>快速媒合加速取得資金</div>
            </div>
          </div>
        </div>
        <div class="">
          <div class="apply-qualify">
            <div class="qualify-title">申請資格</div>
            <div class="qualify-text">
              <div>•具有公司登記或商業登記執照。</div>
              <div>•不得為金融及保險業、宗教、職業及類似組織、特殊娛樂業。</div>
              <div>•實收資本額不得超過1億。</div>
              <div>•近一年平均投保員工人數不得大於200人。</div>
              <div>•企業、負責人、配偶、保證人不得有任何信用瑕疵紀錄。</div>
            </div>
            <div class="qualify-line"></div>
          </div>
          <div class="apply-require">
            <div class="require-title">申請條件</div>
            <div class="require-text">
              <div>•申請額度：不限。</div>
              <div>•借款期間：1~3年。</div>
              <div>•還款方式：每月平均攤還本息。</div>
              <div>•利 費 率：依各產品及資金方最終核准條件為準。</div>
              <div>•費率：普匯平台不收取任何費用，免費服務︒</div>
            </div>
            <div class="require-line"></div>
          </div>
          <div class="apply-process">
            <div class="process-title">申請流程</div>
            <div class="apply-processes">
              <div class="process-item">
                <div>下載</div>
                <div>⌜普匯inFlux⌟APP</div>
                <div class="item-1-line">
                  <div class="line"></div>
                  <div class="circle"></div>
                </div>
              </div>
              <div class="process-item">
                <div>申請</div>
                <div>⌜公司行號/微企e秒貸⌟</div>
                <div class="item-2-line">
                  <div class="line"></div>
                  <div class="circle"></div>
                </div>
              </div>
              <div class="process-item">
                <div>公司行號註冊</div>
                <div>公司行號登入</div>
                <div class="item-3-line">
                  <div class="line"></div>
                  <div class="circle"></div>
                </div>
              </div>
              <div class="process-item item-4">
                <div>完成資料提供</div>
                <div>
                  <div class="item-highlight">1.負責人/配偶/保證人</div>
                  <div class="item-highlight">2.公司企業</div>
                </div>
                <div class="item-4-line">
                  <div class="line"></div>
                  <div class="circle"></div>
                </div>
              </div>
              <div class="process-item">
                <div>等待系統審核</div>
                <div>媒合資金方</div>
                <div class="item-5-line">
                  <div class="line"></div>
                  <div class="circle"></div>
                </div>
              </div>
              <div class="process-item">
                <div>對保簽約後</div>
                <div>立即撥款</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div>
      <div class="apply-data-row">
        <title-dots>
          <div class="title">申貸資料明細一覽</div>
        </title-dots>
        <div class="apply-data-item-row no-gutters">
          <div class="col-auto">
            <div class="apply-title">
              <div>公司行號</div>
              <div>應備資料</div>
            </div>
            <div class="data-item">公司變更事項登記表</div>
            <div class="data-item">公司電子信箱</div>
            <div class="data-item">法人聯合徵信</div>
            <div class="data-item">損益表</div>
            <div class="data-item">員工投保人數資料</div>
            <div class="data-item">金流證明</div>
            <div class="data-item">公司資料表</div>
            <div class="data-item">法人對保</div>
          </div>
          <div class="col-auto apply-img-cover">
            <img
              src="../asset/images/bussiness-loan-apply.png"
              alt="申請明細"
            />
          </div>
          <div class="col-auto">
            <div class="apply-title">
              <div>申請人、保證人</div>
              <div>應備資料</div>
            </div>
            <div class="data-item">實名認證</div>
            <div class="data-item">聯合徵信報告+A11</div>
            <div class="data-item">財務訊息</div>
            <div class="data-item">工作資料</div>
            <div class="data-item">個人資料表</div>
            <div class="data-item">常用電子信箱</div>
          </div>
        </div>
      </div>
    </div>
    <div class="step-row">
      <title-dots :reverse="true">
        <div class="title">三步驟•普匯微企e秒貸</div>
      </title-dots>
      <div class="swiper">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">
          <!-- Slides -->
          <div class="swiper-slide" v-for="(item, index) in steps" :key="index">
            <div class="step-content">
              <div class="col-auto">
                <router-link to="/borrowLink" target="_blank">
                  <img
                    class="get-apple-img"
                    src="../asset/images/get-on-apple.png"
                    alt=""
                  />
                </router-link>
                <router-link to="/borrowLink" target="_blank">
                  <img src="../asset/images/get-on-google.png" alt="" />
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
      <title-dots class="title-dots">
        <div class="title">常見問題</div>
      </title-dots>
      <div class="questions">
        <div class="item" v-for="(item, index) in questions" :key="index">
          <div class="question" @click="item.active = !item.active">
            <div>{{ item.question }}</div>
            <img
              class="chevron"
              :class="{ active: item.active }"
              src="/images/alesis-chevron-down.svg"
            />
          </div>
          <div class="answer" v-show="item.active">
            <div class="">A{{ index + 1 }}：</div>
            <div class="">{{ item.answer }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import titleDots from '../component/titleDots.vue'
import 'swiper/swiper.scss';
import "swiper/components/navigation/navigation.min.css"
import SwiperCore, {
  Navigation
} from 'swiper/core';
export default {
  components: {
    titleDots,
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
  },
  data() {
    return {
      questions: [
        {
          question: 'Q1：聯徵如何申請',
          answer: `您可以使用自然人憑證申請的聯徵電子檔（限 PDF 檔案），或親自至
         聯徵中心、郵局申請正本資料，並郵寄至普匯（台北市中山區松江路
         111 號 11 樓之 1）。`,
          active: false,
        },
        {
          question: 'Q2：工作資料有哪些？',
          answer: `我不知道`,
          active: false,
        },
        {
          question: 'Q3：財務訊息是哪些？',
          answer: `我不知道`,
          active: false,
        },
        {
          question: 'Q4：實名認證拍攝照片無法轉正',
          answer: `我不知道`,
          active: false,
        }
      ],
      steps: [
        {
          img: require('../asset/images/business-step1.png'),
          stepTitle: 'Step 1. 簡單下載註冊',
          stepText: '下載APP完成法人註冊\n申請⌜微型企業線上融資⌟'
        }
      ]
    }
  }
}
</script>

<style lang="scss" scoped>
.bussiness-loan {
  line-height: 1.25;
  .header {
    position: relative;
    height: 550px;
    background-image: url("../asset/images/bussiness-loan-header-2.png");
    background-position: 100%;
    background-size: calc(100% - 500px);
    background-repeat: no-repeat;
    .header-col {
      padding-left: 36px;
      background-image: url("../asset/images/bussiness-loan-header-1.png");
      background-repeat: no-repeat;
    }
    .header-title {
      margin-top: 52px;
      text-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
      font-family: NotoSansTC;
      font-size: 40px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      letter-spacing: 2px;
      text-align: left;
      color: #fff;
    }
    .header-text {
      margin: 8px 0 20px;
      text-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
      font-family: NotoSansTC;
      font-size: 18px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 2;
      letter-spacing: 0.9px;
      text-align: left;
      color: #fff;
    }
    .header-slogan {
      display: flex;
      align-items: flex-end;
      .yellow {
        font-family: ArialNarrow;
        font-size: 63px;
        font-weight: bold;
        font-stretch: normal;
        font-style: italic;
        line-height: 63px;
        letter-spacing: 3.15px;
        text-align: left;
        color: #f2e627;
      }
      .cyan {
        font-family: ArialNarrow;
        font-size: 49px;
        font-weight: bold;
        font-stretch: normal;
        font-style: italic;
        line-height: 63px;
        letter-spacing: 2.45px;
        text-align: left;
        color: #80dde8;
      }
    }
    .header-underline {
      max-width: 435px;
      width: 100%;
      margin: 11px 5px;
      border-bottom: solid 2px #fff;
    }
    .slogan-text {
      font-family: NotoSansTC;
      font-size: 28px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 37px;
      letter-spacing: 1.4px;
      text-align: left;
      color: #fff;
      margin-bottom: 28px;
    }
    .get-apple-img {
      margin-right: 26px;
    }
  }
  .features-row {
    padding: 80px 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 34px;
    .feature {
      width: 350px;
      padding: 20px 5px;
      border-radius: 20px;
      border: solid 1px #8f8f8f;
      .icon {
        margin: 0 auto 10px;
        width: 110px;
        text-align: center;
        border-radius: 50%;
        padding: 25px;
        background-image: linear-gradient(to right, #0d367a, #1e549e);
      }
      .feature-title {
        margin-bottom: 10px;
        font-family: NotoSansTC;
        font-size: 36px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        letter-spacing: 1.8px;
        text-align: center;
        color: #1e69aa;
      }
      .feature-text {
        font-family: NotoSansTC;
        font-size: 24px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        text-align: center;
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
      }

      .suit-title {
        font-family: NotoSansTC;
        font-size: 36px;
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
        font-family: NotoSansTC;
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
        font-family: NotoSansTC;
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
    .suit-col {
      .suit-cover.item-2 {
        margin: 22px 0;
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
        font-family: NotoSansTC;
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
        font-family: NotoSansTC;
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
      margin: 100px auto 50px;
      display: grid;
      grid-template-columns: 444px 444px;
      grid-template-rows: 1fr 1fr;
      gap: 0 130px;
      .feature-order {
        position: absolute;
        display: block;
        z-index: 1;
        font-family: Arial;
        font-size: 200px;
        font-weight: 900;
        color: #dcdcdc;
        left: -73px;
        top: -15px;
        &.order-2 {
          left: 485px;
          top: 20px;
          color: #1e69aa;
        }
        &.order-3 {
          top: 250px;
          left: -77px;
        }
        &.order-4 {
          left: 485px;
          top: 305px;
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
          font-family: NotoSansTC;
          font-size: 36px;
          font-weight: 500;
          font-stretch: normal;
          font-style: normal;
          text-align: left;
          color: #1e69aa;
        }
        .feature-text {
          font-family: NotoSansTC;
          font-size: 24px;
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
        font-family: NotoSansTC;
        font-size: 36px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        text-align: left;
        color: #1e69aa;
      }
      .qualify-text {
        margin: 9px 0 25px 0;
        font-family: NotoSansTC;
        font-size: 24px;
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
        font-family: NotoSansTC;
        font-size: 36px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.5;
        text-align: left;
        color: #1e69aa;
      }
      .require-text {
        margin: 9px 0 25px 0;
        font-family: NotoSansTC;
        font-size: 24px;
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
        font-family: NotoSansTC;
        font-size: 36px;
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
        gap: 62px 62px;
        .process-item {
          position: relative;
          width: 300px;
          height: 140px;
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          border-radius: 20px;
          border: solid 2px #1e69aa;
          background-color: #fff;
          font-family: NotoSansTC;
          font-size: 24px;
          font-weight: normal;
          font-stretch: normal;
          font-style: normal;
          line-height: 1.5;
          color: #5d5555;
          .item-highlight {
            color: #1e69aa;
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
        right: -64px;
      }
      .item-5-line {
        display: flex;
        align-items: center;
        position: absolute;
        transform: rotate(180deg);
        right: -64px;
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
        font-family: NotoSansTC;
        font-size: 36px;
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
        width: 264px;
        font-family: NotoSansTC;
        font-size: 24px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        text-align: center;
        color: #5d5555;
      }
    }
  }
  .step-row {
    .swiper {
      width: 1220px;
      height: 710px;
    }
    .step-content {
      display: flex;
      align-items: center;
      padding: 60px;
      font-family: NotoSansTC;
      font-size: 24.9px;
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
    }
  }
  .question-row {
    background-color: #f5f6f8;
    position: relative;
    .questions {
      padding-bottom: 30px;
      margin: 0 auto;
      max-width: 900px;
      .item {
        margin: 30px;
        .question {
          display: flex;
          justify-content: space-between;
          border: 1px solid #036eb7;
          border-radius: 12px;
          padding: 0.5rem 1.5rem;
          font-family: NotoSansTC;
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
          border: 1px solid #5d5555;
          padding: 0.5rem 1.5rem 1rem;
          font-size: 1.2rem;
          color: #5d5555;
          border-radius: 12px;
          font-family: NotoSansTC;
          font-size: 24px;
          font-weight: normal;
          font-stretch: normal;
          font-style: normal;
          line-height: 1.3;
          letter-spacing: 1.2px;
          text-align: left;
        }
      }
    }
  }
}
</style>
