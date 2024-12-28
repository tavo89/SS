<?php
//****************************************************************** P A R A M E T R O S*************************************************************//
$sede="";$F="";$F2="";
$GROUP_BY=" GROUP BY a.cod_barras,DATE(b.fecha)";
////////////////////////////////////////////////////////////////// FILTRO SEDE ///////////////////////////////////////////////////////////////////
$sede="$codSuc";$F=" a.nit=$codSuc ";$F2=" nit_scs=$codSuc ";
if(isset($_REQUEST['sede'])){$sede=$_REQUEST['sede'];$_SESSION['sede']=$sede;}
if(isset($_SESSION['sede'])){$sede=$_SESSION['sede'];}
$sede=limpiarcampo($sede);
if($sede=="TODAS"){$F=" a.nit>0 ";$F2=" nit_scs>0 ";}
else if($sede!="TODAS"){$F=" a.nit='$sede' ";$F2=" nit_scs='$sede' ";}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$MUN="";
if($sede==1)$MUN="ARAUCA";
else if($sede==2)$MUN="TAME";
else if($sede==3)$MUN="ARAUQUITA";
else if($sede==4)$MUN="SARAVENA";
else $MUN="ARA-TAME-AQ-SARA";

$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}
$FECHA_LIMITE_INI="DATE_SUB(NOW(), INTERVAL 2 MONTH)";
$FECHA_LIMITE_FIN="(NOW())";


//$FECHA_LIMITE_FIN="((LAST_DAY(NOW() - INTERVAL 1 MONTH)+ INTERVAL 1 DAY))";
$SQL_DIAS="DATEDIFF($FECHA_LIMITE_FIN,$FECHA_LIMITE_INI )";

/*
$SQL_DIAS="DATEDIFF( (LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY) , DATE_SUB( ((LAST_DAY(NOW() - INTERVAL 1 MONTH) + INTERVAL 1 DAY)) , INTERVAL 6 
MONTH ) )";
*/

/////////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_ord";
$PAG_fechaF="fechaF_ord";
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" data-inline=\"true\" data-mini=\"true\" >";
if(isset($_REQUEST['fechaI'])){$fechaI=$_REQUEST['fechaI']; $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=$_REQUEST['fechaF'];$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" data-inline=\"true\" data-mini=\"true\" data-icon=\"delete\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$FECHA_LIMITE_INI="'$fechaI'";
	$FECHA_LIMITE_FIN="'$fechaF'";
	$SQL_DIAS="DATEDIFF($FECHA_LIMITE_FIN,$FECHA_LIMITE_INI )";
}





