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
                <h4 class="text-center"><?= ($action = $this->input->post('action')) == 'delete' ? "Are You Sure?" : "Politician Details"; ?></h4>
            </div>

            <!-- modal-body -->
            <div class="modal-body no-padding">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed table-striped admin-table">
                                    <col width="120">
                                    <col width="110">
                                    <col width="115">
                                    <col width="80">
                                    <col width="30">
                                    <col width="30">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Political Party</th>
                                            <th>Designation(s)</th>
                                            <th>Halqa(s)</th>
                                            <th>Likes</th>
                                            <th>Dislikes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <?php

                                            // Political Party triming
                                            $political_party = ucwords($politician['political_party_id']['name']);
                                            $political_party_len = strlen($political_party);
                                            
                                            if (strpos($political_party, '-') !== false) 
                                            {
                                              if(substr_count($political_party, '-') > 1)
                                              {
                                                $political_party = mb_convert_case(mb_strtolower($political_party), MB_CASE_TITLE, "UTF-8");

                                                $pos1 = strpos($political_party, '-') + 1;
                                                $pos2 = strpos($political_party, '-', $pos1 + strlen('-'));
                                                $length = abs($pos1 - $pos2);

                                                $between = strtolower(substr($political_party, $pos1, $length));
                                                $political_party = substr_replace($political_party, $between, $pos1, $length);
                                              }
                                              else
                                              {
                                                $political_party = mb_convert_case(mb_strtolower($political_party), MB_CASE_TITLE, "UTF-8");
                                              }
                                            }
                                            if (strpos($political_party, '(') !== false) 
                                            {
                                                $political_party = explode('(', rtrim($political_party, ')'));
                                                $political_party = $political_party[0] . '(' . ucwords($political_party[1]) . ')';                                                
                                            }

                                            // End Political Party triming
                                            
                                            // Politician
                                            $politician_name = ucwords(entity_decode($politician['name']));

                                            // Designations
                                            $designation = ucwords(entity_decode(implode(', ', array_column($politician['politician_details'], 'designation'))));
                                            
                                            // Halqa
                                            $halqa = strtoupper(entity_decode(implode(', ', array_column($politician['politician_details'], 'halqa'))));

                                        ?>
                                            <td><?= $politician_name ?></td>
                                            <td><?= $political_party; ?></td>
                                            <td><?= $designation; ?> </td>
                                            <td><?= $halqa; ?> </td>
                                            <td><?= ucwords(entity_decode($politician['likes'])); ?></td>
                                            <td><?= ucwords(entity_decode($politician['dislikes'])); ?></td>
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
                        <a href="<?= site_url('admin/post/delete_post_by_id_lookup/' . $politician['id']) ?>" class="btn btn-danger">Delete</a>
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