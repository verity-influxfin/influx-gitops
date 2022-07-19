<style>
    .form-control-static {
        width: 100%;
    }

    .form-control-static span {
        width: 15%;
        position: relative;
        display: inline-block;
    }

    .form-control-static input {
        text-align: right;
    }
</style>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $certification_list[CERTIFICATION_TARGET_APPLY] ?? '' ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form role="form" method="post" action="/admin/certification/user_certification_edit">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?= $id ?? 0; ?>">
                                    <input type="hidden" name="certification_id"
                                           value="<?= CERTIFICATION_TARGET_APPLY; ?>">
                                </div>
                                <h4>審核</h4>
                                <div class="form-group">
                                    <div class="form-control-static">
                                        <label for="status"></label>
                                        <select id="status" name="status" class="form-control">
                                            <? foreach ($status_list as $key => $value) { ?>
                                                <option value="<?= $key ?>"
                                                    <?= $data->status == $key ? "selected" : "" ?>><?= $value ?></option>
                                            <? } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary">確認送出</button>
                            </div>
                        </div>
                    </form>
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