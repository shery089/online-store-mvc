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
                <h4 class="text-center">HHH</h4>
            </div>

            <!-- modal-body -->
            <div class="modal-body no-padding">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                        </div>
                    </div>
                </div>
            </div> <!-- end modal-body -->

            <div class="modal-footer">
                <div>
                    <button type="button" class="btn btn-default" onclick="closeModel()"><? ($action) == 'delete' ? "Cancel" : "Close" ?></button>
                </div>
            </div>  <!-- end modal-footer -->
            <?php //endforeach ?>
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