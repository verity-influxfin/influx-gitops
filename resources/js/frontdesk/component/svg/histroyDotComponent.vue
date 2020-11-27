<template>
  <svg
    version="1.1"
    id="Layer_0"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    x="0px"
    y="0px"
    viewBox="0 0 488.9 312.3"
    style="enable-background: new 0 0 488.9 312.3"
    xml:space="preserve"
  >
    <linearGradient
      id="SVGID_1_"
      gradientUnits="userSpaceOnUse"
      x1="-240.423"
      y1="448.565"
      x2="-10.3209"
      y2="448.565"
      gradientTransform="matrix(1.0826 0 0 -1.0826 500.8037 738.6165)"
    >
      <stop offset="0" style="stop-color: #7873e4" />
      <stop offset="1" style="stop-color: #07e3fd" />
    </linearGradient>
    <path
      class="st0"
      d="M483.7,199.1c-4.3,61.5-55.7,107.8-118.6,107.8c-62.8,0-114.2-46.3-118.6-107.8"
    />
    <linearGradient
      id="SVGID_2_"
      gradientUnits="userSpaceOnUse"
      x1="-348.8596"
      y1="641.1107"
      x2="-304.0677"
      y2="641.1107"
      gradientTransform="matrix(1.0826 0 0 -1.0826 500.8037 738.6165)"
    >
      <stop offset="0" style="stop-color: #5476cf" />
      <stop offset="1" style="stop-color: #153a71" />
    </linearGradient>
    <path class="st2" d="M9.5,199.6" />
    <linearGradient
      id="SVGID_3_"
      gradientUnits="userSpaceOnUse"
      x1="-463.3006"
      y1="548.1401"
      x2="-224.1984"
      y2="548.1401"
      gradientTransform="matrix(1.0826 0 0 -1.0826 500.8037 738.6165)"
    >
      <stop offset="0" style="stop-color: #5479d1" />
      <stop offset="0.2341" style="stop-color: #5375ce" />
      <stop offset="0.4682" style="stop-color: #5476d0" />
      <stop offset="0.6817" style="stop-color: #153a71" />
      <stop offset="0.866" style="stop-color: #153a71" />
      <stop offset="1" style="stop-color: #153a71" />
    </linearGradient>
    <path
      class="st3"
      d="M10.1,199.1c4.3-61.5,55.7-107.8,118.6-107.8c62.8,0,114.2,46.3,118.6,107.8"
    />
    <path class="st2" d="M246.6,199.6" />
    <circle class="st4" cx="246.4" cy="199.1" r="2.8" />
    <g>
      <text transform="matrix(1 0 0 1 60.4141 194.0636)" class="st5 st6 st7">
        {{content.year1}}
      </text>
      <text transform="matrix(1 0 0 1 147.2036 194.0002)" class="st5 st6 st8">
        {{content.date1}}
      </text>
    </g>
    <g>
      <text transform="matrix(1 0 0 1 86.9291 219.8478)" class="st5 st9 st10">
        {{content.title1}}
      </text>
    </g>
    <g>
      <text transform="matrix(1 0 0 1 44.9528 246.9817)" class="st5 st9 st11">
        {{content.desc1}}
      </text>
    </g>
    <line class="st12" x1="46" y1="228" x2="211.3" y2="228" />
    <g>
      <text transform="matrix(1 0 0 1 295.5624 194.0636)" class="st13 st6 st7">
        {{content.year2}}
      </text>
      <text transform="matrix(1 0 0 1 385.352 194.0002)" class="st13 st6 st8">
        {{content.date2}}
      </text>
    </g>
    <text transform="matrix(1 0 0 1 312.5757 219.8478)" class="st13 st9 st10">
      {{content.title2}}
    </text>
    <text transform="matrix(1 0 0 1 281.741 246.9817)" class="st13 st9 st11">
      {{content.desc2}}
    </text>
    <line class="st14" x1="282.8" y1="228" x2="448.1" y2="228" />
  </svg>
</template>

<script>
export default {
  props:['content']
};
</script>

<style lang="scss">
#Layer_0 {
  .st0 {
    fill: none;
    stroke: url(#SVGID_1_);
    stroke-width: 11;
    stroke-linecap: round;
    stroke-linejoin: round;
    stroke-miterlimit: 10;
  }
  .st2 {
    fill: none;
    stroke: #000000;
    stroke-width: 20;
    stroke-linecap: round;
    stroke-linejoin: round;
    stroke-miterlimit: 10;
  }
  .st3 {
    fill: none;
    stroke: url(#SVGID_3_);
    stroke-width: 20;
    stroke-linecap: round;
    stroke-linejoin: round;
    stroke-miterlimit: 10;
  }
  .st4 {
    fill: #ffffff;
  }
  .st5 {
    fill: #153a71;
  }
  .st6 {
    font-family: Arial, "微軟正黑體", "蘋果儷中黑";
  }
  .st7 {
    font-size: 36px;
  }
  .st8 {
    font-size: 18px;
  }
  .st9 {
    font-family: Arial, "微軟正黑體", "蘋果儷中黑";
  }
  .st10 {
    font-size: 21px;
  }
  .st11 {
    font-size: 14px;
  }
  .st12 {
    fill: #153a71;
    stroke: #1a3e78;
    stroke-miterlimit: 10;
  }
  .st13 {
    fill: #1baeef;
  }
  .st14 {
    fill: #1baeef;
    stroke: #1baeef;
    stroke-miterlimit: 10;
  }
}
</style>