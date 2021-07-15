<template>
    <div class="index-wrapper">
        <!-- 標頭 -->
        <alesis-loan-header header="學生貸" content="快速、安全、隱私<br>最受學生信賴的線上借貸平台！<br>最高額度 18 萬、最低利率 5%<br>幫助年輕人完成夢想！" image="/images/asian-student-sitting-campus-stairs-outdoors-with-smartphone-staring-distance1.jpg"></alesis-loan-header>
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
            <index-section header="合作夥伴">
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
                        <img src="/images/alesis-bordered-shanghai.svg" class="image">
                        <div class="name">上海商業儲蓄銀行</div>
                    </div>
                    <div class="item">
                        <img src="/images/alesis-bordered-skbank.svg" class="image">
                        <div class="name">新光銀行</div>
                    </div>
                </div>
            </index-section>
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
        <div class="alesis-steps">
            <index-section header="申貸媒合簡便四步驟">
                <global-carousel :indicator="false">
                    <!-- Step 1 -->
                    <div class="set">
                        <div class="item">
                            <div class="step-1">
                                <div class="left"></div>
                                <div class="center">
                                    <img src="/images/alesis-collegeloan-step1.svg" class="image">
                                </div>
                                <div class="right">
                                    <div class="header">Step 1. 選擇額度</div>
                                    <div class="content">
                                        提供完整資訊<br>
                                        有助提高額度
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Step 1 -->

                    <!-- Step 2 -->
                    <div class="set">
                        <div class="item">
                            <div class="step-2">
                                <div class="left"></div>
                                <div class="center">
                                    <img src="/images/alesis-collegeloan-step2.svg" class="image">
                                </div>
                                <div class="right">
                                    <div class="header">Step 2. 上傳資料</div>
                                    <div class="content">
                                        AI數據分析審核<br>
                                        全程無人打擾
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Step 2 -->

                    <!-- Step 3 -->
                    <div class="set">
                        <div class="item">
                            <div class="step-3">
                                <div class="left"></div>
                                <div class="center">
                                    <img src="/images/alesis-collegeloan-step3.svg" class="image">
                                </div>
                                <div class="right">
                                    <div class="header">Step 3. 持證自拍</div>
                                    <div class="content">
                                        審核成功後<br>
                                        立即上架幫您媒合投資人
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Step 3 -->

                    <!-- Step 4 -->
                    <div class="set">
                        <div class="item">
                            <div class="step-4">
                                <div class="left"></div>
                                <div class="center">
                                    <img src="/images/alesis-collegeloan-step4.svg" class="image">
                                </div>
                                <div class="right">
                                    <div class="header">Step 4. 上架媒合</div>
                                    <div class="content">
                                        24小時收到款項
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Step 4 -->
                </global-carousel>
            </index-section>
        </div>
        <!-- / 申貸媒合簡便四步驟 -->

        <!-- 服務範圍廣 -->
        <div class="alesis-map">
            <index-section header="服務範圍廣" multiline="超過156所大學生，不論是生活急需、還是補習進修費，都在找普匯！
