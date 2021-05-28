<template>
  <div class="index-wrapper">
      <div class="alesis-slate">
          <div class="wrapper">
              <div class="header">
                  <div class="text">普匯投資</div>
              </div>
              <div class="showcase">
                  <img class="image" src="/images/直手機-14.png" alt="">
              </div>
          </div>
      </div>
      <div class="alesis-types">
          <index-section header="債權種類">
            <div class="items">
                <div class="item">
                    <div class="header">債權投資</div>
                    <div class="divider"></div>
                    <div class="features">
                        <div class="feature">
                            <span class="highlighted">自選債權：</span>
                            優良債權　自由選擇
                        </div>
                        <div class="feature">
                            <span class="highlighted">智能投資：</span>
                            設定喜好　AI自動投標
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="header">債權轉讓</div>
                    <div class="divider"></div>
                    <div class="features">
                        <div class="feature -centered">
                            轉讓債權　立即變現
                        </div>
                    </div>
                </div>
            </div>
            </index-section>
      </div>
      <div class="alesis-comparison" >
          <index-section header="投資工具比一比" :secondary="true">
              <div class="grid">
                  <div class="A">
                      <img src="/images/invest_chart_a.png" alt="">
                  </div>
                  <div class="B">
                       <img src="/images/invest_chart_b.png" alt="">
                  </div>
                  <div class="C">
                       <img src="/images/invest_chart_c.png" alt="">
                  </div>
              </div>
          </index-section>
      </div>
      <div class="alesis-report">
          <index-section header="債權風險報告書">
              <div class="wrapper">
                  <div class="cover">
                      <img class="image" src="/images/報告書.png" alt="">
                  </div>
                  <a class="more" href="#!">查看更多</a>
              </div>

          </index-section>
      </div>
      <div class="alesis-projects" >
          <index-section header="現有案件" :secondary="true">
              asad
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
    background-image: url("/images/上班族.png");
    background-size : cover;

    .wrapper {
        display    : flex;
        align-items: center;
        position   : relative;
        padding-top: 4rem;

        &:before {
            content         : ' ';
            display         : block;
            position        : absolute;
            left            : 0;
            top             : 0;
            width           : 100%;
            height          : 100%;
            opacity         : 0.6;
            background-image: url("/images/未命名-3.png");
            background-size : cover;
        }

        .header {
            flex      : 1;
            z-index   : 1;
            position  : relative;
            text-align: center;

            .text {
                color         : #FFF;
                padding-bottom: 0.2rem;
                border-bottom : 1px solid #FFF;
                display       : inline-block;
                font-size     : 1.8rem;
                font-weight   : bolder;
            }
        }
        .showcase {
            flex      : 2;
            text-align: center;
            z-index   : 1;
            position  : relative;

            .image {
                height: 520px;
            }
        }
    }
}

.alesis-types {
    .items {
        display              : grid;
        grid-template-columns: repeat(2, 1fr);
        max-width            : 830px;
        margin               : 0 auto;
        gap                  : 3rem;

        .item {
            border       : 1px solid #2664a5;
            padding      : 2.5rem 1rem;
            border-radius: 1rem;

            .header {
                text-align      : center;
                background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
                background-clip : text;
                width           : fit-content;
                color           : rgba(255, 255, 255, 0);
                margin          : 0px auto;
                font-weight     : bolder;
                font-size       : 1.6rem;
                line-height     : 1.2;
                white-space     : nowrap;
            }

            .divider {
                height    : 1px;
                background: #2664a5;
                margin    : 2rem 0;
            }

            .features {
                display       : flex;
                padding       : 0 1rem;
                flex-direction: column;

                .feature {
                    font-size  : 1.2rem;
                    color      : #5d5555;

                    &.-centered {
                        margin: 0 auto;
                    }

                    .highlighted {
                        font-weight: bolder;
                        color      : #112e53;
                    }

                    &:not(:last-child) {
                        margin-bottom: 1rem;
                    }
                }
            }
        }
    }
}

.alesis-comparison {
    .grid {
        display              : grid;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        grid-template-rows   : 1fr 1fr;
        gap                  : 0px 0px;
        grid-template-areas  :
            "A A A A"
            "B B C C";
        max-width: 1280px;
        margin   : 0 auto;
        gap      : 4rem;

        .A, .B, .C {
            text-align: center;
        }

        .B, .C {
            img {
                width: 530px;
            }
        }

        .A {
            grid-area: A;
        }
        .B {
            grid-area: B;
        }
        .C {
            grid-area: C;
        }
        img {
            width: 580px;
        }
    }
}

.alesis-report {
    .wrapper {
         text-align: center;


        .cover {

            .image {

            }
        }
        .more {
            margin-top: 2rem;
        }
    }
}
</style>
