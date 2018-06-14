<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <?php if($this->session->flashdata('success_message') OR $this->session->flashdata('delete_message')): ?>
                <p class="alert <?= ($this->session->flashdata('delete_message')) ? 'alert-danger' : 'alert-success' ?> alert-dismissable fade in text-center top-height">
                    <?php
                    if($this->session->flashdata('success_message'))
                    {
                        echo $this->session->flashdata('success_message');
                    }
                    elseif($this->session->flashdata('delete_message'))
                    {
                        echo $this->session->flashdata('delete_message');
                    }
                    else
                    {
                        echo '';
                    }
                    ?>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                </p>
            <?php endif; ?>
            </div>
            <div class="col-lg-8">
            <!-- Form -->
            <?php //validation_errors(); ?>
            <?= form_open_multipart('admin/configuration/index', 'class=form id=configuration_form novalidate'); ?>
            <h1 class="page-header text-center"><?= $layout_title ?></h1>

            <!-- Current Password -->

            <div class="col-lg-12 form-item-height">
                <div class="form-group">
                    <?= form_label('Items Per Page: ', 'item_per_page'); ?>
                    <?php

                    $data = array(

                        'class'         => 'form-control text-right',
                        'type'          => 'number',
                        'name'          => 'item_per_page',
                        'id'            => 'item_per_page',
                        'value'         => $configurations['item_per_page']
                    );
                    ?>

                    <?= form_input($data); ?>

                    <div id="item_per_page_error"></div>

                </div>
            </div>

            <!-- Minimum Products Notification -->

            <div class="col-lg-12 form-item-height">
                <div class="form-group">
                    <?= form_label('Minimum Products Notification: ', 'minimum_products_notification'); ?>
                    <?php

                    $data = array(

                        'class'         => 'form-control text-right',
                        'type'          => 'number',
                        'name'          => 'minimum_products_notification',
                        'id'            => 'minimum_products_notification',
                        'value'         => $configurations['minimum_products_notification']
                    );
                    ?>

                    <?= form_input($data); ?>

                    <div id="minimum_products_notification_error"></div>

                </div>
            </div>

            <input type="hidden" id="show_notification_hidden" value="<?= $configurations['show_notification'] ?>">

            <!-- Show Notification -->

            <div class="col-lg-12">
                <div class="form-group">
                    <?= form_label('Show Notification: ', 'show_notification'); ?>
                    <?php
                    $data = array(

                        'class'         => 'text-right',
                        'type'          => 'checkbox',
                        'name'          => 'show_notification',
                        'id'            => 'show_notification',
                        'style'         => 'position: relative;top: 2px;left: 5px;',
                    );
                    ?>

                    <?= form_input($data); ?>

                    <div id="minimum_products_notification_error"></div>

                </div>
            </div>

            <!-- Update Configurations Button -->

            <div class="col-lg-12">
                <div class="form-group text-right">
                    <input type="submit" value="Update Configurations" id="update_configurations_btn" class="btn btn-block btn-success">
                </div>
            </div>

        </div>

        <?= form_close(); ?>
    </div>

</div>

</div> <!-- /.row