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
                <h4 class="text-center"><?= ($action = $this->input->post('action')) == 'delete' ? "Are You Sure?" : "Political Party Details"; ?></h4>
            </div>

            <!-- modal-body -->
            <div class="modal-body no-padding">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed table-striped admin-table">
                                    <col width="30">
                                    <col width="110">
                                    <col width="115">
                                    <col width="40">
                                    <col width="50">
                                    <col width="120">
                                    <col width="120">
                                    <thead>
                                        <th>Flag</th>
                                        <th>Name</th>
                                        <th>Leader</th>
                                        <th>Designation</th>
                                        <th>Founded Date</th>
                                        <th>Address</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <?php 
                                            $name = ucwords(entity_decode($political_party['name']));
                                            $name_len = strlen($name);
                                            
                                            if (strpos($name, '-') !== false) 
                                            {
                                              if(substr_count($name, '-') > 1)
                                              {
                                                $name = mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, "UTF-8");

                                                $pos1 = strpos($name, '-') + 1;
                                                $pos2 = strpos($name, '-', $pos1 + strlen('-'));
                                                $length = abs($pos1 - $pos2);

                                                $between = strtolower(substr($name, $pos1, $length));
                                                $name = substr_replace($name, $between, $pos1, $length);
                                              }
                                              else
                                              {
                                                $name = mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, "UTF-8");
                                              }
                                            }
                                            if (strpos($name, '(') !== false) 
                                            {
                                              $name = explode('(', rtrim($name, ')'));
                                              $name = $name[0] . '(' . ucwords($name[1]) . ')';
                                            } 

                                            // founded_date
                                            $founded_date = entity_decode($political_party['founded_date']);
                            
                                        ?>
                                        <td><img style="width:40px" src="<?= PARTY_IMAGE . entity_decode($political_party['flag']); ?>" alt="Party Flag"></td>
                                        <td><?= $name; ?></td>
                                        <td><?= ucwords(entity_decode($political_party['leader'])); ?></td>
                                        <td><?= ucwords(entity_decode($political_party['designation']['name'])); ?></td>
                                        <td><?= $founded_date === '0000' ? '' : $founded_date ?></td>
                                        <td><?= ucwords(entity_decode($political_party['address'])); ?></td>
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
                        <a href="<?= site_url('admin/political_party/delete_political_party_by_id_lookup/' . $political_party['id']) ?>" class="btn btn-danger">Delete</a>
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