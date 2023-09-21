<style>
    .sk-input {
        width : 100%;
    }
</style>
<script type="text/javascript">
    function check_fail() {
        if ($('#status :selected').val() === '2') {
            $('#fail_div').show();
        } else {
            $('#fail_div').hide();
        }
    }

    $(document).off("change", "select#fail").on("change", "select#fail", function () {
        if ($(this).find(':selected').val() === 'other') {
            $('input#fail').css('display', 'block').attr('disabled', false);
        } else {
            $('input#fail').css('display', 'none').attr('disabled', true);
        }
    });

    $(document).ready(function () {
        check_fail();
        $('select#fail').trigger('change');
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
                                        <td><span>近一年_年度</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncomeYear1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（01）近一年度營業額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearRevenue"></td>
                                    </tr>
                                    <tr>
                                        <td><span>（05）近一年銷貨成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearCostOfGoodsSold">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（07）近一年毛利率（%）</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearGrossMargin">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（89）近一年營業收入分類標準代號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.IndustryCode1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（90）近一年營業收入淨額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncome1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年資產負債表之應收帳款</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearTradeReceivable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年資產負債表之存貨</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearInventory">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年固定成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearFixedCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年變動成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearVariableCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年_年度</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncomeYear2">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（01）近二年度營業額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearRevenue"></td>
                                    </tr>
                                    <tr>
                                        <td><span>（05）近二年銷貨成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearCostOfGoodsSold">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（07）近二年毛利率（%）</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearGrossMargin">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（89）近二年營業收入分類標準代號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.IndustryCode2">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（90）近二年營業收入淨額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncome2">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年資產負債表之應收帳款</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearTradeReceivable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年資產負債表之存貨</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearInventory">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年固定成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearFixedCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年變動成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearVariableCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年_年度</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncomeYear3">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（01）近三年度營業額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearRevenue"></td>
                                    </tr>
                                    <tr>
                                        <td><span>（05）近三年銷貨成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearCostOfGoodsSold">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（07）近三年毛利率（%）</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearGrossMargin">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（89）近三年營業收入分類標準代號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.IndustryCode3">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（90）近三年營業收入淨額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncome3">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年資產負債表之應收帳款</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearTradeReceivable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年資產負債表之存貨</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearInventory">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年固定成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearFixedCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年變動成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearVariableCost">
                                        </td>
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
                                        <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="table table-striped table-bordered table-hover dataTable" v-show="tab==='tab-kgibank'">
                                    <tbody>
                                    <tr style="text-align: center;">
                                        <td colspan="2"><span>普匯微企e秒貸資料確認</span></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年_年度</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncomeYear1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（01）近一年度營業額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearRevenue"></td>
                                    </tr>
                                    <tr>
                                        <td><span>（05）近一年銷貨成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearCostOfGoodsSold">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（07）近一年毛利率（%）</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearGrossMargin">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（89）近一年營業收入分類標準代號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.IndustryCode1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（90）近一年營業收入淨額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncome1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年資產負債表之應收帳款</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearTradeReceivable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年資產負債表之存貨</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearInventory">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年固定成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearFixedCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年變動成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastOneYearVariableCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年_年度</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncomeYear2">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（01）近二年度營業額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearRevenue"></td>
                                    </tr>
                                    <tr>
                                        <td><span>（05）近二年銷貨成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearCostOfGoodsSold">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（07）近二年毛利率（%）</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearGrossMargin">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（89）近二年營業收入分類標準代號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.IndustryCode2">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（90）近二年營業收入淨額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncome2">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年資產負債表之應收帳款</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearTradeReceivable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年資產負債表之存貨</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearInventory">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年固定成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearFixedCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年變動成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastTwoYearVariableCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年_年度</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncomeYear3">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（01）近三年度營業額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearRevenue"></td>
                                    </tr>
                                    <tr>
                                        <td><span>（05）近三年銷貨成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearCostOfGoodsSold">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（07）近三年毛利率（%）</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearGrossMargin">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（89）近三年營業收入分類標準代號</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.IndustryCode3">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>（90）近三年營業收入淨額</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.AnnualIncome3">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年資產負債表之應收帳款</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearTradeReceivable">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年資產負債表之存貨</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearInventory">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年固定成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearFixedCost">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年變動成本</span></td>
                                        <td><input class="sk-input form-control" type="text" v-model="formData.lastThreeYearVariableCost">
                                        </td>
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
                                        <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                            <div class="form-group">
                                <label>損益表 OCR 辨識結果</label>
                                <br>  
                                <? isset($content['ocr_parser']['content']) && !is_array($content['ocr_parser']['content']) ? $content['ocr_parser']['content'] = array($content['ocr_parser']['content']) : '';
                                if(!empty($content['ocr_parser']['content'])) { 
                                    foreach ($content['ocr_parser']['content'] as $key => $value) { 
                                        if ($value['docType'] == 'INCOME_STATEMENT') { ?>
                                            <a class="mr-5" href="/admin/certification/income_statement_ocr_page?id=<? echo $data->id; ?>&type=INCOME_STATEMENT&year=<? echo $value['cell_dict']['end_date_yyy']; ?>"><? echo $value['cell_dict']['end_date_yyy']; ?> 年</a>
                                    <?  }
                                    } 
                                } ?>
                                <br><br>
                                <!-- <label>資產負債表 OCR 辨識結果</label>
                                <br> 
                                <? isset($content['ocr_parser']['content']) && !is_array($content['ocr_parser']['content']) ? $content['ocr_parser']['content'] = array($content['ocr_parser']['content']) : '';
                                if(!empty($content['ocr_parser']['content'])) { 
                                    foreach ($content['ocr_parser']['content'] as $key => $value) { 
                                        if ($value['docType'] == 'BALANCE_SHEET') { ?>
                                            <a class="mr-5" href="/admin/certification/income_statement_ocr_page?id=<? echo $data->id; ?>&type=BALANCE_SHEET&year=<? echo $value['cell_dict']['yyy_str']; ?>"><? echo $value['cell_dict']['yyy_str']; ?> 年</a>
                                    <?  }
                                    } 
                                } ?> -->
                            </div>
                            <div class="form-group">
                              <? isset($ocr['url']) && !is_array($ocr['url']) ? $ocr['url'] = array($ocr['url']) : '';
                              foreach ($ocr['url'] as $key => $value) { ?>
                                  <label><a href="<?= isset($value) ? $value : ''; ?>" target="_blank">前往編輯頁面</a></label>
                              <? } ?>
                            </div>
                            <div class="form-group">
                                <label>備註</label>
                                <?php $fail = '';
                                if ( ! empty($remark['fail']))
                                {
                                    $fail = $remark['fail'];
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark['fail'] . '</p>';
                                } ?>
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
                            <form role="form" method="post" action="/admin/certification/user_certification_edit">
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
                                            <?php $fail_other = TRUE;
                                            foreach ($certifications_msg[$data->certification_id] as $key => $value)
                                            {
                                                $this_option_selected = FALSE;
                                                if ($fail == $value)
                                                {
                                                    $fail_other = FALSE;
                                                    $this_option_selected = TRUE;
                                                } ?>
                                                <option
                                                    <?= $this_option_selected ? 'selected' : '' ?>><?= $value ?></option>
                                            <? } ?>
                                            <option value="other" <?= $fail_other ? 'selected' : ''; ?>>其它</option>
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
                            <fieldset>
                                <div class="form-group">
                                    <label>損益表/資產負債表</label><br>
                                    <?php if ( ! empty($content['income_statement_image']))
                                    {
                                        $content['income_statement_image'] = ! is_array($content['income_statement_image']) ? array($content['income_statement_image']) : $content['income_statement_image'];
                                        foreach ($content['income_statement_image'] as $key => $value)
                                        { ?>
                                            <a href="<?= $value ?>" data-fancybox="images">
                                                <img src="<?= $value ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                    }
                                    if ( ! empty($content['file_list']['image']))
                                    {
                                        foreach ($content['file_list']['image'] as $key => $value)
                                        { ?>
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
                                        { ?>
                                            <a href="<?= $value['url'] ?>">
                                                <i class="fa fa-file"> <?= $value['file_name'] ?? '檔案' ?></i>
                                            </a>
                                        <? }
                                    }?>
                                    <hr/>
                                    <label>其它</label><br>
                                    <?php
                                    if ( ! empty($content['pdf']) && is_array($content['pdf']))
                                    {
                                        $index = 0;
                                        foreach ($content['pdf'] as $value)
                                        { ?>
                                            <a href="<?= $value ?>" class="btn btn-info">
                                                檔案<?= ++$index; ?>
                                            </a>
                                        <?php }
                                    } ?>
                                </div>
                            </fieldset>
                            <?php if ( ! empty($ocr['upload_page']))
                            {
                                ?>
                                <div class="form-group" style="background:#f5f5f5;border-style:double;">
                                    <?= $ocr['upload_page']; ?>
                                </div>
                            <?php } ?>
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
                    tab3Input:'',
                    AnnualIncome1: '',
                    AnnualIncome2: '',
                    AnnualIncome3: '',
                    AnnualIncomeYear1: '',
                    AnnualIncomeYear2: '',
                    AnnualIncomeYear3: '',
                    IndustryCode1: '',
                    IndustryCode2: '',
                    IndustryCode3: '',
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
