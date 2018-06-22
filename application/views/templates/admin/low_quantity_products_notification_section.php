<?php ob_start(); ?>
<?php if(!empty($low_quantity_product_details)): ?>
    <input type="hidden" id="low_quantity_product_count" value="<?= $low_quantity_product_count ?>">
    <li>
        <div style="font-size: 12px;padding: 5px;" class="text-center">You Have <span class="bold"><?= $low_quantity_product_count ?></span> New Notifications</div>
        <div class="row">
            <div class="col-lg-12">
                <div class="divider"></div>
            </div>
        </div>
    </li>
	<?php foreach ($low_quantity_product_details as $low_quantity_product_detail): ?>
        <?php
            $selected_text = $this->input->post('selected_text');
            $function = $selected_text == 'size' ? 'strtoupper' : 'ucwords';
		?>
                <li>
                    <a class="notifications-link" href="<?= base_url('admin/inventory/edit_inventory_lookup/' . $low_quantity_product_detail['id']) ?>"><div class="col-lg-10"><i class="fa fa-inbox fa-fw"></i>
                            <?= ucwords($low_quantity_product_detail['product_name']) ?></div>
                        <div class="col-lg-2">
                            <span class="pull-right badge notification-bg"><?= $low_quantity_product_detail['quantity'] ?></span>
                        </div>

                        <div style="padding-right: 10px" class="col-lg-6">
                            <i class="fa fa-table"></i>
                            <?= ucwords($low_quantity_product_detail['product_attribute_name']); ?>
                        </div>
                        <div class="col-lg-6">
                            <?php if(strpos($low_quantity_product_detail['product_attribute_value'], '#') !== false): ?>
                                <div style="border-style: outset; padding: 5px 20px; background: <?= $low_quantity_product_detail['product_attribute_value'] ?>">
                                </div>
                            <?php else: ?>
                                <?= ucwords($low_quantity_product_detail['product_attribute_value']) ?>
                            <?php endif; ?>
                        </div>
                    </a>
                </li>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="divider"></div>
                    </div>
                </div>
    <?php endforeach ?>
    <li>
        <div class="text-center">
            <a href="<?= base_url('admin/inventory/low_quantity_products/'); ?>"><i class="fa fa-bell"></i>
                <strong>View All Notifications</strong>
            </a>
        </div>
    </li>

<div class="clearfix"></div>

<?php elseif(isset($minimum_products_notification)): ?>
    <div class="col-lg-1">
        <i class="text-danger fa fa-exclamation-triangle"></i>
    </div>
    <div class="col-lg-10">
        <?= $minimum_products_notification ?>
    </div>

<?php elseif(isset($show_notification)): ?>
    <div class="col-lg-1">
        <i class="text-danger fa fa-bell-slash"></i>
    </div>
    <div class="col-lg-10">
        <?= $show_notification ?>
    </div>

<?php elseif(empty($low_quantity_product_details)): ?>
    <div class="col-lg-1">
        <i class="text-danger fa fa-exclamation-triangle"></i>
    </div>
    <div class="col-lg-10">
        No Notifications!
    </div>
<?php endif; ?>
<?= ob_get_clean(); ?>