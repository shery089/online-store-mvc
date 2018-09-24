<?php ob_start(); ?>
    <ul class="nav nav-pills marg-top-10">
        <li><a href="<?= base_url('admin/product_analysis/total_sales/') ?>">Total Sales</a></li>
        <li><a href="<?= base_url('admin/product_analysis/top_five_best_selling_products/') ?>">Top 5 Best Selling Products</a></li>
        <li><a href="<?= base_url('admin/product_analysis/top_five_least_selling_products/') ?>">Top 5 Least Selling Products</a></li>
    </ul>
<?= ob_get_clean(); ?>
