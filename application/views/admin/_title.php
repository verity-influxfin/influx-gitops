        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=admin_url("AdminDashboard") ?>">P2P Lending</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <!--li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li-->
                        <li class="divider"></li>
                        <li><a href="<?=admin_url('admin/logout') ?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
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
                        <li>
                            <a href="<?=admin_url('AdminDashboard/') ?>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li class="<?=isset($menu)&&$menu=="product"?"active":""; ?>">
                            <a href="#"><i class="fa fa-table fa-fw"></i>產品管理<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?=admin_url('product/') ?>">產品列表</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li class="<?=isset($menu)&&$menu=="target"?"active":""; ?>">
                            <a href="#"><i class="fa fa-table fa-fw"></i>標的管理<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?=admin_url('target/') ?>">標的列表</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>                        
						<li class="<?=isset($menu)&&$menu=="user"?"active":""; ?>">
                            <a href="#"><i class="fa fa-table fa-fw"></i>會員管理<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?=admin_url('user/') ?>">會員列表</a>
                                </li>
								<li>
                                    <a href="<?=admin_url('certification/user_certification_list') ?>">會員申請認證</a>
                                </li> 
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li class="<?=isset($menu)&&$menu=="certification"?"active":""; ?>">
                            <a href="#"><i class="fa fa-table fa-fw"></i>認證管理<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?=admin_url('certification/') ?>">認證方式列表</a>
                                </li> 
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						 <li class="<?=isset($menu)&&$menu=="admin"?"active":""; ?>">
                            <a href="#"><i class="fa fa-user fa-fw"></i>後台人員管理<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?=admin_url('admin/') ?>">後台管理員</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li class="<?=isset($menu)&&$menu=="agreement"?"active":""; ?>">
                            <a href="#"><i class="fa fa-table fa-fw"></i>協議書管理<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?=admin_url('Agreement/') ?>">協議書</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
						<li class="<?=isset($menu)&&$menu=="test"?"active":""; ?>">
                            <a href="#"><i class="fa fa-table fa-fw"></i>測試工具<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?=admin_url('TestScript/') ?>">申貸流程</a>
                                </li>
								<li>
                                    <a href="<?=admin_url('TestScript/payment') ?>">匯款工具</a>
                                </li> 
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>