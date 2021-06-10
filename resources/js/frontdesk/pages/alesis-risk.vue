<template>
    <div class="index-wrapper">
        <!-- 標頭 -->
        <div class="alesis-slate">
            <div class="background">
                <img class="image" src="/images/sshot-1848.png" alt="">
            </div>
            <div class="mask">
                <div class="header">風險報告書</div>
            </div>
        </div>
        <!-- 標頭 -->

        <!-- 違約率報告書 -->
        <div class="alesis-report">
            <index-section header="違約率報告書">
                <div class="wrapper">
                    <img src="/images/alesis-report.svg" class="image" alt="">
                    <div class="links">
                        <a href="#!" class="item"><index-button yPadding="0.9rem" xPadding="2.5rem">2021年5月</index-button></a>
                        <a href="#!" class="item"><index-button yPadding="0.9rem" xPadding="2.5rem">2021年4月</index-button></a>
                        <a href="#!" class="item"><index-button yPadding="0.9rem" xPadding="2.5rem">2021年3月</index-button></a>
                    </div>
                    <a href="#!" class="cta"><index-button yPadding="0.9rem" xPadding="2.5rem">立即投資</index-button></a>
                </div>
            </index-section>
        </div>
        <!-- / 違約率報告書 -->
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
        background-image: url(/images/alesis-shell-mask.svg);
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        text-shadow: 1px 1px 4px black;
        color: #FFF;
        font-size: 1.1rem;
        padding-left: 12rem;
        background-size: contain;
        background-repeat: no-repeat;
        min-width: 1485px;
        height: 100%;

        .header {
            color         : #FFF;
            padding-bottom: 0.2rem;
            border-bottom : 1px solid #FFF;
            display       : inline-block;
            font-size     : 1.8rem;
            font-weight   : bolder;
        }
    }
}

.alesis-report {
    margin-top: 8rem;

    .wrapper {
        text-align: center;

        .image {
            width: 430px;
        }

        .links {
            margin-top     : 1.5rem;
            margin-bottom  : 1.5rem;
            display        : flex;
            gap            : 1rem;
            align-items    : center;
            justify-content: center;

            .item {
                // padding: 0.9rem 2.5rem;
            }
        }

        .cta {

        }
    }
}
</style>
