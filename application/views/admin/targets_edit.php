
	<script>
	
		function form_onsubmit(){
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
				<h1 class="page-header">標的資訊</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
					標的資訊
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<?php 
								$image = array("person_image");
								if(!empty($info)){
									foreach($info as $key => $value){
										if(in_array($key,$image)){
											$value = "<img src='".$value."' style='max-width:200px;width:50%'>";
										}
								?>
								<div class="form-group">
									<label><?=isset($target_fields[$key])?$target_fields[$key].$key:$key?></label>
									<p><?=$value?></p>
								</div>
								<?}}?>
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
