<?php
if (isset($_POST["stock_num"]) && !empty($_POST["stock_num"]))      { $dataset_code = $_POST['stock_num'];


//echo $dataset_code;
//live data from quandl.com
$url = 'https://www.quandl.com/api/v3/datasets/XHKG/'.$dataset_code.'.xml?api_key=NzzkduZp5xEeyoC6q-oR';

//live data from quandl.com free
//$url ='https://www.quandl.com/api/v3/datasets/WIKI/'.$dataset_code.'.xml?api_key=NzzkduZp5xEeyoC6q-oR';
//$url = 'data/'.$dataset_code.'.xml';
$xml = xmltojson($url);

echo $xml;
}
else{
    if (isset($_POST["stock_num_index"]) && !empty($_POST["stock_num_index"]))      { $dataset_code_index = $_POST['stock_num_index'];

    $url = 'https://www.quandl.com/api/v3/datasets/XHKG/'.$dataset_code_index.'.xml?api_key=NzzkduZp5xEeyoC6q-oR';
    //$url = 'data/'.$dataset_code_index.'.xml';
    $xml = xmltojson($url);

    echo $xml;
    }
}


if (isset($_POST["compname"]) && !empty($_POST["compname"])){
    $compname = rawurlencode($_POST['compname']);

$quandl_data_url = 'https://www.quandl.com/api/v3/datasets.xml?query='.$compname.'&database_code=XHKG';
$xml_file = simplexml_load_file($quandl_data_url);
$json = json_encode($xml_file);

echo $json;
} 
        

function xmltojson($url) {

    $fileContents= @file_get_contents($url);
    if ($fileContents) {
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);

        $json = json_encode($simpleXml);

        return $json;
    } else {
        // if not exceute this 
        echo "connection error";
    }

}

?>