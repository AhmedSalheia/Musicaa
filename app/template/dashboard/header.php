<div class="header navbar">
    <div class="header-container">
        <ul class="nav-left">
            <li><a id="sidebar-toggle" class="sidebar-toggle" href="javascript:void(0);"><i class="ti-arrow-right"></i></a>
            </li>
            <li class="search-box"><a class="search-toggle no-pdd-right" href="javascript:void(0);"><i
                            class="search-icon ti-search pdd-right-10"></i> <i
                            class="search-icon-close ti-close pdd-right-10"></i></a></li>
            <li class="search-input"><input class="form-control" type="text" placeholder="Search..."></li>
        </ul>
        <ul class="nav-right">
            <li class="dropdown"><a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1"
                                    data-toggle="dropdown">
                    <div class="peer mR-10"><img class="w-2r bdrs-50p"
                                                 src="<?= DASH_IMG.$admin['img'] ?>" alt=""></div>
                    <div class="peer"><span class="fsz-sm c-grey-900"><?= $admin['name'] ?></span></div>
                </a>
                <ul class="dropdown-menu fsz-sm">
                    <li><a href="<?= URL ?>settings/data" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700"><i class="ti-user mR-10"></i>
                            <span>Profile</span></a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="<?= URL ?>index/logout" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700"><i
                                    class="ti-power-off mR-10"></i> <span>Logout</span></a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
