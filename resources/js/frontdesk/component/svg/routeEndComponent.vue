<template>
  <svg
    version="1.1"
    id="routeEnd"
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
              x1="-56.9989"
              y1="159.9968"
              x2="-56.9989"
              y2="160.9968"
              gradientTransform="matrix(-458.587 1.498639e-04 -5.228719e-05 -160 -26138.9512 25919.5078)"
            >
              <stop offset="0" style="stop-color: #81c3f3" />
              <stop offset="1" style="stop-color: #157efb" />
            </linearGradient>
            <path
              class="st0"
              d="M-229.3,280c13.8,23.9,39.7,40,69.3,40c37.6,0,69.1-25.9,77.7-60.8c2.8-11.3,13.1-19.2,24.8-19.2H57.6
					c11.7,0,22-7.9,24.8-19.2c8.5-34.9,40-60.8,77.6-60.8c29.6,0,55.5,16.1,69.3,40"
            />

            <linearGradient
              id="SVGID_2_"
              gradientUnits="userSpaceOnUse"
              x1="-55.9608"
              y1="159.9937"
              x2="-55.9608"
              y2="160.9937"
              gradientTransform="matrix(-149.294 4.878852e-05 -2.614359e-05 -80 -8189.2544 13119.5029)"
            >
              <stop offset="0" style="stop-color: #81c3f3" />
              <stop offset="1" style="stop-color: #157efb" />
            </linearGradient>
            <path
              class="st1"
              d="M240,240c0,44.2-35.8,80-80,80c-29.6,0-55.5-16.1-69.3-40"
            />
            <g>
              <linearGradient
                id="SVGID_3_"
                gradientUnits="userSpaceOnUse"
                x1="-53.9111"
                y1="159.9922"
                x2="-53.9111"
                y2="160.9922"
                gradientTransform="matrix(-64.043 2.092892e-05 -2.092925e-05 -64.044 -3260.3267 10486.4863)"
              >
                <stop offset="0" style="stop-color: #81c3f3" />
                <stop offset="1" style="stop-color: #157efb" />
              </linearGradient>
              <path class="st2" d="M160.3,175.9c35.4,0,64,28.7,64,64" />

              <linearGradient
                id="SVGID_4_"
                gradientUnits="userSpaceOnUse"
                x1="-53.9118"
                y1="159.9922"
                x2="-53.9118"
                y2="160.9922"
                gradientTransform="matrix(-64.043 2.092892e-05 -2.092892e-05 -64.043 -3324.3696 10550.3711)"
              >
                <stop offset="0" style="stop-color: #81c3f3" />
                <stop offset="1" style="stop-color: #157efb" />
              </linearGradient>
              <path class="st3" d="M160.3,304c-35.4,0-64-28.7-64-64" />
            </g>
          </g>
        </g>
      </g>
    </g>
    <text transform="matrix(1 0 0 1 127.8278 265.215)" class="st5">
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
#routeEnd {
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