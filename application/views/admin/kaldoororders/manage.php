<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Kal Door Orders
         <!--<small>advanced tables</small>-->
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('admin')?>"><i class="fa fa-dashboard"></i> Home</a></li>
         <li class="active"><a href="#">Kal Door Orders</a></li>
      </ol>
   </section>
   <div class="col-md-12" id="page-message"><?php echo $this->session->flashdata('dispMessage'); ?></div>
   <!-- Main content -->
   <section class="content">

      <div class="row">
         <div class="col-xs-12">
            <div class="box">
              
               <!-- /.box-header -->
                <form name="uploadfile" id="uploadfile" action="<?php echo base_url('admin/'.$this->controllerName.'/upload'); ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
                  <div class="box-body">
                     
                     <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">Upload Sheet</label>
                        <div class="col-sm-4">
                           <div class="input-group">
                              <input type="file" name="upload_file" id="upload_file" data-bvalidator="required" data-bvalidator-msg="Please select file of type .csv">
                           </div>
                        </div>
                        
                     </div>
                     <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label"></label>
                        <div class="col-md-6">
                           <div class="box-footer">
                              <button type="submit" class="btn btn-primary uploadbtn">Upload File</button>
                           </div>
                        </div>

                     </div>
               </div>
              </form> 
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

<script src="<?php echo base_url(); ?>assets/plugins/bValidator-0.73/jquery.bvalidator.js"></script>

<!-- page script -->
<script type="text/javascript">

$('#uploadfile').bValidator();
$("#uploadfile").submit(function(){
	$(".uploadbtn").html('<i class="fa fa-spinner fa-spin"></i> Processing...');
	$('.uploadbtn').attr('disabled','disabled');
});
</script>

