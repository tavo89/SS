<?php 
include("Conexxx.php");
excel("ROTACION INVENTARIO $MUN $FechaHoy Hora $hora");
//****************************************************************** P A R A M E T R O S*************************************************************//
require_once("param_rotacion.php");
//**************************************************************************************************************************************************//
$sql = "SELECT  * FROM vista_inventario WHERE  $F2 $D $C ORDER BY $order "; 
$rs = $linkPDO->query($sql); 
 

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
<?php
$periodo="$FECHA_I a $FECHA_F";
if(!empty($fechaI)&&!empty($fechaF))$periodo="$fechaI a $fechaF";
?>
<body>
<div class="Section1"> 
<h1>ROTACION <?php echo "$MUN $FechaHoy" ?></h1>
<h1>Periodo <?php echo "$periodo" ?></h1>
<h1>Proveedor: <?php echo show_proveedor("$filtro_fab") ?></h1>
<?php



?>
<form action="<?php echo $url ?>" method="post" name="form">
<table border="1" align="center" rules="rows" frame="box"> 
<thead>
<tr bgcolor="#595959" style="color:#FFF" valign="top"> 
<th width="90px">#</th>

<th width="250">Ref</th>

<th width="200">Codigo</th>

<th width="200">Descripción</th>

<th width="200">Costo</th>
<th width="200">Exist.</th>
<th width="50">Min</th>
<th width="50">Max</th>
<th width="50">Cant. <br>Pedido</th>
<th width="">Punto <br>Pedido</th>
<th width="">Total<br> Vendidos</th>
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
while ($row = $rs->fetch()) 
{ 
			$nit_scs=$row['nit_scs'];
		    $rotacion=0;
			$IR=0;
            $id_inter = $row["id_glo"]; 
            $des = $row["detalle"]; 
			$clase =$row["id_clase"];
			$id = $row["id_sede"];
			$frac = $row["fraccion"];
			$fab = $row["fab"];
			$costo=$row['costo']*1; 
			$iva=$row['iva'];
			$costoIva=$costo*($iva/100 + 1);
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
			
			if($idx==$id)$bgColor="#999999";
			else $bgColor="#FFFFFF";
			
			if($Emx[$id][$nit_scs]==1)$Pp[$id][$nit_scs]=1;
			if($Emx[$id][$nit_scs]==0)$Pp[$id][$nit_scs]=0;
			if($Pp[$id][$nit_scs]>$Emx[$id][$nit_scs])$Pp[$id][$nit_scs]=redondeo($Cp[$id][$nit_scs]*$Tr);
			
			if($CP[$id][$nit_scs]<0){$TOT_INV_SOBRANTE+=$costo*$CP[$id][$nit_scs];$TOT_INV_SOBRANTE_IVA+=$costoIva*$CP[$id][$nit_scs];}
			
			if($exist>0 && $IR==0){$TOT_SIN_ROTACION+=$exist*$costo;$TOT_SIN_ROTACION_IVA+=$exist*$costoIva;}
			
			$TOT_INV_IDEAL+=$Emx[$id][$nit_scs]*$costo;
			$TOT_INV_IDEAL_IVA+=$Emx[$id][$nit_scs]*$costoIva;
			
			
	if($Emn[$id][$nit_scs]>$Emx[$id][$nit_scs])	
	{
		
		$MinFlag=$Emn[$id][$nit_scs];
		$Emn[$id][$nit_scs]=$Emx[$id][$nit_scs];
		$Emx[$id][$nit_scs]=$MinFlag;
	}
if($filtro=="bad" && $Emn[$id][$nit_scs]<=0){	
$ii++;		
?>
<tr  bgcolor="<?php echo $bgColor ?>" tabindex="0" id="tr<?php echo $ii ?>" onClick="click_ele(this);" onBlur="resetCss(this);">
<th><?php echo $ii ?></th>            
<td align="left"><?php echo $id_inter; ?></td>
<td align="left"><?php echo $row['id_sede']; ?></td>
<td align="left"><?php echo $des; ?></td> 
<td align="left"><?php echo punto_a_coma($costo); ?></td>
<td align="center"><?php echo $exist; ?></td>
<td align="center"><?php echo $Emn[$id][$nit_scs]; ?></td> 
<td align="center"><?php echo $Emx[$id][$nit_scs]; ?></td>
<td align="center"><?php echo $CP[$id][$nit_scs]; ?></td>
<td align="center"><?php echo $Pp[$id][$nit_scs]//." Dias: $DIAS, Dias Ventas: $tot_dias_ventas[$id]"//$fab ; ?></td> 
<td align="center"><?php echo $IR ?></td> 
<td><?php echo $SEDES[$COD_SUC]; ?></td> 
<!--<td align="center"><?php //echo $rotacion; ?></td>-->

</tr>         
<?php 
}/////////////////////// FIN SIN ROTACION /////////////////////////////////////////////////////////
		 
if($filtro=="Pp" && $row['exist']<=$Pp[$id][$nit_scs] && $Emn[$id][$nit_scs]>0 &&$Pp[$id][$nit_scs]>0){
	$ii++;
?>
<tr  bgcolor="<?php echo $bgColor ?>" tabindex="0" id="tr<?php echo $ii ?>" onClick="click_ele(this);" onBlur="resetCss(this);">
<th><?php echo $ii ?></th>            
<td align="left"><?php echo $id_inter; ?></td>
<td align="left"><?php echo $row['id_sede']; ?></td>
<td align="left"><?php echo $des; ?></td> 
<td align="left"><?php echo punto_a_coma($row['costo']); ?></td>
<td align="center"><?php echo $row['exist']; ?></td>
<td align="center"><?php echo $Emn[$id][$nit_scs]; ?></td> 
<td align="center"><?php echo $Emx[$id][$nit_scs]; ?></td>
<td align="center"><?php echo $CP[$id][$nit_scs]; ?></td>
<td align="center"><?php echo $Pp[$id][$nit_scs]//." Dias: $DIAS, Dias Ventas: $tot_dias_ventas[$id]"//$fab ; ?></td>
<td align="center"><?php echo $IR ?></td>
<td><?php echo $SEDES[$COD_SUC]; ?></td>
<!--<td align="center"><?php //echo $rotacion; ?></td>-->
</tr>         
<?php
}///////////////////////////////////////// FIN Pp ////////////////////////////////////////////////////////

if($filtro=="all"){
	$ii++;
?>
<tr  bgcolor="<?php echo $bgColor ?>" tabindex="0" id="tr<?php echo $ii ?>" onClick="click_ele(this);" onBlur="resetCss(this);">
<th><?php echo $ii ?></th>            
<td align="left"><?php echo $id_inter; ?></td>
<td align="left"><?php echo $row['id_sede']; ?></td>
<td align="left"><?php echo $des; ?></td> 
<td align="left"><?php echo punto_a_coma($row['costo']); ?></td>
<td align="center"><?php echo $row['exist']; ?></td>
<td align="center"><?php echo $Emn[$id][$nit_scs]; ?></td> 
<td align="center"><?php echo $Emx[$id][$nit_scs]; ?></td>
<td align="center"><?php echo $CP[$id][$nit_scs]; ?></td>
<td align="center"><?php echo $Pp[$id][$nit_scs]//."<br>( $diasRotacion / $DIAS )* $tot_vendidos[$id]<br>Cp=$Cp[$id]<br>Cmn=$Cmn[$id]<br>Cmx=$Cmx[$id]<br>x=$xxy[$id]<br>Prom Inv: $promInv"//." Dias: $DIAS, Dias Ventas: $tot_dias_ventas[$id]"//$fab ; ?></td>  
<td align="center"><?php echo $IR ?></td>
<td><?php echo $SEDES[$COD_SUC]; ?></td>
<!--<td align="center"><?php //echo $rotacion; ?></td>-->
</tr>         
<?php
}///////////////////////////////////////////////// FIN TODAS //////////////////////////////////////////////////////////
		 
		 } ////////////////////////////////////// FIN WHILE QUERY ///////////////////////////////////////////////////
		 
		 
		 
