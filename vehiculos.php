<?php 
require_once("Conexxx.php");
$busq="";
$val="";
$val2="";
$boton="";
$pre="";
$fechaFacs="";
$opc="";

$adminFilter="AND cod_caja='$codCaja'";

if($rolLv==$Adminlvl || val_secc($id_Usu,"caja_centro"))$adminFilter="";

if(isset($_REQUEST['fecha_facs']))$fechaFacs=$_REQUEST['fecha_facs'];
//if(isset($_SESSION['fecha_facs']))$fechaFacs=$_SESSION['fecha_facs'];

if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val=$_REQUEST['valor'];
if(isset($_REQUEST['valor2']))$val2=$_REQUEST['valor2'];
if(isset($_REQUEST['opc']))$boton= $_REQUEST['opc'];
if(isset($_REQUEST['pre']))$pre= $_REQUEST['pre'];
//////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_ven";
$PAG_fechaF="fechaF_ven";
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button\">";
$A="";
if(isset($_REQUEST['fechaI'])){$fechaI=limpiarcampo($_REQUEST['fechaI']); $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=limpiarcampo($_REQUEST['fechaF']);$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" class=\"uk-button uk-icon-undo\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}





if($opc=="QUITAR")
{
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button\" >";
	$fechaI="";
	$fechaF="";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	$A="";
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------

$cols="<th width=\"50\">#</th>
<td width=\"40\"></td>
<th width=\"80\">Placa</th>
<th width=\"80\">Modelo</th>
<th width=\"150\">Conductor</th>
<th width=\"100\">C.C.</th>
<th width=\"80\">Ruta</th>
<th width=\"120\">Estado</th>
";


$tabla="camion";
$col_id="id_cami";
//$columns="a.id_usu,a.nombre,b.placa,b.modelo,b.marca,c.des_ruta,c.id_ruta";
$columns="a.id_usu,a.nombre,b.placa,b.modelo,b.marca,b.id_cami,b.estado,c.des_ruta,c.id_ruta";
$url="vehiculos.php";
$url_dialog="dialog_invIni.php";
$url_mod="mod_vehiculo.php";
$url_new="reg_vehiculo.php";
if(val_secc($id_Usu,"fac_crea") || $rolLv==$Adminlvl)$url_new="reg_vehiculo.php";
else $url_new="#";
$order="fecha";

/////////////////////////////////////////////////////////////////////// PAGINACION ////////////////////////////////////////////////////
$pag="";
$limit = 20; 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) { $pag = 1; } 
$offset = ($pag-1) * $limit; 
$ii=$offset;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 
$num_fac=$val;
$sql = "SELECT  $columns FROM usuarios a LEFT JOIN camion b ON a.id_usu=b.id_usu LEFT JOIN ruta c ON b.id_ruta=c.id_ruta  WHERE  a.cod_su=b.cod_su AND b.cod_su=c.cod_su AND c.cod_su=$codSuc   LIMIT $offset, $limit"; 

$sqlPag=$sql;
//echo "$sql";


if($boton=='del_aff'&& !empty($val)){
	

	$linkPDO->exec("DELETE FROM $tabla WHERE $col_id=$val AND nit_scs=$codSuc");
	header("location: $url");
	}
///////////////////////////////////////// MOD FAC VEN //////////////////////////////////////////////////////////////////////

if($boton=='mod'&& !empty($val) && !empty($pre) && ($val2!="CERRADA") ){
	
	$_SESSION['num_fac_venta']=$val;
	$_SESSION['pre']=$pre;
	
	header("location: $url_mod?num_fac_venta=$val&pre=$pre");
	}
 //if($val2=="ANULADO")eco_alert("Factura ANULADA, no se permiten cambios"); 
 if($val2=="CERRADA")eco_alert("Factura CERRADA, no se permiten cambios"); 
 

//---------------------------------------------------------------------------------------------------------------------------
 
////////////////////////////////////////////////////////////////// PAGINACION PARTE 2//////////////////////////////////////////////////// 
$sqlTotal = "SELECT FOUND_ROWS() as total"; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM fac_venta WHERE nit=$codSuc $adminFilter AND (num_fac_ven LIKE '$busq%' OR id_cli LIKE '$busq%' OR placa LIKE '$busq%' OR nom_cli LIKE '$busq%' OR vendedor LIKE '$busq%' OR prefijo LIKE '$busq%') $A";



$rs=$linkPDO->query($sql_busq);

	
	}

 //echo "$sql";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once("HEADER.php"); ?>	
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">



