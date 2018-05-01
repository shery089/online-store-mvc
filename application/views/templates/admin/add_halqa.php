 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/halqa/add_halqa_lookup', 'class=form id=add_halqa_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
            
        <!-- First Name -->

        <div>
            <div class="col-lg-6 form-item-height">
                <div class="form-group">
                    <?= form_label('Name: ', 'name'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'name',
                            'id'            => 'name',
                            'value'         => set_value('name')
                            
                        );
                    ?>
                    <?= form_input($data); ?>
                    
                    <div id="name_error"></div>

                </div>
            </div>      

            <!-- Halqa Type -->
        
            <div class="col-lg-6 form-item-height">
                <div class="form-group">
                    <?= form_label('Type: ', 'type'); ?>
                    <?php
                        
                        $data = array(
                            
                            'class'         => 'form-control selectpicker',
                            'id'            => 'type',
                            'name'          => 'type',
                            'title'         => 'Choose one ...'
                        );

                        $options = array();

                        foreach ($types as $type) 
                        {
                            $id = $type['id'];
                            $options[$id] = ucwords(entity_decode($type['name']));
                        }

                        $selected = $this->input->post('type');
                    ?>
                    <?= form_dropdown('type', $options, $selected, $data); ?>
                    <div id="type_error"></div>
                </div>
            </div>

        <!-- Add Button -->

        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Add User" name="add_halqa" id="add_halqa" class="btn btn-block btn-success">
            </div>
        </div>
    </div>

    </div> <!-- col-lg-12" -->
</div> <!-- /.row -->

</div> <!-- /.page-wrapper -->