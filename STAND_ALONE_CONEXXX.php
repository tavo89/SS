<?php
session_start();
header("Content-Type: text/html; charset=ISO-8859-1");

include("DB.php");

$codSuc=$_SESSION['cod_su'];

$_SESSION['tipo_usu']=$_SESSION['tipo_usu'];
$_SESSION['id_usu']=$_SESSION['id_usu'];
$_SESSION['rol_lv']=$_SESSION['rol_lv'];
$_SESSION["see_warn_ban_list"]=1;
$_SESSION["see_warn_inv"]=1;

$id=$_SESSION['id_usu'];
$codSuc=$_SESSION['cod_su'];

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
	
	include('variablesSistema.php');

 
}
 

$sql="SELECT * FROM x_config WHERE cod_su=1";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
	
$_SESSION[$row["des_config"]]=$row["val"];		
	
}





/******************/
$TIPO_CHUZO=s('TIPO_CHUZO');
/*****************/
$cert_import=s('cert_import');
$usar_serial=s('usar_serial');
if($TIPO_CHUZO=="STD"){
$usar_color=0;
$usar_talla=0;


 
$usar_fecha_vencimiento=0;
$usar_fracciones_unidades=1;
}
else if($TIPO_CHUZO=="ROPA")
{
$usar_color=1;
$usar_talla=1;
 

$usar_fecha_vencimiento=0;
$usar_fracciones_unidades=1;	
}
else if($TIPO_CHUZO=="DRO")
{
$usar_color=0;
$usar_talla=0;
 
$usar_fecha_vencimiento=1;
$usar_fracciones_unidades=1;	
}
else 
{
$usar_color=0;
$usar_talla=0;
 
$usar_fecha_vencimiento=0;
$usar_fracciones_unidades=1;	
}

$usar_color=s('usar_color');
$usar_fracciones_unidades=s('usar_fracciones_unidades');
$usar_datos_motos=s('usar_datos_motos');
$ropa_campos_extra=s('ropa_campos_extra');




if($codSuc==1)$fechaKardex="2000-05-07";
else if($codSuc==2)$fechaKardex="2010-05-07";
else if($codSuc==3)$fechaKardex="2010-05-07";
else if($codSuc==4)$fechaKardex="2010-05-07";
else $fechaKardex="2010-05-07";
//////////////////////////// funcones varias /////////////////////////////
function q_kardex($ref,$codBar,$feVen, $codSuc)
{
	global $fechaKardex,$usar_fecha_vencimiento;
	
	$ORDER_BY="fe";
	$filtroProducto="cod_barras='$codBar' AND ref='$ref' AND fecha_vencimiento='$feVen'";
	if($usar_fecha_vencimiento==0){$filtroProducto="cod_barras='$codBar' AND ref='$ref'";}
	
	
$GROUP_BY=" GROUP BY ref,cod_barras,fecha_vencimiento,num";	
	
$sql="SELECT 1 as src,a.num_fac_com as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,pvp as precio,a.tot,DATE(f.fecha_mod) fecha,f.fecha as fe FROM art_fac_com a INNER JOIN fac_com f ON a.num_fac_com=f.num_fac_com  WHERE f.estado='CERRADA' AND tipo_fac!='Corte Inventario' AND tipo_fac!='Ajuste Seccion' AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc  and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )  $GROUP_BY
UNION

SELECT 2 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_ven a INNER JOIN fac_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit and f.".VALIDACION_VENTA_VALIDA." and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
UNION

SELECT 3 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli!='Traslado' AND anulado!='FACTURADA' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
UNION

SELECT 4 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_ven a INNER JOIN fac_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo AND a.nit=f.nit and f.anulado='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
UNION

SELECT 5 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli='Traslado' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
UNION

SELECT 6 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli='Traslado' AND estado='Recibido' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and f.sede_destino=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
UNION


SELECT 7 as src,a.num_fac_com as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,pvp as precio,a.tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_dev a INNER JOIN fac_dev f ON a.num_fac_com=f.num_fac_com  AND anulado!='ANULADO' AND  a.serial_dev=f.serial_fac_dev AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
UNION

SELECT 8 as src,a.num_ajuste as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.precio,DATE(f.fecha) fecha,f.fecha as fe FROM art_ajuste a INNER JOIN ajustes f ON a.num_ajuste=f.num_ajuste  WHERE a.cod_su=f.cod_su  and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
UNION

SELECT 9 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_devolucion_venta a INNER JOIN fac_dev_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit and f.anulado!='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
UNION

SELECT 10 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_devolucion_venta a INNER JOIN fac_dev_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo AND a.nit=f.nit and f.anulado='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY

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
   return $h*1; 
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

function t1($SQL)
{
global $linkPDO;
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


$linkPDO->exec($SQL);


$linkPDO->commit();
if($all_query_ok){return true;}
else {return false;}
}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}

};
function t2($q1,$q2)
{
global $linkPDO;
try { 
$linkPDO->beginTransaction();

$linkPDO->exec($q1);
$linkPDO->exec($q2);

$linkPDO->commit();
 
}catch (Exception $e) {$linkPDO->rollBack();echo "Failed: [$q1]- -[$q2]" . $e->getMessage();}

};

