<?php 
/*
  1.MENU FACTURA
*/
include_once("Conexxx.php");
$hideNotMatiasApi = '';
$enviaMail = 1;
if($company_fe=='DATAICO'){
	$hideNotMatiasApi = 'visibility:hidden';
	$enviaMail = 0;
	
}
$busq="";
$val="";
$val2="";
$boton="";
$pre="";
$fechaFacs="";
$opc="";

$adminFilter="AND id_usu='$id_Usu'";
$adminFilter="";
if($rolLv==$Adminlvl || $separar_registros_por_usuarios==0 || $id_Usu=="47440064")$adminFilter="";

if(isset($_REQUEST['fecha_facs']))$fechaFacs=$_REQUEST['fecha_facs'];
//if(isset($_SESSION['fecha_facs']))$fechaFacs=$_SESSION['fecha_facs'];

if(isset($_REQUEST['opc'])){$opc=r('opc');}
if(isset($_REQUEST['busq']))$busq=r('busq');
if(isset($_REQUEST['valor']))$val=r('valor');
if(isset($_REQUEST['valor2']))$val2=r('valor2');
if(isset($_REQUEST['opc']))$boton=r('opc');
if(isset($_REQUEST['pre']))$pre=r('pre');
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
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" class=\"uk-button  uk-button-danger uk-icon-undo\">";}

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





/////////////////////////////////////////////////////////////////////// FILTRO ESTADO FAC. ///////////////////////////////////////////////////////////
$filtroB="";
$B="";
if(isset($_REQUEST['filtroB'])){
	$filtroB=$_REQUEST['filtroB'];
	$_SESSION['filtroB_ventas']=$filtroB;
	if($filtroB=="Pendientes")$B="AND anulado=''";
	else if($filtroB=="Cerradas")$B="AND anulado='CERRADA'";
	else if($filtroB=="Anuladas"){$B=" AND anulado='ANULADO'";}
	else if($filtroB=="Contado"){$B=" AND tipo_venta='Contado'";}
	else $B="";
}

