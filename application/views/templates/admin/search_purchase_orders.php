<?php if(!empty($purchase_orders)): ?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped table">
            <col width="150">
            <col width="100">
            <col width="50">
            <col width="80">
            <col width="50">
            <col width="50">
            <col width="50">
            <col width="50">
            <thead>
            <tr>
                <th>Product Name</th>
                <th>Company</th>
                <th>Product Attribute</th>
                <th>Product Attribute Detail</th>
                <th>Quantity</th>
                <th>Purchase Price</th>
                <th>Sale Price</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($purchase_orders as $purchase_order):?>
                <tr>
                    <td><?= ucwords($purchase_order['product_name']); ?></td>
                    <td><?= ucwords($purchase_order['company_name']); ?></td>
                    <td><?= ucwords($purchase_order['product_attribute_name']); ?></td>
                    <?php if(strpos($purchase_order['product_attribute_value'], '#') !== FALSE): ?>
                        <td style="background: <?= $purchase_order['product_attribute_value'] ?>"></td>
                    <?php else: ?>
                        <td>
                            <?= ucwords($purchase_order['product_attribute_value']); ?>
                        </td>
                    <?php endif; ?>
                    <td><?= ucwords($purchase_order['quantity']); ?></td>
                    <td><?= ucwords($purchase_order['purchase_price']); ?></td>
                    <td><?= ucwords($purchase_order['sale_price']); ?></td>
                    <td>
                        <a title="Edit Product" href="<?= base_url('admin/purchase_order/edit_purchase_order_lookup') . '/' . ucwords($purchase_order['id']); ?>" class="btn btn-sm btn-success actions" style="display: inline-block;""><span class="fa fa-pencil-alt"></span></a>
                        <!--								<a title="Delete Product" href="javascript:void(0)" id="delete_--><?//= ucwords($purchase_order['id']); ?><!--" class="btn btn-sm btn-danger actions" style="display: inline-block;"><span class="fa fa-close"></span></a>-->
                        <a title="Product Details" href="javascript:void(0)" id="view_<?= ucwords($purchase_order['id']); ?>" class="btn btn-sm btn-info actions" style="display: inline-block;"><span class="fa fa-eye "></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?= $links ?>
	<?php else: ?>
		<h3 class="text-center">Sorry No Record Found!</h3>
	<?php endif; ?>