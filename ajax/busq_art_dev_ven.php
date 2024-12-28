<?php
require_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");
$busq=limpiarcampo($_REQUEST['busq']);
$fullSearch=r("fs");

//$colBusq="".tabProductos.".id_pro LIKE '$busq%' OR detalle LIKE '$busq%' OR fab LIKE '$busq%' OR id_clase LIKE '$busq%' OR inv_inter.id_inter LIKE '$busq%'";

//$colBusq="detalle LIKE '$busq%' OR ".tabProductos.".id_pro='$busq' OR fab LIKE '$busq%' OR id_clase LIKE '$busq%' OR inv_inter.id_inter = '$busq'";
$colBusq="inv_inter.id_inter = '$busq' OR ".tabProductos.".id_pro='$busq' OR detalle LIKE '%$busq%' ";
$filtroCant="";

$qry="SELECT $cols_busq FROM ".tabProductos." INNER JOIN inv_inter ON (".tabProductos.".id_pro=inv_inter.id_pro) WHERE nit_scs=$codSuc $filtroCant AND ($colBusq)";
$q=$qry;

$rs=$linkPDO->query($qry);
$nr=$rs->rowCount();;

$tableClass="uk-table uk-table-hover uk-table-striped";
if(isset($rs) && $nr>0){
?>
<table rules="all" frame="box" border="0" align="left" claslpadding="6px"  class="uk-table" id="tab_art" > 
 <?php require_once("encabezado_busq.php"); ?> 
<tbody>
<!--
<tr>
<td colspan="9"><?php echo "$qry<br>nr: $nr";  ?></td>
</tr>
-->
<?php
$i=0;
$selFunct="";
while ($row = $rs->fetch()) 
{ 
//eco_alert("row!");
		   include("busq_rows.php");
			$selFunct="selc_dev('$i','$id','$cod','$fe_ven');";
			
         ?>
 
<tr onclick="<?php echo $selFunct ?>"  bgcolor="#FFFFFF" class="can_be_selected">
<?php include("busq_td.php");  ?>
</tr> 
         
<?php 

$i++;
         } 
      
?>
</tbody>
</table>
<?php

}
else echo "0";

?>