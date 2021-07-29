<template>
    <div>
        <!-- 頂部板岩 -->
        <div class="頂部板岩">
            <div class="標題">
                <div class="文字">產品方案</div>
            </div>
            <div class="手機區塊">
                <div class="左側">
                    <div class="手機">
                        <img src="/images/alesis-phone-slide-left.svg" class="圖片">
                    </div>
                </div>
                <div class="右側">
                    <div class="手機">
                        <img src="/images/alesis-phone-slide-right-2.svg" class="圖片">
                    </div>
                    <div class="手機">
                        <img src="/images/alesis-phone-slide-right.svg" class="圖片">
                    </div>
                </div>
            </div>
        </div>
        <!-- / 頂部板岩 -->

        <alesis-space size="tiny"></alesis-space>

        <!-- 特色區域 -->
        <div class="特色區域">
            <alesis-header>
                <div class="標題">簡單快速專區</div>
                <div class="標題 標題_外框線的">全線上自動審核 無人打擾24小時立即到帳</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="small"></alesis-space>
                <div class="卡片區塊">
                    <alesis-symcard class="symcard animate__animated" image="/images/business-team-manager-meeting1.jpg" header="企業融資" symbol="/images/alesis-companies.svg" description="支持中小企業，協助媒合百萬營運資金"></alesis-symcard>
                    <alesis-symcard class="symcard animate__animated" image="/images/螢幕擷取畫面-2021-07-14-113234.jpg" header="上班族貸" subheader="產品申請手續費4%" symbol="/images/alesis-work.svg" description="急需資金不再慌張，AI審核立即入帳"></alesis-symcard>
                    <alesis-symcard class="symcard animate__animated" image="/images/asian-student-sitting-campus-stairs-outdoors-with-smartphone-staring-distance1.jpg" header="學生貸" subheader="產品申請手續費3%" symbol="/images/alesis-student.svg" description="輕鬆分期不再煩腦，學生社群口碑最好"></alesis-symcard>
                    <alesis-symcard class="symcard animate__animated" image="/images/20200914172847-028f5b7b-me.jpg" header="車輛融資" symbol="/images/alesis-car.svg" description="敬請期待"></alesis-symcard>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 特色區域 -->

        <!-- 普匯推薦 -->
        <div class="普匯推薦">
            <alesis-header>
                <div class="標題">普匯推薦 銀行速貸好條件</div>
                <div class="標題">透過普匯結盟夥伴，可享最高額度300萬，超低利率1.88%起！</div>
            </alesis-header>
            <alesis-section :secondary="true">
                <alesis-space size="medium"></alesis-space>
                <div class="包裹容器">
                    <alesis-shanghai></alesis-shanghai>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 普匯推薦 -->

        <!-- 聽聽他們怎麼說 -->
        <div class="推薦人物">
            <alesis-header class="區塊標題_拖尾的">
                <div class="標題 標題_外框線的">案例分享 / 用戶分享</div>
            </alesis-header>
            <alesis-section header="聽聽他們怎麼說" :outlined="true">
                <alesis-space size="medium"></alesis-space>
                <alesis-suggestion-reviews type="borrow"></alesis-suggestion-reviews>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 聽聽他們怎麼說 -->
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
    mounted() {
        document.querySelectorAll(".symcard").forEach((v, i) => {
            setTimeout(() => {
                v.classList.add("animate__flipInY")
            }, 300 * i)
        })
    },
};
</script>

<style lang="scss">
@import "../component/alesis/alesis";

.alesis-features {
    .cards {

        > .item {
            .header {
                @include rwd {
                    padding-left: 0;
                }
            }

            .header .text .header {
                @include rwd {
                    font-size    : 1.1rem !important;
                }
            }

            .content .description {
                @include rwd {
                    font-size: .8rem !important;
                }
            }

            .symbol {
                @include rwd {
                    width: 70px !important;
                }

                .image {
                    @include rwd {
                        width : 50px !important;
                        height: 50px !important;
                    }
                }

                .wrapper {
                    @include rwd {
                        top : -2rem  !important;
                        left: 0.5rem !important;
                    }
                }
            }
        }
    }
}
</style>

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

/**
 * 頂部板岩
 */

.頂部板岩 {
    display         : flex;
    flex-direction  : column;
    background-image: url("/images/未命名-2.png");
    background-size : cover;
    padding         : 2rem 0 4rem;
    margin-bottom   : 8rem;

    @include rwd {
        height: 53rem;
    }
}

.頂部板岩 .標題 {
    text-align: center;
}

.頂部板岩 .標題 .文字 {
    color         :#FFF;
    padding-bottom: .2rem;
    border-bottom : 1px solid #FFF;
    display       : inline-block;
    font-size     : 1.8rem;
    font-weight   : bolder;
}

.頂部板岩 .手機區塊 {
    display  : flex;
    max-width: 1280px;
    margin   : 3rem auto 0;
    position : relative;

    @include rwd {
        flex-direction: column-reverse;
    }
}

.頂部板岩 .手機區塊 .左側 {
    flex      : 1;
    text-align: center;
    position  : relative;
}

.頂部板岩 .手機區塊 .左側 .手機 {
    position : absolute;
    left     : -29rem;
    animation: fadein3 2s;

    @include rwd {
        left: -2rem;
        top : 15rem;
    }
}

.頂部板岩 .手機區塊 .右側 {
    flex       : 1;
    display    : flex;
    margin-left: 8rem;
    position   : relative;

    @include rwd {
        margin-left: 0;
    }
}

.頂部板岩 .手機區塊 .右側 .手機:first-child {
    position : absolute;
    left     : 21rem;
    animation: fadein2 2s;

    @include rwd {
        top : 12rem;
        left: 4rem;
    }
}

.頂部板岩 .手機區塊 .右側 .手機:last-child {
    animation: fadein1 2s;
}

.頂部板岩 .手機區塊 .手機 .圖片 {
    height: 350px;

    @include rwd {
        height: 209px;
    }
}

/**
 * 特色區域
 */

.特色區域 {
    position: relative;
}

.特色區域 .卡片區塊 {
    display              : grid;
    grid-template-columns: repeat(2, 1fr);
    width                : 740px;
    gap                  : 6rem;
    margin               : 6rem auto 0;

    @include rwd {
        min-width: 330px;
        width    : auto;
        gap      : 4rem 1rem;
    }
}

/**
 * 普匯推薦
 */

.普匯推薦 {
    position: relative;
}

.普匯推薦 .包裹容器 {
    display        : flex;
    align-items    : center;
    justify-content: center;
    margin         : 3rem 0 0;
}

/**
 * 推薦人物
 */

.推薦人物 {
    position: relative;

    --alesis-xheader-offset: -70%;
}
</style>
