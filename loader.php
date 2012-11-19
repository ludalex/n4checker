<?php

function getStatus($nation, $proxy, $url, $referer, $agent, $header, $timeout, $display, $soldout_phrase) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	if($nation != "usa" || $proxy != NULL) {
    		curl_setopt($ch, CURLOPT_PROXY, $proxy);
    		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
	}

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
 
    $result['EXE'] = curl_exec($ch);
    $result['INF'] = curl_getinfo($ch);
    $result['ERR'] = curl_error($ch);
 
    curl_close($ch);

    if (empty($result['ERR'])) {
		
		$html = $result['EXE']; 
		$outof = strpos($html, $soldout_phrase);

		if ($outof) { 
			return "<b>Out of stock.</b>"; } 
		else {
			if($display) {
				echo $result['EXE']; 
				//mail("ludalex@gmail.com", "html", $result['EXE'], "From:$aemail\r\nContent-Type: text/html; charset=iso-8859-1");
			} else {
				return "<b>IN STOCK!</b>"; }
		}
	} else {
			return "Error: ". $result['ERR'];
		 
	}
}

// VARIABLES DEFINITION

$url = "https://play.google.com/store/devices/details?id=nexus_4_16gb";

$referer = "http://www.google.com/";
$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8";

include("proxyList.php");

$soldout_phrase['usa'] = "Sold out";
$soldout_phrase['uk'] = "Sold out";
$soldout_phrase['spain'] = "Agotado";
$soldout_phrase['germany'] = "Ausverkauft";
$soldout_phrase['france'] = "hardware-sold-out";
 
$display = $_GET['display'];
$timeout = $_GET['timeout'];
$nation = $_GET['nation'];

// EXECUTION

echo getStatus($nation,$proxylist[$nation],$url,$referer,$agent,1,$timeout,$display,$soldout_phrase[$nation]);

?>