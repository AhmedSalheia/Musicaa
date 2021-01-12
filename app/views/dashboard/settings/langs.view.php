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
                                <li class="nav-item"><a href="javascript:void(0)" class="nav-link c-grey-800 cH-blue-500">
                                        <div class="peers ai-c jc-sb text-primary">
                                            <div class="peer peer-greed"><i class="mR-10 ti-angle-double-right"></i> <!-- ti-text -->
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
                            <div class="container-fluid">
                                <div class="peers ai-c jc-sb pX-20 pY-20">
                                    <h4 class="c-grey-900 mB-20">Languages: </h4>
                                    <div class="peer"><a href="<?= URL ?>langs/add" class="btn btn-danger bdrs-50p p-15 lh-0"><i
                                                    class="fa fa-plus"></i></a></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="bgc-white bd bdrs-3 p-20 mB-20"><h4 class="c-grey-900 mB-20">Languages In The Database</h4>
                                            <table id="dataTable" class="table table-hover table-striped table-bordered" cellspacing="0"
                                                   width="100%">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Language</th>
                                                    <th>Full Name</th>
                                                    <th title="Actions">A.</th>
                                                </tr>
                                                </thead>
                                                <tfoot>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Language</th>
                                                    <th>Full Name</th>
                                                    <th title="Actions">A.</th>
                                                </tr>
                                                </tfoot>
                                                <tbody>
                                                <?php
                                                foreach ($langs as $lang)
                                                {
                                                    ?>

                                                    <tr>
                                                        <td><?= $lang->id ?></td>
                                                        <td><?= $lang->name ?></td>
                                                        <td><?= $lang->full_name ?></td>
                                                        <td style='height: 60px' width='37' class='p-0'>
                                                            <div class='d-flex w-100 h-100 text-center align-items-center' style='flex-direction: column'>
                                                                <a href='<?= URL ?>langs/edit/<?= $lang->id ?>' title="edit" class='bg-info w-100 h-50 d-flex justify-content-center' style='flex-direction: column;max-width: 70px'><i class='text-white ti-pencil'></i></a>
                                                                <a href='<?= URL ?>langs/delete/<?= $lang->id ?>' title="delete" class='bg-danger w-100 h-50 d-flex justify-content-center' style='flex-direction: column;max-width: 70px' onclick='if (!confirm("Do You Really Want To Delete This Language?")){return false}'><i class='text-white ti-close'></i></a>
                                                            </div>
                                                        </td>
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
