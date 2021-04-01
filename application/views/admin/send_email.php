
	<script>
	
		function form_onsubmit(msg){
			if(confirm(msg)){
				return true;
			}
			return false;
		}
	</script>
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
