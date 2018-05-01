<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <!-- <a class="navbar-brand" rel="home" href="javascript:void(0)"><?= $layout_title ?></a> -->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse">
            <div class="col-sm-12 col-md-12 col-lg-6 text-center navbar-left">
                <form class="navbar-form" role="search" method="post">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search PakDemocrates" id="search_term" name="search_term">
                        <span class="input-group-btn">
                            <button class="btn btn-default" id="search_btn" name="search_btn" type="button">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
            <ul id="navbar-items" class="nav navbar-nav navbar-right">
                <li><a class="text-center" href="<?= site_url('front_end/home') ?>"><i class="fa fa-home" aria-hidden="true"></i> Home</a></li>
                <li id="politician-nav-item"><a class="text-center" href="<?= site_url('front_end/politician') ?>"><i class="fa fa-users" aria-hidden="true"></i> Politician</a></li>
                <li id="political_party-nav-item"><a class="text-center" href="<?= site_url('front_end/political_party') ?>"><i class="fa fa-users" aria-hidden="true"></i> Political Party</a></li>
                <li id="columnist-nav-item"><a class="text-center" href="<?= site_url('front_end/columnist') ?>"><i class="fa fa-files-o fa-fw" aria-hidden="true"></i> Columnist</a></li>
                <li id="nav-login"><a class="text-center" href="javascript:void(0)"><i class="fa fa-sign-in" aria-hidden="true"></i> Log In</a></li>
                <li id="nav-signup"><a class="text-center" href="javascript:void(0)"><i class="fa fa-user-plus" aria-hidden="true"></i> Sign Up</a></li>
<!--                 <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                    <li class="divider"></li>
                    <li><a href="#">One more separated link</a></li>
                  </ul>
                </li> -->
            </ul>
        <!--         <div class="col-sm-3 col-md-3 pull-right">
              <form class="navbar-form" role="search">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                </div>
              </form>
            </div> -->
        </div>
    </div>
</nav>