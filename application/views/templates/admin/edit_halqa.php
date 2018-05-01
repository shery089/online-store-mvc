 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <!-- Form -->
            <?= form_open_multipart('admin/halqa/edit_halqa_lookup/'. $halqa['id'], 'class=form id=edit_halqa_form novalidate'); ?>
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
                            'value'         => ucwords(entity_decode($halqa['name']))
                            
                        );
                    ?>
                    <?= form_input($data); ?>
                    
                    <div id="name_error"></div>
                </div>
            </div>

            <!-- Edit Button -->

            <div class="col-lg-12">
                <div class="form-group text-right">
                    <input type="submit" value="Edit User" name="edit_halqa" id="edit_halqa" class="btn btn-block btn-success">
                </div>
            </div>

        <?= form_close(); ?>
        <!-- / Form -->
        </div> <!-- col-lg-12 -->
    </div> <!-- /.row -->
</div> <!-- /.page_wrapper -->