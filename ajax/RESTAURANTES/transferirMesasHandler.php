<?php
include("../../Conexxx.php");

$idMesaOrigen      = r("idMesaOrigen");
$idMesaAtransferir = r('idMesaAtransferir');

$update="UPDATE mesas mUpdate INNER JOIN mesas mOrigin 
         ON     mOrigin.id_mesa     ='$idMesaOrigen' AND mUpdate.id_mesa='$idMesaAtransferir'
         SET    mUpdate.num_fac_ven = mOrigin.num_fac_ven,
                mUpdate.prefijo     = mOrigin.prefijo,
                mUpdate.hash        = mOrigin.hash,
                mUpdate.estado      = 'Ocupada',
                mUpdate.valor=      mOrigin.valor,
                mOrigin.num_fac_ven = '',
                mOrigin.prefijo     = '',
                mOrigin.hash        = '',
                mOrigin.estado      = 'Disponible',
                mOrigin.valor       =0 
         WHERE  mUpdate.id_mesa='$idMesaAtransferir'";

t1($update);

echo "1";


