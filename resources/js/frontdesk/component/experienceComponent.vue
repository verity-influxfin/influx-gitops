<template>
  <div class="experience-card">
    <div class="t-c">
      <h2>{{ $props.title }}</h2>
    </div>
    <div class="hr"></div>
    <Splide class="comment-box" :options="options" ref="Splide">
      <SplideSlide
        class="item"
        v-for="(item, index) in $props.experiences"
        :key="index"
      >
        <div class="img">
          <img
            :src="item.imageSrc"
            @error="item.imageSrc = '/images/mug_shot.svg'"
            class="img-fluid"
          />
        </div>
        <label class="c-pel">
          {{ item.name }}
        </label>
        <button class="btn btn-show" @click="show(item)">使用心得</button>
      </SplideSlide>
    </Splide>
    <!-- <button class="btn btn-light comment" @click="$root.goFeedback">
      <i class="fas fa-comments"></i>我要回饋
    </button> -->
    <div
      class="feedback-modal modal fade"
      ref="feedbackModal"
      role="dialog"
      aria-labelledby="modalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog" data-dismiss="modal">
        <div class="modal-content">
          <div class="modal-body">
            {{ feedback }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Splide, SplideSlide } from "@splidejs/vue-splide";

export default {
  components: {
    Splide,
    SplideSlide,
  },
  data: () => ({
    options: {
      type: "loop",
      autoplay: true,
      perPage: 4,
      perMove: 1,
      pagination: false,
      gap: "2rem",
      breakpoints: {
        767: { perPage: 1 },
      },
    },
    feedback: "",
  }),
  props: ["experiences", "title"],
  watch: {
    "$props.experiences"() {
      this.$refs.Splide.remount();
    },
  },
  methods: {
    show(item) {
      this.feedback = item.feedback;
      $(this.$refs.feedbackModal).modal("show");
    },
  },
};
</script>

<style lang="scss">
.experience-card {
  padding: 30px;
  overflow: hidden;
  position: relative;

  .t-c {
    background-image: linear-gradient(
      to right,
      #1e2973 0%,
      #319acf 50%,
      #1e2973 75%
    );
    background-clip: text;
    width: fit-content;
    color: #ffffff00;
    margin: 0px auto;

    h2 {
      font-weight: bolder;
    }
  }

  .splide__arrow--prev {
    z-index: 1;
    left: -50px;
  }

  .splide__arrow--next {
    z-index: 1;
    right: -50px;
  }

  .comment {
    position: absolute;
    top: 30px;
    right: 30px;
  }

  .comment-box {
    width: 80%;
    margin: 0px auto;

    .item {
      border-radius: 25px;
      background-image: linear-gradient(to top, #e4eeff, #fbfbfb);
      margin: 25px 0px;

      .img {
        overflow: hidden;
        border-radius: 50%;
        width: 110px;
        margin: 20px auto;
      }

      .c-pel {
        text-align: center;
        margin: 10px auto;
        display: block;
        font-weight: bolder;

        i {
          transform: rotate(90deg);
        }
      }

      .btn-show {
        padding: 5px 20px;
        border-radius: 25px;
        border: solid 3px #1f55a0;
        background-color: #ffffff;
        margin: 0px auto;
        transform: translate(0px, 50%);
        display: block;
        font-size: 16px;
        color: #1f55a0;
        font-weight: 700;
      }
    }
  }
}

@media screen and (max-width: 767px) {
  .experience-card {
    padding: 10px;
    width: 100%;

    .comment {
      position: initial;
      margin: 0px auto;
      display: block;
      width: 50%;
    }
  }
}
</style>