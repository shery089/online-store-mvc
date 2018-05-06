<?php ob_start(); ?>

<!-- Vote Now modal -->
<div id="modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onclick="closeModel()" aria-hidden="true">×</button>
                <h4 class="text-center">Vote Now!</h4>
            </div>
            <!-- modal-body -->
            <div class="modal-body no-padding">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <?= form_open_multipart('front_end/user/insert_halqas_plus_vote_now_lookup/', 'class=form id=vote_now_form novalidate'); ?>
           
                            <!-- On Halqa -->
                            <div class="col-lg-4 form-item-height">
                                <div class="form-group">
                                    <?= form_label('NA Halqa: ', 'on_halqa'); ?>
                                    <?php
                                        
                                        $data = array(
                                            
                                            'class'         => 'form-control',
                                            'id'            => 'on_halqa',
                                            'title'         => 'Choose your NA Halqa',
                                            'data-actions-box' => 'true',
                                            'data-live-search' => 'true'
                                        );

                                        $options[] = '';

                                            
                                        foreach ($halqas as $halqa) 
                                        {
                                            $id = entity_decode($halqa['id']);
                                            $halqa = strtoupper(entity_decode($halqa['name']));
                                            // $id = explode('-', $halqa);
                                            if($halqa == 'NO HALQA')
                                            {
                                                continue;
                                            }

                                            $options[$id] = $halqa;
                                        }

                                        $selected = $this->input->post('on_halqa');

                                    ?>
                                    <?= form_dropdown('on_halqa', $options, $selected, $data); ?>

                                    <div id="on_halqa_error"></div>
                                </div>
                            </div>                                            
                            <!-- On Halqa -->
                        
                            <div class="col-lg-8 form-item-height">
                                <div class="form-group">
                                    <?= form_label('Provincial Assembly ', 'provincial_assembly'); ?>
                                    <?php
                                        
                                        $data = array(
                                            
                                            'class'         => 'form-control',
                                            'id'            => 'provincial_assembly',
                                            'title'         => 'Choose your Provincial Assembly',
                                            'data-actions-box' => 'true'
                                        );

                                        unset($options);
                                        $options[] = '';

                                        foreach ($halqa_types as $halqa_type) 
                                        {
                                            $name = ucwords(entity_decode($halqa_type['name']));
                                            if($name == 'National Assembly')
                                            {
                                                continue;
                                            }
                                            else
                                            {
                                                $id = entity_decode($halqa_type['id']);
                                                $halqa_type = $name;
                                                $options[$id] = $halqa_type;
                                            }
                                        }

                                        $selected = $this->input->post('provincial_assembly');

                                    ?>
                                    <?= form_dropdown('provincial_assembly', $options, $selected, $data); ?>

                                    <div id="provincial_assembly_error"></div>
                                </div>
                            </div>   

                            <div class="col-lg-4 form-item-height">
                            </div>   
                            
                            <div class="col-lg-8 form-item-height">
                                <div class="form-group">
                                    <?= form_label('Provincial Halqa', 'provincial_halqa'); ?>
                                    <?php
                                        
                                        $data = array(
                                            
                                            'class'         => 'form-control',
                                            'id'            => 'provincial_halqa',
                                            'title'         => 'Choose your Provincial Halqa',
                                            'data-actions-box' => 'true',
                                            'data-live-search' => 'true'
                                        );

                                        unset($options);
                                        $options[] = '';


                                        $selected = $this->input->post('provincial_halqa');

                                    ?>
                                    <?= form_dropdown('provincial_halqa', $options, $selected, $data); ?>

                                    <div id="provincial_halqa_error"></div>
                                </div>
                            </div>
                            
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div> <!-- end modal-body -->

            <div class="modal-footer">
                <div>
                    <button type="button" class="btn btn-default" onclick="closeModel()">Close</button>
                    <input type="submit" id="vote-btn" name="vote-btn" class="btn btn-primary" value="Vote Now">
                </div>
            </div>  <!-- end modal-footer -->
        </div>
    </div>
</div>  <!-- / Vote Now Modal  -->
<script>
    function closeModel() 
    {
        jQuery('#modal').modal('hide');
        setTimeout(function(){
            jQuery('#modal').remove();
            jQuery('.modal-backdrop').remove();
        },500);
    }
/*	jQuery	(document).ready(function(){
   	 jQuery("#modal").on('hidden.bs.modal', function () {
            alert('The modal is now hidden.');
           }
    }*/
</script>

<?= ob_get_clean(); ?>