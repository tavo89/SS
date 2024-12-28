<?php
require_once("../Conexxx.php");
$c=limpiarcampo($_REQUEST['c']);
$mod=limpiarcampo($_REQUEST['mod']);
$busq=limpiarcampo($_REQUEST['busq']);
$Sdetalle=fullBusq($busq,"detalle");

$filtroSede=" AND nit_scs=$codSuc";
//if($MODULES["MULTISEDES"]==1){$filtroSede="";}

$qry="SELECT $cols_busq FROM ".tabProductos." INNER JOIN inv_inter ON (".tabProductos.".id_pro=inv_inter.id_pro) WHERE    (".tabProductos.".id_pro LIKE '$busq%' OR $Sdetalle OR fab LIKE '$busq%' OR id_clase LIKE '$busq%' OR inv_inter.id_inter LIKE '$busq%') $filtroSede LIMIT 0,200";

$rs=$linkPDO->query($qry);
$selFunc="";
//echo "$qry";


if($row = $rs->fetch()){
$qry="SELECT $cols_busq FROM ".tabProductos." INNER JOIN inv_inter ON (".tabProductos.".id_pro=inv_inter.id_pro) WHERE  (".tabProductos.".id_pro LIKE '$busq%' OR $Sdetalle OR fab LIKE '$busq%' OR id_clase LIKE '$busq%' OR inv_inter.id_inter LIKE '$busq%') $filtroSede LIMIT 0,200";

$rs=$linkPDO->query($qry);
?>
<table border="0" align="left" claslpadding="6px"  class="uk-table" id="tab_art"> 

<?php require_once("encabezado_busq.php"); ?>
<?php
$i=0;
while ($row = $rs->fetch()) 
{ 
		    
		include("busq_rows.php"); 
		$selFunc=limpiarJS("selc('$i','$id','$cod',$c,'$mod','$fe_ven');"); 			
?>
 
<tr onclick="<?php echo $selFunc ?>"  bgcolor="#FFFFFF" class="can_be_selected">

<?php include("busq_td.php");  ?>

</tr> 
         
<?php 

$i++;
         } 
      
?>
</table>
<?php

}
else echo "0";
   ?>