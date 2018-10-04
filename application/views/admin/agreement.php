    <script>
        tinymce.init({
            selector: 'textarea',
            height: 500
        });
    </script>

	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">協議書</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
					協議書
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-6">
								<form action="<?=$type=="edit"?"updateAgreement":"insertAgreement" ?>" method="post">
									<div class="form-group">
										<label>名稱</label>
										<input id="name" name="name" class="form-control" placeholder="Enter Name" value="<?=isset($data->name)?$data->name:"" ?>">
										<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"" ?>">
									</div>
									<div class="form-group">
										<label>代號</label>
										<input id="alias" name="alias" class="form-control" placeholder="Enter Alias" value="<?=isset($data->alias)?$data->alias:"" ?>" >
									</div>
									<div class="form-group">
										<label>產品縮寫</label> 
										<textarea id="content" name="content" class="form-control" rows="3">
										<?=isset($data->content)?$data->content:"";?>
										</textarea>
									</div>
									<button type="submit" class="btn btn-default">送出</button>
								</form>
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