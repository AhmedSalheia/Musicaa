<main class="main-content bgc-grey-100">
    <div id="mainContent">
        <div class="full-container">
            <div class="email-app">
                <div class="email-side-nav remain-height ov-h">
                    <div class="h-100 layers">
                        <div class="scrollable pos-r bdT layer w-100 fxg-1">
                            <ul class="p-20 nav flex-column">
                                <li class="nav-item"><a href="<?= URL ?>settings/privacy"
                                                        class="nav-link c-grey-800 cH-blue-500 actived">
                                        <div class="peers ai-c jc-sb">
                                            <div class="peer peer-greed"><i class="mR-10 ti-pencil-alt"></i>
                                                <span>Privacy Policy</span></div>
                                        </div>
                                    </a></li>
                                <li class="nav-item"><a href="<?= URL ?>settings/terms" class="nav-link c-grey-800 cH-blue-500">
                                        <div class="peers ai-c jc-sb">
                                            <div class="peer peer-greed"><i class="mR-10 ti-thought"></i>
                                                <span>Terms&Conditions</span></div>
                                        </div>
                                    </a></li>
                                <li class="nav-item"><a href="javascript:void(0)" class="nav-link c-grey-800 cH-blue-500">
                                        <div class="peers ai-c jc-sb text-primary">
                                            <div class="peer peer-greed">
                                                <i class="mR-10 ti-angle-double-right"></i> <span>Themes</span>  <!-- ti-direction-alt -->
                                            </div>
                                        </div>
                                    </a></li>
                                <li class="nav-item"><a href="<?= URL ?>settings/langs" class="nav-link c-grey-800 cH-blue-500">
                                        <div class="peers ai-c jc-sb">
                                            <div class="peer peer-greed"><i class="mR-10 ti-text"></i>
                                                <span>Languages</span></div>
                                        </div>
                                    </a></li>
                                <li class="nav-item"><a href="<?= URL ?>settings/os" class="nav-link c-grey-800 cH-blue-500">
                                        <div class="peers ai-c jc-sb">
                                            <div class="peer peer-greed"><i class="mR-10 ti-panel"></i>
                                                <span>Os Settings</span></div>
                                        </div>
                                    </a></li>
                                <li class="nav-item"><a href="<?= URL ?>settings/data" class="nav-link c-grey-800 cH-blue-500">
                                        <div class="peers ai-c jc-sb">
                                            <div class="peer peer-greed"><i class="mR-10 ti-settings"></i>
                                                <span>Emailing & Login</span></div>
                                        </div>
                                    </a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="email-wrapper row remain-height pos-r scrollable bgc-white">
                    <div class="email-content open no-inbox-view">
                        <div class="email-compose">
                            <div class="container-fluid"><h4 class="c-grey-900 mT-10 mB-30">Data Tables</h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="bgc-white bd bdrs-3 p-20 mB-20"><h4 class="c-grey-900 mB-20">Bootstrap Data
                                                Table</h4>
                                            <table id="dataTable" class="table table-hover table-striped table-bordered" cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>English name</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>English name</th>
                                                </tr>
                                                </tfoot>
                                                <tbody>
                                                <?php
                                                foreach ($themes as $theme)
                                                {
                                                    ?>

                                                    <tr>
                                                        <td><?= $theme->id ?></td>
                                                        <td><?= $theme->name ?></td>
                                                    </tr>

                                                    <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
