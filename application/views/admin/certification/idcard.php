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
    function requestVerdictStatuses() {
          var data = {
              'user_id' : '<?=isset($data->user_id)?$data->user_id:"" ?>',
          }

          var url = '/admin/certification/verdict_statuses';

          $.ajax({
              type: "GET",
              url: url,
              data: data,
              success: function(response) {
                  if (!response) {
                      alert('爬蟲狀態請求未成功送出');
                      return;
                  }
                  if (response.status.code == 400) {
                      alert('參數錯誤，爬蟲狀態請求未成功送出');
                      return;
                  }

                  if (response.status.code == 204) {
                      html = '<tr><td>狀態</td><td>請求未被收到</td></tr>';
                      $('#verdict_list').html(html);
                      $('.run-scraper-tr').show();
                      return;
                  }
                  if (response.status.code == 200) {
                      var data_arry = response.response.verdict_statuses.response;
                      var data_date = new Date( data_arry.updatedAt * 1000);

                      html = '<tr><td>名字</td><td>'+ data_arry.query +'</td></tr><tr><td>戶籍地</td><td>'+ data_arry.location +'</td></tr><tr><td>狀態</td><td>'
                      + data_arry.status + '</td></tr><tr><td>最後更新時間</td><td>'+ data_date +'</td></tr>';
                      $("#verdict_list").html(html);

                      if(data_arry.status=='爬蟲執行完成'){
                        requestVerdictCount();
                        if(new Date($.now()-604800000) > data_date){
                          $('#run-scraper-btn').text('重新執行爬蟲');
                          $('.run-scraper-tr').show();
                        }
                      }

                  }
              },
              error: function() {
                  alert('爬蟲狀態請求未成功送出');
              }
          });
      }

      function requestVerdictCount() {
            var data = {
                'name' : '<?=isset($content['name'])?$content['name']:"" ?>',
            }

            var url = '/admin/certification/verdict_count';

            $.ajax({
                type: "GET",
                url: url,
                data: data,
                success: function(response) {
                    if (!response) {
                        alert('案件資訊請求未成功送出');
                        return;
                    }
                    if (response.status.code == 400) {
                        alert('參數錯誤，案件資訊請求未成功送出');
                        return;
                    }

                    if (response.status.code == 204) {
                      html = '<tr><td colspan="2" style="text-align: -webkit-center;">無案件資料</td></tr>';
                        $("#case_list").html(html);
                        return;
                    }

                    if (response.status.code == 200) {
                        var case_list = response.response.verdict_count.response.verdict_count;
                        case_list.forEach(function(case_list){
                          list_head = '<tr><td ';
                          list_style = 'style="color:red;"';
                          list_foot = '>' + case_list.name +'</td><td><a target="_blank" href="judicial_yuan_case?name=<?=isset($content['name'])?$content['name']:"" ?>&case='+ case_list.name +'&page=1&count='+ case_list.count +'"  >' + case_list.count + '</a></td></tr>';
                          if(case_list.name =='本票裁定' || case_list.name =='支付命令' || case_list.name =='消債之前置協商認可事件' || case_list.name =='詐欺' || case_list.name =='侵佔'){
                                  list = list_head + list_style + list_foot;
                                }else{
                                  list = list_head + list_foot;
                                }
                                $("#case_list").prepend(list);


                        });
                    }
                },
                error: function() {
                    alert('案件資訊請求未成功送出');
                }
            });
        }

        function requestVerdict() {
              var data = {
                  'name' : '<?=isset($content['name'])?$content['name']:"" ?>',
                  'address' : '<?=isset($content['id_card_place'])?$content['id_card_place']:""?>',
                  'user_id' : '<?=isset($data->user_id)?$data->user_id:"" ?>',
              }

              var url = '/admin/certification/verdict';

              $.ajax({
                  type: "GET",
                  url: url,
                  data: data,
                  success: function(response) {
                      if (!response) {
                          alert('爬蟲執行請求未成功送出');
                          return;
                      }
                      if (response.status.code == 400) {
                          alert('參數錯誤，爬蟲執行請求未成功送出');
                          return;
                      }

                      if (response.status.code == 200) {
                          alert('爬蟲執行請求成功送出');
                              return;
                      }

                      if (response.status.code == 201) {
                          alert('爬蟲執行請求2小時內重複發送');

                      }
                  },
                  error: function() {
                      alert('爬蟲執行請求未成功送出');
                  }
              });
          }
    $(document).ready(function(){
      $( '#run-scraper-btn' ).click(function() {
        requestVerdict();
      });
      $( '#read-scraper-btn' ).click(function() {
        requestVerdictStatuses();
      });
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
                              <label>爬蟲資訊資訊</label>
                              <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th scope="col">資訊名稱</th><th scope="col">相關資訊</th>
                                  </tr>
                                </thead>
                                <tbody id="verdict_list">
                                </tbody>
                                <tbody>
                                  <tr class="run-scraper-tr"><td colspan="2" style="text-align: -webkit-center;" ><button id="read-scraper-btn">讀取爬蟲案件</button></td></tr>
                                  <tr class="run-scraper-tr"><td colspan="2" style="text-align: -webkit-center;" ><button id="run-scraper-btn">執行爬蟲</button></td></tr>
                                </tbody>
                              </table>
                            </div>

                            <div class="form-group">
                                <label>案件資訊</label>
                                <table class="table table-bordered">
                                  <thead>
                                    <tr>
                                      <th scope="col">裁判案由</th><th scope="col">總數</th>
                                    </tr>
                                  </thead>
                                  <tbody id="case_list">
                                  </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <label>審核狀態</label>
                                <p class="form-control-static"><?=$data->status==0?'尚未檢核':($data->sys_check==0?"人工":"系統")?></p>
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

                                    echo '<p class="form-control-static">Azure 面孔比對結果：';
                                    echo '<br />持證自拍照：' . $remark["face_count"]['person_count'] . ' 人臉';
                                    echo '<br />身分證正面照：' . $remark["face_count"]['front_count'] . ' 人臉';
                                    if(isset($remark["face"]) && count($remark["face"]) >= 2) {
                                        echo '<br />「身分證正面照」與「持證自拍者」相似度：' . $remark["face"][0];
                                        echo '<br />「身分證正面照」與「持證自拍照證件」相似度：' . $remark["face"][1];
                                        echo '</p>';
                                    }

                                    if(isset($remark["faceplus"]) && count($remark["faceplus"])>0){
                                        echo '<p class="form-control-static">Face++ 面孔比對結果：';
                                        echo '<br />「身分證正面照」與「持證自拍者」相似度：' . $remark["faceplus"][0];
                                        echo '<br />「身分證正面照」與「持證自拍照證件」相似度：' . $remark["faceplus"][1];
                                        echo '</p>';
                                    }

                                    if(isset($remark["face8"])){
                                        function confidence($confidence){
                                            $rs = '低 ( 1/1000 )';
                                            if($confidence >= 0.52){
                                                $rs = '高 ( 1/100000 )';
                                            }elseif($confidence >= 0.46){
                                                $rs = '中 ( 1/10000 )';
                                            }
                                            return $rs;
                                        }
                                        function liveness($value){
                                            return $value>=0.97?'活體':'非活體';
                                        }
                                        echo '<p class="form-control-static">Face8 面孔比對結果：';
                                        echo '<br />持證自拍照：' . $remark["face8"]['count'][0] . ' 人臉 ' . ($remark["face8"]['count'][0] >=2 ? ' ( 臉1 ' . liveness($remark["face8"]['liveness'][0][0]) . ' / 臉2 ' . liveness($remark["face8"]['liveness'][0][1]) . ' )' : '');
                                        echo '<br />身分證正面照：' . $remark["face8"]['count'][1] . ' 人臉 ' .($remark["face8"]['count'][1] > 0 ? ' ( ' . liveness($remark["face8"]['liveness'][0][1]). ' )' : '');
                                        if($remark["face8"]['count'][0] >=2){
                                            echo '<br />「身分證正面照」與「持證自拍者」：' . $remark["face8"]['score'][0];
                                            echo '<br />「身分證正面照」與「持證自拍照證件：' . $remark["face8"]['score'][1];
                                            echo '<br />大於 0.39 即可視為同一人 但有千分之一的錯誤率';
                                            echo '<br />大於 0.46 即可視為同一人 但有萬分之一的錯誤率';
                                            echo '<br />大於 0.52 即可視為同一人 但有十萬分之一的錯誤率';
                                        }
                                        echo '</p>';
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
                                                <label>姓名：</label><?=isset($remark["OCR"]["name"])&&$remark["OCR"]["name"]!=''?"".$remark["OCR"]["name"]:"";?><br>
                                                <label>生日：</label><?=isset($remark["OCR"]["birthday"])&&$remark["OCR"]["birthday"]!=''?"".$remark["OCR"]["birthday"]:"";?><br>
                                                <label>換發日期：</label><?=isset($remark["OCR"]["id_card_date"])&&$remark["OCR"]["id_card_date"]!=''?"".$remark["OCR"]["id_card_date"]:"";?><br>
                                                <label>換發地區：</label><?=isset($remark["OCR"]["id_card_place"])&&$remark["OCR"]["id_card_place"]!=''?"".$remark["OCR"]["id_card_place"]:"";?><br>
												<label>發證方式：</label><?=isset($remark["OCR"]["issueType"])&&$remark["OCR"]["issueType"]!=''?"".$remark["OCR"]["issueType"]:"";?><br>
												<label>身分證字號：</label><?=isset($remark["OCR"]["id_number"])&&$remark["OCR"]["id_number"]!=''?"".$remark["OCR"]["id_number"]:"";?><br>
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

                                                <label>父：</label><?=isset($remark["OCR"]["father"])&&$remark["OCR"]["father"]!='' ? $remark["OCR"]["father"]: '' ;?><br>
                                                <label>母：</label><?=isset($remark["OCR"]["mother"])&&$remark["OCR"]["mother"]!='' ? $remark["OCR"]["mother"].'<br>': '' ;?><br>
                                                <label>配偶：</label><?=isset($remark["OCR"]["spouse"])&&$remark["OCR"]["spouse"]!='' ? $remark["OCR"]["spouse"].'<br>': '' ;?><br>
                                                <label>役別：</label><?=isset($remark["OCR"]["military_service"])&&$remark["OCR"]["military_service"]!='' ? $remark["OCR"]["military_service"].'<br>': '' ;?><br>
                                                <label>出生地：</label><?=isset($remark["OCR"]["born"])&&$remark["OCR"]["born"]!='' ? $remark["OCR"]["born"].'<br>': '' ;?><br>
                                                <label>住址：</label><?=isset($remark["OCR"]["address"])&&$remark["OCR"]["address"]!='' ? $remark["OCR"]["address"].'<br>': '' ;?><br>
                                                <label>綠色號碼：</label><?=isset($remark["OCR"]["gnumber"])&&$remark["OCR"]["gnumber"]!='' ? $remark["OCR"]["gnumber"].'<br>': '' ;?><br>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="form-group">
                                    <label for="disabledSelect">持證自拍照</label><br>
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
                                                <label>姓名：</label><?=isset($remark["OCR"]["healthcard_name"])&&$remark["OCR"]["healthcard_name"]!=''?$remark["OCR"]["healthcard_name"]:"";?><br>
                                                <label>身分證字號：</label><?=isset($remark["OCR"]["healthcard_id_number"])&&$remark["OCR"]["healthcard_id_number"]!=''?$remark["OCR"]["healthcard_id_number"]:"";?><br>
                                                <label>生日：</label><?=isset($remark["OCR"]["healthcard_birthday"])&&$remark["OCR"]["healthcard_birthday"]!=''?$remark["OCR"]["healthcard_birthday"]:"";?><br>
                                                <label>健保卡號：</label><?=isset($remark["OCR"]["healthcard_number"])&&$remark["OCR"]["healthcard_number"]!=''?$remark["OCR"]["healthcard_number"]:"";?><br>
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
