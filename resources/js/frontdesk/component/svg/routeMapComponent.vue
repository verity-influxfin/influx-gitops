<template>
  <div class="route-map" v-if="showData.length !== 0">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      xmlns:xlink="http://www.w3.org/1999/xlink"
      id="r-map"
      viewBox="0 0 1297 679"
    >
      <defs>
        <linearGradient id="kjndxijg4a" x1="-.639%" x2="99.968%" y1="50%" y2="50%">
          <stop offset="0%" stop-color="#45E3FF" />
          <stop offset="100%" stop-color="#9BABFF" />
        </linearGradient>
        <linearGradient id="t6ouanhq5b" x1="-.191%" x2="100.191%" y1="50%" y2="50%">
          <stop offset="0%" stop-color="#00D9FF" />
          <stop offset="100%" stop-color="#768CFF" />
        </linearGradient>
        <linearGradient id="ghpo2gedgf" x1="0%" x2="84.26%" y1="0%" y2="85.796%">
          <stop offset="0%" stop-color="#E2E1E6" />
          <stop offset="100%" stop-color="#FFF" />
        </linearGradient>
        <linearGradient
          id="2vkn5c348g"
          x1="16.543%"
          x2="87.509%"
          y1="13.656%"
          y2="84.622%"
        >
          <stop offset="0%" stop-color="#FFF" />
          <stop offset="100%" stop-color="#E6E6E6" />
        </linearGradient>
        <linearGradient
          id="s0u1kli4dh"
          x1="-.307%"
          x2="100.307%"
          y1="211.894%"
          y2="211.894%"
        >
          <stop offset="0%" stop-color="#00D9FF" />
          <stop offset="100%" stop-color="#768CFF" />
        </linearGradient>
        <linearGradient id="udqubgcr4k" x1="14.946%" x2="84.26%" y1="14.938%" y2="84.26%">
          <stop offset="0%" stop-color="#E2E1E6" />
          <stop offset="84%" stop-color="#FFF" />
        </linearGradient>
        <linearGradient
          id="lscx884nrl"
          x1="-448.846%"
          x2="-348.901%"
          y1="413.713%"
          y2="413.713%"
        >
          <stop offset="0%" stop-color="#FFF" />
          <stop offset="100%" stop-color="#E6E6E6" />
        </linearGradient>
        <linearGradient
          id="9u73cfm28m"
          x1="-.385%"
          x2="100.385%"
          y1="255.925%"
          y2="255.925%"
        >
          <stop offset="0%" stop-color="#00D9FF" />
          <stop offset="100%" stop-color="#768CFF" />
        </linearGradient>
        <linearGradient
          id="snkdd7kjop"
          x1="14.923%"
          x2="84.294%"
          y1="14.923%"
          y2="84.294%"
        >
          <stop offset="0%" stop-color="#E2E1E6" />
          <stop offset="84%" stop-color="#FFF" />
        </linearGradient>
        <linearGradient
          id="0j1h3gr64q"
          x1="-717.564%"
          x2="-617.594%"
          y1="880.397%"
          y2="880.397%"
        >
          <stop offset="0%" stop-color="#FFF" />
          <stop offset="100%" stop-color="#E6E6E6" />
        </linearGradient>
        <linearGradient
          id="y0ld2gdr1r"
          x1="-.557%"
          x2="100.557%"
          y1="398.684%"
          y2="398.684%"
        >
          <stop offset="0%" stop-color="#00D9FF" />
          <stop offset="100%" stop-color="#768CFF" />
        </linearGradient>
        <linearGradient
          id="3x3bu5itxu"
          x1="14.916%"
          x2="84.258%"
          y1="14.902%"
          y2="84.244%"
        >
          <stop offset="0%" stop-color="#E2E1E6" />
          <stop offset="84%" stop-color="#FFF" />
        </linearGradient>
        <linearGradient
          id="70l9q7j3fv"
          x1="-841.391%"
          x2="-741.46%"
          y1="774.298%"
          y2="774.298%"
        >
          <stop offset="0%" stop-color="#FFF" />
          <stop offset="100%" stop-color="#E6E6E6" />
        </linearGradient>
        <linearGradient
          id="j943o6bcnw"
          x1="-.729%"
          x2="100.729%"
          y1="538.654%"
          y2="538.654%"
        >
          <stop offset="0%" stop-color="#00D9FF" />
          <stop offset="100%" stop-color="#768CFF" />
        </linearGradient>
        <linearGradient id="1eueyvhzkz" x1="15.018%" x2="84.286%" y1="15%" y2="84.286%">
          <stop offset="0%" stop-color="#E2E1E6" />
          <stop offset="84%" stop-color="#FFF" />
        </linearGradient>
        <linearGradient
          id="s5b28l0zcA"
          x1="-1251.173%"
          x2="-1151.209%"
          y1="1357.69%"
          y2="1357.69%"
        >
          <stop offset="0%" stop-color="#FFF" />
          <stop offset="100%" stop-color="#E6E6E6" />
        </linearGradient>
        <linearGradient
          id="mtfpsj0t4B"
          x1="-.913%"
          x2="101.162%"
          y1="699.908%"
          y2="699.908%"
        >
          <stop offset="0%" stop-color="#00D9FF" />
          <stop offset="100%" stop-color="#768CFF" />
        </linearGradient>
        <linearGradient
          id="qet05neh6E"
          x1="15.052%"
          x2="84.271%"
          y1="15.052%"
          y2="84.245%"
        >
          <stop offset="0%" stop-color="#E2E1E6" />
          <stop offset="84%" stop-color="#FFF" />
        </linearGradient>
        <linearGradient
          id="5me9k6qvqF"
          x1="-1614.026%"
          x2="-1514.231%"
          y1="1513.846%"
          y2="1513.846%"
        >
          <stop offset="0%" stop-color="#FFF" />
          <stop offset="100%" stop-color="#E6E6E6" />
        </linearGradient>
        <filter
          id="soytwc58cd"
          width="111.5%"
          height="111.5%"
          x="-2.9%"
          y="-2.9%"
          filterUnits="objectBoundingBox"
        >
          <feOffset dx="6" dy="6" in="SourceAlpha" result="shadowOffsetOuter1" />
          <feGaussianBlur
            in="shadowOffsetOuter1"
            result="shadowBlurOuter1"
            stdDeviation="3"
          />
          <feColorMatrix
            in="shadowBlurOuter1"
            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.5 0"
          />
        </filter>
        <filter
          id="uq4yohspzi"
          width="115.4%"
          height="115.4%"
          x="-3.9%"
          y="-3.9%"
          filterUnits="objectBoundingBox"
        >
          <feOffset dx="5" dy="5" in="SourceAlpha" result="shadowOffsetOuter1" />
          <feGaussianBlur
            in="shadowOffsetOuter1"
            result="shadowBlurOuter1"
            stdDeviation="2.5"
          />
          <feColorMatrix
            in="shadowBlurOuter1"
            values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.5 0"
          />
        </filter>
        <filter
          id="6pa52qxxyn"
          width="115.5%"
          height="115.5%"
          x="-3.9%"
          y="-3.9%"
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
        <filter
          id="9p4a25hwms"
          width="116.8%"
          height="116.8%"
          x="-4.2%"
          y="-4.2%"
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
          id="qs7rml46bx"
          width="114.7%"
          height="114.7%"
          x="-3.7%"
          y="-3.7%"
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
          id="12jsmvf70C"
          width="110.4%"
          height="110.4%"
          x="-2.6%"
          y="-2.6%"
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
        <circle id="n0deypsuze" cx="1106.598" cy="312.466" r="104.3" />
        <circle id="sdh7hmrukj" cx="513.2" cy="215.2" r="64.9" />
        <circle id="lk94gm7e3o" cx="778.8" cy="94.4" r="51.7" />
        <circle id="mpf8gsr6it" cx="295.7" cy="97.9" r="35.7" />
        <circle id="j9176b62my" cx="422.8" cy="34.1" r="27.3" />
        <circle id="2t1ygffdhD" cx="181" cy="24" r="19.2" />
        <radialGradient id="w8u0kg9trc" cx="50%" cy="0%" r="100%" fx="50%" fy="0%">
          <stop offset="0%" stop-color="#81C3F3" />
          <stop offset="100%" stop-color="#157EFB" />
        </radialGradient>
      </defs>
      <g fill="none" fill-rule="evenodd">
        <g>
          <g transform="translate(-118 -68) translate(118 68.7)">
            <path
              fill="url(#kjndxijg4a)"
              fill-rule="nonzero"
              d="M180.6 71.6c19.9 0 39.8.233 59.7.7 19.9.467 39.767 1.133 59.6 2 19.9 1 39.8 2.2 59.6 4.2 9.9 1 19.8 2.2 29.7 3.8 4.9.8 9.9 1.8 14.8 3 2.5.6 4.9 1.3 7.3 2.1 2.475.81 4.882 1.813 7.2 3 1.25.672 2.423 1.477 3.5 2.4 1.33 1.09 2.304 2.553 2.8 4.2.116.492.183.995.2 1.5v1.1c-.1.7-.2 1.5-.3 2.2-.305 1.43-.81 2.81-1.5 4.1-1.288 2.407-2.94 4.6-4.9 6.5-1.844 1.837-3.815 3.541-5.9 5.1-4.094 3.015-8.406 5.722-12.9 8.1-4.4 2.4-8.9 4.6-13.4 6.8-18.1 8.5-36.6 15.8-54 25-4.356 2.25-8.594 4.72-12.7 7.4-4.09 2.537-7.91 5.486-11.4 8.8-3.4 3.2-6.3 6.9-7.7 11-.696 1.99-1.002 4.095-.9 6.2.017.538.084 1.074.2 1.6l.3 1.2c.334.834.846 1.585 1.5 2.2 1.513 1.49 3.272 2.708 5.2 3.6 2.117.987 4.287 1.855 6.5 2.6 4.633 1.37 9.34 2.471 14.1 3.3 9.6 1.6 19.5 2.6 29.3 3.3 19.7 1.4 39.6 1.8 59.4 1.9 19.8.1 39.7.1 59.6-.1 39.8-.3 79.5-.8 119.4-.2 19.9.3 39.8.9 59.8 2.1 20 1.2 39.9 2.9 59.7 6.2 9.999 1.602 19.886 3.84 29.6 6.7 4.948 1.474 9.793 3.278 14.5 5.4 4.888 2.156 9.49 4.91 13.7 8.2 4.568 3.51 8.122 8.168 10.3 13.5 1.022 2.789 1.563 5.73 1.6 8.7.037 8.3-2.174 16.456-6.4 23.6-2.712 4.628-6.08 8.838-10 12.5-7.6 7.4-16.4 13-25.3 17.8-8.986 4.695-18.234 8.869-27.7 12.5-18.8 7.3-37.9 13.1-56.9 18.9-19 5.8-38.1 11.4-56.7 17.8-9.3 3.2-18.5 6.7-27.5 10.5-8.926 3.77-17.551 8.216-25.8 13.3-8.1 5.1-15.5 11.1-21 18.3-5.53 6.98-8.59 15.596-8.7 24.5.004 9.256 1.91 18.412 5.6 26.9 3.837 8.557 9 16.454 15.3 23.4 6.459 7.084 13.63 13.485 21.4 19.1 3.9 2.9 7.9 5.6 12 8.2 4.1 2.6 8.3 5 12.6 7.4 8.5 4.7 17.3 9 26.3 12.9 9 3.9 18.1 7.6 27.3 11 18.5 6.8 37.3 12.5 56.4 17.7 19.1 5.2 38.3 9.6 57.7 13.7 38.7 8 77.9 14.3 117.2 19.4 39.3 5.1 78.8 9.1 118.3 12.2 19.8 1.5 39.5 2.9 59.4 4s39.6 2 59.3 2.5h.1c2.572.071 4.911 1.51 6.135 3.773 1.225 2.264 1.148 5.008-.2 7.2-1.348 2.192-3.763 3.498-6.335 3.43-20.1-.603-39.9-1.703-59.8-2.903s-39.8-2.7-59.6-4.4c-39.7-3.4-79.4-7.6-118.9-13s-78.9-11.9-118-20.3c-19.5-4.2-39-8.8-58.3-14.2-19.3-5.4-38.5-11.3-57.3-18.3-9.4-3.5-18.8-7.3-28-11.4-9.2-4.1-18.3-8.6-27.1-13.5-4.4-2.5-8.8-5-13.1-7.8-4.3-2.8-8.5-5.6-12.6-8.7-8.328-6.09-16.002-13.027-22.9-20.7-7.07-7.832-12.843-16.745-17.1-26.4-4.244-9.846-6.357-20.48-6.2-31.2.047-5.566 1.062-11.082 3-16.3 1.904-5.155 4.603-9.98 8-14.3 6.7-8.6 15.1-15.1 23.8-20.6 8.709-5.303 17.8-9.949 27.2-13.9 9.3-4 18.8-7.4 28.2-10.6 19-6.4 38.2-11.9 57.2-17.6 19-5.7 38-11.3 56.4-18.3 9.16-3.434 18.11-7.408 26.8-11.9 8.5-4.5 16.7-9.7 23.4-16.2 3.303-3.155 6.193-6.717 8.6-10.6 2.277-3.828 3.867-8.024 4.7-12.4.398-2.177.599-4.386.6-6.6-.017-2.016-.389-4.013-1.1-5.9-1.583-3.931-4.211-7.355-7.6-9.9-3.636-2.829-7.598-5.212-11.8-7.1-4.36-1.967-8.835-3.67-13.4-5.1-9.345-2.822-18.866-5.027-28.5-6.6-19.4-3.3-39.1-5.1-58.8-6.4-19.7-1.3-39.6-2-59.4-2.4-39.7-.8-79.4-.6-119.2-.5-19.9 0-39.8 0-59.7-.2-19.9-.2-39.8-.8-59.8-2.3-10-.8-20-1.8-29.9-3.5-5.043-.856-10.022-2.06-14.9-3.6-2.505-.775-4.946-1.745-7.3-2.9-2.569-1.217-4.904-2.876-6.9-4.9-1.092-1.172-1.972-2.526-2.6-4-.293-.774-.495-1.58-.6-2.4-.116-.694-.183-1.396-.2-2.1-.164-2.852.244-5.708 1.2-8.4 1.985-5.063 5.138-9.585 9.2-13.2 3.783-3.499 7.903-6.614 12.3-9.3 4.3-2.7 8.7-5.2 13.1-7.5 17.9-9.2 36.5-16.4 54.4-24.8 4.5-2.1 8.9-4.2 13.2-6.6 4.295-2.26 8.437-4.799 12.4-7.6 1.934-1.37 3.74-2.909 5.4-4.6 1.62-1.556 3.001-3.342 4.1-5.3.512-.905.883-1.883 1.1-2.9.116-.492.183-.995.2-1.5v-1.1c-.264-.734-.712-1.388-1.3-1.9-.766-.734-1.643-1.342-2.6-1.8-2.085-1.113-4.26-2.05-6.5-2.8-2.3-.8-4.6-1.4-7-2.1-4.7-1.2-9.6-2.2-14.4-3-9.7-1.7-19.5-2.9-29.4-4-19.7-2.1-39.5-3.5-59.4-4.6-19.9-1.1-39.7-1.9-59.5-2.4-19.8-.5-39.7-.9-59.6-1.1-.39.004-.764-.156-1.03-.442-.267-.285-.4-.669-.37-1.058-.3-1.3.4-2 1.2-2z"
            />
            <path
              stroke="url(#t6ouanhq5b)"
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M1106.59 588.6V462.84c-.034-10.078-7.533-18.57-17.53-19.85-65.036-8.788-113.56-64.303-113.57-129.93h0c.006-72.402 58.698-131.094 131.1-131.1h0c72.402.006 131.094 58.698 131.1 131.1h0"
            />
            <circle
              cx="1106.5"
              cy="615.8"
              r="15.2"
              fill="url(#w8u0kg9trc)"
              fill-rule="nonzero"
            />
            <circle
              cx="513.5"
              cy="420"
              r="13.2"
              fill="url(#w8u0kg9trc)"
              fill-rule="nonzero"
            />
            <circle
              cx="778.7"
              cy="254.9"
              r="10.4"
              fill="url(#w8u0kg9trc)"
              fill-rule="nonzero"
            />
            <circle
              cx="295.7"
              cy="187.2"
              r="7.5"
              fill="url(#w8u0kg9trc)"
              fill-rule="nonzero"
            />
            <path
              fill="url(#w8u0kg9trc)"
              fill-rule="nonzero"
              d="M416.8 99.8c0-3.369 2.731-6.1 6.1-6.1 3.369 0 6.1 2.731 6.1 6.1 0 3.369-2.731 6.1-6.1 6.1-1.621.01-3.179-.628-4.325-1.775-1.147-1.146-1.786-2.704-1.775-4.325z"
            />
            <g fill-rule="nonzero">
              <use fill="#000" filter="url(#soytwc58cd)" xlink:href="#n0deypsuze" />
              <use fill="url(#ghpo2gedgf)" xlink:href="#n0deypsuze" />
            </g>
            <path
              fill="url(#2vkn5c348g)"
              fill-rule="nonzero"
              d="M1106.59 418.46c-58.542 0-106-47.458-106-106s47.458-106 106-106 106 47.458 106 106c-.066 58.515-47.485 105.934-106 106zm0-208.6c-56.664 0-102.6 45.936-102.6 102.6 0 56.664 45.936 102.6 102.6 102.6 56.664 0 102.6-45.936 102.6-102.6-.044-56.646-45.954-102.556-102.6-102.6z"
            />
            <path
              stroke="url(#s0u1kli4dh)"
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M513.2 396v-82.53c.025-8.657-6.139-16.096-14.65-17.68-38.715-7.095-66.838-40.83-66.85-80.19h0c.006-45.009 36.491-81.494 81.5-81.5h0c45.009.006 81.494 36.491 81.5 81.5h0"
            />
            <g fill-rule="nonzero">
              <use fill="#000" filter="url(#uq4yohspzi)" xlink:href="#sdh7hmrukj" />
              <use fill="url(#udqubgcr4k)" xlink:href="#sdh7hmrukj" />
            </g>
            <path
              fill="url(#lscx884nrl)"
              fill-rule="nonzero"
              d="M513.2 281.1c-26.654 0-50.684-16.056-60.884-40.681-10.2-24.625-4.562-52.97 14.286-71.817 18.847-18.848 47.192-24.486 71.817-14.286 24.625 10.2 40.681 34.23 40.681 60.884-.055 36.373-29.527 65.845-65.9 65.9zm0-129.7c-35.236 0-63.8 28.564-63.8 63.8s28.564 63.8 63.8 63.8 63.8-28.564 63.8-63.8c-.028-35.224-28.576-63.772-63.8-63.8z"
            />
            <path
              stroke="url(#9u73cfm28m)"
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M778.7 235.5l.08-61.47c-.032-7.673-5.471-14.259-13-15.74-30.257-6.186-51.983-32.807-51.98-63.69h0c0-35.899 29.101-65 65-65h0c35.899 0 65 29.101 65 65h0"
            />
            <g fill-rule="nonzero">
              <use fill="#000" filter="url(#6pa52qxxyn)" xlink:href="#lk94gm7e3o" />
              <use fill="url(#snkdd7kjop)" xlink:href="#lk94gm7e3o" />
            </g>
            <path
              fill="url(#0j1h3gr64q)"
              fill-rule="nonzero"
              d="M778.8 146.9c-21.275 0-40.455-12.816-48.596-32.47-8.142-19.656-3.641-42.28 11.402-57.324 15.044-15.043 37.668-19.544 57.323-11.402C818.584 53.845 831.4 73.025 831.4 94.3c-.033 29.036-23.564 52.567-52.6 52.6zm0-103.4c-28.111 0-50.9 22.789-50.9 50.9 0 28.111 22.789 50.9 50.9 50.9 28.111 0 50.9-22.789 50.9-50.9-.006-28.109-22.791-50.894-50.9-50.9z"
            />
            <path
              stroke="url(#y0ld2gdr1r)"
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M295.7 174.7v-19.61c-.063-6.444-4.517-12.013-10.79-13.49-20.036-4.971-34.106-22.956-34.11-43.6h0c0-24.798 20.102-44.9 44.9-44.9h0c24.798 0 44.9 20.102 44.9 44.9h0"
            />
            <g fill-rule="nonzero">
              <use fill="#000" filter="url(#9p4a25hwms)" xlink:href="#mpf8gsr6it" />
              <use fill="url(#3x3bu5itxu)" xlink:href="#mpf8gsr6it" />
            </g>
            <path
              fill="url(#70l9q7j3fv)"
              fill-rule="nonzero"
              d="M295.7 134.2c-20.048 0-36.3-16.252-36.3-36.3s16.252-36.3 36.3-36.3S332 77.852 332 97.9c-.033 20.034-16.266 36.267-36.3 36.3zm0-71.5c-19.385 0-35.1 15.715-35.1 35.1s15.715 35.1 35.1 35.1 35.1-15.715 35.1-35.1c-.028-19.374-15.726-35.072-35.1-35.1z"
            />
            <path
              stroke="url(#j943o6bcnw)"
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M422.8 86.77v-7.75c-.046-5.485-3.77-10.254-9.08-11.63-14.896-4.096-25.22-17.64-25.22-33.09h0C388.533 15.37 403.87.033 422.8 0h0c18.93.033 34.267 15.37 34.3 34.3h0"
            />
            <g fill-rule="nonzero">
              <use fill="#000" filter="url(#qs7rml46bx)" xlink:href="#j9176b62my" />
              <use fill="url(#1eueyvhzkz)" xlink:href="#j9176b62my" />
            </g>
            <path
              fill="url(#s5b28l0zcA)"
              fill-rule="nonzero"
              d="M422.8 61.9c-15.298 0-27.7-12.402-27.7-27.7s12.402-27.7 27.7-27.7 27.7 12.402 27.7 27.7c0 7.346-2.918 14.392-8.113 19.587-5.195 5.195-12.24 8.113-19.587 8.113zm0-54.6c-14.829-.028-26.872 11.971-26.9 26.8-.028 14.829 11.971 26.872 26.8 26.9 14.829.028 26.872-11.971 26.9-26.8.006-14.82-11.98-26.85-26.8-26.9z"
            />
            <path
              stroke="url(#mtfpsj0t4B)"
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M180.9 65.24l.06-8.65c-.02-4.383-2.915-8.233-7.12-9.47-10.065-3.142-16.925-12.456-16.94-23h0c-.003-6.394 2.535-12.528 7.055-17.051C168.475 2.545 174.605.003 181 0h0c13.308.006 24.094 10.792 24.1 24.1h0"
            />
            <g fill-rule="nonzero">
              <use fill="#000" filter="url(#12jsmvf70C)" xlink:href="#2t1ygffdhD" />
              <use fill="url(#qet05neh6E)" xlink:href="#2t1ygffdhD" />
            </g>
            <path
              fill="url(#5me9k6qvqF)"
              fill-rule="nonzero"
              d="M181 43.5c-10.77 0-19.5-8.73-19.5-19.5S170.23 4.5 181 4.5s19.5 8.73 19.5 19.5c-.017 10.763-8.737 19.483-19.5 19.5zm0-38.3c-10.438 0-18.9 8.462-18.9 18.9 0 10.438 8.462 18.9 18.9 18.9 10.438 0 18.9-8.462 18.9-18.9-.027-10.427-8.473-18.873-18.9-18.9z"
            />
            <circle
              cx="180.6"
              cy="73.1"
              r="4.9"
              fill="url(#w8u0kg9trc)"
              fill-rule="nonzero"
            />
            <path
              stroke="#1BAEEF"
              d="M0 72.3L165 72.3M113 188.3L278 188.3M325 420.3L490 420.3M799 254.3L964 254.3M439 99.3L604 99.3M1131 615.3L1296 615.3"
            />
            <g fill="#157EFB">
              <text
                font-family="NotoSansTC-Regular, Noto Sans TC"
                font-size="16"
                transform="translate(1129.5 547.3)"
              >
                <tspan x="54.636" y="19">{{ showData[5].hook_date.substr(0, 7) }}</tspan>
              </text>
              <text
                font-family="NotoSansTC-Bold, Noto Sans TC"
                font-size="24"
                font-weight="bold"
                transform="translate(1129.5 547.3)"
              >
                <tspan x="10" y="55">{{ showData[5].title }}</tspan>
              </text>
              <image
                transform="matrix(1 0 0 1 1017.6998 223.3605)"
                :href="showData[5].icon"
                height="180"
                width="180"
              ></image>
            </g>
            <g fill="#157EFB">
              <text
                font-family="NotoSansTC-Regular, Noto Sans TC"
                font-size="16"
                transform="translate(335 357.3)"
              >
                <tspan x="43.136" y="19">{{ showData[4].hook_date.substr(0, 7) }}</tspan>
              </text>
              <text
                font-family="NotoSansTC-Bold, Noto Sans TC"
                font-size="22"
                font-weight="bold"
                transform="translate(335 357.3)"
              >
                <tspan x="39" y="50">{{ showData[4].title }}</tspan>
              </text>
              <image
                transform="matrix(1 0 0 1 458.6998 161.3605)"
                :href="showData[4].icon"
                height="111"
                width="111"
              ></image>
            </g>
            <g fill="#157EFB">
              <text
                font-family="NotoSansTC-Regular, Noto Sans TC"
                font-size="16"
                transform="translate(809 191.3)"
              >
                <tspan x="44.136" y="19">{{ showData[3].hook_date.substr(0, 7) }}</tspan>
              </text>
              <text
                font-family="NotoSansTC-Bold, Noto Sans TC"
                font-size="22"
                font-weight="bold"
                transform="translate(809 191.3)"
              >
                <tspan x="29" y="50">{{ showData[3].title }}</tspan>
              </text>
              <image
                transform="matrix(1 0 0 1 734.6998 50.3605)"
                :href="showData[3].icon"
                height="89"
                width="89"
              />
            </g>
            <g fill="#157EFB">
              <text
                font-family="NotoSansTC-Regular, Noto Sans TC"
                font-size="16"
                transform="translate(123 131.3)"
              >
                <tspan x="42.136" y="19">{{ showData[2].hook_date.substr(0, 7) }}</tspan>
              </text>
              <text
                font-family="NotoSansTC-Bold, Noto Sans TC"
                font-size="20"
                font-weight="bold"
                transform="translate(123 131.3)"
              >
                <tspan x="31" y="47">{{ showData[2].title }}</tspan>
              </text>
              <image
                transform="matrix(1 0 0 1 265.6998 66.3605)"
                :href="showData[2].icon"
                height="62"
                width="62"
              ></image>
            </g>
            <g fill="#157EFB">
              <text
                font-family="NotoSansTC-Regular, Noto Sans TC"
                font-size="16"
                transform="translate(449 42.3)"
              >
                <tspan x="44.136" y="19">{{ showData[1].hook_date.substr(0, 7) }}</tspan>
              </text>
              <text
                font-family="NotoSansTC-Bold, Noto Sans TC"
                font-size="20"
                font-weight="bold"
                transform="translate(449 42.3)"
              >
                <tspan x="43" y="47">{{ showData[1].title }}</tspan>
              </text>
              <image
                transform="matrix(1 0 0 1 400.6998 10.3605)"
                :href="showData[1].icon"
                height="47"
                width="47"
              ></image>
            </g>
            <g fill="#157EFB">
              <text
                font-family="NotoSansTC-Regular, Noto Sans TC"
                font-size="16"
                transform="translate(9.5 19.3)"
              >
                <tspan x="44.636" y="19">{{ showData[0].hook_date.substr(0, 7) }}</tspan>
              </text>
              <text
                font-family="NotoSansTC-Bold, Noto Sans TC"
                font-size="18"
                font-weight="bold"
                transform="translate(9.5 19.3)"
              >
                <tspan x="37.5" y="45">{{ showData[0].title }}</tspan>
              </text>
              <image
                transform="matrix(1 0 0 1 164.6998 7.3605)"
                :href="showData[0].icon"
                height="36"
                width="36"
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
    <div class="route-text">{{ showData[4].content }}</div>
    <div class="route-text">{{ showData[5].content }}</div>
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
.route-map {
  position: relative;
  width: calc(100% - 200px);

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
      top: 12%;
      left: 0%;
    }
    &:nth-of-type(2) {
      top: 16%;
      left: 33.5%;
    }
    &:nth-of-type(3) {
      top: 29%;
      left: 8.5%;
    }
    &:nth-of-type(4) {
      top: 39%;
      left: 61%;
    }
    &:nth-of-type(5) {
      top: 63%;
      left: 25%;
    }
    &:nth-of-type(6) {
      top: 92%;
      left: 87.5%;
    }
  }
}
</style>
