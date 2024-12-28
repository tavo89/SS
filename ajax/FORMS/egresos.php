<?php
include("../../Conexxx.php");
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


$num_comp=serial_comp_egreso();


$feI=r("feI");
$feF=r("feF");
$payComi=r("pay_comi");
$codComi=r("codComi");


$fechaPago=r("fecha_pago");
$valor=limpiarcampo(quitacom($_REQUEST['valor']));	
$concepto=r("concepto");
$tipo_gasto=limpiarcampo($_REQUEST['tipo_gasto']);
$banco=limpiarcampo($_REQUEST['banco']);
$num_cheque=r("num_cheque");
$beneficiario=limpiarcampo($_REQUEST['beneficiario']);
$id_beneficiario=limpiarcampo($_REQUEST['nit']);
$tipo_pago=limpiarcampo($_REQUEST['form_pago']);

$nomBanco="";
$idBanco="";

$banco=r("banco");
if(!empty($banco)){
//$ban = explode("|",$banco);
$nomBanco=r("banco");
$idBanco="";
//eco_alert("banco: $nomBanco, ID_banco: $idBanco");
}
 
$idCtaTrans=r("id_cuenta_TRANS");
$idFacCom=r("num_fac");
$R_FTE=quitacom(r("R_FTE"));
$R_ICA=quitacom(r("R_ICA"));


$idCta=r("id_cuenta");
if(empty($idCta) ){$idCta=val_caja_gral($tipo_pago,"Egresos","-");}

//$forma_pago=limpiarcampo($_REQUEST['tipo_comp']);




$sql="INSERT INTO comp_egreso(num_com,valor,concepto,fecha,cod_su,cajero,tipo_gasto,banco,num_cheque,beneficiario,id_beneficiario,tipo_pago,cod_caja,serial_fac_com,id_banco,id_cuenta,r_fte,r_ica,id_cuenta_trans) VALUES('$num_comp','$valor','$concepto','$fechaPago','$codSuc','$nomUsu','$tipo_gasto','$nomBanco','$num_cheque','$beneficiario','$id_beneficiario','$tipo_pago','$codCaja','$idFacCom','$idBanco','$idCta','$R_FTE','$R_ICA','$idCtaTrans')";	
$linkPDO->exec($sql);



//else {$idCta=0;}

up_cta($tipo_pago,$idCta,$valor-$R_FTE-$R_ICA,"-","Gasto/Egreso Comp:$num_comp","Egresos",$fechaPago);
if(!empty($idCtaTrans)){up_cta($tipo_pago,$idCtaTrans,$valor-$R_FTE-$R_ICA,"+","Gasto/Egreso TRANSFERENCIA Comp:$num_comp","Egresos",$fechaPago);}

$sql="INSERT IGNORE INTO usuarios(id_usu,nombre,cod_su) VALUES('$id_beneficiario','$beneficiario','$codSuc')";
$linkPDO->exec($sql);
$nf=$idFacCom;


$linkPDO->exec("SAVEPOINT A");
if(!empty($nf)){$saldo=saldo_compra_postPago($nf,$codSuc);

if($saldo==0)
{
$sql="UPDATE fac_com SET pago='PAGADO',fecha_pago='$fechaPago' WHERE serial_fac_com='$nf'";
$linkPDO->exec($sql);	
}

}

$sql="SELECT id FROM comp_egreso WHERE num_com='$num_comp'  AND cod_su='$codSuc' FOR UPDATE";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$ID_COMP=$row["id"];
$_SESSION['num_comp_egreso']=$num_comp;
$_SESSION['id_comp_egreso']=$ID_COMP;


$linkPDO->exec("SAVEPOINT B");
if($payComi==1){
	$sql="UPDATE fac_venta SET estado_comi='PAGADO' WHERE nit=$codSuc AND  DATE(fecha)>='$feI' AND DATE(fecha)<='$feF' AND anulado!='ANULADO' AND (cod_comision='$codComi' AND cod_comision!='')";
	$linkPDO->exec($sql);
	}



$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){
echo "1"; 
}
else{echo "500";}
 
}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

?>