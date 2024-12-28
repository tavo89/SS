<?php
require_once("Conexxx.php");
$boton="";
$check="";
$dep="";
$mun="";
$nit= "";
$nom= "";
$tel1= "";
$tel2= "";
$dir= "";
$repre= "";
$mail="";

if(isset($_REQUEST['dep'])){
$dep=$_REQUEST['dep'];
}
if(isset($_REQUEST['boton']) ){
$boton= $_REQUEST['boton'];


 	
$nit= $_REQUEST['nit_s'];
$nom= $_REQUEST['nom_s'];
$tel1= $_REQUEST['tel1'];
$tel2= $_REQUEST['tel2'];
$dir= $_REQUEST['dir'];
$repre= $_REQUEST['repre'];
$mail=$_REQUEST['mail'];
$mun=$_REQUEST['mun'];

}
/*echo "INSERT INTO  `motosem`.`sucursal` (
`nit_su` ,
`nombre_su` ,
`dir_su` ,
`tel1` ,
`tel2` ,
`email_su` ,
`representante_se` ,
`id_dep` ,
`id_mun`
)
VALUES (
'$nit',  '$nom',  '$dir',  '$tel1',  '$tel2',  '$mail',  '$repre',  '$dep',  '$mun'
);";
*/
if($boton=='Guardar'&& isset($nit) &&!empty($nit) && isset($dep) &&!empty($dep) && isset($mun) &&!empty($mun)){
	//echo "ENTRO";
$rs=$linkPDO->query("SELECT * FROM sucursal WHERE nit_su='$nit'");
$row=$rs->fetch();
$result=$row['nit_su'];
if(empty($result)){
$linkPDO->exec("INSERT INTO  `motosem`.`sucursal` (
`nit_su` ,
`nombre_su` ,
`dir_su` ,
`tel1` ,
`tel2` ,
`email_su` ,
`representante_se` ,
`id_dep` ,
`id_mun`
)
VALUES (
'$nit',  '$nom',  '$dir',  '$tel1',  '$tel2',  '$mail',  '$repre',  '$dep',  '$mun'
);");
$check=1;
}
else $check=2;

//header("location:agregar_inventario.php?ck=1");
}

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="JS/jquery.mobile-1.3.0/jquery.mobile-1.3.0.min.css" type="text/css" />
<link rel="stylesheet" href="JS/stilos-opciones.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>
Agregar
</title>
<script src="JS/jquery-2.1.1.js"></script>

</head>
<body class="ui-content" background="Imagenes/fondogris.PNG">
<script language="javascript1.5" type="text/javascript">

function selc(i,id)
{
	$('#id_pro').prop('value',id);
}


function br()
{
	var v=$("#sb").val();
	if($("#sb").length > 0)
	{

$.ajax({
	url:"ajax/br_ao.php",
	data:{ba:v},
	type:"POST",
	cache:"false",
	dataType:"text",
	success:function(text){
	    var $art=$(text);
		$('#busqArt').html($art)//.appendTo('#busqArt');
		},
	error:function(xhr,status){simplePopUp('Ups! Ha ocurrido un error..'); simplePopUp('xhr:' + xhr); simplePopUp('status:' + status);}
	});	
	
	}

};


/*
$(function() {

			$("#btnBusq").click(function() {
				var theName = $.trim($("#sb").val());

				if(theName.length > 0)
				{
					$.ajax({
					  type: "POST",
					  url: "ajax/br_ao.php",
					  data: ({name: theName}),
					  cache: false,
					  dataType: "text",
					  success: onSuccess
					});
				}
			});

			$("#resultLog").ajaxError(function(event, request, settings, exception) {
			  $("#resultLog").html("Error Calling: " + settings.url + "<br />HTTP Code: " + request.status);
			});

			function onSuccess(data)
			{
				$("#resultLog").html("Result: " + data);
			}

		});
		
	*/	
	
</script>

<div data-role="page" data-theme="b" >

<div data-role="content">
<div id="resultLog"></div>

<h1>Registrar Sucursal</h1>
<form action="agregar_su.php" method="get" name="addsu">
<div data-role="popup" id="busqArt">


</div>
<?php  

if($check==1){
	
	?>
    <script language="javascript1.5">
	simplePopUp('Guardado Con Exito');
	location.assign('sucursales.php');
	</script>
    <?php
	
	}
?>

<?php  

