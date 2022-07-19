
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= $contract_name ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= $contract_name ?>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>會員 ID</label>
                                <a class="fancyframe" href="<?=admin_url('User/display?id='.$user_id) ?>" >
                                    <p><?= $user_id??"" ?></p>
                                </a>
                            </div>
                            <div class="form-group">
                                <label>案號</label>
                                <a class="fancyframe" href="<?=admin_url('target/edit?id='.$target_id) ?>" >
                                    <p><?= $target_no??"" ?></p>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <h1>圖片</h1>
                            <fieldset disabled>
                                <div class="form-group">
                                    <label for="disabledSelect"><?= $contract_name ?></label><br>
                                    <?
                                    if(isset($image_url_list)){
                                        foreach ($image_url_list as $key => $value) {
                                            ?>
                                            <a href="<?= $value ?? "" ?>" data-fancybox="images">
                                                <img src="<?= $value ?? "" ?>" style='width:30%;max-width:400px'>
                                            </a>
                                        <? }
                                    }
                                    ?>
                                </div>
                            </fieldset>
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
