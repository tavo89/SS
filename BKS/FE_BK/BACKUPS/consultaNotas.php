<?php
include_once("../../Conexxx.php");
include_once("facturaElectronica.class.php");
$serial_fac = r('serial_fac');

$zipkey = '56afa307-596f-4067-9c51-dbe12e8f7ddb';
$sql="SELECT  * FROM fac_respuestas_dian WHERE serial_fac ='$serial_fac' ";
$rs = $linkPDO->query($sql);
if($row=$rs->fetch())
{
    //$zipkey = $row['ZipKey'];
	
}
$urlApi= 'https://matias-api.com.co/api/ubl2.1/status/zip/'.$zipkey;
echo $urlApi.'<br>';
$res=(enviaDocumentoDian($urlApi,$tokenDianOperaciones,array()));
echo $res;
?>