<nav class="uk-navbar">
<ul class="uk-navbar-nav">
<li><a href="<?php echo $url_new ?>" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Facturar Venta</a></li>
<?php 
if(($rolLv==$Adminlvl || val_secc($id_Usu,"compras")) ){
?>
<li><a href="compras.php" ><i class="uk-icon-list <?php echo $uikitIconSize ?>"></i>&nbsp;Compras</a></li>


<?php

}
?>

<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>
</ul>
<!--
<div class="uk-navbar-flip">

<ul class="uk-navbar-nav">
<li class="uk-parent" data-uk-dropdown="">
<a href="">Parent</a>
<div class="uk-dropdown uk-dropdown-navbar">
  <ul class="uk-nav uk-nav-navbar">
  <li><a href="#">Item</a></li>
 <li><a href="#">Another item</a></li>
  <li class="uk-nav-header">Header</li>
 <li><a href="#">Item</a></li>
 <li><a href="#">Another item</a></li>
    <li class="uk-nav-divider"></li>
       <li><a href="#">Separated item</a></li>
        </ul>

</div>

                                        </li>
                                        <li><a href="">Item</a></li>
                                        <li class="uk-active"><a href="">Active</a></li>
                                    </ul>
</div>
-->
</nav>
<?php
$tipoImp="";
if(isset($_REQUEST['tipo_imp']))$tipoImp=$_REQUEST['tipo_imp'];
//eco_alert("Tipo Imp: $tipoImp, Button: $boton");

imp_fac($num_fac,$pre,$boton);

if($boton=="Imprimir Dia" && !empty($fechaFacs))
{
	//echo "ENTRA".$opc."<br>";
	$FacInf="";
	$FacSup="";
	$_SESSION['fecha_facs']=$fechaFacs;
	if(isset($_REQUEST['facInf']))$FacInf=$_REQUEST['facInf'];
	if(isset($_REQUEST['facSup']))$FacSup=$_REQUEST['facSup'];
	if(!empty($FacInf) && !empty($FacSup))
	{
		$_SESSION['facInf']=$FacInf;
		$_SESSION['facSup']=$FacSup;
		}
	popup("imp_lista_fac.php","Facturas de Venta", "900px","650px");
};
?>
<h1 align="center">VEH&Iacute;CULOS</h1>
<br><br><br>
<div id="sb-search" class="sb-search">
						<form>
							<input class="sb-search-input" placeholder="Ingrese su b&uacute;squeda..." type="text" value="" name="busq" id="search">
							<input class="sb-search-submit" type="submit" value="Buscar" name="opc">
							<span class="sb-icon-search"></span>
						</form>
					</div>
 <br><br><br><br><br>
<form action="<?php echo $url ?>" method="get" name="form" class=" uk-form">

<div class="grid-20">
<table   cellpadding="0" cellspacing="1" align="" >
<thead>
<TR bgcolor="#CCCCCC">
<TH colspan="5" align="center">FECHA </TH>
</TR>
</thead>
<tbody>
<tr>
<td>Inicia:
</td>
<td>
<input type="date" name="fechaI" id="date" value="<?php echo $fechaI ?>"  style="width:135px;">
</td>
<td>Termina:
</td>
<td>
<input type="date" name="fechaF" id="date" value="<?php echo $fechaF ?>" style="width:135px;">
</td>
<td><?php echo $botonFiltro ?></td>
</tr>
</tbody>
</table>
</div>

<div class="grid-40 push-20">
<table align="" cellpadding="0" cellspacing="1" >
<thead>
<TR bgcolor="#CCCCCC">

