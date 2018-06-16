 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/purchase_order/add_purchase_order_lookup', 'class=form id=add_purchase_order_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>

        <div id="purchase_record_1">

        <!-- Products -->
        <div class="col-lg-2 form-item-height">
            <div class="form-group">
                <?= form_label('Product: ', 'product_1'); ?>
                <?php

                    $data = array(

                        'class'         => 'form-control selectpicker',
                        'id'            => 'product_1',
                        'name'          => 'product_1',
                        'title'         => 'Product',
                        'data-live-search'  => TRUE
                    );

                    $options = array();

                    foreach ($products as $product)
                    {
                        $id = $product['id'];
                        $options[$id] = ucwords(entity_decode($product['name']));
                    }

                    $selected = $this->input->post('product_1');
                ?>
                <?= form_dropdown('product_1', $options, $selected, $data); ?>
                <div id="product_1_error"></div>
            </div>
        </div>

        <!-- Product Attribute -->
        <div class="col-lg-2 form-item-height">
            <div class="form-group">
                <?= form_label('Product Attribute: ', 'product_attribute_1'); ?>
                <?php

                    $data = array(

                        'class'         => 'form-control selectpicker',
                        'id'            => 'product_attribute_1',
                        'name'          => 'product_attribute_1',
                        'title'         => 'Product Attribute',
                        'data-live-search'  => TRUE
                    );

                    $options = array();

                    $selected = $this->input->post('product_attribute');
                ?>
                <?= form_dropdown('product_attribute_1', $options, $selected, $data); ?>
                <div id="product_attribute_1_error"></div>
            </div>
        </div>

        <!-- Product Attribute Details -->

        <div class="col-lg-2 form-item-height">
            <div class="form-group">
                <?= form_label('Attribute Details: ', 'product_attr_details_1'); ?>
                <?php

                    $data = array(

                        'class'         => 'form-control selectpicker',
                        'id'            => 'product_attr_details_1',
                        'name'          => 'product_attr_details_1',
                        'title'         => 'Attribute Details'
                    );

                    $options = array();

                    $selected = $this->input->post('product_attr_details_1');
                ?>
                <?= form_dropdown('product_attr_details_1', $options, $selected, $data); ?>
                <div id="product_attr_details_1_error"></div>
            </div>
        </div>

        <!-- Product Quantity -->

        <div class="col-lg-1 form-item-height">
            <div class="form-group">
                <?= form_label('Quantity: ', 'quantity_1'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control text-right',
                    'name'          => 'quantity_1',
                    'type'          => 'number',
                    'id'            => 'quantity_1',
                    'min'           => '1',
                    'value'         => set_value('quantity_1')

                );
                ?>
                <?= form_input($data); ?>

                <div id="quantity_1_error"></div>
            </div>
        </div>

        <!-- Purchase Price -->

        <div class="col-lg-2 form-item-height">
            <div class="form-group">
                <?= form_label('Purchase Price: ', 'purchase_price_1'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control text-right',
                    'name'          => 'purchase_price_1',
                    'type'          => 'number',
                    'id'            => 'purchase_price_1',
                    'value'         => set_value('purchase_price_1'),
                    'min'           => '1',
                    'placeholder'   => 'Purchase Price'
                );
                ?>
                <?= form_input($data); ?>

                <div id="purchase_price_1_error"></div>
            </div>
        </div>


        <!-- Sale Price -->

        <div class="col-lg-2 form-item-height">
            <div class="form-group">
                <?= form_label('Sale Price: ', 'sale_price_1'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control text-right',
                    'name'          => 'sale_price_1',
                    'type'          => 'number',
                    'id'            => 'sale_price_1',
                    'value'         => set_value('sale_price_1'),
                    'min'           => '1',
                    'placeholder'   => 'Sale Price'
                );
                ?>
                <?= form_input($data); ?>

                <div id="sale_price_1_error"></div>
            </div>
        </div>

        <div class="col-lg-1">
            <button data-id="1" id="delete_new_purchase_order_section_1" type="button" title="Remove Last Purchase Order" class="mar-top-27 btn btn-danger btn-circle hide"><i class="fa fa-window-close"></i></button>
        </div>

        </div> <!-- purchase_record_1 -->
        <div class="text-right" style="margin-right: 15px;">
            <div id="loader">
                <img style="margin: 0 auto;" class="img img-responsive" src="<?= ADMIN_IMAGES_PATH . 'loader.gif' ?>" alt="">
            </div>
        </div>

        <!-- Add Button -->
        <div class="col-lg-12">
            <button id="add_new_purchase_order_section" type="button" title="Add New Purchase Order" class="pull-right mar-rgt-25 mar-bot-10 btn btn-info btn-circle"><i class="fa fa-plus"></i></button>
            <div class="form-group text-left">
                <input type="submit" value="Add Product" name="add_purchase_order" id="add_purchase_order" class="btn btn-success">
            </div>
        </div>

        <?= form_close(); ?>
        <!-- / Form -->
    </div>
    </div> <!-- row -->
</div> <!-- page-wrapper -->