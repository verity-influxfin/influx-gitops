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

    <div class="panel panel-default">
        <div class="panel-heading" style="background-color: #ddd;">
            <div class="row" style="height: 40px;">
                <div class="col-md-12">
                    <label>請選擇欲輸出的報表：</label>
                </div>
            </div>    
            <div class="row" style="height: 40px;">
                <div class="col-md-2">
                    <input 
                        type="checkbox" 
                        v-model="loanReportSelect.student"
                    >
                    <span>申貸戶統計-學生貸</span>
                </div>
                <div class="col-md-2">
                    <input 
                        type="checkbox" 
                        v-model="loanReportSelect.work"
                    >
                    <span>申貸戶統計-上班族貸</span>
                </div>
                <div class="col-md-2">
                    <input 
                        type="checkbox" 
                        v-model="loanReportSelect.trend"
                    >
                    <span>申貸戶趨勢圖</span>
                </div>
                <div class="col-md-2">
                    <input 
                        type="checkbox" 
                        v-model="loanReportSelect.statistics"
                    >
                    <span>申貸數據統計</span>
                </div>
            </div>
            <div class="row" style="height: 40px;">
                <div class="col-md-3">
                    <label>案件申請時間：<label>
                    <input
                        class="searchDt"
                        type="date" 
                        v-model="start_date"
                        min="2023-01-01" 
                        max="2030-12-31"
                        placeholder="不指定區間"
                    >  
                </div>
                <div class="col-md-1">
                    <label style="margin-top: 5px;">到：<label>
                </div>
                <div class="col-md-3">
                    <input
                        class="searchDt"
                        type="date" 
                        v-model="end_date"
                        min="2023-01-01" 
                        max="2030-12-31"
                        placeholder="不指定區間"
                    >
                </div>
            </div>
            <div class="row" style="height: 40px;">
                <div class="col-md-3">
                    <button 
                        class="searchBtn"
                        @click="goSearch()"
                    >查詢</button>
                    <button class="searchBtn ml-3">匯出報表</button>
                </div>
            </div>
        </div>

        <div class="panel-body mt-5" v-show="tab == 'tab1'">
            <template v-if="loanReportSelect.student">
                <h3>申貸戶統計 - 學生貸</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td
                                    class="tdTitleBlue" 
                                    v-for="item in loanReportDict.student_loan.date_list" 
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

            <template v-if="loanReportSelect.work">
                <h3 class="mt-3">申貸戶統計 - 上班族貸</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" width="100%">
                        <tbody>
                            <tr>
                                <td class="tdTitleBlue">年月份</td>
                                <td
                                    class="tdTitleBlue" 
                                    v-for="item in loanReportDict.work_loan.date_list" 
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

            <template v-if="loanReportSelect.trend">
                <div id="curve_chart" style="width: 900px; height: 500px"></div>
            </template>
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
            loanReportSelect: {
                student: false,
                work: false,
                trend: false,
                statistics: false
            },
            start_date: '',
            end_date: '',
            loanReportDict: {}
        }
    },
    methods: {
        goSearch() {
            if (this.start_date == '' || this.end_date == '') {
                alert('請選擇查詢時間區間。')
            } else {
                let params = {
                    start_dt: this.start_date,
                    end_dt: this.end_date
                }

                axios.post(`${p2p_orm_host}/personal_finance_report/apply_loan`, this.loanReportSelect, { params })
                .then((res) => {
                    this.loanReportDict = res.data;
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

            for (let i = 0; i < this.loanReportDict.trend.date_list.length; i ++) {
                let trendList = [];

                trendList.push(this.loanReportDict.trend.date_list[i]);
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

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            chart.draw(data, options);
        }
    }
});

</script>
<style>
.searchDt {
    width: 150px;
    height: 30px;
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