        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">學校列表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
							
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>名稱</th>
                                            <th>國立公立</th>
                                            <th>地區</th>
                                            <th>地址</th>
                                            <th>電話</th>
                                            <th>學校網址</th>
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
                                            <td><?=isset($value->id)?$value->id:"" ?></td>
                                            <td><?=isset($value->name)?$value->name:"" ?></td>
                                            <td><?=isset($value->public)?$value->public:"" ?></td>
                                            <td><?=isset($value->city)?$value->city:"" ?></td>
											<td><?=isset($value->address)?$value->address:"" ?></td>
											<td><?=isset($value->phone)?$value->phone:"" ?></td>
											<td><a href="<?=isset($value->url)?$value->url:"" ?>"><?=isset($value->url)?$value->url:"" ?></a></td> 
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