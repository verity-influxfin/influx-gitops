<template>
  <div class="faq-row">
    <div class="faq-row-header" :class="{ expand }" @click="expand = !expand">
      <div class="faq-row-title">{{ title }}</div>
      <div class="faq-row-expand">
        <div v-show="expand">
          <i class="fa fa-chevron-right"></i>
        </div>
        <div v-show="!expand">
          <i class="fa fa-chevron-down"></i>
        </div>
      </div>
      <div class="faq-row-bg-text">{{ bgText }}</div>
    </div>
    <div class="faq-row-content" v-show="expand">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: ''
    },
    bgText: {
      type: String,
      default: ''
    }
  },
  data() {
    return {
      expand: false
    }
  },
}
</script>

<style lang="scss" scoped>
.faq-row {
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  border-radius: 16px;
  user-select: none;
  .faq-row-header {
    &.expand {
      border-radius: 16px 16px 0 0;
    }
    display: flex;
    justify-content: space-between;
    background: linear-gradient(90deg, #4086c9 0%, #7ac1ea 100%);
    padding: 20px 40px;
    position: relative;
    cursor: pointer;
    border-radius: 16px;
    .faq-row-title {
      font-style: normal;
      font-weight: 400;
      font-size: 36px;
      line-height: 1.4;
      color: #ffffff;
    }
    .faq-row-expand {
      font-size: 36px;
      line-height: 1.4;
      color: #fff;
    }
    .faq-row-bg-text {
      position: absolute;
      font-style: normal;
      font-weight: 700;
      font-size: 62px;
      line-height: 1.4;
      opacity: 0.2;
      right: 100px;
      bottom: -5px;
      -webkit-text-fill-color: transparent;
      -webkit-text-stroke-width: 1px;
      -webkit-text-stroke-color: white;
    }
  }
  .faq-row-content {
    padding: 20px 45px;
  }
}
</style>
