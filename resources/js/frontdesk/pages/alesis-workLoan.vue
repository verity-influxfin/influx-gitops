<template>
    <div class="index-wrapper">
        <!-- 標頭 -->
        <alesis-loan-header header="上班族貸" content="
        貸款特色：快速審核、方便操作、申貸流程<br>
        　　　　　簡單明瞭、隱私無照會
        <br><br>
        適用對象：已畢業且有穩定收入的上班族<br>
        　　　　　皆可申請！" image="/images/螢幕擷取畫面-2021-07-14-113234.jpg"></alesis-loan-header>
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
                    <div class="set swiper-slide">
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
                    <div class="set swiper-slide">
                        <div class="item">
                            <div class="step-2">
                                <div class="left">
                                    <div class="phones">
                                        <img src="/images/alesis-workloan-step2-1.svg" class="image">
                                        <img src="/images/alesis-workloan-step2-2.svg" class="image">
                                    </div>
                                    <div class="section" v-if="how === 'work'">
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
                                    <div class="section" v-if="how === 'prove'">
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
                                        近一個月勞保異動明細　<a href="javascript:void(0);" class="help" @click="() => {how = 'work'}">如何申請？</a><br>
                                        近一個月信用報告　<a href="javascript:void(0);" class="help" @click="() => {how = 'prove'}">如何申請？</a><br>
                                        學歷證明（畢業證書）
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Step 2 -->

                    <!-- Step 3 -->
                    <div class="set swiper-slide">
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
                    <div class="set swiper-slide">
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
                            <select v-model="formGraduate">
                                <option selected disabled value="">-請選擇-</option>
                                <option value="phD">博士</option>
                                <option value="master">碩士</option>
                                <option value="bachelor">學士</option>
                                <option value="below">學士以下</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">2.我的職業屬於：</div>
                        <div class="input">
                            <select>
                                <option selected disabled>-請選擇-</option>
                                <option :disabled="item.disabled" v-for="item, index in flattenWorkCategories" :key="index" :value="item.value">{{ item.title }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">3.任職公司是否屬於上市櫃、金融機構或公家機關：</div>
                        <div class="input">
                            <select v-model="formCompany">
                                <option selected disabled value="">-請選擇-</option>
                                <option value="true">是</option>
                                <option value="false">否</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">4.我的投保月薪約為：</div>
                        <div class="input">
                            <input type="text" v-model="formSalary">
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">5.我在銀行的貸款餘額約為：</div>
                        <div class="input">
                            <input type="text" v-model="formLoan">
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">6.每月需攤還多少金額：</div>
                        <div class="input">
                            <input type="text" v-model="formReturn">
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">7.信用卡總額度約為：</div>
                        <div class="input">
                            <input type="text" v-model="formCredit">
                        </div>
                    </div>
                    <div class="row">
                        <div class="label">8.近一個月信用卡帳單總金額約為：</div>
                        <div class="input">
                            <input type="text" v-model="formTotal">
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
                            感謝您使用普匯的上班族貸款額度利率評估服務，<br>
                            經系統自動評估後，符合您的額度及利率區間如下：
                        </div>
                        <div class="values">
                            <div class="row">
                                <div class="label">1. 可借款額度：</div>
                                <div class="value">{{ format(formAnswerTotal) }}</div>
                            </div>
                            <div class="row">
                                <div class="label">2. 借款利率區間：</div>
                                <div class="value">{{ formAnswerSpan }}</div>
                            </div>
                            <div class="row">
                                <div class="label">3. 手續費金額：</div>
                                <div class="value">{{ formAnswerFee }}</div>
                            </div>
                            <div class="row">
                                <div class="label">4. 每期攤還金額約：</div>
                                <div class="value">{{ formAnswerPer }}</div>
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
                    <div class="set swiper-slide">
                        <div class="item">
                            <index-human image="/images/alesis-human-work-symbol.svg">
                                <div slot="video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                        <div class="item">
                            <index-human image="/images/alesis-human-work-symbol.svg">
                                <div slot="video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                    </div>
                    <div class="set swiper-slide">
                        <div class="item">
                            <index-human image="/images/alesis-human-work-symbol.svg">
                                <div slot="video">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/THjekE5p2aw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </index-human>
                        </div>
                        <div class="item">
                            <index-human image="/images/alesis-human-work-symbol.svg">
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
        how: "",
        formGraduate: "",
        formCompany: "",
        formSalary: "",
        formLoan: "",
        formReturn: "",
        formCredit: "",
        formTotal: "",
        formCalculated: false,
        formAnswerTotal: 0,
        formAnswerSpan: 0,
        formAnswerFee: 0,
        formAnswerPer: 0,
        flattenWorkCategories: [],
        workCategories: {
        "n":[
            {
                "no":"jobmanage",
                "des":"管理與財經",
                "n":[
                    {
                    "no":"2001001000",
                    "des":"經營幕僚",
                    "n":[
                        {
                            "no":"2001001002",
                            "des":"儲備幹部"
                        },
                        {
                            "no":"2001001001",
                            "des":"經營管理主管"
                        },
                        {
                            "no":"2001001003",
                            "des":"主管特別助理"
                        }
                    ]
                    },
                    {
                    "no":"2001002000",
                    "des":"人力資源",
                    "n":[
                        {
                            "no":"2001002001",
                            "des":"人力資源主管"
                        },
                        {
                            "no":"2001002003",
                            "des":"教育訓練人員"
                        },
                        {
                            "no":"2001002004",
                            "des":"人力╱外勞仲介"
                        },
                        {
                            "no":"2001002002",
                            "des":"人力資源人員"
                        },
                        {
                            "no":"2001002005",
                            "des":"人力資源助理"
                        }
                    ]
                    },
                    {
                    "no":"2002001000",
                    "des":"行政總務",
                    "n":[
                        {
                            "no":"2002001003",
                            "des":"行政人員"
                        },
                        {
                            "no":"2002001001",
                            "des":"行政主管"
                        },
                        {
                            "no":"2002001002",
                            "des":"總務主管"
                        },
                        {
                            "no":"2002001004",
                            "des":"總務人員"
                        },
                        {
                            "no":"2002001005",
                            "des":"秘書"
                        },
                        {
                            "no":"2002001006",
                            "des":"資料輸入人員"
                        },
                        {
                            "no":"2002001007",
                            "des":"文件管理師"
                        },
                        {
                            "no":"2002001008",
                            "des":"圖書資料管理人員"
                        },
                        {
                            "no":"2002001009",
                            "des":"總機人員"
                        },
                        {
                            "no":"2002001010",
                            "des":"櫃檯接待人員"
                        },
                        {
                            "no":"2002001011",
                            "des":"工讀生"
                        },
                        {
                            "no":"2002001012",
                            "des":"行政助理"
                        }
                    ]
                    },
                    {
                    "no":"2002002000",
                    "des":"法務智財",
                    "n":[
                        {
                            "no":"2002002001",
                            "des":"法務╱智財主管"
                        },
                        {
                            "no":"2002002004",
                            "des":"法務人員"
                        },
                        {
                            "no":"2002002006",
                            "des":"其他法律專業人員"
                        },
                        {
                            "no":"2002002007",
                            "des":"工商登記服務人員"
                        },
                        {
                            "no":"2002002008",
                            "des":"法務助理"
                        },
                        {
                            "no":"2002002002",
                            "des":"律師"
                        },
                        {
                            "no":"2002002005",
                            "des":"商標╱專利人員"
                        },
                        {
                            "no":"2002002003",
                            "des":"代書╱地政士"
                        }
                    ]
                    },
                    {
                    "no":"2003001000",
                    "des":"財會稅務",
                    "n":[
                        {
                            "no":"2003001001",
                            "des":"財務或會計主管"
                        },
                        {
                            "no":"2003001002",
                            "des":"會計師"
                        },
                        {
                            "no":"2003001003",
                            "des":"財務分析╱財務人員"
                        },
                        {
                            "no":"2003001004",
                            "des":"主辦會計"
                        },
                        {
                            "no":"2003001005",
                            "des":"成本會計"
                        },
                        {
                            "no":"2003001006",
                            "des":"記帳╱出納╱一般會計"
                        },
                        {
                            "no":"2003001007",
                            "des":"查帳╱審計人員"
                        },
                        {
                            "no":"2003001008",
                            "des":"稽核人員"
                        },
                        {
                            "no":"2003001009",
                            "des":"稅務人員"
                        },
                        {
                            "no":"2003001010",
                            "des":"財務會計助理"
                        }
                    ]
                    },
                    {
                    "no":"2003002000",
                    "des":"金融專業",
                    "n":[
                        {
                            "no":"2003002001",
                            "des":"金融專業主管"
                        },
                        {
                            "no":"2003002002",
                            "des":"金融研究員"
                        },
                        {
                            "no":"2003002003",
                            "des":"金融交易員"
                        },
                        {
                            "no":"2003002004",
                            "des":"金融承銷員"
                        },
                        {
                            "no":"2003002005",
                            "des":"金融營業員"
                        },
                        {
                            "no":"2003002006",
                            "des":"金融理財專員"
                        },
                        {
                            "no":"2003002007",
                            "des":"銀行辦事員"
                        },
                        {
                            "no":"2003002008",
                            "des":"統計精算人員"
                        },
                        {
                            "no":"2003002009",
                            "des":"不動產產權審核╱估價師"
                        },
                        {
                            "no":"2003002010",
                            "des":"保險業務╱經紀人"
                        },
                        {
                            "no":"2003002011",
                            "des":"融資╱信用業務人員"
                        },
                        {
                            "no":"2003002012",
                            "des":"催收人員"
                        },
                        {
                            "no":"2003002013",
                            "des":"核保╱保險內勤人員"
                        },
                        {
                            "no":"2003002014",
                            "des":"理賠人員"
                        },
                        {
                            "no":"2003002015",
                            "des":"券商後線人員"
                        },
                        {
                            "no":"2003002016",
                            "des":"股務人員"
                        }
                    ]
                    }
                ]
            },
            {
                "no":"jobsale",
                "des":"行銷業務類",
                "n":[
                    {
                    "no":"2004001000",
                    "des":"行銷廣告",
                    "n":[
                        {
                            "no":"2004001001",
                            "des":"廣告企劃主管"
                        },
                        {
                            "no":"2004001002",
                            "des":"行銷企劃主管"
                        },
                        {
                            "no":"2004001003",
                            "des":"品牌宣傳及媒體公關主管"
                        },
                        {
                            "no":"2004001004",
                            "des":"產品行銷人員"
                        },
                        {
                            "no":"2004001005",
                            "des":"行銷企劃人員"
                        },
                        {
                            "no":"2004001006",
                            "des":"活動企劃人員"
                        },
                        {
                            "no":"2004001007",
                            "des":"網站行銷企劃"
                        },
                        {
                            "no":"2004001008",
                            "des":"媒體公關╱宣傳採買"
                        },
                        {
                            "no":"2004001009",
                            "des":"廣告文案╱企劃"
                        },
                        {
                            "no":"2004001010",
                            "des":"市場調查╱市場分析"
                        },
                        {
                            "no":"2004001011",
                            "des":"不動產╱商場開發人員"
                        },
                        {
                            "no":"2004001012",
                            "des":"行銷企劃助理"
                        },
                        {
                            "no":"2004001101",
                            "des":"網站運營經理/主管"
                        }
                    ]
                    },
                    {
                    "no":"2004002000",
                    "des":"產品企劃",
                    "n":[
                        {
                            "no":"2004002001",
                            "des":"產品企劃主管"
                        },
                        {
                            "no":"2004002002",
                            "des":"產品企劃開發人員"
                        },
                        {
                            "no":"2004002003",
                            "des":"傳播媒體企劃人員"
                        },
                        {
                            "no":"2004002004",
                            "des":"發行企劃╱出版人員"
                        },
                        {
                            "no":"2004002005",
                            "des":"遊戲企劃人員"
                        }
                    ]
                    },
                    {
                    "no":"2004003000",
                    "des":"專案管理",
                    "n":[
                        {
                            "no":"2004003001",
                            "des":"專案管理主管"
                        },
                        {
                            "no":"2004003002",
                            "des":"營運管理師╱系統整合╱ERP專案師"
                        },
                        {
                            "no":"2004003003",
                            "des":"軟體相關專案管理師"
                        },
                        {
                            "no":"2004003004",
                            "des":"其他專案管理師"
                        },
                        {
                            "no":"2004003005",
                            "des":"產品管理師"
                        }
                    ]
                    },
                    {
                    "no":"2005002000",
                    "des":"門市管理",
                    "n":[
                        {
                            "no":"2005002004",
                            "des":"門市╱店員╱專櫃人員"
                        },
                        {
                            "no":"2005002005",
                            "des":"售票╱收銀人員"
                        },
                        {
                            "no":"2005002001",
                            "des":"店長╱賣場管理人員"
                        },
                        {
                            "no":"2005002002",
                            "des":"連鎖店管理人員"
                        }
                    ]
                    },
                    {
                    "no":"2005003000",
                    "des":"業務銷售",
                    "n":[
                        {
                            "no":"2005003001",
                            "des":"國內業務主管"
                        },
                        {
                            "no":"2005003002",
                            "des":"國外業務主管"
                        },
                        {
                            "no":"2005003003",
                            "des":"專案業務主管"
                        },
                        {
                            "no":"2005003004",
                            "des":"國內業務人員"
                        },
                        {
                            "no":"2005003005",
                            "des":"國外業務人員"
                        },
                        {
                            "no":"2005003006",
                            "des":"廣告AE業務人員"
                        },
                        {
                            "no":"2005003007",
                            "des":"電話行銷人員"
                        },
                        {
                            "no":"2005003008",
                            "des":"醫藥業務代表"
                        },
                        {
                            "no":"2005003009",
                            "des":"不動產經紀人"
                        },
                        {
                            "no":"2005003010",
                            "des":"汽車銷售人員"
                        },
                        {
                            "no":"2005003011",
                            "des":"傳銷人員"
                        },
                        {
                            "no":"2005003012",
                            "des":"駐校代表"
                        },
                        {
                            "no":"2005003013",
                            "des":"業務助理"
                        },
                        {
                            "no":"2005003014",
                            "des":"產品事業處主管"
                        },
                        {
                            "no":"2005003015",
                            "des":"通路開發人員"
                        }
                    ]
                    },
                    {
                    "no":"2005004000",
                    "des":"貿易船務",
                    "n":[
                        {
                            "no":"2005004001",
                            "des":"國貿人員"
                        },
                        {
                            "no":"2005004002",
                            "des":"船務╱押匯╱報關人員"
                        },
                        {
                            "no":"2005004003",
                            "des":"保稅人員"
                        }
                    ]
                    }
                ]
            },
            {
                "no":"jobservice",
                "des":"客服支援類",
                "n":[
                    {
                    "no":"2005001000",
                    "des":"客戶服務",
                    "n":[
                        {
                            "no":"2005001001",
                            "des":"客戶服務主管"
                        },
                        {
                            "no":"2005001004",
                            "des":"電話客服類人員"
                        },
                        {
                            "no":"2005001005",
                            "des":"其他客戶服務人員"
                        }
                    ]
                    },
                    {
                    "no":"2006002000",
                    "des":"旅遊休閒",
                    "n":[
                        {
                            "no":"2006002001",
                            "des":"旅遊休閒類主管"
                        },
                        {
                            "no":"2006002002",
                            "des":"飯店或餐廳主管"
                        },
                        {
                            "no":"2006002003",
                            "des":"飯店工作人員"
                        },
                        {
                            "no":"2006002004",
                            "des":"空服員"
                        },
                        {
                            "no":"2006002005",
                            "des":"地勤人員"
                        },
                        {
                            "no":"2006002006",
                            "des":"領隊"
                        },
                        {
                            "no":"2006002007",
                            "des":"導遊"
                        },
                        {
                            "no":"2006002008",
                            "des":"OP╱旅行社人員"
                        }
                    ]
                    },
                    {
                    "no":"2006003000",
                    "des":"美容美髮",
                    "n":[
                        {
                            "no":"2006003001",
                            "des":"美容工作者"
                        },
                        {
                            "no":"2006003002",
                            "des":"美髮工作者"
                        },
                        {
                            "no":"2006003003",
                            "des":"整體造型師"
                        },
                        {
                            "no":"2006003004",
                            "des":"美姿美儀人員"
                        },
                        {
                            "no":"2006003005",
                            "des":"美療╱芳療師"
                        },
                        {
                            "no":"2006003006",
                            "des":"寵物美容專業人員"
                        },
                        {
                            "no":"2006003007",
                            "des":"美甲彩繪師"
                        },
                        {
                            "no":"2006003008",
                            "des":"美容類助理"
                        },
                        {
                            "no":"2006003009",
                            "des":"美髮類助理"
                        }
                    ]
                    },
                    {
                    "no":"2010002000",
                    "des":"維修服務",
                    "n":[
                        {
                            "no":"2010002001",
                            "des":"產品售後技術服務"
                        },
                        {
                            "no":"2010002002",
                            "des":"業務支援工程師"
                        },
                        {
                            "no":"2010002003",
                            "des":"電腦組裝╱測試"
                        },
                        {
                            "no":"2010002004",
                            "des":"通信測試維修人員"
                        },
                        {
                            "no":"2010002005",
                            "des":"產品維修人員"
                        },
                        {
                            "no":"2010002006",
                            "des":"空調冷凍技術人員"
                        },
                        {
                            "no":"2010002007",
                            "des":"汽車╱機車引擎技術人員"
                        },
                        {
                            "no":"2010002008",
                            "des":"其他汽車╱機車技術維修人員"
                        },
                        {
                            "no":"2010002009",
                            "des":"飛機裝修工"
                        },
                        {
                            "no":"2010002010",
                            "des":"農業及工業用機器裝修工"
                        },
                        {
                            "no":"2010002011",
                            "des":"電機裝修工"
                        },
                        {
                            "no":"2010002012",
                            "des":"電子設備裝修工"
                        },
                        {
                            "no":"2010002013",
                            "des":"電話及電報機裝修工"
                        },
                        {
                            "no":"2010002014",
                            "des":"電信及電力線路架設工"
                        },
                        {
                            "no":"2010002015",
                            "des":"精密儀器製造工及修理工"
                        },
                        {
                            "no":"2010002016",
                            "des":"FAE工程師"
                        }
                    ]
                    }
                ]
            },
            {
                "no":"jobmake",
                "des":"製造工程類",
                "n":[
                    {
                    "no":"2007001000",
                    "des":"軟體工程",
                    "n":[
                        {
                            "no":"2007001001",
                            "des":"軟體專案主管"
                        },
                        {
                            "no":"2007001002",
                            "des":"電子商務技術主管"
                        },
                        {
                            "no":"2007001003",
                            "des":"通訊軟體工程師"
                        },
                        {
                            "no":"2007001004",
                            "des":"軟體設計工程師"
                        },
                        {
                            "no":"2007001005",
                            "des":"韌體設計工程師"
                        },
                        {
                            "no":"2007001006",
                            "des":"Internet程式設計師"
                        },
                        {
                            "no":"2007001007",
                            "des":"電腦系統分析師"
                        },
                        {
                            "no":"2007001008",
                            "des":"電玩程式設計師"
                        },
                        {
                            "no":"2007001009",
                            "des":"其他資訊專業人員"
                        },
                        {
                            "no":"2007001010",
                            "des":"資訊助理人員"
                        },
                        {
                            "no":"2007001011",
                            "des":"BIOS工程師"
                        },
                        {
                            "no":"2007001012",
                            "des":"演算法開發工程師"
                        },
                        {
                            "no":"2007001101",
                            "des":"資訊/技術經理"
                        },
                        {
                            "no":"2007001102",
                            "des":"資訊/技術專員"
                        },
                        {
                            "no":"2007001103",
                            "des":"系統分析師"
                        },
                        {
                            "no":"2007001104",
                            "des":"系統架構師"
                        },
                        {
                            "no":"2007001105",
                            "des":"系統工程師"
                        },
                        {
                            "no":"2007001106",
                            "des":"技術支援經理"
                        },
                        {
                            "no":"2007001107",
                            "des":"技術支援工程師"
                        },
                        {
                            "no":"2007001108",
                            "des":"測試工程師"
                        },
                        {
                            "no":"2007001109",
                            "des":"技術人員"
                        }
                    ]
                    },
                    {
                    "no":"2007002000",
                    "des":"MIS網管",
                    "n":[
                        {
                            "no":"2007002001",
                            "des":"MIS╱網管主管"
                        },
                        {
                            "no":"2007002002",
                            "des":"資料庫管理人員"
                        },
                        {
                            "no":"2007002003",
                            "des":"MIS程式設計師"
                        },
                        {
                            "no":"2007002004",
                            "des":"MES工程師"
                        },
                        {
                            "no":"2007002005",
                            "des":"網路管理工程師"
                        },
                        {
                            "no":"2007002006",
                            "des":"系統維護╱操作人員"
                        },
                        {
                            "no":"2007002007",
                            "des":"資訊設備管制人員"
                        },
                        {
                            "no":"2007002008",
                            "des":"網路安全分析師"
                        }
                    ]
                    },
                    {
                    "no":"2008001000",
                    "des":"工程研發",
                    "n":[
                        {
                            "no":"2008001001",
                            "des":"硬體工程研發主管"
                        },
                        {
                            "no":"2008001002",
                            "des":"光電工程研發主管"
                        },
                        {
                            "no":"2008001003",
                            "des":"通訊工程研發主管"
                        },
                        {
                            "no":"2008001004",
                            "des":"其他工程研發主管"
                        },
                        {
                            "no":"2008001005",
                            "des":"電機技師╱工程師"
                        },
                        {
                            "no":"2008001006",
                            "des":"機械工程師"
                        },
                        {
                            "no":"2008001007",
                            "des":"機構工程師"
                        },
                        {
                            "no":"2008001008",
                            "des":"機電技師╱工程師"
                        },
                        {
                            "no":"2008001009",
                            "des":"電子工程師"
                        },
                        {
                            "no":"2008001010",
                            "des":"零件工程師"
                        },
                        {
                            "no":"2008001011",
                            "des":"硬體研發工程師"
                        },
                        {
                            "no":"2008001012",
                            "des":"PCB佈線工程師"
                        },
                        {
                            "no":"2008001013",
                            "des":"電源工程師"
                        },
                        {
                            "no":"2008001014",
                            "des":"類比IC設計工程師"
                        },
                        {
                            "no":"2008001015",
                            "des":"數位IC設計工程師"
                        },
                        {
                            "no":"2008001016",
                            "des":"半導體工程師"
                        },
                        {
                            "no":"2008001017",
                            "des":"微機電工程師"
                        },
                        {
                            "no":"2008001018",
                            "des":"光電工程師"
                        },
                        {
                            "no":"2008001019",
                            "des":"光學工程師"
                        },
                        {
                            "no":"2008001020",
                            "des":"電信╱通訊系統工程師"
                        },
                        {
                            "no":"2008001021",
                            "des":"RF通訊工程師"
                        },
                        {
                            "no":"2008001022",
                            "des":"IC佈局工程師"
                        },
                        {
                            "no":"2008001023",
                            "des":"助理工程師"
                        },
                        {
                            "no":"2008001024",
                            "des":"工程助理"
                        },
                        {
                            "no":"2008001025",
                            "des":"其他特殊工程師"
                        },
                        {
                            "no":"2008001026",
                            "des":"電子產品系統工程師"
                        },
                        {
                            "no":"2008001027",
                            "des":"太陽能技術工程師"
                        },
                        {
                            "no":"2008001028",
                            "des":"熱傳工程師"
                        },
                        {
                            "no":"2008001029",
                            "des":"聲學╱噪音工程師"
                        }
                    ]
                    },
                    {
                    "no":"2008002000",
                    "des":"化工研發",
                    "n":[
                        {
                            "no":"2008002001",
                            "des":"化工化學工程師"
                        },
                        {
                            "no":"2008002002",
                            "des":"紡織化學工程師"
                        },
                        {
                            "no":"2008002003",
                            "des":"特用化學工程師"
                        },
                        {
                            "no":"2008002004",
                            "des":"材料研發人員"
                        },
                        {
                            "no":"2008002005",
                            "des":"實驗化驗人員"
                        }
                    ]
                    },
                    {
                    "no":"2008003000",
                    "des":"生技研發",
                    "n":[
                        {
                            "no":"2008003001",
                            "des":"食品研發人員"
                        },
                        {
                            "no":"2008003002",
                            "des":"醫藥研發人員"
                        },
                        {
                            "no":"2008003003",
                            "des":"生物科技研發人員"
                        },
                        {
                            "no":"2008003004",
                            "des":"化學工程研發人員"
                        },
                        {
                            "no":"2008003005",
                            "des":"病理藥理研究人員"
                        },
                        {
                            "no":"2008003006",
                            "des":"農藝╱畜產研究人員"
                        },
                        {
                            "no":"2008003007",
                            "des":"醫療器材研發工程師"
                        }
                    ]
                    },
                    {
                    "no":"2009001000",
                    "des":"生產管理",
                    "n":[
                        {
                            "no":"2009001001",
                            "des":"生產管理主管"
                        },
                        {
                            "no":"2009001002",
                            "des":"工廠主管"
                        },
                        {
                            "no":"2009001003",
                            "des":"工業工程師╱生產線規劃"
                        },
                        {
                            "no":"2009001004",
                            "des":"生管"
                        },
                        {
                            "no":"2009001005",
                            "des":"生管助理"
                        }
                    ]
                    },
                    {
                    "no":"2009002000",
                    "des":"製程規劃",
                    "n":[
                        {
                            "no":"2009002001",
                            "des":"生產技術╱製程工程師"
                        },
                        {
                            "no":"2009002002",
                            "des":"生產設備工程師"
                        },
                        {
                            "no":"2009002003",
                            "des":"自動控制工程師"
                        },
                        {
                            "no":"2009002004",
                            "des":"SMT工程師"
                        },
                        {
                            "no":"2009002005",
                            "des":"半導體製程工程師"
                        },
                        {
                            "no":"2009002006",
                            "des":"LCD製程工程師"
                        },
                        {
                            "no":"2009002007",
                            "des":"半導體設備工程師"
                        },
                        {
                            "no":"2009002008",
                            "des":"LCD設備工程師"
                        }
                    ]
                    },
                    {
                    "no":"2009003000",
                    "des":"品管安規",
                    "n":[
                        {
                            "no":"2009003001",
                            "des":"品管╱品保主管"
                        },
                        {
                            "no":"2009003002",
                            "des":"品管╱品保工程師"
                        },
                        {
                            "no":"2009003003",
                            "des":"可靠度工程師"
                        },
                        {
                            "no":"2009003004",
                            "des":"硬體測試工程師"
                        },
                        {
                            "no":"2009003005",
                            "des":"測試人員"
                        },
                        {
                            "no":"2009003006",
                            "des":"IC封裝╱測試工程師"
                        },
                        {
                            "no":"2009003007",
                            "des":"軟韌體測試工程師"
                        },
                        {
                            "no":"2009003008",
                            "des":"EMC╱電子安規工程師"
                        },
                        {
                            "no":"2009003009",
                            "des":"ISO╱品保人員"
                        },
                        {
                            "no":"2009003010",
                            "des":"品管╱檢驗人員"
                        }
                    ]
                    },
                    {
                    "no":"2009004000",
                    "des":"環境衛生",
                    "n":[
                        {
                            "no":"2009004001",
                            "des":"勞工安全衛生管理師"
                        },
                        {
                            "no":"2009004002",
                            "des":"安全╱衛生相關檢驗人員"
                        },
                        {
                            "no":"2009004003",
                            "des":"環境工程人員 / 工程師"
                        },
                        {
                            "no":"2009004004",
                            "des":"防火及建築檢驗人員"
                        },
                        {
                            "no":"2009004005",
                            "des":"公共衛生人員"
                        },
                        {
                            "no":"2009004006",
                            "des":"廠務"
                        },
                        {
                            "no":"2009004007",
                            "des":"廠務助理"
                        },
                        {
                            "no":"2009004008",
                            "des":"勞工安全衛生管理人員"
                        }
                    ]
                    },
                    {
                    "no":"2012001000",
                    "des":"營建規劃",
                    "n":[
                        {
                            "no":"2012001001",
                            "des":"營建主管"
                        },
                        {
                            "no":"2012001002",
                            "des":"建築師"
                        },
                        {
                            "no":"2012001003",
                            "des":"都市╱交通規劃人員"
                        },
                        {
                            "no":"2012001004",
                            "des":"土木技師╱土木工程師"
                        },
                        {
                            "no":"2012001005",
                            "des":"結構技師╱結構工程師"
                        },
                        {
                            "no":"2012001006",
                            "des":"水利工程師"
                        },
                        {
                            "no":"2012001007",
                            "des":"工程配管繪圖"
                        },
                        {
                            "no":"2012001008",
                            "des":"水保技師╱水保工程師"
                        },
                        {
                            "no":"2012001009",
                            "des":"內業工程師"
                        },
                        {
                            "no":"2012001010",
                            "des":"工務人員╱助理"
                        },
                        {
                            "no":"2012001011",
                            "des":"水電工程師"
                        },
                        {
                            "no":"2012001012",
                            "des":"估算人員"
                        },
                        {
                            "no":"2012001013",
                            "des":"發包人員"
                        },
                        {
                            "no":"2012001014",
                            "des":"土地開發人員"
                        }
                    ]
                    },
                    {
                    "no":"2012002000",
                    "des":"營建施作",
                    "n":[
                        {
                            "no":"2012002001",
                            "des":"營造工程師"
                        },
                        {
                            "no":"2012002002",
                            "des":"工地監工╱主任"
                        },
                        {
                            "no":"2012002003",
                            "des":"建築物電力系統維修工"
                        },
                        {
                            "no":"2012002004",
                            "des":"砌磚工及砌石工"
                        },
                        {
                            "no":"2012002005",
                            "des":"混凝土工"
                        },
                        {
                            "no":"2012002006",
                            "des":"木工"
                        },
                        {
                            "no":"2012002007",
                            "des":"泥水工"
                        },
                        {
                            "no":"2012002008",
                            "des":"油漆工"
                        },
                        {
                            "no":"2012002009",
                            "des":"建築物清潔工"
                        },
                        {
                            "no":"2012002010",
                            "des":"金屬建材架構人員"
                        },
                        {
                            "no":"2012002011",
                            "des":"泥水小工及有關工作者"
                        },
                        {
                            "no":"2012002012",
                            "des":"其他營建構造工及有關工作者"
                        },
                        {
                            "no":"2012002013",
                            "des":"水電工"
                        },
                        {
                            "no":"2012002014",
                            "des":"防水施工人員"
                        }
                    ]
                    },
                    {
                    "no":"2012003000",
                    "des":"製圖測量",
                    "n":[
                        {
                            "no":"2012003001",
                            "des":"CAD╱CAM工程師"
                        },
                        {
                            "no":"2012003002",
                            "des":"建築設計╱繪圖人員"
                        },
                        {
                            "no":"2012003003",
                            "des":"水電及其他工程繪圖人員"
                        },
                        {
                            "no":"2012003004",
                            "des":"機械設計╱繪圖人員"
                        },
                        {
                            "no":"2012003005",
                            "des":"量測╱儀校人員"
                        },
                        {
                            "no":"2012003006",
                            "des":"室內設計╱裝潢人員"
                        },
                        {
                            "no":"2012003007",
                            "des":"景觀設計師"
                        }
                    ]
                    }
                ]
            },
            {
                "no":"jobedu",
                "des":"教育傳播類",
                "n":[
                    {
                    "no":"2013001000",
                    "des":"藝術設計",
                    "n":[
                        {
                            "no":"2013001001",
                            "des":"多媒體開發主管"
                        },
                        {
                            "no":"2013001002",
                            "des":"廣告設計"
                        },
                        {
                            "no":"2013001003",
                            "des":"展場╱櫥窗佈置人員"
                        },
                        {
                            "no":"2013001004",
                            "des":"多媒體動畫設計師"
                        },
                        {
                            "no":"2013001005",
                            "des":"平面設計╱美編人員"
                        },
                        {
                            "no":"2013001006",
                            "des":"網頁設計師"
                        },
                        {
                            "no":"2013001007",
                            "des":"美術設計"
                        },
                        {
                            "no":"2013001008",
                            "des":"商業設計"
                        },
                        {
                            "no":"2013001009",
                            "des":"服裝╱皮包╱鞋類設計"
                        },
                        {
                            "no":"2013001010",
                            "des":"工業設計"
                        },
                        {
                            "no":"2013001011",
                            "des":"包裝設計"
                        },
                        {
                            "no":"2013001012",
                            "des":"電腦繪圖人員"
                        },
                        {
                            "no":"2013001013",
                            "des":"設計助理"
                        },
                        {
                            "no":"2013001014",
                            "des":"織品設計"
                        }
                    ]
                    },
                    {
                    "no":"2013002000",
                    "des":"傳播藝術",
                    "n":[
                        {
                            "no":"2013002001",
                            "des":"節目製作人員"
                        },
                        {
                            "no":"2013002002",
                            "des":"演員"
                        },
                        {
                            "no":"2013002003",
                            "des":"導演╱導播"
                        },
                        {
                            "no":"2013002004",
                            "des":"模特兒"
                        },
                        {
                            "no":"2013002005",
                            "des":"音樂家╱作曲╱歌唱及演奏家"
                        },
                        {
                            "no":"2013002006",
                            "des":"舞蹈指導與舞蹈家"
                        },
                        {
                            "no":"2013002007",
                            "des":"藝術指導 ╱藝術總監"
                        },
                        {
                            "no":"2013002008",
                            "des":"電台工作人員"
                        },
                        {
                            "no":"2013002009",
                            "des":"播音╱配音人員"
                        },
                        {
                            "no":"2013002010",
                            "des":"影片製作技術人員"
                        },
                        {
                            "no":"2013002011",
                            "des":"燈光╱音響師"
                        },
                        {
                            "no":"2013002012",
                            "des":"攝影師"
                        },
                        {
                            "no":"2013002013",
                            "des":"其他娛樂事業人員"
                        },
                        {
                            "no":"2013002014",
                            "des":"節目助理"
                        },
                        {
                            "no":"2013002015",
                            "des":"攝影助理"
                        },
                        {
                            "no":"2013002016",
                            "des":"視聽工程類人員"
                        },
                        {
                            "no":"2013002017",
                            "des":"主持人"
                        }
                    ]
                    },
                    {
                    "no":"2014001000",
                    "des":"文字編譯",
                    "n":[
                        {
                            "no":"2014001001",
                            "des":"技術文件╱說明書編譯"
                        },
                        {
                            "no":"2014001002",
                            "des":"英文翻譯╱口譯人員"
                        },
                        {
                            "no":"2014001003",
                            "des":"日文翻譯╱口譯人員"
                        },
                        {
                            "no":"2014001004",
                            "des":"其他翻譯╱口譯人員"
                        },
                        {
                            "no":"2014001005",
                            "des":"文編╱校對╱文字工作者"
                        },
                        {
                            "no":"2014001006",
                            "des":"排版人員"
                        }
                    ]
                    },
                    {
                    "no":"2014002000",
                    "des":"傳媒採訪",
                    "n":[
                        {
                            "no":"2014002001",
                            "des":"記者╱採編"
                        },
                        {
                            "no":"2014002002",
                            "des":"其他傳媒相關工作"
                        }
                    ]
                    },
                    {
                    "no":"2016001000",
                    "des":"學術研究",
                    "n":[
                        {
                            "no":"2016001001",
                            "des":"物理相關研究員"
                        },
                        {
                            "no":"2016001002",
                            "des":"天文相關研究員"
                        },
                        {
                            "no":"2016001003",
                            "des":"氣象相關研究員"
                        },
                        {
                            "no":"2016001004",
                            "des":"化學相關研究員"
                        },
                        {
                            "no":"2016001005",
                            "des":"地質與地球科學研究員"
                        },
                        {
                            "no":"2016001006",
                            "des":"數學研究員"
                        },
                        {
                            "no":"2016001007",
                            "des":"統計學研究員"
                        },
                        {
                            "no":"2016001008",
                            "des":"社會╱人類學研究人員"
                        },
                        {
                            "no":"2016001009",
                            "des":"哲學╱歷史╱政治相關研究人員"
                        },
                        {
                            "no":"2016001010",
                            "des":"心理學研究人員"
                        },
                        {
                            "no":"2016001011",
                            "des":"生物學專業與研究"
                        },
                        {
                            "no":"2016001012",
                            "des":"應用科學研究員"
                        },
                        {
                            "no":"2016001013",
                            "des":"研究助理"
                        },
                        {
                            "no":"2016001014",
                            "des":"其他研究人員"
                        }
                    ]
                    },
                    {
                    "no":"2016002000",
                    "des":"教育輔導",
                    "n":[
                        {
                            "no":"2016002001",
                            "des":"補習班導師╱管理人員"
                        },
                        {
                            "no":"2016002002",
                            "des":"教授╱副教授╱助理教授"
                        },
                        {
                            "no":"2016002003",
                            "des":"助教"
                        },
                        {
                            "no":"2016002004",
                            "des":"中等學校教師"
                        },
                        {
                            "no":"2016002005",
                            "des":"國小學校教師"
                        },
                        {
                            "no":"2016002006",
                            "des":"特殊教育教師"
                        },
                        {
                            "no":"2016002007",
                            "des":"幼教班老師"
                        },
                        {
                            "no":"2016002008",
                            "des":"安親班老師"
                        },
                        {
                            "no":"2016002009",
                            "des":"升學補習班老師"
                        },
                        {
                            "no":"2016002010",
                            "des":"電腦補習班老師"
                        },
                        {
                            "no":"2016002011",
                            "des":"語文補習班老師"
                        },
                        {
                            "no":"2016002012",
                            "des":"珠心算老師"
                        },
                        {
                            "no":"2016002013",
                            "des":"美術老師"
                        },
                        {
                            "no":"2016002014",
                            "des":"音樂老師"
                        },
                        {
                            "no":"2016002015",
                            "des":"其他補習班老師"
                        },
                        {
                            "no":"2016002016",
                            "des":"其他才藝類老師"
                        },
                        {
                            "no":"2016002017",
                            "des":"其他類講師"
                        },
                        {
                            "no":"2016002018",
                            "des":"社工人員"
                        },
                        {
                            "no":"2016002019",
                            "des":"運動教練"
                        },
                        {
                            "no":"2016002020",
                            "des":"保育員"
                        },
                        {
                            "no":"2016002021",
                            "des":"講師"
                        },
                        {
                            "no":"2016002022",
                            "des":"數理補習班老師"
                        }
                    ]
                    }
                ]
            },
            {
                "no":"jobother",
                "des":"其他專業類",
                "n":[
                    {
                    "no":"2006001000",
                    "des":"餐飲專業",
                    "n":[
                        {
                            "no":"2006001001",
                            "des":"餐飲服務生"
                        },
                        {
                            "no":"2006001002",
                            "des":"中餐廚師"
                        },
                        {
                            "no":"2006001003",
                            "des":"西餐廚師"
                        },
                        {
                            "no":"2006001004",
                            "des":"其他類廚師"
                        },
                        {
                            "no":"2006001005",
                            "des":"麵包師"
                        },
                        {
                            "no":"2006001006",
                            "des":"西點╱蛋糕師"
                        },
                        {
                            "no":"2006001007",
                            "des":"調酒師╱吧台人員"
                        },
                        {
                            "no":"2006001008",
                            "des":"餐廚助手"
                        },
                        {
                            "no":"2006001009",
                            "des":"食品衛生管理師"
                        },
                        {
                            "no":"2006001010",
                            "des":"洗碗人員"
                        },
                        {
                            "no":"2006001011",
                            "des":"日式廚師"
                        }
                    ]
                    },
                    {
                    "no":"2010001000",
                    "des":"操作技術",
                    "n":[
                        {
                            "no":"2010001001",
                            "des":"領班"
                        },
                        {
                            "no":"2010001002",
                            "des":"作業員╱包裝員"
                        },
                        {
                            "no":"2010001003",
                            "des":"電機工程技術員"
                        },
                        {
                            "no":"2010001004",
                            "des":"CNC機台操作人員"
                        },
                        {
                            "no":"2010001005",
                            "des":"CNC電腦程式編排人員"
                        },
                        {
                            "no":"2010001006",
                            "des":"車床人員"
                        },
                        {
                            "no":"2010001007",
                            "des":"銑床人員"
                        },
                        {
                            "no":"2010001008",
                            "des":"沖壓模具技術人員"
                        },
                        {
                            "no":"2010001009",
                            "des":"塑膠模具技術人員"
                        },
                        {
                            "no":"2010001010",
                            "des":"機械加工技術人員"
                        },
                        {
                            "no":"2010001011",
                            "des":"塑膠射出技術人員"
                        },
                        {
                            "no":"2010001012",
                            "des":"印前製作╱印刷技術人員"
                        },
                        {
                            "no":"2010001013",
                            "des":"噴漆人員"
                        },
                        {
                            "no":"2010001014",
                            "des":"板金技術員"
                        },
                        {
                            "no":"2010001015",
                            "des":"PCB技術人員"
                        },
                        {
                            "no":"2010001016",
                            "des":"焊接及切割技術員"
                        },
                        {
                            "no":"2010001017",
                            "des":"紡織工務"
                        },
                        {
                            "no":"2010001018",
                            "des":"打版人員"
                        },
                        {
                            "no":"2010001019",
                            "des":"製鞋類人員"
                        },
                        {
                            "no":"2010001020",
                            "des":"車縫╱裁縫類人員"
                        },
                        {
                            "no":"2010001021",
                            "des":"樂器製造及調音技術員"
                        },
                        {
                            "no":"2010001022",
                            "des":"珠寶及貴金屬技術員"
                        },
                        {
                            "no":"2010001023",
                            "des":"機械裝配員"
                        },
                        {
                            "no":"2010001024",
                            "des":"電機設備裝配員"
                        },
                        {
                            "no":"2010001025",
                            "des":"農業及林業設備操作員"
                        },
                        {
                            "no":"2010001026",
                            "des":"推土機及有關設備操作員"
                        },
                        {
                            "no":"2010001027",
                            "des":"吊車╱起重機及有關設備操作員"
                        },
                        {
                            "no":"2010001028",
                            "des":"手工包裝工及有關工作者"
                        },
                        {
                            "no":"2010001029",
                            "des":"其他機械操作員"
                        },
                        {
                            "no":"2010001030",
                            "des":"鑄造╱鍛造模具技術人員"
                        },
                        {
                            "no":"2010001031",
                            "des":"粉末冶金模具技術人員"
                        },
                        {
                            "no":"2010001032",
                            "des":"壓鑄模具技術人員"
                        },
                        {
                            "no":"2010001033",
                            "des":"電鍍╱表面處理技術人員"
                        },
                        {
                            "no":"2010001034",
                            "des":"精密拋光技術人員"
                        },
                        {
                            "no":"2010001035",
                            "des":"線切割技術員"
                        },
                        {
                            "no":"2010001036",
                            "des":"雷射操作技術員"
                        },
                        {
                            "no":"2010001037",
                            "des":"染整技術人員"
                        },
                        {
                            "no":"2010001038",
                            "des":"鍋爐操作技術人員"
                        },
                        {
                            "no":"2010001039",
                            "des":"塗裝技術人員"
                        }
                    ]
                    },
                    {
                    "no":"2011001000",
                    "des":"採購倉管",
                    "n":[
                        {
                            "no":"2011001001",
                            "des":"採購主管"
                        },
                        {
                            "no":"2011001002",
                            "des":"資材主管"
                        },
                        {
                            "no":"2011001003",
                            "des":"採購人員"
                        },
                        {
                            "no":"2011001004",
                            "des":"倉管"
                        },
                        {
                            "no":"2011001005",
                            "des":"物管╱資材"
                        },
                        {
                            "no":"2011001006",
                            "des":"採購助理"
                        }
                    ]
                    },
                    {
                    "no":"2011002000",
                    "des":"運輸物流",
                    "n":[
                        {
                            "no":"2011002001",
                            "des":"運輸物流類主管"
                        },
                        {
                            "no":"2011002002",
                            "des":"運輸交通專業人員"
                        },
                        {
                            "no":"2011002003",
                            "des":"外務╱快遞╱送貨"
                        },
                        {
                            "no":"2011002004",
                            "des":"鐵路車輛駕駛員"
                        },
                        {
                            "no":"2011002005",
                            "des":"小客車╱計程車及小貨車司機"
                        },
                        {
                            "no":"2011002006",
                            "des":"大貨車及其他類司機"
                        },
                        {
                            "no":"2011002007",
                            "des":"飛行機師"
                        },
                        {
                            "no":"2011002008",
                            "des":"飛安人員"
                        },
                        {
                            "no":"2011002009",
                            "des":"倉儲物流人員"
                        },
                        {
                            "no":"2011002010",
                            "des":"船長╱大副╱船員"
                        }
                    ]
                    },
                    {
                    "no":"2015001000",
                    "des":"醫療專業",
                    "n":[
                        {
                            "no":"2015001001",
                            "des":"醫師"
                        },
                        {
                            "no":"2015001003",
                            "des":"醫事檢驗師"
                        },
                        {
                            "no":"2015001005",
                            "des":"藥師"
                        },
                        {
                            "no":"2015001006",
                            "des":"營養師"
                        },
                        {
                            "no":"2015001008",
                            "des":"公共衛生醫師"
                        },
                        {
                            "no":"2015001010",
                            "des":"麻醉醫師"
                        },
                        {
                            "no":"2015001012",
                            "des":"復建技術師"
                        },
                        {
                            "no":"2015001013",
                            "des":"治療師"
                        },
                        {
                            "no":"2015001016",
                            "des":"醫事放射師"
                        },
                        {
                            "no":"2015001002",
                            "des":"牙醫師"
                        },
                        {
                            "no":"2015001004",
                            "des":"護理師及護士"
                        },
                        {
                            "no":"2015001007",
                            "des":"獸醫師"
                        },
                        {
                            "no":"2015001009",
                            "des":"中醫師"
                        },
                        {
                            "no":"2015001011",
                            "des":"驗光師"
                        },
                        {
                            "no":"2015001015",
                            "des":"藥學助理"
                        },
                        {
                            "no":"2015001014",
                            "des":"其他醫療人員"
                        },
                        {
                            "no":"2015001017",
                            "des":"呼吸治療師"
                        },
                        {
                            "no":"2015001018",
                            "des":"職能治療師"
                        },
                        {
                            "no":"2015001019",
                            "des":"物理治療師"
                        },
                        {
                            "no":"2015001020",
                            "des":"語言治療師"
                        },
                        {
                            "no":"2015001021",
                            "des":"專科護理師"
                        }
                    ]
                    },
                    {
                    "no":"2015002000",
                    "des":"醫療保健",
                    "n":[
                        {
                            "no":"2015002001",
                            "des":"醫院行政管理人員"
                        },
                        {
                            "no":"2015002002",
                            "des":"照顧服務員"
                        },
                        {
                            "no":"2015002004",
                            "des":"按摩╱推拿師"
                        },
                        {
                            "no":"2015002005",
                            "des":"診所助理"
                        },
                        {
                            "no":"2015002006",
                            "des":"牙醫助理"
                        },
                        {
                            "no":"2015002007",
                            "des":"放射性設備使用技術員"
                        },
                        {
                            "no":"2015002008",
                            "des":"醫療設備控制人員"
                        },
                        {
                            "no":"2015002009",
                            "des":"其他醫療從業人員"
                        },
                        {
                            "no":"2015002010",
                            "des":"安心服務員"
                        },
                        {
                            "no":"2015002011",
                            "des":"照顧指導員"
                        },
                        {
                            "no":"2015002012",
                            "des":"居家服務督導員"
                        }
                    ]
                    },
                    {
                    "no":"2017001000",
                    "des":"軍警消防",
                    "n":[
                        {
                            "no":"2017001001",
                            "des":"志願役軍官╱士官╱士兵"
                        },
                        {
                            "no":"2017001002",
                            "des":"消防專業人員"
                        },
                        {
                            "no":"2017001003",
                            "des":"消防員"
                        },
                        {
                            "no":"2017001004",
                            "des":"救生員"
                        }
                    ]
                    },
                    {
                    "no":"2017002000",
                    "des":"保全警衛",
                    "n":[
                        {
                            "no":"2017002001",
                            "des":"保全人員╱警衛"
                        },
                        {
                            "no":"2017002002",
                            "des":"保全技術人員"
                        },
                        {
                            "no":"2017002003",
                            "des":"大樓管理員"
                        },
                        {
                            "no":"2017002005",
                            "des":"總幹事"
                        },
                        {
                            "no":"2017002004",
                            "des":"其他保安服務工作"
                        }
                    ]
                    },
                    {
                    "no":"2018001000",
                    "des":"農林漁牧",
                    "n":[
                        {
                            "no":"2018001001",
                            "des":"農藝作物栽培工作者"
                        },
                        {
                            "no":"2018001002",
                            "des":"一般動物飼育工作者"
                        },
                        {
                            "no":"2018001003",
                            "des":"林木伐運工作者"
                        },
                        {
                            "no":"2018001004",
                            "des":"水產養殖工作者"
                        }
                    ]
                    },
                    {
                    "no":"2018002000",
                    "des":"其他專業",
                    "n":[
                        {
                            "no":"2018002001",
                            "des":"顧問人員"
                        },
                        {
                            "no":"2018002002",
                            "des":"志工人員"
                        },
                        {
                            "no":"2018002003",
                            "des":"生命禮儀師"
                        },
                        {
                            "no":"2018002004",
                            "des":"星象占卜人員"
                        },
                        {
                            "no":"2018002005",
                            "des":"公家機關相關人員"
                        },
                        {
                            "no":"2018002006",
                            "des":"藝術品╱珠寶鑑價╱拍賣顧問"
                        },
                        {
                            "no":"2018002007",
                            "des":"家庭代工"
                        },
                        {
                            "no":"2018002008",
                            "des":"花藝╱園藝人員"
                        },
                        {
                            "no":"2018002009",
                            "des":"生鮮人員"
                        },
                        {
                            "no":"2018002010",
                            "des":"加油員"
                        },
                        {
                            "no":"2018002011",
                            "des":"派報生╱傳單派送"
                        },
                        {
                            "no":"2018002012",
                            "des":"清潔工╱資源回收人員"
                        },
                        {
                            "no":"2018002013",
                            "des":"家事服務人員"
                        },
                        {
                            "no":"2018002014",
                            "des":"汽車美容人員"
                        }
                    ]
                    }
                ]
            }
        ]
        },
    }),
    created() {
        $("title").text(`首頁 - inFlux普匯金融科技`);
    },
    mounted() {
        // 管理與財經
        this.workCategories.n = this.workCategories.n.map(v => {
            this.flattenWorkCategories.push({
                disabled: true,
                title: v.des,
                value: "",
            })

            // 經營幕僚
            v.n = v.n.map((j) => {
                //j.des = `　└ ${j.des}`
                j.des = `　　${j.des}`
                this.flattenWorkCategories.push({
                    disabled: true,
                    title: j.des,
                    value: "",
                })
                // 儲備幹部
                j.n = j.n.map((l, k) => {
                    // var concat = "├"
                    // if (k === j.n.length - 1) {
                    //     concat = "└"
                    // }
                    // l.des = `　　${concat} ${l.des}`
                    l.des = `　　　　${l.des}`
                    this.flattenWorkCategories.push({
                        disabled: false,
                        title: l.des,
                        value: l.no,
                    })
                    return l
                })
                return j
            })
            return v
        })

        console.log(this.workCategories)
    },
    methods: {
        calculateForm() {
            var score = 0
            switch (this.formGraduate) {
                case "phD":
                    score += 20
                    break
                case "master":
                    score += 15
                    break
                case "bachelor":
                    score += 10
                    break
                case "below":
                    score += 0
                    break
            }

            // 職業
            score += 10

            if (this.formCompany === "true") {
                score += 15
            } else {
                score += 5
            }

            var salary = parseInt(this.formSalary)
            if (!isNaN(salary)) {
                if (salary > 23800 && salary < 35000) {
                    score += 10
                } else if (salary > 35000) {
                    score += 15
                }
            }

            var loan = parseInt(this.formLoan)
            if (!isNaN(salary)) {
                if (loan < (salary * 22)) {
                    score += 5
                }
            }

            var returnx = parseInt(this.formReturn)
            if (!isNaN(returnx)) {
                if (returnx > (salary * 0.3)) {
                    score += 5
                } else {
                    score += 10
                }
            }

            // 信用卡總額
            score += 10
            var credit = parseInt(this.formCredit)


            var total = parseInt(this.formTotal)
            if (!isNaN(total) && !isNaN(credit)) {
                if (total > (credit * 0.7)) {
                    score += 5
                } else {
                    score += 15
                }
            }

            if (score >= 0 && score < 20) {
                this.formAnswerTotal = 0
                this.formAnswerSpan = 0
                this.formAnswerFee = 15
                this.formAnswerPer = 0
            } else if (score >= 20 && score < 50) {
                this.formAnswerTotal = 800000
                this.formAnswerSpan = "14~16%"
                this.formAnswerFee = 15
                this.formAnswerPer = 0
            } else if (score >= 50 && score < 70) {
                this.formAnswerTotal = 120000
                this.formAnswerSpan = "10~13%"
                this.formAnswerFee = 0
                this.formAnswerPer = 0
            } else if (score >= 70 && score < 80) {
                this.formAnswerTotal = 160000
                this.formAnswerSpan = "8~10%"
                this.formAnswerFee = 15
                this.formAnswerPer = 0
            } else if (score >= 80 && score < 90) {
                this.formAnswerTotal = 200000
                this.formAnswerSpan = "7~8%"
                this.formAnswerFee = 15
                this.formAnswerPer = 0
            } else if (score >= 90 && score < 100) {
                this.formAnswerTotal = 300000
                this.formAnswerSpan = "6~7%"
                this.formAnswerFee = 15
                this.formAnswerPer = 0
            }


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

.alesis-app {
    @include rwd {
        display: none;
    }
}

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
                color     : #5862a3;

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
                    // padding-left : 45%;
                    outline      : none;

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
                    outline      : none;

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
        .step-2 {
            display              : grid;
            grid-template-columns: 470px 1fr;
            gap                  : 3rem;
            margin               : 0 auto;
            max-width            : 800px;
            margin-left          : 9.1rem;

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
            margin-left          : 9.1rem;


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
            margin-left          : 9.1rem;

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
