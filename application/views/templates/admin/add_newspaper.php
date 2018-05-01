 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
        <?php $_SESSION['KCFINDER']['uploadDir'] = '../some_directory/some_subdir'; ?>
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/newspaper/add_newspaper_lookup', 'class=form id=add_newspaper_form novalidate'); ?>
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
            <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= NEWSPAPER_IMAGE ?>no_image_600_thumb.png" />
            <div id="loading">
                <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
            </div>
        </div>
        <br>

        <!-- Add Button -->
        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Add Political Party" name="add_newspaper" id="add_newspaper" class="btn btn-block btn-success">
            </div>
        </div>

    <?= form_close(); ?>
    <!-- / Form -->

    </div> <!-- image_section -->
    </div> <!-- row -->
</div> <!-- page-wrapper -->