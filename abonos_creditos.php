<?php 
include("Conexxx.php");
$UserFilter="AND fecha>='$fechaCreaUsu'";
$UserFilter='';
if($rolLv==$Adminlvl || $separar_registros_por_usuarios==0)$UserFilter="";
/////////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_comp";
$PAG_fechaF="fechaF_comp";
$botonFiltro="<input class=\"uk-button uk-button-success\" type=\"submit\" name=\"opc\" value=\"Filtrar\"  >";
$A="";
$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}

if(isset($_REQUEST['fechaI'])){$fechaI=limpiarcampo($_REQUEST['fechaI']); $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=limpiarcampo($_REQUEST['fechaF']);$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input class=\"uk-button uk-button-danger\" type=\"submit\" name=\"opc\" value=\"QUITAR\" ";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}




if($opc=="QUITAR")
{
	$botonFiltro="<input class=\"uk-button uk-button-success\" type=\"submit\" name=\"opc\" value=\"Filtrar\"  >";
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
$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"Buscar Nombre\" class=\"uk-button uk-button-success\">";

if(isset($_SESSION['nom_cli'])){$nom_cli=limpiarcampo($_SESSION['nom_cli']);$C=" AND ( (select CONCAT(nombre,' ',snombr, ' ',apelli) as nombre from usuarios WHERE usuarios.id_usu=comprobante_ingreso.id_cli LIMIT 1)='$nom_cli'   OR id_cli='$nom_cli' )";

$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" class=\"uk-button uk-button-danger\" >";
};

if(isset($_REQUEST['nom_cli']) && !empty($_REQUEST['nom_cli'])){$nom_cli=limpiarcampo($_REQUEST['nom_cli']); $_SESSION['nom_cli']=$nom_cli;$C=" AND ( (select nombre from usuarios WHERE usuarios.id_usu=comprobante_ingreso.id_cli LIMIT 1)='$nom_cli'   OR id_cli='$nom_cli')";
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
	unset($_SESSION['nom_cli']);
	$C="";
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



$opc="";
$busq="";
$val="";
$boton="";
$tot_cuotas=0;
$val_cre=0;
$totCre=0;
$pre="";
if(isset($_GET['opc'])){$opc=$_REQUEST['opc'];}
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val=$_REQUEST['valor'];
if(isset($_REQUEST['opc']))$boton= $_REQUEST['opc'];
if(isset($_REQUEST['pre']))$pre= $_REQUEST['pre'];



/////////////////////////////////////////////////////// CONSULTAS ADICIONALES //////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(tot) tot FROM fac_venta WHERE nit=$codSuc AND tipo_venta='Credito'  AND ".VALIDACION_VENTA_VALIDA." $A";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){$totCre=$row['tot'];}

$sql="SELECT SUM(valor) tot FROM comprobante_ingreso WHERE cod_su=$codSuc AND anulado!='ANULADO' $A ";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{$tot_cuotas=$row['tot'];}

$saldo=$val_cre-$tot_cuotas;
if($saldo<0)$saldo=0;

//-----------------------------------------------------------------------------------------------------------------------------------------------------


////////////////////////////////////////////////////////////////////////////////// CABECERA TABLA ////////////////////////////////////////////////////

$cols="<th width=\"90px\">#</th>
<td></td>
<th width=\"250\">Consecutivo</th>
<th width=\"250\">Cliente</th>
<th width=\"250\">Concepto</th>
<th width=\"200\">Valor Pagado</th>
<th width=\"200\">Forma Pago &nbsp;<div class=\"uk-badge\" data-uk-tooltip=\"{pos:'bottom-left'}\" title=\"Doble CLICK sobre las celdas de esta Columna para cambiar la Forma de Pago\"> !</div></th>
<th width=\"200\">Estado</th>
<th width=\"200\">Fecha</th>

";



$tabla="comprobante_ingreso";
$col_id="id_pro";
$columns="concepto,num_com,num_fac,cod_su,fecha,fecha_cuota,valor,anulado,pre,(select CONCAT(nombre,' ',snombr, ' ',apelli) as nombre from usuarios WHERE usuarios.id_usu=comprobante_ingreso.id_cli LIMIT 1)  as nom_cli,forma_pago";
$url="abonos_creditos.php";
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="comp_ingreso.php";

//////////////////////////////////////////////////////////////////////// PAGINACION ///////////////////////////////////////////////////////////////////
$pag="";
$limit = 20; 
$order="fecha";
 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
 

$sql = "SELECT  $columns FROM comprobante_ingreso WHERE  cod_su=$codSuc $UserFilter $A $C ORDER BY num_com DESC  LIMIT $offset, $limit"; 
//echo $sql;

$rs =$linkPDO->query($sql); 





//-----------------------------------------------------------------------------------------------------------------------------------------------------



/////////////////////////////////////////////////////////////////// BUSCAR ///////////////////////////////////////////////////////////////////////////
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM comprobante_ingreso WHERE cod_su=$codSuc $UserFilter AND (  (select nombre from usuarios WHERE usuarios.id_usu=comprobante_ingreso.id_cli LIMIT 1) LIKE '$busq%' OR num_com = '$busq' OR concepto LIKE '%$busq%') $A $C order by num_com DESC";
//echo "$sql_busq";

$rs=$linkPDO->query($sql_busq);
	}
$sqlTotal = "SELECT COUNT(*) AS total FROM comprobante_ingreso WHERE  cod_su=$codSuc $A $UserFilter"; 

$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 
 
 $num_fac=0;
if(isset($_REQUEST['num_fac']))$num_fac=$_REQUEST['num_fac']; 
if($opc=="ver_fac")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['prefijo']=$pre;
	popup("imp_fac_ven.php","Factura No. $num_fac", "900px","600px");
};

