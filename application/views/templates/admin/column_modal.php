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
                <h4 class="text-center"><?= ($action = $action_parts[0]) == 'delete' ? "Are You Sure?" : "Column Details"; ?></h4>
            </div>
            <!-- modal-body -->
            <div class="modal-body no-padding">
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

                                            /**
                                             * [Column triming]
                                             */

                                            $column_desc = $column['column'];
                                            $time_parts = explode(':', entity_decode($column['posted_time']));
                                            $time_parts[2] = preg_replace('/[0-9]+/', '', $time_parts[2]);
                                            $columned_time_24hrs = $time_parts[0] . ':' . $time_parts[1] . ' ' . $time_parts[2];
                                                                        
                                            $columnist_name = entity_decode($column['columnist_name']);
                                            $likes = entity_decode($column['likes']);
                                            $dislikes = entity_decode($column['dislikes']);

                                        ?>
                                            <td><?= entity_decode($column_desc); ?></td>
                                            <td><?= ucwords($columnist_name) ?></td>
                                            <td><?= number_format($likes); ?></td>
                                            <td><?= number_format($dislikes); ?></td>
                                            <td><?= ucwords(entity_decode($column['user_name'])); ?></td>
                                            <td><?= entity_decode($column['posted_date']); ?></td>
                                            <td><?= $columned_time_24hrs; ?></td>
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
                        <a href="<?= site_url('admin/column/delete_column_by_id_lookup/' . $column['id']) ?>" class="btn btn-danger">Delete</a>
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