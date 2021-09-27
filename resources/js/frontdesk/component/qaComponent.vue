<template>
  <div class="qa-card">
    <div class="t-c"><h2>還有其他問題嗎?</h2></div>
    <div class="hr" />
    <div class="qa-row" ref="qa_slick">
      <div class="qa-item" v-for="(item, index) in $props.qaData" :key="index">
        <div class="qa-title">
          <div class="bg">
            <strong>{{ index > 10 ? index + 1 : `0${index + 1}` }}</strong>
          </div>
          <label>{{ item.title }}</label>
        </div>
        <div class="qa-content" v-html="item.content"></div>
      </div>
    </div>
    <div class="row">
      <router-link class="btn link" style="margin: 0px auto" to="faq">
        更多問題
        <i class="fas fa-angle-double-right" />
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  props: ["qaData"],
  watch: {
    qaData() {
      this.$nextTick(() => {
        this.createSlick(this.$refs.qa_slick);
      });
    },
  },
  methods: {
    createSlick(target) {
      $(target).slick({
        infinite: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        // autoplay: true,
        dots: false,
        arrows: false,
        speed: 1000,
        responsive: [
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
.qa-card {
  padding: 30px;
  background: #ffffff;
  overflow: hidden;

  .qa-row {
    margin: 30px 0px;
    overflow: auto;

    .qa-item {
      margin: 0px 20px;

      .qa-title {
        border-radius: 20px;
        border: 2px solid #81c3f3;
        background-image: linear-gradient(to left, #ffffff 100%, #ffffff 0%),
          linear-gradient(to bottom, #81c3f3 0%, #157efb 100%);
        background-origin: border-box;
        background-clip: content-box, border-box;
        text-align: center;
        display: flex;

        .bg {
          background-image: linear-gradient(to bottom, #81c3f3 0%, #157efb 100%);
          background-clip: text;
          width: fit-content;
          color: #81c3f3;
          margin: 0px 10px;
          font-size: 36px;
        }

        label {
          margin: 5px 0px;
          padding: 0px 5px;
          font-weight: 600;
          font-size: 15px;
          text-align: left;
          border-left: 2px solid #157efb;
        }
      }

      .qa-content {
        margin-top: 10px;
        line-height: 1.5;
        font-weight: 600;
        font-size: 15px;
        border-radius: 25px;
        background-image: linear-gradient(to bottom, #ffffff, #e4eeff);
        padding: 20px;
      }
    }
  }

  .link {
    display: block;
    background: #ffffff;
    color: #3492f9;
    width: 20%;
    margin: 0px auto;
    font-weight: bolder;
    border: 3px solid #3492f9;
    border-radius: 20px;
    background-image: linear-gradient(to top, #ebf5ff, #ffffff),
      linear-gradient(to bottom, #81c3f3, #157efb);

    i {
      margin-left: 10px;
    }

    &:hover {
      color: #ffffff;
      background-image: linear-gradient(to top, #4993dd, #0c40a1),
        linear-gradient(to bottom, #81c3f3, #157efb);
    }
  }
}

@media screen and (max-width: 767px) {
  .qa-card {
    padding: 10px;

    .link {
      width: 50%;
    }
  }
}
</style>
