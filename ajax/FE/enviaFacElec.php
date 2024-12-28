<?php
include_once("facturaElectronica.class.php");

$urlApi= $company_fe=='DATAICO'?'https://api.dataico.com/direct/dataico_api/v2/invoices' :'https://api.matias-api.com/api/ubl2.1/invoice';
$formateaJson = $company_fe=='DATAICO' ? false:true;
try {
// datos factura
set_time_limit(60);
$serial_fac = r('serial_fac');
$num_fac = r('num_fac');
$prefijo = r('prefijo');
$codSuc  = r('codSuc');
$hash    = r('hash');
$filtroHash=" AND hash='$hash'";
if(empty($hash) || $hash==0)$filtroHash="";

$sql="SELECT estado_factura_elec,fecha FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' 
  AND nit='$codSuc' $filtroHash";

$rs=$linkPDO->query($sql);
$estadoDian = '';
if($row=$rs->fetch()){
  $estadoDian = $row['estado_factura_elec'];
  $fecha = explode(' ', $row['fecha']);
  $fechaFactura = $fecha[0];
  $horaFactura= $fecha[1];
  //var_dump($fecha);

    }
if($estadoDian!=1){
    
actualizaFechaFE($num_fac,$prefijo,$codSuc,$fechaFactura,$horaFactura);

$camposFactura = creaCamposFacElec($num_fac,$prefijo,$codSuc,$hash);

$resp = enviaDocumentoDian($urlApi,$camposFactura,$formateaJson);
$respArr = json_decode($resp,true);

if($hostName=='preproduccion.nanimosoft.com....'){
echo "$urlApi <br>";
echo "token $tokenDianOperaciones <br>";

if($company_fe=='DATAICO'){
    echo "formatoJson: ".PHP_EOL;
    echo ($camposFactura);
} else {
    //print_r ($camposFactura);
}

echo "<br>Respuesta:<pre>";
print_r($respArr);
}

if($company_fe!='DATAICO'){
$CUFE         = isset($respArr['XmlDocumentKey']) ? $respArr['XmlDocumentKey'] : '';
$XML          = isset($respArr['AttachedDocument']) ? $respArr['AttachedDocument'] : ''; 
$XmlFileName  = isset($respArr['XmlFileName']) ? $respArr['XmlFileName'] : ''; 
$ZipKey       = isset($respArr['ZipKey']) ? $respArr['ZipKey'] : ''; 


if($respArr['success'] == 1 && $respArr['message']!='Documento con errores en campos mandatorios.'){
    $sql = "INSERT INTO `fac_respuestas_dian` (`serial_fac`, `ZipKey`, `ZipBase64Bytes`, `message`, `success`, `type_document_id`, `cufe`, `AttachedDocument`,`XmlFileName`) 
        VALUES ('".$serial_fac."', '".$ZipKey."', 
        '".$respArr['ZipBase64Bytes']."', 
        '".$respArr['message']."', 
        '".$respArr['success']."',
        '7',
		'".$CUFE."',
		'".$XML."',
		'".$XmlFileName."')";
		//echo $sql;
    t1($sql);
	
	
	$sql = "UPDATE fac_venta SET estado_factura_elec = '".$respArr['success']."',
    resp_dian = '".$respArr['message']."',
	cufe = '$CUFE'
    WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo'
	          AND nit='$codSuc' $filtroHash";
    t1($sql);

} else {
	
$sql = "UPDATE fac_venta SET estado_factura_elec = '0',
    resp_dian = '".$respArr['message']."' 
    WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo'
	          AND nit='$codSuc' $filtroHash";
t1($sql);	
}



 
echo $resp;
}// fin FE matias Api
else {

	if(!empty($respArr['dian_status']) && $respArr['dian_status']=='DIAN_ACEPTADO'){
		 
		 $sqlA = "INSERT INTO `fac_respuestas_dian` (`serial_fac`, `ZipKey`, `ZipBase64Bytes`, `message`, `success`, `type_document_id`, `cufe`, `AttachedDocument`,`XmlFileName`) 
        VALUES ('".$serial_fac."', ' ', 
        ' ', 
        ' ', 
        ' ',
        '7',
		'".$respArr['cufe']."',
		'".$respArr['xml']."',
		'".$respArr['number']."')";
		t1($sqlA);
	
	$params = array(
        'estadoFac'=>'1',
        'dian_status'=>$respArr['dian_status'],
        'cufe'=>$respArr['cufe'],
        'numeroFac'=>$num_fac,
        'prefijoFac'=>$prefijo,
        'sucursal'=>$codSuc
    );
    actualizaEstadoFactura($params);
    
	$resp = array('message'=>"Factura ".$respArr['number']." Enviada",
                  'success'=>1,
                  'Error'=>'');
    $resp = json_encode($resp);
    echo $resp;
		} 
		else {
			    $errorPath ='';
				$last=count($respArr['errors'][0]['path']);
				$i=0;
				foreach($respArr['errors'][0]['path'] as $key =>$val){
					$i++;
					$separador= ($last==$i)?'':'->';
					$errorPath.=$val.$separador;
				}
                if(!empty($errorPath )){
				    $errorPath = '. Campo: '.$errorPath;	
				}
                $params = array(
                    'estadoFac'=>'500',
                    'dian_status'=>limpiarcampo($respArr['errors'][0]['error'].$errorPath),
                    'cufe'=>'',
                    'numeroFac'=>$num_fac,
                    'prefijoFac'=>$prefijo,
                    'sucursal'=>$codSuc
                );
                actualizaEstadoFactura($params);
				$resp = array('message'=>"ERROR",
                  'success'=>0,
                  'Error'=>$respArr['errors'][0]['error'].$errorPath);
                $resp = json_encode($resp);
				echo $resp;
			
}

	}
}else {
    $resp = array('message'=>"Esta Factura $prefijo $num_fac ya se Envio",
                  'success'=>1,
                  'Error'=>'');
    $resp = json_encode($resp);
    echo $resp;
}



} catch (Exception $e) {
    echo 'ERROR:  ' . $e->getMessage()."<br>Line:".$e->getLine()."<br>Code:".$e->getCode();
                    }
?>