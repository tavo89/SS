<?php

// Datos plan anual
$_SESSION['fecha_pago_plan']=$row['fecha_pago'];
$_SESSION['estado_pago_plan']=$row['estado_pago'];
$_SESSION['valor_anual_plan']=$row['valor_anual'];

// datos DIAN
$_SESSION['resolPosElectronica']=$row['resolPosElectronica'];
$_SESSION['token_dian']=$row['token_dian'];
$_SESSION['usu_dian']=$row['usu_dian'];
$_SESSION['nit_reg_dian']=$row['nit_reg_dian'];
$_SESSION['dataico_account_id']=$row['dataico_account_id'];




$_SESSION['fecha_crea_usu']=$row['fecha_crea'];

// Datos negocio
$_SESSION['nom_negocio']=$row['nom_negocio'];
$_SESSION['firma_cajero']=$row['firma_op'];
$_SESSION['nombre_su']=$row['nombre_su'];
$_SESSION['nit_negocio']=$row['nit_negocio'];
$_SESSION['actividadPrincipal']=$row['actividadPrincipal'];
$_SESSION['formasPagoFacturacion_facVenta']=$row['formasPagoFacturacion_facVenta'];




// Datos Resoluciones Fac.
$_SESSION['FLUJO_INVENTARIO']=1;
$_SESSION['alas']=$row['alas'];
$_SESSION['cod_contado']=$row['cod_contado'];
$_SESSION['cod_credito']=$row['cod_credito'];

$_SESSION['ResolContado']=$row['resol_contado'];
$_SESSION['FechaResolContado']=$row['fecha_resol_contado'];
$_SESSION['RangoContado']=$row['rango_contado'];

$_SESSION['RangoCredito']=$row['rango_credito'];
$_SESSION['ResolCredito']=$row['resol_credito'];
$_SESSION['FechaResolCredito']=$row['fecha_resol_credito'];

// Datos sucursal
$_SESSION['municipio']=$row['municipio'];
$_SESSION['departamento']=$row['departamento'];
$_SESSION['cod_su']=$row['cod_su'];
$_SESSION['nom_su']=$row['nombre_su'];
$_SESSION['dir_su']=$row['dir_su'];
$_SESSION['tel1_su']=$row['tel1'];
$_SESSION['tel2_su']=$row['tel2'];
$_SESSION['representante_su']=$row['representante_se'];
$_SESSION['mail_su']=$row['email_su'];
$_SESSION['nom_usu']=ucwords(strtolower($row['nombre']));
$_SESSION['tel_usu']=$row['tel'];
$_SESSION['order']="1";
$_SESSION['cod_caja']=$row['cc'];

$logoSucursalA = '';
$logoSucursalB = '';
if(!empty($row['url_LOGO_A'])){
    $_SESSION['url_LOGO_A']=$row['url_LOGO_A'];
    $logoSucursalA = $row['url_LOGO_A'];
}
if(!empty($row['url_LOGO_B'])){
    $_SESSION['url_LOGO_B']=$row['url_LOGO_B'];
    $logoSucursalB = $row['url_LOGO_B'];
}