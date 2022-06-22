<style>
    .sk-input {
        width : 100%;
    }
</style>
<script type="text/javascript">
    function check_fail() {
        var status = $('#status :selected').val();
        if (status == 2) {
            $('#fail_div').show();
        } else {
            $('#fail_div').hide();
        }
    }

    $(document).off("change", "select#fail").on("change", "select#fail", function () {
        var sel = $(this).find(':selected');
        $('input#fail').css('display', sel.attr('value') == 'other' ? 'block' : 'none');
        $('input#fail').attr('disabled', sel.attr('value') == 'other' ? false : true);
    });
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= isset($data->certification_id) ? $certification_list[$data->certification_id] : ""; ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= isset($data->certification_id) ? $certification_list[$data->certification_id] : ""; ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe" href="<?= admin_url('User/display?id=' . $data->user_id) ?>">
                                    <p><?= isset($data->user_id) ? $data->user_id : "" ?></p>
                                </a>
                            </div>
                            <form id="app1" class="form-group" @submit.prevent="doSubmit">
                                <!-- navs -->
                                <ul class="nav nav-tabs">
                                    <li role="presentation" :class="{'active': tab ==='tab-skbank'}"><a @click="changeTab('tab-skbank')">新光</a></li>
                                    <li role="presentation" :class="{'active': tab ==='tab-kgibank'}"><a @click="changeTab('tab-kgibank')">凱基</a></li>
                                </ul>
                                <table class="table table-striped table-bordered table-hover dataTable" v-show="tab==='tab-skbank'">
                                    <tbody>
                                    <tr style="text-align: center;">
                                        <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年度營業額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearRevenue"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年度營業額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearRevenue"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年度營業額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearRevenue"></td>
                                    </tr>
                                    <tr>
                                        <td><span>每日營運資金需求量</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.dailyWorkingCapital"></td>
                                    </tr>
                                    <tr>
                                        <td><span>淨營業週期</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.operatingCycle"></td>
                                    </tr>
                                    <tr>
                                        <td><span>負債總額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.liabilitiesAmount"></td>
                                    </tr>
                                    <tr>
                                        <td><span>權益總額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.equityAmount">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年銷貨成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearCostOfGoodsSold">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年銷貨成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearCostOfGoodsSold">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年銷貨成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearCostOfGoodsSold">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年毛利率</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearGrossMargin">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年毛利率</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearGrossMargin">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年毛利率</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearGrossMargin">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年資產負債表之應收帳款</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearTradeReceivable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年資產負債表之應收帳款</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearTradeReceivable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年資產負債表之應收帳款</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearTradeReceivable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年資產負債表之存貨</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearInventory">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年資產負債表之存貨</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearInventory">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年資產負債表之存貨</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearInventory">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年固定成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearFixedCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年固定成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearFixedCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年固定成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearFixedCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年變動成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearVariableCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年變動成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearVariableCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年變動成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearVariableCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="table table-striped table-bordered table-hover dataTable" v-show="tab==='tab-kgibank'">
                                    <tbody>
                                    <tr style="text-align: center;">
                                        <td colspan="2"><span>普匯微企e秒貸資料確認2</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年度營業額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearRevenue"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年度營業額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearRevenue"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年度營業額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearRevenue"></td>
                                    </tr>
                                    <tr>
                                        <td><span>每日營運資金需求量</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.dailyWorkingCapital"></td>
                                    </tr>
                                    <tr>
                                        <td><span>淨營業週期</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.operatingCycle"></td>
                                    </tr>
                                    <tr>
                                        <td><span>負債總額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.liabilitiesAmount"></td>
                                    </tr>
                                    <tr>
                                        <td><span>權益總額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.equityAmount">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年銷貨成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearCostOfGoodsSold">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年銷貨成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearCostOfGoodsSold">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年銷貨成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearCostOfGoodsSold">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年毛利率</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearGrossMargin">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年毛利率</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearGrossMargin">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年毛利率</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearGrossMargin">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年資產負債表之應收帳款</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearTradeReceivable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年資產負債表之應收帳款</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearTradeReceivable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年資產負債表之應收帳款</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearTradeReceivable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年資產負債表之存貨</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearInventory">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年資產負債表之存貨</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearInventory">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年資產負債表之存貨</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearInventory">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年固定成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearFixedCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年固定成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearFixedCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年固定成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearFixedCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年變動成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearVariableCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年變動成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearVariableCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年變動成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearVariableCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                            <div class="form-group">
                              <? isset($ocr['url']) && !is_array($ocr['url']) ? $ocr['url'] = array($ocr['url']) : '';
                              foreach ($ocr['url'] as $key => $value) { ?>
                                  <label><a href="<?= isset($value) ? $value : ''; ?>" target="_blank">前往編輯頁面</a></label>
                              <? } ?>
                            </div>
                            <div class="form-group">
                                <label>備註</label>
                                <?
                                if ($remark) {
                                    if (isset($remark["fail"]) && $remark["fail"]) {
                                        echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark["fail"] . '</p>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label>系統審核</label>
                                <?
                                if (isset($sys_check)) {
                                    echo '<p class="form-control-static">' . ($sys_check==1?'是':'否') . '</p>';
                                }
                                ?>
                            </div>
                            <h4>審核</h4>
                            <form role="form" method="post">
                                <fieldset>
                                    <div class="form-group">
                                        <select id="status" name="status" class="form-control" onchange="check_fail();">
                                            <? foreach ($status_list as $key => $value) { ?>
                                                <option value="<?= $key ?>"
                                                        <?= $data->status == $key ? "selected" : "" ?>><?= $value ?></option>
                                            <? } ?>
                                        </select>
                                        <input type="hidden" name="id"
                                               value="<?= isset($data->id) ? $data->id : ""; ?>">
                                        <input type="hidden" name="from" value="<?= isset($from) ? $from : ""; ?>">
                                    </div>
                                    <div class="form-group" id="fail_div" style="display:none">
                                        <label>失敗原因</label>
                                        <select id="fail" name="fail" class="form-control">
                                            <option value="" disabled selected>選擇回覆內容</option>
                                            <? foreach ($certifications_msg[$data->certification_id] as $key => $value) { ?>
                                                <option
                                                    <?= $data->status == $value ? "selected" : "" ?>><?= $value ?></option>
                                            <? } ?>
                                            <option value="other">其它</option>
                                        </select>
                                        <input type="text" class="form-control" id="fail" name="fail"
                                               value="<?= $remark && isset($remark["fail"]) ? $remark["fail"] : ""; ?>"
                                               style="background-color:white!important;display:none" disabled="false">
                                    </div>
                                    <button type="submit" class="btn btn-primary">送出</button>
                                </fieldset>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <h1>圖片/文件</h1>
                            <fieldset disabled>
                                <div class="form-group">
                                    <?php if ( ! empty($content['income_statement_image']))
                                    {
                                        echo '<label>損益表</label><br>';
                                        $content['income_statement_image'] = ! is_array($content['income_statement_image']) ? array($content['income_statement_image']) : $content['income_statement_image'];
                                        foreach ($content['income_statement_image'] as $key => $value)
                                        {
                                            if (empty($value)) continue; ?>
                                            <a href="<?= $value ?>" data-fancybox="images">
                                                <img src="<?= $value ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                        echo '<hr/>';
                                    }
                                    if ( ! empty($content['file_list']['image']))
                                    {
                                        foreach ($content['file_list']['image'] as $key => $value)
                                        {
                                            if (empty($value['url']))
                                            {
                                                continue;
                                            } ?>
                                            <a href="<?= $value['url'] ?>" data-fancybox="images">
                                                <img src="<?= $value['url'] ?>"
                                                     style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                        echo '<hr/>';
                                    }
                                    if ( ! empty($content['file_list']['file']))
                                    {
                                        foreach ($content['file_list']['file'] as $key => $value)
                                        {
                                            if (empty($value['url']))
                                            {
                                                continue;
                                            } ?>
                                            <a href="<?= $value['url'] ?>">
                                                <i class="fa fa-file"> <?= $value['file_name'] ?? '檔案' ?></i>
                                            </a>
                                            <?php
                                        }
                                        echo '<hr/>';
                                    } ?>
                                </div>
                            </fieldset>
                        </div>
                      </div>
                      <!-- admin form end -->
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
<script>
    const v = new Vue({
        el: '#app1',
        data() {
            return {
                tab: 'tab-skbank',
                pageId: '',
                formData: {
                    lastOneYearRevenue: '',
                    lastTwoYearRevenue: '',
                    lastThreeYearRevenue: '',
                    dailyWorkingCapital: '',
                    operatingCycle: '',
                    liabilitiesAmount: '',
                    equityAmount: '',
                    lastOneYearCostOfGoodsSold: '',
                    lastTwoYearCostOfGoodsSold: '',
                    lastThreeYearCostOfGoodsSold: '',
                    lastOneYearGrossMargin: '',
                    lastTwoYearGrossMargin: '',
                    lastThreeYearGrossMargin: '',
                    lastOneYearTradeReceivable: '',
                    lastTwoYearTradeReceivable:'',
                    lastThreeYearTradeReceivable:'',
                    lastOneYearInventory:'',
                    lastTwoYearInventory:'',
                    lastThreeYearInventory:'',
                    lastOneYearFixedCost:'',
                    lastTwoYearFixedCost:'',
                    lastThreeYearFixedCost:'',
                    lastOneYearVariableCost:'',
                    lastTwoYearVariableCost:'',
                    lastThreeYearVariableCost:'',
                    tab3Input:''
                }
            }
        },
        mounted() {
            const url = new URL(location.href);
            this.pageId = url.searchParams.get('id');
            this.getData()
        },
        methods: {
            changeTab(tab) {
                this.tab = tab
            },
            doSubmit() {
                let selector = this.$el;
                $(selector).find('button').attr('disabled', true).text('資料更新中...');
                return axios.post('/admin/certification/save_company_cert', {
                    ...this.formData,
                    id: this.pageId
                }).then(({ data }) => {
                    alert(data.result)
                    location.reload()
                })
            },
            getData() {
                axios.get('/admin/certification/getSkbank', {
                    params: {
                        id: this.pageId
                    }
                }).then(({ data }) => {
                    mergeDeep(this.formData, data.response)
                })
            }
        },
    })
</script>