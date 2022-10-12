<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ERP 帳務 - 債權明細表</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <form class="form-inline" ref="search-form" @submit.prevent="doSearch">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="sr-only" for="start_date">開始日期</label>
                                    <input type="text" class="form-control" id="start_date" name="start_date" v-model="searchform.start_date" placeholder="開始日期" required>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="end_date">結束日期</label>
                                    <input type="text" class="form-control" id="end_date" name="end_date" v-model="searchform.end_date" placeholder="結束日期" required>
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="investor_id">投資人 ID</label>
                                    <input type="text" class="form-control" id="investor_id" name="investor_id" v-model="searchform.user_id_int" placeholder="投資人 ID" required>
                                </div>
                                <button type="submit" class="btn btn-primary" :disabled="is_waiting_response">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
                                <button class="btn btn-primary pull-right" type="button" :disabled="is_waiting_response" @click="downloadExcel">
                                    <i class="fa fa-file-excel-o"></i> 檔案下載
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th>產品名稱</th>
                                        <th>債務案號</th>
                                        <th>借款人ID</th>
                                        <th>核准額度</th>
                                        <th>核准信評</th>
                                        <th>最新信評</th>
                                        <th>學校/公司</th>
                                        <th>科系</th>
                                        <th>期數</th>
                                        <th>利率</th>
                                        <th>計息方式</th>
                                        <th>放款日期</th>
                                        <th>案件狀態</th>
                                        <th>債權案號</th>
                                        <th>債權人 ID</th>
                                        <th>債權來源</th>
                                        <th>放款金額</th>
                                        <th>債權狀態</th>
                                        <th>逾期天數</th>
                                        <th>債權本金餘額</th>
                                        <th>合約成立時間</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="assets_sheet.length < 1">
                                        <td colspan="21" class="text-center">沒有資料</td>
                                    </tr>
                                    <template v-else>
                                        <tr v-for="item in assets_sheet" :key="item.target_no">
                                            <td>{{ item.product_name }}</td>
                                            <td>{{ item.target_no }}</td>
                                            <td>{{ item.target_user_id }}</td>
                                            <td>{{ amount(item.target_loan_amount) }}</td>
                                            <td>{{ item.approved_credits_level }}</td>
                                            <td>{{ item.latest_credits_level }}</td>
                                            <td>{{ item.school_or_company }}</td>
                                            <td>{{ item.major }}</td>
                                            <td>{{ item.instalment }}</td>
                                            <td>{{ item.interest_rate }}</td>
                                            <td>{{ item.interest_method }}</td>
                                            <td>{{ item.loan_date }}</td>
                                            <td>{{ item.case_status }}</td>
                                            <td>{{ item.target_id }}</td>
                                            <td>{{ item.investment_user_id }}</td>
                                            <td></td>
                                            <td>{{ amount(item.investment_loan_amount) }}</td>
                                            <td>{{ item.investment_status }}</td>
                                            <td>{{ item.delay_days }}</td>
                                            <td>{{ amount(item.principal_balance) }}</td>
                                            <td>{{ formatTime(item.contract_created_at) }}</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/_footer'); ?>
