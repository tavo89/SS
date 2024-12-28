<?php 
require_once("Conexxx.php");
$val="";
$boton="";
$pag="";
$limit = 20; 
$busq="";
$url="inventario_total.php";
$url_mod="modificar_inv.php";
$url_new="agregar_producto.php";
 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"]; }
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;

if(isset($_REQUEST['opc'])){$boton= $_REQUEST['opc'];}
if(isset($_REQUEST['valor']))$val= $_REQUEST['valor'];
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];



if($boton=='mod'&& !empty($val)){
	
	//$_SESSION['id']=$val;
	$_SESSION['pag']=$pag;
	
	header("location: modificar_producto.php?REF=$val");
	}
 



 
 
$sql = "SELECT  serial_pro,id_pro,detalle,id_clase,frac,fab FROM ".tabProductos." ORDER BY detalle  LIMIT $offset, $limit"; 
$sqlTotal = "SELECT FOUND_ROWS() as total"; 
 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
 
$rowTotal = $rsTotal->fetch(); 

$total = $rowTotal["total"]; 

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){


$rs=$linkPDO->query("SELECT serial_pro,id_pro,detalle,id_clase,frac,fab FROM ".tabProductos." WHERE id_pro LIKE '$busq%' OR detalle LIKE '%$busq%'  OR id_clase LIKE '$busq%'");
	
	}
	
 if($boton=='Aceptar'){
	 
	 $col=$_REQUEST['orden_columna'];
	 $rs=$linkPDO->query("SELECT * FROM inventario ORDER BY $col ASC");
	 }

 
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
<li><a href="<?php echo $url_new ?>" ><i class="uk-icon-plus-square"></i>&nbsp;Crear Producto</a></li>
<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin"></i>&nbsp;Recargar P&aacute;g.</a></li>
</ul>
</nav>
<h1 align="center">Base de Datos de Productos</h1>
<br><br><br>
<div id="sb-search" class="sb-search">
						<form>
							<input class="sb-search-input" placeholder="Ingrese su b&uacute;squeda..." type="text" value="" name="busq" id="search">
							<input class="sb-search-submit" type="submit" value="Buscar" name="opc">
							<span class="sb-icon-search"></span>
						</form>
					</div>
 <br><br><br><br><br>


<form action="inventario_total.php" method="post" name="form" class="uk-form">
<?php require("PAGINACION.php"); ?>
<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped"> 
 <thead>
      <tr bgcolor="#595959" style="color:#FFF" valign="top">
      <th>#</th> 
       <td></td>
        <th width="150">Ref.</th>
        <th width="350">Descripci&oacute;n</th> 
         <th width="100">Clasificaci&oacute;n</th> 
          <th width="50">Fracciones</th>
           <th >Fabricante</th>  
         </tr>
        
  </thead>
  <tbody>        
      
<?php 

while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $id_inter = htmlentities($row["id_pro"]); 
            $des = htmlentities($row["detalle"], ENT_QUOTES,"$CHAR_SET"); 
			$clase = htmlentities($row["id_clase"], ENT_QUOTES,"$CHAR_SET");
			$id = htmlentities($row["serial_pro"]);
			$frac = htmlentities($row["frac"]);
			$fab = htmlentities($row["fab"], ENT_QUOTES,"$CHAR_SET"); 
			 
			
         ?>
 
<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td width="90px">
<table width="80px" cellpadding="0" cellspacing="0">
<tr>
<td>
<?php
if($rolUsu=="Jefe Inventarios"||$rolLv>=$Adminlvl){
?>
<a href="<?php echo "inventario_total.php?opc=mod&valor=$id_inter&pag=$pag" ?>" data-ajax="false" data-role="button" data-inline="true" data-mini="true">
<img src="Imagenes/b_edit.png">
</a></td>
<!--
<td>
<a href="dialog_invTot.php?des=<?php echo $des ?>&id=<?php echo $id_inter ?>&pag=<?php echo $pag ?>" data-rel="dialog"data-role="button" data-inline="true" data-mini="true" >
<img src="Imagenes/b_drop.png">
</a>
<?php
}
?>
</td>
-->
</tr>
</table>


</td>             
<td><?php echo $id_inter; ?></td> 
<td><?php echo $des; ?></td> 
<td><?php echo $clase; ?></td> 

<td><?php echo $frac; ?></td>
<td><?php echo $fab ; ?></td>  
</tr> 
         
<?php 
         } 
      ?>
 
</tbody>
</table>
</form>
<?php require("PAGINACION.php"); ?>
<?php require_once("FOOTER.php"); ?>	
</body>
</html>