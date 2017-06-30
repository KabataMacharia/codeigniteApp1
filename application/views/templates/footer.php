
  	</div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.9/js/material.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.9/js/ripples.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.7.2/parsley.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

	<script type="text/javascript">
		$.material.init();
		$("#country_select").select2({
			placeholder: "Select a state"
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

		$("#country_select").on("select2:unselect", function(){
			$("#phone-pref").val('');
		});

		$(".select2").click(function(){
			$(".select2-container--default .select2-selection--single").removeClass("select-error");
		});

		$("#login_form").submit(function(e){
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
							$('.login-error').html('<strong>Error:</strong> '+dataObj.error).show();
							$("#login_submit").button('reset');
						}
						if('page' in dataObj){
							$('.login-box').hide();
							$('.login-wrapper').html(dataObj.page).show();
						}
					},
					error:  function(jqxhr, error){
						$('.login-error').html('An error occurred. Please try again.').show();
						$("#login_submit").button('reset');
					}
				});
			}
			e.preventDefault();
		});

		$(document).on('submit', '#otp_form', function(e){
			var url = $('#otp_form').attr('action');
			$.ajax({
				type: 	  "POST",
				url: 	  url,
				data: 	  $("#otp_form").serialize(),
				success:  function(data){
					var dataObj = JSON.parse(data);
					if('error' in dataObj){
						$('.login-error').html('<strong>Error:</strong> '+dataObj.error).show();
						$("#login_submit").button('reset');
					}
					if('success' in dataObj){
						window.location.replace(dataObj.redirect);
					}
				},
				error:  function(jqxhr, error){
					$('.login-error').html('An error occurred. Please try again.').show();
					$("#login_submit").button('reset');
				} 
			});
			e.preventDefault();
		});

		$("#register_form").submit(function(e){
			if($("#country_select").val() == ''){
				$(".select2-container--default .select2-selection--single").addClass("select-error");
				e.preventDefault();
			}
		});

		$(document).on('click', '#resend_code', function(){
			$("#resend-progress").show();
			var url = $(this).data('resend');
			$.ajax({
				type:    'get',
				url:     url,
				success: function(data){
					if(data == '1'){
						$(".resend_success").html('Verification code sent').show();
						$("#resend-progress").hide();
					}else{
						$(".resend_error").html('Sending verification code failed').show();
						$("#resend-progress").hide();
					}
				},
				error:   function(jqxhr, error){
					$("#resend_error").html('An error occurred. Please contact the system admin');
					$("#resend-progress").hide();
				}
			});
		});
	</script>
  </body>
</html>