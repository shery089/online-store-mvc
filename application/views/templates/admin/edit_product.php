 <div id="page-wrapper">
    <div class="row">

        <?php foreach ($record as $product): ?>

        <?= $tabs ?>

        <div class="col-lg-8">
            <!-- Form -->
            <?= form_open_multipart('admin/product/edit_product_lookup/' . custom_echo($product, 'id'), 'class=form id=edit_product_form novalidate'); ?>
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
                            'value'         => custom_echo($product, 'name')

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

                    $selected = custom_echo($product, 'category_id');

                ?>
                <?= form_dropdown('category', $options, $selected, $data); ?>
                <div id="category_error"></div>
            </div>

        </div>

        <?php $selected_product_attributes = custom_echo($product, 'product_attribute_detail_id', 'no_case_change'); ?>

        <?php $count = 1; ?>

        <?php foreach($selected_product_attributes as $selected_product_attribute): ?>

        <!-- Product Attribute -->
        <div class="col-lg-6 form-item-height">
            <div class="form-group">
                <?= form_label('Product Attribute: ', 'product_attribute_' . $count); ?>
                <?php

                    $data = array(

                        'class'         => 'form-control selectpicker',
                        'id'            => 'product_attribute_' . $count,
                        'name'          => 'product_attribute_' . $count,
                        'title'         => 'Choose one or more...',
                        'data-live-search'  => TRUE
                    );

                    $options = array();

                    foreach ($product_attributes as $product_attribute)
                    {
                        $id = $product_attribute['id'];
                        $options[$id] = ucwords(entity_decode($product_attribute['name']));

                        if($selected_product_attribute == $id) {
                            $selected = $id;
                        }
                    }

                ?>
                <?= form_dropdown('product_attribute_' . $count, $options, $selected, $data); ?>
                <div id="product_attribute_<?= $count . '_error' ?>"></div>
            </div>
        </div>

        <input type="hidden" id="product_attribute_detail_value" value="<?= implode(', ', custom_echo($product, 'product_attribute_detail_value', 'no_case_change')); ?>">

        <!-- Product Attribute Details -->

        <div class="col-lg-6 form-item-height">
            <div class="form-group">
                <?= form_label('Product Attribute Details: ', 'product_attr_details_' . $count); ?>
                <?php

                    $data = array(

                        'class'         => 'form-control selectpicker',
                        'id'            => 'product_attr_details_' . $count,
                        'name'          => 'product_attr_details_' . $count,
                        'multiple'      => 'multiple',
                        'title'         => 'Choose one or more...',
                        'data-selected-text-format' => 'count',
                        'data-live-search'  => TRUE
                    );

                    $options = array();

                    $selected = $this->input->post('product_attr_details_' . $count);
                ?>
                <?= form_dropdown('product_attr_details_' . $count, $options, $selected, $data); ?>
                <div id="submitted_product_attr_details_<?= $count . '_error' ?>"></div>
            </div>

        </div>

        <?php
            $count++;
            endforeach;
        ?>

        <div class="text-right" style="margin-right: 15px;">
            <div id="loader">
                <img style="margin: 0 auto;" class="img img-responsive" src="<?= ADMIN_IMAGES_PATH . 'loader.gif' ?>" alt="">
            </div>
        <button id="add_new_product_attribute_section" type="button" title="Add New Product Attribute" class="btn btn-info btn-circle"><i class="fa fa-plus"></i></button>
        <button id="delete_new_product_attribute_section" type="button" title="Remove Last Product Attribute" class="btn btn-danger btn-circle <?= count($selected_product_attributes) > 1 ? '' : 'hide' ?>"><i class="fa fa-close"></i></button>
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
            <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= PRODUCT_IMAGE . custom_echo($product, 'image', 'no_case_change'); ?>" />
            <div id="loading">
                <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
            </div>
        </div>
        <br>

        <!-- Edit Button -->
        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Edit Product" name="edit_product" id="edit_product" class="btn btn-block btn-success">
            </div>
        </div>

    <?= form_close(); ?>
    <!-- / Form -->

    </div> <!-- image_section -->
    </div> <!-- row -->
     <?php endforeach ?>
</div> <!-- page-wrapper -->