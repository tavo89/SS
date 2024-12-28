<?php
require_once("../Conexxx.php");
$name=limpiarcampo($_REQUEST['name']);
$id=limpiarcampo($_REQUEST['id']);
$class=limpiarcampo($_REQUEST['clase']);

$sql="SELECT * FROM ".tabProductos." group by fab";
	$rs=$linkPDO->query($sql);
	$selec="<select name=\"$name\" id=\"$id\" class=\"$class\"><option value=\"\"  selected></option>";
	while($row=$rs->fetch())
	{ 
		$fab=$row['fab'];
		$selec.="<option value=\"$fab\">$fab</option>";
	}
	$selec.="</select>";

	echo $selec;

?>