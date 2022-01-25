<template>
  <div>
    <!-- 標頭 -->
    <alesis-loan-header
      :header-img="require('@/asset/images/3S-title.webp')"
      header-img-class="img-title"
      content="學生貸款史上最低利3%~5%<br>最高額度18萬，點先繳息不還本<br>最高額度 15 萬、最低利率 5%<br>鼓勵名校同學創業、投資、自我成長"
      :image="require('@/asset/images/3S-banner.webp')"
      :image-phone="require('@/asset/images/3S-banner-phone.webp')"
    ></alesis-loan-header>
    <!-- 標頭 -->
    <div class="intro">
      <alesis-section>
        <alesis-space size="small"></alesis-space>
        <div class="intro-title">普匯貸你度過資金難關、勇敢追夢</div>
        <div class="intro-title-underline"></div>
        <div class="intro-list">
          <div class="intro-list-item">日常開銷：學雜費、生活費、住宿費</div>
          <div class="intro-list-item">
            自我成長：自學進修、補習考研、留學交換
          </div>
          <div class="intro-list-item">
            人生夢想：投資理財、財務自由、車庫創業
          </div>
          <div class="intro-list-item">必備工具：手機、3C筆電、摩托車</div>
        </div>
        <div class="intro-list-underline"></div>
        <div class="points-grid">
          <div class="point-item">
            <img src="~images/3S-simple.png" class="point-img" alt="" />
            <div>簡單便利，</div>
            <div>一支手機即可申辦</div>
          </div>
          <div class="point-item">
            <img src="~images/3S-speed.png" class="point-img" alt="" />
            <div>AI自動審核，</div>
            <div>365天快速放款</div>
          </div>
          <div class="point-item">
            <img src="~images/3S-safety.png" class="point-img" alt="" />
            <div>申貸全程隱私，</div>
            <div>無人照會</div>
          </div>
        </div>
        <alesis-space size="small"></alesis-space>
      </alesis-section>
    </div>

    <!-- 適用對象 -->
    <div class="適用對象">
      <alesis-space size="medium"></alesis-space>
      <alesis-header>
        <div class="標題">適用對象</div>
        <div class="點"></div>
        <div class="點"></div>
        <div class="suit-info">
          <div>
            滿20歲且符合申貸資格之名校在校生，準備雙證件、學生證、金融卡
          </div>
          <div>只需手機APP拍照上傳即可申請</div>
        </div>
      </alesis-header>
      <alesis-section :secondary="true">
        <alesis-space size="large"></alesis-space>
        <div class="項目列表">
          <div class="項目">
            <img class="圖示" src="/images/alesis-symbol-student.svg" />
            <div class="標籤">學生身分</div>
          </div>
          <div class="項目">
            <img class="圖示" src="/images/alesis-symbol-id.svg" />
            <div class="標籤">雙證件</div>
          </div>
          <div class="項目">
            <img class="圖示" src="/images/alesis-symbol-creditcard.svg" />
            <div class="標籤">金融帳號</div>
          </div>
          <div class="項目">
            <img class="圖示" src="/images/alesis-symbol-email.svg" />
            <div class="標籤">電子信箱</div>
          </div>
        </div>
        <div class="download-button">
          <router-link to="/borrowLink" target="_blank">
            <button class="btn orange-download-btn">點我下載普匯inFlux</button>
          </router-link>
        </div>
        <div class="download-info">
          下載APP註冊登入，瞭解更多符合申請資格之學校名單
        </div>
      </alesis-section>
    </div>
    <!-- / 適用對象 -->

    <!-- 為何選擇 -->
    <div class="為何選擇">
      <alesis-header>
        <div class="標題">為什麼選擇3S名校貸</div>
      </alesis-header>
      <alesis-section>
        <alesis-space size="medium"></alesis-space>
        <div class="choose-grid">
          <div class="choose-item">
            <div class="choose-title">學生最低利</div>
            <div class="choose-info">利率最低3%~最高5%</div>
          </div>
          <div class="choose-item">
            <div class="choose-title">還款更靈活</div>
            <div class="choose-info">可每月先繳息，到期再還本金</div>
          </div>
          <div class="choose-item">
            <div class="choose-title">額度再升級</div>
            <div class="choose-info">最高額度可達18萬</div>
          </div>
          <div class="choose-item">
            <div class="choose-title">核貸率極高</div>
            <div class="choose-info">核貸率高達90%以上</div>
          </div>
        </div>
        <alesis-space size="medium"></alesis-space>
      </alesis-section>
    </div>
    <!-- / 為何選擇 -->

    <!-- 步驟說明 -->
    <div class="步驟說明">
      <alesis-header>
        <div class="標題">申貸四步驟</div>
      </alesis-header>
      <alesis-section :secondary="true">
        <alesis-space size="medium"></alesis-space>
        <div class="step-grid">
          <div class="step-item">
            <div class="step-title">選擇額度</div>
            <div class="step-picture">
              <div class="step-num step-1">1</div>
              <img class="step-img" src="~images/3S-step1.webp" />
            </div>
            <div class="step-info">
              <div>提供完整資訊</div>
              <div>有助提高額度</div>
            </div>
          </div>
          <div class="step-item">
            <div class="step-title">上傳資料</div>
            <div class="step-picture">
              <div class="step-num step-2">2</div>
              <img class="step-img" src="~images/3S-step2.webp" />
            </div>
            <div class="step-info">
              <div>AI數據分析審核</div>
              <div>全程無人打擾</div>
            </div>
          </div>
          <div class="step-item">
            <div class="step-title">持證自拍</div>
            <div class="step-picture">
              <div class="step-num step-3">3</div>
              <img class="step-img" src="~images/3S-step3.webp" />
            </div>
            <div class="step-info">
              <div>審核成功後</div>
              <div>立即上架幫您媒合投資人</div>
            </div>
          </div>
          <div class="step-item">
            <div class="step-title">上架媒合</div>
            <div class="step-picture">
              <div class="step-num step-4">4</div>
              <img class="step-img" src="~images/3S-step4.webp" />
            </div>
            <div class="step-info">24小時收到款項</div>
          </div>
        </div>
        <alesis-space size="medium"></alesis-space>
      </alesis-section>
    </div>
    <!-- / 步驟說明 -->
    <div>
      <alesis-section>
        <alesis-space size="small"></alesis-space>
        <div class="row no-gutters justify-content-center">
          <div class="col-auto apply-item">
            <img src="~images/3S-apply1.png" class="apply-item-img" />
            <span class="apply-text"
              ><span class="apply-hint">5</span>分鐘申請
            </span>
          </div>
          <div class="col-auto apply-item apply-arrow">➜</div>
          <div class="col-auto apply-item">
            <img src="~images/3S-apply2.png" class="apply-item-img" />
            <span class="apply-text">
              <span class="apply-hint">10</span>分鐘審核
            </span>
          </div>
          <div class="col-auto apply-item apply-arrow">➜</div>
          <div class="col-auto apply-item">
            <img src="~images/3S-apply3.png" class="apply-item-img" />
            <span class="apply-text">
              <span class="apply-hint">60</span>分鐘到帳
            </span>
          </div>
        </div>
        <alesis-space size="small"></alesis-space>
      </alesis-section>
    </div>

    <!-- 應用程式推薦 -->
    <alesis-app-recommendation action="我想借款"></alesis-app-recommendation>
    <!-- / 應用程式推薦 -->
  </div>
