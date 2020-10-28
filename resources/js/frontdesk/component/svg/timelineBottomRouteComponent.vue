<template>
  <svg
    version="1.1"
    id="timelineBottomRoute"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    viewBox="0 0 300 536.4"
    xml:space="preserve"
  >
    <defs>
      <linearGradient :id="`B_${this.$props.index}`">
        <stop offset="-10%" :stop-color="start" />
        <stop offset="110%" :stop-color="end" />
      </linearGradient>
    </defs>
    <g>
      <path
        :fill="`url(#B_${this.$props.index})`"
        d="M299.8,267.3c-0.3-0.3-0.6-0.5-0.9-0.8c-36.5-31.6-74.3-64.4-149.6-64.4c-74.9,0-112.7,32.4-149,63.9   c3.8,4.2,7.6,8.4,11.4,12.6c1.5,1.7,3,3.4,4.5,5c34.4-29.8,67.2-57.7,133.1-57.7c66.5,0,99.3,28.4,134,58.6   c0.2,0.1,0.3,0.3,0.5,0.4c2.1-3.4,4.8-6.4,7.6-9.2c1.5-1.5,3.1-2.9,4.6-4.4c0.7-0.7,1.4-1.3,2-2c-0.1,0.1,1.9-2.2,1.3-1.4   C299.5,267.7,299.6,267.5,299.8,267.3z"
      />
    </g>
  </svg>
</template>

<script>
export default {
  props: ["index", "route"],
  data: () => ({
    start: "",
    end: "",
  }),
  created() {
    console.log(this.$props.route[this.$props.index - 1]);
    this.start = this.$props.route[this.$props.index - 1]
      ? this.$props.route[this.$props.index - 1]
      : this.$props.route[this.$props.index];
    this.end = this.$props.route[this.$props.index + 1]
      ? this.$props.route[this.$props.index + 1]
      : this.$props.route[this.$props.index];
  },
};
</script>

<style lang="scss">
</style>