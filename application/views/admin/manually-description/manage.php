<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <h1>
         Manually Update Description
         <!--<small>advanced tables</small>-->
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('admin')?>"> Home</a></li>
         <li class="active"><a href="#">Manually Update Description</a></li>
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
                  <div class="pull-right">   
                     <button type="button" class="btn btn-primary " onclick="return updateCategory();">
                     <i class="fa fa-plus"></i> Update Description
                     </button>
                    
                  </div>
               </div>
               <!-- /.box-header -->
               <div class="box-body">
                  <form name="searchdata" id="searchdata" method="" action="<?php echo base_url('admin/manually_description'); ?>">
                     <div id="custom-search-input" style="padding: 20px 0 20px;">
                        <div class="input-group">
                           <input type="text" name="keyword" id="skills" class="search-query form-control" placeholder="Search Categories" value="<?php if(isset($_GET['keyword'])){ echo $_GET['keyword']; } ?>"/>
                           <span class="input-group-btn">
                           <button class="btn btn-primary" type="submit" onclick="return check_search();">
                           <span class=" glyphicon glyphicon-search"></span>
                           </button>
                           </span>
                           <span> <a href="<?php echo base_url('admin/manually_update');?>"> <button class="btn btn-primary pull-right" type="button">Reset</button></a></span>
                        </div>
                        <span style="color: #f00" id="error"></span>
                     </div>
                  </form>
                  <div class="loader hide"></div>

               </div>
               <?php echo form_open("#", array('name' => 'frmlist','id'=>'frmlist')) ?>
               <table id="example1" class="display" cellspacing="0" width="100%">
                  <thead>
                     <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>DESCRIPTION</th>
                        
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        if($results){ 
                          foreach ($results as $data) {
                       ?>
                     <tr>
                        <td class="pull-center"><input type="hidden" name="cat_id[]" value="<?=$data['cat_id']?>"><?=$data['cat_id']?></td>
                        <td><input type="hidden" name="name[]" value="<?=$data['name']?>"><?=$data['name']?></td>
                        <td class="pull-center">
                         <div class="form-group">
                           <textarea type="text" name="description[]" value="" class="form-control" autocomplete="off"> </textarea>
                         </div> 
                        </td>
                       
                     </tr>
                     <?php }}?>
                  </tbody>
               </table>
               <?php echo form_close(); ?>
             
            </div>
            <!-- /.box-body -->
         </div>
         <!-- /.box -->
      </div>
      <!-- /.col -->
</section>
<!-- /.row -->
</div>
<!-- /.content -->

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/URI.js/1.17.0/URI.min.js"></script>
<!-- select script for categories -->
<script src="<?php echo base_url(); ?>assets/plugins/select2/select/select2.full.min.js"></script>
<!-- auto suggestion start -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/autocomplete/jquery-ui.css">

<script>
  function updateCategory(){
        $('.loader').removeClass('hide'); 
        $('#frmlist').attr('action','<?php echo base_url('admin/'.$this->controllerName.'/manuallyUpdate'); ?>');
        $('#frmlist').submit();
  }

</script>