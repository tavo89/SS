<?php 
include("Conexxx.php");
$num_exp=$_SESSION['num_exp'];
	$tot_cuotas=0;
	$tot_anti=0;
	$sql="SELECT * FROM exp_anticipo WHERE num_exp=$num_exp";
	$rs=$linkPDO->query($sql);
	
	if($row=$rs->fetch())
	{
	 $tot_anti=$row['tot'];  	
	}
	
	$sql="SELECT SUM(valor) as tot_abon FROM comp_anti c INNER JOIN exp_anticipo ex ON c.num_exp=ex.num_exp WHERE ex.num_exp=$num_exp AND ex.cod_su=c.cod_su AND c.estado!='ANULADO' AND ex.cod_su=$codSuc";
	//echo "<br><b>$sql</b>";
	$rs=$linkPDO->query($sql);
	
	if($row=$rs->fetch())
	{
	   	$tot_cuotas=$row['tot_abon'];
	}

$busq="";
$val="";
$boton="";
$opc="";
if(isset($_REQUEST['opc'])){
$busq=r('busq');
$val= r('valor');
$boton= r('opc');
$opc=r('opc');
}

$cols="<th width=\"90px\">#</th>
<td></td>
<th width=\"250\">No. Comprobante</th>
<th width=\"200\">Valor Pagado</th>
<th width=\"250\">Cliente</th>
<th width=\"200\">Fecha Comprobante</th>
<th width=\"200\">Cajero</th>
<th width=\"200\">Estado</th>
";

$tabla="";
$col_id="id_pro";
$columns="a.num_com,a.cod_su,a.fecha,a.valor,a.estado,a.cajero,a.num_exp,a.tipo_pago,e.nom_cli,a.id";
$url="cuotas_anticipos.php";
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="comp_anticipo.php";
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
 

$sql = "SELECT  $columns FROM comp_anti a INNER JOIN exp_anticipo e ON a.num_exp=e.num_exp AND a.cod_su=e.cod_su  WHERE a.num_exp=$num_exp AND a.cod_su=$codSuc  LIMIT $offset, $limit"; 
 //echo $sql;
$sqlTotal = "SELECT COUNT(*) as total FROM comp_anti WHERE cod_su='$codSuc'"; 
$rs = $linkPDO->query($sql ); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 


	

 
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
<li><a href="<?php echo $url ?>?opc=new_comp&valor=<?php echo 0 ?>&pag=<?php echo $pag ?>"  ><i class="uk-icon-plus-square"></i>&nbsp;Crea Comprobante</a></li>
<li><a href="expedientes.php" ><i class="uk-icon-briefcase"></i>&nbsp;Historial.</a></li>

<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin"></i>&nbsp;Recargar P&aacute;g.</a></li>

</ul>
</nav>
<h1 align="center">Cuotas Anticipo No.<?php echo $num_exp ?></h1>

<?php //echo $opc."<br>"; 
if($opc=="ver_fac")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_fac_ven']=$num_fac;
	popup("imp_fac_ven_cre.php","Factura No. $num_fac", "900px","1200px");
};
if(val_secc($id_Usu,"crear_anticipo") || $rolLv==$Adminlvl){
if($opc=="new_comp")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	popup("comp_anticipo.php?exp=$num_exp","Comprobante de Anticipo", "800px","500px");
};
};
if($opc=="ver_plan")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['cod_plan']=$val;
	popup("imp_plan_amor.php","Comprobante de Ingreso", "900px","700px");
};

if($opc=="imp_comp")
{
$_SESSION['num_com_anti']=$val;
$_SESSION['num_exp']=$num_exp;
$ID_COMP=r("ID");
$_SESSION['id_comp_anti']=$ID_COMP;
imp_a("num_comp_anti",$val,"imp_comp_anti.php","Comprobante de Anticipo No. $val","800px","600px");
};

if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM comp_anti a INNER JOIN exp_anticipo e ON a.num_exp=e.num_exp WHERE a.cod_su=$codSuc AND (a.num_exp LIKE '$busq%' OR a.num_com LIKE '$busq%' OR a.cajero LIKE '$busq%' OR a.concepto LIKE '$busq%' OR e.nom_cli LIKE '$busq%') AND a.num_exp=$num_exp order by num_com DESC";


$rs=$linkPDO->query($sql_busq);
	}

?>
<br><br><br>
<div id="sb-search" class="sb-search">
						<form class="uk-form ms_panels">
							<input class="sb-search-input" placeholder="Ingrese su b&uacute;squeda..." type="text" value="" name="busq" id="search">
							<input class="sb-search-submit" type="submit" value="Buscar" name="opc">
							<span class="sb-icon-search"></span>
						</form>
					</div>


<form autocomplete="off" action="<?php echo $url ?>" method="post" name="form">

<br><br><br><br>
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
		    $ID =  $row["id"] ;
            $num_comp =  $row["num_com"] ;
			$fecha_comp= $row["fecha"];
			$cajero=$row["cajero"]; 
			$valor=money($row['valor']);
			$estado=$row["estado"];
			$nomCli=$row['nom_cli'];
			 
			$functValor="mod_tab_row('tabTD03$ii','comp_anti','valor','$valor',' id=\'$ID\' AND cod_su=\'$codSuc\'','$ii','text','','');";			
         ?>
 
<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<a href="<?php echo "$url?opc=imp_comp&valor=$num_comp&pag=$pag&ID=$ID" ?>  " data-ajax="false" data-role="button" data-inline="true" data-mini="true">

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
<td id="tabTD03<?php echo $ii ?>" onDblClick="<?php echo $functValor; ?>"><?php echo $valor; ?></td>
<td><?php echo $nomCli; ?></td>
<td><?php echo $fecha_comp; ?></td> 
<td><?php echo $cajero; ?></td>
<td><?php echo $estado; ?></td>
</tr> 
         
<?php 
         } 
      ?>
 
</tbody>
</table>

</form>


<?php require("PAGINACION.php"); ?>	
<?php require_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
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