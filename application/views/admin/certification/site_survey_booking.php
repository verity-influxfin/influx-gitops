<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">入屋現勘/遠端視訊預約時間</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    入屋現勘/遠端視訊預約時間
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>日期</label>
                                <input type="text" class="form-control" disabled value="<?= $content['date'] ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>時間</label>
                                <input type="text" class="form-control" disabled value="<?= $content['time'] ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" id="submit" onclick="window.close()">確認
                                </button>
                            </div>
                        </div>
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