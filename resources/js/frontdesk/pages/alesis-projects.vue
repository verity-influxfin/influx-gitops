<template>
    <div class="index-wrapper">
        <!-- 標頭 -->
        <div class="alesis-slate">
            <div class="background">
                <img class="image" src="/images/sshot-1907.png" alt="">
            </div>
            <div class="mask">
                <div class="header">查看案件</div>
                <div class="content">
                    獨立債權，多元投資<br>
                    超高報酬，穩定收入
                </div>
            </div>
        </div>
        <!-- 標頭 -->

        <!-- 目前案件 -->
        <div class="alesis-projects">
            <index-section header="目前案件">
                <div class="wrapper">
                    <div class="selector">
                        <button class="item -active">
                            <div class="text">全部</div>
                            <div class="label">99+</div>
                        </button>
                        <button class="item">
                            <div class="text">學生</div>
                            <div class="label">86</div>
                        </button>
                        <button class="item">
                            <div class="text">上班族</div>
                            <div class="label">60</div>
                        </button>
                    </div>
                    <div class="orders">
                        <a href="#!" class="item -active">上架日</a>
                        <a href="#!" class="item">金額</a>
                        <a href="#!" class="item">期數</a>
                        <a href="#!" class="item">年利率</a>
                        <a href="#!" class="item">信評</a>
                        <div class="item">
                            <div class="arrows">
                                <img src="/images/alesis-work-loan-arrow-up.svg" class="up">
                                <img src="/images/alesis-work-loan-arrow-down.svg" class="down">
                            </div>
                        </div>
                    </div>
                    <div class="cards">
                        <div class="item">
                            <index-project></index-project>
                        </div>
                        <div class="item">
                            <index-project></index-project>
                        </div>
                        <div class="item">
                            <index-project></index-project>
                        </div>
                        <div class="item">
                            <index-project></index-project>
                        </div>
                        <div class="item">
                            <index-project></index-project>
                        </div>
                        <div class="item">
                            <index-project></index-project>
                        </div>
                    </div>
                    <div class="paginations">
                        <a href="#!" class="item">上一頁</a>
                        <a href="#!" class="item">1</a>
                        <a href="#!" class="item">2</a>
                        <a href="#!" class="item">3</a>
                        <a href="#!" class="item">4</a>
                        <a href="#!" class="item">...</a>
                        <a href="#!" class="item">30</a>
                        <a href="#!" class="item">下一頁</a>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 目前案件 -->

        <!-- 已成交案件 -->
        <div class="alesis-projects">
            <index-section header="已成交案件">
                <div class="wrapper">
                    <div class="selector">
                        <button class="item -active">
                            <div class="text">全部</div>
                            <div class="label">99+</div>
                        </button>
                        <button class="item">
                            <div class="text">學生</div>
                            <div class="label">86</div>
                        </button>
                        <button class="item">
                            <div class="text">上班族</div>
                            <div class="label">60</div>
                        </button>
                    </div>
                    <div class="orders">
                        <a href="#!" class="item -active">上架日</a>
                        <a href="#!" class="item">金額</a>
                        <a href="#!" class="item">期數</a>
                        <a href="#!" class="item">年利率</a>
                        <a href="#!" class="item">信評</a>
                        <div class="item">
                            <div class="arrows">
                                <img src="/images/alesis-work-loan-arrow-up.svg" class="up">
                                <img src="/images/alesis-work-loan-arrow-down.svg" class="down">
                            </div>
                        </div>
                    </div>
                    <div class="cards">
                        <div class="item">
                            <index-project></index-project>
                        </div>
                        <div class="item">
                            <index-project></index-project>
                        </div>
                        <div class="item">
                            <index-project></index-project>
                        </div>
                        <div class="item">
                            <index-project></index-project>
                        </div>
                        <div class="item">
                            <index-project></index-project>
                        </div>
                        <div class="item">
                            <index-project></index-project>
                        </div>
                    </div>
                    <div class="paginations">
                        <a href="#!" class="item">上一頁</a>
                        <a href="#!" class="item">1</a>
                        <a href="#!" class="item">2</a>
                        <a href="#!" class="item">3</a>
                        <a href="#!" class="item">4</a>
                        <a href="#!" class="item">...</a>
                        <a href="#!" class="item">30</a>
                        <a href="#!" class="item">下一頁</a>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 已成交案件 -->
    </div>
</template>

<script>
// Alesis components
import indexCounter                    from "../component/alesis/index/counter";
import indexSection                    from "../component/alesis/index/section";
import indexBullet                     from "../component/alesis/index/bullet";
import indexMoon                       from "../component/alesis/index/moon";
import indexPlan                       from "../component/alesis/index/plan";
import indexHuman                      from "../component/alesis/index/human";
import indexButton                     from "../component/alesis/index/button";
import indexShanghai                   from "../component/alesis/index/shanghai";
import globalCarousel                  from "../component/alesis/global-carousel";
import indexProject                    from "../component/alesis/index/project";
import AlesisLoanHeader                from "../component/alesis/loan-header";
import AlesisApplicationRecommendation from "../component/alesis/application-recommendation";

