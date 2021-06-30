<template>
    <div class="index-wrapper">
        <!-- 標頭 -->
        <alesis-loan-header header="上班族貸" content="
        貸款特色：快速審核、方便操作、申貸流程<br>
        　　　　　簡單明瞭、隱私無照會
        <br><br>
        適用對象：已畢業且有穩定收入的上班族<br>
        　　　　　皆可申請！" image="/images/sshot-1749.png"></alesis-loan-header>
        <!-- 標頭 -->

        <!-- 針對上市櫃、公家機關員工 精英快審 -->
        <div class="alesis-fast-approve">
            <index-section header="針對上市櫃、公家機關員工 精英快審">
                <div class="items">
                    <div class="item">
                        <img class="image" src="/images/alesis-registered.svg" alt="">
                        <div class="label">立即申請→立即審核</div>
                    </div>
                    <div class="item">
                        <img class="image" src="/images/alesis-symbol-cash.svg" alt="">
                        <div class="label">加快投資人媒合速度→立即放款</div>
                    </div>
                    <div class="item">
                        <img class="image" src="/images/alesis-symbol-upcash.svg" alt="">
                        <div class="label">擴大核准額度至20萬</div>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 適用對象 -->

        <!-- 上班族 普匯合作專區 -->
        <div class="alesis-bank">
            <index-section header="上班族 普匯合作專區">
                <div class="wrapper">
                    <index-shanghai></index-shanghai>
                </div>
            </index-section>
        </div>
        <!-- / 上班族 普匯合作專區 -->

        <!-- 申貸流程 -->
        <div class="alesis-flow">
            <index-section header="申貸流程" :secondary="true">
                <global-carousel :indicator="false">
                    <!-- Step 1 -->
                    <div class="set">
                        <div class="item">
                            <div class="step-1">
                                <div class="left">
                                    <a href="#!"><img src="/images/alesis-app-store.svg" class="image"></a>
                                    <a href="#!"><img src="/images/alesis-google-play.svg" class="image"></a>
                                </div>
                                <div class="center">
                                    <img src="/images/alesis-workloan-step1.svg" class="image">
                                </div>
                                <div class="right">
                                    <div class="header">Step 1.：</div>
                                    <div class="content">
                                        下載「普匯influx」APP<br>
                                        註冊會員<br>
                                        申請「上班族貸」<br>
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
                                <div class="left">
                                    <div class="phones">
                                        <img src="/images/alesis-workloan-step2-1.svg" class="image">
                                        <img src="/images/alesis-workloan-step2-2.svg" class="image">
                                    </div>
                                    <div class="section">
                                        <div class="header">工作證明</div>
                                        <div class="divider"></div>
                                        <div class="paragraph">
                                            <div class="label">方法 1</div>
                                            <div class="box">
                                                <index-button size="1.2rem" xPadding="2rem" yPadding=".8rem">自然人憑證</index-button>
                                            </div>
                                        </div>
                                        <div class="content">至各縣市勞動部申請 : 申請後請拍照上傳至APP內</div>
                                        <div class="divider"></div>
                                        <div class="paragraph">
                                            <div class="label">方法 2</div>
                                            <div class="box">
                                                <index-button size="1.2rem" xPadding="2rem" yPadding=".8rem">至各縣市勞動部申請</index-button>
                                            </div>
                                        </div>
                                        <div class="content">
                                            自然人憑證 : 使用自然人憑證卡搭配電腦與讀卡機至勞保局網頁 (<a href="#!">https://edesk.bli.gov.tw/aa/</a>) 進行申請
                                        </div>
                                    </div>
                                    <div class="section">
                                        <div class="header">聯徵證明</div>
                                        <div class="divider"></div>
                                        <div class="paragraph">
                                            <div class="label">方法 1</div>
                                            <div class="box">
                                                <index-button size="1.2rem" xPadding="2rem" yPadding=".8rem">郵局</index-button>
                                            </div>
                                        </div>
                                        <div class="content">郵局：至郵局櫃台申請聯徵,並寄送至普匯金融科技 (台北市松江路111號11樓之2)</div>
                                        <div class="divider"></div>
                                        <div class="paragraph">
                                            <div class="label">方法 2</div>
                                            <div class="box">
                                                <index-button size="1.2rem" xPadding="2rem" yPadding=".8rem">自然人憑證</index-button>
                                            </div>
                                        </div>
                                        <div class="content">
                                            自然人憑證 : 使用自然人憑證卡搭配電腦與讀卡機至財團法人金融聯合中心網頁 (<a href="#!">https://apply.jcic.org.tw/CreditQueryInput.do</a>) 進行申請
                                        </div>
                                        <div class="divider"></div>
                                        <div class="paragraph">
                                            <div class="label">方法 3</div>
                                            <div class="box">
                                                <index-button size="1.2rem" xPadding="2rem" yPadding=".8rem">投資人行動網</index-button>
                                            </div>
                                        </div>
                                        <div class="content">
                                            投資人行動網 : 下載投資人行動網APP(TWID)並選擇您開立證券戶之券商
                                        </div>
                                    </div>
                                </div>
                                <div class="right">
                                    <div class="header">Step 2.：</div>
                                    <div class="content">
                                        選擇申請金額、期數<br>
                                        完成實名認證<br>
                                        上傳申貸資料<br>
                                    </div>
                                    <br><br>
                                    <div class="header">需提供資料：</div>
                                    <div class="content">
                                        近一個月勞保異動明細　<a href="#!" class="help">如何申請？</a><br>
                                        近一個月信用報告　<a href="#!" class="help">如何申請？</a><br>
                                        學歷證明（畢業證書）
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
                                <div class="left">
                                    <img src="/images/alesis-workloan-step3-1.svg" class="image">
                                    <img src="/images/alesis-workloan-step3-2.svg" class="image">
                                </div>
                                <div class="right">
                                    <div class="header">Step 3.：</div>
                                    <div class="content">
                                        審核通過<br>
                                        線上簽約<br>
                                        立即上架媒和<br>
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
                                <div class="left">
                                    <img src="/images/alesis-donezo.svg" class="image">
                                </div>
                                <div class="right">
                                    <div class="header">Step 4.：</div>
                                    <div class="content">
                                        成功媒合投資人<br>
                                        立即放款
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Step 4 -->
                </global-carousel>
            </index-section>
        </div>
        <!-- / 申貸流程 -->

        <!-- 上班族產品特色 -->
        <div class="alesis-features">
            <index-section header="上班族產品特色" >
                <div class="wrapper">
                    <div class="left">
                        <div class="section">
                            <div class="symbol">
                                <img src="/images/alesis-work-loan-feature1.svg" class="image">
                                <img src="/images/alesis-workloan-feature-1-simplified.svg" class="rwd-image">
                            </div>
                            <div class="content">
                                <div class="header">即時審核，快速媒合放款</div>
                                <div class="divider"></div>
                                <div class="text">系統自動化審核加快申貸的速度，平均3分鐘完成申請、10分鐘核准、60分鐘到帳！</div>
                            </div>
                        </div>
                        <div class="section">
                            <div class="symbol">
                                <img src="/images/alesis-work-loan-feature2.svg" class="image">
                                <img src="/images/alesis-workloan-feature-2-simplified.svg" class="rwd-image">
                            </div>
                            <div class="content">
                                <div class="header">全線無人化</div>
                                <div class="divider"></div>
                                <div class="text">運用AI整合會員資料進行大數據分析，自動線上完成徵信、授信、甚至續約程序，操作方便簡單。</div>
                            </div>
                        </div>
                        <div class="section">
                            <div class="symbol">
                                <img src="/images/alesis-work-loan-feature3.svg" class="image">
                                <img src="/images/alesis-workloan-feature-3-simplified.svg" class="rwd-image">
                            </div>
                            <div class="content">
                                <div class="header">隱私無照會</div>
                                <div class="divider"></div>
                                <div class="text">申貸全程無人干擾，更不會接到任何業務員的照會電話，也不會主動聯絡借款人及其緊急聯絡人。</div>
                            </div>
                        </div>
                    </div>
                    <div class="center">
                        <div class="showcase">
                            <img src="/images/alesis-work-loan-phone.svg" class="image">
                        </div>
                        <div class="slogan">假日放款全年無休</div>
                        <a href="#!" class="get"><index-button size="1.3rem" xPadding="3rem" yPadding=".8rem">取得資金</index-button></a>
                    </div>
                    <div class="right">
                        <div class="section">
                            <div class="symbol">
                                <img src="/images/alesis-work-loan-feature4.svg" class="image">
                                <img src="/images/alesis-workloan-feature-4-simplified.svg" class="rwd-image">
                            </div>
                            <div class="content">
                                <div class="header">聯徵不留紀錄</div>
                                <div class="divider"></div>
                                <div class="text">不會留下任何貸款相關紀錄，不影響未來信用狀況，也不佔任何銀行額度。</div>
                            </div>
                        </div>
                        <div class="section">
                            <div class="symbol">
                                <img src="/images/alesis-work-loan-feature5.svg" class="image">
                                <img src="/images/alesis-workloan-feature-5-simplified.svg" class="rwd-image">
                            </div>
                            <div class="content">
                                <div class="header">費率單純透明</div>
                                <div class="divider"></div>
                                <div class="text">只收取一次手續費，且費率以及每月需還本息金額公開透明，不用擔心被收取其他費用。</div>
                            </div>
                        </div>
                        <div class="section">
                            <div class="symbol">
                                <img src="/images/alesis-work-loan-feature6.svg" class="image">
                                <img src="/images/alesis-workloan-feature-6-simplified.svg" class="rwd-image">
                            </div>
                            <div class="content">
                                <div class="header">金融機構合作</div>
                                <div class="divider"></div>
                                <div class="text">在會員用戶逐漸成長的情況下，普匯專業優質的名聲，吸引許多金融機構相邀合作，包含「凱基銀行」、「上海商銀」等，共同為台灣金融科技發展貢獻一份力。</div>
                            </div>
                        </div>
                    </div>
                    <div class="bottom">
                        <div class="slogan">假日放款全年無休</div>
                        <a href="#!" class="get"><index-button size="1.3rem" xPadding="3rem" yPadding=".8rem">取得資金</index-button></a>
                    </div>
                </div>
            </index-section>
        </div>
        <!-- / 上班族產品特色 -->

        <!-- 額度利率評估問卷 -->
        <div class="alesis-form">
            <index-section header="額度利率評估問卷" multiline="請您依現在的真實狀況填寫以下題目" :outlinedMulti="true" :secondary="true">
                <div class="form">
                    <div class="row">
                        <div class="label">1.我的教育程度：</div>
                        <div class="input">
                            <select>
                                <option selected disabled>-請選擇-</option>
                                <option>博士</option>
                                <option>碩士</option>
                                <option>學士</option>
                                <option>學士以下</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">2.我的職業屬於：</div>
                        <div class="input">
                            <select>
                                <option selected disabled>-請選擇-</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">3.任職公司是否屬於上市櫃、金融機構或公家機關：</div>
                        <div class="input">
                            <select>
                                <option selected disabled>-請選擇-</option>
                                <option>是</option>
                                <option>否</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">4.我的投保月薪約為：</div>
                        <div class="input">
                            <input type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">5.我在銀行的貸款餘額約為：</div>
                        <div class="input">
                            <input type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">6.每月需攤還多少金額：</div>
                        <div class="input">
                            <input type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">7.信用卡總額度約為：</div>
                        <div class="input">
                            <input type="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">8.近一個月信用卡帳單總金額約為：</div>
                        <div class="input">
                            <input type="text">
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
                            <button>取得報告</button>
                        </div>
                    </div>
                </div>
                <div class="result">
                    <div class="showcase">
                        <img src="/images/alesis-phone-and-cash.svg" class="image">
                    </div>
                    <div class="content">
                        <div class="header">親愛的用戶您好：</div>
                        <div class="content">
                            感謝您使用普匯的上班族貸款額度利率評估服務，<br>
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
        <!-- / 額度利率評估問卷 -->

        <!-- 案例分享 -->
        <div class="alesis-humans">
            <index-section header="案例分享 / 用戶分享" :outlined="true">
                <global-carousel>
                    <div class="set">
                        <div class="item">
                            <index-human>
                                <div slot="video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                        <div class="item">
                            <index-human>
                                <div slot="video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                    </div>
                    <div class="set">
                        <div class="item">
                            <index-human>
                                <div slot="video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                        <div class="item">
                            <index-human>
                                <div slot="video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                    </div>
                </global-carousel>
            </index-section>
        </div>
        <!-- / 案例分享 -->
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
@import "../component/alesis/alesis";

.alesis-humans {

    .left, .right {
        img {
            @include rwd {
                width: 10px;
            }
        }
    }

    .right > div {
        left: -1rem;
    }

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
</style>

<style lang="scss" scoped>
@import "../component/alesis/alesis";

.alesis-fast-approve {
    margin-top: 8rem;

    .items {
        display              : grid;
        max-width            : 1280px;
        margin               : 0 auto 1rem;
        grid-template-columns: repeat(3, 1fr);
        justify-content      : center;
        gap                  : 2rem;

        .item {
            text-align: center;

            .image {
                @include rwd {
                    width: 80px;
                }
            }

            .label {
                margin-top : 1rem;
                font-weight: bold;
                color      : #112E53;
                font-size  : 1.3rem;

                @include rwd {
                    font-size: .8rem;
                }
            }
        }
    }
}

.alesis-bank {
  .wrapper {
        display        : flex;
        align-items    : center;
        justify-content: center;
        margin         : 2rem 0 0;
    }
}

.alesis-form {

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

.alesis-flow {
    .carousel {
        margin   : 2rem auto;
        max-width: 1280px;

        .selector .lightbox .set {
            grid-template-columns: 1fr;
        }

        .selector .lightbox .set .item {
            width          : 1020px;
            min-height     : 550px;
            display        : flex;
            align-items    : center;
            justify-content: initial;
        }

        .step-1, .step-2, .step-3, .step-4 {
            min-width: 800px;
        }

        .step-1 {
            display              : inline-grid;
            grid-template-columns: repeat(3, 1fr);
            margin               : 0 auto;
            gap                  : 6rem;

            .left {
                display        : flex;
                align-items    : flex-end;
                justify-content: center;
                flex-direction : column;
                gap            : 1.5rem;

                .image {

                }
            }
            .center {
                .image {

                }
            }
            .right {
                display        : flex;
                justify-content: center;
                flex-direction : column;

                .header {
                    color    : #112E53;
                    font-size: 1.3rem;
                }
                .content {
                    color    : #5D5555;
                    font-size: 1.1rem;
                }
            }
        }
        .step-2 {
            display              : grid;
            grid-template-columns: 470px 1fr;
            gap                  : 3rem;
            margin               : 0 auto;
            max-width            : 800px;

            .left {
                display       : flex;
                flex-direction: column;
                align-items   : center;

                .phones {
                    display: flex;
                    gap    : 3rem;

                    .image {
                        width: 210px;
                    }
                }
                .section {
                    border       : 1px solid #2664A5;
                    border-radius: .8rem;
                    padding      : 1.5rem;
                    margin-top   : 3rem;

                    .header {
                        text-align: center;
                        font-size : 1.5rem;
                        color     : #036EB7;
                    }
                    .divider {
                        height    : 1px;
                        background: #2664A5;
                        margin    : 1rem 0;
                    }

                    .paragraph {
                        display              : grid;
                        grid-template-columns: 1fr 4fr;
                        align-items          : center;

                        .label {
                            font-size: 1.2rem;
                            color    : #036EB7;
                        }
                        .box {
                            .bordered {
                                position       : relative;
                                padding        : 1rem 1.5rem;
                                display        : inline-flex;
                                align-items    : center;
                                justify-content: center;
                                line-height    : 1;
                                background-image   : url(/images/alesis-button-lines.svg);
                                background-size    : contain;
                                background-repeat  : no-repeat;
                                background-position: center;
                                font-size  : 1.1rem;
                                font-weight: bold;
                                color      : #036eb7;
                            }

                        }
                    }

                    .content {
                        line-height: 1.8;
                        margin-top : .8rem;
                        color      : #5D5555;

                        a {
                            text-decoration: underline;
                        }
                    }
                }
            }
            .right {
                padding-top: 6rem;

                .header {
                    color    : #112E53;
                    font-size: 1.3rem;
                }
                .content {
                    color    : #5D5555;
                    font-size: 1.1rem;

                    .help {
                        font-size      : .9rem;
                        text-decoration: underline;
                        color          : #112E53;
                    }
                }
            }
        }
        .step-3 {
            display              : grid;
            grid-template-columns: 470px 1fr;
            gap                  : 3rem;
            margin               : 0 auto;
            max-width            : 800px;

            .left {
                display        : flex;
                align-items    : flex-end;
                justify-content: center;
                flex-direction : row;
                gap            : 3rem;

                .image {
                    width: 210px;
                }
            }
            .right {
                display        : flex;
                justify-content: center;
                flex-direction : column;

                .header {
                    color    : #112E53;
                    font-size: 1.3rem;
                }
                .content {
                    color    : #5D5555;
                    font-size: 1.1rem;
                }
            }
        }
        .step-4 {
            display              : grid;
            grid-template-columns: 470px 1fr;
            gap                  : 3rem;
            margin               : 0 auto;
            max-width            : 800px;

            .left {
                text-align: center;

                .image {
                    width: 260px;
                }
            }

            .right {
                display        : flex;
                justify-content: center;
                flex-direction : column;

                .header {
                    color    : #112E53;
                    font-size: 1.3rem;
                }
                .content {
                    color    : #5D5555;
                    font-size: 1.1rem;
                }
            }
        }
    }

}

.alesis-features {
    .wrapper {
        display  : flex;
        max-width: 1280px;
        margin   : 0 auto;

        @include rwd {
            flex-direction: column;
            width         : min-content;
        }

        .left {
            flex          : 1;
            display       : flex;
            flex-direction: column;
            align-items   : flex-start;
            margin-right  : -5rem;
            margin-left   : 5rem;

            @include rwd {
                margin: 0;
            }

            .section {
                flex-direction: row-reverse;

                @include rwd {
                    flex-direction: row;
                }

                .symbol {
                    margin-left : 1.5rem;
                    margin-right: 0;

                    @include rwd {
                        margin-left: 0;
                    }
                }
            }
        }

        .center {
            text-align: center;
            position  : relative;
            top       : initial;
            left      : initial;
            transform : none;

            @include rwd {
                display: none;
            }

            .showcase {
                margin-top: 4rem;

                .image {
                    height: 34rem;
                }
            }

            .slogan {
                text-align : center;
                color      : #f29600;
                font-size  : 2rem;
                font-weight: bolder;
                margin-top : 7rem;
            }

            .get {
                display   : inline-block;
                margin-top: 1rem;
            }
        }
        .right {
            flex: 1;

            .section:nth-child(2) {
                margin-left: -2rem;

                @include rwd {
                    margin-left: 0;
                }
            }
            .section:nth-child(3) {
                margin-left: -3rem;

                @include rwd {
                    margin-left: 0;
                }
            }
        }

        .bottom {
            display   : none;
            text-align: center;

            @include rwd {
                display: block;
            }

            .slogan {
                text-align : center;
                color      : #f29600;
                font-size  : 1.4rem;
                font-weight: bolder;
                margin-top : 2rem;
            }

            .get {
                display   : inline-block;
                margin-top: 1rem;
            }
        }

        .section {
            display      : flex;
            margin-bottom: 5rem;

            @include rwd {
                margin-bottom: 0;
            }

            .symbol {
                margin-right: 1.5rem;

                @include rwd {
                    margin-right: 0;
                }

                .image {

                    @include rwd {
                        display: none;
                    }
                }

                .rwd-image {
                    display: none;

                    @include rwd {
                        display     : initial;
                        width       : 70px;
                        margin-right: 1rem;
                    }
                }
            }

            .content {
                margin-top : 2.7rem;
                width      : 290px;
                line-height: 1.6;

                @include rwd {
                    margin-top: 1.3rem;
                }

                .header {
                    display                : inline-block;
                    background-image       : linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
                    -webkit-background-clip: text;
                    background-clip        : text;
                    color                  : rgba(255, 255, 255, 0);
                    font-weight            : bolder;
                    font-size              : 1.6rem;

                    @include rwd {
                        font-size: 1.3rem;
                    }
                }

                .divider {
                    width     : 160px;
                    height    : 1px;
                    background: #2f95cb;
                    margin    : .5rem 0 1rem;
                }

                .text {
                    color     : #5d5555;
                    min-height: 5rem;
                    text-align: justify;
                }
            }
        }
    }
}
</style>
