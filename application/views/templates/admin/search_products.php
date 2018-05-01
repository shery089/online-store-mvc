<?php if(!empty($products)): ?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped table">
			<col width="20">
			<col width="220">
			<col width="30">
			<col width="100">
			<!--					<col width="70">-->
			<!--					<col width="100">-->
			<!-- <col width="250"> -->
			<thead>
			<tr>
				<th>Thumbnail</th>
				<th>Name</th>
				<th>Category</th>
				<th>Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($products as $product):?>
				<tr>
					<td style="padding: 0;width: 7% !important;text-align: center;"><img src="<?= PRODUCT_IMAGE  .  custom_echo($product, 'thumbnail', 'no_case_change'); ?>" alt=""></td>
					<td><?= custom_echo($product, 'name'); ?></td>
					<td><?= custom_echo($product, 'category'); ?></td>
					<td>
						<a title="Edit Product" href="<?= base_url('admin/product/edit_product_lookup/') . '/' . custom_echo($product, 'id'); ?>" class="btn btn-sm btn-success actions"><span class="glyphicon glyphicon-pencil"></span></a>
						<a title="Edit Product Description" href="<?= base_url('admin/product/edit_product_description_lookup/') . '/' . custom_echo($product, 'id'); ?>" class="btn btn-sm btn-primary actions"><span class="glyphicon glyphicon-pencil"></span></a>
						<a title="Delete Product" href="javascript:void(0)" id="delete_<?= custom_echo($product, 'id'); ?>" class="btn btn-sm btn-danger actions"><span class="glyphicon glyphicon-remove-sign"></span></a>
						<a title="Product Description" href="javascript:void(0)" id="view_<?= custom_echo($product, 'id'); ?>" class="btn btn-sm btn-info actions"><span class="fa fa-file-o"></span></a>
						<a title="Product Attributes" href="javascript:void(0)" id="details_<?= custom_echo($product, 'id'); ?>" class="btn btn-sm btn-info actions"><span class="fa fa-eye"></span></a>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<?php else: ?>
		<h3 class="text-center">Sorry No Record Found!</h3>
	<?php endif; ?>