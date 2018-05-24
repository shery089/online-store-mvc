<!-- Sidebar -->
<div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a href="javascript:void(0)"><i class="fa fa-inbox fa-fw"></i> Product Section<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="<?= site_url('admin/product'); ?>"><i class="fa fa-table fa-fw"></i> Product</a>
                        </li>
                        <li>
                            <a href="<?= site_url('admin/product_attribute'); ?>"><i class="fa fa-table fa-fw"></i> Product Attributes</a>
                        </li>
                        <li>
                            <a href="<?= site_url('admin/product_attribute_detail'); ?>"><i class="fa fa-th fa-fw"></i> Product Attributes Details</a>
                        </li>
                        <li>
                            <a href="<?= site_url('admin/category'); ?>"><i class="fa fa-table fa-fw"></i> Category</a>
                        </li>
                        <li>
                            <a href="<?= site_url('admin/company'); ?>"><i class="fa fa-building fa-fw"></i> Company</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>

                <li>
                    <a href="javascript:void(0)"><i class="fa fa-users fa-fw"></i> User Section<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="<?= site_url('admin/user'); ?>"><i class="fa fa-user fa-fw"></i> Users</a>
                        </li>
                        <li>
                            <a href="<?= site_url('admin/role'); ?>"><i class="fa fa-sitemap fa-fw"></i> Roles</a>
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
<!-- / Sidebar -->