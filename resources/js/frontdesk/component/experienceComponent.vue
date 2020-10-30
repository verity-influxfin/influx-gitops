<template>
  <div class="experience-card">
    <h2>{{ $props.title }}</h2>
    <div class="hr"></div>
    <div class="comment-box" ref="comment_slick">
      <div
        class="item"
        v-for="(item, index) in $props.experiences"
        :key="index"
      >
        <label class="c-pel">
          {{ item.type === "loan" ? "借款" : "投資" }}回饋<i
            class="fas fa-slash"
          ></i
          >{{ item.name }}<i class="fas fa-slash"></i
          >{{ item.rank === "student" ? "在學生" : "上班族" }}
        </label>
        <div class="img">
          <img
            :src="item.imageSrc"
            @error="item.imageSrc = '/images/mug_shot.svg'"
            class="img-fluid"
          />
        </div>
        <span>{{ item.feedback }}</span>
      </div>
    </div>
    <button class="btn btn-light comment" @click="$root.goFeedback">
      <i class="fas fa-comments"></i>我要回饋
    </button>
  </div>
</template>

<script>
export default {
  props: ["experiences", "title"],
  watch: {
    "$props.experiences"() {
      this.$nextTick(() => {
        this.createSlick(this.$refs.comment_slick);
      });
    },
  },
  methods: {
    createSlick(tar) {
      $(tar).slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        customPaging(slider, i) {
          return '<i class="fas fa-circle"></i>';
        },
        arrows: true,
        speed: 1000,
        prevArrow: '<i class="fas fa-chevron-left arrow-left"></i>',
        nextArrow: '<i class="fas fa-chevron-right arrow-right"></i>',
        responsive: [
          {
            breakpoint: 1023,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1,
            },
          },
          {
            breakpoint: 767,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            },
          },
        ],
      });
    },
  },
};
</script>

<style lang="scss">
.experience-card {
  padding: 30px;
  overflow: hidden;
  position: relative;
  background-image: url("../asset/images/index_feedback.png");
  background-position: 0 0;
  background-repeat: no-repeat;
  background-size: cover;

  %arrow {
    position: absolute;
    top: 50%;
    z-index: 1;
    font-size: 20px;
    color: #ffffff;
  }

  .arrow-left {
    @extend %arrow;
    left: -20px;
  }

  .arrow-right {
    @extend %arrow;
    right: -20px;
  }

  h2 {
    text-align: center;
    color: #ffffff !important;
  }

  .comment {
    position: absolute;
    top: 30px;
    right: 30px;
  }

  .comment-box {
    width: 90%;
    margin: 0px auto;

    .item {
      margin: 20px;
      pointer-events: none;

      .img {
        overflow: hidden;
        border-radius: 50%;
        width: 200px;
        margin: 20px auto;
        border: solid 5px #ffffff;
        filter: drop-shadow(0px 0px 2px #ffffff);
      }

      .c-pel {
        text-align: center;
        margin: 10px auto;
        display: block;
        font-weight: bolder;
        color: #ffffff;

        i {
          transform: rotate(90deg);
        }
      }

      span {
        color: #ffffff;
      }
    }
  }
}

@media screen and (max-width: 767px) {
  .experience-card {
    padding: 10px;
    width: 100%;

    .comment-box {
      .item {
        margin: 10px 0px;
      }
    }

    .comment {
      position: initial;
      margin: 0px auto;
      display: block;
      width: 50%;
    }
  }
}
</style>