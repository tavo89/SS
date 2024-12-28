<?php 
require_once("Conexxx.php");
$busq="";
$val="";
$boton="";
$val4="";
if(isset($_REQUEST['opc'])){
$busq=$_REQUEST['busq'];
$val= $_REQUEST['valor'];
$val4= $_REQUEST['valor4'];
$_SESSION['cod_su_tras']=$val4;
$boton= $_REQUEST['opc'];
}

$cols="<th width=\"90px\">#</th>
<td></td>
<th width=\"50\">Codigo</th>
<th width=\"200\">Sede Origen</th>
<th width=\"200\">Tot. Costo</th>
<th width=\"200\">Estado</th>
<th width=\"150\">Fecha Envio</th>
<th width=\"150\">Fecha Recibido</th>
";


$tabla="inv_inter";
$col_id="id_pro";
$columns="cod_tras,cod_su,cod_su_dest,fecha_envio,fecha_recibo,estado,tot,(SELECT nombre_su FROM sucursal WHERE cod_su=t.cod_su)as nombre_su";
$url="recibir_traslados.php";
$url_dialog="dialog_invIni.php";
$url_mod="confirm_traslado.php";
$url_new="traslado_envia.php";
$pag="";
$limit = 20; 
$order="fecha_envio";
 

if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
 

$sql = "SELECT  $columns FROM traslados t WHERE cod_su_dest=$codSuc ORDER BY fecha_envio DESC LIMIT $offset, $limit"; 





if($boton=='mod'&& !empty($val)){
	
	//$_SESSION['n_tras']=$val;
	$_SESSION['pag']=$pag;
	//$_SESSION['cod_su_tras']=$val4;
	header("location: $url_mod?nt=$val&cod_su_tras=$val4");
	}
 


 
 
$sqlTotal = "SELECT FOUND_ROWS() as total"; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM traslados t WHERE (t.cod_tras LIKE '$busq%' OR t.fecha_envio LIKE '$busq%' OR t.estado LIKE '$busq%')  AND cod_su_dest=$codSuc";


$rs=$linkPDO->query($sql_busq);

	
	}
	

 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>Productos</title>
<link rel="stylesheet" href="JS/jquery.mobile-1.3.0/jquery.mobile-1.3.0.min.css" type="text/css" />
<link rel="stylesheet" href="JS/themes/honda.css" type="text/css" />

<script src="JS/jquery-2.1.1.js"></script>
<script type="text/javascript" language="javascript1.5" src="JS/jquery.mobile-1.3.0/jquery.mobile-1.3.0.min.js"></script>
<script language="javascript1.5" type="text/javascript">

</script>
</head>

<body>
<div data-role="page" data-theme="d" >
<div data-role="header" data-id="fix1" data-position="fixed">
<h1>Traslados Recibidos</h1>

<div style="position:absolute; top:-4px; right:5px;"><img src="<?php echo $url_LOGO_A ?>" width="<?php echo $x ?>" height="<?php echo $y ?>"></div>

<div data-role="navbar">
<ul>
<li><a href="<?php echo $url_new ?>"  data-icon="plus" data-iconpos="bottom" data-ajax="false" >Enviar Traslado</a></li>


</ul>

</div>


</div>

<div data-role="content">
<?php

if($boton=="Imprimir")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_tras']=$val;
	$_SESSION['suc_orig']=$val4;
	$_SESSION['pag']=$pag;
	popup("imp_tras.php","Traslado No. $val", "900px","650px");
};


?><form action="<?php echo $url ?>" method="post" name="form">
<table width="700px"  cellpadding="0" cellspacing="0" align="right">
<tr>
<td><input data-inline="true" data-mini="true" type="text" name="pag" value=""  placeholder="P&aacute;gina" id="pag"></td><td>&nbsp;</td>

<td align="left"><input palceholder="Buscar" type="search" name="busq" id="busqGeneral" value=""  data-inline="true" data-mini="true"/></td>
<td ><input type="submit" value="Buscar" name="opc" data-inline="true" data-mini="true" ></td>
</tr>
</table>
<br><br><br>
<?php //echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>
<table border="0" align="center" claslpadding="6px" bgcolor="#000000"> 
 
      <tr bgcolor="#595959" style="color:#FFF" valign="top"> 
      
<?php echo $cols;   ?>

       </tr>
        
          
      
<?php 

while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $cod_tras = htmlentities($row["cod_tras"]); 
            $cod_su = $row["cod_su"]; 
			$cod_su_dest = $row["cod_su_dest"];
			$nom_su = htmlentities($row["nombre_su"]);
			$tot = money($row["tot"]);
			$fecha_envio = $row["fecha_envio"];
			$fecha_recibo =$row["fecha_recibo"];
			$estado = htmlentities($row["estado"]);
			 
			
         ?>
 
<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<a href="<?php echo $url ?>?opc=mod&valor=<?php echo $cod_tras?>&valor4=<?php echo $cod_su ?>&pag=<?php echo $pag ?>" data-ajax="false" data-role="button" data-inline="true" data-mini="true">

<img src="Imagenes/confirm.png">
</a>
</td>
<td>
<a href="<?php echo $url ?>?opc=Imprimir&valor=<?php echo $cod_tras ?>&tipo_imp=carta&pag=<?php echo $pag ?>&valor4=<?php echo $cod_su ?>" data-ajax="false" data-role="button" data-inline="true" data-mini="true">

<img src="Imagenes/printer.png" width="20" height="20">
</a>
</td>

<!--<td>
<a href="<?php //echo $url_dialog ?>?des=<?php //echo $nom_su ?>&id=<?php //echo $cod_su ?>&pag=<?php //echo $pag ?>" data-rel="dialog">
<button data-inline="true" data-mini="true"><img src="Imagenes/cancel (1).png"></button>
</a>
</td>-->
</tr>
</table>


</td>             
<td><?php echo $cod_tras; ?></td>
<td><?php echo $nom_su; ?></td>
<td><?php echo $tot; ?></td> 
<td><?php echo $estado; ?></td>
<td><?php echo $fecha_envio; ?></td>
<td><?php echo $fecha_recibo; ?></td>
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
          
   
</table>

</form>

</div>
<div data-role="footer" data-id="fix1" data-position="fixed">
<?php echo "<a href=\"$url?pag=1\" data-icon=\"home\" data-role=\"button\">Inicio</a><a href=\"$url?pag=".$ant."\" data-icon=\"arrow-l\" data-role=\"button\">Ant</a><a href=\"$url?pag=".$sig."\"  data-role=\"button\">".$pag."/$totalPag</a><a href=\"$url?pag=".$sig."\" data-icon=\"arrow-r\" data-role=\"button\">Sig</a><a href=\"$url?pag=$totalPag\">Fin</a>";  ?>
<!--<img src="Imagenes/GlyphishBadges/badge-4.png" onClick="location.assign('http://www.glyphish.com/')">
-->
<?php echo "TOTAL: $total" ;



?>
<!--<img src="Imagenes/GlyphishBadges/badge-4.png" onClick="location.assign('http://www.glyphish.com/')">
-->

</div>
</div><!--fin pag 1-->

</body>
</html>