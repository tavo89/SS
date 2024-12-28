<?php
include_once("facturaElectronica.class.php");

try {
// datos factura
$datosFactura = getFacturaData();
if($datosFactura['estadoDian']!=1){

    $numFactura = formatoNumFactura(prefijo, num_fac);

    $respConsultaFac =consultaIdFacDian($numFactura);

    $idFactura = $respConsultaFac['invoice']['uuid'];
    if(!empty($idFactura)){
        
        $url ='https://api.dataico.com/direct/dataico_api/v2/invoices/'.$idFactura;
        $data = array('actions'=>array('send_dian'=>true));
        $respuesta=reEnviarFacturaOmail($url,$data, true);

        if($respuesta['dian_status']=='DIAN_ACEPTADO'){
          $params = array(
            'estadoFac'=>'1',
            'dian_status'=>$respuesta['dian_status'],
            'cufe'=>$respuesta['cufe'],
            'numeroFac'=>num_fac,
            'prefijoFac'=>prefijo,
            'sucursal'=>codSuc
          );
          actualizaEstadoFactura($params);
          $respuestaEnvioCorreo=reEnviaCorreo($datosFactura['correoCliente'], $idFactura);

          $resp = array('message'=>"Factura $numFactura se Envio satisfactoriamente",
                  'success'=>1,
                  'Error'=>'');
        }

        
    }


    $resp = json_encode($resp);
    echo $resp;
}
} catch (Exception $e) {
                         echo 'ERROR:  ' . $e->getMessage();
                    }
?>