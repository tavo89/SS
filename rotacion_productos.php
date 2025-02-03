<?php 
include("Conexxx.php");
include("vars_rotacion.php");

header("Content-Type: application/vnd.ms-excel");header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");header("content-disposition: attachment;filename=Orden de Pedido $MUN $FechaHoy Hora $hora.xls");

 



$sql = "SELECT   $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro  AND  $F2 $D $C GROUP BY id_glo,id_sede ORDER BY $order "; 

if($filtro=="bad"){$sql = "SELECT   $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro AND inv_inter.id_pro NOT IN (SELECT ref FROM art_fac_com a INNER JOIN fac_com b  ON a.num_fac_com=b.num_fac_com AND a.cod_su=b.cod_su AND a.nit_pro=b.nit_pro AND (DATE(b.fecha)>=DATE_SUB(NOW(), INTERVAL 3 MONTH) AND DATE(b.fecha)<=NOW()   )  )  AND  $F2 $D $C GROUP BY id_glo,id_sede  ORDER BY $order "; }

 
 
 


 
$rs = $linkPDO->query($sql ); 
 

	

	

 
 
?>
<!DOCTYPE html >
<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 11">

<title>Orden de Pedido</title>
<style>
@page
{
   mso-header-data:"&CInventory";
	mso-footer-data:"Page &P of &N";
	margin:.31in 0in .31in 0in;
	mso-header-margin:0in;
	mso-footer-margin:0in;
	mso-page-orientation:landscape;}
tr {mso-height-source:auto;}
col
	{mso-width-source:auto;}
br
	{mso-data-placement:same-cell;}	

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
<?php
$periodo="$FECHA_I a $FECHA_F";
if(!empty($fechaI)&&!empty($fechaF))$periodo="$fechaI a $fechaF";
?>
<body>
<div class="Section1"> 
<h1>Orden de Pedido <?php echo "$MUN $FechaHoy "?></h1>
<h1>Periodo <?php echo "$periodo" ?></h1>

<?php
//echo "AND b.fecha>= $FECHA_LIMITE_INI AND b.fecha<=$FECHA_LIMITE_FIN";
if(!empty($filtro_fab)){

$s="SELECT * FROM provedores WHERE nit='$filtro_fab'";
$r=$linkPDO->query($s);
if($rowA=$r->fetch()){
	$nomPro=$rowA["nom_pro"];
	echo "<h2>PROVEEDOR: $nomPro</h2>";
}


}

 
 
?>
<form action="<?php echo $url ?>" method="post" name="form">
<table border="1" align="center" rules="rows" frame="box" style="font-size:16px;"> 
<thead>
<tr bgcolor="#CCCCCC"> 
      
<th width="90px">#</th>
<th width="250">
<div style="display:inline-block;table-layout:fixed;width:80%;">Ref</div>
 
</th>

<th width="200">
<div style="display:inline-block;table-layout:fixed;width:80%;">Codigo</div>
 
</th>

<th width="200">
Descripci&oacute;n
 
</th>

<th width="200">Costo</th>
<?php
$CONT_MESES=0;
$text="";
	foreach($meses_ventas as $key=>$resultado)
	{
		$CONT_MESES++;
	$text.="<td>$MESES[$resultado]</td>";
	
	}
	echo $text."";

?>
<th width="200">ROTACION <?php //echo $CONT_MESES; ?></th>
<th width="200">
Exist.
 
</th>
<th width="50">Min</th>
<th width="50">Max</th>
<th width="50">Promedio</th>
<th width="">Promedio2</th>
<th width="50">Pedir</th>
<th width="50">Pedir Real</th>

<!--
<th width="50">Ult. Compra</th>
<th width="50">Ult. Venta</th>
-->
<th width="50">Otras Sedes</th>
<th width="">Vendidos</th>
<th width="50">Sede</th>

       </tr>
        
</thead>
<tbody>          
      
<?php 
$bgColor="";
$rotacion=0;
$IR=0;
//echo last_day($FechaHoy);
$TOT_SIN_ROTACION=0;
$TOT_SIN_ROTACION_IVA=0;

$TOT_INV_IDEAL=0;
$TOT_INV_IDEAL_IVA=0;

$TOT_INV_SOBRANTE=0;
$TOT_INV_SOBRANTE_IVA=0;

