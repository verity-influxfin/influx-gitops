
	<script>
	
		function form_onsubmit(){
			<?if($type=='add'){?>
				var pwd = $("#pwd").val();
				var cpwd = $("#confirm_pwd").val();
				if(pwd != cpwd){
					alert('Confirm Password is error');
					$("#confirm_pwd").val("");
					return false;
				}
			<?}?>
			return true;
		}
		
		$(document).ready(function () {
			$('.instalment_chk').change(function(){
				var instalment = "[";
				$(".instalment_chk").each(function() {
					if($(this).is(":checked")){
						if(instalment=="["){
							instalment = instalment + $(this).val();
						}else{
							instalment = instalment + "," + $(this).val();
						}
					}
				});
				var instalment = instalment + "]";
				$("#instalment").val(instalment);
			})
		});	
	</script>
	
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=$type=="edit"?"修改產品資訊":"新增產品" ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						<?=$type=="edit"?"修改產品資訊":"新增產品" ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post" onsubmit="return form_onsubmit();" > 
                                        <div class="form-group">
                                            <label>名稱</label>
                                            <input id="name" name="name" class="form-control" placeholder="Enter Name" value="<?=isset($data->name)?$data->name:"";?>">
											<?
												if($type=="edit"){
											?>
											<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
											<? } ?>
                                        </div>
                                        <div class="form-group">
                                            <label>簡介</label>
											<textarea id="description" name="description" class="form-control" rows="3"><?=isset($data->description)?$data->description:"";?></textarea>
                                        </div>
										<div class="form-group">
                                            <label>產品縮寫</label> 
                                            <input id="alias" name="alias" class="form-control" placeholder="Enter Alias" value="<?=isset($data->alias)?$data->alias:"";?>" >
                                        </div>
										<div class="form-group">
                                            <label>產品分類</label>
                                            <select class="form-control" id="category" name="category">
												<? 
												if(isset($category_list) && !empty($category_list)){
													foreach($category_list as $key => $value){
												?>
                                                <option value="<?=$key; ?>"><?=$value; ?></option>
												<? }} ?>
                                            </select>
                                        </div>
										<div class="form-group">
                                            <label>借款額度</label> 
                                            <input id="loan_range_s" name="loan_range_s" class="form-control" value="<?=isset($data->loan_range_s)?$data->loan_range_s:"";?>" >
                                            <input id="loan_range_e" name="loan_range_e" class="form-control" value="<?=isset($data->loan_range_e)?$data->loan_range_e:"";?>" >
                                        </div>
										<div class="form-group">
                                            <label>年利率下限（%）</label> 
                                           <input id="interest_rate_s" name="interest_rate_s" class="form-control" value="<?=isset($data->interest_rate_s)?$data->interest_rate_s:"";?>" >
                                        </div>
										<div class="form-group">
                                            <label>年利率下限（%）</label> 
                                           <input id="interest_rate_e" name="interest_rate_e" class="form-control" value="<?=isset($data->interest_rate_e)?$data->interest_rate_e:"";?>" >
                                        </div>
										<div class="form-group">
                                            <label>期數</label>
											<? 
											if(isset($instalment_list) && !empty($instalment_list)){
												foreach($instalment_list as $key => $value){
											?>
											<div class="checkbox">
                                                <label>
                                                    <input type="checkbox" class="instalment_chk" value="<?=$key ?>" <?=isset($instalment)&& in_array($key,$instalment)?"checked":""?>><?=$value ?>
                                                </label>
                                            </div>
											<? }} ?>
											<input type="hidden" name="instalment" id="instalment" value="<?=isset($data->instalment)?$data->instalment:"";?>" >
                                        </div>
                                        <button type="submit" class="btn btn-default">Submit Button</button>
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
