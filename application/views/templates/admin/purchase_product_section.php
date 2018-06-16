<div style="padding-top: 95px;" id="purchase_record_<?= $this->input->post('id_prepend') ?>">
<!-- Products -->
<div class="col-lg-2 form-item-height">
    <div class="form-group">
        <?= form_label('Product: ', 'product_' . $this->input->post('id_prepend')); ?>
        <?php

        $data = array(

            'class'         => 'form-control selectpicker',
            'id'            => 'product_' . $this->input->post('id_prepend'),
            'name'          => 'product_' . $this->input->post('id_prepend'),
            'title'         => 'Product',
            'data-live-search'  => TRUE
        );

        $options = array();

        foreach ($products as $product)
        {
            $id = $product['id'];
            $options[$id] = ucwords(entity_decode($product['name']));
        }

        $selected = $this->input->post('product_' . $this->input->post('id_prepend'));
        ?>
        <?= form_dropdown('product_' . $this->input->post('id_prepend'), $options, $selected, $data); ?>
        <div id="product_<?= $this->input->post('id_prepend') ?>_error"></div>
    </div>
</div>

<!-- Product Attribute -->
<div class="col-lg-2 form-item-height">
	<div class="form-group">
		<?php $attributes = array('class'=>'pull-left'); ?>
		<?= form_label('Product Attribute: ', 'product_attribute_' . $this->input->post('id_prepend'),$attributes ); ?>
		<?php

		$data = array(

				'class'         => 'form-control selectpicker',
				'id'            => 'product_attribute_' . $this->input->post('id_prepend'),
				'name'          => 'product_attribute_' . $this->input->post('id_prepend'),
				'title'         => 'Product Attribute',
				'data-live-search'  => TRUE
		);

		$options = array();

		$selected = $this->input->post('product_attribute_' . $this->input->post('id_prepend'));
		?>
		<?= form_dropdown('product_attribute_' . $this->input->post('id_prepend'), $options, $selected, $data); ?>
		<div id="product_attribute_<?= $this->input->post('id_prepend') ?>_error"></div>
	</div>
</div>

<!-- Attribute Details -->

<div class="col-lg-2 form-item-height">
	<div class="form-group">
		<?= form_label('Attribute Details: ', 'product_attr_details_' . $this->input->post('id_prepend'), $attributes); ?>
		<?php

		$data = array(

				'class'         => 'form-control selectpicker',
				'id'            => 'product_attr_details_' . $this->input->post('id_prepend'),
				'name'          => 'product_attr_details_' . $this->input->post('id_prepend'),
				'title'         => 'Attribute Details',
		);

		$options = array();

		$selected = $this->input->post('product_attr_details_' . $this->input->post('id_prepend'));
		?>
		<?= form_dropdown($this->input->post('product_attr_details_' . $this->input->post('id_prepend') ), $options, $selected, $data); ?>
		<div id="product_attr_details_<?= $this->input->post('id_prepend') . '_error'?>"></div>
	</div>
</div>


<!-- Product Quantity -->

<div class="col-lg-1 form-item-height">
    <div class="form-group">
        <?= form_label('Quantity: ', 'quantity_' . $this->input->post('id_prepend')); ?>
        <?php

        $data = array(

            'class'         => 'form-control text-right',
            'name'          => 'quantity_' . $this->input->post('id_prepend'),
            'type'          => 'number',
            'id'            => 'quantity_' . $this->input->post('id_prepend'),
            'value'         => set_value('quantity_' . $this->input->post('id_prepend'))
        );
        ?>
        <?= form_input($data); ?>

        <div id="quantity_<?= $this->input->post('id_prepend') ?>_error"></div>
    </div>
</div>

<!-- Purchase Price -->

<div class="col-lg-2 form-item-height">
    <div class="form-group">
        <?= form_label('Purchase Price: ', 'purchase_price_' . $this->input->post('id_prepend')); ?>
        <?php

        $data = array(

            'class'         => 'form-control text-right',
            'name'          => 'purchase_price_' . $this->input->post('id_prepend'),
            'type'          => 'number',
            'id'            => 'purchase_price_' . $this->input->post('id_prepend'),
            'value'         => set_value('purchase_price_' . $this->input->post('id_prepend')),
            'placeholder'   => 'Purchase Price'
        );
        ?>
        <?= form_input($data); ?>

        <div id="purchase_price_<?= $this->input->post('id_prepend') ?>_error"></div>
    </div>
</div>


<!-- Sale Price -->

<div class="col-lg-2 form-item-height">
    <div class="form-group">
        <?= form_label('Sale Price: ', 'sale_price_' . $this->input->post('id_prepend')); ?>
        <?php

        $data = array(

            'class'         => 'form-control text-right',
            'name'          => 'sale_price_' . $this->input->post('id_prepend'),
            'type'          => 'number',
            'id'            => 'sale_price_' . $this->input->post('id_prepend'),
            'value'         => set_value('sale_price_' . $this->input->post('id_prepend')),
            'placeholder'   => 'Sale Price'
        );
        ?>
        <?= form_input($data); ?>

        <div id="sale_price_<?= $this->input->post('id_prepend') ?>_error"></div>
    </div>
</div>

<div class="col-lg-1">
    <button data-id="<?= $this->input->post('id_prepend'); ?>" id="delete_new_purchase_order_section_<?= $this->input->post('id_prepend') ?>" type="button" title="Remove Last Purchase Order" class="btn btn-danger btn-circle mar-top-27 hide"><i class="fa fa-window-close"></i></button>
</div>

</div><!-- purchase_record_$this->input->post('id_prepend') -->