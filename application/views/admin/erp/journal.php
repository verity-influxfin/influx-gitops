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
                            <tbody>
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
