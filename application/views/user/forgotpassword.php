<?php
$this->load->view('templates/header');

$csrf = [
	'name'  => $this->security->get_csrf_token_name(),
	'token' => $this->security->get_csrf_hash()
	];
?>

<div class="row reset-email-wrapper">
	<div class="col-md-6 col-md-offset-3">
		<div class="well">
			<div class="alert alert-success reset-success" style="display: none;"></div>
			<div class="alert alert-danger reset-error" style="display: none;"></div>
			<form action="<?php echo base_url('forgot-password') ?>" method="post" id="reset-email-form" data-parsley-validate>
				<legend>Enter your email address to reset your account password</legend>
				<div class="form-group">
					<label>Email</label>
					<input type="text" name="reset_email" class="form-control" data-parsley-required>
				</div>
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['token']; ?>">
				<input type="hidden" name="reset_email_submit" value="reset_email_submit">

				<button type="submit" id="reset_email_submit" data-loading-text="Sending email... <i class='fa fa-spinner fa-pulse fa-fw'></i>" class="btn btn-primary btn-block btn-raised btn-disabled" autocomplete="off">
				  Submit
				</button>
			</form>
			<div id="after-send" style="display: none;">
				<div class="alert alert-success"></div>
				<div class="text-center">
					<p>An email has been sent to your email address. Access it to reset your password.</p>
					<a href="<?php echo base_url('login'); ?>" class="btn btn-primary btn-raised">Login to your account</a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$this->load->view('templates/footer');
?>