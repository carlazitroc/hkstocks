<?php 

include('header.html');

?>
<div class="wrapper-body">
    <div class="container">

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
                        <form id="chart_lookup" action="stocks.php" method="post">
                          <div class="form-group row">
                            <label for="symbol" class="col-sm-12 form-control-label">Stock Symbol</label>
                            <div class="col-sm-12">
                              <input type="text" name="stock_num" class="form-control" placeholder="Stock Symbol" required/>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary" id="search-id">Search</button>
                            </div>
                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <?php 

        if (isset($_POST["compname"]) && !empty($_POST["compname"])): 
            $compname = rawurlencode($_POST['compname']);
        ?>
        
        <!--get code for quandl-->
        <section>
            <?php
                $url = 'https://www.quandl.com/api/v3/datasets.xml?query='.$compname.'&database_code=XHKG';
                //$url = 'data/00700.xml';
                $xml = @simplexml_load_file($url);
                
                if($xml){

                print '<h4 class="text-center"><b>Quandl Stock Information</b></h4>
                <table class="table table-bordered table-indicator">
                    <thead id="headertable">
                        <tr>
                            <th class="col-sm-2">Symbol</th>
                            <th class="col-sm-10">Company Name</th>
                        </tr>
                    </thead>
                    <tbody id=results>';


                    // get company name and stock symbol
                    foreach( $xml->datasets->dataset as $xmlResult){
                        $name   = $xmlResult->name;
                        $symbol = $xmlResult->{'dataset-code'};

                        print '<tr>'; 
                        print '<td class="col-sm-2">'.$symbol.'</td>';
                        print '<td class="col-sm-10">'.$name.'</td>';
                        print '</tr>';
                    }
                print"</tbody>
                </table>";

                }else{
                    
                }
            ?>
        </section>

        <!--result from yahoo finance-->
        <section>
            <?php 
                $compname = rawurlencode($_POST['compname']);
                $jsondata = file_get_contents('http://autoc.finance.yahoo.com/autoc?&region=1&lang=en&query='.$compname.'');
                $data = json_decode($jsondata, true);
                $results = $data['ResultSet']['Result'];

                print '<h4 class="text-center"><b>Yahoo Finance Stock Information</b></h4>
                <table class="table table-bordered table-indicator">
                    <thead id="headertable">
                        <tr>
                            <th class="col-sm-2">Symbol</th>
                            <th class="col-sm-10">Company Name</th>
                        </tr>
                    </thead>
                    <tbody id=results>';
                
                foreach ($results as $result) {
                    $symbol = $result['symbol'];
                    $name   = $result['name'];
                    
                    print '<tr>'; 
                    print '<td class="col-sm-2">'.$symbol.'</td>';
                    print '<td class="col-sm-10">'.$name.'</td>';
                    print '</tr>';
                }

                print '</tbody>
                </table>';
            ?>
        </section>
        
        <?php endif;?>

    </div>

    <a id="back-to-top" href="#page-top" class="btn btn-primary btn-lg back-to-top" role="button" title="Click to return on the top page" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-chevron-up"></span></a>
</div>
<?php 

include('footer.html'); 

?>
