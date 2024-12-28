<?php
session_start();
header("Content-Type: text/html; charset=ISO-8859-1");

include("../../DB.php");


$qry="SELECT * FROM tipo_usu INNER JOIN usu_login ON usu_login.id_usu=tipo_usu.id_usu WHERE usu='admin' ";
//echo $qry;
$rs=$linkPDO->query($qry );
if($row=$rs->fetch()){
	$id=$row['id_usu'];
	$tipoUsu=$row['des'];
	$lv=$row['rol_lv'];
	$_SESSION['tipo_usu']=$tipoUsu;
	$_SESSION['id_usu']=$id;
	$_SESSION['rol_lv']=$lv;
	$_SESSION["see_warn_ban_list"]=1;
	$_SESSION["see_warn_inv"]=1;

$qry="SELECT sucursal.*,departamento.departamento,municipio.municipio,usuarios.nombre,usuarios.tel,usuarios.cod_caja as cc 
FROM usuarios,sucursal,departamento,municipio WHERE usuarios.id_usu='$id' AND usuarios.cod_su=sucursal.cod_su 
AND sucursal.id_dep=departamento.id_dep AND sucursal.id_mun=municipio.id_mun";

$qry="SELECT  sucursal.*,
(SELECT departamento FROM departamento WHERE departamento.id_dep=sucursal.id_dep) AS departamento,
(SELECT municipio FROM municipio WHERE municipio.id_mun=sucursal.id_mun)          AS municipio,
usuarios.nombre,
usuarios.firma_op,usuarios.tel,usuarios.cod_caja as cc,
usuarios.fecha_crea 
FROM usuarios,sucursal
WHERE sucursal.cod_su='$codSuc'
AND usuarios.id_usu='$id'  LIMIT 1";

//echo $qry;
$rs=$linkPDO->query($qry );
if($row=$rs->fetch()){

	include('../../variablesSistema.php');

 
}
 
	
}
else eco_alert("Usuario no encontrado...");

$codSuc=$_SESSION['cod_su'];





//////////////////////////// funcones varias /////////////////////////////
function q_kardex($ref,$codBar,$feVen,$codSuc)
{
	global  $fechaKardex,$usar_fecha_vencimiento;
	
	$ORDER_BY="fe";
	$filtroProducto="cod_barras='$codBar' AND ref='$ref' AND fecha_vencimiento='$feVen'";
	if($usar_fecha_vencimiento==0){$filtroProducto="cod_barras='$codBar' AND ref='$ref'";}
	
	
	
	
	$sql="SELECT 1 as src,a.num_fac_com as num,cod_barras,ref,fecha_vencimiento,des,cant,fraccion,unidades_fraccion,a.iva,costo as precio,a.tot,DATE(f.fecha_mod) fecha,f.fecha as fe FROM art_fac_com a INNER JOIN fac_com f ON a.num_fac_com=f.num_fac_com  WHERE f.estado='CERRADA' AND tipo_fac!='Corte Inventario' AND tipo_fac!='Ajuste Seccion' AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc  and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )
UNION

SELECT 2 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,cant,fraccion,unidades_fraccion,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_ven a INNER JOIN fac_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit and f.anulado!='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )
UNION

SELECT 3 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,cant,fraccion,unidades_fraccion,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli!='Traslado' AND anulado!='FACTURADA' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )
UNION

SELECT 4 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,cant,fraccion,unidades_fraccion,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_ven a INNER JOIN fac_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo AND a.nit=f.nit and f.anulado='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )
UNION

SELECT 5 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,cant,fraccion,unidades_fraccion,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli='Traslado' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )
UNION

SELECT 6 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,cant,fraccion,unidades_fraccion,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli='Traslado' AND estado='Recibido' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and f.sede_destino=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )
UNION


SELECT 7 as src,a.num_fac_com as num,cod_barras,ref,fecha_vencimiento,des,cant,fraccion,unidades_fraccion,a.iva,costo as precio,a.tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_dev a INNER JOIN fac_dev f ON a.num_fac_com=f.num_fac_com  AND anulado!='ANULADO' AND  a.serial_dev=f.serial_fac_dev AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )
UNION

SELECT 8 as src,a.num_ajuste as num,cod_barras,ref,fecha_vencimiento,des,cant,fraccion,unidades_fraccion,a.iva,costo as precio,a.precio,DATE(f.fecha) fecha,f.fecha as fe FROM art_ajuste a INNER JOIN ajustes f ON a.num_ajuste=f.num_ajuste  WHERE a.cod_su=f.cod_su  and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )
UNION

SELECT 9 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,cant,fraccion,unidades_fraccion,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_devolucion_venta a INNER JOIN fac_dev_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit and f.anulado!='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )
UNION

SELECT 10 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,cant,fraccion,unidades_fraccion,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_devolucion_venta a INNER JOIN fac_dev_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo AND a.nit=f.nit and f.anulado='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )

