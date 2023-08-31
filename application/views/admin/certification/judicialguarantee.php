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
            <h1 class="page-header"><?=isset($data->certification_id)?$certification_list[$data->certification_id]:"";?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?=isset($data->certification_id)?$certification_list[$data->certification_id]:"";?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe" href="<?=admin_url('User/display?id='.$data->user_id) ?>" >
                                    <p><?=isset($data->user_id)?$data->user_id:"" ?></p>
                                </a>
                            </div>
                            <div class="form-group">
                                <label>備註</label>
                                <?php $fail = '';
                                if ( ! empty($remark["fail"]))
                                {
                                    $fail = $remark['fail'];
                                    echo '<p style="color:red;" class="form-control-static">失敗原因：' . $remark["fail"] . '</p>';
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
                            <form role="form" method="post" action="/admin/certification/user_certification_edit">
                                <fieldset>
                                    <div class="form-group">
                                        <select id="status" name="status" class="form-control" onchange="check_fail();" >
                                            <? foreach($status_list as $key => $value){ ?>
                                            <option value="<?=$key?>" <?=$data->status==$key?"selected":""?>><?=$value?></option>
                                            <? } ?>
                                        </select>
                                        <input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
                                        <input type="hidden" name="from" value="<?=isset($from)?$from:"";?>" >
                                    </div>
                                    <div class="form-group" id="fail_div" style="display:none">
                                        <label>失敗原因</label>
                                        <input type="text" class="form-control" id="fail" name="fail" value="<?=$remark && isset($remark["fail"])?$remark["fail"]:"";?>" >
                                    </div>
                                    <button type="submit" class="btn btn-primary">送出</button>
                                </fieldset>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <h1>圖片/文件</h1>
                            <fieldset>
                                <div class="form-group">
                                    <fieldset>
                                        <div class="form-group">
                                            <label>公司授權核實</label><br>
                                            <? isset($content['image_url']) && !is_array($content['image_url']) ? $content['image_url'] = array($content['image_url']) : '';
                                            if(!empty($content['image_url'])){
                                                foreach ($content['image_url'] as $key => $value) { ?>
                                                    <a href="<?= $value ?>" data-fancybox="images">
                                                        <img src="<?= $value ?>" style='width:30%;max-width:400px'>
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
