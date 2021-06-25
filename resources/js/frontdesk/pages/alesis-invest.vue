<template>
    <div class="index-wrapper">
        <!-- 標頭 -->
        <div class="alesis-slate">
            <div class="background">
                <img class="image" src="/images/sshot-2112.png" alt="">
            </div>
            <div class="mask">
                <div class="header">
                    <div class="text">普匯投資</div>
                </div>
            </div>
            <div class="showcase">
                <img class="image" src="/images/直手機-14.png" alt="">
            </div>
        </div>
        <!-- 標頭 -->

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
                    <a class="more" href="#!"><index-button yPadding="0.9rem" xPadding="2.5rem">查看更多</index-button></a>
                </div>

            </index-section>
        </div>
        <div class="alesis-projects" >
            <index-section header="現有案件" :secondary="true">
                <div class="wrapper">
                    <div class="header">優質項目專區</div>
                    <global-carousel>
                        <div class="set">
                            <div class="item">
                                <index-project></index-project>
                            </div>
                            <div class="item">
                                <index-project></index-project>
                            </div>
                        </div>
                        <div class="set">
                            <div class="item">
                                <index-project></index-project>
                            </div>
                            <div class="item">
                                <index-project></index-project>
                            </div>
                        </div>
                    </global-carousel>
                </div>
            </index-section>
            <index-section>
                <div class="wrapper">
                    <div class="header -fast">快速變現專區</div>
                    <global-carousel>
                        <div class="set">
                            <div class="item">
                                <index-project></index-project>
                            </div>
                            <div class="item">
                                <index-project></index-project>
                            </div>
                        </div>
                        <div class="set">
                            <div class="item">
                                <index-project></index-project>
                            </div>
                            <div class="item">
                                <index-project></index-project>
                            </div>
                        </div>
                    </global-carousel>
                </div>
            </index-section>
        </div>

        <!-- 應用程式推薦 -->
        <alesis-application-recommendation></alesis-application-recommendation>
        <!-- / 應用程式推薦 -->
    </div>
</template>
<script>
//
import indexSection                    from "../component/alesis/index/section";
import indexSymcard                    from "../component/alesis/index/symcard";
import indexHuman                      from "../component/alesis/index/human";
import indexButton                     from "../component/alesis/index/button";
import indexProject                    from "../component/alesis/index/project";
import AlesisApplicationRecommendation from "../component/alesis/application-recommendation";
import globalCarousel                  from "../component/alesis/global-carousel";

export default {
    components: {
        indexSection,
        indexSymcard,
        indexHuman,
        indexProject,
        indexButton,
        globalCarousel,
        AlesisApplicationRecommendation
    },
};
</script>


<style lang="scss">
@import "../component/alesis/alesis";

.alesis-projects {
    .sectionx {
        @include rwd {
            background: none !important;
        }
    }



    .wrapper {

        .carousel {
            @include rwd {
                margin: -7rem 0;
            }

            .lightbox {
                @include rwd {
                    width: 230px;
                }

                .set {
                    @include rwd {
                        grid-template-columns: 1fr !important;
                        transform: scale(0.6) translateX(-50%);
                        width    : 220px;
                    }
                }
            }

            .left, .right {
                img {
                    @include rwd {
                        width: 10px;
                    }
                }
            }
        }

    }
}
</style>

<style lang="scss" scoped>
@import "../component/alesis/alesis";

.alesis-slate {
    position: relative;
    height  : 33rem;

    @include rwd {
        height: 162vw;
    }

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
            padding-top     : 21vw;
            align-items     : center;
        }

        .header {
            padding-bottom         : 0.2rem;
            border-bottom          : 1px solid #FFF;
            display                : inline-block;
            font-weight            : bolder;
            background-image       : linear-gradient(to right, #e7e7f4 0%, #4fb7ec 50%, #c4c4fc 75%);
            background-clip        : text;
            width                  : fit-content;
            color                  : rgba(255, 255, 255, 0);
            font-size              : 2rem;
            line-height            : 1.2;

            @include rwd {
                font-size: 1.8rem;
            }
        }
    }

    .showcase {
        position: absolute;
        right   : 0;
        left    : calc(50vw + 490px / 5);
        bottom  : 0;

        @include rwd {
            left     : 50%;
            transform: translateX(-50%);
        }

        .image {
            height: 490px;

            @include rwd {
                height: 94vw;
            }
        }
    }
}

