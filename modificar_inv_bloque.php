<?php

if($rolLv!=$Adminlvl && !val_secc($id_Usu,"inventario_mod")){header("location: centro.php");}

$val= "";
$boton= "";
$pag="";
$boton= "";
$id_inter="";
$id_pro= "";
$pag=$_SESSION['pag'];
$backURL="inventario_inicial.php";


$serialInv=r("serial_inv");
$serialPro=r("serial_pro");

$nit= "";
$exis= "";
$max= "";
$min= "";
$cost= "";
$pvp= "";
$fra= "";
$check="";
$iva= "";
$gana= "";
$codBarras=$_REQUEST['REF'];
//$idPro=r("idPro");
$idPro=urldecode($_REQUEST["idPro"]);
$feVen=r('fe_ven');

$id="";
if(isset($_REQUEST['REF']))$id=r('REF');
else{header("location: $backURL");}
if(isset($_REQUEST['boton']))

{
	$boton= r('boton');
	$cost= quitacom($_REQUEST['cost']);
	$gana= limpiarcampo($_REQUEST['ganancia']);
	
}


if($boton=='Volver'){header("location:inventario_inicial.php?pag=".$_SESSION['pag']);}

if($boton=='Guardar'){


$id_inter= r('id_inter');
//$id_pro= $_REQUEST['id_pro'];
 	
$exis= r('exis');
$max= 0;//$_REQUEST['max'];
$min= 0;//$_REQUEST['min'];
$cost= quitacom(r('cost'));
$pvp= quitacom(r('pvp'));
$pvpCre= quitacom(r('pvpCre'));
$pvpMay= quitacom(r('pvpMay'));
$fra= limpianum(r('fra'));
$des=rm('des');
//$check=$_REQUEST['ck'];
$iva= r('iva');
$ubi=rm("ubi");
$gana= r('ganancia');
$color= rm('color');
$talla= rm('talla');
$gana2= "";//limpiarcampo($_REQUEST['descuento2']);
$tipoD="";// limpiarcampo($_REQUEST['tipo_dcto']);
$fechaVenci=r('fechaVenci');
$REFE=r("refe");
$tipoProducto=r("tipoProducto");


//echo "UPDATE  motosem.inv_inter SET  id_inter =  '$id_inter',exist =  '$exis',max =  '$max',min =  '$min',costo =  '$cost',precio_v =  '$pvp',fraccion =  '$fra',gana =  '$gana',iva =  '$iva' WHERE inv_inter.id_pro =  '$id'<br>";

$sql="select id_pro from inv_inter where serial_inv='$serialInv'";
//echo "$sql";
$rs2=$linkPDO->query($sql);
$row2=$rs2->fetch();
$ref=$row2['id_pro'];
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;



$fab=rm('fab');
$clas=rm('clas');
$sub_clas=rm('sub_clas');
$pres=rm('pres');
$url_img="";
$url_img=subir_imagen("img_pro", "".tabProductos."");
$aplica_vehi=rm("aplica_vehi");
$DES_FULL=rm("des_full");
//echo $url_img;
$sql="SELECT COUNT(*) as rep FROM inv_inter WHERE   id_pro='$idPro'";
$rs=$linkPDO->query($sql);
$REPETIDOS=0;
if($row=$rs->fetch()){
$REPETIDOS=$row["rep"];

$campo_add_01=r("campo_add_01");
$campo_add_02=r("campo_add_02");
}

//echo "$sql<br> rep: $REPETIDOS";

// actualiza tabla Productos
if($url_img=="-2"){
$sql="UPDATE IGNORE ".tabProductos." SET des_full='$DES_FULL',id_sub_clase='$sub_clas',id_clase='$clas',fab='$fab',presentacion='$pres',detalle='$des', id_pro='$REFE'  Where serial_pro='$serialPro'";

$linkPDO->exec($sql);
}
else{
$sql="UPDATE IGNORE ".tabProductos." SET des_full='$DES_FULL',id_sub_clase='$sub_clas',id_clase='$clas',fab='$fab',presentacion='$pres',detalle='$des', id_pro='$REFE',url_img='$url_img' Where serial_pro='$serialPro'";
$linkPDO->exec($sql);
}

if($REPETIDOS>1){
	$sql="INSERT IGNORE INTO ".tabProductos."(id_pro,detalle,id_clase,frac,fab,presentacion,id_sub_clase,url_img,des_full) VALUES('$idPro','$des','$clas',1,'$fab','$pres','$sub_clas','$url_img','$DES_FULL')";
	$linkPDO->exec($sql);
	}
 


$ref=$REFE;


$cdi=r("cdi");
$envase=r("envase");

// acctualiza tabla inv_inter
$sql="UPDATE IGNORE inv_inter SET  id_inter =  '$id_inter',max =  '$max',min =  '$min',costo =  '$cost',precio_v =  '$pvp',fraccion =  '$fra',gana =  '$gana',iva =  '$iva',dcto2='$gana2',tipo_dcto='$tipoD',certificado_importacion =  '$cdi', pvp_credito='$pvpCre',id_pro='$ref',envase='$envase',pvp_may='$pvpMay',tipo_producto='$tipoProducto' WHERE id_pro='$idPro' AND nit_scs=$codSuc";
$linkPDO->exec($sql);

$sql="UPDATE IGNORE inv_inter SET  ubicacion='$ubi',color =  '$color',talla =  '$talla',certificado_importacion =  '$cdi',aplica_vehi='$aplica_vehi',campo_add_01='$campo_add_01',campo_add_02='$campo_add_02',tipo_producto='$tipoProducto' WHERE id_pro='$idPro' AND nit_scs=$codSuc AND id_inter =  '$id_inter'";
$linkPDO->exec($sql);

if($MODULES["INVENTARIO_PVP_UNIFICADO"]==1){
	
$sql="UPDATE IGNORE inv_inter SET  ubicacion='$ubi',id_inter =  '$id_inter',max =  '$max',min =  '$min',costo =  '$cost',precio_v =  '$pvp',fraccion =  '$fra',gana =  '$gana',iva =  '$iva',dcto2='$gana2',tipo_dcto='$tipoD',color =  '$color',talla =  '$talla',certificado_importacion =  '$cdi', pvp_credito='$pvpCre',id_pro='$ref',envase='$envase',pvp_may='$pvpMay',aplica_vehi='$aplica_vehi',tipo_producto='$tipoProducto' WHERE id_pro='$idPro'  ";
$linkPDO->exec($sql);


}

$print=0;

// actualiza todo el KARDEZ

//$sql="UPDATE IGNORE art_fac_remi SET ref='$ref', cod_barras='$id_inter',des='$des',fraccion =  '$fra' WHERE ref='$idPro' AND cod_barras='$id'";
$sql="UPDATE IGNORE art_fac_remi SET fraccion =  '$fra' WHERE ref='$idPro' AND cod_barras='$id'";
$linkPDO->exec($sql);
if($print){echo "<li>$sql;</li>";}

//$sql="UPDATE IGNORE art_fac_ven SET ref='$ref', cod_barras='$id_inter',des='$des',fraccion =  '$fra' WHERE ref='$idPro' AND cod_barras='$id'";
$sql="UPDATE IGNORE art_fac_ven SET  fraccion =  '$fra' WHERE ref='$idPro' AND cod_barras='$id'";
$linkPDO->exec($sql);
if($print){echo "<li>$sql;</li>";}

//$sql="UPDATE IGNORE art_fac_com SET ref='$ref', cod_barras='$id_inter',des='$des',fraccion =  '$fra' WHERE ref='$idPro' AND cod_barras='$id'";
$sql="UPDATE IGNORE art_fac_com SET  fraccion =  '$fra' WHERE ref='$idPro' AND cod_barras='$id'";
$linkPDO->exec($sql);
if($print){echo "<li>$sql;</li>";}

//$sql="UPDATE IGNORE art_fac_dev SET ref='$ref', cod_barras='$id_inter',des='$des',fraccion =  '$fra' WHERE ref='$idPro' AND cod_barras='$id'";
$sql="UPDATE IGNORE art_fac_dev SET  fraccion =  '$fra' WHERE ref='$idPro' AND cod_barras='$id'";
$linkPDO->exec($sql);
if($print){echo "<li>$sql;</li>";}

//$sql="UPDATE IGNORE art_ajuste SET ref='$ref', cod_barras='$id_inter',des='$des',fraccion =  '$fra' WHERE ref='$idPro' AND cod_barras='$id'";
$sql="UPDATE IGNORE art_ajuste SET  fraccion =  '$fra' WHERE ref='$idPro' AND cod_barras='$id'";
$linkPDO->exec($sql);
if($print){echo "<li>$sql;</li>";}

if($print){echo '<li><span class="uk-button uk-button-large" onClick="location.assign(\'inventario_inicial.php\');"><i class=" uk-icon-history"></i>Volver</span></li>';}


$idPro=$REFE;
$check=1;

$sql="UPDATE inv_inter SET fecha_vencimiento='$fechaVenci' WHERE fecha_vencimiento='$feVen' AND id_inter='$id' AND id_pro='$idPro'";
$linkPDO->exec($sql);

$linkPDO->commit();
if($all_query_ok){

}
else{eco_alert("ERROR! Intente nuevamente");}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

}


$sql="SELECT *,inv_inter.id_inter as cod_bar,".tabProductos.".id_pro as ref,".tabProductos.".presentacion,".tabProductos.".id_clase,".tabProductos.".fab,inv_inter.fraccion,fecha_vencimiento,certificado_importacion,tipo_producto FROM inv_inter INNER JOIN ".tabProductos." ON (inv_inter.id_pro=".tabProductos.".id_pro) WHERE nit_scs=$codSuc AND inv_inter.id_inter='$id' AND inv_inter.id_pro='$idPro' AND fecha_vencimiento='$feVen'";

//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);
