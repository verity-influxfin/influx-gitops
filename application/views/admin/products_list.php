        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">產品列表</h1>
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
                                <table class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NO.</th>
                                            <th>產品類型</th>
                                            <th>身份類型</th>
                                            <th>名稱</th>
                                            <th>簡介</th>
                                            <th>借款額度</th>
                                            <th>年利率（%）</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php 
										if(isset($product_list) && !empty($product_list)){
											$count = 0;
											foreach($product_list as $key => $value){
												$count++;
									?>
                                        <tr class="<?=$count%2==0?"odd":"even"; ?>">
                                            <td><?=isset($value['id'])?$value['id']:'' ?></td>
                                            <td><?=isset($product_type[$value['type']])?$product_type[$value['type']]:'' ?></td>
                                            <td><?=isset($product_identity[$value['identity']])?$product_identity[$value['identity']]:'' ?></td>
                                            <td><?=isset($value['name'])?$value['name']:'' ?></td>
                                            <td><p><?=isset($value['description'])?$value['description']:'' ?></p></td>
											<td><?=isset($value['loan_range_s'])?$value['loan_range_s']:'' ?> - <?=isset($value['loan_range_e'])?$value['loan_range_e']:'' ?></td>
											<td><?=isset($value['interest_rate_s'])?$value['interest_rate_s']:'' ?> - <?=isset($value['interest_rate_e'])?$value['interest_rate_e']:'' ?></td>
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