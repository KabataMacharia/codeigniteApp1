<?php 
	$this->load->view('templates/header');
	$csrf = [
	'name'  => $this->security->get_csrf_token_name(),
	'token' => $this->security->get_csrf_hash()
	];
?>
<div class="after-reg-wrapper"></div>
<div class="row reg-wrapper">
	<div class="col-md-6 col-md-offset-3">
		<div class="well">
			<div class="alert alert-danger reg-error" style="display: none;"></div>
			<legend>Create an account</legend>
			<form action="<?php echo base_url("register"); ?>" method="post" id="register_form" data-parsley-validate>
				<div class="form-group">
					<label>First name</label>
					<input type="text" name="firstname" class="form-control" value="<?php echo set_value('firstname'); ?>" data-parsley-required>
					<?php echo form_error('firstname','<span class="help-block1">','</span>'); ?>
				</div>
				<div class="form-group">
					<label>Last name</label>
					<input type="text" name="lastname" class="form-control" value="<?php echo set_value('lastname'); ?>" data-parsley-required>
					<?php echo form_error('lastname','<span class="help-block1">','</span>'); ?>
				</div>
				<div class="form-group">
					<label>Email<span id="email-verify" style="margin-left: 10px;"></i></span></label>
					<input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" data-parsley-required data-parsley-type="email" id="email_input">
					<span class="help-block1 email_error"></span>
					<?php echo form_error('email','<span class="help-block1">','</span>'); ?>
				</div>
				<div class="form-group">
					<label>Country</label>
					<select id="country_select" class="form-control" name="country">
						<option></option>
						<?php
						foreach($countries as $country){
						?>
							<option value="<?php echo $country->alpha2Code; ?>"><?php echo $country->name; ?></option>
						<?php
						}
						?>
					</select>
					<?php echo form_error('country','<span class="help-block1">','</span>'); ?>
				</div>
				<div class="form-group">
					<label>Phone number<span id="phone-verify" style="margin-left: 10px;"></span></label>
					<div class="row">
						<div class="col-xs-3">
							<input type="text" name="phone_pref" id="phone-pref" class="form-control" value="<?php echo set_value('phone-pref'); ?>" readonly>
						</div>
						<div class="col-xs-9">
							<input type="text" id="r_phone" name="phone" class="form-control" value="<?php echo set_value('phone'); ?>" placeholder="712345678" data-parsley-required>
							<span class="help-block1 phone_error"></span>
						</div>
					</div>
					<?php echo form_error('phone','<span class="help-block1">','</span>'); ?>
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" name="password" id="password" class="form-control" data-parsley-required data-parsley-minlength="6">
					<?php echo form_error('password','<span class="help-block1">','</span>'); ?>
				</div>
				<div class="form-group">
					<label>Confirm password</label>
					<input type="password" name="password_confirm" class="form-control" data-parsley-required data-parsley-equalto="#password" data-parsley-equalto-message="Both passwords should be the same">
					<?php echo form_error('password_confirm','<span class="help-block1">','</span>'); ?>
				</div>
				<div class="g-recaptcha" class="form-group" data-sitekey="6Lce-icUAAAAANIBmHxAe3k499rnyKe7DIBO4AbC"></div>
				<div class="g-errors-list" id="g-recaptcha-error"></div>
				<input type="hidden" name="<?php echo $csrf['name']; ?>" value="<?php echo $csrf['token']; ?>">
				<input type="hidden" name="register_submit" value="register_submit">
			
				<button type="submit" id="register_submit" data-loading-text="Register <i class='fa fa-spinner fa-pulse fa-fw'></i>" class="btn btn-primary btn-block btn-raised" autocomplete="off">
				 Register
				</button>
			</form>
			<p class="text-center">Already have an account? <a href="<?php echo base_url('login'); ?>">Login here</a></p>
		</div>
	</div>
</div>

<div class="row login-wrapper" style="display:none;">
	<div class="col-md-4 col-md-offset-4 login-box">
		<div class="well">
			<div class="alert alert-success reg-success" style="display: none;"></div>
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

<?php $this->load->view('templates/footer'); ?>