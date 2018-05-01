 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/political_party/add_political_party_lookup', 'class=form id=add_political_party_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
            
        <!-- Name -->

        <div>
            <div class="col-lg-6 form-item-height">
                <div class="form-group">
                    <?= form_label('Name: ', 'name'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'name',
                            'id'            => 'name',
                            'value'         => set_value('name')
                            
                        );
                    ?>
                    <?= form_input($data); ?>
                    
                    <div id="name_error"></div>

                </div>
            </div>              

            <!-- Founded Date -->
            
            <div class="col-lg-6 form-item-height">
                <div class="form-group">
                    <?= form_label('Founded Date: ', 'founded_date'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'founded_date',
                            'id'            => 'founded_date',
                            'value'         => set_value('founded_date')
                            
                        );
                    
                        $starting_year  = date('Y', strtotime('-100 year'));
                        $ending_year = date('Y', strtotime('+0 year'));
                        $current_year = date('Y');
                        for($starting_year; $starting_year <= $ending_year; $starting_year++)
                        {
                            $options[$starting_year] = $starting_year;
                        } 

                        $selected = $this->input->post('founded_date');
                    ?>
                    <?= form_dropdown('founded_date', $options, $selected, $data); ?>


                    <div id="founded_date_error"></div>

                </div>
            </div>
   
            <!-- Leader -->
        
            <div class="col-lg-6 form-item-height">
                <div class="form-group">
                    <?= form_label('Leader: ', 'leader'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'leader',
                            'id'            => 'leader',
                            'value'         => set_value('leader')
                        );
                    ?>
                        
                    <?= form_input($data); ?>

                    <div id="leader_error"></div>
    
                </div>
            </div>      

            <!-- Designation -->
        
            <div class="col-lg-6 form-item-height">
                <div class="form-group">
                    <?= form_label('Designation: ', 'Designation'); ?>
                    <?php
                        
                        $data = array(
                            
                            'class'         => 'form-control selectpicker',
                            'id'            => 'designation',
                            'name'          => 'designation',
                            'title'         => 'Choose one ...'
                        );

                        $options = array();

                        foreach ($designations as $designation) 
                        {
                            $id = $designation['id'];
                            $options[$id] = ucwords(entity_decode($designation['name']));
                        }

                        $selected = $this->input->post('designation');
                    ?>
                    <?= form_dropdown('designation', $options, $selected, $data); ?>
                    <div id="designation_error"></div>
                </div>
            </div>      
        </div> 
        
        <!-- Address -->
    
        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Address: ', 'address'); ?>
                <?php

                    $data = array(
                        
                        'class'         => 'form-control',
                        'name'          => 'address',
                        'id'            => 'address',
                        'value'         => set_value('address')
                    );
                ?>

                <?= form_textarea($data); ?>

                <div id="address_error"></div>

            </div>
        </div>             

        
        <!-- Introduction -->
    
        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Introduction: ', 'introduction'); ?>
                <?php

                    $data = array(
                        
                        'class'         => 'form-control',
                        'name'          => 'introduction',
                        'id'            => 'introduction',
                        'value'         => set_value('introduction')
                    );
                ?>

                <?= form_textarea($data); ?>

                <div id="introduction_error"></div>

            </div>
        </div>

        <!-- Election History -->
    
        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Election History: ', 'election_history'); ?>
                <?php

                    $data = array(
                        
                        'class'         => 'form-control',
                        'name'          => 'election_history',
                        'id'            => 'election_history',
                        'value'         => set_value('election_history')
                    );
                ?>

                <?= form_textarea($data); ?>

                <div id="election_history_error"></div>

            </div>
        </div> 
        
    </div>

    <div class="col-lg-4 image_section">

    <!-- Image -->

    <div class="col-lg-12">
        <div class="form-group">
            <?= form_label('Flag: ', 'flag'); ?>
            <?php

                $data = array(
                    
                    'class'         => 'form-control',
                    'name'          => 'flag',
                    'id'            => 'flag',
                );
            ?>

            <?= form_upload($data); ?>
        </div>
    </div>
        <!-- Image Preveiw -->
        <div id="image_preview">
            <div id="message" class="text-center"></div>
            <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= PARTY_IMAGE ?>no_image_600.png" />
            <div id="loading">
                <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
            </div>
        </div>
        <br>

        <!-- Add Button -->
        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Add Political Party" name="add_political_party" id="add_political_party" class="btn btn-block btn-success">
            </div>
        </div>

    <?= form_close(); ?>
    <!-- / Form -->

    </div> <!-- image_section -->
    </div> <!-- row -->
</div> <!-- page-wrapper -->