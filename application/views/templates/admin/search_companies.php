
     		<?php if(!empty($companies)): ?>

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