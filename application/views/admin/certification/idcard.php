<script type="text/javascript">
    function check_fail(){
        var status = $('#status :selected').val();
        if(status==2){
            $('#fail_div').show();
        }else{
            $('#fail_div').hide();
        }
    }
    $(document).off("change","select#fail").on("change","select#fail" ,  function(){
        var sel=$(this).find(':selected');
        $('input#fail').css('display',sel.attr('value')=='other'?'block':'none');
        $('input#fail').attr('disabled',sel.attr('value')=='other'?false:true);
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

                            <?
                            if($content['name']==''&&$data->status==3){
                                echo '<form role="form" method="post">
                                <div class="form-group">
                                    <label>姓名</label>
                                    <input type="text" name="name" value="' . $content['name'] . '" />
                                    <input type="hidden" name="id" value="' .$data->id. '" >
                                    <input type="hidden" name="from" value="' .$from. '" >
                                </div>
                                <button type="submit" class="btn btn-primary">確認姓名</button>
                                </form><br />';
                            }
                            else {
                                echo '<div class="form-group">
                                <label>姓名</label>
                                <p class="form-control-static">'.$content['name'].'</p>
                                </div>';
                            }
                            ?>
                            <div class="form-group">
                                <label>身分證字號</label>
                                <p class="form-control-static"><?=isset($content['id_number'])?$content['id_number']:""?></p>
                            </div>
                            <div class="form-group">
                                <label>發證日期</label>
                                <p class="form-control-static"><?=isset($content['id_card_date'])?$content['id_card_date']:""?></p>
                            </div>
                            <div class="form-group">
                                <label>發證地點</label>
                                <p class="form-control-static"><?=isset($content['id_card_place'])?$content['id_card_place']:""?></p>
                            </div>
                            <div class="form-group">
                                <label>生日</label>
                                <p class="form-control-static"><?=isset($content['birthday'])?$content['birthday']:""?></p>
                            </div>
                            <?
                            if($content['address']==''&&$data->status==3){
                                echo '<form role="form" method="post">
                                <div class="form-group">
                                    <label>地址</label>
                                    <input type="text" name="address" value="' . $content['address'] . '" />
                                    <input type="hidden" name="id" value="' . $data->id . '" >
                                    <input type="hidden" name="from" value="' . $from . '" >
                                </div>
                                <button type="submit" class="btn btn-primary">確認地址</button>
                                </form><br />';
                            }
                            else {
                                echo '<div class="form-group">
                                <label>地址</label>
                                    <p class="form-control-static">'.$content['address'].'</p>
                                </div>';
                            }
                            ?>
                            <div class="form-group">
                                <label>審核狀態</label>
                                <p class="form-control-static"><?=isset($data->sys_check)&&$data->sys_check==0?"人工":"系統"?></p>
                            </div>
                            <div class="form-group">
                                <label>備註</label>
                                <?
                                if($remark){
                                    if(isset($remark["fail"]) && $remark["fail"]){
                                        echo '<p style="color:red;" class="form-control-static">失敗原因：'.$remark["fail"].'</p>';
                                    }
                                    if(isset($remark["error"]) && $remark["error"]){
                                        echo '<p style="color:red;" class="form-control-static">錯誤：<br />'.$remark["error"].'</p>';
                                    }
                                    if($remark["face"] && is_array($remark["face"])){
                                        echo '<p class="form-control-static">照片比對結果(Sys1)：';
                                        foreach($remark["face"] as $key => $value){
                                            echo $value."% ";
                                        }
                                        echo '</p>';
                                        if(isset($remark["faceplus"])&&count($remark["faceplus"])>0){
                                            echo '<p class="form-control-static">照片比對結果(Sys2)：';
                                            foreach($remark["faceplus"] as $key => $value){
                                                echo $value."% ";
                                            }
                                            echo '</p>';
                                        }
                                    }
                                }
                                ?>
                            </div>
                            <h4>審核</h4>
                            <form role="form" method="post">
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
                                        <select id="fail" name="fail" class="form-control">
                                            <option value="" disabled selected>選擇回覆內容</option>
                                            <? foreach($certifications_msg[1] as $key => $value){ ?>
                                                <option <?=$data->status==$value?"selected":""?>><?=$value?></option>
                                            <? } ?>
                                            <option value="other">其它</option>
                                        </select>
                                        <input type="text" class="form-control" id="fail" name="fail" value="<?=$remark && isset($remark["fail"])?$remark["fail"]:"";?>" style="background-color:white!important;display:none" disabled="false">
                                    </div>
                                    <button type="submit" class="btn btn-primary" <?=($content['name']==''||$content['address']=='')&&$data->status==3?'disabled':''; ?>>送出</button>
                                </fieldset>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <h1>圖片</h1>
                            <fieldset disabled>
                                <div class="form-group">
                                    <label for="disabledSelect">身分證正面照</label><br>
                                    <table>
                                        <tr>
                                            <td rowspan="6">
                                                <a href="<?=isset($content['front_image'])?$content['front_image']:""?>" data-fancybox="images">
                                                    <img src="<?=isset($content['front_image'])?$content['front_image']:""?>" style='width:100%;max-width:300px'>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr><td>
                                                <label>人臉數量：</label><?=isset($remark["face_count"]["front_count"])?$remark["face_count"]["front_count"]:0;?><br>
                                                <?=isset($remark["OCR"]["name"])&&$remark["OCR"]["name"]!=''?"<label>姓名：</label>".$remark["OCR"]["name"]."<br>":"";?>
                                                <?=isset($remark["OCR"]["birthday"])&&$remark["OCR"]["birthday"]!=''?"<label>生日：</label>".$remark["OCR"]["birthday"]."<br>":"";?>
                                                <?=isset($remark["OCR"]["id_card_date"])&&$remark["OCR"]["id_card_date"]!=''?"<label>換發日期：</label>".$remark["OCR"]["id_card_date"]."<br>":"";?>
                                                <?=isset($remark["OCR"]["id_card_place"])&&$remark["OCR"]["id_card_place"]!=''?"<label>換發地區：</label>".$remark["OCR"]["id_card_place"]."<br>":"";?>
                                                <?=isset($remark["OCR"]["id_number"])&&$remark["OCR"]["id_number"]!=''?"<label>身分證字號：</label>".$remark["OCR"]["id_number"]."<br>":"";?>
                                            </td></tr>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <label for="disabledSelect">身分證背面照</label><br>
                                    <table>
                                        <tr>
                                            <td width="300px">
                                                <a href="<?=isset($content['back_image'])?$content['back_image']:""?>" data-fancybox="images">
                                                    <img src="<?=isset($content['back_image'])?$content['back_image']:""?>" style='width:100%;max-width:300px;'>
                                                </a>
                                            </td>
                                            <td>
                                                <?
                                                if(isset($remark["OCR"]["father"])&&$remark["OCR"]["father"]!=''){echo'<label>父：</label>'.$remark["OCR"]["father"].'<br>';}
                                                if(isset($remark["OCR"]["mother"])&&$remark["OCR"]["mother"]!=''){echo'<label>母：</label>'.$remark["OCR"]["mother"].'<br>';}
                                                if(isset($remark["OCR"]["spouse"])&&$remark["OCR"]["spouse"]!=''){echo'<label>配偶：</label>'.$remark["OCR"]["spouse"].'<br>';}
                                                if(isset($remark["OCR"]["military_service"])&&$remark["OCR"]["military_service"]!=''){echo'<label>役別：</label>'.$remark["OCR"]["military_service"].'<br>';}
                                                if(isset($remark["OCR"]["born"])&&$remark["OCR"]["born"]!=''){echo'<label>出生地：</label>'.$remark["OCR"]["born"].'<br>';}
                                                if(isset($remark["OCR"]["address"])&&$remark["OCR"]["address"]!=''){echo'<label>住址：</label>'.$remark["OCR"]["address"].'<br>';}
                                                if(isset($remark["OCR"]["gnumber"])&&$remark["OCR"]["gnumber"]!=''){echo'<label>綠色號碼：</label>'.$remark["OCR"]["gnumber"].'<br>';}
                                                if(isset($remark["OCR"]["film_number"])&&$remark["OCR"]["film_number"]!=''){echo'<label>膠膜號碼：</label>'.$remark["OCR"]["film_number"];}
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <label for="disabledSelect">本人照</label><br>
                                    <table>
                                        <tr>
                                            <td rowspan="2">
                                                <a href="<?=isset($content['person_image'])?$content['person_image']:""?>" data-fancybox="images">
                                                    <img src="<?=isset($content['person_image'])?$content['person_image']:""?>" style='width:100%;max-width:300px;'>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr><td><label>人臉數量：</label><?=isset($remark["face_count"]["person_count"])?$remark["face_count"]["person_count"]:0;?></td></tr>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <label for="disabledSelect">健保卡照</label><br>
                                    <table>
                                        <tr>
                                            <td rowspan="6">
                                                <a href="<?=isset($content['healthcard_image'])?$content['healthcard_image']:""?>" data-fancybox="images">
                                                    <img src="<?=isset($content['healthcard_image'])?$content['healthcard_image']:""?>" style='width:100%;max-width:300px;'>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr><td>
                                                <?=isset($remark["OCR"]["healthcard_name"])&&$remark["OCR"]["healthcard_name"]!=''?"<label>姓名：</label>".$remark["OCR"]["healthcard_name"]."<br>":"";?>
                                                <?=isset($remark["OCR"]["healthcard_id_number"])&&$remark["OCR"]["healthcard_id_number"]!=''?"<label>生日：</label>".$remark["OCR"]["healthcard_id_number"]."<br>":"";?>
                                                <?=isset($remark["OCR"]["healthcard_birthday"])&&$remark["OCR"]["healthcard_birthday"]!=''?"<label>身分證字號：</label>".$remark["OCR"]["healthcard_birthday"]."<br>":"";?>
                                                <?=isset($remark["OCR"]["healthcard_number"])&&$remark["OCR"]["healthcard_number"]!=''?"<label>健保卡號：</label>".$remark["OCR"]["healthcard_number"]."<br>":"";?>
                                            </td></tr>
                                    </table>
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