ORDER BY $ORDER_BY
 ";

return $sql;
};
function getId_art_com()
{
$ID=0;
$sql="SELECT MAX(id) r FROM art_fac_com ";
$rs=$linkPDO->query($sql);

if($row=$rs->fetch()){$ID=$row["r"]+1;}
else {$ID=1;}

return $ID;
};
function retenciones($fechaI,$fechaF,$filtroHora,$filtroNOanuladas,$filtroSEDE_nit)
{
	$R["fte"]=0;
	$R["ica"]=0;
	$R["iva"]=0;
	
	$sql="SELECT SUM(r_fte) fte, SUM(r_ica) ica, SUM(r_ica) iva FROM fac_venta WHERE (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND tipo_venta='Contado' $filtroNOanuladas $filtroSEDE_nit";
	
	//echo "$sql";
	$rs=$linkPDO->query($sql);
	
	if($row=$rs->fetch()){
		
		$R["fte"]=$row["fte"];
		$R["ica"]=$row["ica"];
		$R["iva"]=$row["iva"];
	}
	
	return $R;
};

function money($num)
{
	global $PUNTUACION_MILES,$PUNTUACION_DECIMALES;
	
	$num=round($num,2);
	$num=$num*1;
	$T=(string)$num;
   $T=quitacom($T);
	
	$i = strlen($T) - 1;
	$ii = strlen($T); 
   	$T=preg_split('//',$T,-1,PREG_SPLIT_NO_EMPTY);
   $x;
   $a;
   $b; 
   $c; 
   $C = 0; 
   $h = '';
   $ff=0; 
   while($i >= 0) {
	   
	  //alert('C:'+C+', ii:'+ii+'T[:'+i+']:'+T[i]);
	 
      if($C == 3 && $ii != 3 && $T[$i] != '-') {
         $h = $T[$i] . ',' . $h; 
         $C = 0; 
		//alert('IF\nh:['+h+']; C:'+C);
         }
      else {
         $h = $T[$i] . $h;
		 //alert('ELSE \nh:['+h+']; C:'+C);
         }
      //if(T[i] != '-'&&T[i]=='.')
	  
	  
	   if($T[$i]=='.'){$C=-1;
	   //$h=quitacom($h);
	   }
	   $C++; 
	   $i--;
       
      }
   return "".$h; 
};
function money2($num)
{
	global $PUNTUACION_MILES,$PUNTUACION_DECIMALES;
	
	$num=round($num,2);
	$T=(string)$num;
    $T=quitacom($T);
	$t=$T;
	$a="";
	$b="00";
	if (substr_count($t, '.') > 0) 
	{
    list($a, $b) = explode('.', $t);
	}
	else $a=$T;
	$i = strlen($a) - 1;
	$ii = strlen($a); 
	
   	$T=preg_split('//',$a,-1,PREG_SPLIT_NO_EMPTY);
   $x;
   $a;
   $b; 
   $c; 
   $C = 0; 
   $h = '';
   $ff=0; 
   while($i >= 0) {
	   
	  //alert('C:'+C+', ii:'+ii+'T[:'+i+']:'+T[i]);
	 
      if($C == 3 && $ii != 3 && $T[$i] != '-') {
         $h = $T[$i] . "$PUNTUACION_MILES" . $h; 
         $C = 0; 
		//alert('IF\nh:['+h+']; C:'+C);
         }
      else {
         $h = $T[$i] . $h;
		 //alert('ELSE \nh:['+h+']; C:'+C);
         }
      //if(T[i] != '-'&&T[i]=='.')
	  
	  
	   if($T[$i]==','){$C=-1;
	   //$h=quitacom($h);
	   }
	   $C++; 
	   $i--;
       
      }
   return "$ ".$h; 
};
function money3($num)
{
	global $PUNTUACION_MILES,$PUNTUACION_DECIMALES;
	
	//$num=round($num,2);
	$T=(string)$num;
    $T=quitacom($T);
	$t=$T;
	$a="";
	$b="00";
	if (substr_count($t, '.') > 0) 
	{
    list($a, $b) = explode('.', $t);
	}
	else $a=$T;
	$i = strlen($a) - 1;
	$ii = strlen($a); 
	
   	$T=preg_split('//',$a,-1,PREG_SPLIT_NO_EMPTY);
   
   $a;
   $b; 

   $C = 0; 
   $h = '';
   $ff=0; 
   while($i >= 0) {
	   
	  //alert('C:'+C+', ii:'+ii+'T[:'+i+']:'+T[i]);
	 
      if($C == 3 && $ii != 3 && $T[$i] != '-') {
         $h = $T[$i] . "$PUNTUACION_MILES" . $h; 
         $C = 0; 
		//alert('IF\nh:['+h+']; C:'+C);
         }
      else {
         $h = $T[$i] . $h;
		 //alert('ELSE \nh:['+h+']; C:'+C);
         }
      //if(T[i] != '-'&&T[i]=='.')
	  
	  
	   if($T[$i]==','){$C=-1;
	   //$h=quitacom($h);
	   }
	   $C++; 
	   $i--;
       
      }
  if($b!=0) {return "$ ".$h."$PUNTUACION_DECIMALES".$b; }
  else {return money2($num);}
  
};

