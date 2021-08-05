<template>
    <div>
        <!-- 頂部板岩 -->
        <div class="頂部板岩">
            <div class="背景">
                <img class="圖片" src="/images/sshot-2112.png">
            </div>
            <div class="遮罩">
                <div class="標題">
                    <div>普匯投資</div>
                </div>
            </div>
            <div class="展示區域">
                <img class="圖片" src="/images/直手機-14.png">
            </div>
        </div>
        <!-- 頂部板岩 -->

        <!-- 債權種類 -->
        <div class="債權種類">
            <alesis-header>
                <div class="標題">債權種類</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="medium"></alesis-space>
                <div class="項目清單">
                    <div class="項目">
                        <div class="標題">債權投資</div>
                        <div class="分隔線"></div>
                        <div class="特色列表">
                            <div class="特色">
                                <span class="顯著的">自選債權：</span>
                                優良債權　自由選擇
                            </div>
                            <div class="特色">
                                <span class="顯著的">智能投資：</span>
                                設定喜好　AI自動投標
                            </div>
                        </div>
                    </div>
                    <div class="項目">
                        <div class="標題">債權轉讓</div>
                        <div class="分隔線"></div>
                        <div class="特色列表">
                            <div class="特色 特色_置中的">
                                轉讓債權　立即變現
                            </div>
                        </div>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 債權種類 -->

        <!-- 對比區域 -->
        <div class="對比區域">
            <alesis-header>
                <div class="標題">投資工具比一比</div>
            </alesis-header>
            <alesis-section :secondary="true">
                <alesis-space size="medium"></alesis-space>
                <div class="網格系統">
                    <div class="甲">
                        <img src="/images/invest_chart_a.svg">
                    </div>
                    <div class="乙">
                        <img src="/images/invest_chart_b.png">
                    </div>
                    <div class="丙">
                        <img src="/images/invest_chart_c.png">
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 對比區域 -->

        <!-- 風險報告 -->
        <div class="風險報告">
            <alesis-header>
                <div class="標題">債權風險報告書</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="medium"></alesis-space>
                <div class="包裹容器">
                    <div class="封面">
                        <img class="圖片" src="/images/報告書.png">
                    </div>
                    <a class="更多" href="/risk">
                        <alesis-button yPadding="0.9rem" xPadding="2.5rem">查看更多</alesis-button>
                    </a>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 風險報告 -->

        <!-- 現有案件 -->
        <div class="現有案件">
            <alesis-header>
                <div class="標題">現有案件</div>
            </alesis-header>
            <alesis-section header="現有案件" :secondary="true">
                <alesis-space size="medium"></alesis-space>
                <div class="包裹容器">
                    <div class="標題">優質項目專區</div>
                    <div class="swiper-container 幻燈片 幻燈片_優質項目">
                        <div class="swiper-wrapper">
                            <!-- 群組 -->
                            <div class="swiper-slide" v-if="goodCase[0] || goodCase[1]">
                                <div class="群組">
                                    <div class="項目" v-if="goodCase[0]">
                                        <alesis-project v-bind="goodCase[0]"></alesis-project>
                                    </div>
                                    <div class="項目" v-if="goodCase[1]">
                                        <alesis-project v-bind="goodCase[1]"></alesis-project>
                                    </div>
                                </div>
                            </div>
                            <!-- / 群組 -->

                            <!-- 群組 -->
                            <div class="swiper-slide" v-if="goodCase[2] || goodCase[3]">
                                <div class="群組">
                                    <div class="項目" v-if="goodCase[2]">
                                        <alesis-project v-bind="goodCase[2]"></alesis-project>
                                    </div>
                                    <div class="項目" v-if="goodCase[3]">
                                        <alesis-project v-bind="goodCase[3]"></alesis-project>
                                    </div>
                                </div>
                            </div>
                            <!-- / 群組 -->

                            <!-- 群組 -->
                            <div class="swiper-slide" v-if="goodCase[4] || goodCase[5]">
                                <div class="群組">
                                    <div class="項目" v-if="goodCase[4]">
                                        <alesis-project v-bind="goodCase[4]"></alesis-project>
                                    </div>
                                    <div class="項目" v-if="goodCase[5]">
                                        <alesis-project v-bind="goodCase[5]"></alesis-project>
                                    </div>
                                </div>
                            </div>
                            <!-- / 群組 -->
                        </div>

                        <div class="swiper-pagination"></div>


                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
            <alesis-section>
                <div class="包裹容器">
                    <div class="標題 標題_快速的">快速變現專區</div>
                    <div class="swiper-container 幻燈片 幻燈片_快速變現">
                        <div class="swiper-wrapper">
                            <!-- 群組 -->
                            <div class="swiper-slide" v-if="transferCase[0] || transferCase[1]">
                                <div class="群組">
                                    <div class="項目" v-if="transferCase[0]">
                                        <transferCase v-bind="transferCase[0]"></transferCase>
                                    </div>
                                    <div class="項目" v-if="transferCase[1]">
                                        <transferCase v-bind="transferCase[1]"></transferCase>
                                    </div>
                                </div>
                            </div>
                            <!-- / 群組 -->

                            <!-- 群組 -->
                            <div class="swiper-slide" v-if="transferCase[2] || transferCase[3]">
                                <div class="群組">
                                    <div class="項目" v-if="transferCase[2]">
                                        <transferCase v-bind="transferCase[2]"></transferCase>
                                    </div>
                                    <div class="項目" v-if="transferCase[3]">
                                        <transferCase v-bind="transferCase[3]"></transferCase>
                                    </div>
                                </div>
                            </div>
                            <!-- / 群組 -->

                            <!-- 群組 -->
                            <div class="swiper-slide" v-if="transferCase[4] || transferCase[5]">
                                <div class="群組">
                                    <div class="項目" v-if="transferCase[4]">
                                        <transferCase v-bind="transferCase[4]"></transferCase>
                                    </div>
                                    <div class="項目" v-if="transferCase[5]">
                                        <transferCase v-bind="transferCase[5]"></transferCase>
                                    </div>
                                </div>
                            </div>
                            <!-- / 群組 -->
                        </div>

                        <div class="swiper-pagination"></div>


                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 現有案件 -->

        <!-- 應用程式推薦 -->
        <alesis-app-recommendation></alesis-app-recommendation>
        <!-- / 應用程式推薦 -->
    </div>
