<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">QR Code 合約審核</h1>
        </div>
    </div>
    <div class="row" v-if="is_review_mode">
        <form ref="contract_form" @submit.prevent="edit_contract">
            <div class="col-lg-4">
                <div class="panel panel-default">
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
                        <pre class="preview_zone">{{context}}</pre>
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
                                            <span class="label label-info" v-if="item.status==3">{{item.status_name}}</span>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-default btn-sm" @click="print_contract(item.qrcode_apply_id)">
                                                <i class="fa fa-print"></i> 列印
                                            </button>

                                            <button type="button" class="btn btn-default btn-sm" @click="review_contract(item.qrcode_apply_id)">
                                                <i class="fa fa-pencil"></i> 審查合約
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
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li>
                                <a href="#">
                                    <span>&laquo;</span>
                                </a>
                            </li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li>
                                <a href="#">
                                    <span>&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
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