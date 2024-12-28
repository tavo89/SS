<?php

include('Conexxx.php');
session();
$num_fac=$_SESSION['cod_plan'];
$pre=$_SESSION['prefijo'];
$boton="";

if(isset($_REQUEST['boton'])){
//$num_fac=limpiarcampo($_REQUEST["num_fac"]);

$boton=limpiarcampo($_REQUEST["boton"]);
}
//out.print(" nc:"+nc);

$maxDay[]=0;
$maxDay[0]=31;$maxDay[1]=28;$maxDay[2]=31;$maxDay[3]=30;$maxDay[4]=31;
$maxDay[5]=30;$maxDay[6]=31;$maxDay[7]=31;$maxDay[8]=30;$maxDay[9]=31;
$maxDay[10]=30;$maxDay[11]=31;

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script language="javascript" type="text/javascript" src="JS/jQuery1.8.2.min.js"></script>
<script language='javascript' src="JS/popcalendar.js"></script> 
<script language='javascript' src="JS/imp_fac.js"></script> 
<script language="javascript" type="text/javascript">
function crear_plan()
{
	if(confirm('Para crear un Plan de Amortizacion se debe BORRAR el anterior'))
	{
		location.assign('plan_amortizacion.php?boton=elimina');
		}
	
	}

function submitt(val,id,frm)
{
	$('#'+id).attr("value",val);

	frm.submit();
	
}


function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Plan Amortizaci&oacute;n</title>

</head>
<body>
<form action="imp_plan_amor.php" name="form1" method="post">
  <?php 
  
$rs=$linkPDO->query("select * from plan_amortizacion where codigo=$num_fac AND cod_su=$codSuc AND pre='$pre'");

//$cli=rs.getString("id_usu");
//$co=rs.getString("id_co");

