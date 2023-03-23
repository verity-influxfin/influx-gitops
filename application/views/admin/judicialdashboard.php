<script src="//code.jquery.com/jquery-3.3.1.min.js"></script>
<link href="<?= base_url() ?>assets/admin/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="<?= base_url() ?>assets/admin/css/sb-admin-2.css?t=2" rel="stylesheet">
<!-- Morris Charts CSS -->
<link href="<?= base_url() ?>assets/admin/css/plugins/morris.css" rel="stylesheet">
<!-- Morris Charts JavaScript -->
<script src="<?= base_url() ?>assets/admin/js/plugins/morris/raphael.min.js"></script>
<script src="<?= base_url() ?>assets/admin/js/plugins/morris/morris.min.js"></script>
<?php
    $controller = 'target';
    $method = 'waiting_verify';
    if (isset($permission[$controller][$method]['action']['granted']) &&
    isset($permission[$controller][$method]['action']['valid']) &&
    $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
    )
    { ?>
<div class="col-lg-3 col-md-6">
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
        <a target="_parent" href="<?= admin_url("{$controller}/{$method}") ?>">
            <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
    </div>
</div>
<?php } ?>

<?php
    $controller = 'target';
    $method = 'waiting_bidding';
    if (isset($permission[$controller][$method]['action']['granted']) &&
    isset($permission[$controller][$method]['action']['valid']) &&
    $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
    )
    { ?>
<div class="col-lg-3 col-md-6">
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
        <a target="_parent" href="<?= admin_url("{$controller}/{$method}") ?>">
            <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
    </div>
</div>
<?php } ?>

<?php if ($type != 'judicial')
{
    $controller = 'target';
    $method = 'waiting_loan';
    if (isset($permission[$controller][$method]['action']['granted']) &&
        isset($permission[$controller][$method]['action']['valid']) &&
        $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
    )
    { ?>
    <div class="col-lg-3 col-md-6">
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
            <a target="_parent" href="<?= admin_url("{$controller}/{$method}") ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <?php }
} ?>

<?php if ($type == 'judicial')
{
    $controller = 'target';
    $method = 'waiting_verify';
    if (isset($permission[$controller][$method]['action']['granted']) &&
        isset($permission[$controller][$method]['action']['valid']) &&
        $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
    )
    { ?>
    <div class="col-lg-3 col-md-6">
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
            <a target="_parent" href="<?= admin_url("{$controller}/{$method}") ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <?php }
} ?>

<?php
    $controller = 'target';
    $method = 'repayment_delayed';
    if (isset($permission[$controller][$method]['action']['granted']) &&
    isset($permission[$controller][$method]['action']['valid']) &&
    $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
    )
    { ?>
<div class="col-lg-3 col-md-6">
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
        <a target="_parent" href="<?= admin_url("{$controller}/{$method}?delay=1&status=5") ?>">
            <div class="panel-footer">
                <span class="pull-left">View Details</span>
                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                <div class="clearfix"></div>
            </div>
        </a>
    </div>
</div>
<?php } ?>

<?php if ($type != 'judicial')
{
    $controller = 'target';
    $method = 'prepayment';
    if (isset($permission[$controller][$method]['action']['granted']) &&
        isset($permission[$controller][$method]['action']['valid']) &&
        $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
    )
    { ?>
    <div class="col-lg-3 col-md-6">
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
            <a target="_parent" href="<?= admin_url("{$controller}/{$method}") ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <?php }
} ?>

<?php if ($type != 'judicial')
{
    $controller = 'transfer';
    $method = 'waiting_transfer';
    if (isset($permission[$controller][$method]['action']['granted']) &&
        isset($permission[$controller][$method]['action']['valid']) &&
        $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
    )
    { ?>
    <div class="col-lg-3 col-md-6">
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
            <a target="_parent" href="<?= admin_url("{$controller}/{$method}") ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <?php }
} ?>

<?php if ($type != 'judicial')
{
    $controller = 'transfer';
    $method = 'waiting_transfer_success';
    if (isset($permission[$controller][$method]['action']['granted']) &&
        isset($permission[$controller][$method]['action']['valid']) &&
        $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
    )
    { ?>
    <div class="col-lg-3 col-md-6">
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
            <a target="_parent" href="<?= admin_url("{$controller}/{$method}") ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <?php }
} ?>

<?php if ($type != 'judicial')
{
    $controller = 'passbook';
    $method = 'withdraw_waiting';
    if (isset($permission[$controller][$method]['action']['granted']) &&
        isset($permission[$controller][$method]['action']['valid']) &&
        $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
    )
    { ?>
    <div class="col-lg-3 col-md-6">
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
            <a target="_parent" href="<?= admin_url("{$controller}/{$method}") ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <?php }
} ?>

<?php if ($type != 'judicial')
{
    $controller = 'target';
    $method = 'waiting_approve_order_transfer';
    if (isset($permission[$controller][$method]['action']['granted']) &&
        isset($permission[$controller][$method]['action']['valid']) &&
        $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
    )
    { ?>
    <div class="col-lg-3 col-md-6">
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
            <a target="_parent" href="<?= admin_url("{$controller}/{$method}") ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <?php }
} ?>

<?php if ($type != 'judicial')
{
    $controller = 'target';
    $method = 'waiting_evaluation';
    if (isset($permission[$controller][$method]['action']['granted']) &&
        isset($permission[$controller][$method]['action']['valid']) &&
        $permission[$controller][$method]['action']['granted'] & $permission[$controller][$method]['action']['valid']
    )
    { ?>
    <div class="col-lg-3 col-md-6">
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
            <a target="_parent" href="<?= admin_url("{$controller}/{$method}") ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Details</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <?php }
} ?>

<div class="row">
    <!-- /.col-lg-6 -->
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                註冊、上架
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div id="morris-area-chart2"></div>
            </div>
            <!-- /.panel-body -->
        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-6 -->
</div>

<!-- /#page-wrapper -->
<script>
    $(function () {
        Morris.Area({
            element: 'morris-area-chart2',
            data: [
                <? if($chart_list){
                $count = 0;
                foreach($chart_list as $date => $value){
                if ($count != 0) {
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
            ykeys: ['loan', 'register'],
            labels: ['上架', '註冊'],
            pointSize: 2,
            hideHover: 'auto',
            resize: true
        });
    });
</script>
