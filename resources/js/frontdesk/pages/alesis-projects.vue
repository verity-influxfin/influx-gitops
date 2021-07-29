<template>
    <div>
        <!-- 頂部板岩 -->
        <div class="頂部板岩">
            <div class="背景">
                <img class="圖片" src="/images/sshot-1907.png">
            </div>
            <div class="遮罩">
                <div class="標題">查看案件</div>
                <div class="內容">
                    獨立債權，多元投資<br>
                    超高報酬，穩定收入
                </div>
            </div>
        </div>
        <!-- / 頂部板岩 -->

        <!-- 案件區塊 -->
        <div class="案件區塊">
            <alesis-section header="目前案件">
                <div class="包裹容器">
                    <div class="分類">
                        <button class="項目 項目_啟用的">
                            <div class="文字">全部</div>
                            <div class="標籤">99+</div>
                        </button>
                        <button class="項目">
                            <div class="文字">學生</div>
                            <div class="標籤">86</div>
                        </button>
                        <button class="項目">
                            <div class="文字">上班族</div>
                            <div class="標籤">60</div>
                        </button>
                    </div>
                    <div class="排序條件">
                        <a href="#!" class="項目 項目_啟用的">上架日</a>
                        <a href="#!" class="項目">金額</a>
                        <a href="#!" class="項目">期數</a>
                        <a href="#!" class="項目">年利率</a>
                        <a href="#!" class="項目">信評</a>
                        <div class="項目">
                            <div class="箭頭群組">
                                <img src="/images/alesis-work-loan-arrow-up.svg" class="上">
                                <img src="/images/alesis-work-loan-arrow-down.svg" class="下">
                            </div>
                        </div>
                    </div>
                    <div class="卡片列表">
                        <div class="項目">
                            <alesis-project></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project></alesis-project>
                        </div>
                    </div>
                    <div class="分頁導覽">
                        <a href="#!" class="項目">上一頁</a>
                        <a href="#!" class="項目">1</a>
                        <a href="#!" class="項目">2</a>
                        <a href="#!" class="項目">3</a>
                        <a href="#!" class="項目">4</a>
                        <a href="#!" class="項目">...</a>
                        <a href="#!" class="項目">30</a>
                        <a href="#!" class="項目">下一頁</a>
                    </div>
                </div>
            </alesis-section>
        </div>
        <!-- / 案件區塊 -->

        <!-- 案件區塊 -->
        <div class="案件區塊">
            <alesis-section header="已成交案件">
                <div class="包裹容器">
                    <div class="分類">
                        <button class="項目 項目_啟用的">
                            <div class="文字">全部</div>
                            <div class="標籤">99+</div>
                        </button>
                        <button class="項目">
                            <div class="文字">學生</div>
                            <div class="標籤">86</div>
                        </button>
                        <button class="項目">
                            <div class="文字">上班族</div>
                            <div class="標籤">60</div>
                        </button>
                    </div>
                    <div class="排序條件">
                        <a href="#!" class="項目 項目_啟用的">上架日</a>
                        <a href="#!" class="項目">金額</a>
                        <a href="#!" class="項目">期數</a>
                        <a href="#!" class="項目">年利率</a>
                        <a href="#!" class="項目">信評</a>
                        <div class="項目">
                            <div class="箭頭群組">
                                <img src="/images/alesis-work-loan-arrow-up.svg" class="上">
                                <img src="/images/alesis-work-loan-arrow-down.svg" class="下">
                            </div>
                        </div>
                    </div>
                    <div class="卡片列表">
                        <div class="項目">
                            <alesis-project></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project></alesis-project>
                        </div>
                    </div>
                    <div class="分頁導覽">
                        <a href="#!" class="項目">上一頁</a>
                        <a href="#!" class="項目">1</a>
                        <a href="#!" class="項目">2</a>
                        <a href="#!" class="項目">3</a>
                        <a href="#!" class="項目">4</a>
                        <a href="#!" class="項目">...</a>
                        <a href="#!" class="項目">30</a>
                        <a href="#!" class="項目">下一頁</a>
                    </div>
                </div>
            </alesis-section>
        </div>
        <!-- / 案件區塊 -->
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

