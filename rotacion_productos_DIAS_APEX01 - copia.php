<?php
//$Aux[$nit_scs]=$REF_LIST[$nit_scs];
//if(in_array($id_inter,$REF_LIST,TRUE)){
	$bigHTML ='';
if(1){
	$bigHTML .='<tr  bgcolor="'.$bgColor.'" tabindex="0" id="tr'.$ii.'" onClick="click_ele(this);" onBlur="resetCss(this);">';
?>
<tr  bgcolor="<?php echo $bgColor ?>" tabindex="0" id="tr<?php echo $ii ?>" onClick="click_ele(this);" onBlur="resetCss(this);">

<th class="<?php if($show_cols!="all"){echo "uk-hidden";}?>"><?php echo $ii ?></th>            
<td align="left" class="<?php if($show_cols!="all"){echo "uk-hidden";}?>"><?php echo $REF; ?></td>
<td align="left"><?php echo $row['id_sede']; ?></td>
<td align="left" style="font-size:10px;"><?php echo $des; ?></td> 
<td align="left" class="<?php if($show_cols!="all"){echo "uk-hidden";}?>"><?php echo money2($costo); ?></td>
<?php

$s01="select SUM(exist) cant FROM inv_inter WHERE nit_scs!=$nit_scs and id_pro='$id_inter'";
$rs01=$linkPDO->query($s01);
$row01=$rs01->fetch();
$CANT_OTRAS_SEDES=$row01["cant"];

$text="";
$CONT_MESES=0;
$CONT_FACTORIAL_MESES=0;
$PROM2=0;
foreach($meses_ventas as $key=>$resultado)
{
	$CONT_MESES++;
	$CONT_FACTORIAL_MESES+=$CONT_MESES;
	$PROM2+=($tot_ventas_meses[$id_inter][$resultado][$nit_scs] +$tot_ventas_mesesFrac[$id_inter][$resultado][$nit_scs]/$frac) *$CONT_MESES; 
	//echo "tot_ventas_meses[ $id_inter ][ $resultado ][ $nit_scs ] * $CONT_MESES";
$text.="<td>".$tot_ventas_meses[$id_inter][$resultado][$nit_scs].";".$tot_ventas_mesesFrac[$id_inter][$resultado][$nit_scs]."</td>";
//else {echo "<td>0</td>"; $tot_ventas_meses[$id_inter][$resultado][$nit_scs]=0;}
}

echo $text."";
$PROM2=round($PROM2/$CONT_FACTORIAL_MESES,1);
//background-color:rgba(255, 0, 0, 0.88);
if( $tipo_rotacion[$id_inter][$nit_scs]>=5  )
{echo '<td style="background-color:#0C0;">'.$tipo_rotacion[$id_inter][$nit_scs].'</td>';
}else if($tipo_rotacion[$id_inter][$nit_scs]>=3 &&$tipo_rotacion[$id_inter][$nit_scs]<=4)
{echo '<td style="background-color:#FF0;">'.$tipo_rotacion[$id_inter][$nit_scs].'</td>';
	
}else if($tipo_rotacion[$id_inter][$nit_scs]>=1 &&$tipo_rotacion[$id_inter][$nit_scs]<=2)
{echo '<td style="background-color:#F90;">'.$tipo_rotacion[$id_inter][$nit_scs].'</td>';
	
}else if($tipo_rotacion[$id_inter][$nit_scs]==0){
echo '<td style="background-color:#F00;">'.$tipo_rotacion[$id_inter][$nit_scs].'</td>';	
	
}
else {echo '<td>'.$tipo_rotacion[$id_inter][$nit_scs].'</td>';}

?>
<td align="center"><?php echo $row['exist'].";$unidades"; ?></td>
<td align="center" class="<?php if($show_cols!="all"){echo "uk-hidden";}?>"><?php echo $Emn[$id_inter][$nit_scs]; ?></td> 
<td align="center" class="<?php if($show_cols!="all"){echo "uk-hidden";}?>"><?php echo $Emx[$id_inter][$nit_scs]; ?></td>

<td align="center"><?php 
$PROM_VENTAS=round(($IR)/count($meses_ventas),1);

echo $PROM_VENTAS.""; ?>
</td>
<td align="center"><?php echo $PROM2; ?></td> 
<td align="center"><?php
//echo $CP[$id_inter][$nit_scs]; 
$PEDIR= round($PROM2*1.5 - ($row['exist']+ $unidades/$frac  ),0) ;
echo "$PEDIR";
?>
</td>
<td style="background-color:#0FF ;">
<?php
$PEDIR2= round($PROM2*1.5 - ($row['exist']+ $unidades/$frac  ),0)-$CANT_OTRAS_SEDES;
echo "$PEDIR2";
?>
</td>

<!--
<td align="center"><?php echo $ultCom ?></td>
<td align="center"><?php echo $ultVen ?></td>
--> 
<td><?php echo "$CANT_OTRAS_SEDES"; ?></td>
<td align="center"><?php echo $IR ?></td>
<td><?php echo $SEDES[$COD_SUC]; ?></td>
</tr>
<?php }?>