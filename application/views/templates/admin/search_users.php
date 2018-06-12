
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
							<td><?= ucwords($user['full_name']); ?></td>
							<td><?= ucwords($user['user_name']); ?></td>
							<td><?= $user['email']; ?></td>
							<td><?= $user['mobile_number']; ?></td>
							<td><?= ucwords($user['role']); ?></td>
				   			<td>
				   				<a href="<?= base_url('admin/user/edit_user_lookup') . '/' . $user['id']; ?>" class="btn btn-sm btn-success actions"><span class="fa fa-pencil-alt"></span></a>
								<a href="javascript:void(0)" id="delete_<?= $user['id']; ?>" class="btn btn-sm btn-danger actions"><span class="fa fa-window-close"></span></a>
				   				<a href="javascript:void(0)" id="view_<?= $user['id']; ?>" class="btn btn-sm btn-info actions"><span class="fa fa-eye"></span></a>
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