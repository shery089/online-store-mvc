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
                <?php $action_parts = explode('_', $this->input->post('action')); ?>
                <h4 class="text-center"><?= ($action = $action_parts[0]) == 'delete' ? "Are You Sure?" : "Columnist Details"; ?></h4>
            </div>
            <!-- modal-body -->
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed table-striped admin-table">
                                    <col width="150">
                                    <col width="40">
                                    <col width="40">
                                    <col width="40">
                                    <col width="80">
                                    <col width="30">
                                    <col width="30">
                                    <thead>  
                                        <th>Post</th>
                                        <th>Likes</th>
                                        <th>Dislikes</th>
                                        <th>Entity</th>
                                        <th>Posted By</th>
                                        <th>Posted Date</th>
                                        <th>Posted Time</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <?php

                                            $columnist_name = ucwords(entity_decode($columnist['name']));
                                            $newspaper = array_column($columnist['newspaper_id'], 'name');
                                            $newspaper = implode(', ', $newspaper);
                                            $columnist_name = (strlen($columnist_name) > 32) ? substr($columnist_name,0,26).'.....' : $columnist_name;

                                        ?>
                                        <td class="text-justify"><?= entity_decode($columnist_desc); ?></td>
                                        <td class="text-justify"><?= $likes == 0 ? '' : $likes ?></td>
                                        <td class="text-justify"><?= $dislikes == 0 ? '' : $dislikes ?></td>
                                        <td class="text-justify"><?= ucwords(str_replace('_', ' ', $entity)); ?></td>
                                        <td class="text-justify"><?= ucwords(entity_decode($columnist['user_details']['full_name'])); ?></td>
                                        <td class="text-justify"><?= entity_decode($columnist['posted_date']); ?></td>
                                        <td class="text-justify"><?= $columnisted_time_24hrs; ?></td>
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
                        <a href="<?= site_url('admin/post/delete_post_by_id_lookup/' . $columnist['id']) ?>" class="btn btn-danger">Delete</a>
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