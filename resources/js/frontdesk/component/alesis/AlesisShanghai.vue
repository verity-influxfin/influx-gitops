<template>
    <div class="上海容器">
        <img class="商標" src="/images/shanghai.png">
        <div class="分隔線"></div>
        <div class="表單" v-if="isForm">
            <input class="輸入欄位" type="text" placeholder="姓名">
            <input class="輸入欄位" type="text" placeholder="*手機">
            <input class="輸入欄位" type="text" placeholder="*E-mail">
            <a href="https://bit.ly/3xRNTdE" class="按鈕">前往優惠</a>
            <div class="註釋">
                信用貸款總費用年百分率說明：貸款總費用年百分率約2.62%~7.72%。例如：貸款金額：30萬元，貸款期間：5年，貸款年利率：前三個月1.88% (固定利率)，第四個月起2.25%~7.92%，開辦費為新臺幣3,000元，其總費用年百分率約為2.62%~7.72%。(1)本廣告揭露之年百分率係按主管機關備查之標準計算範例予以計算，實際貸款條件，以本行提供之產品為準，且每一顧客實際之年百分率仍以其個別貸款產品及授信條件而有所不同。(2)總費用年百分率不等於貸款利率。(3)本總費用年百分率之計算基準日係依據活動專案適用起日之本行貸款定儲指數(G)調整日期訂定之(當期利率請至本行網站查詢)，請詳閱本行官網定儲指數說明。例如：110年04月23日之貸款定儲指數(G)為0.80%。(4)上海商銀保有依申貸人資信條件核定貸款條件、實際核貸金額以及最終核准貸款與否之權利。
            </div>
        </div>
        <template v-if="!isForm">
            <div class="特色清單">
                <div class="項目">1日快速核准</div>
                <div class="項目">前3個月利率1.88%起</div>
                <div class="項目">額度最高達300萬，期間最長7年</div>
            </div>
            <div class="動作群組">
                <a class="動作" href="/scsbank">
                    <alesis-button :fluid="true" yPadding=".4rem" xPadding="1rem" size="1.1rem">了解更多</alesis-button>
                </a>

                <a class="動作" data-toggle="modal" data-target="#survey-modal" href="javascript:;">
                    <alesis-button :fluid="true" yPadding=".4rem" xPadding="1rem" size="1.1rem">取得資格</alesis-button>
                </a>
            </div>
        </template>

        <!-- Modal -->
        <div class="modal fade" id="survey-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-body 評估問卷">
                        <form class="表單" ref="borrowReport" v-if="!formCalculated">
                            <input type="hidden" name="identity" value="2" />
                            <div class="列">
                                <div class="標籤">1.我的教育程度：</div>
                                <div class="輸入欄位">
                                    <select v-model="formGraduate" name="educational_level">
                                        <option selected disabled value="">-請選擇-</option>
                                        <option value="phD">博士</option>
                                        <option value="master">碩士</option>
                                        <option value="bachelor">學士</option>
                                        <option value="below">學士以下</option>
                                    </select>
                                </div>
                            </div>
                            <div class="列">
                                <div class="標籤">2.我的職業屬於：</div>
                                <div class="輸入欄位">
                                    <select name="job">
                                        <option selected disabled>-請選擇-</option>
                                        <option :disabled="item.disabled" v-for="item, index in flattenWorkCategories" :key="index" :value="item.value">{{ item.title }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="列">
                                <div class="標籤">3.任職公司是否屬於上市櫃、金融機構或公家機關：</div>
                                <div class="輸入欄位">
                                    <select v-model="formCompany" name="is_top_enterprises">
                                        <option selected disabled value="">-請選擇-</option>
                                        <option value="1">是</option>
                                        <option value="0">否</option>
                                    </select>
                                </div>
                            </div>
                            <div class="列">
                                <div class="標籤">4.我的投保月薪約為：</div>
                                <div class="輸入欄位">
                                    <input type="number" min="0" step="100" v-model="formSalary" name="insurance_salary">
                                </div>
                            </div>
                            <div class="列">
                                <div class="標籤">5.我在銀行的貸款餘額約為：</div>
                                <div class="輸入欄位">
                                    <input type="number" min="0" step="100" v-model="formLoan" name="debt_amount">
                                </div>
                            </div>
                            <div class="列">
                                <div class="標籤">6.每月需攤還多少金額：</div>
                                <div class="輸入欄位">
                                    <input type="number" min="0" step="100" v-model="formReturn" name="monthly_repayment">
                                </div>
                            </div>
                            <div class="列">
                                <div class="標籤">7.信用卡總額度約為：</div>
                                <div class="輸入欄位">
                                    <input type="number" min="0" step="100" v-model="formCredit" name="creditcard_quota">
                                </div>
                            </div>
                            <div class="列">
                                <div class="標籤">8.近一個月信用卡帳單總金額約為：</div>
                                <div class="輸入欄位">
                                    <input type="number" min="0" step="100" v-model="formTotal" name="creditcard_bill">
                                </div>
                            </div>
                            <div class="列">
                                <div class="標籤">暱稱：</div>
                                <div class="輸入欄位">
                                    <input type="text" name="name">
                                </div>
                            </div>
                            <div class="列">
                                <div class="標籤">E-mail：</div>
                                <div class="輸入欄位">
                                    <input type="email" name="email">
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
                                    感謝您使用普匯的上班族貸款額度利率評估服務，<br>
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
                                <div class="說明 red">
                                    ►申請普匯上班族貸不留任何信用紀錄，不佔銀行額度，不影響銀行信用評估結果。
                                </div>
                                <div class="說明 yellow">
                                    ►僅為初步評估，實際貸款條件依照您真實提供的資料而定。
                                </div>
                                <div class="列">
                                    <button class="btn btn-primary" type="button" @click="formCalculated=false">返回</button>

                                    <!-- 上海商銀 -->
                                    <button class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#scsbank-event-modal">
                                        前往銀行申請頁面
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="scsbank-event-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="col-xs-12">
                            <p>信用貸款總費用年百分率說明：貸款總費用年百分率約2.62%~7.72%。例如：貸款金額：30萬元，貸款期間：5年，貸款年利率：前三個月1.88% (固定利率)，第四個月起2.25%~7.92%，開辦費為新臺幣3,000元，其總費用年百分率約為2.62%~7.72%。(1)本廣告揭露之年百分率係按主管機關備查之標準計算範例予以計算，實際貸款條件，以本行提供之產品為準，且每一顧客實際之年百分率仍以其個別貸款產品及授信條件而有所不同。(2)總費用年百分率不等於貸款利率。(3)本總費用年百分率之計算基準日係依據活動專案適用起日之本行貸款定儲指數(G)調整日期訂定之(當期利率請至本行網站查詢)，請詳閱本行官網定儲指數說明。例如：110年04月23日之貸款定儲指數(G)為0.80%。(4)上海商銀保有依申貸人資信條件核定貸款條件、實際核貸金額以及最終核准貸款與否之權利。</p>
                        </div>
                        <div class="col-xs-12">
                            <a href="https://bit.ly/3xRNTdE" class="btn btn-modal btn-block" target="_self">
                                確認前往申貸頁面
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AlesisButton from "./AlesisButton";

// 遠端資料
import WorkCategories from "../../data/work_categories"

export default {
    name : "AlesisShanghai",
    components: {
        AlesisButton,
    },
    data() {
        return {
            isForm: false,
            flattenWorkCategories: [],
            formGraduate   : "",
            formCompany    : "",
            formSalary     : "",
            formLoan       : "",
            formReturn     : "",
            formCredit     : "",
            formTotal      : "",
            formCalculated : false,
            formAnswerTotal: 0,
            formAnswerSpan : 0,
            formAnswerFee  : 0,
            formAnswerPer  : 0,
            isFormValid    : true,
            borrowReportResult   : {},
            workCategories : WorkCategories
        }
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
                j.des = `　　${j.des}`
                this.flattenWorkCategories.push({
                    disabled: true,
                    title: j.des,
                    value: "",
                })
                // 儲備幹部
                j.n = j.n.map((l, k) => {
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
        });
    },
    methods: {
        calculateForm() {
            this.isFormValid = false;
            let data = new FormData(this.$refs.borrowReport);

            try {

                let attrs = [
                    'identity',
                    'educational_level',
                    'job',
                    'is_top_enterprises',
                    'insurance_salary',
                    'debt_amount',
                    'monthly_repayment',
                    'creditcard_quota',
                    'creditcard_bill',
                    'name',
                    'email',
                ];

                attrs.forEach( attr => {
                    if ('' == data.get(attr)) {
                        throw new Error(`Invalid value ` + attr);
                    }
                });
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

            } catch (e) {
                this.isFormValid = true;
                alert('請提供正確資料');
            }
        },
    }
};
</script>

<style lang="scss" scoped>
    @import "./alesis";

    .上海容器 {
        display      : inline-block;
        border-radius: 17px;
        border       : 1px solid #2664a5;
        padding      : 1.5rem 1.25rem;
        width    : 380px;
        text-align   : justify;
        font-size    : 1.3rem;

        @include rwd {
            padding  : 1.5rem 1.25rem;
            font-size: 1.1rem;
            max-width: 80vw;
        }

        .商標 {
            margin : 0 auto;
            display: block;
        }

        .分隔線 {
            height    : 1px;
            background: #2664a5;
            margin    : 1rem 0;
        }

        .特色清單 {
            line-height: 2;
            margin-left: 1rem;

            .項目 {
                display: list-item;
                line-height: 1.5em;
                padding-bottom: 1em;

                @include rwd {
                    font-size: 15px;
                }
            }
        }
        .動作群組 {
            margin-top: 1rem;
            display   : flex;
            .動作 {
                flex         : 1;
                margin-right : 1rem;
                cursor       : pointer;
                & + .動作 {
                    margin-right: 0;
                }
            }
        }

        & > .表單 {
            display       : flex;
            flex-direction: column;

            .輸入欄位 {
                appearance   : none;
                border       : 1px solid #326398;
                border-radius: .4rem;
                line-height  : 2.2;
                padding      : 0 .5rem;
                outline      : none;
            }

            .按鈕 {
                background   : #326398;
                width        : 100%;
                display      : block;
                color        : #FFF;
                padding      : .5rem;
                text-align   : center;
                border-radius: .4rem;
            }
            & > *:not(:last-child) {
                margin-bottom: 1rem;
            }
            .註釋 {
                font-size: .8rem;
            }
        }
    }
</style>
