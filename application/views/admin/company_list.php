<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">千大企業清單</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <table id="dataTables-paging" style="width:100%">
                        <thead>
                            <tr>
                                <th>公司名稱</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                        <? foreach($taiwan_1000_list as $key => $value){ ?>
						    <tr>
                                <td><?=$key?></td>
                            </tr>
						<? } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>
