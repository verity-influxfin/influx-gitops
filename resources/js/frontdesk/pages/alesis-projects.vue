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
                    <div class="header">學生貸</div>
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
                    <div class="header -fast">上班族貸</div>
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
        <!-- / 目前案件 -->

        <!-- 目前案件 -->
        <div class="alesis-projects">
            <index-section header="已成交案件">
                <div class="wrapper">
                    <div class="header">學生貸</div>
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
                    <div class="header -fast">上班族貸</div>
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
        <!-- / 目前案件 -->
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
.alesis-slate {
    position: relative;
    height  : 33rem;

    .background {
        position: absolute;
        top     : 0;
        left    : 10rem;
        right   : 0;
        bottom  : 0;

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

        .header {
            display      : inline-block;
            font-size    : 2.2rem;
            font-weight  : bolder;
            color        : #F2E627;
            border-bottom: 0;
        }

        .content {
            margin-top : 1rem;
            line-height: 1.8;
            font-size  : 1.3rem;
        }
    }
}

.alesis-projects {
    margin-top: 8rem;

    & + & {
        margin-top: 3rem;
    }

    .wrapper {
        width : min-content;
        margin: 0 auto;

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

            &.-fast {
                margin-top: -1rem;
            }
        }

        .index-card {
            min-width: 450px;
        }
    }
}
</style>
