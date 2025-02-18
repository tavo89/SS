<?php
require_once("../Conexxx.php");

$boton= "";
$id_pro= "";
$id_inter= "";
 	
$clas= "";
$des= "";
$fra= "";
$fab="";
$chk="";
$nit="";
$exis=0;
$max="";
$min="";
$gana="";
$cost="";
$pvp="";
$iva="";
$tipoD="";

$clasA="";
$clasB="";
$colorA="";
$colorB="";
$tallaA="";
$tallaB="";

$presenA="";
$presenB="";

$fabA="";
$fabB="";


if(isset($_REQUEST['boton'])){
$boton= $_REQUEST['boton'];
$id_pro=r("id_producto");
$id_pro2=r("id_producto2");


if($id_pro==$id_pro2)$id_pro= serial_id_pro();

if(isset($_REQUEST['claseA']))$clasA= rm('claseA');
if(isset($_REQUEST['claseB']))$clasB= rm('claseB');

$sub_claseA=rm("sub_claseA");

if(isset($_REQUEST['fab']))$fabA= rm('fab');
if(isset($_REQUEST['fabB']))$fabB= rm('fabB');

if(isset($_REQUEST['presentacion']))$presenA= rm('presentacion');
if(isset($_REQUEST['presentacionB']))$presenB= rm('presentacionB');

if(isset($_REQUEST['colorA']))$colorA= limpiarcampo($_REQUEST['colorA']);
if(isset($_REQUEST['colorB']))$colorB= limpiarcampo($_REQUEST['colorB']);

if(isset($_REQUEST['tallaA']))$tallaA= limpiarcampo($_REQUEST['tallaA']);
if(isset($_REQUEST['tallaB']))$tallaB= limpiarcampo($_REQUEST['tallaB']);


$des= rm('des');
$aplica_vehi= rm('aplica_vehi');
$fra=limpianum(r('frac'));
if($fra==0)$fra=1;
$fab= limpiarcampo($_REQUEST['fab']);

$boton= $_REQUEST['boton'];
$id_inter= limpiarcampo($_REQUEST['id_inter']);

$tipoProducto= limpiarcampo($_REQUEST['tipoProducto']);
 	

$exis=0;// $_REQUEST['exis'];
$max=0;// $_REQUEST['max'];
$min=0;// $_REQUEST['min'];
$fechaVen=r('fecha_ven');

$cost= quitacom($_REQUEST['cost']);
$gana= quitacom($_REQUEST['gana']);
$tipoD= "";//limpiarcampo($_REQUEST['tipo_dcto']);
$iva= $_REQUEST['iva'];
$impSaludable= r('impSaludable0');
$pvp= quitacom(r('pvp'));
$pvpCre= quitacom(r('pvpCre'));
$pvpMay= quitacom(r('pvpMay'));
}

