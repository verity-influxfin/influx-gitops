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
                    <form class="form-inline" ref="search-form" @submit.prevent="doSearch">
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
                            <input type="text" class="form-control" v-model="searchform.user_id_int" id="investor_id" name="investor_id" placeholder="投資人 ID" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-search"></i> 搜尋
                        </button>
                        <button class="btn btn-primary pull-right" type="button" @click="downloadExcel" :disabled="is_waiting_response">
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
                                    <tr v-if="! table_has_data">
                                        <td colspan="3" class="text-center">沒有資料</td>
                                    </tr>
                                    <template v-else>
                                        <template v-if="has_revenues">
                                            <tr class="active">
                                                <th colspan="3">收益</th>
                                            </tr>
                                            <!-- 第一行 -->
                                            <template v-for="item in table_data.revenues.subjectGroup_list">
                                                <tr>
                                                    <td style="padding-left: 1em;">{{ getListTitle(item) }}</td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right">{{ amount(item.subtotal) }}</td>
                                                </tr>
                                                <tr v-for="row in item.subject_list">
                                                    <td style="padding-left: 2em;">{{ row.name }}</td>
                                                    <td class="text-right">{{ amount(row.amount) }}</td>
                                                    <td class="text-right"></td>
                                                </tr>
                                            </template>
                                            <tr>
                                                <td colspan="2"><strong>收益合計</strong></td>
                                                <td class="text-right"><strong>{{ amount(table_data.revenues.subtotal) }}</strong></td>
                                            </tr>
                                        </template>
                                        <template v-if="has_expense">
                                            <tr class="active">
                                                <th colspan="3">費損</th>
                                            </tr>
                                            <!-- 第一行 -->
                                            <template v-for="item in table_data.expenses.subjectGroup_list">
                                                <tr>
                                                    <td style="padding-left: 1em;">{{ getListTitle(item) }}</td>
                                                    <td class="text-right"></td>
                                                    <td class="text-right">{{ amount(item.subtotal) }}</td>
                                                </tr>
                                                <tr v-for="row in item.subject_list">
                                                    <td style="padding-left: 2em;">{{ row.name }}</td>
                                                    <td class="text-right">{{ amount(row.amount) }}</td>
                                                    <td class="text-right"></td>
                                                </tr>
                                            </template>
                                            <tr>
                                                <td colspan="2"><strong>費損合計</strong></td>
                                                <td class="text-right"><strong>{{ amount(table_data.expenses.subtotal) }}</strong></td>
                                            </tr>
                                        </template>
                                        <tr class="active">
                                            <td colspan="2"><strong>損益總額</strong></td>
                                            <td class="text-right"><strong>{{ amount(table_data.total) }}</strong></td>
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
