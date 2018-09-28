
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
					<a href="<?=admin_url('Target/waiting_verify') ?>">
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
					<a href="<?=admin_url('Target/index?status=3') ?>">
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
								<div class="huge"><?=$target_count["success"];?></div>
								<div>待放款</div>
							</div>
						</div>
					</div>
					<a href="<?=admin_url('Target/waiting_loan') ?>">
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
								<div>還款已逾期</div>
							</div>
						</div>
					</div>
					<a href="<?=admin_url('Target/index?delay=1&status=5') ?>">
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
			<!-- /.col-lg-6 -->
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						註冊、申請成功
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div id="morris-bar-chart"></div>
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-6 -->
			<div class="col-lg-6">
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

		</div>
		<!-- /.row -->
	</div>
	<!-- /#page-wrapper -->
	<script>
	$(function() {
		Morris.Bar({
			element: 'morris-bar-chart',
			data: [
			<? if($chart_list){
				$count=0;
				foreach($chart_list as $date => $value){
					if($count!=0){
						echo ',';
					}
			?>
			{
				y: '<?=$date ?>',
				a: <?=$value['register'] ?>,
				b: <?=$value['loan'] ?>
			}
			<? 
				$count++;}}
			?>
			],
			xkey: 'y',
			ykeys: ['b','a'],
			labels: ['申貸成功','註冊'],
			hideHover: 'auto',
			resize: true
		});
		});
	</script>