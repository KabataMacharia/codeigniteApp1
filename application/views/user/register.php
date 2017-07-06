<?php 
	$this->load->view('templates/header');
	$csrf = [
	'name'  => $this->security->get_csrf_token_name(),
	'token' => $this->security->get_csrf_hash()
	];
?>
<div class="alert alert-danger reg-error" style="display: none;"></div>
<div class="after-reg-wrapper"></div>
<div class="row reg-wrapper">
	<div class="col-md-6 col-md-offset-3">
		<div class="well">
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
					<label>Email</label>
					<input type="email" name="email" class="form-control" value="<?php echo set_value('email'); ?>" data-parsley-required data-parsley-type="email">
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
					<label>Phone number</label>
					<div class="row">
						<div class="col-xs-3">
							<input type="text" name="phone_pref" id="phone-pref" class="form-control" value="<?php echo set_value('phone-pref'); ?>" readonly>
						</div>
						<div class="col-xs-9">
							<input type="text" name="phone" class="form-control" value="<?php echo set_value('phone'); ?>" data-parsley-required>
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

<?php $this->load->view('templates/footer'); ?>