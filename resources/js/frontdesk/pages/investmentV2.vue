<template>
  <div class="investment">
    <div class="banner">
      <h1 class="banner-title">普匯債權投資</h1>
      <h2 class="banner-title">P2P認購債權 公開透明安全</h2>
      <img
        src="@/asset/images/investment/investment-banner.png"
        class="w-100 d-md-block d-none"
      />
      <img
        src="@/asset/images/investment/investment-banner-phone.png"
        class="w-100 d-md-none d-block"
      />
      <a class="btn btn-banner" target="_blank" href="/investLink">
        立即投資 <i class="fa fa-chevron-right"></i>
      </a>
    </div>
    <div class="slogan">
      <div class="block-content">
        <div class="slogan-title">
          成功媒合投資金額已達
          <span class="em">{{ format(tweenTotal) }}</span>
          億！
        </div>
        <div class="slogan-text d-sm-block d-none">
          <div>已有超過千萬位投資人365天24小時隨時隨地利用普匯AI智能投資</div>
          <div>不斷創造被動收入，享受投資理財樂趣與生活</div>
        </div>
        <div class="slogan-text d-sm-none d-block">
          <div>已有超過千萬位投資人365天24小時</div>
          <div>隨時隨地利用普匯AI智能投資，</div>
          <div>不斷創造被動收入，享受投資</div>
          <div>理財樂趣與生活</div>
        </div>
      </div>
    </div>
    <div class="product">
      <div class="block-content">
        <div>
          <h1 class="h1">產品種類</h1>
          <h2 class="h2">
            <div>科技理財：投資不求人</div>
            <div>理專、業務也讚嘆</div>
          </h2>
        </div>
        <div class="product-row" data-aos="fade-left" data-aos-duration="800">
          <div>
            <div class="product-row-title">1. 債權投資</div>
            <div class="product-row-intro">
              <div>
                P2P金融科技媒合，不受營業時間影響，隨時下單小額、分散，低風險、高報酬
              </div>
            </div>
            <div class="product-row-item">
              <div>
                <div class="product-row-item-title">開始使用：</div>
                <div class="product-row-sub-text">
                  下載普匯投資人App，瀏覽「債權投資」分頁，選擇欲投資的債權並下標。操作容易，快速上手。全程沒有任何推銷話術，不干擾您的決策。
                </div>
              </div>
            </div>
            <div class="product-row-item">
              <div>
                <div class="product-row-item-title">投資心法：</div>
                <div class="product-row-sub-text">
                  隨著各借款人條件，每個債權標的的利率也有所不同。您可依照自身投資能力與習慣，自由選擇欲投資的債權；分散投資標的，享受穩定收益。
                </div>
              </div>
            </div>
            <div class="product-row-item">
              <div>
                <div class="product-row-item-title">風險管理：</div>
                <div class="product-row-sub-text">
                  普匯金融科技透過嚴格的徵信審核流程，竭盡所能降低您的投資風險，幫助您創造資產的更高價值。
                </div>
              </div>
            </div>
          </div>
          <div>
            <img
              src="@/asset/images/investment/product-row-p1.png"
              class="img-fluid"
            />
          </div>
        </div>
        <div class="product-row" data-aos="fade-right" data-aos-duration="800">
          <div>
            <img
              src="@/asset/images/investment/product-row-p2.png"
              class="img-fluid"
            />
          </div>
          <div>
            <div class="product-row-title">2. 債權轉讓</div>
            <div class="product-row-intro">
              <div>
                隨時轉讓手中債權，提供其他投資人認購，立即變現
                追求財富最大收益，妥善調度資金
              </div>
            </div>
            <div class="product-row-sub-title">
              您有提早拿回投資金額的需求？
            </div>
            <div class="product-row-sub-text">
              別擔心，普匯金融科技提供「債權轉讓」功能，所有的投資人，皆可
              將手中持有的債權上架至平台，提供其他投資人認購。讓您在獲利的
              路上，擁有更多的機會與選擇。
            </div>
            <div class="mt-3 d-md-block d-flex justify-content-center">
              <router-link class="product-link" to="/transfer">
                查看更多
              </router-link>
            </div>
          </div>
        </div>
        <div
          class="product-row"
          data-aos="fade-left"
          data-aos-duration="800"
          id="ai"
        >
          <div>
            <div class="product-row-title">3. AI智能投資</div>
            <div class="product-row-intro">
              <div>設定您的喜好類別，配對標的</div>
              <div>分秒必爭的新時代，掌握投資先機</div>
            </div>
            <div class="product-row-sub-title">
              <div>
                優良債權太熱門，您還在無時不刻的盯盤嗎？現在有更好的選擇！
              </div>
              <div class="my-3">
                <div>
                  使用普匯金融科技開發的AI智能投資功能，讓您依照自身喜好，
                </div>
                <div>量身打造最符合您需求的投資條件。</div>
              </div>
              <div>
                只要動動手指，即可掌握投資先機，不必擔心優良債權一上架就被秒殺！
              </div>
            </div>
          </div>
          <div>
            <img
              src="@/asset/images/investment/product-row-p3.png"
              class="img-fluid"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="compare">
      <div class="block-content">
        <div>
          <h1 class="h1">投資工具比一比</h1>
          <h2 class="h2">深入分析、權衡風險，掌握理財訣竅</h2>
        </div>
        <div class="compare-table">
          <investmentTable />
        </div>
      </div>
    </div>
    <div class="process">
      <div class="block-content">
        <h1 class="h1">投資流程</h1>
        <h2 class="h2">
          <div>您的一小筆錢，可能幫他圓夢</div>
          <div>更可能救了他的人生</div>
        </h2>
        <div class="step-main">
          <div class="step-content-1">
            <div class="step-content-title">步驟一</div>
            <div class="step-content-info">
              <div>進入我的資產</div>
            </div>
            <div class="step-content-img">
              <img
                class="img-fluid"
                src="@/asset/images/index/invest-step-1.png"
                alt=""
              />
            </div>
          </div>
          <div class="step-content-2">
            <div class="step-content-title">步驟二</div>
            <div class="step-content-info">
              <div>選擇單筆/打包出售</div>
            </div>
            <div class="step-content-img">
              <img
                class="img-fluid"
                src="@/asset/images/index/invest-step-2.png"
                alt=""
              />
            </div>
          </div>
          <div class="step-content-3">
            <div class="step-content-title">步驟三</div>
            <div class="step-content-info">
              <div>調整出讓金額，並點選出讓</div>
              <div>詳閱轉讓協議書</div>
            </div>
            <div class="step-content-img">
              <img
                class="img-fluid"
                src="@/asset/images/index/invest-step-3.png"
                alt=""
              />
            </div>
          </div>
          <div class="step-content-4">
            <div class="step-content-title">步驟四</div>
            <div class="step-content-info">
              <div>上架成功，等待媒合</div>
            </div>
            <div class="step-content-img">
              <img
                class="img-fluid"
                src="@/asset/images/index/invest-step-4.png"
                alt=""
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="cases">
      <div class="block-content">
        <h1 class="h1">現有案件</h1>
        <h2 class="h2">優良債權任君挑選，現在就開始您的投資！</h2>
        <div class="case-title">債權投資專區</div>
        <div class="swiper">
          <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide d-md-flex d-none">
              <alesis-project v-bind="goodCase[0]"></alesis-project>
              <alesis-project v-bind="goodCase[1]"></alesis-project>
              <alesis-project v-bind="goodCase[2]"></alesis-project>
            </div>
            <div
              class="swiper-slide d-md-flex d-none"
              v-if="goodCase.length > 3"
            >
              <alesis-project v-bind="goodCase[3]"></alesis-project>
              <alesis-project v-bind="goodCase[4]"></alesis-project>
              <alesis-project v-bind="goodCase[5]"></alesis-project>
            </div>
            <div
              class="swiper-slide d-md-flex d-none"
              v-if="goodCase.length > 6"
            >
              <alesis-project v-bind="goodCase[6]"></alesis-project>
              <alesis-project v-bind="goodCase[7]"></alesis-project>
              <alesis-project v-bind="goodCase[8]"></alesis-project>
            </div>
            <!-- phone -->
            <div
              class="swiper-slide d-md-none d-block"
              v-for="item in goodCase"
            >
              <alesis-project v-bind="item" />
            </div>
          </div>
          <div class="swiper-pagination"></div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
        <div class="case-title">債權轉讓專區</div>
        <div class="swiper">
          <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide d-md-flex d-none">
              <transferCase v-bind="transferCase[0]" />
              <transferCase v-bind="transferCase[1]" />
              <transferCase v-bind="transferCase[2]" />
            </div>
            <div
              class="swiper-slide d-md-flex d-none"
              v-if="transferCase.length > 3"
            >
              <transferCase v-bind="transferCase[3]" />
              <transferCase v-bind="transferCase[4]" />
              <transferCase v-bind="transferCase[5]" />
            </div>
            <div
              class="swiper-slide d-md-flex d-none"
              v-if="transferCase.length > 6"
            >
              <transferCase v-bind="transferCase[6]" />
              <transferCase v-bind="transferCase[7]" />
              <transferCase v-bind="transferCase[8]" />
            </div>
            <!-- phone -->
            <div
              class="swiper-slide d-md-none d-block"
              v-for="item in transferCase"
            >
              <transferCase v-bind="item" />
            </div>
          </div>
          <div class="swiper-pagination"></div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </div>
    </div>
    <div class="download">
      <div class="block-content">
        <h1 class="h1">
          <div class="d-md-block d-none">下載投資人App，享優越體驗</div>
          <div class="d-md-none d-block">
            <div>下載投資人App</div>
            <div>享優越體驗</div>
          </div>
        </h1>
        <div class="list">
          <div class="list-item">•取得完整債權資訊</div>
          <div class="list-item">•操作投資債權功能</div>
          <div class="list-item">•操作轉讓債權功能</div>
          <div class="list-item">•可設定AI智能投資</div>
        </div>
        <div class="download-group">
          <h2 class="h2">立即加入</h2>
          <div class="d-flex justify-content-center">
            <a href="/investLink" target="_blank" class="col-5">
              <img class="img-fluid" src="@/asset/images/get-on-apple.png" />
            </a>
            <a href="/investLink" target="_blank" class="col-5">
              <img src="@/asset/images/get-on-google.png" class="img-fluid" />
            </a>
          </div>
        </div>
        <div class="d-md-none d-block">
          <img
            src="@/asset/images/investment/investment-download-bg-phone.png"
            class="img-fluid"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import investmentTable from '@/component/investmentTable'
