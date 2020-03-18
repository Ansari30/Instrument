<div class="clearfix"></div>

  <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
     <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Charts</li>
      </ol>
      <h1>
      Charts
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
                </div>
                <!-- /.box-header -->
                 
                <!-- form start -->
                <div class="box-body">
                  <div id="line_chart"></div>

                </div>
                  <!-- /.box-body -->

              
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


<html>
    <head>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>


<script type="text/javascript">
      // Load the Visualization API and the line package.
      google.charts.load('current', {'packages':['line']});
      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);
  
    function drawChart() {
  
        $.ajax({
        type: 'POST',
        url: 'http://localhost/instrumental/admin/charts/getdata',
          
        success: function (data1) {
        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable();
  
      data.addColumn('string', 'Year');
      data.addColumn('number', 'Sales');
      data.addColumn('number', 'Expense');
        
      var jsonData = $.parseJSON(data1);
      
      for (var i = 0; i < jsonData.length; i++) {
            data.addRow([jsonData[i].year, parseInt(jsonData[i].sales), parseInt(jsonData[i].expense)]);
      }
      var options = {
        chart: {
          title: 'Company Performance',
          subtitle: 'Show Sales and Expense of Company'
        },
        width: 900,
        height: 500,
        axes: {
          x: {
            0: {side: 'bottom'} 
          }
        }
         
      };
      var chart = new google.charts.Line(document.getElementById('line_chart'));
      chart.draw(data, options);
       }
     });
    }
  </script>
</head>
<body>
  
  <div id="line_chart"></div>
</body>
</html>