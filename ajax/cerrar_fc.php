<?php
require_once("../Conexxx.php");
$num_facH=0;
$nit_proH="";

/*
if(isset($_SESSION['num_fac'])){$num_fac=$_SESSION['num_fac'];$num_facH=$_SESSION['num_fac'];}
if(isset($_SESSION['nit_pro'])){$nit_pro=$_SESSION['nit_pro'];$nit_proH=$_SESSION['nit_pro'];}
*/


$num_fac=r('num_fac');
$nit_pro=r('nit');


$confirm=r('confirma');
cerrar_fc($num_fac,$nit_pro,$codSuc);


?>