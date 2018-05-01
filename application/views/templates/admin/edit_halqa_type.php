 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!-- Form -->
            <?= form_open_multipart('admin/halqa_type/edit_halqa_type_lookup/'. $halqa_type['id'], 'class=form id=edit_halqa_type_form novalidate'); ?>
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
                            'value'         => ucwords(entity_decode($halqa_type['name']))                            
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
                            'value'         => ucfirst(entity_decode($halqa_type['abbreviation']))
                            
                        );
                    ?>
                    <?= form_input($data); ?>
                    
                    <div id="abbreviation_error"></div>

                </div>
            </div>      

          
        </div>

            <!-- Edit Button -->

            <div class="col-lg-12">
                <div class="form-group text-right">
                    <input type="submit" value="Edit User" name="edit_halqa_type" id="edit_halqa_type" class="btn btn-block btn-success">
                </div>
            </div>

        <?= form_close(); ?>
        <!-- / Form -->
        </div> <!-- col-lg-12 -->
    </div> <!-- /.row -->
</div> <!-- /.page_wrapper -->