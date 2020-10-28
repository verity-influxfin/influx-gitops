<template>
  <svg
    version="1.1"
    id="timelineTop"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    viewBox="0 0 300 536.4"
    xml:space="preserve"
  >
    <defs>
      <pattern
        :id="`img${$props.num}`"
        patternUnits="userSpaceOnUse"
        width="140"
        height="140"
        x="-28"
        y="0"
      >
        <image :href="$props.img" width="80" height="80" />
      </pattern>
    </defs>
    <g>
      <g>
        <rect x="144.5" y="242.7" class="st1" width="14.3" height="79.7" />
        <circle cx="151.6" cy="322.4" r="31.6" class="st2" />
        <circle cx="151.6" cy="322.4" r="26.6" class="st3" />
        <circle cx="151.6" cy="322.4" r="14.3" :fill="$props.color" />
        <circle cx="151.6" cy="183.1" r="84.1" class="st1" />
        <circle cx="151.6" cy="183.1" r="69.4" :fill="$props.color" />
        <circle cx="151.6" cy="183.1" r="60.4" class="st3" />
        <circle cx="151.6" cy="183.1" r="60" :fill="`url(#img${$props.num})`" />
        <text
          class="text"
          x="50%"
          y="71%"
          dominant-baseline="middle"
          text-anchor="middle"
        >
          {{ $props.date }}
        </text>
      </g>
    </g>
  </svg>
</template>

<script>
export default {
  props: ["color", "date", "num", "img"],
};
</script>

<style lang="scss">
#timelineTop {
  .st0 {
    fill: #299bcb;
  }
  .st1 {
    fill: #ffffff;
    stroke: #b3b3b3;
    stroke-miterlimit: 10;
  }
  .st2 {
    fill: #ffffff;
  }
  .st3 {
    fill: #ffffff;
  }
  .text {
    fill: #ff7b0a;
    font-size: 1.5rem;
    text-shadow: 0 0 3px white;
    font-weight: bold;
  }
}
</style>