if($check==2){
	
	?>
    <script language="javascript1.5">
	simplePopUp('ya hay una sucursal Registrada con este NIT <?php echo $nit ?>');
	</script>
    <?php
	
	}
?>


<table align="left" >
<tr>
<td colspan="2"><label>N.I.T:</label></td>
</tr>
<tr>
<td colspan="2"><input name="nit_s" value="<?php if(isset($_REQUEST['nit_s']))echo $_REQUEST['nit_s']; ?>" type="text" placeholder="NIT"  id="NIT"  /></td>
</tr>
<tr><td colspan="2"><label>Sucursal:</label></td>
</tr>
<tr><td colspan="2"><input name="nom_s" value="<?php if(isset($_REQUEST['nom_s']))echo $_REQUEST['nom_s'] ?>" type="text" placeholder="Nombre de la Sucursal" id="nom_s" /></td>
</tr>
<tr><td colspan="2"><label>Direcci&oacute;n:</label></td>
</tr>
<tr><td colspan="2"><input name="dir" value="<?php if(isset($_REQUEST['dir']))echo $_REQUEST['dir'] ?>" type="text" placeholder="Direcci&oacute;n" id="dir" /></td>
</tr>
<tr><td colspan="2"><label>Tel. 1:</label></td>
</tr> 
<tr>
<td colspan="2"><input name="tel1" value="<?php if(isset($_REQUEST['tel1']))echo $_REQUEST['tel1'] ?>" type="text" placeholder="Tel&eacute;fono 1" id="tel1" /></td>
</tr>
<tr><td colspan="2"><label>Tel. 2:</label></td>
</tr> 
<tr>
<td colspan="2"><input name="tel2" value="<?php if(isset($_REQUEST['tel2']))echo $_REQUEST['tel2'] ?>" type="text" placeholder="Tel&eacute;fono 2" id="tel2" /></td>
</tr>
<tr><td colspan="2"><label>E-mail:</label></td>
</tr> 
<tr>
<td colspan="2"><input name="mail" value="<?php if(isset($_REQUEST['mail']))echo $_REQUEST['mail'] ?>" type="text" placeholder="Correo electr&oacute;nico" id="mail" /></td>
</tr>

<tr><td colspan="2"><label>Representante:</label></td>
</tr> 
<tr>
<td colspan="2"><input name="repre" value="<?php if(isset($_REQUEST['repre']))echo $_REQUEST['repre'] ?>" type="text" placeholder="Representante" id="repre" /></td>
</tr>
<tr><td colspan="2"><label>Departamento:</label></td>
</tr> 
<tr>
<td colspan="2">
<select name="dep" data-inline="false" data-theme="a" onChange="document.forms['addsu'].submit()">
<option value="" selected></option>

<?php

$rs=$linkPDO->query("SELECT * FROM departamento ORDER BY departamento");
while($row = $rs->fetch()) 
         {
		 if(isset($_REQUEST['dep'])&&$_REQUEST['dep']==$row["id_dep"]){echo "<option value=\"".$row["id_dep"]."\" selected>".$row["departamento"]."</option>  ";} 
		 else {echo "<option value=\"".$row["id_dep"]."\">".$row["departamento"]."</option>  ";}
		 }
?>

</select>

</td>
</tr>
<tr><td colspan="2"><label>Municipio:</label></td>
</tr> 
<tr>
<td>
<?php

?>
<select name="mun" data-inline="false" data-theme="a">
<option value="" selected ></option>
<?php


if(isset($_REQUEST['dep'])){
$rs=$linkPDO->query("SELECT * FROM municipio WHERE id_dep=$dep ORDER BY municipio");

while($row = $rs->fetch()) 
         { 
		 if(isset($_REQUEST['mun'])&&$_REQUEST['mun']==$row["id_mun"]){echo "<option value=\"".$row["id_mun"]."\" selected>".$row["municipio"]."</option>  ";}
		else{ echo "<option value=\"".$row["id_mun"]."\">".$row["municipio"]."</option>  ";}
		 }
		 
}
?>

</select>
</td>


<tr>
<td colspan="2">
<table cellpadding="0" cellspacing="0">
<tr>
<td><input class="ui-btn-active" name="boton" type="submit" value="Guardar" data-icon="check"/></td>
<td><input class="ui-btn-active" type="button" value="Volver" onClick="location.assign('sucursales.php')"></td>
</tr>
</table>
</td>
</tr>
</table>


</form>
</div>


</div>
<?php



 ?>
</body>
</html>