if($opc=="informe_abono")
{
	popup("informe_abonos_credito.php","Factura No. $num_fac", "900px","600px");
};

if(($rolLv==$Adminlvl || val_secc($id_Usu,"creditos_publico")) && $codSuc>0){
if($opc=="new_comp")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	popup("comp_ingreso.php","Comprobante de Ingreso", "1200px","400px");
};
};
if($opc=="ver_plan")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['cod_plan']=$num_fac;
	popup("imp_plan_amor.php","Comprobante de Ingreso", "900px","600px");
};

if($opc=="imp_comp")
{
	imp_comp_ingre($val);
};
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("HEADER.php"); ?>
<script type="text/javascript">
var FPIngresosOpt='<?php echo egresoOpt($FP_ingresos);?>';

</script>
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
		<a class="uk-navbar-brand uk-visible-large" href="#"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 
			
			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-center" style="width:530px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
			
            
            
            
             <?php 
				if($MODULES["VENTA_VEHICULOS"]!=1){
					?>
				<li class="uk-parent ss-navbar-center" data-uk-dropdown="" aria-haspopup="true" aria-expanded="false">
				<a href="#"><i class="uk-icon-file-text-o <?php echo $uikitIconSize ?>"></i> INFORMES</a>
			
            
            	
				<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" style="top: 40px; left: 0px;">
					<ul class="uk-nav uk-nav-navbar">
						<li ><a href="<?php echo $url."?opc=informe_abono" ?>"><i class="uk-icon-file-text"></i>Reporte Comprobantes</a></li>

					</ul>

				</div>
				</li>

<li class="ss-navbar-center"><a href="creditos_parti.php"  ><i class="uk-icon-credit-card-alt <?php echo $uikitIconSize ?>"></i>&nbsp;CARTERA</a></li>




<?php }else {?>


<li class="ss-navbar-center"><a href="#"  onClick="print_pop('comp_ingreso_vario.php','EGRESO',600,650)"><i class="uk-icon-credit-card <?php echo $uikitIconSize ?>"></i>&nbsp;BONO Ventas</a></li>



<?php }?>
				<li><a   href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a> </li>

			</ul>
						   
			<!--<div class="uk-navbar-content">Some <a href="#">link</a>.</div>-->
										
					
			<div class="uk-navbar-content uk-hidden-small uk-navbar-flip">
				<form class="uk-form uk-margin-remove uk-display-inline-block">
					<div class="uk-form-icon">
						<i class="uk-icon-search"></i>
					<input type="text" name="busq" placeholder="Buscar..." class="">
					</div>
					<input type="submit" value="Buscar" name="opc" class="uk-button uk-button-primary">
				</form>
			</div>
			<div class="uk-navbar-content uk-navbar-flip  uk-hidden-small">
					
			<div class="uk-button-group"> 
			 
					  
						<!--<button class="uk-button uk-button-danger">Button</button>
						<a class="uk-button uk-button-danger " href="compras.php">Volver</a> 
						--> 
			</div>
			</div>
		</nav>


 
<h1 align="center"><?php if($MODULES["VENTA_VEHICULOS"]!=1){ echo "COMPROBANTES INGRESO-CARTERA CLIENTES";}else {echo "BONOS VENTAS";}?></h1>



<form action="<?php echo $url ?>" method="get" name="form" class="uk-form">
<div class="uk-width-2-3">

	<table align="left" class="creditos_filter_table tabla-datos">
	<TR>

		<th style="background:rgba(0,0,0,0);"></th>
			<TH colspan="5" align="center">Fecha </TH>
			<th colspan="4">Nombre</th>
	</TR>
	
	<TR>
		<td>
			<div style="position:relative;top:-15px;padding-left:10px;padding-right:10px;" class="uk-text-center">
				FILTROS INFORMES:
			</div>
		</td>
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
<td align="center">Filtro por Nombre:</td>
<td  ><input data-inline="true" data-mini="true" type="text" name="nom_cli" value="<?php echo $nom_cli ?>"  placeholder="Nombre Cliente" id="nom_cli"></td>
<td><?php echo $botonFiltroNom ?></td>

