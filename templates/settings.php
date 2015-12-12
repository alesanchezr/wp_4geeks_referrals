<div class="wrap">
    <div class="container">
	 	<div class='row'>
		    <form class="form-inline" method="post" action="options.php"> 
		        <?php @settings_fields('WP_Geeks_Referrals-group'); ?>
		        <?php @do_settings_fields('WP_Geeks_Referrals-group'); ?>
		        <?php do_settings_sections('WP_Geeks_Referrals'); ?>
		        <?php @submit_button(); ?>
		    </form>
		</div>
     	<div class='row text-right'>
     		<a class="btn btn-primary" data-toggle="modal" data-target="#myModal">Add new code</a>
        </div>

	      <div class="row">
				<table class="table table-striped"> 
					<thead> 
						<tr> 
							<th>#</th> 
							<th>Time</th> 
							<th>External ID</th> 
							<th>Referred by</th> 
							<th>Hash</th> 
							<th>Other</th> 
							<th></th> 
						</tr> 
					</thead> 
					<tbody> 
	 					<?php foreach($view_data["referrals"] as $ref){ ?>
						<tr> 
							<th scope="row">1</th> 
							<td><?php echo $ref->time; ?></td> 
							<td><?php echo $ref->external_id; ?></td> 
							<td><?php echo (isset($ref->referred_by)) ? $ref->referred_by : "None"; ?></td> 
							<td class="user-hash"><a href="<?php echo "http://4geeksacademy.com/?rhs=".$ref->user_hash; ?>" target="_blank"><?php echo $ref->user_hash; ?></a></td> 
							<td><?php echo $ref->other; ?></td> 
							<td><a data-referralid="<?php echo $ref->id; ?>" href="#" class="delete-referral-btn btn btn-sm btn-danger">delete</a></td> 
						</tr> 
	      				<?php } ?>
					</tbody> 
				</table>
	      </div>
     </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Referral information</h4>
      </div>
      <div class="modal-body">
			<form class="form-horizontal">
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">External ID</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="external_id" name="external_id" placeholder="External Id">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputPassword3" class="col-sm-2 control-label">Reffered By</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="referred_by" name="referred_by" placeholder="Referral Id">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="inputPassword3" class="col-sm-2 control-label">Other details</label>
			    <div class="col-sm-10">
			      <input type="text" class="form-control" id="other" name="other" placeholder="Other details">
			    </div>
			  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="save-referral-btn" class="btn btn-primary">Save referral</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" >
	jQuery(document).ready(function($) {

		jQuery('#save-referral-btn').click(function(){
			var data = {
				'action': 'save-geeks-referrals',
				'external_id': jQuery('#external_id').val(),
				'referred_by': jQuery('#referred_by').val(),
				'other': jQuery('#other').val()
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				if(!response || response=="" || response.code!=200) alert('Error procesing the request.');
				else location.reload();
			});

			return false;
		});
		jQuery('.delete-referral-btn').click(function(){
			var data = {
				'action': 'delete-geeks-referrals',
				'id': jQuery(this).data('referralid')
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				if(!response || response=="" || response.code!=200) alert('Error procesing the request.');
				else location.reload();
			});

			return false;
		});

	});
</script>