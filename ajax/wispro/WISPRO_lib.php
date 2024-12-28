<?php

function mostrar_contratos($page,$LimiteReg, $auth_basic)
{
	$curl = curl_init();
	
	$URL="https://www.cloud.wispro.co/api/v1/contracts/";
	$POSTFIELDS='{"page":"'.$page.'", "per":"'.$LimiteReg.'"}';

 	//echo "$URL<br> $POSTFIELDS";
 

curl_setopt_array($curl, array(
  CURLOPT_URL => "$URL",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 90,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => $POSTFIELDS,
  CURLOPT_HTTPHEADER => array(
    "Authorization:".$auth_basic,
    "Cache-Control: no-cache",
    "Content-Type: application/json"
  ),
));

curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
$respuesta["err"]=$err;
$respuesta["response"]=$response;

if ($respuesta["err"]) {
  echo "cURL Error #:" . $respuesta["err"];
} else {
  //echo $respuesta["response"];
}

return $respuesta;
};


function mod_contrato($estadoContrato,$idContrato,$auth_basic)
{
	$curl = curl_init();

 echo "https://www.cloud.wispro.co/api/v1/clients/".$idContrato."<br>";

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.cloud.wispro.co/api/v1/contracts/".$idContrato,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 90,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "PUT",
  CURLOPT_POSTFIELDS => '{"state":"'.$estadoContrato.'"}',
  CURLOPT_HTTPHEADER => array(
    "Authorization:".$auth_basic,
    "Cache-Control: no-cache",
    "Content-Type: application/json"
  ),
));

curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
$respuesta["err"]=$err;
$respuesta["response"]=$response;

if ($respuesta["err"]) {
  echo "cURL Error #:" . $respuesta["err"];
} else {
  echo $respuesta["response"];
}

return $respuesta;
};

?>

