<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">債權轉讓 - 移轉成功</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <form class="form-inline" ref="search-form" @submit.prevent="doSearch">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="sr-only" for="sdate">開始日期</label>
                                <input type="text" class="form-control" id="sdate" name="sdate" v-model="searchform.sdate" placeholder="開始日期" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="sr-only" for="edate">結束日期</label>
                                <input type="text" class="form-control" id="edate" name="edate" v-model="searchform.edate" placeholder="結束日期" autocomplete="off">
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
                <div class="panel-heading">
                    <h4>單筆債權</h4>
                </div>

                <div class="panel-body">
                    <div class="tab-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th>轉(受)讓日期</th>
                                        <th>出讓人會員 ID</th>
                                        <th>受讓人會員 ID</th>
                                        <th>案號</th>
                                        <th>債權金額</th>
                                        <th>年化利率</th>
                                        <th>價金</th>
                                        <th>剩餘本金</th>
                                        <th>剩餘利息</th>
                                        <th>剩餘延滯息</th>
                                        <th>剩餘期數</th>
                                    </tr>
                                </thead>
                                <!-- add loading html -->
                                <tbody v-if="is_waiting_response">
                                    <tr>
                                        <td colspan="30">
                                            <div class="text-center">
                                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr v-if="trasfer_sheet.length < 1">
                                        <td colspan="30" class="text-center">沒有資料</td>
                                    </tr>
                                    <template v-else>
                                        <tr v-for="transfer in trasfer_sheet">
                                            <td>{{ transfer.transfer_date }}</td>
                                            <td>{{ transfer.user_id }}</td>
                                            <td>{{ transfer.new_user_id }}</td>
                                            <td>{{ transfer.target_no }}</td>
                                            <td>{{ transfer.loan_amount }}</td>
                                            <td>{{ transfer.interest_rate }}</td>
                                            <td>{{ transfer.amount }}</td>
                                            <td>{{ transfer.principal }}</td>
                                            <td>{{ transfer.interest }}</td>
                                            <td>{{ transfer.delay_interest }}</td>
                                            <td>{{ transfer.instalment }}</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel-heading">
                    <h4>打包債權</h4>
                </div>

                <div class="panel-body">
                    <div class="tab-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th>轉(受)讓日期</th>
                                        <th>債權包案號</th>
                                        <th>出讓人會員 ID</th>
                                        <th>受讓人會員 ID</th>
                                        <th>債權筆數</th>
                                        <th>價金</th>
                                        <th>平均年化利率</th>
                                        <th>剩餘本金</th>
                                        <th>已發生利息</th>
                                        <th>已發生延滯息</th>
                                        <th>最小剩餘期數</th>
                                        <th>最大剩餘期數</th>
                                        <th>打包內容</th>
                                    </tr>
                                </thead>
                                <!-- add loading html -->
                                <tbody v-if="is_waiting_response">
                                    <tr>
                                        <td colspan="30">
                                            <div class="text-center">
                                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr v-if="conbine_sheet.length < 1">
                                        <td colspan="30" class="text-center">沒有資料</td>
                                    </tr>
                                    <template v-else>
                                        <tr v-for="conbine in conbine_sheet">
                                            <td>{{ conbine.transfer_date }}</td>
                                            <td>{{ conbine.combination_no }}</td>
                                            <td>{{ conbine.user_id }}</td>
                                            <td>{{ conbine.new_user_id }}</td>
                                            <td>{{ conbine.count }}</td>
                                            <td>{{ conbine.amount }}</td>
                                            <td>{{ conbine.avg_interest_rate }}</td>
                                            <td>{{ conbine.principal }}</td>
                                            <td>{{ conbine.combine_interest }}</td>
                                            <td>{{ conbine.combine_delay_interest }}</td>
                                            <td>{{ conbine.min_instalment }}</td>
                                            <td>{{ conbine.max_instalment }}</td>
                                            <td><button class="btn btn-info" @click="setDetailTableRow(conbine.combination_no)">
                                                    查看
                                                </button></td>

                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <form class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close mb-3" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h3 class="modal-title">打包內容</h3>
                            </div>
                            <div class="modal-body p-5">
                                <table id="history-table">
                                    <thead>
                                        <tr>
                                            <th>轉(受)讓日期</th>
                                            <th>出讓人會員 ID</th>
                                            <th>受讓人會員 ID</th>
                                            <th>案號</th>
                                            <th>債權金額</th>
                                            <th>年化利率</th>
                                            <th>剩餘本金</th>
                                            <th>剩餘利息</th>
                                            <th>剩餘延滯息</th>
                                            <th>剩餘期數</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .modal-xl {
        width: 95%;
    }
</style>
<?php $this->load->view('admin/_footer'); ?>