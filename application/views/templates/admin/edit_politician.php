 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
            <!-- Form -->
            <?= form_open_multipart('admin/politician/edit_politician_lookup/' . $politician['id'], 'class=form id=edit_politician_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
            
        <!-- Name -->
        <div>
            <div class="col-lg-12 form-item-height">
                <div class="form-group">
                    <?= form_label('Name: ', 'name'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'name',
                            'id'            => 'name',
                            'value'         => ucwords(entity_decode($politician['name']))
                            
                        );
                    ?>
                    <?= form_input($data); ?>
                    
                    <div id="name_error"></div>

                </div>
            </div>                
        </div>                

        <!-- Designation -->
        
        <div>            
            <div class="col-lg-6">
                <div class="form-group">
                    <?= form_label('Designation: ', 'designation'); ?>
                    <?php
                        
                        $data = array(
                            
                            'class'         => 'form-control selectpicker',
                            'id'            => 'designation',
                            'name'            => 'designation',
                            'multiple'      => 'multiple',
                            'title'         => 'Choose one or more...',
                            'data-selected-text-format' => 'count',
                            'data-live-search'  => TRUE

                        );
                            
                        foreach ($designations as $designation) 
                        {
                            $id = $designation['id'];
                            $options[$id] = ucwords(entity_decode($designation['name']));
                        }

                        $selected = array_column($politician['politician_details'], 'designation_id');

                    ?>
                    <?= form_multiselect('designation', $options, $selected, $data); ?>
                    <div id="designation_error"></div>
                </div>
            </div>

            <!-- Halqa -->
            <div class="col-lg-6 form-item-height">
                <div class="form-group">
                    <?= form_label('Halqa: ', 'halqa'); ?>
                    <?php
                        
                        $data = array(
                            
                            'class'         => 'form-control selectpicker',
                            'id'            => 'halqa',
                            'multiple'      => 'multiple',
                            'title'         => 'Choose one or more...',
                            'data-selected-text-format' => 'count',
                            'data-live-search'  => TRUE
                        );

                        $options = array();

                        foreach ($halqas as $halqa)
                        {
                            $id = $halqa['id'];
                            $options[$id] = strtoupper(entity_decode($halqa['name']));
                        }

                        $selected = array_column($politician['politician_details'], 'halqa_id');

                    ?>
                    <?= form_multiselect('halqa', $options, $selected, $data); ?>
                    <div id="halqa_error"></div>
                </div>
            </div> 
        </div>

        <div>
            <div class="col-lg-12 form-item-height">
                <div class="form-group">
                    <?= form_label('Political Party: ', 'political_party'); ?>
                    <?php
                        
                        $data = array(
                            
                            'class'         => 'form-control selectpicker',
                            'id'            => 'political_party',
                            'name'          => 'political_party',
                            'title'         => 'Choose one ...',
                           'data-live-search'  => TRUE
                        );

                        $options = array();

                        foreach ($political_parties as $political_party) 
                        {
                            $id = $political_party['id'];
                            $options[$id] = ucwords(entity_decode($political_party['name']));
                        }

                        $selected = $politician['political_party_id']['id'];

                    ?>
                    <?= form_dropdown('political_party', $options, $selected, $data); ?>
                    <div id="political_party_error"></div>
                </div>
            </div>      
        </div> 
        
        <!-- Introduction -->
    
        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Introduction: ', 'introduction'); ?>
                <?php
                $introduction = html_entity_decode(stripslashes(str_replace(array('\n\r', '\r\n', '\n', '\r'), '&#013;', $politician['introduction'])), ENT_QUOTES);

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
                $election_history = html_entity_decode(stripslashes(str_replace(array('\n\r', '\r\n', '\n', '\r'), '&#013;', $politician['election_history'])), ENT_QUOTES);
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
            <?= form_label('Image: ', 'image'); ?>
            <?php

                $data = array(
                    
                    'class'         => 'form-control',
                    'name'          => 'image',
                    'id'            => 'image',
                );
            ?>

            <?= form_upload($data); ?>
        </div>
    </div>
        <!-- Image Preveiw -->
        <div id="image_preview">
            <div id="message" class="text-center"></div>
            <?php $image = (entity_decode($politician['image']) == '' ? 'no_image_600.png' : entity_decode($politician['image'])) ?>
            <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= POLITICIAN_IMAGE . $image ?>" />
            <div id="loading">
                <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
            </div>
        </div>
        <br>

        <!-- Edit Button -->
        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Edit Political Party" name="edit_politician" id="edit_politician" class="btn btn-block btn-success">
            </div>
        </div>
    <?= form_close(); ?>
    <!-- / Form -->

    </div> <!-- image_section -->
    </div> <!-- row -->
</div> <!-- page-wrapper -->