export default {
    components: {
        indexCounter,
        indexSection,
        indexBullet,
        indexMoon,
        indexPlan,
        indexHuman,
        indexProject,
        indexButton,
        indexShanghai,
        globalCarousel,
        AlesisApplicationRecommendation,
        AlesisLoanHeader
    },
    data: () => ({

    }),
    created() {
        $("title").text(`首頁 - inFlux普匯金融科技`);
    },
    mounted() {
    },
    methods: {
        format(data) {
            data = parseInt(data);
            if (!isNaN(data)) {
                let l10nEN = new Intl.NumberFormat("en-US");
                return l10nEN.format(data.toFixed(0));
            }
            return 0;
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../component/alesis/alesis";

.alesis-slate {
    position: relative;
    height  : 33rem;

    .background {
        position: absolute;
        top     : 0;
        left    : 10rem;
        right   : 0;
        bottom  : 0;

        @include rwd {
            left: 0;
        }

        .image {
            height    : 100%;
            width     : 100%;
            object-fit: cover;
        }
    }

    .mask {
        background-image : url(/images/alesis-shell-mask.svg);
        position         : relative;
        z-index          : 1;
        display          : flex;
        flex-direction   : column;
        justify-content  : center;
        text-shadow      : 1px 1px 4px black;
        color            : #FFF;
        font-size        : 1.1rem;
        padding-left     : 12rem;
        background-size  : contain;
        background-repeat: no-repeat;
        min-width        : 1485px;
        height           : 100%;

        @include rwd {
            background-image: url(/images/alesis-wave-bg-rotated.svg);
            padding-left    : 0;
            background-size : 100vw;
            min-width       : initial;
            justify-content : initial;
            padding-top     : 11vw;
            align-items     : center;
        }

        .header {
            display      : inline-block;
            font-size    : 2.2rem;
            font-weight  : bolder;
            color        : #F2E627;
            border-bottom: 0;

            @include rwd {
                font-size: 1.4rem;
            }
        }

        .content {
            margin-top : 1rem;
            line-height: 1.8;
            font-size  : 1.3rem;

            @include rwd {
                font-size: 1rem;
            }
        }
    }
}

.alesis-projects {
    margin-top: 8rem;

    & + & {
        margin-top: 3rem;
    }

    .wrapper {
        margin: 0 auto;

        .cards {
            max-width            : 950px;
            margin               : 0 auto;
            display              : grid;
            grid-template-columns: repeat(2, 1fr);
            gap                  : 3rem;
            margin-top           : 2rem;

            @include rwd {
                grid-template-columns: 1fr;
            }

            .item {
                @include rwd {
                    display        : flex;
                    align-items    : center;
                    justify-content: center;
                }
            }
        }

        .paginations {
            display        : flex;
            width          : min-content;
            white-space    : nowrap;
            margin         : 0 auto;
            border         : 1px solid #326398;
            border-radius  : .4rem;
            align-items    : center;
            justify-content: center;
            margin-top     : 2rem;

            .item {
                line-height    : 1;
                color          : #326398;
                padding        : .5rem .7rem;
                font-size      : 1.1rem;
                border-right   : 1px solid #326398;
                vertical-align : top;
                text-decoration: none;

                @include rwd {
                    font-size: 1rem;
                    padding  : .5rem .5rem;
                }

                &:last-child {
                    border-right: 0;
                }
            }
        }

        .orders {
            display        : flex;
            align-items    : center;
            justify-content: center;
            margin-top     : 1rem;

            @include rwd {
                margin-top: 0;
            }

            .item {
                padding        : 1rem 3rem;
                font-size      : 1.2rem;
                color          : #AAAAAA;
                text-decoration: none;
                white-space    : nowrap;

                @include rwd {
                    padding  : 1rem .7rem;
                    font-size: 1rem;
                }

                &.-active {
                    color: #4D4D4D;
                }

                .arrows {
                    display       : flex;
                    flex-direction: column;

                    img {
                        width: 16px;

                        @include rwd {
                            width: 12px;
                        }
                    }
                }
            }

        }

        > .selector {
            display        : flex;
            align-items    : center;
            justify-content: center;
            gap            : 1rem;

            .item {
                border       : 1px solid #326398;
                border-radius: .4rem;
                background   : transparent;
                appearance   : none;
                padding      : .25rem .8rem;
                font-size    : 1.5rem;
                display      : flex;
                align-items  : center;
                gap          : .8rem;
                white-space  : nowrap;
                color        : #326398;

                @include rwd {
                    font-size: 1rem;
                    padding  : .25rem .5rem;
                    gap      : .4rem;
                }

                &.-active {
                    background: #326398;
                    color     : #FFF;
                }

                .text {

                }

                .label {
                    font-size    : 1rem;
                    border       : 1px solid #326398;
                    padding      : .2rem .5rem;
                    border-radius: .4rem;
                    line-height  : 1;
                    color        : #326398;
                    background   : #FFF;

                    @include rwd {
                        font-size: .8rem;
                    }
                }
            }

        }

        > .header {
            text-align      : center;
            background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
            background-clip : text;
            width           : fit-content;
            color           : rgba(255, 255, 255, 0);
            font-weight     : bolder;
            font-size       : 1.9rem;
            line-height     : 1.2;
            white-space     : nowrap;
            border-bottom   : 1px solid #2664A5;
            padding-bottom  : .5rem;
            padding-right   : 1rem;
            margin-bottom   : 2rem;
            margin-left     : 4rem;

            @include rwd {
                font-size     : 1.5rem;
                padding-bottom: 0.25rem;
                margin-left   : 3rem;
                padding-right : 1rem;
            }

            &.-fast {
                margin-top: -1rem;
            }
        }

        .carousel .selector .lightbox .set {
            @include rwd {
                grid-template-columns: 1fr;
            }
        }

        .index-card {
            min-width: 450px;
            max-width: 450px;
        }
    }
}
</style>
