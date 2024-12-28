<?php
include_once("../Conexxx.php");

try {
$idCliente=r("idCliente");
$infoPlanes = new Clientes();

$sql="SELECT * FROM servicio_internet_planes WHERE id_cli='$idCliente'";
$rsINI=$linkPDO->query($sql);
$ii=0;
$numFacs=0;
$idServBody="1011001";
$hashFac='';
$serviciosArr = array();
while($rowINI=$rsINI->fetch()){
	$ii++;
	$validaPlan = $infoPlanes->defineMoraCliente($rowINI['ultimoPago'],$rowINI['fechaCobro'], $rowINI['tipoPlan']);
	if(!$validaPlan['estadoPago']){
		set_time_limit(180);
		$codServ=$rowINI["id"];
		$idServ=$rowINI["id"];
		if($fac_servicios_mensuales == 1 && $MODULES["modulo_planes_internet"]==0){
			$fechaVenceHosting = date("Y-m-d", strtotime(date("Y-m-d", strtotime($rowINI['fechaCobro'])) . " + 365 day"));
			$serv = $rowINI["nombre_servicio"];
			$nota=$rowINI["nombreSucursal"]." VENCE: <B>$fechaVenceHosting</B> ";
			$tec_serv="";
			$ivaServ=0;
		} else {
		$serv="PLAN INTERNET ".$rowINI["anchobanda"]." ".$rowINI["tipo_cliente"]." Estrato [".$rowINI["estrato"]."]";
		$nota="";
		$tec_serv="";
		if($rowINI["tipo_cliente"]=="Empresarial" || $rowINI["tipo_cliente"]=="Comercial" || $rowINI["estrato"]>3){$ivaServ=19;}
		else {$ivaServ=0;}
		}
		$pvpServ=$rowINI["precioplan"];
		$hashFac="[$ii]$idServ/ $hoy ";
		$anchobanda=$rowINI["anchobanda"];
		$tipo_cliente=$rowINI["tipo_cliente"];
		$estratoPlan=$rowINI["estrato"];
		
		$serviciosArr[]=array('serv'        =>$serv,
							'nota'        =>$nota,
							'tec_serv'    =>$tec_serv,
							'ivaServ'     =>$ivaServ,
							'pvpServ'     =>$pvpServ,
							'anchobanda'  =>$anchobanda,
							'tipo_cliente'=>$tipo_cliente,
							'estratoPlan' =>$estratoPlan,
							'idServ'      =>$idServ,
							'codServ'    =>$codServ);
	}


}// FIN WHILE GRANDE


// elimina el array con la infor, si ya se factura. Esto detiene el proceso de facturar 2 veces el mismo servicio al mismo cliente
echo "cliente: $idCliente <BR>";
foreach ($serviciosArr as $key => $value) {
	$sqlAuth="SELECT a.num_fac_ven,a.fecha,b.id_serv,estado_pago 
	          FROM fac_venta a INNER JOIN serv_fac_ven b 
			  ON a.num_fac_ven=b.num_fac_ven AND a.prefijo=b.prefijo AND a.nit=b.cod_su 
			  WHERE a.id_cli='$idCliente' AND b.id_serv='$value[idServ]' ORDER BY fecha DESC LIMIT 1";
	$rsAuth=$linkPDO->query($sqlAuth);
    if($rowAuth=$rsAuth->fetch()){
        //unset($serviciosArr[$key]);
    }
}

echo '<pre>Arr:';
print_r($serviciosArr);

if(isset($serviciosArr[0]['idServ']) && !empty($serviciosArr[0]['idServ']))
{
	facturar_planes_mes($hashFac,$idCliente,$serviciosArr);
}

} catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}





?>