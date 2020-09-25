<template>
  <div class="qa-card">
    <h2>還有其他問題嗎?</h2>
    <div class="hr" />
    <div class="qa-row" ref="qa_slick">
      <div class="qa-item" v-for="(item,index) in $props.qaData" :key="index">
        <div class="qa-title">
          <strong>{{index > 10 ? index+1 : `0${index+1}`}}</strong>
          <label>{{item.title}}</label>
        </div>
        <div class="qa-content" v-html="item.content"></div>
      </div>
    </div>
    <div class="row">
      <router-link class="btn link" style="margin:0px auto;" to="qa">
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
        autoplay: true,
        dots: false,
        arrows: false,
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
      pointer-events: none;
      
      .qa-title {
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #ffffff;
        padding: 30px 15px;
        position: relative;
        text-align: center;

        strong {
          box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
          background-color: #ffffff;
          color: #083a6e;
          font-size: 20px;
          padding: 7px 11px;
          border-radius: 50%;
          position: absolute;
          top: 50%;
          left: 0%;
          transform: translate(-50%, -50%);
        }

        label {
          margin: 0px;
          font-weight: 600;
          font-size: 13px;
        }
      }

      .qa-content {
        margin-top: 20px;
        line-height: 1.5;
        font-weight: 600;
        font-size: 15px;
      }
    }
  }
}

@media screen and (max-width: 767px) {
  .qa-card {
    padding: 10px;
  }
}
</style>