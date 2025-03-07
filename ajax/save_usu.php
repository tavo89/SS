<?php
require_once("../Conexxx.php");
	$nombre=limpiarcampo(mb_strtoupper($_REQUEST['cli'],"$CHAR_SET"));
	$ced=limpiarcampo($_REQUEST['ced']);
	$usu=rl('usu');
	$cla=limpiarcampo($_REQUEST['cla']);
	$tipoUsu=limpiarcampo($_REQUEST['tipo_usu']);
	$mail=rl('mail');
	$lv=0;
	if($tipoUsu=="adm")$lv=10;
	else $lv=1;
if(!empty($_REQUEST['ced'])&&!empty($_REQUEST['cli'])&&!empty($_REQUEST['usu'])&&!empty($_REQUEST['cla'])){
	
	$nombre=limpiarcampo(mb_strtoupper($_REQUEST['cli'],"$CHAR_SET"));
	$ced=limpiarcampo($_REQUEST['ced']);
	$usu=limpiarcampo($_REQUEST['usu']);
	$cla=limpiarcampo($_REQUEST['cla']);
	$tipoUsu=limpiarcampo($_REQUEST['tipo_usu']);
	$mail=rl('mail');
	$cursoMejoramiento=r("curso");
	$lv=0;
	if($tipoUsu=="adm")$lv=10;
	else $lv=1;
	
	$usuType=$tipoUsu;
	if($lv==10)$usuType="Administrador";
	if($tipoUsu=="ven")$usuType="Vendedor";
	
	$chofer=0;
	if($tipoUsu=="Conductor")$chofer=1;
	
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sql="SELECT * FROM usuarios WHERE id_usu='$ced'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$sql="UPDATE usuarios SET cliente=0 WHERE id_usu='$ced'";
	$linkPDO->exec($sql);
}


$sql="INSERT IGNORE INTO usuarios(id_usu,nombre,cod_su,cliente,mail_cli,nomina_1) VALUES('$ced','$nombre',$codSuc,0,'$mail','$cursoMejoramiento')";
$linkPDO->exec($sql);
$resp=1;


$sql="INSERT INTO usu_login(id_usu,usu,cla,rol_lv) VALUES('$ced','$usu','$cla',$lv)";
$linkPDO->exec($sql);
$resp=0;


$sql="INSERT INTO tipo_usu(des,id_usu) VALUES('$usuType','$ced')";
$linkPDO->exec($sql);


$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){
echo "1";
}
else{echo "500";}

}catch (Exception $e) {
  echo "Failed: " . $e->getMessage();
}


}
else echo 2;
?>