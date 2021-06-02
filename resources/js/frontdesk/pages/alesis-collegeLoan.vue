<template>
    <div class="index-wrapper">
        <!-- 標頭 -->
        <alesis-loan-header></alesis-loan-header>
        <!-- 標頭 -->

        <!-- 開頭計數器 -->
        <div class="alesis-counters">
            <div class="wrapper">
                <index-counter image="/images/alesis-registered.svg" header="普匯目前用戶" number="60000" unit="人"></index-counter>
                <index-counter image="/images/alesis-totalmoney.svg" header="已媒合金額" number="15857" unit="萬"></index-counter>
                <index-counter image="/images/alesis-totalapproved.svg" header="服務學校達" number="37390" unit="所"></index-counter>
            </div>
        </div>
        <!-- / 開頭計數器 -->

        <!-- 合作對象 -->
        <div class="alesis-coops">
            <div class="group">
                <div class="item">
                    <img src="/images/alesis-bordered-ntpu.svg" class="image">
                    <div class="name">國立臺北大學</div>
                </div>
                <div class="item">
                    <img src="/images/alesis-bordered-ntu.svg" class="image">
                    <div class="name">國立臺灣大學</div>
                </div>
                <div class="item">
                    <img src="/images/alesis-bordered-ntub.svg" class="image">
                    <div class="name">國立臺北商業大學</div>
                </div>
                <div class="item">
                    <img src="/images/alesis-bordered-kgi.svg" class="image">
                    <div class="name">凱基銀行</div>
                </div>
                <div class="item">
                    <img src="/images/alesis-bordered-shanghai.svg" class="image">
                    <div class="name">上海商業儲蓄銀行</div>
                </div>
                <div class="item">
                    <img src="/images/alesis-bordered-skbank.svg" class="image">
                    <div class="name">新光銀行</div>
                </div>
            </div>
        </div>
        <!-- / 合作對象 -->

        <!-- 適用對象 -->
        <div class="alesis-for">
            <index-section :secondary="true" header="適用對象" multiline="滿20歲的在校生，準備您的雙證件、學生證、金融卡，
在APP上拍照上傳，立即核准！" :descriptionMulti="true">
                <div class="items">
                    <div class="item">
                        <img class="image" src="/images/alesis-symbol-student.svg" alt="">
                        <div class="label">學生身分</div>
                    </div>
                    <div class="item">
                        <img class="image" src="/images/alesis-symbol-id.svg" alt="">
                        <div class="label">雙證件</div>
                    </div>
                    <div class="item">
                        <img class="image" src="/images/alesis-symbol-creditcard.svg" alt="">
                        <div class="label">金融帳號</div>
                    </div>
                    <div class="item">
                        <img class="image" src="/images/alesis-symbol-email.svg" alt="">
                        <div class="label">電子信箱</div>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 適用對象 -->

        <!-- 申貸媒合簡便四步驟 -->
        <div class="alesis-">
            <index-section header="申貸媒合簡便四步驟">

            </index-section>
        </div>
        <!-- / 申貸媒合簡便四步驟 -->

        <!-- 服務範圍廣 -->
        <div class="alesis-">
            <index-section header="服務範圍廣" multiline="超過156所大學生，不論是生活急需、還是補習進修費，都在找普匯！
成立三年已幫助超過2萬名學生，讓學生一想到借錢，就想到普匯！" :descriptionMulti="true" :secondary="true">

            </index-section>
        </div>
        <!-- / 服務範圍廣 -->

        <!-- 為什麼選擇普匯 -->
        <div class="alesis-">
            <index-section header="為什麼選擇普匯">

            </index-section>
        </div>
        <!-- / 為什麼選擇普匯 -->

        <!-- 貸安吶 -->
        <div class="alesis-">
            <index-section header="貸安吶" multiline="請您依現在的真實狀況填寫以下題目" :outlinedMulti="true" :secondary="true">

            </index-section>
        </div>
        <!-- / 貸安吶 -->

        <!-- 案例分享 -->
        <div class="alesis-">
            <index-section header="案例分享 / 用戶分享" :outlined="true">

            </index-section>
        </div>
        <!-- / 案例分享 -->

        <!-- 應用程式推薦 -->
        <alesis-application-recommendation></alesis-application-recommendation>
        <!-- / 應用程式推薦 -->
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

<style lang="scss">
.alesis-counters {
    margin: 4rem auto 5rem;
}

.alesis-coops {
    max-width: 1280px;
    margin   : 0 auto;

    .group {
        display              : grid;
        margin               : 0 auto 8rem;
        grid-template-columns: repeat(6, 1fr);
        align-items          : center;
        justify-content      : center;

        .item {
            text-align: center;

            .image {

            }

            .name {
                margin-top : 1rem;
                color      : #5D5555;
                font-weight: bold;
            }
        }
    }
}

.alesis-for {
    .items {
        display              : grid;
        max-width            : 1280px;
        margin               : 6rem auto 0;
        grid-template-columns: repeat(4, 1fr);
        align-items          : center;
        justify-content      : center;

        .item {
            text-align: center;

            .image {

            }

            .label {
                margin-top : 1rem;
                font-weight: bold;
                color      : #112E53;
                font-size  : 1.3rem;
            }
        }
    }
}
</style>
