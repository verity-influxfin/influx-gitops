
	<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="page-header">Dashboard</h1>
				</div>
				<!-- /.col-lg-12 -->
			</div>		<!-- /.row -->
		<div class="row">
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-tasks fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$target_count["approve"];?></div>
								<div>待批覆上架</div>
							</div>
						</div>
					</div>
					<a href="#">
						<div class="panel-footer">
							<span class="pull-left">View Details</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-green">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-tasks fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$target_count["bidding"];?></div>
								<div>已上架</div>
							</div>
						</div>
					</div>
					<a href="#">
						<div class="panel-footer">
							<span class="pull-left">View Details</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-yellow">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-tasks fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$target_count["repay"];?></div>
								<div>還款中</div>
							</div>
						</div>
					</div>
					<a href="#">
						<div class="panel-footer">
							<span class="pull-left">View Details</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-lg-3 col-md-6">
				<div class="panel panel-red">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-tasks fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="huge"><?=$target_count["delay"];?></div>
								<div>已逾期</div>
							</div>
						</div>
					</div>
					<a href="#">
						<div class="panel-footer">
							<span class="pull-left">View Details</span>
							<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
							<div class="clearfix"></div>
						</div>
					</a>
				</div>
			</div>
		</div>
		<!-- /.row -->
		<div class="row">
			<!-- /.col-lg-8 -->
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						投訴與建議
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="list-group">
							<? if(!empty($contact_list)){
								foreach($contact_list as $key => $value){
							?>
							<a href="<?=admin_url('contact/edit?id='.$value->id) ?>" class="list-group-item">
								<?=$value->content ?>
								<span class="pull-right text-muted small">
									<em><?=date("Y-m-d H:i:d",$value->created_at) ?></em>
								</span>
							</a>
							<? }} ?>
						</div>
						<!-- /.list-group -->
						<a href="<?=admin_url('contact/') ?>" class="btn btn-default btn-block">View All Alerts</a>
					</div>
					<!-- /.panel-body -->
				</div>

				<!-- /.panel .chat-panel -->
			</div>
			<!-- /.col-lg-4 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /#page-wrapper -->
