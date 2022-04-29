        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">企業評分表</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <span>標的資訊</span>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="col-lg-12">
                            <h3><?=$name?></h3>
                            <hr style="border:1px solid gray;"/>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" style="font-weight:bold;font-size:16px;" width="100%">
                                    <tr><td>產業類別</td><td><?=$type?></td></tr>
                                </table>
                                <table class="table table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>項目</th>
                                            <th>說明</th>
                                            <th>評分標準</th>
                                            <th>得分</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    	<?php foreach ($details as $detail) { ?>
                                    		<tr>
                                                <td><?=$detail['item']?></td>
                                                <td><?=$detail['subitem']?></td>
                                                <td><?=$detail['option']?></td>
                                                <td><?=$detail['score']?></td>
                                            </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                                <table class="table table-bordered" style="font-weight:bold;font-size:16px;" width="100%">
                                    <tr><td>總分</td><td><?=$total?></td></tr>
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