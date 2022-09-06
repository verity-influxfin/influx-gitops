<template>
  <main class="risk-report">
    <section class="banner"></section>
    <section class="title">
      <div class="block-content">
        <div class="title-group">
          <div class="title-1">月 借貸媒合風險報告書</div>
          <div class="title-2">May 2022</div>
        </div>
        <div class="hint">以下資料統計至2022年5月31日</div>
      </div>
    </section>
    <section class="anniversary">
      <div class="block-content">
        <h1 class="h1">累計至本月之平均年化報酬率</h1>
        <div class="value-group">
          <span class="value-1">12</span>
          <span class="value-2">.02％</span>
        </div>
      </div>
    </section>
    <section class="apply-cases">
      <div class="block-content">
        <div>
          <h1 class="h1">本月申貸案件數</h1>
          <div class="apply-case-value">
            <span>1,580</span>
            <span class="h1">件</span>
          </div>
        </div>
        <div>
          <h1 class="h1">學生貸申貸案件598件+上班族申貸案件數982件</h1>
          <div class="apply-case-bar-group">
            <div class="apply-case-bar-left" style="width: calc(38% + 100px)">
              <span class="apply-case-bar-text">38%</span>
            </div>
            <div class="apply-case-bar-right" style="width: 62%">
              <span class="apply-case-bar-text">62%</span>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section class="success-cases">
      <div class="block-content">
        <div>
          <h1 class="h1">累計媒合成功件數</h1>
          <div class="success-case-value">
            <span class="em">1,580</span>
            <span class="h1">件</span>
          </div>
        </div>
        <div>
          <h1 class="h1">累積金額</h1>
          <div class="success-case-value">539,531,987</div>
          <div class="h1">元</div>
        </div>
        <div>
          <h1 class="h1">累積筆數</h1>
          <div class="success-case-value">95,701</div>
          <div class="h1">筆</div>
        </div>
      </div>
    </section>
    <section class="index-cases">
      <div class="block-content">
        <h1 class="h1">
          累計效益與指標：平均每筆投資金額<span class="em">10,725</span>元
        </h1>
        <h2 class="h2">
          學生貸媒合成功均額: <span class="em">20,446</span>元/件均
          上班族貸媒合成功均額: <span class="em">60,362</span>元/件均
        </h2>
        <h3 class="h3">
          <div>
            逾期概況：已累計回收逾期金額 <span class="em">17,435,176 </span>元
          </div>
          <div class="sm">
            <div>當月逾期人數: 19 人</div>
            <div>當月逾期筆數: 29 人</div>
          </div>
        </h3>
      </div>
    </section>
    <section class="repay">
      <h1 class="h1">
        準時還款率 <span class="sm">(1~9級依會員信評等級)</span>
      </h1>
    </section>
    <section class="analysis">
      <h1 class="h1">重要指標分析</h1>
      <div class="block-content">
        <div class="block-text">
          本月媒合金額，較去年同月<span class="em">↑增長 2.0%</span>
        </div>
        <div class="block-text">
          本月學生貸申請數，較去年同月<span class="em">↑增長 2.0%</span>
        </div>
        <div class="block-text">
          本月上班族貸申請數，較去年同月 <span class="em">↑增長 3.5%</span>
        </div>
        <div class="hint"></div>
      </div>
    </section>
    <section class="functions">
      <div class="block-content">
        <div class="swiper">
          <!-- Additional required wrapper -->
          <div class="swiper-wrapper">
            <!-- Slides -->
            <div class="swiper-slide">
              <a href="javascript:;">
                <alesis-button class="month-btn">test</alesis-button>
              </a>
            </div>
          </div>
          <!-- If we need navigation buttons -->
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
        <div class="mt-5 d-flex justify-content-center">
          <a href="/investLink" target="_blank">
            <button class="btn btn-invest">立即投資</button>
          </a>
        </div>
      </div>
    </section>
  </main>
</template>

