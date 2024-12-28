<?php
//$Aux[$nit_scs]=$REF_LIST[$nit_scs];
//if(in_array($id_inter,$REF_LIST,TRUE)){
	$bigHTML ='';
if(1){
	$hidCols=($show_cols!="all")?'uk-hidden':'';
	
	$bigHTML .='<tr  bgcolor="'.$bgColor.'" tabindex="0" id="tr'.$ii.'" onClick="click_ele(this);" onBlur="resetCss(this);">';
	$bigHTML .='<th class="'.$hidCols.'"> '.$ii.' </th>';
	$bigHTML .='<td align="left" class="'.$hidCols.'">'.$REF.'</td>';
	$bigHTML .='<td align="left">'.$row['id_sede'].'</td>';
	$bigHTML .='<td align="left" style="font-size:10px;">'.$des.'</td> ';
	$bigHTML .='<td align="left" class="'.$hidCols.'"> '.money2($costo).' </td>';
	
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
	$bigHTML.="<td>".$tot_ventas_meses[$id_inter][$resultado][$nit_scs].";".$tot_ventas_mesesFrac[$id_inter][$resultado][$nit_scs]."</td>";
	//else {echo "<td>0</td>"; $tot_ventas_meses[$id_inter][$resultado][$nit_scs]=0;}
	}

	
	$PROM2=round($PROM2/$CONT_FACTORIAL_MESES,1);
	//background-color:rgba(255, 0, 0, 0.88);
	if( $tipo_rotacion[$id_inter][$nit_scs]>=5  )
	{
		$bigHTML .='<td style="background-color:#0C0;">'.$tipo_rotacion[$id_inter][$nit_scs].'</td>';
	}
	else if($tipo_rotacion[$id_inter][$nit_scs]>=3 &&$tipo_rotacion[$id_inter][$nit_scs]<=4)
	{
		$bigHTML .='<td style="background-color:#FF0;">'.$tipo_rotacion[$id_inter][$nit_scs].'</td>';
		
	}
	else if($tipo_rotacion[$id_inter][$nit_scs]>=1 &&$tipo_rotacion[$id_inter][$nit_scs]<=2)
	{
		$bigHTML .= '<td style="background-color:#F90;">'.$tipo_rotacion[$id_inter][$nit_scs].'</td>';
		
	}
	else if($tipo_rotacion[$id_inter][$nit_scs]==0){
	    $bigHTML .= '<td style="background-color:#F00;">'.$tipo_rotacion[$id_inter][$nit_scs].'</td>';	
		
	}
	else {
		$bigHTML .= '<td>'.$tipo_rotacion[$id_inter][$nit_scs].'</td>';
	}

	$PROM_VENTAS=round(($IR)/count($meses_ventas),1);
	$PEDIR = round($PROM2*1.5 - ($row['exist']+ $unidades/$frac  ),0) ;
	$PEDIR2= round($PROM2*1.5 - ($row['exist']+ $unidades/$frac  ),0)-$CANT_OTRAS_SEDES;

	$bigHTML .='<td align="center"> '.$row['exist'].";$unidades".'</td>';
	$bigHTML .='<td align="center" class="'.$hidCols.'"> '.$Emn[$id_inter][$nit_scs].'</td>';
	$bigHTML .='<td align="center" class="'.$hidCols.'"> '.$Emx[$id_inter][$nit_scs].'</td>';
	$bigHTML .='<td align="center">'.$PROM_VENTAS.'</td>';
	$bigHTML .='<td align="center">'.$PROM2.'</td>';
	$bigHTML .='<td align="center">'.$PEDIR.'</td>';
	$bigHTML .='<td style="background-color:#0FF ;">'.$PEDIR2.'</td>';
	$bigHTML .='<td>'.$CANT_OTRAS_SEDES.'</td>';
	$bigHTML .='<td align="center">'.$IR.'</td>';
	$bigHTML .='<td>'.$SEDES[$COD_SUC].'</td>';
	$bigHTML .='</tr>';
	$bigHTML .='';

	if($PEDIR2>0){
		echo $bigHTML;
	};
 }
 
 ?>