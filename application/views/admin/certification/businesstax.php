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
        if ($(this).find(':selected') === 'other') {
            $('input#fail').css('display', 'block').attr('disabled', false);
        } else {
            $('input#fail').css('display', 'none').attr('disabled', true);
        }
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
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastOneYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastOneYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastOneYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastOneYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastOneYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastOneYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastTwoYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastTwoYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastTwoYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastTwoYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastTwoYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastTwoYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastTwoYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastThreeYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastThreeYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastThreeYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastThreeYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastThreeYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastThreeYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastThreeYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastFourYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastFourYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastFourYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastFourYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastFourYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastFourYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastFourYearInvoiceAmountM11M12"></td>
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
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastOneYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastOneYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastOneYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastOneYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastOneYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近一年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastOneYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastTwoYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastTwoYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastTwoYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastTwoYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastTwoYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastTwoYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近二年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastTwoYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastThreeYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastThreeYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastThreeYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastThreeYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastThreeYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastThreeYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近三年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastThreeYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅年份</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.businessTaxLastFourYear"
                                                   placeholder="格式:YYY"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅01~02月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastFourYearInvoiceAmountM1M2"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅03~04月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastFourYearInvoiceAmountM3M4"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅05~06月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastFourYearInvoiceAmountM5M6"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅07~08月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastFourYearInvoiceAmountM7M8"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅09~10月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastFourYearInvoiceAmountM9M10"></td>
                                    </tr>
                                    <tr>
                                        <td><span>近四年申報營業稅11~12月開立發票金額</span></td>
                                        <td><input class="sk-input form-control" type="number" v-model="formData.lastFourYearInvoiceAmountM11M12"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </form>
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
                            <div class="form-group">
                                <h1>圖片/文件</h1>
                                <fieldset>
                                    <div class="form-group">
                                        <?php
                                        if (isset($images))
                                        { // 擴大信保【後】的APP上傳圖片，分成近1/2/3/4年
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
                                                echo '<hr/>';
                                            }
                                        }
                                        if (isset($content['business_tax_image']))
                                        { // 擴大信保【前】的APP上傳圖片
                                            $content['business_tax_image'] = ! is_array($content['business_tax_image'])
                                                ? array($content['business_tax_image'])
                                                : $content['business_tax_image'];
                                            foreach ($content['business_tax_image'] as $key => $value)
                                            {
                                                if (empty($value)) continue; ?>
                                                <a href="<?= $value ?>" data-fancybox="images">
                                                    <img src="<?= $value ?>"
                                                         style='width:30%;max-width:400px'>
                                                </a>
                                            <?php }
                                            echo '<hr/>';
                                        }
                                        if ( ! empty($content['file_list']['image']))
                                        { // 擴大信保【後】的Web上傳圖片
                                            foreach ($content['file_list']['image'] as $key => $value)
                                            {
                                                if (empty($value['url'])) continue; ?>
                                                <a href="<?= $value['url'] ?>" data-fancybox="images">
                                                    <img src="<?= $value['url'] ?>"
                                                         style='width:30%;max-width:400px'>
                                                </a>
                                            <?php }
                                            echo '<hr/>';
                                        }
                                        if ( ! empty($content['file_list']['file']))
                                        { // 擴大信保【後】的Web上傳PDF
                                            foreach ($content['file_list']['file'] as $key => $value)
                                            {
                                                if (empty($value['url'])) continue; ?>
                                                <a href="<?= $value['url'] ?>">
                                                    <i class="fa fa-file"> <?= $value['file_name'] ?? '檔案' ?></i>
                                                </a>
                                            <?php }
                                            echo '<hr/>';
                                        } ?>
                                    </div>
                                </fieldset>
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
                    lastOneYearInvoiceAmountM1M2:'',
                    lastOneYearInvoiceAmountM3M4:'',
                    lastOneYearInvoiceAmountM5M6:'',
                    lastOneYearInvoiceAmountM7M8:'',
                    lastOneYearInvoiceAmountM9M10:'',
                    lastOneYearInvoiceAmountM11M12:'',
                    lastTwoYearInvoiceAmountM1M2:'',
                    lastTwoYearInvoiceAmountM3M4:'',
                    lastTwoYearInvoiceAmountM5M6:'',
                    lastTwoYearInvoiceAmountM7M8:'',
                    lastTwoYearInvoiceAmountM9M10:'',
                    lastTwoYearInvoiceAmountM11M12:'',
                    lastThreeYearInvoiceAmountM1M2:'',
                    lastThreeYearInvoiceAmountM3M4:'',
                    lastThreeYearInvoiceAmountM5M6:'',
                    lastThreeYearInvoiceAmountM7M8:'',
                    lastThreeYearInvoiceAmountM9M10:'',
                    lastThreeYearInvoiceAmountM11M12:'',
                    lastFourYearInvoiceAmountM1M2:'',
                    lastFourYearInvoiceAmountM3M4:'',
                    lastFourYearInvoiceAmountM5M6:'',
                    lastFourYearInvoiceAmountM7M8:'',
                    lastFourYearInvoiceAmountM9M10:'',
                    lastFourYearInvoiceAmountM11M12:'',
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
                    skbank_form: {...this.formData},
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
                    mergeDeep(this.formData, data.response.skbank_form)
                })
            }
        },
    })
</script>