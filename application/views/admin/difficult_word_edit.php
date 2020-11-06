
	<script>

		function form_onsubmit(){
			return true;
		}
	</script>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"><?=$type=="edit"?"修改困難字":"新增困難字" ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						<?=$type=="edit"?"修改困難字":"新增困難字" ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post" onsubmit="return form_onsubmit();" >
                                        <div class="form-group">
                                            <label>文字</label>
											<?
												if($type=="edit"){
											?>
												<p class="form-control-static"><?=isset($data->word)?$data->word:"";?></p>
												<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
											<? }else{ ?>
												<input id="word" name="word" class="form-control" maxlength="2">
											<? } ?>
                                        </div>
                                        <div class="form-group">
                                            <label>拆分文字 - 請加全型括弧及＋－ 例：（方＋方＋土）</label>
                                            <input id="spelling" name="spelling" class="form-control" value="<?=isset($data->spelling)?$data->spelling:"";?>">
                                        </div>

                                        <button type="submit" class="btn btn-default">送出</button>
                                    </form>
                                </div>

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
