 <div id="page-wrapper">
    <div class="row">

        <?= $tabs ?>

        <div class="col-lg-12">
                <!-- Form -->

            <?= form_open_multipart('admin/product/edit_product_description_lookup/' . $product['id'], 'class=form id=edit_product_desc_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>

        <!-- Short Description -->

        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Short Description: ', 'short_description'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control',
                    'name'          => 'short_description',
                    'rows'          => 5,
                    'id'            => 'short_description',
                    'value'         => stripcslashes(ucwords($product['short_description']))
                );
                ?>

                <?= form_textarea($data); ?>

                <div id="short_description_error"></div>

            </div>
        </div>

        <!-- Long Description -->

        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Long Description: ', 'long_description'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control',
                    'name'          => 'long_description',
                    'rows'          => 15,
                    'id'            => 'long_description',
                    'value'         => stripcslashes(ucwords($product['long_description']))
                );
                ?>

                <?= form_textarea($data); ?>

                <div id="long_description_error"></div>

            </div>
        </div>

            <!-- Edit Button -->
            <div class="col-lg-12">
                <div class="form-group text-right">
                    <input type="submit" value="Edit Product Description" name="edit_product_desc" id="edit_product_desc" class="btn btn-block btn-success">
                </div>
            </div>

            <?= form_close(); ?>
            <!-- / Form -->

    </div>
    </div> <!-- row -->
</div> <!-- page-wrapper -->