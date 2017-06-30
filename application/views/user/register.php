<?php 
	$this->load->view('templates/header');
	$csrf = [
	'name'  => $this->security->get_csrf_token_name(),
	'token' => $this->security->get_csrf_hash()
	];
?>

<div class="row">
	<div class="col-md-6 col-md-offset-3 well">
		<form action="" method="post" data-parsley-validate>
			<div class="form-group">
				<label>First name</label>
				<input type="text" name="firstname" class="form-control" value="<?php echo set_value('firstname'); ?>" data-parsley-required>
				<?php echo form_error('firstname','<span class="help-block">','</span>'); ?>
			</div>
			<div class="form-group">
				<label>Last name</label>
				<input type="text" name="lastname" class="form-control" value="<?php echo set_value('lastname'); ?>" data-parsley-required>
				<?php echo form_error('lastname','<span class="help-block">','</span>'); ?>
			</div>
			<div class="form-group">
				<label>Email</label>
				<input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" data-parsley-required data-parsley-type="email">
				<?php echo form_error('email','<span class="help-block">','</span>'); ?>
			</div>
			<div class="form-group">
				<label>Phone number</label>
				<input type="text" name="phone" class="form-control" value="<?php echo set_value('phone'); ?>" data-parsley-required>
				<?php echo form_error('phone','<span class="help-block">','</span>'); ?>
			</div>
			<div class="form-group">
				<label>Password</label>
				<input type="password" name="password" class="form-control" data-parsley-required>
				<?php echo form_error('password','<span class="help-block">','</span>'); ?>
			</div>
			<div class="form-group">
				<label>Confirm password</label>
				<input type="password" name="password_confirm" class="form-control" data-parsley-required>
				<?php echo form_error('password_confirm','<span class="help-block">','</span>'); ?>
			</div>
			<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['token']; ?>">
			<input type="submit" name="register_submit" value="Register" class="btn btn-primary btn-raised btn-block">
		</form>
		<p class="footInfo">Already have an account? <a href="<?php echo base_url('index.php/login'); ?>">Login here</a></p>
	</div>
</div>

<?php $this->load->view('templates/footer'); ?>