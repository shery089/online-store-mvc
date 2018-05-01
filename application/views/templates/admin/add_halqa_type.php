 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/halqa_type/add_halqa_type_lookup', 'class=form id=add_halqa_type_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
            

        <div>
            
            <!-- Name -->
            
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

            <!-- Abbreviation -->

            <div class="col-lg-6 form-item-height">
                <div class="form-group">
                    <?= form_label('Abbreviation: ', 'abbreviation'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'abbreviation',
                            'id'            => 'abbreviation',
                            'value'         => set_value('abbreviation')
                            
                        );
                    ?>
                    <?= form_input($data); ?>
                    
                    <div id="abbreviation_error"></div>

                </div>
            </div>      

          
            </div>

        <!-- Add Button -->

        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Add Halqa Type" name="add_halqa_type" id="add_halqa_type" class="btn btn-block btn-success">
            </div>
        </div>
    </div>

    </div> <!-- col-lg-12" -->
</div> <!-- /.row -->

</div> <!-- /.page-wrapper -->