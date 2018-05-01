
 <div id="page-wrapper">
    <div class="row">

        <div class="col-lg-8">
            <!-- Form -->
            <?php foreach ($record as $user): ?>
            <?= form_open_multipart('admin/user/edit_user_lookup/'. custom_echo($user, 'id'), 'class=form id=edit_user_form novalidate'); ?>
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
                            'value'         => custom_echo($user, 'first_name')
                            
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
                            'value'         => custom_echo($user, 'middle_name')
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
                            'value'         => custom_echo($user, 'last_name')
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
                            'value'         => custom_echo($user, 'user_name')
                        );
                    ?>

                    <?= form_input($data); ?>

                    <div id="user_name_error"></div>

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
                            'value'         => custom_echo($user, 'email')
                        );
                    ?>

                    <?= form_input($data); ?>

                    <div id="email_error"></div>

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

                        $selected = custom_echo($user, 'role_id')

                    ?>
                    <?= form_dropdown('role', $options, $selected, $data); ?>

                    <div id="role_error"></div>
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
                            'value'         => custom_echo($user, 'mobile_number')
                        );
                    ?>

                    <?= form_input($data); ?>

                    <div id="mobile_number_error"></div>
                    
                </div>
            </div>
        </div>

        <!-- Edit Button -->
        
        <div>
            <div class="col-lg-12">
                <div class="form-group text-right">
                    <input type="submit" value="Edit User" name="edit_user" id="edit_user" class="btn btn-success">
                </div>
            </div>
        </div>

        <!-- </div> -->
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
    <?= form_close(); ?>
    <!-- / Form -->
        <div id="image_preview">
            <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= USER_IMAGE_PATH . custom_echo($user, 'image', 'no_case_change') ?>" />
            <div id="loading">
                <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
            </div>
            <div id="message" class="text-center"></div>
        </div>
        <br>
    </div>

    </div>
    <?php endforeach ?>
            
</div> <!-- /.row