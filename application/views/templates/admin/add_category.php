 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
        
        <!-- Form -->
        
        <?= form_open_multipart('admin/category/add_category_lookup', 'class=form id=add_category_form novalidate'); ?>
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
                        'value'         => set_value('name')
                        
                    );
                ?>

                <?= form_input($data); ?>
                
                <div id="name_error"></div>

            </div>
        </div>                

        <!-- Parent -->

        <div class="col-lg-12">
            <div class="form-group">
                <?= form_label('Parent: ', 'parent'); ?>
                <?php

                    $data = array(

                        'class'         => 'form-control selectpicker',
                        'id'            => 'parent',
                        'name'            => 'parent',
                        'title'         => 'Choose Parent Category',
                        'data-live-search'  => TRUE

                    );

                    if(!empty($categories))
                    {
                        foreach ($categories as $category)
                        {
                            $id = $category['id'];
                            if(is_array($category['parent']) && $category['parent']['name'] != 'parent') {

                                $options[$id] = ucwords( $category['parent']['name'] . ' - ' .  entity_decode($category['name']));
                            }
                            else {
                                $options[$id] = ucwords(entity_decode($category['name']));
                            }
                        }
                    }

                    $selected = $this->input->post('parent');

                ?>

                <?= form_dropdown('parent', $options, $selected, $data); ?>

                <div id="parent_error"></div>
            </div>
        </div>

<!--        <div class="col-lg-12">
            <div class="form-group">
                <?/*= form_label('Parent: ', 'parent'); */?>
                <?php /*print_r($categories); die; */?>

                <div class="col-lg-12">
                    <div class="form-group">
                        <?/*= form_label('Category: ', 'category'); */?>
                        <select class="selectpicker" multiple>

                            <?php /*foreach ($categories as $category):
                                */?>

                                <optgroup label="Condiments">
                                    <option>Mustard</option>

                                    <option>Ketchup</option>
                                    <option>Relish</option>
                                </optgroup>
                            <?php /*endforeach; */?>
                        </select>
                        <div id="category_error"></div>
                    </div>
                </div>

                <div id="parent_error"></div>
            </div>
        </div>-->
        
        </div> 

        <!-- /col-lg-8 -->

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
                <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= CATEGORY_IMAGE ?>no_image_600.png" />
                <div id="loading">
                    <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
                </div>
            </div>

            <br>

            <!-- Add Button -->

            <div class="col-lg-12">
                <div class="form-group text-right">
                    <input type="submit" value="Add Category" name="add_category" id="add_category" class="btn btn-block btn-success">
                </div>
            </div>

        <?= form_close(); ?>

        <!-- / Form -->

    </div> <!-- image_section -->
    </div> <!-- row -->
</div> <!-- page-wrapper -->