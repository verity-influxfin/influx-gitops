<template>
    <div class="index-wrapper">
        <!-- 產品方案 -->
        <div class="alesis-slate">
            <div class="header">
                <div class="text">產品方案</div>
            </div>
            <div class="phones">
                <div class="left">
                    <div class="phone">
                        <img src="/images/alesis-phone-slide-left.svg" class="image" alt="">
                    </div>
                </div>
                <div class="right">
                    <div class="phone">
                        <img src="/images/alesis-phone-slide-right.svg" class="image" alt="">
                    </div>
                    <div class="phone">
                        <img src="/images/alesis-phone-slide-right.svg" class="image" alt="">
                    </div>
                </div>
            </div>
        </div>
        <!-- / 產品方案 -->

        <!-- 簡單快速專區 -->
        <div class="alesis-features">
            <index-section header="簡單快速專區" multiline="全線上自動審核 無人打擾24小時立即到帳" :outlinedMulti="true">
                <div class="cards">
                <index-symcard class="symcard animate__animated" image="/images/sshot-1635.png" header="企業融資" symbol="/images/alesis-companies.svg" description="支持中小企業，協助媒合百萬營運資金"></index-symcard>
                <index-symcard class="symcard animate__animated" image="/images/sshot-1636.png" header="上班族貸" symbol="/images/alesis-work.svg" description="急需資金不再慌張，AI審核立即入帳"></index-symcard>
                <index-symcard class="symcard animate__animated" image="/images/sshot-1638.png" header="學生貸" symbol="/images/alesis-student.svg" description="輕鬆分期不再煩腦，學生社群口碑最好"></index-symcard>
                <index-symcard class="symcard animate__animated" image="/images/sshot-1637.png" header="車輛融資" symbol="/images/alesis-car.svg" description="敬請期待"></index-symcard>
                </div>
            </index-section>
        </div>
        <!-- / 簡單快速專區 -->

        <!-- 銀行合作專區 -->
        <div class="alesis-recommends">
            <index-section header="銀行合作專區" multiline="透過普匯推薦合作銀行，可享最高額度300萬！" :secondary="true">
                <div class="wrapper">
                    <index-shanghai></index-shanghai>
                </div>
            </index-section>
        </div>
        <!-- / 銀行合作專區 -->

        <!-- 聽聽他們怎麼說 -->
        <div class="alesis-humans">
            <index-section header="聽聽他們怎麼說" :outlined="true">
                <global-carousel>
                    <div class="set">
                        <div class="item">
                            <index-human>
                                <div slot="video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                        <div class="item">
                            <index-human>
                                <div slot="video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                    </div>
                    <div class="set">
                        <div class="item">
                            <index-human>
                                <div slot="video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                        <div class="item">
                            <index-human>
                                <div slot="video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                    </div>
                </global-carousel>
            </index-section>
        </div>
        <!-- / 聽聽他們怎麼說 -->
    </div>
</template>

<script>
//
import indexSection   from "../component/alesis/index/section";
import indexSymcard   from "../component/alesis/index/symcard";
import indexHuman     from "../component/alesis/index/human";
import indexShanghai  from "../component/alesis/index/shanghai";
import globalCarousel from "../component/alesis/global-carousel";

export default {
    components: {
        indexSection,
        indexSymcard,
        indexHuman,
        indexShanghai,
        globalCarousel
    },
    mounted() {
        document.querySelectorAll(".symcard").forEach((v, i) => {
            setTimeout(() => {
                v.classList.add("animate__flipInY")
            }, 300 * i)
        })
    },
};
</script>

<style lang="scss" scoped>
@import "../component/alesis/alesis";

@keyframes fadein2 {
    0%   { opacity: 0; }
    50%  { opacity: 0; }
    100% { opacity: 1; }
}
@keyframes fadein1 {
    0%   { opacity: 0; }
    30%  { opacity: 0; }
    100% { opacity: 1; }
}
@keyframes fadein3 {
    0%   { opacity: 0; }
    100% { opacity: 1; }
}

.alesis-slate {
    display         : flex;
    flex-direction  : column;
    background-image: url("/images/未命名-2.png");
    background-size : cover;
    padding         : 2rem 0 4rem;
    margin-bottom   : 8rem;

    .header {
        text-align: center;

        .text {
            color         :#FFF;
            padding-bottom: .2rem;
            border-bottom : 1px solid #FFF;
            display       : inline-block;
            font-size     : 1.8rem;
            font-weight   : bolder;
        }
    }

    .phones {
        display  : flex;
        max-width: 1280px;
        margin   : 3rem auto 0;
        position : relative;

        .left {
            flex: 1;
            text-align: center;
            position: relative;

            .phone {
                position : absolute;
                left     : -29rem;
                animation: fadein3 2s;
            }
        }

        .right {
            flex       : 1;
            display    : flex;
            margin-left: 8rem;
            position   : relative;

            .phone:first-child {
                position : absolute;
                left     : 21rem;
                animation: fadein2 2s;
            }
            .phone:last-child {
                animation: fadein1 2s;
            }
        }

        .phone {
            .image {
                height: 350px
            }
        }
    }
}

.alesis-features {
  .cards {
    display              : grid;
    grid-template-columns: repeat(2, 1fr);
    width                : 740px;
    gap                  : 6rem;
    margin               : 6rem auto 0;
  }
}

.alesis-recommends {
  .wrapper {
        display        : flex;
        align-items    : center;
        justify-content: center;
        margin         : 3rem 0 0;
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
