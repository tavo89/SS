<?php
include("Conexxx.php");
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=Inventario $munSuc $FechaHoy.doc");

$busq="";
$val="";
$boton="";
if(isset($_REQUEST['opc'])){
$busq=$_REQUEST['busq'];
$val= $_REQUEST['valor'];
$boton= $_REQUEST['opc'];
}
$tabla="inv_inter";
$col_id="id_pro";
$columns="id_inv,".tabProductos.".id_pro id_glo,inv_inter.id_inter  id_sede,detalle,id_clase,fraccion,fab,max,min,costo,precio_v,exist,iva,gana";
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
 

$cols="<th width=\"90px\">#</th>
<td></td>
<th width=\"250\">
<div style=\"display:inline-block;table-layout:fixed;width:80%;\">Ref</div>
</th>

<th width=\"200\">
<div style=\"display:inline-block;table-layout:fixed;width:80%;\">Codigo</div>
</th>

<th width=\"200\">
<div style=\"display:inline-block;width:80%;\">Descripci&oacute;n</div>
</div></th>
<th width=\"200\">presentaci&oacute;n</th>
<th width=\"200\">Costo</th>
<th width=\"150\">I.V.A</th>
<th width=\"200\">P.V.P</th>
<th width=\"200\">Exist./Fraccion</th>
<th width=\"50\">Presentacion</th>
<th width=\"200\">Fabricante</th>";

if($usar_fecha_vencimiento==1)
{
$cols.="<th width=\"200\">Fecha Vencimiento</th>";	
}



$sql = "SELECT  $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE  nit_scs=$codSuc ORDER BY $order  LIMIT $offset, $limit"; 


$sqlTotal = "SELECT FOUND_ROWS() as total"; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

$titulo="Inventario Honda $munSuc";
?>
<!DOCTYPE html>
<html xmlns:o='urn:schemas-microsoft-com:office:office'
    xmlns:w='urn:schemas-microsoft-com:office:word'
    xmlns='http://www.w3.org/TR/REC-html40'>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo $titulo ?></title>
<link rel=File-List href="<?php echo $carpeta ?>/filelist.xml">
<!--[if gte mso 9]-->
    <xml>
        <w:WordDocument>
            <w:View>Print</w:View>
            <w:Zoom>90</w:Zoom>
            <w:DoNotOptimizeForBrowser/>
        </w:WordDocument>
    </xml>
    <!-- [endif]-->
<style>
@page
{
    size:21cm 27.9cmt;  /* letter */
    margin:1cm 1cm 1cm 1cm; /* Margins: 2.5 cm on each side */
    mso-page-orientation: portrait;
	
}
@page Section1 { }
div.Section1 { page:Section1; }
p.MsoHeader, p.MsoFooter { border: 1px solid black; }
p.MsoDate, li.MsoDate, div.MsoDate
        {mso-style-link:"Date Char";
        margin:0in;
        margin-bottom:.0001pt;
        font-size:12.0pt;
        font-family:"Times New Roman","serif";}
td { page-break-inside:avoid; }
tr { page-break-after:avoid; }
 
    </style>

</head>

<body>
<div class="Section1">
<h2>Inventario Motorepuestos Honda <?php echo $_SESSION["municipio"]." ".$hoy ?></h2>
<form action="<?php echo $url ?>" method="post" name="form">
<table border="1" align="center" cellpadding="0px" cellspacing="0px" style="font-size:10px; width:650px"> 
 
      <tr bgcolor="#4B8EFA" style="color:#FFF" class="ui-btn-active"> 
      
<?php echo $cols;   ?>

       </tr>
        
          
      
<?php 
$pagBreak=0;
$once=0;
$pag=1;
$TOT_PAG=round($total/28);
if($TOT_PAG<($total/28))$TOT_PAG++;
while ($row = $rs->fetch()) 
{ 
$ii++;
$pagBreak++;		    
            $id_inter = htmlentities($row["id_glo"]); 
            $des = htmlentities($row["detalle"], ENT_QUOTES,"$CHAR_SET"); 
			$clase = htmlentities($row["id_clase"], ENT_QUOTES,"$CHAR_SET");
			$id = htmlentities($row["id_sede"]);
			$frac = htmlentities($row["fraccion"]);
			$uni = htmlentities($row["unidades_frac"]);
			$fab = htmlentities($row["fab"], ENT_QUOTES,"$CHAR_SET"); 
			$fechaVen=$row['fecha_vencimiento'];
			$pvp=money($row['precio_v']);
			 
			
         ?>
 
<tr  bgcolor="#FFF" style="font-size:10px;">

<td><?php echo $id_inter; ?></td>
<td><?php echo $row['id_sede']; ?></td>
<td width="130"><?php echo $des; ?></td>
<td><?php echo $row['presentacion']; ?></td> 
<td width="80"><?php echo $pvp; ?></td>
<td align="center"><?php echo (1*$row['exist']).";".$row['unidades_frac']; ?></td>
<?php
if($usar_fecha_vencimiento==1){
?>
<td><?php echo $fechaVen ; ?></td>
<?php
}
?> 
<td width="20"></td> 
  
<!--<td width="70"><?php //echo $fab; ?></td>-->
</tr> 
         
<?php          
if($pagBreak==28&&$once==0){
	//eco_alert("once: $once");
	
	  //aecho "<tr  ><td colspan=7> <br clear=all style='mso-special-character:line-break;page-break-before:always'></td></tr>";
	  ?>
      </table>
	   <?php echo "<h3><b>P&aacute;gina $pag/$TOT_PAG</b></h3>" ?>
      <br clear=all style="mso-special-character:line-break;page-break-before:always">
      <?php echo "<b>Inventario Moto Repuestos Honda $munSuc, $hoy</b>" ?>
<table border="1" align="center" cellpadding="0px" cellspacing="0px" style="font-size:10px; width:650px">
      <?php
	  
	 $once=1;
	$pagBreak=0;
$pag++;		
	  }	


 if($pagBreak==28){
	  //aecho "<tr  ><td colspan=7> <br clear=all style='mso-special-character:line-break;page-break-before:always'></td></tr>";
	  ?>
      </table>
	  <?php echo "<h3><b>P&aacute;gina $pag/$TOT_PAG</b></h3>" ?>
      <br clear=all style="mso-special-character:line-break;page-break-before:always">
      <?php echo "<b>Inventario Moto Repuestos Honda $munSuc, $hoy</b>" ?>
<table border="1" align="center" cellpadding="0px" cellspacing="0px" style="font-size:10px; width:650px">
      <?php
	  
	  $pagBreak=0; 
	  $pag++;	
	  }
		 
	 
		 
		 } ////////////////// fin while
      ?>
 

 
             <?php 
        
$sql="select sum(exist*costo) tot from inv_inter where nit_scs=$codSuc";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
$TOT=$row['tot'];	
}

      ?>
          
   <tr style="font-size:24px">
   <td width="300PX" colspan="3" >Total Costo sin IVA:</td><td colspan="5"><?php echo money($TOT) ?></td>
   </tr>
</table>
 <?php echo "<h3><b>P&aacute;gina $pag/$TOT_PAG</b></h3>" ?>
</form>

</div>

</body>
</html>