if($row=$rs->fetch()){
$fecha=$row["fecha_fac"];
$nom_cli=htmlentities($row["nom_cli"], ENT_QUOTES,'UTF-8');
$id_cli=$row["id_cliente"];
$dir_cli=$row['dir1_cli'];
$tel_cli=$row['tel1_cli'];
$expedicion_cli=$row['exp_cli'];
$ciudad_cli=htmlentities($row["ciudad"], ENT_QUOTES,'UTF-8');
$val_fac=$row['val_fac'];
$val_dif=$row['val_dif'];
$plazo=$row['plazo'];
$val_cre=$row['val_cre'];
$num_cuotas=$row['num_c'];
$pagado=$row['pagado'];
$estado=$row['estado'];
$prefijo=$row['pre'];
 // rs=con.consultar("select * from usuario where id_usu='"+cli+"' and tipo_usu='Cliente'");
 // rs.first();
?>
 
  <table align="center" cellspacing="1" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;top:0px; width:21.5cm; height:27.9cm;">
    <tr>
    
      <td height="80" colspan="5" align="center">
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
      <tr><td width="150px"> <div align="center"><?php echo $logo_fac_pos ?></div></td>
      <td align="left">

<?php echo $PUBLICIDAD ?>

 </td>
 <td><p align="center"><b>C&oacute;digo</b><br />

 <span style="color:#F00; font-size:18px"><strong></strong>&nbsp;<?php echo $num_fac?><strong></strong></span>
 </p>
 <br />
 
 
 </td></tr>
 </table>
 
 </td>
    </tr>
    
    <tr>
      <td colspan="5">
      <table cellpadding="5px" frame="box"  width="100%" style="-webkit-border-radius:10px;-moz-border-radius:10px">
      <tr>
                      

            <td width="61" colspan="1"  >&nbsp;</td>
            
            <td colspan="4" align="center" style="font-size:24px">DATOS DEL CLIENTE</td>
          
           
            <td  align="center"></td>
          </tr>
     
          
          <tr>
            <td width="50px">Nombres:</td>
            <td ><?php echo $nom_cli?></td>
            
            <td >C.C./NIT:</td>
            <td ><?php echo $id_cli?></td>
            <td align="right">Expedida en:</td>
            <td colspan="2"><?php echo $expedicion_cli ?></td>
          </tr>
          <tr>
            <td >Direcci&oacute;n :</td>
            <td ><?php echo $dir_cli?></td>
            <td >&nbsp;Tel&eacute;fono :</td>
            <td><?php echo $tel_cli?></td>
            <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td colspan="2">&nbsp;</td>
          </tr>
          
          <tr>
            <td>Ciudad:</td><td colspan="5"><?php echo $ciudad_cli ?></td></tr>

<?php 

?>

          
        </table>
        </td>
    </tr>
  
  <!--  
    <tr>
      <td colspan="5">
      <table cellpadding="5px" frame="box"  width="100%" style="-webkit-border-radius:10px;-moz-border-radius:10px">
      <tr>
                      

            <td width="61" colspan="1"  ></td>
            
            <td colspan="4" align="center" style="font-size:24px">DATOS DEL CODEUDOR</td>
          
           
            <td  align="center"></td>
          </tr>
     
          
          <tr>
            <td width="50px">Nombres:</td>
            <td ><input type="text" name="nom2" value="<?php ?>" /></td>
            
            <td >C.C./NIT:</td>
            <td ><input type="text" name="ced2" value="<?php ?>" />  </td>
            <td align="right">Expedida en:</td>
            <td colspan="2"><input type="text" name="exp2" value="" /></td>
          </tr>
          <tr>
            <td >Direcci&oacute;n Casa:</td>
            <td ><input type="text" name="dir2a" value="<?php ?>" /></td>
            <td >&nbsp;Tel&eacute;fono Casa:</td>
            <td><input type="text" name="tel2a" value="<?php ?>" /></td>
            <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td >Direcci&oacute;n Oficina:</td>
            <td ><input type="text" name="dir2b" value="" /></td>
            <td >&nbsp;Tel&eacute;fono Oficina:</td>
            <td><input type="text" name="tel2b" value="" /></td>
            <td >&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td colspan="2">&nbsp;</td>
          </tr>
-->

<?php 
?>

          
     
    <tr>
    <td colspan="5">
    <table height="400px"  width="100%" cellspacing="0" cellpadding="0" frame="box"  style="border-width:1px;-webkit-border-radius:10px;-moz-border-radius:10px" >
    <tr>
    <th align="center" colspan="5">DATOS DE LA FACTURA</th>
    </tr>
    <tr>
    <td><span style="color:red">No.Factura:</span></td><td><span style="color:red"><?php echo "$prefijo - $num_fac" ?></span></td><td>Fecha Factura:</td><td><?php echo fecha($fecha)?></td>
    </tr>
    <tr>
    <td>Valor Factura:</td><td><?php echo money($val_fac)?></td><td></td><td></td>
    </tr>
    
    
     <?php 
 
 
 ?>

    <tr>
    <th  colspan="5"> VALORES, FECHAS Y PLAZOS</th>
    </tr>
    <tr>
    <td>Fecha entrega:</td><td><?php echo fecha($fecha)?></td><td>Valor Cr&eacute;dito:</td><td><?php echo money($val_cre) ?></td> 
    </tr>
    
    
    
 <?php 

$rs=$linkPDO->query("SELECT * FROM fechas_pago WHERE num_fac=$num_fac AND cod_su=$codSuc order by fecha");
if($row=$rs->fetch()){

 ?>

   <td width="100%" colspan="5" >
   <table width="100%">
    <tr >
   <td >Fecha Cuota Inicial: </td>
   <td><?php echo fecha($row['fecha']) ?></td>
   <td>Valor Cuota Inicial: </td><td><?php echo money($row['v_cuota']) ?></td></tr>

   <tr>
   <?php
   
}
$con=1;
    while($row=$rs->fetch()){
   ?>
   
  <tr><td > Fecha <?php echo $con ?>:</td><td><?php echo fecha($row['fecha']) ?></td><td>&nbsp;&nbsp;Cuota <?php echo $con ?>:</td><td><?php echo money($row['v_cuota']) ?></td></tr>"
   
   <?php
   $con++;
	}
   ?>
   </table>
   
    </td></tr>
    <tr>
      <td colspan="5" width="100%" align="center">Valor Cr&eacute;dito a Diferir:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo money($val_dif) ?>
<input id="v_difH" type="hidden" name="v_diferir" value="0"></td></tr>
    <tr><td colspan="5" width="100%" height="100%"></td></tr>
    </table></td></tr>
    <?php //
//$hora=rs.getString("hora");
}

