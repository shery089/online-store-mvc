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
            <h1 class="page-header"><?= $layout_title; ?></h1>
             </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <form id="purchase_product_form">
            <div class="col-lg-3">
                <div class="form-group custom-search-form">
                    <div class="form-group">
                        <?php

                        $data = array(

                            'class'         => 'form-control selectpicker',
                            'id'            => 'search_by_product_company',
                            'name'          => 'product_company',
                            'title'         => 'Choose a Company'
                        );

                        $options = array();

                        foreach ($companies as $company)
                        {
                            $id = $company['id'];
                            $options[$id] = ucwords(entity_decode($company['name']));
                        }

                        $selected = $this->input->post('search_by_product_company');

                        ?>
                        <?= form_dropdown('search_by_product_company', $options, $selected, $data); ?>

                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group custom-search-form">
                    <div class="form-group">
                        <?php

                        $data = array(

                            'class'         => 'form-control selectpicker',
                            'id'            => 'search_by_product_id',
                            'name'          => 'product_id',
                            'title'         => 'Choose a Product',
                            'data-live-search'  => TRUE
                        );

                        $options = array();

                        $selected = '';

                        ?>
                        <?= form_dropdown('search_by_product_id', $options, $selected, $data); ?>

                    </div>
                </div>
            </div>

			<div class="col-lg-4">
                <div class="form-group custom-search-form">
                    <?php
                    $data = array(

                        'class'         => 'form-control',
                        'name'          => 'quantity',
                        'type'          => 'number',
                        'id'            => 'search_by_quantity',
                        'min'           => '1',
                        'placeholder'   => 'Quantity'
                    );
                    ?>
                    <?= form_input($data); ?>
                </div>
            </div>

            <div class="col-lg-1">
                <a href="<?= base_url('admin/purchase_order') ?>" class="btn btn-warning" type="button">Reset <i class="fa fa-undo"></i></a>
            </div>

            </form>

            <?php if($layout_title !== 'Inventory'): ?>

			<!-- Add Product Button -->
			<div class="form-group text-left" style="margin-top: 55px">
				<a href="<?= site_url('admin/purchase_order/add_purchase_order_lookup'); ?>" class="btn btn-primary">Add New</a>
			</div>

            <?php endif; ?>


     		<?php if(!empty($purchase_orders)): ?>
			<div id="searched_results" style="<?= $layout_title === 'Inventory' ? 'margin-top: 55px' : ''; ?>">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table">
					<col width="150">
					<col width="100">
                    <col width="50">
                    <col width="80">
                    <col width="50">
                    <col width="50">
                    <col width="50">
                    <col width="50">
                    <thead>
				    	<tr>
				    		<th>Product Name</th>
				    		<th>Company</th>
				    		<th>Product Attribute</th>
				    		<th>Product Attribute Detail</th>
				    		<th>Quantity</th>
				    		<th>Purchase Price</th>
				    		<th>Sale Price</th>
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
					<?php foreach ($purchase_orders as $purchase_order):?>
						<tr>
							<td><?= ucwords($purchase_order['product_name']); ?></td>
				   			<td><?= ucwords($purchase_order['company_name']); ?></td>
				   			<td><?= ucwords($purchase_order['product_attribute_name']); ?></td>
				   			<?php if(strpos($purchase_order['product_attribute_value'], '#') !== FALSE): ?>
                                <td style="background: <?= $purchase_order['product_attribute_value'] ?>"></td>
				   		    	<?php else: ?>
                                <td>
                                    <?= ucwords($purchase_order['product_attribute_value']); ?>
                                </td>
				   			<?php endif; ?>
				   			<td><?= ucwords($purchase_order['quantity']); ?></td>
                            <td><?= ucwords($purchase_order['purchase_price']); ?></td>
                            <td><?= ucwords($purchase_order['sale_price']); ?></td>
				   			<td>
				   				<a title="Edit Product" href="<?= base_url('admin/purchase_order/edit_purchase_order_lookup') . '/' . ucwords($purchase_order['id']); ?>" class="btn btn-sm btn-success actions" style="display: inline-block;""><span class="fa fa-pencil-alt"></span></a>
<!--								<a title="Delete Product" href="javascript:void(0)" id="delete_--><?//= ucwords($purchase_order['id']); ?><!--" class="btn btn-sm btn-danger actions" style="display: inline-block;"><span class="fa fa-close"></span></a>-->
								<a title="Product Details" href="javascript:void(0)" id="view_<?= ucwords($purchase_order['id']); ?>" class="btn btn-sm btn-info actions" style="display: inline-block;"><span class="fa fa-eye "></span></a>
							</td>
				   		</tr>
					<?php endforeach; ?>
				    </tbody>
				</table>
			</div>
                <?= $links ?>
			</div>
			<?php else: ?>
	  			<h3 class="text-center">Sorry No Record Found!</h3>
	  		<?php endif; ?>
			</div>
		</div>
    </div>
</div> <!-- /.row  -->