</template>

<script>
// Alesis 元件
import AlesisAppRecommendation from '../component/alesis/AlesisAppRecommendation'
import AlesisBullet from '../component/alesis/AlesisBullet'
import AlesisButton from '../component/alesis/AlesisButton'
import AlesisCounter from '../component/alesis/AlesisCounter'
import AlesisHeader from '../component/alesis/AlesisHeader'
import AlesisHorizontalRoadmap from '../component/alesis/AlesisHorizontalRoadmap'
import AlesisHuman from '../component/alesis/AlesisHuman'
import AlesisLoanHeader from '../component/alesis/AlesisLoanHeader'
import AlesisMoon from '../component/alesis/AlesisMoon'
import AlesisPlan from '../component/alesis/AlesisPlan'
import AlesisProject from '../component/alesis/AlesisProject'
import AlesisSection from '../component/alesis/AlesisSection'
import AlesisSuggestionReviews from '../component/alesis/AlesisSuggestionReviews'
import AlesisSymcard from '../component/alesis/AlesisSymcard'
import AlesisTaiwanMap from '../component/alesis/AlesisTaiwanMap'
import AlesisVerticalRoadmap from '../component/alesis/AlesisVerticalRoadmap'
import AlesisSpace from '../component/alesis/AlesisSpace'
// 遠端資料

import 'swiper/swiper.scss'
import 'swiper/components/navigation/navigation.min.css'
import SwiperCore, { Navigation } from 'swiper/core'

export default {
  components: {
    AlesisAppRecommendation,
    AlesisBullet,
    AlesisButton,
    AlesisCounter,
    AlesisHeader,
    AlesisHorizontalRoadmap,
    AlesisHuman,
    AlesisLoanHeader,
    AlesisMoon,
    AlesisPlan,
    AlesisProject,
    AlesisSection,
    AlesisSuggestionReviews,
    AlesisSymcard,
    AlesisTaiwanMap,
    AlesisVerticalRoadmap,
    AlesisSpace
  },
  mounted() {},
}
</script>

<style lang="scss">
@import '../component/alesis/alesis';
.img-title {
  width: 340px;
  @include rwd {
        width: 220px;
    }
}

