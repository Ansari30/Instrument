<?php 
   // $result = $result['result'];
?>


<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Change Password
        <!--<small>advanced tables</small>-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin')?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="#">Change Password</a></li>
      </ol>
    </section>
     <div class="col-md-12" id="page-message"><?php echo $this->session->flashdata('dispMessage'); ?></div> 
    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-xs-12">
        
        <div class="box">

            <div class="box-header">
                <!--<h3 class="box-title">Data Table With Full Features</h3>-->
                

            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <form name="changePassword" id="changePassword" action="<?php echo base_url('admin/'.$this->controllerName.'/changePassword'); ?>" method="post">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                        <input type="password" class="form-control" name="currentPassword" id="currentPassword" placeholder="Current Password" required="">
                    </div>
                    <div class="form-group">
                      
                        <input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="New Password" required="">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required="" >
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" onClick="checkConfirmPassword('confirmPassword');">Save</button>
                    </div>                     
                  </div>   
                </div> 
              </form>        
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>



 <!-- Add purchesh note information --> 


<!-- View purchesh note information --> 
  

<!-- Edit purchesh note information --> 
  

  <!--  Custome css -->
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/custome.css">
<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.css">

<!-- DataTables -->
<script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>

<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/all.css">
<!-- iCheck 1.0.1 -->
<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
<!-- page script -->

<script type="text/javascript">
    function checkConfirmPassword(str){
    if(str=='confirmPassword'){
       var currentPassword = $('#currentPassword').val();
       var newPwd = $('#newPassword').val();
       var confirmPassword = $('#confirmPassword').val();

       if(currentPassword==''){  
           $('#currentPassword').css('border','1px solid red');
       }
       if(newPwd==''){  
           $('#newPassword').css('border','1px solid red');
       }
       if(confirmPassword==''){
           $('#confirmPassword').css('border','1px solid red');
       }
        
       if(newPwd!='' && currentPassword!=''){ 
            if(newPwd==confirmPassword){
                alert
                 $('#changePassword').submit();
                 return false;
            }else{
                alert('Please enter same password');
                return false;
            }
        }
       }
      
    }
</script>