$TOT_INV_NOW=0;
$TOT_INV_NOW_IVA=0;
while ($row = $rs->fetch()) 
{ 
			$nit_scs=$row['nit_scs'];
		    $rotacion=0;
			$IR=0;
            $id_inter = $row["id_glo"]; 
			$REF=$id_inter;
			$codBar=$row["id_sede"];
			$feVen=$row["fecha_vencimiento"];
	
			$ID_UNICO="$id_inter/$codBar";
 			$id_inter=$ID_UNICO;
            $des =$row["detalle"]; 
			$clase = $row["id_clase"];
			$id = $row["id_sede"];
			$frac=$row["fraccion"]>0?$row["fraccion"]:1;
			$unidades=$row["unidades_frac"];
			$fab = $row["fab"];
			$costo=$row['costo']*1; 
			$iva=$row['iva'];
			$costoIva=$costo*($iva/100 + 1);
			$exist=$row['exist'];
			$COD_SUC=$row['nit_scs'];
			
			$ultCom="";
			$ultVen="";
			
			$TOT_INV_NOW+=$costo*$exist;
			$TOT_INV_NOW_IVA+=$costoIva*$exist;
			
 
			if($DIAS>0)$minSeg[$id_inter][$nit_scs]=(sqrt($x2[$id_inter][$nit_scs]/$DIAS)) * $Z*$t;
			//$minSeg[$r][$nit_scs]=$Z*$t;
			$Emn[$id_inter][$nit_scs]=redondeo(($Cp[$id_inter][$nit_scs])+$minSeg[$id_inter][$nit_scs]);
			
			$IR=$tot_vendidos[$id_inter][$nit_scs];
			if($FILTRO_SEMANA==1)$IR=$tot_vendidos2[$id_inter][$nit_scs];
			$rotacion=0;
			//$rotacion=redondeo3($promInv);
			//$rotacion=$promInv;
			
			/*if($promInv>0)$a=$tot_vendidos[$id_inter]/$promInv;
			else $a=0;
			
			if($a>0)$diasRotacion=$DIAS/$a;
			else $diasRotacion=0;
			*/
			//$diasRotacion=45;
			
			if($DIAS>0)$Emx[$id_inter][$nit_scs]=redondeo(($diasRotacion/$DIAS)*$tot_vendidos[$id_inter][$nit_scs]);
			
			
			$ST1=$Emn[$id_inter][$nit_scs]==1 && $Emx[$id_inter][$nit_scs]==0;
			
			$Pp[$id_inter][$nit_scs]=redondeo($Cp[$id_inter][$nit_scs]*$Tr +$Emn[$id_inter][$nit_scs]);
			$CP[$id_inter][$nit_scs]=$Emx[$id_inter][$nit_scs] - $E[$id_inter][$nit_scs];
			if($CP[$id_inter][$nit_scs]==0 && $Emn[$id_inter][$nit_scs]=1)	{$CP[$id_inter][$nit_scs]=1;}	
			if($Pp[$id_inter][$nit_scs]>$Emx[$id_inter][$nit_scs])$Pp[$id_inter][$nit_scs]=redondeo($Cp[$id_inter][$nit_scs]*$Tr);
			if($ST1){$Pp[$id_inter][$nit_scs]=1;}	
			
			if($idx==$id_inter)$bgColor="#999999";
			else $bgColor="#FFFFFF";
			
			if($Emx[$id_inter][$nit_scs]==1){$Pp[$id_inter][$nit_scs]=1;}
			if($Emx[$id_inter][$nit_scs]==0 && $Emn[$id_inter][$nit_scs]==0){$Pp[$id_inter][$nit_scs]=0;}
			
			 
			
			
			
if($CP[$id_inter][$nit_scs]<0){$TOT_INV_SOBRANTE+=$costo*$CP[$id_inter][$nit_scs];$TOT_INV_SOBRANTE_IVA+=$costoIva*$CP[$id_inter][$nit_scs];}
			
if($exist>0 && $IR==0){$TOT_SIN_ROTACION+=$exist*$costo;$TOT_SIN_ROTACION_IVA+=$exist*$costoIva;}
			
$TOT_INV_IDEAL+=$Emx[$id_inter][$nit_scs]*$costo;
$TOT_INV_IDEAL_IVA+=$Emx[$id_inter][$nit_scs]*$costoIva;
			
			
			   
if($filtro=="bad" && $Emn[$id_inter][$nit_scs]<=0){	
$ii++;		

include("rotacion_productos_DIAS_APEX01.php");

 
}/////////////////////// FIN SIN ROTACION /////////////////////////////////////////////////////////		
//$filtro=="Pp" && $row['exist']<$Pp[$id_inter][$nit_scs] && $Emn[$id_inter][$nit_scs]>0 &&$Pp[$id_inter][$nit_scs]>0 
if($filtro=="Pp" && $IR>=1){
$ii++;
include("rotacion_productos_DIAS_APEX01.php");
}///////////////////////////////////////// FIN Pp ////////////////////////////////////////////////////////

if($filtro=="all"){
$ii++;
include("rotacion_productos_DIAS_APEX01.php");
}///////////////////////////////////////////////// FIN TODAS //////////////////////////////////////////////////////////
		 
} ////////////////////////////////////// FIN WHILE QUERY ///////////////////////////////////////////////////
      ?>
 

 
</tbody>
</table>
<br />

</form>

</div>
</body>
</html>
<?php

?>