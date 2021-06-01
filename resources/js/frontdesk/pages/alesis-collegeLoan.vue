<template>
    <div class="index-wrapper">


        <!-- 開頭計數器 -->
        <div class="alesis-counters">
            <div class="wrapper">
                <index-counter image="/images/alesis-registered.svg" header="累積註冊用戶" number="60000" unit="人"></index-counter>
                <index-counter image="/images/alesis-totalmoney.svg" header="累積放款金額" number="15857" unit="萬"></index-counter>
                <index-counter image="/images/alesis-totalapproved.svg" header="累積成交筆數" number="37390" unit="筆"></index-counter>
            </div>
        </div>
        <!-- / 開頭計數器 -->

    </div>
</template>

<script>
import { Splide, SplideSlide } from "@splidejs/vue-splide";
//
import creditBoard from "../component/svg/creditBoardComponent";
import float       from "../component/floatComponent";
// Alesis components
import indexCounter   from "../component/alesis/index/counter";
import indexSection   from "../component/alesis/index/section";
import indexBullet    from "../component/alesis/index/bullet";
import indexMoon      from "../component/alesis/index/moon";
import indexPlan      from "../component/alesis/index/plan";
import indexHuman     from "../component/alesis/index/human";
import indexButton    from "../component/alesis/index/button";
import indexShanghai  from "../component/alesis/index/shanghai";
import globalCarousel from "../component/alesis/global-carousel";

export default {
    components: {
        float,
        creditBoard,
        Splide,
        SplideSlide,
        //
        indexCounter,
        indexSection,
        indexBullet,
        indexMoon,
        indexPlan,
        indexHuman,
        indexButton,
        indexShanghai,
        globalCarousel
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

<style lang="scss">

</style>
