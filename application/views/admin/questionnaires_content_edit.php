
	<script>
	
		function form_onsubmit(){
			return true;
		}
	</script>
	<style>
		.content .question {
			margin-bottom: 20px;
			border: 1px solid gray;
			padding: 10px;
			border-radius: 10px;
		}
	</style>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">設定題目</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

				<input type="hidden" name="id" value="<?=isset($data->id)?$data->id:"";?>" >
				<div class="form-group">
					<a href="javascript:void(0);" class="btn btn-default">新增單選題</a>
					<a href="javascript:void(0);" class="btn btn-default">新增多選題</a>
				</div>

				<div class="row">
					<div class="content col-lg-12">
						<ul>
							<li>
								<div class="question row">
									<div class="col-lg-10">
										<label>問題1.</label>
										<textarea class="form-control" rows="2"></textarea><br>
										<div class="form-group">
											<a href="javascript:void(0);" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span></a>
										</div>
										<div class="options row">
											<div class="q1 row">
												<span class="col-lg-2"><a href="javascript:void(0);" class="btn btn-default">X</a> 選項：</span>
												<input name="q1" class="col-lg-7" placeholder="輸入選項" value="">
												<span class="col-lg-1">分值：</span>
												<input name="q1" class="col-lg-2" placeholder="分值" value="">
											</div>
											<div class="q1 row">
												<span class="col-lg-2"><a href="javascript:void(0);" class="btn btn-default">X</a> 選項：</span>
												<input name="q1" class="col-lg-7" placeholder="輸入選項" value="">
												<span class="col-lg-1">分值：</span>
												<input name="q1" class="col-lg-2" placeholder="分值" value="">
											</div>
										</div>
										
									</div>
									<div class="button col-lg-2">
										<div class="form-group">
											<a href="javascript:void(0);" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></a>
										</div>
										<div class="form-group">
											<label>Next</label>
											<select class="form-control">
												<option>下一題</option>
												<option>結束</option>
												<option>3</option>
												<option>4</option>
												<option>5</option>
											</select>
										</div>
									</div>
								</div>
							</li>
							<li>
								<div class="question row">
									<div class="col-lg-10">
										<label>問題1.</label>
										<textarea class="form-control" rows="2"></textarea><br>
										<div class="form-group">
											<a href="javascript:void(0);" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span></a>
										</div>
										<div class="options row">
											<div class="q1 row">
												<span class="col-lg-2"><a href="javascript:void(0);" class="btn btn-default">X</a> 選項：</span>
												<input name="q1" class="col-lg-7" placeholder="輸入選項" value="">
												<span class="col-lg-1">分值：</span>
												<input name="q1" class="col-lg-2" placeholder="分值" value="">
											</div>
											<div class="q1 row">
												<span class="col-lg-2"><a href="javascript:void(0);" class="btn btn-default">X</a> 選項：</span>
												<input name="q1" class="col-lg-7" placeholder="輸入選項" value="">
												<span class="col-lg-1">分值：</span>
												<input name="q1" class="col-lg-2" placeholder="分值" value="">
											</div>
										</div>
										
									</div>
									<div class="button col-lg-2">
										<div class="form-group">
											<a href="javascript:void(0);" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></a>
										</div>
										<div class="form-group">
											<label>Next</label>
											<select class="form-control">
												<option>下一題</option>
												<option>結束</option>
												<option>3</option>
												<option>4</option>
												<option>5</option>
											</select>
										</div>
									</div>
								</div>
							</li>
						</ul>  
					</div>
				</div>
        </div>
        <!-- /#page-wrapper -->
