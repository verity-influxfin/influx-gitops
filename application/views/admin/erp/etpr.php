<?php $this->load->view('admin/_header'); ?>

<?php $this->load->view('admin/_title', $menu); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">ERP 帳務 - 本攤表</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <form class="form-inline" ref="search-form" @submit.prevent="search">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group" :class="{'has-error': form_error.search.start_date}">
                                    <label class="sr-only" for="start_date">開始日期</label>
                                    <input type="text" class="form-control" id="start_date" name="start_date" v-model="searchform.start_date" placeholder="開始日期" required>
                                </div>
                                <div class="form-group" :class="{'has-error': form_error.search.end_date}">
                                    <label class="sr-only" for="end_date">結束日期</label>
                                    <input type="text" class="form-control" id="end_date" name="end_date" v-model="searchform.end_date" placeholder="結束日期" required>
                                </div>
                                <div class="form-group" :class="{'has-error': form_error.search.investor_id}">
                                    <label class="sr-only" for="investor_id">投資人 ID</label>
                                    <input type="text" class="form-control" id="investor_id" name="investor_id" v-model="searchform.investor_id" placeholder="投資人 ID" required>
                                </div>
                                <button type="submit" class="btn btn-primary" :disabled="is_waiting_response">
                                    <i class="fa fa-search"></i> 搜尋
                                </button>
                                <!-- <button class="btn btn-excel pull-right" type="button" v-on:click="spreadsheet_export" :disabled="is_waiting_response" v-if="data?.length > 0">
                                    <i class="fa fa-file-excel-o"></i> 檔案下載
                                </button> -->
                            </div>
                        </div>
                        <div class="row" v-if="contract_list?.length > 1">
                            <div class="col-xs-12">
                                <h5 class="text-center text-" style="color: #9e9e9e;border-bottom: 1px solid #ddd;">合約</h5>
                                <button class="btn btn-xs btn-primary" type="button" @click="select_all(1)">全選</button>
                                <button class="btn btn-xs btn-primary" type="button" @click="select_all(0)">全不選</button>
                            </div>
                            <div class="col-md-3 col-sm-4" v-for="contract in contract_list">
                                <div class="form-group">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" :value="contract" v-model="searchform.contracts"> {{contract}}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li :class="tabactive('normal')">
                            <a href="javascript:;" @click="settab('normal')">正常案</a>
                        </li>
                        <li :class="tabactive('overdue')">
                            <a href="javascript:;" @click="settab('overdue')">逾期案</a>
                        </li>
                        <li :class="tabactive('cleanup')">
                            <a href="javascript:;" @click="settab('cleanup')">提前清償</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="table-responsive" v-if="istab('normal')">
                            <table class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="10%">日期</th>
                                        <th>當期本金</th>
                                        <th>當期利息</th>
                                        <th>本息合計</th>
                                        <th>本金餘額</th>
                                        <th>當期償還本金</th>
                                        <th>當期償還利息</th>
                                        <th>當期償還本息</th>
                                        <th>回款手續費</th>
                                        <th>投資回款淨額</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="data?.length < 1">
                                        <td colspan="15" class="text-center">沒有資料</td>
                                    </tr>
                                    <tr v-for="(item, index) in data" v-if="data?.length > 0">
                                        <td>{{index + 1}}</td>

                                        <!-- 日期 -->
                                        <td>{{item.date}}</td>

                                        <!-- 當期本金 -->
                                        <td>{{item.principal | asset_amount}}</td>

                                        <!-- 當期利息 -->
                                        <td>{{item.interest_amount | asset_amount}}</td>

                                        <!-- 本息合計 -->
                                        <td>{{item.pit | asset_amount}}</td>

                                        <!-- 本金餘額 -->
                                        <td>{{item.principal_balance | asset_amount}}</td>

                                        <!-- 當期償還本金 -->
                                        <td>{{item.repayment_principal | asset_amount}}</td>

                                        <!-- 當期償還利息 -->
                                        <td>{{item.repayment_interest_amount | asset_amount}}</td>

                                        <!-- 當期償還本息 -->
                                        <td>{{item.repayment_pit | asset_amount}}</td>

                                        <!-- 回款手續費 -->
                                        <td>{{item.repayment_payback_fee | asset_amount}}</td>

                                        <!-- 投資回款淨額 -->
                                        <td>{{item.repayment_net_amount | asset_amount}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive" v-if="istab('overdue')">
                            <table class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="10%">日期</th>
                                        <th>當期本金</th>
                                        <th>當期利息</th>
                                        <th>本息合計</th>
                                        <th>延滯息</th>
                                        <th>當期償還本金</th>
                                        <th>當期償還利息</th>
                                        <th>當期償還本息</th>
                                        <th>當期償還延滯息</th>
                                        <th>回款手續費</th>
                                        <th>投資回款淨額</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="data?.length < 1">
                                        <td colspan="15" class="text-center">沒有資料</td>
                                    </tr>
                                    <tr v-for="(item, index) in data" v-if="data?.length > 0">
                                        <td>{{index + 1}}</td>

                                        <!-- 日期 -->
                                        <td>{{item.date}}</td>

                                        <!-- 尚欠本金 -->
                                        <td>{{item.principal | asset_amount}}</td>

                                        <!-- 尚欠利息 -->
                                        <td>{{item.interest_amount | asset_amount}}</td>

                                        <!-- 尚欠本息 -->
                                        <td>{{item.pit | asset_amount}}</td>

                                        <!-- 延滯息 -->
                                        <td>{{item.demurrage | asset_amount}}</td>

                                        <!-- 當期償還本金 -->
                                        <td>{{item.repayment_principal | asset_amount}}</td>

                                        <!-- 當期償還利息 -->
                                        <td>{{item.repayment_interest_amount | asset_amount}}</td>

                                        <!-- 當期償還本息 -->
                                        <td>{{item.repayment_pit | asset_amount}}</td>

                                        <!-- 當期償還延滯息 -->
                                        <td>{{item.repayment_demurrage | asset_amount}}</td>

                                        <!-- 回款手續費 -->
                                        <td>{{item.repayment_payback_fee | asset_amount}}</td>

                                        <!-- 投資回款淨額 -->
                                        <td>{{item.repayment_net_amount | asset_amount}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive" v-if="istab('cleanup')">
                            <table class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="10%">日期</th>
                                        <th>當期本金</th>
                                        <th>當期利息</th>
                                        <th>本息合計</th>
                                        <th>本金餘額</th>
                                        <th>當期償還本金</th>
                                        <th>當期償還利息</th>
                                        <th>當期償還本息</th>
                                        <th>回款手續費</th>
                                        <th>投資回款淨額</th>
                                        <th>補貼</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="data?.length < 1">
                                        <td colspan="15" class="text-center">沒有資料</td>
                                    </tr>
                                    <tr v-for="(item, index) in data" v-if="data?.length > 0">
                                        <td>{{index + 1}}</td>

                                        <!-- 日期 -->
                                        <td>{{item.date}}</td>

                                        <!-- 當期本金 -->
                                        <td>{{item.principal | asset_amount}}</td>

                                        <!-- 當期利息 -->
                                        <td>{{item.interest_amount | asset_amount}}</td>

                                        <!-- 本息合計 -->
                                        <td>{{item.pit | asset_amount}}</td>

                                        <!-- 本金餘額 -->
                                        <td>{{item.principal_balance | asset_amount}}</td>

                                        <!-- 當期償還本金 -->
                                        <td>{{item.repayment_principal | asset_amount}}</td>

                                        <!-- 當期償還利息 -->
                                        <td>{{item.repayment_interest_amount | asset_amount}}</td>

                                        <!-- 當期償還本息 -->
                                        <td>{{item.repayment_pit | asset_amount}}</td>

                                        <!-- 回款手續費 -->
                                        <td>{{item.repayment_payback_fee | asset_amount}}</td>

                                        <!-- 投資回款淨額 -->
                                        <td>{{item.repayment_net_amount | asset_amount}}</td>

                                        <!-- 補貼 -->
                                        <td>{{item.subsidy | asset_amount}}</td>
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