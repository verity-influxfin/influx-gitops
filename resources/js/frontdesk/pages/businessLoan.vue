<template>
  <div class="business-loan">
    <div class="header row">
      <div class="header-col col">
        <div class="header-title">企業金融•普匯微企e秒貸</div>
        <ul class="header-text">
          <li>企業主/創業夢想</li>
          <li>手機全線上無人化方便簡單快速安全</li>
          <li>全台第一政府支持信保保證銀行放款</li>
          <li>溫馨扶持背後金援</li>
        </ul>
        <div class="header-slogan">
          <div class="yellow">FREE</div>
          <div class="cyan">免費資金媒合</div>
        </div>
        <div class="header-underline"></div>
        <div class="slogan-text">
          <img src="../asset/images/coin.png" class="apply-icon" /> 金融科技
          <img src="../asset/images/coin.png" class="apply-icon" /> 銀行資金
          <img src="../asset/images/coin.png" class="apply-icon" /> 媒合借貸
        </div>
        <div class="get-app">
          <div class="phone-none">
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
          <div class="phone-show">
            <router-link to="/borrowLink" target="_blank">
              <img
                class="get-apple-img"
                src="/images/ios_download_link_mobile.svg"
                alt="download app"
              />
            </router-link>
            <router-link to="/borrowLink" target="_blank">
              <img
                src="/images/android_download_link_mobile.svg"
                class="get-android-img"
                alt="download app"
              />
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <div class="features-row">
      <div class="feature d-none d-sm-block">
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
      <div class="feature e-loan">
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
      <div class="feature d-block d-sm-none">
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
              <img src="../asset/images/suit-1.png" class="suit-img" />
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
              <img src="../asset/images/suit-2.png" class="suit-img" />
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
              <img src="../asset/images/suit-3.png" class="suit-img" />
            </div>
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
            <div class="feature-title">獲得銀行肯定、政策支持</div>
            <div class="feature-text">
              <div>台灣第一，全線上無人化</div>
              <div>中小微企業線上審核融資平台</div>
            </div>
          </div>
          <div class="feature-item item-2">
            <div class="feature-title">AI全線上無人審核</div>
            <div class="feature-text">
              <div>全程無人化手機APP申請</div>
              <div>AI人工智慧風控審核</div>
            </div>
          </div>
          <div class="feature-item item-3">
            <div class="feature-title">銀行資金</div>
            <div class="feature-text">
              <div>資料API直串銀行系統</div>
              <div>銀行即時同步系統審核</div>
              <div>核准條件同步普匯APP</div>
              <div>銀行簽約對保放款</div>
            </div>
          </div>
          <div class="feature-item item-4">
            <div class="feature-title">信保基金保證</div>
            <div class="feature-text">
              <div>移送政府信保基金保證</div>
              <div>協助中小企業加強擔保力</div>
            </div>
          </div>
        </div>
        <div class="">
          <div class="apply-qualify">
            <div class="qualify-title">申請資格</div>
            <ul class="qualify-text">
              <li>具有公司登記或商業登記執照。</li>
              <li>不得為金融及保險業、宗教、職業及類似組織、特殊娛樂業。</li>
              <li>實收資本額不得超過1億。</li>
              <li>近一年平均投保員工人數不得大於200人。</li>
              <li>企業、負責人、配偶、保證人不得有任何信用瑕疵紀錄。</li>
            </ul>
            <div class="qualify-line"></div>
          </div>
          <div class="apply-require">
            <div class="require-title">申請條件</div>
            <ul class="require-text">
              <li>申請額度：不限。</li>
              <li>借款期間：1~3年。</li>
              <li>還款方式：每月平均攤還本息。</li>
              <li>利 費 率：3.8%起。</li>
              <li>費率：普匯平台不收取任何費用，免費服務︒</li>
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
              </div>
              <div class="process-item item-5">
                <div>等待系統審核</div>
                <div>媒合資金方</div>
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
    <div>
      <div class="apply-data-row">
        <title-dots>
          <div class="title">申貸資料明細一覽</div>
        </title-dots>
        <div class="apply-data-item-row no-gutters">
          <div class="col-auto col-text">
            <div class="apply-title">
              <div>公司行號</div>
              <div>應備資料</div>
            </div>
            <div class="data-item">公司變更事項登記表</div>
            <div class="data-item">公司授權核實</div>
            <div class="data-item">近6個月封面及內頁公司存摺</div>
            <div class="data-item">員工投保人數資料</div>
            <div class="data-item">近三年損益表</div>
            <div class="data-item">公司聯合徵信</div>
            <div class="data-item">公司電子信箱</div>
          </div>
          <div class="col-auto apply-img-cover">
            <img src="../asset/images/business-loan-apply.png" alt="申請明細" />
          </div>
          <div class="col-auto col-text">
            <div class="apply-title">
              <div>申請人、保證人</div>
              <div>應備資料</div>
            </div>
            <div class="data-item">實名認證</div>
            <div class="data-item">聯合徵信報告+A11</div>
            <div class="data-item">財務收支</div>
            <div class="data-item">工作資料</div>
            <div class="data-item">個人基本資料</div>
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
              <div class="col-auto get-app">
                <router-link to="/borrowLink" target="_blank">
                  <img
                    class="get-app-img"
                    src="../asset/images/get-on-apple.png"
                    alt="download app"
                  />
                </router-link>
                <router-link to="/borrowLink" target="_blank">
                  <img
                    src="../asset/images/get-on-google.png"
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
      <title-dots class="title-dots">
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
import titleDots from '../component/titleDots.vue'
import float from "../component/floatComponent";
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
    $('title').text('普匯中小微企業線上審核融資平台 台灣第一全線上無人化');
  },
  data() {
    return {
      questions: [
        {
          question: '實名認證拍攝照片無法轉正？',
          answer: '您好！非常抱歉造成您的困擾！因系統採取全自動化辨識，而有角度上微微的調整 發現辨識上錯誤的話，須再麻煩您重新拍照上傳～謝謝您！如還有多次未能成功再麻煩聯絡我們客服，我們會儘速與您聯繫。',
          active: false,
        },
        {
          question: '工作資料有哪些？',
          answer: `您好！目前的工作資料需提供的是『勞保異動明細』、『勞保異動明細』申請方式可至「各縣市的勞動部申請」或「自然人憑證線上申請」，申請完後的紙本資料做拍照上傳驗證的動作即可審核。`,
          active: false,
        },
        {
          question: '財務訊息是哪些？',
          answer: `您好！目前的<span style="color:red">財務訊息</span>需提供的是「近六個月往來存摺封面及內頁」幫我拍照上傳即可！`,
          active: false,
        },
        {
          question: '聯徵如何申請？',
          answer: '您好！以下有兩種不同身份聯徵的申請方式，請至：<a href="https://www.jcic.org.tw/main_ch/docDetail.aspx?uid=525&pid=93&docid=364" target="_blank">https://www.jcic.org.tw/main_ch/docDetail.aspx?uid=525&pid=93&docid=364</a>「個人聯徵申請」需攜帶雙證件親自至聯徵中心、或至郵局申請正本資料，並郵寄至普匯（台北市中山區松江路 111 號 11 樓之 1）「企業聯徵申請」需由負責人親自至聯徵中心辦理或負責人委託他人至聯合徵信中心服務櫃檯辦理。郵局也有指定格式可以申請，並郵寄至普匯（台北市中山區松江路 111 號 11 樓之 1）。',
          active: false,
        },
        {
          question: '系統會通知我的保證人進行驗證嗎？',
          answer: '您好！我們會根據您填寫的保證人資料，以<span style="color:red">簡訊及Email</span>方式寄送通知，因此在填寫保證人相關資料時，請幫我確認資料是否完整正確，謝謝您！',
          active: false,
        },
        {
          question: '保證人如果反悔不願擔任，該怎麼處理？',
          answer: `您好！請先聯絡普匯客服人員提出更改，系統會先將您的案子退件，以便您重新修改，再進行申請。`,
          active: false,
        },
        {
          question: '申請人，保證人資料都驗證完成，接下來該如何處理？',
          answer: '您好！接下來會將您的案件資訊轉由銀行做最後核決，只需等待審核通過即可！',
          active: false,
        },
        {
          question: '銀行員打電話來，後續條件如何洽談？',
          answer: '您透過普匯APP申請的【微企e秒貸】案件進入銀行端後，銀行會派專員與您聯繫進行對保動作，利率與實際核准額度將由銀行端與您接洽，在普匯APP上也會同步顯示出來。',
          active: false,
        },
        {
          question: '利率與核貸金額核定後，還能調整嗎？',
          answer: '由銀行端核定的額度與利率即為本次申請的合約內容 您可選擇是否需要動用本次條件的申貸，至於內容能否調整，可與銀行端與您接洽的窗口洽詢。',
          active: false,
        },
        {
          question: '一般企業融資沒還款會怎樣？',
          answer: '因貸款方為銀行，若您忘記還款，請立即與放款銀行聯繫，處理後續逾期事宜，要逾期即會造成企業、負責人的信用瑕疵，為避免上述情況發生，請務必依約定準時還款。',
          active: false,
        },
        {
          question: '這次申請過金額不夠用，還能再申請一筆這種普匯企業貸款嗎？',
          answer: '可以的，除了固定申請一百萬額度的【微企e秒貸】，也可以在普匯申請信保專案、一般企業融資專案，解決您運營上的資金問題。',
          active: false,
        }
      ],
      steps: [
        {
          img: require('../asset/images/business-step1.png'),
          stepTitle: 'Step 1. 簡單下載註冊',
          stepText: '下載APP完成法人註冊\n申請⌜微型企業線上融資⌟'

        },
        {
          img: require('../asset/images/business-step2.png'),
          stepTitle: 'Step 2. 完成線上認證',
          stepText: '完成負責人/配偶/保證人\n公司企業資料驗證'
        },
        {
          img: require('../asset/images/business-step3.png'),
          stepTitle: 'Step 3. 新光對保撥款',
          stepText: '銀行專人審核\n立即對保撥款'
        },
      ]
    }
  }
}
</script>