</template>
<script>
// Alesis 元件
import AlesisAppRecommendation from "../component/alesis/AlesisAppRecommendation";
import AlesisBullet            from "../component/alesis/AlesisBullet";
import AlesisButton            from "../component/alesis/AlesisButton";
import AlesisCounter           from "../component/alesis/AlesisCounter";
import AlesisHeader            from "../component/alesis/AlesisHeader";
import AlesisHorizontalRoadmap from "../component/alesis/AlesisHorizontalRoadmap";
import AlesisHuman             from "../component/alesis/AlesisHuman";
import AlesisLoanHeader        from "../component/alesis/AlesisLoanHeader";
import AlesisMoon              from "../component/alesis/AlesisMoon";
import AlesisPlan              from "../component/alesis/AlesisPlan";
import AlesisProject           from "../component/alesis/AlesisProject";
import AlesisSection           from "../component/alesis/AlesisSection";
import AlesisShanghai          from "../component/alesis/AlesisShanghai";
import AlesisSuggestionReviews from "../component/alesis/AlesisSuggestionReviews";
import AlesisSymcard           from "../component/alesis/AlesisSymcard";
import AlesisTaiwanMap         from "../component/alesis/AlesisTaiwanMap";
import AlesisVerticalRoadmap   from "../component/alesis/AlesisVerticalRoadmap";
import AlesisSpace             from "../component/alesis/AlesisSpace";
import TransferCase             from "../component/TransferCase";

import 'swiper/swiper.scss';
import "swiper/components/navigation/navigation.min.css"
import SwiperCore, {
  Navigation
} from 'swiper/core';

