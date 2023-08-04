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
                    <a role="tab" data-toggle="tab" aria-controls="test1" aria-expanded="true" @click="tab='tab1'">借貸申貸概況報表</a>
                </li>
                <li role="presentation">
                    <a role="tab" data-toggle="tab" aria-controls="test2" aria-expanded="false" @click="tab='tab2'">借款成交概況報表</a>
                </li>
                <li role="presentation">
                    <a role="tab" data-toggle="tab" aria-controls="test3" aria-expanded="false" @click="tab='tab3'">實名制統計報表</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="panel panel-default"  v-show="tab == 'tab1'">
        <div class="panel-heading" style="background-color: #ddd;">
            <table>
                <tr style="height: 40px;">
                    <td>
                        <label>請選擇欲輸出的報表：</label>
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <input 
                            type="checkbox" 
                            v-model="loanSelect.student"
                        >
                        <span>申貸戶統計-學生貸</span>
                    </td>
                    <td>
                        <input 
                            class="ml-5"
                            type="checkbox" 
                            v-model="loanSelect.work"
                        >
                        <span>申貸戶統計-上班族貸</span>
                    </td>
                    <td>
                        <input 
                            class="ml-5"
                            type="checkbox" 
                            v-model="loanSelect.trend"
                        >
                        <span>申貸戶趨勢圖</span>
                    </td>
                    <td>
                        <input 
                            class="ml-5"
                            type="checkbox" 
                            v-model="loanSelect.statistics"
                        >
                        <span>申貸數據統計</span>
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <label>案件申請時間：<label>
                    </td>
                    <td>
                        <input
                            class="searchDt"
                            type="date" 
                            v-model="start_date"
                            min="2000-01-01" 
                            max="2030-12-31"
                            placeholder="不指定區間"
                        >  
                    </td>
                    <td>
                        到：
                    </td>
                    <td>
                        <input
                            class="searchDt"
                            type="date" 
                            v-model="end_date"
                            min="2000-01-01" 
                            max="2030-12-31"
                            placeholder="不指定區間"
                        >
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <button 
                            class="searchBtn"
                            @click="goSearchLoan()"
                        >查詢</button>
                    </td>
                    <td>
                        <button class="searchBtn ml-3">匯出報表</button>
                    </td>
                </tr>
            </table>
        </div>

        <div class="panel-body mt-5">
            <template v-if="loanSelect.student">
                <h3>申貸戶統計 - 學生貸</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td
                                    class="tdTitleBlue" 
                                    v-for="item in loanReportDict.date_list" 
                                    :key="item"
                                >
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
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td
                                    class="tdTitleBlue" 
                                    v-for="item in loanReportDict.date_list" 
                                    :key="item"
                                >
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
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td
                                    class="tdTitleBlue" 
                                    v-for="item in loanReportDict.date_list" 
                                    :key="item"
                                >
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
                                <td class="tdTitleBlue">成交率(%)</td>
                                <td v-for="item in loanReportDict.statistics.student_turnover_rate" :key="item">
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
                                <td class="tdTitleBlue">成交率(%)</td>
                                <td v-for="item in loanReportDict.statistics.work_turnover_rate" :key="item">
                                    {{ item }} ％
                                </td>
                            </tr>                              
                        </tbody>
                    </table>
                </div>
            </template>
        </div>
    </div>

    <div class="panel panel-default"  v-show="tab == 'tab2'">
        <div class="panel-heading" style="background-color: #ddd;">
            <table>
                <tr style="height: 40px;">
                    <td>
                        <label>請選擇欲輸出的報表：</label>
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <input 
                            type="checkbox" 
                            v-model="transactionSelect.case"
                        >
                        <span>成交案統計</span>
                    </td>
                    <td>
                        <input 
                            class="ml-5"
                            type="checkbox" 
                            v-model="transactionSelect.household"
                        >
                        <span>成交戶統計</span>
                    </td>
                    <td>
                        <input 
                            class="ml-5"
                            type="checkbox" 
                            v-model="transactionSelect.trend"
                        >
                        <span>成交趨勢圖</span>
                    </td>
                    <td>
                        <input 
                            class="ml-5"
                            type="checkbox" 
                            v-model="transactionSelect.new_household"
                        >
                        <span>成交新戶統計</span>
                    </td>
                    <td>
                        <input 
                            class="ml-5"
                            type="checkbox" 
                            v-model="transactionSelect.amount"
                        >
                        <span>成交金額統計</span>
                    </td>
                    <td>
                        <input 
                            class="ml-5"
                            type="checkbox" 
                            v-model="transactionSelect.amount_trend"
                        >
                        <span>成交金額趨勢圖</span>
                    </td>
                    <td>
                        <input 
                            class="ml-5"
                            type="checkbox" 
                            v-model="transactionSelect.statistics"
                        >
                        <span>成交數據統計</span>
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <label>案件申請時間：<label>
                    </td>
                    <td>
                        <input
                            class="searchDt"
                            type="date" 
                            v-model="start_date"
                            min="2000-01-01" 
                            max="2030-12-31"
                            placeholder="不指定區間"
                        >  
                    </td>
                    <td>
                        到：
                    </td>
                    <td>
                        <input
                            class="searchDt"
                            type="date" 
                            v-model="end_date"
                            min="2000-01-01" 
                            max="2030-12-31"
                            placeholder="不指定區間"
                        >
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <button 
                            class="searchBtn"
                            @click="goSearchCase()"
                        >查詢</button>
                    </td>
                    <td>
                        <button class="searchBtn ml-3">匯出報表</button>
                    </td>
                </tr>
            </table>
        </div>

        <div class="panel-body mt-5">
            <template v-if="transactionSelect.case">
                <h3>成交案統計</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td
                                    class="tdTitleBlue" 
                                    v-for="item in transactionReportDict.date_list" 
                                    :key="item"
                                >
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
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.case.student_total" :key="item">
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
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.case.work_total" :key="item">
                                    {{ item }}
                                </td>
                            </tr>                           
                        </tbody>
                    </table>
                </div>
            </template>

            <template v-if="transactionSelect.household">
                <h3>成交戶統計</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td
                                    class="tdTitleBlue" 
                                    v-for="item in transactionReportDict.date_list" 
                                    :key="item"
                                >
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
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.household.student_total" :key="item">
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
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.household.work_total" :key="item">
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
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td
                                    class="tdTitleBlue" 
                                    v-for="item in transactionReportDict.date_list" 
                                    :key="item"
                                >
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
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.new_household.student_total" :key="item">
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
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.new_household.work_total" :key="item">
                                    {{ item }}
                                </td>
                            </tr>                           
                        </tbody>
                    </table>
                </div>
            </template>

            <template v-if="transactionSelect.amount">
                <h3>成交金額</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td
                                    class="tdTitleBlue" 
                                    v-for="item in transactionReportDict.date_list" 
                                    :key="item"
                                >
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
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.amount.student_total" :key="item">
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
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.amount.work_total" :key="item">
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
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td
                                    class="tdTitleBlue" 
                                    v-for="item in transactionReportDict.date_list" 
                                    :key="item"
                                >
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
                                <td v-for="item in transactionReportDict.statistics.student_new_household_percentage" :key="item">
                                    {{ item }} ％
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">學生貸均放款金額</td>
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.statistics.student_average_amount" :key="item">
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
                                <td v-for="item in transactionReportDict.statistics.work_new_household_percentage" :key="item">
                                    {{ item }} ％
                                </td>
                            </tr>
                            <tr>
                                <td class="tdTitleOrg">上班族貸均放款金額</td>
                                <td class="tdTitleOrg" v-for="item in transactionReportDict.statistics.work_average_amount" :key="item">
                                    {{ item }}
                                </td>
                            </tr>                           
                        </tbody>
                    </table>
                </div>
            </template>
        </div>
    </div>

    <div class="panel panel-default"  v-show="tab == 'tab3'">
        <div class="panel-heading" style="background-color: #ddd;">
            <table>
                <tr style="height: 40px;">
                    <td>
                        <label>案件申請時間：<label>
                    </td>
                    <td>
                        <input
                            class="searchDt"
                            type="date" 
                            v-model="start_date"
                            min="2000-01-01" 
                            max="2030-12-31"
                            placeholder="不指定區間"
                        >  
                    </td>
                    <td>
                        到：
                    </td>
                    <td>
                        <input
                            class="searchDt"
                            type="date" 
                            v-model="end_date"
                            min="2000-01-01" 
                            max="2030-12-31"
                            placeholder="不指定區間"
                        >
                    </td>
                </tr>
                <tr style="height: 40px;">
                    <td>
                        <button 
                            class="searchBtn"
                            @click="goSearchIdentity()"
                        >查詢</button>
                    </td>
                    <td>
                        <button class="searchBtn ml-3">匯出報表</button>
                    </td>
                </tr>
            </table>
        </div>

        <div class="panel-body mt-5">
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
var p2p_orm_host = '<?php print_r(getenv('ENV_P2P_ORM_HTTPS_HOST'))?>';

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
            transactionReportDict: {}
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
                    this.loanTrendChart()

                }).catch((err) => {
                    console.log(err);
                })
            }
        },
        loanTrendChart() {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(this.drawLoanTrendChart);
        },
        drawLoanTrendChart() {
            let loanData = [ ['年月份', '學生貸', '上班族貸'] ];

            for (let i = 0; i < this.loanReportDict.date_list.length; i ++) {
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
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(this.drawCaseTrendChart);
        },
        drawCaseTrendChart() {
            let loanData = [ ['年月份', '學生貸', '上班族貸'] ];

            for (let i = 0; i < this.transactionReportDict.date_list.length; i ++) {
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
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(this.drawHouseholdTrendChart);
        },
        drawHouseholdTrendChart() {
            let loanData = [ ['年月份', '學生貸', '上班族貸'] ];

            for (let i = 0; i < this.transactionReportDict.date_list.length; i ++) {
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
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(this.drawAmountTrendChart);
        },
        drawAmountTrendChart() {
            let loanData = [ ['年月份', '學生貸', '上班族貸'] ];

            for (let i = 0; i < this.transactionReportDict.date_list.length; i ++) {
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
    }
});

</script>
<style>
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