<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">查詢虛擬帳號狀態</h1>
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
                                    <label class="sr-only" for="user_id_int">開始日期</label>
                                    <input type="text" class="form-control" id="user_id_int" name="user_id_int" v-model="searchform.user_id_int" placeholder="借款人 ID">
                                </div>
                                <button type="submit" class="btn btn-primary" :disabled="is_waiting_response">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>借款人 ID</th>
                                        <th>狀態</th>
                                        <th>建立時間</th>
                                        <th>虛擬存摺 總金額</th>
                                        <th>更改狀態</th>
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
                                    <tr v-if="!virtual_account_status.id_int">
                                        <td colspan="30" class="text-center">沒有資料</td>
                                    </tr>
                                    <tr v-else>
                                        <td>{{ virtual_account_status.uesr_id_int }}</td>
                                        <td>{{ statusText(virtual_account_status.status_int) }}</td>
                                        <td>{{ formatTime(virtual_account_status.created_at_ts_sec_int) }}</td>
                                        <td>{{ amount(virtual_account_status.sum_virtual_passbook_amount_int) }}</td>
                                        <td>
                                            <div class="form-group">
                                                <label>更新狀態</label>
                                                <select class="form-control" v-model="editStatus">
                                                    <option :value="0">凍結中</option>
                                                    <option :value="1">正常</option>
                                                    <option :value="2">使用中</option>
                                                </select>
                                            </div>
                                            <button class="btn" @click="doEdit">更新</button>
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

<?php $this->load->view('admin/_footer'); ?>
