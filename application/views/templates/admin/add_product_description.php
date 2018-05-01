 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/product/add_product_description_lookup', 'class=form id=add_product_description_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
            
        <!-- Product Name -->

        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Product Name: ', 'product_name'); ?>
                <?php

                    $data = array(

                        'class'         => 'form-control',
                        'name'          => 'product_name',
                        'id'            => 'product_name',
                        'value'         => set_value('product_name')
                    );
                ?>
                <?= form_input($data); ?>

                <div id="product_name_error"></div>

            </div>
        </div>

        <!-- Product Short Description -->

        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Product Short Description: ', 'short_desc'); ?>
                <?php

                    $data = array(

                        'class'         => 'form-control',
                        'name'          => 'short_desc',
                        'id'            => 'short_desc',
                        'value'         => set_value('short_desc'),
                        'rows'          => '4'

                    );
                ?>
                <?= form_textarea($data); ?>

                <div id="short_desc_error"></div>

            </div>
        </div>

        <!-- Product Long Description -->

        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Product Long Description: ', 'long_desc'); ?>
                <?php

                    $data = array(

                        'class'         => 'form-control',
                        'name'          => 'long_desc',
                        'id'            => 'long_desc',
                        'value'         => set_value('long_desc')

                    );
                ?>
                <?= form_textarea($data); ?>

                <div id="long_desc_error"></div>

            </div>
        </div>


        <!-- Add Button -->
        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Add Product Description" name="add_product_desc" id="add_product_desc" class="btn btn-block btn-success">
            </div>
        </div>

        <?= form_close(); ?>
        <!-- / Form -->

    </div>

    </div> <!-- row -->
</div> <!-- page-wrapper -->