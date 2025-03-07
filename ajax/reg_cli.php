<?php
include_once("../Conexxx.php");
	$cliente=rm('cli');
	$ced=r('ced');
	$cuidad=r('city');
	$dir=rm('dir');
	$tel=r('tel');
	$nit=$_SESSION['cod_su'];
	$mail=rl('mail');
	$sim="";//limpiarcampo($_REQUEST['sim']);
if(!empty($_REQUEST['ced'])&&!empty($_REQUEST['cli'])){
	
	$cliente=rm('cli');
	$ced=r('ced');
	$cuidad=r('city');
	$tipoD="";
	$dir=rm('dir');
	$tel=r('tel');
	$nit=$_SESSION['cod_su'];
	$mail=rl('mail');
	$sim="";//limpiarcampo($_REQUEST['sim']);
	
	$topCre= quitacom(r("top_cre"));
	$auth_cre=r("auth_cre");
	
	$codComision=r("cod_comision");
	$tipoUsu=r("tipo_usu");
	
	$snombr=r("snombr");
	$apelli=r("apelli");
	
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
	

try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


if($MODULES["FACTURACION_ELECTRONICA"]==1){$sql="SELECT * FROM usuarios WHERE id_usu='$ced' ";}
else{$sql="SELECT * FROM usuarios WHERE id_usu='$ced' AND nombre='$cliente' ";}
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
  $sql="UPDATE IGNORE usuarios SET nombre='$cliente',tel='$tel',dir='$dir',
cuidad='$cuidad',mail_cli='$mail',snombr='$snombr', 
apelli='$apelli', claper='$claper',coddoc='$coddoc',paicli='$paicli',
depcli='$depcli',regFiscal='$regFiscal', nomcon='$nomcon',
regtri='$regtri',razsoc='$razsoc' 
WHERE id_usu='$ced'";
$linkPDO->exec($sql);

}
else
{ 

$sql="INSERT IGNORE INTO  usuarios(id_usu,nombre,dir,tel,cuidad,cod_su,cliente,mail_cli,sim,tope_credito,auth_credito,cod_comision,tipo_usu,snombr,apelli,claper,coddoc,paicli,depcli,regFiscal,nomcon,regtri,razsoc,fecha_afiliacion,fecha_suspension,fecha_terminacion) VALUES('$ced','$cliente','$dir','$tel','$cuidad',$codSuc,1,'$mail','$sim','$topCre','1','$codComision','$tipoUsu','$snombr','$apelli','$claper','$coddoc','$paicli','$depcli','$regFiscal','$nomcon','$regtri','$razsoc','$fecha_afiliacion','$fecha_suspension','$fecha_terminacion')";



$linkPDO->exec($sql);

$pageHTML="";
if(isset($_REQUEST['html']))$pageHTML=$_REQUEST['html'];
logDB($sql,"Clientes",$OPERACIONES[1],"NO APLICA",$pageHTML,$ced);

}
$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){
echo "1";
	$_SESSION['id_cli']=$ced;
	$_SESSION['nombre_cli']=$cliente;
	$_SESSION['snombr_cli']=$snombr;
	$_SESSION['apelli_cli']=$apelli;
}
else{echo "500";}

}catch (Exception $e) {
  echo "Failed: " . $e->getMessage();
}

}
else echo 2;
?>