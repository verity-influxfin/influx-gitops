<template>
  <svg
    version="1.1"
    id="routestart"
    xmlns="http://www.w3.org/2000/svg"
    xmlns:xlink="http://www.w3.org/1999/xlink"
    x="0px"
    y="0px"
    viewBox="0 0 240 480"
    style="enable-background: new 0 0 240 480"
    xml:space="preserve"
  >
    <g>
      <g>
        <g>
          <g>
            <linearGradient
              id="SVGID_1_"
              gradientUnits="userSpaceOnUse"
              x1="-57.3746"
              y1="161.4906"
              x2="-57.3746"
              y2="162.4906"
              gradientTransform="matrix(458.587 0 0 160 26551.2539 -25678.5)"
            >
              <stop offset="0" style="stop-color: #81c3f3" />
              <stop offset="1" style="stop-color: #157efb" />
            </linearGradient>
            <path
              class="st0"
              d="M469.3,200c-13.8-23.9-39.7-40-69.3-40c-37.6,0-69.1,25.9-77.7,60.8c-2.8,11.3-13.1,19.2-24.8,19.2h-115
					c-11.7,0-22,7.9-24.8,19.2C149.1,294.1,117.6,320,80,320c-29.6,0-55.5-16.1-69.3-40"
            />

            <linearGradient
              id="SVGID_2_"
              gradientUnits="userSpaceOnUse"
              x1="-57.1148"
              y1="162.9812"
              x2="-57.1148"
              y2="163.9812"
              gradientTransform="matrix(149.294 0 0 80 8601.5527 -12878.5)"
            >
              <stop offset="0" style="stop-color: #81c3f3" />
              <stop offset="1" style="stop-color: #157efb" />
            </linearGradient>
            <path
              class="st1"
              d="M0,240c0-44.2,35.8-80,80-80c29.6,0,55.5,16.1,69.3,40"
            />
            <g>
              <linearGradient
                id="SVGID_3_"
                gradientUnits="userSpaceOnUse"
                x1="-56.6014"
                y1="163.724"
                x2="-56.6014"
                y2="164.724"
                gradientTransform="matrix(64.043 0 0 64.044 3672.624 -10245.4854)"
              >
                <stop offset="0" style="stop-color: #81c3f3" />
                <stop offset="1" style="stop-color: #157efb" />
              </linearGradient>
              <path class="st2" d="M79.7,304.1c-35.4,0-64-28.7-64-64" />

              <linearGradient
                id="SVGID_4_"
                gradientUnits="userSpaceOnUse"
                x1="-56.6021"
                y1="163.724"
                x2="-56.6021"
                y2="164.724"
                gradientTransform="matrix(64.043 0 0 64.043 3736.667 -10309.3691)"
              >
                <stop offset="0" style="stop-color: #81c3f3" />
                <stop offset="1" style="stop-color: #157efb" />
              </linearGradient>
              <path class="st3" d="M79.7,176c35.4,0,64,28.7,64,64" />
            </g>
          </g>
        </g>
      </g>
    </g>
    <text transform="matrix(1 0 0 1 45.6998 270.3605)" class="st5">
      {{ num >= 10 ? num : `0${num}` }}
    </text>
  </svg>
</template>

<script>
export default {
  props: ["num"],
};
</script>

<style lang="scss">
#routestart {
  .st0 {
    fill: none;
    stroke: url(#SVGID_1_);
    stroke-linecap: round;
  }
  .st1 {
    fill: none;
    stroke: url(#SVGID_2_);
    stroke-linecap: round;
  }
  .st2 {
    fill: none;
    stroke: url(#SVGID_3_);
    stroke-linecap: round;
  }
  .st3 {
    fill: none;
    stroke: url(#SVGID_4_);
    stroke-linecap: round;
  }
  .st5 {
    fill: #81c3f3;
    font-size: 80px;
    font-family: "MicrosoftJhengHeiLight";
  }
}
</style>