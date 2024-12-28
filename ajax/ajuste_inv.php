<?php
require_once("../Conexxx.php");

$fecha=$hoy;
$nom=limpiarcampo($nomUsu);
$cc=limpiarcampo($id_Usu);

$num_ajus=serial_ajustes($conex);



$ref=r( 'ref');
$cod_bar=r('cod_barras');
$cant=r("cant");

if(!empty($ref)&&!empty($cod_bar))
{
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;

$num_ajus=serial_ajustes($conex);
$i=0;
$flag=0;

$sql="INSERT INTO ajustes(num_ajuste,fecha,cod_su,nom_usu,id_usu) VALUES($num_ajus,'$fecha',$codSuc,'$nom','$cc')";
$sqlLog="<ul><li>$sql</li>";
$linkPDO->exec($sql);
		
		
		$sql="SELECT * FROM vista_inventario WHERE id_glo='$ref' AND id_sede='$cod_bar'";
		$rs=$linkPDO->query($sql);
		
		if($row=$rs->fetch()){
		$presentacion=$row["presentacion"];
		$det=$row["detalle"];
		$motivo="GARANTIA BATERIA";
		
		$cant_saldo=$row["exist"];
		$cant_saldo=$cant_saldo+$cant;
		
		$uni=0;
		$frac=1;
		$uniSis=0;
		$uniSaldo=$uniSis+$uni;
		
		$feVen=r('fecha_vencimiento');
		if(empty($feVen))$feVen="0000-00-00";
		
		$iva=$row["iva"];
		$util=$row["gana"];
		if(empty($iva))$iva=0;
		$precio=$row["precio_v"];
		$costo=$row["costo"];
		
        $sql="INSERT INTO art_ajuste(num_ajuste,ref,des,cant,costo,precio,util,iva,cod_su,motivo,cant_saldo,cod_barras,presentacion,fraccion,unidades_fraccion,unidades_saldo,fecha_vencimiento) VALUES($num_ajus,'$ref','$det',$cant,$costo,$precio,$util,$iva,$codSuc,'$motivo','$cant_saldo','$cod_bar','$presentacion','$frac','$uni','$uniSaldo','$feVen')";
		$sqlLog.="<li>$sql</li>";
		if(!empty($cant)||!empty($uni)){$linkPDO->exec($sql);}
		else {$flag=1;break;}
		$update="UPDATE inv_inter set exist=exist+$cant WHERE id_inter='$cod_bar' AND nit_scs=$codSuc";
		//t3($Insert1,$Insert2,$update);
		
		
		$sql="UPDATE `inv_inter` i 
INNER JOIN 
(select ar.cod_su nitAr,sum(cant) cant,ref,cod_barras,unidades_fraccion,fecha_vencimiento from art_ajuste ar inner join (select * from ajustes f WHERE num_ajuste=$num_ajus and cod_su='$codSuc' ) fv ON fv.num_ajuste=ar.num_ajuste WHERE fv.cod_su=ar.cod_su and fv.cod_su=$codSuc group by ar.ref,ar.fecha_vencimiento,ar.cod_barras) a 
ON i.id_inter=a.cod_barras 
SET i.exist=(i.exist+a.cant),i.unidades_frac=(i.unidades_frac+a.unidades_fraccion) WHERE i.nit_scs=a.nitAr and i.nit_scs=$codSuc AND i.fecha_vencimiento=a.fecha_vencimiento AND i.id_pro=a.ref";
$linkPDO->exec($sql);


$sqlLog.="<li>$sql</li></ul>";
	$HTML_antes="";
	$HTML_despues="";
	if(isset($_REQUEST['htmlPag']))$HTML_despues=$_REQUEST['htmlPag'];
	logDB($sqlLog,$SECCIONES[8],$OPERACIONES[1],$HTML_antes,$HTML_despues,$num_ajus);
	
$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){
	echo "1";
}
else{echo 0;}


		
		}else {echo "0";}
		
		
}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}
	

}
else echo "0";



   ?>