<?php?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Material Design fonts -->
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/0.5.9/css/bootstrap-material-design.min.css">

    <style type="text/css">
        .bold{
            font-weight: 400;
            font-size: 16px;
        }
        .margin-30{
            margin-bottom: 30px;
        }
    </style>
  </head>
  <body>
      <div class="container">
          <div class="row">
              <div class="col-md-6 col-md-offset-3">
                  <div style="background-color: #fff;padding: 19px;margin-bottom: 20px;border: 1px solid #eee;border-radius: 2px;text-align: center;">
                      <div style="text-align: center;"><h2>Telesign Auth App</h2></div>
                      <div style="font-weight: 400;font-size: 16px;">
                          <div style="margin-bottom: 30px;">Hello <?php echo $username; ?>,</div>
                          <div style="margin-bottom: 30px;">A request has been received to change the password for your account.</div>
                          <div style="text-align: center;margin-bottom: 30px;"><a href="<?php echo $link; ?>" style="background-color: #009688;color: rgba(255,255,255,.84);border: none;border-radius: 2px;position: relative;padding: 8px 30px;margin: 10px 1px;font-size: 14px;font-weight: 500;text-transform: uppercase;letter-spacing: 0;will-change: box-shadow,transform;transition: box-shadow .2s cubic-bezier(.4,0,1,1),background-color .2s cubic-bezier(.4,0,.2,1),color .2s cubic-bezier(.4,0,.2,1);outline: 0;cursor: pointer;text-decoration: none;display: inline-block;line-height: 1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;touch-action: manipulation;">Reset Password</a></div>
                          <div style="margin-bottom: 30px;">If you did not initiate this request, please ignore this email.</div>
                          <div style="margin-bottom: 30px;">Thank you.</div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </body>
</html>