function t3($q1,$q2,$q3)
{
global $linkPDO;
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


$linkPDO->exec($q1);
$linkPDO->exec($q2);
$linkPDO->exec($q3);

$linkPDO->commit();
 
if($all_query_ok){return true;}
else {return false;}
}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}
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
function limpiarcampo($string){
global $conex;

//$string = $conex->real_escape_string($string);
$liberate =$string;

 
$liberate = str_replace("'","-", $liberate);
$liberate = str_replace("<","&lt;", $liberate);
$liberate = str_replace(">","&gt;", $liberate);
$liberate = str_replace('"',"&quot;", $liberate);
$liberate = str_replace("\\","", $liberate);

return trim(limp2($liberate));
};
function limpiarJS($dirty){
 $liberate=$dirty;
//$liberate = str_replace("'","\'", $liberate);
 
$liberate = str_replace('"','\"', $liberate);

/*

UPDATE your_table
SET your_field = REPLACE(your_field, '"', '&quot;')

*/

return trim($liberate);
};
function limpWhere($n)
{
	//$n=strtoupper($n);
	$m=$n;
	$m=str_replace("INSERT","",$m);
	$m=str_replace("UPDATE","",$m);
	$m=str_replace("DELETE","",$m);
	return $m;
};
function limp2($str)
{
	$n = preg_split("[|]",$str);
   $i = 0;
    $h = ""; 
   for($i = 0; $i < count($n); $i++) {
      $h = $h.$n[$i]; 
      }
   return $h;
};
 
function quitaamp($val){

   $n = preg_split("[amp;]",$val);
   $i = 0;
    $h = ""; 
   for($i = 0; $i < count($n); $i++) {
      $h = $h.$n[$i]; 
      }
	  
	  $Result=$h;
	  $htmlCods=array("&Aacute;","&aacute;","&Eacute;","&eacute;","&Iacute;","&iacute;","&Oacute;","&oacute;","&Uacute;","&uacute;","&Ntilde;","&ntilde;","&Uuml;","&uuml;");
	  //$jsCods=array("\u00C1","\u00E1","\u00B4","\u00C0","\u00E0","\u00C9","\u00E9","\u00C8","\u00E8","\u00CD","\u00ED","\u00CC","\u00EC","\u00O3","\u00F3","\u00D2","\u00F2","\u00DA","\u00FA","\u00D9","\u00F9","\u00D1","\u00F1","\u00DC","\u00FC");
	  
	  $jsCods=array("\u00C1","\u00E1","\u00C9","\u00E9","\u00CD","\u00ED","\u00D3","\u00F3","\u00DA","\u00FA","\u00D1","\u00F1","\u00DC","\u00FC");
	  //$Result=str_replace($htmlCods,$jsCods,$h);
	  
	  
   return $Result; 

};

