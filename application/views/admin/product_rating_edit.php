
	<script>
	
		function form_onsubmit(){
			return true;
		}
		
		$(document).ready(function(){
			$("#pick_all").click(function(){
				var pick = $("#pick_all").prop("checked")
				$(".pick_chekbox").prop("checked", pick);
			});	
		});

	</script>
	
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">設定評級加權</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						設定評級加權
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post" onsubmit="return form_onsubmit();" > 
										<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover" id="dataTables-example">
												<thead>
													<tr>
														<th><input type="checkbox" id="pick_all" ></th>
														<th>名稱</th>
														<th>簡介</th>
														<th>加權分數</th>
													</tr>
												</thead>
												<tbody>
												<?php 
													if(isset($rating) && !empty($rating)){
														$count = 0;
														foreach($rating as $key => $value){
															$count++;
												?>
													<tr class="<?=$count%2==0?"odd":"even"; ?>">
														<td>
															<input type="checkbox" class="pick_chekbox" name="rating[<?=isset($value->id)?$value->id:""; ?>]" value="<?=isset($value->id)?$value->id:"" ?>" <?=isset($product_rating[$value->id])&&$product_rating[$value->id]["status"]==1?"checked":""; ?>> <?=isset($value->id)?$value->id:"" ?>
														</td>
														<td><?=isset($value->name)?$value->name:"" ?></td>
														<td><?=isset($value->description)?$value->description:"" ?></td>
														<td><input type="number" min="0" max="9" name="rating_value[<?=isset($value->id)?$value->id:""; ?>]" class="form-control" value="<?=isset($product_rating[$value->id])&&$product_rating[$value->id]["value"]?$product_rating[$value->id]["value"]:0; ?>" onKeyUp="if(this.value>9){this.value='9';} return ValidateNumber($(this),value);"></td>
													</tr>                                        
												<?php  
													}}else{
												?>
												<tr class="odd">
													<th class="text-center" colspan="11">目前尚無資料</th>
												</tr>
												<?php 
													}
												?>
												</tbody>
											</table>
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
