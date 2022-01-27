    <script>
        tinymce.init({
            selector: 'textarea',
            height: 500
        });
    </script>

	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header"><?=isset($data)?'修改':'新增'?> <?=$type_list[$type] ?></h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
					<?=isset($data)?'修改':'新增'?> <?=$type_list[$type] ?>
					</div>
					<div class="panel-body">
						<div class="row">
							<form action="<?=isset($data)?"edit":"add" ?>" method="post" enctype="multipart/form-data">
								<div class="col-lg-6">
									<div class="form-group">
										<label>標題</label>
										<input id="title" name="title" class="form-control" placeholder="Enter Title" value="<?=isset($data->title)?$data->title:"" ?>">
										<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"" ?>">
										<input type="hidden" name="type" value="<?=isset($type)?$type:"" ?>">
									</div>
									<div class="form-group">
										<label>內容</label> 
										<textarea id="content" name="content" class="form-control" rows="3">
										<?=isset($data->content)?$data->content:"";?>
										</textarea>
									</div>
								</div>
								<!-- /.col-lg-6 (nested) -->
								<div class="col-lg-6">
									<div class="form-group">
										<label>排序</label>
										<input type="number" id="rank" name="rank" class="form-control" value="<?=isset($data->rank)?$data->rank:0 ?>" onkeyup="return ValidateNumber(this,value)">
									</div>
									<div class="form-group">
										<label>連結</label>
										<input id="url" name="url" class="form-control" placeholder="https://" value="<?=isset($data->url)?$data->url:"" ?>" >
									</div>
									<div class="form-group">
										<label>更換圖片</label>
										<? if(isset($data->image)){ ?>
										<a href="<?=isset($data->image)?$data->image:""?>" data-fancybox="images">
											<img src="<?=isset($data->image)?$data->image:""?>" style='max-width:300px;'>
										</a>
										<? } ?>
										<input type="file" name="image" id="image" class="form-control">
									</div>
									<button type="submit" class="btn btn-default">送出</button>
								</div>
								<!-- /.col-lg-6 (nested) -->
							</form>
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