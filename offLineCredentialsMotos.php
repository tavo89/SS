<?php

$usu = 'admin';
$cla = '123';

$qry="SELECT * FROM tipo_usu INNER JOIN usu_login ON usu_login.id_usu=tipo_usu.id_usu WHERE usu='$usu' AND cla='$cla'";
$rs=$linkPDO->query($qry);

if($row = $rs->fetch()) {
    $id=$row['id_usu'];
	$tipoUsu=$row['des'];
	$lv=$row['rol_lv'];
	$_SESSION['tipo_usu']=$tipoUsu;
	$_SESSION['id_usu']=$id;
	$_SESSION['rol_lv']=$lv;

    $qry="SELECT sucursal.*,departamento.departamento,municipio.municipio,usuarios.nombre,usuarios.tel,usuarios.cod_caja as cc 
        FROM usuarios,sucursal,departamento,municipio 
        WHERE usuarios.id_usu='$id' AND usuarios.cod_su=sucursal.cod_su 
        AND sucursal.id_dep=departamento.id_dep AND sucursal.id_mun=municipio.id_mun";
    $rs=$linkPDO->query($qry);
    if($row = $rs->fetch()) {
        $_SESSION['enviar_mail_firma_ot']=$row['enviar_mail_firma_ot'];	
        $_SESSION['factor_hero']=$row['factor_hero'];
        $_SESSION['factor_hero_tecnico']=$row['factor_hero_tecnico'];
        $_SESSION["see_warn_resol"]=1;
        $_SESSION['alas']=$row['alas'];
        $_SESSION['cod_contado']=$row['cod_contado'];
        $_SESSION['cod_credito']=$row['cod_credito'];
        $_SESSION['ResolContado']=$row['resol_contado'];
        $_SESSION['FechaResolContado']=$row['fecha_resol_contado'];
        $_SESSION['RangoContado']=$row['rango_contado'];
        $_SESSION['RangoCredito']=$row['rango_credito'];
        $_SESSION['ResolCredito']=$row['resol_credito'];
        $_SESSION['FechaResolCredito']=$row['fecha_resol_credito'];
        $_SESSION['municipio']=$row['municipio'];
        $_SESSION['departamento']=$row['departamento'];
        //$_SESSION['NvAdm']="1"; 
        $_SESSION['cod_su']=$row['cod_su'];
        $_SESSION['nom_su']=$row['nombre_su'];
        $_SESSION['dir_su']=$row['dir_su'];
        $_SESSION['tel1_su']=$row['tel1'];
        $_SESSION['tel2_su']=$row['tel2'];
        $_SESSION['representante_su']=$row['representante_se'];
        $_SESSION['nom_usu']=mb_strtoupper($row['nombre'],'utf-8');
        $_SESSION['tel_usu']=$row['tel'];
        $_SESSION['order']="1";
        $_SESSION['ipClient']=$ipClient;
        $_SESSION['cod_caja']=$row['cc'];

        $sql="SELECT * FROM x_config WHERE cod_su=1";
        $rs=$linkPDO->query($sql);

        while($row=$rs->fetch()){
        $_SESSION[$row["des_config"]]=$row["val"];	
        }

        $useragent=$_SERVER['HTTP_USER_AGENT'];

    }
}