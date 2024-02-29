<style>
    .sk-input form-control {
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
                                        <td><span>近一年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastOneYear"
                                            placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastOneYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastOneYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastOneYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastOneYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastOneYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastOneYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastTwoYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastTwoYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastTwoYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastTwoYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastTwoYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastTwoYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastTwoYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastThreeYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastThreeYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastThreeYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastThreeYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastThreeYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastThreeYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastThreeYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastFourYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastFourYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastFourYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastFourYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastFourYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastFourYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastFourYearInvoiceAmountM11M12"></td>
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
                                        <td><span>近一年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastOneYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastOneYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastOneYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastOneYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastOneYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastOneYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastOneYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastTwoYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastTwoYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastTwoYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastTwoYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastTwoYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastTwoYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastTwoYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastThreeYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastThreeYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastThreeYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastThreeYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastThreeYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastThreeYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastThreeYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastFourYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastFourYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastFourYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastFourYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastFourYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastFourYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.LastFourYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
                            <div class="form-group">
                                <label>備註</label>
                                <?php $fail = '';
                                if ( ! empty($remark["fail"]))
                                {
                                    $fail = $remark['fail'];
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark["fail"] . '</p>';

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
                                                    <?= $this_option_selected ? "selected" : "" ?>><?= $value ?></option>
                                            <?php } ?>
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
                            <div class="form-group">
                                <h1>圖片/文件</h1>
                                <fieldset>
                                    <div class="form-group">
                                        <label>近三年401/403/405表格照</label><br>
                                        <?php
                                        if (isset($content['business_tax_image']))
                                        {
                                            foreach ($content['business_tax_image'] as $key => $value)
                                            { ?>
                                                <a href="<?= $value ?>" data-fancybox="images">
                                                    <img src="<?= $value ?>" style='width:30%;max-width:400px'>
                                                </a>
                                            <?php }
                                        } ?>
                                        <?php
                                        if (isset($images))
                                        { // APP上傳圖片，分成近1/2/3/4年
                                            foreach ($images as $key => $value)
                                            {
                                                $this_block_html = '';
                                                if ( ! empty($value['name']))
                                                {
                                                    $this_block_html .= "<label>{$value['name']}</label><br/>";
                                                }
                                                if ( ! empty($value['url']))
                                                {
                                                    if (is_array($value['url']))
                                                    {
                                                        array_map(function ($item) use (&$this_block_html) {
                                                            if ( ! empty($item))
                                                            {
                                                                $this_block_html .= '<a href="' . $item . '" data-fancybox="images"><img alt="" src="' . $item . '" style="width: 30%; max-width:400px"></a>';
                                                            }
                                                        }, $value['url']);
                                                    }
                                                    else
                                                    {
                                                        $this_block_html .= '<a href="' . $value['url'] . '" data-fancybox="images"><img alt="" src="' . $value['url'] . '" style="width: 30%; max-width:400px"></a>';
                                                    }
                                                }
                                                if ( ! empty($value['upload']))
                                                {
                                                    $this_block_html .= '<div class="form-group" style="background:#f5f5f5;border-style:double;">' . $value['upload'] . '</div>';
                                                }
                                                echo "<div>{$this_block_html}</div>";
                                            }
                                        } ?>
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
                pageId:'',
                formData: {
                    businessTaxLastOneYear:'',
                    businessTaxLastTwoYear:'',
                    businessTaxLastThreeYear:'',
                    businessTaxLastFourYear:'',
                    LastOneYearInvoiceAmountM1M2:'',
                    LastOneYearInvoiceAmountM3M4:'',
                    LastOneYearInvoiceAmountM5M6:'',
                    LastOneYearInvoiceAmountM7M8:'',
                    LastOneYearInvoiceAmountM9M10:'',
                    LastOneYearInvoiceAmountM11M12:'',
                    LastTwoYearInvoiceAmountM1M2:'',
                    LastTwoYearInvoiceAmountM3M4:'',
                    LastTwoYearInvoiceAmountM5M6:'',
                    LastTwoYearInvoiceAmountM7M8:'',
                    LastTwoYearInvoiceAmountM9M10:'',
                    LastTwoYearInvoiceAmountM11M12:'',
                    LastThreeYearInvoiceAmountM1M2:'',
                    LastThreeYearInvoiceAmountM3M4:'',
                    LastThreeYearInvoiceAmountM5M6:'',
                    LastThreeYearInvoiceAmountM7M8:'',
                    LastThreeYearInvoiceAmountM9M10:'',
                    LastThreeYearInvoiceAmountM11M12:'',
                    LastFourYearInvoiceAmountM1M2:'',
                    LastFourYearInvoiceAmountM3M4:'',
                    LastFourYearInvoiceAmountM5M6:'',
                    LastFourYearInvoiceAmountM7M8:'',
                    LastFourYearInvoiceAmountM9M10:'',
                    LastFourYearInvoiceAmountM11M12:'',
                }
            }
        },
        mounted () {
            const url = new URL(location.href);
            this.pageId = url.searchParams.get('id');
            this.getData()
        },
        methods: {
            changeTab(tab) {
                this.tab = tab
            },
            doSubmit(){
                let selector = this.$el;
                $(selector).find('button').attr('disabled', true).text('資料更新中...');
                return axios.post('/admin/certification/save_company_cert',{
                    ...this.formData,
                    id: this.pageId
                }).then(({data})=>{
                    alert(data.result)
                    location.reload()
                })
            },
            getData(){
                axios.get('/admin/certification/getSkbank',{
                    params:{
                        id: this.pageId
                    }
                }).then(({data})=>{
                    mergeDeep(this.formData, data.response)
                })
            }
        },
    })
</script>