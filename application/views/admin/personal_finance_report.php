<meta charset="UTF-8">
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-header">個金報表查詢</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs" id="myTabs" role="tablist">
                <li role="presentation" class="active">
                    <a role="tab" data-toggle="tab" aria-controls="test1" aria-expanded="true"
                        @click="tab='tab1'">借貸申貸概況報表</a>
                </li>
                <li role="presentation">
                    <a role="tab" data-toggle="tab" aria-controls="test2" aria-expanded="false"
                        @click="tab='tab2'">借款成交概況報表</a>
                </li>
                <li role="presentation">
                    <a role="tab" data-toggle="tab" aria-controls="test3" aria-expanded="false"
                        @click="tab='tab3'">實名制統計報表</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="panel panel-default" v-show="tab == 'tab1'">
        <div class="panel-heading" style="background-color: #ddd;">
            <table>
                <tr style="height: 40px;">
                    <td>
                        <label>請選擇欲輸出的報表：</label>
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <input type="checkbox" v-model="loanSelect.student">
                        <span>申貸戶統計-學生貸</span>
                    </td>
                    <td>
                        <input class="ml-5" type="checkbox" v-model="loanSelect.work">
                        <span>申貸戶統計-上班族貸</span>
                    </td>
                    <td>
                        <input class="ml-5" type="checkbox" v-model="loanSelect.trend">
                        <span>申貸戶趨勢圖</span>
                    </td>
                    <td>
                        <input class="ml-5" type="checkbox" v-model="loanSelect.statistics">
                        <span>申貸數據統計</span>
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <label>案件申請時間：<label>
                    </td>
                    <td>
                        <input class="searchDt" type="date" v-model="start_date" min="2000-01-01" max="2030-12-31"
                            placeholder="不指定區間">
                    </td>
                    <td>
                        到：
                    </td>
                    <td>
                        <input class="searchDt" type="date" v-model="end_date" min="2000-01-01" max="2030-12-31"
                            placeholder="不指定區間">
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <button class="searchBtn" @click="goSearchLoan">查詢</button>
                    </td>
                    <td>
                        <button class="searchBtn ml-3" @click="exportLoanPPT">匯出報表</button>
                    </td>
                </tr>
            </table>
        </div>

        <div class="panel-body mt-5">
            <template v-if="loanSelect.student">
                <h3>申貸戶統計 - 學生貸</h3>
                <div class="table-responsive" id="loanDiv1">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td class="tdTitleBlue" v-for="item in loanReportDict.date_list" :key="item">
                                    {{ item }} 月
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生貸</td>
                                <td v-for="item in loanReportDict.student_loan.student" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">產品轉換</td>
                                <td v-for="item in loanReportDict.student_loan.transfer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">工程師貸</td>
                                <td v-for="item in loanReportDict.student_loan.engineer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">手機貸</td>
                                <td v-for="item in loanReportDict.student_loan.phone" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">名校貸</td>
                                <td v-for="item in loanReportDict.student_loan.famous" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td>總和</td>
                                <td v-for="item in loanReportDict.student_loan.total" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>

            <template v-if="loanSelect.work">
                <h3 class="mt-3">申貸戶統計 - 上班族貸</h3>
                <div class="table-responsive" id="loanDiv2">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td class="tdTitleBlue" v-for="item in loanReportDict.date_list" :key="item">
                                    {{ item }} 月
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族貸</td>
                                <td v-for="item in loanReportDict.work_loan.work" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">產品轉換</td>
                                <td v-for="item in loanReportDict.work_loan.transfer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">工程師貸</td>
                                <td v-for="item in loanReportDict.work_loan.engineer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">手機貸</td>
                                <td v-for="item in loanReportDict.work_loan.phone" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">裝修(入口)</td>
                                <td v-for="item in loanReportDict.work_loan.decorate" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">購車(入口)</td>
                                <td v-for="item in loanReportDict.work_loan.buy_car" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">購房(入口)</td>
                                <td v-for="item in loanReportDict.work_loan.buy_house" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td>總和</td>
                                <td v-for="item in loanReportDict.work_loan.total" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>

            <template v-if="loanSelect.trend">
                <div id="loan_trend_chart" style="width: 900px; height: 500px"></div>
            </template>

            <template v-if="loanSelect.statistics">
                <h3>申貸數據統計</h3>
                <div class="table-responsive" id="loanDiv3">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td class="tdTitleBlue" v-for="item in loanReportDict.date_list" :key="item">
                                    {{ item }} 月
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生申貸案</td>
                                <td v-for="item in loanReportDict.statistics.student_loan_application" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生申貸戶</td>
                                <td v-for="item in loanReportDict.statistics.student_user" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生均申貸次數</td>
                                <td v-for="item in loanReportDict.statistics.student_average_time" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生申貸新戶</td>
                                <td v-for="item in loanReportDict.statistics.student_new_user" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生新戶占比(%)</td>
                                <td v-for="item in loanReportDict.statistics.student_new_user_percentage" :key="item">
                                    {{ item }} ％
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">成交率(%)</td>
                                <td class="tdTitleOrg" v-for="item in loanReportDict.statistics.student_turnover_rate"
                                    :key="item">
                                    {{ item }} ％
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族申貸案</td>
                                <td v-for="item in loanReportDict.statistics.work_loan_application" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族申貸戶</td>
                                <td v-for="item in loanReportDict.statistics.work_user" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族均申貸次數</td>
                                <td v-for="item in loanReportDict.statistics.work_average_time" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族申貸新戶</td>
                                <td v-for="item in loanReportDict.statistics.work_new_user" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族新戶占比(%)</td>
                                <td v-for="item in loanReportDict.statistics.work_new_user_percentage" :key="item">
                                    {{ item }} ％
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">成交率(%)</td>
                                <td class="tdTitleOrg" v-for="item in loanReportDict.statistics.work_turnover_rate"
                                    :key="item">
                                    {{ item }} ％
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </div>
    </div>

    <div class="panel panel-default" v-show="tab == 'tab2'">
        <div class="panel-heading" style="background-color: #ddd;">
            <table>
                <tr style="height: 40px;">
                    <td>
                        <label>請選擇欲輸出的報表：</label>
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <input type="checkbox" v-model="transactionSelect.case">
                        <span>成交案統計</span>
                    </td>
                    <td>
                        <input class="ml-5" type="checkbox" v-model="transactionSelect.household">
                        <span>成交戶統計</span>
                    </td>
                    <td>
                        <input class="ml-5" type="checkbox" v-model="transactionSelect.trend">
                        <span>成交趨勢圖</span>
                    </td>
                    <td>
                        <input class="ml-5" type="checkbox" v-model="transactionSelect.new_household">
                        <span>成交新戶統計</span>
                    </td>
                    <td>
                        <input class="ml-5" type="checkbox" v-model="transactionSelect.amount">
                        <span>成交金額統計</span>
                    </td>
                    <td>
                        <input class="ml-5" type="checkbox" v-model="transactionSelect.amount_trend">
                        <span>成交金額趨勢圖</span>
                    </td>
                    <td>
                        <input class="ml-5" type="checkbox" v-model="transactionSelect.statistics">
                        <span>成交數據統計</span>
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <label>案件申請時間：<label>
                    </td>
                    <td>
                        <input class="searchDt" type="date" v-model="start_date" min="2000-01-01" max="2030-12-31"
                            placeholder="不指定區間">
                    </td>
                    <td>
                        到：
                    </td>
                    <td>
                        <input class="searchDt" type="date" v-model="end_date" min="2000-01-01" max="2030-12-31"
                            placeholder="不指定區間">
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <button class="searchBtn" @click="goSearchCase()">查詢</button>
                    </td>
                    <td>
                        <button class="searchBtn ml-3" @click="exportTransactionPPT()">匯出報表</button>
                    </td>
                </tr>
            </table>
        </div>

        <div class="panel-body mt-5">
            <template v-if="transactionSelect.case">
                <h3>成交案統計</h3>
                <div class="table-responsive" id="transactionDiv1">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td class="tdTitleBlue" v-for="item in transactionReportDict.date_list" :key="item">
                                    {{ item }} 月
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生貸</td>
                                <td v-for="item in transactionReportDict.case.student" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生產品轉換</td>
                                <td v-for="item in transactionReportDict.case.student_transfer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生工程師貸</td>
                                <td v-for="item in transactionReportDict.case.student_engineer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生名校貸</td>
                                <td v-for="item in transactionReportDict.case.student_famous" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">學生總和</td>
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.case.student_total"
                                    :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族貸</td>
                                <td v-for="item in transactionReportDict.case.work" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族產品轉換</td>
                                <td v-for="item in transactionReportDict.case.work_transfer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族工程師貸</td>
                                <td v-for="item in transactionReportDict.case.work_engineer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族手機貸</td>
                                <td v-for="item in transactionReportDict.case.work_phone" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">上班族總和</td>
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.case.work_total"
                                    :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>

            <template v-if="transactionSelect.household">
                <h3>成交戶統計</h3>
                <div class="table-responsive" id="transactionDiv2">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td class="tdTitleBlue" v-for="item in transactionReportDict.date_list" :key="item">
                                    {{ item }} 月
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生貸</td>
                                <td v-for="item in transactionReportDict.household.student" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生產品轉換</td>
                                <td v-for="item in transactionReportDict.household.student_transfer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生工程師貸</td>
                                <td v-for="item in transactionReportDict.household.student_engineer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生名校貸</td>
                                <td v-for="item in transactionReportDict.household.student_famous" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">學生總和</td>
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.household.student_total"
                                    :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族貸</td>
                                <td v-for="item in transactionReportDict.household.work" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族產品轉換</td>
                                <td v-for="item in transactionReportDict.household.work_transfer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族工程師貸</td>
                                <td v-for="item in transactionReportDict.household.work_engineer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">上班族總和</td>
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.household.work_total"
                                    :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>

            <template v-if="transactionSelect.trend">
                <div id="case_trend_chart" style="width: 900px; height: 500px"></div>
                <div id="household_trend_chart" style="width: 900px; height: 500px"></div>
            </template>

            <template v-if="transactionSelect.new_household">
                <h3>新戶成交統計</h3>
                <div class="table-responsive" id="transactionDiv3">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td class="tdTitleBlue" v-for="item in transactionReportDict.date_list" :key="item">
                                    {{ item }} 月
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生貸</td>
                                <td v-for="item in transactionReportDict.new_household.student" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生工程師貸</td>
                                <td v-for="item in transactionReportDict.new_household.student_engineer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生名校貸</td>
                                <td v-for="item in transactionReportDict.new_household.student_famous" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">學生總和</td>
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.new_household.student_total"
                                    :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族貸</td>
                                <td v-for="item in transactionReportDict.new_household.work" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族階段上架</td>
                                <td v-for="item in transactionReportDict.new_household.work_level" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族工程師貸</td>
                                <td v-for="item in transactionReportDict.new_household.work_engineer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">上班族總和</td>
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.new_household.work_total"
                                    :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>

            <template v-if="transactionSelect.amount">
                <h3>成交金額</h3>
                <div class="table-responsive" id="transactionDiv4">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td class="tdTitleBlue" v-for="item in transactionReportDict.date_list" :key="item">
                                    {{ item }} 月
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生貸</td>
                                <td v-for="item in transactionReportDict.amount.student" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生產品轉換</td>
                                <td v-for="item in transactionReportDict.amount.student_transfer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生工程師貸</td>
                                <td v-for="item in transactionReportDict.amount.student_engineer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生名校貸</td>
                                <td v-for="item in transactionReportDict.amount.student_famous" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">學生總和</td>
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.amount.student_total"
                                    :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族貸</td>
                                <td v-for="item in transactionReportDict.amount.work" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族產品轉換</td>
                                <td v-for="item in transactionReportDict.amount.work_transfer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族工程師貸</td>
                                <td v-for="item in transactionReportDict.amount.work_engineer" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">上班族總和</td>
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.amount.work_total"
                                    :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>

            <template v-if="transactionSelect.amount_trend">
                <div id="amount_trend_chart" style="width: 900px; height: 500px"></div>
            </template>

            <template v-if="transactionSelect.statistics">
                <h3>成交數據統計</h3>
                <div class="table-responsive" id="transactionDiv5">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td class="tdTitleBlue" v-for="item in transactionReportDict.date_list" :key="item">
                                    {{ item }} 月
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生貸成交案</td>
                                <td v-for="item in transactionReportDict.statistics.student_done" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生貸成交戶</td>
                                <td v-for="item in transactionReportDict.statistics.student_household" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">每戶均成交次數</td>
                                <td v-for="item in transactionReportDict.statistics.student_average_done" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生貸成交新戶</td>
                                <td v-for="item in transactionReportDict.statistics.student_new_household" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">占總成交戶比重(％)</td>
                                <td v-for="item in transactionReportDict.statistics.student_new_household_percentage"
                                    :key="item">
                                    {{ item }} ％
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">學生貸均放款金額</td>
                                <td class="tdTitleOrg"
                                    v-for="item in transactionReportDict.statistics.student_average_amount" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族貸成交案</td>
                                <td v-for="item in transactionReportDict.statistics.work_done" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族貸成交戶</td>
                                <td v-for="item in transactionReportDict.statistics.work_household" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">每戶均成交次數</td>
                                <td v-for="item in transactionReportDict.statistics.work_average_done" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族貸成交新戶</td>
                                <td v-for="item in transactionReportDict.statistics.work_new_household" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">占總成交戶比重(％)</td>
                                <td v-for="item in transactionReportDict.statistics.work_new_household_percentage"
                                    :key="item">
                                    {{ item }} ％
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">上班族貸均放款金額</td>
                                <td class="tdTitleOrg"
                                    v-for="item in transactionReportDict.statistics.work_average_amount" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </div>
    </div>

    <div class="panel panel-default" v-show="tab == 'tab3'">
        <div class="panel-heading" style="background-color: #ddd;">
            <table>
                <tr style="height: 40px;">
                    <td>
                        <label>案件申請時間：<label>
                    </td>
                    <td>
                        <input class="searchDt" type="date" v-model="start_date" min="2000-01-01" max="2030-12-31"
                            placeholder="不指定區間">
                    </td>
                    <td>
                        到：
                    </td>
                    <td>
                        <input class="searchDt" type="date" v-model="end_date" min="2000-01-01" max="2030-12-31"
                            placeholder="不指定區間">
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <button class="searchBtn" @click="goSearchIdentity()">查詢</button>
                    </td>
                    <td>
                        <button class="searchBtn ml-3" @click="exportIdentityPPT()">匯出報表</button>
                    </td>
                </tr>
            </table>
        </div>

        <div class="panel-body mt-5">
            <template v-if="identityReportDict.identity_household">
                <h3>申貸戶</h3>
                <div class="table-responsive" id="identityDiv1">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td class="tdTitleBlue" v-for="item in identityReportDict.date_list" :key="item">
                                    {{ item }} 月
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生完成實名</td>
                                <td v-for="item in identityReportDict.identity_household.student_done" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生未完成實名</td>
                                <td v-for="item in identityReportDict.identity_household.student_unfinish" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生總計</td>
                                <td v-for="item in identityReportDict.identity_household.student_total" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">學生完成占比(%)</td>
                                <td class="tdTitleOrg"
                                    v-for="item in identityReportDict.identity_household.student_rate" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族完成實名</td>
                                <td v-for="item in identityReportDict.identity_household.work_done" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族未完成實名</td>
                                <td v-for="item in identityReportDict.identity_household.work_unfinish" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族總計</td>
                                <td v-for="item in identityReportDict.identity_household.work_total" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">上班族完成占比(%)</td>
                                <td class="tdTitleOrg" v-for="item in identityReportDict.identity_household.work_rate"
                                    :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>

            <template v-if="identityReportDict.identity_new_household">
                <h3>申貸新戶</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%" id="identityDiv2">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td class="tdTitleBlue" v-for="item in identityReportDict.date_list" :key="item">
                                    {{ item }} 月
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生完成實名</td>
                                <td v-for="item in identityReportDict.identity_new_household.student_done" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生未完成實名</td>
                                <td v-for="item in identityReportDict.identity_new_household.student_unfinish"
                                    :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">學生總計</td>
                                <td v-for="item in identityReportDict.identity_new_household.student_total" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">學生完成占比(%)</td>
                                <td class="tdTitleOrg"
                                    v-for="item in identityReportDict.identity_new_household.student_rate" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族完成實名</td>
                                <td v-for="item in identityReportDict.identity_new_household.work_done" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族未完成實名</td>
                                <td v-for="item in identityReportDict.identity_new_household.work_unfinish" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleBlue">上班族總計</td>
                                <td v-for="item in identityReportDict.identity_new_household.work_total" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">上班族完成占比(%)</td>
                                <td class="tdTitleOrg"
                                    v-for="item in identityReportDict.identity_new_household.work_rate" :key="item">
                                    {{ item }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pptxgenjs@3.12.0/dist/pptxgen.bundle.js"></script>
<script>
    var p2p_orm_host = '<?php print_r(getenv('ENV_P2P_ORM_HTTPS_HOST')) ?>';

    const v = new Vue({
        el: '#page-wrapper',
        data() {
            return {
                tab: 'tab1',
                start_date: '',
                end_date: '',
                loanSelect: {
                    student: false,
                    work: false,
                    trend: false,
                    statistics: false
                },
                transactionSelect: {
                    case: false,
                    household: false,
                    trend: false,
                    new_household: false,
                    amount: false,
                    amount_trend: false,
                    statistics: false
                },
                loanReportDict: {},
                transactionReportDict: {},
                identityReportDict: {}
            }
        },
        methods: {
            goSearchLoan() {
                if (this.start_date == '' || this.end_date == '') {
                    alert('請選擇查詢時間區間。')
                } else {
                    let params = {
                        start_dt: this.start_date,
                        end_dt: this.end_date
                    }

                    axios.post(`${p2p_orm_host}/personal_finance_report/apply_loan`, this.loanSelect, { params })
                        .then((res) => {
                            this.loanReportDict = res.data;
                            console.log(this.loanReportDict);
                            this.loanReportDict.trend && this.loanTrendChart()
                        }).catch((err) => {
                            console.log(err);
                        })
                }
            },
            loanTrendChart() {
                google.charts.load('current', { 'packages': ['corechart'] });
                google.charts.setOnLoadCallback(this.drawLoanTrendChart);
            },
            drawLoanTrendChart() {
                let loanData = [['年月份', '學生貸', '上班族貸']];

                for (let i = 0; i < this.loanReportDict.date_list.length; i++) {
                    let trendList = [];

                    trendList.push(this.loanReportDict.date_list[i]);
                    trendList.push(this.loanReportDict.trend.student_trend[i]);
                    trendList.push(this.loanReportDict.trend.work_trend[i]);

                    loanData.push(trendList);
                }
                var data = google.visualization.arrayToDataTable(loanData);

                var options = {
                    title: '申貸戶',
                    curveType: 'function',
                    legend: { position: 'bottom' }
                };

                var chart = new google.visualization.LineChart(document.getElementById('loan_trend_chart'));

                chart.draw(data, options);
            },
            goSearchCase() {
                if (this.start_date == '' || this.end_date == '') {
                    alert('請選擇查詢時間區間。')
                } else {
                    let params = {
                        start_dt: this.start_date,
                        end_dt: this.end_date
                    }

                    axios.post(`${p2p_orm_host}/personal_finance_report/transaction_done`, this.transactionSelect, { params })
                        .then((res) => {
                            this.transactionReportDict = res.data;
                            console.log(this.transactionReportDict);
                            this.caseTrendChart();
                            this.householdTrendChart();
                            this.amountTrendChart();
                        }).catch((err) => {
                            console.log(err);
                        })
                }
            },
            caseTrendChart() {
                google.charts.load('current', { 'packages': ['corechart'] });
                google.charts.setOnLoadCallback(this.drawCaseTrendChart);
            },
            drawCaseTrendChart() {
                let loanData = [['年月份', '學生貸', '上班族貸']];

                for (let i = 0; i < this.transactionReportDict.date_list.length; i++) {
                    let trendList = [];

                    trendList.push(this.transactionReportDict.date_list[i]);
                    trendList.push(this.transactionReportDict.trend.student_case[i]);
                    trendList.push(this.transactionReportDict.trend.work_case[i]);

                    loanData.push(trendList);
                }
                var data = google.visualization.arrayToDataTable(loanData);

                var options = {
                    title: '成交案',
                    curveType: 'function',
                    legend: { position: 'bottom' }
                };

                var chart = new google.visualization.LineChart(document.getElementById('case_trend_chart'));

                chart.draw(data, options);
            },
            householdTrendChart() {
                google.charts.load('current', { 'packages': ['corechart'] });
                google.charts.setOnLoadCallback(this.drawHouseholdTrendChart);
            },
            drawHouseholdTrendChart() {
                let loanData = [['年月份', '學生貸', '上班族貸']];

                for (let i = 0; i < this.transactionReportDict.date_list.length; i++) {
                    let trendList = [];

                    trendList.push(this.transactionReportDict.date_list[i]);
                    trendList.push(this.transactionReportDict.trend.student_household[i]);
                    trendList.push(this.transactionReportDict.trend.work_household[i]);

                    loanData.push(trendList);
                }
                var data = google.visualization.arrayToDataTable(loanData);

                var options = {
                    title: '成交戶',
                    curveType: 'function',
                    legend: { position: 'bottom' }
                };

                var chart = new google.visualization.LineChart(document.getElementById('household_trend_chart'));

                chart.draw(data, options);
            },
            amountTrendChart() {
                google.charts.load('current', { 'packages': ['corechart'] });
                google.charts.setOnLoadCallback(this.drawAmountTrendChart);
            },
            drawAmountTrendChart() {
                let loanData = [['年月份', '學生貸', '上班族貸']];

                for (let i = 0; i < this.transactionReportDict.date_list.length; i++) {
                    let trendList = [];

                    trendList.push(this.transactionReportDict.date_list[i]);
                    trendList.push(this.transactionReportDict.amount_trend.student[i]);
                    trendList.push(this.transactionReportDict.amount_trend.work[i]);

                    loanData.push(trendList);
                }
                var data = google.visualization.arrayToDataTable(loanData);

                var options = {
                    title: '成交金額趨勢圖',
                    curveType: 'function',
                    legend: { position: 'bottom' }
                };

                var chart = new google.visualization.LineChart(document.getElementById('amount_trend_chart'));

                chart.draw(data, options);
            },
            goSearchIdentity() {
                if (this.start_date == '' || this.end_date == '') {
                    alert('請選擇查詢時間區間。')
                } else {
                    axios.post(`${p2p_orm_host}/personal_finance_report/identity?start_dt=${this.start_date}&end_dt=${this.end_date}`)
                        .then((res) => {
                            console.log(res.data);
                            this.identityReportDict = res.data;
                        })
                        .catch((err) => {
                            console.log(err);
                        })
                }
            },
            exportIdentityPPT() {
                const isDataQueried = this.identityReportDict.identity_household || this.identityReportDict.identity_new_household
                if (!isDataQueried) {
                    alert("請先完成查詢")
                    return
                }

                var pptx = new PptxGenJS();

                pptx.tableToSlides('identityDiv1');
                pptx.tableToSlides('identityDiv2');

                // 匯出PPT檔案並下載
                pptx.writeFile({ fileName: "identity.pptx" });
            },
            exportLoanPPT() {
                const isDataQueried = this.loanReportDict.student_loan || this.loanReportDict.work_loan || this.loanReportDict.trend || this.loanReportDict.statistics
                if (!isDataQueried) {
                    alert("請先完成查詢")
                    return
                }

                const pptx = new PptxGenJS();

                this.loanReportDict.student_loan && pptx.tableToSlides('loanDiv1');
                this.loanReportDict.work_loan && pptx.tableToSlides('loanDiv2');

                if (this.loanReportDict.trend) {
                    const labels_list = [...this.loanReportDict.date_list];
                    const student_list = [...this.loanReportDict.trend.student_trend.slice(0, labels_list.length)];
                    const work_list = [...this.loanReportDict.trend.work_trend.slice(0, labels_list.length)];
                    const dataChartAreaLine = [
                        {
                            name: "上班族貸",
                            labels: labels_list,
                            values: work_list,
                        },
                        {
                            name: "學生貸",
                            labels: labels_list,
                            values: student_list,
                        },
                    ];

                    const slide = pptx.addSlide();
                    slide.addChart(pptx.ChartType.line, dataChartAreaLine, { x: 1, y: 1, w: 8, h: 4 });
                }

                this.loanReportDict.statistics && pptx.tableToSlides('loanDiv3');

                // 匯出PPT檔案並下載
                pptx.writeFile({ fileName: "loan.pptx" });
            },
            exportTransactionPPT() {
                const isDataQueried = this.transactionReportDict.case || this.transactionReportDict.household || this.transactionReportDict.trend || this.transactionReportDict.new_household || this.transactionReportDict.amount || this.transactionReportDict.amount_trend || this.transactionReportDict.statistics
                if (!isDataQueried) {
                    alert("請先完成查詢")
                    return
                }

                var pptx = new PptxGenJS();

                pptx.tableToSlides('transactionDiv1');
                pptx.tableToSlides('transactionDiv2');

                let labels_list = [];
                let student_list = [];
                let work_list = [];
                for (let i = 0; i < this.transactionReportDict.date_list.length; i++) {
                    labels_list.push(this.transactionReportDict.date_list[i]);
                    student_list.push(this.transactionReportDict.trend.student_case[i]);
                    work_list.push(this.transactionReportDict.trend.work_case[i]);
                }
                let dataChartAreaLine = [
                    {
                        name: "上班族貸",
                        labels: labels_list,
                        values: work_list,
                    },
                    {
                        name: "學生貸",
                        labels: labels_list,
                        values: student_list,
                    },
                ];
                let slide = pptx.addSlide();
                slide.addChart(pptx.ChartType.line, dataChartAreaLine, { x: 1, y: 1, w: 8, h: 4 });


                let labels_list1 = [];
                let student_list1 = [];
                let work_list1 = [];
                for (let i = 0; i < this.transactionReportDict.date_list.length; i++) {
                    labels_list1.push(this.transactionReportDict.date_list[i]);
                    student_list1.push(this.transactionReportDict.trend.student_household[i]);
                    work_list1.push(this.transactionReportDict.trend.work_household[i]);
                }
                let dataChartAreaLine1 = [
                    {
                        name: "上班族貸",
                        labels: labels_list1,
                        values: work_list1,
                    },
                    {
                        name: "學生貸",
                        labels: labels_list1,
                        values: student_list1,
                    },
                ];
                let slide1 = pptx.addSlide();
                slide1.addChart(pptx.ChartType.line, dataChartAreaLine1, { x: 1, y: 1, w: 8, h: 4 });


                pptx.tableToSlides('transactionDiv3');
                pptx.tableToSlides('transactionDiv4');

                let labels_list2 = [];
                let student_list2 = [];
                let work_list2 = [];
                for (let i = 0; i < this.transactionReportDict.date_list.length; i++) {
                    labels_list2.push(this.transactionReportDict.date_list[i]);
                    student_list2.push(this.transactionReportDict.amount_trend.student[i]);
                    work_list2.push(this.transactionReportDict.amount_trend.work[i]);
                }
                let dataChartAreaLine2 = [
                    {
                        name: "上班族貸",
                        labels: labels_list2,
                        values: work_list2,
                    },
                    {
                        name: "學生貸",
                        labels: labels_list2,
                        values: student_list2,
                    },
                ];
                let slide2 = pptx.addSlide();
                slide2.addChart(pptx.ChartType.line, dataChartAreaLine2, { x: 1, y: 1, w: 8, h: 4 });


                pptx.tableToSlides('transactionDiv5');

                // 匯出PPT檔案並下載
                pptx.writeFile({ fileName: "transaction.pptx" });
            }
        }
    });

</script>
<style scoped>
    * {
        font-family: PMingLiU;
    }

    .searchDt {
        width: 150px;
        height: 28px;
    }

    .searchBtn {
        border-radius: 4px;
        width: 80px;
    }

    .tdTitleBlue {
        background-color: #003D79 !important;
        color: white !important;
    }

    .tdTitleOrg {
        background-color: #BB3D00 !important;
        color: white !important;
    }
</style>