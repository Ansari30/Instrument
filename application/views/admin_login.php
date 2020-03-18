<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Log in - Instrumentalparts</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="shortcut icon"   href="<?php echo base_url() ?>assets/img/dk-logo.jpg"  type="image/x-icon" />

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="javascript:void(0)"><b>Instrumental</b>Parts</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <?php echo $this->session->flashdata('loginError'); ?>
     
    <?php echo form_open(base_url("admin/checklogin"),array('id'=>"formID"));?>
      <div class="form-group has-feedback <?php if (form_error('txtUserEmail')) echo 'has-error'; ?>">
        <input type="email" class="form-control" placeholder="Email" id="email" name="txtUserEmail"  value="<?php echo set_value('txtUserEmail'); ?>"  />
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        <span for="email" class="help-block"><?php echo form_error('txtUserEmail'); ?> </span> 
      </div>
      <div class="form-group has-feedback <?php if (form_error('txtPassword')) echo 'has-error'; ?>">
        <input type="password" id="password" class="form-control" placeholder="Password" name="txtPassword"  >
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <span for="password" class="help-block"><?php echo form_error('txtPassword'); ?> </span> 
      </div>
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              <!--<a href="<?php echo base_url('admin/forgot_password')?>">Forgot password</a><br>-->
              <!--<input type="checkbox"> Remember Me-->
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    <?php echo form_close()?>
  
     

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 1.11.3 -->
<script src="<?php echo base_url(); ?>assets/dist/js/jquery-1.11.3.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
<!--
<script src="<?php echo base_url(); ?>assets/dist/js/jquery.validate.js"></script>
 
<script>
// only for demo purposes
  
$().ready(function() {
  // validate the form when it is submitted
  var validator = $("#formID").validate({
    errorPlacement: function(error, element) {
       
      // Append error within linked label
      $( element )
        .closest( "form" )
          .find( "span[for='" + element.attr( "id" ) + "']" )
            .append( error );
    },
    errorElement: "span",
    messages: {
      txtUserEmail: {
        required: " Email Required"
      },
      txtPassword: {
        required: " Password required",
        minlength: " (must be between 5 and 12 characters)",
        maxlength: " (must be between 5 and 12 characters)"
      }
    }
  }); 

});
</script>
 -->
</body>
</html>
