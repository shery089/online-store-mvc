 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
        
        <!-- Form -->

        <?= form_open_multipart('admin/product_attribute/edit_product_attribute_lookup/'. $record['id'], 'class=form id=edit_product_attribute_form novalidate'); ?>
        <h1 class="page-header text-center"><?= $layout_title ?></h1>
            
        <!-- Name -->

        <div class="col-lg-12 form-item-height">
            <div class="form-group">
                <?= form_label('Name: ', 'name'); ?>
                <?php

                    $data = array(
                        
                        'class'         => 'form-control',
                        'name'          => 'name',
                        'id'            => 'name',
                        'value'         => ucwords($record['name'])
                        
                    );
                ?>

                <?= form_input($data); ?>
                
                <div id="name_error"></div>

            </div>
        </div>

        <!-- Add Button -->

        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Edit Category" name="edit_product_attribute" id="edit_product_attribute" class="btn btn-block btn-success">
            </div>
        </div>

        <?= form_close(); ?>

        <!-- / Form -->

        </div> 

        <!-- /col-lg-8 -->
    </div> <!-- row -->
</div> <!-- page-wrapper -->