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
                <h4 class="text-center">Product Attributes</h4>
            </div>

            <!-- modal-body -->
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
<!--                                <table class="table table-bordered table-condensed table-striped admin-table">-->
<!--                                    <thead>-->
                                    <?php foreach ($product_attributes as $product_attribute): ?>
                                        <?php foreach ($record as $product): ?>
                                            <?php if(in_array_r($product_attribute, $product)): ?>
<!--                                                <th>--><?//= ucwords($product_attribute); ?><!--</th>-->
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
<!--                                    </thead>-->
<!--                                    <tbody>-->
<!---->
<!--                                    <tr>-->
                                    <?php foreach ($product_attributes as $product_attribute): ?>
                                        <?php foreach ($record as $product): ?>
                                            <?php if(in_array_r($product_attribute, $product)): ?>
                                                    <?php
                                                        switch($product_attribute) {
                                                            case 'color': ?>
                                                                <h4 style="margin-top: 15px;"><?= ucwords($product_attribute) ?></h4>
                                                                <?php
                                                                foreach(custom_echo($product, $product_attribute, 'no_case_change') as $color):
                                                                ?>
                                                                    <span style="border-style: outset; padding: 5px 20px; margin-right: 5px; background: <?= $color; ?>"></span>
                                                                <?php
                                                                endforeach;
                                                                break;
                                                            case 'size': ?>
                                                                <h4 style="margin-top: 15px;"><?= ucwords($product_attribute) ?></h4>
                                                                <?php
                                                                foreach(custom_echo($product, $product_attribute, 'no_case_change') as $size):
                                                                ?>
                                                                    <span><?= strtoupper($size); ?></span>

                                                                <?php
                                                                endforeach;
                                                                break;
                                                            default: ?>
                                                                <h4 style="margin-top: 15px;"><?= ucwords($product_attribute) ?></h4>
                                                                <?php
                                                                foreach(custom_echo($product, $product_attribute, 'no_case_change') as $attr):
                                                                ?>
                                                                <h2>
                                                                    <span><?= $attr; ?></span>
                                                                </h2>
                                                                <?php
                                                                endforeach;
                                                                break;
                                                        }
                                                    ?>
                                                 <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
<!--                                    </tr>-->
<!--                                    </tbody>-->
<!--                                </table>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end modal-body -->

            <div class="modal-footer">
                <div>
                    <button type="button" class="btn btn-default" onclick="closeModel()">Close</button>
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