<?php
$fields = ['filters' => ['cat' => [], 'plat' => []]];
$fields['filters']['cat'][] = 1;
$fields['filters']['plat'][] = 1;

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://projectimago.com/api/v1/getLive');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
	"Content-Type: application/json",
	"Authentication-Info: JAKELBSCAJMAG87P"
]);

$res = curl_exec($ch);
$json = json_decode($res, True);
var_dump($res);
?>