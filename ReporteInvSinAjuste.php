<?php

include("Conexxx.php");
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$opc=r("opc");
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=InventarioHondaSinAjuste $munSuc $FechaHoy Hora $hora.xls");

/////////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_ajus";
$PAG_fechaF="fechaF_ajus";
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" data-inline=\"true\" data-mini=\"true\" >";
$A="";
if(isset($_REQUEST['fechaI'])){$fechaI=$_REQUEST['fechaI']; $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=$_REQUEST['fechaF'];$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" data-inline=\"true\" data-mini=\"true\" data-icon=\"delete\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}





if($opc=="QUITAR")
{
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" data-inline=\"true\" data-mini=\"true\" >";
	$fechaI="";
	$fechaF="";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	$A="";
}
//---------------
$cols="
<th width=\"20\">#</th>
<th width=\"70\">Ref</th>
<th width=\"70\">Codigo</th>
<th width=\"200\">Descripci&oacute;n</th>
<th width=\"30\">PvP</th>
<th width=\"30\">Saldo</th>
<th width=\"50\">Bueno</th>
<th width=\"50\">Malo</th>
<th width=\"30\">Total</th>
<!--<th width=\"50\">Frabricante</th>-->" ;


$tabla="inv_inter";
$col_id="a.id_pro";
$columns="id_inv,a.id_pro id_glo,b.id_inter  id_sede,detalle,id_clase,fraccion,fab,max,min,costo,precio_v,exist,iva,gana";
$url="inventario_inicial.php";
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="agregar_inventario.php";
$pag="";
$limit = 20; 
$order="id_glo";
 
if(isset($_SESSION['order'])){

if($_SESSION['order']="1")$order="id_glo";
else if($_SESSION['order']="2")$order="id_glo";
else if($_SESSION['order']="3")$order="fab";

}
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
 
$subSql="SELECT ref FROM ajustes a INNER JOIN art_ajuste ar ON a.num_ajuste=ar.num_ajuste WHERE a.cod_su=ar.cod_su AND a.cod_su=$codSuc AND ar.estado2!='ANULADO' $A ";

$sql = "SELECT  $columns FROM ".tabProductos." a INNER JOIN inv_inter b ON a.id_pro=b.id_pro WHERE nit_scs=$codSuc AND b.exist>0 AND a.id_pro NOT IN ($subSql) ORDER BY $order "; 

$rs = $linkPDO->query($sql ); 

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Productos</title>
</head>

<body>
<div data-role="page" data-theme="d" >
<div data-role="header" data-id="fix1" data-position="fixed">
<h2>Inventario SIN AJUSTES Motorepuestos Honda <?php echo $_SESSION["municipio"]." ".$hoy ?></h2>





</div>

<div data-role="content">
<form action="<?php echo $url ?>" method="post" name="form">
<?php //echo $sql_busq;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; 

//echo "$sql";
?>
<table border="1" align="center" cellpadding="0px" cellspacing="0px" style="font-size:10px; width:600px"> 
 
      <tr bgcolor="#4B8EFA" style="color:#FFF" class="ui-btn-active"> 
      
<?php echo $cols;   ?>

       </tr>
        
          
      
<?php 

while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $id_inter = htmlentities($row["id_glo"]); 
            $des = htmlentities($row["detalle"], ENT_QUOTES,'UTF-8'); 
			$clase = htmlentities($row["id_clase"], ENT_QUOTES,'UTF-8');
			$id = htmlentities($row["id_sede"]);
			$frac = htmlentities($row["fraccion"]);
			$fab = htmlentities($row["fab"], ENT_QUOTES,'UTF-8'); 
			$pvp=money($row['precio_v']);
			 
			
         ?>
 
<tr  bgcolor="#FFF" style="font-size:10px;">
<td width="100" align="left" height="10"><?php echo $ii; ?></td>
<td width="100" align="left" height="20"><?php echo $id_inter; ?></td>
<td width="60" align="left"><?php echo $row['id_sede']; ?></td>
<td width="130"><?php echo $des; ?></td> 
<td width="80"><?php echo money3($pvp); ?></td>
<td width="20"><?php echo $row['exist']; ?></td>
<td width="20"></td> 
<td width="20"></td>
<td width="20"></td>  
<!--<td width="70"><?php //echo $fab; ?></td>-->
</tr> 
         
<?php 
         } 
      ?>
 
</table>

</form>

</div>
<div data-role="footer" data-id="fix1" data-position="fixed">

</div>
</div><!--fin pag 1-->

</body>
</html>