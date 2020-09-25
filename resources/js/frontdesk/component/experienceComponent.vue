<template>
  <div class="experience-card">
    <h2>用戶回饋</h2>
    <div class="hr"></div>
    <div class="comment-box" ref="comment_slick">
      <div
        class="item"
        v-for="(item, index) in $props.experiences"
        :key="index"
      >
        <div
          :class="[
            'memo',
            { 'laod-s-b': item.type === 'loan' },
            { 'invest-s-b': item.type === 'invest' },
          ]"
        >
          <h5
            :class="[
              { 'laod-s': item.type === 'loan' },
              { 'invest-s': item.type === 'invest' },
            ]"
          >
            {{ item.type === "loan" ? "借款" : "投資" }}回饋
          </h5>
          <span>{{ item.feedback }}</span>
        </div>
        <div class="img">
          <img :src="item.imageSrc" class="img-fluid" />
        </div>
        <label class="c-pel">
          {{ item.name }}
          <br />
          {{ item.rank === "student" ? "在學生" : "上班族" }}
        </label>
      </div>
    </div>
    <button class="btn btn-primary comment" @click="$root.goFeedback">
      <i class="fas fa-comments"></i>我要回饋
    </button>
  </div>
</template>

<script>
export default {
  props: ["experiences"],
  watch: {
    "$props.experiences"(xzc) {
      console.log(xzc);
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
        arrows: false,
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
  background: #f5f5f5;

  h2 {
    text-align: center;
    color: #083a6e;
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

      .laod-s-b {
        border: solid 1.5px #880ca2;
      }

      .invest-s-b {
        border: solid 1.5px #0ca283;
      }

      .laod-s {
        color: #880ca2;
      }

      .invest-s {
        color: #0ca283;
      }

      .memo {
        h5 {
          text-align: center;
          font-weight: bold;
        }

        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #ffffff;
        line-height: 1.83;
        padding: 41px 20px;
        text-align: justify;
        min-height: 235px;
      }

      .img {
        overflow: hidden;
        border-radius: 50%;
        width: fit-content;
        margin: -40px auto 0px auto;

        img {
          width: 80px;
        }
      }

      .c-pel {
        text-align: center;
        margin: 12px auto;
        display: block;
      }
    }
  }
  .slick-active {
    opacity: 0.5;
    transform: translateY(10%);
  }

  .slick-active + .slick-active {
    transform: translateY(2%);
    opacity: 1;
  }

  .slick-active + .slick-active + .slick-active {
    opacity: 0.5;
    transform: translateY(10%);
  }
}

@media screen and (max-width: 767px) {
  .experience-card {
    padding: 10px;
    width: 100%;

    .comment-box {
      .item {
        margin: 10px;
      }
    }

    .comment {
      position: initial;
      margin: 0px auto;
      display: block;
      width: 50%;
    }

    .slick-active {
      opacity: 1;
      transform: translateY(0%);
    }

    .slick-active + .slick-active {
      opacity: 1;
      transform: translateY(0%);
    }

    .slick-active + .slick-active + .slick-active {
      opacity: 1;
      transform: translateY(0%);
    }
  }
}
</style>