<?php
$this->load->view('templates/header'); 

$csrf = [
	'name'  => $this->security->get_csrf_token_name(),
	'token' => $this->security->get_csrf_hash()
	];
?>
<div class="alert alert-danger reset-error" style="display: none;"></div>
<div class="row">
	<div class="col-md-4 col-md-offset-4 well">
		<form action="<?php echo base_url('reset-password'); ?>" method="post" id="reset-form" data-parsley-validate>
			<legend>Enter your new password</legend>
			<div class="form-group">
				<label>New password</label>
				<input type="password" name="password" id="password" class="form-control" data-parsley-required>
			</div>
			<div class="form-group">
				<label>Confirm new password</label>
				<input type="password" name="password_confirm" class="form-control"  data-parsley-required data-parsley-equalto="#password">
			</div>
			<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['token']; ?>">
			<input type="hidden" name="r_token" value="<?php echo $token; ?>">
			<input type="hidden" name="reset_submit" value="reset_submit">

			<button type="submit" id="reset_submit" data-loading-text="Loading <i class='fa fa-spinner fa-pulse fa-fw'></i>" class="btn btn-primary btn-block btn-raised" autocomplete="off">
			  Reset password
			</button>
		</form>
		<div id="after-reset" style="display: none;">
			<div class="alert alert-success"></div>
			<div class="text-center">
				<p>Your password has been successfully changed.</p>
				<a href="<?php echo base_url('login'); ?>" class="btn btn-primary btn-raised">Login to your account</a>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('templates/footer'); ?>