//echo "idPro: $id_pro, boton: $boton";
if($boton=='Guardar'  && isset($id_pro) &&!empty($id_pro)){
 
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;



if($id_pro==$id_pro2)$id_pro= serial_id_pro();
$color="";
$talla="";
$clas="";
$presentacion="";
if(!empty($colorA))
{
	
	$sql="INSERT IGNORE INTO colores(color) VALUES('$colorA')";

$linkPDO->exec($sql);
	$color=$colorA;
	
}
else{$color=$colorB;}

if(!empty($fabA))
{
	
	$sql="INSERT IGNORE INTO fabricantes(fabricante) VALUES('$fabA')";

$linkPDO->exec($sql);
	$fab=$fabA;
	
}
else{$fab=$fabB;}

if(!empty($presenA))
{
	
	$sql="INSERT IGNORE INTO presentacion(presentacion) VALUES('$presenA')";
	
$linkPDO->exec($sql);
	$presentacion=$presenA;
	
}
else{$presentacion=$presenB;}


if(!empty($tallaA))
{
	
	$sql="INSERT IGNORE INTO tallas(talla) VALUES('$tallaA')";
	
$linkPDO->exec($sql);
	$talla=$tallaA;
	
}
else{$talla=$tallaB;}


if(!empty($clasA))
{
	
	$sql="INSERT IGNORE INTO clases(des_clas) VALUES('$clasA')";
	
$linkPDO->exec($sql);
	$clas=$clasA;
	
}
else{$clas=$clasB;}

$cdi=r("cdi");
$envase=r("envase");


$sql="INSERT IGNORE INTO sub_clase(des_sub_clase,id_clase) VALUES('$sub_claseA',(select id_clas from clases where des_clas='$clasA'))";
$linkPDO->exec($sql);

$color=mb_strtoupper($color,"$CHAR_SET");
$talla=mb_strtoupper($talla,"$CHAR_SET");
$clas=mb_strtoupper($clas,"$CHAR_SET");

$url_img="";
//$url_img=subir_imagen("img_pro", "".tabProductos."");
//echo "<li>IMG $url_img</li>";

$rs=$linkPDO->query("SELECT * FROM inv_inter WHERE id_inter='$id_inter' AND nit_scs!='$codSuc'");
if($r=$rs->fetch())
{
	
	$idInter=$r['id_inter'];
	$idPro=$r['id_pro'];
	$exist=0;
	$costo=$r['costo'];
	$Fracc=$r['fraccion'];
	$PvP=$r['precio_v'];
	$Util=$r['gana'];
	$Iva=$r['iva'];
	$TipoDcto=$r['tipo_dcto'];
	$Color=$r['color'];
	$Talla=$r['talla'];
	$Fv=$r['fecha_vencimiento'];
	$Cimp=$r['certificado_importacion'];
	$Envase=$r['envase'];
	
	
$sql="INSERT IGNORE INTO  `inv_inter` (`id_pro` ,
                                       `nit_scs` ,
									   `id_inter` ,
									   `exist` ,
									   `max` ,
									   `min` ,
									   `costo` ,
									   `precio_v` ,
									   `fraccion`,
									   `gana`,
									   `iva`,
									   `tipo_dcto`,
									   `color`,
									   `talla`,
									   `fecha_vencimiento`,
									   `certificado_importacion`,
									   `envase`,
									   `pvp_credito`,
									   `pvp_may`,
									   impuesto_saludable,
									   aplica_vehi) 
							   VALUES ('$idPro',  
							            $codSuc,  
									   '$idInter',  
									   '$exist',  
									   '$max',  
									   '$min',  
									   '$costo',  
									   '$PvP',  
									   '$Fracc',
									   '$Util',
									   '$Iva',
									   '$TipoDcto',
									   '$Color',
									   '$Talla',
									   '$Fv',
									   '$Cimp',
									   '$Envase',
									   '$pvpCre',
									   '$pvpMay',
									   '$impSaludable',
									   '$aplica_vehi')";
$check=1;
//echo "$sql";
$linkPDO->exec($sql);
}
else{
$sql="INSERT IGNORE INTO ".tabProductos." (id_pro,detalle,id_clase,frac,fab,presentacion,id_sub_clase,url_img) VALUES ('$id_pro','$des','$clas',$fra,'$fab','$presentacion','$sub_claseA','$url_img')";
//echo "<li>$sql</li>";
$linkPDO->exec($sql);


$sql="INSERT IGNORE INTO  `inv_inter` (`id_pro` ,
                                       `nit_scs` ,
									   `id_inter` ,
									   `exist` ,
									   `max` ,
									   `min` ,
									   `costo` ,
									   `precio_v` ,
									   `fraccion`,
									   `gana`,
									   `iva`,
									   `tipo_dcto`,
									   `color`,
									   `talla`,
									   `fecha_vencimiento`,
									   `certificado_importacion`,
									   `envase`,
									   `pvp_credito`,
									   `pvp_may`,
									   tipo_producto,
									   impuesto_saludable,
									   aplica_vehi) 
							   VALUES ('$id_pro',  
							            $codSuc,  
									   '$id_inter',  
									   '$exis',  
									   '$max',  
									   '$min',  
									   '$cost',  
									   '$pvp',  
									   '$fra',
									   '$gana',
									   '$iva',
									   '$tipoD',
									   '$color',
									   '$talla',
									   '$fechaVen',
									   '$cdi',
									   '$envase',
									   '$pvpCre',
									   '$pvpMay',
									   '$tipoProducto',
									   '$impSaludable',
									   '$aplica_vehi')";
$check=1;
$linkPDO->exec($sql);
}


$id_serial=r("id_serial");
$cur_serial=r("cur_serial");

if($id_pro==$cur_serial && !empty($id_serial)){
	$sql="UPDATE seriales_inv set current=current+1 WHERE id='$id_serial'";
	$linkPDO->exec($sql);
	
}

$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){
$chk=1;
}
else{$chk=0;}
}catch (Exception $e) {
    echo "Failed: " . $e->getMessage();
}


}

echo "$chk";
?>