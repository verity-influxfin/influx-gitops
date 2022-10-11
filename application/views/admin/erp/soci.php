<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ERP 帳務 - 損益表</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <form class="form-inline" ref="search-form">
                        <div class="form-group">
                            <label class="sr-only" for="start_date">開始日期</label>
                            <input type="text" class="form-control" v-model="searchform.start_date" id="start_date" name="start_date" placeholder="開始日期">
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="end_date">結束日期</label>
                            <input type="text" class="form-control" v-model="searchform.end_date" id="end_date" name="end_date" placeholder="結束日期">
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="investor_id">投資人 ID</label>
                            <input type="text" class="form-control" v-model="searchform.user_id" id="investor_id" name="investor_id" placeholder="投資人 ID">
                        </div>
                        <button type="button" @click="search" class="btn btn-primary">
                            <i class="fa fa-search"></i> 搜尋
                        </button>
                        <button class="btn btn-excel pull-right" type="button" v-on:click="spreadsheet_export" :disabled="is_waiting_response" v-if="table_data.length > 0">
                            <i class="fa fa-file-excel-o"></i> 檔案下載
                        </button>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="info">
                                        <th width="70%">項目</th>
                                        <th width="15%" class="text-right">小計</th>
                                        <th width="15%" class="text-right">合計</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="! table_has_data">
                                        <td colspan="3" class="text-center">沒有資料</td>
                                    </tr>
                                    <template v-else>
                                        <template v-if="has_revenues">
                                            <tr class="active">
                                                <th colspan="3">收益</th>
                                            </tr>
                                            <tr v-for="item in table_data.revenues.items">
                                                <td style="padding-left: 1em;">{{item.title}}</td>
                                                <td class="text-right">{{item.amount | amount}}</td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><strong>收益合計</strong></td>
                                                <td class="text-right"><strong>{{table_data.revenues.amount | amount}}</strong></td>
                                            </tr>
                                        </template>
                                        <template v-if="has_expense">
                                            <tr class="active">
                                                <th colspan="3">費損</th>
                                            </tr>
                                            <tr v-for="item in table_data.expense.items">
                                                <td style="padding-left: 1em;">{{item.title}}</td>
                                                <td class="text-right">{{item.amount | amount}}</td>
                                                <td class="text-right"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"><strong>費損合計</strong></td>
                                                <td class="text-right"><strong>{{table_data.expense.amount | amount}}</strong></td>
                                            </tr>
                                        </template>
                                        <tr class="active">
                                            <td colspan="2"><strong>損益總額</strong></td>
                                            <td class="text-right"><strong>{{table_data.total_amount | amount}}</strong></td>
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