<!-- 
    Turn on output buffering. No output is sent from the script (other 
    than headers), instead the output is stored in an internal buffer. 
-->
<?php ob_start(); ?>

<!--=====================================
=            Modal comment              =
======================================-->

<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="closeModel()" aria-hidden="true">×</button>
                <h4 class="text-center"><?= ($action = $this->input->post('action')) == 'delete' ? "Are You Sure?" : "Purchase Order Details"; ?></h4>
            </div>

            <!-- modal-body -->
            <div class="modal-body no-padding">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 no-padding">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed table-striped admin-table">
                                    <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Company</th>
                                        <th>Product Attribute</th>
                                        <th>Product Attribute Detail</th>
                                        <th>Quantity</th>
                                        <th>Purchase Price</th>
                                        <th>Sale Price</th>
                                        <th>Last Updated On</th>
                                        <th>Last Updated By</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?= ucwords($purchase_order['product_name']); ?></td>
                                            <td><?= ucwords($purchase_order['company_name']); ?></td>
                                            <td><?= ucwords($purchase_order['product_attribute_name']); ?></td>
                                            <?php if(strpos($purchase_order['product_attribute_value'], '#') !== FALSE): ?>
                                                <td style="background: <?= $purchase_order['product_attribute_value'] ?>"></td>
                                            <?php else: ?>
                                                <?= ucwords($purchase_order['product_attribute_value']); ?>
                                            <?php endif; ?>
                                            <td><?= ucwords($purchase_order['quantity']); ?></td>
                                            <td><?= ucwords($purchase_order['purchase_price']); ?></td>
                                            <td><?= ucwords($purchase_order['sale_price']); ?></td>
                                            <td><?= ucwords($purchase_order['last_updated_on']); ?></td>
                                            <td><?= ucwords($purchase_order['last_updated_by']); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end modal-body -->

            <div class="modal-footer">
                <div>
                    <button type="button" class="btn btn-default" onclick="closeModel()"><?= ($action) == 'delete' ? "Cancel" : "Close" ?></button>
                    <?php
                    if($action == 'delete'): ?>
                        <a href="<?= site_url('admin/user/delete_user_by_id_lookup/' . ucwords($user['id'])); ?>" class="btn btn-danger">Delete</a>
                    <?php endif; ?>
                </div>
            </div>  <!-- end modal-footer -->
        </div>
</div>  

<!--====  End of Modal comment  ====-->

<script>
    /**
     * [closeModel: This function closes/remove the modal and also removes
     * modal-backdrop after 500ms i.e 0.5s]
     * @return {[type]} [description]
     */
    function closeModel() 
    {
        jQuery('#modal').modal('hide');
        setTimeout(function(){
            jQuery('#modal').remove();
            jQuery('.modal-backdrop').remove();
        },500);
    }
</script>

<!-- Get current buffer contents and delete current output buffer -->
<?= ob_get_clean(); ?>