<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ERP 帳務 - 投資人開帳作業</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <form class="form-inline" ref="search-form" @submit.prevent="search">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group" :class="{'has-error': form_error.search.investor_id}">
                                    <label class="sr-only" for="investor_id">投資人 ID</label>
                                    <input type="text" class="form-control" id="investor_id" name="investor_id" v-model="searchform.investor_id" placeholder="投資人 ID" required>
                                </div>
                                <button type="submit" class="btn btn-primary" :disabled="!is_search_valid">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <table class="table table-hover" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>案號 target_no</th>
                                <th class="amount">申貸金額</th>
                                <th>類型</th>
                                <th>借款人 ID</th>
                                <th>分期期數</th>
                                <th>狀態</th>
                                <th>申貸日期</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="active" v-if="data.length < 1">
                                <td colspan="8" class="text-center">沒有資料</td>
                            </tr>
                            <template v-if="data.length > 0">
                                <tr v-for="(target, index) in data">

                                    <!-- ID -->
                                    <td>{{target.target_id}}</td>

                                    <!-- 案號 target_no -->
                                    <td>{{target.target_no}}</td>

                                    <!-- 申貸金額 -->
                                    <td>{{target.loan_amount | asset_amount}}</td>

                                    <!-- 類型 -->
                                    <td>{{target.type}}</td>

                                    <!-- 借款人 ID -->
                                    <td>{{target.borrower_id}}</td>

                                    <!-- 分期期數 -->
                                    <td>{{target.instalment}}</td>

                                    <!-- 狀態 -->
                                    <td>{{target.status}}</td>

                                    <!-- 申貸日期 -->
                                    <td>{{target.loan_date}}</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <nav v-if="">
                        <ul class="pagination">
                        <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>
                        <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/_footer'); ?>