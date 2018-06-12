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
        <div class="col-lg-7">
			<!-- Add User Button -->
			<div class="form-group text-left">
				<a href="<?= site_url('admin/product_attribute_detail/add_product_attribute_detail_lookup'); ?>" class="btn btn-primary">Add New</a>
			</div>
     		<?php if(!empty($product_attribute_details)): ?>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table">
					<col width="50">
					<col width="20">
					<col width="20">
					<!-- <col width="250"> -->
				    <thead>
				    	<tr>
				    		<th>Product Attribute</th>
				    		<th>Product Attribute Details</th>
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
				    <?php foreach ($product_attribute_details as $product_attribute_detail): ?>
						<?php $is_color = strlen(strpos($product_attribute_detail['product_attribute_value'], '#'));?>
						<tr>
				   			<td><?= ucwords(entity_decode($product_attribute_detail['product_attribute_name'])); ?></td>
							<?php $function = strlen($product_attribute_detail['product_attribute_value']) <= 3 ? 'strtoupper' : 'ucwords' ?>
				   			<td style="background-color: <?= $is_color > 0 ? (entity_decode($product_attribute_detail['product_attribute_value'])) : '' ?>"> <?= $is_color > 0 ? '' : $function(entity_decode($product_attribute_detail['product_attribute_value'])); ?></td>
				   			<td>
				   				<a href="<?= base_url('admin/product_attribute_detail/edit_product_attribute_detail_lookup/') . '/' . $product_attribute_detail['id']; ?>" class="btn btn-sm btn-success actions"><span class="fa fa-pencil-alt"></span></a>
								<a href="javascript:void(0)" id="delete_<?= $product_attribute_detail['id']; ?>" class="btn btn-sm btn-danger actions"><span class="fa fa-window-close"></span></a>
				   				<a href="javascript:void(0)" id="view_<?= $product_attribute_detail['id']; ?>" class="btn btn-sm btn-info actions"><span class="fa fa-eye"></span></a>
				   			</td>
				   		</tr>
					<?php endforeach; ?>
				    </tbody>
				</table>
			</div>
			<?= $links ?>
			<?php else: ?>
	  			<h3 class="text-center">Sorry No Record Found!</h3>
	  		<?php endif; ?>
			</div>
		</div>
    </div>
</div> <!-- /.row  -->