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
            <alesis-header>
                <div class="標題">目前案件</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="medium"></alesis-space>
                <alesis-project-viewer :status=3></alesis-project-viewer>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 案件區塊 -->

        <!-- 案件區塊 -->
        <div class="案件區塊">
            <alesis-header>
                <div class="標題">已成交案件</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="medium"></alesis-space>
                <alesis-project-viewer :status=10></alesis-project-viewer>
                <alesis-space size="medium"></alesis-space>
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
import AlesisProjectViewer     from "../component/alesis/AlesisProjectViewer";

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
        AlesisProjectViewer,
    },
    props: {

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

.頂部板岩 .遮罩 {
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

.頂部板岩 .遮罩 .標題 {
    display      : inline-block;
    font-size    : 2.2rem;
    font-weight  : bolder;
    color        : #F2E627;
    border-bottom: 0;

    @include rwd {
        font-size: 1.4rem;
    }
}

.頂部板岩 .遮罩 .內容 {
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
    position: relative;
    margin-top: 8rem;
}

.案件區塊 + .案件區塊 {
    margin-top: 3rem;
}
</style>
