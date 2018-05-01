 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/product/add_product_lookup', 'class=form id=add_product_form novalidate'); ?>
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

        <!-- Category -->

        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Category: ', 'category'); ?>
                <?php

                    $data = array(

                        'class'         => 'form-control selectpicker',
                        'id'            => 'category',
                        'name'          => 'category',
                        'title'         => 'Choose one or more...',
                        'data-selected-text-format' => 'count',
                        'data-live-search'  => TRUE

                    );

                    foreach ($categories as $category)
                    {
                        $id = $category['id'];
                        if(is_array($category['parent']) && $category['parent']['name'] != 'parent') {

                            $options[$id] = ucwords( $category['parent']['name'] . ' - ' .  entity_decode($category['name']));
                        }
                        else {
                            if($category['name'] == 'parent') {
                                continue;
                            }
                            $options[$id] = ucwords(entity_decode($category['name']));
                        }
                    }

                    $selected = $this->input->post('category');

                ?>
                <?= form_dropdown('category', $options, $selected, $data); ?>
                <div id="category_error"></div>
            </div>

        </div>

        <!-- Product Attribute -->
        <div class="col-lg-6 form-item-height">
            <div class="form-group">
                <?= form_label('Product Attribute: ', 'product_attribute_1'); ?>
                <?php

                    $data = array(

                        'class'         => 'form-control selectpicker',
                        'id'            => 'product_attribute_1',
                        'name'          => 'product_attribute_1',
                        'title'         => 'Choose one or more...',
                        'data-live-search'  => TRUE
                    );

                    $options = array();

                    foreach ($product_attributes as $product_attribute)
                    {
                        $id = $product_attribute['id'];
                        $options[$id] = ucwords(entity_decode($product_attribute['name']));
                    }

                    $selected = $this->input->post('product_attribute');
                ?>
                <?= form_dropdown('product_attribute_1', $options, $selected, $data); ?>
                <div id="product_attribute_1_error"></div>
            </div>
        </div>

        <!-- Product Attribute Details -->

        <div class="col-lg-6 form-item-height">
            <div class="form-group">
                <?= form_label('Product Attribute Details: ', 'product_attr_details_1'); ?>
                <?php

                    $data = array(

                        'class'         => 'form-control selectpicker',
                        'id'            => 'product_attr_details_1',
                        'name'          => 'product_attr_details_1',
                        'multiple'      => 'multiple',
                        'title'         => 'Choose one or more...',
                        'data-selected-text-format' => 'count',
                        'data-live-search'  => TRUE
                    );

                    $options = array();

                    $selected = $this->input->post('product_attr_details_1');
                ?>
                <?= form_dropdown('product_attr_details_1', $options, $selected, $data); ?>
                <div id="submitted_product_attr_details_1_error"></div>
            </div>

        </div>

        <div class="text-right" style="margin-right: 15px;">
            <div id="loader">
                <img style="margin: 0 auto;" class="img img-responsive" src="<?= ADMIN_IMAGES_PATH . 'loader.gif' ?>" alt="">
            </div>
        <button id="add_new_product_attribute_section" type="button" title="Add New Product Attribute" class="btn btn-info btn-circle"><i class="fa fa-plus"></i></button>
        <button id="delete_new_product_attribute_section" type="button" title="Remove Last Product Attribute" class="btn btn-danger btn-circle hide"><i class="fa fa-close"></i></button>
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
            <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= PRODUCT_IMAGE ?>no_image_600.png" />
            <div id="loading">
                <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
            </div>
        </div>
        <br>

        <!-- Add Button -->
        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Add Product" name="add_product" id="add_product" class="btn btn-block btn-success">
            </div>
        </div>

    <?= form_close(); ?>
    <!-- / Form -->

    </div> <!-- image_section -->
    </div> <!-- row -->
</div> <!-- page-wrapper -->