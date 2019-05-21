<script>
    /*tinymce.init({
        selector: 'textarea',
        height: 500
    });*/
</script>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">合約書</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    合約書
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <form action="updateContract" method="post">
                                <div class="form-group">
                                    <label>名稱</label>
                                    <input id="" name="" class="form-control" placeholder="輸入名稱" value="<?=isset($contract->title)?$contract->title:"" ?>">
                                    <input type="hidden" name="id" value="<?=isset($contract->id)?$contract->id:"" ?>">
                                </div>
                                <div class="form-group">
                                    <label>代號</label>
                                    <input id="" name="" class="form-control" placeholder="輸入代號" value="<?=isset($contract->type)?$contract->type:"" ?>" >
                                </div>
                                <div class="form-group">
                                    <label>內容</label>
                                    <textarea id="content" name="content" class="form-control" rows="3" style="width: 725px; height: 800px;">
										<?=isset($contract->content)?$contract->content:"";?>
										</textarea>
                                </div>
                                <div class="form-group">
                                    <label>版本</label>
                                    <input id="version" name="version" class="form-control" placeholder="輸入版本" value="<?=isset($contract->version)?$contract->version:"" ?>" >
                                </div>
                                <div class="form-group">
                                    <label>備註</label>
                                    <input id="remark" name="remark" class="form-control" placeholder="輸入備註" value="<?=isset($contract->remark)?$contract->remark:"" ?>" >
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