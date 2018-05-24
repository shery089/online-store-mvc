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
			<div class="col-lg-3"></div>
			<div class="col-lg-4">
				<input type="text" id="search_by_company_name" name="search_by_company_name" class="form-control" placeholder="Company Name">
			</div>
            <div class="col-lg-4">
				<div class="input-group custom-search-form">
					<input type="text" id="search_by_company_email" name="search_by_company_email" class="form-control" placeholder="Company Email">
					<span class="input-group-btn">
					<button class="btn btn-default" id="company_search_btn" name="company_search_btn" type="button">
						<i class="fa fa-search"></i>
					</button>
				</span>
				</div>
			</div>

			<div class="col-lg-1">
				<a href="<?= base_url('admin/company') ?>" class="btn btn-warning" type="button">Reset <i class="fa fa-undo"></i></a>
			</div>

			<img id="spinner" class="hide" src="<?= ADMIN_IMAGES_PATH . 'ajax_clock_small.gif' ?>">

			<!-- Add User Button -->
			<div class="form-group text-left" style="margin-top: 55px">
				<a href="<?= site_url('admin/company/add_company_lookup'); ?>" class="btn btn-primary">Add New</a>
			</div>

     		<?php if(!empty($companies)): ?>

			<div id="searched_results">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table">
					<col width="100">
					<col width="70">
					<col width="85">
					<col width="100">
					<col width="70">
					<col width="100">
					<!-- <col width="250"> -->
				    <thead>
				    	<tr>
				    		<th>Thumbnail</th>
				    		<th>Name</th>
				    		<th>Email</th>
				    		<th>Phone Number(s)</th>
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
					<?php foreach ($companies as $company):?>
						<tr>
                            <td style="padding: 0;width: 7% !important;text-align: center;"><img src="<?= COMPANY_IMAGE  .  $company['thumbnail']; ?>" alt="Company Image"></td>
				   			<td><?= ucwords($company['name']); ?></td>
				   			<td><?= $company['email']; ?></td>
				   			<td><?= ucwords($company['phone_number']); ?></td>
				   			<td>
				   				<a href="<?= base_url('admin/company/edit_company_lookup') . '/' . $company['id']; ?>" class="btn btn-sm btn-success actions"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="javascript:void(0)" id="delete_<?= $company['id']; ?>" class="btn btn-sm btn-danger actions"><span class="glyphicon glyphicon-remove-sign"></span></a>
				   				<a href="javascript:void(0)" id="view_<?= $company['id']; ?>" class="btn btn-sm btn-info actions"><span class="fa fa-eye"></span></a>
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
			</div><!--	searched_results	-->
			<div class="text-center">
				<img id="loading" style="display: none;" class="big-loader-img" src="<?= ADMIN_IMAGES_PATH . 'big_loading.gif' ?>" />
			</div>
			</div>
		</div>
    </div>
</div> <!-- /.row  -->