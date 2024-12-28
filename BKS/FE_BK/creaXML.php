<?php
include_once("../../Conexxx.php");
include_once("facturaElectronica.class.php");
$serial_fac = r('serial_fac');
$num_fac = r('num_fac');
$prefijo = r('prefijo');

$sql="SELECT  AttachedDocument,XmlFileName FROM fac_respuestas_dian WHERE serial_fac ='$serial_fac' ";
$rs = $linkPDO->query($sql);
$xmlBody = '';
$XmlFileName = $prefijo.''.$num_fac;
if($row=$rs->fetch())
{
    $xmlBody = base64_decode($row['AttachedDocument']);
	$XmlFileName = $row['XmlFileName'];
	
}
if(empty($XmlFileName)){
$XmlFileName = $prefijo.''.$num_fac;	
}
/*
echo "<pre>";
echo base64_decode($xmlBody);
echo "</pre>";*/
header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="'.$XmlFileName.'.xml"');
$sxe = new SimpleXMLElement($xmlBody);
$dom = new DOMDocument('1,0');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->loadXML($sxe->asXML());

echo $dom->saveXML();

//$dom->save($XmlFileName.'.xml');
//readfile($XmlFileName.'.xml');
//unlink($XmlFileName.'.xml');
?>