<?php
require_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");

$ref=limpiarcampo($_REQUEST['ref']);
$id_cli=limpiarcampo($_REQUEST['id_cli']);

$sql="SELECT * FROM descuentos WHERE id_pro='$ref' AND id_cli='$id_cli' ";
$rs=$linkPDO->query($sql);

if($row=$rs->fetch()){
	        
	        $dcto=$row['dcto'];
			$html=htmlentities("$dcto", ENT_QUOTES,"$CHAR_SET");
			echo $html;
			
}
else {echo "0";}

?>