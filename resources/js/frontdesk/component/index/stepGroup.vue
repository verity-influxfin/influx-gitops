<template>
  <div class="step-main swiper-mode" v-if="swiperMode">
    <div class="d-none d-md-block">
      <div
        v-for="(step, index) in steps"
        :key="index"
        :class="['step-content', 'swiper-slide', contentClass]"
      >
        <div class="step-content-title" :data-step="index + 1">
          {{ step.title }}
        </div>
        <div class="step-content-info" v-html="step.info"></div>
        <div class="step-content-img">
          <img class="img-fluid" :src="step.img" />
        </div>
      </div>
    </div>
    <div class="d-md-none d-block">
      <div class="swiper-step">
        <div class="swiper-wrapper">
          <div
            v-for="(step, index) in steps"
            :key="index"
            :class="['step-content', 'swiper-slide', contentClass]"
          >
            <div class="step-content-title" :data-step="index + 1">
              {{ step.title }}
            </div>
            <div class="step-content-info" v-html="step.info"></div>
            <div class="step-content-img">
              <img class="img-fluid" :src="step.img" />
            </div>
          </div>
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </div>
  <div class="step-main" v-else>
    <div
      v-for="(step, index) in steps"
      :key="index"
      :class="['step-content', contentClass]"
    >
      <div class="step-content-title" :data-step="index + 1">
        {{ step.title }}
      </div>
      <div class="step-content-info" v-html="step.info"></div>
      <div class="step-content-img">
        <img class="img-fluid" :src="step.img" />
      </div>
    </div>
  </div>
</template>

<script>
import Swiper from 'swiper/bundle';

export default {
  props: {
    steps: {
      type: Array,
      default: [
        {
          img: require('@/asset/images/index/invest-step-1.png'),
          title: '步驟一',
          info: '進入我的資產'
        },
        {
          img: require('@/asset/images/index/invest-step-2.png'),
          title: '步驟二',
          info: '選擇單筆/打包出售'
        },
        {
          img: require('@/asset/images/index/invest-step-3.png'),
          title: '步驟三',
          info: '<div>調整出讓金額，並點選出讓</div><div>詳閱轉讓協議書</div>'
        },
        {
          img: require('@/asset/images/index/invest-step-4.png'),
          title: '步驟四',
          info: '上架成功，等待媒合'
        }
      ]
    },
    contentClass: {
      type: String,
      default: ''
    },
    swiperMode: {
      type: Boolean,
      default: false
    }
  },
  mounted() {
    if (this.swiperMode) {
      const swiper = new Swiper('.swiper-step', {
        pagination: {
          el: '.swiper-pagination',
        },
      })
    }
  },
}
</script>

<style lang="scss" scoped>
$color--primary: #036eb7;
$color__text--primary: #023d64;
.step-content {
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
    .step-content-img {
      z-index: 10;
      opacity: 1;
      transition-property: opacity;
      transition-duration: 1s;
    }
  }
  &:first-of-type {
    .step-content-img {
      opacity: 1;
      z-index: 3;
    }
  }
  &:last-of-type {
    border-left: none;
  }
}
.step-main {
  margin: 45px auto;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: flex-end;
}
.step-content-title {
  position: relative;
  font-style: normal;
  font-weight: 700;
  font-size: 36px;
  color: #036fb7;
  &::before {
    background: $color--primary;
    border-radius: 50%;
    color: #fff;
    content: attr(data-step);
    font-size: 36px;
    font-style: normal;
    font-weight: 500;
    height: 60px;
    line-height: 60px;
    position: absolute;
    left: -72px;
    text-align: center;
    width: 60px;
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
  top: -4px;
  left: 15px;
  position: absolute;
  width: 262px;
  transition-property: opacity;
  transition-duration: 1s;
  opacity: 0;
}
.swiper-slide {
  position: initial;
}
@media screen and (max-width: 767px) {
  .step-content {
    width: 155px;
    padding: 0 15px 15px 20px;
    border-left: 3px solid $color--primary;
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
    &:first-of-type {
      .step-content-img {
        opacity: 1;
        z-index: 3;
      }
    }
    &:last-of-type {
      border-left: none;
    }
  }
  .step-main {
    margin: 0 auto;
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
      content: attr(data-step);
      font-size: 14px;
      height: 24px;
      line-height: 24px;
      position: absolute;
      left: -33px;
      width: 24px;
    }
  }
  .step-content-info {
    font-size: 12px;
    line-height: 1.4;
  }
  .step-content-img {
    top: -114px;
    left: -45px;
    width: 144px;
  }
  .step-main.swiper-mode {
    margin: 22px auto;
    position: relative;
    display: block;
  }
  .swiper-step {
    // width: 400px;
    height: 620px;
    .swiper-slide {
      position: relative;
    }
    .step-content {
      padding: 15px;
      border-left: none;
    }
    .step-content-title {
      font-size: 18px;
      text-align: center;
      line-height: 1.4;
      margin-bottom: 6px;
      &::before {
        display: none;
      }
    }
    .step-content-info {
      text-align: center;
      font-size: 12px;
      line-height: 16px;
    }
    .step-content-img {
      top: 0;
      left: 0;
      display: flex;
      justify-content: center;
      position: relative;
      width: initial;
      max-height: 520px;
      opacity: 1;
    }
  }
  .swiper-pagination {
    gap: 5px;
    display: flex;
    justify-content: center;
    transform: scale(1.2);
    position: relative;
  }
}
</style>
