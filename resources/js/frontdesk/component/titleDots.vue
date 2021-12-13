<template>
  <div class="title-dots" :class="{ reverse: reverse }">
    <div class="title-dot-up">
      <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
      <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
      <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
      <div class="dots" style="opacity: 0.75; width: 6px; height: 6px"></div>
    </div>
    <div class="d-flex justify-content-center">
      <slot></slot>
    </div>
    <div class="title-dot-down">
      <div class="dots" style="opacity: 0.75; width: 6px; height: 6px"></div>
      <div class="dots" style="opacity: 0.6; width: 6px; height: 6px"></div>
      <div class="dots" style="opacity: 0.4; width: 4px; height: 4px"></div>
      <div class="dots" style="opacity: 0.2; width: 2px; height: 2px"></div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    reverse: {
      type: Boolean,
      default: false
    },
  },
}
</script>

<style lang="scss" scoped>
.title-dots {
  background: linear-gradient(#fff 50%, #f5f6f8 50%);
  &.reverse {
    background: linear-gradient(#f5f6f8 50%, #fff 50%);
  }
}
.dots {
  margin: 0.7rem auto;
  background-color: #3670d3;
  border-radius: 50%;
}
.title {
  background: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
  background-clip: text;
  color: rgba($color: #fff, $alpha: 0);
  font-size: 36px;
  line-height: 1.2;
}
</style>
