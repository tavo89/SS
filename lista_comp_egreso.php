<?php 
require("Conexxx.php");
$UserFilter="AND fecha>='$fechaCreaUsu'";
if($rolLv==$Adminlvl || $separar_registros_por_usuarios==0)$UserFilter="";
//////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_comp_egreso";
$PAG_fechaF="fechaF_comp_egreso";
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


/////////////////////////////////////////////////////////////////////// FILTRO Pagos FAC Compras. ///////////////////////////////////////////////////////////
$filtroB="";
$D="";
if(isset($_REQUEST['filtroB'])){
	$filtroB=$_REQUEST['filtroB'];
	$_SESSION['filtroB_ventas']=$filtroB;
	if($filtroB=="Pendientes")$B="AND anulado=''";
	else if($filtroB=="Cerradas")$B="AND anulado='CERRADA'";
	else if($filtroB=="Anuladas"){$B=" AND anulado='ANULADO'";}
	else $B="";
}

if(isset($_SESSION['filtroB_ventas']))
{
	$filtroB=$_SESSION['filtroB_ventas'];
	if($filtroB=="Pagos")$D="AND serial_fac_com!=0";

	else $D="";
	
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////// FILTRO NOMBRE ///////////////////////////////////////////////////////////////////

$C="";
$nom_cli="";


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
if(isset($_REQUEST['boton']))$boton= $_REQUEST['boton'];
if(isset($_REQUEST['pre']))$pre= $_REQUEST['pre'];



/////////////////////////////////////////////////////// CONSULTAS ADICIONALES //////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(valor) tot FROM comp_egreso WHERE cod_su=$codSuc AND anulado!='ANULADO' $A";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{$tot_cuotas=$row['tot'];}

$saldo=$val_cre-$tot_cuotas;
if($saldo<0)$saldo=0;

//-----------------------------------------------------------------------------------------------------------------------------------------------------


//////////////////////////////////////////////////////////////////////////////// CABECERA TABLA ////////////////////////////////////////////////////

$cols="<th width=\"90px\">#</th>
<td></td>
<th width=\" 120\">Pago&nbsp;<div class=\"uk-badge\" data-uk-tooltip=\"{pos:'bottom-left'}\" title=\"Doble CLICK sobre las celdas de esta Columna para ACTUALIZAR los datos\"> !</div></th>
<th width=\" 100\">Tipo Gasto&nbsp;<div class=\"uk-badge\" data-uk-tooltip=\"{pos:'bottom-left'}\" title=\"Doble CLICK sobre las celdas de esta Columna para ACTUALIZAR los datos\"> !</div></th>
<th width=\"80 \">No.<div class=\"uk-badge\" data-uk-tooltip=\"{pos:'bottom-left'}\" title=\"Doble CLICK sobre las celdas de esta Columna para ACTUALIZAR los datos\"> !</div></th>
<th width=\" 150\">Descripci&oacute;n&nbsp;<div class=\"uk-badge\" data-uk-tooltip=\"{pos:'bottom-left'}\" title=\"Doble CLICK sobre las celdas de esta Columna para ACTUALIZAR los datos\"> !</div></th>
<th width=\" 100\">CC/NIT&nbsp;<div class=\"uk-badge\" data-uk-tooltip=\"{pos:'bottom-left'}\" title=\"Doble CLICK sobre las celdas de esta Columna para ACTUALIZAR los datos\"> !</div></th>
<th width=\" 120\">Beneficiario&nbsp;<div class=\"uk-badge\" data-uk-tooltip=\"{pos:'bottom-left'}\" title=\"Doble CLICK sobre las celdas de esta Columna para ACTUALIZAR los datos\"> !</div></th>
<th width=\" 100\">Valor&nbsp;<div class=\"uk-badge\" data-uk-tooltip=\"{pos:'bottom-left'}\" title=\"Doble CLICK sobre las celdas de esta Columna para ACTUALIZAR los datos\"> !</div></th>
<th width=\" \">Fac.</th>
<th width=\" \">Estado</th>
<th width=\"200\">Fecha&nbsp;<div class=\"uk-badge\" data-uk-tooltip=\"{pos:'bottom-left'}\" title=\"Doble CLICK sobre las celdas de esta Columna para ACTUALIZAR los datos\"> !</div></th>
";



$tabla="comp_egreso";
$col_id="num_comp";
$columns="*";
$url="lista_comp_egreso.php";
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="comp_egreso.php";

///////////////////////////////////////////////////////////////////// PAGINACION ///////////////////////////////////////////////////////////////////
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
 

$sql = "SELECT  $columns FROM comp_egreso WHERE  cod_su=$codSuc $UserFilter $A $C $D ORDER BY num_com DESC  LIMIT $offset, $limit"; 
//echo $sql;

$rs =$linkPDO->query($sql); 





//-----------------------------------------------------------------------------------------------------------------------------------------------------



//////////////////////////////////////////////////////////////// BUSCAR ///////////////////////////////////////////////////////////////////////////
//echo "bot: $boton ---->$busq";
if($boton=='Buscar' && isset($busq) && !empty($busq)){

//concepto LIKE '%$busq%' OR
$ND="(concepto LIKE '%$busq%' OR   beneficiario LIKE '$busq%' OR id_beneficiario='$busq' OR tipo_gasto LIKE '$busq%' OR serial_fac_com='$busq')";

$sql_busq="SELECT $columns FROM comp_egreso WHERE cod_su=$codSuc $UserFilter AND ( $ND OR (num_com = '$busq')   ) $A $C $D ";


$rs=$linkPDO->query($sql_busq);
	}
$sqlTotal = "SELECT COUNT(*) AS total FROM comp_egreso WHERE  cod_su=$codSuc $UserFilter $A $D"; 

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

if( val_secc($id_Usu,"crear_comp_egreso") || $rolLv==$Adminlvl){
if($opc=="new_comp")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	popup("$url_new","Comprobante de Ingreso", "600px","820px");
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
	
$_SESSION['id_comp_egreso']=$val;
imp_a("num_comp_ingre",$val,"imp_comp_egreso.php","Comprobante de Egreso No. $val","800px","600px");
};
//echo list_comprasOpt();
?>
<!DOCTYPE html>
<html  >
<head>
<?php require_once("HEADER.php"); ?>
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/style.css">
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/docsupport/prism.css">
<link rel="stylesheet" href="PLUG-INS/chosen_v1.4.2/chosen.css">
  
<script type="text/javascript">
var TipoEgresoOpt='<?php echo tipoEgresoOpt();?>';
var FPEgresoOpt='<?php echo egresoOpt($FP_egresos);?>';
var list_comprasOpt='<?php  echo list_comprasOpt();?>';
</script>	
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">



		<!-- Lado izquierdo del Nav -->
		<nav class="uk-navbar">

		<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-center" style="width:630px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
				<li class="ss-navbar-center"><a href="<?php echo $url ?>?opc=new_comp&valor=<?php echo 0 ?>&pag=<?php echo $pag ?>" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Crear Comprobante</a></li>
 
 

				<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>
			</ul>

			
			<!-- Lado derecho del Navbar -->
				
		<div class="uk-navbar-content uk-hidden-small uk-navbar-flip">
			<form class="uk-form uk-margin-remove uk-display-inline-block">
				<div class="uk-form-icon">
						<i class="uk-icon-search"></i>
						<input type="text" name="busq" placeholder="Buscar..." class="">
				</div>
				<input type="submit" value="Buscar" name="boton" class="uk-button uk-button-primary">
			</form>
		</div>
			
		</nav>

<h1 align="center">Comprobantes de Egreso</h1>
<br><br><br>

<form autocomplete="off" action="<?php echo $url ?>" method="get" name="form">
<div class=" tabla-datos">
<table align="left" class="creditos_filter_table tabla-datos">
<thead>
<TR bgcolor="#CCCCCC">
<TH colspan="5" align="center">Fecha </TH>
<TH colspan="2" align="center">Filtro Pagos Compras</TH>
</TR>
</thead>
<TR>
<td>Inicia:</td>
<td>
<input type="date" name="fechaI" id="date" value="<?php echo $fechaI ?>"  style="width:135px;">
</td>
<td>Termina:
</td>
<td>
<input type="date" name="fechaF" id="date" value="<?php echo $fechaF ?>" style="width:135px;">
</td>
<td><?php echo $botonFiltro ?></td>
 <td>
 
<select name="filtroB" onChange="submit()">
<option value="TODOS" <?php if($filtroB=="TODOS")echo "selected" ?>>TODOS</option>
<option value="Pagos" <?php if($filtroB=="Pagos")echo "selected" ?>>Pagos Compras</option>

</select>
</td>
</thead>
 
</table>
</div>



 
<?php //echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>
<?php require("PAGINACION.php"); ?>
<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped tabla-datos" >
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
			
			$ID=$row["id"];
		    
            $num_comp = $row["num_com"];
			$fecha_comp=$row["fecha"];
			$valor=$row['valor'];
			$anulado=$row["anulado"];
			$des=$row['concepto'];
			$beneficiario=$row['beneficiario'];
			$id_bene=$row['id_beneficiario'];
			$banco=$row['banco'];
			$num_cheque=$row['num_cheque'];
			$tipo_pago=$row['tipo_pago'];
			$tipo_gasto=$row['tipo_gasto'];
			$serialFac=$row["serial_fac_com"];
			$comText="";
			
			if(!empty($serialFac))$comText="<b>Pago factura codigo: $serialFac</b>";
			 
			
         ?>
 
<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<a href="<?php echo "$url" ?>?opc=imp_comp&valor=<?php echo $ID ?>&pag=<?php echo $pag ?>" class="uk-icon-print uk-icon-button uk-icon-hover uk-icon-small"></a>
</td>
<?php 
if($rolLv==$Adminlvl || val_secc($id_Usu,"anula_comp_egreso")){
?>
<td>
<a href="#"  onMouseUp="anular_comp('<?php echo $num_comp; ?>');" class="uk-icon-remove uk-icon-button uk-icon-hover uk-icon-small">
</a>
</td>
<?php 
}
$functNumCom="";
$functPago="";	
$functDes="";	
$functValor="";	
$functTipo="";
$functBene="";
$functIdBene="";
$functFecha="";
$functPagoComp="";


if($rolLv==$Adminlvl || val_secc($id_Usu,"mod_comp_egreso")){

//$functNumCom="mod_tab_row('tabTD08$ii','comp_egreso','num_com','$num_comp',' id=\'$ID\' AND cod_su=\'$codSuc\'','$ii','text','','');";	

$functPago="mod_tab_row('tabTD01$ii','comp_egreso','tipo_pago','$valor',' id=\'$ID\' AND cod_su=\'$codSuc\' ','$ii','select',FPEgresoOpt,'');";

$functTipo="mod_tab_row('tabTD04$ii','comp_egreso','tipo_gasto','$tipo_gasto',' id=\'$ID\' AND cod_su=\'$codSuc\' ','$ii','select',TipoEgresoOpt,'');";

$functPagoComp="mod_tab_row_x('tabTD09$ii','comp_egreso','serial_fac_com','$serialFac',' id=\'$ID\' AND cod_su=\'$codSuc\' ','$ii','select',list_comprasOpt,'',successFunction('$ID'),'$ID');";

if($MODULES["CUENTAS_BANCOS"]==0){$functValor="mod_tab_row('tabTD03$ii','comp_egreso','valor','$valor',' id=\'$ID\' AND cod_su=\'$codSuc\'','$ii','text','','');";}
else $functValor="error_pop('NO SE PERMITE ESTA ACCION, ANULE Y HAGA UN NUEVO COMPROBANTE');";

$functDes="mod_tab_row('tabTD02$ii','comp_egreso','concepto','$des',' id=\'$ID\' AND cod_su=\'$codSuc\'','$ii','text','','');";
$functBene="mod_tab_row('tabTD05$ii','comp_egreso','beneficiario','$beneficiario',' id=\'$ID\' AND cod_su=\'$codSuc\'','$ii','text','','');";
$functIdBene="mod_tab_row('tabTD06$ii','comp_egreso','id_beneficiario','$id_bene',' id=\'$ID\' AND cod_su=\'$codSuc\'','$ii','text','','');";

$functFecha="mod_tab_row('tabTD07$ii','comp_egreso','fecha','$fecha_comp',' id=\'$ID\' AND cod_su=\'$codSuc\'','$ii','date','','');";
//$functNumComp="mod_tab_row('tabTD08$ii','comp_egreso','num_com','$num_comp',' id=\'$ID\' AND cod_su=\'$codSuc\'','$ii','text','','');";
}
?>

</tr>
</table>


</td> 
<td id="tabTD01<?php echo $ii ?>" onDblClick="<?php echo $functPago; ?>"><?php echo $tipo_pago; ?></td> 
<td id="tabTD04<?php echo $ii ?>" onDblClick="<?php echo $functTipo; ?>"><?php echo $tipo_gasto; ?></td>
            
<td id="tabTD08<?php echo $ii ?>" onDblClick="<?php echo $functNumCom; ?>"><?php echo $num_comp; ?></td>
<td id="tabTD02<?php echo $ii ?>" onDblClick="<?php echo $functDes; ?>"><?php echo "$des $comText"; ?></td>
<td id="tabTD06<?php echo $ii ?>" onDblClick="<?php echo $functIdBene; ?>"><?php echo $id_bene; ?></td>
<td id="tabTD05<?php echo $ii ?>" onDblClick="<?php echo $functBene; ?>"><?php echo $beneficiario; ?></td>
<td id="tabTD03<?php echo $ii ?>" onDblClick="<?php echo $functValor; ?>"><?php echo money($valor); ?></td>
<td id="tabTD09<?php echo $ii ?>" style="border:double" onDblClick="<?php echo $functPagoComp; ?>"><?php echo $serialFac ?></td>
<td><?php echo $anulado ?></td>
<td id="tabTD07<?php echo $ii ?>"  onDblClick="<?php echo $functFecha; ?>"><?php echo $fecha_comp; ?></td> 
<!--<td><?php //echo $fecha_cuotas; ?></td>-->

</tr> 
         
<?php  } ?>
</tbody>
</table>
</form>

