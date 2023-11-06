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

    $(document).ready(function () {
        var university = $("#university").text();
        var account = $("#account").text();
        fetchSipData({university, account});
        fetchSipRisk();
        setInterval(regularCheckSipResult, 5000);

        // 爬蟲資料抓取
        function fetchSipData({university, account}) {
            $.ajax({
                type: "GET",
                url: "/admin/scraper/sip" + "?university=" + university + "&account=" + account,
                success: function (response) {
                    if (response.status.code != 200) {
                        return false;
                    }
                    sipData = response.response;
                    fillSipData(sipData.university, sipData.result, sipData.school_status);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest.status);
                    console.log(XMLHttpRequest.readyState);
                    console.log(textStatus);
                },
            });
        }

        function fillSipData(university, dataResponse, school_status) {
            if (!university || !dataResponse) {
                return false;
            }
            // console.log(dataResponse);
            const status = (st)=>{
                if(st){
                    return `
					<button type="button" class="btn btn-success btn-circle">
						<i class="fa fa-check"></i>
					</button>
				`
                }
                return `
				<button type="button" class="btn btn-danger btn-circle">
					<i class="fa fa-times"></i>
				</button>
			`
            }
            const universityStatus = (st) => {
                switch (st) {
                    case 0: return '驗證碼問題'
                    case 1: return '正常狀態'
                    case 2: return '黑名單學校'
                    case 3: return 'server問題'
                    case 4: return 'VPN相關問題'
                    case 5: return '要求改密碼'
                    case 6: return '問卷問題'
                    case 7: return '不穩定 有時有未知異常'
                    default: return 'unKnown Error'
                }
            }
            $('#name-scraper').html(dataResponse.name + status(school_status.deep_scrape_enabled.name));
            $('#id-scraper').html(dataResponse.idNumber + status(school_status.deep_scrape_enabled.idNumber));
            $('#sip-university').text(university + ' - ' + universityStatus(school_status.status));
            $('#department-scraper').html(dataResponse.department + status(school_status.deep_scrape_enabled.department));
            $('#school-status').html(dataResponse.schoolStatus + status(school_status.deep_scrape_enabled.schoolStatus));
            $('#student-phone').html(dataResponse.studentPhone + status(school_status.deep_scrape_enabled.studentPhone));
            $('#home-phone').html(dataResponse.homePhone + status(school_status.deep_scrape_enabled.homePhone));
            $('#guardian').html(dataResponse.guardian + status(school_status.deep_scrape_enabled.guardian));
            $('#guardian-phone').html(dataResponse.guardianPhone + status(school_status.deep_scrape_enabled.guardianPhone));
            $('#communication-address').html(dataResponse.communicationAddress + status(school_status.deep_scrape_enabled.communicationAddress));
            $('#household-address').html(dataResponse.householdAddress + status(school_status.deep_scrape_enabled.householdAddress));
            $('#latest-grades').html(dataResponse.latestGrades + status(school_status.deep_scrape_enabled.latestGrades));
        }

        function regularCheckSipResult() {
            var sipResult = $("#sip-login-info").text();

            if (sipResult != '爬蟲尚未開始' && sipResult != '爬蟲正在執行中') {
                return;
            }

            fetchSipLogin(university, account)
        }

        function fetchSipRisk(){
            $.ajax({
                type: "GET",
                url: `/admin/certification/getMeta?id=<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>`,
                dataType: "json",
                success: function (response) {
                    if (response.status.code == 200 && response.response != '') {
                        Object.keys(response.response).forEach(function (key) {
                            if ($(`[name='${key}']`).length) {
                                if ($(`[name='${key}']`).is("input")) {
                                    $(`[name='${key}']`).val(response.response[key]);
                                } else {
                                    let $select = $(`[name='${key}']`).selectize();
                                    let selectize = $select[0].selectize;
                                    selectize.setValue(selectize.search(response.response[key]).items[0].id);
                                }
                            }
                        })
                    } else {
                        console.log(response);
                    }
                },
                error: function (error) {
                    alert(error);
                }
            });
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
                            <div class="form-group">
                                <label>學校名稱</label>
                                <p id="university"
                                   class="form-control-static"><?= $content['school'] ?? '' ?></p>
                            </div>
                            <div class="form-group">
                                <label>學制</label>
                                <p class="form-control-static"><?= $school_system[$content['system'] ?? ''] ?? '' ?></p>
                            </div>
                            <div class="form-group">
                                <label>學門</label>
                                <p class="form-control-static"><?= isset($content['major']) ? $content['major'] : "" ?></p>
                            </div>
                            <div class="form-group">
                                <label>系所</label>
                                <p class="form-control-static"><?= $content['department'] ?? '' ?></p>
                            </div>
                            <div class="form-group">
                                <label>年級</label>
                                <p class="form-control-static"><?= isset($content['grade']) ? $content['grade'] : "" ?></p>
                            </div>
                            <div class="form-group">
                                <label>學號</label>
                                <p class="form-control-static"><?= isset($content['student_id']) ? $content['student_id'] : "" ?></p>
                            </div>
                            <div class="form-group">
                                <label>校內電子信箱</label>
                                <p class="form-control-static"><?= isset($content['email']) ? $content['email'] : "" ?></p>
                                <p class="form-control-static">驗證狀態:
                                    <?php
                                    if (isset($content['email']) && ! empty($content['email']))
                                    {
                                        if (isset($content['email_verify_status']) && $content['email_verify_status'] == TRUE)
                                        {
                                            echo '已驗證信箱';
                                        }
                                        else
                                        {
                                            echo '尚未驗證信箱';
                                        }
                                    }
                                    else
                                    {
                                        echo '不進行驗證';
                                    }
                                    ?>
                                </p>
                                <p class="form-control-static">驗證時間:
                                    <?php
                                    if (isset($content['email']) && ! empty($content['email']))
                                    {
                                        if (isset($content['email_verify_time']) && is_numeric($content['email_verify_time']))
                                        {
                                            echo date('Y-m-d H:i:s', $content['email_verify_time']);
                                        }
                                        else
                                        {
                                            echo '';
                                        }
                                    }
                                    else
                                    {
                                        echo '不進行驗證';
                                    }
                                    ?></p>
                            </div>
                            <div class="form-group">
                                <label>SIP帳號</label>
                                <p id="account"
                                   class="form-control-static"><?= isset($content['sip_account']) ? $content['sip_account'] : "" ?></p>
                            </div>
                            <div class="form-group">
                                <label>SIP密碼</label>
                                <p id="password"
                                   class="form-control-static"><?= isset($content['sip_password']) ? $content['sip_password'] : "" ?></p>
                            </div>

                            <div class="form-group">
                                <label>SIP 網址</label><br>
                                <? if (isset($content['sipURL']) && ! empty($content['sipURL'])) { ?>
                                    <a href="<?= $content['sipURL']?>" target="_blank"> <?=$content['sipUniversity']?> </a>
                                <? } else { echo "無"; } ?>
                            </div>

                            <div class="form-group">
                                <label>SIP結果</label><br>
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th>姓名</th>
                                        <td id="name-scraper"></td>
                                        <th>身分證</th>
                                        <td id="id-scraper"></td>
                                    </tr>
                                    <tr>
                                        <th>學校</th>
                                        <td id="sip-university"></td>
                                        <th>科系</th>
                                        <td id="department-scraper"></td>
                                    </tr>
                                    <tr>
                                        <th>在學狀態</th>
                                        <td id="school-status"></td>
                                        <th>手機</th>
                                        <td id="student-phone"></td>
                                    </tr>
                                    <tr>
                                        <th>家用電話</th>
                                        <td id="home-phone"></td>
                                        <th>緊急聯絡人</th>
                                        <td id="guardian"></td>
                                    </tr>
                                    <tr>
                                        <th>緊急聯絡人電話</th>
                                        <td id="guardian-phone"></td>
                                        <th>通訊地址</th>
                                        <td id="communication-address"></td>
                                    </tr>
                                    <tr>
                                        <th>戶籍地址</th>
                                        <td id="household-address"></td>
                                        <th>近一學期成績</th>
                                        <td id="latest-grades"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <label>預計畢業時間</label>
                                <p class="form-control-static"><?= isset($content['graduate_date']) ? $content['graduate_date'] : "未填寫" ?></p>
                            </div>
                            <div class="form-group">
                                <form role="form" action="/admin/certification/save_meta" method="post">
                                    <table class="table table-striped table-bordered table-hover dataTable">
                                        <tbody>
                                        <tr style="text-align: center;">
                                            <td colspan="2"><span>風控因子確認</span></td>
                                        </tr>
                                        <tr hidden>
                                            <td><span>徵提資料ID</span></td>
                                            <td><input class="meta-input" type="text" name="id"
                                                       value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span>近一學期成績</span></td>
                                            <td><input class="meta-input" type="text" name="last_grade" placeholder="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <button type="submit" class="btn btn-primary" style="margin:0 45%;">送出
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                            <form role="form" method="post" action="/admin/certification/user_certification_edit">
                                <div class="form-group">
                                    <label>專業證書加分 (最高4級)</label>
                                    <?php if ($data->status == 1) { ?>
                                        <p><?= isset($content['license_level']) && $content['license_level'] > 0 ? $content['license_level'] . "級" : "專業證書不加分" ?></p>
                                    <? } else { ?>
                                        <select name="license_level" class="form-control">
                                            <option value="0" <?= isset($content['license_level']) && $content['license_level'] == 0 ? "selected" : "" ?>>
                                                不加分
                                            </option>
                                            <option value="1" <?= isset($content['license_level']) && $content['license_level'] == 1 ? "selected" : "" ?>>
                                                1級
                                            </option>
                                            <option value="2" <?= isset($content['license_level']) && $content['license_level'] == 2 ? "selected" : "" ?>>
                                                2級
                                            </option>
                                            <option value="3" <?= isset($content['license_level']) && $content['license_level'] == 3 ? "selected" : "" ?>>
                                                3級
                                            </option>
                                            <option value="4" <?= isset($content['license_level']) && $content['license_level'] == 4 ? "selected" : "" ?>>
                                                4級
                                            </option>
                                        </select>
                                    <? } ?>
                                </div>
                                <div class="form-group">
                                    <label>競賽作品加分 (最高4級)</label>
                                    <?php if ($data->status == 1) { ?>
                                        <p><?= isset($content['game_work_level']) && $content['game_work_level'] > 0 ? $content['game_work_level'] . "級" : "競賽作品不加分" ?></p>
                                    <? } else { ?>
                                        <select name="game_work_level" class="form-control">
                                            <option value="0" <?= isset($content['game_work_level']) && $content['game_work_level'] == 0 ? "selected" : "" ?>>
                                                不加分
                                            </option>
                                            <option value="1" <?= isset($content['game_work_level']) && $content['game_work_level'] == 1 ? "selected" : "" ?>>
                                                1級
                                            </option>
                                            <option value="2" <?= isset($content['game_work_level']) && $content['game_work_level'] == 2 ? "selected" : "" ?>>
                                                2級
                                            </option>
                                            <option value="3" <?= isset($content['game_work_level']) && $content['game_work_level'] == 3 ? "selected" : "" ?>>
                                                3級
                                            </option>
                                            <option value="4" <?= isset($content['game_work_level']) && $content['game_work_level'] == 4 ? "selected" : "" ?>>
                                                4級
                                            </option>
                                        </select>
                                    <? } ?>
                                </div>
                                <div class="form-group">
                                    <label>專家調整 (最高3級)</label>
                                    <?php if ($data->status == 1) { ?>
                                        <p><?= isset($content['pro_level']) && $content['pro_level'] > 0 ? $content['pro_level'] . "級" : "專家調整不加分" ?></p>
                                    <? } else { ?>
                                        <select name="pro_level" class="form-control">
                                            <option value="0" <?= isset($content['pro_level']) && $content['pro_level'] == 0 ? "selected" : "" ?>>
                                                不加分
                                            </option>
                                            <option value="1" <?= isset($content['pro_level']) && $content['pro_level'] == 1 ? "selected" : "" ?>>
                                                1級
                                            </option>
                                            <option value="2" <?= isset($content['pro_level']) && $content['pro_level'] == 2 ? "selected" : "" ?>>
                                                2級
                                            </option>
                                            <option value="3" <?= isset($content['pro_level']) && $content['pro_level'] == 3 ? "selected" : "" ?>>
                                                3級
                                            </option>
                                        </select>
                                    <? } ?>
                                </div>
                                <br/>
                                <div class="form-group">
                                    <label>驗證結果</label>
                                    <?php
                                    if ($remark && isset($remark['verify_result']) && is_array($remark['verify_result']))
                                    {
                                        foreach ($remark['verify_result'] as $verify_result)
                                        {
                                            echo '<p style="color:red;">' . $verify_result . '</p>';
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="form-group">
                                    <label>備註</label>
                                    <?php
                                    if ($remark)
                                    {
                                        if (isset($remark["fail"]) && $remark["fail"] && ! is_array($remark['fail']))
                                        {
                                            echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark["fail"] . '</p>';
                                        }
                                        if (isset($remark["fail"]) && $remark["fail"] && is_array($remark['fail']))
                                        {
                                            foreach ($remark['fail'] as $fail_result)
                                            {
                                                echo '<p style="color:red;">' . $fail_result . '</p>';
                                            }
                                        }
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
                                <form role="form" method="post" action="/admin/certification/user_certification_edit">
                                    <h4>審核人員確認</h4>
                                    <?php
                                    $admin_edit = $content['admin_edit'] ?? [];
                                    $input_disabled = $data->status != CERTIFICATION_STATUS_PENDING_TO_REVIEW ? 'disabled' : '';
                                    ?>
                                    <fieldset>
                                        <div class="form-group">
                                            <table class="admin-edit">
                                                <tr>
                                                    <td><label>學校名稱</label></td>
                                                    <td>
                                                        <select name="admin_edit[school]" <?= $input_disabled ?> class="form-control">
                                                            <option value=""></option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label>學制</label></td>
                                                    <td>
                                                        <select name="admin_edit[system]" <?= $input_disabled ?> class="form-control">
                                                            <option value=""></option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label>系所</label></td>
                                                    <td>
                                                        <select name="admin_edit[department]" <?= $input_disabled ?> class="form-control">
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><label>實名認證姓名</label></td>
                                                    <td>
                                                        <input type="text" class="form-control"
                                                               value="<?= $cert_identity_name ?? '' ?>"
                                                               disabled>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </fieldset>
                                    <h4>審核</h4>
                                    <fieldset>
                                        <div class="form-group">
                                            <select id="status" name="status" class="form-control"
                                                    onchange="check_fail();">
                                                <?php foreach ($status_list as $key => $value) { ?>
                                                    <option value="<?= $key ?>" <?= $data->status == $key ? "selected" : "" ?>><?= $value ?></option>
                                                <?php } ?>
                                            </select>
                                            <input type="hidden" name="id"
                                                   value="<?= isset($data->id) ? $data->id : ""; ?>">
                                            <input type="hidden" name="from" value="<?= isset($from) ? $from : ""; ?>">
                                        </div>
                                        <div class="form-group" id="fail_div" style="display:none">
                                            <label>失敗原因</label>
                                            <select id="fail" name="fail" class="form-control">
                                                <option value="" disabled selected>選擇回覆內容</option>
                                                <?php foreach ($certifications_msg[2] as $key => $value) { ?>
                                                    <option <?= $data->status == $value ? "selected" : "" ?>><?= $value ?></option>
                                                <?php } ?>
                                                <option value="other">其它</option>
                                            </select>
                                            <input type="text" class="form-control" id="fail" name="fail"
                                                   value="<?= $remark && isset($remark["fail"]) && ! is_array($remark["fail"]) ? $remark["fail"] : ""; ?>"
                                                   style="background-color:white!important;display:none"
                                                   disabled="false">
                                        </div>
                                        <button type="submit" class="btn btn-primary">送出</button>
                                    </fieldset>
                                </form>

                        </div>
                        <div class="col-lg-6">
                            <h1>圖片</h1>
                            <fieldset disabled>
                                <div class="form-group">
                                    <label for="disabledSelect">學生證正面照</label><br>
                                    <table>
                                        <tr>
                                            <td>
                                                <a href="<?= $content['front_image'] ?>" data-fancybox="images">
                                                    <img src="<?= $content['front_image'] ?>"
                                                         style='width:100%;max-width:200px'>
                                                </a>
                                            </td>
                                            <td>
                                                <label>OCR辨識結果</label><br/>
                                                <label>學校名稱：</label><?= $content['ocr_parser']['content']['university']['name'] ?? '' ?><br/>
                                                <label>學制：</label><?= $content['ocr_parser']['content']['student']['academic_degree'] ?? '' ?><br/>
                                                <label>系所：</label><?= $content['ocr_parser']['content']['student']['department'] ?? '' ?><br/>
                                                <label>姓名：</label><?= $content['ocr_parser']['content']['student']['name'] ?? '' ?><br/>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <label>學生證背面照</label><br>
                                    <a href="<?= $content['back_image'] ?>" data-fancybox="images">
                                        <img src="<?= $content['back_image'] ?>" style='width:100%;max-width:200px'>
                                    </a>
                                </div>
                                <?
                                if (isset($content['transcript_image']))
                                {
                                    ! is_array($content['transcript_image']) ? $content['transcript_image'] = [$content['transcript_image']] : '';
                                    echo '<div class="form-group"><label for="disabledSelect">成績單</label><br>';
                                    foreach ($content['transcript_image'] as $key => $value)
                                    {
                                        echo '<a href="' . $value . '" data-fancybox="images"><img src="' . $value . '" style="width:100%;max-width:200px"></a>';
                                    }
                                    echo '</div>';
                                }
                                ?>
                                <?php if (isset($content['programming_language']) || isset($content['pro_certificate']) || isset($content['game_work']))
                                {
                                    echo '<br /><br /><br /><h4>【其他輔助證明】</h4>';
                                    if (isset($content['programming_language']))
                                    {
                                        echo '<div class="form-group"><label for="disabledSelect">專業語言</label><br>';
                                        if ($techie_lang)
                                        {
                                            echo '程式語言：' . implode('、', $techie_lang) . '<br/>';
                                        }
                                        if ($other_lang)
                                        {
                                            echo '程式語言(自填)：' . implode('、', $other_lang);
                                        }

                                        echo '</div>';
                                    }
                                    if (isset($content['pro_certificate_image']))
                                    {
                                        echo '<div class="form-group"><label for="disabledSelect"><h4>專業證書</h4></label><br>';

                                        if (isset($content['pro_certificate'])){

                                            $arr_pro_certificate = explode(',', $content['pro_certificate']);
                                        }
                                        foreach ($content['pro_certificate_image'] as $key => $value)
                                        {
                                            echo '<a href="' . $value . '" data-fancybox="images"><img src="' . $value . '" style="width:100%;max-width:200px"></a><br>';
                                            echo '圖片說明：' . (isset($arr_pro_certificate[$key]) && ! empty($arr_pro_certificate[$key]) ? $arr_pro_certificate[$key] : '未填寫說明') . "<br><br>";
                                        }
                                        echo '</div><br />';
                                    }
                                    if (isset($content['game_work_image']))
                                    {
                                        echo '<div class="form-group"><label for="disabledSelect"><h4>競賽作品</h4></label><br>';

                                        if (isset($content['pro_certificate'])){
                                            
                                            $arr_game_work = explode(',', $content['game_work']);
                                        }
                                        foreach ($content['game_work_image'] as $key => $value)
                                        {
                                            echo '<a href="' . $value . '" data-fancybox="images"><img src="' . $value . '" style="width:100%;max-width:200px"></a><br>';
                                            echo '圖片說明：' . (isset($arr_game_work[$key]) && ! empty($arr_game_work[$key]) ? $arr_game_work[$key] : '未填寫說明') . "<br><br>";
                                        }
                                        echo '</div>';
                                    }
                                    echo '<br /><br /><br />';
                                } ?>

                            </fieldset>
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
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    $(document).ready(function () {
        // 學制
        const system_list = <?= json_encode($config['school_system_list'] ?? []) ?>;
        const system_value = '<?= $content['admin_edit']['system'] ?? $content['system'] ?? '' ?>';
        let $admin_edit_system = $('select[name="admin_edit[system]"]');
        $.each(system_list, function (key, value) {
            $admin_edit_system.append($('<option></option>').text(value).val(key));
        });
        $admin_edit_system.val(system_value);

        // 學校名稱 + 系所
        const school_value = '<?= $content['admin_edit']['school'] ?? $content['school'] ?? '' ?>';
        const department_value = '<?= $content['admin_edit']['department'] ?? $content['department'] ?? '' ?>';
        let $admin_edit_school = $('select[name="admin_edit[school]"]');
        let $admin_edit_department = $('select[name="admin_edit[department]"]');
        var school_department_list;
        axios({
            method: 'get',
            url: '/api/v2/website/department'
        }).then(resp => {
            school_department_list  = resp.data.data.list;
            console.log(school_department_list)
            if (!school_department_list) {
                return;
            }
            $.each(school_department_list, function (key, value) {
                $admin_edit_school.append($('<option></option>').text(key).val(key));
            });
            $admin_edit_school.val(school_value).trigger('change');
            $admin_edit_department.val(department_value);
        });
        $admin_edit_school.on('change', function () {
            let department_disabled = $admin_edit_department.prop('disabled');
            $admin_edit_department.prop('disabled', true).find('option').remove();
            $admin_edit_department.append($('<option></option>'));
            let this_school_value = $(this).val();
            const discipline_list = school_department_list[this_school_value].discipline;
            $.each(discipline_list, function (discipline, department_list) {
                $.each(department_list, function (key, value) {
                    $admin_edit_department.append($('<option></option>').text(value).val(value));
                });
            })
            $admin_edit_department.prop('disabled', department_disabled);
        });
    });
</script>