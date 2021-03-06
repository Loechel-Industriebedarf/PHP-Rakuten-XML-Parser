<?php

/* 
 * Some variables
 */

$api_base = "http://webservice.rakuten.de/merchants/";
$api_key = "XXXXX";

$csvPath = "..\order.csv";
$datePath = "date.txt";

date_default_timezone_set('Europe/Berlin');

$lastDate = readDate($datePath);
$lastDateStr = $lastDate->format('Y-m-d H:i:s');

$get_orders = $api_base."orders/getOrders?key=".$api_key."&created_from=".$lastDateStr;



function readDate($datePath){
    $datetime = file_get_contents($datePath); 
    $date = new DateTime($datetime);
    return $date;
}