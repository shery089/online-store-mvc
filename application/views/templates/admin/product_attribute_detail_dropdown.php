<?php ob_start(); ?>
<?php $product_attribute_detail_db_values = !empty($this->input->post('product_attribute_detail_values')) ? explode(', ', $this->input->post('product_attribute_detail_values')) : '' ; ?>
<?php if(!empty($product_attribute_details)): ?>
	<?php foreach ($product_attribute_details as $product_attribute_detail): ?>
			<?php
				$selected_text = $this->input->post('selected_text');
				$function = $selected_text == 'size' ? 'strtoupper' : 'ucwords';
		?>
		<option <?= is_array($product_attribute_detail_db_values) && in_array($product_attribute_detail['id'], $product_attribute_detail_db_values) ? 'selected' : ''; ?> <?= $selected_text == 'color' ? 'style="background: ' . $product_attribute_detail['name'] . '"' : '' ?> value="<?= $product_attribute_detail['id'] ?>"><?= $selected_text == 'color' ? '' : $function($product_attribute_detail['name']) ?></option>
	<?php endforeach ?>
<?php endif; ?>
<?= ob_get_clean(); ?>