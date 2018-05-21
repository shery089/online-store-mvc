 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">

            <!-- Form -->
            <?= form_open_multipart('admin/gallery/edit_gallery_pics_lookup/'. $gallery['product_id'] . '/' . $gallery['id'], 'class=form id=edit_gallery_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
            
            <!-- Title -->

            <div class="col-lg-12 form-item-height">
                <div class="form-group">
                    <?= form_label('Title: ', 'title'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'title',
                            'id'            => 'title',
                            'value'         => ucwords(entity_decode($gallery['title']))
                            
                        );
                    ?>
                    <?= form_input($data); ?>
                    
                    <div id="title_error"></div>

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
                <div id="image_error"></div>
            </div>
        </div>
            <?php if (empty(form_error('image'))): ?>
                
                <div id="image_preview">
                    <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= GALLERY_IMAGE . $gallery['image'] ?>" />
                    <div id="loading">
                        <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
                    </div>
                    <div id="message" class="text-center"></div>
                </div>
                <br>
                        <!-- Edit Button -->
            <?php endif ?>
            
            <div>
                <div class="col-lg-12">
                    <div class="form-group text-right">
                        <input type="submit" value="Edit Picture" name="edit_picture" id="edit_picture" class="btn btn-block btn-success">
                    </div>
                </div>
            </div>

        </div>
        <?= form_close(); ?>
        <!-- / Form -->
    </div>
            
</div> <!-- /.row