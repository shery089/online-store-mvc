 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
                <!-- Form -->
            <?= form_open_multipart('admin/political_party/edit_political_party_lookup/' . $political_party['id'], 'class=form id=edit_political_party_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
            <!-- Name -->
            <div>
                <div class="col-lg-6 form-item-height">
                    <div class="form-group">
                        <?= form_label('Name: ', 'name'); ?>
                        <?php

                            $data = array(
                                
                                'class'         => 'form-control',
                                'name'          => 'name',
                                'id'            => 'name',
                                'value'         => ucwords(entity_decode($political_party['name']))
                                
                            );
                        ?>
                        <?= form_input($data); ?>
                        
                        <div id="name_error"></div>

                    </div>
                </div>

            <!-- Founded Date -->
            
            <div class="col-lg-6 form-item-height">
                <div class="form-group">
                    <?= form_label('Founded Date: ', 'founded_date'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'founded_date',
                            'id'            => 'founded_date',
                            'value'         => set_value('founded_date')
                            
                        );
                    
                        $starting_year  = date('Y', strtotime('-100 year'));
                        $ending_year = date('Y', strtotime('+0 year'));
                        $current_year = date('Y');
                        for($starting_year; $starting_year <= $ending_year; $starting_year++)
                        {
                            $options[$starting_year] = $starting_year;
                        } 

                        $selected = $this->input->post('founded_date');
                    ?>
                    <?= form_dropdown('founded_date', $options, $selected, $data); ?>


                    <div id="founded_date_error"></div>

                </div>
            </div>    

                <!-- Leader -->
            
                <div class="col-lg-6 form-item-height">
                    <div class="form-group">
                        <?= form_label('Leader: ', 'leader'); ?>
                        <?php

                            $data = array(
                                
                                'class'         => 'form-control',
                                'name'          => 'leader',
                                'id'            => 'leader',
                                'value'         => ucwords(entity_decode($political_party['leader']))
                            );
                        ?>
                            
                        <?= form_input($data); ?>

                        <div id="leader_error"></div>

                    </div>
                </div>      

                <!-- Designation -->
            
                <div class="col-lg-6 form-item-height">
                    <div class="form-group">
                        <?= form_label('Designation: ', 'Designation'); ?>
                        <?php
                            
                            $data = array(
                                
                                'class'         => 'form-control selectpicker',
                                'id'            => 'designation',
                                'name'          => 'designation',
                                'title'         => 'Choose one ...'
                            );
                               
                            $options = array(); 
                            foreach ($designations as $designation) 
                            {
                                $id = $designation['id'];
                                $options[$id] = ucwords(entity_decode($designation['name']));
                            }

                            $selected = ucwords(entity_decode($political_party['designation_id']))
                        ?>
                        <?= form_dropdown('designation', $options, $selected, $data); ?>
                        <div id="designation_error"></div>
                    </div>
                </div>      
            </div> 
            
            <!-- Address -->

            <div class="col-lg-12">
                <div class="form-group">
                    <?= form_label('Address: ', 'address'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'address',
                            'id'            => 'address',
                            'value'         => ucwords(entity_decode($political_party['address']))
                        );
                    ?>

                    <?= form_textarea($data); ?>

                    <div id="address_error"></div>

                </div>
            </div>   

                    <!-- Introduction -->
        
            <div class="col-lg-12">
                <div class="form-group">
                    <?= form_label('Introduction: ', 'introduction'); ?>
                    <?php
                    $introduction = html_entity_decode(stripslashes(str_replace(array('\n\r', '\r\n', '\n', '\r'), '&#013;', $political_party['introduction'])), ENT_QUOTES);

                    $introduction = str_replace(array('[removed]'), '', $introduction);

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'introduction',
                            'id'            => 'introduction',
                            'value'         => ucfirst($introduction)
                        );
                    ?>

                    <?= form_textarea($data); ?>

                    <div id="introduction_error"></div>

                </div>
            </div>

            <!-- Election History -->
        
            <div class="col-lg-12">
                <div class="form-group">
                    <?= form_label('Election History: ', 'election_history'); ?>
                    <?php
                    $election_history = html_entity_decode(stripslashes(str_replace(array('\n\r', '\r\n', '\n', '\r'), '&#013;', $political_party['election_history'])), ENT_QUOTES);
                    $election_history = str_replace(array('[removed]'), '', $election_history);
                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'election_history',
                            'id'            => 'election_history',
                            'value'         => $election_history
                        );
                    ?>

                    <?= form_textarea($data); ?>

                    <div id="election_history_error"></div>

                </div>
            </div>             

            
        </div>

        <div class="col-lg-4 image_section">

        <!-- Image -->

        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Flag: ', 'flag'); ?>
                <?php

                    $data = array(
                        
                        'class'         => 'form-control',
                        'name'          => 'flag',
                        'id'            => 'flag',
                    );
                ?>

                <?= form_upload($data); ?>
            </div>
        </div>

        <!-- Image Preveiw -->
        <div id="image_preview">
            <div id="message" class="text-center"></div>

                <?php 
                    $flag = $political_party['flag'];
                    $flag = empty($flag) ? 'no_image_600.png' : $flag; 
                ?>
                <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= PARTY_IMAGE . $flag ?>" />
            <div id="loading">
                <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
            </div>
        </div>
        <br>


        <!-- Edit Button -->
        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Edit Political Party" name="edit_political_party" id="edit_political_party" class="btn btn-block btn-success">
            </div>
        </div>

    <?= form_close(); ?>
    <!-- / Form -->
    </div> <!-- image_section -->
    </div> <!-- row -->
</div> <!-- page-wrapper -->