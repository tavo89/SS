<?php 
include_once("Conexxx.php");
$busq="";
$val="";
$val2="";
$boton="";
$pre="";
$fechaFacs="";
$opc="";
$adminFilter="AND cod_caja='$codCaja'";
$adminFilter="";

if($rolLv==$Adminlvl)$adminFilter="";

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
$PAG_fechaI="fechaI_remi";
$PAG_fechaF="fechaF_remi";
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
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button uk-button-success\">";
	$fechaI="";
	$fechaF="";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	$A="";
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------



///////////////////////////////////////////////////////////////// FILTRO NOMBRE //////////////////////////////////////////////////////////////////////

$C="";
$nom_cli="";
$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"Buscar Nombre\" class=\"uk-button uk-button-success\" >";

if(isset($_SESSION['nom_cli_remi'])){$nom_cli=limpiarcampo($_SESSION['nom_cli_remi']);$C=" AND (nom_cli='$nom_cli' OR id_cli='$nom_cli')";

$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" class=\"uk-button uk-button-danger\" >";
};

if(isset($_REQUEST['nom_cli']) && !empty($_REQUEST['nom_cli'])){$nom_cli=limpiarcampo($_REQUEST['nom_cli']); $_SESSION['nom_cli_remi']=$nom_cli;$C=" AND (nom_cli='$nom_cli' OR id_cli='$nom_cli')";
$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" class=\"uk-button uk-button-danger\" >";
}

/*
if(isset($_REQUEST['nom_cli'])){
	
	$nom_cli=limpiarcampo($_REQUEST['nom_cli']);
	$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" data-inline=\"true\" data-mini=\"true\" >";	
}
*/

if($opc=="QUITAR NOMBRE")
{
	$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"Buscar Nombre\" class=\"uk-button uk-button-success\" >";
	$nom_cli="";
	unset($_SESSION['nom_cli_remi']);
	$C="";
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$cols="<th width=\"50\">#</th>
<td width=\"40\"></td>
<th width=\"80\">No. Fac</th>
<th width=\"150\">Cliente</th>
<th width=\"100\">C.C. Cliente</th>
<th width=\"80\">Tipo Cli. </th>
<th width=\"120\">Forma Pago</th>
<th width=\"150\">Vendedor</th>
<th >TOT</th>
<th width=\"100\">Nota</th>
<th width=\"100\">Estado</th>
<th width=\"100\" align=\"center\">Caja</th>
<th width=\"150\">Fecha</th>";


$tabla="fac_remi";
$col_id="id_pro";
$columns="num_fac_ven,id_cli,nom_cli,fecha,tel_cli,dir,mecanico,vendedor,sub_tot,iva,descuento,tot,placa,tipo_cli,anulado,prefijo,tipo_venta,anticipo_bono,cod_caja,sede_destino,nit,estado,nota";
$url="COTIZACIONES.php";
$url_dialog="dialog_invIni.php";
$url_mod="mod_remi.php";
$url_new="fac_remi.php";
/*if(val_secc($id_Usu,"cotizacion") || $rolLv==$Adminlvl)$url_new="fac_remi.php";
else $url_new="#";*/
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
$sql = "SELECT  $columns FROM fac_remi  WHERE  nit=$codSuc AND tipo_fac='cotizacion' $adminFilter $A $C ORDER BY $order desc  LIMIT $offset, $limit"; 

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
	
	header("location: $url_mod?co=1&num_fac_venta=$val&pre=$pre");
	}
 //if($val2=="ANULADO")eco_alert("Factura ANULADA, no se permiten cambios"); 
 if($val2=="CERRADA")eco_alert("Factura CERRADA, no se permiten cambios"); 
 

//---------------------------------------------------------------------------------------------------------------------------
 
////////////////////////////////////////////////////////////////// PAGINACION PARTE 2//////////////////////////////////////////////////// 
$sqlTotal = "SELECT COUNT(*) as total FROM fac_remi WHERE nit=$codSuc AND tipo_fac='cotizacion' $adminFilter $A $C"; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM fac_remi WHERE nit=$codSuc AND tipo_fac='cotizacion'  $adminFilter  AND (num_fac_ven LIKE '$busq%' OR id_cli LIKE '$busq%' OR placa LIKE '$busq%' OR nom_cli LIKE '$busq%' OR vendedor LIKE '$busq%' OR prefijo LIKE '$busq%' OR placa LIKE '$busq%') $A $C";



$rs=$linkPDO->query($sql_busq );

	
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

			<ul class="uk-navbar-nav uk-navbar-center" style="width:90%;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
<li class="ss-navbar-center"><a href="<?php echo "$url_new?co=1" ?>" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Cotizar</a></li>

<?php 
if(($rolLv==$Adminlvl || val_secc($id_Usu,"fac_crea")) ){
?>
<li class="ss-navbar-center"><a href="<?php  echo "fac_venta.php?fuente=cotiza&FechaI=$fechaI&FechaF=$fechaF&idCliente=".urlencode($nom_cli);  ?>" ><i class="uk-icon-money <?php echo $uikitIconSize ?>"></i>&nbsp;Facturar Fin de Mes</a></li>
<?php } ?>