function r($REQUEST_VARIABLE)
{
	$resp="";
	if(isset($_REQUEST[$REQUEST_VARIABLE]) )$resp=limpiarcampo(trim($_REQUEST[$REQUEST_VARIABLE]));
	$resp = str_replace("|","", $resp);
	return $resp;	
};

$LAST_VER="01542122016";
function quitar_session($dato = array()) {
    foreach ($dato as $die) {
        unset($_SESSION[$die]);
    }
};
function quitacom($val){
   $n = preg_split("[,]",$val);
   $i = 0;
    $h = ""; 
   for($i = 0; $i < count($n); $i++) {
      $h = $h.$n[$i]; 
      }
   return (int)$h; 
};
function s($SESSION_VARIABLE)
{
	$resp="";
	if(isset($_SESSION[$SESSION_VARIABLE]))$resp=limpiarcampo($_SESSION[$SESSION_VARIABLE]);
	$resp = str_replace("|","", $resp);
	return $resp;	
};
function rs($sql)
{
	
	global $conex;
	set_time_limit(960);
	$rs=mysqli_query($conex, $sql);
	return $rs;
};
function ft()
{ 	
	global $conex;
	$row=mysqli_fetch_array($rs, MYSQLI_ASSOC);
	return $row;
	
};

function fa()
{ 	global $conex;
	$row=mysqli_fetch_array($rs, MYSQLI_ASSOC);
	return $row;
};
function queT($sql,$sp)
{
	global $conex;
	//echo "\n\n\n$sql>";
	$rs=mysqli_query($sql);
	
	
	/*if($rs)
	{return true;}
	else {return false;}*/
	
};
function startTrans()
{
global $conex;
 
mysqli_autocommit($conex, FALSE);
$conex->autocommit(false);
}

function commit()
{
global $conex;
 
mysqli_commit($conex);
}
function savepoint($id)
{

	
};
function rollback_to_sp($id)
{

};
function rollback()
{
 
}

function t1($sq1)
{
global $conex;
$conex->autocommit(false);
$all_query_ok=true;
$conex->query($sq1)? null : $all_query_ok=false;
$linkPDO->commit();
};
function t2($q1,$q2)
{
global $conex;
$conex->autocommit(false);
$all_query_ok=true;

$conex->query($q1)? null : $all_query_ok=false;
$conex->query($q2)? null : $all_query_ok=false;

$linkPDO->commit();
if($all_query_ok){return true;}
else {return false;}
};

function t3($q1,$q2,$q3)
{
global $conex;
$conex->autocommit(false);
$all_query_ok=true;

$conex->query($q1)? null : $all_query_ok=false;
$conex->query($q2)? null : $all_query_ok=false;
$conex->query($q3)? null : $all_query_ok=false;

$linkPDO->commit();
};
 

function eco_alert($msj) {
    echo "<script type='text/javascript'>alert('$msj');</script>";
};

function redondeo($num)
{
	$NUM=quitacom($num);
	$result=round($NUM,2);
	return $result;
};
function redondeo2($num)
{
	$NUM=quitacom($num);
	$result=round($NUM,4);
	return $result;
};
function redondeoF($num,$dec)
{
	global $tipo_redondeo;
	$result=0;
	$NUM=quitacom($num);
	if($tipo_redondeo=="decimal")$result=round($NUM,$dec,PHP_ROUND_HALF_UP);
	
	//$result=round($NUM,$dec,PHP_ROUND_HALF_UP);
	else if($tipo_redondeo=="centena"){$result=ceil($NUM/100)*100;}
	return $result;
};



function ajuste_frac($existNew,$uniNew,$frac,$ref){
	
$resp[1]=0;
$resp[2]=0;

$i=0;

if($frac!=0){
while($uniNew>= $frac){

if( $uniNew>=$frac ){
$uniNew=$uniNew - $frac;
$existNew=$existNew +1;

//echo "<li>WHILE 1 : uniNew= $uniNew</li>";

}//END IF;
$i++;
//if($i>10)break;
}//END WHILE;


$i=0;
while( $uniNew < 0)
{
	
if( $uniNew < 0)
{
$uniNew=$uniNew+$frac;
$existNew=$existNew-1;
//set_time_limit(60);

//echo "<li>WHILE 2 : uniNew= $uniNew $ref  I[$i]</li>";
}//END IF;
$i++;
//if($i>1000)break;
}//end while

}//END IF;
$resp[1]=$existNew;
$resp[2]=$uniNew;

return $resp;
};

?>