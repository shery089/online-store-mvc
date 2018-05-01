 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/column/add_column_lookup/', 'class=form id=add_column_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
               
        <!-- Designation -->
        
        <div> 
            <div class="col-lg-6">
                <div class="form-group">
                    <?= form_label('Columnist: ', 'columnist'); ?>
                    <?php
                        
                        $data = array(
                            
                            'class'         => 'form-control selectpicker',
                            'id'            => 'columnist',
                            'name'          => 'columnist',
                            'title'         => 'Choose one ...',
                            'data-selected-text-format' => 'count',
                            'data-live-search'  => TRUE

                        );
                            
                        $options = array();
                        foreach ($columnists as $columnist) 
                        {
                            $id = $columnist['id'];
                            $options[$id] = ucwords(entity_decode($columnist['name']));
                        }

                        $selected = $this->input->post('columnist');

                        // $selected = explode(',', $this->input->post('submitted_designations'));

                    ?>
                    <?= form_dropdown('columnist', $options, $selected, $data); ?>
                </div>
                
                <div id="columnist_error"></div>
            </div> 
            <div class="col-lg-6 form-item-height">
                <div class="form-group">
                    <?= form_label('Title: ', 'title'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'title',
                            'id'            => 'title',
                            'value'         => set_value('title')
                            
                        );
                    ?>
                    <?= form_input($data); ?>
                    
                    <div id="title_error"></div>

                </div>
            </div>           
        </div>
       
        <!-- Post -->
    
        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Column: ', 'column'); ?>
                <?php

                    $data = array(
                        
                        'class'         => 'form-control',
                        'name'          => 'column',
                        'id'            => 'column',
                        'value'         => set_value('column')
                    );
                ?>

                <?= form_textarea($data); ?>

                <div id="column_error"></div>

            </div>
        </div>
     
        <div class="col-lg-6">
        </div>
        <!-- Add Button -->
        <div class="col-lg-6">
            <div class="form-group text-right">
                <input type="submit" value="Add Column" name="add_column" id="add_column" class="btn btn-success pull-right">
            </div>
        </div>

        <?= form_close(); ?>
        <!-- / Form -->
        
    </div>

    </div> <!-- row -->
</div> <!-- page-wrapper -->