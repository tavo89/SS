<?php
$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}

$tipoReport=r("tipoReport");
/////////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_inv";
$PAG_fechaF="fechaF_inv";
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button\">";
$A="";
if(isset($_REQUEST['fechaI'])){$fechaI=limpiarcampo($_REQUEST['fechaI']); $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=limpiarcampo($_REQUEST['fechaF']);$_SESSION[$PAG_fechaF]=$fechaF;}


if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" class=\"uk-button uk-icon-undo\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha_vencimiento)>='$fechaI' AND DATE(fecha_vencimiento)<='$fechaF') ";
}

if($opc=="QUITAR")
{
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button\" >";
	$fechaI="";
	$fechaF="";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	$A="";
}

//////////////////////////////////////////////////////// FILTROS TABLA ////////////////////////////////////////


$FILTRO_CLASE=multiSelcFilter("clases","filtro_clase","id_clase",$opc);
$FILTRO_SUB_CLASE=multiSelcFilter("sub_clases","filtro_sub_clase","id_sub_clase",$opc);
//$FILTRO_LAB="";
$FILTRO_LAB=multiSelcFilter("fabricantes","filtro_lab","fab",$opc);

//filtroExist
$FILTRO_PROVEDORES=multiSelcFilter("provedores","filtro_provedores","nit_proveedor",$opc);
$FILTRO_EXIST=existFilter("filtroExist","filtro_existencias","",$opc);

$FILTRO_DES=desFilter("filtroDes","filtro_descripcion","",$opc);

$FILTRO_VENCIDOS=venciFilter("filtroVencidos","filtroVencidos",$opc);

$FILTROS_TABLA=" $FILTRO_SUB_CLASE $FILTRO_EXIST $FILTRO_CLASE $FILTRO_LAB $FILTRO_DES $FILTRO_VENCIDOS $FILTRO_PROVEDORES";
//echo "$FILTROS_TABLA";
//-----------------------------------------------------------------------------------------------------------//



$busq="";
$val="";
$boton="";
if(isset($_REQUEST['opc'])){
$busq=$_REQUEST['busq'];
$val= $_REQUEST['valor'];
$boton= $_REQUEST['opc'];
}



$tabla="inv_inter";
$col_id="id_pro";

$url="inventario_inicial.php";
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="agregar_inventario.php";
$pag="";
$limit = 20; 
$order="detalle";
 
if(isset($_SESSION['order'])){

if($_SESSION['order']="1")$order="id_glo";
else if($_SESSION['order']="2")$order="id_glo";
else if($_SESSION['order']="3")$order="fab";

}

$ii=$offset;

$columns="fab,ubicacion,".tabProductos.".id_pro id_glo,inv_inter.id_inter  id_sede,detalle,id_clase,fraccion,fab,max,min,costo,precio_v,exist,iva,gana,fecha_vencimiento,unidades_frac,talla,color,".tabProductos.".presentacion";

$sql = "SELECT  $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE nit_scs=$codSuc $A $FILTROS_TABLA ORDER BY detalle "; 

if($tipoReport=="all_su")
{
	
$columns="fab,ubicacion,".tabProductos.".id_pro id_glo,inv_inter.id_inter  id_sede,detalle,id_clase,fraccion,fab,max,min,costo,precio_v,SUM(exist) exist,iva,gana,fecha_vencimiento,SUM(unidades_frac) unidades_frac,talla,color";

$sql = "SELECT  $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE nit_scs!=0 $A $FILTROS_TABLA GROUP BY id_glo,id_sede ORDER BY detalle "; 
}

 $rs=$linkPDO->query($sql);

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM ".tabProductos." INNER JOIN inv_inter ON (inv_inter.id_pro=".tabProductos.".id_pro) WHERE (".tabProductos.".id_pro LIKE '$busq%' OR detalle LIKE '$busq%' OR fab LIKE '$busq%' OR id_clase LIKE '$busq%' OR inv_inter.id_inter LIKE '$busq%')  ";
$rs=$linkPDO->query($sql_busq);
}
?>	

 