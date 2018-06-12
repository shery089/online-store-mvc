 <div id="page-wrapper">
    <div class="row">
        <?= $tabs; ?>
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
			<!-- Add User Button -->
			<div class="form-group text-left">
<!--				<a href="--><?//= site_url('admin/gallery/add_gallery_pics_lookup'); ?><!--" class="btn btn-primary">Add New</a>-->
			</div>
     		<?php if(!empty($pictures)): ?>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table">
					<col width="40">
					<col width="430">
					<col width="150">
					<col width="102">
					<col width="73">
				    <thead>
				    	<tr>
				    		<th>Thumbnail</th>
				    		<th>Title</th>
				    		<th>Uploaded By</th>
				    		<th>Uploaded Date</th>
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
				    <?php foreach ($pictures as $picture): ?>
				    	<tr>
				    	<?php $status = $picture['status']; ?>
				   			<td><img src="<?= GALLERY_IMAGE . $picture['thumbnail'] ?>" alt=""></td>
				   			<td><?= ucwords(entity_decode($picture['title'])); ?></td>
				   			<td><?= ucwords(entity_decode($picture['full_name'])); ?></td>
				   			<td><?= entity_decode($picture['uploaded_date']); ?></td>
				   			<td style="display: inline-grid; margin-left: 10px;margin-top: 10px;">
				   				<a href="<?= base_url('admin/gallery/edit_gallery_pics_lookup') . '/' . $picture['product_id'] . '/' . $picture['id']; ?>" class="btn btn-sm btn-success actions"><span class="fa fa-pencil-alt"></span></a>
								<a href="javascript:void(0)" id="delete_<?= $picture['id']; ?>" class="btn btn-sm btn-danger actions"><span class="fa fa-trash"></span></a>
				   				<a href="javascript:void(0)" id="view_<?= $picture['id']; ?>" class="btn btn-sm btn-info actions"><span class="fa fa-eye"></span></a>
				   				<a href="javascript:void(0)" id="feature_<?= $picture['id'] . '_' . $status ?>" class="btn btn-sm btn-primary actions"><span class="<?= $status == 0 ? 'fa fa-plus' : 'fa fa-minus'; ?>"></span></a>
				   			</td>
				   		</tr>
					<?php endforeach; ?>
				    </tbody>
				</table>
			</div>
			<?php else: ?>
	  			<h3 class="text-center">Sorry No Record Found!</h3>
	  		<?php endif; ?>
			</div>
		</div>
    </div>
</div> <!-- /.row  -->