<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>
		
			</ul>
			
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
if($imprimir_remi_pos==0){imp_fac($num_fac,$pre,$boton,2,"no",0,7); }
else{imp_fac_pos($num_fac,$pre,$boton,4);}

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
<h1 align="center">COTIZACIONES</h1>

<form action="<?php echo $url ?>" method="get" name="form" class=" uk-form">

<div class="grid-20">
<table   cellpadding="0" cellspacing="1" align="" class="creditos_filter_table">
	<thead>
	<TR bgcolor="#CCCCCC">
	<TH colspan="5" align="center">Fecha </TH>
	<TH colspan="2" align="center">Nombre </TH>
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


	<td><input type="text" name="nom_cli" value="<?php echo $nom_cli ?>"  placeholder="Nombre Cliente" id="nom_cli"></td>
	<td><?php echo $botonFiltroNom ?></td>
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
		    
            $cod_fac = $row["num_fac_ven"]; 
            $nom_cli = $row["nom_cli"]; 
			$dir = $row["dir"];
			$tel = $row["tel_cli"];
			$mecanico = $row["mecanico"];
			$vendedor =$row["vendedor"]; 
			$fecha = $row["fecha"];
			$tot = money($row["tot"]*1);
			$placa =$row["placa"];
			$nota =$row["nota"];
			$id_cli = $row["id_cli"];
			$tipoCli=$row['tipo_cli'];
			$estado=$row['anulado'];
			$pre=$row['prefijo'];
			$formaPago=$row['tipo_venta'];
			$anti=$row['anticipo_bono'];
			$cod_caja=$row['cod_caja'];
			$codDest=$row['sede_destino'];
			$codOrig=$row['nit'];
			if($anti=="SI")$add_des="(ANTICIPO)";
			else $add_des="";
			$bgColor="#FFFFFF";
		    
				
			if($estado=="FACTURADA")$bgColor="#FFFFFF";
			
         ?>
 
<tr  bgcolor="<?php echo "$bgColor" ?>">
<th><?php echo $ii ?></th>
<td>

<table cellpadding="0" cellspacing="0">
<tr>
<!--
<td>
<a href="<?php echo "$url?opc=mod&valor=$cod_fac&pre=$row[prefijo]&valor2=$estado"; ?>" class="uk-icon-pencil uk-icon-button uk-icon-hover uk-icon-small">

</a>
</td>
-->

<td>

<div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
<a class="uk-button uk-button-primary" style="width:100px;">Opciones <i class="uk-icon-caret-down"></i></a>
<div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom" style="top: 30px; left: 0px;">
<ul class="uk-nav uk-nav-dropdown">
<?php if($rolLv==$Adminlvl || (val_secc($id_Usu,"cotizacion") && $estado!="ANULADO")){ ?>
<li>
<a href="<?php echo "$url_mod?co=1&num_fac_venta=$cod_fac&pre=$pre&origen=$codOrig" ?>" class=" ">
<i class="uk-icon-pencil   uk-icon-small"></i> Modificar Cotizaci&oacute;n
</a>

</li>
<?php } ?>
<li>
<a href="<?php echo "$url?opc=Imprimir&valor=$cod_fac&pre=$row[prefijo]&tipo_imp=post&pag=$pag" ?>" class="">
<i class="uk-icon-print   uk-icon-small"></i> Imprimir
</a>
</li>
 
<li>
<a href="<?php echo "fac_venta.php?co=1&nf=$cod_fac&prefijo=$pre&idCliente=$id_cli" ?>" class="">
<i class="uk-icon-money   uk-icon-small"></i> Convertir a Factura
</a>
</li>

 
<li>
<a href="<?php echo "fac_remi.php?co_remi=1&nf=$cod_fac&prefijo=$pre&idCliente=$id_cli" ?>" class="">
<i class="uk-icon-book   uk-icon-small"></i> Convertir a Orden de Compra
</a>

</li>
</ul>
</div>
</div>
</td>

</tr>
</table>

</td>             
<td><?php echo "$pre $cod_fac"; ?></td>
<td style=""><?php echo $nom_cli ?></td>
<td style=""><?php echo $id_cli ?></td> 
<td><?php echo $tipoCli ?></td>
<td><?php echo $formaPago." $add_des" ?></td>
<td style=""><?php echo $vendedor ?></td>
<td><?php echo $tot ?></td>
<td style="font-size:10px" bgcolor="<?php echo "$bgColor" ?>"><?php echo "<b>$nota</b>" ?></td>
<td><b><?php echo $estado ?></b></td>
<td ><b><?php echo $cod_caja ?></b></td>
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

<?php include_once("FOOTER.php"); 
include('alertaPagoClienteSS.php');?>	
<?php include_once("autoCompletePack.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js<?php echo "?$LAST_VER"; ?>"></script>
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js<?php echo "?$LAST_VER"; ?>"></script>
<script type="text/javascript" language="javascript1.5" >
$(document).ready(function() {
$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


call_autocomplete('ID',$('#nom_cli'),'ajax/busq_cli.php');	
	});

</script>
</body>
</html>