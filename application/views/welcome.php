<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/header');
?>

<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="jumbotron">
		  <h1>Welcome!</h1>
		  <p>This is a test application that uses Telesign 2-factor authentication </p>
		  <p>
		  	<a class="btn btn-primary btn-raised" href="<?php echo base_url('index.php/login'); ?>" role="button">Log in</a>
		  	<a class="btn btn-primary btn-raised" href="<?php echo base_url('index.php/register'); ?>" role="button">Register</a>
		  </p>
		</div>
	</div>
</div>


<?php $this->load->view('templates/footer'); ?>


<!--
Ensure unique email
Add parsley validation to register
Change ajax url to use form action
-->