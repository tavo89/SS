<?php 
include("Conexxx.php");
$UserFilter="AND a.fecha>='$fechaCreaUsu'";
if($rolLv==$Adminlvl || $separar_registros_por_usuarios==0)$UserFilter="";
$opc="";
$busq="";
$val="";
$boton="";
$tot_cuotas=0;
$val_cre=0;
$totCre=0;
$num_exp="";
if(isset($_GET['num_exp'])){$num_exp=$_REQUEST['num_exp'];}
if(isset($_GET['opc'])){$opc=$_REQUEST['opc'];}
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val=$_REQUEST['valor'];
if(isset($_REQUEST['opc']))$boton= $_REQUEST['opc'];

///////////////////////////////////////////////////// FILTRO FECHA /////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_anti";
$PAG_fechaF="fechaF_anti";
$botonFiltro="<input class=\"uk-button uk-button-success\" type=\"submit\" name=\"opc\" value=\"Filtrar\"  >";
$A="";
if(isset($_REQUEST['fechaI'])){$fechaI=$_REQUEST['fechaI']; $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=$_REQUEST['fechaF'];$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\"  class=\"uk-button uk-button-danger\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(a.fecha)>='$fechaI' AND DATE(a.fecha)<='$fechaF') ";
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


/////////////////////////////////////////////////////// CONSULTAS ADICIONALES //////////////////////////////////////////////////////////////////////
$sql="SELECT SUM(valor) tot FROM comp_anti a WHERE a.cod_su=$codSuc AND a.estado!='ANULADO' $A";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){$totCre=$row['tot'];}

$sql="SELECT SUM(valor) tot FROM comp_anti a WHERE a.cod_su=$codSuc AND a.estado='COBRADO' $A";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{$tot_cuotas=$row['tot'];}

$saldo=$val_cre-$tot_cuotas;
if($saldo<0)$saldo=0;

//-----------------------------------------------------------------------------------------------------------------------------------------------------


////////////////////////////////////////////////////////////////////////////////// CABECERA TABLA ////////////////////////////////////////////////////

$cols="<th width=\"90px\">#</th>
<td></td>
<th width=\"250\">No. Comprobante</th>
<th width=\"250\">No. Expediente</th>
<th width=\"250\">Cliente</th>
<th width=\"200\">Valor Pagado</th>
<th width=\"200\">Forma Pago</th>
<th width=\"200\">Fecha Comprobante</th>
<th width=\"200\">Cajero</th>
<th width=\"200\">Estado</th>
";



$tabla="";
$col_id="id_pro";
$columns="a.id,a.num_com,a.cod_su,a.fecha,a.valor,a.estado,a.cajero,a.num_exp,a.tipo_pago,e.nom_cli";
$url="abonos_anticipos.php";
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="comp_anticipo.php";

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
 

$sql = "SELECT  $columns FROM comp_anti a INNER JOIN exp_anticipo e ON a.num_exp=e.num_exp WHERE a.cod_su=$codSuc $A $UserFilter ORDER BY num_com DESC  LIMIT $offset, $limit";  
//echo $sql;

$rs = $linkPDO->query($sql) ; 




$sqlTotal = "SELECT COUNT(*) AS total FROM comp_anti a WHERE  a.cod_su=$codSuc $A $UserFilter"; 

$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 
//-----------------------------------------------------------------------------------------------------------------------------------------------------



/////////////////////////////////////////////////////////////////// BUSCAR ///////////////////////////////////////////////////////////////////////////
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM comp_anti a INNER JOIN exp_anticipo e ON a.num_exp=e.num_exp WHERE a.cod_su=$codSuc AND (a.num_exp LIKE '$busq%' OR a.num_com LIKE '$busq%' OR a.cajero LIKE '$busq%' OR a.concepto LIKE '$busq%' OR e.nom_cli LIKE '$busq%') $A $UserFilter order by num_com DESC";


$rs=$linkPDO->query($sql_busq);
	}

 
?>
<!DOCTYPE html>
<html >
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




		<!-- Lado izquierdo del Nav -->
	<nav class="uk-navbar">

	<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico"  class="icono_ss"> &nbsp;SmartSelling</a> 

		<!-- Centro del Navbar -->

		<ul class="uk-navbar-nav uk-navbar-center" style="width:540px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
			<li class="ss-navbar-center"><a style="cursor:pointer;" href="#"  onClick="print_pop('agregar_exp.php','EGRESO',800,600)"><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Agregar Anticipo</a></li>
			<li><a href="expedientes.php" ><i class="uk-icon-briefcase <?php echo $uikitIconSize ?>"></i>&nbsp;Historial.</a></li>

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


</ul>




<?php //echo $sql."<br>";

