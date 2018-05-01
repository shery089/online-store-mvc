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
        <div class="col-lg-6">
			<!-- Add User Button -->
			<div class="form-group text-left">
				<a href="<?= site_url('admin/halqa_type/add_halqa_type_lookup'); ?>" class="btn btn-primary">Add New</a>
			</div>
     		<?php if(!empty($halqa_types)): ?>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table">
					<col width="120">
					<col width="65">
					<col width="85">
					<!-- <col width="250"> -->
				    <thead>
				    	<tr>
				    		<th>Name</th>
				    		<th>Abbreviation</th>
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
				    <?php foreach ($halqa_types as $halqa_type): ?>
				    	<tr>
				   			<td><?= ucwords(entity_decode($halqa_type['name'])); ?></td>
				   			<td><?= strtoupper(entity_decode($halqa_type['abbreviation'])); ?></td>
				   			<td>
				   				<a href="<?= base_url('admin/halqa_type/edit_halqa_type_lookup/') . '/' . $halqa_type['id']; ?>" class="btn btn-sm btn-success actions"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="javascript:void(0)" id="delete_<?= $halqa_type['id']; ?>" class="btn btn-sm btn-danger actions"><span class="glyphicon glyphicon-remove-sign"></span></a>
				   				<a href="javascript:void(0)" id="view_<?= $halqa_type['id']; ?>" class="btn btn-sm btn-info actions"><span class="fa fa-eye"></span></a>
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