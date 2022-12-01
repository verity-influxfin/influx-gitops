<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ERP 帳務 - 分類帳</h1>
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
                                <div class="form-group">
                                    <label class="sr-only" for="user_id">投資人 ID</label>
                                    <input type="text" class="form-control" id="user_id" name="user_id" v-model="searchform.user_id" placeholder="投資人 ID">
                                </div>
                                <button type="button" class="btn btn-primary" v-on:click="search">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
                                <!-- <button class="btn btn-excel pull-right" type="button" v-on:click="spreadsheet_export" :disabled="is_waiting_response" v-if="table_data.length > 0">
                                    <i class="fa fa-file-excel-o"></i> 檔案下載
                                </button> -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th>日期</th>
                                    <th>傳票編號</th>
                                    <th>摘要</th>
                                    <th class="amount">借方金額</th>
                                    <th class="amount">貸方金額</th>
                                    <th class="amount">餘額</th>
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
                                <tr class="active" v-if="table_data.length < 1">
                                    <td colspan="6" class="text-center">沒有資料</td>
                                </tr>
                                <template v-if="table_data.length > 0" v-for="(item, index) in table_data">
                                    <tr><td colspan="6"></td></tr>
                                    <tr class="active">
                                        <th>{{item.grade}}</th>
                                        <th>{{item.account}}</th>
                                        <th></th>
                                        <th colspan="3" class="amount">前期結餘: {{item.preliminary_balance | amount}}</th>
                                    </tr>

                                    <tr v-for="(iit, index) in item.items">

                                        <!-- 日期 -->
                                        <td>{{iit.date}}</td>

                                        <!-- 傳票編號 -->
                                        <td v-if="iit.voucher_no != null">{{iit.voucher_no}}</td>
                                        <td v-if="iit.voucher_no == null"> - </td>

                                        <!-- 摘要 -->
                                        <td>{{iit.summary}}</td>

                                        <!-- 借方金額 -->
                                        <td class="amount" title="借方金額">{{iit.debit_amount | amount}}</td>

                                        <!-- 貸方金額 -->
                                        <td class="amount" title="貸方金額">{{iit.credit_amount | amount}}</td>

                                        <!-- 餘額 -->
                                        <td class="amount" title="餘額">{{getsubtotal(item.preliminary_balance, item.items)[index]}}</td>
                                    </tr>

                                    <tr class="double-border">
                                        <th></th>
                                        <th></th>
                                        <th>合計</th>
                                        <th class="amount" title="借方金額">{{getdebittotal(item.items) | amount}}</th>
                                        <th class="amount" title="貸方金額">{{getcredittotal(item.items) | amount}}</th>
                                        <th class="amount" title="餘額">{{item.total_amount | amount}}</th>
                                    </tr>
                                    <tr><td colspan="6"></td></tr>
                                    
                                </template>
                            </tbody>
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

.double-border {
    border-top: 1px solid #333;
    box-shadow: inset 0 1px 0 #9e9e9e;
}

</style>
<?php $this->load->view('admin/_footer'); ?>
