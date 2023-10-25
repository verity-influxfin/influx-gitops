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
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe" href="<?= admin_url('User/display?id=' . $user_id) ?>">
                                    <p><?= $user_id ?></p>
                                </a>
                            </div>
                            <div class="form-group">
                                <label>備註</label>
                                <?php
                                $fail_msg = '';
                                if ( ! empty($remark['fail']))
                                {
                                    $fail_msg = $remark['fail'];
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $fail_msg . '</p>';
                                }
                                ?>
                            </div>
                            <div class="form-group">
                                <label>系統審核</label>
                                <?php
                                if (isset($sys_check))
                                {
                                    echo '<p class="form-control-static">' . ($sys_check == 1 ? '是' : '否') . '</p>';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <?php
                            if (!empty($content['transactions_image'])
                                && empty($content['pdf']) && empty($ocr['upload_page'])
                            ) {
                                $upload_title = '圖片';
                            } elseif (!empty($content['pdf']) || !empty($ocr['upload_page'])) {
                                $upload_title = '檔案';
                            } else {
                                $upload_title = '圖片/檔案';
                            }
                            ?>
                            <h1><?= $upload_title ?></h1>
                            <fieldset>
                                <div class="form-group">
                                    <label>土地建物謄本</label><br>
                                    <?php
                                    if ( ! empty($content['transactions_image']) && is_array($content['transactions_image']))
                                    {
                                        $index = 0;
                                        foreach ($content['transactions_image'] as $image)
                                        { ?>
                                            <a href="<?= $image ?>" data-fancybox="images">
                                                <img src="<?= $image ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <?php }
                                    }
                                    echo "<br/>";
                                    if ( ! empty($content['pdf']) && is_array($content['pdf']))
                                    {
                                        $index = 0;
                                        foreach ($content['pdf'] as $value)
                                        { ?>
                                            <a href="<?= $value ?>" class="btn btn-info"
                                               style="margin: 1px 1px 1px 1px">
                                                檔案<?= ++$index; ?>
                                            </a>
                                        <?php }
                                    } ?>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <?php
                                    if ( ! empty($ocr['upload_page']))
                                    {
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
                            <?php
                            $content_ary = json_decode($data->content, TRUE);
                            $ocr_parser_ary = $content['ocr_parser']['content'] ?? [];
                            ?>
                            <table border="2" cellpadding="2" class="table">
                                <tbody>
                                <tr style="font-weight: bold; background-color: #aaa;">
                                    <td colspan="12">
                                        <h3>不動產鑑估表</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>案號：</td>
                                    <td><?= $ocr_parser_ary['landPart']['case_no'] ?? ''; ?></td>
                                    <td>所有權人：</td>
                                    <td><?= $ocr_parser_ary['landPart']['owner_str'] ?? ''; ?></td>
                                    <td>原因發生日期</td>
                                    <td><?= $ocr_parser_ary['landPart']['reason_occur_date'] ?? ''; ?></td>
                                    <td>登記原因：</td>
                                    <td colspan="2"><?= $ocr_parser_ary['landPart']['registration_reason_str'] ?? ''; ?></td>
                                    <td>鑑估日期</td>
                                    <td><?= $ocr_parser_ary['landPart']['appraisal_date'] ?? ''; ?></td>
                                </tr>
                                <tr>
                                    <td rowspan="3">土地標示部</td>
                                    <td rowspan="3">使用分區：</td>
                                    <td rowspan="3"><?= $ocr_parser_ary['landPart']['use_zone_str'] ?? ''; ?></td>
                                    <td rowspan="3">使用地類別：</td>
                                    <td rowspan="3"><?= $ocr_parser_ary['landPart']['use_land_type_str'] ?? ''; ?></td>
                                    <td rowspan="3">其他登記事項</td>
                                    <td>土地標示部</td>
                                    <td colspan="4"><?= $ocr_parser_ary['landPart']['landMarking_other_registration_item_str'] ?? ''; ?></td>
                                </tr>
                                <tr>
                                    <td>土地所有權部</td>
                                    <td colspan="4"><?= $ocr_parser_ary['landPart']['landOwnership_other_registration_item_str'] ?? ''; ?></td>
                                </tr>
                                <tr>
                                    <td>土地他項權利部</td>
                                    <td colspan="4"><?= $ocr_parser_ary['landPart']['landOtherRight_other_registration_item_str'] ?? ''; ?></td>
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
                                <?php if ( ! empty($ocr_parser_ary['landPart']['land_list']))
                                {
                                    foreach ($ocr_parser_ary['landPart']['land_list'] as $value)
                                    { ?>
                                        <tr>
                                            <td><?= $value['no'] ?? ''; ?></td>
                                            <td><?= $value['area_num'] ?? ''; ?></td>
                                            <td><?= $value['ownership_portion_numerator_int'] ?? ''; ?></td>
                                            <td><?= $value['ownership_portion_denominator_int'] ?? ''; ?></td>
                                            <td><?= $value['ownership_portion_area_num'] ?? ''; ?></td>
                                            <td><?= $value['public_land_value_int'] ?? ''; ?></td>
                                            <td><?= $value['previous_transfer_value_int'] ?? ''; ?></td>
                                            <td><?= $value['previous_transfer_yyy_mm_str'] ?? ''; ?></td>
                                            <td><?= $value['ownership_portion_years_num'] ?? ''; ?></td>
                                            <td><?= $value['price_index_num'] ?? ''; ?></td>
                                            <td><?= $value['public_value_added_tax_num'] ?? ''; ?></td>
                                        </tr>
                                    <?php }
                                } ?>
                                <tr>
                                    <td rowspan="2">合計</td>
                                    <td rowspan="2"><?= $ocr_parser_ary['landPart']['sum_area_num'] ?? '' ?></td>
                                    <td rowspan="2"></td>
                                    <td rowspan="2"></td>
                                    <td><?= $ocr_parser_ary['landPart']['sum_ownership_portion_area_num'] ?? '' ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td rowspan="2"><?= $ocr_parser_ary['landPart']['sum_public_value_added_tax_num'] ?? '' ?></td>
                                </tr>
                                <tr>
                                    <td><?= $ocr_parser_ary['landPart']['sum_ownership_portion_square_feet_area_str'] ?? '' ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- second -->
                                <tr>
                                    <td colspan="11"></td>
                                </tr>
                                <tr>
                                    <td>建物門牌:</td>
                                    <td colspan="3"><?= $ocr_parser_ary['buildingPart']['address_str'] ?? '' ?></td>
                                    <td>建物坐落地號</td>
                                    <td colspan="2"><?= $ocr_parser_ary['buildingPart']['land_no_str'] ?? '' ?></td>
                                    <td>建築完成日期</td>
                                    <td><?= $ocr_parser_ary['buildingPart']['complete_date'] ?? '' ?></td>
                                    <td>屋齡</td>
                                    <td><?= $ocr_parser_ary['buildingPart']['building_age_num'] ?? '' ?></td>
                                </tr>
                                <tr>
                                    <td>建物登記建號</td>
                                    <td colspan="3"><?= $ocr_parser_ary['buildingPart']['registration_no'] ?? '' ?></td>
                                    <td>主要用途</td>
                                    <td><?= $ocr_parser_ary['buildingPart']['main_use_str'] ?? '' ?></td>
                                    <td>主要建材</td>
                                    <td><?= $ocr_parser_ary['buildingPart']['main_material_str'] ?? '' ?></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>標示部其他登記事項</td>
                                    <td colspan="10"><?= $ocr_parser_ary['buildingPart']['marking_other_registration_item_str'] ?? '' ?></td>
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
                                <?php if ( ! empty($ocr_parser_ary['buildingPart']['buildingItem_list']))
                                {
                                    foreach ($ocr_parser_ary['buildingPart']['buildingItem_list'] as $value)
                                    { ?>
                                        <tr>
                                            <td><?= $value['name'] ?? '' ?></td>
                                            <td><?= $value['floor_str'] ?? '' ?></td>
                                            <td><?= $value['area_num'] ?? '' ?></td>
                                            <td><?= $value['ownership_portion_numerator_int'] ?? '' ?></td>
                                            <td><?= $value['ownership_portion_denominator_int'] ?? '' ?></td>
                                            <td><?= $value['ownership_portion_area_num'] ?? '' ?></td>
                                            <td><?= $value['ownership_portion_square_feet_area_num'] ?? '' ?></td>
                                            <td><?= $value['square_feet_unit_price_int'] ?? '' ?></td>
                                            <td colspan="2"><?= $value['appraisal_total_price_num'] ?? '' ?></td>
                                            <td><?= $value['other_registration_item_str'] ?? '' ?></td>
                                        </tr>
                                    <?php }
                                } ?>
                                <tr>
                                    <td>建物總面積</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><?= $ocr_parser_ary['buildingPart']['sum_ownership_portion_area_num'] ?? ''; ?></td>
                                    <td><?= $ocr_parser_ary['buildingPart']['sum_ownership_portion_square_feet_area_num'] ?? ''; ?></td>
                                    <td></td>
                                    <td colspan="2"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="8"></td>
                                    <td>鑑估總值</td>
                                    <td colspan="2"><?= $ocr_parser_ary['buildingPart']['sum_appraisal_total_sprice_num'] ?? ''; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="8"></td>
                                    <td>鑑估淨值</td>
                                    <td colspan="2"><?= $ocr_parser_ary['buildingPart']['net_sum_appraisal_total_sprice_num'] ?? ''; ?></td>
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
                                <?php if ( ! empty($ocr_parser_ary['buildingPart']['buildingOtherRight_list']))
                                {
                                    foreach ($ocr_parser_ary['buildingPart']['buildingOtherRight_list'] as $value)
                                    { ?>
                                        <tr>
                                            <td><?= $value['rank_str'] ?? ''; ?></td>
                                            <td><?= $value['registration_order_int'] ?? ''; ?></td>
                                            <td><?= $value['registration_date_str'] ?? ''; ?></td>
                                            <td><?= $value['right_type_str'] ?? ''; ?></td>
                                            <td><?= $value['registration_reason_str'] ?? ''; ?></td>
                                            <td colspan="2"><?= $value['owner_name'] ?? ''; ?></td>
                                            <td><?= $value['debt_ratio_str'] ?? ''; ?></td>
                                            <td colspan="3"><?= $value['debt_total_amount_int'] ?? ''; ?></td>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- 人員 -->
                        <form role="form" method="post" action="/admin/certification/user_certification_edit">
                            <div class="col-lg-12 table-responsive">
                                <h1>審核人員確認</h1>
                                <?php
                                $content_ary = json_decode($data->content, TRUE);
                                $admin_edit_ary = $content['admin_edit'] ?? [];
                                ?>
                                <table border="2" cellpadding="2" class="table">
                                    <tbody>
                                    <tr style="font-weight: bold; background-color: #aaa;">
                                        <td colspan="12">
                                            <h3>不動產鑑估表</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>案號：</td>
                                        <td><input type="text" class="form-control" name="admin_edit[landPart][case_no]"
                                                   value="<?= $admin_edit_ary['landPart']['case_no'] ?? ''; ?>"></td>
                                        <td>所有權人：</td>
                                        <td><input type="text" class="form-control"
                                                   name="admin_edit[landPart][owner_str]"
                                                   value="<?= $admin_edit_ary['landPart']['owner_str'] ?? ''; ?>"></td>
                                        <td>原因發生日期</td>
                                        <td><input type="text" class="form-control"
                                                   name="admin_edit[landPart][reason_occur_date]"
                                                   value="<?= $admin_edit_ary['landPart']['reason_occur_date'] ?? ''; ?>">
                                        </td>
                                        <td>登記原因：</td>
                                        <td colspan="2"><input type="text" class="form-control"
                                                               name="admin_edit[landPart][registration_reason_str]"
                                                               value="<?= $admin_edit_ary['landPart']['registration_reason_str'] ?? ''; ?>">
                                        </td>
                                        <td>鑑估日期</td>
                                        <td><input type="text" class="form-control"
                                                   name="admin_edit[landPart][appraisal_date]"
                                                   value="<?= $admin_edit_ary['landPart']['appraisal_date'] ?? ''; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td rowspan="3">土地標示部</td>
                                        <td rowspan="3">使用分區：</td>
                                        <td rowspan="3"><input type="text" class="form-control"
                                                               name="admin_edit[landPart][use_zone_str]"
                                                               value="<?= $admin_edit_ary['landPart']['use_zone_str'] ?? ''; ?>"></input>
                                        </td>
                                        <td rowspan="3">使用地類別：</td>
                                        <td rowspan="3"><input type="text" class="form-control"
                                                               name="admin_edit[landPart][use_land_type_str]"
                                                               value="<?= $admin_edit_ary['landPart']['use_land_type_str'] ?? ''; ?>"></input>
                                        </td>
                                        <td rowspan="3">其他登記事項</td>
                                        <td>土地標示部</td>
                                        <td colspan="4"><input type="text" class="form-control"
                                                               name="admin_edit[landPart][landMarking_other_registration_item_str]"
                                                               value="<?= $admin_edit_ary['landPart']['landMarking_other_registration_item_str'] ?? ''; ?>"></input>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>土地所有權部</td>
                                        <td colspan="4"><input type="text" class="form-control"
                                                               name="admin_edit[landPart][landOwnership_other_registration_item_str]"
                                                               value="<?= $admin_edit_ary['landPart']['landOwnership_other_registration_item_str'] ?? ''; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>土地他項權利部</td>
                                        <td colspan="4"><input type="text" class="form-control"
                                                               name="admin_edit[landPart][landOtherRight_other_registration_item_str]"
                                                               value="<?= $admin_edit_ary['landPart']['landOtherRight_other_registration_item_str'] ?? ''; ?>">
                                        </td>
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
                                    <?php if ( ! empty($admin_edit_ary['landPart']['land_list']))
                                    {
                                        foreach ($admin_edit_ary['landPart']['land_list'] as $key => $value)
                                        { ?>
                                            <tr>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[landPart][land_list][<?= $key ?>][no]"
                                                           value="<?= $value['no'] ?? ''; ?>"></td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[landPart][land_list][<?= $key ?>][area_num]"
                                                           value="<?= $value['area_num'] ?? ''; ?>"></td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[landPart][land_list][<?= $key ?>][ownership_portion_numerator_int]"
                                                           value="<?= $value['ownership_portion_numerator_int'] ?? ''; ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[landPart][land_list][<?= $key ?>][ownership_portion_denominator_int]"
                                                           value="<?= $value['ownership_portion_denominator_int'] ?? ''; ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[landPart][land_list][<?= $key ?>][ownership_portion_area_num]"
                                                           value="<?= $value['ownership_portion_area_num'] ?? ''; ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[landPart][land_list][<?= $key ?>][public_land_value_int]"
                                                           value="<?= $value['public_land_value_int'] ?? ''; ?>"></td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[landPart][land_list][<?= $key ?>][previous_transfer_value_int]"
                                                           value="<?= $value['previous_transfer_value_int'] ?? ''; ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[landPart][land_list][<?= $key ?>][previous_transfer_yyy_mm_str]"
                                                           value="<?= $value['previous_transfer_yyy_mm_str'] ?? ''; ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[landPart][land_list][<?= $key ?>][ownership_portion_years_num]"
                                                           value="<?= $value['ownership_portion_years_num'] ?? ''; ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[landPart][land_list][<?= $key ?>][price_index_num]"
                                                           value="<?= $value['price_index_num'] ?? ''; ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[landPart][land_list][<?= $key ?>][public_value_added_tax_num]"
                                                           value="<?= $value['public_value_added_tax_num'] ?? ''; ?>">
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                    <tr>
                                        <td rowspan="2">合計</td>
                                        <td rowspan="2"><input type="text" class="form-control"
                                                               name="admin_edit[landPart][sum_area_num]"
                                                               value="<?= $admin_edit_ary['landPart']['sum_area_num'] ?? '' ?>">
                                        </td>
                                        <td rowspan="2"></td>
                                        <td rowspan="2"></td>
                                        <td><input type="text" class="form-control"
                                                   name="admin_edit[landPart][sum_ownership_portion_area_num]"
                                                   value="<?= $admin_edit_ary['landPart']['sum_ownership_portion_area_num'] ?? '' ?>">
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td rowspan="2"><input type="text" class="form-control"
                                                               name="admin_edit[landPart][sum_public_value_added_tax_num]"
                                                               value="<?= $admin_edit_ary['landPart']['sum_public_value_added_tax_num'] ?? '' ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control"
                                                   name="admin_edit[landPart][sum_ownership_portion_square_feet_area_str]"
                                                   value="<?= $admin_edit_ary['landPart']['sum_ownership_portion_square_feet_area_str'] ?? '' ?>">
                                        </td>
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
                                        <td colspan="3"><input type="text" class="form-control"
                                                               name="admin_edit[buildingPart][address_str]"
                                                               value="<?= $admin_edit_ary['buildingPart']['address_str'] ?? '' ?>">
                                        </td>
                                        <td>建物坐落地號</td>
                                        <td colspan="2"><input type="text" class="form-control"
                                                               name="admin_edit[buildingPart][land_no_str]"
                                                               value="<?= $admin_edit_ary['buildingPart']['land_no_str'] ?? '' ?>">
                                        </td>
                                        <td>建築完成日期</td>
                                        <td><input type="text" class="form-control"
                                                   name="admin_edit[buildingPart][complete_date]"
                                                   value="<?= $admin_edit_ary['buildingPart']['complete_date'] ?? '' ?>">
                                        </td>
                                        <td>屋齡</td>
                                        <td><input type="text" class="form-control"
                                                   name="admin_edit[buildingPart][building_age_num]"
                                                   value="<?= $admin_edit_ary['buildingPart']['building_age_num'] ?? '' ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>建物登記建號</td>
                                        <td colspan="3"><input type="text" class="form-control"
                                                               name="admin_edit[buildingPart][registration_no]"
                                                               value="<?= $admin_edit_ary['buildingPart']['registration_no'] ?? '' ?>">
                                        </td>
                                        <td>主要用途</td>
                                        <td><input type="text" class="form-control"
                                                   name="admin_edit[buildingPart][main_use_str]"
                                                   value="<?= $admin_edit_ary['buildingPart']['main_use_str'] ?? '' ?>">
                                        </td>
                                        <td>主要建材</td>
                                        <td><input type="text" class="form-control"
                                                   name="admin_edit[buildingPart][main_material_str]"
                                                   value="<?= $admin_edit_ary['buildingPart']['main_material_str'] ?? '' ?>">
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>標示部其他登記事項</td>
                                        <td colspan="10"><input type="text" class="form-control"
                                                                name="admin_edit[buildingPart][marking_other_registration_item_str]"
                                                                value="<?= $admin_edit_ary['buildingPart']['marking_other_registration_item_str'] ?? '' ?>">
                                        </td>
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
                                    <?php if ( ! empty($admin_edit_ary['buildingPart']['buildingItem_list']))
                                    {
                                        foreach ($admin_edit_ary['buildingPart']['buildingItem_list'] as $key => $value)
                                        { ?>
                                            <tr>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingItem_list][<?= $key ?>][name]"
                                                           value="<?= $value['name'] ?? '' ?>"></td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingItem_list][<?= $key ?>][floor_str]"
                                                           value="<?= $value['floor_str'] ?? '' ?>"></td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingItem_list][<?= $key ?>][area_num]"
                                                           value="<?= $value['area_num'] ?? '' ?>"></td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingItem_list][<?= $key ?>][ownership_portion_numerator_int]"
                                                           value="<?= $value['ownership_portion_numerator_int'] ?? '' ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingItem_list][<?= $key ?>][ownership_portion_denominator_int]"
                                                           value="<?= $value['ownership_portion_denominator_int'] ?? '' ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingItem_list][<?= $key ?>][ownership_portion_area_num]"
                                                           value="<?= $value['ownership_portion_area_num'] ?? '' ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingItem_list][<?= $key ?>][ownership_portion_square_feet_area_num]"
                                                           value="<?= $value['ownership_portion_square_feet_area_num'] ?? '' ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingItem_list][<?= $key ?>][square_feet_unit_price_int]"
                                                           value="<?= $value['square_feet_unit_price_int'] ?? '' ?>">
                                                </td>
                                                <td colspan="2"><input type="text" class="form-control"
                                                                       name="admin_edit[buildingPart][buildingItem_list][<?= $key ?>][appraisal_total_price_num]"
                                                                       value="<?= $value['appraisal_total_price_num'] ?? '' ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingItem_list][<?= $key ?>][other_registration_item_str]"
                                                           value="<?= $value['other_registration_item_str'] ?? '' ?>">
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                    <tr>
                                        <td>建物總面積</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><input type="text" class="form-control"
                                                   name="admin_edit[buildingPart][sum_ownership_portion_area_num]"
                                                   value="<?= $admin_edit_ary['buildingPart']['sum_ownership_portion_area_num'] ?? ''; ?>">
                                        </td>
                                        <td><input type="text" class="form-control"
                                                   name="admin_edit[buildingPart][sum_ownership_portion_square_feet_area_num]"
                                                   value="<?= $admin_edit_ary['buildingPart']['sum_ownership_portion_square_feet_area_num'] ?? ''; ?>">
                                        </td>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                        <td>鑑估總值</td>
                                        <td colspan="2"><input type="text" class="form-control"
                                                               name="admin_edit[buildingPart][sum_appraisal_total_sprice_num]"
                                                               value="<?= $admin_edit_ary['buildingPart']['sum_appraisal_total_sprice_num'] ?? ''; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8"></td>
                                        <td>鑑估淨值</td>
                                        <td colspan="2"><input type="text" class="form-control"
                                                               name="admin_edit[buildingPart][net_sum_appraisal_total_sprice_num]"
                                                               value="<?= $admin_edit_ary['buildingPart']['net_sum_appraisal_total_sprice_num'] ?? ''; ?>">
                                        </td>
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
                                    <?php if ( ! empty($admin_edit_ary['buildingPart']['buildingOtherRight_list']))
                                    {
                                        foreach ($admin_edit_ary['buildingPart']['buildingOtherRight_list'] as $key => $value)
                                        { ?>
                                            <tr>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingOtherRight_list][<?= $key ?>][rank_str]"
                                                           value="<?= $value['rank_str'] ?? ''; ?>"></td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingOtherRight_list][<?= $key ?>][registration_order_int]"
                                                           value="<?= $value['registration_order_int'] ?? ''; ?>"></td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingOtherRight_list][<?= $key ?>][registration_date_str]"
                                                           value="<?= $value['registration_date_str'] ?? ''; ?>"></td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingOtherRight_list][<?= $key ?>][right_type_str]"
                                                           value="<?= $value['right_type_str'] ?? ''; ?>">
                                                </td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingOtherRight_list][<?= $key ?>][registration_reason_str]"
                                                           value="<?= $value['registration_reason_str'] ?? ''; ?>"></td>
                                                <td colspan="2"><input type="text" class="form-control"
                                                                       name="admin_edit[buildingPart][buildingOtherRight_list][<?= $key ?>][owner_name]"
                                                                       value="<?= $value['owner_name'] ?? ''; ?>"></td>
                                                <td><input type="text" class="form-control"
                                                           name="admin_edit[buildingPart][buildingOtherRight_list][<?= $key ?>][debt_ratio_str]"
                                                           value="<?= $value['debt_ratio_str'] ?? ''; ?>">
                                                </td>
                                                <td colspan="3"><input type="text" class="form-control"
                                                                       name="admin_edit[buildingPart][buildingOtherRight_list][<?= $key ?>][debt_total_amount_int]"
                                                                       value="<?= $value['debt_total_amount_int'] ?? ''; ?>">
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
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
                                        <td colspan="2"><input type="text" class="form-control"
                                                               name="admin_edit[ddReport][community_name]"
                                                               value="<?= $admin_edit_ary['ddReport']['community_name'] ?? ''; ?>">
                                        </td>
                                        <td>公設比</td>
                                        <td colspan="2"><input type="text" class="form-control"
                                                               name="admin_edit[ddReport][public_ratio]"
                                                               value="<?= $admin_edit_ary['ddReport']['public_ratio'] ?? ''; ?>">
                                        </td>
                                        <td>有無現勘：</td>
                                        <td colspan="2">
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][site_survey]" value="1">
                                                有
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][site_survey]" value="0">
                                                無
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>建物維修狀況：</td>
                                        <td colspan="8">
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][maintenance_condition]" value="1">
                                                正常
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][maintenance_condition]" value="2">
                                                有漏水、壁癌
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][maintenance_condition]" value="3">
                                                地下室積水
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][maintenance_condition]" value="4">
                                                陰暗破舊
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][maintenance_condition]" value="0">
                                                其他：
                                            </label>
                                            <label>
                                                <input type="text" class="form-control"
                                                       name="admin_edit[ddReport][maintenance_condition_other]"
                                                       value="<?= $admin_edit_ary['ddReport']['maintenance_condition_other'] ?? ''; ?>"
                                                       readonly>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>使用情形</td>
                                        <td colspan="8">
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][usage]" value="1">
                                                自用
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][usage]" value="2">
                                                出租
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>臨路狀況：</td>
                                        <td colspan="8">
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][road_side]" value="0">
                                            </label>
                                            <label>
                                                <input type="text" class="form-control"
                                                       name="admin_edit[ddReport][road_side_other]"
                                                       value="<?= $admin_edit_ary['ddReport']['road_side_other'] ?? '' ?>">
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][road_side]" value="1">
                                                4米以下
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][road_side]" value="2">
                                                未臨路
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>不動產類別：</td>
                                        <td colspan="8">
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][building_type]" value="1">
                                                公寓
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][building_type]" value="2">
                                                電梯大厦
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][building_type]" value="3">
                                                套房
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][building_type]" value="4">
                                                別墅
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][building_type]" value="5">
                                                透天厝
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][building_type]" value="6">
                                                辦公室
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][building_type]" value="7">
                                                店面、商場
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][building_type]" value="0">
                                                其他
                                            </label>
                                            <label>
                                                <input type="text" class="form-control"
                                                       name="admin_edit[ddReport][building_type_other]"
                                                       value="<?= $admin_edit_ary['ddReport']['building_type_other'] ?? ''; ?>"
                                                       readonly>
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>嫌惡因素：</td>
                                        <td colspan="8">
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][aversion_reason]" value="0">
                                                無
                                            </label>
                                            <label>
                                                <input type="radio" class="form-check-input"
                                                       name="admin_edit[ddReport][aversion_reason]" value="1">
                                                有（宮廟、路沖、墳場、殯葬館）
                                            </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>輻射屋/海砂屋查詢</td>
                                        <td colspan="8">
                                            <input type="text" class="form-control"
                                                   name="admin_edit[ddReport][radiation_or_sand_house_str]"
                                                   value="<?= $admin_edit_ary['ddReport']['radiation_or_sand_house_str'] ?? ''; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>特別說明：</td>
                                        <td colspan="8">
                                            <textarea class="form-control"
                                                      name="admin_edit[ddReport][memo]"><?= $admin_edit_ary['ddReport']['memo'] ?? '' ?></textarea>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-12">
                                <h4>爬蟲執行狀態</h4>
                                <fieldset>
                                    <div class="form-group">
                                        <!-- 規格尚未確定 -->
                                    </div>
                                    <div class="form-group">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <div class="d-flex aic my-2">
                                                    <div class="mx-3" id="scrapper_status_1" hidden>
                                                        <button type="button" class="btn btn-success btn-circle">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                        <label>執行成功</label>
                                                    </div>
                                                    <div class="mx-3" id="scrapper_status_2" hidden>
                                                        <button type="button" class="btn btn-danger btn-circle">
                                                            <i class="fa fa-times"></i>
                                                        </button>
                                                        <label>執行失敗</label>
                                                    </div>
                                                    <div class="mx-3" id="scrapper_status_4" hidden>
                                                        <button type="button" class="btn btn-warning btn-circle">
                                                            <i class="fa fa-refresh"></i>
                                                        </button>
                                                        <label>執行中</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success"
                                                onclick="recheck_ocr_parser(this)">
                                            重新執行爬蟲
                                        </button>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-lg-12">
                                <input type="hidden" name="id" value="<?= $data->id ?? '' ?>">
                                <input type="hidden" name="from" value="<?= $from ?? '' ?>">
                                <input type="hidden" name="certification_id"
                                       value="<?= $data->certification_id ?? '' ?>">
                                <h4>使用者自填</h4>
                                <fieldset>
                                    <div class="form-group">
                                        <table class="admin-edit">
                                            <tr>
                                                <td><label>房屋門牌地址</label></td>
                                                <td>
                                                    <input type="text" class="form-control" class="form-control" name=""
                                                           value="<?= $house_deed_address_by_user ?? ''; ?>"
                                                           disabled>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </fieldset>
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
                                                    <input type="text" class="form-control" class="form-control" name=""
                                                           value="<?= $house_deed_address ?? '' ?>"
                                                           disabled>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><label>房屋門牌地址</label></td>
                                                <td>
                                                    <input type="text" class="form-control" class="form-control"
                                                           name="admin_edit[address]"
                                                           value="<?= $admin_edit_ary['address'] ?? '' ?>" <?= $input_disabled ?>>
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
                                        <input type="text" class="form-control" class="form-control" id="fail2"
                                               name="fail2"
                                               value="<?= $fail_msg; ?>"
                                               style="background-color:white!important;display:none">
                                    </div>
                                    <button type="submit" class="btn btn-primary">送出</button>
                                </fieldset>
                            </div>
                        </form>
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
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    function recheck_ocr_parser(item) {
        const apiUrl = '/admin/certification';
        const searchParams = url.searchParams;
        $(item).prop('disabled', true).text('執行中...');
        axios.get(`${apiUrl}/recheck_land_and_building_transactions_ocr_parser`, {
            params: {
                id: searchParams.get('id')
            }
        }).then(({data}) => {
            if (data.success === true) {
                alert('執行成功');
            } else {
                alert(data.msg);
            }
            location.reload();
        })
    }

    $(document).ready(function () {
        $('input[name="admin_edit[ddReport][maintenance_condition]"]').on('click', function () {
            if ($(this).val() === '0') {
                $('input[name="admin_edit[ddReport][maintenance_condition_other]"]').prop('readonly', false);
            } else {
                $('input[name="admin_edit[ddReport][maintenance_condition_other]"]').prop('readonly', true).val('');
            }
        });

        $('input[name="admin_edit[ddReport][building_type]"]').on('click', function () {
            if ($(this).val() === '0') {
                $('input[name="admin_edit[ddReport][building_type_other]"]').prop('readonly', false);
            } else {
                $('input[name="admin_edit[ddReport][building_type_other]"]').prop('readonly', true).val('');
            }
        });

        $('input[name="admin_edit[ddReport][road_side]"]').on('click', function () {
            if ($(this).val() === '0') {
                $('input[name="admin_edit[ddReport][road_side_other]"]').prop('readonly', false);
            } else {
                $('input[name="admin_edit[ddReport][road_side_other]"]').prop('readonly', true).val('');
            }
        });

        let maintenance_condition = '<?= $admin_edit_ary['ddReport']['maintenance_condition'] ?? ''; ?>';
        $(`input[name="admin_edit[ddReport][maintenance_condition]"][value="${maintenance_condition}"]`)
            .prop('checked', true)
            .trigger('click');

        let road_side = '<?= $admin_edit_ary['ddReport']['road_side'] ?? ''; ?>';
        $(`input[name="admin_edit[ddReport][road_side]"][value="${road_side}"]`)
            .prop('checked', true)
            .trigger('click');

        let building_type = '<?= $admin_edit_ary['ddReport']['building_type'] ?? ''; ?>';
        $(`input[name="admin_edit[ddReport][building_type]"][value="${building_type}"]`)
            .prop('checked', true)
            .trigger('click');

        let scraper_status = '<?= $ocr_parser_ary['scraperResult']['status_int'] ?? 4; ?>';
        $(`div#scrapper_status_${scraper_status}`).prop('hidden', false);
    });
</script>
