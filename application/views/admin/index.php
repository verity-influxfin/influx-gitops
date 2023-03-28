<style>
    ul.nav-pills{
        margin: 0 0px 15px 0;
    }
    ul.nav-pills li{
        width: 150px;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        letter-spacing: 2px;
    }
    .nav-pills li.active a{
        background-color: #31b0d5!important;
    }
    #judicial{
        width: 100%;
        overflow: hidden;
        border: 0;
        height: 600px;
    }
</style>

<script>
    $(document).ready(function() {
        $(".judicial a").click(function () {
            $('#personal.show').removeClass('show');
        });
        $(".personal a").click(function () {
            $('#personal.show').addClass('show');
        });
        $('.nav-link.active').click();
    });
</script>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Dashboard</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>		<!-- /.row -->
        <ul class="nav nav-pills">

            <li class="nav-item personal">

                <a class="nav-link active" data-toggle="pill" href="#personal">個金</a>

            </li>

            <li class="nav-item judicial">

                <a class="nav-link" data-toggle="pill" href="#judicial">企金</a>

            </li>

        </ul>
        <div class="tab-content">

            <div class="tab-pane fade show active" id="personal">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class=""><?php
                            $controller = 'target';
                            $method = 'waiting_verify';
                            if (isset($permission[$controller][$method]['action']['granted']) &&
                                isset($permission[$controller][$method]['action']['valid']) &&
                                $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                            )
                            { ?>
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-file-text fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?= $target_count["approve"]; ?></div>
                                                <div>待批覆上架</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?= admin_url("{$controller}/{$method}") ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if ($type != 'judicial') { ?>
                            <div class=""><?php
                                $controller = 'target';
                                $method = 'prepayment';
                                if (isset($permission[$controller][$method]['action']['granted']) &&
                                    isset($permission[$controller][$method]['action']['valid']) &&
                                    $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                                )
                                { ?>
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-file-text fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge"><?= $target_count["prepayment"]; ?></div>
                                                    <div>申請提前還款</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?= admin_url("{$controller}/{$method}") ?>">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($type != 'judicial') { ?>
                            <div class=""><?php
                                $controller = 'target';
                                $method = 'waiting_approve_order_transfer';
                                if (isset($permission[$controller][$method]['action']['granted']) &&
                                    isset($permission[$controller][$method]['action']['valid']) &&
                                    $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                                )
                                { ?>
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-file-text fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge"><?= $target_count["waiting_approve_order_transfer"]; ?></div>
                                                    <div>消費貸債轉待批覆</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?= admin_url("{$controller}/{$method}") ?>">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class=""><?php
                            $controller = 'target';
                            $method = 'waiting_bidding';
                            if (isset($permission[$controller][$method]['action']['granted']) &&
                                isset($permission[$controller][$method]['action']['valid']) &&
                                $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                            )
                            { ?>
                                <div class="panel panel-green">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-shopping-cart fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?= $target_count["bidding"]; ?></div>
                                                <div>已上架</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?= admin_url("{$controller}/{$method}") ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if ($type != 'judicial') { ?>
                            <div class=""><?php
                                $controller = 'transfer';
                                $method = 'waiting_transfer';
                                if (isset($permission[$controller][$method]['action']['granted']) &&
                                    isset($permission[$controller][$method]['action']['valid']) &&
                                    $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                                )
                                { ?>
                                    <div class="panel panel-green">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-shopping-cart fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge"><?= $target_count["transfer_bidding"]; ?></div>
                                                    <div>債權轉讓 - 已上架</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?= admin_url("{$controller}/{$method}") ?>">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class=""><?php
                            $controller = 'transfer';
                            $method = 'bidding';
                            if (isset($permission[$controller][$method]['action']['granted']) &&
                                isset($permission[$controller][$method]['action']['valid']) &&
                                $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                            )
                            { ?>
                                <div class="panel panel-yellow">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-usd fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?= $bidding_count ?></div>
                                                <div>已投標</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?= admin_url("{$controller}/{$method}") ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if ($type != 'judicial') { ?>
                            <div class=""><?php
                                $controller = 'target';
                                $method = 'waiting_loan';
                                if (isset($permission[$controller][$method]['action']['granted']) &&
                                    isset($permission[$controller][$method]['action']['valid']) &&
                                    $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                                )
                                { ?>
                                    <div class="panel panel-yellow">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-usd fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge"><?= $target_count["success"]; ?></div>
                                                    <div>待放款</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?= admin_url("{$controller}/{$method}") ?>">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($type != 'judicial') { ?>
                            <div class=""><?php
                                $controller = 'transfer';
                                $method = 'waiting_transfer_success';
                                if (isset($permission[$controller][$method]['action']['granted']) &&
                                    isset($permission[$controller][$method]['action']['valid']) &&
                                    $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                                )
                                { ?>
                                    <div class="panel panel-yellow">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-usd fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge"><?= $target_count["transfer_success"]; ?></div>
                                                    <div>債權轉讓 - 待放款</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?= admin_url("{$controller}/{$method}") ?>">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div class=""><?php
                            $controller = 'target';
                            $method = 'waiting_evaluation';
                            if (isset($permission[$controller][$method]['action']['granted']) &&
                                isset($permission[$controller][$method]['action']['valid']) &&
                                $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                            )
                            { ?>
                                <div class="panel panel-yellow">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-shield fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?= $target_count["evaluation"]; ?></div>
                                                <div>二審</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?= admin_url("{$controller}/{$method}") ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if ($type == 'judicial') { ?>
                            <div class=""><?php
                                $controller = 'target';
                                $method = 'waiting_verify';
                                if (isset($permission[$controller][$method]['action']['granted']) &&
                                    isset($permission[$controller][$method]['action']['valid']) &&
                                    $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                                )
                                { ?>
                                    <div class="panel panel-yellow">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-usd fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge"><?= $target_count["manual_handling"]; ?></div>
                                                    <div>人工處理</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?= admin_url("{$controller}/{$method}") ?>">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class=""><?php
                            $controller = 'target';
                            $method = 'repayment_delayed';
                            if (isset($permission[$controller][$method]['action']['granted']) &&
                                isset($permission[$controller][$method]['action']['valid']) &&
                                $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                            )
                            { ?>
                                <div class="panel panel-red">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-wheelchair fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?= $target_count["delay"]; ?></div>
                                                <div>還款已逾期</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="<?= admin_url("{$controller}/{$method}?delay=1&status=5") ?>">
                                        <div class="panel-footer">
                                            <span class="pull-left">View Details</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if ($type != 'judicial') { ?>
                            <div class=""><?php
                                $controller = 'passbook';
                                $method = 'withdraw_waiting';
                                if (isset($permission[$controller][$method]['action']['granted']) &&
                                    isset($permission[$controller][$method]['action']['valid']) &&
                                    $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                                )
                                { ?>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <i class="fa fa-credit-card fa-5x"></i>
                                                </div>
                                                <div class="col-xs-9 text-right">
                                                    <div class="huge"><?= $target_count["withdraw"]; ?></div>
                                                    <div>虛擬帳號提領</div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="<?= admin_url("{$controller}/{$method}") ?>">
                                            <div class="panel-footer">
                                                <span class="pull-left">View Details</span>
                                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                                <div class="clearfix"></div>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <!-- /.col-lg-6 -->
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                註冊、上架
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div id="morris-area-chart"></div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-6 -->
                </div>

            </div>

            <div class="tab-pane fade" id="judicial">
                <iframe id="judicial" src="<?=admin_url('AdminDashboard/?type=judicial') ?>" scrolling='no' ></iframe>
            </div>

        </div>

		<!-- /.row -->
		<div class="row">
			<div class="col-lg-6"><?php
                $controller = 'contact';
                $method = 'index';
                if (isset($permission[$controller][$method]['action']['granted']) &&
                    isset($permission[$controller][$method]['action']['valid']) &&
                    $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
                )
                { ?>
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
                <?php } ?>
				<!-- /.panel .chat-panel -->
			</div>

		</div>
		<!-- /.row -->
	</div>
	<!-- /#page-wrapper -->
	<script>
	$(function() {
		Morris.Area({
			element: 'morris-area-chart',
			data: [
			<? if($chart_list){
				$count=0;
				foreach($chart_list as $date => $value){
					if($count!=0){
						echo ',';
					}
			?>
			{
				date: '<?=$date ?>',
				register: <?=$value['register'] ?>,
				loan: <?=$value['loan'] ?>
			}
			<?
				$count++;}}
			?>
			],
			xkey: 'date',
			ykeys: [ 'loan','register'],
			labels: ['上架','註冊'],
			pointSize: 2,
			hideHover: 'auto',
			resize: true
		});
	});
	</script>
