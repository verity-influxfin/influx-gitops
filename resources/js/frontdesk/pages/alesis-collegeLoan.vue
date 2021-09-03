<template>
    <div>
        <!-- 標頭 -->
        <alesis-loan-header header="學生貸" content="快速、安全、隱私、簡單<br>最受學生信賴的線上借貸平台！<br>最高額度 15 萬、最低利率 5%<br>幫助年輕人完成夢想！" image="/images/asian-student-sitting-campus-stairs-outdoors-with-smartphone-staring-distance1.jpg"></alesis-loan-header>
        <!-- 標頭 -->

        <alesis-space size="medium"></alesis-space>
        <!-- 適用對象 -->
        <div class="適用對象">
            <alesis-header>
                <div class="標題">適用對象</div>
                <div class="註釋">
                    滿20歲的在校生，準備您的雙證件、學生證、金融卡，APP拍照上傳，<br>10分鐘申請、30分鐘審核、60分鐘極速到帳！
                </div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="large"></alesis-space>
                <div class="項目列表">
                    <div class="項目">
                        <img class="圖示" src="/images/alesis-symbol-student.svg">
                        <div class="標籤">學生身分</div>
                    </div>
                    <div class="項目">
                        <img class="圖示" src="/images/alesis-symbol-id.svg">
                        <div class="標籤">雙證件</div>
                    </div>
                    <div class="項目">
                        <img class="圖示" src="/images/alesis-symbol-creditcard.svg">
                        <div class="標籤">金融帳號</div>
                    </div>
                    <div class="項目">
                        <img class="圖示" src="/images/alesis-symbol-email.svg">
                        <div class="標籤">電子信箱</div>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 適用對象 -->

        <!-- 步驟說明 -->
        <div class="步驟說明">
            <alesis-header>
                <div class="標題">申貸四步驟</div>
            </alesis-header>
            <alesis-section :secondary="true">
                <alesis-space size="medium"></alesis-space>
                <div class="swiper-container steps-container">
                    <div class="swiper-wrapper">
                        <!-- 步驟一 -->
                        <div class="swiper-slide">
                            <div class="步驟一">
                                <div class="左邊"></div>
                                <div class="中間">
                                    <img src="/images/alesis-collegeloan-step1.svg" class="圖片">
                                </div>
                                <div class="右邊">
                                    <div class="標題">Step 1. 選擇額度</div>
                                    <div class="內容">
                                        提供完整資訊<br>
                                        有助提高額度
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / 步驟一 -->

                        <!-- 步驟二 -->
                        <div class="swiper-slide">
                            <div class="步驟二">
                                <div class="左邊"></div>
                                <div class="中間">
                                    <img src="/images/alesis-collegeloan-step2.svg" class="圖片">
                                </div>
                                <div class="右邊">
                                    <div class="標題">Step 2. 上傳資料</div>
                                    <div class="內容">
                                        AI數據分析審核<br>
                                        全程無人打擾
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / 步驟二 -->

                        <!-- 步驟三 -->
                        <div class="swiper-slide">
                            <div class="步驟三">
                                <div class="左邊"></div>
                                <div class="中間">
                                    <img src="/images/alesis-collegeloan-step3.svg" class="圖片">
                                </div>
                                <div class="右邊">
                                    <div class="標題">Step 3. 持證自拍</div>
                                    <div class="內容">
                                        審核成功後<br>
                                        立即上架幫您媒合投資人
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / 步驟三 -->

                        <!-- 步驟四 -->
                        <div class="swiper-slide">
                            <div class="步驟四">
                                <div class="左邊"></div>
                                <div class="中間">
                                    <img src="/images/alesis-collegeloan-step4.svg" class="圖片">
                                </div>
                                <div class="右邊">
                                    <div class="標題">Step 4. 上架媒合</div>
                                    <div class="內容">
                                        24小時收到款項
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / 步驟四 -->
                    </div>

                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 步驟說明 -->

        <!-- 貸安吶 -->
        <div class="貸安吶" id="credit_report">
            <alesis-header>
                <div class="標題">試算您的信用額度</div>
                <div class="標題">貸安吶</div>
                <div class="標題 標題_外框線的">請您依現在的真實狀況填寫以下題目</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="tiny"></alesis-space>
                <alesis-space size="medium"></alesis-space>
                <form class="表單" ref="borrowReport">
                    <div class="列">
                        <div class="標籤">1.就讀學校：</div>
                        <div class="輸入欄位">
                            <input type="hidden" name="identity" value="1"/>
                            <select v-model="formSchool" name="school_name">
                                <option selected disabled value="">-請選擇-</option>
                                <option v-for="item, index in schools" :key="index" :value="item.title">{{ item.title }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">2.科系：</div>
                        <div class="輸入欄位">
                            <select v-model="formSchoolDiscipline" name="department">
                                <option selected disabled value="">-請選擇-</option>
                                <option :disabled="item.disabled" v-for="item, index in schoolDisciplines[formSchool]" :key="index" :value="item.value">{{ item.title }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">3.是否有學貸：</div>
                        <div class="輸入欄位">
                            <select name="is_student_loan">
                                <option selected disabled>-請選擇-</option>
                                <option value="True">是</option>
                                <option value="False">否</option>
                            </select>
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">4.是否有打工或兼職：</div>
                        <div class="輸入欄位">
                            <select name="is_part_time_job">
                                <option selected disabled>-請選擇-</option>
                                <option value="True">是</option>
                                <option value="False">否</option>
                            </select>
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">5.每月經濟收入：</div>
                        <div class="輸入欄位">
                            <select name="monthly_economy">
                                <option selected disabled>-請選擇-</option>
                                <option value="<5000">低於 5,000 元</option>
                                <option value="5000-10000">5,000 元 - 10,000 元</option>
                                <option value="10000-15000">10,000 元 - 15,000 元</option>
                                <option value="15000-20000">15,000 元 - 20,000 元</option>
                                <option value=">20000">20,000 以上</option>
                            </select>
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">暱稱：</div>
                        <div class="輸入欄位">
                            <input type="text" name="name" required>
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">E-mail：</div>
                        <div class="輸入欄位">
                            <input type="email" name="email" required>
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤"></div>
                        <div class="輸入欄位">
                            <button @click="calculateForm" type="button" :disabled="!isFormValid">取得報告</button>
                        </div>
                    </div>
                </form>
                <div class="結果" v-if="formCalculated">
                    <div class="展示區塊">
                        <img src="/images/alesis-phone-and-cash.svg" class="圖片">
                    </div>
                    <div class="內容">
                        <div class="標題">親愛的用戶您好：</div>
                        <div class="段落">
                            感謝您使用普匯的學生貸款額度利率評估服務，<br>
                            經系統自動評估後，符合您的額度及利率區間如下：
                        </div>
                        <div class="數值">
                            <div class="列">
                                <div class="標籤">1. 可借款額度：</div>
                                <div class="值">{{borrowReportResult.amount | amount}}</div>
                            </div>
                            <div class="列">
                                <div class="標籤">2. 借款利率區間：</div>
                                <div class="值">{{borrowReportResult.rate}}</div>
                            </div>
                            <div class="列">
                                <div class="標籤">3. 手續費金額：</div>
                                <div class="值">{{borrowReportResult.platform_fee | amount}}</div>
                            </div>
                            <div class="列">
                                <div class="標籤">4. 每期攤還金額約：</div>
                                <div class="值">{{borrowReportResult.repayment}}</div>
                            </div>
                        </div>
                        <div class="說明">►僅為初步評估，實際貸款條件依照您真實提供的資料而定。</div>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 貸安吶 -->

        <!-- 服務範圍 -->
        <div class="服務範圍">
            <alesis-header>
                <div class="標題">服務範圍廣</div>
                <div class="註釋">
                    超過156所大學，不論是生活急需、還是補習進修，都是找普匯！<br>
                    成立以來，幫助數萬名學生，陪伴度過資金難關！
                </div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="large"></alesis-space>
                <div class="包裹容器">
                    <div class="專案">
                        <div class="項目">
                            <alesis-project v-bind="collegePreviews[0]"></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project v-bind="collegePreviews[1]"></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project v-bind="collegePreviews[2]"></alesis-project>
                        </div>
                        <div class="項目">
                            <alesis-project v-bind="collegePreviews[3]"></alesis-project>
                        </div>
                    </div>
                    <div class="地圖">
                        <alesis-taiwan-map></alesis-taiwan-map>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 服務範圍 -->

        <!-- 為何選擇 -->
        <div class="為何選擇">
            <alesis-header>
                <div class="標題">為什麼選擇普匯</div>
            </alesis-header>
            <alesis-section :secondary="true">
                <alesis-space size="medium"></alesis-space>
                <div class="項目列表">
                    <div class="項目">
                        <img class="圖示" src="/images/alesis-symbol-taiwan.svg">
                        <div class="標籤">全台學生貸市佔量最高</div>
                    </div>
                    <div class="項目">
                        <img class="圖示" src="/images/alesis-symbol-dnd.svg">
                        <div class="標籤">全程使用APP  無人打擾</div>
                    </div>
                    <div class="項目">
                        <img class="圖示" src="/images/alesis-symbol-cash.svg">
                        <div class="標籤">放款不受休假限制</div>
                    </div>
                    <div class="項目">
                        <img class="圖示" src="/images/alesis-symbol-approve.svg">
                        <div class="標籤">核貸率高達90%以上</div>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 為何選擇 -->

        <!-- 案例分享 -->
        <div class="案例分享">
            <alesis-header class="區塊標題_拖尾的">
                <div class="標題 標題_外框線的">案例分享 / 用戶分享</div>
            </alesis-header>
            <alesis-section :secondary="true">
                <alesis-space size="small"></alesis-space>
                <alesis-suggestion-reviews type="college"></alesis-suggestion-reviews>
                <alesis-space size="small"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 案例分享 -->

        <!-- 合作對象 -->
        <div class="合作對象">
            <alesis-header>
                <div class="標題">合作夥伴</div>
            </alesis-header>
            <alesis-section :secondary="true">
                <div class="container">
                    <alesis-space size="medium"></alesis-space>
                    <div class="群組">
                        <div class="項目">
                            <img src="/images/alesis-bordered-ntpu.svg" class="圖片">
                            <div class="名稱">國立臺北大學</div>
                        </div>
                        <div class="項目">
                            <img src="/images/alesis-bordered-ntu.svg" class="圖片">
                            <div class="名稱">國立臺灣大學</div>
                        </div>
                        <div class="項目">
                            <img src="/images/alesis-bordered-ntub.svg" class="圖片">
                            <div class="名稱">國立臺北商業大學</div>
                        </div>
                        <div class="項目">
                            <img src="/images/alesis-bordered-shanghai.svg" class="圖片">
                            <div class="名稱">上海商業儲蓄銀行</div>
                        </div>
                        <div class="項目">
                            <img src="/images/alesis-bordered-skbank.svg" class="圖片">
                            <div class="名稱">新光銀行</div>
                        </div>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 合作對象 -->

        <!-- 應用程式推薦 -->
        <alesis-app-recommendation action="我想借款"></alesis-app-recommendation>
        <!-- / 應用程式推薦 -->
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
// 遠端資料
import CollegeSchools from "../data/college_schools"
import StudentDone   from "../data/projects_student_done"

import 'swiper/swiper.scss';
import "swiper/components/navigation/navigation.min.css"
import SwiperCore, {
  Navigation
} from 'swiper/core';

$( document ).ready(function() {
    setTimeout(() => {
        let toPoint = location.hash;
        if(toPoint){
            document.getElementById("credit_report").scrollIntoView();
        }
    },5000)
});
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
    filters: {
        amount: (value) => {
              return Number(parseFloat(value).toFixed(3)).toLocaleString('en')
        }
    },
    data: () => ({
        formSchool          : "",
        formSchoolDiscipline: "",
        formRank            : "",
        formHasAward        : "",
        name                : "",
        email               : "",
        isStudentLoan       : null,
        isPartTimeJob       : null,
        monthlyEconomy      : null,
        formCalculated      : false,
        schools             : [],
        schoolDisciplines   : {},
        originalSchools     : CollegeSchools,
        collegePreviews     : {},
        borrowReportResult  : {},
        isFormValid         : true,
    }),
    created() {
        $("title").text(`學生貸 - inFlux普匯金融科技`);
    },
    mounted() {
        this.initSchools();
        this.initSteps();
    },
    methods: {
        // initSchools 會扁平化學校清單，並且展開其科系資料作為下拉式選單選項。
        initSchools() {
            // 遍歷整個學校，然後扁平化其結構。
            Object.keys(this.originalSchools).forEach(v => {
                // 將每個學校推入到學校清單中。
                this.schools.push({
                    title: v,
                    value: this.originalSchools[v].shortName
                })
                // 替這個學校建立對應的科系陣列。
                this.schoolDisciplines[v] = [];
                // 遍歷這個學校的科系陣列，並將期推到對應的科系清單裡，試圖扁平化。
                Object.keys(this.originalSchools[v].discipline).forEach(j => {
                    this.schoolDisciplines[v].push({
                        disabled: true,
                        title: j,
                        value: "",
                    })
                    // 扁平化這個科系下的子科系，但用全形空白當作 Indent。
                    this.originalSchools[v].discipline[j].forEach(k => {
                        this.schoolDisciplines[v].push({
                            disabled: false,
                            title: `　　${k}`,
                            value: k,
                        })
                    })
                })
            })
        },
        // 學生案件
        getStudentCase() {
            let studenCase = new FormData();
            studenCase.append('status',10);
            studenCase.append('product_id',1);

            axios.post(
                `${location.origin}/getCase`,
                studenCase
            ).then((res) => {
                this.collegePreviews = res.data
            })
            .catch((error) => {
                console.error('getCase 發生錯誤，請稍後再試');
            });
        },

        // initSteps 會初始化步驟教學幻燈片。
        initSteps() {
            SwiperCore.use([Navigation]);
            new Swiper('.swiper-container.steps-container', {
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        },

        // calculateForm 會計算表單。
        calculateForm() {
            this.isFormValid = false
            let data = new FormData(this.$refs.borrowReport)

            axios({
                url: `${location.origin}/getBorrowReport`,
                method: 'post',
                data: data,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Accept': 'application/json',
                }
            }).then((res) => {
                this.borrowReportResult.amount = res.data.amount
                this.borrowReportResult.rate = res.data.rate
                this.borrowReportResult.platform_fee = res.data.platform_fee
                this.borrowReportResult.repayment = res.data.repayment
                this.formCalculated = true
                this.isFormValid = true
            })
            .catch((error) => {
                console.error('getBorrowReport 發生錯誤，請稍後再試');
            });

        },
    },
};
</script>

<style lang="scss">
@import "../component/alesis/alesis";

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

.swiper-container.steps-container {
    width: 900px;
    height: 549px;

    @include rwd {
        width: 100%;
    }
}

button:disabled,
button[disabled] {
    cursor: no-drop;
}

.swiper-container.human-container {
    max-width: 1170px;
    padding-bottom: 3rem;
    padding-top: 3rem;

    .swiper-slide {
        .set {
            max-width: 90%;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 3rem;
            margin: 0 auto;
        }
    }
}

.swiper-button-next, .swiper-button-prev {
    opacity: 0.6;
    transform: scale(0.7);
}

.swiper-button-disabled {
    display: none;
}

.alesis-humans {
    position: relative;
    --alesis-xheader-offset: -70%;
    --alesis-xsection-offset-top: 8rem;
}

/**
 * 計數器
 */
@media only screen and (max-width: 767px) {
    .計數器 {
        display: none;
    }
    .適用對象 {
        margin-top: 100px;
        .註釋 {
            max-width: 19rem !important;
        }
    }
}
.計數器 {
    margin   : 4rem auto 8rem;
    max-width: 1280px;

    @include rwd {
        margin-bottom: 3rem;
    }
}

.計數器 .包裹容器 {
    display        : flex;
    justify-content: center;
}

/**
 * 服務範圍
 */

.服務範圍 {
    position: relative;

    --alesis-xheader-offset     : -5rem;
    --alesis-xsection-offset-top: 8rem;

    @include rwd {
        --alesis-xheader-offset: -4.5rem;
    }
}

.服務範圍 .包裹容器 {
    display              : grid;
    max-width            : 1620px;
    margin               : 0 auto;
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
}

.服務範圍 .包裹容器 .專案 {
    display              : grid;
    grid-template-columns: repeat(2, 1fr);
    gap                  : 3rem;
    height               : min-content;
    margin               : 0 2rem;

    @include rwd {
        grid-template-columns: repeat(1, 1fr);
        transform            : scale(0.9);
        margin               : -3rem 0;
    }
}

.服務範圍 .包裹容器 .地圖 {
    pointer-events: none;

    @include rwd {
        margin-top: -4rem;
    }
}

/**
 * 步驟說明
 */

.步驟說明 {
    position: relative;

    --alesis-xsection-offset-top: 8rem;
}

.步驟說明 .步驟一,
.步驟說明 .步驟二,
.步驟說明 .步驟三,
.步驟說明 .步驟四 {
    display              : grid;
    grid-template-columns: repeat(3, 1fr);
    gap                  : 3rem;
    max-width            : 900px;

    @include rwd {
        grid-template-columns: repeat(1, 1fr);
        align-items          : center;
        justify-content      : center;
    }
}

.步驟說明 .步驟一 .左邊,
.步驟說明 .步驟二 .左邊,
.步驟說明 .步驟三 .左邊,
.步驟說明 .步驟四 .左邊 {
    @include rwd {
        display: none;
    }
}

.步驟說明 .步驟一 .中間,
.步驟說明 .步驟二 .中間,
.步驟說明 .步驟三 .中間,
.步驟說明 .步驟四 .中間 {
    text-align: center;
}

.步驟說明 .步驟一 .中間 .圖片,
.步驟說明 .步驟二 .中間 .圖片,
.步驟說明 .步驟三 .中間 .圖片,
.步驟說明 .步驟四 .中間 .圖片 {
    @include rwd {
        width: 200px;
    }
}

.步驟說明 .步驟一 .右邊,
.步驟說明 .步驟二 .右邊,
.步驟說明 .步驟三 .右邊,
.步驟說明 .步驟四 .右邊 {
    display        : flex;
    justify-content: center;
    flex-direction : column;

    @include rwd {
        margin      : 0 auto;
        padding-left: 1rem;
    }
}

.步驟說明 .步驟一 .右邊 .標題,
.步驟說明 .步驟二 .右邊 .標題,
.步驟說明 .步驟三 .右邊 .標題,
.步驟說明 .步驟四 .右邊 .標題 {
    color    : #112E53;
    font-size: 1.3rem;

    @include rwd {
        font-size: 1.1rem;
    }
}

.步驟說明 .步驟一 .右邊 .標題 .內容,
.步驟說明 .步驟二 .右邊 .標題 .內容,
.步驟說明 .步驟三 .右邊 .標題 .內容,
.步驟說明 .步驟四 .右邊 .標題 .內容 {
    color    : #5D5555;
    font-size: 1.1rem;

    @include rwd {
        font-size: 1rem;
        padding-left: 1rem;
    }
}
.步驟說明 .swiper-button-next,
.步驟說明 .swiper-button-prev {

    @include rwd {
        margin-top: -7rem;
    }
}


/**
 * 貸安吶
 */

.貸安吶 {
    position  : relative;
    margin-top: 4rem;

    --alesis-xheader-offset     : -8rem;
    --alesis-xsection-offset-top: 8rem;

    @include rwd {
        margin-top: 1rem;

        --alesis-xheader-offset: -6rem;
    }
}

.貸安吶 .表單 {
    max-width: 1280px;
    margin   : 0 auto;
}

.貸安吶 .表單 .列 {
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
}

.貸安吶 .表單 .列 .標籤 {
    text-align: right;
    font-size : 1.4rem;
    color     : #5862a3;

    @include rwd {
        text-align: left;
        font-size : 1rem;
    }
}

.貸安吶 .表單 .列 .輸入欄位 button {
    outline: none;
}

.貸安吶 .表單 .列 .輸入欄位 select {
    width        : 100%;
    padding      : .5rem 1rem;
    font-size    : 1.4rem;
    border-radius: .4rem;
    border       : 1px solid #036eb7;
    background   : transparent;
    text-align   : center;
    color        : #036eb7;
    outline      : none;

    @include rwd {
        font-size: 1rem;
        padding  : .5rem;
    }
}

.貸安吶 .表單 .列 .輸入欄位 input {
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

.貸安吶 .表單 .列 .輸入欄位 button {
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

.貸安吶 .結果 {
    display      : flex;
    align-items  : center;
    margin-top   : 8rem;
    margin-bottom: 2rem;

    @include rwd {
        margin-top    : 3rem;
        margin-bottom : 0;
        flex-direction: column;
    }
}

.貸安吶 .結果 .展示區塊 {
    flex      : 1;
    text-align: right;
}

.貸安吶 .結果 .展示區塊 .圖片 {
    height: 350px;

    @include rwd {
        width        : 70vw;
        height       : auto;
        margin-bottom: 2rem;
    }
}

.貸安吶 .結果 .內容 {
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
}

.貸安吶 .結果 .內容 .標題 {
    margin-bottom: 1.5rem;
    font-size    : 1.8rem;

    @include rwd {
        font-size: 1.2rem;
    }
}

.貸安吶 .結果 .內容 .段落 {
    font-size    : 1.3rem;
    line-height  : 1.7;
    margin-bottom: 1.5rem;

    @include rwd {
        font-size: 1rem;
    }
}

.貸安吶 .結果 .內容 .數值 {
    font-size    : 1.2rem;
    line-height  : 1.8;
    margin-bottom: 2rem;

    @include rwd {
        font-size: 1rem;
    }
}

.貸安吶 .結果 .內容 .數值 .列 {
    margin : 0;
    display: flex;
}

.貸安吶 .結果 .內容 .數值 .列 .標籤 {
    color: #036EB7;
}

.貸安吶 .結果 .內容 .數值 .列 .值 {
    color      : #5d5555;
    font-weight: normal;
}

.貸安吶 .結果 .內容 .說明 {
    color      : #f29600;
    font-size  : .9rem;
    font-weight: normal;
}

/**
 * 合作對象
 */

.合作對象 {
    position : relative;
    margin   : 4rem auto -4rem;

    --alesis-xsection-offset-top: 8rem;
}

.合作對象 .群組 {
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
}

.合作對象 .群組 .項目 {
    text-align     : center;
    text-decoration: none;

    @include rwd {
        flex: calc(100% / 3);

    }

    @include rwd-minimum {
        &:nth-child(4) {
            .圖片,
            .名稱 {
                margin-left: 0;
            }
        }
        &:nth-child(5) {
            .圖片,
            .名稱 {
                margin-right: 0;
            }
        }
    }
}

.合作對象 .群組 .項目 .圖片 {
    width: 130px;

    @include rwd {
        width: 90px;
    }
}

.合作對象 .群組 .項目 .名稱 {
    margin-top : 1rem;
    color      : #5D5555;
    font-weight: bold;

    @include rwd {
        font-size: .9rem;
    }
}

/**
 * 適用對象
 */

.適用對象 {
    position: relative;

    --alesis-xheader-offset        : -33%;
}


.適用對象 .項目列表 {
    display              : grid;
    max-width            : 1280px;
    margin               : 0 auto;
    grid-template-columns: repeat(4, 1fr);
    align-items          : center;
    justify-content      : center;

    @include rwd {
        grid-template-columns: repeat(2, 1fr);
        gap                  : 2rem 0rem;
        max-width            : 310px;
    }
}

.適用對象 .項目列表 .項目 {
    text-align: center;
}

.適用對象 .項目列表 .項目 .圖示 {
    width: 100px;

    @include rwd {
        width: 80px;
    }
}

.適用對象 .項目列表 .項目 .標籤 {
    margin-top : 1rem;
    color      : #112E53;
    font-size  : 1.3rem;

    @include rwd {
        font-size : 1rem;
        margin-top: .5rem;
    }
}

/**
 * 為何選擇
 */

.為何選擇 {
    position: relative;

    --alesis-xsection-offset-top: 7rem;
}

.為何選擇 .項目列表 {
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
        align-items          : flex-start;
    }
}

.為何選擇 .項目列表 .項目 {
    text-align: center;
}

.為何選擇 .項目列表 .項目 .圖示 {
    @include rwd {
        width: 70px;
    }
}

.為何選擇 .項目列表 .項目 .標籤 {
    margin-top : 1rem;
    font-weight: bold;
    color      : #112E53;
    font-size  : 1.3rem;

    @include rwd {
        font-size: 1rem;
    }
}

/**
 * 案例分享
 */

.案例分享 {
    position: relative;

    --alesis-xheader-offset: -70%;
}
</style>
