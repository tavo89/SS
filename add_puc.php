<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"clientes_add")){header("location: centro.php");}

$cuenta=r("cuenta");
$boton=r("Guardar");
if($boton=="Guardar"){


$aux=explode("\n",$cuenta);

$n=count($aux);

for($i=0;$i<$n;$i++){
$aux2=explode(" ",$aux[$i],2);
$clase=substr($aux2[0],0,1);
$grupo=substr($aux2[0],0,2);
$Cuenta=substr($aux2[0],0,4);
$subCuenta=substr($aux2[0],0,6);
$des=$aux2[1];
$sql="INSERT IGNORE INTO puc_cuentas(clase,grupo,cuenta,subcuenta,des) VALUES('$clase','$grupo','$Cuenta','$subCuenta','$des')";
t1($sql);
//echo "<li>$clase/$grupo/$Cuenta/$subCuenta- $des</li>";
	
}
	
	
}
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once("HEADER.php"); ?>
<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">
<div class="grid-100 posicion_form">
<h1 align="center">PUC</h1>
<form action="add_puc.php" method="post" id="frm" class="uk-form">
<textarea name="cuenta" id="cuenta" cols="30" rows="30" style="width:400px;"></textarea>
<input type="submit" name="Guardar" value="Guardar" class="uk-button uk-button-primary uk-button-large">
<div id="mensaje">
</div>
  <input type="hidden" name="check" value="0" id="check" />
    <input type="hidden" name="num_d" value="0" id="num_d" />
    <input type="hidden" value="" name="html" id="pagHTML">
</form>
</div>
<?php require_once("FOOTER.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >
var cd=0;
var $nd=$('#num_d');

$('input').on("change",function(){
	
	$(this).prop('value',this.value);
});

$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});



	
	});

function add_dcto()
{
	//var fab=fabricante('fab'+cd,'fab'+cd,'dcto_'+cd,'fab_dcto'+cd);
	var html='<tr class="dcto_'+cd+'" style="font-size:24px"><td class="dcto_'+cd+'"><input class="dcto_'+cd+'" type="text" name="dcto_'+cd+'" id="dcto_'+cd+'"   style="width:200px" placeholder="DCTO %"/></td><td class="dcto_'+cd+'" width="60"><select class="dcto_'+cd+'" name="tipo_dcto'+cd+'" id="tipo_dcto'+cd+'"><option value="NETO">NETO</option><option value="PRODUCTO">PRODUCTO</option></select></td><td class="dcto_'+cd+'">Fabricante:</td><td class="dcto_'+cd+'" id="fab_dcto'+cd+'"></td></tr>';
	
	var $d=$(html);
	$d.appendTo('#descuentos');
	fabricante('fab'+cd,'fab'+cd,'dcto_'+cd,'fab_dcto'+cd);
	cd++;
	
	$nd.prop('value',cd);
	
	
};
function d(cant)
{
   var c=100+cant*1;
   var entre=100000;
   var vOri=1000;
   var d=(1-(entre/(c*vOri)) )*100;
   $dcto=$('#dcto');
   $dcto.prop('value',d);
};

</script>

</body>
</html>