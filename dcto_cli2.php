<?php 
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"clientes_mod")){header("location: centro.php");}
$id_cli="";
$nombre_cliente="";
if(isset($_SESSION['id_cli']))$id_cli=$_SESSION['id_cli'];
if(isset($_SESSION['nombre_cli']))$nombre_cliente=$_SESSION['nombre_cli'];

$busq="";
$val="";
$val2="";
$val3="";
$val4="";
$boton="";
if(isset($_REQUEST['opc'])){
$busq=$_REQUEST['busq'];
$val= $_REQUEST['valor'];
$val2= $_REQUEST['valor2'];
$val3= $_REQUEST['valor3'];
$val4= $_REQUEST['valor4'];
$boton= $_REQUEST['opc'];
}

$cols="<th width=\"90px\">#</th>
<td></td>
<th width=\"50\">C.C</th>
<th width=\"200\">Nombre</th>
<th width=\"200\">Operador</th>
<th width=\"200\">Dcto %</th>
<th width=\"150\">Fecha Modificaci&oacute;n</th>

";


$tabla="descuentos";
$col_id="id_pro";
$columns="id_cli,fabricante,fecha_mod,dcto,(select nombre from usuarios where id_usu='$id_cli') as nombre,cod_su";
$url="dcto_cli2.php";
$url_dialog="dialog_invIni.php";
$url_mod="mod_traslado.php";
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
 

$sql = "SELECT  $columns FROM dcto_fab WHERE cod_su=$codSuc AND id_cli='$id_cli' ORDER BY nombre LIMIT $offset, $limit"; 








if($boton=='mod'&& !empty($val) && $val2=="SIN CONFIRMAR"){// && $val3==$FechaHoy){
	
	$_SESSION['n_tras']=$val;
	$_SESSION['cod_su_tras']=$val4;
	$_SESSION['pag']=$pag;
	
	header("location: $url_mod");
	}
 


 
 
$sqlTotal = "SELECT FOUND_ROWS() as total"; 
$rs = $linkPDO->query($sql) ; 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM dcto_fab WHERE (id_pro LIKE '$busq%' OR fabricante LIKE '$busq%')  AND id_cli='$id_cli'";



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
<h1>Descuentos por Operador- <?php echo "$nombre_cliente" ?></h1>
<div style="position:absolute; top:-4px; right:5px;"><img src="<?php echo $url_LOGO_A ?>" width="<?php echo $x ?>" height="<?php echo $y ?>"></div>


<div data-role="navbar">
<ul>
<li><a href="crear_descuento2.php"  data-icon="grid" data-ajax="false">Crear Dcto. a Cliente</a></li>



</ul>

</div>


</div>

<div data-role="content">
<?php

if($boton=="Imprimir")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_tras']=$val;
	$_SESSION['suc_orig']=$codSuc;
	$_SESSION['pag']=$pag;
	popup("imp_tras.php","Traslado No. $val", "900px","650px");
};


?>
<form action="<?php echo $url ?>" method="post" name="form">
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
		    
            $id = htmlentities($row["id_cli"]); 
            $cod_su = $row["cod_su"]; 
			$nom_su = htmlentities($row["nombre"]);
			$dcto=$row['dcto'];
			$fab = $row["fabricante"];
			$fecha =$row["fecha_mod"];
			 
			
         ?>
 
<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td>
<!--
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<a href="<?php echo $url ?>?opc=mod&valor=<?php echo $id ?>&pag=<?php echo $pag ?>" data-ajax="false" data-role="button" data-inline="true" data-mini="true">

<img src="Imagenes/b_edit.png">
</a>
</td>
<td>
<a href="<?php echo $url ?>?opc=Imprimir&valor=<?php echo $id ?>&tipo_imp=carta&pag=<?php echo $pag ?>" data-ajax="false" data-role="button" data-inline="true" data-mini="true">

<img src="Imagenes/printer.png" width="20" height="20">
</a>
</td>
</tr>
</table>

-->
</td>             
<td><?php echo $id; ?></td>
<td><?php echo $nom_su; ?></td>
<td><?php echo $fab; ?></td> 
<td align="center" id="D<?php echo $ii ?>" onDblClick="mod_dcto('<?php echo $id_cli ?>','<?php echo $fab ?>','<?php echo $dcto ?>',<?php echo $ii ?>)">
<?php echo $dcto; ?></td>
<td><?php echo $fecha; ?></td>
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
<script language="javascript1.5" type="text/javascript">
function esVacio(val) {
   if(val == null) {
      return true; 
      }
   for(var i = 0; i < val.length; i++) {
      if((val.charAt(i) != ' ') && (val.charAt(i) != "\t") && (val.charAt(i) != "\n") && (val.charAt(i) != "\r")) {
         return false; 
         }
      }
   return true; 
   };
function update(t,c,vc,col1,col2,valCol1,valCol2,ii)
{
	var $td=$('#D'+ii);
	
	if(!esVacio(t)&&!esVacio(c)&&!esVacio(vc)&&!esVacio(col1))
	{
		$.ajax({
			url:'ajax/update_2.php',
			data:{t:t,c:c,vc:vc,col1:col1,col2:col2,val_col1:valCol1,val_col2:valCol2},
			type:'POST',
			dataType:'text',
			success:function(resp)
			{
				if(resp==1)
				{
				simplePopUp('Modificacion Exitosa!');
				$td.html(vc);
				}
				else if(resp==0)
				{
					simplePopUp('Ocurio un error...');
					
				}
			}			
			});
		
	}	
};
function clearx(ii,dcto)
{
	var $td=$('#D'+ii);
	$td.html(dcto);
};

function mod_dcto(id_cli,fab,dcto,ii)
{
	var $td=$('#D'+ii);
	$td.html('');
	var html="<input type=\"text\" name=\"dcto\" value=\""+dcto+"\" id=\"txt_dcto"+ii+"\" onChange=\"update('dcto_fab','dcto',this.value,'id_cli','fabricante','"+id_cli+"','"+fab+"',"+ii+"); \" onBlur=\"clearx('"+ii+"','"+dcto+"')\">";
	$('#txt_dcto'+ii);
	var $box=$(html);
	$box.appendTo($td);
	$box.focus();	
}
</script>
</body>
</html>