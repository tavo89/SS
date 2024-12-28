<?php
require_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");
$busq=limpiarcampo($_REQUEST['busq']);
$Sdetalle=fullBusq($busq,"detalle");
$qry="SELECT $cols_busq FROM ".tabProductos." INNER JOIN inv_inter ON (".tabProductos.".id_pro=inv_inter.id_pro) WHERE nit_scs=$codSuc AND (fab LIKE '$busq%' OR id_clase LIKE '$busq%' OR inv_inter.id_inter = '$busq' OR inv_inter.id_pro = '$busq' OR $Sdetalle)";
//echo "$qry";
$rs=$linkPDO->query($qry);

if(isset($rs)){
?>
<table border="0" align="left" claslpadding="6px"  class="uk-table uk-table-hover uk-table-striped"> 
<?php require_once("encabezado_busq.php"); ?>
<tbody>
<?php
$i=0;
while ($row = $rs->fetch()) 
{ 
		    
			include("busq_rows.php");
			$selFunct="selc_ajus('$i','$id','$cod','$fe_ven');";
			
         ?>
 
<tr onclick="<?php echo $selFunct ?>" bgcolor="#FFFFFF" class="can_be_selected">
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