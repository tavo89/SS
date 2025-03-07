<?php
include_once("../Conexxx.php");
	$cliente=rm('cli');
	$ced=r('ced');
	$NEWced=r('NEWced');
	$city=rm('city');
	$dir=limpiarcampo(strtoupper($_REQUEST['dir']));
	$tel=limpiarcampo($_REQUEST['tel']);
	$nit=limpiarcampo($_SESSION['cod_su']);
	$mail=limpiarcampo(strtolower($_REQUEST['mail']));
	$sim="";//limpiarcampo($_REQUEST['sim']);
	
	$COD_CAJA=r("cod_caja");
if(!empty($_REQUEST['ced'])&&!empty($_REQUEST['cli'])){
	
	$cliente=limpiarcampo(strtoupper($_REQUEST['cli']));
	$ced=limpiarcampo($_REQUEST['ced']);
	$nomA=r("cliA");
	$cuidad=limpiarcampo(strtoupper($_REQUEST['city']));
	$dir=limpiarcampo(strtoupper($_REQUEST['dir']));
	$tel=limpiarcampo($_REQUEST['tel']);
	$nit=$_SESSION['cod_su'];
	$mail=limpiarcampo(strtolower($_REQUEST['mail']));
	$sim="";//limpiarcampo($_REQUEST['sim']);
	$topCre= quitacom(r("top_cre"));
	
	$auth_cre=r("auth_cre");
	
	$tipoUsu=r("tipo_usu");
	$codComision=r("cod_comision");
	
	$aliasCli=rm("aliasCli");
	

	
	
	$usu="";
	$cla="";
	$t_usu="";
	$fechaBan=r("fecha_ban");
	$montoBan=quitacom(r("monto_ban"));
	
	$fechaBanRemi=r("fecha_ban_remi");
	$montoBanRemi=quitacom(r("monto_ban_remi"));
	
	$refID=r("ref_id");
	if(isset($_REQUEST['usu']))$usu=limpiarcampo($_REQUEST['usu']);
	
	if(isset($_REQUEST['cla']))$cla=limpiarcampo($_REQUEST['cla']);
	
	if(isset($_REQUEST['tipo_usu']))$t_usu=limpiarcampo($_REQUEST['tipo_usu']);
	
	$snombr=r("snombr");$snombrA=r("snombrA");
	$apelli=r("apelli");$apelliA=r("apelliA");
	
	$claper=r("claper");
	
	$coddoc=r("coddoc");
	$paicli=r("paicli");
	$depcli=r("depcli");
	$regFiscal=r("regFiscal");
	$nomcon=r("nomcon");
	$regtri=r("regtri");
	$razsoc=r("razsoc");
	
	$fecha_afiliacion=r("fecha_afiliacion");
	$fecha_suspension=r("fecha_suspension");
	$fecha_terminacion=r("fecha_terminacion");
	
	if($company_fe=='DATAICO'){
	$depcli = substr($city, 0, 2);
	$cuidad =$city;
	}
	
$cursoMejoramiento=r("curso");
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;



if( 1){$sql="UPDATE IGNORE usuarios SET cod_caja='$COD_CAJA',tipo_usu='$tipoUsu',id_usu='$NEWced',nombre='$cliente',dir='$dir',tel='$tel',cuidad='$cuidad',cod_su='$codSuc',mail_cli='$mail',sim='$sim', auth_credito='$auth_cre',tope_credito='$topCre',fecha_ban='$fechaBan',monto_ban='$montoBan',fecha_ban_remi='$fechaBanRemi',monto_ban_remi='$montoBanRemi',cod_comision='$codComision',alias='$aliasCli', nomina_1='$cursoMejoramiento',snombr='$snombr', apelli='$apelli', claper='$claper',coddoc='$coddoc',paicli='$paicli',depcli='$depcli',regFiscal='$regFiscal', nomcon='$nomcon',regtri='$regtri',razsoc='$razsoc',fecha_afiliacion='$fecha_afiliacion',fecha_suspension='$fecha_suspension',fecha_terminacion='$fecha_terminacion' WHERE id_usu='$ced' AND (nombre='$nomA' AND snombr='$snombrA' AND apelli='$apelliA')";}

else{$sql="UPDATE IGNORE usuarios SET cod_caja='$COD_CAJA',tipo_usu='$tipoUsu',id_usu='$NEWced',nombre='$cliente',dir='$dir',tel='$tel',cuidad='$cuidad',cod_su='$codSuc',mail_cli='$mail',sim='$sim', auth_credito='$auth_cre',tope_credito='$topCre',fecha_ban='$fechaBan',monto_ban='$montoBan',fecha_ban_remi='$fechaBanRemi',monto_ban_remi='$montoBanRemi',cod_comision='$codComision',alias='$aliasCli', nomina_1='$cursoMejoramiento',fecha_afiliacion='$fecha_afiliacion',fecha_suspension='$fecha_suspension',fecha_terminacion='$fecha_terminacion' WHERE id_usu='$ced' AND nombre='$nomA'";}

//echo "$sql";
$linkPDO->exec($sql);

if(!empty($refID))
{
	$colID="id_cli";
	$valID=$ced;
	
if($refID=="cc"){

		
		}

if($MODULES["FACTURACION_ELECTRONICA"]==1){
if($refID=="nombre"){

$colID="nom_cli";
$valID=$nomA;
$whereCON="(nom_cli='$nomA' AND snombr='$snombrA' AND apelli='$apelliA')";
	
$sql="UPDATE cartera_mult_pago SET id_cli='$NEWced' WHERE id_cli='$ced'";
$linkPDO->exec($sql);

$sql="UPDATE comprobante_ingreso SET id_cli='$NEWced' WHERE id_cli='$ced'";
$linkPDO->exec($sql);

$sql="UPDATE servicio_internet_planes SET id_cli='$NEWced' WHERE id_cli='$ced'";
$linkPDO->exec($sql);
}else {
	$whereCON=" $colID='$valID' ";
	}

$sql="UPDATE fac_venta 
	  SET nom_cli='$cliente',
	  id_cli='$NEWced',
	  tel_cli='$tel',
	  dir='$dir',
	  ciudad='$cuidad',
	  mail='$mail', 
	  depcli='$depcli',
	  claper='$claper',
	  regtri='$regtri', 
	  razsoc='$razsoc', 
	  coddoc='$coddoc', 
	  regFiscal='$regFiscal'
WHERE $whereCON";
$linkPDO->exec($sql);
//echo "$sql";
$sql="UPDATE fac_remi SET nom_cli='$cliente',id_cli='$NEWced',tel_cli='$tel',dir='$dir',ciudad='$cuidad',mail='$mail' WHERE $whereCON";
$linkPDO->exec($sql);
}// fin mod facElec


else {

if($refID=="nombre"){
$colID="nom_cli";$valID=$nomA;
	
$sql="UPDATE cartera_mult_pago SET id_cli='$NEWced' WHERE id_cli='$ced'";
$linkPDO->exec($sql);

$sql="UPDATE comprobante_ingreso SET id_cli='$NEWced' WHERE id_cli='$ced'";
$linkPDO->exec($sql);

$sql="UPDATE servicio_internet_planes SET id_cli='$NEWced' WHERE id_cli='$ced'";
$linkPDO->exec($sql);
}

$sql="UPDATE fac_venta SET nom_cli='$cliente',id_cli='$NEWced',tel_cli='$tel',dir='$dir',ciudad='$cuidad',mail='$mail', 
depcli='$depcli',claper='$claper',regtri='$regtri', razsoc='$razsoc', coddoc='$coddoc', regFiscal='$regFiscal'
 WHERE $colID='$valID'";
$linkPDO->exec($sql);

$sql="UPDATE fac_remi SET nom_cli='$cliente',id_cli='$NEWced',tel_cli='$tel',dir='$dir',ciudad='$cuidad',mail='$mail' WHERE $colID='$valID'";
$linkPDO->exec($sql);
	
}// fin else no FacElec
}
$_SESSION['id_cli']=$NEWced;

$HTML_antes="";
$HTML_despues="";
if(isset($_REQUEST['html_antes']))$HTML_antes=$_REQUEST['html_antes'];
if(isset($_REQUEST['html_despues']))$HTML_despues=$_REQUEST['html_despues'];
logDB($sql,"Clientes",$OPERACIONES[2],$HTML_antes,$HTML_despues,$ced);


$fecha=$hoy;
$nom=$cliente;
$cc=$ced;

if(isset($_REQUEST['num_d']))$nd=limpiarcampo(strtoupper($_REQUEST['num_d']));
else $nd=0;
for($i=0;$i<$nd;$i++)
{
	if(!empty($_REQUEST['dcto_'.$i])&&!empty($_REQUEST['tipo_dcto'.$i])&&!empty($_REQUEST['fab'.$i]))
	{
		$fab=limpiarcampo($_REQUEST['fab'.$i]);
		$dcto=limpiarcampo(limpiafloat($_REQUEST['dcto_'.$i]));
		$tipoD=limpiarcampo($_REQUEST['tipo_dcto'.$i]);
		$sql="SELECT * FROM dcto_fab WHERE id_cli='$ced' AND fabricante='$fab'";
		$rs=$linkPDO->query($sql);
		if($row=$rs->fetch())
		{	
		$sql="UPDATE dcto_fab SET id_cli='$NEWced',fabricante='$fab',dcto='$dcto',usu='$nom',id_usu='$NEWced',cod_su='$codSuc',tipo_dcto='$tipoD',id_usu='$id_Usu',usu='$nomUsu' WHERE id_cli='$ced' AND fabricante='$fab'";
$linkPDO->exec($sql);
		}
		else
		{
			$sql="INSERT INTO dcto_fab(id_cli,fabricante,dcto,usu,id_usu,cod_su,tipo_dcto) VALUES('$NEWced','$fab',$dcto,'$nomUsu','$id_Usu',$codSuc,'$tipoD')";
			$linkPDO->exec($sql);
		}
	}
};




$sql="SELECT * FROM usu_login WHERE usu='$usu' AND id_usu='$ced'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$sql="UPDATE usu_login SET cla='$cla' WHERE  usu='$usu' AND id_usu='$ced'";
	$linkPDO->exec($sql);
	if(!empty($t_usu)){$sql="UPDATE tipo_usu SET des='$t_usu' WHERE  id_usu='$ced'";
					$linkPDO->exec($sql);
	}
		
}
else
{
	
}


$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){
echo "1";
}
else{echo "0";}

}catch (Exception $e) {
  echo "Failed: " . $e->getMessage();
}

}
else echo 2;
?>