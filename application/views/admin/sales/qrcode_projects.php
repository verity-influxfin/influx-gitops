<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">QR Code 方案設定</h1>
        </div>
    </div>
    <button type="button" class="btn btn-link" @click="cancel()" v-if="is_edit_mode">
        <i class="fa fa-angle-double-left"></i> 返回列表
    </button>
    <div class="row" v-if="is_edit_mode">
        <form ref="contract_form" @submit.prevent="update_contract">
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-body form-horizontal">
                        <div class="form-group">
                            <label for="platform_fee" class="col-sm-6 control-label">服務手續費 (%)</label>
                            <div class="col-sm-6">
                                <input type="" class="form-control" v-model="contract.platform_fee" id="platform_fee" placeholder="服務手續費 (%)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="interest" class="col-sm-6 control-label">利息手續費 (%)</label>
                            <div class="col-sm-6">
                                <input type="" class="form-control" v-model="contract.interest" id="interest" placeholder="利息手續費 (%)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="collaboration_person" class="col-sm-6 control-label">第三方合作個人產品 (元)</label>
                            <div class="col-sm-6">
                                <input type="" class="form-control" v-model="contract.collaboration_person" id="collaboration_person" placeholder="第三方合作個人產品 (元)">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="collaboration_enterprise" class="col-sm-6 control-label">第三方合作企業產品 (元)</label>
                            <div class="col-sm-6">
                                <input type="" class="form-control" v-model="contract.collaboration_enterprise" id="collaboration_enterprise" placeholder="第三方合作企業產品 (元)">
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary" v-bind:disabled="is_waiting_response">確認修改</button>
                    </div>
                </div>
                <div class="panel panel-default" v-if="false">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-4">
                                <button type="button" class="btn btn-success btn-block">
                                    <i class="fa fa-check"></i> 核可
                                </button>
                            </div>
                            <div class="col-xs-4">
                                <button type="button" class="btn btn-default btn-block">
                                    <i class="fa fa-undo"></i> 退回
                                </button>
                            </div>
                            <div class="col-xs-4">
                                <button type="button" class="btn btn-danger btn-block">
                                    <i class="fa fa-times"></i> 否決
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <pre class="preview_zone" v-if="Array.isArray(context)">{{context[0]}}<input type="number" v-model="contract.platform_fee" />{{context[1]}}<input type="number" v-model="contract.interest" />{{context[2]}}<input type="number" v-model="contract.collaboration_person" />{{context[3]}}<input type="number" v-model="contract.collaboration_enterprise" />{{context[4]}}</pre>
                        <pre class="preview_zone" v-else>{{context}}</pre>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="row" v-if="is_list_mode">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <form class="form-inline" ref="search_form" @submit.prevent="search">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>會員 ID</label>
                                    <input type="number" class="form-control" name="user_id" placeholder="User ID" v-model="searchform.user_id">
                                </div>
                                <div class="form-group">
                                    <label>建檔日期</label>
                                    <input type="text" class="form-control" id="sdate" name="sdate" v-model="searchform.sdate" placeholder="開始日期">
                                    <input type="text" class="form-control" id="edate" name="edate" v-model="searchform.edate" placeholder="結束日期">
                                </div>
                                <div class="form-group">
                                    <label for="alias">合作方案</label>
                                    <select class="form-control" name="alias" v-model="searchform.alias">
                                        <option value="all" selected>請選擇</option>
                                        <option value="general">一般方案</option>
                                        <option value="campus_ambassador">校園大使方案</option>
                                        <option value="appointed">特約方案</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
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
                                <tr class="active">
                                    <th>#</th>
                                    <th>建檔日期</th>
                                    <th>會員 ID</th>
                                    <th>合作方案</th>
                                    <th>狀態</th>
                                    <th width="30%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-if="data.length > 0">
                                    <tr v-for="item in data">

                                        <!-- # -->
                                        <td>{{item.qrcode_apply_id}}</td>

                                        <!-- 建檔日期 -->
                                        <td>{{item.created_at}}</td>

                                        <!-- 會員 ID -->
                                        <td>{{item.user_id}}</td>

                                        <!-- 合作方案 -->
                                        <td>{{item.alias_chinese_name}}</td>

                                        <!-- 狀態 -->
                                        <td>
                                            <span class="label label-success" v-if="item.status==1">{{item.status_name}}</span>
                                            <span class="label label-info" v-if-else="item.status==3">{{item.status_name}}</span>
                                            <span class="label label-default" v-else>{{item.status_name}}</span>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" @click="print_contract(item.qrcode_apply_id)" v-bind:disabled="is_waiting_response">
                                                <i class="fa fa-print"></i> 列印
                                            </button>

                                            <button type="button" class="btn btn-default btn-sm" @click="edit_contract(item.qrcode_apply_id)" v-bind:disabled="is_waiting_response">
                                                <i class="fa fa-pencil"></i> 修改合約
                                            </button>

                                            <button type="button" class="btn btn-primary btn-sm" @click="contract_submit" v-bind:disabled="is_waiting_response">
                                                <i class="fa fa-paper-plane-o"></i> 送出審核
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td colspan="6">沒有資料</td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="panel-footer text-right">
                    <v-page v-bind:data="pagination" @change_page="change_page"></v-page>
                </div>
            </div>
        </div>
    </div>
    <div class="printable contract">{{contract_printing}}</div>
</div>
<style type="text/css">
.printable {
    display: none;
}
@media print {
    @page :footer {
        display: none
    }
    @page :header {
        display: none
    }
    #page-wrapper *:not(.printable) { display: none; }
    .printable {
        display: block;
    }
    .printable.contract {
        white-space: pre-wrap;
    }
}
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
.preview_zone {
    max-height: 40em;
}
.preview_zone input {
    width: 8em;
}
.panel-footer ul.pagination {
    margin: 0;
}

</style>
<?php $this->load->view('admin/_footer'); ?>
