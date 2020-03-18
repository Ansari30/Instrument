<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo "Reset Password - ".SITE_TITLE;?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="shortcut icon"   href="<?php echo base_url() ?>assets/img/fav.ico"  type="image/x-icon" />

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  
   
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Flame</b>Lily</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Reset Password</p>

        <?php echo $this->session->flashdata('resetError'); ?>
        
        <?php echo form_open(base_url("admin/".$this->controllerName."/reset/".$id),array('id'=>"frmReset"));?>
            <div class="form-group has-feedback <?php if (form_error('password')) echo 'has-error'; ?>">
                <input type="password" class="form-control" placeholder="Password" name="password"   value="">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <span for="InputRole" class="help-block"><?php echo form_error('password') ?></span> 
            </div>

            <div class="form-group has-feedback <?php if (form_error('c_password')) echo 'has-error'; ?>">
                <input type="password" class="form-control" placeholder="Confirm Password" name="c_password"  value="">
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                <span for="InputRole" class="help-block"><?php echo form_error('c_password') ?></span> 
             </div>

     
      <div class="row">
        <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
               
            </label>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Change</button>
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
 
</body>
</html>
