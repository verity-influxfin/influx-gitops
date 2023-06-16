<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ERP 帳務 - 開帳表</h1>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary active" @click="lang = 'tw'">
                    <input type="radio" name="options" id="option1" autocomplete="off"  checked>中文
                </label>
                <label class="btn btn-primary" @click="lang = 'en'">
                    <input type="radio" name="options" id="option2" autocomplete="off"> 英文
                </label>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-12">
            <div>
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li role="presentation" class="active">
                        <a role="tab" data-toggle="tab" aria-controls="test1" aria-expanded="true" @click="tab='b_dict'">開帳表字典</a>
                    </li>
                    <li role="presentation">
                        <a role="tab" data-toggle="tab" aria-controls="test2" aria-expanded="false" @click="tab='b_diff'">開帳表差異</a>
                    </li>
                </ul>
            </div>
            <div class="panel panel-default" v-show="tab == 'b_dict'">
                <div class="panel-heading clearfix">
                    <form class="form-inline" ref="search-form" @submit.prevent="doDictSearch">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="sr-only" for="date1">結束日期</label>
                                    <input type="text" class="form-control" id="date1" name="end_date" v-model="searchform.end_date" placeholder="結束日期">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="investor_id">投資人 ID</label>
                                    <input type="text" class="form-control" id="investor_id" name="investor_id" v-model="searchform.user_id_int" placeholder="投資人 ID">
                                </div>
                                <button type="submit" class="btn btn-primary" :disabled="is_waiting_response">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div v-if="is_waiting_response" class="w-100">
                            <div class="text-center">
                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <template v-if="dictData.user_id_int">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>科目</th>
                                                    <th>金額</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ langKey.left.bank_balance }}</td>
                                                    <td>{{ format(dictData.left.bank_balance) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.virtual_account_balance }}</td>
                                                    <td>{{ format(dictData.left.virtual_account_balance) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.principal_balance }}</td>
                                                    <td>{{ format(dictData.left.principal_balance) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.delay_principal_balance }}</td>
                                                    <td>{{ format(dictData.left.delay_principal_balance) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.transfer_in_loss }}</td>
                                                    <td>{{ format(dictData.left.transfer_in_loss) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.transfer_out_loss }}</td>
                                                    <td>{{ format(dictData.left.transfer_out_loss) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.platform_fee }}</td>
                                                    <td>{{ format(dictData.left.platform_fee) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.transfer_out_fee }}</td>
                                                    <td>{{ format(dictData.left.transfer_out_fee) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.donation_expense }}</td>
                                                    <td>{{ format(dictData.left.donation_expense) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.bank_verify_fee }}</td>
                                                    <td>{{ format(dictData.left.bank_verify_fee) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.legal_collection_platform_fee }}</td>
                                                    <td>{{ format(dictData.left.legal_collection_platform_fee) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.total }}</td>
                                                    <td>{{ format(dictData.left.total) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.other }}</td>
                                                    <td v-if="dictData.fixed_left.other">{{ format(dictData.fixed_left.other) }}</td>
                                                    <td v-else>0</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>科目</th>
                                                <th>金額</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ langKey.right.sales_tax }}</td>
                                                <td>{{ format(dictData.right.sales_tax) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.interest_income }}</td>
                                                <td>{{ format(dictData.right.interest_income) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.transfer_in_gain }}</td>
                                                <td>{{ format(dictData.right.transfer_in_gain) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.prepayment_compensation }}</td>
                                                <td>{{ format(dictData.right.prepayment_compensation) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.delay_interest_income }}</td>
                                                <td>{{ format(dictData.right.delay_interest_income) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.transfer_out_gain }}</td>
                                                <td>{{ format(dictData.right.transfer_out_gain) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.total }}</td>
                                                <td>{{ format(dictData.right.total) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.other }}</td>
                                                <td v-if="dictData.fixed_right.other">{{ format(dictData.fixed_right.other) }}</td>
                                                <td v-else>0</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>科目</th>
                                                <th>金額</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ langKey.balance }}</td>
                                                <td>{{ format(dictData.balance) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.all_principal_repaid }}</td>
                                                <td>{{ format(dictData.all_principal_repaid) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.all_loan_amount }}</td>
                                                <td>{{ format(dictData.all_loan_amount) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.all_interest_receivable }}</td>
                                                <td>{{ format(dictData.all_interest_receivable) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.all_delay_interest_receivable }}</td>
                                                <td>{{ format(dictData.all_delay_interest_receivable) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.adjust_different }}</td>
                                                <td>{{ format(dictData.fixed_balance) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <div class="panel panel-default" v-show="tab == 'b_diff'">
                <div class="panel-heading clearfix">
                    <form class="form-inline" ref="search-form" @submit.prevent="doDiffSearch">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="sr-only" for="date2">日期(前)</label>
                                    <input type="text" class="form-control" id="date2" name="start_date" v-model="searchform.start_date" placeholder="日期(前)">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="date3">日期(後)</label>
                                    <input type="text" class="form-control" id="date3" name="end_date" v-model="searchform.end_date" placeholder="日期(後)">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="investor_id">投資人 ID</label>
                                    <input type="text" class="form-control" id="investor_id" name="investor_id" v-model="searchform.user_id_int" placeholder="投資人 ID">
                                </div>
                                <button type="submit" class="btn btn-primary" :disabled="is_waiting_response">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div v-if="is_waiting_response" class="w-100">
                            <div class="text-center">
                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <template v-if="diffData.user_id_int">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>科目</th>
                                                    <th>金額 ({{ diffData.end_date.before }})</th>
                                                    <th>金額 ({{ diffData.end_date.after }})</th>
                                                    <th>金額(差異)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ langKey.left.bank_balance }}</td>
                                                    <td>{{ format(diffData.left.bank_balance.before) }}</td>
                                                    <td>{{ format(diffData.left.bank_balance.after) }}</td>
                                                    <td :class="textClass(diffData.left.bank_balance.diff)">{{ format(diffData.left.bank_balance.diff) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.virtual_account_balance }}</td>
                                                    <td>{{ format(diffData.left.virtual_account_balance.before) }}</td>
                                                    <td>{{ format(diffData.left.virtual_account_balance.after) }}</td>
                                                    <td :class="textClass(diffData.left.virtual_account_balance.diff)">{{ format(diffData.left.virtual_account_balance.diff) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.principal_balance }}</td>
                                                    <td>{{ format(diffData.left.principal_balance.before) }}</td>
                                                    <td>{{ format(diffData.left.principal_balance.after) }}</td>
                                                    <td :class="textClass(diffData.left.principal_balance.diff)">{{ format(diffData.left.principal_balance.diff) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.delay_principal_balance }}</td>
                                                    <td>{{ format(diffData.left.delay_principal_balance.before) }}</td>
                                                    <td>{{ format(diffData.left.delay_principal_balance.after) }}</td>
                                                    <td :class="textClass(diffData.left.delay_principal_balance.diff)">{{ format(diffData.left.delay_principal_balance.diff) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.transfer_in_loss }}</td>
                                                    <td>{{ format(diffData.left.transfer_in_loss.before) }}</td>
                                                    <td>{{ format(diffData.left.transfer_in_loss.after) }}</td>
                                                    <td :class="textClass(diffData.left.transfer_in_loss.diff)">{{ format(diffData.left.transfer_in_loss.diff) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.transfer_out_loss }}</td>
                                                    <td>{{ format(diffData.left.transfer_out_loss.before) }}</td>
                                                    <td>{{ format(diffData.left.transfer_out_loss.after) }}</td>
                                                    <td :class="textClass(diffData.left.transfer_out_loss.diff)">{{ format(diffData.left.transfer_out_loss.diff) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.platform_fee }}</td>
                                                    <td>{{ format(diffData.left.platform_fee.before) }}</td>
                                                    <td>{{ format(diffData.left.platform_fee.after) }}</td>
                                                    <td :class="textClass(diffData.left.platform_fee.diff)">{{ format(diffData.left.platform_fee.diff) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.transfer_out_fee }}</td>
                                                    <td>{{ format(diffData.left.transfer_out_fee.before) }}</td>
                                                    <td>{{ format(diffData.left.transfer_out_fee.after) }}</td>
                                                    <td :class="textClass(diffData.left.transfer_out_fee.diff)">{{ format(diffData.left.transfer_out_fee.diff) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.donation_expense }}</td>
                                                    <td>{{ format(diffData.left.donation_expense.before) }}</td>
                                                    <td>{{ format(diffData.left.donation_expense.after) }}</td>
                                                    <td :class="textClass(diffData.left.donation_expense.diff)">{{ format(diffData.left.donation_expense.diff) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.bank_verify_fee }}</td>
                                                    <td>{{ format(diffData.left.bank_verify_fee.before) }}</td>
                                                    <td>{{ format(diffData.left.bank_verify_fee.after) }}</td>
                                                    <td :class="textClass(diffData.left.bank_verify_fee.diff)">{{ format(diffData.left.bank_verify_fee.diff) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ langKey.left.legal_collection_platform_fee }}</td>
                                                    <td>{{ format(diffData.left.legal_collection_platform_fee.before) }}</td>
                                                    <td>{{ format(diffData.left.legal_collection_platform_fee.after) }}</td>
                                                    <td :class="textClass(diffData.left.legal_collection_platform_fee.diff)">{{ format(diffData.left.legal_collection_platform_fee.diff) }}</td>
                                                <tr>
                                                    <td>{{ langKey.left.total }}</td>
                                                    <td>{{ format(diffData.left.total.before) }}</td>
                                                    <td>{{ format(diffData.left.total.after) }}</td>
                                                    <td :class="textClass(diffData.left.total.diff)">{{ format(diffData.left.total.diff) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>科目</th>
                                                <th>金額 ({{ diffData.end_date.before }})</th>
                                                <th>金額 ({{ diffData.end_date.after }})</th>
                                                <th>金額(差異)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ langKey.right.sales_tax }}</td>
                                                <td>{{ format(diffData.right.sales_tax.before) }}</td>
                                                <td>{{ format(diffData.right.sales_tax.after) }}</td>
                                                <td :class="textClass(diffData.right.sales_tax.diff)">{{ format(diffData.right.sales_tax.diff) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.interest_income }}</td>
                                                <td>{{ format(diffData.right.interest_income.before) }}</td>
                                                <td>{{ format(diffData.right.interest_income.after) }}</td>
                                                <td :class="textClass(diffData.right.interest_income.diff)">{{ format(diffData.right.interest_income.diff) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.transfer_in_gain }}</td>
                                                <td>{{ format(diffData.right.transfer_in_gain.before) }}</td>
                                                <td>{{ format(diffData.right.transfer_in_gain.after) }}</td>
                                                <td :class="textClass(diffData.right.transfer_in_gain.diff)">{{ format(diffData.right.transfer_in_gain.diff) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.prepayment_compensation }}</td>
                                                <td>{{ format(diffData.right.prepayment_compensation.before) }}</td>
                                                <td>{{ format(diffData.right.prepayment_compensation.after) }}</td>
                                                <td :class="textClass(diffData.right.prepayment_compensation.diff)">{{ format(diffData.right.prepayment_compensation.diff) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.delay_interest_income }}</td>
                                                <td>{{ format(diffData.right.delay_interest_income.before) }}</td>
                                                <td>{{ format(diffData.right.delay_interest_income.after) }}</td>
                                                <td :class="textClass(diffData.right.delay_interest_income.diff)">{{ format(diffData.right.delay_interest_income.diff) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.transfer_out_gain }}</td>
                                                <td>{{ format(diffData.right.transfer_out_gain.before) }}</td>
                                                <td>{{ format(diffData.right.transfer_out_gain.after) }}</td>
                                                <td :class="textClass(diffData.right.transfer_out_gain.diff)">{{ format(diffData.right.transfer_out_gain.diff) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.right.total }}</td>
                                                <td>{{ format(diffData.right.total.before) }}</td>
                                                <td>{{ format(diffData.right.total.after) }}</td>
                                                <td :class="textClass(diffData.right.total.diff)">{{ format(diffData.right.total.diff) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>科目</th>
                                                <th>金額 ({{ diffData.end_date.before }})</th>
                                                <th>金額 ({{ diffData.end_date.after }})</th>
                                                <th>金額(差異)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ langKey.balance }}</td>
                                                <td>{{ format(diffData.balance.before) }}</td>
                                                <td>{{ format(diffData.balance.after) }}</td>
                                                <td :class="textClass(diffData.balance.diff)">{{ format(diffData.balance.diff) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.all_principal_repaid }}</td>
                                                <td>{{ format(diffData.all_principal_repaid.before) }}</td>
                                                <td>{{ format(diffData.all_principal_repaid.after) }}</td>
                                                <td :class="textClass(diffData.all_principal_repaid.diff)">{{ format(diffData.all_principal_repaid.diff) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.all_loan_amount }}</td>
                                                <td>{{ format(diffData.all_loan_amount.before) }}</td>
                                                <td>{{ format(diffData.all_loan_amount.after) }}</td>
                                                <td :class="textClass(diffData.all_loan_amount.diff)">{{ format(diffData.all_loan_amount.diff) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.all_interest_receivable }}</td>
                                                <td>{{ format(diffData.all_interest_receivable.before) }}</td>
                                                <td>{{ format(diffData.all_interest_receivable.after) }}</td>
                                                <td :class="textClass(diffData.all_interest_receivable.diff)">{{ format(diffData.all_interest_receivable.diff) }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ langKey.all_delay_interest_receivable }}</td>
                                                <td>{{ format(diffData.all_delay_interest_receivable.before) }}</td>
                                                <td>{{ format(diffData.all_delay_interest_receivable.after) }}</td>
                                                <td :class="textClass(diffData.all_delay_interest_receivable.diff)">{{ format(diffData.all_delay_interest_receivable.diff) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/_footer'); ?>
