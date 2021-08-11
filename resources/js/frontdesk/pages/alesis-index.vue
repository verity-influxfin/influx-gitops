<template>
    <div class="index-wrapper">
        <div class="swiper-container 標頭幻燈片">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="banner">
                        <div class="puhey-banner">
                            <img src="/images/首頁BANNER.png" class="hidden-desktop img-fluid" />
                            <img src="/images/index-banner-m.png" class="hidden-phone img-fluid" />
                            <img src="/images/diagram-d.svg" class="diagram hidden-desktop" />
                            <img src="/images/diagram-m.svg" class="diagram hidden-phone" />
                            <div class="content">
                                <p>最貼近年輕人的金融科技平台</p>
                                <span>普匯．你的手機ATM</span>
                                <div class="box">
                                    <a class="loan" href="/investLink">
                                        <img src="../asset/images/light-b.svg" class="img-fluid" />
                                        <div class="text">我要投資</div>
                                    </a>
                                    <a class="borrow" href="/borrowLink">
                                        <img src="../asset/images/light-y.svg" class="img-fluid" />
                                        <div class="text">我要借款</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="/images/skBankIndex.jpg" class="旗幟圖片">
                    <img src="/images/skBankIndexM.jpg" class="旗幟圖片 旗幟圖片_手機的">
                    <div class="新光銀行功能">
                        <a href="https://www.influxfin.com/skbank" class="連結">
                            <img src="/images/skbankbuttom1.svg">
                        </a>
                        <a href="javascript:;" class="連結" data-toggle="modal" data-target="#event-modal" @click="bank_event='skbank'">
                            <img src="/images/skbankbuttom2.svg">
                        </a>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="/images/banner.jpg" class="旗幟圖片">
                    <img src="/images/banner--.jpg" class="旗幟圖片 旗幟圖片_手機的">

                    <div class="上海商銀功能">
                        <a href="/scsbank?move=page" class="連結">
                            <img src="/images/skbankbuttom1.svg">
                        </a>
                        <a href="javascript:;" class="連結" data-toggle="modal" data-target="#event-modal" @click="bank_event='scsbank'">
                            <img src="/images/skbankbuttom2.svg">
                        </a>
                    </div>
                </div>
                <div class="swiper-slide">
                    <a href="https://www.influxfin.com/borrowLink">
                        <img src="/images/210129首頁-全台首創banner.jpg" class="旗幟圖片">
                    </a>
                    <a href="https://www.influxfin.com/borrowLink">
                        <img src="/images/210129首頁手機-全台首創banner (1).jpg" class="旗幟圖片 旗幟圖片_手機的">
                    </a>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="event-modal" tabindex="-1" role="dialog">
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

                                    <!-- 新光銀行 -->
                                    <a class="btn btn-primary" href="https://servicedesk.skbank.com.tw/CloudDesk/AuthOTP/SMSOTPForm3/36?CMPN_ID=20201214100035&CMPN_REF=inFlux_apply&CMPN_SRC=zOTHER" target="_target" v-if="bank_event=='skbank'">
                                        前往銀行申請頁面
                                    </a>

                                    <!-- 上海商銀 -->
                                    <button class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#scsbank-event-modal" v-if="bank_event=='scsbank'">
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

        <!-- 計數器 -->
        <div class="計數器">
            <alesis-space size="small"></alesis-space>
            <div class="包裹容器">
                <alesis-counter image="/images/alesis-registered.svg" header="累積註冊用戶" number="60000" unit="人"></alesis-counter>
                <alesis-counter image="/images/alesis-totalmoney.svg" header="累積放款金額" number="15857" unit="萬"></alesis-counter>
                <alesis-counter image="/images/alesis-totalapproved.svg" header="累積成交筆數" number="37390" unit="筆"></alesis-counter>
            </div>
            <alesis-space size="medium"></alesis-space>
        </div>
        <!-- / 計數器 -->

        <!-- 服務區塊 -->
        <div class="服務區塊">
            <alesis-header>
                <div class="標題">我們的服務</div>
            </alesis-header>
            <alesis-section :secondary="true">
                <alesis-space size="medium"></alesis-space>
                <img class="圖片" src="/images/p2p.svg" />
                <div class="動作區塊">
                    <div class="左側">
                        <a href="/investLink" class="動作">立即投資</a>
                        <a href="/invest" class="動作">了解更多</a>
                    </div>
                    <div class="右側">
                        <a href="/borrowLink" class="動作">立即借款</a>
                        <a href="/borrow" class="動作">了解更多</a>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 服務區塊 -->

        <!-- 公司簡介 -->
        <div class="公司簡介">
            <alesis-header>
                <div class="標題">公司簡介</div>
            </alesis-header>
            <div class="h-c">
              <div class="arrows al">
                <div @click="pre()">
                  <img src="../asset/images/left-arrow.svg" class="img-fluid" />
                </div>
              </div>
              <routeMap v-if="isDesktop" :routeData="routeData" />
              <routeMapM v-else :routeData="routeData" />
              <div class="arrows ar">
                <div @click="next()">
                  <img src="../asset/images/right-arrow.svg" class="img-fluid" />
                </div>
              </div>
            </div>
        </div>
        <!-- / 公司簡介 -->

        <!-- 產品特色 -->
        <div class="產品特色">
            <alesis-header>
                <div class="標題">產品特色</div>
            </alesis-header>
            <alesis-section :secondary="true">
                <alesis-space size="medium"></alesis-space>
                <div class="包裹容器">
                    <div class="展示區塊">
                        <img class="圖片" src="/images/步驟1-01.png">
                    </div>
                    <div class="內容區塊">
                        <alesis-bullet class="bullet" image="/images/alesis-stonk.svg" header="“以「普惠金融」為志業”" description="透過P2P、區塊鏈技術，進行大量、小額、分散借貸投資及債權轉讓，深受學生、社會新鮮人、中小企業及眾多投資人喜愛。"></alesis-bullet>
                        <div class="半月列表">
                            <div class="項目">
                                <alesis-moon class="圖示" header="簡單" :level=1></alesis-moon>
                                <div class="內容">
                                    AWS安全系統為架構，<br>
                                    輔以簡潔的操作界面。
                                </div>
                            </div>
                            <div class="項目">
                                <alesis-moon class="圖示" header="快速" :level=2></alesis-moon>
                                <div class="內容">
                                    10分鐘快速填寫申請資料，<br>
                                    全程線上操作，無人干擾。
                                </div>
                            </div>
                            <div class="項目">
                                <alesis-moon class="圖示" header="安全" :level=3></alesis-moon>
                                <div class="內容 security">
                                    資訊傳輸由HTTPS方式利用SSL/TLS加密，儲存於亞馬遜AWS雲端伺服器。
                                </div>
                            </div>
                            <div class="項目">
                                <alesis-moon class="圖示" header="隱私" :level=4></alesis-moon>
                                <div class="內容">
                                    全台唯一無人化借貸平台，<br>
                                    讓手機成為您專屬的ATM。
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="標語">假日放款全年無休</div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 產品特色 -->

        <!-- 快速變現 -->
        <div class="快速變現">
            <alesis-header>
                <div class="標題">普匯債權快速變現</div>
                <div class="標題">全台唯一債權轉讓功能</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="medium"></alesis-space>
                <div class="包裹容器">
                    <div class="圖表">
                        <img class="圖片" src="/images/alesis-transfer-graph.svg">
                    </div>
                    <div class="半月列表">
                        <alesis-moon class="項目" header="債權轉讓" multiline="快速變現" :level=3></alesis-moon>
                        <alesis-moon class="項目" header="小額資金" multiline="分散風險" :level=2></alesis-moon>
                        <alesis-moon class="項目" header="智能投資" multiline="自動下標" :level=1></alesis-moon>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 快速變現 -->

        <!-- 產品方案 -->
        <div class="產品方案">
            <alesis-header>
                <div class="標題">產品方案</div>
            </alesis-header>
            <alesis-section :secondary="true">
                <alesis-space size="medium"></alesis-space>
                <div class="包裹容器">
                    <div class="箭頭" @click="prevSolution">
                        <img class="圖示" src="/images/alesis-styled-arrow-left.svg">
                    </div>
                    <alesis-plan class="方案表" :header="item.header" :targets="item.targets" :image="item.image" :action="item.action" :features="item.features" :unready="item.unready" v-for="(item, index) in plans" :link="item.link" :key="index" :class="{'方案表_已啟用': index == currentPlan}"></alesis-plan>
                    <div class="箭頭" @click="nextSolution">
                        <img class="圖示" src="/images/alesis-styled-arrow-right.svg">
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 產品方案 -->

        <!-- 普匯推薦 -->
        <div class="普匯推薦">
            <alesis-header>
                <div class="標題">普匯推薦 銀行速貸好條件</div>
                <div class="標題" style="margin-top: .8rem; line-height: 1.4;">透過普匯推薦合作銀行，可享最高額度300萬，超低利率1.88%起！</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="tiny"></alesis-space>
                <alesis-space size="medium"></alesis-space>
                <div class="包裹容器">
                    <alesis-shanghai></alesis-shanghai>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 普匯推薦 -->

        <!-- 分期計算機 -->
        <div class="分期計算機">
            <alesis-header>
                <div class="標題">分期還款沒壓力</div>
            </alesis-header>
            <alesis-section :secondary="true">
                <alesis-space size="medium"></alesis-space>
                <div class="包裹容器">
                    <div class="計算機">
                        <creditBoard amount="300000" minRate="5" maxRate="16" v-on:update-calculator="updateCalculator"/>
                    </div>
                    <div class="輸入群組">
                        <div class="列">
                            <div class="文字">申請</div>
                            <input type="text" class="輸入欄位" v-model="amountCount">
                            <div class="文字">元</div>
                        </div>
                        <div class="列">
                            <div class="文字">分期償還</div>
                            <input type="text" class="輸入欄位" v-model="period">
                            <div class="文字">期</div>
                        </div>
                        <div class="列">
                            <div class="文字">每月僅需約</div>
                            <input type="text" class="輸入欄位" v-model="pmt">
                            <div class="文字">元</div>
                        </div>
                    </div>
                </div>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 分期計算機 -->

        <!-- 人物推薦 -->
        <div class="人物推薦">
            <alesis-header>
                <div class="標題">看看他們怎麼說</div>
            </alesis-header>
            <alesis-section>
                <alesis-space size="small"></alesis-space>
                <alesis-suggestion-reviews type="index" category="loan"></alesis-suggestion-reviews>
                <alesis-space size="medium"></alesis-space>
            </alesis-section>
        </div>
        <!-- / 人物推薦 -->

        <!-- 媒體報導 -->
        <div class="媒體報導">
            <alesis-header class="區塊標題_拖尾的">
                <div class="標題 標題_外框線的">媒體報導支持</div>
            </alesis-header>
            <alesis-space size="medium"></alesis-space>
            <div class="包裹容器">
                <div class="商標區塊">
                    <a class="項目" href="https://tw.money.yahoo.com/%E9%87%91%E8%9E%8D%E7%A7%91%E6%8A%80%E5%89%B5%E6%96%B0%E5%9C%92%E5%8D%809%E5%AE%B6%E5%BB%A0%E5%95%86%E5%8A%A0%E5%85%A5-%E4%BC%81%E6%A5%AD%E5%AF%A6%E9%A9%97%E5%AE%A4%E9%A6%96%E6%B3%A2%E5%85%AD%E5%A4%A7%E4%B8%BB%E9%A1%8C%E5%85%AC%E5%B8%83-083826449.html" target="_blank"><img class="圖片" src="/images/a645f762048f5f425d0f0a8486f34612.png"></a>
                    <a class="項目" href="http://n.yam.com/Article/20180803346949" target="_blank"><img class="圖片" src="/images/1JHY1QOLYPF0.png"></a>
                    <a class="項目" href="https://www.nownews.com/news/20180807/2798010/" target="_blank"><img class="圖片" src="/images/Now_News_logo.png"></a>
                    <a class="項目" href="https://act.chinatimes.com/market/content.aspx?AdID=6585&chdtv" target="_blank"><img class="圖片" src="/images/logo-chinatimes2019-1200x635.png"></a>
                </div>
                <div class="商標區塊 商標區塊_三欄">
                    <a class="項目" href="https://news.cnyes.com/news/id/4267004" target="_blank"><img class="圖片" src="/images/logo-cn-yes-1.png"></a>
                    <a class="項目" href="https://www.wealth.com.tw/home/articles/20567" target="_blank"><img class="圖片" src="/images/logo-red.png"></a>
                    <a class="項目" href="https://m.ctee.com.tw/livenews/aj/a95645002019042615564198" target="_blank"><img class="圖片" src="/images/logo300_90.png"></a>
                </div>
            </div>
        </div>
        <!-- / 媒體報導 -->

        <float />
    </div>
