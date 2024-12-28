<?php
include("../../Conexxx.php");

//////////////////////////////////////////////////////////////////////////////// CABECERA TABLA ////////////////////////////////////////////////////

$tabla="rotacion_inv";
$codSuCol="cod_su";
$col_id="ref";
$columns="ref,cod_bar,des,exist,min,max,CP,Pp,tot_ventas";
$colSet= array(0=>"ref","cod_bar","des","exist","min","max","CP","Pp","tot_ventas");

$sql="SELECT a.COLUMN_NAME, a.COLUMN_COMMENT FROM information_schema.COLUMNS a WHERE a.TABLE_NAME =  '$tabla' AND a.COLUMN_COMMENT !=  ''";
$rs=$linkPDO->query($sql);
$HEADER_TABLE[]="";

while($row=$rs->fetch()){
	$HEADER_TABLE[$row["COLUMN_NAME"]]=$row["COLUMN_COMMENT"];
	
}
$cols="";
$max=count($colSet);
for($i=0;$i<$max;$i++){

	$cols.="<td>".$HEADER_TABLE[$colSet[$i]]."</td>";
}

$sql = "SELECT  $columns FROM $tabla WHERE  $codSuCol=$codSuc AND Pp>1 AND tot_ventas>1  ORDER BY CP DESC    "; 
$rs=$linkPDO->query($sql);

if($row=$rs->fetch()){
	if($see_warn_inv==1){
		

$rs=$linkPDO->query($sql);
?>
<input id="butt_gfv" type="button" value="DEJAR DE VER ESTO" name="boton" onClick="disable_list_inv_warn();" class=" uk-button uk-button-success uk-button-large" />
<div class="table-responsive">
<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class=" display dataTable table table-striped table-bordered table-hover tablaDataTables" > 

 <thead>
 <tr bgcolor="#595959" style="color:#FFF" valign="top"> 
      
<?php echo "".$cols;   ?>

       </tr>
  </thead>      
<tbody>
      
<?php 
$ii=0;
while ($row = $rs->fetch()) 
{ 

	$ii++;		

			$FunctPop=""; 
		//$FunctPop="ver_historial('$row[placa]','$row[modelo]','$row[color]','$row[km]','$row[id_propietario]');";	
         ?>
 
<tr  bgcolor="#FFF">
<?php

for($i=0;$i<$max;$i++){
	//gettype($row[$colSet[$i]])."-".
	$funct="mod_tab_row('tabTD$ii".$i."','$tabla','$colSet[$i]','".$row[$colSet[$i]]."',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','text','','');";
	if($rolLv!=$Adminlvl){$funct="";}
	if(gettype($row[$colSet[$i]]*1)!="double"){echo "<td id=\"tabTD$ii".$i."\" onDblClick=\"$funct\">".$row[$colSet[$i]]."</td>";}
	else{echo "<td id=\"tabTD$ii".$i."\" onDblClick=\"\">".money($row[$colSet[$i]])."</td>";}
}

?>
</tr> 
         
<?php  } ?>
</tbody>
</table>
</div>
<input id="butt_gfv" type="button" value="DEJAR DE VER ESTO" name="boton" onClick="disable_list_inv_warn();" class=" uk-button uk-button-success uk-button-large" />

<?php
	}
	else echo "0";

}///////////// FIN IF
else echo "0";

?>