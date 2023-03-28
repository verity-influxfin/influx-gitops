<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">查詢協議清償表</h1>
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
                                    <input type="text" class="form-control" id="user_id_int" name="user_id_int" v-model="searchform.user_id_int" placeholder="借款人 ID">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="total_repayment_amount_int" name="user_id_int" v-model="searchform.total_repayment_amount_int" placeholder="清償金額">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="sum_agreement_penalty_amount_int" name="user_id_int" v-model="searchform.sum_agreement_penalty_amount_int" placeholder="協議違約金">
                                </div>
                                <button type="submit" class="btn btn-primary" :disabled="is_waiting_response">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
                                <button type="button" class="btn btn-primary" :disabled="is_waiting_response" @click="downloadExcel">
                                    <i class="fa fa-file-excel-o"></i> 檔案下載
                                </button>
                                <button type="button" v-if="repayment_agreement.user_id_int" class="btn btn-warning" :disabled="is_waiting_response" @click="confirmRepayment">
                                    <i class="fa fa-check-circle"></i> 確立清償協議表
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" v-if="is_waiting_response">
                                <tbody>
                                    <tr>
                                        <td colspan="30">
                                            <div class="text-center">
                                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table table-striped table-bordered table-hover" v-else-if="!repayment_agreement.user_id_int">
                                <tr>
                                    <td colspan="30" class="text-center">沒有資料</td>
                                </tr>
                            </table>
                            <div v-else>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr colspan=6>
                                            <h4>借款人基本資料</h4>
                                        </tr>
                                        <tr>
                                            <th>借款人 ID</th>
                                            <th>應收本金 總額</th>
                                            <th>應收違約金 總額</th>
                                            <th>協議違約金 總額</th>
                                            <th>總回款金額</th>
                                            <th>協議金額</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ repayment_agreement.user_id_int }}</td>
                                            <td>{{ amount(repayment_agreement.sum_receivable_principal_amount_int) }}</td>
                                            <td>{{ amount(repayment_agreement.sum_receivable_penalty_amount_int) }}</td>
                                            <td>{{ amount(repayment_agreement.sum_agreement_penalty_amount_int) }}</td>
                                            <td>{{ amount(repayment_agreement.total_repayment_amount_int) }}</td>
                                            <td>{{ amount(repayment_agreement.agreement_amount_int) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-striped table-bordered table-hover mt-4">
                                    <thead>
                                        <tr>
                                            <th>案號</th>
                                            <th>應收違約金</th>
                                            <th>違約金比例</th>
                                            <th>協議違約金</th>
                                            <th>投資人 ID</th>
                                            <th>應收本金</th>
                                            <th>應收約定利息</th>
                                            <th>應收延滯息</th>
                                            <th>應收回款手續費</th>
                                            <th>比例</th>
                                            <th>受償金額</th>
                                            <th>預估受償本金</th>
                                            <th>預估受償約息</th>
                                            <th>預估受償延滯息</th>
                                            <th>預估回款手續費</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="row in repayment_agreement.raTarget_list">
                                            <tr v-for="(item, itemIndex) in row.row_list">
                                                <td v-if="itemIndex === 0" :rowspan="row.row_list.length">{{row.target_no_str}}</td>
                                                <td v-if="itemIndex === 0" :rowspan="row.row_list.length">{{amount(row.receivable_penalty_amount_int)}}</td>
                                                <td v-if="itemIndex === 0" :rowspan="row.row_list.length" :title="row.receivable_penalty_ratio">{{formatRatio(row.receivable_penalty_ratio)}}</td>
                                                <td v-if="itemIndex === 0" :rowspan="row.row_list.length">{{amount(row.agreement_penalty_amount_int)}}</td>
                                                <td>{{item.investor_id_int}}</td>
                                                <td>{{amount(item.receivable_principal_amount_int)}}</td>
                                                <td>{{amount(item.receivable_agreed_interest_amount_int)}}</td>
                                                <td>{{amount(item.receivable_delay_interest_amount_int)}}</td>
                                                <td>{{amount(item.receivable_repayment_fee_amount_int)}}</td>
                                                <td :title="item.receivable_principal_ratio">{{formatRatio(item.receivable_principal_ratio)}}</td>
                                                <td>{{amount(item.final_get_amount_int)}}</td>
                                                <td>{{amount(item.final_get_principal_amount_int)}}</td>
                                                <td>{{amount(item.final_get_agreed_interest_amount_int)}}</td>
                                                <td>{{amount(item.final_get_delay_interest_amount_int)}}</td>
                                                <td>{{amount(item.final_get_repayment_fee_amount_int)}}</td>
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
</div>
</div>

<?php $this->load->view('admin/_footer'); ?>
