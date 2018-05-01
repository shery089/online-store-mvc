 <div id="page-wrapper">
    <div class="row">
        <?= $tabs; ?>
        <div class="col-lg-12">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>   
            <?= form_open_multipart('admin/gallery/add_gallery_pics_lookup', 'class="form dropzone" id="add_gallery_form" novalidate'); ?>
            <div class="dz-default dz-message"><span>Drop or select one or more images</span></div>
        </div>

    <!-- Image Preveiw -->

    </div> <!-- /.row -->
    <?= form_close(); ?>
            
</div>  <!-- page wrapper -->