<style lang="scss" scoped>
@import "../component/alesis/alesis";
@keyframes bouncing {
  from {
    transform:scale(.8);
  }
  to {
    transform:scale(1.15);
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
    height: 550px;
    background-image: url("../asset/images/business-loan-header-all.jpg");
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
      color: #fff;
    }
    .header-text {
      margin: 8px 0 20px;
      text-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
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
        font-size: 55px;
        font-weight: bold;
        font-stretch: normal;
        font-style: italic;
        line-height: 55px;
        letter-spacing: 3.15px;
        text-align: left;
        color: #f2e627;
      }
      .cyan {
        font-size: 44px;
        font-weight: bold;
        font-stretch: normal;
        font-style: italic;
        line-height: 55px;
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
      .apply-icon {
        width: 30px;
        height: 30px;
      }
      font-size: 24px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 30px;
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
      &.e-loan {
        transform: scale(1.05);
        .icon {
          animation: bouncing .5s infinite alternate;
        }
      }
      width: 320px;
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
        top: -15px;
        &.order-2 {
          left: 470px;
          top: 20px;
          color: #1e69aa;
        }
        &.order-3 {
          top: 215px;
          left: -70px;
        }
        &.order-4 {
          left: 470px;
          top: 260px;
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
            font-size: .75em;
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
        width: 264px;
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
    .swiper {
      width: 1220px;
      height: 710px;
    }
    .step-content {
      display: flex;
      align-items: center;
      padding: 60px;
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
      .step-img {
        max-height: 578px;
      }
    }
  }
  .question-row {
    background-color: #f5f6f8;
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
@media screen and (max-width: 767px) {
  .business-loan {
    .phone-show {
      display: block;
    }
    .phone-none {
      display: none;
    }
    .header {
      height: 675px;
      background-image: url("../asset/images/business-header-phone.png");
      .header-col {
        margin: 12px 15px;
        padding-left: 12px;
      }
      .header-title {
        font-size: 22px;
        margin: 0;
      }
      .header-text {
        margin: 5px 0 5px;
        font-size: 12px;
        padding-inline: 25px;
      }
      .header-slogan {
        display: flex;
        align-items: flex-end;
        .yellow {
          font-size: 22px;
          line-height: 26px;
          color: #f2e627;
        }
        .cyan {
          font-size: 26px;
          line-height: 26px;
        }
      }
      .header-underline {
        max-width: 230px;
        width: 100%;
        margin: 8px 0;
        border-bottom: solid 2px #fff;
      }
      .slogan-text {
        .apply-icon {
          width: 16px;
          height: 16px;
        }
        font-size: 14px;
        line-height: 16px;
        margin-bottom: 20px;
      }
      .get-app {
        position: absolute;
        right: 12px;
        top: 130px;
      }
      .get-apple-img {
        margin-right: 15px;
        height: 44px;
      }
      .get-android-img {
        height: 44px;
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
          width: 70px;
          height: 70px;
          padding: 17px;
          background-image: linear-gradient(to right, #0d367a, #1e549e);
        }
        .icon-img {
          height: 40px;
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
          top: -15px;
          &.order-2 {
            left: -5px;
            top: 155px;
          }
          &.order-3 {
            top: 320px;
            left: -5px;
          }
          &.order-4 {
            left: -5px;
            top: 499px;
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
          bottom: 45px;
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
