<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('templates/header');
?>

<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<div class="jumbotron">
		  <h1>Awesome!</h1>
		  <p>You have successfully been authenticated using Telesign two-factor authentication. It's a great time to be alive!</p>
		</div>
	</div>
</div>


<?php $this->load->view('templates/footer'); ?>