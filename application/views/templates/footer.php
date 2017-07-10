	</div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.9/js/material.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.9/js/ripples.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.7.2/parsley.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	<script type="text/javascript">
		$.material.init();
		$("#country_select").select2({
			placeholder: "Select a country"
		});
		$("#country_select").on("select2:select", function(){
			var url = 'https://restcountries.eu/rest/v2/alpha/'+$("#country_select").val();
			$.ajax({
				type: 'get',
				url:  url,
				success: function(data){
					$("#phone-pref").val(data.callingCodes[0]);
				}
			});
		});

		$(".select2").click(function(){
			$(".select2-container--default .select2-selection--single").removeClass("select-error");
		});

		$("#email_input").blur(function(){
			if($(this).val() !== ''){
				$("#email-verify").html('<i class="fa fa-spinner fa-pulse fa-fw">');
				var data = {
					'email':$(this).val(),
					'csrf_test_name': $('input[name=csrf_test_name]').val()
				};
				var url = '<?php echo base_url('email-check');?>';
				$.ajax({
					type:   'post',
					data:   data,
					url:    url,
					success:function(data){
						var dataObj = JSON.parse(data);
						if(dataObj.error !== ''){
							$(".email_error").html(dataObj.error);
							$("#email-verify").hide();
						}else{
							$(".email_error").html('');
							$("#email-verify").html('<i class="fa fa-check"></i>').show();
						}
					}
				});
			}else{
				$(".email_error").html('');
				$("#email-verify").html('');
			}
		});

		$("#r_phone").blur(function(){
			var phone = $(this).val();
			if(phone.indexOf("0") == 0){
				$(this).val(phone.substr(1));
			}
			
			if($(this).val() !== ''){
				if($("#phone-pref").val() !== ''){
					$("#phone-verify").html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
					var data = {
						'phone':$("#phone-pref").val()+$(this).val(),
						'csrf_test_name': $('input[name=csrf_test_name]').val()
					};
					var url = '<?php echo base_url('phone-check');?>';
					$.ajax({
						type:   'post',
						data:   data,
						url:    url,
						success:function(data){
							if(data !== ''){
								var dataObj = JSON.parse(data);
								if(dataObj.error !== ''){
									$(".phone_error").html(dataObj.error);
									$("#phone-verify").hide();
								}else{
									$(".phone_error").html('');
									$("#phone-verify").html('<i class="fa fa-check"></i>').show();
								}
							}
						}
					});
				}
			}else{
				$(".phone_error").html('');
				$(".phone-verify").html('');
			}
		});

		$(document).on("submit", "#login_form", function(e){
			var url = $(this).attr('action');
			var data = $(this).serialize();
			if(data.indexOf('=&') > -1 || data.substr(data.length - 1) == '='){
			   //you've got empty values
			}else{
				$("#login_submit").button('loading');
				$.ajax({
					type: 	  "POST",
					url: 	  url,
					data: 	  data,
					success:  function(data){
						var dataObj = JSON.parse(data);
						if('error' in dataObj){
							$('.alert-success').hide();
							$('.login-error').html('<strong>Error:</strong> '+dataObj.error).fadeIn();
							window.setTimeout(function() {
							    $(".login-error").fadeOut().slideUp(500, function(){
							        $(this).hide(); 
							    });
							}, 5000);
							$("#login_submit").button('reset');
						}
						if('page' in dataObj){
							$('.login-box').hide();
							$('.alert').hide();
							$('.login-wrapper').html(dataObj.page).show();
						}
					},
					error:  function(jqxhr, error){
						$('.alert-success').hide();
						$('.login-error').html('An error occurred. Please try again.').fadeIn();
						window.setTimeout(function() {
							    $(".login-error").fadeOut().slideUp(500, function(){
							        $(this).hide(); 
							    });
							}, 5000);
						$("#login_submit").button('reset');
					}
				});
			}
			e.preventDefault();
		});

		$(document).on('submit', '#otp_form', function(e){
			if($("input[name=otp]").val() == ''){
				$(".otp-box .help-block1").html("This field is required");
			}else{
				$("#otp_submit").button("loading");
				var url = $('#otp_form').attr('action');
				$.ajax({
					type: 	  "POST",
					url: 	  url,
					data: 	  $("#otp_form").serialize(),
					success:  function(data){
						var dataObj = JSON.parse(data);
						if('error' in dataObj){
							$('.resend_error').html('<strong>Error:</strong> '+dataObj.error).fadeIn();
							window.setTimeout(function() {
								    $(".resend_error").fadeOut().slideUp(500, function(){
								        $(this).hide(); 
								    });
								}, 5000);
							$("#otp_submit").button('reset');
						}
						if('success' in dataObj){
							window.location.replace(dataObj.redirect);
						}
					},
					error:  function(jqxhr, error){
						$('.resend_error').html('An error occurred. Please try again.').fadeIn();
						window.setTimeout(function() {
								    $(".resend_error").fadeOut().slideUp(500, function(){
								        $(this).hide(); 
								    });
								}, 5000);
						$("#otp_submit").button('reset');
					} 
				});
			}
			e.preventDefault();
		});

		$(document).on('keyup', '#otp_code_input', function(e){
			$(".otp-box .help-block1").html("");
		});

		$("#register_form").submit(function(evt){
			var data = $(this).serialize();
			if($("#g-recaptcha-response").val() == '' || $("#country_select").val() == ''){
				if($("#country_select").val() == ''){
					$(".select2-container--default .select2-selection--single").addClass("select-error");
				}
				if($("#g-recaptcha-response").val() == ''){
					$("#g-recaptcha-error").html("Please verify you are human").show();
				}
			}else if(data.indexOf('=&') > -1 || data.substr(data.length - 1) == '='){
			   //you've got empty values
			   console.log("You've got empty values");
			}else if($(this).parsley().isValid()){
				$("#g-recaptcha-error").hide();
				$("#register_submit").button('loading');
				var url = $(this).attr('action');
				$.ajax({
					type:  	  'post',
					url:   	  url,
					data:  	  data,
					success:  function(data){
						var dataObj = JSON.parse(data);
						if('error' in dataObj){
							$('.reg-error').html(dataObj.error).fadeIn();
							window.setTimeout(function() {
							    $(".reg-error").fadeOut().slideUp(500, function(){
							        $(this).hide(); 
							    });
							}, 5000);
							$("#register_submit").button('reset');
						}else{
							$('.reg-error').hide();
							$('.reg-wrapper').hide();
							$('.reg-success').html(dataObj.success).fadeIn();
							window.setTimeout(function() {
							    $(".reg-success").fadeOut().slideUp(500, function(){
							        $(this).hide(); 
							    });
							}, 5000);
							$('.login-wrapper').show();
						}
					},
					error:    function(jqxhr, error){
						$('.alert').hide();
						$("#register_submit").button('reset');
						$("#resend_error").html('An error occurred. Please contact the system admin');
					}
				});
			}
			evt.preventDefault();
		});

		$(document).on('click', '#resend_code', function(){
			$("#resend-progress").show();
			var url = $(this).data('resend');
			$.ajax({
				type:    'get',
				url:     url,
				success: function(data){
					if(data == '1'){
						$('.alert').hide();
						$(".resend_success").html('Verification code sent').fadeIn();
						window.setTimeout(function() {
							    $(".resend_success").fadeOut().slideUp(500, function(){
							        $(this).hide(); 
							    });
							}, 3000);
						$("#resend-progress").hide();
					}else{
						$('.alert').hide();
						$(".resend_error").html('Sending verification code failed').fadeIn();
						window.setTimeout(function() {
							    $(".resend_error").fadeOut().slideUp(500, function(){
							        $(this).hide(); 
							    });
							}, 3000);
						$("#resend-progress").hide();
					}
				},
				error:   function(jqxhr, error){
					$('.alert').hide();
					$("#resend_error").html('An error occurred. Please contact the system admin');
					$("#resend-progress").hide();
				}
			});
		});

		$("#reset-email-form").submit(function(evt){
			var data = $(this).serialize();
			if(data.indexOf('=&') > -1 || data.substr(data.length - 1) == '='){
			   //you've got empty values
			}else{
				$("#reset_email_submit").button('loading');
				var url = $(this).attr('action');
				$('.reset-error').hide();
				$.ajax({
					type:    'post',
					url:     url,
					data:    data,
					success: function(data){
						var dataObj = JSON.parse(data);
						if('error' in dataObj){
							$('.reset-error').html(dataObj.error).fadeIn();
							window.setTimeout(function() {
							    $(".reset-error").fadeOut().slideUp(500, function(){
							        $(this).hide(); 
							    });
							}, 3000);
							$("#reset_email_submit").button('reset');
						}
						if('success' in dataObj){
							$('.reset-error').hide();
							$("#after-send .alert-success").html(dataObj.success);
							$("#reset-email-form").hide();
							$("#after-send").show();
						}
					},
					error:   function(jqxhr, error){
						$(".reset-error").html('An error occurred. Please contact the administrator');
						$("#reset_email_submit").button('reset');
					}
				});
			}
			evt.preventDefault();
		});

		$("#reset-form").submit(function(e){
			var data = $(this).serialize();
			if(data.indexOf('=&') > -1 || data.substr(data.length - 1) == '='){
			   //you've got empty values
			}else{
				$("#reset_submit").button('loading');
				var url  = $(this).attr('action');
				$.ajax({
					type:   'post',
					url:     url,
					data:    data,
					success: function(data){
						var dataObj = JSON.parse(data);
						if('error' in dataObj){
							$(".reset-error").html(dataObj.error).fadeIn();
							window.setTimeout(function() {
							    $(".reset-error").fadeOut().slideUp(500, function(){
							        $(this).hide(); 
							    });
							}, 3000);
							$("#reset_submit").button('reset');
						}
						if('success' in dataObj){
							$('.reset-error').hide();
							$("#after-reset .alert-success").html(dataObj.success);
							$("#reset-form").hide();
							$("#after-reset").show();
						}
					},
					error:   function(jqxhr, error){
						$(".reset-error").html('An error occurred. Please contact the administrator');
						$("#reset_submit").button('reset');
					}
				});
			}
			e.preventDefault();
		});
	</script>
  </body>
</html>