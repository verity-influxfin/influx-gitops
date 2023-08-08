<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ERP 帳務 - 本金攤還表</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div>
                <ul class="nav nav-tabs" id="myTabs" role="tablist">
                    <li role="presentation" class="active">
                        <a role="tab" data-toggle="tab" aria-controls="test2" aria-expanded="true" @click="tab='tab1'">投資人本金攤還表</a>
                    </li>
                    <li role="presentation">
                        <a role="tab" data-toggle="tab" aria-controls="test1" aria-expanded="false" @click="tab='tab2'">本金攤還表底稿</a>
                    </li>
                </ul>
            </div>
            <div class="panel panel-default" v-show="tab == 'tab2'">
                <div class="panel-heading clearfix">
                    <form class="form-inline" ref="search-form" @submit.prevent="doSearch">
                        <div class="row alert alert-info m-3">
                            <div class="col-lg-12">
                                <div>
                                    投資人 ID ,債權 ID 串列 擇一輸入，若兩者皆輸入，則以投資人ID為主<br />
                                    債權 ID 串列以 ',' 分隔，如: 3,4,5
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="sr-only" for="start_date">開始日期</label>
                                    <input type="text" class="form-control" id="start_date" name="start_date" v-model="searchform.start_date" placeholder="開始日期">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="end_date">結束日期</label>
                                    <input type="text" class="form-control" id="end_date" name="end_date" v-model="searchform.end_date" placeholder="結束日期">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="investor_id">投資人 ID</label>
                                    <input type="text" class="form-control" id="investor_id" name="investor_id" v-model="searchform.user_id_int" placeholder="投資人 ID">
                                </div>
                                <div class="form-group w-25">
                                    <label class="sr-only" for="investor_id">債權 ID 串列</label>
                                    <input type="text" class="form-control" id="investment_id_int_list_str" name="investment_id_int_list_str" v-model="searchform.investment_id_int_list_str" placeholder="債權 ID 串列">
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
                            <!-- <code>
                                <pre>
                                {{ replayment_list }}
                                </pre>
                            </code> -->
                            <table class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                    <tr class="info" style="position: sticky;top: 0;">
                                        <th>日期</th>
                                        <th>應收本金</th>
                                        <th>應收利息</th>
                                        <th>本金餘額</th>
                                        <th>逾期本金餘額</th>
                                    </tr>
                                </thead>
                                <tbody v-if="is_waiting_response">
                                    <tr>
                                        <td colspan="22">
                                            <div class="text-center">
                                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <template v-for="item in replayment_list">
                                        <tr class="active">
                                            <th colspan="99">
                                                {{item.date}} - {{eventCode(item.event_code)}} <br>
                                                債權 ID {{item.investment_id_int}}
                                            </th>
                                        </tr>
                                        <template v-for="row in item.rsRow_list">
                                            <tr>
                                                <td style="padding-left: 1em;">{{ row.date }}</td>
                                                <td class="text-right">{{ amount(row.principal_receivable) }}</td>
                                                <td class="text-right">{{ amount(row.interest_receivable) }}</td>
                                                <td class="text-right">{{ amount(row.principal_balance) }}</td>
                                                <td class="text-right">{{ amount(row.delay_principal_balance) }}</td>
                                            </tr>
                                        </template>
                                        <!-- <tr>
                                            <td colspan="2"><strong>收益合計</strong></td>
                                            <td class="text-right"><strong>{{ amount(table_data.revenues.subtotal) }}</strong></td>
                                        </tr> -->
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default" v-show="tab == 'tab1'">
                <div class="panel-heading clearfix">
                    <form class="form-inline" ref="search-form" @submit.prevent="doSearchLatest">
                        <!-- <div class="row alert alert-info m-3">
                            <div class="col-lg-12">
                                <div>
                                    投資人 ID ,債權 ID 串列 擇一輸入，若兩者皆輸入，則以投資人ID為主<br />
                                    債權 ID 串列以 ',' 分隔，如: 3,4,5
                                </div>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="sr-only" for="start_date">開始日期</label>
                                    <input type="text" class="form-control" id="start_date" name="start_date" v-model="searchform.start_date" placeholder="開始日期">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="end_date">結束日期</label>
                                    <input type="text" class="form-control" id="end_date" name="end_date" v-model="searchform.end_date" placeholder="結束日期">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="investor_id">投資人 ID</label>
                                    <input type="text" class="form-control" id="investor_id" name="investor_id" v-model="searchform.user_id_int" placeholder="投資人 ID">
                                </div>
                                <!-- <div class="form-group w-25">
                                    <label class="sr-only" for="investor_id">債權 ID 串列</label>
                                    <input type="text" class="form-control" id="investment_id_int_list_str" name="investment_id_int_list_str" v-model="searchform.investment_id_int_list_str" placeholder="債權 ID 串列">
                                </div> -->
                                <button type="submit" class="btn btn-primary" :disabled="is_waiting_response">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
                                <button class="btn btn-primary pull-right" type="button" :disabled="is_waiting_response" @click="downloadExcelLatest">
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
                                    <tr class="info" style="position: sticky;top: 0px;">
                                        <th colspan="99">
                                            <div class="d-flex aic">
                                                <div class="mr-4">正常案本金攤還表</div>
                                                <button class="btn btn-default" @click="hide = !hide">
                                                    <i class="fa" :class="eyeIcon"></i>
                                                </button>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr class="info" style="position: sticky;top: 38px;">
                                        <th>日期</th>
                                        <th>應收本金</th>
                                        <th>應收利息</th>
                                        <th>本金餘額</th>
                                    </tr>
                                </thead>
                                <tbody v-if="is_waiting_response">
                                    <tr>
                                        <td colspan="22">
                                            <div class="text-center">
                                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <template v-for="row in filteredReplaymentListLatest.stacked_coded1_rsRow_list">
                                        <tr>
                                            <td style="padding-left: 1em;">{{ row.date }}</td>
                                            <td class="text-right">{{ amount(row.principal_receivable) }}</td>
                                            <td class="text-right">{{ amount(row.interest_receivable) }}</td>
                                            <td class="text-right">{{ amount(row.principal_balance) }}</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <div></div>
                            <table class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                    <tr class="info" style="position: sticky;top: 0px;">
                                        <th colspan="99">
                                            <div class="d-flex aic">
                                                <div class="mr-4">逾期案本金攤還表</div>
                                                <button class="btn btn-default" @click="hide = !hide">
                                                    <i class="fa" :class="eyeIcon"></i>
                                                </button>
                                            </div>
                                        </th>
                                    </tr>
                                    <tr class="info" style="position: sticky;top: 38px;">
                                        <th>日期</th>
                                        <th>應收利息</th>
                                        <th>逾期本金餘額</th>
                                    </tr>
                                </thead>
                                <tbody v-if="is_waiting_response">
                                    <tr>
                                        <td colspan="22">
                                            <div class="text-center">
                                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <template v-for="row in filteredReplaymentListLatest.stacked_coded2_rsRow_list">
                                        <tr>
                                            <td style="padding-left: 1em;">{{ row.date }}</td>
                                            <td class="text-right">{{ amount(row.interest_receivable) }}</td>
                                            <td class="text-right">{{ amount(row.delay_principal_balance) }}</td>
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
