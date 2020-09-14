<template>
  <div class="applyFlow-card">
    <h2>{{$props.title}}</h2>
    <div class="flow" ref="flow_slick">
      <div class="box" v-for="(item,index) in $props.step" :key="index">
        <div class="step">
          <div class="img">
            <img :src="item.imgSrc" class="img-fluid" />
          </div>
          <div class="num">{{index+1}}</div>
          <h5 v-html="item.stepTitle.replace(',','<br>')"></h5>
          <p v-html="item.stepDesc.replace(',','<br>')"></p>
        </div>
      </div>
    </div>
    <div class="tips" v-if="$props.requiredDocuments.length !== 0">
      <div class="pu-say">
        <div class="img">
          <img :src="'./images/ah-pu.svg'" class="img-fluid" />
        </div>
        <p class="dailog">先把下列資料準備好可以加快申貸速度喔！</p>
      </div>
      <div class="required">
        <div class="item" v-for="(item,index) in $props.requiredDocuments" :key="index">
          <div class="img">
            <img class="img-fluid" :src="item.imgSrc" />
          </div>
          <p>{{item.text}}</p>
          <div class="other" v-if="item.memo" v-html="item.memo"></div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["title", "requiredDocuments", "step"],
  watch: {
    "$props.requiredDocuments"() {
      this.$nextTick(() => {
        this.createSlick(this.$refs.comment_slick);
      });
    },
  },
  methods: {
    createSlick() {
      $(this.$refs.flow_slick).slick({
        infinite: true,
        slidesToShow: 5,
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
.applyFlow-card {
  background: #f6f6f6f6;
  padding: 30px;
  text-align: center;

  .flow {
    text-align: initial;
    width: 100%;
    margin: 0px auto;

    .box {
      margin: 20% 15px 0px 15px;

      .step {
        border-radius: 10px;
        padding: 10px;
        margin: 10px;
        box-shadow: 0 1.5px 3px 0 rgba(0, 0, 0, 0.16);
        background-color: #ffffff;
        position: relative;

        .img {
          margin-top: -100%;
        }

        .num {
          background-color: #083a6e;
          font-size: 25px;
          font-weight: bold;
          width: fit-content;
          color: #ffffff;
          padding: 0px 12px;
          border-radius: 50%;
          margin: 5px auto;
        }

        h5,
        p {
          height: 48px;
          font-size: 15.5px;
          font-weight: bold;
          line-height: 1.56;
          text-align: center;
          color: #040404;
        }
      }
    }
  }

  .tips {
    padding: 10px;
    background-image: linear-gradient(129deg, #00aeff 3%, #00d9d5 102%);
    position: relative;
    filter: drop-shadow(0px 1.5px 3px #00000029);
    margin-top: 20px;
    border-radius: 5px;
    width: fit-content;
    margin: 20px auto 0px auto;

    .pu-say {
      display: flex;
      width: fit-content;
      margin: 0px auto;

      .dailog {
        position: relative;
        background: #ffffff;
        font-weight: bold;
        padding: 5px 10px;
        border-radius: 10px;
        margin-left: 20px;
        margin-bottom: 0px;
        line-height: 52px;

        &::after {
          content: "";
          position: absolute;
          border-right: 15px solid #ffffff;
          border-top: 10px solid #ffffff00;
          border-bottom: 10px solid #ffffff00;
          left: -2%;
          top: 50%;
          transform: translate(-50%, -50%);
        }
      }
    }

    .required {
      margin: 5px auto;
      display: flex;
      width: fit-content;

      .item {
        text-align: center;
        margin: 10px;
        position: relative;

        .img {
          background: #ffffff;
          width: 70px;
          height: 70px;
          border-radius: 50%;
          position: relative;
          border: 5px solid #f1f1f1;
          margin: 5px auto;

          img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            width: 85%;
          }
        }

        p {
          color: #ffffff;
          font-weight: bold;
        }

        .other {
          background: #ffffff;
          padding: 10px;
          border-radius: 10px;
          width: fit-content;
          box-shadow: 0px 0px 4px 0px #0000009e;
          margin: 0px auto;
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
          .img {
            margin: initial;
          }

          h5,
          p {
            height: initial;
          }
        }
      }
    }

    .tips {
      margin: 0px auto;

      .pu-say {
        .dailog {
          line-height: initial;
        }
      }

      .required {
        display: block;
        overflow: hidden;

        .item {
          float: left;
          width: calc(33% - 10px);
          margin: 5px;
        }
      }
    }
  }
}
</style>