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
			<!-- Add politician Button -->
			<div class="form-group text-left">
				<a href="<?= site_url('admin/politician/add_politician_lookup'); ?>" class="btn btn-primary">Add New</a>
			</div>
     		<?php if(!empty($politicians)): ?>
			<div class="table-responsive">
				<table class="table table-bordered table-striped table">
					<col width="100">
					<col width="40">
					<col width="30">
					<col width="30">
					<col width="80">
					<col width="80">
					<col width="110">
					<col width="115">
					<!-- <col width="250"> -->
				    <thead>
				    	<tr>
				    		<!-- <th>Flag</th> -->
				    		<th>Name</th>
				    		<th>Political Party</th>
				    		<th>Designation(s)</th>
				    		<th>Halqa(s)</th>
				    		<th>Likes</th>
				    		<th>Dislikes</th>
				    		<!-- <th>Votes in 2013</th> -->
				    		<th>Actions</th>
				    	</tr>
				    </thead>
				    <tbody>
				    <?php foreach ($politicians as $politician): ?>
			        <?php 
			        	// Political Party triming
			            $political_party = ucwords($politician['political_party_id']['name']);
			            $political_party_len = strlen($political_party);
			            
			            if (strpos($political_party, '-') !== false) 
			            {
			              if(substr_count($political_party, '-') > 1)
			              {
			                $political_party = mb_convert_case(mb_strtolower($political_party), MB_CASE_TITLE, "UTF-8");

			                $pos1 = strpos($political_party, '-') + 1;
			                $pos2 = strpos($political_party, '-', $pos1 + strlen('-'));
			                $length = abs($pos1 - $pos2);

			                $between = strtolower(substr($political_party, $pos1, $length));
			                $political_party = substr_replace($political_party, $between, $pos1, $length);
			              }
			              else
			              {
			                $political_party = mb_convert_case(mb_strtolower($political_party), MB_CASE_TITLE, "UTF-8");
			              }
			            }
			            if (strpos($political_party, '(') !== false) 
			            {
			            	$political_party = explode('(', rtrim($political_party, ')'));
			            	$political_party = $political_party[0] . '(' . ucwords($political_party[1]) . ')';
			        		$political_party = (strlen($political_party) > 32) ? substr($political_party,0,26).'.....' : $political_party;			            	
			            }

			        	// End Political Party triming
				        
				        // Politician
				        $politician_name = ucwords(entity_decode($politician['name']));
			        	$politician_name = (strlen($politician_name) > 32) ? substr($politician_name,0,26).'.....' : $politician_name;

			        	// Designations
			        	$designation = ucwords(entity_decode(implode(', ', array_column($politician['politician_details'], 'designation'))));
			        	$designation = (strlen($designation) > 35) ? substr($designation,0,29).'.....' : $designation;			        	
			        	
			        	// Halqa
			        	$halqa = strtoupper(entity_decode(implode(', ', array_column($politician['politician_details'], 'halqa'))));
			        	$halqa = (strlen($halqa) > 15) ? substr($halqa,0,11).'.....' : $halqa;			        	

			        ?>
				    	<tr>
				   			<!-- <td><img style="width:40px" src="<? PARTY_IMAGE . entity_decode($politician['flag']); ?>" alt="Party Flag"></td> -->
				   			<td><?= $politician_name ?></td>
				   			<td><?= $political_party; ?></td>
				   			<td><?= $designation; ?> </td>
				   			<td><?= $halqa; ?> </td>
				   			<td><?= ucwords(entity_decode($politician['likes'])); ?></td>
				   			<td><?= ucwords(entity_decode($politician['dislikes'])); ?></td>
				   			<td>
				   				<a href="<?= base_url('admin/politician/edit_politician_lookup/') . '/' . $politician['id']; ?>" class="btn btn-sm btn-success actions"><span class="glyphicon glyphicon-pencil"></span></a>
								<a href="javascript:void(0)" id="delete_<?= $politician['id']; ?>" class="btn btn-sm btn-danger actions"><span class="glyphicon glyphicon-remove-sign"></span></a>
				   				<a href="javascript:void(0)" id="view_<?= $politician['id']; ?>" class="btn btn-sm btn-info actions"><span class="fa fa-eye"></span></a>
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