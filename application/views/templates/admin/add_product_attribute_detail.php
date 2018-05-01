 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
            <!-- Form -->
            <?= form_open_multipart('admin/product_attribute_detail/add_product_attribute_detail_lookup', 'class=form id=add_product_attribute_detail_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
            <!-- Name -->
            <div class="col-lg-12">
                <div class="form-group">
                    <?= form_label('Product Attribute: ', 'product_attribute'); ?>
                    <?php

                    $data = array(

                        'class'         => 'form-control selectpicker',
                        'id'            => 'product_attribute',
                        'name'            => 'product_attribute',
                        'title'         => 'Choose Product Attribute',
                        'data-live-search'  => TRUE

                    );

                    if(!empty($product_attributes))
                    {
                        foreach ($product_attributes as $product_attribute)
                        {
                            $id = $product_attribute['id'];
                            $options[$id] = ucwords(entity_decode($product_attribute['name']));
                        }
                    }

                    $selected = $this->input->post('product_attribute');

                    ?>

                    <?= form_dropdown('product_attribute', $options, $selected, $data); ?>

                    <div id="product_attribute_error"></div>
                </div>
            </div>

            <!-- Product Attribute Detail -->

            <div class="col-lg-12">
                <div class="form-group">
                    <?= form_label('Product Attribute Detail: ', 'product_attribute_detail'); ?>
                    <?php

                    $data = array(

                        'class'         => 'form-control',
                        'name'          => 'product_attribute_detail',
                        'id'            => 'product_attribute_detail',
                    );
                    ?>

                    <?= form_input($data); ?>

                    <div id="product_attribute_detail_error"></div>

                </div>
            </div>

            <!-- Product Attribute Detail Color -->

            <div class="col-lg-12 hide">
                <div class="form-group">
                    <?= form_label('Product Attribute Detail Color: ', 'product_attribute_detail_color'); ?>
                    <?php

                    $data = array(

                        'class'         => 'pick-a-color form-control',
                        'name'          => 'product_attribute_detail_color',
                        'id'            => 'product_attribute_detail_color',
                        'readonly'      => 'readonly',
                        'value'         => ''
                    );
                    ?>

                    <?= form_input($data); ?>

                    <div id="product_attribute_detail_color_error"></div>

                </div>
            </div>

            <!-- Add Button -->
            <div class="col-lg-12">
                <div class="form-group text-right">
                    <input type="submit" value="Add Product Attribute Detail" name="add_product_attribute_detail" id="add_product_attribute_detail" class="btn btn-block btn-success">
                </div>
            </div>

            <?= form_close(); ?>
            <!-- / Form -->


        </div>
    </div> <!-- row -->
</div> <!-- page-wrapper -->