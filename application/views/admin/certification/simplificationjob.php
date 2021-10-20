<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
<style>
    .sk-input {
        width : 100%;
    }
</style>
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
                                    <div class="form-group">
                                        <div>
                                            <ul class="nav nav-tabs" id="skbank_form_tab" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#Pr" role="tab" data-toggle="tab" aria-expanded="true">負責人</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#Spouse" role="tab" data-toggle="tab" aria-expanded="false">配偶</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#GuOne" role="tab" data-toggle="tab" aria-expanded="false">保證人甲</a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#GuTwo" role="tab" data-toggle="tab" aria-expanded="false">保證人乙</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="table-responsive" id="Pr">
                                            <form role="form" action="/admin/certification/sendSkbank" method="post">
                                                <table class="table table-striped table-bordered table-hover dataTable">
                                                    <tbody>
                                                        <tr style="text-align: center;"><td colspan="2"><span>新光百萬信保微企貸資料確認</span></td></tr>
                                                        <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                        <tr><td><span>負責人-被保險人勞保異動查詢日期</span></td><td><input class="sk-input" type="text" name="PrLaborQryDate" placeholder="格式:YYYYMMDD"></td></tr>
                                                        <tr><td><span>負責人-被保險人勞保異動查詢-最近期投保薪資</span></td><td><input class="sk-input" type="text" name="PrLaborInsSalary"></td></tr>
                                                        <tr><td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td></tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                        <div class="table-responsive" id="Spouse">
                                            <form role="form" action="/admin/certification/sendSkbank" method="post">
                                                <table class="table table-striped table-bordered table-hover dataTable">
                                                    <tbody>
                                                        <tr style="text-align: center;"><td colspan="2"><span>新光百萬信保微企貸資料確認</span></td></tr>
                                                        <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                        <tr><td><span>配偶-被保險人勞保異動查詢日期</span></td><td><input class="sk-input" type="text" name="SpouseLaborQryDate" placeholder="格式:YYYYMMDD"></td></tr>
                                                        <tr><td><span>配偶-被保險人勞保異動查詢-最近期投保薪資</span></td><td><input class="sk-input" type="text" name="SpouseLaborInsSalary"></td></tr>
                                                        <tr><td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td></tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                        <div class="table-responsive" id="GuOne">
                                            <form role="form" action="/admin/certification/sendSkbank" method="post">
                                                <table class="table table-striped table-bordered table-hover dataTable">
                                                    <tbody>
                                                        <tr style="text-align: center;"><td colspan="2"><span>新光百萬信保微企貸資料確認</span></td></tr>
                                                        <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                        <tr><td><span>甲保證人-被保險人勞保異動查詢日期</span></td><td><input class="sk-input" type="text" name="GuOneLaborQryDate" placeholder="格式:YYYYMMDD"></td></tr>
                                                        <tr><td><span>甲保證人-被保險人勞保異動查詢-最近期投保薪資</span></td><td><input class="sk-input" type="text" name="GuOneLaborInsSalary"></td></tr>
                                                        <tr><td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td></tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                        <div class="table-responsive" id="GuTwo">
                                            <form role="form" action="/admin/certification/sendSkbank" method="post">
                                                <table class="table table-striped table-bordered table-hover dataTable">
                                                    <tbody>
                                                        <tr style="text-align: center;"><td colspan="2"><span>新光百萬信保微企貸資料確認</span></td></tr>
                                                        <tr hidden><td><span>徵提資料ID</span></td><td><input class="sk-input" type="text" name="id" value="<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>"></td></tr>
                                                        <tr><td><span>乙保證人-被保險人勞保異動查詢日期</span></td><td><input class="sk-input" type="text" name="GuTwoLaborQryDate" placeholder="格式:YYYYMMDD"></td></tr>
                                                        <tr><td><span>乙保證人-被保險人勞保異動查詢-最近期投保薪資</span></td><td><input class="sk-input" type="text" name="GuTwoLaborInsSalary"></td></tr>
                                                        <tr><td colspan="2"><button type="submit" class="btn btn-primary" style="margin:0 45%;">送出</button></td></tr>
                                                    </tbody>
                                                </table>
                                            </form>
                                        </div>
                                    </div>
                                    <? if(isset($content['labor_type'])){?>
                                        <div class="form-group">
                                            <label>勞保卡</label><br>
                                            <p><?= isset($content['labor_type']) && $content['labor_type'] ? '電子郵件交件' : '紙本交件' ?></p>
                                            <? if($content['labor_type']==1){?>
                                                    <? if (!empty($content['pdf_file'])) { ?>
                                                        <a href="<?= isset($content['pdf_file']) ? $content['pdf_file'] : ""?>" target="_blank">下載</a>
                                                    <? }else{?>
                                                        <p>尚未收到回信PDF</p>
                                            <?}?>
                                        </div>
                                        <?}} ?>
                                    <br />
                                    <div class="form-group">
										<label>備註</label>

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
                                                    <? foreach($certifications_msg[501] as $key => $value){ ?>
                                                        <option <?=$data->status==$value?"selected":""?>><?=$value?></option>
                                                    <? } ?>
                                                    <option value="other">其它</option>
                                                </select>
                                                <input type="text" class="form-control" id="fail" name="fail" value="<?=$remark && isset($remark["fail"])?$remark["fail"]:"";?>" style="background-color:white!important;display:none" disabled="false">
											</div>
											<button type="submit" class="btn btn-primary">送出</button>
                                        </fieldset>
                                    </form>
                                </div>
								<div class="col-lg-6">
                                    <h1>圖片</h1>
									<fieldset disabled>
                                        <? if (isset($content['labor_image'])) {
                                            echo '<h4>【勞保異動明細】</h4><div class="form-group"><label for="disabledSelect">勞保異動明細</label><br>';
                                            foreach($content['labor_image'] as $key => $value){
                                                echo'<a href="'.$value.'" data-fancybox="images"><img src="'.$value.'" style="width:30%;max-width:400px"></a>';
                                            }
                                            echo '</div><br /><br /><br />';
                                        }?>
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
<script>
$('select').selectize({
    sortField: 'text',
});
$(document).ready(function() {
    $.ajax({
        type: "GET",
        url: `/admin/certification/getSkbank?id=<?= isset($data->id) && is_numeric($data->id) ? $data->id : ""; ?>`,
        dataType: "json",
        success: function (response) {
            if(response.status.code == 200 && response.response != ''){
                Object.keys(response.response).forEach(function(key) {
                    console.log(key);
                    console.log(response.response[key]);
                    if($(`[name='${key}']`).length){
                        if($(`[name='${key}']`).is("input")){
                            $(`[name='${key}']`).val(response.response[key]);
                        }else{
                            let $select = $(`[name='${key}']`).selectize();
                            let selectize = $select[0].selectize;
                            selectize.setValue(selectize.search(response.response[key]).items[0].id);
                        }
                    }
                })
            }else{
                console.log(response);
            }
        },
        error: function(error) {
          alert(error);
        }
    });
    $('#skbank_form_tab a').click(function (e) {
        let show_id = $(this).attr("href");
        $(".table-responsive").hide()
        $(show_id).show()
    })
    $( "#skbank_form_tab :first-child :first-child" ).trigger( "click" );
});
</script>
