        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?=admin_url('AdminDashboard') ?>">
                    <img src="<?=FRONT_CDN_URL ?>public/logo.png" alt="" width="150px" />
                </a>
                <h5 class="navbar-brand"><?=isset($login_info->name)?$login_info->name:''?> [ <?= "{$role_info['department']} {$role_info['position']}" ?> ]</h5>
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
                    <?php if (! empty($menu)):?>
                        <?php foreach ($menu as $key => $value):
                            if (empty($value['parent_url']) || empty($value['parent_name'])) continue; ?>
                            <li data-id="<?= $key ?>" class="<?= ($active == $key) ? 'active' : '' ?>">
                                <a href="<?= $value['parent_url'] ?>"><?= $value['parent_name'] ?>
                                    <?= ! empty($value['sub']) ? '<span class="fa arrow"></span>' : '' ?>
                                </a>
                                <?php if ( ! empty($value['sub']))
                                { ?>
                                    <ul class="nav nav-second-level">
                                        <?php array_map(function ($item) {
                                            if (empty($item['url']) || empty($item['name'])) return;
                                            echo '<li><a href="' . $item['url'] . '">' . $item['name'] . '</a></li>';
                                        }, $value['sub']); ?>
                                    </ul>
                                <?php } ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif;?>
                    <?php if (is_development()): ?>
                        <li><a href="<?=admin_url('TestScript/')?>">測試工具</a></li>
                    <?php endif; ?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>