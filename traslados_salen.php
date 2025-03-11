<?php 
include_once("Conexxx.php");
$UserFilter="AND fecha>='$fechaCreaUsu'";
if($rolLv==$Adminlvl || $separar_registros_por_usuarios==0)$UserFilter="";
$busq="";
$val="";
$val2="";
$boton="";
$pre="";
$fechaFacs="";
$opc="";

 

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
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button uk-button-success\">";
$A="";
if(isset($_REQUEST['fechaI'])){$fechaI=limpiarcampo($_REQUEST['fechaI']); $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=limpiarcampo($_REQUEST['fechaF']);$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" class=\"uk-button uk-button-danger uk-icon-undo\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}





if($opc=="QUITAR")
{
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button uk-button-success\" >";
	$fechaI="";
	$fechaF="";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	$A="";
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------

$cols="<th width=\"50\">#</th>
<td width=\"40\"></td>
<th width=\"80\">Remisi&oacute;n</th>
<th width=\"200\">Cliente</th>
<th width=\"150\">Origen </th>
<th width=\"150\">Destino</th>

<th width=\"150\">Elaboro</th>
<th >TOT</th>
<th width=\"100\">Estado</th>
<th width=\"120\">Recibido</th>
<th width=\"150\">Fecha</th>";


$tabla="fac_remi";
$col_id="id_pro";
$columns="num_fac_ven,id_cli,nom_cli,fecha,tel_cli,dir,mecanico,vendedor,sub_tot,iva,descuento,tot,placa,tipo_cli,anulado,prefijo,tipo_venta,anticipo_bono,cod_caja,sede_destino,nit,estado";
$url="traslados_salen.php";
$url_dialog="dialog_invIni.php";
$url_mod="mod_remi.php";
$url_new="fac_remi.php";
if(val_secc($id_Usu,"fac_crea") || $rolLv==$Adminlvl)$url_new="fac_remi.php";
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
$sql = "SELECT  $columns FROM fac_remi  WHERE  nit=$codSuc AND tipo_cli='Traslado' $UserFilter $A ORDER BY $order desc  LIMIT $offset, $limit"; 

$sqlPag=$sql;
//echo "$sql";


if($boton=='del_aff'&& !empty($val)){
	

	$linkPDO->exec("DELETE FROM $tabla WHERE $col_id=$val AND nit_scs=$codSuc");
	header("location: $url");
	}
///////////////////////////////////////// MOD FAC VEN //////////////////////////////////////////////////////////////////////

if($boton=='mod'&& !empty($val) && !empty($pre) && ($val2!="CERRADA") &&($val2!="ANULADO" || $rolLv>=$Adminlvl)){
	
	$_SESSION['num_fac_venta']=$val;
	$_SESSION['pre']=$pre;
	
	header("location: $url_mod?num_fac_venta=$val&pre=$pre");
	}
 //if($val2=="ANULADO")eco_alert("Factura ANULADA, no se permiten cambios"); 
 if($val2=="CERRADA")eco_alert("Factura CERRADA, no se permiten cambios"); 
 

//---------------------------------------------------------------------------------------------------------------------------
 
////////////////////////////////////////////////////////////////// PAGINACION PARTE 2//////////////////////////////////////////////////// 
$sqlTotal = "SELECT COUNT(*) as total FROM fac_remi WHERE nit=$codSuc"; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM fac_remi WHERE nit=$codSuc $UserFilter AND tipo_cli='Traslado' AND (num_fac_ven LIKE '$busq%' OR id_cli LIKE '$busq%' OR placa LIKE '$busq%' OR nom_cli LIKE '$busq%' OR vendedor LIKE '$busq%' OR prefijo LIKE '$busq%') $A";



$rs=$linkPDO->query($sql_busq);

	
	}

 //echo "$sql";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("HEADER.php"); ?>	
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
			<?php include_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">



<!-- Lado izquierdo del Nav -->
		<nav class="uk-navbar">

		<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/favSmart.png" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-center" style="width:430px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
			
<li class="ss-navbar-center"><a href="<?php echo $url_new."?origen=$codSuc" ?>" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Genera Traslado</a></li>
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
			
			<!-- Lado derecho del Navbar -->
				
		<div class="uk-navbar-content uk-hidden-small uk-navbar-flip">
			<form class="uk-form uk-margin-remove uk-display-inline-block">
				<div class="uk-form-icon">
						<i class="uk-icon-search"></i>
						<input type="text" name="busq" placeholder="Buscar..." class="">
				</div>
				<input type="submit" value="Buscar" name="opc" class="uk-button uk-button-primary">
			</form>
		</div>
			
		</nav>

<?php
$tipoImp="";
if(isset($_REQUEST['tipo_imp']))$tipoImp=$_REQUEST['tipo_imp'];
//eco_alert("Tipo Imp: $tipoImp, Button: $boton");


if($imprimir_remi_pos==0){imp_fac($num_fac,$pre,$boton,2,$val2,0,7);}
else{imp_fac_pos($num_fac,$pre,$boton,3,$val2);}
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
<h1 align="center">MERCANC&Iacute;A DESPACHADA</h1>
 

<form action="<?php echo $url ?>" method="get" name="form" class=" uk-form">

<div class="grid-20">
<table   cellpadding="0" cellspacing="1" align="" class="creditos_filter_table">
<thead>
<TR bgcolor="#CCCCCC">
<TH colspan="5" align="center">Fecha </TH>
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



<br><br><br><br>
<?php 

//echo "$sqlPag";
include("PAGINACION.php"); ?>	
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
while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $cod_fac =$row["num_fac_ven"]; 
            $nom_cli = $row["nom_cli"]; 
			$dir = $row["dir"];
			$tel = $row["tel_cli"];
			$mecanico = $row["mecanico"];
			$vendedor = $row["vendedor"]; 
			$fecha = $row["fecha"];
			$tot = money($row["tot"]*1);
		
			$id_cli = $row["id_cli"];
			$tipoCli=$row['tipo_cli'];
			$estado=$row['anulado'];
			$pre=$row['prefijo'];
			$formaPago=$row['tipo_venta'];
			$anti=$row['anticipo_bono'];
			$cod_caja=$row['cod_caja'];
			$confirma=$row['estado'];
			$codDest=$row['sede_destino'];
			$codOrig=$row['nit'];
			if($anti=="SI")$add_des="(ANTICIPO)";
			else $add_des="";
			
         ?>
 
<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td>

<div class="uk-button-group" >
<button class="uk-button uk-button-primary"><i class="uk-icon-cog"></i> &nbsp;Opc.</button>
<div data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false" class="">
<button class="uk-button uk-button-primary"><i class="uk-icon-caret-down"></i></button>
<div class="uk-dropdown uk-dropdown-small">
<ul class="uk-nav uk-nav-dropdown">

<li><a href="<?php echo $url ?>?opc=Imprimir&valor=<?php echo $cod_fac ?>&pre=<?php echo $row['prefijo'] ?>&tipo_imp=post&pag=<?php echo $pag ?>"><i class="uk-icon-print"></i>Imprimir</a></li>

<?php if($confirma!="Recibido y Facturado" && $confirma!="Recibido" && ($rolLv==$Adminlvl || val_secc($id_Usu,"compras_add")) ){ ?>
<li><a href="<?php echo "$url_mod?num_fac_venta=$cod_fac&pre=$pre&origen=$codOrig" ?>"><i class="uk-icon-pencil"></i> Modificar</a></li>
<?php } ?>


<li class="uk-nav-divider"></li>

<?php 
if($rolLv==$Adminlvl || val_secc($id_Usu,"fac_anula")){
	
	if($MODULES["CARROS_RUTAS"]==1)$func="anular_fac_remi2('$cod_fac','$pre','$codSuc');";
	else $func="anular_fac_remi('$cod_fac','$pre','$codOrig');";
?>
<li><a href="#" onMouseUp="<?php echo $func; ?>"><i class="uk-icon-remove"></i> Anular</a></li>
<li><a href="#" onMouseUp="<?php echo "recover_fac_remi('$cod_fac','$pre')"; ?>"><i class="uk-icon-reply"></i> Restaurar</a></li>
<!--

-->
<?php 
}
?>
</ul>
  </div>
      </div>
        </div>
</td>             
<td><?php echo "$pre $cod_fac"; ?></td>
<td style=""><?php echo $nom_cli ?></td>
<td style=""><?php echo sede($codOrig); ?></td> 
<td><?php echo sede($codDest); ?></td>
<td style=""><?php echo $vendedor ?></td>
<td><?php echo $tot ?></td>
<td><b><?php echo $estado ?></b></td>
<td ><b><?php echo $confirma ?></b></td>
<td style=" font-size:12px;"><?php echo $fecha ?></td>
</tr> 
         
<?php 
         } 
      ?>
 

 
         
  </tbody>        
   
</table>
<?php include("PAGINACION.php"); ?>	
</form>
</div>

<?php include_once("FOOTER.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js?<?php echo "$LAST_VER"."222222" ?>"></script>
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >
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