</template>

<script>
import { Splide, SplideSlide } from "@splidejs/vue-splide";
//
import creditBoard from "../component/svg/creditBoardComponent";
import float       from "../component/floatComponent";
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
import AlesisSpace             from "../component/alesis/AlesisSpace";
import AlesisVerticalRoadmap   from "../component/alesis/AlesisVerticalRoadmap";
import histroyDot from "../component/svg/histroyDotComponent";
// 遠端資料
import PlanData from "../data/index_plans"
import WorkCategories from "../data/work_categories"
import routeMap from "../component/svg/routeMapComponent";
import routeMapM from "../component/svg/routeMapMComponent.vue";

import 'swiper/swiper.scss';
import "swiper/components/navigation/navigation.min.css"
import SwiperCore, {
  Navigation
} from 'swiper/core';

export default {
    components: {
        float,
        creditBoard,
        Splide,
        SplideSlide,
        //
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
        AlesisSpace,
        AlesisVerticalRoadmap,
        routeMap,
        routeMapM,
    },
    data: () => ({
        isDesktop: window.innerWidth > 767 ? true : false,
        load2: false,
        load3: false,
        routeIndex: {
          start: 0,
          end: 0,
        },
        bank_event            : 'skbank',
        amountCount           : 5000,
        rateCount             : 5,
        pmt                   : 0,
        tweenedPmt            : 0,
        currentPlan           : 0,
        plans                 : PlanData,
        isForm                : false,
        period                : 3,
        flattenWorkCategories : [],
        formGraduate          : "",
        formCompany           : "",
        formSalary            : "",
        formLoan              : "",
        formReturn            : "",
        formCredit            : "",
        formTotal             : "",
        formCalculated        : false,
        formAnswerTotal       : 0,
        formAnswerSpan        : 0,
        formAnswerFee         : 0,
        formAnswerPer         : 0,
        isFormValid           : true,
        borrowReportResult    : {},
        routeData: [],
        workCategories        : WorkCategories
    }),
    created() {
    this.getMilestoneData();
        $("title").text(`首頁 - inFlux普匯金融科技`);
    },
    mounted() {
       // document.querySelector(".alesis-company-introduction .animate__animated").classList.add("animate__fadeInUp")

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

        SwiperCore.use([Navigation]);
        new Swiper('.swiper-container.標頭幻燈片', {
            autoplay: {
                delay: 3000,
            },
            loop: true,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    },
    watch : {
        "routeIndex.start"() {
          this.routeData = [];
          this.milestone.forEach((item, index) => {
            if (this.routeIndex.start <= index && this.routeIndex.end >= index) {
              this.routeData.push(item);
            }
          });
        },
    },
    methods: {
        async getMilestoneData() {
          let res = await axios.post(`${location.origin}/getMilestoneData`);

          this.milestone = res.data.reverse();

          this.routeIndex.end = this.milestone.length - 1;
          this.routeIndex.start =
            window.innerWidth > 767 ? this.milestone.length - 6 : this.milestone.length - 4;

          this.milestone.forEach((item, index) => {
            if (this.routeIndex.start <= index && this.routeIndex.end >= index) {
              this.routeData.push(item);
            }
          });
        },
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
        nextSolution() {
            if (this.currentPlan + 1 > this.plans.length - 1) {
                this.currentPlan = 0
            } else {
                this.currentPlan++
            }
        },
        prevSolution() {
            if (this.currentPlan - 1 < 0) {
                this.currentPlan = this.plans.length - 1
            } else {
                this.currentPlan--
            }
        },
        updateCalculator(e) {
            this.pmt = e.pmt
            switch (e.key) {
                case 0:
                    this.period = 3
                    break
                case 1:
                    this.period = 6
                    break
                case 2:
                    this.period = 12
                    break
                case 3:
                    this.period = 18
                    break
                case 4:
                    this.period = 24
                    break
            }
            this.rateCount   = e.rateCount
            this.amountCount = e.amountCount
        },
        format(data) {
            data = parseInt(data);
            if (!isNaN(data)) {
                let l10nEN = new Intl.NumberFormat("en-US");
                return l10nEN.format(data.toFixed(0));
            }
            return 0;
        },
        pre() {
          if (this.routeIndex.start > 0) {
            this.routeIndex.start--;
            this.routeIndex.end--;
          }
        },
        next() {
          if (this.routeIndex.end < this.milestone.length - 1) {
            this.routeIndex.start++;
            this.routeIndex.end++;
          }
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../component/alesis/alesis";

/**
 * 標頭幻燈片
 */

.標頭幻燈片 {

}

.標頭幻燈片 .旗幟圖片 {
    width: 100vw;

    @include rwd {
        display: none;
    }
}

.標頭幻燈片 .旗幟圖片.旗幟圖片_手機的 {
    display: none;

    @include rwd {
        display: block;
    }
}

.標頭幻燈片 {
    .新光銀行功能 {
        position       : absolute;
        z-index        : 100;
        gap            : 3rem;
        top            : initial;
        bottom         : 1rem;
        left           : 50%;
        right          : initial;
        width          : 32vw;
        transform      : translateX(-113%);
        align-items    : flex-end;
        justify-content: center;
        display        : flex;

        @include rwd {
            top         : -6%;
            bottom      : 0;
            left        : 0;
            right       : 0;
            height      : 100%;
            width       : initial;
            transform   : translateX(0);
            align-items : center;
        }
        .連結 {
            display: block;
            width  : 28vw;
        }
    }
    .上海商銀功能 {
        position       : absolute;
        z-index        : 100;
        gap            : 5rem;
        top            : initial;
        bottom         : 3rem;
        left           : 50%;
        right          : initial;
        width          : 34vw;
        transform      : translateX(-113%);
        align-items    : flex-end;
        justify-content: center;
        display        : flex;

        @include rwd {
            top: 37%;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2rem;
            width: 18rem;
            transform: translateX(0);
            align-items: center;
            gap: 1rem;
        }
        .連結 {
            display: block;
            width  : 28vw;
        }
    }
}


/**
 * 計數器
 */

.計數器 {
    margin   : 0 auto;
    max-width: 1280px;
}

.計數器 .包裹容器 {
    display        : flex;
    justify-content: center;
}

/**
 * 服務區塊
 */

.服務區塊 {
    position: relative;
}

.服務區塊 .圖片 {
    width    : 100%;
    max-width: 920px;
    margin   : 0 auto 3rem;
    display  : block;
    z-index  : 0;
    position : relative;
}

.服務區塊 .動作區塊 {
    position  : absolute;
    display   : flex;
    left      : 50%;
    transform : translateX(-50%);
    width     : 100%;
    max-width : 1200px;
    margin-top: -4rem;
    z-index   : 1;
}

.服務區塊 .動作區塊 .左側,
.服務區塊 .動作區塊 .右側 {
    text-align: center;
    flex      : 1;

    @include rwd {
        display       : flex;
        flex-direction: column;
        gap           : 1rem;
        align-items   : center;
    }
}

.服務區塊 .動作區塊 .左側 {
    margin-right: 4.5rem;
}

.服務區塊 .動作區塊 .右側 {
    margin-left: 4.5rem;
}

.服務區塊 .動作區塊 .動作 {
    border-radius: 1rem;
    padding      : 0.3rem 1.5rem;
    font-size    : 1.2rem;
    font-weight  : bolder;
    color        : #112e53;
    border       : 1px solid #1a5fa2;

    @include rwd {
        font-size: .8rem;
        max-width: 7rem;
        padding  : .2rem 1rem;
    }

    &:not(:first-child) {
        margin-left: 1rem;

        @include rwd {
            margin-left: 0;
        }
    }
}

/**
 * 公司簡介
 */

.公司簡介 {
    position: relative;

    @include rwd {
        padding: 0 1rem;
    }
}

.公司簡介 .包裹容器 {
    margin     : 0 auto 3rem;
    text-align : center;

    @include rwd {
        display: none;
    }
}

.公司簡介 .包裹容器 svg {
    width    : 90vw;
    max-width: 1170px;
    height   : 550px;

    @include rwd {
        height : initial;
    }
}

.公司簡介 .手機容器 {
    display   : none;
    text-align: center;

    @include rwd {
        display: block;
    }
}

.公司簡介 .手機容器 svg {
    width        : 70vw;
    margin-bottom: 4rem;
}

.公司簡介 .介紹區域 {
    display  : flex;
    max-width: 1280px;
    margin   : 0 auto;

    @include rwd {
        flex-direction: column;
    }
}

.公司簡介 .介紹區域 .展示區塊 {
    flex      : 1;
    max-width : min(590px, 45vw);
    text-align: center;

    @include rwd {
        max-width: initial;
    }
}

.公司簡介 .介紹區域 .展示區塊 .圖片 {
    max-width: min(590px, 32vw);

    @include rwd {
        max-width: 100%;
        width    : 100%;
    }
}

.公司簡介 .介紹區域 .項目清單 {
    flex           : 1;
    display        : flex;
    flex-direction : column;
    align-items    : flex-start;
    justify-content: center;
}

.公司簡介 .介紹區域 .項目清單 .項目 {
    margin-top: 3rem;
}

/**
 * 產品特色
 */

.產品特色 {
    position: relative;
}

.產品特色 .包裹容器 {
    display        : flex;
    justify-content: center;
    max-width      : 1280px;
    margin         : 0 auto;

    @include rwd {
        flex-direction: column;
        gap           : 3rem;
        padding       : 0 0rem;
    }
}

.產品特色 .包裹容器 .展示區塊 {
    flex       : 0.75;
    text-align : center;
    padding-top: 4rem;

    @include rwd {
        padding-top: 0;
    }
}

.產品特色 .包裹容器 .展示區塊 .圖片 {
    width: 240px;
}

.產品特色 .包裹容器 .內容區塊 {
    flex: 1;

    @include rwd {
        max-width: 450px;
        margin   : 0 auto;
    }
}

.產品特色 .包裹容器 .內容區塊 .bullet {
    margin-left: 2.3rem;

    @include rwd {
        margin-left: 0;
    }
}

.產品特色 .包裹容器 .內容區塊 .bullet .symbol {
    @include rwd {
        margin-right: 2rem;
    }
}

.產品特色 .包裹容器 .半月列表 > .項目 {
    display   : flex;
    margin-top: 3.5rem;

    @include rwd {
        &:not(:first-child) {
            margin-top: 1.5rem;
        }
    }
}

.產品特色 .包裹容器 .半月列表 > .項目 .圖示 {
    margin-right: 3rem;

    @include rwd {
        margin-right: 1.5rem;
    }

    @include rwd-minimum {
        margin-right: 0rem;
    }
}

.產品特色 .包裹容器 .半月列表 > .項目 .內容.security {
    max-width: 18em;

    @include rwd {
        max-width: none;
    }
}

.產品特色 .包裹容器 .半月列表 > .項目 .內容 {
    color: #5d5555;

    @include rwd {
        font-size  : .9rem;
        line-height: 1.8;
    }
}

.產品特色 .標語 {
    text-align : center;
    color      : #f29600;
    font-size  : 2rem;
    font-weight: bolder;
    margin-top : 4rem;

    @include rwd {
        font-size : 1rem;
        margin-top: 2rem;
    }
}

/**
 * 快速變現
 */

.快速變現 {
    position: relative;
}

.快速變現 .包裹容器 {
    display    : flex;
    margin     : 0 auto;
    align-items: center;
    max-width  : 1000px;

    @include rwd {
        flex-direction: column;
        gap           : 4rem;
    }
}

.快速變現 .包裹容器 .圖表 {
    text-align: center;
    flex      : 1;
}

.快速變現 .包裹容器 .圖表 .圖片 {
    width: 590px;

    @include rwd {
        width: 76vw;
    }
}

.快速變現 .包裹容器 .半月列表 > .項目:not(:first-child) {
    margin-top: 5rem;

    @include rwd {
        margin-top: 3rem;
    }
}

/**
 * 產品方案
 */

.產品方案 {
    position: relative;
}

.產品方案 .包裹容器 {
    display              : grid;
    grid-template-columns: repeat(4, 1fr);
    max-width            : 1100px;
    margin               : 3rem auto 0;
    gap                  : 1.5rem;

    @include rwd {
        align-items          : center;
        justify-content      : center;
        grid-template-columns: min-content 1fr min-content;
        max-width            : 400px;
        min-height           : 465px;
    }
}

.產品方案 .包裹容器 .箭頭 {
    display: none;

    @include rwd {
        text-align: center;
        display   : block;
    }
}

.產品方案 .包裹容器 .箭頭 .圖示 {
    width: 33px;
}

.產品方案 .包裹容器 .方案表 {
    padding: 1rem;
    flex   : 1;

    @include rwd {
        &:not(.方案表_已啟用) {
            display: none;
        }
    }
}

/**
 * 普匯推薦
 */

.普匯推薦 {
    --alesis-xheader-offset     : -34%;
    --alesis-xsection-offset-top: 7rem;

    position: relative;
}

.普匯推薦 .包裹容器 {
    display        : flex;
    align-items    : center;
    justify-content: center;
    margin         : 0 0;
}

/**
 * 媒體報導
 */

.媒體報導 {
    position: relative;
}

.媒體報導 .包裹容器 {
    background-image: url('/images/sshot-1644.png');
    background-size : cover;
    padding         : .5rem 1rem;

    @include rwd {
        padding: .5rem 1rem;
    }
}

.媒體報導 .包裹容器 .商標區塊 {
    display              : grid;
    grid-template-columns: repeat(4, 1fr);
    max-width            : 1280px;
    margin               : 2rem auto;
    gap                  : 1rem;

    @include rwd {
        margin: 1rem auto;
    }
}

.媒體報導 .包裹容器 .商標區塊.商標區塊_三欄 {
    grid-template-columns: repeat(3, 1fr);
}

.媒體報導 .包裹容器 .商標區塊 .項目 {
    text-align: center;
}

.媒體報導 .包裹容器 .商標區塊 .項目 .圖片 {
    height        : 60px;
    margin        : 0 auto;
    mix-blend-mode: darken;

    @include rwd {
        height    : 6vw;
        max-height: 30px;
    }
}

/**
 * 計算器
 */

.分期計算機 {
    position: relative;
}

.分期計算機 .包裹容器 {
    display        : flex;
    align-items    : center;
    justify-content: center;
    max-width      : 1280px;
    margin         : 0 auto;

    @include rwd {
        flex-direction: column;
    }
}

.分期計算機 .包裹容器 .計算機 {
    flex           : 1;
    display        : flex;
    align-items    : center;
    justify-content: center;
    margin-right   : 7rem;

    @include rwd {
        width       : 380px;
        margin-right: 0;
    }
}

.分期計算機 .包裹容器 .計算機 > div {
    width: 600px;
}

.分期計算機 .包裹容器 .輸入群組 {
    flex: 1;
}

.分期計算機 .包裹容器 .輸入群組 .列 {
    margin    : 0;
    margin-top: 1rem;
    display   : flex;

    @include rwd {
        justify-content: center;

        &:first-child {
            display     : inline-flex;
            margin-right: 1rem;
        }

        &:nth-child(2) {
            display: inline-flex;

            .輸入欄位 {
                width: 3rem;
            }
        }
    }
}

.分期計算機 .包裹容器 .輸入群組 .列 .文字 {
    text-align      : center;
    background-image: linear-gradient(to right, #1e2973 0%, #319acf 50%, #1e2973 75%);
    background-clip : text;
    width           : fit-content;
    color           : rgba(255, 255, 255, 0);
    font-weight     : bolder;
    font-size       : 2rem;
    line-height     : 1.2;
    white-space     : nowrap;

    @include rwd {
        font-size: 1.2rem;
    }
}

.分期計算機 .包裹容器 .輸入群組 .列 .輸入欄位 {
    appearance    : none;
    border        : 0;
    border-bottom : 1px solid #2b8bc3;
    padding       : .25rem 1rem;
    box-sizing    : border-box;
    background    : transparent;
    color         : #f29600;
    font-size     : 2.3rem;
    margin-top    : -1rem;
    line-height   : 1;
    width         : 11rem;
    text-align    : center;
    outline       : none;
    pointer-events: none;

    @include rwd {
        font-size : 1.2rem;
        width     : 6rem;
        padding   : 0;
        margin-top: -.5rem;
    }
}

/**
 * 人物推薦
 */

.人物推薦 {
    position: relative;
}

.index-wrapper {
    width: 100%;

    .banner {
        position: relative;
        overflow: hidden;

        .puhey-banner {
            img {
                width: 100%;
            }

            .diagram {
                position: absolute;
                top     : 11%;
                left    : 29%;
                width   : 50%;
            }

            .content {
                position  : absolute;
                top       : 45%;
                left      : 35%;
                transform : translate(-50%, -50%);
                text-align: center;

                p {
                    text-shadow   : 2px 6px 6px rgba(0, 0, 0, 0.75);
                    font-family   : NotoSansTC;
                    font-size     : 2.3vw;
                    letter-spacing: 2.4px;
                    color         : #ffffff;
                }

                span {
                    text-shadow   : 2px 6px 6px rgba(0, 0, 0, 0.75);
                    font-size     : 2.1vw;
                    letter-spacing: 1.8px;
                    color         : #f2e627;
                    text-align    : center;
                }
            }

            .box {
                display   : flex;
                margin-top: 5vw;

                a {
                    img {
                        width: 17vw;
                        max-width: 230px;
                    }
                }

                %block {
                    // width   : 40%;
                    width   : 100%;
                    margin  : 0px auto;
                    position: relative;

                    :hover {
                        color          : #ffffff;
                        text-decoration: none;
                    }

                    .text {
                        color    : #ffffff;
                        position : absolute;
                        top      : 51%;
                        left     : 47%;
                        transform: translate(-50%, -50%);
                        font-size: min(1.5vw, 26px);
                    }
                }

                .loan {
                    @extend %block;
                }

                .borrow {
                    @extend %block;
                }
            }
        }
    }
    .histroy-card {
      padding: 40px 30px;
      background-color: #ecedf1;

      .h-c {
        display: flex;

        .arrows {
          position: relative;
          width: 100px;
          z-index: 1;

          div {
            width: 100px;
            position: absolute;
            top: 50%;
            transform: translate(0px, -50%);
            cursor: pointer;
          }
        }
      }
    }

    @media screen and (max-width: 767px) {
    .histroy-card {
      padding: 10px;

      .h-c {
        width: 100%;
        padding: 10px 0px;
        flex-direction: column;

        .al {
          div {
            right: 0px;
            transform: translate(0px, 50%) rotate(90deg);
          }
        }

        .ar {
          div {
            left: 0px;
            transform: translate(0px, -100%) rotate(90deg);
          }
        }

        .arrows {
          width: 100%;

          div {
            width: 70px;
          }
        }
      }
    }
        .banner {
            .puhey-banner {
                .diagram {
                    position: absolute;
                    top     : 25%;
                    left    : 10px;
                    width   : 65%;
                }

                img {
                    width: 100%;
                }

                .content {
                    font-size: 16px;
                    width    : 100%;
                    left     : 50%;
                    top      : 50%;

                    p {
                        font-size     : 25px;
                        letter-spacing: 0px;
                    }

                    span {
                        font-size: 22px;
                        letter-spacing: 0px;
                    }

                    .box {
                        margin-top: 103vw;

                        %block {
                            width: 170px;

                            .text {
                                font-size: 16px;
                            }
                        }

                        a {
                            img {
                                width: 100%;
                            }
                        }
                    }
                }
            }
        }
    }
}
</style>
