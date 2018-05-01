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

			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<div class="form-group custom-search-form">
					<div class="form-group">
						<?php

						$data = array(

								'class'         => 'form-control selectpicker',
								'id'            => 'search_by_product_category',
								'title'         => 'Choose a Category'
						);

						$options[''] = '';

						foreach ($categories as $category)
						{
							$id = $category['id'];
							if(is_array($category['parent']) && $category['parent']['name'] != 'parent') {

								$options[$id] = ucwords( $category['parent']['name'] . ' - ' .  entity_decode($category['name']));
							}
							else {
								if($category['name'] == 'parent') {
									continue;
								}
								$options[$id] = ucwords(entity_decode($category['name']));
							}
						}

						$selected = $this->input->post('search_by_product_category');

						?>
						<?= form_dropdown('search_by_product_category', $options, $selected, $data); ?>

					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="input-group custom-search-form">
					<input type="text" id="search_by_product_name" name="search_by_product_name" class="form-control" placeholder="Product Name">
					<span class="input-group-btn">
					<button class="btn btn-default" id="product_search_btn" name="product_search_btn" type="button">
						<i class="fa fa-search"></i>
					</button>
				</span>
				</div>
			</div>

			<!-- Add Product Button -->
			<div class="form-group text-left" style="margin-top: 55px">
				<a href="<?= site_url('admin/product/add_product_lookup'); ?>" class="btn btn-primary">Add New</a>
				<a href="<?= site_url('admin/product/indice_product_elastic_search_lookup'); ?>" class="btn btn-warning pull-right">Indice Product Elastic Search</a>
			</div>

     		<?php if(!empty($products)): ?>
			<div id="searched_results">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table">
					<col width="20">
					<col width="220">
					<col width="30">
					<col width="100">
					<!--					<col width="70">-->
<!--					<col width="100">-->
					<!-- <col width="250"> -->
				    <thead>
				    	<tr>
				    		<th>Thumbnail</th>
				    		<th>Name</th>
				    		<th>Category</th>
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
					<?php foreach ($products as $product):?>
						<tr>
							<td style="padding: 0;width: 7% !important;text-align: center;"><img src="<?= PRODUCT_IMAGE  .  custom_echo($product, 'thumbnail', 'no_case_change'); ?>" alt=""></td>
							<td><?= custom_echo($product, 'name'); ?></td>
				   			<td><?= custom_echo($product, 'category'); ?></td>
				   			<td>
				   				<a title="Edit Product" href="<?= base_url('admin/product/edit_product_lookup/') . '/' . custom_echo($product, 'id'); ?>" class="btn btn-sm btn-success actions"><span class="fa fa-pencil-square-o"></span></a>
								<a title="Product Gallery" href="<?= base_url('admin/gallery/add_gallery_pics_lookup/') . '/' . custom_echo($product, 'id'); ?>" class="btn btn-sm btn-warning actions"><span class="fa fa-photo"></span></a>
								<a title="Edit Product Description" href="<?= base_url('admin/product/edit_product_description_lookup/') . '/' . custom_echo($product, 'id'); ?>" class="btn btn-sm btn-primary actions"><span class="fa fa-pencil-square"></span></a>
								<a title="Delete Product" href="javascript:void(0)" id="delete_<?= custom_echo($product, 'id'); ?>" class="btn btn-sm btn-danger actions"><span class="fa fa-close"></span></a>
								<a title="Product Description" href="javascript:void(0)" id="view_<?= custom_echo($product, 'id'); ?>" class="btn btn-sm btn-info actions"><span class="fa fa-comment-o "></span></a>
								<a title="Product Attributes" href="javascript:void(0)" id="details_<?= custom_echo($product, 'id'); ?>" class="btn btn-sm btn-info actions"><span class="fa fa-eye"></span></a>
							</td>
				   		</tr>
					<?php endforeach; ?>
				    </tbody>
				</table>
			</div>
			</div>
			<?= $links ?>
			<?php else: ?>
	  			<h3 class="text-center">Sorry No Record Found!</h3>
	  		<?php endif; ?>
			</div>
		</div>
    </div>
</div> <!-- /.row  -->