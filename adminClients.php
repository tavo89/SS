<?php
include("DB.php");
$usar_fecha_vencimiento=0;
$fechaKardex="2012-01-01";
include("offline_LIB.php");
 


 
 
?>
<!DOCTYPE html PUBLIC>
<html>
<head>
<?php require_once("HEADER.php"); ?>
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->


<div class="uk-width-9-10 uk-container-center">

<div class=" posicion_form uk-grid uk-contrast">
<form class="uk-form" action="<?php echo $_SERVER['PHP_SELF']; ?>">



    
<?php
$i=0;
$contadorPendientes=0;
$contadorPagos=0;
$contadorInhabilitados=0;
$montoCobrar=0;
$montoCobrarPagos=0;
$montoCobrarInhabilitados=0;
foreach($SUB_DOMINIOS as $index => $val)
{
	
	//&& ($index>=1 && $index<=28)
if($SUB_DOMINIOS[$index]!="127.0.0.1/SS/" ){
echo "<h1>[$index] => $val |".$CREDENCIALES[$val]["db"]."</h1>";

$BaseDatos=$CREDENCIALES[$val]["db"];
$USU=$CREDENCIALES[$val]["usu"];
$CLA=$CREDENCIALES[$val]["cla"];
$HOST="127.0.0.1";
 

$conex = new mysqli("$HOST", "$USU", "$CLA", "$BaseDatos");
if ($conex->connect_errno) {
    echo "<br> Falló la conexión a MySQL[".$CREDENCIALES[$val]["db"]."]: (" . $conex->connect_errno . ") " . $conex->connect_error;
}
/////////////////////////////////////// PDO //////////////////////////////////////////////////////
try {
  $linkPDO = new PDO("mysql:host=$HOST;dbname=".$CREDENCIALES[$val]["db"]."", $USU, $CLA, 
      array(PDO::ATTR_PERSISTENT => 0));
	  $linkPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected\n";
} catch (Exception $e) {
  die("Unable to connect: " . $e->getMessage());
}



$sql="SELECT * FROM x_config WHERE 1=1";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
	
$_SESSION[$row["des_config"]]=$row["val"];		
	
}
//echo "<br>tipo: ".$_SESSION["TIPO_CHUZO"]."<br>";
$usar_fecha_vencimiento=0;
$fechaKardex="2000-05-07";
if($_SESSION["TIPO_CHUZO"]=="DRO"){$usar_fecha_vencimiento=1;}


$sql="SELECT * FROM sucursal ";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
	$i++;
$classPanel = '';
if($row['estado_pago']=='Inhabilitado'){$classPanel = 'uk-block-secondary uk-contrast';}
echo '<fieldset>
<div class="uk-form-row">
<div class="uk-width-small-1-2 uk-width-1-1">      	
<div class="uk-panel uk-panel-box '.$classPanel.'">';
$codSuc=$row['cod_su'];
$nomNegocio=$row['nom_negocio'];

$idEstadoPago = "estadoPago".$i;
$idFechaPago = "fechaPago".$i;
$idValorAnualidad = "valorAnualidad".$i;

$classEstado='';
if($row['estado_pago']=='Pendiente'){
	$contadorPendientes++;
	$montoCobrar+=$row['valor_anual'];
	$classEstado='uk-form-danger';
	}

if($row['estado_pago']=='Pago'){
	$contadorPagos++;
	$montoCobrarPagos+=$row['valor_anual'];
	}
	
if($row['estado_pago']=='Inhabilitado'){
	$contadorInhabilitados++;
	$montoCobrarInhabilitados+=$row['valor_anual'];
	}	
?>
<table class="uk-table">
<thead>
<tr>
<th colspan="4"><?php echo "$nomNegocio [$codSuc] URL: <a href=\"http://".$SUB_DOMINIOS[$index]." \" class='uk-button uk-button-link' target=\"_blank\">Link</a>"; ?></th>
</tr>
<tr>
<th>Estado Pago</th><th>Fecha Ult. Pago</th><th>Valor Anualidad</th>
</tr>
</thead>
<tbody>
        <tr>
            <td>
            <select name="estadoPago" id="<?php echo $idEstadoPago;?>" class="<?php echo $classEstado;?>">
            <option value="Pago" <?php if($row['estado_pago']=='Pago'){echo 'selected';}?> >Pago</option>
            <option value="Pendiente" <?php if($row['estado_pago']=='Pendiente'){echo 'selected';}?>>Pendiente</option>
            <option value="Inhabilitado" <?php if($row['estado_pago']=='Inhabilitado'){echo 'selected';}?>>Inhabilitado</option>
            </select></td>
            <td><input type="date" name="fechaPago" id="<?php echo $idFechaPago;?>" class="" value="<?php echo $row['fecha_pago'];?>"></td>
            <td><input type="text" name="valorAnualidad" id="<?php echo $idValorAnualidad;?>" class="" value="<?php echo money($row['valor_anual']);?>" onKeyUp="puntoa($(this));"></td>
            
        </tr>
        <tr>
        <td colspan="4" align="center">
<input type="button" value="Guardar" class="uk-button uk-button-primary" onClick="saveDataExternal($('#<?php echo $idEstadoPago;?>').val(),$('#<?php echo $idFechaPago;?>').val(),$('#<?php echo $idValorAnualidad;?>').val(),'<?php echo $val;?>','<?php echo $codSuc;?>')">
</td>
        </tr>
    </tbody>
</table>




<?php
 

echo '</div>
</div>
</div>
</fieldset>';

}






//
	}
}
 
?>
<table class="uk-table uk-panel-box-primary" style="width:50%;">
<thead>
<tr>
<th >&nbsp;</th>
<th >Pendiente</th>
<th >Pago</th>
<th >Inhabilitado</th>
</tr>
</thead>
<tbody>
        <tr>
            <td>
            Cantidad
            </td>
            <td><?php echo $contadorPendientes;?></td>
            <td><?php echo $contadorPagos;?></td>
            <td><?php echo $contadorInhabilitados;?></td>
        </tr>
        <tr>
            <td>
            Monto
            </td>
            <td><?php echo money($montoCobrar);?></td>
            <td><?php echo money($montoCobrarPagos);?></td>
            <td><?php echo money($montoCobrarInhabilitados);?></td>
        </tr>
                <tr>
            <td>
            Total Activos
            </td>
            <td><?php echo ($contadorPendientes+$contadorPagos);?></td>
            <td><?php echo money($montoCobrarPagos+$montoCobrar);?></td>
            <td> </td>
        </tr>
</tbody>
</table>
<script src="JS/jquery-2.1.1.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script language="javascript1.5" type="text/javascript" src="JS/UNIVERSALES.js?<?php  echo "$LAST_VER"; ?>" ></script>
<script language="javascript" type="text/javascript">
function saveDataExternal(estadoPago,fechaPago,valorAnualidad,cliente,codSuc)
{
	console.log(estadoPago+', '+fechaPago+', '+valorAnualidad+', '+cliente+', '+codSuc);
	var urlAjax = 'ajax/MANAGER/saveExternalClient.php';
	$.ajax({
		url:urlAjax,
		data:{estadoPago:estadoPago,fechaPago:fechaPago,valorAnualidad:valorAnualidad,cliente:cliente,codSuc:codSuc},
	    type: 'POST',
		dataType:'text',
		success:function(text){
			if(text!=0){
			
			}
			else {}
			
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		});
	
}
</script>
</form>

</div>
</div>
</div>

</body>
</html>