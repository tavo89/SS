<?php 
include("Conexxx.php");

$sede="";$F="";$F2="";
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


header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Orden de Pedido $MUN.xls");


$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}
$FECHA_LIMITE_INI="DATE_SUB(NOW(), INTERVAL 6 MONTH)";
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
	else if($filtro_fab=="otrosAll")$C=" AND id_fab!='$NIT_FANALCA'";
	else $C=" AND id_fab='$filtro_fab'" ;
	
}

$diasRotacion=45;
$Tr=25;
if(isset($_REQUEST['diasInv'])){$diasRotacion=$_REQUEST['diasInv'];$_SESSION['diasInv']=$diasRotacion;}
if(isset($_SESSION['diasInv']))$diasRotacion=$_SESSION['diasInv'];

if(isset($_REQUEST['diasPro'])){$Tr=$_REQUEST['diasPro'];$_SESSION['diasPro']=$Tr;}
if(isset($_SESSION['diasPro']))$Tr=$_SESSION['diasPro'];

//Tiempo de reposicion de inventario

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
$minSeg[][]=0;
$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE $F2");
while($row=$rs->fetch())
{
$nit_scs=$row['nit_scs'];
$tot_cant_vendidas[$row['id_inter']][$nit_scs]=0;
$tot_dias_ventas[$row['id_inter']][$nit_scs]=0;
$tot_prom_ventas[$row['id_inter']][$nit_scs]=0;
$tot_vendidos[$row['id_inter']][$nit_scs]=0;	
$E[$row['id_inter']][$nit_scs]=$row['exist'];	
$Pp[$row['id_inter']][$nit_scs]=0;
//$Tr[$row['id_inter']]=0;
$Cp[$row['id_inter']][$nit_scs]=0;
$Cmx[$row['id_inter']][$nit_scs]=0;
$Cmn[$row['id_inter']][$nit_scs]=0;//$row['exist'];
$Emx[$row['id_inter']][$nit_scs]=0;
$Emn[$row['id_inter']][$nit_scs]=0;
$CP[$row['id_inter']][$nit_scs]=0;

$minSeg[$row['id_inter']][$nit_scs]=0;
$xxy[$row['id_inter']][$nit_scs]=0;
}


//////////////////////////////////////////////////////////// QUERY VENTAS DIARIAS ///////////////////////////////////////////////

$sql="SELECT a.num_fac_ven,a.prefijo,a.ref,a.cod_barras,a.des,a.cant,a.costo,b.fecha,b.anulado,b.nit,SUM(a.cant) tot_dia,AVG(a.cant) prom_dia,MIN(a.cant) min,MAX(a.cant) max, $SQL_DIAS AS dias,$FECHA_LIMITE_INI as fechaI,$FECHA_LIMITE_FIN as fechaF FROM art_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.cod_barras IN (SELECT id_inter FROM inv_inter) AND a.prefijo=b.prefijo AND a.nit=b.nit AND $F AND b.anulado!='ANULADO' AND b.fecha>= $FECHA_LIMITE_INI AND b.fecha<=$FECHA_LIMITE_FIN GROUP BY a.ref,DATE(b.fecha)";

$rs=$linkPDO->query($sql);
$DIAS=0;
$tot_vendidos[]=0;
$FECHA_I="";
$FECHA_F="";
while($row=$rs->fetch())
{
	$nit_scs=$row['nit'];
	$FECHA_I=$row['fechaI'];
	$FECHA_F=$row['fechaF'];
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
};

///////////////////////////////////// ANALISIS PRELIMINAR ///////////////////////////////////////////////////////////////////////////
$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE $F2");

