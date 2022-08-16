<template>
  <div class="step-main">
    <div class="step-content" v-for="(step, index) in steps" :key="index">
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
    right: 269px;
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
  transform: scale(0.97);
  transition-property: opacity;
  transition-duration: 1s;
  opacity: 0;
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
      right: 143px;
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
    transform: scale(0.55);
  }
}
</style>
