<template>
  <div class="experience-card">
    <div class="t-c">
      <h2>{{ $props.title }}</h2>
    </div>
    <div class="hr"></div>
    <Splide class="comment-box" :options="options" ref="Splide">
      <SplideSlide class="item" v-for="(item, index) in $props.experiences" :key="index">
        <template v-if="item.video_link">
          <iframe
            :src="item.video_link"
            frameborder="0"
            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            style="width: 100%"
          ></iframe>
        </template>
        <template v-else>
          <div class="img">
            <img
              :src="item.imageSrc"
              @error="item.imageSrc = '/images/mug_shot.svg'"
              class="img-fluid"
            />
          </div>
        </template>
        <label class="c-pel">
          {{ item.post_title }}
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
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <template v-if="feedback">
              {{ feedback }}
            </template>
            <template v-if="feedbackImg.length !== 0">
              <Splide class="img-row" :options="fOptions">
                <SplideSlide v-for="(item, index) in feedbackImg" :key="index">
                  <div class="img">
                    <img :src="`/upload/feedbackImg/${item.image}`" class="img-fluid" />
                  </div>
                </SplideSlide>
              </Splide>
            </template>
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
    feedback: "",
    feedbackImg: [],
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
    fOptions: {
      type: "loop",
      autoplay: true,
      arrow: true,
      perPage: 1,
      perMove: 1,
      pagination: false,
    },
  }),
  props: ["experiences", "title"],
  watch: {
    "$props.experiences"() {
      this.$refs.Splide.remount();
    },
  },
  methods: {
    async show(item) {
      this.feedbackImg = [];
      if (!item.feedback) {
        let res = await axios.post("getFeedbackImg", { ID: item.ID });
        this.feedbackImg = res.data;
      }
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
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
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
      padding: 15px;
      box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.25);

      .img {
        overflow: hidden;
        border-radius: 50%;
        width: 125px;
        margin: 20px auto;
      }

      .c-pel {
        text-align: center;
        margin: 10px auto;
        display: block;
        font-weight: bolder;
        font-size: 20px;
        color: #1c2a54;

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
        display: block;
        font-size: 16px;
        color: #1f55a0;
        font-weight: 700;
      }
    }
  }

  .feedback-modal {
    .img-row {
      .img {
        width: 466px;
        text-align: center;
      }
    }

    .splide__arrow--prev {
      z-index: 1;
      left: 1rem;
    }

    .splide__arrow--next {
      z-index: 1;
      right: 1rem;
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

      .item {
        .c-pel {
          font-size: 16px;
        }
      }
    }

    .feedback-modal {
      .img-row {
        .img {
          width: 326px;
        }
      }

      .splide__arrow--prev {
        left: -0.5rem;
      }

      .splide__arrow--next {
        right: -0.5rem;
      }
    }
  }

  .modal-dialog {
    top: 20%;
  }
}
</style>
