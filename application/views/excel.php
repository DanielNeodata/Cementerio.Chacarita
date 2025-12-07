<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$csv=fopen('php://temp/maxmemory:'. (5*1024*1024), 'r+');
fputs($csv,$bom=(chr(0xEF).chr(0xBB).chr(0xBF)));
if (!isset($parameters["columns"]) or !is_array($parameters["columns"])){
    $parameters["columns"]=array(
        array("field"=>"code","format"=>"code"),
        array("field"=>"description","format"=>"text"),
    );
}
$headers=array();

foreach ($parameters["columns"] as $column) {
    $headers[]=lang("p_".$column["field"]);
}
fputcsv($csv, $headers,$parameters["delimiter"]);

foreach ((array)$parameters["records"]["data"] as $record){
    $line=array();
    foreach ($parameters["columns"] as $column) {
        $line[]=$record[$column["field"]];
    }
    fputcsv($csv,$line,$parameters["delimiter"]);
}
rewind($csv);
$output=stream_get_contents($csv);// TOque aca!
echo $output;// TOque aca!
fclose($csv);// TOque aca!
?>