$TOT_INV=costo_now($codSuc,"con");
$costoC=$TOT_INV[0];
$costoS=$TOT_INV[1];
$inv_pvp=$TOT_INV[2];
$IVA=$costoC-$costoS;
      ?>
 

 
</tbody>
</table>
<br />
<table style=" font-size:14px; font-weight:bold;">
<tr>
<td colspan="3">SIN Rotaci&oacute;n(Costo sin IVA):</td><td> <?php echo money($TOT_SIN_ROTACION) ?></td>
</tr>

<tr>
<td colspan="3">SIN Rotaci&oacute;n(Costo con IVA):</td><td> <?php echo money($TOT_SIN_ROTACION_IVA) ?></td>
</tr>

<tr>
<td colspan="3">Inventario Ideal(Costo sin IVA):</td><td> <?php echo money($TOT_INV_IDEAL) ?></td>
</tr>
<tr>
<td colspan="3">Inventario Ideal(Costo con IVA): </td><td><?php echo money($TOT_INV_IDEAL_IVA) ?></td>
</tr>

<tr>
<td colspan="3">Inventario Actual(Costo sin IVA): </td><td><?php echo money($costoS) ?></td>
</tr>
<tr>
<td colspan="3">Inventario Actual(Costo con IVA): </td><td><?php echo money($costoC) ?></td>
</tr>
<tr>
<td colspan="3">Inventario sobre el L&iacute;mite de Existencias(Costo sin IVA): </td><td><?php echo money($TOT_INV_SOBRANTE*-1) ?></td>
</tr>
<tr>
<td colspan="3">Inventario sobre el L&iacute;mite de Existencias(Costo con IVA):</td><td> <?php echo money($TOT_INV_SOBRANTE_IVA*-1) ?></td>
</tr>
</table>
</form>

</div>
</body>
</html>