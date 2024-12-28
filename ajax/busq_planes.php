<?php
require_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");

$tabla="servicio_internet_lista_planes";
$codSuCol="cod_su";
$col_id="id";
// columnas para mostrar y modificar
if($fac_servicios_mensuales==1){$columns="nombre_servicio,precioplan,equipreco,servadic,tipo_cliente";}
else{$columns="estrato,anchobanda,precioplan,velbajada,velsubida,equipreco,servadic,tipo_cliente";}
$cols=explode(",",$columns);
$maxCols=count($cols);
$colSet[]="";
for($i=0;$i<$maxCols;$i++){$colSet[$i]=$cols[$i];}
$ORDER_BY="ORDER BY estrato,velbajada";

$sql="SELECT a.COLUMN_NAME, a.COLUMN_COMMENT FROM information_schema.COLUMNS a WHERE a.TABLE_NAME =  '$tabla' AND a.COLUMN_COMMENT !=  ''";
$rs=$linkPDO->query($sql);
$HEADER_TABLE[]="";

while($row=$rs->fetch()){$HEADER_TABLE[$row["COLUMN_NAME"]]=$row["COLUMN_COMMENT"];}
$cols="";
$max=count($colSet);
for($i=0;$i<$max;$i++){
	
	if($HEADER_TABLE[$colSet[$i]]=="ID"){$HIDDEN=" uk-hidden";}
	else{$HIDDEN="";}

	$cols.="<td class=\" $HIDDEN \">".$HEADER_TABLE[$colSet[$i]]."</td>";
}

$sql = "SELECT  $columns FROM $tabla WHERE  $codSuCol=$codSuc $ORDER_BY DESC  "; 
//echo $sql;

$rs =$linkPDO->query($sql); 
$nr=$rs->rowCount();;

$divCols=4;
$tableClass="uk-table uk-table-hover uk-table-striped";
if(isset($rs) && $nr>0){

?>
<table rules="all" frame="box" border="0" align="left" claslpadding="6px"  class="uk-table" id="tab_art" > 
<thead>
<?php echo $cols; ?>
</thead>
<tbody>

<?php
$i=0;
$ii=0;
$selFunct="";
while ($row = $rs->fetch()) 
{ 
$ii++;
//eco_alert("row!");

			$selFunct="selc_plan(";
			
for($i=0;$i<$max;$i++){

	
if($i!=($max-1)){$selFunct.="'".$row[$colSet[$i]]."',";}
else {$selFunct.="'".$row[$colSet[$i]]."');";}
 
	

	
}
			
         ?>
 
<tr onclick="<?php echo $selFunct ?>"  bgcolor="#FFFFFF" class="can_be_selected">
<?php



for($i=0;$i<$max;$i++){
	if($HEADER_TABLE[$colSet[$i]]=="ID"){$HIDDEN=" uk-hidden";}
	else{$HIDDEN="";}
	
	//gettype($row[$colSet[$i]])."-".
 
	
	if($rolLv!=$Adminlvl){$funct="";}
	if(!is_numeric($row[$colSet[$i]])){echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" >".$row[$colSet[$i]]."</td>";}
	else if(($row[$colSet[$i]]*1)<100){echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" >".$row[$colSet[$i]]."</td>";}
	else{echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" >$".money($row[$colSet[$i]])."</td>";}
}



?>
</tr> 
         
<?php 

$i++;
} ?>
</tbody>
</table>
<?php


	
}
else {echo "0";}
   ?>