?>
<tr>
    <th colspan="5" align="center">CONTROL DE FECHAS Y VALORES POR PAGAR
</th></tr>
    
<tr>
    <td colspan="5" align="center">
    
    <table width="100%"  cellspacing="0" cellpadding="0" frame="box"  style="border-width:1px;-webkit-border-radius:10px;-moz-border-radius:10px" >
    <tr><td align="center">
    <table  border="0" rules="rows" cellpadding="0px" cellspacing="0px" width="65%">
    <tr>
    <td><b>Cuota No.</b></td><td><b>Fecha Pago</b></td>
    <td><b>Saldo Obligaci&oacute;n</b></td>
    <td><b>Valor Cuota</b></td>
    </tr>
    
    <?php
	$dim1=$con;
	$dim3=1;
	$dim4=$dim1;
	$SaldoOb=$val_cre;
	
$rs=$linkPDO->query("SELECT * FROM fechas_pago WHERE num_fac=$num_fac AND cod_su=$codSuc order by fecha");
if($row=$rs->fetch())
{
  
  echo "<tr><td align=\"center\">1</td><td>".fecha($row["fecha"])."</td><td>".money($SaldoOb)."</td><td>".money($row["v_cuota"])."</td></tr>";
  $SaldoOb=$SaldoOb-$row["v_cuota"];
  while($row=$rs->fetch()){
	  $dim3++;
	  $dim4++;
	  
	  echo "<tr><td align=center>".$dim3."</td><td>".fecha($row["fecha"])."</td><td id=\"vu".$dim4."\">".money($SaldoOb)."</td><td>".money($row["v_cuota"])."</td></tr>";
	  $SaldoOb=$SaldoOb-$row["v_cuota"];
	  
	  
	  }
	  
  }



  ?> 

    </table>
    </td></tr>
    </table>
    </td>
</tr>
    

<tr>
    
      <td  colspan="5" align="center" >
<table width="100%" cellspacing="5px" cellpadding="2px" frame="box" style="border-width:1px;-webkit-border-radius:10px;-moz-border-radius:10px">
<tr>      
<td align="center">Plazo: <?php echo $dim1?> Cuotas <?php echo $row["plazo"] ?> en las fechas indicadas</td>
</tr>      
</table></td></tr>

<tr>
    
      <td  colspan="5" align="center" height="100%">
      <table width="100%" height="100%">

    <tr><td height="100%" ></td></tr>
    <tr >
    <td align="center">_________________________________<br><?php echo $nom_cli ?><br>Cliente</td>
   <!-- <td align="center">_________________________________<br><%=rs.getString("nom_co")%><br>Codeudor</td>-->
          </tr>
    
    </table></td></tr>
  </table><input type="hidden" value="" name="boton" id="genera"/>
  
</form>
 <div id="imp" align="center" style="position:absolute; top:953px; left:463px; border-style: groove; border-color:#F00">
    <input type="button" value="VOLVER" onClick="javascript:location.assign('creditos.php?pag=<?php if(isset($_SESSION['pag']))echo $_SESSION['pag'] ?>');" />
    <input  type="button" value="IMPRIMIR" name="boton"  onClick="imprimir()"/>
    
    <input name="hid" type="hidden" value="<%=dim4%>" id="Nart" />
  </div>
</body>
</html>