$t=sqrt($Tr);
while($row=$rs->fetch())
{
$nit_scs=$row['nit_scs'];
$r=$row['id_inter'];
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

$sql="SELECT a.num_fac_ven,a.prefijo,a.ref,a.cod_barras,a.des,a.cant,a.costo,b.fecha,b.anulado,b.nit,SUM(a.cant) tot_dia,AVG(a.cant) prom_dia,MIN(a.cant) min,MAX(a.cant) max,$SQL_DIAS AS dias,$FECHA_LIMITE_INI as fechaI,$FECHA_LIMITE_FIN as fechaF FROM art_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven WHERE a.cod_barras IN (SELECT id_inter FROM inv_inter) AND a.prefijo=b.prefijo AND a.nit=b.nit AND $F AND b.anulado!='ANULADO' AND b.fecha>= $FECHA_LIMITE_INI AND b.fecha<=$FECHA_LIMITE_FIN GROUP BY a.ref,DATE(b.fecha)";

$rs=$linkPDO->query($sql);

while($row=$rs->fetch())
{
	$nit_scs=$row['nit'];
	$r=$row['cod_barras'];
	$tot_dia=$row['tot_dia'];
	$y=$tot_dia-$Cp[$r][$nit_scs];
	//$xxy[$r]+=pow($y,2);
	//$xxy[$r][$nit_scs]+=$y*$y;
	$xxy[$r][$nit_scs]+=pow($y,2);
	
	
	
	
	
}



///////////////////////////////////// ANALISIS FINAL ///////////////////////////////////////////////////////////////////////////
$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE $F2");
while($row=$rs->fetch())
{
$nit_scs=$row['nit_scs'];
$r=$row['id_inter'];
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
$col_id="id_pro";
$columns="".tabProductos.".id_pro id_glo,inv_inter.id_inter  id_sede,detalle,id_clase,fraccion,fab,max,min,costo,precio_v,exist,iva,gana,fab,nit_scs";
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
$busq=$_REQUEST['busq'];
$val= $_REQUEST['valor'];
$boton= $_REQUEST['opc'];
}

$cols="<th width=\"250\">
<div style=\"display:inline-block;table-layout:fixed;width:80%;\">Ref</div>
</th>

<th width=\"200\">
<div style=\"display:inline-block;width:80%;\">Descripci&oacute;n</div>
</th>

<th width=\"50\">Cantidad <br>a Pedir</th>
<th width=\"50\">Bancorde</th>
<th width=\"50\">Sede</th>
";



$sql = "SELECT  $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE  $F2 $D $C ORDER BY $order "; 
 
if($boton=='mod'&& !empty($val)){
	
	//$_SESSION['id']=$val;
	$_SESSION['pag']=$pag;
	
	header("location: $url_mod?REF=$val");
	}
 

 

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM ".tabProductos." INNER JOIN inv_inter ON (inv_inter.id_pro=".tabProductos.".id_pro) WHERE $F2 AND (".tabProductos.".id_pro LIKE '$busq%' OR detalle LIKE '$busq%' OR id_clase LIKE '$busq%' OR inv_inter.id_inter LIKE '$busq%')";



$rs=$linkPDO->query($sql_busq);

	
	}
	

 
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv=Content-Type content="text/html; charset=<?php echo $CHAR_SET ?>">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 11">

<title>Orden de Pedido</title>
<style>
@page
{
   {mso-header-data:"&CInventory";
	mso-footer-data:"Page &P of &N";
	margin:.31in 0in .31in 0in;
	mso-header-margin:0in;
	mso-footer-margin:0in;
	mso-page-orientation:landscape;}
tr
	{mso-height-source:auto;}
col
	{mso-width-source:auto;}
br
	{mso-data-placement:same-cell;}	
}
@page Section1 { }
div.Section1 { page:Section1; }
p.MsoHeader, p.MsoFooter { border: 1px solid black; }
td { page-break-inside:avoid; }
tr { page-break-after:avoid; }
 
    </style>
    
