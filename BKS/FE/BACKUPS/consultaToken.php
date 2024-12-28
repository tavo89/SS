<?php
include("../../Conexxx.php");
$curl = curl_init();
$urlApi= 'https://matias-api.com.co/api/ubl2.1/auth/login';
//$usuarioDian = 'myriam-3@hotmail.com';
//$nit_reg_dian = '63352083';
 echo "usu: $usuarioDian,  cla: $nit_reg_dian. Token:<br>";
curl_setopt_array($curl, array(
  CURLOPT_URL => $urlApi,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 90,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => '{"email":"'.$usuarioDian.'", "password":"'.$nit_reg_dian.'","remember_me":0}',
  CURLOPT_HTTPHEADER => array(
    "Cache-Control: no-cache",
    "Content-Type: application/json"
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