</div>
<?php require("PAGINACION.php"); ?>
<?php require_once("FOOTER.php"); ?>
<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/chosen.jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="PLUG-INS/chosen_v1.4.2/docsupport/prism.js"></script>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<!--<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php //echo "$LAST_VER" ?>"></script>-->
<script type="text/javascript">
    var config = {
      '.chosen-select'           : {no_results_text:'Oops, NO se encontro nada!'},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, NO se encontro nada!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>

<script type="text/javascript" language="javascript1.5">

function successFunction(a,b)
{
	//alert("FUNC paga_compra("+a+","+b);
	var data='ID='+a+"&id_compra="+b;
	ajax_x("ajax/pagar_compra_2.php",data,function(resp){
		
		//alert("GG");
		
		});
};

function anular_comp(num_comp)
{
	//alert(num_fac);
	if(!esVacio(num_comp)){
	if(confirm('Desea ANULAR Comprobante Egreso No.'+num_comp)){
	 $.ajax({
		url:'ajax/anula_comp_egreso.php',
		data:{num_comp:$.trim(num_comp)},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		var r=text.split('|');
		//alert(text);
		//$('#query').html(text);
		
		if(resp==0)simplePopUp('Este Comprobante YA esta Anulado!');
		else if(resp!=-2&&resp!=-1)
		{
			simplePopUp('Comprobante No.'+num_comp+' ANULADO');
			waitAndReload();
			
		}
		else if(resp==-1){simplePopUp('Este Comprobante supera el limite de tiempo permitido(1 dia) para modificaciones, accion cancelada.... ');}
		else simplePopUp('Comprobante No.'+num_comp+' NO encontrado');
		
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
	 });
	 
	}// if confirma
	
	}// if vacios
else {simplePopUp('Complete los espacios! No. Factura y PREFIJO(MTRH,RH,RAC,etc.)')}
	};



function pagar(url,pre,fac)
{
	if(confirm('Desea pagar Factura:'+pre+'-'+fac+' ??'))
	{
		location.assign(url);
	}
	else
	{
		simplePopUp('Operacion Cancelada');
	}
};
</script>
</div>
</div><!--fin pag 1-->

</body>
</html>