import AlesisProject from '../component/alesis/AlesisProject'
import TransferCase from '../component/TransferCase.vue'
import 'swiper/swiper.scss'
import 'swiper/components/navigation/navigation.min.css'
import 'swiper/components/pagination/pagination.min.css'
import SwiperCore, { Navigation, Pagination } from 'swiper/core'
import { gsap } from 'gsap/dist/gsap'
export default {
  components: {
    investmentTable,
    AlesisProject,
    TransferCase
  },
  data() {
    return {
      goodCase: [],
      transferCase: [],
      total: 0,
      tweenTotal: 0
    }
  },
  mounted() {
    SwiperCore.use([Navigation, Pagination])
    AOS.init({ disable: 'mobile' })
    const swiper = new Swiper('.swiper', {
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      pagination: {
        el: '.swiper-pagination',
      },
    })
    axios.post(`/getCase`, { status: 10, product_id: 3 }).then((res) => {
      this.goodCase = res.data.slice(0, 8)
    }).catch((error) => {
      console.error('getCase 發生錯誤，請稍後再試');
    });
    axios.post(`/getTransferCase`).then((res) => {
      this.transferCase = res.data.slice(0, 8)
    }).catch((error) => {
      console.error('getCase 發生錯誤，請稍後再試');
    });
    this.total = 5.4
    if (this.$route.hash === '#ai') {
      setTimeout(() => {
        document.querySelector('#ai').scrollIntoView({ behavior: 'smooth', block: 'end' })
      }, 1000)
    }
  },
  methods: {
    format(x) {
      return x.toFixed(1)
    }
  },
  watch: {
    total(newValue) {
      gsap.to(this.$data, {
        duration: 3, tweenTotal: newValue
      });
    },
  },
}
</script>

