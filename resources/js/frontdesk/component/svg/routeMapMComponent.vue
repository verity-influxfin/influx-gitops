<template>
  <div class="routeM-map" v-if="showData.length !== 0">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      id="r-map"
      viewBox="0 0 293 474"
    >
      <defs>
        <linearGradient
          id="ckqa1kzdea"
          x1=".014%"
          x2="100.009%"
          y1="50.003%"
          y2="50.003%"
        >
          <stop offset="0%" stop-color="#45E3FF" />
          <stop offset="100%" stop-color="#9BABFF" />
        </linearGradient>
        <linearGradient id="whby063ocb" x1="50%" x2="50%" y1="-.217%" y2="100%">
          <stop offset="0%" stop-color="#009DFF" />
          <stop offset="100%" stop-color="#3656FF" />
        </linearGradient>
        <linearGradient id="zt8pdeip9c" x1="100.739%" x2="-.739%" y1="50%" y2="50%">
          <stop offset="0%" stop-color="#00D9FF" />
          <stop offset="100%" stop-color="#768CFF" />
        </linearGradient>
        <linearGradient id="j7yt7fi9wf" x1="85.1%" x2="26.85%" y1="14.9%" y2="73.15%">
          <stop offset="0%" stop-color="#E2E1E6" />
          <stop offset="100%" stop-color="#FFF" />
        </linearGradient>
        <linearGradient
          id="wyig6z72sg"
          x1="-1032.886%"
          x2="-1032.886%"
          y1="-2748.27%"
          y2="-2847.231%"
        >
          <stop offset="0%" stop-color="#009DFF" />
          <stop offset="100%" stop-color="#3656FF" />
        </linearGradient>
        <linearGradient
          id="t7p3mpxn2h"
          x1="-185.964%"
          x2="-84.485%"
          y1="-186.02%"
          y2="-186.02%"
        >
          <stop offset="0%" stop-color="#00D9FF" />
          <stop offset="100%" stop-color="#768CFF" />
        </linearGradient>
        <linearGradient
          id="kzx5ih3dyk"
          x1="1565.864%"
          x2="1565.864%"
          y1="-3871.813%"
          y2="-3971.813%"
        >
          <stop offset="0%" stop-color="#009DFF" />
          <stop offset="100%" stop-color="#3656FF" />
        </linearGradient>
        <linearGradient
          id="p333b7rccl"
          x1="315.701%"
          x2="417.15%"
          y1="-436.304%"
          y2="-436.304%"
        >
          <stop offset="0%" stop-color="#00D9FF" />
          <stop offset="100%" stop-color="#768CFF" />
        </linearGradient>
        <linearGradient
          id="a2cr6g8qro"
          x1="-1049.387%"
          x2="-1049.387%"
          y1="-4002.196%"
          y2="-4102.349%"
        >
          <stop offset="0%" stop-color="#009DFF" />
          <stop offset="100%" stop-color="#3656FF" />
        </linearGradient>
        <linearGradient
          id="04hteoa98p"
          x1="-317.367%"
          x2="-215.858%"
          y1="-695.828%"
          y2="-695.828%"
        >
          <stop offset="0%" stop-color="#00D9FF" />
          <stop offset="100%" stop-color="#768CFF" />
        </linearGradient>
        <linearGradient id="bx8r6uazts" x1="100%" x2="0%" y1="50%" y2="50%">
          <stop offset="0%" stop-color="#81C3F3" />
          <stop offset="100%" stop-color="#157EFB" />
        </linearGradient>
        <linearGradient id="0qw06001nt" x1="100%" x2="0%" y1="50%" y2="50%">
          <stop offset="0%" stop-color="#157EFB" />
          <stop offset="100%" stop-color="#81C3F3" />
        </linearGradient>
        <linearGradient id="khnazndmju" x1="100%" x2="0%" y1="50%" y2="50%">
          <stop offset="0%" stop-color="#157EFB" />
          <stop offset="100%" stop-color="#81C3F3" />
        </linearGradient>
        <filter
          id="gu87ttr6nd"
          width="107.4%"
          height="107.4%"
          x="-1.9%"
          y="-1.9%"
          filterUnits="objectBoundingBox"
        >
          <feOffset dx="1" dy="1" in="SourceAlpha" result="shadowOffsetOuter1" />
          <feGaussianBlur
            in="shadowOffsetOuter1"
            result="shadowBlurOuter1"
            stdDeviation=".5"
          />
          <feColorMatrix
            in="shadowBlurOuter1"
            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.5 0"
          />
        </filter>
        <filter
          id="9ki3n9a5ri"
          width="113.9%"
          height="113.9%"
          x="-3.5%"
          y="-3.5%"
          filterUnits="objectBoundingBox"
        >
          <feOffset dx="2" dy="2" in="SourceAlpha" result="shadowOffsetOuter1" />
          <feGaussianBlur
            in="shadowOffsetOuter1"
            result="shadowBlurOuter1"
            stdDeviation="1"
          />
          <feColorMatrix
            in="shadowBlurOuter1"
            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.5 0"
          />
        </filter>
        <filter
          id="qj1245923m"
          width="119.4%"
          height="119.4%"
          x="-4.9%"
          y="-4.9%"
          filterUnits="objectBoundingBox"
        >
          <feOffset dx="3" dy="3" in="SourceAlpha" result="shadowOffsetOuter1" />
          <feGaussianBlur
            in="shadowOffsetOuter1"
            result="shadowBlurOuter1"
            stdDeviation="1.5"
          />
          <feColorMatrix
            in="shadowBlurOuter1"
            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.5 0"
          />
        </filter>
        <filter
          id="j9otqw9bvq"
          width="125.1%"
          height="125.1%"
          x="-6.3%"
          y="-6.3%"
          filterUnits="objectBoundingBox"
        >
          <feOffset dx="4" dy="4" in="SourceAlpha" result="shadowOffsetOuter1" />
          <feGaussianBlur
            in="shadowOffsetOuter1"
            result="shadowBlurOuter1"
            stdDeviation="2"
          />
          <feColorMatrix
            in="shadowBlurOuter1"
            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.5 0"
          />
        </filter>
        <circle id="b0t46ki87e" cx="39.42" cy="34.32" r="26.88" />
        <circle id="4caps4svnj" cx="207.61" cy="154" r="28.88" />
        <circle id="eqhjwpex1n" cx="37.98" cy="265.58" r="30.88" />
        <circle id="48iv2f2x9r" cx="252.02" cy="382.39" r="31.88" />
      </defs>
      <g fill="none" fill-rule="evenodd">
        <g>
          <g>
            <g transform="translate(-4 -13) translate(5 13)">
              <path
                fill="url(#ckqa1kzdea)"
                fill-rule="nonzero"
                d="M250.19 459.73c-13.779-3.35-27.352-7.497-40.65-12.42-12-4.33-24.59-9.37-39.7-15.88-7.59-3.32-13.79-6.11-19.5-8.8-2.12-1-4.24-1.95-6.35-2.9l-3.45-1.56-9.72-4.53c-14-6.64-26.36-13.09-37.87-19.72-10.9-6.39-23.55-14.24-34.78-24.05-2.616-2.308-5.113-4.748-7.48-7.31-.92-1-1.75-2-2.63-3l-.67-.79-.57-.77c-.85-1.14-1.66-2.22-2.37-3.4-1.813-2.713-3.184-5.697-4.06-8.84-.855-2.974-.934-6.117-.23-9.13.778-3.04 2.242-5.863 4.28-8.25 2.143-2.537 4.614-4.777 7.35-6.66 5.728-3.884 11.928-7.023 18.45-9.34 6.584-2.435 13.34-4.38 20.21-5.82l1.28-.26c.36-.06.67-.14 1-.22l3.07-.75c1.77-.43 3.53-.87 5.25-1.33 3.5-.88 7-1.85 10.45-2.78 13.931-3.805 27.622-8.44 41-13.88 6.716-2.767 13.272-5.906 19.64-9.4 6.445-3.5 12.543-7.605 18.21-12.26 6.45-5.46 11.15-11 14.37-17 1.806-3.36 3.116-6.964 3.89-10.7.753-3.755.928-7.602.52-11.41-.949-7.46-3.504-14.624-7.49-21-3.81-6.266-8.297-12.094-13.38-17.38-8.74-9.29-19.26-18.19-33.17-27.95-10.92-7.66-22.9-15.13-36.63-22.85-12.07-6.77-24.6-13.25-38.29-19.8-14.15-6.74-27-12.43-39.43-17.4L39.61 103c12.29 5 25.08 10.79 39.12 17.6 13.39 6.52 26.15 13.24 37.94 20 13.56 7.75 25.39 15.24 36.15 22.9 13.58 9.73 23.86 18.51 32.33 27.6 4.83 5.094 9.089 10.7 12.7 16.72 3.605 5.832 5.904 12.375 6.74 19.18.339 3.34.164 6.713-.52 10-.71 3.285-1.888 6.451-3.5 9.4-3.429 5.97-7.959 11.237-13.35 15.52-5.45 4.414-11.31 8.295-17.5 11.59-6.229 3.353-12.639 6.357-19.2 9-13.224 5.269-26.756 9.73-40.52 13.36-3.36.9-6.84 1.82-10.37 2.69-1.72.44-3.45.85-5.17 1.26l-3.06.73c-.25.06-.5.12-.74.16l-1.36.26c-7.17 1.446-14.224 3.424-21.1 5.92-7.022 2.43-13.701 5.757-19.87 9.9-3.188 2.169-6.07 4.755-8.57 7.69-2.645 3.072-4.554 6.708-5.58 10.63-.961 4.004-.903 8.185.17 12.16 1.017 3.765 2.616 7.349 4.74 10.62.83 1.39 1.78 2.68 2.69 3.92l1.27 1.62c.93 1.16 1.9 2.35 2.92 3.43 2.49 2.74 5.125 5.347 7.89 7.81 11.62 10.33 24.55 18.44 35.68 25.08 11.65 6.81 24.15 13.44 38.24 20.26l9.75 4.63 3.45 1.59c2.1 1 4.2 1.93 6.26 2.92 5.78 2.77 12 5.64 19.64 9 15.2 6.68 27.92 11.88 40 16.35 13.558 5.129 27.401 9.47 41.46 13l1.48.35 1.81-7.8-1.44-.32z"
                opacity=".2"
              />
              <circle
                cx="39.42"
                cy="101.58"
                r="4.61"
                fill="url(#whby063ocb)"
                fill-rule="nonzero"
              />
              <path
                stroke="url(#zt8pdeip9c)"
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M5.61 34.4h0C5.61 15.727 20.747.59 39.42.59h0c18.673 0 33.81 15.137 33.81 33.81h0c-.003 15.54-10.596 29.08-25.68 32.82-4.725 1.113-8.08 5.306-8.13 10.16v14.77"
                transform="matrix(-1 0 0 1 78.84 0)"
              />
              <g fill-rule="nonzero">
                <use fill="#000" filter="url(#gu87ttr6nd)" xlink:href="#b0t46ki87e" />
                <use fill="url(#j7yt7fi9wf)" xlink:href="#b0t46ki87e" />
              </g>
              <path
                fill="url(#wyig6z72sg)"
                fill-rule="nonzero"
                d="M213.35 226.65c-.022 3.184-2.615 5.751-5.8 5.74-3.184-.011-5.76-2.596-5.76-5.78s2.576-5.769 5.76-5.78c3.185-.011 5.778 2.556 5.8 5.74v.08z"
              />
              <path
                stroke="url(#t7p3mpxn2h)"
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M171.81 154.08h0c-.003-9.496 3.768-18.605 10.482-25.32 6.714-6.717 15.821-10.49 25.318-10.49h0c19.777 0 35.81 16.033 35.81 35.81h0c-.006 16.462-11.224 30.803-27.2 34.773-5.004 1.17-8.56 5.61-8.61 10.75v15.644"
              />
              <g fill-rule="nonzero">
                <use fill="#000" filter="url(#9ki3n9a5ri)" xlink:href="#4caps4svnj" />
                <use fill="url(#j7yt7fi9wf)" xlink:href="#4caps4svnj" />
              </g>
              <circle
                cx="37.98"
                cy="342.39"
                r="7.06"
                fill="url(#kzx5ih3dyk)"
                fill-rule="nonzero"
              />
              <path
                stroke="url(#p333b7rccl)"
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M.17 265.659h0c0-10.032 3.986-19.652 11.082-26.743 7.095-7.091 16.718-11.072 26.75-11.066h0c10.027 0 19.644 3.983 26.734 11.074 7.09 7.09 11.074 16.707 11.074 26.735h0c-.01 17.39-11.87 32.535-28.75 36.713-5.273 1.254-9.01 5.942-9.059 11.361v16.506"
                transform="matrix(-1 0 0 1 75.98 0)"
              />
              <g fill-rule="nonzero">
                <use fill="#000" filter="url(#qj1245923m)" xlink:href="#eqhjwpex1n" />
                <use fill="url(#j7yt7fi9wf)" xlink:href="#eqhjwpex1n" />
              </g>
              <circle
                cx="252.63"
                cy="463.62"
                r="9.79"
                fill="url(#a2cr6g8qro)"
                fill-rule="nonzero"
              />
              <path
                stroke="url(#04hteoa98p)"
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M213.21 382.471h0c0-21.426 17.362-38.798 38.789-38.811h0c10.293 0 20.165 4.09 27.443 11.368 7.279 7.278 11.368 17.15 11.368 27.443h0c-.002 17.843-12.162 33.389-29.479 37.687-5.426 1.274-9.28 6.09-9.332 11.663v16.943"
              />
              <g fill-rule="nonzero">
                <use fill="#000" filter="url(#j9otqw9bvq)" xlink:href="#48iv2f2x9r" />
                <use fill="url(#j7yt7fi9wf)" xlink:href="#48iv2f2x9r" />
              </g>
            </g>
            <g>
              <path
                stroke="url(#khnazndmju)"
                d="M1 25L146 25"
                transform="translate(-4 -13) translate(5 13) translate(57 358)"
              />
              <text
                fill="#157EFB"
                font-family="NotoSansTC-Light, Noto Sans TC"
                font-size="12"
                font-weight="300"
                transform="translate(-4 -13) translate(5 13) translate(83 16)"
              >
                <tspan x="83.24" y="14">{{ showData[0].hook_date.substr(0, 7) }}</tspan>
              </text>
              <text
                fill="#157EFB"
                font-family="NotoSansTC-Medium, Noto Sans TC"
                font-size="12"
                font-weight="400"
                transform="translate(-4 -13) translate(5 13) translate(83 16)"
              >
                <tspan x="0" y="14">{{ showData[0].title }}</tspan>
              </text>
              <image
                transform="matrix(1 0 0 1 15.6998 10.3605)"
                :href="showData[0].icon"
                height="50"
                width="50"
              ></image>
            </g>
            <g>
              <path
                stroke="url(#bx8r6uazts)"
                d="M1 20L126 20"
                transform="translate(-4 -13) translate(5 13) translate(85 246)"
              />
              <text
                fill="#157EFB"
                font-family="NotoSansTC-Light, Noto Sans TC"
                font-size="12"
                font-weight="300"
                transform="translate(-4 -13) translate(5 13) translate(35 134)"
              >
                <tspan x="1.24" y="16">{{ showData[1].hook_date.substr(0, 7) }}</tspan>
              </text>
              <text
                fill="#157EFB"
                font-family="NotoSansTC-Medium, Noto Sans TC"
                font-size="13"
                font-weight="400"
                transform="translate(-4 -13) translate(5 13) translate(35 134)"
              >
                <tspan x="50" y="15">{{ showData[1].title }}</tspan>
              </text>
              <image
                transform="matrix(1 0 0 1 6.6998 232.3605)"
                :href="showData[1].icon"
                height="64"
                width="64"
              ></image>
            </g>
            <g>
              <path
                stroke="url(#0qw06001nt)"
                d="M1 20L126 20"
                transform="translate(-4 -13) translate(5 13) translate(35 134)"
              />
              <image
                transform="matrix(1 0 0 1 179.6998 125.3605)"
                :href="showData[2].icon"
                height="58"
                width="58"
              ></image>
            </g>
            <g>
              <path
                stroke="url(#bx8r6uazts)"
                d="M1 18L126 18"
                transform="translate(-4 -13) translate(5 13) translate(83 16)"
              />
              <text
                fill="#157EFB"
                font-family="NotoSansTC-Light, Noto Sans TC"
                font-size="12"
                font-weight="300"
                transform="translate(-4 -13) translate(5 13) translate(85 246)"
              >
                <tspan x="83.24" y="16">{{ showData[2].hook_date.substr(0, 7) }}</tspan>
              </text>
              <text
                fill="#157EFB"
                font-family="NotoSansTC-Medium, Noto Sans TC"
                font-size="13"
                font-weight="400"
                transform="translate(-4 -13) translate(5 13) translate(85 246)"
              >
                <tspan x="1" y="15">{{ showData[2].title }}</tspan>
              </text>
              <text
                fill="#157EFB"
                font-family="NotoSansTC-Light, Noto Sans TC"
                font-size="12"
                font-weight="300"
                transform="translate(-4 -13) translate(5 13) translate(57 358)"
              >
                <tspan x="1.24" y="18">{{ showData[3].hook_date.substr(0, 7) }}</tspan>
              </text>
              <text
                fill="#157EFB"
                font-family="NotoSansTC-Medium, Noto Sans TC"
                font-size="14"
                font-weight="400"
                transform="translate(-4 -13) translate(5 13) translate(57 358)"
              >
                <tspan x="51" y="16">{{ showData[3].title }}</tspan>
              </text>
              <image
                transform="matrix(1 0 0 1 221.6998 351.3605)"
                :href="showData[3].icon"
                height="63"
                width="63"
              ></image>
            </g>
          </g>
        </g>
      </g>
    </svg>
    <div class="route-text">{{ showData[0].content }}</div>
    <div class="route-text">{{ showData[1].content }}</div>
    <div class="route-text">{{ showData[2].content }}</div>
    <div class="route-text">{{ showData[3].content }}</div>
  </div>
</template>

<script>
export default {
  props: ["routeData"],
  data: () => ({
    showData: [],
  }),
  watch: {
    routeData(newVal) {
      this.showData = newVal;
      console.log(this.showData);
    },
  },
};
</script>

<style lang="scss">
.routeM-map {
  position: relative;
  width: 100%;

  #r-map {
    width: 100%;
  }

  text {
    user-select: none;
  }

  .route-text {
    position: absolute;
    width: 185px;
    color: #007eff;
    font-size: 12px;
    user-select: none;

    &:nth-of-type(1) {
      top: 8%;
      left: 29%;
    }
    &:nth-of-type(2) {
      top: 33%;
      left: 12.5%;
    }
    &:nth-of-type(3) {
      top: 56.5%;
      left: 29.5%;
    }
    &:nth-of-type(4) {
      top: 81%;
      left: 20%;
    }
  }
}
</style>
