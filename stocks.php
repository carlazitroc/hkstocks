<?php 

include('header.html');

?>
<div class="wrapper-body">
    <script>
    <?php 
    if (isset($_POST["stock_num"]) && !empty($_POST["stock_num"])){
        $stock_num = $_POST["stock_num"];
    }else{
        $stock_num = '';

    } 
    ?>

    // $(window).load(function() {
    //     var stock_num_from_index = '<?php echo $stock_num ?>';
    //     if(typeof(stock_num_from_index) != "undefined" && stock_num_from_index !== null) {
    //          var stock_num_index = stock_num_from_index;
    //     }
    // });
    $(document).ready(function(){
      $('#loadingmessage').show();

        var stock_num_from_index = '<?php echo $stock_num ?>';

        var vstock_num;
        $("#chart_lookup").submit(function () 
        {
            vstock_num = $("#stock_num").val();
            stock_num_from_index = '';
            $('#loadingmessage').show();
            $.post("data.php",
                {stock_num: vstock_num,
                 stock_num_index: stock_num_from_index},
                function(response) 
                {
                    // Check the output of json
                    try{
                       var obj = $.parseJSON(response);
                    }catch(err){
                      $('#loadingmessage').hide();
                      $('#errorModal').modal('show');
                    }
                    
                    //child data
                    var result = obj.dataset.data.datum;
                    var name = obj.dataset.name;

                    var dataPoints1 = [];
                    var dataPoints2 = [];
                    var dataPoints3 = [];
                    var dataPoints4 = [];

                    var chart = new CanvasJS.Chart("chartContainer",{
                      zoomEnabled: true,
                      zoomType: "xy",
                      title: {
                        text: name //company name    
                      },
                      toolTip: {
                        shared: true
                      },
                      legend: {
                        verticalAlign: "top",
                        horizontalAlign: "center",
                        fontSize: 14,
                        fontWeight: "bold",
                        fontFamily: "calibri",
                        fontColor: "dimGrey"
                      },
                      axisX: {
                        labelAngle: -20
                      },
                      axisY:{
                        prefix: '',
                      }, 
                      data: [{ 
                        // dataSeries1 dayHigh
                        type: "line",
                        xValueType: "dateTime",
                        showInLegend: true,
                        name: "Day High",
                        dataPoints: dataPoints1
                      },{ 
                        // dataSeries2 dayLow
                        type: "line",
                        xValueType: "dateTime",
                        showInLegend: true,
                        name: "Day Low",
                        dataPoints: dataPoints2
                      },{ 
                        // dataSeries1 dayHigh
                        type: "line",
                        xValueType: "dateTime",
                        showInLegend: true,
                        name: "Opening",
                        dataPoints: dataPoints3
                      },{ 
                        // dataSeries1 dayHigh
                        type: "line",
                        xValueType: "dateTime",
                        showInLegend: true,
                        name: "Closing",
                        dataPoints: dataPoints4
                      }],
                          legend:{
                            cursor:"pointer",
                            itemclick : function(e) {
                              if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                                e.dataSeries.visible = false;
                              }
                              else {
                                e.dataSeries.visible = true;
                              }
                              chart.render();
                      
                            }
                          }
                    });

                    //loop to grandchild data
                    $.each(result, function(key,value) {
                      var date = (value.datum[0]);
                           
                      var date_split = date.split('-');
                      var year = date_split[0];
                      var month = date_split[1];
                      var day = date_split[2];

                      var open = parseFloat((value.datum[1]));
                      var high = parseFloat((value.datum[2]));
                      var low  = parseFloat((value.datum[3]));
                      var close= parseFloat((value.datum[4]));

                      var date_f = new Date(year,month,day);
                      var open_f = parseFloat(open.toFixed(2));
                      var high_f = parseFloat(high.toFixed(2));
                      var low_f = parseFloat(low.toFixed(2));
                      var close_f = parseFloat(close.toFixed(2));
                      
                      // pushing the new values
                      dataPoints1.push({
                        x: date_f,
                        y: high_f,
                        mouseover: function(e) { 
                          $( "#kd" ).html( "<p>" + open_f + "</p>").toggle( "highlight" );
                          $( "#rsi" ).html( "<p>" + close_f + "</p>").toggle( "highlight" );
                          $( "#dayHigh" ).html( "<p>" + high_f + "</p>").toggle( "highlight" );
                          $( "#dayLow" ).html( "<p>" + low_f + "</p>").toggle( "highlight" ); 
                        }  
                      });
                      //pushing the new values
                      dataPoints2.push({
                        x: date_f,
                        y: low_f,
                        mouseover: function(e) { 
                          $( "#kd" ).html( "<p>" + open_f + "</p>").toggle( "highlight" );
                          $( "#rsi" ).html( "<p>" + close_f + "</p>").toggle( "highlight" );
                          $( "#dayHigh" ).html( "<p>" + high_f + "</p>").toggle( "highlight" );
                          $( "#dayLow" ).html( "<p>" + low_f + "</p>").toggle( "highlight" ); 
                          }  
                      });
                      dataPoints3.push({
                        x: date_f,
                        y: open_f,
                        mouseover: function(e) { 
                          $( "#kd" ).html( "<p>" + open_f + "</p>").toggle( "highlight" );
                          $( "#rsi" ).html( "<p>" + close_f + "</p>").toggle( "highlight" );
                          $( "#dayHigh" ).html( "<p>" + high_f + "</p>").toggle( "highlight" );
                          $( "#dayLow" ).html( "<p>" + low_f + "</p>").toggle( "highlight" ); 
                        }  
                      });
                      dataPoints4.push({
                        x: date_f,
                        y: close_f,
                        mouseover: function(e) { 
                          $( "#kd" ).html( "<p>" + open_f + "</p>").toggle( "highlight" );
                          $( "#rsi" ).html( "<p>" + close_f + "</p>").toggle( "highlight" );
                          $( "#dayHigh" ).html( "<p>" + high_f + "</p>").toggle( "highlight" );
                          $( "#dayLow" ).html( "<p>" + low_f + "</p>").toggle( "highlight" ); 
                        }  
                      });

                    });
                    chart.render();

                    $('#loadingmessage').hide();
                });



        });
            //if from index
            $.post("data.php",
                {stock_num: vstock_num,
                 stock_num_index: stock_num_from_index},
                function(response) 
                {
                    // Check the output of json
                    try{
                       var obj = $.parseJSON(response);
                    }catch(err){
                      $('#loadingmessage').hide();
                      $('#errorModal').modal('show');
                    }
                    
                    //child data
                    var result = obj.dataset.data.datum;
                    var name = obj.dataset.name;

                    var dataPoints1 = [];
                    var dataPoints2 = [];
                    var dataPoints3 = [];
                    var dataPoints4 = [];

                    var chart = new CanvasJS.Chart("chartContainer",{
                      zoomEnabled: true,
                      zoomType: "xy",
                      title: {
                        text: name //company name    
                      },
                      toolTip: {
                        shared: true
                      },
                      legend: {
                        verticalAlign: "top",
                        horizontalAlign: "center",
                        fontSize: 14,
                        fontWeight: "bold",
                        fontFamily: "calibri",
                        fontColor: "dimGrey"
                      },
                      axisX: {
                        labelAngle: -20
                      },
                      axisY:{
                        prefix: '',
                      }, 
                      data: [{ 
                        // dataSeries1 dayHigh
                        type: "line",
                        xValueType: "dateTime",
                        showInLegend: true,
                        name: "Day High",
                        dataPoints: dataPoints1
                      },{ 
                        // dataSeries2 dayLow
                        type: "line",
                        xValueType: "dateTime",
                        showInLegend: true,
                        name: "Day Low",
                        dataPoints: dataPoints2
                      },{ 
                        // dataSeries1 dayHigh
                        type: "line",
                        xValueType: "dateTime",
                        showInLegend: true,
                        name: "Opening",
                        dataPoints: dataPoints3
                      },{ 
                        // dataSeries1 dayHigh
                        type: "line",
                        xValueType: "dateTime",
                        showInLegend: true,
                        name: "Closing",
                        dataPoints: dataPoints4
                      }],
                          legend:{
                            cursor:"pointer",
                            itemclick : function(e) {
                              if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                                e.dataSeries.visible = false;
                              }
                              else {
                                e.dataSeries.visible = true;
                              }
                              chart.render();
                      
                            }
                          }
                    });

                    //loop to grandchild data
                    $.each(result, function(key,value) {
                      var date = (value.datum[0]);
                           
                      var date_split = date.split('-');
                      var year = date_split[0];
                      var month = date_split[1];
                      var day = date_split[2];

                      var open = parseFloat((value.datum[1]));
                      var high = parseFloat((value.datum[2]));
                      var low  = parseFloat((value.datum[3]));
                      var close= parseFloat((value.datum[4]));

                      var date_f = new Date(year,month,day);
                      var open_f = parseFloat(open.toFixed(2));
                      var high_f = parseFloat(high.toFixed(2));
                      var low_f = parseFloat(low.toFixed(2));
                      var close_f = parseFloat(close.toFixed(2));
                      
                      // pushing the new values
                      dataPoints1.push({
                        x: date_f,
                        y: high_f,
                        mouseover: function(e) { 
                          $( "#kd" ).html( "<p>" + open_f + "</p>").toggle( "highlight" );
                          $( "#rsi" ).html( "<p>" + close_f + "</p>").toggle( "highlight" );
                          $( "#dayHigh" ).html( "<p>" + high_f + "</p>").toggle( "highlight" );
                          $( "#dayLow" ).html( "<p>" + low_f + "</p>").toggle( "highlight" ); 
                        }  
                      });
                      //pushing the new values
                      dataPoints2.push({
                        x: date_f,
                        y: low_f,
                        mouseover: function(e) { 
                          $( "#kd" ).html( "<p>" + open_f + "</p>").toggle( "highlight" );
                          $( "#rsi" ).html( "<p>" + close_f + "</p>").toggle( "highlight" );
                          $( "#dayHigh" ).html( "<p>" + high_f + "</p>").toggle( "highlight" );
                          $( "#dayLow" ).html( "<p>" + low_f + "</p>").toggle( "highlight" ); 
                          }  
                      });
                      dataPoints3.push({
                        x: date_f,
                        y: open_f,
                        mouseover: function(e) { 
                          $( "#kd" ).html( "<p>" + open_f + "</p>").toggle( "highlight" );
                          $( "#rsi" ).html( "<p>" + close_f + "</p>").toggle( "highlight" );
                          $( "#dayHigh" ).html( "<p>" + high_f + "</p>").toggle( "highlight" );
                          $( "#dayLow" ).html( "<p>" + low_f + "</p>").toggle( "highlight" ); 
                        }  
                      });
                      dataPoints4.push({
                        x: date_f,
                        y: close_f,
                        mouseover: function(e) { 
                          $( "#kd" ).html( "<p>" + open_f + "</p>").toggle( "highlight" );
                          $( "#rsi" ).html( "<p>" + close_f + "</p>").toggle( "highlight" );
                          $( "#dayHigh" ).html( "<p>" + high_f + "</p>").toggle( "highlight" );
                          $( "#dayLow" ).html( "<p>" + low_f + "</p>").toggle( "highlight" ); 
                        }  
                      });

                    });
                    chart.render();

                    $('#loadingmessage').hide();
                });
    });
    </script>
    <div class="container">
      <!--show search form for chart-->
      <section class="search_form">
        <!--symbol lookup-->
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Stock Symbol Lookup</h3>
                </div>
                    <div class="panel-body">
                    <h4>Search Hong Kong Company name to get company's stock code symbol.</h4>
                    <form action="index.php" method="post">
                    <div class="form-group row">
                        <label for="inputPassword3" class="col-sm-12 form-control-label">Company Name</label>
                        <div class="col-sm-12">
                          <input type="text" class="form-control" name="compname" id="companyname" placeholder="Company Name" required/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12">
                          <button type="submit" class="btn btn-primary" id="symlookup">Lookup</button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!--show search form for chart-->
        <div class="col-md-6">
          <div class="panel panel-primary">
              <div class="panel-heading">
                  <h3 class="panel-title">Hong Kong Stocks Historical Chart Loookup</h3>
              </div>
              <div class="panel-body">
                <h4>Use Quandl Stock Information result for symbol and company name to view the chart</h4>
                <form id="chart_lookup" action="#" method="post">
                <div class="form-group row">
                    <label for="stocksymbol" class="col-sm-12 form-control-label">Stock Symbol</label>
                    <div class="col-sm-12">
                      <input name="stock_num" type="text"   class="form-control" id="stock_num" placeholder="Stock Symbol" required/>    
                    </div>
                  </div>
                  <div class="form-group row">
                    <div class="col-sm-12">
                      <button type="submit" class="btn btn-primary" id="search-id">Search</button>
                      <!--<input name="search" type="button" class="btn btn-primary" id="search-btn" value="Search"/>-->
                    </div>
                  </div>
                </form>
              </div>
          </div>
        </div>
      </section>

      <section id='loadingmessage' class="text-center" style="display: none;">
        <img src='img/ajax-loader.gif'/>
      </section>
	      
      <!--graph-->
  		<section>
  		    <div id="chartContainer" style="height: 400px; width: 100%;"></div>
  		</section>

		  <!--table indicator-->
	    <section>
	      	<h3 class="text-center">Indicator</h3>
	      	<table class="table table-bordered table-indicator">
    			  <thead>
    			    <tr>
    			      <th>#</th>
    			      <th>Values</th>
    			    </tr>
    			  </thead>
    			  <tbody>
              <tr>
                <td>Opening</td>
                <td><div id="kd"></div></td>
              </tr>
    			    <tr>
    			      <td>Closing</td>
    			      <td><div id="rsi"></div></td>
    			    </tr>
    			    <tr>
    			      <td>Day High</td>
    			      <td><div id="dayHigh"></div></td>
    			    </tr>
    			   	<tr>
    			      <td>Day Low</td>
    			      <td><div id="dayLow"></div></td>
    			    </tr>
    			  </tbody>
    			</table>
	    </section>
      
        <!--dialog for error-->
      <div id="errorModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Data does not exist.</h4>
            </div>
            <div class="modal-body">
              <p>Please search other symbol to search.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->

    </div>

    <a id="back-to-top" href="#page-top" class="btn btn-primary btn-lg back-to-top" role="button" title="Click to return on the top page" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>
</div>
<?php 

include('footer.html'); 

?>
