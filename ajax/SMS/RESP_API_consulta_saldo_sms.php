<?php
$auth_basic = base64_encode("admin@interplustel.com:PvJgMdN4bEqTjLv2zyWTIFX8WgzPdUFS");

$curl = curl_init();



curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.labsmobile.com/json/balance",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic ".$auth_basic,
    "Cache-Control: no-cache",
  ),
));

curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
?>