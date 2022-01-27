        <script>
			function success(id){
				if(id){
					$.ajax({
						url: './success?id='+id,
						type: 'GET',
						success: function(response) {
							alert(response);
							location.reload();
						}
					});
				}
			}
			
			function failed(id){
				if(id){
					$.ajax({
						url: './failed?id='+id,
						type: 'GET',
						success: function(response) {
							alert(response);
							location.reload();
						}
					});
				}
			}
			
			function del(id){
				if(id){
					$.ajax({
						url: './del?id='+id,
						type: 'GET',
						success: function(response) {
							alert(response);
							location.reload();
						}
					});
				}
			}
		</script>
		
		<div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=$type_list[$type] ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							<span><?=$type_list[$type] ?></span>
							<a href="<?=admin_url('Article/add?type='.$type) ?>">
								<button class="btn btn-primary">新增</button>
							</a>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>標題</th>
                                            <th>圖片</th>
                                            <th>狀態</th>
                                            <th>排序</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
										if(isset($list) && !empty($list)){
											$count = 0;
											foreach($list as $key => $value){
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
											<td><?=isset($value->title)?$value->title:"" ?></td>
											<td>
												<? if(isset($value->image)){ ?>
												<a href="<?=isset($value->image)?$value->image:""?>" data-fancybox="images">
													<img src="<?=isset($value->image)?$value->image:""?>" style='max-width:300px;'>
												</a>
												<? } ?>
											</td>
                                            <td>
											<?=isset($value->status)?$status_list[$value->status]:"" ?>
												<? if($value->status){?>
													<button onclick="failed(<?=isset($value->id)?$value->id:"" ?>);" type="button" class="btn btn-danger btn-circle"><i class="fa fa-pause"></i></button>
												<? }else{ ?>
													<button onclick="success(<?=isset($value->id)?$value->id:"" ?>);" type="button" class="btn btn-success btn-circle"><i class="fa fa-play"></i> </button>
												<? } ?>
											</td>
											<td><?=isset($value->rank)?$value->rank:"" ?></td>
											<td><a href="<?=admin_url('Article/edit?id=').$value->id ?>" class="btn btn-default">Edit</a></td> 
											<td><button onclick="del(<?=isset($value->id)?$value->id:"" ?>);" type="button" class="btn btn-danger btn-circle"><i class="fa fa-times"></i></button></td> 
										</tr>
									<?php 
										}}
									?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>
        <!-- /#page-wrapper -->