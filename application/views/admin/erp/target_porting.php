<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="page-header">ERP 帳務 - 借款案帳務轉移</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <form class="form-inline" @submit.prevent="update_target_list">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" value="2019-01-10" name="start_date" placeholder="開始日期" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control datepicker" value="2024-12-31" name="end_date" placeholder="結束日期" required>
                                </div>
                                <div class="form-group">
                                    <select type="text" class="form-control" name="target_type" required>
                                        <option disabled> - 案例類型 - </option>
                                        <option value="normal" selected>🟢 正常還款案</option>
                                        <option value="cleanup">🔵 提前清償案</option>
                                        <option value="overdue">🔴 逾期案</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="borrower_id" placeholder="借款人 ID (選填)" @keyup="number_only($event)">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="investor_id" placeholder="投資人 ID (選填)" @keyup="number_only($event)">
                                </div>
                                <button type="submit" class="btn btn-primary" :disabled="is_waiting_response">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group has-feedback" v-if="Array.isArray(target_list) && target_list?.length > 0">
                                <label class="control-label sr-only">Hidden label</label>
                                <input class="form-control input-sm" type="text" placeholder="案號篩選" autocomplete="off" v-model="target_filter" />
                                <span class="fa fa-times form-control-feedback form-control-clear" title="清除" aria-hidden="true" @click="target_filter=''"></span>
                            </div>
                            <div class="list-group">
                                <div class="list-group-item list-group-item-warning" v-if="Array.isArray(target_list) && filtered_target_list?.length<=0 && is_waiting_response===false">沒有資料</div>
                                <div style="position:relative;" v-for="item in filtered_target_list">
                                    <a href="javascript:;" :class="{'active': active_target == item.target_no}" @click="check_target(item.target_no)" class="list-group-item">
                                        <p class="list-group-item-text text-muted">
                                            {{item.loan_date}} | 借款人: {{item.borrower_id}}
                                        </p>
                                        <h4 class="list-group-item-heading">
                                            {{item.target_no}}
                                        </h4>
                                    </a>
                                    <button type="button" @click="copy_target_no($event, item.target_no)" class="btn btn-xs btn-default btn-copy-target">copy</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="panel-group" id="accordion">
                                <div class="row" v-if="investment_list?.length > 0">
                                    <div class="col-lg-9">
                                        <div class="alert alert-info" style="padding:6px 15px;">總計: {{total_amount|amount}} | 共 {{total_investments}} 案</div>
                                    </div>
                                    <div class="col-lg-3">
                                        <button type="button" class="btn btn-block btn-primary" @click="call_target_testfy" :disabled="is_waiting_response">
                                            執行帳務移轉 <i class="fa fa-exchange" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="panel panel-default" v-for="(item, index) in investment_list">
                                    <div class="panel-heading" :id="'iph_'+index">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion" :href="'#ipc_'+index">
                                              {{item|investment_title}}
                                            </a>
                                        </h4>
                                    </div>
                                    <div :id="'ipc_'+index" class="panel-collapse collapse">
                                        <div class="panel-body">
                                            <pre>{{item.contract.content}}</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    .btn-copy-target:hover,
    .btn-copy-target:focus {
        opacity: 1;
    }
    .btn-copy-target {
        opacity: .5;
        position: absolute;
        bottom: 17px;
        left: 17em;
        z-index: 10;
    }

    .form-control-clear {
        z-index: 10;
        pointer-events: auto;
        cursor: pointer;
        opacity: .7;
    }
    .form-control-clear:hover {
        opacity:  1;
    }
    .form-control-clear:active {

    }
</style>
<?php $this->load->view('admin/_footer'); ?>