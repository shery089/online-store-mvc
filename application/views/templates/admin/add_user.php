 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/user/add_user_lookup', 'class=form id=add_user_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>

        <!-- First Name -->

        <div>
            <div class="col-lg-4 form-item-height">
                <div class="form-group">
                    <?= form_label('First Name: ', 'first_name'); ?>
                    <?php

                        $data = array(

                            'class'         => 'form-control',
                            'name'          => 'first_name',
                            'id'            => 'first_name',
                            'value'         => set_value('first_name')

                        );
                    ?>
                    <?= form_input($data); ?>

                    <div id="first_name_error"></div>

                </div>
            </div>

            <!-- Middle Name -->

            <div class="col-lg-4 form-item-height">
                <div class="form-group">
                    <?= form_label('Middle Name: ', 'middle_name'); ?>
                    <?php

                        $data = array(

                            'class'         => 'form-control',
                            'name'          => 'middle_name',
                            'id'            => 'middle_name',
                            'value'         => set_value('middle_name')
                        );
                    ?>

                    <?= form_input($data); ?>

                    <div id="middle_name_error"></div>

                </div>
            </div>

            <!-- Last Name -->

            <div class="col-lg-4 form-item-height">
                <div class="form-group">
                    <?= form_label('Last Name: ', 'last_name'); ?>
                    <?php

                        $data = array(

                            'class'         => 'form-control',
                            'name'          => 'last_name',
                            'id'            => 'last_name',
                            'value'         => set_value('last_name')
                        );
                    ?>
                    <?= form_input($data); ?>

                    <div id="last_name_error"></div>

                </div>
            </div>
        </div>

        <div>

            <!-- User Name -->

            <div class="col-lg-4 form-item-height">
                <div class="form-group">
                    <?= form_label('User Name: ', 'user_name'); ?>
                    <?php

                        $data = array(

                            'class'         => 'form-control',
                            'name'          => 'user_name',
                            'id'            => 'user_name',
                            'value'         => set_value('user_name')
                        );
                    ?>

                    <?= form_input($data); ?>

                    <div id="user_name_error"></div>

                </div>
            </div>

            <!-- Password -->
        
            <div class="col-lg-4 form-item-height">
                <div class="form-group">
                    <?= form_label('Password: ', 'password'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'password',
                            'id'            => 'password'
                        );
                    ?>

                    <?= form_password($data); ?>

                    <div id="password_error"></div>

                </div>
            </div> 
            
            <!-- Confirm Password -->
        
            <div class="col-lg-4 form-item-height">
                <div class="form-group">
                    <?= form_label('Confirm Password: ', 'confirm_password'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'confirm_password',
                            'id'            => 'confirm_password'
                        );
                    ?>

                    <?= form_password($data); ?>

                    <div id="confirm_password_error"></div>

                </div>
            </div> 

        </div>

        <div>

            <!-- Email -->

            <div class="col-lg-5 form-item-height">
                <div class="form-group">
                    <?= form_label('Email: ', 'email'); ?>
                    <?php

                        $data = array(

                            'class'         => 'form-control',
                            'type'          => 'email',
                            'name'          => 'email',
                            'id'            => 'email',
                            'value'         => set_value('email')
                        );
                    ?>

                    <?= form_input($data); ?>

                    <div id="email_error"></div>

                </div>
            </div>

            <!-- Mobile Number -->

            <div class="col-lg-4 form-item-height">
                <div class="form-group">
                    <?= form_label('Mobile Number: ', 'mobile_number'); ?>
                    <?php

                        $data = array(

                            'class'         => 'form-control',
                            'name'          => 'mobile_number',
                            'id'            => 'mobile_number',
                            'value'         => set_value('mobile_number')
                        );
                    ?>

                    <?= form_input($data); ?>

                    <div id="mobile_number_error"></div>

                </div>
            </div>

            <!-- Role -->

            <div class="col-lg-3 form-item-height">
                <div class="form-group">
                    <?= form_label('Role: ', 'role'); ?>
                    <?php

                        $data = array(

                            'class'         => 'form-control selectpicker',
                            'id'            => 'role',
                            'title'         => 'Choose a role',
                            'data-actions-box' => 'true'
                        );

                        foreach ($roles as $role)
                        {
                            $id = $role['id'];
                            $options[$id] = ucwords(entity_decode($role['name']));
                        }

                        $selected = $this->input->post('role');

                    ?>
                    <?= form_dropdown('role', $options, $selected, $data); ?>

                    <div id="role_error"></div>
                </div>
            </div>

        </div>

    </div>

    <!-- Image Preveiw -->

    <div class="col-lg-4 image_section">

    <!-- Image -->

    <div class="col-lg-12">
        <div class="form-group">
            <?= form_label('Image: ', 'image'); ?>
            <?php

                $data = array(

                    'class'         => 'form-control',
                    'name'          => 'image',
                    'id'            => 'image',
                );
            ?>

            <?= form_upload($data); ?>
        </div>
    </div>

    <!-- / Form -->
        <div id="image_preview">
            <div id="message" class="text-center"></div>
            <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= USER_IMAGE_PATH ?>no_image_600.png" />
            <div id="loading">
                <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
            </div>
        </div>
        <br>

        <!-- Add Button -->

        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Add User" name="add_user" id="add_user" class="btn btn-block btn-success">
            </div>
          </div>
    </div>
    <?= form_close(); ?>

    </div>
            
</div> <!-- /.row