<!--[if gte mso 9]><xml>
 <x:ExcelWorkbook>
  <x:ExcelWorksheets>
   <x:ExcelWorksheet>
    <x:Name>Inventory</x:Name>
    <x:WorksheetOptions>
     <x:Print>
      <x:ValidPrinterInfo/>
      <x:PaperSizeIndex>9</x:PaperSizeIndex>
      <x:HorizontalResolution>600</x:HorizontalResolution>
      <x:VerticalResolution>600</x:VerticalResolution>
     </x:Print>
     <x:Selected/>
     <x:Panes>
      <x:Pane>
       <x:Number>1</x:Number>
       <x:ActiveRow>1</x:ActiveRow>
      </x:Pane>
     </x:Panes>
     <x:ProtectContents>False</x:ProtectContents>
     <x:ProtectObjects>False</x:ProtectObjects>
     <x:ProtectScenarios>False</x:ProtectScenarios>
    </x:WorksheetOptions>
   </x:ExcelWorksheet>
  </x:ExcelWorksheets>
  <x:WindowHeight>8835</x:WindowHeight>
  <x:WindowWidth>15180</x:WindowWidth>
  <x:WindowTopX>120</x:WindowTopX>
  <x:WindowTopY>105</x:WindowTopY>
  <x:ProtectStructure>False</x:ProtectStructure>
  <x:ProtectWindows>False</x:ProtectWindows>
 </x:ExcelWorkbook>
</xml><![endif]-->
</head>

<body>
<div class="Section1"> 
<?php
$periodo="$FECHA_I a $FECHA_F";
if(!empty($fechaI)&&!empty($fechaF))$periodo="$fechaI a $fechaF";
?>
<body>
<div class="Section1"> 
<h1>Orden de Pedido <?php echo "$MUN $FechaHoy" ?></h1>
<h1>Periodo <?php echo "$periodo" ?></h1>

<form action="<?php echo $url ?>" method="post" name="form">
<table border="1" align="center" rules="rows" frame="box"> 
 <thead>
      <tr bgcolor="#CCCCCC"> 
      
<?php echo $cols;   ?>

       </tr>
        
</thead>
<tbody>          
      
