 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/designation/add_designation_lookup', 'class=form id=add_designation_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
            
        <!-- First Name -->

        <div>
            <div class="col-lg-12 form-item-height">
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

        <!-- Add Button -->

        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Add User" name="add_designation" id="add_designation" class="btn btn-block btn-success">
            </div>
        </div>
    </div>
    <?= form_close(); ?>
    </div> <!-- col-lg-12" -->
</div> <!-- /.row -->

</div> <!-- /.page-wrapper -->