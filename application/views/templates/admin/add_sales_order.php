 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/sales_order/add_sales_order_lookup', 'class=form id=add_sales_order_form novalidate'); ?>

            <div class="marg-top-10 alert alert-danger alert-dismissible hide">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong id="global_error_message"></strong>
            </div>

            <h1 class="page-header text-center"><?= $layout_title ?></h1>

        <div id="sales_record_1">

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

        <!-- Price -->

        <div class="col-lg-2 form-item-height">
            <div class="form-group">
                <?= form_label('Price: ', 'price_1'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control text-right',
                    'name'          => 'price_1',
                    'type'          => 'number',
                    'id'            => 'price_1',
                    'value'         => set_value('price_1'),
                    'min'           => '1',
                    'placeholder'   => 'Price'
                );
                ?>
                <?= form_input($data); ?>

                <div id="price_1_error"></div>
            </div>
        </div>

        <!-- Discount -->

        <div class="col-lg-2 form-item-height">
            <div class="form-group">
                <?= form_label('Discount in %: ', 'discount_1'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control text-right',
                    'name'          => 'discount_1',
                    'type'          => 'number',
                    'id'            => 'discount_1',
                    'min'           => '1',
                    'value'         => set_value('discount_1'),
                    'placeholder'   => 'Discount in %'
                );
                ?>
                <?= form_input($data); ?>

                <div id="discount_1_error"></div>
            </div>
        </div>
            
        <div class="col-lg-1">
            <button data-id="1" id="delete_new_sales_order_section_1" type="button" title="Remove Last Purchase Order" class="mar-top-27 btn btn-danger btn-circle hide"><i class="fa fa-window-close"></i></button>
        </div>

        </div> <!-- sales_record_1 -->
        <div class="text-right" style="margin-right: 15px;">
            <div id="loader">
                <img style="margin: 0 auto;" class="img img-responsive" src="<?= ADMIN_IMAGES_PATH . 'loader.gif' ?>" alt="">
            </div>
        </div>

        <!-- Add Button -->
        <div class="col-lg-12">
            <button id="add_new_sales_order_section" type="button" title="Add New Purchase Order" class="pull-right mar-rgt-25 mar-bot-10 btn btn-info btn-circle"><i class="fa fa-plus"></i></button>
            <div class="form-group text-left">
                <input type="submit" value="Create Invoice" name="add_sales_order" id="add_sales_order" class="btn btn-success">
            </div>
        </div>

        <?= form_close(); ?>
        <!-- / Form -->
    </div>
    </div> <!-- row -->
</div> <!-- page-wrapper -->