<template>
  <div class="step-item">
    <div>
      <img v-bind="step.img" class="img-fluid" />
    </div>
    <div>
      <slot>
        <div class="step-item-title">{{ step.title }}</div>
        <div class="step-item-content" v-html="step.content"></div>
      </slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    step: {
      type: Object,
      default() {
        return {
          title: '',
          content: '',
          img: {
            src: '',
            alt: '',
          }
        }
      }
    },
  },
}
</script>

<style lang="scss" scoped>
.step-item {
  display: flex;
  align-items: center;
  gap: 35px;
  &-title {
    font-style: normal;
    font-weight: 700;
    font-size: 32px;
    line-height: 1.4;
    color: #023d64;
  }
  &-content {
    font-style: normal;
    font-weight: 400;
    font-size: 24px;
    line-height: 1.4;
    color: #023d64;
    opacity: 0.56;
  }
}
</style>
