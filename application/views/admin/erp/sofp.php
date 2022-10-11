<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ERP 帳務 - 資產負債表</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <form class="form-inline" ref="search-form">
                        <div class="form-group">
                            <label class="sr-only" for="date">日期</label>
                            <input type="text" class="form-control" v-model="searchform.date" id="date" name="date" placeholder="日期">
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
                            <table class="table" v-for="p in table_data">
                                <thead>
                                    <tr class="info">
                                        <th colspan="4">
                                            <h4><strong>{{p.year - 1911}} 年度</strong></h4>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding:0;">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>項目</th>
                                                        <th>金額</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="active" v-if="p.content.assets.items.length > 0">
                                                        <th colspan="2">資產</th>
                                                    </tr>
                                                    <tr v-for="item in p.content.assets.items">
                                                        <td>{{item.index + "\t" + item.title}}</td>
                                                        <td>{{item.amount | sofp_amount}}</td>
                                                    </tr>
                                                    <tr v-for="i in count_blank(p.content, 'left')">
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="active">
                                                        <th>
                                                            資產總計
                                                        </th>
                                                        <th>
                                                            {{p.content.assets.amount | sofp_amount}}
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </td>
                                        <td style="padding:0;">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>項目</th>
                                                        <th>金額</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="active" v-if="p.content.liabilities.items.length > 0">
                                                        <th colspan="2">負債</th>
                                                    </tr>
                                                    <tr v-for="item in p.content.liabilities.items">
                                                        <td>{{item.index + "\t" + item.title}}</td>
                                                        <td>{{item.amount | sofp_amount}}</td>
                                                    </tr>
                                                    <tr class="active" v-if="p.content.equity.items.length > 0">
                                                        <th colspan="2">股東權益</th>
                                                    </tr>
                                                    <tr v-for="item in p.content.equity.items">
                                                        <td>{{item.index + "\t" + item.title}}</td>
                                                        <td>{{item.amount | sofp_amount}}</td>
                                                    </tr>
                                                    <tr v-for="i in count_blank(p.content, 'right')">
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr class="active">
                                                        <th>
                                                            負債及股東權益總計
                                                        </th>
                                                        <th>
                                                            {{p.content.liabilities.amount + p.content.equity.amount | sofp_amount }}
                                                        </th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
tr {
    height: 3.7rem;
}
</style>
<?php $this->load->view('admin/_footer'); ?>