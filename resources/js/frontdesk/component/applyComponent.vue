<template>
  <div class="applyFlow-card">
    <div class="t-c">
      <h2>{{ $props.title }}</h2>
    </div>
    <div class="hr"></div>
    <Splide class="flow" ref="flowSlick" :options="applyOptions">
      <SplideSlide
        class="box"
        v-for="(item, index) in $props.step"
        :key="index"
      >
        <div class="step">
          <div class="img">
            <img :src="item.imgSrc" class="img-fluid" />
            <div :class="`sub-img${index}`" v-if="item.subImgSrc">
              <img :src="item.subImgSrc" class="img-fluid" />
            </div>
          </div>
          <div class="num">{{ index + 1 }}</div>
          <h5 v-html="item.stepTitle.replace(',', '<br>')"></h5>
          <p v-html="item.stepDesc.replace(',', '<br>')"></p>
        </div>
      </SplideSlide>
    </Splide>
  </div>
</template>

<script>
import { Splide, SplideSlide } from "@splidejs/vue-splide";

export default {
  props: ["title", "step"],
  components: {
    Splide,
    SplideSlide,
  },
  data: () => ({
    applyOptions: {
      type: "loop",
      autoplay: true,
      perPage: 4,
      perMove: 1,
      arrows: false,
      pagination: false,
      gap: "2rem",
      breakpoints: {
        767: { perPage: 1 },
      },
    },
  }),
  watch: {
    "$props.step"() {
      this.applyOptions.perPage = this.step.length;
      this.$nextTick(() => {
        this.$refs.flowSlick.remount();
      });
    },
  },
};
</script>

<style lang="scss">
.applyFlow-card {
  background-image: linear-gradient(to bottom, #ecedf1 65%, #ffffff 65%);
  padding: 30px;
  text-align: center;
  position: relative;

  %arrow {
    position: absolute;
    top: 50%;
    z-index: 1;
    font-size: 20px;
  }

  .arrow-left {
    @extend %arrow;
    left: 0%;
  }

  .arrow-right {
    @extend %arrow;
    right: 0%;
  }

  .flow {
    text-align: initial;
    width: 90%;
    margin: 0px auto;

    .box {
      margin: 20rem 0px 0px 0px;
      padding: 20px 0px;

      .step {
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #ffffff;
        position: relative;

        .img {
          margin-top: -100%;
          position: relative;
          text-align: center;

          .sub-img1 {
            position: absolute;
            width: 90%;
            top: 90px;
            right: -40px;
          }

          .sub-img2 {
            position: absolute;
            width: 80%;
            top: 100px;
            right: -40px;
          }
        }

        .num {
          font-size: 25px;
          font-weight: bold;
          width: fit-content;
          color: #083a6e;
          padding: 0px 12px;
          border-radius: 50%;
          margin: 10px auto;
          border: 1px solid #083a6e;
        }

        h5,
        p {
          height: 48px;
          font-size: 15.5px;
          font-weight: bold;
          line-height: 1.56;
          text-align: center;
          color: #153a71;
        }
      }
    }
  }
}

@media screen and (max-width: 767px) {
  .applyFlow-card {
    padding: 10px;
    overflow: hidden;

    h2 {
      word-break: keep-all;
    }

    .flow {
      display: block;

      .box {
        margin: 10px;

        .step {
          margin-top: 90%;

          .img {
            .sub-img1 {
              right: -30px;
            }

            .sub-img2 {
              right: -30px;
            }
          }

          h5,
          p {
            height: initial;
          }
        }
      }
    }
  }
}
</style>