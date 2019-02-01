<?php

require_once 'settings.php';

/* 
 * Check, if the csv exists.
 * If it doesn't exist: Download recent xml from the rakuten api
 * and parse it to csv. 
*/
if(!file_exists($csvPath)){
    //$xml_src = file_get_contents($get_orders);
    $xml_src = file_get_contents("test.xml");
    $xml = new SimpleXMLElement($xml_src);
    $csv = generateCSV($xml); 

    writeCsv($csv, $csvPath);

    writeDate($datePath);
}







/*
 * Generates the csv-content from a xml-file.
 * 
 * @param string    $xml    The xml you want to parse to csv.
 * @return string   $csv    The parsed csv. 
*/
function generateCSV($xml){
    
    $csv = generateCSVHeadline();
    
    //Goes through every order in the xml file. If there is no order left, it stops.
    for($i = 0; isset($xml->orders[0]->order[$i]->order_no[0]); $i++){
        //Goes through every item in the order. If there is no item left, it stops.
        for($j = 0; isset($xml->orders[0]->order[$i]->items[0]->item[$j]->product_art_no[0]); $j++){
            $csv .= "\r\n";
            $csv .= $xml->orders[0]->order[$i]->order_no[0].";"; //Order number
            $csv .= $xml->orders[0]->order[$i]->created[0].";"; //Order date
            $csv .= $xml->orders[0]->order[$i]->client[0]->email[0].";"; //Order date

            //TODO Check for multiple items ordered
            $csv .= $xml->orders[0]->order[$i]->items[0]->item[$j]->product_art_no[0].";"; //Article number
            $csv .= $xml->orders[0]->order[$i]->items[0]->item[$j]->qty[0].";"; //Article quantity
            $csv .= $xml->orders[0]->order[$i]->items[0]->item[$j]->price[0].";"; //Article price

            $company = $xml->orders[0]->order[$i]->delivery_address[0]->company[0];
            $name = $xml->orders[0]->order[$i]->delivery_address[0]->first_name[0] . " "
                  . $xml->orders[0]->order[$i]->delivery_address[0]->last_name[0];
            //If company is filled: set company to DeliveryClient and name to DeliveryClient2
            //If company is empty: set name to DeliveryClient and leave DeliveryClient2 empty
            if($company != ""){$csv .= $company . ";" . $name . ";"; }
            else{ $csv .= $name . ";" . $company . ";"; }
            $csv .= $xml->orders[0]->order[$i]->delivery_address[0]->street[0] . " "
                  . $xml->orders[0]->order[$i]->delivery_address[0]->street_no[0].";"; //Delivery street
            $csv .= $xml->orders[0]->order[$i]->delivery_address[0]->zip_code[0].";"; //Delivery zip
            $csv .= $xml->orders[0]->order[$i]->delivery_address[0]->city[0].";"; //Delivery city
            $csv .= $xml->orders[0]->order[$i]->delivery_address[0]->country[0].";"; //Delivery country code

            $iCompany = $xml->orders[0]->order[$i]->client[0]->company[0];
            $iName = $xml->orders[0]->order[$i]->client[0]->first_name[0] . " " 
                   . $xml->orders[0]->order[$i]->client[0]->last_name[0];
            //If company is filled: set company to DeliveryClient and name to DeliveryClient2
            //If company is empty: set name to DeliveryClient and leave DeliveryClient2 empty
            if($iCompany != ""){$csv .= $iCompany . ";" . $iName . ";"; }
            else{ $csv .= $iName . ";" . $iCompany . ";"; }
            $csv .= $xml->orders[0]->order[$i]->client[0]->street[0] . " "
                  . $xml->orders[0]->order[$i]->client[0]->street_no[0].";"; //Invoice street
            $csv .= $xml->orders[0]->order[$i]->client[0]->zip_code[0].";"; //Invoice zip
            $csv .= $xml->orders[0]->order[$i]->client[0]->city[0].";"; //Invoice city
            $csv .= $xml->orders[0]->order[$i]->client[0]->country[0].";"; //Invoice country code

            $csv .= $xml->orders[0]->order[$i]->client[0]->phone[0].";"; //Client phone
            $csv .= $xml->orders[0]->order[$i]->payment[0]; //Client payment type
        }
    }
    
    
    return $csv;
}

/*
 * Generates the headline of the csv file.
 * 
 * @return string   $csv_headline    The headline for the csv file.
*/
function generateCSVHeadline(){
    $csv_headline = ""
            . "OrderNumber;OrderDate;EMail;"
            . "ArticleNumber;ArticleQuantity;ArticlePrice;"
            . "DeliveryClient;DeliveryClient2;DeliveryStreet;"
            . "DeliveryZIP;DeliveryCity;DeliveryCountry;"
            . "InvoiceClient;InvoiceClient2;InvoiceStreet;"
            . "InvoiceZIP;InvoiceCity;InvoiceCountry;"
            . "Phone;PaymentType";
    return $csv_headline;
}

/*
 * Writes the current DateTime to a file.
*/
function writeDate($datePath){
    $date = new DateTime();
    
    $fp = fopen($datePath, 'w');
    fwrite($fp, $date->format('Y-m-d h:i:s'));
    fclose($fp);
}

/*
 * Creates a csv file from a string.
 * 
 * @input string    $csv    Csv content, that should be written to a file
*/
function writeCsv($csv, $csvPath){
    echo $csv;
    
    $fp = fopen($csvPath, 'w');
    fwrite($fp, $csv);
    fclose($fp);
}