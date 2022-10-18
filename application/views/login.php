<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$bu=base_url()."adminlte310";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POSM | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $bu;?>/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap --
  <link rel="stylesheet" href="<?php echo $bu;?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css"-->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $bu;?>/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logox" style="text-align:center;">
	<p><img src="<?php echo $bu;?>/my/img/logo.png" /></p>
    
    <!--b style="font-size:4rem; line-height:40px;">ODS</b><br />
	<span style="font-size:90%;">OMNICOM DATA SYSTEM</span-->
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
	  <p class="login-box-msg">Sign in to start your session</p>

      <form id="quickForm" action="<?php echo base_url()?>sign/in" method="post">
        <div class="input-group mb-3">
          <input type="text" name="uid" class="form-control form-control-sm" placeholder="ID" value="<?php if(isset($uid)) echo $uid; ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="upwd" class="form-control form-control-sm" placeholder="Password" value="<?php if(isset($upwd)) echo $upwd; ?>">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!--div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div-->
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!--div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div-->
      <!-- /.social-auth-links -->

      <!--p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p-->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo $bu;?>/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo $bu;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- jquery-validation -->
<script src="<?php echo $bu;?>/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="<?php echo $bu;?>/plugins/jquery-validation/additional-methods.min.js"></script>

<!-- SweetAlert2 -->
<script src="<?php echo $bu;?>/plugins/sweetalert2/sweetalert2.all.min.js"></script>

<!-- AdminLTE App -->
<script src="<?php echo $bu;?>/dist/js/adminlte.min.js"></script>

<!-- My Own -->
<script src="<?php echo $bu;?>/my/js/custom.js"></script>

<script>
var menuc='.';
var pmenuc='.';
$(document).ready(function(){
	document_ready();
	
	$('#quickForm').validate({
    rules: {
      uid: {
        required: true
      },
      upwd: {
        required: true
      },
      terms: {
        required: true
      },
    },
    messages: {
      uid: {
        required: "Please enter your ID",
        email: "Please enter a vaild email address"
      },
      upwd: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long"
      },
      terms: "Please accept our terms"
    }
	});
	
<?php
	if(isset($script)) echo $script;
?>
	
})
</script>

</body>
</html>
