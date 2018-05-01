<?php ob_start(); ?>
    <?php if(!empty($record)): ?>
        <?php foreach ($record as $product): ?>
            <ul class="nav nav-pills marg-top-10">
                <li><a href="<?= base_url('admin/product/edit_product_lookup/' . custom_echo($product, 'id')) ?>">Product Details</a></li>
                <li><a href="<?= base_url('admin/product/edit_product_description_lookup/' . custom_echo($product, 'id')) ?>">Product Description</a></li>
                <li><a href="<?= base_url('admin/gallery/add_gallery_pics_lookup/' . custom_echo($product, 'id')) ?>">Add New Product Images</a></li>
                <li><a href="<?= base_url('admin/gallery/edit_gallery_pics_lookup/' . custom_echo($product, 'id')) ?>">Edit Product Gallery Images</a></li>
            </ul>
        <?php endforeach ?>
    <?php else: ?>
        <ul class="nav nav-pills marg-top-10">
            <li><a href="<?= base_url('admin/product/edit_product_lookup/' . $product['id']) ?>">Product Details</a></li>
            <li><a href="<?= base_url('admin/product/edit_product_description_lookup/' . $product['id']) ?>">Product Description</a></li>
            <li><a href="<?= base_url('admin/gallery/add_gallery_pics_lookup/' . $product['id']) ?>">Add New Product Images</a></li>
            <li><a href="<?= base_url('admin/gallery/edit_gallery_pics_lookup/' . $product['id']) ?>">Edit Product Gallery Images</a></li>
        </ul>
    <?php endif; ?>
<?= ob_get_clean(); ?>