<th colspan="6">Imprimir Cola Facturas</th>
</TR>
</thead>
<tbody>
<tr>
<td>Fecha:</td>
<td>
<input type="date" name="fecha_facs" id="dateFacs" value=""  style="width:135px;">
</td>
<td>Rango Facturas:</td>
<td><input type="text" value="" name="facInf" placeholder="Num. Inferior" data-inline="true" data-mini="true"></td>
<td><input type="text" value="" name="facSup" placeholder="Num. Superior" data-inline="true" data-mini="true"></td>
<td height="24">
<input type="submit" value="Imprimir Dia" name="opc" class="uk-button">
</td>

</tr>
</tbody>
</table>
</div>

<br><br><br><br>
<?php 

//echo "$sqlPag";
require("PAGINACION.php"); ?>	
<?php //echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>
<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped" > 
 <thead>
      <tr bgcolor="#595959" style="color:#FFF" valign="top"> 
      
<?php echo $cols;   ?>

       </tr>
        
  </thead>
  <tbody>        
      
<?php 
//echo "$sql";
$style_no_cerradas="";
while ($row = $rs->fetch()) 
{ 
$ii++;
		$cod_cami=$row['id_cami'];
		$cod_ruta=$row['id_ruta'];
		$desRuta=$row['des_ruta'];
		$idChofer=$row['id_usu'];
		$nombreChofer=$row['nombre'];
		$placa=$row['placa'];
		$modelo=$row['modelo'];
		$marca=$row['marca'];
		$estado=$row['estado'];
			
			
         ?>
 
<tr  bgcolor="#FFF" style="<?php echo $style_no_cerradas ?>" > 
<th><?php echo $ii ?></th>
<td>

<table cellpadding="0" cellspacing="0">
<tr>

<td>
<div class="uk-button-group" >
<button class="uk-button uk-button-primary"><i class="uk-icon-cog"></i> &nbsp;Opc.</button>
<div data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false" class="">
<button class="uk-button uk-button-primary"><i class="uk-icon-caret-down"></i></button>
<div class="uk-dropdown uk-dropdown-small">
<ul class="uk-nav uk-nav-dropdown">

<li><a href="<?php echo $url ?>"><i class="uk-icon-pencil"></i>&nbsp;Modificar</a></li>


<!--<li><a href="#"><i class="uk-icon-check-square-o"></i> Confirma Tras.</a></li>-->

<li class="uk-nav-divider"></li>

<?php 
if($rolLv==$Adminlvl || val_secc($id_Usu,"fac_anula")){
?>
<li><a href="#" onMouseUp=""><i class="uk-icon-remove"></i>&nbsp;Eliminar</a></li>
<?php 
}
?>
</ul>
  </div>
      </div>
        </div>
</td>


</tr>

</table>

</td>             
<td><?php echo $placa; ?></td>
<td style=""><?php echo $modelo ?></td>
<td style=""><?php echo $nombreChofer ?></td> 
<td><?php echo $idChofer ?></td>
<td><?php echo $desRuta ?></td>

<td><b><?php echo $estado ?></b></td>

</tr> 
         
<?php 
         } 
      ?>
 

 
         
  </tbody>        
   
</table>
<?php include("PAGINACION.php"); ?>	
</form>
</div>

<?php require_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 	
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >
function close_fv(nf,pre)
{
	if(confirm('Desea Cerrar esta  Factura de Venta?NO SE PERMITIRAN MODIFICACIONES'))
	{
	$.ajax({
		url:'ajax/cerrar_fv.php',
		data:{num_fac_venta:nf,pre:pre},
		type:'POST',
		dataType:'text',
		success:function(text){
			
			//alert(text);
			//$('<p>'+text+'</p>').appendTo('#salida');
			if(text!=0)
			{
				//alert('FACTURA CERRADA');
				//alert(text);
				location.assign('ventas.php?opc=Imprimir&valor='+nf+'&pre='+pre+'&tipo_imp=post&pag=1');
				
				}
				else if(text==0){
					
					//alert('La factura  CERRADA');
					location.assign('ventas.php?opc=Imprimir&valor='+nf+'&pre='+pre+'&tipo_imp=post&pag=1');
					}
			//else alert('Actualizado');
		}
		
		});
	}

};
$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=UTF-8');}
catch(e){}
}});
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=UTF-8');}
catch(e){}
}});

	
	});





</script>
</body>
</html>