<style lang="scss" scoped>
$color--primary: #036eb7;
$color__text--primary: #023d64;
$color__background--primary: #f3f9fc;
.block-content {
  width: 100%;
  max-width: 1400px;
  margin: 0 auto;
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
.investment {
  .banner {
    position: relative;
    .banner-title {
      position: absolute;
      color: transparent;
    }
    .btn-banner {
      position: absolute;
      top: 63.5%;
      left: 12.5vw;
      border-radius: 12px;
      padding: 10px;
      color: #fff;
      background: #083a6e;
      box-shadow: 4px 4px 2px rgba(0, 0, 0, 0.1);
      width: 250px;
      font-size: 28px;
    }
  }
  .slogan {
    .block-content {
      background-color: #f2f5fd;
      background-image: url('~images/investment/investment-slogan-bg.png');
      padding: 40px 30px 25px;
      .slogan-title {
        font-style: normal;
        font-weight: 700;
        font-size: 52px;
        line-height: 1.6;
        text-align: center;
        letter-spacing: 0.14em;
        color: transparent;
        background: linear-gradient(90deg, #036eb7 60%, #6ab0d5 100%);
        background-clip: text;
        .em {
          font-size: 120px;
        }
      }
      .slogan-text {
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 23px;
        text-align: center;
        letter-spacing: 7px;
        color: #393939;
      }
    }
  }
  .product {
    padding: 100px 0;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 24px;
    }
    .product-row {
      &:nth-child(odd) {
        grid-template-columns: 11fr 12fr;
      }
      align-items: center;
      display: grid;
      grid-template-columns: 9fr 8fr;
      gap: 64px;
      margin: 50px 0;
      &-title {
        font-style: normal;
        font-weight: 700;
        font-size: 48px;
        line-height: 1.4;
        color: $color--primary;
        margin-bottom: 12px;
      }
      &-intro {
        font-style: normal;
        font-weight: 500;
        font-size: 30px;
        line-height: 1.4;
        color: #036eb7;
        opacity: 0.64;
        margin-bottom: 32px;
      }
      &-item {
        display: flex;
        &::before {
          display: block;
          content: '';
          background-image: url('~images/investment/Vector65.svg');
          background-position: center;
          background-repeat: no-repeat;
          margin-right: 15px;
          flex: 0 0 56px;
          width: 56px;
          height: 56px;
          position: relative;
          top: -4px;
          border-radius: 50%;
          border: 2px solid #036fb7;
        }
        &-title {
          font-style: normal;
          font-weight: 700;
          font-size: 32px;
          line-height: 1.4;
          color: $color__text--primary;
          margin-bottom: 12px;
        }
      }
      &-sub-title {
        font-style: normal;
        font-weight: 700;
        font-size: 24px;
        line-height: 1.4;
        color: $color__text--primary;
        margin-bottom: 12px;
      }
      &-sub-text {
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 1.4;
        color: $color__text--primary;
        opacity: 0.56;
        margin-bottom: 30px;
      }
    }
    &-link {
      padding: 10px 64px;
      font-style: normal;
      font-weight: 500;
      font-size: 28px;
      line-height: 1.4;
      color: $color--primary;
      border: 2px solid $color--primary;
      border-radius: 10px;
    }
  }
  .compare {
    padding: 50px 0 120px;
    background-color: $color__background--primary;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 24px;
    }
    &-table {
      margin-top: 84px;
    }
  }
  .process {
    padding: 64px 0;
    background-image: url('~images/investment/investment-process-bg.png');
    background-repeat: no-repeat;
    background-position: center;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 24px;
    }
    .step-content-1 {
      width: 300px;
      padding: 0 15px 40px 40px;
      border-left: 5px solid $color--primary;
      &:hover,
      &:focus-within {
        .step-content-title {
          color: $color__text--primary;
        }
        .step-content-info {
          opacity: 1;
        }
      }
      .step-content-title::before {
        content: '1';
        background: $color--primary;
      }
      .step-content-img {
        opacity: 1;
        z-index: 3;
      }
    }
    .step-content-2 {
      width: 300px;
      padding: 0 15px 40px 40px;
      border-left: 5px solid $color--primary;
      .step-content-title::before {
        content: '2';
        background: $color--primary;
      }
      &:hover,
      &:focus-within {
        .step-content-title {
          color: $color__text--primary;
        }
        .step-content-info {
          opacity: 1;
        }
        .step-content-img {
          z-index: 10;
          opacity: 1;
          transition-property: opacity;
          transition-duration: 1s;
        }
      }
    }
    .step-content-3 {
      width: 300px;
      padding: 0 15px 25px 40px;
      border-left: 5px solid $color--primary;
      .step-content-title::before {
        content: '3';
        background: $color--primary;
      }
      &:hover,
      &:focus-within {
        .step-content-title {
          color: $color__text--primary;
        }
        .step-content-info {
          opacity: 1;
        }
        .step-content-img {
          z-index: 10;
          opacity: 1;
          transition-property: opacity;
          transition-duration: 1s;
        }
      }
    }
    .step-content-4 {
      width: 300px;
      padding: 0 15px 10px 40px;
      .step-content-title::before {
        content: '4';
        background: $color--primary;
      }
      &:hover,
      &:focus-within {
        .step-content-title {
          color: $color__text--primary;
        }
        .step-content-info {
          opacity: 1;
        }
        .step-content-img {
          z-index: 10;
          opacity: 1;
          transition-property: opacity;
          transition-duration: 1s;
        }
      }
    }
    .step-main {
      margin: 45px auto;
      width: 650px;
      position: relative;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
    }
    .step-content-title {
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      color: #036fb7;
      &::before {
        background: #036fb7;
        border-radius: 50%;
        color: #fff;
        font-size: 36px;
        font-style: normal;
        font-weight: 500;
        position: absolute;
        right: 269px;
        text-align: center;
        width: 60px;
        height: 60px;
      }
    }
    .step-content-info {
      font-weight: 400;
      font-size: 20px;
      line-height: 23px;
      color: #023d64;
      opacity: 0.56;
    }
    .step-content-img {
      top: 0;
      left: 15px;
      position: absolute;
      transition-property: opacity;
      transition-duration: 1s;
      opacity: 0;
    }
  }
  .cases {
    padding: 64px 0;
    background: $color__background--primary;
    .h1 {
      color: $color--primary;
    }
    .h2 {
      color: $color--primary;
      opacity: 0.64;
      margin-top: 24px;
    }
    .case-title {
      margin-left: 40px;
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      line-height: 1.4;
      color: $color__text--primary;
    }
    .swiper-slide {
      display: flex;
      justify-content: space-evenly;
      padding: 35px 45px;
      transform: scale(0.95);
    }
  }
  .download {
    padding: 64px 0;
    background-image: url('~images/investment/investment-download-bg.png');
    background-position: center;
    background-repeat: no-repeat;
    .h1 {
      color: $color--primary;
      text-align: left;
    }
    .h2 {
      color: $color__text--primary;
      text-align: center;
      font-size: 40px;
      margin-bottom: 24px;
    }
    .list {
      width: 300px;
      gap: 12px;
      margin: 16px 0;
      padding-bottom: 24px;
      border-bottom: 6px solid #dce9ed;
      font-style: normal;
      font-weight: 500;
      font-size: 32px;
      line-height: 46px;
      color: $color--primary;
      opacity: 0.64;
    }
    &-group {
      width: 380px;
      margin: 24px 0;
    }
  }
}
@media screen and (max-width: 767px) {
  .block-content {
    padding: 0 15px;
  }
  .h1 {
    font-size: 28px;
  }
  .h2 {
    font-size: 18px;
  }
  .investment {
    .banner {
      .btn-banner {
        position: absolute;
        top: 40%;
        left: 33.4vw;
        padding: 6px;
        width: 130px;
        font-size: 14px;
      }
    }
    .slogan {
      .block-content {
        background-position: center center;
        padding: 36px 15px;
        .slogan-title {
          font-size: 28px;
          .em {
            font-size: 76px;
          }
        }
        .slogan-text {
          font-size: 12px;
          line-height: 17px;
        }
      }
    }
    .product {
      padding: 64px 0;
      .h2 {
        margin-top: 8px;
      }
      .product-row {
        &:nth-child(odd) {
          flex-direction: column-reverse;
        }
        align-items: center;
        display: flex;
        flex-direction: column;
        margin: 36px 0;
        &-title {
          font-size: 24px;
          margin-bottom: 8px;
        }
        &-intro {
          font-size: 16px;
          margin-bottom: 15px;
        }
        &-item {
          display: flex;
          &::before {
            content: '';
            background-size: 8px;
            margin-right: 7px;
            flex: 0 0 32px;
            width: 28px;
            height: 28px;
            top: 0;
          }
          &-title {
            font-size: 20px;
            margin-bottom: 8px;
          }
        }
        &-sub-title {
          font-size: 20px;
          margin-bottom: 8px;
        }
        &-sub-text {
          font-size: 12px;
          margin-bottom: 8px;
        }
      }
      &-link {
        padding: 10px 40px;
        font-size: 16px;
      }
    }
    .compare {
      padding: 48px 0;
      .h2 {
        margin-top: 8px;
      }
      &-table {
        margin-top: 36px;
      }
    }
    .process {
      padding: 36px 0;
      .h2 {
        margin-top: 8px;
      }
      .step-content-1 {
        width: 155px;
        padding: 0 15px 15px 20px;
        border-left: 5px solid $color--primary;
        &:hover,
        &:focus-within {
          .step-content-title {
            color: $color__text--primary;
          }
          .step-content-info {
            opacity: 1;
          }
        }
        .step-content-title::before {
          content: '1';
          background: $color--primary;
        }
        .step-content-img {
          opacity: 1;
          z-index: 3;
        }
      }
      .step-content-2 {
        width: 155px;
        padding: 0 15px 15px 20px;
        border-left: 5px solid $color--primary;
        .step-content-title::before {
          content: '2';
          background: $color--primary;
        }
        &:hover,
        &:focus-within {
          .step-content-title {
            color: $color__text--primary;
          }
          .step-content-info {
            opacity: 1;
          }
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }
      .step-content-3 {
        width: 155px;
        padding: 0 15px 15px 20px;
        border-left: 5px solid $color--primary;
        .step-content-title::before {
          content: '3';
          background: $color--primary;
        }
        &:hover,
        &:focus-within {
          .step-content-title {
            color: $color__text--primary;
          }
          .step-content-info {
            opacity: 1;
          }
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }
      .step-content-4 {
        width: 155px;
        padding: 0 15px 15px 20px;
        .step-content-title::before {
          content: '4';
          background: $color--primary;
        }
        &:hover,
        &:focus-within {
          .step-content-title {
            color: $color__text--primary;
          }
          .step-content-info {
            opacity: 1;
          }
          .step-content-img {
            z-index: 10;
            opacity: 1;
            transition-property: opacity;
            transition-duration: 1s;
          }
        }
      }
      .step-main {
        margin: 45px auto;
        width: 100%;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
      }
      .step-content-title {
        font-style: normal;
        font-weight: 700;
        font-size: 18px;
        color: #036fb7;
        &::before {
          font-size: 14px;
          line-height: 24px;
          position: absolute;
          right: 140px;
          width: 24px;
          height: 24px;
        }
      }
      .step-content-info {
        font-size: 12px;
        line-height: 1.4;
      }
      .step-content-img {
        top: -120px;
        left: -35px;
        transform: scale(0.55);
        position: absolute;
        transition-property: opacity;
        transition-duration: 1s;
        opacity: 0;
      }
    }
    .cases {
      padding: 36px 0;
      .h2 {
        margin-top: 8px;
      }
      .case-title {
        margin-left: 0;
        text-align: center;
        font-size: 20px;
      }
      .swiper-slide {
        padding: 0;
        transform: scale(0.65);
        right: 15px;
      }
      .swiper-button-next,
      .swiper-button-prev {
        transform: scale(0.65);
      }
    }
    .download {
      padding: 64px 0;
      background-image: none;
      .h1 {
        color: $color--primary;
        text-align: center;
      }
      .h2 {
        color: $color__text--primary;
        text-align: center;
        font-size: 20px;
        margin-bottom: 16px;
      }
      .list {
        width: 165px;
        gap: 12px;
        margin: 16px auto;
        font-size: 18px;
        line-height: 1.6;
      }
      &-group {
        width: 380px;
        margin: 24px 0;
      }
    }
  }
}
</style>
