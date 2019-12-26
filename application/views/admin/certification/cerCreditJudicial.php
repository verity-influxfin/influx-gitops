<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><? echo !isset($content) ? '建立' : '' ;echo '「' . ($selling_type) . '商」'.(isset($data->certification_id) ? $certification_list[$data->certification_id] : $certification_list[$cid]); ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <script type="text/javascript">
        function success() {
            if (confirm("確認內容是否正確？")) {
                if ($('#report select,#report input.case').length == $('#report :checked,#report :selected').length) {
                    var data = 'cid=1006&id=' + <? echo isset($data->id) ? $data->id : $user_id; ?> +'&selltype=' + <?=isset($selltype) ? $selltype : ''; ?>;
                    $.each($('#report :checked,#report :selected'), function (e) {
                        data += '&' + $(this).data('value') + '=' + $(this).val();
                    });
                    $.ajax({
                        url: '../certification/user_certification_edit',
                        data: data,
                        type: 'POST',
                        success: function (e) {
                            var res = JSON.parse(e);
                            alert(res.msg);
                            window.location.href = res.redirect;
                        }
                    });
                } else {
                    alert('請填寫完成 !!');
                }
            }
        }
    </script>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"><? echo isset($data->certification_id) ? $certification_list[$data->certification_id] : $certification_list[$cid]; ?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe"
                                   href="<? $ruser_id = isset($data->user_id) ? $data->user_id : $user_id;
                                   echo admin_url('User/display?id=' . $ruser_id) ?>">
                                    <p><?= $ruser_id ?></p>
                                </a>
                            </div>
                            <div class="form-group">
                                <div id="report">
                                    <? if (isset($creditJudicialConfig)) {
                                        foreach ($creditJudicialConfig as $key => $value) {
                                            !empty($value['name']) ? print('<br /><label>' . $value['name'] . '</label><br/>') : '';
                                            if ($value['selctType'] == 'select') {
                                                echo '<select class="form-control case">';
                                                foreach ($value['descrtion'] as $descrtionKey => $descrtionValue) {
                                                    echo '<option value="' . $descrtionKey . '" '.$ddd.' data-value="' . $key . '"'.(isset($content) && $content[$key] == $descrtionKey?' selected disabled':' disabled').'>' . $descrtionValue . '</option>';
                                                }
                                                echo '</select>';
                                            } elseif ($value['selctType'] == 'radio') {
                                                foreach ($value['descrtion'] as $descrtionKey => $descrtionValue) {
                                                    echo ' <input class="case" type="radio" name="' . $descrtionValue[0] . '" data-value="' . $descrtionValue[0] . '" value="1"'.(isset($content) && $content[$descrtionValue[0]] == 1?' checked disabled':' disabled').'> 是 ';
                                                    echo ' <input type="radio" name="' . $descrtionValue[0] . '" data-value="' . $descrtionValue[0] . '" value="0"'.(isset($content) && $content[$descrtionValue[0]] == 0?' checked disabled':' disabled').'> 否 ';
                                                    echo ' - ' . $descrtionValue[1] . '<br />';
                                                }
                                            }
                                        }
                                        ?>
                                    <? } ?>
                                </div>
                            </div>
                            <?
                            if(!isset($content)){
                                echo '<button type="submit" class="btn btn-primary"
                                    onclick="success()">產生報告
                                  </button>';
                            }
                            ?>
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
