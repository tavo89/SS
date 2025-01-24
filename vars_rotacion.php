<?php
$show_cols="all";
$sede="";$F="";$F2="";
$FILTRO_SEMANA=r("filtro_sem");
$NIT_HERO_PARTS="";

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
$fechaFinal="NOW()";

$fechaFinal="'".mes_anterior($FechaHoy)."'";
$FECHA_LIMITE_INI="DATE_SUB($fechaFinal, INTERVAL 6 MONTH)";
$FECHA_LIMITE_FIN="($fechaFinal)";
//$FECHA_LIMITE_FIN="((LAST_DAY($fechaFinal - INTERVAL 1 MONTH)+ INTERVAL 1 DAY))";
$SQL_DIAS="DATEDIFF($FECHA_LIMITE_FIN,$FECHA_LIMITE_INI )";

/*
$SQL_DIAS="DATEDIFF( (LAST_DAY($fechaFinal - INTERVAL 1 MONTH) + INTERVAL 1 DAY) , DATE_SUB( ((LAST_DAY($fechaFinal - INTERVAL 1 MONTH) + INTERVAL 1 DAY)) , INTERVAL 6 
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
	//$SQL_DIAS="DATEDIFF($FECHA_LIMITE_FIN,$FECHA_LIMITE_INI )";
}





if($opc=="QUITAR")
{
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" data-inline=\"true\" data-mini=\"true\" >";
	$fechaI="";
	$fechaF="";
	$FECHA_LIMITE_INI="DATE_SUB($fechaFinal, INTERVAL 6 MONTH)";
	$FECHA_LIMITE_FIN="($fechaFinal)";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	
	
}


if($FILTRO_SEMANA==1){
	
$FECHA_LIMITE_INI="DATE_SUB($fechaFinal, INTERVAL 6 MONTH)";
$FECHA_LIMITE_FIN="($fechaFinal)";	
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------





$filtro="all";
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
	else if($filtro_fab=="$NIT_FANALCA")$C=" AND id_fab='$NIT_FANALCA'";

	else if($filtro_fab=="$NIT_HERO_PARTS")$C=" AND id_fab='$NIT_HERO_PARTS'";
	else if($filtro_fab=="otrosAll")$C=" AND id_fab!='$NIT_FANALCA' AND id_fab!='$NIT_HERO_PARTS'";
	else $C=" AND id_fab='$filtro_fab'" ;
	
}


$diasRotacion=45;
$Tr=25;//Tiempo de reposicion de inventario
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
$tot_vendidosFrac[][]=0;
$tot_vendidos2[][]=0;
$x2[][]=0;
$minSeg[][]=0;









///////////////////////////////////////////// TOT VENTAS MENSUALES ////////////////////////
//////////////// MESES///////
$meses_ventas[]=0;

$sql="SELECT a.num_fac_ven,a.prefijo,a.ref,a.cod_barras,a.fecha_vencimiento,a.des,a.cant,a.costo,MONTH(b.fecha) fecha,b.anulado,b.nit,SUM(a.cant) tot_dia,SUM(a.unidades_fraccion) tot_diaFrac,AVG(a.cant) prom_dia,MIN(a.cant) min,MAX(a.cant) max, $SQL_DIAS AS dias,$FECHA_LIMITE_INI as fechaI,$FECHA_LIMITE_FIN as fechaF FROM art_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND a.nit=b.nit AND $F AND b.".VALIDACION_VENTA_VALIDA." AND DATE(b.fecha)>= $FECHA_LIMITE_INI AND DATE(b.fecha)<=$FECHA_LIMITE_FIN GROUP BY MONTH(b.fecha)";

//echo "$sql";

$rs=$linkPDO->query($sql) ;
$i=0;
while($row=$rs->fetch())
{
	$ref=$row['ref'];
	$MeS=$row["fecha"];
 
	$meses_ventas[$i]=$MeS;
	$i++;
	
}

$REF_LIST[]="";
$tot_ventas_meses[][][]=0;
$tot_ventas_mesesFrac[][][]=0;
$tipo_rotacion[][]=0;

$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE $F2" );
while($row=$rs->fetch())
{
	$nit_scs=$row['nit_scs'];
	
	$ref=$row['id_pro'];
	$codBar=$row["id_inter"];
	$feVen=$row["fecha_vencimiento"];
	
	$ID_UNICO="$ref/$codBar";
 	$ref=$ID_UNICO;
	$REF_LIST[$ref]=$ref; 
	
	foreach($meses_ventas as $key=>$resultado)
	{
			
				$tot_ventas_meses[$ref][$resultado][$nit_scs]=0;
				$tot_ventas_mesesFrac[$ref][$resultado][$nit_scs]=0;
				$tipo_rotacion[$ref][$nit_scs]=0;
				
			
			
	}

$tot_cant_vendidas[$ref][$nit_scs]=0;
$tot_dias_ventas[$ref][$nit_scs]=0;
$tot_prom_ventas[$ref][$nit_scs]=0;
$tot_vendidos[$ref][$nit_scs]=0;
$tot_vendidosFrac[$ref][$nit_scs]=0;
$tot_vendidos2[$ref][$nit_scs]=0;	
$E[$ref][$nit_scs]=$row['exist'];	
$Pp[$ref][$nit_scs]=0;
//$Tr[$ref]=0;
$Cp[$ref][$nit_scs]=0;
$Cmx[$ref][$nit_scs]=0;
$Cmn[$ref][$nit_scs]=0;//$row['exist'];
$Emx[$ref][$nit_scs]=0;
$Emn[$ref][$nit_scs]=0;
$CP[$ref][$nit_scs]=0;

$minSeg[$ref][$nit_scs]=0;
$x2[$ref][$nit_scs]=0;
}


//////////////// MESES///////

$sql="SELECT a.num_fac_ven,a.prefijo,a.ref,a.cod_barras,a.fecha_vencimiento,a.des,a.cant,a.costo,MONTH(b.fecha) fecha,b.anulado,b.nit,SUM(a.cant) tot_dia,SUM(a.unidades_fraccion) tot_diaFrac,AVG(a.cant) prom_dia,MIN(a.cant) min,MAX(a.cant) max, $SQL_DIAS AS dias,$FECHA_LIMITE_INI as fechaI,$FECHA_LIMITE_FIN as fechaF FROM art_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND a.nit=b.nit AND $F AND b.".VALIDACION_VENTA_VALIDA." AND DATE(b.fecha)>= $FECHA_LIMITE_INI AND DATE(b.fecha)<=$FECHA_LIMITE_FIN GROUP BY a.ref,a.cod_barras,MONTH(b.fecha),nit";

//echo "$sql";
$rs=$linkPDO->query($sql) ;
$i=0;
while($row=$rs->fetch())
{
	$nit=$row["nit"];
	
	$ref=$row['ref'];
	$codBar=$row["cod_barras"];
	$feVen=$row["fecha_vencimiento"];
	
	$ID_UNICO="$ref/$codBar";
 	$ref=$ID_UNICO;

	$MeS=$row["fecha"];
	//if(in_array($ref,$REF_LIST,TRUE)){
if(isset($tot_ventas_meses[$ref][$MeS][$nit])){
	$tot_ventas_meses[$ref][$MeS][$nit]+=$row["tot_dia"];
	$tot_ventas_mesesFrac[$ref][$MeS][$nit]+=$row["tot_diaFrac"];
	if($row["tot_dia"]>0 || $row["tot_diaFrac"]>0){$tipo_rotacion[$ref][$nit]+=1;}
		}
	//}
	$i++;
	
}



//////////////////////////////////////////////////////////// QUERY VENTAS DIARIAS ///////////////////////////////////////////////

$sql="SELECT fraccion,a.num_fac_ven,a.prefijo,a.ref,a.cod_barras,a.fecha_vencimiento,a.des,a.cant,a.costo,DATE(b.fecha) fecha, MONTH(b.fecha) mes,b.anulado,b.nit,SUM(a.cant) tot_dia,SUM(a.unidades_fraccion) tot_diaFrac,AVG(a.cant) prom_dia,MIN(a.cant) min,MAX(a.cant) max, $SQL_DIAS AS dias,$FECHA_LIMITE_INI as fechaI,$FECHA_LIMITE_FIN as fechaF FROM art_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND a.nit=b.nit AND $F AND b.".VALIDACION_VENTA_VALIDA." AND DATE(b.fecha)>= $FECHA_LIMITE_INI AND DATE(b.fecha)<=$FECHA_LIMITE_FIN GROUP BY a.ref,a.cod_barras,DATE(b.fecha),nit";
//echo "$sql";

$rs=$linkPDO->query($sql) ;
$DIAS=0;
//$tot_vendidos[]=0;
//$tot_vendidosFrac[]=0;
//$tot_vendidos2[]=0;
$FECHA_I="";
$FECHA_F="";



while($row=$rs->fetch())
{
	$codBar=$row["cod_barras"];
	$feVen=$row["fecha_vencimiento"];
	$ref=$row['ref'];
	$ID_UNICO="$ref/$codBar";
 	$ref=$ID_UNICO;
	$nit_scs=$row['nit'];
	//echo "<li>[$ref][$nit_scs]:".$tot_vendidos[$ref][$nit_scs]."</li>";
	if(in_array($ref,$REF_LIST,TRUE)){
	
	$FECHA_I=$row['fechaI'];
	$FECHA_F=$row['fechaF'];
	
	
	$DIAS=$row['dias'];
	$tot_dia=$row['tot_dia'];
	$tot_diaFrac=$row['tot_diaFrac'];
	$frac=$row["fraccion"]>0?$row["fraccion"]:1;
	if(isset($tot_vendidos[$ref][$nit_scs])){
	
	$tot_vendidosFrac[$ref][$nit_scs]+=$tot_diaFrac;
	$tot_vendidos[$ref][$nit_scs]+=$tot_dia+$tot_diaFrac/$frac;
	
	//echo "<li>[$ref][$nit_scs]:".$tot_vendidos[$ref][$nit_scs]."</li>";
	}
	
	$prom_dia=$row['prom_dia'];
	$min=$tot_dia;
	$max=$tot_dia;
	if(isset($tot_dias_ventas[$ref][$nit_scs])){$tot_dias_ventas[$ref][$nit_scs]++;}
	if(isset($tot_prom_ventas[$ref][$nit_scs])){$tot_prom_ventas[$ref][$nit_scs]+=$prom_dia;}
	if(isset($tot_cant_vendidas[$ref][$nit_scs])){$tot_cant_vendidas[$ref][$nit_scs]+=$prom_dia;}
	if(isset($Cmx[$ref][$nit_scs])){if($max>$Cmx[$ref][$nit_scs]){$Cmx[$ref][$nit_scs]=$max;}}
	if(isset($Cmn[$ref][$nit_scs])){if($min>0&&$Cmn[$ref][$nit_scs]==0){$Cmn[$ref][$nit_scs]=$min;}}
	
	 if(isset($Cmn[$ref][$nit_scs])){if($min<$Cmn[$ref][$nit_scs]){$Cmn[$ref][$nit_scs]=$min;}}
	}
};








$DIAS2=0;
if($FILTRO_SEMANA==1){
$SQL_DIAS2="DATEDIFF('$fechaF','$fechaI' )";
//$SQL_DIAS2="DATEDIFF('2016-03-05','2016-03-01' )";
$sql="SELECT a.num_fac_ven,a.prefijo,a.ref,a.cod_barras,a.fecha_vencimiento,a.des,a.cant,a.costo,b.fecha,b.anulado,b.nit,SUM(a.cant) tot_dia,SUM(a.unidades_fraccion) tot_diaFrac,AVG(a.cant) prom_dia,MIN(a.cant) min,MAX(a.cant) max, $SQL_DIAS2 AS dias,$FECHA_LIMITE_INI as fechaI,$FECHA_LIMITE_FIN as fechaF FROM art_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND a.nit=b.nit AND $F AND b.".VALIDACION_VENTA_VALIDA." AND DATE(b.fecha)>= '$fechaI' AND DATE(b.fecha)<='$fechaF' GROUP BY a.ref,a.cod_barras,DATE(b.fecha),nit";


$sql="SELECT fraccion,a.num_fac_ven,a.prefijo,a.ref,a.cod_barras,a.fecha_vencimiento,a.des,a.cant,a.costo,b.fecha,b.anulado,b.nit,SUM(a.cant) tot_dia,SUM(a.unidades_fraccion) tot_diaFrac,AVG(a.cant) prom_dia,MIN(a.cant) min,MAX(a.cant) max, $SQL_DIAS2 AS dias,$FECHA_LIMITE_INI as fechaI,$FECHA_LIMITE_FIN as fechaF FROM art_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND a.nit=b.nit AND $F AND b.".VALIDACION_VENTA_VALIDA." AND DATE(b.fecha) BETWEEN '$fechaI' AND '$fechaF' GROUP BY a.ref,a.cod_barras,DATE(b.fecha),nit";

//echo "$sql";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch())
{
	$nit_scs=$row['nit'];

	$ref=$row['ref'];
	$codBar=$row["cod_barras"];
	$feVen=$row["fecha_vencimiento"];
	
	$ID_UNICO="$ref/$codBar";
 	$ref=$ID_UNICO;
	$DIAS2=$row['dias'];
	$tot_dia=$row['tot_dia'];
	$frac=$row["fraccion"];
	$tot_diaFrac=$row['tot_diaFrac'] / $frac;
	
	$tot_vendidos2[$ref][$nit_scs]+=$tot_dia+$tot_diaFrac;
	
};

}/////// if filtro semana

///////////////////////////////////// ANALISIS PRELIMINAR ///////////////////////////////////////////////////////////////////////////
$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE $F2");

$t=sqrt($Tr);
while($row=$rs->fetch())
{
$nit_scs=$row['nit_scs'];
$r=$row['id_pro'];
	$codBar=$row["id_inter"];
	$feVen=$row["fecha_vencimiento"];
	
	$ID_UNICO="$r/$codBar";
 	$r=$ID_UNICO;
//if($tot_dias_ventas[$r]>0)$Cp[$r]=$tot_prom_ventas[$r]/$tot_dias_ventas[$r];
if($DIAS>0)$Cp[$r][$nit_scs]=$tot_vendidos[$r][$nit_scs]/$DIAS;
//$Cp[$r]=$tot_vendidos[$r]/$DIAS;
//$Cp[$r]=($Cmx[$r]+$Cmn[$r])/2;
//$Emn[$r]=($Cmn[$r])*$Tr;
$Emn[$r][$nit_scs]=redondeo(($Cp[$r][$nit_scs])*$Tr);
//$Emx[$r]=($Cmx[$r] * $Tr)+$Emn[$r];
$Emx[$r][$nit_scs]=($Cmx[$r][$nit_scs] * $Tr)+$Emn[$r][$nit_scs];
}


//////////////////////////////////////////////////////////////SEGUNDO ANALISIS PRE///////////////////////////////////////////////////////////

$sql="SELECT a.num_fac_ven,a.prefijo,a.ref,a.cod_barras,a.fecha_vencimiento,a.des,a.cant,a.costo,b.fecha,b.anulado,b.nit,SUM(a.cant) tot_dia,SUM(a.unidades_fraccion) tot_diaFrac,AVG(a.cant) prom_dia,MIN(a.cant) min,MAX(a.cant) max,$SQL_DIAS AS dias,$FECHA_LIMITE_INI as fechaI,$FECHA_LIMITE_FIN as fechaF FROM art_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.prefijo=b.prefijo AND a.nit=b.nit AND $F AND b.".VALIDACION_VENTA_VALIDA." AND DATE(b.fecha)>= $FECHA_LIMITE_INI AND DATE(b.fecha)<=$FECHA_LIMITE_FIN GROUP BY a.ref,a.cod_barras,DATE(b.fecha),nit";

$rs=$linkPDO->query($sql) ;

while($row=$rs->fetch())
{
	$ref=$row['ref'];
	$codBar=$row["cod_barras"];
	$feVen=$row["fecha_vencimiento"];
	
	$ID_UNICO="$ref/$codBar";
 	$ref=$ID_UNICO;
	$nit_scs=$row['nit'];
	if(in_array($ref,$REF_LIST,TRUE)){
	
	$r=$row['ref'];
	$tot_dia=$row['tot_dia'];
	if(isset($Cp[$r][$nit_scs])){$y=$tot_dia-$Cp[$r][$nit_scs];}
	else {$y=$tot_dia-0;}
	//$x2[$r]+=pow($y,2);
	//$x2[$r][$nit_scs]+=$y*$y;
	if(isset($x2[$r][$nit_scs])){$x2[$r][$nit_scs]+=pow($y,2);}
	
	}
	
	
	
}



///////////////////////////////////// ANALISIS FINAL ///////////////////////////////////////////////////////////////////////////
$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE $F2" );
while($row=$rs->fetch())
{
$nit_scs=$row['nit_scs'];
$r=$row['id_pro'];
	$codBar=$row["id_inter"];
	$feVen=$row["fecha_vencimiento"];
	
	$ID_UNICO="$r/$codBar";
 	$r=$ID_UNICO;
//$Pp[$r]=redondeo(($Cp[$r]*$Tr)+$Emn[$r]);
//$Pp[$r]=redondeo(($Cp[$r]*$Tr));
if($DIAS>0)$minSeg[$r][$nit_scs]=(sqrt($x2[$r][$nit_scs]/$DIAS)) * $Z*$t;
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
$col_id="id_pro";
$columns="".tabProductos.".id_pro id_glo,inv_inter.id_inter  id_sede,detalle,id_clase,inv_inter.fraccion,fab,max,min,costo,precio_v,SUM(exist) AS exist,iva,gana,fab,nit_scs,fecha_vencimiento,SUM(unidades_frac) unidades_frac";

$url="PEDIDOS.php";
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="agregar_producto.php";
$pag="";
$limit = 40; 
$order="detalle";
$sort="";
$colArray=array(0=>'id_glo','id_sede','detalle','gana','exist');
$classActive=array(0=>'','','','','');
$offset=0;

if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;


if(isset($_REQUEST['sort']))
{
	$sort=$_REQUEST['sort'];
	$order= $colArray[$sort];
	$_SESSION['sort_inv']=$sort;
	$classActive[$sort]="ui-btn-active ui-state-persist";
}

if(isset($_SESSION['sort_inv']))
{        
        $sort=$_SESSION['sort_inv'];
		$order= $colArray[$sort];
		$classActive[$_SESSION['sort_inv']]="ui-btn-active ui-state-persist";
}

if(isset($_SESSION['id']))$idx=$_SESSION['id'];
if(isset($_REQUEST['opc'])){
$busq=r('busq');
$val= r('valor');
$boton= r('opc');
}


?>