
<!-- Product Attribute -->
<div class="col-lg-6 form-item-height">
	<div class="form-group">
		<?php $attributes = array('class'=>'pull-left'); ?>
		<?= form_label('Product Attribute: ', 'product_attribute_' . $this->input->post('id_prepend'),$attributes ); ?>
		<?php

		$data = array(

				'class'         => 'form-control selectpicker',
				'id'            => 'product_attribute_' . $this->input->post('id_prepend'),
				'name'          => 'product_attribute_' . $this->input->post('id_prepend'),
				'title'         => 'Choose one or more...',
				'data-live-search'  => TRUE
		);

		$options = array();

		foreach ($product_attributes as $product_attribute)
		{
			$id = $product_attribute['id'];

			$name = ucwords(entity_decode($product_attribute['name']));

//			$options_arr = $this->input->post('options_arr');
/*
			if(!empty($options_arr)) {

				if(in_array($name, $options_arr)) {

					$options[$id] = $name;
				}
			}
			else {*/

				$options[$id] = $name;
//			}
		}

		$selected = $this->input->post('product_attribute_' . $this->input->post('id_prepend'));
		?>
		<?= form_dropdown('product_attribute_' . $this->input->post('id_prepend'), $options, $selected, $data); ?>
		<div id="product_attribute_<?= $this->input->post('id_prepend') ?>_error"></div>
	</div>
</div>

<!-- Product Attribute Details -->

<div class="col-lg-6 form-item-height">
	<div class="form-group">
		<?= form_label('Product Attribute Details: ', 'product_attr_details_' . $this->input->post('id_prepend'), $attributes); ?>
		<?php

		$data = array(

				'class'         => 'form-control selectpicker',
				'id'            => 'product_attr_details_' . $this->input->post('id_prepend'),
				'name'          => 'product_attr_details_' . $this->input->post('id_prepend'),
				'multiple'      => 'multiple',
				'title'         => 'Choose one or more...',
				'data-selected-text-format' => 'count',
				'data-live-search'  => TRUE
		);

		$options = array();

		$selected = $this->input->post('product_attr_details_' . $this->input->post('id_prepend'));
		?>
		<?= form_multiselect($this->input->post('product_attr_details_' . $this->input->post('id_prepend') ), $options, $selected, $data); ?>
		<div id="submitted_product_attr_details_<?= $this->input->post('id_prepend') . '_error'?>"></div>
	</div>
</div>