.alesis-types {
    margin-top: 7rem;

    .items {
        display              : grid;
        grid-template-columns: repeat(2, 1fr);
        max-width            : 830px;
        margin               : 0 auto;
        gap                  : 3rem;

        @include rwd {
            grid-template-columns: 1fr;
        }

        .item {
            border       : 1px solid #2664a5;
            padding      : 2.5rem 1rem;
            border-radius: 1rem;

            @include rwd {
                width      : min-content;
                white-space: nowrap;
                margin     : 0 auto;
                min-width  : 19rem;
            }

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

                @include rwd {
                    font-size: 1.3rem;
                }
            }

            .divider {
                height    : 1px;
                background: #2664a5;
                margin    : 2rem 0;

                @include rwd {
                    margin: 1rem 0;
                }
            }

            .features {
                display       : flex;
                padding       : 0 1rem;
                flex-direction: column;

                .feature {
                    font-size  : 1.2rem;
                    color      : #5d5555;

                    @include rwd {
                        font-size: 1rem;
                    }

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

.alesis-projects {
    .wrapper {
        width : min-content;
        margin: 0 auto;

        .header {
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
            padding-right   : 2rem;
            margin-bottom   : 2rem;
            margin-left     : 4rem;

            @include rwd {
                font-size     : 1.5rem;
                padding-bottom: 0.25rem;
                margin-left   : 2rem;
                padding-right : 1rem;
            }

            &.-fast {
                margin-top: -1rem;
            }
        }

        .set {
            @include rwd {
                grid-template-columns: 1fr;
            }
        }

        .index-card {
            min-width: 450px;
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

        @include rwd {
            grid-template-areas  : none;
            grid-template-columns: 1fr;
            grid-template-rows   : initial;
            gap                  : 2rem;
        }

        .B, .C {
            img {
                width: 530px;

                @include rwd {
                    width: 250px;
                }
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

        .A, .B, .C {
            text-align: center;

            @include rwd {
                grid-area: initial;
            }
        }

        img {
            width: 580px;

            @include rwd {
                width: 300px;
            }
        }
    }
}

.alesis-app {
    .wrapper {
        background-image: url('/images/sshot-1644.png');
        background-size : cover;
        display         : flex;
        align-items     : center;
        justify-content : center;
        min-height      : 34rem;
        position        : relative;

        @include rwd {
            flex-direction: column;
        }

        .content {
            flex      : 1;
            text-align: center;

            @include rwd {
                margin-top   : 3rem;
                margin-bottom: 3rem;
            }

            .button {
                text-align      : center;
                background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
                background-clip : text;
                width           : fit-content;
                color           : rgba(255, 255, 255, 0);
                font-weight     : bolder;
                font-size       : 1.9rem;
                line-height     : 1;
                white-space     : nowrap;
                border-bottom   : 1px solid #2664A5;
                padding         : .25rem 1.5rem;
                border-radius   : 1rem;
                border          : 2px solid #036eb7;
            }
            .divider {
                background: #2f95cb;
                height    : 1px;
                margin    : 2rem auto 2rem;
                width     : 330px;

                @include rwd {
                    width: 270px;
                }
            }
            .description {
                font-size  : 1.1rem;
                font-weight: bolder;
                color      : #112e53;
            }
            .markets {
                margin-top: 2rem;

                .link {
                    &:first-child {
                        margin-right: 1rem;
                    }

                    img {
                        @include rwd {
                            width: 160px;
                        }
                    }
                }
            }
        }
        .demo {
            flex: 1;

            .phone {
                @include rwd {
                    width        : 380px;
                    margin-bottom: 2rem;
                }
            }

            .edge {
                position: absolute;
                top     : 0;
                right   : 0;
                bottom  : 0;
                height  : 100%;
            }
        }
    }
}

.alesis-report {
    .wrapper {
         text-align: center;


        .cover {

            .image {
                @include rwd {
                    width: 60vw;
                }
            }
        }
        .more {
            margin-top: 3rem;
            display   : inline-block;
        }
    }
}
</style>
