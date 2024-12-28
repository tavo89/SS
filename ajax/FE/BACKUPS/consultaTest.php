<?php
include_once("../../Conexxx.php");
include_once("facturaElectronica.class.php");
$serial_fac = r('serial_fac');
//$zipkey = '992664f8-5026-4b8b-aa66-f83d4c3de77a';
$sql="SELECT  * FROM fac_respuestas_dian WHERE serial_fac ='$serial_fac' ";
$rs = $linkPDO->query($sql);
if($row=$rs->fetch())
{
    $zipkey = $row['ZipKey'];
	
}
$urlApi= 'https://matias-api.com.co/api/ubl2.1/status/zip/'.$zipkey;
//echo $urlApi.'<br>';
$res=(enviaDocumentoDian($urlApi,$tokenDianOperaciones,array()));
echo $res;
?>