/**
 * 頂部板岩
 */

.頂部板岩 {
    position: relative;
    height  : 33rem;

    @include rwd {
        height: 119vw;
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
        top : 50vw;
    }
}
.頂部板岩 .背景 .圖片 {
    height    : 100%;
    width     : 100%;
    object-fit: cover;
}

.頂部板岩 .背景 .遮罩 {
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
}

.頂部板岩 .背景 .遮罩 .標題 {
    display      : inline-block;
    font-size    : 2.2rem;
    font-weight  : bolder;
    color        : #F2E627;
    border-bottom: 0;

    @include rwd {
        font-size: 1.4rem;
    }
}

.頂部板岩 .背景 .遮罩 .內容 {
    margin-top : 1rem;
    line-height: 1.8;
    font-size  : 1.3rem;

    @include rwd {
        font-size: 1rem;
    }
}

/**
 * 案件區塊
 */

.案件區塊 {
    margin-top: 8rem;
}

.案件區塊 + .案件區塊 {
    margin-top: 3rem;
}

.案件區塊 .包裹容器 {
    margin: 0 auto;
}

.案件區塊 .包裹容器 .分類 {
    display        : flex;
    align-items    : center;
    justify-content: center;
    gap            : 1rem;
}

.案件區塊 .包裹容器 .分類 .項目 {
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
}

.案件區塊 .包裹容器 .分類 .項目.項目_啟用的 {
    background: #326398;
    color     : #FFF;
}

.案件區塊 .包裹容器 .分類 .項目 .標籤 {
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

.案件區塊 .包裹容器 .排序條件 {
    display        : flex;
    align-items    : center;
    justify-content: center;
    margin-top     : 1rem;

    @include rwd {
        margin-top: 0;
    }
}

.案件區塊 .包裹容器 .排序條件 .項目 {
    padding        : 1rem 3rem;
    font-size      : 1.2rem;
    color          : #AAAAAA;
    text-decoration: none;
    white-space    : nowrap;

    @include rwd {
        padding  : 1rem .7rem;
        font-size: 1rem;
    }
}

.案件區塊 .包裹容器 .排序條件 .項目.項目_啟用的 {
    color: #4D4D4D;
}

.案件區塊 .包裹容器 .排序條件 .項目 .箭頭群組 {
    display       : flex;
    flex-direction: column;
}

.案件區塊 .包裹容器 .排序條件 .項目 .箭頭群組 img {
    width: 16px;

    @include rwd {
        width: 12px;
    }
}

.案件區塊 .包裹容器 .卡片列表 {
    max-width            : 950px;
    margin               : 0 auto;
    display              : grid;
    grid-template-columns: repeat(2, 1fr);
    gap                  : 3rem;
    margin-top           : 2rem;

    @include rwd {
        grid-template-columns: 1fr;
    }
}

.案件區塊 .包裹容器 .卡片列表 > .項目 {
    @include rwd {
        display        : flex;
        align-items    : center;
        justify-content: center;
        transform      : scale(0.8) translateX(4.3rem);
        margin-left    : -7rem;
        margin-top     : -3rem;
    }
}

.案件區塊 .包裹容器 .卡片列表 .項目 .分頁導覽 {
    display        : flex;
    width          : min-content;
    white-space    : nowrap;
    margin         : 0 auto;
    border         : 1px solid #326398;
    border-radius  : .4rem;
    align-items    : center;
    justify-content: center;
    margin-top     : 2rem;
}

.案件區塊 .包裹容器 .卡片列表 .項目 .分頁導覽 .項目 {
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
}

.案件區塊 .包裹容器 .卡片列表 .項目 .分頁導覽 .項目:last-child {
    border-right: 0;
}
</style>
