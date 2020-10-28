<template>
  <svg
    version="1.1"
    id="timelineTopRoute"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    viewBox="0 0 300 536.4"
    xml:space="preserve"
  >
    <defs>
      <linearGradient :id="`T_${this.$props.index}`">
        <stop offset="-10%" :stop-color="start" />
        <stop offset="110%" :stop-color="end" />
      </linearGradient>
    </defs>
    <g>
      <path
        :fill="`url(#T_${this.$props.index})`"
        d="M295.2,264.7c-3.8-4.2-7.6-8.4-11.4-12.7c-34.7,30.1-67.5,58.4-133.9,58.4c-66.5,0-99.3-28.4-134-58.6   l-15.6,18c36.5,31.6,74.3,64.4,149.6,64.4c75.4,0,113.1-32.7,149.6-64.4c0.1-0.1,0.1-0.1,0.2-0.2   C298.2,268,296.7,266.4,295.2,264.7z"
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