<script>
import AlesisButton from '@/component/alesis/AlesisButton';
import 'swiper/swiper.scss';
import "swiper/components/navigation/navigation.min.css"
import SwiperCore, {
  Navigation
} from 'swiper/core';
export default {
  components: {
    AlesisButton,
  },
  mounted() {
    SwiperCore.use([Navigation]);
    // 初始化這個案例分享容器幻燈片。
    new Swiper('.swiper', {
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  },
}
</script>

<style lang="scss" scoped>
$color--primary: #f2b162;
$color__text--primary: #000000;
$color__text--secondary: #6b6b6b70;
$color__background--gradient: linear-gradient(180deg, #ffffff 0%, #f3f9fc 100%);
.block-content {
  width: 100%;
  max-width: 1400px;
  padding: 0 15px;
  margin: 0 auto;
}
.banner {
  background-image: url('~images/risk/risk-banner.png');
  background-position: top left;
  background-repeat: no-repeat;
  background-size: cover;
  position: relative;
  height: 800px;
}
.title {
  background-image: url('~images/risk/risk-wave.svg');
  background-repeat: no-repeat;
  background-position: bottom center;
  background-size: cover;
  height: 280px;
  .block-content {
    bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }
  .title-group {
    position: relative;
    .title-1 {
      font-style: normal;
      font-weight: 700;
      font-size: 48px;
      line-height: 70px;
      color: #083a6e;
      &::before {
        content: '5';
        font-style: normal;
        font-weight: 900;
        font-size: 128px;
        line-height: 1.4;
        margin-right: 5px;
        color: $color__text--primary;
      }
    }
    .title-2 {
      position: absolute;
      bottom: 86px;
      left: 80px;
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      line-height: 1.4;
      color: #393939;
    }
  }
  .hint {
    font-style: normal;
    font-weight: 400;
    font-size: 24px;
    line-height: 70px;
    color: $color__text--secondary;
  }
}
.anniversary {
  padding: 5px;
  min-height: 300px;
  background: url('~images/risk/risk-anniversary-bg1.png'),
    url('~images/risk/risk-anniversary-bg2.png');
  background-position: center center;
  background-repeat: no-repeat;
  .h1 {
    font-style: normal;
    font-weight: 700;
    font-size: 32px;
    line-height: 51px;
    text-align: center;
    color: #393939;
  }
  .value-group {
    font-style: normal;
    font-weight: 700;
    text-align: center;
    color: #0f3560;
    .value-1 {
      font-size: 100px;
      line-height: 100px;
      margin: 0;
    }
    .value-2 {
      margin: 0;
      font-size: 40px;
      line-height: 100px;
    }
  }
}
.apply-cases {
  background-image: url('~images/risk/risk-wave-up.svg');
  background-position: top center;
  background-repeat: no-repeat;
  background-size: cover;
  padding: 162px 0 105px;
  .block-content {
    display: grid;
    grid-template-columns: 4fr 7fr;
    .h1 {
      font-style: normal;
      font-weight: 700;
      font-size: 32px;
      line-height: 51px;
      text-align: center;
      color: #393939;
    }
    .apply-case-value {
      text-align: center;
      font-style: normal;
      font-weight: 700;
      font-size: 96px;
      line-height: 110px;
      letter-spacing: -0.2px;
      color: $color--primary;
      .h1 {
        line-height: 110px;
      }
    }
    .apply-case-bar-group {
      display: flex;
      min-width: 720px;
      margin-top: 20px;
      .apply-case-bar-left,
      .apply-case-bar-right {
        border-radius: 35px;
        height: 50px;
        position: relative;
        display: flex;
        justify-content: center;
      }
      .apply-case-bar-text {
        color: $color__text--secondary;
        font-size: 40px;
        line-height: 50px;
        text-align: center;
        position: relative;
        top: 50px;
      }
      .apply-case-bar-left {
        background-color: $color--primary;
        z-index: 3;
        right: -40px;
      }
      .apply-case-bar-right {
        position: relative;
        background-color: #083a6e;
        z-index: 2;
      }
    }
  }
}
.success-cases {
  background: $color__background--gradient;
  padding: 100px 0 85px;
  .block-content {
    display: grid;
    grid-template-columns: 4fr 3fr 3fr;
    gap: 30px;
    .h1 {
      font-style: normal;
      font-weight: 700;
      font-size: 32px;
      line-height: 1.4;
      text-align: center;
      color: #393939;
    }
    .success-case-value {
      text-align: center;
      font-style: normal;
      font-weight: 700;
      font-size: 48px;
      line-height: 1.4;
      letter-spacing: -0.2px;
      color: $color__text--secondary;
      .em {
        color: $color--primary;
        font-size: 96px;
        line-height: 110px;
      }
      .h1 {
        line-height: 110px;
      }
    }
  }
}
.index-cases {
  padding: 43px 0;
  .block-content {
    .h1 {
      font-style: normal;
      font-weight: 700;
      font-size: 40px;
      line-height: 64px;
      color: $color__text--primary;
      text-align: center;
      .em {
        color: $color--primary;
        font-size: 64px;
        line-height: 1;
      }
    }
    .h2 {
      margin-top: 10px;
      font-style: normal;
      font-weight: 700;
      font-size: 36px;
      line-height: 50px;
      color: $color__text--secondary;
      text-align: center;
      .em {
        color: $color--primary;
        font-size: 40px;
        line-height: 50px;
      }
    }
    .h3 {
      display: flex;
      margin-top: 57px;
      gap: 57px;
      align-items: center;
      justify-content: center;
      font-style: normal;
      font-weight: 700;
      font-size: 40px;
      line-height: 50px;
      color: #083a6e;
      text-align: center;
      .em {
        color: $color--primary;
        font-size: 40px;
        line-height: 50px;
      }
      .sm {
        color: $color--primary;
        font-style: normal;
        font-weight: 500;
        font-size: 24px;
        line-height: 27px;
        color: #393939;
      }
    }
  }
}
.repay {
  padding: 43px 0;
  background-color: #f3f9fc;
  .h1 {
    font-style: normal;
    font-weight: 700;
    font-size: 40px;
    line-height: 48px;
    color: #393939;
    text-align: center;
    .sm {
      color: $color__text--secondary;
      font-size: 32px;
      line-height: 48px;
    }
  }
}
.analysis {
  padding: 43px 0;
  .h1 {
    font-style: normal;
    font-weight: 700;
    font-size: 48px;
    line-height: 1.4;
    text-align: center;
    color: $color__text--primary;
  }
  .block-content {
    margin-top: 50px;
  }
  .block-text {
    font-style: normal;
    font-weight: 500;
    font-size: 28px;
    line-height: 1.8;
    text-align: center;
    color: $color__text--primary;
    .em {
      @extend .block-text;
      color: $color--primary;
    }
  }
}
.functions {
  padding: 100px 0;
  .swiper {
    max-width: 800px;
  }
  .swiper-slide {
    display: flex;
    gap: 30px;
    justify-content: center;
  }
  .month-btn {
    padding: 12px 42px;
    font-size: 28px;
  }
  .btn-invest {
    border-radius: 12px;
    padding: 10px;
    width: 250px;
    background-color: #083a6e;
    box-shadow: 4px 4px 2px rgb(0 0 0 / 10%);
    font-size: 28px;
    color: #fff;
  }
}
</style>
