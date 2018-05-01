 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
            <!-- Form -->
            <?= form_open_multipart('admin/newspaper/edit_newspaper_lookup/' . $newspaper['id'], 'class=form id=edit_newspaper_form novalidate'); ?>
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
                                'value'         => ucwords(entity_decode($newspaper['name']))
                                
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
            <?php $image = (entity_decode($newspaper['thumbnail']) == '' ? 'no_image_600.png' : entity_decode($newspaper['thumbnail'])) ?>
            <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= NEWSPAPER_IMAGE . $image ?>" />
            <div id="loading">
                <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
            </div>
        </div>
        <br>

        <!-- Edit Button -->
        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Edit Newspaper" name="edit_newspaper" id="edit_newspaper" class="btn btn-block btn-success">
            </div>
        </div>
    <?= form_close(); ?>
    <!-- / Form -->

    </div> <!-- image_section -->
    </div> <!-- row -->
</div> <!-- page-wrapper -->
