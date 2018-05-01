<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
            <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/user/change_password_lookup', 'class=form id=change_password_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>

            <!-- Current Password -->

            <div class="col-lg-12 form-item-height">
                <div class="form-group">
                    <?= form_label('Current Password: ', 'current_password'); ?>
                    <?php

                    $data = array(

                        'class'         => 'form-control',
                        'name'          => 'current_password',
                        'id'            => 'current_password'
                    );
                    ?>

                    <?= form_password($data); ?>

                    <div id="current_password_error"></div>

                </div>
            </div>

            <!-- Change Password -->

            <div class="col-lg-12 form-item-height">
                <div class="form-group">
                    <?= form_label('Change Password: ', 'new_password'); ?>
                    <?php

                    $data = array(

                        'class'         => 'form-control',
                        'name'          => 'new_password',
                        'id'            => 'new_password'
                    );
                    ?>

                    <?= form_password($data); ?>

                    <div id="new_password_error"></div>

                </div>
            </div>

            <!-- Confirm New Password -->

            <div class="col-lg-12 form-item-height">
                <div class="form-group">
                    <?= form_label('Confirm New Password: ', 'confirm_new_password'); ?>
                    <?php

                    $data = array(

                        'class'         => 'form-control',
                        'name'          => 'confirm_new_password',
                        'id'            => 'confirm_new_password'
                    );
                    ?>

                    <?= form_password($data); ?>

                    <div id="confirm_new_password_error"></div>

                </div>
            </div>


            <!-- Change Password Button -->

            <div class="col-lg-12">
                <div class="form-group text-right">
                    <input type="submit" value="Change Password" name="new_password" id="new_password" class="btn btn-block btn-success">
                </div>
            </div>

        </div>

        <?= form_close(); ?>
    </div>

</div>

</div> <!-- /.row