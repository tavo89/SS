<?php
include('Conexxx.php');

$num_fac=r('n_fac_ven');
$pre=r('prefijo');
$boton="";
$IVA=0;
$SUBTOT=0;
$TOT=0;




if(isset($_REQUEST['boton'])){
//$num_fac=limpiarcampo($_REQUEST["num_fac"]);

$boton=limpiarcampo($_REQUEST["boton"]);
}
//out.print(" nc:"+nc);
$maxDay[]=0;
$maxDay[0]=31;$maxDay[1]=28;$maxDay[2]=31;$maxDay[3]=30;$maxDay[4]=31;
$maxDay[5]=30;$maxDay[6]=31;$maxDay[7]=31;$maxDay[8]=30;$maxDay[9]=31;
$maxDay[10]=30;$maxDay[11]=31;
//$sql="UPDATE fac_venta SET tot=$val_cre, porcentaje_cre=$interes WHERE num_fac_ven=$num_fac AND nit=$codSuc";
//echo "<br>".$sql;
if($boton=="GUARDAR"&&isset($_REQUEST['boton']))
{
$nom1=limpiarcampo($_REQUEST["nom1"]);
$ced1=limpiarcampo($_REQUEST["ced1"]);
$exp1=limpiarcampo($_REQUEST["exp1"]);
$tel1a=limpiarcampo($_REQUEST["tel1a"]);
$dir1a=limpiarcampo($_REQUEST["dir1a"]);
//$entidad=limpiarcampo($_REQUEST["entidad"]);


$v_fac=limpiarcampo($_REQUEST["val_fac"]);
$fe_fac=limpiarcampo($_REQUEST["fechafac"]);

$fe_entrega=limpiarcampo($_REQUEST["fe_entrega"]);
$val_cre=limpiarcampo($_REQUEST["val_cre"]);
$fe_ini=limpiarcampo($_REQUEST["fe_entrega0"]);
$cu_ini=limpiarcampo(quitacom($_REQUEST["val_cuota0"]));
$valDif=limpiarcampo($_REQUEST["v_diferir"]);
$plazos=limpiarcampo($_REQUEST["plazos"]);
$nr=limpiarcampo($_REQUEST["nr"]);
$nc=limpiarcampo($_REQUEST["num_c"]);
$ciudad=limpiarcampo($_REQUEST["ciudad"]);
$interes=limpiarcampo($_REQUEST["interes"]);
$tipoCre=limpiarcampo($_REQUEST['tipo_cre']);


try { 
$linkPDO->beginTransaction();
$all_query_ok=true;

$sql="insert into plan_amortizacion values('".$ced1."','".$nom1."','".$exp1."','".$dir1a."','".$tel1a."','".$fe_fac."',".$v_fac.",".$valDif.",'".$plazos."',".$num_fac.",".$val_cre.",".$nc.",0,".$num_fac.",$codSuc,'PENDIENTE','$ciudad','$codCreditoSuc');";
//echo "<br>".$sql;
$linkPDO->exec($sql);


$sql="UPDATE art_fac_ven SET sub_tot=ROUND((sub_tot + sub_tot*($interes/100)),-1), precio=ROUND((precio + precio*($interes/100) ),-1) WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$codSuc";
//echo "<br>".$sql;
$linkPDO->exec($sql);




$sql="UPDATE serv_fac_ven SET precio=ROUND((precio + precio*($interes/100)),-1), precio_iva=ROUND((precio_iva + precio_iva*($interes/100) ),-1) WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$codSuc AND man!='Inspeccion' AND revision=0";
//echo "<br>".$sql;
$linkPDO->exec($sql);

totFacVen($num_fac,$pre,$codSuc);

if($tipoCre!="FANALCA")
{
$sql="UPDATE fac_venta SET tot=$TOT, porcentaje_cre=$interes, iva=$IVA,sub_tot=$SUBTOT WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$codSuc";
//echo "<br>".$sql;
$linkPDO->exec($sql);



$sql="UPDATE plan_amortizacion SET val_cre=$TOT WHERE codigo=$num_fac AND cod_su=$codSuc AND pre='$codCreditoSuc'";
$linkPDO->exec($sql);
}


$excedente=$TOT-$val_cre;
$cu_ini+=$excedente;
$sql="insert into fechas_pago values($num_fac,'$fe_fac',$cu_ini,'',0,$num_fac,$codSuc,'$codCreditoSuc');";
//echo "<br><b>$qry</b>";
$linkPDO->exec($sql);

	


if($nr!=0&&!empty($nr)&&isset($nr))
	{
		
		$n=$nr;
		for($i=1;$i<=$n;$i++)
		{
			
			//echo "<br><b>fecha cuota$fe_cu</b>";
			if(isset($_REQUEST["fe_entrega".$i])&&!empty($_REQUEST["fe_entrega".$i]))
			{
				$fe_cu=r("fe_entrega".$i);
				$vc=r("val_cuota".$i);
				$sql="insert into fechas_pago values($num_fac,'$fe_cu',$vc,'',0,$num_fac,$codSuc,'$codCreditoSuc');";
				//echo "<br><b>$qry</b>";
$linkPDO->exec($sql);
			}
		}
		//out.print(qry);
		
	}
else if($plazos=="Diarias")
{
	
$feini[]=fe_fac.split("-");
//nc="10";
//out.print(Integer.valueOf(fff[1]));
if($isset($nc)&&!empty($nc))
{
$num=$nc;
$a=$feini[0];
$m=$feini[1];
$d=$feini[2];
$dd=$d;
for($i=0;$i<$num;$i++)
{
	
	if($a%4==0){$maxDay[1]=29;}else {$maxDay[1]=28;}
	$dd++;
	if($dd>$maxDay[$m-1]){$dd=$dd-$maxDay[$m-1];$m++;}
	if($m>12){$a++;$m=1;}
	$f=$a."-".$m."-".$dd;
	$qry=$qry."insert into fechas_pago values(".$num_nota.",'".$f."',".$valCD.",'',0,0,'$codCreditoSuc');";
	}
}
	
	}
	
$linkPDO->commit();

if($all_query_ok){
		$_SESSION['cod_plan']=$num_fac;
eco_alert("Operaccion Exitosa");
js_location("imp_plan_amor.php");
}
else{eco_alert("ERROR! Intente nuevamente");}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

	 
	
	
	}




?>
<html  >
<head>
<?php require_once("IMP_HEADER.php"); ?>
<link href="JS/fac_ven.css" rel="stylesheet" type="text/css" />
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
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
	if(esVacio(frm.exp1.value)){simplePopUp('Ingrese lugar de Expedicion del Documento');frm.exp1.focus();}
	else if(esVacio(frm.tipo_cre.value)){simplePopUp('Especifique el Tipo de Credito!');frm.tipo_cre.focus();}
	else if(esVacio(frm.ciudad.value)){simplePopUp('Ingrese la Ciudad!');frm.ciudad.focus();}
	else if(esVacio(frm.val_cuota0.value)){simplePopUp('Ingrese la cuota Inicial!');frm.val_cuota0.focus();}
	else {frm.submit();}

	
	
}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Plan Amortizaci&oacute;n</title>

