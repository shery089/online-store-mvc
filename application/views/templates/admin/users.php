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
<!---->
<!--			<div class="col-lg-4">-->
<!--				<div class="form-group custom-search-form">-->
<!--					<input type="text" id="search_doctor_name" name="search_doctor_name" class="form-control" placeholder="Doctor Name">-->
<!--				</div>-->
<!--			</div>-->
			<div class="col-lg-4"></div>
			<div class="col-lg-4">
				<div class="form-group custom-search-form">
					<div class="form-group">
						<?php

						$data = array(

							'class'         => 'form-control selectpicker',
							'id'            => 'search_by_user_role',
							'title'         => 'Choose a Role'
						);

						$options[''] = '';

						foreach ($roles as $role)
						{
							$id = $role['id'];
							$options[$id] = ucwords(entity_decode($role['name']));
						}

						$selected = $this->input->post('search_by_user_role');

						?>
						<?= form_dropdown('search_by_user_role', $options, $selected, $data); ?>

					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="input-group custom-search-form">
					<input type="text" id="search_by_user_full_name" name="search_by_user_full_name" class="form-control" placeholder="Full Name">
					<span class="input-group-btn">
					<button class="btn btn-default" id="user_search_btn" name="user_search_btn" type="button">
						<i class="fa fa-search"></i>
					</button>
				</span>
				</div>
			</div>

			<!-- Add User Button -->
			<div class="form-group text-left" style="margin-top: 55px">
				<a href="<?= site_url('admin/user/add_user_lookup'); ?>" class="btn btn-primary">Add New</a>
				<a href="<?= site_url('admin/user/indice_user_elastic_search_lookup'); ?>" class="btn btn-warning pull-right">Indice User Elastic Search</a>
			</div>

     		<?php if(!empty($users)): ?>

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
				    		<th>Full Name</th>
				    		<th>User Name</th>
				    		<th>Email</th>
				    		<th>Mobile Number</th>
				    		<th>Role</th>
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
					<?php foreach ($users as $user):?>
						<tr>
				   			<td><?= custom_echo($user, 'full_name'); ?></td>
				   			<td><?= custom_echo($user, 'user_name'); ?></td>
				   			<td><?= custom_echo($user, 'email'); ?></td>
				   			<td><?= custom_echo($user, 'mobile_number'); ?></td>
				   			<td><?= custom_echo($user, 'role'); ?></td>
				   			<td>
				   				<a href="<?= base_url('admin/user/edit_user_lookup/') . '/' . custom_echo($user, 'id'); ?>" class="btn btn-sm btn-success actions"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="javascript:void(0)" id="delete_<?= custom_echo($user, 'id'); ?>" class="btn btn-sm btn-danger actions"><span class="glyphicon glyphicon-remove-sign"></span></a>
				   				<a href="javascript:void(0)" id="view_<?= custom_echo($user, 'id'); ?>" class="btn btn-sm btn-info actions"><span class="fa fa-eye"></span></a>
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
			</div>
		</div>
    </div>
</div> <!-- /.row  -->