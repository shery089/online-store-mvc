 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
        <?php $_SESSION['KCFINDER']['uploadDir'] = '../some_directory/some_subdir'; ?>
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/columnist/add_columnist_lookup', 'class=form id=add_columnist_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
            
        <!-- Name -->

        <div>
            <div class="col-lg-12 form-item-height">
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
        </div>                

        <!-- Newspaper -->
        
        <div>            
            <div class="col-lg-6">
                <div class="form-group">
                    <?= form_label('Newspaper: ', 'newspaper'); ?>
                    <?php
                        
                        $data = array(
                            
                            'class'         => 'form-control selectpicker',
                            'id'            => 'newspaper',
                            'name'            => 'newspaper',
                            'multiple'      => 'multiple',
                            'title'         => 'Choose one or more...',
                            'data-selected-text-format' => 'count',
                            'data-live-search'  => TRUE

                        );
                            
                        foreach ($newspapers as $newspaper) 
                        {
                            $id = $newspaper['id'];
                            $options[$id] = ucwords(entity_decode($newspaper['name']));
                        }

                        $selected = $this->input->post('newspaper');

                        // $selected = explode(',', $this->input->post('submitted_designations'));

                    ?>
                    <?= form_multiselect('newspaper', $options, $selected, $data); ?>
                    <div id="newspaper_error"></div>
                </div>
            </div>
            
            <!-- City -->

            <div class="col-lg-6">
                <div class="form-group">
                    <?= form_label('City: ', 'city'); ?>
                    <?php
                        
                        $data = array(
                            
                            'class'         => 'form-control selectpicker',
                            'id'            => 'city',
                            'name'          => 'city',
                            'title'         => 'Choose one...',
                            'data-selected-text-format' => 'count',
                            'data-live-search'  => TRUE

                        );
                            
                        foreach ($cities as $city) 
                        {
                            $id = $city['id'];
                            $options[$id] = ucwords(entity_decode($city['name']));
                        }

                        $selected = $this->input->post('city');

                    ?>
                    <?= form_dropdown('city', $options, $selected, $data); ?>
                    <div id="city_error"></div>
                </div>
            </div>
        </div>
        
        <!-- Date of Birth -->

        <div class="col-lg-6">
            <div class="form-group">
                <?= form_label('Date of Birth: ', 'dob'); ?>
                <?php

                    $data = array(
                        'type'          => 'text',
                        'class'         => 'form-control',
                        'name'          => 'dob',
                        'id'            => 'dob',
                        'readonly'      => 'dob',
                        'value'         => set_value('dob')
                    );
                ?>

                <?= form_input($data); ?>
                <div id="dob_error"></div>
            </div>
        </div>  

        <div class="col-lg-6"></div>  
        
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
        
    </div>

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
        <!-- Image Preveiw -->
        <div id="image_preview">
            <div id="message" class="text-center"></div>
            <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= COLUMNIST_IMAGE ?>no_image_600.png" />
            <div id="loading">
                <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
            </div>
        </div>
        <br>

        <!-- Add Button -->
        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Add Columnist" name="add_columnist" id="add_columnist" class="btn btn-block btn-success">
            </div>
        </div>

    <?= form_close(); ?>
    <!-- / Form -->

    </div> <!-- image_section -->
    </div> <!-- row -->
</div> <!-- page-wrapper -->