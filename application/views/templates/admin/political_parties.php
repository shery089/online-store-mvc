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
			<!-- Add political_party Button -->
			<div class="form-group text-left">
				<a href="<?= site_url('admin/political_party/add_political_party_lookup'); ?>" class="btn btn-primary">Add New</a>
			</div>
     		<?php if(!empty($political_parties)): ?>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table">
					<col width="30">
					<col width="110">
					<col width="115">
					<col width="40">
					<col width="50">
					<col width="140">
					<col width="120">
					<!-- <col width="250"> -->
				    <thead>
				    	<tr>
				    		<th>Flag</th>
				    		<th>Name</th>
				    		<th>Leader</th>
				    		<th>Designation</th>
				    		<th>Founded Date</th>
				    		<th>Address</th>
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
				    <?php foreach ($political_parties as $political_party): ?>
				    	<tr>
				    		<?php 
				    			$address = ucwords(entity_decode($political_party['address']));
				   				$cut_address = (strlen($address) > 40) ? substr($address,0,35).'.....' : $address;

                                $name = ucwords(entity_decode($political_party['name']));
                                $name_len = strlen($name);
                                
                                if (strpos($name, '-') !== false) 
                                {
                                  if(substr_count($name, '-') > 1)
                                  {
                                    $name = mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, "UTF-8");

                                    $pos1 = strpos($name, '-') + 1;
                                    $pos2 = strpos($name, '-', $pos1 + strlen('-'));
                                    $length = abs($pos1 - $pos2);

                                    $between = strtolower(substr($name, $pos1, $length));
                                    $name = substr_replace($name, $between, $pos1, $length);
                                  }
                                  else
                                  {
                                    $name = mb_convert_case(mb_strtolower($name), MB_CASE_TITLE, "UTF-8");
                                  }
                                }
                                if (strpos($name, '(') !== false) 
                                {
                                  $name = explode('(', rtrim($name, ')'));
                                  $name = $name[0] . '(' . ucwords($name[1]) . ')';
                                } 	
								$founded_date = entity_decode($political_party['founded_date']);

				   			?>
				   			<td><img style="width:40px" src="<?= PARTY_IMAGE . entity_decode($political_party['flag']); ?>" alt="Party Flag"></td>
				   			<td><?= $name; ?></td>
				   			<td><?= ucwords(entity_decode($political_party['leader'])); ?></td>
				   			<td><?= ucwords(entity_decode($political_party['designation']['name'])); ?></td>
				   			<td><?= $founded_date === '0000' ? '' : $founded_date ?></td>
				   			<td><?= $cut_address ?></td>
				   			<td>
				   				<a href="<?= base_url('admin/political_party/edit_political_party_lookup/') . '/' . $political_party['id']; ?>" class="btn btn-sm btn-success actions"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="javascript:void(0)" id="delete_<?= $political_party['id']; ?>" class="btn btn-sm btn-danger actions"><span class="glyphicon glyphicon-remove-sign"></span></a>
				   				<a href="javascript:void(0)" id="view_<?= $political_party['id']; ?>" class="btn btn-sm btn-info actions"><span class="fa fa-eye"></span></a>
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