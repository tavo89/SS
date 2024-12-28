<?php
require_once("../Conexxx.php");
$per=limpiarcampo($_REQUEST['per']);
$idCli=limpiarcampo($_REQUEST['id_cli']);
$idSecc=limpiarcampo($_REQUEST['id_secc']);

	$sql="SELECT * FROM permisos WHERE id_usu='$idCli' AND id_secc='$idSecc'";
	$rs=$linkPDO->query($sql);
	
	if($row=$rs->fetch())
	{ 
		$permite=$row['permite'];
		$sql="UPDATE permisos SET permite='$per' WHERE id_usu='$idCli' AND id_secc='$idSecc'";
		$linkPDO->exec($sql);
		
		
	}
	

	else 
	{
		$permite=$row['permite'];
		$sql="INSERT INTO permisos(id_usu,id_secc,permite) VALUES('$idCli','$idSecc','$per')";
		$linkPDO->exec($sql);
		
	}
	
?>