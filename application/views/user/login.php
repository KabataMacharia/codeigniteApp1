<?php
if(!$this->session->flashdata('new_reg')){
	$this->load->view('templates/header'); 
} 

$csrf = [
	'name'  => $this->security->get_csrf_token_name(),
	'token' => $this->security->get_csrf_hash()
	];
?>

<div class="row login-wrapper">
	<div class="col-md-4 col-md-offset-4 login-box">
		<div class="well">
			<?php if(isset($success)){ ?>
				<div class="alert alert-success"><?php echo $success ?></div>
			<?php } ?>
			<div class="alert alert-success login-success" style="display: none;"></div>
			<div class="alert alert-danger login-error" style="display: none;"></div>
			<form action="<?php echo base_url('login'); ?>" id="login_form" method="post" data-parsley-validate>
				<legend>Log in to your account</legend>
				<div class="form-group">
					<label>Email</label>
					<input type="text" name="username" class="form-control" data-parsley-required>
					<?php echo form_error('username','<span class="help-block">','</span>'); ?>
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" name="password" class="form-control" data-parsley-required>
					<?php echo form_error('password','<span class="help-block">','</span>'); ?>
				</div>
				<div class="form-group">
				  <div class="checkbox">
				    <label>
				      <input type="checkbox" name="remember-me"> Remember me
				    </label>
				  </div>
				  <p class="help-block">Do not use this on a public computer</p>
				</div>
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['token']; ?>">
				<input type="hidden" name="login_submit" value="login_submit">
				
				<button type="submit" id="login_submit" data-loading-text="Logging you in <i class='fa fa-spinner fa-pulse fa-fw'></i>" class="btn btn-primary btn-block btn-raised" autocomplete="off">
				  Log in
				</button>
			</form>
			<div class="text-center"><a href="<?php echo base_url('forgot-password')?>">Forgot password?</a></div>
		</div>
	</div>
</div>

<?php
if(!$this->session->flashdata('new_reg')){
	$this->load->view('templates/footer'); 
}?>