.alesis-steps {
  .carousel {
    .selector {
      @include rwd {
        min-height: 520px;
        align-items: initial;
      }
    }
  }
}
</style>

<style lang="scss" scoped>
@import '../component/alesis/alesis';
/**
 * 適用對象
 */

.適用對象 {
  position: relative;
  --alesis-xheader-offset: 20%;
}
.標題 {
  margin-bottom: 10px;
}
.intro {
  max-width: 1200px;
  margin: auto;
  .intro-title {
    font-size: 42px;
    font-weight: bold;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.45;
    letter-spacing: 5.6px;
    text-align: left;
    color: #1e69aa;
    margin-bottom: 25px;
  }
  .intro-title-underline {
    width: 225px;
    border: solid 4px #1e69aa;
  }
  .intro-list {
    margin: 40px 0;
    .intro-list-item {
      &::before {
        content: '';
        display: inline-block;
        width: 52px;
        height: 52px;
        margin-right: 35px;
        background-image: url('~images/check-item.png');
      }
      display: flex;
      align-items: center;
      font-size: 28px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      letter-spacing: 2.8px;
      text-align: left;
      color: #404040;
      margin-bottom: 30px;
    }
  }
  .intro-list-underline {
    border: solid 2px #1e69aa;
    width: 113px;
    margin: 20px 0 40px;
  }
  .points-grid {
    display: flex;
    justify-content: space-between;
    .point-item {
      font-size: 28px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.43;
      letter-spacing: 2.8px;
      text-align: center;
      color: #8f8f8f;
    }
  }
}
.suit-info {
  font-size: 26px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.77;
  letter-spacing: 2.6px;
  text-align: center;
  color: #404040;
}
.download-button {
  margin: 40px auto 20px;
  text-align: center;
  .orange-download-btn {
    padding: 13px 22px;
    font-size: 28px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.44;
    letter-spacing: 3.6px;
    text-align: left;
    color: #fff;
    border-radius: 20px;
    box-shadow: 3px 6px 6px 0 rgba(0, 0, 0, 0.1);
    background-color: #f29600;
  }
}
.download-info {
  padding-bottom: 150px;
  font-size: 26px;
  font-weight: normal;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.5;
  letter-spacing: 2px;
  text-align: center;
  color: #404040;
}
.choose-grid {
  display: grid;
  gap: 25px;
  grid-template-columns: 1fr 1fr;
  max-width: 900px;
  margin: 0 auto;
  .choose-item {
    padding: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    .choose-title {
      width: fit-content;
      font-size: 32px;
      font-weight: 500;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.44;
      letter-spacing: 3.6px;
      text-align: left;
      color: #fff;
      padding: 11px 22px;
      margin-bottom: 30px;
      border-radius: 20px;
      background-color: #1e69aa;
    }
    .choose-info {
      padding: 10px 0;
      font-size: 26px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.2;
      letter-spacing: 2.6px;
      text-align: center;
      color: #404040;
    }
  }
}
.step-grid {
  display: grid;
  margin: auto;
  grid-template-columns: 290px 290px 300px 290px;
  gap: 35px;
  width: fit-content;
  .step-item {
    padding-left: 100px;
    .step-title {
      font-size: 26px;
      font-weight: 500;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.25;
      letter-spacing: normal;
      text-align: center;
      color: #1e69aa;
      margin-bottom: 25px;
    }
    .step-picture {
      position: relative;
      .step-num {
        -webkit-text-stroke: 4px #f29600;
        color: #fff;
        font-family: Arial;
        font-size: 262px;
        font-stretch: normal;
        font-style: normal;
        font-weight: bold;
        letter-spacing: 13.1px;
        line-height: 1.15;
        position: absolute;
        text-align: left;
        z-index: -1;
        &.step-1 {
          left: -115px;
          top: 35px;
        }
        &.step-2 {
          left: -105px;
          top: 35px;
        }
        &.step-3 {
          left: -105px;
          top: 35px;
        }
        &.step-4 {
          left: -105px;
          top: 35px;
        }
      }
    }
    .step-info {
      font-size: 18px;
      font-weight: normal;
      font-stretch: normal;
      font-style: normal;
      line-height: 1.56;
      letter-spacing: normal;
      text-align: center;
      color: #404040;
      margin-top: 25px;
    }
  }
}
.apply-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  &.apply-arrow {
    padding: 0 77px;
    -webkit-text-stroke: 2px #f29600;
    font-family: Arial;
    font-size: 128px;
    font-weight: bold;
    font-stretch: normal;
    line-height: 0.27;
    letter-spacing: 12.6px;
    text-align: left;
    color: #fff;
  }
  .apply-text {
    font-size: 26px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.31;
    letter-spacing: 2.6px;
    text-align: left;
    color: #404040;
  }
  .apply-hint {
    font-family: Arial;
    font-size: 50px;
    font-weight: bold;
    font-style: italic;
    letter-spacing: 5px;
    color: #f29600;
  }
}

