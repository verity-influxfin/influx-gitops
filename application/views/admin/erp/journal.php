<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ERP 帳務 - 日記簿</h1>
        </div>
    </div>
    <div>
        <ul class="nav nav-tabs" id="myTabs" role="tablist">
            <!-- <li role="presentation" class="active">
                <a role="tab" data-toggle="tab" aria-controls="test1" aria-expanded="true" @click="tab='tab1'">個人</a>
            </li> -->
            <li role="presentation">
                <a role="tab" data-toggle="tab" aria-controls="test2" aria-expanded="false" @click="tab='tab2'">平台</a>
            </li>
        </ul>
    </div>
    <!-- <div class="row" v-show="tab == 'tab1'">
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
                                    <label class="sr-only" for="investor_id">投資人 ID</label>
                                    <input type="text" class="form-control" id="investor_id" name="investor_id" v-model="searchform.user_id_int" placeholder="投資人 ID" required>
                                </div>
                                <button type="submit" class="btn btn-primary" :disabled="is_waiting_response">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
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
                                    <th>會計科目</th>
                                    <th>科目名稱</th>
                                    <th>摘要</th>
                                    <th class="amount">借方金額</th>
                                    <th class="amount">貸方金額</th>
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
                                    <td colspan="8" class="text-center">沒有資料</td>
                                </tr>
                                <template v-if="table_data.length > 0">
                                    <template v-for="item in table_data">
                                        <tr class="info">
                                            <td>{{ formateTime(item.sub_event.time) }}</td>
                                            <td colspan="4">{{ item.sub_event.id }}</td>
                                            <td class="amount"></td>
                                            <td class="amount"></td>
                                        </tr>
                                        <tr v-for="(value,key) in item.entry_list">
                                            <td></td>
                                            <td></td>
                                            <td>{{ value.subject_code }}</td>
                                            <td>{{ value.subject_name }}</td>
                                            <td>{{ value.summary }}</td>
                                            <td class="amount">{{ dcAmount(value.amount,value.dc_code,'C') }}</td>
                                            <td class="amount">{{ dcAmount(value.amount,value.dc_code,'D') }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="8"></td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="row" v-show="tab == 'tab2'">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <form class="form-inline" ref="search-form" @submit.prevent="doErpSearch">
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
                                <button type="submit" class="btn btn-primary" :disabled="is_waiting_response">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th>會計科目</th>
                                    <th>會計科目名稱</th>
                                    <th>客戶＼供商</th>
                                    <th>摘要</th>
                                    <th>借＼貸</th>
                                    <th class="amount">借方金額</th>
                                    <th class="amount">貸方金額</th>
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
                                <tr class="active" v-if="erp_balance_data.length < 1">
                                    <td colspan="20" class="text-center">沒有資料</td>
                                </tr>
                                <template v-if="erp_balance_data.length > 0">
                                    <tr v-for="item in erp_balance_data">
                                        <td>{{ item.subject_code_str }}</td>
                                        <td>{{ item.subject_name_str }}</td>
                                        <td>{{ item.customer_name_str }}</td>
                                        <td>{{ item.summary_str }}</td>
                                        <td>{{ item.role_str }}</td>
                                        <td class="amount">{{ amount(item.borrower_amount_int_str) }}</td>
                                        <td class="amount">{{ amount(item.lender_amount_int_str) }}</td>
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

    .label>button {
        background: none;
        border: 0;
    }
</style>
<?php $this->load->view('admin/_footer'); ?>
