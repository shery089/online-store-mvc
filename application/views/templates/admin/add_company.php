 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-8">
                <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/company/add_company_lookup', 'class=form id=add_company_form novalidate'); ?>
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
        </div>

        <div>



        </div>

        <div>
            <!-- Email -->

            <div class="col-lg-12 form-item-height">
                <div class="form-group">
                    <?= form_label('Email: ', 'email'); ?>
                    <?php

                        $data = array(

                            'class'         => 'form-control',
                            'type'          => 'email',
                            'name'          => 'email',
                            'id'            => 'email',
                            'value'         => set_value('email')
                        );
                    ?>

                    <?= form_input($data); ?>

                    <div id="email_error"></div>

                </div>
            </div>

            <!-- Phone Number -->

            <div class="col-lg-12 form-item-height">
                <div class="form-group">
                    <?= form_label('Phone Number: ', 'phone_number'); ?>
                    <?php

                        $data = array(

                            'class'         => 'form-control',
                            'name'          => 'phone_number',
                            'id'            => 'phone_number',
                            'placeholder'   => 'Enter one or more by separating them with a space',
                            'value'         => set_value('phone_number')
                        );
                    ?>

                    <?= form_input($data); ?>

                    <div id="phone_number_error"></div>

                </div>
            </div>

            <!-- Website -->

            <div class="col-lg-12 form-item-height">
                <div class="form-group">
                    <?= form_label('Website: ', 'website'); ?>
                    <?php

                        $data = array(

                            'class'         => 'form-control',
                            'name'          => 'website',
                            'id'            => 'website',
                            'value'         => set_value('website')
                        );
                    ?>

                    <?= form_input($data); ?>

                    <div id="website_error"></div>

                </div>
            </div>

            <!-- Website -->

            <div class="col-lg-12">
                <div class="form-group">
                    <?= form_label('Description: ', 'description'); ?>
                    <?php

                        $data = array(

                            'class'         => 'form-control',
                            'name'          => 'description',
                            'id'            => 'description',
                            'value'         => set_value('description')
                        );
                    ?>

                    <?= form_textarea($data); ?>

                    <div id="description_error"></div>

                </div>
            </div>
        </div>

    </div>

    <!-- Image Preveiw -->

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

    <!-- / Form -->
        <div id="image_preview">
            <div id="message" class="text-center"></div>
            <img class="img-responsive previewing fadein img-rounded" id="previewing" src="<?= COMPANY_IMAGE ?>no_image_600.png" />
            <div id="loading">
                <img class="img-responsive" src="<?= ADMIN_IMAGES_PATH; ?>/coursera_ditto.gif" />
            </div>
        </div>
        <br>

        <!-- Spinner -->

        <img id="spinner" class="hide" src="<?= ADMIN_IMAGES_PATH . 'ajax_clock_small.gif' ?>">

        <!-- Add Button -->

        <div class="col-lg-12">
            <div class="form-group text-right">
                <input type="submit" value="Add Company" name="add_company" id="add_company" class="btn btn-block btn-success">
            </div>
          </div>
    </div>
    <?= form_close(); ?>

    </div>
            
</div> <!-- /.row