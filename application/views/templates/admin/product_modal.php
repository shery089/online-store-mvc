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
                <h4 class="text-center"><?= ($action = $this->input->post('action')) == 'delete' ? "Are You Sure?" : "Product Details"; ?></h4>
            </div>

            <!-- modal-body -->
            <div class="modal-body no-padding">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12 no-padding">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed table-striped admin-table">
                                    <col width="100">
                                    <col width="70">
                                    <col width="85">
                                    <col width="100">
                                    <col width="110">
                                    <col width="100">
                                    <thead>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Category</th>
                                    <th class="text-center">Short Description</th>
                                    <th class="text-center">Long Description</th>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-center"><?= ucwords($product['name']); ?></td>
                                        <td class="text-center"><?= ucwords($product['category']); ?></td>
                                        <td class="text-justify"><?= stripcslashes(ucwords($product['short_description'])); ?></td>
                                        <td class="text-justify"><?= stripcslashes(ucwords($product['long_description'])); ?></td>
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
                        <a href="<?= site_url('admin/product/delete_product_by_id_lookup/' . $product['id']); ?>" class="btn btn-danger">Delete</a>
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