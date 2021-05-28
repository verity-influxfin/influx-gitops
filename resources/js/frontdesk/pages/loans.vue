<template>
  <div class="index-wrapper">
    <div class="alesis-slate">
      <div class="header">
        <div class="text">
          產品方案
        </div>
      </div>
      <div class="phones">
        <div class="left">
          <div class="phone">
            <img src="/images/mobile.png" class="image" alt="">
          </div>
        </div>
        <div class="right">
          <div class="phone">
            <img src="/images/mobile.png" class="image" alt="">
          </div>
          <div class="phone">
            <img src="/images/mobile.png" class="image" alt="">
          </div>
        </div>
      </div>
    </div>
    <div class="alesis-features">
      <index-section header="簡單快速專區" multiline="全線上自動審核 無人打擾24小時立即到帳" :outlinedMulti="true">
        <div class="cards">
          <index-symcard></index-symcard>
          <index-symcard></index-symcard>
          <index-symcard></index-symcard>
          <index-symcard></index-symcard>
        </div>
      </index-section>
    </div>
    <div class="alesis-recommends">
       <index-section header="銀行合作專區" multiline="透過普匯推薦合作銀行，可享最高額度300萬！" :secondary="true">
          <div class="wrapper">
                <div class="box">
                    <img class="logo" src="/images/shanghai.png" alt="">
                    <div class="divider"></div>
                    <div class="list">
                        <div class="item">1日快速核准</div>
                        <div class="item">前3個月利率1.88%起</div>
                        <div class="item">額度最高達300萬，期間最長7年</div>
                    </div>
                    <div class="buttons">
                        <a class="button" href="#!">了解更多</a>
                        <a class="button" href="#!">取得資格</a>
                    </div>
                </div>
          </div>
       </index-section>
    </div>
    <div class="alesis-humans">
       <index-section header="聽聽他們怎麼說" :outlined="true">
         <div class="carousel">
                <div class="left">
                    <img src="/images/sshot-1559.png" alt="">
                </div>
                <div class="lightbox">
                    <index-human></index-human>
                    <index-human></index-human>
                </div>
                <div class="right">
                    <img src="/images/sshot-1558.png" alt="">
                </div>
          </div>
       </index-section>
    </div>

  </div>
</template>

<script>
//svg
import routeMap from "../component/svg/routeMapComponent";
import routeMapM from "../component/svg/routeMapMComponent.vue";
//
import indexSection from "../component/alesis/index/section";
import indexSymcard from "../component/alesis/index/symcard";
import indexHuman from "../component/alesis/index/human";

export default {
  components: {
    indexSection,
    indexSymcard,
    indexHuman
    //banner
    // shanghuiBanner,
  },
  data: () => {
  },
};
</script>

<style lang="scss" scoped>
.alesis-slate {
    display: flex;
    flex-direction: column;
    background-image: url("/images/未命名-2.png");
    background-size: cover;
    padding: 2rem 0;
    margin-bottom: 8rem;

  .header {
    text-align: center;
    .text {
      color:#FFF;
      padding-bottom: .2rem;
      border-bottom: 1px solid #FFF;
      display: inline-block;
      font-size: 1.8rem;
      font-weight: bolder;
    }
  }
  .phones {
    display: flex;

    .left {
      flex: 1;
      text-align: center;
    }
    .right {
        flex: 1;
        display: flex;
    }
    .phone {
      .image {
        height: 450px
      }
    }
  }
}

.alesis-features {
  .cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    width: 740px;
    gap: 6rem;
    margin: 6rem auto 0;
  }
}

.alesis-recommends {
  .wrapper {
        display        : flex;
        align-items    : center;
        justify-content: center;
        margin         : 3rem 0 0;

        .box {
            display      : inline-block;
            border-radius: 17px;
            border       : 1px solid #2664a5;
            padding      : 1.5rem 1rem;

            .logo {

            }

            .divider {
                height    : 1px;
                background: #2664a5;
                margin    : 1rem 0;
            }

            .list {
                line-height: 2;
                margin-left: 1rem;

                .item {
                    display: list-item;
                }
            }

            .buttons {
                margin-top: 2rem;
                display   : flex;

                .button {
                    display      : block;
                    flex         : 1;
                    text-align   : center;
                    padding      : .1rem 1rem;
                    border       : 1px solid #2664a5;
                    border-radius: .4rem;
                    width        : 100%;
                    font-size    : 1.2rem;
                    font-weight  : bold;
                    color        : #036eb7;
                    margin-right : 1rem;
                    line-height  : 1.4;

                    & + .button {
                        margin-right: 0;
                    }
                }
            }
        }
    }
}

.alesis-humans {
    .carousel {
        max-width  : 1280px;
        margin     : 2rem auto 0;
        display    : flex;
        align-items: center;

        .left {

        }
        .lightbox {
            flex                 : 1;
            display              : grid;
            grid-template-columns: repeat(2, 1fr);
            gap                  : 2rem;
        }
        .right {

        }
    }
}
</style>
