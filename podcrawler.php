<?php
require_once __DIR__ . '/config.php';


//grab pods from the-federation
$chss = curl_init();
curl_setopt($chss, CURLOPT_URL, 'https://the-federation.info/pods.json');
curl_setopt($chss, CURLOPT_CONNECTTIMEOUT, 19);
curl_setopt($chss, CURLOPT_TIMEOUT, 19);
curl_setopt($chss, CURLOPT_RETURNTRANSFER, 1);
$output      = curl_exec($chss);
curl_close($chss);
$json = json_decode($output);

foreach($json->pods as $mydata) {
  $addjson = exec('php-cgi db/add.php domain='.$mydata->host);
  echo $addjson."\r\n";
}
