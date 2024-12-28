<?php
include_once("../../Conexxx.php");
include_once("facturaElectronica.class.php");
//$tokenDianOperaciones = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiOWE1NGRmNDQyZmUxZDFlMTYzOWU0YTU2MThlMmU2MTU1NDdhM2QxYjZkZWJiM2MwNGVmYzFkYzM0NjRkYmM4MzUzODJhZGVhNWUyNzFkZjIiLCJpYXQiOjE2MDc1NzkxOTUsIm5iZiI6MTYwNzU3OTE5NSwiZXhwIjoxNjM5MTE1MTk1LCJzdWIiOiIyMyIsInNjb3BlcyI6W119.kH3RvyrJbAhvslRJfjTqocQIk-eM4Z0a8XWiurUqenzoSn4PjW7N0LwWBxD6yQzsltgo_dDGSJliavLuz5CRQD7uq_xo3zcKjRAI9ebCE70wfw9GWvCb1IQHHJ0L5L5rnMN6AE_jHKumyFf4-HtbRKbGHqvZv1qFA6WN7PxmZzeHXwTO8lrog4PxWBQhsMC_ABKnY5HkPwvh6gvRuM0HXiC2xYaMgqop_zIwR3VVZf5-rJ5CkRrapBgcFZGzpUu5w9C03Aq41_Ut7pwT20IFt5pUEdmNLyNSfwHAp297-2EPrMDCS_mwynN4tgzd0eCT3e3Aa3ZZsHPGBRfnOGnsnslF1jsJ9kLEWG8XtiA9wyzC-jPi7VOSTxWiCNbFgIqG08btfnwsxoapbnriaD4uR3v-SrCe-QWvmkXMLILBbWaTdCuYSVMNfO8R6VU5vybaOGSWEJWX9-up6CuB1c0SNhwv1crHEYeM2kdhuPrTNKBI5YW_uc19NbMvHFtqQ9dH_dIMpzFiUHoD9eX3TxTXZusOq8Mpdq6V6g73MSGxz8AoX4dPGX02J4_4t_Z9Fe9BdHsdt6nFEfxUoo0mXCFkj7wbyhq7bR2tBbjwUs6scYBbSjkm1hZAF_1LJ6EN-crB59Kvyv_YOtAofNL_1hAffZY7K3rbuKsbfFr7g1nwFaM';

//$usuarioDian = 'dario_123blanco@hotmail.com';
//$nit_reg_dian = '96194059';
//  /status/zip/d418bc64-ecba-405d-8c89-bc0685a25b99
$urlApi= 'https://matias-api.com.co/api/ubl2.1/typeitemidentifications';
//$urlApi= 'https://matias-api.com.co/api/ubl2.1/status/zip/1c631aff-c80d-4604-baea-6ff6dca99c36';

$res=(consultarCamposDian($urlApi,$tokenDianOperaciones));
//$res=(enviaDocumentoDian($urlApi,$tokenDianOperaciones,array()));
echo 'RESP:<BR>'.$res;
//$respArr = json_decode($res,true);
//print_r($respArr);
/*
$sel = '<br><select name="city" id="city">';
foreach ($respArr['records'] as $key => $value) {
	
	echo "key: $key<br><pre>";
	print_r($value);
	echo '</pre>';
    
	$sel.='<option value="'.$value['id'].'">'.$value['name_city'].'</option>';
}

$sel.= '</select>';

echo $sel;*/
?>