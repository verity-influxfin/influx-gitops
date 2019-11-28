
	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>
	
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">投訴與建議</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						<?=$type=="edit"?"修改管理員資訊":"新增管理員" ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post" onsubmit="return form_onsubmit();" >
                                        <input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
										<div class="form-group">
                                            <label>User ID</label><br>
                                           <a href="<?=admin_url('user/edit?id='.$data->user_id) ?>" target="_blank" ><?=isset($data->user_id)?$data->user_id:"" ?></a>
                                        </div>
                                        <div class="form-group">
                                            <label>借款端/出借端</label>
                                            <p><?=isset($data->investor)?$investor_list[$data->investor]:"" ?></p>
                                        </div>
                                        <div class="form-group">
                                            <label>內容</label>
											<p><?=isset($data->content)?$data->content:"" ?></p>
                                        </div>
										<div class="form-group">
                                            <label>附圖</label><br />
											<? 
												if(isset($data->image) && !empty($data->image)){
													$image = json_decode($data->image,TRUE);
													foreach($image as $key => $value){
														if(!empty($value)&&!is_array($value)){ ?>
													        <img src='<?=$value ?>' style='width:30%'>
												        <? }elseif(is_array($value)){
                                                            foreach($value as $key_arr => $value_arr){ ?>
                                                                <img src='<?=$value_arr ?>' style='width:30%'>
                                                            <?}
                                                        }}
												} ?>
                                        </div>
										<div class="form-group">
                                            <label>回報時間</label> 
                                            <p><?=isset($data->created_at)?date("Y-m-d H:i:s",$data->created_at):"" ?></p>
                                        </div>
										<div class="form-group">
                                            <label>處理人</label> 
                                            <p><?=$data->admin_id&&isset($name_list[$data->admin_id])?$name_list[$data->admin_id]:"未處理" ?></p>
                                        </div>
										<div class="form-group">
                                            <label>處理狀態</label>
                                            <select class="form-control" id="status" name="status">
												<? 
												if(isset($status_list) && !empty($status_list)){
													foreach($status_list as $key => $value){
												?>
                                                <option value="<?=$key; ?>" <?=isset($data->status)&&$data->status==$key?"selected":"";?>><?=$value; ?></option>
												<? }} ?>
                                            </select>
                                        </div>
										<div class="form-group">
                                            <label>處理情形</label><br>
											<textarea cols="50" rows="5" name="remark"><?=isset($data->remark)?$data->remark:"" ?></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-default">送出</button>
                                    </form>
                                </div>

                                </div>
                                <!-- /.col-lg-6 (nested) -->
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
