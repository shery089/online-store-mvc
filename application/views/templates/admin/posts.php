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
				<a href="<?= site_url('admin/post/add_post_lookup'); ?>" class="btn btn-primary">Add New</a>
			</div>
     		<?php if(!empty($posts)): ?>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table">
					<col width="100">
					<col width="180">
					<col width="140">
					<col width="180">
					<col width="130">
					<col width="130">
					<col width="250">
					<!-- <col width="250"> -->
				    <thead>
				    	<tr>
				    		<th>Post</th>
				    		<th>Entity Name</th>
				    		<th>Entity</th>
	    		            <th>Posted By</th>
                            <th>Posted Date</th>
                            <th>Posted Time</th>
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
				    <?php foreach ($posts as $post): ?>
				    	<tr>
					    	<?php
				    	        /**
						         * [Post triming]
						         */

						        $post_desc = $post['post'];
                                $time_parts = explode(':', entity_decode($post['posted_time']));
                                $time_parts[2] = preg_replace('/[0-9]+/', '', $time_parts[2]);
                                $posted_time_24hrs = $time_parts[0] . ':' . $time_parts[1] . ' ' . $time_parts[2];
						        							
						    	$entity = entity_decode($post['entity']);
						    	$likes = entity_decode($post['likes']);
								$dislikes = entity_decode($post['dislikes']);
								$featured = entity_decode($post['featured']);
								$entity_id = entity_decode($post['entity_id']);
								$entity_name = $post['entity_name']['name'];
								
								$entity_name_len = strlen($entity_name);
            
					            if (strpos($entity_name, '-') !== false) 
					            {
					              if(substr_count($entity_name, '-') > 1)
					              {
					                $entity_name = mb_convert_case(mb_strtolower($entity_name), MB_CASE_TITLE, "UTF-8");

					                $pos1 = strpos($entity_name, '-') + 1;
					                $pos2 = strpos($entity_name, '-', $pos1 + strlen('-'));
					                $length = abs($pos1 - $pos2);

					                $between = strtolower(substr($entity_name, $pos1, $length));
					                $entity_name = substr_replace($entity_name, $between, $pos1, $length);
					              }
					              else
					              {
					                $entity_name = mb_convert_case(mb_strtolower($entity_name), MB_CASE_TITLE, "UTF-8");
					              }
					            }
					            if (strpos($entity_name, '(') !== false) 
					            {
					              $entity_name = explode('(', rtrim($entity_name, ')'));
					              $entity_name = $entity_name[0] . '(' . strtoupper($entity_name[1]) . ')';
					            }

						    ?>
				   			<td style="width: 250px !important"><?= entity_decode($post_desc); ?></td>
				   			<td><?= ucwords($entity_name) ?></td>
				   			<td><?= ucwords(str_replace('_', ' ', $entity)); ?></td>
				   			<td><?= ucwords(entity_decode($post['user_details']['full_name'])); ?></td>
				   			<td><?= entity_decode($post['posted_date']); ?></td>
				   			<td><?= $posted_time_24hrs; ?></td>
				   			<td>
				   				<!-- <a href="<?= base_url('admin/post/edit_post_lookup/') . '/' . $post['id']; ?>" class="btn btn-sm btn-success actions-inline"><span class="glyphicon glyphicon-pencil"></span></a> -->
								<a href="javascript:void(0)" id="delete_<?= $post['id'] . '_' . $post['entity']; ?>" class="btn btn-sm btn-danger actions-inline"><span class="glyphicon glyphicon-remove-sign"></span></a>
				   				<a href="javascript:void(0)" id="view_<?= $post['id'] . '_' . $post['entity']; ?>" class="btn btn-sm btn-info actions-inline"><span class="fa fa-eye"></span></a>
				   				<a href="javascript:void(0)" id="feature_<?= $post['id'] . '_' . $post['entity'] . '_' . $featured . '_' . $entity_id; ?>" class="btn btn-sm btn-primary actions-inline"><span class="<?= ($featured == 0) ? 'fa fa-plus' : 'fa fa-minus'; ?>"></span></a>
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