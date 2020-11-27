<template>
  <svg
    version="1.1"
    id="routeDot"
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
              x1="-11.036"
              y1="162.4844"
              x2="-11.036"
              y2="163.4844"
              gradientTransform="matrix(778.587 0 0 160 8712.4561 -25837.5)"
            >
              <stop offset="0" style="stop-color: #81c3f3" />
              <stop offset="1" style="stop-color: #157efb" />
            </linearGradient>
            <path
              class="st0"
              d="M509.3,200c-13.8-23.9-39.7-40-69.3-40c-37.6,0-69.1,25.9-77.7,60.8c-2.8,11.3-13.1,19.2-24.8,19.2h-115
					c-11.7,0-22,7.9-24.8,19.2c-8.6,34.9-40.1,60.8-77.7,60.8c-29.6,0-55.5-16.1-69.3-40 M189.3,200c-13.8-23.9-39.7-40-69.3-40
					c-37.6,0-69.1,25.9-77.7,60.8C39.5,232.1,29.2,240,17.5,240h-115c-11.7,0-22,7.9-24.8,19.2c-8.6,34.9-40.1,60.8-77.7,60.8
					c-29.6,0-55.5-16.1-69.3-40"
            />
            <g>
              <linearGradient
                id="SVGID_2_"
                gradientUnits="userSpaceOnUse"
                x1="-11.491"
                y1="167.8032"
                x2="-11.491"
                y2="168.8032"
                gradientTransform="matrix(57.05 0 0 34.91 803.7119 -5682)"
              >
                <stop offset="0" style="stop-color: #81c3f3" />
                <stop offset="1" style="stop-color: #157efb" />
              </linearGradient>
              <path class="st1" d="M119.6,176c24.9,0,46.4,14.2,57.1,34.9" />

              <linearGradient
                id="SVGID_3_"
                gradientUnits="userSpaceOnUse"
                x1="-11.2396"
                y1="164.7084"
                x2="-11.2396"
                y2="165.7084"
                gradientTransform="matrix(117.149 0 0 64.044 1430.8075 -10308.5293)"
              >
                <stop offset="0" style="stop-color: #81c3f3" />
                <stop offset="1" style="stop-color: #157efb" />
              </linearGradient>
              <path
                class="st2"
                d="M172.7,275.9c-11.5,17-31,28.2-53.1,28.2c-23.5,0-44-12.6-55.2-31.5c-5.6-9.5-8.9-20.7-8.9-32.6"
              />
            </g>
          </g>
        </g>
      </g>
    </g>
    <text transform="matrix(1 0 0 1 82.2069 265.215)" class="st5">
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
#routeDot {
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
  .st5 {
    fill: #81c3f3;
    font-size: 80px;
    font-family: "MicrosoftJhengHeiLight";
  }
}
</style>