export default {
    components: {
        AlesisAppRecommendation,
        AlesisBullet,
        AlesisButton,
        AlesisCounter,
        AlesisHeader,
        AlesisHorizontalRoadmap,
        AlesisHuman,
        AlesisLoanHeader,
        AlesisMoon,
        AlesisPlan,
        AlesisProject,
        AlesisSection,
        AlesisShanghai,
        AlesisSuggestionReviews,
        AlesisSymcard,
        AlesisTaiwanMap,
        AlesisVerticalRoadmap,
        AlesisSpace,
        TransferCase
    },
    data: () => ({
        goodCase : {},
        transferCase : {},
    }),
    created() {
        this.getGoodCase();
        this.getTransferCase();
    },
    mounted() {
        SwiperCore.use([Navigation]);
        new Swiper('.swiper-container.幻燈片_優質項目', {
            pagination: {
                el: '.swiper-pagination',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });

        new Swiper('.swiper-container.幻燈片_快速變現', {
            pagination: {
                el: '.swiper-pagination',
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    },
    methods: {
        // 優質項目專區
        getGoodCase() {
            let studenCase = new FormData();
            studenCase.append('status',3);
            studenCase.append('product_id',0);

            self = this;
            axios.post(
                `${location.origin}/getCase`,
                studenCase
            ).then((res) => {
                self.goodCase = res.data
            })
            .catch((error) => {
                console.error('getCase 發生錯誤，請稍後再試');
            });
        },
        // 快速變現
        getTransferCase() {
            self = this;
            axios.post(
                `${location.origin}/getTransferCase`
            ).then((res) => {
                self.transferCase = res.data
            })
            .catch((error) => {
                console.error('getTransferCase 發生錯誤，請稍後再試');
            });
        },
    }
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

/**
 * 頂部板岩
 */

.頂部板岩 {
    position: relative;
    height  : 33rem;

    @include rwd {
        height: 162vw;
    }
}

.頂部板岩 .背景 {
    position: absolute;
    top     : 0;
    left    : 10rem;
    right   : 0;
    bottom  : 0;

    @include rwd {
        left: 0;
    }
}

.頂部板岩 .背景 .圖片 {
    height    : 100%;
    width     : 100%;
    object-fit: cover;
}


.頂部板岩 .遮罩 {
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
}

.頂部板岩 .遮罩 .標題 {
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

.頂部板岩 .展示區域 {
    position: absolute;
    right   : 0;
    left    : calc(50vw + 490px / 5);
    bottom  : 0;
    overflow: hidden;
    top     : 2rem;


    @include rwd {
        left     : 50%;
        transform: translateX(-50%);
        top      : initial;
    }
}

.頂部板岩 .展示區域 .圖片 {
    height: 620px;

    @include rwd {
        height: 94vw;
    }
}

/**
 * 債權種類
 */

.債權種類 {
    position  : relative;
    margin-top: 7rem;
}

.債權種類 .項目清單 {
    display              : grid;
    grid-template-columns: repeat(2, 1fr);
    max-width            : 830px;
    margin               : 0 auto;
    gap                  : 3rem;

    @include rwd {
        grid-template-columns: 1fr;
    }
}

.債權種類 .項目清單 .項目 {
    border       : 1px solid #2664a5;
    padding      : 2.5rem 1rem;
    border-radius: 1rem;

    @include rwd {
        width      : min-content;
        white-space: nowrap;
        margin     : 0 auto;
        min-width  : 19rem;
    }
}

.債權種類 .項目清單 .項目 .標題 {
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

.債權種類 .項目清單 .項目 .分隔線 {
    height    : 1px;
    background: #2664a5;
    margin    : 2rem 0;

    @include rwd {
        margin: 1rem 0;
    }
}

.債權種類 .項目清單 .項目 .特色列表 {
    display       : flex;
    padding       : 0 1rem;
    flex-direction: column;
}

.債權種類 .項目清單 .項目 .特色列表 .特色 {
    font-size  : 1.2rem;
    color      : #5d5555;

    @include rwd {
        font-size: 1rem;
    }
}

.債權種類 .項目清單 .項目 .特色列表 .特色.特色_置中的 {
    margin: 0 auto;
}

.債權種類 .項目清單 .項目 .特色列表 .特色 .顯著的 {
    font-weight: bolder;
    color      : #112e53;
}

.債權種類 .項目清單 .項目 .特色列表 .特色:not(:last-child) {
    margin-bottom: 1rem;
}

/**
 * 現有案件
 */

.現有案件 {
    position: relative;
}

.現有案件 .包裹容器 {
    width : min-content;
    margin: 0 auto;
}

.現有案件 .包裹容器 .標題 {
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
    margin-left     : 6rem;

    @include rwd {
        font-size     : 1.5rem;
        padding-bottom: 0.25rem;
        margin-left   : 2rem;
        padding-right : 1rem;
    }
}

.現有案件 .包裹容器 .標題.標題_快速的 {
    margin-top: -1rem;
}

.現有案件 .包裹容器 .幻燈片 {
    width         : 1050px;
    padding-bottom: 2rem;

    @include rwd {
        width: 80vw;
    }
}

.現有案件 .包裹容器 .幻燈片 .swiper-button-prev,
.現有案件 .包裹容器 .幻燈片 .swiper-button-next {
    transform: scale(0.5);
}

.現有案件 .包裹容器 .幻燈片 .群組 {
    grid-template-columns: 1fr 1fr;
    display              : grid;
    margin               : 0 6rem;
    gap                  : 4rem;
    padding-bottom       : 1rem;

    @include rwd {
        grid-template-columns: 1fr;
        gap                  : 0;
        margin               : 0 -1rem;
    }
}

.現有案件 .包裹容器 .幻燈片 .項目 {
    @include rwd {
        transform: scale(0.7);
    }
}

.現有案件 .包裹容器 .幻燈片.幻燈片_優質項目 {

}

.現有案件 .包裹容器 .幻燈片.幻燈片_快速變現 {

}

.alesis-projects {
    .wrapper {

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

/**
 * 對比區域
 */

.對比區域 {
    position: relative;
}

.對比區域 .網格系統 {
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
}

.對比區域 .網格系統 .甲 {
    grid-area: A;
}

.對比區域 .網格系統 .乙 {
    grid-area: B;
}

.對比區域 .網格系統 .丙 {
    grid-area: C;
}

.對比區域 .網格系統 .甲,
.對比區域 .網格系統 .乙,
.對比區域 .網格系統 .丙 {
    text-align: center;

    @include rwd {
        grid-area: initial;
    }
}

.對比區域 .網格系統 .乙 img,
.對比區域 .網格系統 .丙 img {
    width: 530px;

    @include rwd {
        width: 250px;
    }
}

.對比區域 .網格系統 .甲 img {
    width: 580px;

    @include rwd {
        width: 300px;
    }
}

/**
 * 風險報告
 */

.風險報告 {
    position: relative;
}

.風險報告 .包裹容器 {
    text-align: center;
}

.風險報告 .包裹容器 .封面 .圖片 {
    @include rwd {
        width: 60vw;
    }
}

.風險報告 .包裹容器 .更多 {
    margin-top: 3rem;
    display   : inline-block;
}
</style>
