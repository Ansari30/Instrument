<div class="clearfix"></div>

	<!-- Content Wrapper. Contains page content -->
  	<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Website Settings</li>
      </ol>
      <h1>
     	Website Settings
      </h1>
     
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <div class="panel">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                   <?php echo $this->session->flashdata('page_msg');?>
                </div>
                <!-- /.box-header -->
                 
                <!-- form start -->
                <?php  echo form_open(base_url("admin/".$this->controllerName), array("class" => "","id"=>"formID")); ?>
                <div class="box-body">
                  	<?php if($settings_data):?>								
						<?php foreach($settings_data as $settings):?> 
                    <div class="form-group <?php if (form_error('title')) echo 'has-error'; ?>">
                      	<label for="exampleInputEmail1"><?php echo humanize($settings['key']);?></label>
                      	<input type="text" class="form-control" id="exampleInputEmail1" placeholder="<?php echo $settings['description'];?>" name="settings_data[<?php echo $settings['key'];?>]" value="<?php echo $settings['value'];?>" required >
                      	<span for="title" class="help-block"> <?php echo form_error("settings_data[".$settings['key']."]")?> </span>
                      	<?php if($settings['key']=="TWITTER"){?>
                      	<span for="title" class="help-block"><b>Note: First copy and past Embed link in notepad. Only copy second a tag href and paste here. </b></span>
                      	<?PHP } ?>
                        <?php if($settings['key']=="FACEBOOK_ACCESS_TOKEN"){?>
                        <span for="title" class="help-block"><b>Note: Set value like this app_id|app_secret. Get app_id and app_secretkey from facebook developer account. </b></span>
                        <?PHP } ?>
                    </div>
 					<?php endforeach;?>								
				<?php endif;?>

                </div>
                  <!-- /.box-body -->

             	<div class="box-footer">
                    <button type="submit" class="btn btn-info pull-right">Save Settings</button>
              	</div>
             	<?php echo form_close() ?>
            </div>
          <!-- /.box -->
           </div>

          
          
 

        </div>
        <!--/.col (left) -->
        
         
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

<!-- validator -->
 <!-- Select2 -->
<script src="<?php echo base_url(); ?>assets/plugins/select2/select2.full.min.js"></script>
<script type="text/javascript"> 
  $(document).ready(function () { 
      //Initialize Select2 Elements
      $(".select2").select2();
  });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/plugins/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () { 
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.replace('exampleInputContent'); 
    });
    
</script>
<script type="text/javascript">

    var rowCount = 1;

    function addMoreRows(frm) {

    

    var recRow = '<div class="col-xs-12 form-group" id="rowCount'+rowCount+'" ><div class="col-xs-12 col-sm-6 col-lg-3" ><input name="extra_fieldname[]" type="text" size="17%"  maxlength="120" class=" form-control" placeholder="Field Name" /></div><div class="col-xs-12 col-sm-6 col-lg-3" ><input name="extra_fieldvalue[]" type="text"  maxlength="120" class=" form-control" placeholder="Field Value" /> </div> <a href="javascript:void(0);" onclick="removeRow('+rowCount+');">Delete</a></div>';

    jQuery('#addedRows').append(recRow);
    rowCount ++;

    }

     

    function removeRow(removeNum) {

        jQuery('#rowCount'+removeNum).remove();

    }
    function removeRowpre(removeNum){
         jQuery('#removediv_'+removeNum).remove();
    }

    function  hide(id){
         $("#show_"+id).attr("type","text");
        //document.getElementById('show_'+id).attr('type','text');
        document.getElementById('show_'+id).style.display = "block"; 
        
        document.getElementById('hide_'+id).style.display = "none";
    }

    function check_url(urls){
            var url = '<?php echo base_url('admin/'.$this->controllerName.'/checkalready'); ?>'; 
            var page_id = "<?php echo $id; ?>";
            $.ajax({
              url : url,
              type : 'post',
              dataType:'json',
              data : "checkurl="+urls+"&id="+page_id,
              success : function(data) { 
                  if(data.type=='error'){
                    $('#url').val('');
                    alert(data.message);
                  }
              }
            });
    }

    function display_category(cate){
      if(cate=='blog'){
        document.getElementById('display_category').style.display = 'block';
      }else{
        document.getElementById('display_category').style.display = 'none';
      }
    }

    </script>
