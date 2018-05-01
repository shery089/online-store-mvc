  <!--center-->
  <div class="col-sm-6 col-md-6 col-lg-6 dark-border" id="edit-user">
      <?php $first_name = ucwords(entity_decode($user['first_name'])); ?>
      <h2 class="text-center"><?= $first_name ?>'s Account</h2>
      <!-- Form -->
      <?= form_open_multipart('front_end/user/edit_user_lookup/'. $user['id'], 'class=form id=edit_user_form novalidate'); ?>
      <!-- First Name -->

        <!-- Image Preveiw -->

        <div class="col-lg-12 image_section">
            <div id="image_preview">
                <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= USER_IMAGE_PATH . $user['picture'] ?>" />
                <div id="loading">
                    <!-- <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" /> -->
                </div>
                <div id="message" class="text-center"></div>
            </div>
        </div>
        <div class="col-lg-4 form-item-height">
            <div class="form-group">
                <?= form_label('First Name: ', 'first_name'); ?>
                <?php

                    $data = array(
                        
                        'class'         => 'form-control',
                        'name'          => 'first_name',
                        'id'            => 'first_name',
                        'value'         => $first_name,
                        'autocomplete'  => 'off'
                        
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
                        'value'         => ucwords(entity_decode($user['middle_name'])),
                        'autocomplete'  => 'off'
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
                        'value'         => ucwords(entity_decode($user['last_name'])),
                        'autocomplete'  => 'off'
                    );
                ?>                        
                <?= form_input($data); ?>

                <div id="last_name_error"></div>

            </div>
        </div>      
          
        <!-- User Name -->
    
        <div class="col-lg-4 form-item-height">
            <div class="form-group">
                <?= form_label('User Name: ', 'user_name'); ?>
                <?php

                    $data = array(
                        
                        'class'         => 'form-control',
                        'name'          => 'user_name',
                        'id'            => 'user_name',
                        'value'         => ucwords(entity_decode($user['user_name'])),
                        'autocomplete'  => 'off'
                    );
                ?>

                <?= form_input($data); ?>

                <div id="user_name_error"></div>

            </div>
        </div>              
      
        <!-- Email -->
    
        <div class="col-lg-8 form-item-height">
            <div class="form-group">
                <?= form_label('Email: ', 'email'); ?>
                <?php

                    $data = array(
                        
                        'class'         => 'form-control',
                        'type'          => 'email',
                        'name'          => 'email',
                        'id'            => 'email',
                        'value'         => ucwords(entity_decode($user['email'])),
                        'autocomplete'  => 'off'
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
                        'value'         => ucwords(entity_decode($user['mobile_number'])),
                        'autocomplete'  => 'off'
                    );
                ?>

                <?= form_input($data); ?>

                <div id="mobile_number_error"></div>
                
            </div>
        </div>

      <!-- Image -->

      <div class="col-lg-8">
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

      <!-- Edit Button -->
      
      <div>
          <div class="col-lg-12">
              <div class="form-group text-right">
                  <input type="submit" value="Save Changes" name="edit_user" id="edit_user" class="btn btn-success">
              </div>
          </div>
      </div>

  <?= form_close(); ?>
  <!-- / Form -->
  </div>