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
			<!-- Add Post Button -->
			<div class="form-group text-left">
				<a href="<?= site_url('admin/column/add_column_lookup'); ?>" class="btn btn-primary">Add New</a>
			</div>
     		<?php if(!empty($columns)): ?>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table">
					<col width="100">
					<col width="180">
					<col width="100">
					<col width="100">
					<col width="170">
					<col width="110">
					<col width="110">
					<col width="140">
					<!-- <col width="250"> -->
				    <thead>
				    	<tr>
				    		<th>Post</th>
				    		<th>Columnist</th>
				    		<th>Likes</th>
				    		<th>Dislikes</th>
	    		            <th>Posted By</th>
                            <th>Posted Date</th>
                            <th>Posted Time</th>
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
				    <?php foreach ($columns as $column): ?>
				    	<tr>
					    	<?php
				    	        /**
						         * [Column triming]
						         */

						        $column_desc = $column['column'];
                                $time_parts = explode(':', entity_decode($column['posted_time']));
                                $time_parts[2] = preg_replace('/[0-9]+/', '', $time_parts[2]);
                                $columned_time_24hrs = $time_parts[0] . ':' . $time_parts[1] . ' ' . $time_parts[2];
						        							
						    	$columnist_name = entity_decode($column['columnist_name']);
						    	$likes = entity_decode($column['likes']);
								$dislikes = entity_decode($column['dislikes']);

						    ?>
				   			<td><?= entity_decode($column_desc); ?></td>
				   			<td><?= ucwords($columnist_name) ?></td>
				   			<td><?= number_format($likes); ?></td>
				   			<td><?= number_format($dislikes); ?></td>
				   			<td><?= ucwords(entity_decode($column['user_name'])); ?></td>
				   			<td><?= entity_decode($column['posted_date']); ?></td>
				   			<td><?= $columned_time_24hrs; ?></td>
				   			<td>
				   				<!-- <a href="<?= base_url('admin/column/edit_column_lookup/') . '/' . $column['id']; ?>" class="btn btn-sm btn-success actions"><span class="glyphicon glyphicon-pencil"></span></a> -->
								<a href="javascript:void(0)" id="delete_<?= $column['id']; ?>" class="btn btn-sm btn-danger actions"><span class="glyphicon glyphicon-remove-sign"></span></a>
				   				<a href="javascript:void(0)" id="view_<?= $column['id']; ?>" class="btn btn-sm btn-info actions"><span class="fa fa-eye"></span></a>
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