 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/' . $entity . '/edit_' . $entity . '_lookup/' . $purchase_order['id'], 'class=form id=edit_purchase_order_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>

        <!-- Product -->

        <div class="col-lg-6 form-item-height">
            <div class="form-group">
                <?= form_label('Product: ', 'product'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control',
                    'name'          => 'product',
                    'id'            => 'product',
                    'id'            => 'product',
                    'readonly'      => 'readonly',
                    'disabled'      => 'disabled',
                    'value'         => ucwords($purchase_order['product_name'])
                );
                ?>
                <?= form_input($data); ?>
            </div>
        </div>

        <!-- Product Attribute -->

        <div class="col-lg-6 form-item-height">
            <div class="form-group">
                <?= form_label('Product Attribute: ', 'product_attribute'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control',
                    'name'          => 'product_attribute',
                    'id'            => 'product_attribute',
                    'id'            => 'product_attribute',
                    'readonly'      => 'readonly',
                    'disabled'      => 'disabled',
                    'value'         => ucwords($purchase_order['product_attribute_name'])
                );
                ?>
                <?= form_input($data); ?>
            </div>
        </div>

        <!-- Product Attribute Details -->

        <div class="col-lg-6 form-item-height">
            <div class="form-group">
                <?= form_label('Product Attribute Details: ', 'product_attribute_value'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control',
                    'name'          => 'product_attribute_value',
                    'id'            => 'product_attribute_value',
                    'id'            => 'product_attribute_value',
                    'readonly'      => 'readonly',
                    'disabled'      => 'disabled',
                    'value'         => ucwords($purchase_order['product_attribute_value'])
                );
                ?>
                <?= form_input($data); ?>
            </div>
        </div>

        <!-- Product Quantity -->

        <div class="col-lg-6 form-item-height">
            <div class="form-group">
                <?= form_label('Quantity: ', 'quantity'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control text-right',
                    'name'          => 'quantity',
                    'type'          => 'number',
                    'id'            => 'quantity',
                    'min'           => '1',
                    'value'         => $purchase_order['quantity']

                );
                ?>
                <?= form_input($data); ?>

                <div id="quantity_error"></div>
            </div>
        </div>

        <!-- Purchase Price -->

        <div class="col-lg-6 form-item-height">
            <div class="form-group">
                <?= form_label('Purchase Price: ', 'purchase_price'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control text-right',
                    'name'          => 'purchase_price',
                    'type'          => 'number',
                    'id'            => 'purchase_price',
                    'value'         => $purchase_order['purchase_price'],
                    'min'           => '1',
                    'placeholder'   => 'Purchase Price'
                );
                ?>
                <?= form_input($data); ?>

                <div id="purchase_price_error"></div>
            </div>
        </div>


        <!-- Sale Price -->

        <div class="col-lg-6 form-item-height">
            <div class="form-group">
                <?= form_label('Sale Price: ', 'sale_price'); ?>
                <?php

                $data = array(

                    'class'         => 'form-control text-right',
                    'name'          => 'sale_price',
                    'type'          => 'number',
                    'id'            => 'sale_price',
                    'value'         => $purchase_order['sale_price'],
                    'min'           => '1',
                    'placeholder'   => 'Sale Price'
                );
                ?>
                <?= form_input($data); ?>

                <div id="sale_price_error"></div>
            </div>
        </div>

        <div class="text-right" style="margin-right: 15px;">
            <div id="loader">
                <img style="margin: 0 auto;" class="img img-responsive" src="<?= ADMIN_IMAGES_PATH . 'loader.gif' ?>" alt="">
            </div>
        </div>

        <!-- Edit Button -->
        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Edit Product" name="edit_purchase_order" id="edit_purchase_order" class="btn btn-success">
            </div>
        </div>

        <?= form_close(); ?>
        <!-- / Form -->
    </div>
    </div> <!-- row -->
</div> <!-- page-wrapper -->