<?php 
$bgColor="";
$rotacion=0;
$IR=0;
//echo last_day($FechaHoy);
$TOT_SIN_ROTACION=0;
$TOT_INV_IDEAL=0;
$TOT_INV_SOBRANTE=0;
while ($row = $rs->fetch()) 
{ 
			$nit_scs=$row['nit_scs'];
		    $rotacion=0;
			$IR=0;
            $id_inter = htmlentities($row["id_glo"]); 
            $des = htmlentities($row["detalle"], ENT_QUOTES,"$CHAR_SET"); 
			$clase = htmlentities($row["id_clase"], ENT_QUOTES,"$CHAR_SET");
			$id = htmlentities($row["id_sede"]);
			$frac = htmlentities($row["fraccion"]);
			$fab = htmlentities($row["fab"], ENT_QUOTES,"$CHAR_SET");
			$costo=$row['costo']*1; 
			$exist=$row['exist'];
			$COD_SUC=$row['nit_scs'];
			
			/*
			$promInv=promedio_inventario($codSuc,$FECHA_I,$FECHA_F,$id_inter,$Cp[$id]);
			//if($promInv<=0)$promInv=1;
			if($promInv!=0){$IR=redondeo3(($tot_vendidos[$id]*$costo)/($promInv*$costo));}
			else $IR=0;
			
			//if($IR!=0)$rotacion=redondeo3($DIAS/$IR);
			
			if($tot_vendidos[$id]>0&&$promInv!=0)$rotacion=redondeo($DIAS*(($promInv*$costo) / ($tot_vendidos[$id]*$costo)));
			else $rotacion=0;
			*/
			if($DIAS>0)$minSeg[$id][$nit_scs]=(sqrt($xxy[$id][$nit_scs]/$DIAS)) * $Z*$t;
			//$minSeg[$r][$nit_scs]=$Z*$t;
			$Emn[$id][$nit_scs]=redondeo(($Cp[$id][$nit_scs])+$minSeg[$id][$nit_scs]);
			
			$IR=$tot_vendidos[$id][$nit_scs];
			$rotacion=0;
			//$rotacion=redondeo3($promInv);
			//$rotacion=$promInv;
			
			/*if($promInv>0)$a=$tot_vendidos[$id]/$promInv;
			else $a=0;
			
			if($a>0)$diasRotacion=$DIAS/$a;
			else $diasRotacion=0;
			*/
			//$diasRotacion=45;
			
			if($DIAS>0)$Emx[$id][$nit_scs]=redondeo(($diasRotacion/$DIAS)*$tot_vendidos[$id][$nit_scs]);
			
			
			$Pp[$id][$nit_scs]=redondeo($Cp[$id][$nit_scs]*$Tr +$Emn[$id][$nit_scs]);
			$CP[$id][$nit_scs]=$Emx[$id][$nit_scs] - $E[$id][$nit_scs];			
			
			if($idx==$id_inter)$bgColor="#999999";
			else $bgColor="#FFFFFF";
			
			if($Emx[$id][$nit_scs]==1)$Pp[$id][$nit_scs]=1;
			if($Emx[$id][$nit_scs]==0)$Pp[$id][$nit_scs]=0;
			if($Pp[$id][$nit_scs]>$Emx[$id][$nit_scs])$Pp[$id][$nit_scs]=redondeo($Cp[$id][$nit_scs]*$Tr);
			
			if($CP[$id][$nit_scs]<0){$TOT_INV_SOBRANTE+=$costo*$CP[$id][$nit_scs];}
			
			if($exist>0 && $IR==0)$TOT_SIN_ROTACION+=$exist*$costo;
			
			$TOT_INV_IDEAL+=$Emx[$id][$nit_scs]*$costo;
			
			   
if($filtro=="bad" && $Emn[$id][$nit_scs]<=0){	
$ii++;		
?>
<tr  bgcolor="<?php echo $bgColor ?>" tabindex="0" id="tr<?php echo $ii ?>" onClick="click_ele(this);" onBlur="resetCss(this);">
<td align="left"><?php echo $id_inter; ?></td>
<td><?php echo $des; ?></td> 
<td align="center"><?php echo $CP[$id][$nit_scs]; ?></td>
<td align="center"></td>
<td><?php echo $SEDES[$COD_SUC]; ?></td> 
</tr>         
<?php 
}/////////////////////// FIN SIN ROTACION /////////////////////////////////////////////////////////
		 
if($filtro=="Pp" && $row['exist']<=$Pp[$id][$nit_scs] && $Emn[$id][$nit_scs]>0 &&$Pp[$id][$nit_scs]>0){
	$ii++;
?>
<tr  bgcolor="<?php echo $bgColor ?>" tabindex="0" id="tr<?php echo $ii ?>" onClick="click_ele(this);" onBlur="resetCss(this);">
<td align="left"><?php echo $id_inter; ?></td>
<td><?php echo $des; ?></td> 
<td align="center"><?php echo $CP[$id][$nit_scs]; ?></td>
<td align="center"></td>
<td><?php echo $SEDES[$COD_SUC]; ?></td> 
</tr>         
<?php
}///////////////////////////////////////// FIN Pp ////////////////////////////////////////////////////////

if($filtro=="all"){
	$ii++;
?>
<tr  bgcolor="<?php echo $bgColor ?>" tabindex="0" id="tr<?php echo $ii ?>" onClick="click_ele(this);" onBlur="resetCss(this);">
<td align="left"><?php echo $id_inter; ?></td>
<td align="left"><?php echo $des; ?></td> 
<td align="center"><?php echo $CP[$id][$nit_scs]; ?></td>
<td align="center"></td>
<td><?php echo $SEDES[$COD_SUC]; ?></td> 
</tr>         
<?php
}///////////////////////////////////////////////// FIN TODAS //////////////////////////////////////////////////////////
		 
		 } ////////////////////////////////////// FIN WHILE QUERY ///////////////////////////////////////////////////
      ?>
 

 
</tbody>
</table>


</form>

</div>
</body>
</html>