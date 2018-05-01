
     		<?php if(!empty($users)): ?>

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