</TR>
</table>
</div>

<br><br><br><br><br>
<?php include("PAGINACION.php"); ?>
<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped tabla-datos" style=" ">
<thead> 
     <tr bgcolor="#595959" style="color:#FFF" valign="top"> 
      
<?php echo $cols;   ?>

       </tr>
 </thead>
 <tbody>       
          
      
<?php 

while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $num_comp =$row["num_com"];
			$concepto =$row["concepto"];
			$num_fac = $row["num_fac"];
			$fecha_comp=$row["fecha"];
			$fecha_cuotas=$row["fecha_cuota"]; 
			$valor=money($row['valor']);
			$anulado=$row["anulado"];
			$nom_cli=$row['nom_cli'];
			$pre=$row['pre'];
			$formPago=$row['forma_pago'];
			 
			if($MODULES["CUENTAS_BANCOS"]==0){$functTipo="mod_tab_row('tabTD04$ii','comprobante_ingreso','forma_pago','$formPago',' num_com=\'$num_comp\' AND cod_su=\'$codSuc\' ','$ii','select',FPIngresosOpt,'');";}
			else {$functTipo="error_pop('NO SE PERMITE ESTA ACCION, ANULE Y HAGA UN NUEVO COMPROBANTE');";}
         ?>
 
<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<a href="<?php echo $url ?>?opc=imp_comp&valor=<?php echo $num_comp ?>&num_fac=<?php echo $num_fac ?>&pre=<?php echo $pre ?>&pag=<?php echo $pag ?>" class="uk-icon-print uk-icon-button uk-icon-hover uk-icon-small">

</a>
</td>
<?php 
if($rolLv==$Adminlvl || val_secc($id_Usu,"anular_comp_ingreso")){
?>
<td>
<a href="#" onMouseUp="anular_comp('<?php echo $num_comp; ?>');" class="uk-icon-remove uk-icon-button uk-icon-hover uk-icon-small">

</a>
</td>
<?php 
}
?>
</tr>
</table>


</td>             
<td><?php echo $num_comp; ?></td>
<td><?php echo $nom_cli; ?></td>
<td ><div  class="uk-scrollable-box"><?php echo $concepto; ?></div></td>
<td><?php echo $valor; ?></td>
<td id="tabTD04<?php echo $ii ?>" onDblClick="<?php echo $functTipo; ?>"><?php echo $formPago; ?></td>
<td <?php if($anulado=="ANULADO"){echo "style=\"background-color:red; color:white; font-weigh:bold;\"";} ?>><?php echo $anulado; ?></td>
<td><?php echo $fecha_comp; ?></td> 
<!--<td><?php //echo $fecha_cuotas; ?></td>-->

</tr> 
         
<?php 
         } 
      ?>
 
 </tbody>        
   
</table>

</form>
<?php include("PAGINACION.php"); ?>	
<?php include_once("FOOTER.php"); 
include('alertaPagoClienteSS.php');?>
<?php include_once("autoCompletePack.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5">
function anular_comp(num_comp)
{
	if(!esVacio(num_comp)){
	if(confirm('Desea ANULAR Comprobante Ingreso No.'+num_comp)){
	 $.ajax({
		url:'ajax/anula_comp_ingreso.php',
		data:{num_comp:$.trim(num_comp)},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		var r=text.split('|');
		
		if(resp==0)simplePopUp('Este Comprobante YA esta Anulado!');
		else if(resp!=-2&&resp!=-1&&resp!=-4)
		{
			simplePopUp('Comprobante No.'+num_comp+' ANULADO');
			waitAndReload();
			
		}
		else if(resp==-1){simplePopUp('Este Comprobante supera el limite de tiempo permitido(<?php echo $dias_anula_comps?> dia) para modificaciones, accion cancelada.... ');}
		else if(resp==-4)simplePopUp('Este comprobante ya fue COBRADO');
		else simplePopUp('Comprobante No.'+num_comp+' NO encontrado');
		
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
	 });
	 
	}// if confirma
	
	}// if vacios
else {simplePopUp('Complete los espacios! No. Factura y PREFIJO(MTRH,RH,RAC,etc.)')}
	};


$(document).ready( function() {
	
	call_autocomplete('ID',$('#nom_cli'),'ajax/busq_cli.php');
	
	
});
</script>
</body>
</html>