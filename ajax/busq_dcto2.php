<?php
require_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");

$ref=r( 'ref' );
$id_cli=r( 'id_cli' );
$fab="";
$sql="SELECT * FROM ".tabProductos." WHERE id_pro='$ref'";
//echo "$sql<br>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){$fab=$row['fab'];}

$sql="SELECT * FROM dcto_fab WHERE id_cli='$id_cli' AND fabricante='$fab' ";
$rs=$linkPDO->query($sql);
//echo "$sql<br>";
if($row=$rs->fetch()){
	        
	        $dcto=$row['dcto'];
			$tipoD=$row['tipo_dcto'];
			$html=htmlentities("$dcto|$tipoD", ENT_QUOTES,"$CHAR_SET");
			echo $html;
			
}
else {echo "0";}

?>