</head>
<body>
<form action="plan_amortizacion.php" name="form1" method="post">
  <?php 
  
$rs=$linkPDO->query("select *,DATE(fecha) as fecha from fac_venta where num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$codSuc");

//$cli=rs.getString("id_usu");
//$co=rs.getString("id_co");

if($row=$rs->fetch()){
$fecha=$row["fecha"];
// rs=con.consultar("select * from usuario where id_usu='"+cli+"' and tipo_usu='Cliente'");
// rs.first();
?>
  <table align="center" cellspacing="1" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
    <tr>
    
      <td height="80" colspan="5" align="center">
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
      <tr><td width="150px"> <div align="center">
<img src="<?php echo $url_LOGO_A ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>">
</div></td>
      <td align="left"><?php  echo $PUBLICIDAD ?></td></tr>
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
            <td ><input type="text" name="nom1" value="<?php echo $row["nom_cli"] ?>" /></td>
            
            <td >C.C./NIT:</td>
            <td ><input type="text" name="ced1" value="<?php echo $row["id_cli"]?>" />  </td>
            <td align="right">Expedida en:</td>
            <td colspan="2"><input type="text" name="exp1" value="" /></td>
          </tr>
          <tr>
            <td >Direcci&oacute;n :</td>
            <td ><input type="text" name="dir1a" value="<?php echo $row["dir"]?>" /></td>
            <td >&nbsp;Tel&eacute;fono :</td>
            <td><input type="text" name="tel1a" value="<?php echo $row["tel_cli"]?>" /></td>
            <td align="right">Tipo Credito:</td>
            <td colspan="2"><select name="tipo_cre" id="tipo_credito" onChange="fe_fanalca(this);creditos($('#intereses'),$(this),$('#cantC'));">
            <option value="" selected></option>
            <option value="Empleados">Empleados</option>
            <option value="Independientes">Independientes</option>
            <option value="FANALCA">FANALCA</option>
            </select></td>
            
          </tr>
          
          <tr>
            <td>Ciudad:</td><td colspan="5"><input type="text" name="ciudad" value="<?php echo $munSuc?>" ></td></tr>

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
    <td><span style="color:red">No.Factura:</span></td><td><input readonly type="text" name="num_fac" value="<?php echo $row["num_fac_ven"]?>" /></td><td>Fecha Factura:</td><td><input readonly type="text" name="fechafac" id="fe_fac" value="<?php echo $row["fecha"]?>" /></td>
    </tr>
    <tr>
    <td>Valor Factura:</td><td><input readonly id="val_tot" type="text" value="<?php echo money($row["tot"])?>" /><input id="val_totH" type="hidden" name="val_fac" value="<?php echo $row["tot"]?>" /></td><td></td><td></td>
    </tr>
    <tr>
    <th  colspan="5"> VALORES, FECHAS Y PLAZOS</th>
    </tr>
    <tr>
    <td>Fecha entrega:</td><td><input readonly type="text" name="fe_entrega" value="<?php echo $row["fecha"]?>" id="fe_entrega" /></td><td>Valor Cr&eacute;dito:</td><td><input readonly id="val_credito" type="text" value="<?php echo money($row["tot"])?>" /><input id="val_creditoH" type="hidden" name="val_cre" value="<?php echo $row["tot"]?>" />
<input id="intereses" type="text" name="interes" placeholder="%" value="0" size="5" style="width:30px;" onChange="int(this.value)" onBlur="nanC($(this));" readonly></td> 
    </tr>
 <tr >
   <td >Fecha Cuota Inicial: </td>
   <td><input readonly type="text" name="fe_entrega0" value="<?php echo $row["fecha"]?>" id="fe_entrega0" onClick="popUpCalendar(this, form1.fe_entrega0, 'yyyy-mm-dd');"/></td><td>Valor Cuota Inicial: </td><td><input type="text" id="val_cu0" value="" name="val_cuota0" onBlur="nanC($(this));" onkeyUp="puntoa($(this));val_cuotas();fechas();" /><img style=" top:5px; position:relative; visibility: hidden" onMouseUp="eli($(this).attr('class'))" class="1" src="../Imagenes/iconos/delete.png" width="20px" heigth="20px"></td></tr>



   <tr><td width="100%" colspan="5" id="fechas-plazos">
   
   
    </td></tr>
    <tr>
      <td colspan="5" width="100%" align="center">Valor Cr&eacute;dito a Diferir:&nbsp;&nbsp;&nbsp;&nbsp;<input readonly id="v_dif" type="text"  value="" onkeyUp="puntoa($(this))">
        <input id="v_difH" type="hidden" name="v_diferir" value="0"></td></tr>
    <tr><td colspan="5" width="100%" height="100%"></td></tr>
    </table></td></tr>
    <?php //
//$hora=rs.getString("hora");
}

?>
<tr>
    
      <td  colspan="5" align="center">
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">


    <tr >
    <td align="center"><select name="plazos" id="plazos" onChange="cambio($('#cantC').val())">
    <option value="Semanales">Semanales</option>
    <option value="Quincenales">Quincenales</option>
    <option value="Mensuales" selected>Mensuales</option>
    </select>
    <input type="text" value="1" size="5"  id="cantC" name="num_c"><input id="add_fe_btn" type="button" onClick="addFe($('#cantC').val())" value="+"><input type="hidden" name="nr" value="1" id="nr"></td>
          </tr>
    
    </table></td></tr>
  </table><input type="hidden" value="" name="boton" id="genera"/>
  
</form>
<div id="imp" align="center">
    <input type="button" value="VOLVER" onClick="javascript:location.assign('creditos.php');" />
    <input  type="button" value="GUARDAR" name="boton" onClick="submitt('GUARDAR','genera',document.forms['form1']);" />
    
  </div>
</body>
</html>
