<template>
  <div class="product-banner" ref="banner">
    <div class="item" v-for="(row, index) in $props.data" :key="index">
      <template v-if="index === 0">
        <img :src="row.bannerDesktopHref" style="width: 100%" class="hidden-desktop" />
        <img :src="row.bannerMoblieHref" style="width: 100%" class="hidden-phone" />
        <div class="banner-cnt">
          <h1 class="banner-title">{{ row.productName }}</h1>
          <div class="banner-desc" v-html="row.desc"></div>
          <a
            v-if="isBorrow"
            class="banner-download"
            href="/borrowLink"
            onClick="ga('send', 'event', 'Click', 'Nav Click', 'borrowLink','10')"
            target="_blank"
            ><img src="../asset/images/light-y.svg" class="img-fluid" />
            <div class="text">立即借款</div></a
          >
          <a
            v-if="isInvest"
            class="banner-download"
            href="/investLink"
            onClick="ga('send', 'event', 'Click', 'Nav Click', 'investLink','10')"
            target="_blank"
            ><img src="../asset/images/light-y.svg" class="img-fluid" />
            <div class="text">立即投資</div></a
          >
        </div>
      </template>
      <template v-else>
        <a :href="row.link" target="_blank">
          <img
            :src="`/upload/banner/${row.desktop}`"
            style="width: 100%"
            class="hidden-desktop img-fluid"
          />
          <img
            :src="`/upload/banner/${row.mobile}`"
            style="width: 100%"
            class="hidden-phone img-fluid"
          />
        </a>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  props: ["data", "isInvest", "isBorrow"],
  created() {},
  watch: {
    "$props.data"() {
      this.$nextTick(() => {
        this.createSlick();
      });
    },
  },
  mounted() {},
  methods: {
    createSlick() {
      $(this.$refs.banner).slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        dots: false,
        arrows: true,
        speed: 1000,
        prevArrow: '<svg class="arrow-left slick-arrow"xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40"><path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path></svg>',
        nextArrow: '<svg class="arrow-right slick-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" width="40" height="40"><path d="m15.5 0.932-4.3 4.38 14.5 14.6-14.5 14.5 4.3 4.4 14.6-14.6 4.4-4.3-4.4-4.4-14.6-14.6z"></path></svg>',
      });
    },
  },
};
</script>

<style lang="scss">
.product-banner {
  width: 100%;
  overflow: hidden;
  position: relative;

  %arrow {
    position: absolute;
    top: 50%;
    transform: translate(0px, -50%);
    color: #ffffff;
    cursor: pointer;
    z-index: 2;
    width: 20px;
    height: 20px;
    padding: 5px;
    background-color: #bfb5b55e;
    border-radius: 8px;
  }

  .arrow-left {
    @extend %arrow;
    left: 0%;
    -moz-transform:scaleX(-1);
    -webkit-transform:scaleX(-1);
    -o-transform:scaleX(-1);
    transform:scaleX(-1);
    margin: -21px 0 0 5px;
  }
  .arrow-left:hover {
    background-color: #bfb5b596;
  }

  .arrow-right {
    @extend %arrow;
    right: 0%;
    margin: 0 5px 0 0px;
  }
  .arrow-right:hover {
    background-color: #bfb5b596;
  }

  .item {
    position: relative;
  }

  .banner-cnt {
    position: absolute;
    top: 54%;
    left: 40px;
    transform: translate(0px, -50%);
    width: 545px;

    .banner-title {
      text-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
      font-size: 48px;
      font-weight: normal;
      line-height: 1.48;
      letter-spacing: 2.4px;
      text-align: left;
      color: #f2e627;
    }

    .banner-desc {
      text-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
      font-family: NotoSansTC;
      font-size: 30px;
      line-height: 1.5;
      letter-spacing: 1.8px;
      color: #ffffff;
    }

    .banner-download {
      width: 40%;
      margin: 1rem auto;
      position: relative;
      display: block;

      :hover {
        color: #ffffff;
        text-decoration: none;
      }

      .text {
        color: #ffffff;
        position: absolute;
        top: 51%;
        left: 47%;
        transform: translate(-50%, -50%);
        font-size: 20px;
      }
    }
  }
}

@media (max-width: 767px) {
  .product-banner {
    .banner-cnt {
      width: 100%;
      top: 25%;
      left: 50%;
      transform: translate(-50%, -50%);

      .banner-title {
        text-align: center;
      }

      .banner-desc {
        text-align: center;
        font-size: 18px;
      }

      .banner-download {
        width: 60%;
      }
    }
  }
}
</style>
