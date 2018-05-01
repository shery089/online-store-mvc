 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
            <!-- Form -->
            <?php foreach ($record as $post): ?>
            <?= form_open_multipart('admin/post/edit_post_lookup/' . $post['id'], 'class=form id=edit_post_form novalidate'); ?>
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
                        $selected = $record['politician_ids'][0];
                        // $selected = array_column($post['politician_details'], 'designation_id');

                        // $selected = $this->input->post('politician');

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

                            $selected = $record['political_party_ids'][0];

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
                $actual_post = html_entity_decode(stripslashes(str_replace(array('\n\r', '\r\n', '\n', '\r'), '&#013;', $post['post'])), ENT_QUOTES);
                $actual_post = str_replace(array('[removed]'), '', $actual_post);
                    $data = array(
                        
                        'class'         => 'form-control',
                        'name'          => 'post',
                        'id'            => 'post',
                        'value'         => $actual_post
                    );
                ?>

                <?= form_textarea($data); ?>

                <div id="post_error"></div>

            </div>
        </div>
     
        <div class="col-lg-6">
        </div>
        <!-- edit Button -->
        <div class="col-lg-6">
            <div class="form-group text-right">
                <input type="submit" value="Edit Post" name="edit_post" id="edit_post" class="btn btn-success pull-right">
            </div>
        </div>

        <?= form_close(); ?>
        <!-- / Form -->
        
    </div>

    <?php break; ?>  
    <?php endforeach; ?>
    <div class="col-lg-4">
    </div>

    </div> <!-- row -->
</div> <!-- page-wrapper -->