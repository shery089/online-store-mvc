<!-- 
    Turn on output buffering. No output is sent from the script (other 
    than headers), instead the output is stored in an internal buffer. 
-->
<?php ob_start(); ?>

<!--=====================================
=            Modal comment              =
======================================-->

<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="closeModel()" aria-hidden="true">×</button>
                <h4 class="text-center"><?= ($action = $this->input->post('action')) == 'delete' ? "Are You Sure?" : "Product Attribute Details"; ?></h4>
            </div>

            <!-- modal-body -->
            <div class="modal-body no-padding">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 no-padding">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed table-striped admin-table text-center no-margin">
                                    <col width="100">
                                    <col width="70">
                                    <col width="70">
                                    <thead>
                                        <th class="text-center">Product Attribute</th>
                                        <th class="text-center">Product Attribute Details</th>
                                    </thead>
                                    <tbody>
                                    <?php $is_color = strlen(strpos($product_attribute_detail['product_attribute_value'], '#'));?>
                                    <?php $function = strlen($product_attribute_detail['product_attribute_value']) <= 3 ? 'strtoupper' : 'ucwords' ?>
                                        <tr>
                                            <td><?= ucwords(entity_decode($product_attribute_detail['product_attribute_name'])); ?></td>
                                            <td style="background-color: <?= $is_color > 0 ? (entity_decode($product_attribute_detail['product_attribute_value'])) : '' ?>"> <?= $is_color > 0 ? '' : $function(entity_decode($product_attribute_detail['product_attribute_value'])); ?></td>
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
                        <a href="<?= site_url('admin/product_attribute_detail/delete_product_attribute_detail_by_id_lookup/' . $product_attribute_detail['id']) ?>" class="btn btn-danger">Delete</a>
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