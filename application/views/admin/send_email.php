
	<script>
	
		function form_onsubmit(msg){
			if(confirm(msg)){
				return true;
			}
			return false;
		}
	</script>
	
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">通知工具</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
						發送Email
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post" onsubmit="return form_onsubmit('確認發送此郵件？');" >
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input id="email" name="email" class="form-control" placeholder="Enter Email" >
                                        </div>
										<div class="form-group">
                                            <label>標題</label>
											<input id="title" name="title" class="form-control" placeholder="Enter Title" >
                                        </div>
                                        <div class="form-group">
                                            <label>內容</label>
											<textarea id="content" name="content" class="form-control" rows="5"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-default">Submit Button</button>
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

			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							發送推播
						</div>
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12">
									<form role="form" method="post" onsubmit="return form_onsubmit('確認發送此推播？');" >
										<div class="align-items-start">
											<div class="form-group mr-3">
												<label>發送對象：</label><br/>
												<input type="checkbox" name="loan" value="1">
												<label for="loan">借款</label>
												<input type="checkbox" name="investment" value="1">
												<label for="investment">投資</label>
											</div>
											<div class="form-group mr-3">
												<label>平台：</label><br/>
												<input type="checkbox" name="android" value="1">
												<label for="android">Android</label>
												<input type="checkbox" name="ios" value="1">
												<label for="ios">IOS</label>
											</div>
											<div class="form-group mr-3">
												<label>性別：</label><br/>
												<input type="radio" name="gender" value="M">
												<label for="male">男</label>
												<input type="radio" name="gender" value="F">
												<label for="female">女</label>
											</div>
											<div class="form-group">
												<label>年齡：</label><br/>
												<input type="text" name="age_range_start">
												－
												<input type="text" name="age_range_end">
											</div>
										</div>
										<hr/>
										<div class="form-group">
											<label>會員id（請用逗點隔開，或以-指定範圍）：</label><br/>
											<input type="text" name="user_ids" placeholder="1,2,3,20-35,...">
										</div>
										<hr/>
										<div class="form-group">
											<label>標題</label>
											<input id="title" name="title" class="form-control" placeholder="輸入標題" >
										</div>
										<div class="form-group">
											<label>內容</label>
											<textarea id="content" name="content" class="form-control" rows="5"></textarea>
										</div>
										<hr>
										<div class="form-group">
											<label>跳轉到標的：(選填，僅供投資端app使用)</label>
											<input id="title" name="title" class="form-control" placeholder="請輸入案件編號(STN000000000)" >
										</div>
										<div class="form-group">
											<label>發送時間：</label>
											<input type="radio" name="send_right_now">
											<label for="male">即時發送</label>
											<div class='input-group date' id='datetimepicker_notify'>
												<input type='text' name="send_date" class="form-control" />
												<span class="input-group-addon">
										    <span class="glyphicon glyphicon-calendar"></span>
										    </span>
											</div>
										</div>
										<input type="hidden" name="notification" value="1">
										<div class="form-group">
											<div class="align-items-start col-md-6">
												<button type="submit" class="btn btn-default">送出按鈕</button>
											</div>
											<div class="align-items-end col-md-6">
												<button type="submit" class="btn btn-primary">查詢送出紀錄</button>
											</div>
										</div>
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
        <!-- /#page-wrapper -->

	<script type="text/javascript">
		$(function () {
			$('#datetimepicker_notify').datetimepicker({
				defaultDate: "11/1/2013",
			});
		});
	</script>