if($opc=="QUITAR")
{
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" data-inline=\"true\" data-mini=\"true\" >";
	$fechaI="";
	$fechaF="";
	$FECHA_LIMITE_INI="DATE_SUB(NOW(), INTERVAL 6 MONTH)";
	$FECHA_LIMITE_FIN="(NOW())";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------





$filtro="Pp";
$filtro_cant="all";$D="";
$filtro_fab="ALL";$C="";
if(isset($_SESSION['filtro']))$filtro=$_SESSION['filtro'];
if(isset($_REQUEST['filtro'])){$filtro=$_REQUEST['filtro'];$_SESSION['filtro']=$filtro;};

if(isset($_SESSION['filtro_cant']))$filtro_cant=$_SESSION['filtro_cant'];
if(isset($_REQUEST['filtro_cant'])){$filtro_cant=$_REQUEST['filtro_cant'];$_SESSION['filtro_cant']=$filtro_cant;};

if(isset($_SESSION['filtro_fab']))$filtro_fab=$_SESSION['filtro_fab'];
if(isset($_REQUEST['filtro_fab'])){$filtro_fab=$_REQUEST['filtro_fab'];$_SESSION['filtro_fab']=$filtro_fab;};


if(!empty($filtro_cant)){
	
	
	if($filtro_cant=="cero")$D=" AND exist=0";
	else if($filtro_cant=="noCero")$D=" AND exist>0";
	else $D=" ";
	
}

if(!empty($filtro_fab)){
	
	
	if($filtro_fab=="ALL")$C=" ";
	else if($filtro_fab=="$NIT_FANALCA")$C=" AND nit_proveedor='$NIT_FANALCA'";
	else if($filtro_fab=="otrosAll")$C=" AND nit_proveedor!='$NIT_FANALCA'";
	else $C=" AND nit_proveedor='$filtro_fab'" ;
	
}


$diasRotacion=60;
$Tr=8;//Tiempo de reposicion de inventario
if(isset($_REQUEST['diasInv'])){$diasRotacion=$_REQUEST['diasInv'];$_SESSION['diasInv']=$diasRotacion;}
if(isset($_SESSION['diasInv']))$diasRotacion=$_SESSION['diasInv'];

if(isset($_REQUEST['diasPro'])){$Tr=$_REQUEST['diasPro'];$_SESSION['diasPro']=$Tr;}
if(isset($_SESSION['diasPro']))$Tr=$_SESSION['diasPro'];



//$Z=1.645;//90%
//$Z=1;//68.27%
$Z=1.96;//95%
//$Z=2;//95.45%
//$Z=2.58;//99%
//$Z=3;//99.73%

$Pp[][]=0;//Punto de pedido
$Cp[][]=0;//consumo medio diario
$Cmx[][]=0;//consumo Max diario
$Cmn[][]=0;//consumi Min diario
$Emx[][]=0;//Existencia Max
$Emn[][]=0;//Existencia Min
$CP[][]=0;//Cantidad de Pedido
$E[][]=0;//Existencia actual
$tot_dias_ventas[][]=0;
$tot_prom_ventas[][]=0;
$tot_cant_vendidas[][]=0;
$tot_vendidos[][]=0;
$xxy[][]=0;
$xyz[][]=0;
$minSeg[][]=0;
$REF_LIST[][]="";
$rs=$linkPDO->query("SELECT nit_scs,id_sede,exist  FROM vista_inventario WHERE $F2");
//$rs=$linkPDO->query("SELECT nit_scs,id_inter id_sede,exist  FROM inv_inter WHERE $F2");
while($row=$rs->fetch())
{
$nit_scs=$row['nit_scs'];
$r=$row['id_sede'];
$REF_LIST[$row['id_sede']]=$row['id_sede'];


$tot_cant_vendidas[$row['id_sede']][$nit_scs]=0;
$tot_dias_ventas[$row['id_sede']][$nit_scs]=0;
$tot_prom_ventas[$row['id_sede']][$nit_scs]=0;
$tot_vendidos[$row['id_sede']][$nit_scs]=0;	
$E[$row['id_sede']][$nit_scs]=$row['exist'];	
$Pp[$row['id_sede']][$nit_scs]=0;
//$Tr[$row['id_inter']]=0;
$Cp[$row['id_sede']][$nit_scs]=0;
$Cmx[$row['id_sede']][$nit_scs]=0;
$Cmn[$row['id_sede']][$nit_scs]=0;//$row['exist'];
$Emx[$row['id_sede']][$nit_scs]=0;
$Emn[$row['id_sede']][$nit_scs]=0;
$CP[$row['id_sede']][$nit_scs]=0;

$minSeg[$row['id_sede']][$nit_scs]=0;
$xxy[$row['id_sede']][$nit_scs]=0;
$xyz[$row['id_sede']][$nit_scs]=0;
}


//////////////////////////////////////////////////////////// QUERY VENTAS DIARIAS ///////////////////////////////////////////////
$colsVentas="a.num_fac_ven,a.prefijo,a.ref,a.cod_barras,a.des,a.cant,a.costo,b.fecha,b.anulado,b.nit,SUM(a.cant) tot_dia,AVG(a.cant) prom_dia,MIN(a.cant) min,MAX(a.cant) max, $SQL_DIAS AS dias";

//$sql="SELECT a.num_fac_ven,a.prefijo,a.ref,a.cod_barras,a.des,a.cant,a.costo,b.fecha,b.anulado,b.nit,SUM(a.cant) tot_dia,AVG(a.cant) prom_dia,MIN(a.cant) min,MAX(a.cant) max, $SQL_DIAS AS dias FROM art_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.cod_barras IN (SELECT id_inter FROM inv_inter) AND a.prefijo=b.prefijo AND a.nit=b.nit AND $F AND b.".VALIDACION_VENTA_VALIDA." AND DATE(fecha)>= $FECHA_LIMITE_INI AND DATE(fecha)<=$FECHA_LIMITE_FIN $GROUP_BY";

$sql="SELECT *, $SQL_DIAS AS dias FROM vista_ventas_rotacion  WHERE  preArt=preFac AND facNit=artNit AND facNit=$codSuc AND ".VALIDACION_VENTA_VALIDA." AND DATE(fecha)>= DATE($FECHA_LIMITE_INI) AND DATE(fecha)<=DATE($FECHA_LIMITE_FIN)  ";
//echo "$sql";
$rs=$linkPDO->query($sql);
$DIAS=0;
$tot_vendidos[]=0;
$FECHA_I="";
$FECHA_F="";
while($row=$rs->fetch())

{
	$ref=$row['cod_barras'];
	if(in_array($ref,$REF_LIST,TRUE)){
		
	$nit_scs=$row['artNit'];
	//$nit_scs=$row['nit'];
	//$FECHA_I=$row['fechaI'];
	//$FECHA_F=$row['fechaF'];
	$ref=$row['cod_barras'];
	$DIAS=$row['dias'];
	$tot_dia=$row['tot_dia'];
	$tot_vendidos[$ref][$nit_scs]+=$tot_dia;
	$prom_dia=$row['prom_dia'];
	$min=$tot_dia;
	$max=$tot_dia;
	$tot_dias_ventas[$ref][$nit_scs]++;
	$tot_prom_ventas[$ref][$nit_scs]+=$prom_dia;
	$tot_cant_vendidas[$ref][$nit_scs]+=$prom_dia;
	if($max>$Cmx[$ref][$nit_scs])$Cmx[$ref][$nit_scs]=$max;
	if($min>0&&$Cmn[$ref][$nit_scs]==0)$Cmn[$ref][$nit_scs]=$min;
	else if($min<$Cmn[$ref][$nit_scs])$Cmn[$ref][$nit_scs]=$min;
	}
};

///////////////////////////////////// ANALISIS PRELIMINAR ///////////////////////////////////////////////////////////////////////////
$rs=$linkPDO->query("SELECT nit_scs,id_sede  FROM vista_inventario WHERE $F2");

$t=sqrt($Tr);
while($row=$rs->fetch())
{
$nit_scs=$row['nit_scs'];
$r=$row['id_sede'];
//if($tot_dias_ventas[$r]>0)$Cp[$r]=$tot_prom_ventas[$r]/$tot_dias_ventas[$r];
if($DIAS>0){
	$Cp[$r][$nit_scs]=$tot_vendidos[$r][$nit_scs]/$DIAS;}
	
//$Cp[$r]=$tot_vendidos[$r]/$DIAS;
//$Cp[$r]=($Cmx[$r]+$Cmn[$r])/2;
//$Emn[$r]=($Cmn[$r])*$Tr;
$Emn[$r][$nit_scs]=redondeo(($Cp[$r][$nit_scs])*$Tr);
//$Emx[$r]=($Cmx[$r] * $Tr)+$Emn[$r];
$Emx[$r][$nit_scs]=($Cmx[$r][$nit_scs] * $Tr)+$Emn[$r][$nit_scs];
}


//////////////////////////////////////////////////////////////SEGUNDO ANALISIS PRE///////////////////////////////////////////////////////////

//$sql="SELECT a.num_fac_ven,a.prefijo,a.ref,a.cod_barras,a.des,a.cant,a.costo,b.fecha,b.anulado,b.nit,SUM(a.cant) tot_dia,AVG(a.cant) prom_dia,MIN(a.cant) min,MAX(a.cant) max,$SQL_DIAS AS dias,$FECHA_LIMITE_INI as fechaI,$FECHA_LIMITE_FIN as fechaF FROM art_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.cod_barras IN (SELECT id_inter FROM inv_inter) AND a.prefijo=b.prefijo AND a.nit=b.nit AND $F AND b.".VALIDACION_VENTA_VALIDA." AND b.fecha>= $FECHA_LIMITE_INI AND b.fecha<=$FECHA_LIMITE_FIN $GROUP_BY";

$sql="SELECT *, $SQL_DIAS AS dias FROM vista_ventas_rotacion  WHERE  preArt=preFac AND facNit=artNit AND facNit=$codSuc AND ".VALIDACION_VENTA_VALIDA." AND DATE(fecha)>= $FECHA_LIMITE_INI AND DATE(fecha)<=$FECHA_LIMITE_FIN  ";

$rs=$linkPDO->query($sql);

while($row=$rs->fetch())
{
	$ref=$row['cod_barras'];
	if(in_array($ref,$REF_LIST,TRUE)){
	$nit_scs=$row['artNit'];
	$r=$row['cod_barras'];
	$tot_dia=$row['tot_dia'];
	$y=$tot_dia-$Cp[$r][$nit_scs];
	//$xxy[$r]+=pow($y,2);
	//$xxy[$r][$nit_scs]+=$y*$y;
	$xxy[$r][$nit_scs]+=pow($y,2);
	
	}
	
	
	
}



///////////////////////////////////// ANALISIS FINAL ///////////////////////////////////////////////////////////////////////////
$rs=$linkPDO->query("SELECT nit_scs,id_sede  FROM vista_inventario WHERE $F2" );
while($row=$rs->fetch())
{
$nit_scs=$row['nit_scs'];
$r=$row['id_sede'];
//$Pp[$r]=redondeo(($Cp[$r]*$Tr)+$Emn[$r]);
//$Pp[$r]=redondeo(($Cp[$r]*$Tr));
if($DIAS>0)$minSeg[$r][$nit_scs]=(sqrt($xxy[$r][$nit_scs]/$DIAS)) * $Z*$t;
$Emn[$r][$nit_scs]=redondeo(($Cp[$r][$nit_scs])+$minSeg[$r][$nit_scs]);
$Emx[$r][$nit_scs]=($Cmx[$r][$nit_scs] * $Tr)+$Emn[$r][$nit_scs];
$Pp[$r][$nit_scs]=redondeo($Cp[$r][$nit_scs]*$Tr +$Emn[$r][$nit_scs]);
$CP[$r][$nit_scs]=$Emx[$r][$nit_scs] - $E[$r][$nit_scs];
}



$busq="";
$val="";
$boton="";
$idx="";
$tabla="inv_inter";
$col_id="id_inter";
$columns="".tabProductos.".id_pro id_glo,inv_inter.id_inter  id_sede,detalle,id_clase,fraccion,fab,max,min,costo,precio_v,exist,iva,gana,fab,nit_scs";
$url="rotacion_inventario.php";
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="agregar_producto.php";
$pag="";
$limit = 6000; 
$order="detalle";
$sort="";
$colArray=array(0=>'id_glo','id_sede','detalle','gana','exist');
$classActive=array(0=>'','','','','');
$offset=0;

if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) { $pag = 1; } 
$offset = ($pag-1) * $limit; 
$ii=$offset;


$idx=s('id');
$boton= r('opc');
$busq=r('busq');
$val= r('valor');

?>