成立三年已幫助超過2萬名學生，讓學生一想到借錢，就想到普匯！" :descriptionMulti="true" :secondary="true">
                <div class="wrapper">
                    <div class="projects">
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
                    <div class="map">
                        <alesis-taiwan-map></alesis-taiwan-map>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 服務範圍廣 -->

        <!-- 為什麼選擇普匯 -->
        <div class="alesis-why">
            <index-section header="為什麼選擇普匯">
                <div class="items">
                    <div class="item">
                        <img class="image" src="/images/alesis-symbol-taiwan.svg" alt="">
                        <div class="label">全台學生貸市佔量最高</div>
                    </div>
                    <div class="item">
                        <img class="image" src="/images/alesis-symbol-dnd.svg" alt="">
                        <div class="label">全程使用APP  無人打擾</div>
                    </div>
                    <div class="item">
                        <img class="image" src="/images/alesis-symbol-cash.svg" alt="">
                        <div class="label">放款不受休假限制</div>
                    </div>
                    <div class="item">
                        <img class="image" src="/images/alesis-symbol-approve.svg" alt="">
                        <div class="label">學生核貸率100%</div>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 為什麼選擇普匯 -->

        <!-- 貸安吶 -->
        <div class="alesis-form">
            <index-section topline="試算您的信用額度" header="貸安吶" multiline="請您依現在的真實狀況填寫以下題目" :outlinedMulti="true" :secondary="true">
                <div class="form">
                    <div class="row">
                        <div class="label">1.就讀學校：</div>
                        <div class="input">
                            <select>
                                <option selected disabled>-請選擇-</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">2.科系：</div>
                        <div class="input">
                            <select>
                                <option selected disabled>-請選擇-</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">3.系上排名：</div>
                        <div class="input">
                            <select v-model="formRank">
                                <option disabled value="">-請選擇-</option>
                                <option value="10%">前 10%</option>
                                <option value="30%">前 30%</option>
                                <option value="50%">前 50%</option>
                                <option value="70%">前 70%</option>
                                <option value="70%+">70% 之後</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">4.是否拿過獎學金：</div>
                        <div class="input">
                            <select v-model="formHasAward">
                                <option disabled value="">-請選擇-</option>
                                <option value="true">是</option>
                                <option value="false">否</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">5.是否有學貸：</div>
                        <div class="input">
                            <select>
                                <option selected disabled>-請選擇-</option>
                                <option>是</option>
                                <option>否</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">6.是否有打工或兼職：</div>
                        <div class="input">
                            <select>
                                <option selected disabled>-請選擇-</option>
                                <option>是</option>
                                <option>否</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">7.是否有承擔家中經濟來源：</div>
                        <div class="input">
                            <select>
                                <option selected disabled>-請選擇-</option>
                                <option>是</option>
                                <option>否</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">8.每月經濟：</div>
                        <div class="input">
                            <select>
                                <option selected disabled>-請選擇-</option>
                                <option>低於 5,000 元</option>
                                <option>5,000 元 - 10,000 元</option>
                                <option>10,000 元 - 15,000 元</option>
                                <option>15,000 元 - 20,000 元</option>
                                <option>20,000 以上</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">暱稱：</div>
                        <div class="input">
                            <input type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">E-mail：</div>
                        <div class="input">
                            <input type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="label"></div>
                        <div class="input">
                            <button @click="calculateForm">取得報告</button>
                        </div>
                    </div>
                </div>
                <div class="result" v-if="formCalculated">
                    <div class="showcase">
                        <img src="/images/alesis-phone-and-cash.svg" class="image">
                    </div>
                    <div class="content">
                        <div class="header">親愛的用戶您好：</div>
                        <div class="content">
                            感謝您使用普匯的學生貸款額度利率評估服務，<br>
                            經系統自動評估後，符合您的額度及利率區間如下：
                        </div>
                        <div class="values">
                            <div class="row">
                                <div class="label">1. 可借款額度：</div>
                                <div class="value">200,000</div>
                            </div>
                            <div class="row">
                                <div class="label">2. 借款利率區間：</div>
                                <div class="value">24</div>
                            </div>
                            <div class="row">
                                <div class="label">3. 手續費金額：</div>
                                <div class="value">15</div>
                            </div>
                            <div class="row">
                                <div class="label">4. 每期攤還金額約：</div>
                                <div class="value">6000</div>
                            </div>
                        </div>
                        <div class="description">►僅為初步評估，實際貸款條件依照您真實提供的資料而定。</div>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 貸安吶 -->

        <!-- 案例分享 -->
        <div class="alesis-humans">
            <index-section header="案例分享 / 用戶分享" :outlined="true">
                <global-carousel>
                    <div class="set">
                        <div class="item">
                            <index-human>
                                <div slot="video">
                                    <iframe src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                        <div class="item">
                            <index-human>
                                <div slot="video">
                                    <iframe src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                    </div>
                    <div class="set">
                        <div class="item">
                            <index-human>
                                <div slot="video">
                                    <iframe src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                        <div class="item">
                            <index-human>
                                <div slot="video">
                                    <iframe src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                    </div>
                </global-carousel>
            </index-section>
        </div>
        <!-- / 案例分享 -->

        <!-- 應用程式推薦 -->
        <alesis-application-recommendation action="我想借款"></alesis-application-recommendation>
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
import indexProject                    from "../component/alesis/index/project";
import AlesisLoanHeader                from "../component/alesis/loan-header";
import AlesisTaiwanMap                 from "../component/alesis/taiwan-map";
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
        indexProject,
        globalCarousel,
        AlesisApplicationRecommendation,
        AlesisTaiwanMap,
        AlesisLoanHeader
    },
    data: () => ({
        formRank: "",
        formHasAward: "",
        formCalculated: false,
    }),
    created() {
        $("title").text(`首頁 - inFlux普匯金融科技`);
    },
    mounted() {
    },
    methods: {
        calculateForm() {
            this.formCalculated = true
        },
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
@import "../component/alesis/alesis";

.alesis-humans {
    .set {
        @include rwd {
            grid-template-columns: 1fr !important;
            gap                  : 0 !important;
        }

        .item {
            @include rwd {
                &:not(:first-child) {
                    display: none;
                }
            }
        }
    }
}

.alesis-map {
    .wrapper {
        .map {
            svg {
                @include rwd-laptop {
                    width: 100%;
                }
                @include rwd {
                    width : 70vw;
                    height: 90vw;
                }
            }
        }
    }
}

.alesis-steps {
    .carousel {
        .selector {
            @include rwd {
                min-height : 520px;
                align-items: initial;
            }
        }
    }
}
</style>

<style lang="scss" scoped>
@import "../component/alesis/alesis";

.alesis-app {
    @include rwd {
        display: none;
    }
}

.alesis-counters {
    margin   : 4rem auto 8rem;
    max-width: 1280px;

    @include rwd {
        margin-bottom: 3rem;
    }

    .wrapper {
      display        : flex;
      justify-content: center;
    }
}

.alesis-map {
    .wrapper {
        display              : grid;
        max-width            : 1620px;
        margin               : 8rem auto 0;
        gap                  : 5rem;
        grid-template-columns: 1fr 1fr;

        @include rwd {
            display       : flex;
            flex-direction: column-reverse;
            align-items   : center;
            margin-top    : 2rem;
            gap           : 1rem;
        }

        @include rwd-laptop {
            gap: 2rem;
        }

        .projects {
            display              : grid;
            grid-template-columns: repeat(2, 1fr);
            gap                  : 3rem;

            @include rwd {
                grid-template-columns: repeat(1, 1fr);
                transform            : scale(0.9);
                margin               : -3rem 0;
            }

            .item {

            }
        }
        .map {

        }
    }
}

.alesis-steps {
    .carousel {
        .selector .lightbox .set {
            grid-template-columns: 1fr;
        }
    }

    .step-1,
    .step-2,
    .step-3,
    .step-4 {
        display              : grid;
        grid-template-columns: repeat(3, 1fr);
        gap                  : 3rem;
        max-width            : 800px;

        @include rwd {
            grid-template-columns: repeat(1, 1fr);
            align-items          : center;
            justify-content      : center;
        }

        .left {
            @include rwd {
                display: none;
            }
        }
        .center {
            .image {
                @include rwd {
                    width: 200px;
                }
            }
        }

        .right {
            display        : flex;
            justify-content: center;
            flex-direction : column;

            .header {
                color    : #112E53;
                font-size: 1.3rem;

                @include rwd {
                    font-size: 1.1rem;
                    padding-left: 1rem;
                }
            }
            .content {
                color    : #5D5555;
                font-size: 1.1rem;

                @include rwd {
                    font-size: 1rem;
                    padding-left: 1rem;
                }
            }
        }
    }
    .step-1 {

    }
    .step-2 {

    }
    .step-3 {

    }
    .step-4 {

    }
}

.alesis-form {
    margin-top: 4rem;

    @include rwd {
        margin-top: 1rem;
    }

    .form {
        max-width: 1280px;
        margin   : 4rem auto 0;

        .row {
            grid-template-columns: 1.06fr 1fr;
            display              : grid;
            margin               : 0;
            align-items          : center;
            gap                  : 1rem;

            @include rwd {
                grid-template-columns: 1fr;
                gap                  : 0.5rem;
            }

            &:not(:last-child) {
                margin-bottom: 1.5rem;

                @include rwd {
                    margin-bottom: 0.5rem;
                }
            }

            .label {
                text-align: right;
                font-size : 1.4rem;

                @include rwd {
                    text-align: left;
                    font-size : 1rem;
                }
            }

            .input {
                select {
                    width        : 100%;
                    padding      : .5rem 1rem;
                    font-size    : 1.4rem;
                    border-radius: .4rem;
                    border       : 1px solid #036eb7;
                    background   : transparent;
                    text-align   : center;
                    color        : #036eb7;
                    padding-left : 45%;

                    @include rwd {
                        font-size   : 1rem;
                        padding     : .5rem;
                        padding-left: 37%;
                    }
                }
                input {
                    width        : 100%;
                    padding      : .5rem 1rem;
                    font-size    : 1.4rem;
                    border-radius: .4rem;
                    border       : 1px solid #036eb7;
                    background   : transparent;
                    color        : #036eb7;

                    @include rwd {
                        font-size: 1rem;
                        padding  : .5rem;
                    }
                }
                button {
                    width        : 100%;
                    padding      : 0.5rem 1rem;
                    font-size    : 1.4rem;
                    border-radius: 0.4rem;
                    border       : 0;
                    background   : #326398;
                    color        : #FFF;

                    @include rwd {
                        font-size: 1rem;
                        padding  : .5rem;
                    }
                }
            }
        }
    }

    .result {
        display      : flex;
        align-items  : center;
        margin-top   : 8rem;
        margin-bottom: 2rem;

        @include rwd {
            margin-top    : 3rem;
            margin-bottom : 0;
            flex-direction: column;
        }

        .showcase {
            flex      : 1;
            text-align: right;

            .image {
                height: 350px;

                @include rwd {
                    width        : 70vw;
                    height       : auto;
                    margin-bottom: 2rem;
                }
            }
        }

        > .content {
            background-image       : linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
            -webkit-background-clip: text;
            background-clip        : text;
            color                  : rgba(255, 255, 255, 0);
            font-weight            : bolder;
            font-size              : 2rem;
            flex                   : 1;
            margin-left            : 3rem;

            @include rwd {
                margin-left: 0;
            }

            .header {
                margin-bottom: 1.5rem;
                font-size    : 1.8rem;

                @include rwd {
                    font-size: 1.2rem;
                }
            }
            .content {
                font-size    : 1.3rem;
                line-height  : 1.7;
                margin-bottom: 1.5rem;

                @include rwd {
                    font-size: 1rem;
                }
            }
            .values {
                font-size    : 1.2rem;
                line-height  : 1.8;
                margin-bottom: 2rem;

                @include rwd {
                    font-size: 1rem;
                }

                .row {
                    margin: 0;

                    .label {

                    }
                    .value {
                        color      : #5d5555;
                        font-weight: normal;
                    }
                }
            }
            .description {
                color      : #f29600;
                font-size  : .9rem;
                font-weight: normal;
            }
        }
    }
}

.alesis-coops {
    max-width: 1280px;
    margin   : 8rem auto -8rem;

    .group {
        display              : grid;
        margin               : 0 auto 8rem;
        grid-template-columns: repeat(5, 1fr);
        align-items          : center;
        justify-content      : center;

        @include rwd {
            max-width  : 430px;
            gap        : 2rem 0;
            display    : flex;
            flex-wrap  : wrap;
            white-space: nowrap;
        }

        .item {
            text-align: center;

            @include rwd {
                flex: calc(100% / 3);

                &:nth-child(4) {
                    .image, .name {
                        margin-left: 4rem;
                    }
                }
                &:nth-child(5) {
                    .image, .name {
                        margin-right: 4rem;
                    }
                }
            }

            .image {
                width: 130px;

                @include rwd {
                    width: 90px;
                }
            }

            .name {
                margin-top : 1rem;
                color      : #5D5555;
                font-weight: bold;

                @include rwd {
                    font-size: .9rem;
                }
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

        @include rwd {
            grid-template-columns: repeat(2, 1fr);
            gap                  : 2rem 0rem;
            margin-top           : 3rem;
            max-width            : 310px;
        }

        .item {
            text-align: center;

            .image {
                width: 100px;

                @include rwd {
                    width: 80px;
                }
            }

            .label {
                margin-top : 1rem;
                color      : #112E53;
                font-size  : 1.3rem;

                @include rwd {
                    font-size : 1rem;
                    margin-top: .5rem;
                }
            }
        }
    }
}

.alesis-why {
    .items {
        display              : grid;
        max-width            : 1280px;
        margin               : 0 auto;
        grid-template-columns: repeat(4, 1fr);
        align-items          : center;
        justify-content      : center;

        @include rwd {
            grid-template-columns: repeat(2, 1fr);
            max-width            : 400px;
            gap                  : 3rem 0rem;
        }

        .item {
            text-align: center;

            .image {
                @include rwd {
                    width: 70px;
                }
            }

            .label {
                margin-top : 1rem;
                font-weight: bold;
                color      : #112E53;
                font-size  : 1.3rem;

                @include rwd {
                    font-size: 1rem;
                }
            }
        }
    }
}
</style>
