<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ERP 帳務 - 發票資料查詢</h1>
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
                                    <input type="text" class="form-control" id="start_date" name="start_date" v-model="searchform.start_date" placeholder="開始日期">
                                </div>
                                <div class="form-group">
                                    <label class="sr-only" for="end_date">結束日期</label>
                                    <input type="text" class="form-control" id="end_date" name="end_date" v-model="searchform.end_date" placeholder="結束日期">
                                </div>
                                <div class="form-group">
                                    <label for="investor_id" class="m-2">收據類型:</label>
                                    <select class="form-control" id="form_intEnum" name="form_intEnum" v-model="searchform.form_intEnum" placeholder="收據類型">
                                        <option :value="0">普匯租賃</option>
                                        <option :value="1">普匯金融</option>
                                    </select>
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
                    <ul class="nav nav-tabs" id="myTabs" role="tablist">
                        <li role="presentation" class="active">
                            <a role="tab" data-toggle="tab" aria-controls="test1" aria-expanded="true" @click="tab='tab1'">發票資料</a>
                        </li>
                        <li role="presentation">
                            <a role="tab" data-toggle="tab" aria-controls="test2" aria-expanded="false" @click="tab='tab2'">金額明細</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" width="100%" v-show="tab==='tab1'">
                                <!-- a_sheet -->
                                <thead>
                                    <tr>
                                        <th v-for="(item,index) in a_sheet.column" :key="index">{{item}}</th>
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
                                    <tr v-if="a_sheet.tableData.length < 1">
                                        <td colspan="99" class="text-center">沒有資料</td>
                                    </tr>
                                    <template v-else>
                                        <tr v-for="(item,index) in a_sheet.tableData" :key="index">
                                            <td v-for="(value,key) in item" :key="key">{{ value }}</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <table class="table table-striped table-bordered table-hover" width="100%" v-show="tab==='tab2'">
                                <!-- b_sheet -->
                                <thead>
                                    <tr>
                                        <th v-for="(item,index) in b_sheet.column" :key="index">{{item}}</th>
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
                                    <tr v-if="b_sheet.tableData.length < 1">
                                        <td colspan="99" class="text-center">沒有資料</td>
                                    </tr>
                                    <template v-else>
                                        <tr v-for="(item,index) in b_sheet.tableData" :key="index">
                                            <td v-for="(value,key) in item" :key="key">{{ value }}</td>
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
