<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ERP 帳務 - 日記簿</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <form class="form-inline" ref="search-form">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="sr-only" for="role">角色</label>
                                    <select class="form-control" v-model="searchform.role" disabled>
                                        <option value="investor" selected>投資人</option>
                                        <option value="borrower">借款人</option>
                                        <option value="platform">平台</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin-right:1em;">
                                    <label class="sr-only" for="user_id">用戶 ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" v-model="searchform.user_id" placeholder="用戶 ID">
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control" id="year" name="year" v-model="searchform.year" placeholder="年">
                                    <label for="year">年</label>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" id="month" name="month" v-model="searchform.month" placeholder="月">
                                        <option disabled>請選擇月份</option>
                                        <option value="01">01</option>
                                        <option value="02">02</option>
                                        <option value="03">03</option>
                                        <option value="04">04</option>
                                        <option value="05">05</option>
                                        <option value="06">06</option>
                                        <option value="07">07</option>
                                        <option value="08">08</option>
                                        <option value="09">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                    <label for="month">月</label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="set_duration" name="set_duration" v-model="set_duration"> 搜尋期間
                                    </label>
                                </div>
                                <template v-if="set_duration">
                                    <div class="form-group">
                                        <input type="number" class="form-control" id="end_year" name="end_year" v-model="searchform.end_year" placeholder="年">
                                        <label for="end_year">年</label>
                                    </div>
                                    <div class="form-group">
                                        <select class="form-control" id="end_month" name="end_month" v-model="searchform.end_month" placeholder="月">
                                            <option disabled>請選擇月份</option>
                                            <option value="01">01</option>
                                            <option value="02">02</option>
                                            <option value="03">03</option>
                                            <option value="04">04</option>
                                            <option value="05">05</option>
                                            <option value="06">06</option>
                                            <option value="07">07</option>
                                            <option value="08">08</option>
                                            <option value="09">09</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                        <label for="end_month">月</label>
                                    </div>
                                </template>
                                <button type="button" class="btn btn-primary" v-on:click="search">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
                                <button class="btn btn-excel pull-right" type="button" v-on:click="spreadsheet_export" :disabled="is_waiting_response" v-if="table_data.length > 0">
                                    <i class="fa fa-file-excel-o"></i> 檔案下載
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <span class="label label-primary" v-if="filter_grade!=''">
                        {{filter_grade}}
                        <button type="button" @click="filter_grade=''">
                            <i class="fa fa-times"></i>
                        </button>
                    </span>
                    <div class="table-responsive">
                        <table class="table table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th>日期</th>
                                    <th>傳票編號</th>
                                    <th>會計科目</th>
                                    <th>科目名稱</th>
                                    <th>摘要</th>
                                    <th class="amount">借方金額</th>
                                    <th class="amount">貸方金額</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="active" v-if="table_data.length < 1">
                                    <td colspan="8" class="text-center">沒有資料</td>
                                </tr>
                                <template v-if="table_data.length > 0">
                                    <tr v-for="(item, index) in entry_filter"
                                        :class="{'debit': item.is_debit, 'credit': ! item.is_debit}" :title="item.remark">

                                        <!-- 日期 -->
                                        <td>{{item.date}}</td>

                                        <!-- 傳票編號 -->
                                        <td v-if="item.voucher_no != null">{{item.voucher_no}}</td>
                                        <td v-if="item.voucher_no == null"> - </td>

                                        <!-- 會計科目 -->
                                        <td>
                                            <button class="btn btn-link btn-sm" type="button" @click="filter_grade=item.grade">{{item.grade}}</button>
                                        </td>

                                        <!-- 科目名稱 -->
                                        <td>{{item.account}}</td>

                                        <!-- 摘要 -->
                                        <td>{{item.details}}</td>

                                        <template v-if="item.is_debit">
                                            <td class="amount">{{item.amount | amount}}</td>
                                            <td class="amount"></td>
                                        </template>
                                        <template v-if="!item.is_debit">
                                            <td class="amount"></td>
                                            <td class="amount">{{item.amount | amount}}</td>
                                        </template>

                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    
                                </template>
                            </tbody>
                            <tfoot>
                                <tr class="active" v-if="table_data.length > 0">
                                    <td colspan="5" class="text-right">合計</td>
                                    <td class="amount">{{debit_total | amount}}</td>
                                    <td class="amount">{{credit_total | amount}}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style type="text/css">

tr {
    height: 3rem;
}

.text-secondary {
    color: #999999;
}
.amount {
    padding: 8px 2rem !important;
    min-width: 10rem;
    text-align: right;
}
.label > button {
    background: none;
    border: 0;
}
</style>
<?php $this->load->view('admin/_footer'); ?>