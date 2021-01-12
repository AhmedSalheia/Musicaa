<main class="main-content bgc-grey-100">
    <div id="mainContent">
        <div class="container-fluid"><h4 class="c-grey-900 mT-10 mB-30">Users</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="bgc-white bd bdrs-3 p-20 mB-20"><h4 class="c-grey-900 mB-20">Data</h4>
                        <table id="dataTable" class="table table-hover table-bordered" cellspacing="0"
                               width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>Gender</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Country</th>
                                <th>Gender</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            <?php

                            use MUSICAA\models\Data;
                            use MUSICAA\models\Genders;

                            foreach ($users as $user)
                                {
                                    $id = $user->id;
                                    ?>
                                    <tr>
                                        <td><a href="/users/details/<?= $id ?>"><?= $id ?></a></td>
                                        <td><a href="/users/details/<?= $id ?>"><?= $user->firstname.' '.$user->middlename.' '.$user->lastname ?></a></td>
                                        <td><?= $user->phone ?></td>
                                        <td><?= $user->email ?></td>
                                        <td><?= ucfirst(Data::get('SELECT * FROM iso_3166_1 WHERE iso="'.($user->country).'"')[0]->printable_name) ?></td>
                                        <td><?= ucfirst(Genders::getByPk($user->gender)->gender) ?></td>
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
</main>
