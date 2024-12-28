<?php
include_once("facturaElectronica.class.php");

try {
// datos factura
$datosFactura = getFacturaData();


    $numFactura = formatoNumFactura(prefijo, num_fac);
    $respConsultaFac =consultaIdFacDian($numFactura);
    $idFactura = $respConsultaFac['invoice']['uuid'];
    if(!empty($idFactura)){
        if(!empty($datosFactura['correoCliente'])){
        $respuestaEnvioCorreo=reEnviaCorreo($datosFactura['correoCliente'], $idFactura);

        if($respuestaEnvioCorreo['email_status']=='CORREO_ENVIADO')
        $resp = array('message'=>"Coreo enviado correctamente a : ".$datosFactura['correoCliente'],
                  'success'=>1,
                  'Error'=>'');
        $resp = json_encode($resp);
        } else {
            $resp = array('message'=>"Coreo INCORRECTO, revise su formato : ".$datosFactura['correoCliente'],
                  'success'=>0,
                  'Error'=>'');
            $resp = json_encode($resp);
        }
    }


    



echo $resp;
} catch (Exception $e) {
                         echo 'ERROR:  ' . $e->getMessage();
                    }
?>