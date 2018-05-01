 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!-- Form -->
            <?= form_open_multipart('admin/designation/edit_designation_lookup/'. $designation['id'], 'class=form id=edit_designation_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>
            
        <!-- Name -->

            <div class="col-lg-12 form-item-height">
                <div class="form-group">
                    <?= form_label('Name: ', 'name'); ?>
                    <?php

                        $data = array(
                            
                            'class'         => 'form-control',
                            'name'          => 'name',
                            'id'            => 'name',
                            'value'         => ucwords(entity_decode($designation['name']))
                            
                        );
                    ?>
                    <?= form_input($data); ?>
                    
                    <div id="name_error"></div>
                </div>
            </div>

            <!-- Edit Button -->

            <div class="col-lg-12">
                <div class="form-group text-right">
                    <input type="submit" value="Edit User" name="edit_designation" id="edit_designation" class="btn btn-block btn-success">
                </div>
            </div>

        <?= form_close(); ?>
        <!-- / Form -->
        </div> <!-- col-lg-12 -->
    </div> <!-- /.row -->
</div> <!-- /.page_wrapper -->