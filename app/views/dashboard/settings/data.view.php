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
                                <li class="nav-item"><a href="<?= URL ?>settings/themes" class="nav-link c-grey-800 cH-blue-500">
                                        <div class="peers ai-c jc-sb">
                                            <div class="peer peer-greed">
                                                <i class="mR-10 ti-direction-alt"></i> <span>Themes</span>
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
                                <li class="nav-item"><a href="javascript:void(0)" class="nav-link c-grey-800 cH-blue-500">
                                        <div class="peers ai-c jc-sb text-primary">
                                            <div class="peer peer-greed"><i class="mR-10 ti-settings ti-angle-double-right"></i>
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
                            <div class="d-n@md+ p-20"><a class="email-side-toggle c-grey-900 cH-blue-500 td-n"
                                                         href="javascript:void(0)"><i class="ti-menu"></i></a>
                            </div>

                            <form class="email-compose-body" method="post">
                                <h4 class="c-grey-900 mb-0">Login Data</h4>
                                <p class="c-grey-500 mB-20">This is the default settings for Login</p>

                                <div class="send-header">
                                    <div class="container-fluid">
                                        <div class="form-group row">
                                            <input type="email" class="form-control col mr-4" placeholder="Email" name="email" value="<?= $login['email'] ?>">
                                            <input type="text" class="form-control col mr-4" placeholder="Password" name="password" value="<?= $login['password'] ?>">
                                            <input type="text" class="form-control col" placeholder="username" name="name" value="<?= $login['name'] ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right mrg-top-30">
                                    <button class="btn btn-primary" name="sub" value="login" type="submit"><i class="ti-save-alt"> </i> Save</button>
                                    <button class="btn btn-danger" type="reset"><i class="ti-back-left"> </i> Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