function ajusta_kardex_all($codSuc)
{


global $conex,$usar_fecha_vencimiento,$linkPDO;
	
$saldo[][][]=0;
$REF_LIST[]="";
$s="UPDATE inv_inter SET exist=0, unidades_frac=0 WHERE nit_scs=$codSuc";
echo "<li>$s</li>";
query($s);
$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE nit_scs=$codSuc");
while($row=$rs->fetch())
{

$ref=$row['id_pro'];
$cod=$row['id_inter'];
$fe=$row['fecha_vencimiento'];
if($usar_fecha_vencimiento==0){$fe="0000-00-00";}

$REF_LIST[$ref]=$ref;
$saldo[$ref][$cod][$fe][1]=0;
$saldo[$ref][$cod][$fe][2]=0;	
	
}


$iii=0;
$rsm=$linkPDO->query("SELECT *  FROM inv_inter WHERE nit_scs=$codSuc");
while($rowm=$rsm->fetch())
{

set_time_limit(60);
$iii++;
$REF=$rowm["id_pro"];
if(in_array($REF,$REF_LIST,TRUE)){
$COD_BAR=$rowm["id_inter"];
$FE_VEN=$rowm["fecha_vencimiento"];
if($usar_fecha_vencimiento==0){$FE_VEN="0000-00-00";}
$FRAC=1;
$sql=q_kardex($REF,$COD_BAR,$FE_VEN, $codSuc);
//echo $sql;
$rs=$linkPDO->query($sql);

//if($REF=="10975"){echo "$sql";}

//                   1       2          3        4          5                 6                7              8					 	9					10
$fuente=array(1=>"Compra","Venta","Remision","ANULADO","Envio Traslado","Recibo Traslado","Devolucion","Ajuste Inventario","Devolucion Venta","ANULA Devolucion Venta");
//               1   2   3   4   5   6   7   8   9   10
$signo=array(1=>"+","-","-","~","-","+","-","+","+","-");
$UPDATE="";
while($row=$rs->fetch())
{
	$ref=$row['ref'];
	
	$num_fac=$row['num'];
	$subTot=money($row['tot']);
	$IVA=money($row['iva']);
	//$des=htmlentities($row['des'], ENT_QUOTES,"$CHAR_SET");
	$des=$row['des'];
	$cant=$row['cant']*1;
	$uni=$row["unidades_fraccion"];
	$FRAC=$row["fraccion"];
	if($FRAC<=0){$FRAC=1;}
	$valor=money($row['precio']);
	
	
$cod=$row['cod_barras'];
$fe=$row['fecha_vencimiento'];
if($usar_fecha_vencimiento==0){$fe="0000-00-00";}
	//$vendedor=htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET");
	//$HORA=$row['hora'];
	$fecha=$row['fecha'];
	$src=$row['src'];
	if($signo[$src]=="+"){$saldo[$ref][$cod][$fe][1]+=$cant;$saldo[$ref][$cod][$fe][2]+=$uni;}
	else if($signo[$src]=="-"){$saldo[$ref][$cod][$fe][1]-=$cant;$saldo[$ref][$cod][$fe][2]-=$uni;}
	
	
}
$saldoFrac=ajuste_frac($saldo[$REF][$COD_BAR][$FE_VEN][1],$saldo[$REF][$COD_BAR][$FE_VEN][2],$FRAC,$REF);
$UPDATE="UPDATE inv_inter SET exist='$saldoFrac[1]', unidades_frac=$saldoFrac[2] WHERE id_pro='$REF' AND id_inter='$COD_BAR' AND fecha_vencimiento='$FE_VEN' AND nit_scs=$codSuc;";
if($usar_fecha_vencimiento==0){
$UPDATE="UPDATE inv_inter SET exist='$saldoFrac[1]', unidades_frac=$saldoFrac[2] WHERE id_pro='$REF' AND id_inter='$COD_BAR'  AND nit_scs=$codSuc;";
}

t1($UPDATE);
//echo "$UPDATE";
//if($REF=="10975"){echo "$UPDATE ---> $saldo[$ref][$cod][$fe][1] ; $saldo[$ref][$cod][$fe][2] ref: $ref";}
//echo $iii."\r";
}

}
t1("UPDATE x_material_query SET state=CONCAT('Done At: ',NOW()),last=NOW() WHERE seccion='Ajuste Kardex' AND cod_su=$codSuc;");
//echo "<li>UPDATE x_material_query SET state=CONCAT('Done At: ',NOW()),last=NOW() WHERE id=2 AND cod_su=$codSuc;</li>";
	
}
function query($sql)
{
	global $conex;
	$rs=mysqli_query($conex, $sql);
	
};
?>