if(isset($_SESSION['filtroB_ventas']))
{
	$filtroB=$_SESSION['filtroB_ventas'];
	if($filtroB=="Pendientes")$B="AND anulado=''";
	else if($filtroB=="Cerradas")$B="AND anulado='CERRADA'";
	else if($filtroB=="Anuladas"){$B=" AND anulado='ANULADO'";}
	else if($filtroB=="Contado"){$B=" AND tipo_venta='Contado'";}
	else $B="";
	
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





///////////////////////////////////////////////////////////////// FILTRO NOMBRE //////////////////////////////////////////////////////////////////////

$C="";
$nom_cli="";
$botonFiltroNom="<input class=\"uk-button uk-button-success\" type=\"submit\" name=\"opc\" value=\"Buscar Nombre\" data-inline=\"true\" data-mini=\"true\" >";

if(isset($_SESSION['nom_cli_facVen'])){$nom_cli=limpiarcampo($_SESSION['nom_cli_facVen']);$C=" AND (nom_cli='$nom_cli' OR id_cli='$nom_cli')";

$botonFiltroNom="<input class=\"uk-button uk-button-danger\" type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" data-inline=\"true\" data-mini=\"true\" >";
};

if(isset($_REQUEST['nom_cli']) && !empty($_REQUEST['nom_cli'])){$nom_cli=limpiarcampo($_REQUEST['nom_cli']); $_SESSION['nom_cli_facVen']=$nom_cli;$C=" AND (nom_cli='$nom_cli' OR id_cli='$nom_cli')";
$botonFiltroNom="<input class=\"uk-button uk-button-danger\" type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" data-inline=\"true\" data-mini=\"true\" >";
}

/*
if(isset($_REQUEST['nom_cli'])){
	
	$nom_cli=limpiarcampo($_REQUEST['nom_cli']);
	$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" data-inline=\"true\" data-mini=\"true\" >";	
}
*/

if($opc=="QUITAR NOMBRE")
{
	$botonFiltroNom="<input class=\"uk-button uk-button-success\" type=\"submit\" name=\"opc\" value=\"Buscar Nombre\" data-inline=\"true\" data-mini=\"true\" >";
	$nom_cli="";
	unset($_SESSION['nom_cli_facVen']);
	$C="";
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////






/////////////////////////////////////////////////////////////////////// FILTRO RESOL FAC. ///////////////////////////////////////////////////////////
$filtroD="";
$D="";
if(isset($_REQUEST['filtroD'])){

	$filtroD=$_REQUEST['filtroD'];
	$_SESSION['filtroD_ventas']=$filtroD;

	switch($filtroD){
		case 'FE': 
			$D=" AND (TIPDOC = '7')";
			break;
		case 'FE_SIN_ENVIAR': 
			$D=" AND (TIPDOC = '7' AND ( estado_factura_elec!=1) )";
			break;
		case 'POSDIAN_SIN_ENVIAR': 
			$D=" AND ( TIPDOC != '7' AND idFacturaDian = 0 )";
			break;
		default: 
		    $D="";
	}
}

if(isset($_SESSION['filtroD_ventas']))
{
	$filtroD=$_SESSION['filtroD_ventas'];
	
	switch($filtroD){
		case 'FE': 
			$D=" AND (TIPDOC = '7')";
			break;
		case 'FE_SIN_ENVIAR': 
			$D=" AND (TIPDOC = '7' AND ( estado_factura_elec!=1) )";
			break;
		case 'POSDIAN_SIN_ENVIAR': 
			$D=" AND ( TIPDOC != '7' AND idFacturaDian = 0 )";
			break;
		default: 
		    $D="";
	}
	
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




if($MODULES["VENTA_VEHICULOS"]!=1){
$cols="<th width=\"50\" class=\"uk-hidden-touch\">#</th>
<td width=\"40\"></td>
<th width=\"80\">No. Fac</th>
<th width=\"150\">Cliente</th>
<th width=\"100\" class=\"uk-hidden-touch\">C.C. Cliente</th>
<th width=\"80\" class=\"uk-hidden-touch\">Tipo Cli. </th>
<th width=\"120\" class=\"uk-hidden-touch\">Forma Pago</th>
<th width=\"150\" class=\"uk-hidden-touch\" >Vendedor</th>
<th >TOT</th>
<th class=\"uk-hidden-touch\" >Tarj.</th>
<th width=\"100\" class=\"uk-hidden-touch\">Estado</th>
<th width=\"100\" align=\"center\" class=\"uk-hidden-touch\">DIAN</th>
<th width=\"150\">Fecha</th>";
}

else{
	
$cols="<th width=\"50\" class=\"uk-hidden-touch\">#</th>
<td width=\"40\"></td>
<th width=\"80\">Factura</th>
<th width=\"100\" >NOTA ENTREGA</th>
<th width=\"150\">Cliente</th>


<th width=\"120\" class=\"uk-hidden-touch\">Forma Pago</th>
<th width=\"150\">Vendedor</th>
<th width=\"150\" class=\"uk-hidden-touch\">Cu. Inicial</th>
<th width=\"150\">TOT</th>
<th width=\"80\" class=\"uk-hidden-touch\">Inicial</th>
<th width=\"150\">Moto</th>
<th width=\"100\" class=\"uk-hidden-touch\">Estado</th>

<th width=\"150\">Fecha</th>";	
	}

$tabla="fac_venta";
$col_id="serial_fac";
$aliasCol="(select alias from usuarios WHERE usuarios.id_usu=fac_venta.id_cli AND alias!='' LIMIT 1)";
$columns="serial_fac,num_fac_ven,id_cli,nom_cli,fecha,tel_cli,dir,mecanico,vendedor,sub_tot,iva,descuento,tot,tot_tarjeta,placa,tipo_cli,anulado,prefijo,tipo_venta,anticipo_bono,cod_caja,DATE(fecha) as fe, DATE(fecha_anula) as fe_anula,fecha_anula,$aliasCol AS alias,entrega,marca_moto,hash,num_pagare,estado_factura_elec,snombr,apelli, DATEDIFF(CURRENT_DATE(),DATE(fecha)) AS limAnula, estado,TIPDOC,mail,idFacturaDian";
$url="ventas.php";
$url_dialog="dialog_invIni.php";
$url_mod="mod_fac_ven.php";
$url_new="fac_venta.php";
if(val_secc($id_Usu,"fac_crea") || $rolLv==$Adminlvl)$url_new="fac_venta.php";
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


/*         Formulario dinamico       */
$SelOpt1=quitaSaltosLinea("<option value=\'Contado\'>Contado</option><option value=\'Cheque\'>Cheque</option>
		  <option value=\'Credito\'>Credito</option>
		  <option value=\'Tarjeta Credito\'>Tarjeta Credito</option>
		  <option value=\'Tarjeta Debito\'>Tarjeta Debito</option>
		  <option value=\'Transferencia Bancaria\'>Transferencia Bancaria</option>");

$selecOPTColumns=array("tipo_venta"=>$SelOpt1);



 
$num_fac=$val;
$sql = "SELECT  $columns FROM fac_venta  WHERE  nit=$codSuc $adminFilter $A $B $C $D ORDER BY $order DESC  LIMIT $offset, $limit"; 

$sqlPag=$sql;
//echo "$sql";


if($boton=='del_aff'&& !empty($val)){
	

	$linkPDO->exec("DELETE FROM $tabla WHERE $col_id=$val AND nit_scs=$codSuc");
	header("location: $url");
	}
///////////////////////////////////////// MOD FAC VEN //////////////////////////////////////////////////////////////////////
$hash=r("hash");
if($boton=='mod'&& !empty($val) && !empty($pre) && ($val2!="CERRADA" || $MODULES["VENTA_VEHICULOS"]==1 || $MODULES["modulo_planes_internet"]==1 || $fac_servicios_mensuales==1) ){
//if($boton=='mod'&& !empty($val) && !empty($pre)  ){
	
	$_SESSION['num_fac_venta']=$val;
	$_SESSION['pre']=$pre;
	
	header("location: $url_mod?num_fac_venta=$val&pre=$pre&hash=$hash");
	}
 if($val2=="ANULADO")eco_alert("Factura ANULADA, no se permiten cambios"); 
 if($val2=="CERRADA")eco_alert("Factura CERRADA, no se permiten cambios"); 
 

//---------------------------------------------------------------------------------------------------------------------------
 
////////////////////////////////////////////////////////////////// PAGINACION PARTE 2//////////////////////////////////////////////////// 
$sqlTotal = "SELECT COUNT(*) as total FROM fac_venta WHERE nit=$codSuc $A $adminFilter $B $C $D"; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM fac_venta WHERE nit=$codSuc $adminFilter AND (num_fac_ven LIKE '$busq' OR num_pagare LIKE '$busq' OR id_cli LIKE '$busq' OR placa LIKE '$busq%' OR nom_cli LIKE '$busq%' OR $aliasCol LIKE '$busq%' OR vendedor LIKE '$busq%') $A $D";



$rs=$linkPDO->query($sql_busq);

	
	}

 //echo "$sql_busq";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include_once("HEADER.php"); ?>	
<!--
<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="css/animate.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
-->
<style type="text/css">
.console {
  font-family:Courier;
 color: #CCCCCC;
  background: #000000;
  border: 3px double #CCCCCC;
  padding: 10px;
}
</style>

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

			<ul class="uk-navbar-nav uk-navbar-center" style="width:1330px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
			
			<li class="ss-navbar-center"><a href="<?php echo $url_new ?>" >
			<i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Facturar</a></li>


             <?php 
				if($MODULES["VENTA_VEHICULOS"]==1){
				if(($rolLv==$Adminlvl || val_secc($id_Usu,"crea_recibo_caja")) ){
				?>
<li class="ss-navbar-center">
	<a href="#"  onClick="print_pop('comp_ingreso_vario.php','EGRESO',600,650)">
		<i class="uk-icon-credit-card <?php echo $uikitIconSize ?>"></i>&nbsp;BONO Ventas</a></li>

<li class="ss-navbar-center"><a href="abonos_creditos.php"  onClick="">
	<i class="uk-icon-list <?php echo $uikitIconSize ?>"></i>&nbsp;Lista BONOS</a></li>
				<?php
				}
				}
				?>

					<?php 
					//refacturar_fe
					if(  $rolLv==$Adminlvl  || val_secc($id_Usu,"refacturar_fe")){
					?>

					<li class="">
					<a href="<?php  echo "fac_venta.php?FechaI=$fechaI&FechaF=$fechaF&reFacturar=1";  ?>" >
				    <i class="uk-icon-check-square-o <?php echo $uikitIconSize ?>"></i>&nbsp;FE-Masiva</a>
				    </li>
                    
					<li class="uk-parent " data-uk-dropdown="{pos:'bottom-center',mode:'click'}" aria-haspopup="true" aria-expanded="false">
            
		
            
			<a href="#" style="cursor:pointer;"><i class="uk-icon-file-text-o <?php echo $uikitIconSize ?>"></i> Enviar Facturas FE</a>

			<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" style="top: 40px; left: 0px;">
				<ul class="uk-nav uk-nav-navbar">

				<li class="" >
					<a href="#"  id="actualizaFechasFE" style="cursor: pointer;">
				    <i class="uk-icon-calendar <?php echo $uikitIconSize ?>"></i>&nbsp;Actualiza Fechas FE
				    </a>
				    </li>

					<li class="" >
					<a href="#"  id="enviarPendientes" style="cursor: pointer;">
				    <i class="uk-icon-paper-plane-o <?php echo $uikitIconSize ?>"></i>&nbsp;Envia todas FE
				    </a>
				    </li>


					 




				</ul>

			</div>
		</li>





					<?php

					}
					?>


					<?php if($MODULES["mesas_pedidos"]==1){?>

						<li><a href="MESAS.php" ><i class="uk-icon-sticky-note-o <?php echo $uikitIconSize ?>"></i>&nbsp;Pedidos</a></li>
					<?php }?>


				<!-- <li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>-->
			
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
//eco_alert("Tipo Imp: $tipoImp, Button: $boton");
$tipoDocImp=r('tipoDoc');
imp_fac($num_fac,$pre,$boton,1,"no",0,$tipoDocImp); 

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
<h1 align="center">FACTURAS DE VENTA</h1>
 
<form action="<?php echo $url ?>" method="get" name="form" class=" uk-form">

<div   style="display:inline-block;">
	<table   cellpadding="0" cellspacing="1" class="creditos_filter_table tabla-datos">
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

<div style="display:inline-block;">
	<table cellpadding="0" cellspacing="1" class="creditos_filter_table tabla-datos" >
	<thead>
	<TR bgcolor="#CCCCCC">

	<TH colspan="" align="center">Filtro Estado</TH>
    <TH colspan="" align="center">Filtro Resoluci&oacute;n FAC.</TH>
	</TR>
	</thead>
	<tr>
	<td>
	<select name="filtroB" onChange="submit()">
	<option value="TODOS" <?php if($filtroB=="TODOS")echo "selected" ?>>TODOS</option>
	<option value="Pendientes" <?php if($filtroB=="Pendientes")echo "selected" ?>>Pendientes</option>
	<option value="Cerradas" <?php if($filtroB=="Cerradas")echo "selected" ?>>Cerradas</option>
	<option value="Anuladas" <?php if($filtroB=="Anuladas")echo "selected" ?>>ANULADAS</option>
    <option value="Contado" <?php if($filtroB=="Contado")echo "selected" ?>>Contado</option>
	</select>
	</td>
    
    <td>
	<select name="filtroD" onChange="submit()">
	<option value="TODOS" <?php if($filtroD=="TODOS")echo "selected" ?>>TODOS</option>
	<option value="FE" <?php if($filtroD=="FE")echo "selected" ?>>Factura Electr&oacute;nica</option>
	<option value="FE_SIN_ENVIAR" <?php if($filtroD=="FE_SIN_ENVIAR")echo "selected" ?>>Factura Electr&oacute;nica SIN Enviar</option>
	
	<option value="POSDIAN_SIN_ENVIAR" <?php if($filtroD=="POSDIAN_SIN_ENVIAR")echo "selected" ?>>POS SIN Factura Elec.</option>
	<?php
 
	?>
	</select>
	</td>
	</tr>
	</tbody>
	</table>
</div>

 
<?php 

//echo "$sqlPag"; class="uk-hidden-touch"
include("PAGINACION.php"); ?>	
<div class="uk-overflow-containerS">
<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped tabla-datos"  id="tabQuery" > 
<thead>
<tr bgcolor="#595959" style="color:#FFF" valign="top">     
<?php echo $cols;  ?>
</tr>
</thead>
<tbody>        
      
<?php 
//echo "$sql";

$style_no_cerradas="";
//
while ($row = $rs->fetch()) 
{ 
$ii++;
            $idFacturaDian = $row['idFacturaDian'];
		    $ID=$row["serial_fac"];
            $cod_fac = $row["num_fac_ven"]; 

			$nom_cli = "$row[nom_cli] $row[snombr] $row[apelli]";
			$dir = $row["dir"];
			$tel = $row["tel_cli"];
			$mecanico = $row["mecanico"];
			$vendedor =$row["vendedor"]; 
			$fecha = $row["fecha"];
			$tot = money($row["tot"]*1);
			$placa = $row["placa"];
			$id_cli = $row["id_cli"];
			$tipoCli=$row['tipo_cli'];
			$estado=$row['anulado'];
			$pre=$row['prefijo'];
			$formaPago=$row['tipo_venta'];
			$anti=$row['anticipo_bono'];
			$cod_caja=$row['cod_caja'];
			$alias=$row['alias'];
			$TIPDOC = $row['TIPDOC'];

			$serial_fac=$row['serial_fac'];
			
			$entregado=$row["entrega"];
			
			$marcaMoto=$row["marca_moto"];
			
			$EstadoDIAN=$row["estado_factura_elec"];
			
			$limAnulacion=$row["limAnula"];
			
			if($EstadoDIAN==1){$showDIAN='<div class="uk-text-success"><i class="uk-icon uk-icon-check-square uk-icon-large"></i></div>';}
			else if($EstadoDIAN!=1 && !empty($EstadoDIAN)){$showDIAN='<div class="uk-text-danger"><i class="uk-icon uk-icon-remove uk-icon-large"></i></div>';}
			else {$showDIAN='';}
            
			if(!empty($dataico_account_id)){
				if($idFacturaDian!=0 && $TIPDOC!=7) {
					$showDIAN='<div class="uk-text-primary "><i class="uk-icon uk-icon-check-circle uk-icon-large"></i></div>';
				}
				else if($idFacturaDian==0 && $TIPDOC!=7){
					$showDIAN='<div class=" uk-text-danger"><i class="uk-icon uk-icon-exclamation-triangle uk-icon-large"></i></div>';
				}
		    }

			
			$TOTAL_FAC=$row["tot"];
			if($TOTAL_FAC==0)$TOTAL_FAC=1;
			$per_pago=redondeo(100* ($entregado/$TOTAL_FAC) );
			
			
			$printAlias="";
			if(!empty($alias))$printAlias="(<B>$alias</B>)";
			
			$fe=$row["fe"];
			$fe_anula=$row["fe_anula"];
			if($fe_anula=="0000-00-00")$fe_anula=$fe;
			if($anti=="SI")$add_des="(ANTICIPO)";
			else $add_des="";
			
			if(empty($estado))$style_no_cerradas="color: #F00; font-weight:bold; font-size:18px;";
			else $style_no_cerradas="";
			
			
			$estadoCartera=$row["estado"];
			$mailCliente =preg_replace("/\s+/", " ", $row['mail']);
			$numNota=$row["num_pagare"];
			$hashFac=$row["hash"];
			$functMod="";
			$functMod="";
			$url_modificar="$url?opc=mod&valor=$cod_fac&pre=$row[prefijo]&valor2=$estado";
			$url_print="$url?opc=Imprimir&valor=$cod_fac&pre=$row[prefijo]&tipo_imp=post&pag=$pag&valor2=$row[serial_fac]&hash=$hashFac&tipoDoc=$TIPDOC";
			$url_print_pos="$url?opc=Imprimir&valor=$cod_fac&pre=$row[prefijo]&tipo_imp=post&pag=$pag&valor2=$row[serial_fac]&hash=$hashFac&tipoDoc=$TIPDOC&formatoImp=POS";

			if($MODULES["CUENTAS_BANCOS"]==1 && ($estado=="ANULADO" && ($formaPago!="Contado"&& $formaPago!="Credito"&& $formaPago!="Cheque") ))
			{
				$functMod="error_pop('Operacion ILEGAL, haga una nueva Factura.');";$url_modificar="#";
			}
			
			$functFecha=($rolLv==$Adminlvl || val_secc($id_Usu,"fac_mod") )?"mod_tab_row('tabTD07$ii','fac_venta','fecha','$fecha',' serial_fac=\'$ID\' AND nit=\'$codSuc\'','$ii','date','','');":'';
			$functEstadoDian=($rolLv==$Adminlvl)?"mod_tab_row('tabTD06$ii','fac_venta','estado_factura_elec','$EstadoDIAN',' serial_fac=\'$ID\' AND nit=\'$codSuc\'','$ii','text','','');":'';
			$functModTipoPago=(val_secc($id_Usu,"fac_mod") || $rolLv==$Adminlvl)?"mod_tab_row('tabTD04$ii','fac_venta','tipo_venta','".$formaPago."',' serial_fac=\'$ID\' AND nit=\'$codSuc\'','$ii','select','".$selecOPTColumns['tipo_venta']."','');":'';
         ?>
 
<tr  bgcolor="#FFF" style="<?php echo $style_no_cerradas ?>" > 
<th class="uk-hidden-touch"><?php echo $ii//." [$row[serial_fac]]" ?></th>
<td>

<table cellpadding="0" cellspacing="0">
<tr>
<td>

<div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
<a class="uk-button uk-button-primary" style="width:100px; font-size:16px;">
Opciones <i class="uk-icon-caret-down"></i>
</a>
<div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom" style="top: 30px; left: 0px;">
<ul class="uk-nav uk-nav-dropdown" style=" font-size:18px;">

<?php 
// MENU FACTURA
if( ($rolLv==$Adminlvl || val_secc($id_Usu,"caja_centro")) && $estado!="CERRADA" && $estado!="ANULADO"  && $estado!="DIAN" ){
	
	if($limAnulacion<$limite_anula_ventas){
?>
<li>
<a href="#" onClick="<?php echo "close_fvAlt('$cod_fac','$row[prefijo]');"; ?>" class="">
<i class="uk-icon-check-square-o   uk-icon-small"></i> Cerrar Factura
</a>
</li>
<?php 
	}// limite anula/mod
}
?>
<li><a href="<?php echo $url_print  ?>" class="" ><i class="uk-icon-print     uk-icon-small"></i> Imprimir Factura</a></li>
<li><a href="<?php echo $url_print_pos  ?>" class="" ><i class="uk-icon-print     uk-icon-small"></i> Imprimir POS</a></li>
<?php 
if( ($rolLv==$Adminlvl || val_secc($id_Usu,"fac_anula"))  /*&& $EstadoDIAN!=1*/){
?>
<li><a  class="" href="#"  onMouseUp="anular_fac_ven('<?php echo $cod_fac; ?>','<?php echo $pre; ?>');">
<i class="uk-icon-remove   uk-icon-small"></i> Anular Factura
</a></li>
<?php 
}
?>


<?php 
if(($rolLv==$Adminlvl || ( (val_secc($id_Usu,"fac_mod") || $usar_datos_motos==1)   &&  ($fe_anula==$fe ))) /*&& $EstadoDIAN!=1*/){
	
	if($limAnulacion<$limite_anula_ventas){
?>
<li><a href="<?php echo $url_modificar; ?>" class="" onClick="<?php echo $functMod; ?>">
<i class="uk-icon-remove   uk-icon-pencil uk-icon-small"></i> Modificar Fac. <?php //echo "$limAnulacion < $limite_anula_ventas";?>
</a></li>
<?php 
	}// lim anula/mod
}
?>

<li>
<a href="#" class=" " onMouseUp="location.assign('<?php echo "$url_new?nf=$cod_fac&prefijo=$pre"; ?>');" >
<i class="uk-icon-recycle     uk-icon-small"></i> Cargar Productos en Factura NUEVA
</a>
</li>

<li>
<a href="#" class=" " onMouseUp="location.assign('<?php echo "fac_remi.php?co=1&nf=$cod_fac&prefijo=$pre"; ?>');" >
<i class="uk-icon-recycle     uk-icon-small"></i> Cargar Productos en COTIZACI&Oacute;N
</a>
</li>
<li class="uk-nav-divider"></li>
<?php 
if( ($rolLv==$Adminlvl || val_secc($id_Usu,"fac_lista")) && $EstadoDIAN!=1 && $TIPDOC=='7'){
?>
<li>
<a href="#"  onMouseUp="SEND_facElec(<?php echo "'$cod_fac','$pre','$hashFac','$codSuc','$serial_fac' ,'$mailCliente','$id_cli' "; ?>);" class="">
<i class="uk-icon-send     uk-icon-small"></i> Enviar Factura DIAN
</a>
 </li>



<?php 
}
if( ($rolLv==$Adminlvl || val_secc($id_Usu,"fac_lista")) && ($EstadoDIAN!=1 && !empty($EstadoDIAN)) && $TIPDOC=='7'){
?>
<li>
<a href="#"  onMouseUp="RESEND_facElec(<?php echo "'$cod_fac','$pre','$hashFac','$codSuc','$serial_fac' ,'$mailCliente','$id_cli' "; ?>);" class="">
<i class="uk-icon-send     uk-icon-small"></i> Reenviar Factura DIAN
</a>
</li>

<?php 
}
if( ($rolLv==$Adminlvl || val_secc($id_Usu,"fac_lista"))  && $TIPDOC=='7' && $EstadoDIAN==1){
?> 
 <li>
<a href="#"  onMouseUp="RESEND_mail(<?php echo "'$cod_fac','$pre','$hashFac','$codSuc','$serial_fac' ,'$mailCliente','$id_cli' "; ?>);" class="">
<i class="uk-icon-envelope     uk-icon-small"></i> Reenviar Correo
</a>
</li>
 
 
<li style="<?php echo $hideNotMatiasApi; ?>">
<a href="ajax/FE/creaXML.php<?php echo "?serial_fac=$serial_fac&num_fac=$cod_fac&prefijo=$pre"; ?>"  onMouseUp="" class="">
<i class="uk-icon-file-code-o   uk-icon-small"></i> Descarga XML
</a>
</li>  
  
<li style="<?php echo $hideNotMatiasApi; ?>">
<a href="#"  onMouseUp="verificaFacElec('<?php echo $cod_fac; ?>','<?php echo $pre; ?>','<?php echo $hashFac; ?>','<?php echo $codSuc; ?>','<?php echo $serial_fac; ?>');" class="">
<i class="uk-icon-check   uk-icon-small"></i> Verifica Factura
</a>
</li>  
<?php }?> 
</ul>
</div>
</div>


</td>

</tr>
 
<?php 
if($mod_resolucion==1 && $EstadoDIAN!=1){
?>
<tr>
<td colspan="3" class="uk-hidden-touch">

<select onChange="<?php echo "cambia_resol('$cod_fac','$row[prefijo]',$(this).val(),'$row[serial_fac]');"; ?>" id="CAMBIA_RESOL<?php echo $ii;?>" name="CAMBIA_RESOL<?php echo $ii;?>">
<option value="" selected>RESOL.</option>
<option value="POS">POS</option>
<option value="PAPEL">Electrónica</option>

</select>
</td>

</tr>
<?php 
}
?>
</table>

</td> 
<?php
if($MODULES["VENTA_VEHICULOS"]==1){
?>            
<td><?php echo "$cod_fac $pre"; ?></td>
<td style="" class="uk-hidden-touch"><?php echo $numNota ?></td> 
<td style=""><?php echo "$nom_cli $printAlias" ?></td>

<td class="<?php if($MODULES["VENTA_VEHICULOS"]==1){echo "uk-hidden";}?> uk-hidden-touch"><?php echo $tipoCli ?></td>
<td class="uk-hidden-touch" id="tabTD04<?php echo $ii ?>"  onDblClick="<?php echo $functModTipoPago; ?>"><?php echo $formaPago." $add_des" ?></td>
<td style="" class="uk-hidden-touch"><?php echo $vendedor ?></td>
<td class="<?php if($MODULES["VENTA_VEHICULOS"]!=1){echo "uk-hidden";}?> uk-hidden-touch"><?php echo money2($entregado); ?></td>

<td><?php echo $tot ?></td>
<td class="<?php if($MODULES["VENTA_VEHICULOS"]!=1){echo "uk-hidden";}?> uk-hidden-touch"><?php echo $per_pago; ?> %</td>
<td class="<?php if($MODULES["VENTA_VEHICULOS"]!=1){echo "uk-hidden";}?> uk-hidden-touch"><?php echo $marcaMoto; ?></td>
<td class="<?php if($MODULES["VENTA_VEHICULOS"]==1){echo "uk-hidden";}?> uk-hidden-touch"><?php echo money2($row["tot_tarjeta"]); ?></td>
<td  class="uk-hidden-touch" <?php if($estado=="ANULADO"){echo "style=\"background-color:red; color:white; font-weigh:bold;\"";} ?>><b><?php echo $estado ?></b></td>
<td class="uk-hidden-touch <?php if($MODULES["VENTA_VEHICULOS"]==1){echo "uk-hidden";}?>"><b><?php echo $showDIAN ?></b></td>
<td style=" " id="tabTD07<?php echo $ii ?>"  onDblClick="<?php echo $functFecha; ?>"><?php echo $fecha ?></td>

<?php 
}else{
?>

<td><?php echo "$cod_fac $pre"; ?></td>
<td style=""><?php echo "$nom_cli $printAlias" ?></td>
<td style="" class="uk-hidden-touch"><?php echo $id_cli ?></td> 
<td class="uk-hidden-touch <?php if($MODULES["VENTA_VEHICULOS"]==1){echo "uk-hidden";}?>"><?php echo $tipoCli ?></td>
<td class="uk-hidden-touch" id="tabTD04<?php echo $ii ?>"  onDblClick="<?php echo $functModTipoPago; ?>"><?php echo $formaPago." $add_des" ?></td>
<td style="" class="uk-hidden-touch"><?php echo $vendedor ?></td>
<td class="uk-hidden-touch <?php if($MODULES["VENTA_VEHICULOS"]!=1){echo "uk-hidden";}?>"><?php echo money2($entregado); ?></td>

<td><?php echo $tot ?></td>
<td class="uk-hidden-touch <?php if($MODULES["VENTA_VEHICULOS"]!=1){echo "uk-hidden";}?>"><?php echo $per_pago; ?> %</td>
<td class=" uk-hidden-touch <?php if($MODULES["VENTA_VEHICULOS"]!=1){echo "uk-hidden";}?>"><?php echo $marcaMoto; ?></td>
<td class="uk-hidden-touch <?php if($MODULES["VENTA_VEHICULOS"]==1){echo "uk-hidden";}?>"><?php echo money2($row["tot_tarjeta"]); ?></td>
<td class="uk-hidden-touch" <?php if($estado=="ANULADO"){echo "style=\"background-color:red; color:white; font-weigh:bold;\"";} ?>><b><?php echo $estado ?></b></td>
<td class="uk-hidden-touch <?php if($MODULES["VENTA_VEHICULOS"]==1){echo "uk-hidden";}?>" id="tabTD06<?php echo $ii ?>" onDblClick="<?php echo $functEstadoDian; ?>">
<b><?php echo $showDIAN ?></b></td>

<td style=" " id="tabTD07<?php echo $ii ?>"  onDblClick="<?php echo $functFecha; ?>"><?php echo $fecha ?></td>


<?php }?>
</tr> 
         
<?php 
         } 
      ?>
 

 
         
  </tbody>        
   
</table>
</div>
<?php include("PAGINACION.php"); ?>	

</form>
</div>




<?php include_once("FOOTER.php"); 
include('alertaPagoClienteSS.php');
?>

<?php include_once("autoCompletePack.php"); ?>
<!--
<script src="JS/bootstrap.min.js"></script>
<script src="JS/plugins/dataTables/datatables.min.js"></script>
<script src="JS/plugins/dataTables/numericCommaPlugIn.js"></script>
-->
<div class="uk-modal" id="modalAjax">
    <div class="uk-modal-dialog">
        Verificando petici&oacute;n
    </div>
</div>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js?<?php echo "$LAST_VER"."22222" ?>"></script>
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" >

var listaPendientes;

$('#actualizaFechasFE').bind('click', function(){
	if(confirm('Actualizar Fechas facturas ultimos 3 dias?')) {
    var data='';
	var modal = UIkit.modal.blockUI("Actualizando Fechas Facturas DIAN, por favor espere...");
	ajax_x('ajax/ventas/FEactualizaFechasPendientes.php',data,function(resp){
	
		var respuesta = resp*1;
		if(respuesta) {
			modal.hide();
			warrn_pop('Facturas Actualizadas');
			waitAndReload();
		}
	
	});
}
});

$('#enviarPendientes').bind('click', function(){
	if(confirm('Enviar facturas ultimos 3 dias?')) {
    var data='';
	var modal = UIkit.modal.blockUI('Enviando Facturas DIAN, por favor espere...<br><div class="uk-progress uk-progress-success"><div id="progress_bar" class="uk-progress-bar" style="width: 0%;">0%</div></div>');
	$.ajax({
		url:'ajax/ventas/getFacturasPendientes.php',
		data:{data},
		type:'POST',
		dataType:'JSON',
		success:function(response){
			if(Object.keys(response).length) {
				listaPendientes = response;
				console.log(listaPendientes);
				
				enviarPendientes();

			}
			//modal.hide();
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');modal.hide();}
		
		});
}
});


var indiceGlobal=0;
function enviarPendientes(){
	let totalLista = Object.keys(listaPendientes).length;
	console.log('indice: '+indiceGlobal+'/'+Object.keys(listaPendientes).length);
    if(indiceGlobal<totalLista){
		$.ajax({
			url:'ajax/FE/enviaFacElec.php',
			data:{num_fac:listaPendientes[indiceGlobal].num_fac_ven,
				prefijo:listaPendientes[indiceGlobal].prefijo,
				hash:'',
				codSuc:listaPendientes[indiceGlobal].codSuc,
				serial_fac:listaPendientes[indiceGlobal].serial_fac},
			type:'POST',
			dataType:'JSON',
			success:function(response){
				var porcentaje=(indiceGlobal/totalLista)*100;
				console.log(response);
				if(response.success==true && response.message!='Documento con errores en campos mandatorios.'){
					
				// simplePopUp(response.message);
					//waitAndReload();
					porcentaje=redondeox(porcentaje,0);
					$('#progress_bar').css("width", porcentaje+"%").html(porcentaje+"%");
					indiceGlobal++;
					enviarPendientes();
					
					
				}else {
					//modal.hide();
					
					enviarPendientes();
					indiceGlobal++;
					warrn_pop('Error: '+response.message+', '+response.Error);
					var $modalPopUp = $.UIkit.modal("#modal");
					$modalPopUp.on({

						'show.uk.modal': function(){
						
						},
					
						'hide.uk.modal': function(){
							
							waitAndReload();
						}
					});
					
				}
				
				},
			error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ'+xhr+' status:'+status);modal.hide();}
			
			});
	}
}


function close_fvAlt(nf,pre)
{
	if(confirm('Desea Cerrar esta  Factura de Venta?NO SE PERMITIRAN MODIFICACIONES'))
	{
	$.ajax({
		url:'ajax/cerrar_fv.php',
		data:{num_fac_venta:nf,pre:pre},
		type:'POST',
		dataType:'text',
		success:function(text){
			
			if(text!=0)
			{

				location.assign('ventas.php?opc=Imprimir&valor='+nf+'&pre='+pre+'&tipo_imp=post&pag=1');
				
				}
				else if(text==0){
					
					location.assign('ventas.php?opc=Imprimir&valor='+nf+'&pre='+pre+'&tipo_imp=post&pag=1');
					}
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});
	}

};

$(document).ready(function() {
	
call_autocomplete('ID',$('#nom_cli'),'ajax/busq_cli.php');


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