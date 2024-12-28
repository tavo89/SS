<?php 
require_once("Conexxx.php");
$adminFilter="AND cod_caja='$codCaja'";
$adminFilter="";
if($MODULES["UN_BAN_CLI2"]==1){auto_unban_cli2();}
if($MODULES["AUTO_BAN_CLI"]==1){auto_ban_cli();}


if($rolLv==$Adminlvl)$adminFilter="";

/////////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_cre";
$PAG_fechaF="fechaF_cre";
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button uk-button-success\" >";
$A="";
$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}

if(isset($_REQUEST['fechaI'])){$fechaI=limpiarcampo($_REQUEST['fechaI']); $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=limpiarcampo($_REQUEST['fechaF']);$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" class=\"uk-button uk-button-danger\">";}

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


///////////////////////////////////////////////////////////////// FILTRO NOMBRE //////////////////////////////////////////////////////////////////////

$C="";
$nom_cli="";
$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"Buscar Nombre\" class=\"uk-button uk-button-success\" >";

if(isset($_SESSION['nom_cli'])){$nom_cli=limpiarcampo($_SESSION['nom_cli']);$C=" AND (nom_cli='$nom_cli' OR id_cli='$nom_cli')";

$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" class=\"uk-button uk-button-danger\" >";
};

if(isset($_REQUEST['nom_cli']) && !empty($_REQUEST['nom_cli'])){$nom_cli=limpiarcampo($_REQUEST['nom_cli']); $_SESSION['nom_cli']=$nom_cli;$C=" AND (nom_cli='$nom_cli' OR id_cli='$nom_cli')";
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

$busq="";
$val="";
$boton="";
$pre="";

if(isset($_REQUEST['pre']))$pre=$_REQUEST['pre'];

if(isset($_REQUEST['opc'])){$boton= $_REQUEST['opc'];}
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val= $_REQUEST['valor'];
$num_fac=$val;
if(isset($_REQUEST['boton']))$boton=$_REQUEST['boton'];





/////////////////////////////////////////////////////////////////////// FILTRO ESTADO FAC. ///////////////////////////////////////////////////////////
$filtroB="";
$B="";
if(isset($_REQUEST['filtroB'])){
	$filtroB=$_REQUEST['filtroB'];
	$_SESSION['filtroB']=$filtroB;
	if($filtroB=="Pendientes")$B="AND estado='PENDIENTE'";
	else if($filtroB=="Pagados")$B="AND estado='PAGADO'";
	else if($filtroB=="Morosos"){$B=" AND DATEDIFF(CURRENT_DATE(),DATE(fecha) )>$DIAS_BAN_CLI AND fecha_pago='0000-00-00 00:00:00' ";}
	else $B="";
}

if(isset($_SESSION['filtroB']))
{
	$filtroB=$_SESSION['filtroB'];
	if($filtroB=="Pendientes")$B="AND estado='PENDIENTE'";
	else if($filtroB=="Pagados")$B="AND estado='PAGADO'";
	else if($filtroB=="Morosos"){$B=" AND DATEDIFF(CURRENT_DATE(),DATE(fecha) )>$DIAS_BAN_CLI AND fecha_pago='0000-00-00 00:00:00' ";}
	else $B="";
	
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////// FILTRO TIPO USUARIOS ///////////////////////////////////////////////////////////
$filtroE="";
$E="";
if(isset($_REQUEST['filtroE'])){
	$filtroE=$_REQUEST['filtroE'];
	$_SESSION['filtroE']=$filtroE;
	if($filtroE!="TODOS")$E="AND id_cli IN (SELECT id_usu FROM usuarios WHERE tipo_usu='$filtroE')";
	else $E="";
}

if(isset($_SESSION['filtroE']))
{
	$filtroE=$_SESSION['filtroE'];
	if($filtroE!="TODOS")$E="AND id_cli IN (SELECT id_usu FROM usuarios WHERE tipo_usu='$filtroE')";
	else $E="";
	
}
$E="";
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$cols="<th width=\"90px\">#</th>
 
<th width=\"250\">No. Ficha</th>
<th width=\"250\">No. Factura</th>
<th width=\"200\">Documento Cliente</th>
<th width=\"200\">Nombre</th>
<th width=\"200\">Tel.</th>
<th width=\"200\">Ciudad</th>

<th width=\"200\">Fecha Tratamiento</th>
<th width=\"200\">Fecha Registro</th>
";


$tabla="inv_inter";
$col_id="id_pro";
$aliasCol="(select alias from usuarios WHERE usuarios.id_usu=fac_venta.id_cli AND alias!='' LIMIT 1)";
$columns="num_ficha,num_fac_ven,id_cli,nom_cli,tel_cli,ciudad,fecha_tratamiento,fecha ";
$url="lista_fichas_manicura.php";
$url_dialog="dialog_invIni.php";
$url_mod="#";
$url_new="#";
$pag="";
$limit = 20; 
$order="num_ficha";
 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
 

$sql = "SELECT  $columns FROM ficha_tecnica_manicura WHERE nit=$codSuc $adminFilter $A $C $E ORDER BY fecha DESC,num_ficha DESC LIMIT $offset, $limit"; 






if($boton=='cuotas'&& !empty($val)){
	
	$_SESSION['num_fac_cuota']=$val;
	$_SESSION['prefijo']=$pre;
	$_SESSION['pag']=$pag;
	
	header("location: cuotas_cli.php");
	}
 
if($boton=='plan'&& !empty($val)&&$_REQUEST['plan']!='Si'){
	
	$_SESSION['cod_plan']=$val;
	$_SESSION['n_fac_ven']=$val;
	$_SESSION['prefijo']=$pre;
	$_SESSION['pag']=$pag;
	header("location: Plan_amortizacion.php");
	//header("location: imp_plan_amor.php");
	};

if($boton=='impPlan'&& !empty($val)&&$_REQUEST['plan']=='Si'){
	
	$_SESSION['cod_plan']=$val;
	$_SESSION['n_fac_ven']=$val;
	$_SESSION['pag']=$pag;
	$_SESSION['prefijo']=$pre;
	
	header("location: imp_plan_amor.php");
	};



 
 
$sqlTotal = "SELECT COUNT(*) as total FROM ficha_tecnica_manicura WHERE nit='$codSuc' $adminFilter $A $C $E"; 
$rs = $linkPDO->query($sql ) ; 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 




if(( isset($busq) && !empty($busq))){

$sql_busq="SELECT $columns FROM ficha_tecnica_manicura  WHERE (id_cli='$busq'  OR nom_cli LIKE '$busq%' OR num_fac_ven='$busq' LIKE '$busq%')  AND (nit=$codSuc $adminFilter $B $A $C $E)";


 
$rs=$linkPDO->query($sql_busq );
//echo "$sql_busq";
	}
 
?>
<!DOCTYPE html>
<html  >
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




<nav class="uk-navbar"> <a class="uk-navbar-brand" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 

		<ul class="uk-navbar-nav uk-navbar-center" style="width:850px;">
                    <li class="ss-navbar-center"><a href="ficha_manicura.php"><i class="uk-icon-sticky-note-o <?php echo $uikitIconSize ?>"></i>&nbsp;Nueva Ficha</a></li>
                    <li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>
		</ul>
						   
		<!--
        
        <li><a   href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a> </li>
        
        <div class="uk-navbar-content">Some <a href="#">link</a>.</div>-->
									
				
		<div class="uk-navbar-content uk-hidden-small uk-navbar-flip">
			<form class="uk-form uk-margin-remove uk-display-inline-block">
				<div class="uk-form-icon">
						<i class="uk-icon-search"></i>
						<input type="text" name="busq" placeholder="Buscar..." class="">
				</div>
				<input type="submit" value="Buscar" name="boton" class="uk-button uk-button-primary">
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










<div id="filtro_mora" class="uk-modal">
<div class="uk-modal-dialog ">

<a class="uk-modal-close uk-close"></a>

    <h1 class="uk-text-primary uk-text-bold">Morosos a la Fecha</h1>
    <form class="uk-form uk-margin-remove uk-display-inline-block">
       <i class="uk-icon-calendar uk-icon-large"> </i><input type="date" value="" name="fecha_mora" id="fecha_mora" class="uk-form-large">
    
       <input type="submit" value="Morosos Fecha" name="boton" class="uk-button uk-button-primary" onClick="//submit();">  
       </form> 
    </div>
</div>
<?php
/*if($boton=="PAGAR"&& (($rolLv==$Adminlvl || val_secc($id_Usu,"creditos_empleados")) && $codSuc>0))
{
	$sql1="select * from fac_venta WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit='$codSuc' and estado='PAGADO'";
	$rs1=$linkPDO->query($sql1);
	
	$sql2="select * from comprobante_ingreso WHERE num_fac=$num_fac AND pre='$pre' AND cod_su='$codSuc'";
	$rs2=$linkPDO->query($sql2);
	
	if($row=$rs1->fetch())
	{
		eco_alert("FACURA $pre-$num_fac Ya fue Pagada, operacion ANULADA");
	}
	else if($row2=$rs2->fetch())
	{
		eco_alert("FACURA $pre-$num_fac Esta siendo pagada por CUOTAS, Remitase a COMPROBANTES DE INGRESO para realizar el pago");
	}
	else{
	$sql="UPDATE fac_venta SET estado='PAGADO',modifica='$nomUsu-$id_Usu',fecha_pago=CURRENT_TIMESTAMP() WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit='$codSuc' and estado!='PAGADO'";
	$linkPDO->exec($sql);
	$HTML_antes="";
	$HTML_despues="<div style='font-size:24px;'>FACTURA <span style='color:red'>No. $num_fac - $pre</span> <b>PAGADA</b></div>";
	logDB($sql,$SECCIONES[3],$OPERACIONES[2],$HTML_antes,$HTML_despues,$num_fac);
	eco_alert("FACURA $pre-$num_fac PAGADA");
	js_location($url);
	}
	
	
};
$tipoImp="";
if(isset($_REQUEST['tipo_imp']))$tipoImp=$_REQUEST['tipo_imp'];
//eco_alert("Tipo Imp: $tipoImp, Button: $boton");
if($boton=="Estados de Cuenta Creditos")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("arqueos_estados_cuenta.php","Factura No. $val", "900px","650px");
};
if($boton=="Creditos Por Cobrar")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("informe_creditos_pendientes.php","Factura No. $val", "900px","650px");
};


if($boton=="Cuentas Publico")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("estados_cuenta_publico.php","Factura No. $val", "900px","650px");
};

if($boton=="Creditos Pendientes Publico")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("estados_cuenta_publico.php?resumen=1","Factura No. $val", "900px","650px");
};

if($boton=="Morosos")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("estados_cuenta_publico.php?filtro=1","Factura No. $val", "900px","650px");
};

if($boton=="pagos")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("informe_creditos_pagos.php","Factura No. $val", "900px","650px");
};

if($boton=="Morosos Fecha" && !empty($_REQUEST["fecha_mora"]))
{
	//echo "ENTRA".$opc."<br>";
	$fechaMora=r("fecha_mora");
	popup("estados_cuenta_publico.php?fecha_mora=$fechaMora","Factura No. $val", "900px","650px");
};
imp_fac($num_fac,$pre,$boton);

if(($rolLv==$Adminlvl || val_secc($id_Usu,"creditos_publico")) && $codSuc>0){
if($opc=="new_comp")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	popup("comp_ingreso_vario.php","Comprobante de Ingreso", "600px","620px");
};
};*/
?>
<h1 align="center">FICHAS TECNICAS DE MANICURA</h1>
<br><br><br>
<!--
<div id="sb-search" class="sb-search">
						<form>
							<input class="sb-search-input" placeholder="Ingrese su b&uacute;squeda..." type="text" value="" name="busq" id="search">
							<input class="sb-search-submit" type="submit" value="Buscar" name="opc">
							<span class="sb-icon-search"></span>
						</form>
					</div>
                    -->
 

<form action="<?php echo $url ?>" method="get" name="form" class="uk-form">

<div class="uk-width-2-3">

	<table align="left" class="creditos_filter_table" style="margin-bottom:10px;">
	<TR>

		<th style="background:rgba(0,0,0,0);"></th>
		<TH colspan="5" align="center">Fecha </TH>
		<th colspan="3">Nombre</th>
	</TR>
	
	<TR>
		<td>
			<div style="position:relative;top:-15px;padding-left:10px;padding-right:10px;" class="uk-text-center">
				FILTROS GENERALES:
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
		<td ></td>
		<td><input type="text" name="nom_cli" value="<?php echo $nom_cli ?>"  placeholder="Nombre Cliente" id="nom_cli"></td>
		<td><?php echo $botonFiltroNom ?></td>

	</TR>
	</table>
</DIV>


<div >
	
	<!-- <table class="creditos_filter_table" width=""  cellpadding="0" cellspacing="1" align="center" style="padding:10px 10px 9px 10px;position:relative;top:5px;">
		<TR >

			<TH colspan="" align="center">Filtro Estado Credito</TH><th>Tipo Cliente</th>
		</TR>
		
		<tr>
			<td>
				<select name="filtroB" onChange="submit()">
					<option value="TODOS" <?php if($filtroB=="TODOS")echo "selected" ?>>TODOS</option>
					<option value="Pendientes" <?php if($filtroB=="Pendientes")echo "selected" ?>>Pendientes</option>
					<option value="Pagados" <?php if($filtroB=="Pagados")echo "selected" ?>>Pagados</option>
					<option value="Morosos" <?php if($filtroB=="Morosos")echo "selected" ?>>MOROSOS</option>
				</select>
			</td>
			<td>
				<select name="filtroE" onChange="submit()">
					<?php
						$sql3="SELECT tipo_usu FROM usuarios GROUP BY tipo_usu";
						$rs3=$linkPDO->query($sql3);
						$selected="";
						while($row3=$rs3->fetch()){
						if($filtroE==$row3['tipo_usu'])$selected=" selected ";
						else $selected="";
						echo "<option value=\"$row3[tipo_usu]\" $selected>$row3[tipo_usu]</option>";	
							
						}
					?>
					</select>
			</td>
		</tr>
	</table> -->
	
</div>


<!--
<div class="uk-alert uk-alert-danger" data-uk-alert>Este Vagabundo no puede sacar mas Creditos!!!!<a href="#" class="uk-alert-close uk-close"></a></div>
-->

<?php require("PAGINACION.php"); ?>

<table border="0" align="center"  class="uk-table uk-table-hover uk-table-striped" > 
 <thead>
      <tr valign="top" class="uk-block uk-block-secondary uk-contrast"> 
      
<?php echo $cols;   ?>

       </tr>
        
   </thead>
   <tbody>       
      
<?php 
$bgColor="#FFF";
while ($row = $rs->fetch()){
$ii++;
		    
            $id_cli = $row["id_cli"]; 
            $nom = $row["nom_cli"]; 
			$tel = $row["tel_cli"];
			$ciudad =$row["ciudad"];
			$num_fac = $row["num_fac_ven"];
                        $num_ficha = $row["num_ficha"];
                        $fecha = $row["fecha"];
			$fechaTratamiento = $row["fecha_tratamiento"];
			
         ?>
 
<tr  bgcolor="<?php echo $bgColor ?>">
<th><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<?php
if(($rolLv==$Adminlvl || val_secc($id_Usu,"creditos_publico")) && $codSuc>0){
?>
<td>
<a href="editar_ficha_manicura.php?ficha=<?php echo $num_ficha ?>&pag=<?php echo $pag ?>" class="uk-icon-pencil uk-icon-button uk-icon-hover uk-icon-small">
<!--
<img src="Imagenes/my_invoices.png" width="26" height="26" title="Ver CUOTAS">
-->
</a>
</td>

<?php

}
?>
<td>
  
<a onclick="window.open('imp_ficha_manicura.php?ficha=<?php echo $num_ficha; ?>','Ficha No. <?php echo $num_ficha; ?>','width=1200px,height=600px,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');" class="uk-icon-print uk-icon-button uk-icon-hover uk-icon-small"> 
 
</a>
</td>
</tr>
<?php if($MODULES["PLAN_AMOR"]==1){?>
<tr>
<td><a href="#" onClick="print_pop('<?php echo "plan_amortizacion.php?n_fac_ven=$num_fac&prefijo=$pre"; ?>','Kardex',850,650);"><i class="uk-icon-calculator uk-icon-button uk-icon-hover uk-icon-small"></i></a></td>
</tr>
<?php }?>
</table>


</td>     
<td><?php echo $num_ficha; ?></td>
<td><?php echo $num_fac; ?></td>
<td><?php echo $id_cli; ?></td>
<td><?php echo $nom; ?></td> 
<td><?php echo $tel; ?></td>
<td><?php echo $ciudad; ?></td>
<td><?php echo $fechaTratamiento; ?></td>
<td><?php echo $fecha; ?></td>
</tr> 
         
<?php 
         }
      ?>

   </tbody>       
   
</table>

</form>
<?php 

if(isset($_REQUEST['plan'])&&$_REQUEST['plan']=='Si'&& !empty($val))eco_alert("La Factura #$val requiere un PLan de Amortizacion");
//echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>

<?php require("PAGINACION.php"); ?>	
<?php require_once("FOOTER.php"); ?>
<?php require_once("autoCompletePack.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5">
function ban_list_warn()
{
	var data='';
	ajax_b('ajax/WARNINGS/list_near_ban.php',data,function(resp){
		
		var msg1='<div class="uk-alert-close">MIRAR DE INMEDIATO!!!</div>',msg2='Lista Clientes a punto de entrar en MORA',msg3=resp;
		
		if(resp!=0)open_pop(msg1,msg2,msg3)
		
		});
	
};
function disable_ban_list_warn()
{
	var data='';
	ajax_b('ajax/WARNINGS/disable_ban_list.php',data,function(resp){
		hide_pop('#modal');
	});
};

//$(document).bind("pagebeforeshow", function() {
$(document).ready( function() {
	ban_list_warn();
	call_autocomplete('ID',$('#nom_cli'),'ajax/busq_cli.php');
	//$("#cli");
	//alert('load!');
	
	
});


</script>
</div>
</div><!--fin pag 1-->

</body>
</html>