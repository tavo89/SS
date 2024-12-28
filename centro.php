<?php
include_once("Conexxx.php");
valida_session();



$boton="";
$check="";

$SUBTOT=0;
$IVA=0;
$TOT=0;
$num_fac=0;
$i=0;
$facturas[]=0;
$prefijos[]=0;



if(isset($_REQUEST['boton'])&&!empty($_REQUEST['boton']))$boton=$_REQUEST['boton'];
if($boton=="Cerrar Caja")
{
	$sql="SELECT cajas.cod_caja as cod, estado_caja, estado_caja,usu,id_usu FROM cajas,cajasb WHERE fecha=CURRENT_DATE() AND cajas.cod_su=$codSuc AND cajas.cod_caja=cajasb.cod_caja";
	$rs=$linkPDO->query($sql);
    if($row=$rs->fetch())
	{
	 $cod_caja=$row['cod']; 
	 $estado_caja=$row['estado_caja'];
	 $usu=$row['usu'];
	 $idUSU=$row['id_usu'];
	 if($estado_caja=="ABIERTA"){
	
	 $sql="UPDATE cajas SET estado_caja='CERRADA' WHERE fecha=CURRENT_DATE() AND cod_su=$codSuc AND cod_caja=$cod_caja";
	 $linkPDO->exec($sql);
	 
	 $sql="UPDATE cajasb SET cierre=CURRENT_TIMESTAMP(), costos_fin=(SELECT IFNULL(SUM(exist * (costo*(iva/100) + (costo))),0) as TOTcosto FROM inv_inter WHERE nit_scs=$codSuc), costo_bruto_fin=(SELECT IFNULL(SUM(exist *costo),0) as TOTcosto FROM inv_inter WHERE nit_scs=$codSuc) WHERE cod_su=$codSuc AND cod_caja=$cod_caja";
	 $linkPDO->exec($sql);
	 }
	}
	
$_SESSION['fechaI']=$FechaHoy;
	$_SESSION['fechaF']=$FechaHoy;
	

}
if($boton=="Abrir Caja")
{
	$cod_caja=0;
	$estado_caja="";
	if($hora<$horaApertura && $rolLv!=$Adminlvl){eco_alert("Hora Inferior a la permitida ($horaApertura), Intente mas Tarde!");}
	else if($hora>$horaCierre && $rolLv!=$Adminlvl){eco_alert("Hora Sobrepasa la permitida ($horaCierre) !");}
	else{
	$sql="SELECT * FROM cajas WHERE fecha=CURRENT_DATE() AND cod_su=$codSuc";
	$rs=$linkPDO->query($sql);
    if($row=$rs->fetch())
	{
	 $cod_caja=$row['cod_caja']; 
	 $estado_caja=$row['estado_caja'];
	 
	 if($estado_caja=="CERRADA"&& $rolLv==$Adminlvl){
	 $sql="UPDATE cajas SET estado_caja='ABIERTA' WHERE fecha=CURRENT_DATE() AND cod_su=$codSuc AND cod_caja=$cod_caja";
	 $linkPDO->exec($sql);
	 
	
	 
	 $sql="SELECT * FROM cajasb WHERE cod_caja=$cod_caja AND cod_su=$codSuc";
	 $rs=$linkPDO->query($sql);
     if($row=$rs->fetch())
	 {
		 //$sql="UPDATE cajasb SET ";
	 }
	 else 
	 {
		 $sql="INSERT INTO cajasb(cod_caja,usu,id_usu,cod_su,inicio,costos_ini,costo_bruto_ini) VALUES($cod_caja,'$nomUsu','$id_Usu',$codSuc,CURRENT_TIMESTAMP(),(SELECT (SELECT IFNULL(SUM(exist * (costo*(iva/100) + (costo))),0) as TOTcosto FROM inv_inter WHERE nit_scs=$codSuc),(SELECT (SELECT IFNULL(SUM(exist*costo),0) as TOTcosto FROM inv_inter WHERE nit_scs=$codSuc))";
		 echo $sql;
	     $linkPDO->exec($sql); 
	 }
	 
	 
	 }//if CERRADA
	 
	 else{
		eco_alert("PETICION DENEGADA, CONTACTE SU ADMINISTRADOR"); 
	 }
	}//IF $RS
	else 
	{
		$sql="INSERT INTO  cajas(fecha,cod_su,estado_caja) VALUES(CURRENT_DATE(),$codSuc,'ABIERTA')";
	    $linkPDO->exec($sql);
		
		$sql="SELECT * FROM cajas WHERE fecha=CURRENT_DATE() AND cod_su=$codSuc";
	    $rs=$linkPDO->query($sql);
        if($row=$rs->fetch())
	    {
	     $cod_caja=$row['cod_caja']; 
	     $estado_caja=$row['estado_caja'];
		// $sql="INSERT INTO cajasb(cod_caja,usu,id_usu,cod_su,inicio,costos_ini) VALUES($cod_caja,'$nomUsu','$id_Usu',$codSuc,CURRENT_TIMESTAMP(),(SELECT (SELECT SUM(exist * (costo*(iva/100) + (costo))) as TOTcosto FROM inv_inter WHERE nit_scs=$codSuc))";
		 $sql="INSERT INTO cajasb(cod_caja,usu,id_usu,cod_su,inicio,costos_ini,costo_bruto_ini) VALUES($cod_caja,'$nomUsu','$id_Usu',$codSuc,CURRENT_TIMESTAMP(),(SELECT (SELECT IFNULL(SUM(exist * (costo*(iva/100) + (costo))),0) as TOTcosto FROM inv_inter WHERE nit_scs=$codSuc)),(SELECT (SELECT IFNULL(SUM(exist*costo ),0) as TOTcosto FROM inv_inter WHERE nit_scs=$codSuc)))";
		 //$sql="INSERT INTO cajasb(cod_caja,usu,id_usu,cod_su,inicio) VALUES($cod_caja,'$nomUsu','$id_Usu',$codSuc,CURRENT_TIMESTAMP())";
		
		 //$check=$sql;
	     $linkPDO->exec($sql);
		 
		 //echo "<span style=\"color:#fff\" >$sql</span>";
	    }
	}
	
	
	}
}
//echo "<span style=\"color:#fff\" >sql: $check</span>";
?>


