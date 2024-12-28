<?php
$codSuc=s('cod_su');
if(empty($codSuc)){$codSuc = (isset($_REQUEST['su']))? $_REQUEST['su'] : 1;}

$qry="SELECT sucursal.*,departamento.departamento,municipio.municipio FROM sucursal,departamento,municipio WHERE  sucursal.cod_su='$codSuc' ";

$qry="SELECT  sucursal.*,
(SELECT departamento FROM departamento WHERE departamento.id_dep=sucursal.id_dep) AS departamento,
(SELECT municipio FROM municipio WHERE municipio.id_mun=sucursal.id_mun)          AS municipio,
usuarios.nombre,
usuarios.firma_op,usuarios.tel,usuarios.cod_caja as cc,
usuarios.fecha_crea 
FROM usuarios,sucursal
WHERE sucursal.cod_su='$codSuc' LIMIT 1";
//echo $qry;
$rs=$linkPDO->query($qry);
if($row=$rs->fetch()){

	include('variablesSistema.php');

$sql="SELECT * FROM x_config WHERE cod_su=1";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
	$_SESSION[$row["des_config"]]=$row["val"];
}

if($codSuc!=1){
    $_SESSION['url_LOGO_A']= $logoSucursalA;
	$_SESSION['url_LOGO_B']= $logoSucursalB;
}

}

$RESOLUCIONES=resol();

$FX_MENU_TOP=r('ACTIVE_FLUX');
if(!empty($FX_MENU_TOP)){
	$_SESSION['FLUJO_INVENTARIO']=$FX_MENU_TOP;
	//eco_alert("fx: $FX_MENU_TOP");
	}
	$FLUJO_INV=$_SESSION['FLUJO_INVENTARIO'];

$codContadoSuc=$RESOLUCIONES['POS'][0];
$ResolContado=$RESOLUCIONES['POS'][1];
$FechaResolContado=$RESOLUCIONES['POS'][2];
$RangoContado=$RESOLUCIONES['POS'][3];

$codCreditoSuc=$RESOLUCIONES['COM'][0];
$ResolCredito=$RESOLUCIONES['COM'][1];
$FechaResolCredito=$RESOLUCIONES['COM'][2];
$RangoCredito=$RESOLUCIONES['COM'][3];

$codPapelSuc=$RESOLUCIONES['PAPEL'][0];
$ResolPapel=$RESOLUCIONES['PAPEL'][1];
$FechaResolPapel=$RESOLUCIONES['PAPEL'][2];
$RangoPapel=$RESOLUCIONES['PAPEL'][3];

$codCreditoANTSuc=$RESOLUCIONES['CRE'][0];
$ResolCreditoANT=$RESOLUCIONES['CRE'][1];
$FechaResolCreditoANT=$RESOLUCIONES['CRE'][2];
$RangoCreditoANT=$RESOLUCIONES['CRE'][3];

$codRemiPOS=$RESOLUCIONES['REM_POS'][0];
$ResolRemiPOS=$RESOLUCIONES['REM_POS'][1];
$FechaRemiPOS=$RESOLUCIONES['REM_POS'][2];
$RangoRemiPOS=$RESOLUCIONES['REM_POS'][3];

$codRemiCOM=$RESOLUCIONES['REM_COM'][0];
$ResolRemiCOM=$RESOLUCIONES['REM_COM'][1];
$FechaRemiCOM=$RESOLUCIONES['REM_COM'][2];
$RangoRemiCOM=$RESOLUCIONES['REM_COM'][3];

$codRemiCOM2=$RESOLUCIONES['REM_COM2'][0];
$ResolRemiCOM2=$RESOLUCIONES['REM_COM2'][1];
$FechaRemiCOM2=$RESOLUCIONES['REM_COM2'][2];
$RangoRemiCOM2=$RESOLUCIONES['REM_COM2'][3];


$nomSuc=$_SESSION['nom_su'];
$dirSuc=$_SESSION['dir_su'];
$tel1Su=$_SESSION['tel1_su'];
$tel2Su=$_SESSION['tel2_su'];

//$nomUsu=htmlentities($_SESSION['nom_usu'],ENT_QUOTES,"$CHAR_SET");
$nomUsu=s('nom_usu');
$id_Usu='999999';
$rolUsu='Admin';
$rolLv='1';
$munSuc=$_SESSION['municipio'];
$depSuc=$_SESSION['departamento'];
$email_sucursal=$_SESSION["mail_su"];
//echo "$email_sucursal mail";

$codCaja=1;

$IP_CLIENTE='-----';
$fechaCreaUsu=s("fecha_crea_usu");
?>