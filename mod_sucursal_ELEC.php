<?php
include("Conexxx.php");
$boton=r('boton');
if($rolLv!=$Adminlvl ){header("location: centro.php");}
//if(isset($_REQUEST['boton']) && !empty($_REQUEST['boton'])){$boton=$_REQUEST['boton'];}
if($boton=="Guardar" ){
	
$resol=r('resol');
$feResol=r('feresol');
$inf=r('inf');
$sup=r('sup');
$pre=r('pre');
if(empty($pre)){$pre="---";}
$rangoFac=r('rango');


try { 
$linkPDO->beginTransaction();
$all_query_ok=true;

$sql="UPDATE sucursal SET cod_papel='$pre', resol_papel='$resol',fecha_resol_papel='$feResol',  rango_papel='$rangoFac' WHERE cod_su=$codSuc";
$linkPDO->exec($sql);



$sql="UPDATE seriales SET serial_sup='$sup', serial_inf='$inf' WHERE seccion='resol_papel' AND nit_sede=$codSuc";
$linkPDO->exec($sql);


$linkPDO->commit();
if($all_query_ok){
eco_alert("Guardado con Exito!");
js_location("mod_sucursal_ELEC.php");
}
else{eco_alert("ERROR! Intente nuevamente");}


}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

}

?>
<!DOCTYPE html>
<html>
<head>
<?php include_once("HEADER.php"); ?>

<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
</head>
<body>

<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
			<?php include_once("boton_menu.php"); ?>	


<div class="uk-width-9-10 uk-container-center">
<div class=" grid-100 posicion_form">

<!-- Lado izquierdo del Nav -->
<nav class="uk-navbar">
<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 

<!-- Centro del Navbar -->
<ul class="uk-navbar-nav uk-navbar-center" style="width:830px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->

<li><a href="mod_sucursal.php">POS</a></li>
<li><a href="mod_sucursal_COM.php">Computador</a></li>
<li class="uk-active"><a href="mod_sucursal_ELEC.php">Facturaci&oacute;n electr&oacute;nica</a></li>
      
</ul>
</nav>

<?php 
$SQL="SELECT * FROM `sucursal` s INNER JOIN seriales ss ON s.cod_su=ss.nit_sede WHERE cod_su='$codSuc' AND ss.seccion='resol_papel'";
$rs=$linkPDO->query($SQL);

if($row=$rs->fetch()){
$resol=$row['resol_papel'];
$fechaResol=$row['fecha_resol_papel'];
$inf=$row['serial_inf'];
$sup=$row['serial_sup'];
$pre=$row['cod_papel'];
$rango=$row['rango_papel'];


 ?>
<form  method="get" id="frm" class="uk-form">

<table align="center" class="uk-contrast dark_bg uk-text-large">
<tr>
<td>
<table align="center">

<tr>
<th colspan="4" style="font-size:24px">Resoluci&oacute;n Fac. Electr&oacute;nica</th>
</tr>
 
<tr>
<td>No. Resol.:</td>
<td><input name="resol" type="text" id="resol" value="<?php echo "$resol" ?>" /></td>
<td>Fecha Expedici&oacute;n:</td>
<td><input name="feresol" type="date" id="feresol" value="<?php echo "$fechaResol" ?>"  /></td>
</tr>
<tr>
<td >Prefijo:</td>
<td ><input name="pre" type="text" value="<?php echo "$pre" ?>"  /></td>
<td >Rango:</td>
<td ><input name="rango" type="text" value="<?php echo "$rango" ?>"  /></td>
</tr>

<tr>
<th colspan="4" style="font-size:24px">Numeracion Fac. sistema</th>
</tr>

<tr>
<td>Num. Inferior:</td>
<td><input name="inf" type="text" value="<?php echo "$inf" ?>"   />
</td>
<td >Num. Superior:</td>
<td ><input name="sup" type="text" value="<?php echo "$sup" ?>" /></td>
</tr>




<tr>
<td colspan="2" align="center">
<input type="submit" name="boton" value="Guardar" data-icon="check">
</td>
</tr>



</table>
<div id="mensaje">
</div>
  <input type="hidden" name="check" value="0" id="check" />
    <input type="hidden" name="num_d" value="0" id="num_d" />
</form>
<?php
}

?>

<?php include_once("FOOTER.php"); 
$rs=null;
$stmt=null;
$linkPDO= null;
?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >
var cd=0;
var $nd=$('#num_d');
$(document).ready(function(){


	
	});

function add_dcto()
{
	//var fab=fabricante('fab'+cd,'fab'+cd,'dcto_'+cd,'fab_dcto'+cd);
	var html='<tr class="dcto_'+cd+'" style="font-size:24px"><td class="dcto_'+cd+'"><input class="dcto_'+cd+'" type="text" name="dcto_'+cd+'" id="dcto_'+cd+'"   style="width:200px" placeholder="DCTO %"/></td><td class="dcto_'+cd+'" width="60"><select class="dcto_'+cd+'" name="tipo_dcto'+cd+'" id="tipo_dcto'+cd+'"><option value="NETO">NETO</option><option value="PRODUCTO">PRODUCTO</option></select></td><td class="dcto_'+cd+'">Operador:</td><td class="dcto_'+cd+'" id="fab_dcto'+cd+'"></td></tr>';
	
	var $d=$(html);
	$d.appendTo('#descuentos');
	fabricante('fab'+cd,'fab'+cd,'dcto_'+cd,'fab_dcto'+cd);
	cd++;
	
	$nd.attr('value',cd);
	
	
};
function d(cant)
{
   var c=100+cant*1;
   var entre=100000;
   var vOri=1000;
   var d=(1-(entre/(c*vOri)) )*100;
   //alert(c);
   $dcto=$('#dcto');
   $dcto.attr('value',d);
};

</script>

</div>
</div>

</body>
</html>