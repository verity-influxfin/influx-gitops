<script type="text/javascript">
    function check_fail() {
        var status = $('#status :selected').val();
        if (status == 2) {
            $('#fail_div').show();
        } else {
            $('#fail_div').hide();
        }
    }

    $(document).off("change", "select#fail").on("change", "select#fail", function() {
        let selector = $('input#fail2');
        if ($(this).find(':selected').attr('value') === 'other') {
            selector.css('display', 'block').attr('disabled', false);
        } else {
            selector.css('display', 'none').attr('disabled', true);
        }
    });
</script>
<style>
    table.admin-edit td {
        padding: 1px 3px 0 3px !important;
    }
</style>
<?php
$certification_name = $certification_list[$data->certification_id] ?? '';
$user_id = $data->user_id ?? '';
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $certification_name; ?></h1>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= $certification_name; ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe" href="<?= admin_url('User/display?id=' . $user_id) ?>">
                                    <p><?= $user_id ?></p>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <h1>圖片/檔案</h1>
                            <fieldset>
                                <div class="form-group">
                                    <label>土地建物謄本</label><br>
                                    <?php
                                    if (!empty($content['transactions_image']) && is_array($content['transactions_image'])) {
                                        $index = 0;
                                        foreach ($content['transactions_image'] as $image) { ?>
                                            <a href="<?= $image ?>" data-fancybox="images">
                                                <img src="<?= $image ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                    }
                                    echo "<br/>";
                                    if (!empty($content['pdf']) && is_array($content['pdf'])) {
                                        $index = 0;
                                        foreach ($content['pdf'] as $value) { ?>
                                            <a href="<?= $value ?>" class="btn btn-info" style="margin: 1px 1px 1px 1px">
                                                檔案<?= ++$index; ?>
                                            </a>
                                    <?php }
                                    } ?>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <?php
                                    if (!empty($ocr['upload_page'])) {
                                    ?>
                                        <div class="form-group" style="background:#f5f5f5;border-style:double;">
                                            <?= $ocr['upload_page']; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </fieldset>
                        </div>
                        <!-- OCR -->
                        <div class="col-lg-12 table-responsive">
                            <h1>OCR辨識結果</h1>
                            <table border="2" cellpadding="2" class="table">
                                <tbody>
                                    <tr style="font-weight: bold; background-color: #aaa;">
                                        <td colspan="12">
                                            <h3>不動產鑑估表</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>案號：</td>
                                        <td>202211001</td>
                                        <td>所有權人：</td>
                                        <td></td>
                                        <td>原因發生日期</td>
                                        <td>2019/5/2</td>
                                        <td>登記原因：</td>
                                        <td colspan="2"></td>
                                        <td>鑑估日期</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="3">土地標示部</td>
                                        <td rowspan="3">使用分區：</td>
                                        <td rowspan="3">(空白)</td>
                                        <td rowspan="3">使用地類別：</td>
                                        <td rowspan="3">(空白)</td>
                                        <td rowspan="3">其他登記事項</td>
                                        <td>土地標示部</td>
                                        <td colspan="4">...</td>
                                    </tr>
                                    <tr>
                                        <td>土地所有權部</td>
                                        <td colspan="4">...</td>
                                    </tr>
                                    <tr>
                                        <td>土地他項權利部</td>
                                        <td colspan="4">...</td>
                                    </tr>
                                    <tr>
                                        <td>地號</td>
                                        <td>面積</td>
                                        <td>持分分子</td>
                                        <td>持分分母</td>
                                        <td>持分面積</td>
                                        <td>公告土地現值</td>
                                        <td>前次移轉現值</td>
                                        <td>前次移轉時間</td>
                                        <td>持有年限</td>
                                        <td>物價指數</td>
                                        <td>公告增值稅</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2">合計</td>
                                        <td rowspan="2"></td>
                                        <td rowspan="2"></td>
                                        <td rowspan="2"></td>
                                        <td>100</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td rowspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td>100</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <!-- second -->
                                    <tr>
                                        <td colspan="11">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>建物門牌:</td>
                                        <td colspan="3"></td>
                                        <td>建物坐落地號</td>
                                        <td colspan="2"></td>
                                        <td>建築完成日期</td>
                                        <td></td>
                                        <td>屋齡</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>建物登記建號</td>
                                        <td colspan="3"></td>
                                        <td>主要用途</td>
                                        <td></td>
                                        <td>主要建材</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>標示部其他登記事項</td>
                                        <td colspan="10"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>層次/層數</td>
                                        <td>面積</td>
                                        <td>持分分子</td>
                                        <td>持分分母</td>
                                        <td>持分面積</td>
                                        <td>面積/坪</td>
                                        <td>每坪單價</td>
                                        <td colspan="2">鑑估總價</td>
                                        <td>備註</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>建物總面積</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td> 204.83 </td>
                                        <td> 61.96 </td>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                        <td>鑑估總值</td>
                                        <td colspan="2"> - </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                        <td>鑑估淨值</td>
                                        <td colspan="2"> - </td>
                                    </tr>
                                    <tr>
                                        <td>他項權利部</td>
                                        <td>登記次序</td>
                                        <td>登記日期</td>
                                        <td>權利種類</td>
                                        <td>登記原因</td>
                                        <td colspan="2">權利人</td>
                                        <td>債權額比例</td>
                                        <td colspan="3">擔保債權總金額</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table border="1" cellpadding="2" cellspacing="0" class="table mt-2">
                                <tbody>
                                    <tr style="font-weight: bold; background-color: #aaa;">
                                        <td colspan="9">
                                            <h3>DD報告</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>社區名稱：</td>
                                        <td></td>
                                        <td></td>
                                        <td>公設比</td>
                                        <td></td>
                                        <td></td>
                                        <td>有無現勘：</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>建物維修狀況：</td>
                                        <td>□正常</td>
                                        <td>□有漏水、壁癌</td>
                                        <td>□外觀殘破</td>
                                        <td>□地下室積水</td>
                                        <td>□陰暗破舊</td>
                                        <td>□其他:</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>使用情形</td>
                                        <td>□自用</td>
                                        <td>□出租</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>臨路狀況：</td>
                                        <td>6米</td>
                                        <td>□4米以下</td>
                                        <td>□未臨路</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>不動產類別：</td>
                                        <td>□公寓</td>
                                        <td>□電梯大厦</td>
                                        <td>□套房</td>
                                        <td>□別墅</td>
                                        <td>□透天厝</td>
                                        <td>□辦公室</td>
                                        <td>□店面、商場</td>
                                        <td>□其他</td>
                                    </tr>
                                    <tr>
                                        <td>嫌惡因素：</td>
                                        <td>□無</td>
                                        <td>□有（宮廟、路沖、墳場、殯葬館）</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>輻射屋/海砂屋查詢</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>特別說明：</td>
                                        <td colspan="8"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- 人員 -->
                        <div class="col-lg-12 table-responsive">
                            <h1>審核人員確認</h1>
                            <table border="2" cellpadding="2" class="table">
                                <tbody>
                                    <tr style="font-weight: bold; background-color: #aaa;">
                                        <td colspan="12">
                                            <h3>不動產鑑估表</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>案號：</td>
                                        <td>202211001</td>
                                        <td>所有權人：</td>
                                        <td></td>
                                        <td>原因發生日期</td>
                                        <td>2019/5/2</td>
                                        <td>登記原因：</td>
                                        <td colspan="2"></td>
                                        <td>鑑估日期</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="3">土地標示部</td>
                                        <td rowspan="3">使用分區：</td>
                                        <td rowspan="3">(空白)</td>
                                        <td rowspan="3">使用地類別：</td>
                                        <td rowspan="3">(空白)</td>
                                        <td rowspan="3">其他登記事項</td>
                                        <td>土地標示部</td>
                                        <td colspan="4">...</td>
                                    </tr>
                                    <tr>
                                        <td>土地所有權部</td>
                                        <td colspan="4">...</td>
                                    </tr>
                                    <tr>
                                        <td>土地他項權利部</td>
                                        <td colspan="4">...</td>
                                    </tr>
                                    <tr>
                                        <td>地號</td>
                                        <td>面積</td>
                                        <td>持分分子</td>
                                        <td>持分分母</td>
                                        <td>持分面積</td>
                                        <td>公告土地現值</td>
                                        <td>前次移轉現值</td>
                                        <td>前次移轉時間</td>
                                        <td>持有年限</td>
                                        <td>物價指數</td>
                                        <td>公告增值稅</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2">合計</td>
                                        <td rowspan="2"></td>
                                        <td rowspan="2"></td>
                                        <td rowspan="2"></td>
                                        <td>100</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td rowspan="2"></td>
                                    </tr>
                                    <tr>
                                        <td>100</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <!-- second -->
                                    <tr>
                                        <td colspan="11">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>建物門牌:</td>
                                        <td colspan="3"></td>
                                        <td>建物坐落地號</td>
                                        <td colspan="2"></td>
                                        <td>建築完成日期</td>
                                        <td></td>
                                        <td>屋齡</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>建物登記建號</td>
                                        <td colspan="3"></td>
                                        <td>主要用途</td>
                                        <td></td>
                                        <td>主要建材</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>標示部其他登記事項</td>
                                        <td colspan="10"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td>層次/層數</td>
                                        <td>面積</td>
                                        <td>持分分子</td>
                                        <td>持分分母</td>
                                        <td>持分面積</td>
                                        <td>面積/坪</td>
                                        <td>每坪單價</td>
                                        <td colspan="2">鑑估總價</td>
                                        <td>備註</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>建物總面積</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td> 204.83 </td>
                                        <td> 61.96 </td>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                        <td>鑑估總值</td>
                                        <td colspan="2"> - </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                        <td>鑑估淨值</td>
                                        <td colspan="2"> - </td>
                                    </tr>
                                    <tr>
                                        <td>他項權利部</td>
                                        <td>登記次序</td>
                                        <td>登記日期</td>
                                        <td>權利種類</td>
                                        <td>登記原因</td>
                                        <td colspan="2">權利人</td>
                                        <td>債權額比例</td>
                                        <td colspan="3">擔保債權總金額</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table border="1" cellpadding="2" cellspacing="0" class="table mt-2">
                                <tbody>
                                    <tr style="font-weight: bold; background-color: #aaa;">
                                        <td colspan="9">
                                            <h3>DD報告</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>社區名稱：</td>
                                        <td></td>
                                        <td></td>
                                        <td>公設比</td>
                                        <td></td>
                                        <td></td>
                                        <td>有無現勘：</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>建物維修狀況：</td>
                                        <td>□正常</td>
                                        <td>□有漏水、壁癌</td>
                                        <td>□外觀殘破</td>
                                        <td>□地下室積水</td>
                                        <td>□陰暗破舊</td>
                                        <td>□其他:</td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>使用情形</td>
                                        <td>□自用</td>
                                        <td>□出租</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>臨路狀況：</td>
                                        <td>6米</td>
                                        <td>□4米以下</td>
                                        <td>□未臨路</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>不動產類別：</td>
                                        <td>□公寓</td>
                                        <td>□電梯大厦</td>
                                        <td>□套房</td>
                                        <td>□別墅</td>
                                        <td>□透天厝</td>
                                        <td>□辦公室</td>
                                        <td>□店面、商場</td>
                                        <td>□其他</td>
                                    </tr>
                                    <tr>
                                        <td>嫌惡因素：</td>
                                        <td>□無</td>
                                        <td>□有（宮廟、路沖、墳場、殯葬館）</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>輻射屋/海砂屋查詢</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>特別說明：</td>
                                        <td colspan="8"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>備註</label>
                                <?php
                                $fail_msg = '';
                                if (!empty($remark['fail'])) {
                                    $fail_msg = $remark['fail'];
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $fail_msg . '</p>';
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label>系統審核</label>
                                <?php
                                if (isset($sys_check)) {
                                    echo '<p class="form-control-static">' . ($sys_check == 1 ? '是' : '否') . '</p>';
                                }
                                ?>
                            </div>
                            <form role="form" method="post" action="/admin/certification/user_certification_edit">
                                <input type="hidden" name="id" value="<?= $data->id ?? '' ?>">
                                <input type="hidden" name="from" value="<?= $from ?? '' ?>">
                                <input type="hidden" name="certification_id" value="<?= $data->certification_id ?? '' ?>">
                                <h4>審核人員確認</h4>
                                <?php
                                $admin_edit = $content['admin_edit'] ?? [];
                                $input_disabled = $data->status != CERTIFICATION_STATUS_PENDING_TO_REVIEW ? 'disabled' : '';
                                ?>
                                <fieldset>
                                    <div class="form-group">
                                        <table class="admin-edit">
                                            <tr>
                                                <td><label>合約地址</label></td>
                                                <td>
                                                    <input type="text" class="form-control" name="" value="<?= '' ?>" disabled>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>房屋門牌地址</label></td>
                                                <td>
                                                    <input type="text" class="form-control" name="" placeholder="還沒做功能" value="<?= '' ?>" <?= $input_disabled ?>>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </fieldset>
                                <h4>審核</h4>
                                <fieldset>
                                    <div class="form-group">
                                        <select id="status" name="status" class="form-control" onchange="check_fail();">
                                            <?php foreach ($status_list as $key => $value) { ?>
                                                <option value="<?= $key ?>" <?= $data->status == $key ? 'selected' : '' ?>><?= $value ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" name="id" value="<?= $data->id ?? '' ?>">
                                        <input type="hidden" name="from" value="<?= $from ?? '' ?>">
                                    </div>
                                    <div class="form-group" id="fail_div" style="display:none">
                                        <label>失敗原因</label>
                                        <select id="fail" name="fail" class="form-control">
                                            <option value="" disabled selected>選擇回覆內容</option>
                                            <?php foreach ($certifications_msg[$data->certification_id] as $key => $value) { ?>
                                                <option <?= $data->status == $value ? 'selected' : '' ?>><?= $value ?></option>
                                            <?php } ?>
                                            <option value="other">其它</option>
                                        </select>
                                        <input type="text" class="form-control" id="fail2" name="fail2" value="<?= $fail_msg; ?>" style="background-color:white!important;display:none">
                                    </div>
                                    <button type="submit" class="btn btn-primary">送出</button>
                                </fieldset>
                            </form>
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
