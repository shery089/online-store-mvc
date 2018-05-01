 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/post/add_post_lookup', 'class=form id=add_post_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
               
        <!-- Designation -->
        
        <div>            
            <div class="col-lg-6">
                <div class="form-group">
                    <?= form_label('Politician: ', 'politician'); ?>
                    <?php
                        
                        $data = array(
                            
                            'class'         => 'form-control selectpicker',
                            'id'            => 'politician',
                            'name'          => 'politician',
                            'multiple'      => 'multiple',
                            'title'         => 'Choose one or more...',
                            'data-selected-text-format' => 'count',
                            'data-live-search'  => TRUE

                        );
                            
                        $options = array();
                        foreach ($politicians as $politician) 
                        {
                            $id = $politician['id'];
                            $options[$id] = ucwords(entity_decode($politician['name']));
                        }

                        $selected = $this->input->post('politician');

                        // $selected = explode(',', $this->input->post('submitted_designations'));

                    ?>
                    <?= form_multiselect('politician', $options, $selected, $data); ?>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <?= form_label('Political Party: ', 'political_party'); ?>
                    <?php
                        
                        $data = array(
                            
                            'class'         => 'form-control selectpicker',
                            'id'            => 'political_party',
                            'name'          => 'political_party',
                            'multiple'      => 'multiple',
                            'title'         => 'Choose one or more...',
                            'data-selected-text-format' => 'count',
                            'data-live-search'  => TRUE

                        );
                            
                       $options = array();
                        foreach ($political_parties as $political_party) 
                        {
                            $id = $political_party['id'];
                            $options[$id] = ucwords(entity_decode($political_party['name']));
                        }

                        $selected = $this->input->post('political_party');

                        // $selected = explode(',', $this->input->post('submitted_designations'));

                    ?>
                    <?= form_multiselect('political_party', $options, $selected, $data); ?>
                </div>
            </div>
            <div class="col-lg-12" id="type_error"></div>
        </div>
       
        <!-- Post -->
    
        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Post: ', 'post'); ?>
                <?php

                    $data = array(
                        
                        'class'         => 'form-control',
                        'name'          => 'post',
                        'id'            => 'post',
                        'value'         => set_value('post')
                    );
                ?>

                <?= form_textarea($data); ?>

                <div id="post_error"></div>

            </div>
        </div>
     
        <div class="col-lg-6">
        </div>
        <!-- Add Button -->
        <div class="col-lg-6">
            <div class="form-group text-right">
                <input type="submit" value="Add Post" name="add_post" id="add_post" class="btn btn-success pull-right">
            </div>
        </div>

        <?= form_close(); ?>
        <!-- / Form -->
        
    </div>

    </div> <!-- row -->
</div> <!-- page-wrapper -->