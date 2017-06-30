
  	</div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.9/js/material.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.9/js/ripples.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.7.2/parsley.min.js"></script>

	<script type="text/javascript">
		$.material.init();

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
						console.log(data);
						var dataObj = JSON.parse(data);
						if('error' in dataObj){
							$('.login-error').html('<strong>Error:</strong> '+dataObj.error).show();
							$("#login_submit").button('reset');
						}
						if('page' in dataObj){
							$('.login-box').hide();
							$('.login-wrapper').html(dataObj.page).show();
						}
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
				} 
			});
			e.preventDefault();
		});
	</script>
  </body>
</html>