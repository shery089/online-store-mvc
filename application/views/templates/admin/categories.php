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
			<!-- Add category Button -->
			<div class="form-group text-left">
				<a href="<?= site_url('admin/category/add_category_lookup'); ?>" class="btn btn-primary">Add New</a>
			</div>
     		<?php if(!empty($categories)): ?>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table">
					<col width="100">
					<col width="180">
					<col width="50">
					<col width="60">
					<col width="60">
					<col width="90">
					<!-- <col width="250"> -->
				    <thead>
				    	<tr>
				    		<th>Image</th>
				    		<th>Name</th>
				    		<th>Parent</th>
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
				    <?php foreach ($categories as $category): ?>
			        <?php 
				        $category_name = ucwords(entity_decode($category['name']));

				        $parent = $category_name != 'Parent' ? $category['parent']['name'] : '';

				        $image = entity_decode($category['profile_image']);
			        ?>
				    	<tr>
				   			<td><img style="width:25px" src="<?= CATEGORY_IMAGE . $image ?>" alt="Category Image"></td>
				   			<td><?= $category_name ?></td>
				   			<td><?= ucwords($parent) ?></td>
				   			<td>
				   				<a href="<?= base_url('admin/category/edit_category_lookup/') . '/' . $category['id']; ?>" class="btn btn-sm btn-success actions"><span class="fa fa-pencil-alt"></span></a>
								<a href="javascript:void(0)" id="delete_<?= $category['id']; ?>" class="btn btn-sm btn-danger actions"><span class="fa fa-window-close"></span></a>
				   				<a href="javascript:void(0)" id="view_<?= $category['id']; ?>" class="btn btn-sm btn-info actions"><span class="fa fa-eye"></span></a>
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