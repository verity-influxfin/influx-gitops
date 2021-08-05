<template>
    <div>
        <!-- 標頭 -->
        <alesis-loan-header header="上班族貸" content="
        貸款特色：快速審核、方便操作、申貸流程<br>
        　　　　　簡單明瞭、隱私無照會
        <br><br>
        適用對象：已畢業且有穩定收入的上班族<br>
        　　　　　皆可申請！" image="/images/螢幕擷取畫面-2021-07-14-113234.jpg"></alesis-loan-header>
        <!-- 標頭 -->

        <!-- 快速審核 -->
        <div class="快速審核">
            <alesis-header>
                <div class="標題">針對上市櫃、公家機關員工 精英快審</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="medium"></alesis-space>
                <div class="項目列表">
                    <div class="項目">
                        <img class="圖示" src="/images/alesis-registered.svg">
                        <div class="標籤">立即申請→立即審核</div>
                    </div>
                    <div class="項目">
                        <img class="圖示" src="/images/alesis-symbol-cash.svg">
                        <div class="標籤">加快投資人媒合速度→立即放款</div>
                    </div>
                    <div class="項目">
                        <img class="圖示" src="/images/alesis-symbol-upcash.svg">
                        <div class="標籤">擴大核准額度至30萬</div>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 快速審核 -->

        <!-- 普匯銀行 -->
        <div class="普匯銀行">
            <alesis-header>
                <div class="標題">上班族 普匯合作專區</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="medium"></alesis-space>
                <div class="包裹容器">
                    <alesis-shanghai></alesis-shanghai>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 普匯銀行 -->

        <!-- 申貸流程 -->
        <div class="申貸流程">
            <alesis-header>
                <div class="標題">申貸流程</div>
            </alesis-header>
            <alesis-section :secondary="true">
                <alesis-space size="small"></alesis-space>
                <div class="swiper-container 幻燈片">
                    <div class="swiper-wrapper">
                        <!-- 步驟一 -->
                        <div class="群組 swiper-slide">
                            <div class="項目">
                                <div class="步驟一">
                                    <div class="左邊">
                                        <a href="https://apps.apple.com/tw/app/%E6%99%AE%E5%8C%AFinflux/id1463581445" target="_balnk"><img src="/images/alesis-app-store.svg" class="圖片"></a>
                                        <a href="https://play.google.com/store/apps/details?id=com.influxfin.borrow&hl=zh_TW&gl=US" target="_blank"><img src="/images/alesis-google-play.svg" class="圖片"></a>
                                    </div>
                                    <div class="中間">
                                        <img src="/images/alesis-workloan-step1.svg" class="圖片">
                                    </div>
                                    <div class="右邊">
                                        <div class="標題">Step 1.：</div>
                                        <div class="內容">
                                            下載「普匯influx」APP<br>
                                            註冊會員<br>
                                            申請「上班族貸」<br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / 步驟一 -->

                        <!-- 步驟二 -->
                        <div class="群組 swiper-slide">
                            <div class="項目">
                                <div class="步驟二">
                                    <div class="左邊">
                                        <div class="手機區塊">
                                            <img src="/images/alesis-workloan-step2-1.svg" class="圖片">
                                            <img src="/images/alesis-workloan-step2-2.svg" class="圖片">
                                        </div>
                                        <div class="內容" v-if="how === 'work'">
                                            <div class="標題">工作證明</div>
                                            <div class="分隔線"></div>
                                            <div class="文章段落">
                                                <div class="標籤">方法 1</div>
                                                <div class="容器">
                                                    <alesis-button size="1.2rem" xPadding="2rem" yPadding=".8rem">自然人憑證</alesis-button>
                                                </div>
                                            </div>
                                            <div class="內容">至各縣市勞動部申請 : 申請後請拍照上傳至APP內</div>
                                            <div class="分隔線"></div>
                                            <div class="文章段落">
                                                <div class="標籤">方法 2</div>
                                                <div class="容器">
                                                    <alesis-button size="1.2rem" xPadding="2rem" yPadding=".8rem">至各縣市勞動部申請</alesis-button>
                                                </div>
                                            </div>
                                            <div class="內容">
                                                自然人憑證 : 使用自然人憑證卡搭配電腦與讀卡機至勞保局網頁 (<a href="#!">https://edesk.bli.gov.tw/aa/</a>) 進行申請
                                            </div>
                                        </div>
                                        <div class="內容" v-if="how === 'prove'">
                                            <div class="標題">聯徵證明</div>
                                            <div class="分隔線"></div>
                                            <div class="文章段落">
                                                <div class="標籤">方法 1</div>
                                                <div class="容器">
                                                    <alesis-button size="1.2rem" xPadding="2rem" yPadding=".8rem">郵局</alesis-button>
                                                </div>
                                            </div>
                                            <div class="內容">郵局：至郵局櫃台申請聯徵,並寄送至普匯金融科技 (台北市松江路111號11樓之2)</div>
                                            <div class="分隔線"></div>
                                            <div class="文章段落">
                                                <div class="標籤">方法 2</div>
                                                <div class="容器">
                                                    <alesis-button size="1.2rem" xPadding="2rem" yPadding=".8rem">自然人憑證</alesis-button>
                                                </div>
                                            </div>
                                            <div class="內容">
                                                自然人憑證 : 使用自然人憑證卡搭配電腦與讀卡機至財團法人金融聯合中心網頁 (<a href="#!">https://apply.jcic.org.tw/CreditQueryInput.do</a>) 進行申請
                                            </div>
                                            <div class="分隔線"></div>
                                            <div class="文章段落">
                                                <div class="標籤">方法 3</div>
                                                <div class="容器">
                                                    <alesis-button size="1.2rem" xPadding="2rem" yPadding=".8rem">投資人行動網</alesis-button>
                                                </div>
                                            </div>
                                            <div class="內容">
                                                投資人行動網 : 下載投資人行動網APP(TWID)並選擇您開立證券戶之券商
                                            </div>
                                        </div>
                                    </div>
                                    <div class="右邊">
                                        <div class="標題">Step 2.：</div>
                                        <div class="內容">
                                            選擇申請金額、期數<br>
                                            完成實名認證<br>
                                            上傳申貸資料<br>
                                        </div>
                                        <br><br>
                                        <div class="標題">需提供資料：</div>
                                        <div class="內容">
                                            近一個月勞保異動明細　<a href="javascript:void(0);" class="help" @click="() => {how = 'work'}">如何申請？</a><br>
                                            近一個月信用報告　<a href="javascript:void(0);" class="help" @click="() => {how = 'prove'}">如何申請？</a><br>
                                            學歷證明（畢業證書）
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / 步驟二 -->

                        <!-- 步驟三 -->
                        <div class="群組 swiper-slide">
                            <div class="項目">
                                <div class="步驟三">
                                    <div class="左邊">
                                        <img src="/images/alesis-workloan-step3-1.svg" class="圖片">
                                        <img src="/images/alesis-workloan-step3-2.svg" class="圖片">
                                    </div>
                                    <div class="右邊">
                                        <div class="標題">Step 3.：</div>
                                        <div class="內容">
                                            審核通過<br>
                                            線上簽約<br>
                                            立即上架媒和<br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / 步驟三 -->

                        <!-- 步驟四 -->
                        <div class="群組 swiper-slide">
                            <div class="項目">
                                <div class="步驟四">
                                    <div class="左邊">
                                        <img src="/images/alesis-donezo.svg" class="圖片">
                                    </div>
                                    <div class="右邊">
                                        <div class="標題">Step 4.：</div>
                                        <div class="內容">
                                            成功媒合投資人<br>
                                            立即放款
                                        </div>
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
        <!-- / 申貸流程 -->

        <!-- 產品特色 -->
        <div class="產品特色">
            <alesis-header>
                <div class="標題">上班族產品特色</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="medium"></alesis-space>
                <div class="包裹容器">
                    <div class="左邊">
                        <div class="區塊">
                            <div class="象徵">
                                <img src="/images/alesis-work-loan-feature1.svg" class="圖片">
                                <img src="/images/alesis-workloan-feature-1-simplified.svg" class="響應式圖片">
                            </div>
                            <div class="內容">
                                <div class="標題">即時審核，快速媒合放款</div>
                                <div class="分隔線"></div>
                                <div class="文字">系統自動化審核加快申貸的速度，平均3分鐘完成申請、10分鐘核准、60分鐘到帳！</div>
                            </div>
                        </div>
                        <div class="區塊">
                            <div class="象徵">
                                <img src="/images/alesis-work-loan-feature2.svg" class="圖片">
                                <img src="/images/alesis-workloan-feature-2-simplified.svg" class="響應式圖片">
                            </div>
                            <div class="內容">
                                <div class="標題">全線無人化</div>
                                <div class="分隔線"></div>
                                <div class="文字">運用AI整合會員資料進行大數據分析，自動線上完成徵信、授信、甚至續約程序，操作方便簡單。</div>
                            </div>
                        </div>
                        <div class="區塊">
                            <div class="象徵">
                                <img src="/images/alesis-work-loan-feature3.svg" class="圖片">
                                <img src="/images/alesis-workloan-feature-3-simplified.svg" class="響應式圖片">
                            </div>
                            <div class="內容">
                                <div class="標題">隱私無照會</div>
                                <div class="分隔線"></div>
                                <div class="文字">申貸全程無人干擾，更不會接到任何業務員的照會電話，也不會主動聯絡借款人及其緊急聯絡人。</div>
                            </div>
                        </div>
                    </div>
                    <div class="中間">
                        <div class="展示區域">
                            <img src="/images/alesis-work-loan-phone.svg" class="圖片">
                        </div>
                        <div class="標語">假日放款全年無休</div>
                        <a href="#!" class="取得">
                            <alesis-button size="1.3rem" xPadding="3rem" yPadding=".8rem">取得資金</alesis-button>
                        </a>
                    </div>
                    <div class="右邊">
                        <div class="區塊">
                            <div class="象徵">
                                <img src="/images/alesis-work-loan-feature4.svg" class="圖片">
                                <img src="/images/alesis-workloan-feature-4-simplified.svg" class="響應式圖片">
                            </div>
                            <div class="內容">
                                <div class="標題">聯徵不留紀錄</div>
                                <div class="分隔線"></div>
                                <div class="文字">不會留下任何貸款相關紀錄，不影響未來信用狀況，也不佔任何銀行額度。</div>
                            </div>
                        </div>
                        <div class="區塊">
                            <div class="象徵">
                                <img src="/images/alesis-work-loan-feature5.svg" class="圖片">
                                <img src="/images/alesis-workloan-feature-5-simplified.svg" class="響應式圖片">
                            </div>
                            <div class="內容">
                                <div class="標題">費率單純透明</div>
                                <div class="分隔線"></div>
                                <div class="文字">只收取一次手續費，且費率以及每月需還本息金額公開透明，不用擔心被收取其他費用。</div>
                            </div>
                        </div>
                        <div class="區塊">
                            <div class="象徵">
                                <img src="/images/alesis-work-loan-feature6.svg" class="圖片">
                                <img src="/images/alesis-workloan-feature-6-simplified.svg" class="響應式圖片">
                            </div>
                            <div class="內容">
                                <div class="標題">金融機構合作</div>
                                <div class="分隔線"></div>
                                <div class="文字">在會員用戶逐漸成長的情況下，普匯專業優質的名聲，吸引許多金融機構相邀合作，包含「凱基銀行」、「上海商銀」等，共同為台灣金融科技發展貢獻一份力。</div>
                            </div>
                        </div>
                    </div>
                    <div class="底部">
                        <div class="標語">假日放款全年無休</div>
                        <a href="#!" class="取得">
                            <alesis-button size="1.3rem" xPadding="3rem" yPadding=".8rem">取得資金</alesis-button>
                        </a>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 產品特色 -->

        <!-- 額度利率評估問卷 -->
        <div class="評估問卷">
            <alesis-header>
                <div class="標題">額度利率評估問卷</div>
                <div class="標題 標題_外框線的">請您依現在的真實狀況填寫以下題目</div>
            </alesis-header>
            <alesis-section :secondary="true">
                <alesis-space size="medium"></alesis-space>
                <div class="表單">
                    <div class="列">
                        <div class="標籤">1.我的教育程度：</div>
                        <div class="輸入欄位">
                            <select v-model="formGraduate">
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
                            <select>
                                <option selected disabled>-請選擇-</option>
                                <option :disabled="item.disabled" v-for="item, index in flattenWorkCategories" :key="index" :value="item.value">{{ item.title }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">3.任職公司是否屬於上市櫃、金融機構或公家機關：</div>
                        <div class="輸入欄位">
                            <select v-model="formCompany">
                                <option selected disabled value="">-請選擇-</option>
                                <option value="true">是</option>
                                <option value="false">否</option>
                            </select>
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">4.我的投保月薪約為：</div>
                        <div class="輸入欄位">
                            <input type="text" v-model="formSalary">
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">5.我在銀行的貸款餘額約為：</div>
                        <div class="輸入欄位">
                            <input type="text" v-model="formLoan">
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">6.每月需攤還多少金額：</div>
                        <div class="輸入欄位">
                            <input type="text" v-model="formReturn">
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">7.信用卡總額度約為：</div>
                        <div class="輸入欄位">
                            <input type="text" v-model="formCredit">
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">8.近一個月信用卡帳單總金額約為：</div>
                        <div class="輸入欄位">
                            <input type="text" v-model="formTotal">
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">暱稱：</div>
                        <div class="輸入欄位">
                            <input type="text">
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤">E-mail：</div>
                        <div class="輸入欄位">
                            <input type="text">
                        </div>
                    </div>
                    <div class="列">
                        <div class="標籤"></div>
                        <div class="輸入欄位">
                            <button @click="calculateForm">取得報告</button>
                        </div>
                    </div>
                </div>
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
                                <div class="值">{{ format(formAnswerTotal) }}</div>
                            </div>
                            <div class="列">
                                <div class="標籤">2. 借款利率區間：</div>
                                <div class="值">{{ formAnswerSpan }}</div>
                            </div>
                            <div class="列">
                                <div class="標籤">3. 手續費金額：</div>
                                <div class="值">{{ formAnswerFee }}</div>
                            </div>
                            <div class="列">
                                <div class="標籤">4. 每期攤還金額約：</div>
                                <div class="值">{{ formAnswerPer }}</div>
                            </div>
                        </div>
                        <div class="說明">►僅為初步評估，實際貸款條件依照您真實提供的資料而定。</div>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 評估問卷 -->

        <!-- 案例分享 -->
        <div class="案例分享">
            <alesis-header class="區塊標題_拖尾的">
                <div class="標題 標題_外框線的">案例分享 / 用戶分享</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="medium"></alesis-space>
                <alesis-suggestion-reviews type="work"></alesis-suggestion-reviews>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 案例分享 -->

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
import WorkCategories from "../data/work_categories"
// 幻燈片
import 'swiper/swiper.scss';
import "swiper/components/navigation/navigation.min.css"
import SwiperCore, {
  Navigation
} from 'swiper/core';

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
    data: () => ({
        how                  : "",
        formGraduate         : "",
        formCompany          : "",
        formSalary           : "",
        formLoan             : "",
        formReturn           : "",
        formCredit           : "",
        formTotal            : "",
        formCalculated       : false,
        formAnswerTotal      : 0,
        formAnswerSpan       : 0,
        formAnswerFee        : 0,
        formAnswerPer        : 0,
        flattenWorkCategories: [],
        workCategories       : WorkCategories
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
        })

        SwiperCore.use([Navigation]);
         const swiperA = new Swiper('.swiper-container.flow-container', {
            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
            },

            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
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

<style lang="scss" scoped>
@import "../component/alesis/alesis";

/**
 * 快速審核
 */

.快速審核 {
    position: relative;
    margin-top: 8rem;
}

.快速審核 .項目列表 {
    display              : grid;
    max-width            : 1280px;
    margin               : 0 auto 1rem;
    grid-template-columns: repeat(3, 1fr);
    justify-content      : center;
    gap                  : 2rem;
}

.快速審核 .項目列表 .項目 {
    text-align: center;
}

.快速審核 .項目列表 .項目 .圖示 {
    @include rwd {
        width: 80px;
    }
}

.快速審核 .項目列表 .項目 .標籤 {
    margin-top : 1rem;
    font-weight: bold;
    color      : #112E53;
    font-size  : 1.3rem;

    @include rwd {
        font-size: .8rem;
    }
}

/**
 * 普匯銀行
 */

.普匯銀行 {
    position: relative;
}

.普匯銀行 .包裹容器 {
    display        : flex;
    align-items    : center;
    justify-content: center;
}

/**
 * 評估問卷
 */

.評估問卷 {
    position: relative;
}

.評估問卷 .表單 {
    max-width: 1280px;
    margin   : 0 auto;
}

.評估問卷 .表單 .列 {
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

.評估問卷 .表單 .列 .標籤 {
    text-align: right;
    font-size : 1.4rem;
    color     : #5862a3;

    @include rwd {
        text-align: left;
        font-size : 1rem;
    }
}

.評估問卷 .表單 .列 .輸入欄位 select {
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
        text-align  : left;
    }
}

.評估問卷 .表單 .列 .輸入欄位 input {
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

.評估問卷 .表單 .列 .輸入欄位 button {
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

.評估問卷 .結果 {
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

.評估問卷 .結果 .展示區塊 {
    flex      : 1;
    text-align: right;
}

.評估問卷 .結果 .展示區塊 .圖片 {
    height: 350px;

    @include rwd {
        width        : 70vw;
        height       : auto;
        margin-bottom: 2rem;
    }
}

.評估問卷 .結果 .內容 {
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

.評估問卷 .結果 .內容 .標題 {
    margin-bottom: 1.5rem;
    font-size    : 1.8rem;

    @include rwd {
        font-size: 1.2rem;
    }
}

.評估問卷 .結果 .內容 .段落 {
    font-size    : 1.3rem;
    line-height  : 1.7;
    margin-bottom: 1.5rem;

    @include rwd {
        font-size: 1rem;
    }
}

.評估問卷 .結果 .內容 .數值 {
    font-size    : 1.2rem;
    line-height  : 1.8;
    margin-bottom: 2rem;

    @include rwd {
        font-size: 1rem;
    }
}

.評估問卷 .結果 .內容 .數值 .列 {
    margin : 0;
    display: flex;
}

.評估問卷 .結果 .內容 .數值 .列 .標籤 {
    color: #036EB7;
}

.評估問卷 .結果 .內容 .數值 .列 .值 {
    color      : #5d5555;
    font-weight: normal;
}

.評估問卷 .結果 .內容 .說明 {
    color      : #f29600;
    font-size  : .9rem;
    font-weight: normal;
}

/**
 * 申貸流程
 */

.申貸流程 {
    position: relative;
}

.申貸流程 .幻燈片 {
    margin        : 0 auto;
    max-width     : 1170px;
}

.申貸流程 .幻燈片 .swiper-button-next,
.申貸流程 .幻燈片 .swiper-button-prev {
    transform: scale(0.6);
}

.申貸流程 .幻燈片 .swiper-button-disabled {
    opacity: 0;
}

.申貸流程 .群組 {
    display              : grid;
    grid-template-columns: repeat(2, 1fr);
    gap                  : 3rem;
    margin               : 0 auto;
}

.申貸流程 .群組 .項目 {
    width          : 1170px;
    min-height     : 430px;
    display        : flex;
    align-items    : center;
    justify-content: initial;
    margin         : 0 auto;
}

.申貸流程 .步驟一,
.申貸流程 .步驟二,
.申貸流程 .步驟三,
.申貸流程 .步驟四 {
    min-width: 800px;
}

.申貸流程 .步驟一 {
    display              : inline-grid;
    grid-template-columns: repeat(3, 1fr);
    margin               : 0 auto;
    gap                  : 6rem;
}

.申貸流程 .步驟一 .左邊 {
    display        : flex;
    align-items    : flex-end;
    justify-content: center;
    flex-direction : column;
    gap            : 1.5rem;
}

.申貸流程 .步驟一 .中間 .圖片 {
    width: 210px;
}

.申貸流程 .步驟一 .右邊 {
    display        : flex;
    justify-content: center;
    flex-direction : column;
}

.申貸流程 .步驟一 .右邊 .標題 {
    color    : #112E53;
    font-size: 1.3rem;
}

.申貸流程 .步驟一 .右邊 .內容 {
    color    : #5D5555;
    font-size: 1.1rem;
}

.申貸流程 .步驟二 {
    display              : grid;
    grid-template-columns: 470px 1fr;
    gap                  : 3rem;
    margin               : 0 auto;
    max-width            : 800px;
    margin-left          : 9.1rem;
}

.申貸流程 .步驟二 .左邊 {
    display       : flex;
    flex-direction: column;
    align-items   : center;
}

.申貸流程 .步驟二 .左邊 .手機區塊 {
    display: flex;
    gap    : 3rem;
}

.申貸流程 .步驟二 .左邊 .手機區塊 .圖片 {
    width: 210px;
}

.申貸流程 .步驟二 .左邊 > .內容 {
    border       : 1px solid #2664A5;
    border-radius: .8rem;
    padding      : 1.5rem;
    margin-top   : 3rem;
}

.申貸流程 .步驟二 .左邊 .內容 .標題 {
    text-align: center;
    font-size : 1.5rem;
    color     : #036EB7;
}

.申貸流程 .步驟二 .左邊 .內容 .分隔線 {
    height    : 1px;
    background: #2664A5;
    margin    : 1rem 0;
}

.申貸流程 .步驟二 .左邊 .內容 .文章段落 {
    display              : grid;
    grid-template-columns: 1fr 4fr;
    align-items          : center;
}

.申貸流程 .步驟二 .左邊 .內容 .文章段落 .標籤 {
    font-size: 1.2rem;
    color    : #036EB7;
}

.申貸流程 .步驟二 .左邊 .內容 .文章段落 .容器.容器_框線的 {
    position       : relative;
    padding        : 1rem 1.5rem;
    display        : inline-flex;
    align-items    : center;
    justify-content: center;
    line-height    : 1;
    font-size      : 1.1rem;
    font-weight    : bold;
    color          : #036eb7;

    background-image   : url(/images/alesis-button-lines.svg);
    background-size    : contain;
    background-repeat  : no-repeat;
    background-position: center;
}

.申貸流程 .步驟二 .左邊 .內容 .內容 {
    line-height: 1.8;
    margin-top : .8rem;
    color      : #5D5555;
}

.申貸流程 .步驟二 .左邊 .內容 .內容 a {
    text-decoration: underline;
}

.申貸流程 .步驟二 .右邊 {
    padding-top: 6rem;
}

.申貸流程 .步驟二 .右邊 .標題 {
    color    : #112E53;
    font-size: 1.3rem;
}

.申貸流程 .步驟二 .右邊 .內容 {
    color    : #5D5555;
    font-size: 1.1rem;
}

.申貸流程 .步驟二 .右邊 .說明 {
    font-size      : .9rem;
    text-decoration: underline;
    color          : #112E53;
}

.申貸流程 .步驟三 {
    display              : grid;
    grid-template-columns: 470px 1fr;
    gap                  : 3rem;
    margin               : 0 auto;
    max-width            : 800px;
    margin-left          : 9.1rem;
}

.申貸流程 .步驟三 .左邊 {
    display        : flex;
    align-items    : flex-end;
    justify-content: center;
    flex-direction : row;
    gap            : 3rem;
}

.申貸流程 .步驟三 .左邊 .圖片 {
    width: 210px;
}

.申貸流程 .步驟三 .右邊 {
    display        : flex;
    justify-content: center;
    flex-direction : column;
}

.申貸流程 .步驟三 .右邊 .標題 {
    color    : #112E53;
    font-size: 1.3rem;
}

.申貸流程 .步驟三 .右邊 .內容 {
    color    : #5D5555;
    font-size: 1.1rem;
}

.申貸流程 .步驟四 {
    display              : grid;
    grid-template-columns: 470px 1fr;
    gap                  : 3rem;
    margin               : 0 auto;
    max-width            : 800px;
    margin-left          : 9.1rem;
}

.申貸流程 .步驟四 .左邊 {
    text-align: center;
}

.申貸流程 .步驟四 .左邊 .圖片 {
    width: 260px;
}

.申貸流程 .步驟四 .右邊 {
    display        : flex;
    justify-content: center;
    flex-direction : column;
}

.申貸流程 .步驟四 .右邊 .標題 {
    color    : #112E53;
    font-size: 1.3rem;
}

.申貸流程 .步驟四 .右邊 .內容 {
    color    : #5D5555;
    font-size: 1.1rem;
}

/**
 * 產品特色
 */

.產品特色 {
    position: relative;
}

.產品特色 .包裹容器 {
    display  : flex;
    max-width: 1280px;
    margin   : 0 auto;

    @include rwd {
        flex-direction: column;
        width         : min-content;
    }
}

.產品特色 .包裹容器 .左邊 {
    flex          : 1;
    display       : flex;
    flex-direction: column;
    align-items   : flex-start;
    margin-right  : -5rem;
    margin-left   : 5rem;

    @include rwd {
        margin: 0;
    }
}

.產品特色 .包裹容器 .左邊 .區塊 {
    flex-direction: row-reverse;

    @include rwd {
        flex-direction: row;
    }
}

.產品特色 .包裹容器 .左邊 .區塊 .象徵 {
    margin-left : 1.5rem;
    margin-right: 0;

    @include rwd {
        margin-left: 0;
    }
}

.產品特色 .包裹容器 .中間 {
    text-align: center;
    position  : relative;
    top       : initial;
    left      : initial;
    transform : none;

    @include rwd {
        display: none;
    }
}

.產品特色 .包裹容器 .中間 .展示區域 {
    margin-top: 4rem;
}

.產品特色 .包裹容器 .中間 .展示區域 .圖片 {
    height: 34rem;
}

.產品特色 .包裹容器 .中間 .標語 {
    text-align : center;
    color      : #f29600;
    font-size  : 2rem;
    font-weight: bolder;
    margin-top : 7rem;
}

.產品特色 .包裹容器 .中間 .取得 {
    display   : inline-block;
    margin-top: 1rem;
}

.產品特色 .包裹容器 .右邊 {
    flex: 1;
}

.產品特色 .包裹容器 .右邊 .區塊:nth-child(2) {
    margin-left: -2rem;

    @include rwd {
        margin-left: 0;
    }
}

.產品特色 .包裹容器 .右邊 .區塊:nth-child(3) {
    margin-left: -3rem;

    @include rwd {
        margin-left: 0;
    }
}

.產品特色 .包裹容器 .底部 {
    display   : none;
    text-align: center;

    @include rwd {
        display: block;
        margin-left: -20%;
    }
}

.產品特色 .包裹容器 .底部 .標語 {
    text-align : center;
    color      : #f29600;
    font-size  : 1.4rem;
    font-weight: bolder;
    margin-top : 2rem;
}

.產品特色 .包裹容器 .底部 .取得 {
    display   : inline-block;
    margin-top: 1rem;
}

.產品特色 .包裹容器 .區塊 {
    display      : flex;
    margin-bottom: 5rem;

    @include rwd {
        margin-bottom: 0;
    }
}

.產品特色 .包裹容器 .區塊 .象徵 {
    margin-right: 1.5rem;

    @include rwd {
        margin-right: 0;
    }
}

.產品特色 .包裹容器 .區塊 .圖片 {
    @include rwd {
        display: none;
    }
}

.產品特色 .包裹容器 .區塊 .響應式圖片 {
    display: none;

    @include rwd {
        display     : initial;
        width       : 70px;
        margin-right: 1rem;
    }
}

.產品特色 .包裹容器 .區塊 .內容 {
    margin-top : 2.7rem;
    width      : 290px;
    line-height: 1.6;

    @include rwd {
        margin-top: 1.3rem;
    }
}

.產品特色 .包裹容器 .區塊 .內容 .標題 {
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

.產品特色 .包裹容器 .區塊 .內容 .分隔線 {
     width     : 160px;
    height    : 1px;
    background: #2f95cb;
    margin    : .5rem 0 1rem;
}

.產品特色 .包裹容器 .區塊 .內容 .文字 {
    color     : #5d5555;
    min-height: 5rem;
    text-align: justify;

    @include rwd {
        text-align: left;
        width     : 63vw;
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
