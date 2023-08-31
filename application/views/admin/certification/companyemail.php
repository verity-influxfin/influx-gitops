		<script type="text/javascript">
            function check_fail() {
                if ($('#status :selected').val() === '2') {
                    $('#fail_div').show();
                } else {
                    $('#fail_div').hide();
                }
            }

            $(document).ready(function () {
                $('#status').trigger('change');
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
										<label>Email</label>
										<p class="form-control-static"><?=isset($content['email'])?$content['email']:""?></p>
									</div>
									<div class="form-group">
										<label>備註</label>
                                        <?php
                                        if ( ! empty($remark["fail"]))
                                        {
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
