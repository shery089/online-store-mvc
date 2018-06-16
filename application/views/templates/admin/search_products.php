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
                    <td style="padding: 0;width: 7% !important;text-align: center;"><img src="<?= PRODUCT_IMAGE  .  ucwords($product['thumbnail']); ?>" alt="Product Image"></td>
                    <td><?= ucwords($product['name']); ?></td>
                    <td><?= ucwords($product['category']); ?></td>
                    <td>
                        <a title="Edit Product" href="<?= base_url('admin/product/edit_product_lookup') . '/' . ucwords($product['id']); ?>" class="btn btn-sm btn-success actions" style="display: inline-block;""><span class="fa fa-pencil-alt"></span></a>
                        <a title="Product Gallery" href="<?= base_url('admin/gallery/add_gallery_pics_lookup') . '/' . ucwords($product['id']); ?>" class="btn btn-sm btn-warning actions" style="display: inline-block;"><span class="fa fa-photo"></span></a>
                        <a title="Edit Product Description" href="<?= base_url('admin/product/edit_product_description_lookup') . '/' . ucwords($product['id']); ?>" class="btn btn-sm btn-primary actions" style="display: inline-block;"><span class="fa fa-pencil-square"></span></a>
                        <a title="Delete Product" href="javascript:void(0)" id="delete_<?= ucwords($product['id']); ?>" class="btn btn-sm btn-danger actions" style="display: inline-block;"><span class="fa fa-window-close"></span></a>
                        <a title="Product Description" href="javascript:void(0)" id="view_<?= ucwords($product['id']); ?>" class="btn btn-sm btn-info actions" style="display: inline-block;"><span class="fa fa-comment-o "></span></a>
                        <a title="Product Attributes" href="javascript:void(0)" id="details_<?= ucwords($product['id']); ?>" class="btn btn-sm btn-info actions" style="display: inline-block;"><span class="fa fa-eye"></span></a>
                    </td>
                </tr>
			<?php endforeach; ?>
			</tbody>
		</table>
        <?= $links ?>
    </div>
	<?php else: ?>
		<h3 class="text-center">Sorry No Record Found!</h3>
	<?php endif; ?>