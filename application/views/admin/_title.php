        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=admin_url("AdminDashboard") ?>">普匯金融科技內部管理後台</a>
                <p class="navbar-brand">您好，<?=isset($login_info->name)?$login_info->name:""?></p>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
							<a href="<?=admin_url('AdminDashboard/personal') ?>"><i class="fa fa-user fa-fw"></i> 個人資料</a>
                        </li>
                        <li class="divider"></li>
                        <li>
							<a href="<?=admin_url('Admin/logout') ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
	
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
					<? if(!empty($menu)){
							foreach($menu as $key => $value){
								if(isset($value["name"])){
					?>
							<li class="<?=$active==$key?"active":""; ?>">
								<a href="<?=admin_url($key.'/') ?>"><i class="fa <?=$value["icon"] ?> fa-fw"></i> <?=$value["name"] ?></a>
							</li>
					
								<?}else{?>
                        
							<li class="<?=$active==$key?"active":""; ?>">
								<a href="#"><i class="fa <?=$value["parent_icon"] ?> fa-fw"></i><?=$value["parent_name"] ?><span class="fa arrow"></span></a>
								<ul class="nav nav-second-level">
									<? 
										unset($value["parent_icon"],$value["parent_name"]);
										foreach($value as $k => $v){
									?>
									<li>
										<a href="<?=admin_url($key.'/'.$k) ?>"><?=$v ?></a>
									</li>
									<? } ?>
								</ul>
							</li>
					<?}}}?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>