<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
    <?php include_once("HEADER.php"); ?>
    <script language="javascript1.5" type="text/javascript">
    var hora = '<?php echo $hora ?>';
    var horaCierre = '<?php echo $horaCierre ?>';
    </script>
</head>

<body>
    <div class="container">
        <!-- Push Wrapper -->
        <div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
            <?php include_once("boton_menu.php"); ?>
            <form name="frm" action="centro.php" method="post">
                <BR /><BR /><BR />
                <div align="center"><img src="<?php echo $url_LOGO_A ?>" style="height:30%;" />
                </div>

                <!--
<video width="320" height="240" controls>
 
  <source src="vids/t01.webm" type="video/webM">
  
Your browser does not support the video tag.
</video>
-->
                <center>
                    <h1 style="font-family:'Lucida Console', Monaco, monospace; color:#CCC">BIENVENIDO</h1>
                    <BR /><BR /><BR />

                    <?php

if(val_vencidos() && $usar_fecha_vencimiento==1){
?>

                    <div class="uk-alert uk-alert-warning" data-uk-alert>
                        <a href="" class="uk-alert-close uk-close"></a>
                        <p>
                            <a style="color:#000;" href="<?php echo "inventario_inicial.php?filtroVencidos=1" ?>">HAY
                                PRODUCTOS APUNTO DE VENCER &nbsp;&nbsp;&nbsp;<i
                                    class="uk-icon-info <?php echo $uikitIconSize ?>"></i>&nbsp;</a>

                        </p>
                    </div>
                    <?php
}

?>

                    <?php
if(($rolLv==$Adminlvl || val_secc($id_Usu,"caja_centro")) && $codSuc>0)
{
	
//echo "ADMIN!";	

$sql="SELECT * FROM cajas,cajasb WHERE fecha=CURRENT_DATE() AND cajas.cod_su=$codSuc AND cajas.cod_caja=cajasb.cod_caja AND cajas.cod_su=cajasb.cod_su  AND estado_caja='ABIERTA'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	
	?>				
                    <h2>CAJA <span style="color: #0C0">ABIERTA</span> </h2>
                    <div align="center">
                        <?php
		if(($rolLv==$Adminlvl || val_secc($id_Usu,"caja_centro")) && $codSuc>0){
		 ?>
                        <input class=" uk-button-large" type="submit" name="boton" value="Cerrar Caja" />
                        <?php
		 }
		 ?>
                    </div>
                    <h2><?php echo "Hora Servidor: $hora"; ?></h2>
                    <h2><?php echo "Hora de Cierre: $horaCierre"; ?></h2>


                    <?php
}
else 
{
	?>
                    <h2>CAJA <span style="color: #000"><b>CERRADA</b></span> <?php echo "Hora: $hora"; ?> </h2>
                    <div align="center">
                        <?php
     if($hostName=='tavo.nanimosoft.com'){
        $linkClientes = '<a href="SSadminClients.php" target="_blank" class="uk-button uk-button-large">Clientes Smart Selling</a>';

        echo "$linkClientes <br><br><br>";
     }


	 $sql="SELECT * FROM cajas,cajasb WHERE fecha=CURRENT_DATE() AND cajas.cod_su=$codSuc AND cajas.cod_caja=cajasb.cod_caja AND cajas.cod_su=cajasb.cod_su  AND estado_caja='CERRADA'";
     $rs=$linkPDO->query($sql);
    if($row=$rs->fetch())
    {

		if(($rolLv==$Adminlvl || val_secc($id_Usu,"caja_centro")) && $codSuc>0){

            
	  ?>
                        <input class="uk-button-large" type="submit" name="boton" value="Abrir Caja" />
                        
                        <?php
		 }
		 
		 
     }//if rs
     else 
	 {
       
	  ?>
                        <input class="uk-button-large" type="submit" name="boton" value="Abrir Caja" />
                        
                        <?php
	 }

		 ?>
                    </div>
                    <?php
}
?>



                    <?php
}
//echo "$sqlSede";