.alesis-humans {
  position: relative;
  --alesis-xheader-offset: -70%;
  --alesis-xsection-offset-top: 8rem;
}

/**
 * 步驟說明
 */

.步驟說明 {
  position: relative;

  --alesis-xsection-offset-top: 8rem;
}

.適用對象 .項目列表 {
  display: grid;
  max-width: 900px;
  margin: 40px auto 20px;
  grid-template-columns: repeat(4, 1fr);

  @include rwd {
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem 0rem;
    max-width: 310px;
  }
}

.適用對象 .項目列表 .項目 {
  text-align: center;
}

.適用對象 .項目列表 .項目 .圖示 {
  width: 100px;

  @include rwd {
    width: 80px;
  }
}

.適用對象 .項目列表 .項目 .標籤 {
  margin-top: 1rem;
  color: #112e53;
  font-size: 1.3rem;

  @include rwd {
    font-size: 1rem;
    margin-top: 0.5rem;
  }
}

/**
 * 為何選擇
 */

.為何選擇 {
  position: relative;

  --alesis-xsection-offset-top: 7rem;
}

@media screen and (max-width: 768px) {
  .intro {
    max-width: 1200px;
    margin: auto;
    .intro-title {
      font-size: 26px;
      line-height: 1.25;
      letter-spacing: 1px;
      text-align: left;
      color: #1e69aa;
      margin-bottom: 15px;
    }
    .intro-title-underline {
      width: 125px;
      border: solid 2px #1e69aa;
    }
    .intro-list {
      margin: 20px 0;
      .intro-list-item {
        &::before {
          content: '';
          width: 22px;
          height: 22px;
          margin-right: 15px;
          background-position: center center;
          background-size: contain;
          background-repeat: no-repeat;
          background-image: url('~images/check-item.png');
        }
        font-size: 16px;
        letter-spacing: 1.2px;
      }
    }
    .intro-list-underline {
      border: solid 2px #1e69aa;
      width: 73px;
      margin: 20px 0;
    }
    .points-grid {
      display: flex;
      justify-content: space-between;
      .point-item {
        max-width: 30%;
        font-size: 14px;
        letter-spacing: 1px;
      }
      .point-img {
        width: 100%;
        max-width: 200px;
      }
    }
  }
  .suit-info {
    font-size: 16px;
    line-height: 1.25;
    letter-spacing: 1px;
  }
  .download-button {
    margin: 20px auto 20px;
    .orange-download-btn {
      padding: 6px 15px;
      font-size: 18px;
      letter-spacing: 1.25px;
    }
  }
  .download-info {
    padding-bottom: 150px;
    font-size: 16px;
    line-height: 1.5;
  }
  .choose-grid {
    display: grid;
    gap: 18px;
    grid-template-columns: 1fr 1fr;
    .choose-item {
      padding: 4px;
      .choose-title {
        width: fit-content;
        font-size: 20px;
        letter-spacing: 1px;
        padding: 8px 16px;
        margin-bottom: 20px;
      }
      .choose-info {
        padding: 10px 0;
        font-size: 14px;
        letter-spacing: 1px;
        text-align: center;
        color: #404040;
      }
    }
  }
  .step-grid {
    display: grid;
    margin: auto;
    grid-template-columns: 290px 290px 300px 290px;
    gap: 35px;
    width: fit-content;
    .step-item {
      padding-left: 100px;
      .step-title {
        font-size: 20px;
        margin-bottom: 15px;
      }
      .step-picture {
        position: relative;
        .step-num {
          -webkit-text-stroke: 4px #f29600;
          color: #fff;
          font-family: Arial;
          font-size: 262px;
          font-stretch: normal;
          font-style: normal;
          font-weight: bold;
          letter-spacing: 13.1px;
          line-height: 1.15;
          position: absolute;
          text-align: left;
          z-index: -1;
          &.step-1 {
            left: -115px;
            top: 35px;
          }
          &.step-2 {
            left: -105px;
            top: 35px;
          }
          &.step-3 {
            left: -105px;
            top: 35px;
          }
          &.step-4 {
            left: -105px;
            top: 35px;
          }
        }
      }
      .step-info {
        font-size: 16px;
      }
    }
  }
  .apply-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    .apply-item-img{
        width: 52px;
    }
    &.apply-arrow {
      padding: 0 0 20px;
      font-size: 36px;
      letter-spacing: 1.6px;
    }
    .apply-text {
      font-size: 12px;
      letter-spacing: 1px;
    }
    .apply-hint {
      font-family: Arial;
      font-size: 20px;
    }
  }
}
</style>
