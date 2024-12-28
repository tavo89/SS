<?php
function utf8_to_gsm0338($string)
{
$dict = array(
			'@' => "\x00", '£' => "\x01", '$' => "\x02", '¥' => "\x03", 'è' => "\x04", 'é' => "\x05", 'ù' => "\x06", 'ì' => "\x07", 'ò' => "\x08", 'Ç' => "\x09", 'Ø' => "\x0B", 'ø' => "\x0C", 'Å' => "\x0E", 'å' => "\x0F",
			'Δ' => "\x10", '_' => "\x11", 'Φ' => "\x12", 'Γ' => "\x13", 'Λ' => "\x14", 'Ω' => "\x15", 'Π' => "\x16", 'Ψ' => "\x17", 'Σ' => "\x18", 'Θ' => "\x19", 'Ξ' => "\x1A", 'Æ' => "\x1C", 'æ' => "\x1D", 'ß' => "\x1E", 'É' => "\x1F",
			// all \x2? removed
			// all \x3? removed
			// all \x4? removed
			'Ä' => "\x5B", 'Ö' => "\x5C", 'Ñ' => "\x5D", 'Ü' => "\x5E", '§' => "\x5F",
			'¿' => "\x60",
			'ä' => "\x7B", 'ö' => "\x7C", 'ñ' => "\x7D", 'ü' => "\x7E", 'à' => "\x7F",
			'^' => "\x1B\x14", '{' => "\x1B\x28", '}' => "\x1B\x29", '\\' => "\x1B\x2F", '[' => "\x1B\x3C", '~' => "\x1B\x3D", ']' => "\x1B\x3E", '|' => "\x1B\x40", '€' => "\x1B\x65"
		);
		$converted = strtr($string, $dict);
		
		// Replace unconverted UTF-8 chars from codepages U+0080-U+07FF, U+0080-U+FFFF and U+010000-U+10FFFF with a single ?
		return preg_replace('/([\\xC0-\\xDF].)|([\\xE0-\\xEF]..)|([\\xF0-\\xFF]...)/m','?',$converted);
}


function envia_SMS($SMS,$telArray,$auth_basic)
{
	global $enviar_sms;
	if($enviar_sms==1){
	$curl = curl_init();

$POSTFIELDS='{"message":"'.$SMS.'", "tpoa":"22435","recipient":['.$telArray.']}';
//echo "$POSTFIELDS<br>";

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.labsmobile.com/json/send",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 90,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => '{"message":"'.$SMS.'", "tpoa":"22435","recipient":['.$telArray.'],"ucs2": 1}',
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic ".$auth_basic,
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
	}
};

?>