$sql_once=" ";




$BigSQL="
 

";


?>


                </center>


                <textarea name="SQL" id="SQL" cols="30" rows="10"
                    style="width:400px; visibility:hidden"><?php echo "$BigSQL"; ?></textarea>
            </form>
            <div class="uk-modal-dialog modalDiaSinIVA" id="modalDiaSinIVA">
                <div class="uk-modal-dialog">
                    <a class="uk-modal-close uk-close"></a>

                </div>
            </div>


            
            <?php 
			include_once("FOOTER.php"); 
			include('alertaPagoClienteSS.php');
			?>
            <script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script>
            <script language="javascript1.5" type="text/javascript">
            $(document).ready(function() {
                var modal = UIkit.modal(".modalDiaSinIVA");
                var modalFacturaSS = UIkit.modal(".facturarClienteSS");


                <?php
if($dia_sin_iva==1){
	echo ' UIkit.modal.alert("<span class=\"uk-text-large uk-text-bold uk-text-danger\">D&iacute;a sin IVA activado, las ventas tendran descuento de forma autom&aacute;tica. APLICA SOLO PARA FACTURACI&Oacute;N ELECTR&Oacute;NICA</span><br>Decreto 1314 del 20 de octubre de 2021");';
}else {
	echo 'modal.hide();';
}
?>

            });


            var cont_i = 0;
            var Global_txt = '';

            function sumit($data, separador) {
                var text = $data.val().trim();
                Global_txt = text.split(separador);
                var n = Global_txt.length;
                var i = 0;
                var html = '';

                html =
                    '<h1 class="uk-text-bold uk-text-primary">Actualizando Base de datos</h1><h3 class="uk-text-bold uk-text-warning">Por favor ESPERE</h3>';
                html +=
                    '<div class="uk-progress uk-progress-success"><div id="progress_bar" class="uk-progress-bar" style="width: 0%;">0%</div></div>';


                var modal = UIkit.modal.blockUI(html);
                cola_exe(n, modal);



                //}
            };

            function cola_exe(n, modal) {

                //block_modal('#modal');
                if (1) {
                    var data = ('SQL=' + Global_txt[cont_i]);



                    ajax_x('ajax/MANAGER/ejecon.php', data, function(resp) {
                        var html = '';
                        var $html = '';

                        var r = parseInt(resp);



                        if (r == 1) {
                            html = 'Ok ' + cont_i + ' ' + resp;

                        } else {
                            html = '<span style="color:white; font-size:12px;"><b>' + resp + '</b></span>';
                        }


                        $html = $('<li>' + html + '</li>');
                        //$html.appendTo('#mensaje');


                        cont_i++;

                        var porcentaje = (cont_i / n) * 100;
                        porcentaje = redondeox(porcentaje, 0);
                        $('#progress_bar').css("width", porcentaje + "%").html(porcentaje + "%");
                        if (cont_i < n) {
                            cola_exe(n);
                        } else {
                            waitAndReload();
                        }
                        //hide_pop('#modal');

                    });




                }

            };





            <?php 
$BigSQL="";
if($FechaHoy=="$FECHA_ACTUALIZAR_SW"){


$rs=$linkPDO->query("SELECT * FROM x_updates WHERE fecha_exe='$FECHA_ACTUALIZAR_SW' AND estado='ACTUALIZADO'");
if($row=$rs->fetch()){}
else{


 

?>
            var BigSQL = $('#SQL').val();
            sumit($('#SQL'), ';');
            //alert('BigSQL :'+BigSQL);

            <?php	
	}
}


// WIN   C:/wamp/bin/php/php5.4.12-php5.3.10   C:/wamp/www/SS
// UBUNTU /usr/bin/php5
///public_html/facturacion




?>
            </script>

</body>

</html>