 <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
   			<?php if($this->session->flashdata('success_message') OR $this->session->flashdata('delete_message')
                    OR $this->session->flashdata('error_message')): ?>
   				<p class="alert <?= ($this->session->flashdata('delete_message') OR $this->session->flashdata('delete_message'))
                    ? 'alert-danger' : 'alert-success' ?> alert-dismissable fade in text-center top-height">
   				<?php 
   					if($this->session->flashdata('success_message')) 
                    {
                        echo $this->session->flashdata('success_message');
                    }
                    elseif($this->session->flashdata('delete_message'))
                    {
                        echo $this->session->flashdata('delete_message');
                    }
                    elseif($this->session->flashdata('error_message'))
                    {
                        echo $this->session->flashdata('error_message');
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
            </div>
            <div class="col-lg-4">
            </div>

			<div class="col-lg-4"><!--
                <div class="form-group custom-search-form">
                    <?php
/*                    $data = array(

                        'class'         => 'form-control',
                        'name'          => 'quantity',
                        'type'          => 'number',
                        'id'            => 'search_by_quantity',
                        'min'           => '1',
                        'placeholder'   => 'Quantity'
                    );
                    */?>
                    <?/*= form_input($data); */?>
                </div>-->
            </div>

            <div class="col-lg-1">
                <a href="<?= base_url('admin/sales_order') ?>" class="btn btn-warning" type="button">Reset <i class="fa fa-undo"></i></a>
            </div>

            </form>

            <?php if($layout_title !== 'Inventory'): ?>

			<!-- Add Product Button -->
			<div class="form-group text-left" style="margin-top: 55px">
				<a href="<?= site_url('admin/sales_order/add_sales_order_lookup'); ?>" class="btn btn-primary">Add New</a>
			</div>

            <?php endif; ?>


     		<?php if(!empty($sales_orders)): ?>
			<div id="searched_results" style="<?= $layout_title === 'Inventory' ? 'margin-top: 55px' : ''; ?>">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table">
					<col width="50">
					<col width="100">
                    <col width="100">
                    <col width="100">
                    <col width="100">
                    <col width="100">
                    <col width="50">
                    <col width="50">
                    <thead>
				    	<tr>
				    		<th>Invoice Id</th>
				    		<th>Created Date</th>
                            <th>Created By</th>
                            <th>Updated Date</th>
                            <th>Last Updated By</th>
				    		<th>Total</th>
				    		<th>Total Discount</th>
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
					<?php foreach ($sales_orders as $sales_order):?>
						<tr>
							<td><?= ucwords($sales_order['id']); ?></td>
				   			<td><?= ucwords($sales_order['created_date']); ?></td>
				   			<td><?= ucwords($sales_order['created_by']); ?></td>
                            <td><?= ucwords($sales_order['updated_date']); ?></td>
                            <td><?= ucwords($sales_order['updated_by']); ?></td>
                            <td><?= ucwords($sales_order['total']); ?></td>
				   			<td><?= ucwords($sales_order['total_discount']); ?></td>
				   			<td>
				   				<a title="Edit Product" href="<?= base_url('admin/' . $entity . '/edit_' . $entity . '_lookup') . '/' . ucwords($sales_order['id']); ?>" class="btn btn-sm btn-success actions" style="display: inline-block;""><span class="fa fa-pencil-alt"></span></a>
<!--								<a title="Delete Product" href="javascript:void(0)" id="delete_--><?//= ucwords($sales_order['id']); ?><!--" class="btn btn-sm btn-danger actions" style="display: inline-block;"><span class="fa fa-window-close"></span></a>-->
<!--								<a title="Product Details" href="javascript:void(0)" id="view_--><?//= ucwords($sales_order['id']); ?><!--" class="btn btn-sm btn-info actions" style="display: inline-block;"><span class="fa fa-eye "></span></a>-->
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