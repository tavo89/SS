<?php
require_once("Conexxx.php");

if(isset($_REQUEST['boton'])){
$mun= $_REQUEST['mun'];
$mun_reg= $_REQUEST['mun_reg'];
$dep_reg= $_REQUEST['dep_reg'];
$boton=$_REQUEST['boton'];
}



if($boton="Borrar Municipio" && isset($mun_reg) &&!empty($mun_reg)){
$linkPDO->exec("DELETE FROM municipio WHERE id_mun=$mun_reg");
}

if($boton="Guardar" && isset($mun) &&!empty($mun)&& isset($dep_reg) &&!empty($dep_reg)){
$linkPDO->exec("INSERT INTO municipio (municipio,id_dep) VALUES ('$mun','$dep_reg')");
$consulta="INSERT INTO municipio (municipio,id_dep) VALUES ('$mun','$dep_reg')";
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>
</title>
<link rel="stylesheet" href="JS/jquery.mobile-1.3.0/jquery.mobile-1.3.0.min.css" type="text/css" />

<script src="JS/jquery-2.1.1.js"></script>

<script type="text/javascript" language="javascript1.5" src="JS/jquery.mobile-1.3.0/jquery.mobile-1.3.0.min.js"></script>
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
<script type="text/javascript" language="javascript1.5" >

function class_in(){//alert('ci !');

location.assign('inventario_total.php');
/*
$.ajax({
		url:'ajax/save_cl.php',
		data:$('#form_class').serialize(),
	    type: 'POST',
		dataType:'text',
		success:function(text){//alert(text);
			if(text!=0){
				$('#volver').click();
						}
			else {alert('Hay  campos vacios');}
			
		},
		error:function(xhr,status){alert('Error, xhr:'+xhr+' STATUS:'+status);}
		});

	*/	
		
};
</script>

</head>
<body>

<div data-role="page" data-theme="b" >

<div data-role="content">
<h1>Municipios</h1>
<?php //echo "boton:".$boton." ; dep: ".$dep."<br>INSERT INTO departamento (departamento) VALUES ('$dep')"; ?>
<form action="mun.php" method="get" name="mun" id="form_mun">

<table  width="600px">
<tr>
<td><label>Municipio:</label></td>
</tr>
<tr>

<td width="400px" colspan="2"><input name="mun" type="text" placeholder="Municipio"/></td>
</tr>

<tr>
<td width="200px">
Departamento:
</td>
</tr>
<tr>
<td colspan="2">
<select name="dep_reg" data-inline="false" data-theme="a">
<option value="" selected ></option>
<?php

$rs=$linkPDO->query("SELECT * FROM departamento ORDER BY departamento");
while($row = $rs->fetch()) 
         { 
		 echo "<option value=\" ".$row["id_dep"]."\">".$row["departamento"]."</option>  ";
		 }
?>

</select>

</td>
</tr>
<tr>
<td width="200px">
Municipios Registrados:
</td>
</tr>
<tr>
<td>
<select name="mun_reg" data-inline="false" data-theme="a">
<option value="" selected ></option>
<?php

$rs=$linkPDO->query("SELECT * FROM municipio ORDER BY municipio");
while($row = $rs->fetch()) 
         { 
		 echo "<option value=\" ".$row["id_mun"]."\">".$row["municipio"]."</option>  ";
		 }
?>

</select>
</td>
<td>
<input type="submit" value="Borrar Municipio" name="boton" data-icon="delete" data-theme="a">
</td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="submit" value="Guardar" name="boton" data-icon="check">
</td>
</tr>

</table>
</form>
</div>
</div>
</body>
</html>