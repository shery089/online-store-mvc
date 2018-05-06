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
                <h4 class="text-center"><?= ($action = $action_parts[0]) == 'delete' ? "Are You Sure?" : "Political Party Details"; ?></h4>
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
                                            $post_desc = $post['post']; 
                                            /**
                                            * [Post triming]
                                            */
                                            /*$post_desc = html_entity_decode($post['post'], ENT_QUOTES); 
                                            // $post_desc = preg_replace('/\r\n|\r|\n+/', "\n", $post_desc);
                                            $post_desc = implode('<br>', array_map('ucfirst', explode('<br>', stripslashes(str_replace(array('\r\n', '\n\r', '\r', '\n'), '<br>', $post_desc)))));
                                            $post_desc = preg_replace('/\[removed\]/', '', $post_desc);
                                            // $post_desc = implode('<br>', array_map('ucfirst', explode('<br>', preg_replace('![\r\n]+|[\n\r]+|[\n]+|[\r]+!', '<br>', $post_desc))));
                                            // $post_desc = preg_replace('![\r\n]+|[\n\r]+|[\n]+|[\r]+!', PHP_EOL, $post_desc);
                                            // $post_desc = html_entity_decode(stripslashes($post_desc), ENT_QUOTES);
                                            $post_desc = preg_replace('/(<br>)+/', '<br>', $post_desc);
                                            $post_desc = preg_replace('/[\t]+/', '    ', $post_desc);
                                            $post_desc = preg_replace('/[\s]+/', ' ', $post_desc);
                                            */
                                            /**
                                            * [posted_time triming]
                                            */
                                            
                                            $time_parts = explode(':', entity_decode($post['posted_time']));
                                            $time_parts[2] = preg_replace('/[0-9]+/', '', $time_parts[2]);
                                            $posted_time_24hrs = $time_parts[0] . ':' . $time_parts[1] . ' ' . $time_parts[2];
                                            $entity = entity_decode($post['entity']);
                                            $likes = entity_decode($post['likes']);
                                            $dislikes = entity_decode($post['dislikes']);

                                        ?>
                                        <td class="text-justify"><?= entity_decode($post_desc); ?></td>
                                        <td class="text-justify"><?= $likes == 0 ? '' : $likes ?></td>
                                        <td class="text-justify"><?= $dislikes == 0 ? '' : $dislikes ?></td>
                                        <td class="text-justify"><?= ucwords(str_replace('_', ' ', $entity)); ?></td>
                                        <td class="text-justify"><?= ucwords(entity_decode($post['user_details']['full_name'])); ?></td>
                                        <td class="text-justify"><?= entity_decode($post['posted_date']); ?></td>
                                        <td class="text-justify"><?= $posted_time_24hrs; ?></td>
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
                        <a href="<?= site_url('admin/post/delete_post_by_id_lookup/' . $post['id']) ?>" class="btn btn-danger">Delete</a>
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