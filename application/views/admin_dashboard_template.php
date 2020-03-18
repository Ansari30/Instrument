<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Instrumentalparts | <?php echo $page_title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="shortcut icon"   href="<?php echo base_url() ?>assets/img/dk-logo.jpg"  type="image/x-icon" />

  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  
  <!-- Select2 -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/select2.min.css">


  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/skin-blue.min.css">
   


 <!--  Custome css -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/custome.css">
 <!--  Datepicker css -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
  
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

<?php $loggedinname = $this->session->userdata('admin_logged_in')['username'];  ?>
<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url('admin'); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><?php echo 'Instrumentalparts'; ?></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php echo 'Instrumentalparts'; ?></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

         <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
               <!--<img src="<?php //echo base_url(); ?>assets/img/dk-logo.jpg" class="user-image" alt="User Image">-->
               <span class="logged-text hidden-xs"> Logged in as ADMIN<?php //echo $loggedinname;?></span>
            <ul class="dropdown-menu">
               <!-- User image -->
               <li class="user-header">
                  <img src="<?php echo base_url(); ?>assets/img/dk-logo.jpg" class="img-circle" alt="User Image">
                  <p>
                      <?php echo $loggedinname;?>
                      <!--<small>Member since Nov. 2012</small>-->
                  </p>
               </li>
               <!-- Menu Body -->
 
               <!-- Menu Footer-->
               <li class="user-footer">
                   <!--<div class="pull-left">
                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                   </div>-->
                   <div class="pull-right">
                        <a href="<?php echo base_url('admin/logout'); ?>" class="btn btn-default btn-flat">Sign out</a>
                   </div>
               </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
         
        </ul>
      </div>
    </nav>
  </header>
    
  <!-- Left side column. contains the logo and sidebar -->
 <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel hide">
        <div class="pull-left image">
          <img src="<?php echo base_url(); ?>assets/img/dkoldies-logo.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p> <?php echo $loggedinname;?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
       
      <?php $segment2 = $this->uri->segment('2'); $segment3 = $this->uri->segment('3'); ?>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MANAGE</li>
        
        <!-- Check permission -->

        
        <li class="<?php if($segment2=='dashboard'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin'); ?>">
            <i class="fa fa-dashboard"></i> 
            <span>Dashboard</span> 
          </a> 
        </li>
          
       
          
        <!--<li class="<?php if($segment2=='deal'){ ?>active<?php } ?>">
          <a href="<?php //echo base_url('admin/deal'); ?>">
            <i class="fa fa-laptop"></i> 
            <span>Product Deal</span> 
          </a> 
        </li>-->

        <li class="<?php if($segment2=='update_categories'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/update_categories'); ?>">
            <i class="fa fa-upload"></i> 
            <span>Update Categories</span> 
          </a> 
        </li>

         <li class="<?php if($segment2=='manually_update'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/manually_update'); ?>">
            <i class="fa fa-check-square"></i> 
            <span>Manually Update (Cat.)</span> 
          </a> 
        </li>
        <li class="<?php if($segment2=='upload_description'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/upload_description'); ?>">
            <i class="fa fa-key"></i> 
            <span>Upload Description</span> 
          </a> 
        </li>
         <li class="<?php if($segment3=='manually_description'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/manually_description'); ?>">
            <i class="fa fa-check-square"></i> 
            <span>Manually Update (Des.)</span> 
          </a> 
        </li>

        <li class="<?php if($segment3=='match_category' || $segment3=='create_category'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/categories/match_category'); ?>">
            <i class="fa fa-check"></i> 
            <span>Create & Match Cat.</span> 
          </a> 
        </li>

        <li class="<?php if($segment3=='assign_product'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/categories/assign_product'); ?>">
            <i class="fa fa-check"></i> 
            <span>Assign product to cat</span> 
          </a> 
        </li>

        <li class="<?php if($segment3=='category_8999_match'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/categories/category_8999_match'); ?>">
            <i class="fa fa-check"></i> 
            <span>8999 Cat. Match</span> 
          </a> 
        </li>
        <li class="<?php if($segment2=='variant'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/variant'); ?>">
            <i class="fa fa-check-square"></i> 
            <span>Variants(Pro.) Update</span> 
          </a> 
        </li>

        <li class="<?php if($segment2=='change_password'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/change_password'); ?>">
            <i class="fa fa-key"></i> 
            <span>Change Password</span> 
          </a> 
        </li>
        <li class="<?php if($segment2=='budgetbox'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/budgetbox'); ?>">
            <i class="fa fa-key"></i> 
            <span>Budgetbox</span> 
          </a> 
        </li>
         <li class="<?php if($segment3=='relatedview'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/budgetbox/relatedview'); ?>">
            <i class="fa fa-key"></i> 
            <span>Budgetbox Realated</span> 
          </a> 
        </li>
        <li class="<?php if($segment3=='zurclrview'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/budgetbox/zurclrview'); ?>">
            <i class="fa fa-key"></i> 
            <span>Budgetbox Zurclr</span> 
          </a> 
        </li>
        <li class="<?php if($segment2=='kaldoororders'){ ?>active<?php } ?>">
          <a href="<?php echo base_url('admin/kaldoororders'); ?>">
            <i class="fa fa-key"></i> 
            <span>Kal Door Orders</span> 
          </a> 
        </li>
        <li >
          <a href="<?php echo base_url('admin/logout'); ?>">
            <i class="fa fa-power-off"></i> 
            <span>Logout</span> 
          </a> 
        </li>


        

        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
<!-- jQuery 1.11.3 -->
<!--<script src="<?php //echo base_url(); ?>assets/dist/js/jquery-1.11.3.min.js"></script>-->
<script
        src="//code.jquery.com/jquery-2.2.4.js"
        integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI="
        crossorigin="anonymous"></script>

  <!-- Content Wrapper. Contains page content -->
  <?php $this->load->view($page_name); ?>
  <!-- /.content-wrapper -->
 <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2017 <a target="_blank" href="https://www.instrumentalparts.com/">www.instrumentalparts.com</a>.</strong> All rights
    reserved.
  </footer>
     
</div>

<!-- ./wrapper -->



<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url(); ?>assets/dist/js/jquery-ui.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
   
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.min.js"></script>  
</body>
</html>
