<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"></i> Home</a></li>
        <li class="active"><a href="#">Dashboard</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-white">
            <div class="inner">
              <p>Un-upload Categories</p>
              <h3><?php echo $total_product; ?></h3>
               <div class="icon_box">
                    <img src="<?php echo base_url(); ?>assets/img/dashboard.png">
               </div>
            </div>
           
            <!-- <a href="<?php //echo base_url('admin/product'); ?>" class="small-box-title">Click here for view inventory <i class="fa fa-arrow-circle-right"></i></a>-->
          </div>
        </div>
         
        </div>
        <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-white">
            <div class="inner">
              <p>Un-upload Description</p>
              <h3><?php echo $total_product_description; ?></h3>
               <div class="icon_box">
                    <img src="<?php echo base_url(); ?>assets/img/dashboard.png">
               </div>
            </div>
           
            <!-- <a href="<?php //echo base_url('admin/product'); ?>" class="small-box-title">Click here for view inventory <i class="fa fa-arrow-circle-right"></i></a>-->
          </div>
        </div>
         
        </div>
        <!-- /.row --> 
    </section>
    <!-- /.content -->
  </div>