$num_fac=0;
if(isset($_REQUEST['num_fac']))$num_fac=$_REQUEST['num_fac']; 
if($opc=="ver_fac")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['prefijo']=$codCreditoSuc;
	popup("imp_fac_ven_cre.php","Factura No. $num_fac", "900px","600px");
};
if(val_secc($id_Usu,"crear_anticipo") || $rolLv==$Adminlvl){
if($opc=="new_comp")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	popup("comp_anticipo.php?exp=$num_exp","Comprobante de Anticipo", "1200px","400px");
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
$_SESSION['num_com_anti']=$val;
$_SESSION['num_exp']=$num_exp;
$ID_COMP=r("ID");
$_SESSION['id_comp_anti']=$ID_COMP;
imp_a("num_comp_anti",$val,"imp_comp_anti.php","Comprobante de Anticipo No. $val","800px","600px");
};

?>
<h1 align="center">Comprobantes de Ingreso - Anticipos CLIENTES</h1>


<form autocomplete="off" action="<?php echo $url ?>" method="get" name="form">

<center>
	<div class="ms_panels uk-width-3-5" align="center">
		<label >
			Desde:
		</label>

		<input size="10" name="fechaI" value="<?php echo $fechaI ?>" type="date" id="f_ini" onClick="//popUpCalendar(this, form.f_ini, 'yyyy-mm-dd');"  placeholder="Fecha Inicial" style=" ">

		<label>
			Hasta:
		</label>

		<input size="15" value="<?php echo $fechaF ?>" type="date" name="fechaF" id="f_fin" onClick="//popUpCalendar(this, form.f_fin, 'yyyy-mm-dd');"  placeholder="Fecha Final" style=" ">

		<?php echo $botonFiltro ?>

	</div>
	</center>
<?php require("PAGINACION.php"); ?>
<table border="0" align="center" claslpadding="6px" bgcolor="#ffffff" class="uk-table uk-table-hover uk-table-striped tabla-datos" > 
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
		    $ID = $row["id"];
            $num_comp = $row["num_com"];
			$num_exp = $row["num_exp"];
	
			$fecha_comp=$row["fecha"];
			$cajero=$row["cajero"]; 
			$valor=money($row['valor']);
			$estado=$row["estado"];
			$forma_pago=$row["tipo_pago"];
			$nomCli=$row['nom_cli'];
			 
			
         ?>
 
<tr >
<th><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<a href="<?php echo "$url?opc=imp_comp&valor=$num_comp&num_exp=$num_exp&ID=$ID&pag=$pag" ?>" data-ajax="false" data-role="button" data-inline="true" data-mini="true">

<img src="Imagenes/printer.png" width="26" height="26">
</a>
</td>
<?php 
if($rolLv==$Adminlvl || val_secc($id_Usu,"anular_comp_anticipo")){
?>
<td>
<a href="#" data-role="button" data-inline="true" data-mini="true" onMouseUp="anular_comp('<?php echo $num_comp; ?>');">
<img src="Imagenes/cancel (1).png" width="20" height="20">
</a>
</td>
<?php 
}
?>

</tr>
</table>


</td>             
<td><?php echo $num_comp; ?></td>
<td><?php echo $num_exp; ?></td>
<td><?php echo $nomCli; ?></td>
<td><?php echo $valor; ?></td>
<td><?php echo $forma_pago; ?></td>
<td><?php echo $fecha_comp; ?></td> 
<td><?php echo $cajero; ?></td>
<td  <?php  if($estado=="ANULADO")echo "bgcolor=\"#CCCCCC\"";else echo "bgcolor=\"#FFF\""; ?>><?php echo $estado; ?></td>
</tr> 
         
<?php 
         } 
      ?>
 

 
             <?php 
         $totalPag = ceil($total/$limit); 
         $links = array(); 
		 $sig=$pag+1;
		 if($sig>$totalPag)$sig=$pag;
		 $ant=$pag-1;
		 if($ant<1)$ant=$pag;
         for( $i=1; $i<=$totalPag ; $i++) 
         { 
            $links[] = "<a href=\"?pag=$i\">$i</a>";  
         } 
         //echo "<a href=\"?pag=1\">Inicio</a>-<a href=\"?pag=".$ant."\" data-icon=\"row-l\" data-role=\"button\">Ant</a>-".implode(" - ", $links)."-<a href=\"?pag=".$sig."\">Sig</a>-<a href=\"?pag=$totalPag\">Fin</a>"; 
      ?>
          
</tbody>
   
</table>

</form>

<?php require("PAGINACION.php"); ?>	
<?php require_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5">
function anular_comp(num_comp)
{
	if(!esVacio(num_comp)){
	if(confirm('Desea ANULAR Comprobante Anticipo No.'+num_comp)){
	 $.ajax({
		url:'ajax/anula_comp_anticipo.php',
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
		else if(resp==-1){simplePopUp('Este Comprobante supera el limite de tiempo permitido(<?php echo $dias_anula_comps?> dia(s)) para modificaciones, accion cancelada.... ');}
		else if(resp==-4)simplePopUp('Este comprobante ya fue COBRADO');
		else simplePopUp('Comprobante No.'+num_comp+' NO encontrado');
		
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
	 });
	 
	}// if confirma
	
	}// if vacios
else {simplePopUp('Complete los espacios! No. Factura y PREFIJO(MTRH,RH,RAC,etc.)')}
	};


</script>


</body>
</html>