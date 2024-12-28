<?php
error_reporting(E_ERROR | E_PARSE);
$VER_PROGRAMA="4.9.530102018";
$FECHA_ACTUALIZAR_SW="2018-10-30";
$LAST_VER="2410201821222222";
$CHAR_SET="UTF-8";
ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);
//$CHAR_SET="ISO-8859-1";
session_start();
$_opt_facVen_dev="FAC_VEN";
header("Content-Type: text/html; charset=$CHAR_SET");
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$FechaHoy = gmdate("Y-m-d",hora_local(-5));
$hora = gmdate("H:i:s",hora_local(-5));

$mesActual=gmdate("m",hora_local(-5));
$yearActual=gmdate("Y",hora_local(-5));

$fechaVenciInterval="days";
$fechaVenciMINval="180";
$fechaVenciALERTval="150";
$fechaVenciOPTval="170";

///////////////// FECHAS VENCIMIENTOS //////////////////

$fechaVenciMIN=date("Y-m-d",strtotime($FechaHoy. "+ $fechaVenciMINval $fechaVenciInterval"));
$fechaVenciALERT=date("Y-m-d",strtotime($FechaHoy. "+ $fechaVenciALERTval $fechaVenciInterval"));
//$fechaVenciMIN=date('Y-m-d', strtotime($FechaHoy. ' + 10 days'));

///////////////////////////////////////////////////////


$horaB = gmdate("H:i A",hora_local(-5));
$FECHA_HORA=$FechaHoy."T".$hora;
$FECHA_HORA2="00-00-0000"."T".$hora;
$codSuc=s('cod_su');

$MESES=array(1,"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
$SECCIONES=array(1,"Clientes","Factura Venta","Pago Factura Credito","Comprobante Ingreso","Anulacion Facturas Venta","Facturas de Compra","Inventario","Ajustes Inventario");
$OPERACIONES=array(1,"Crear Registro","Modificar Registro","Eliminar Registro");

include_once('vendor/phpqrcode-master/phpqrcode.php');


if($codSuc==1)$fechaKardex="2000-05-07";
else if($codSuc==2)$fechaKardex="2010-05-07";
else if($codSuc==3)$fechaKardex="2010-05-07";
else if($codSuc==4)$fechaKardex="2010-05-07";
else $fechaKardex="2010-05-07";


function imgBase64($urlImg)
{
    $path = $urlImg;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    $base64Img = 'data:image/' . $type . ';base64,' . base64_encode($data);	
	return $base64Img;
}
function getQRcode($url){
    ob_start();
    $returnData = QRcode::pngString($url,false, "H", 3, 1);
    $imageString = base64_encode(ob_get_contents());
    ob_end_clean();
    $str = "data:image/png;base64," . $imageString;
    return $str;
}
function ajusta_kardex_all($codSuc)
{

global $conex,$usar_fecha_vencimiento;
global $linkPDO;

try{

$LIMIT_SQL=" LIMIT $offset, $limit";
//$LIMIT_SQL=" ";

$extraFilter=" AND id_inter='7702027416859'";
$extraFilter="";

ini_set('memory_limit', '2048M');
$filtros=" ";
switch($filter){
	
	case 1 :$filtros="AND (exist>0 OR unidades_frac>0)"; break;
	case 0 :$filtros="  $extraFilter"; break;
	
}
$saldo[][][]=0;
$REF_LIST[]="";

if($offset==0){
	
	$s="UPDATE inv_inter SET exist=0, unidades_frac=0 WHERE nit_scs=$codSuc $filtros";
	echo "<li>$s </li>";
	$linkPDO->exec($s);
}
set_time_limit(60);
$sql="SELECT *  FROM inv_inter WHERE nit_scs=$codSuc $filtros $LIMIT_SQL";
//echo "<li>$sql </li>";
$rs=$linkPDO->query($sql);
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
$sql="SELECT *  FROM inv_inter WHERE nit_scs=$codSuc $filtros $LIMIT_SQL";
$rsm=$linkPDO->query($sql);
//echo "<li>$sql</li>";
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
$sql=q_kardex($REF,$COD_BAR,$FE_VEN);


//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);

//if($REF=="10975"){echo "$sql";}

$fuente=array(1=>"Compra","Venta","Remision","ANULADO","Envio Traslado","Recibo Traslado","Devolucion","Ajuste Inventario","Devolucion Venta","ANULA Devolucion Venta","Remision Facturada","Compra ANULADA");
//                12

//               1   2   3   4   5   6   7   8   9   10  11  12 
$signo=array(1=>"+","-","-","~","-","+","-","+","+","~","~","~");
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
	
if(!empty($extraFilter))
{
echo "<li> $ref|$cod|$fe".$signo[$src]." $cant;$uni saldo:".$saldo[$ref][$cod][$fe][1]."; ".$saldo[$ref][$cod][$fe][2]."</li>";	
}

}
$saldoFrac=ajuste_frac($saldo[$REF][$COD_BAR][$FE_VEN][1],$saldo[$REF][$COD_BAR][$FE_VEN][2],$FRAC,$REF);
$UPDATE="UPDATE inv_inter SET exist='$saldoFrac[1]', unidades_frac=$saldoFrac[2] WHERE id_pro='$REF' AND id_inter='$COD_BAR' AND fecha_vencimiento='$FE_VEN' AND nit_scs=$codSuc;";
if($usar_fecha_vencimiento==0){
$UPDATE="UPDATE inv_inter SET exist='$saldoFrac[1]', unidades_frac=$saldoFrac[2] WHERE id_pro='$REF' AND id_inter='$COD_BAR'  AND nit_scs=$codSuc;";
}

if(!empty($extraFilter)){echo "<br>$UPDATE";}
t1($UPDATE);

}

}
t1("UPDATE x_material_query SET state=CONCAT('Done At: ',NOW()),last=NOW() WHERE id=2 AND cod_su=$codSuc;");
echo "<li>UPDATE x_material_query SET state=CONCAT('Done At: ',NOW()),last=NOW() WHERE id=2 AND cod_su=$codSuc;</li>";
}catch(Throwable $e){
	echo "Failed: " . $e->getMessage();

}
};
function q_kardex($ref,$codBar,$feVen,$codSuc)
{
	global $fechaKardex,$usar_fecha_vencimiento;
	
	$ORDER_BY="fe";
	$filtroProducto="cod_barras='$codBar' AND ref='$ref' AND fecha_vencimiento='$feVen'";
	if($usar_fecha_vencimiento==0){$filtroProducto="cod_barras='$codBar' AND ref='$ref'";}
	
	
$GROUP_BY=" GROUP BY ref,cod_barras,fecha_vencimiento,num";	
	
$sql="SELECT 1 as src,a.num_fac_com as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,pvp as precio,a.tot,DATE(f.fecha_mod) fecha,f.fecha as fe FROM art_fac_com a INNER JOIN fac_com f ON a.num_fac_com=f.num_fac_com  WHERE f.estado='CERRADA' AND tipo_fac!='Corte Inventario' AND tipo_fac!='Ajuste Seccion' AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc  and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto )  $GROUP_BY
UNION

SELECT 2 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_ven a INNER JOIN fac_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit and f.anulado!='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($filtroProducto ) $GROUP_BY
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
function rs($sql)
{
	global $conex;
	$rs=mysqli_query($conex, $sql);
	return $rs;
};
function fa()
{ 	
 	global $conex;
	$row=mysqli_fetch_array($rs, MYSQLI_ASSOC);
	return $row;
	
	
};
function query($sql)
{
	
global $conex;
	$rs=mysqli_query($conex, $sql);
	
};
function s($SESSION_VARIABLE)
{
	$resp="";
	if(isset($_SESSION[$SESSION_VARIABLE]))$resp=limpiarcampo($_SESSION[$SESSION_VARIABLE]);
	$resp = str_replace("|","", $resp);
	return $resp;	
};
function hora_local($zona_horaria = 0) {
    if ($zona_horaria > -12.1 and $zona_horaria < 12.1) {
        $hora_local = time() + ($zona_horaria * 3600);
        return $hora_local;
    }
    return 'error';
};
function r($REQUEST_VARIABLE)
{
	$resp="";
	if(isset($_REQUEST[$REQUEST_VARIABLE]))$resp=limpiarcampo(trim($_REQUEST[$REQUEST_VARIABLE]));
	return $resp;	
};
function limpianum($num)
{
	$T=(string)$num;
   
	$i = strlen($T) - 1;
	$ii = strlen($T); 
   	$T=preg_split('//',$T,-1,PREG_SPLIT_NO_EMPTY);
	$long=count($T);
	$resp="0";
	for($j=0;$j<$long;$j++)
	{
		if($T[$j]=="0"||$T[$j]=="1"||$T[$j]=="2"||$T[$j]=="3"||$T[$j]=="4"||$T[$j]=="5"||$T[$j]=="6"||$T[$j]=="7"||$T[$j]=="8"||$T[$j]=="9"||$T[$j]==".")
		{
			$resp.=$T[$j];
		}
	}
	return $resp;
};

function limpiarcampo($string){	
	global $conex;
	$string = $conex->real_escape_string($string);
	return $string;
};

function eco_alert($msj) {
    echo "<script type='text/javascript'>alert('$msj');</script>";
};
function js_location($url) {
    echo "<script type='text/javascript'>location.assign('$url');</script>";
};
function callback($thisURL)
{
	$_SESSION['url_back']=$thisURL;
	js_location("callback.php");
	
};

function money($num)
{
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
{/*
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
   */
};

function fecha($fechaENG)
{
	$fechaESP="";
	if(!empty($fechaENG))
	{
	$fecha=$fechaENG;
	$kkk=preg_split("[-]",$fecha);
	$fechaESP="$kkk[2]-$kkk[1]-$kkk[0]";
	}
	return $fechaESP;
};

function quitacom($val){

   $n = preg_split("[,]",$val);
   $i = 0;
    $h = ""; 
   for($i = 0; $i < count($n); $i++) {
      $h = $h.$n[$i]; 
      }
   return limpiarcampo($h); 

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
function num_rows($rs)
{
	return $rs->rowCount();
};
function ajusta_texto_txt($text)
{
	$resp="";
	
	$len=strlen($text);
	//echo "<br>len $len, $text";
	if($len<20){


for($i=$len;$i<20;$i++){ $text.=" "; }

	return $text;
	}
	else return $text;
	
};
function trunc_text($text)
{
	$resp="";
	
	$len=strlen($text);
	$mid=$len/2;
	$mark=35;
	if($len>30){
	$B=mb_substr($text,$mark,$len);
	if(!empty($B) )$resp=mb_substr($text,0,$mark);//."<br>".mb_substr($text,$mark,$len);
	else $resp=$text;
	return $resp;
	}
	else return $text;
	
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

//echo "<li>WHILE 1 : uniNew= $uniNew- I[$i]-uniNew=$uniNew - $frac;</li>";

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
//echo "<li>WHILE 2 : uniNew= $uniNew- I[$i]-uniNew=$uniNew - $frac;</li>";
//set_time_limit(60);

//echo "<li>WHILE 2 : uniNew= $uniNew</li>";
}//END IF;
$i++;
if($i>1000)break;
}//end while

}//END IF;
$resp[1]=$existNew;
$resp[2]=$uniNew;

return $resp;
};
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
function ejeSql($SQL){
	

try{
set_time_limit(180);
t1($SQL);
echo "<li>1 >>$SQL</li>";
}
catch(Exception $e){} 
};
function get_last_words($amount, $string)
{
    $amount+=1;
    $string_array = explode(' ', $string);
    $totalwords= str_word_count($string, 1, 'àáãç3');
    if($totalwords > $amount){
        $words= implode(' ',array_slice($string_array, count($string_array) - $amount));
    }else{
        $words= implode(' ',array_slice($string_array, count($string_array) - $totalwords));
    }

    return $words;
};
function totFacVen2($num_fac,$pre,$codSuc)
{
	
	global $conex,$MODULES,$linkPDO;
	$factor="((unidades_fraccion/fraccion)+cant)";
	$pvpE="( precio/(1+(iva/100)) )";
	$sub=0;
	$ivaTOT=0;
	$tot=0;
	
	$serv_sub_tot="pvp/(1+(iva/100))";
 
	$sql="SELECT *  FROM `art_fac_ven` a  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc'";
	$stmt = $linkPDO->query($sql);
	//echo "----------FAC $pre $num_fac --------------<br>";
	while($row = $stmt->fetch()) {
	
	 $ref=$row["ref"];
	 $codBar=$row["cod_barras"];
	 $feVen=$row["fecha_vencimiento"];
	 
	 $pvp=$row["precio"];
	 
	 $iva=$row["iva"];
	 $cant=$row["cant"];
	 $frac=$row["fraccion"];
	 $uni=$row["unidades_fraccion"];
	 $factor=$uni/$frac + $cant;
	 
	 $pvpSin=$pvp/(1+($iva/100));
	 
	 $SUB_tot_art=$pvpSin*$factor;
	 $IVA_art=$SUB_tot_art*($iva/100);
	 
	 $tot2=$factor*$pvp;
	 
	 $sub+=$SUB_tot_art;
	 $ivaTOT+=$IVA_art;
	 $tot+=$factor*$pvp;
	 
	 $sqla="UPDATE art_fac_ven SET sub_tot='$tot2' WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc' AND (ref='$ref' AND cod_barras='$codBar' AND fecha_vencimiento='$feVen')";
	// echo "<li>$sqla</li>";
	 $linkPDO->exec($sqla);
	}
if($MODULES["SERVICIOS"]==1){
	$sql2="SELECT SUM($serv_sub_tot) AS sub, SUM(($serv_sub_tot)*(iva/100) ) AS iva  FROM `serv_fac_ven` a  WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND cod_su='$codSuc'";
	$stmt = $linkPDO->query($sql2);
	if ($row2 = $stmt->fetch()) {
	 $sub+=$row2['sub'];
	 $ivaTOT+=$row2['iva'];
	 if($row2['sub']!=0)$tot+=$row2['sub']+$row2['iva'];
	}
}
	 
	$sql="UPDATE fac_venta SET sub_tot='$sub', iva='$ivaTOT', tot='$tot'+imp_consumo-DESCUENTO_IVA WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc'";
	$linkPDO->exec($sql);
	
};
function ajustaFacturas2($fechaI,$fechaF)
{
	global $codSuc,$conex;
	global $linkPDO;
$tabla_fac="fac_venta";
$sql="SELECT * FROM $tabla_fac WHERE   (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') AND nit=$codSuc  ";
//echo "$sql";
$rs=$linkPDO->query($sql);

while($row=$rs->fetch())
{

	
$num_fac=$row['num_fac_ven'];
$pre=$row['prefijo'];
totFacVen2($num_fac,$pre,$codSuc);
echo "FAC $pre $num_fac ajustada<br>";
}// fin while facturas
	
};


function resol()
{
	global $codSuc;
	global $linkPDO;
	$resp[][]="";
	$rs=$linkPDO->query("SELECT * FROM sucursal WHERE cod_su='$codSuc'");
	$row=$rs->fetch();
	$resp['POS'][0]=$row['cod_contado'];
	$resp['POS'][1]=$row['resol_contado'];
	$resp['POS'][2]=$row['fecha_resol_contado'];
	$resp['POS'][3]=$row['rango_contado'];

	$resp['COM'][0]=$row['cod_credito'];
	$resp['COM'][1]=$row['resol_credito'];
	$resp['COM'][2]=$row['fecha_resol_credito'];
	$resp['COM'][3]=$row['rango_credito'];

	$resp['PAPEL'][0]=$row['cod_papel'];
	$resp['PAPEL'][1]=$row['resol_papel'];
	$resp['PAPEL'][2]=$row['fecha_resol_papel'];
	$resp['PAPEL'][3]=$row['rango_papel'];

	$resp['CRE'][0]=$row['cod_credito_ant'];
	$resp['CRE'][1]=$row['resol_credito_ant'];
	$resp['CRE'][2]=$row['fecha_resol_credito_ant'];
	$resp['CRE'][3]=$row['rango_credito_ant'];

	$resp['REM_POS'][0]=$row['cod_remi_pos'];
	$resp['REM_POS'][1]=$row['resol_remi_pos'];
	$resp['REM_POS'][2]=$row['fecha_remi_pos'];
	$resp['REM_POS'][3]=$row['rango_remi_pos'];

	$resp['REM_COM'][0]=$row['cod_remi_com'];
	$resp['REM_COM'][1]=$row['resol_remi_com'];
	$resp['REM_COM'][2]=$row['fecha_remi_com'];
	$resp['REM_COM'][3]=$row['rango_remi_com'];

	$resp['REM_COM2'][0]=$row['cod_remi_com2'];
	$resp['REM_COM2'][1]=$row['resol_remi_com2'];
	$resp['REM_COM2'][2]=$row['fecha_remi_com2'];
	$resp['REM_COM2'][3]=$row['rango_remi_com2'];

	$resp[0][10]="SELECT * FROM sucursal WHERE cod_su='$codSuc'";

	return $resp;
};


function vehi($placa)
{
	global $linkPDO;
	$resp[]="";
	$resp["color"]="";
	$resp["modelo"]="";
	$resp["cc"]="";
	$resp["id_pro"]="";
	$resp["km"]="";
	$resp["nom_pro"]="";
	$resp["tel_pro"]="";
	$sql="SELECT * FROM  vehiculo WHERE placa='$placa'";
	$rs=$linkPDO->query($sql);
	if($row=$rs->fetch())
	{
		$resp["color"]=$row["color"];
		$resp["placa"]=$row["placa"];
		$resp["modelo"]=$row["modelo"];
		$resp["cc"]=$row["cc"];
		$resp["id_pro"]=$row["id_propietario"];
		$resp["km"]=$row["km"];
		$sql="SELECT * FROM  usuarios WHERE id_usu='$resp[id_pro]'";
		$rs=$linkPDO->query($sql);
		if($row=$rs->fetch())
		{
			$resp["nom_pro"]=$row["nombre"];
			$resp["tel_pro"]=$row["tel"];
		}
	}
	return $resp;
};

function money3($num)
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
  if($b!=0) {return "$ ".$h."$PUNTUACION_DECIMALES".$b; }
  else {return money2($num);}

};
function getTec($idTec)
{
	global $linkPDO;
	$rs=$linkPDO->query("SELECT a.id_usu,a.nombre FROM usuarios a INNER JOIN tipo_usu b ON a.id_usu=b.id_usu WHERE des='Tecnico' OR des='Mecanico' AND a.id_usu='$idTec'");
	$resp="";
	if($row=$rs->fetch())
	{
		$resp=$row["nombre"];
	}
	return $resp;
};

define("tabProductos","productos");

function q_kardexFecha($ref,$codBar,$feVen,$codSuc,$fechaFin)
{
	global $fechaKardex,$usar_fecha_vencimiento;
	
	$ORDER_BY="fe";
	$filtroProducto="cod_barras='$codBar' AND ref='$ref' AND fecha_vencimiento='$feVen'";
	//if($usar_fecha_vencimiento==0){$filtroProducto="cod_barras='$codBar' AND ref='$ref'";}


//(DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin')	
	
$GROUP_BY=" GROUP BY ref,cod_barras,fecha_vencimiento,num";	
	
$sql="SELECT 1 as src,a.num_fac_com as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,pvp as precio,a.tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_com a INNER JOIN fac_com f ON a.num_fac_com=f.num_fac_com  WHERE f.estado='CERRADA' AND tipo_fac!='Corte Inventario' AND tipo_fac!='Ajuste Seccion' AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc  and (DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin') and ($filtroProducto )  $GROUP_BY
UNION


SELECT 12 as src,a.num_fac_com as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,pvp as precio,a.tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_com a INNER JOIN fac_com f ON a.num_fac_com=f.num_fac_com  WHERE (f.estado!='CERRADA' AND tipo_fac!='Corte Inventario' AND tipo_fac!='Ajuste Seccion') AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc  and (DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin') and ($filtroProducto )  $GROUP_BY
UNION

SELECT 2 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_ven a INNER JOIN fac_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit and f.anulado!='ANULADO' and f.anulado='CERRADA' and a.nit=$codSuc and (DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin') and ($filtroProducto ) $GROUP_BY
UNION

SELECT 3 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli!='Traslado' AND anulado!='FACTURADA' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and a.nit=$codSuc and (DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin') and ($filtroProducto ) $GROUP_BY
UNION

SELECT 11 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli!='Traslado' AND anulado='FACTURADA' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and a.nit=$codSuc and (DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin') and ($filtroProducto ) $GROUP_BY
UNION

SELECT 4 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_ven a INNER JOIN fac_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo AND a.nit=f.nit and f.anulado='ANULADO' and a.nit=$codSuc and (DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin') and ($filtroProducto ) $GROUP_BY
UNION

SELECT 5 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli='Traslado' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and a.nit=$codSuc and (DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin') and ($filtroProducto ) $GROUP_BY
UNION

SELECT 6 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_remi a INNER JOIN fac_remi f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit AND (tipo_cli='Traslado' AND estado='Recibido' AND tipo_fac!='cotizacion') and f.anulado!='ANULADO' and f.sede_destino=$codSuc and (DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin') and ($filtroProducto ) $GROUP_BY
UNION


SELECT 7 as src,a.num_fac_com as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,pvp as precio,a.tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_fac_dev a INNER JOIN fac_dev f ON a.num_fac_com=f.num_fac_com  AND anulado!='ANULADO' AND  a.serial_dev=f.serial_fac_dev AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc and (DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin') and ($filtroProducto ) $GROUP_BY
UNION

SELECT 8 as src,a.num_ajuste as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.precio,DATE(f.fecha) fecha,f.fecha as fe FROM art_ajuste a INNER JOIN ajustes f ON a.num_ajuste=f.num_ajuste  WHERE a.cod_su=f.cod_su  and a.cod_su=$codSuc and (DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin') and ($filtroProducto ) $GROUP_BY
UNION

SELECT 9 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_devolucion_venta a INNER JOIN fac_dev_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo and a.nit=f.nit and f.anulado!='ANULADO' and a.nit=$codSuc and (DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin') and ($filtroProducto ) $GROUP_BY
UNION

SELECT 10 as src,a.num_fac_ven as num,cod_barras,ref,fecha_vencimiento,des,SUM(cant) cant,fraccion,unidades_fraccion,a.iva,costo,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe FROM art_devolucion_venta a INNER JOIN fac_dev_venta f ON a.num_fac_ven=f.num_fac_ven  WHERE a.prefijo=f.prefijo AND a.nit=f.nit and f.anulado='ANULADO' and a.nit=$codSuc and (DATE(f.fecha)>='$fechaKardex' AND DATE(f.fecha)<='$fechaFin') and ($filtroProducto ) $GROUP_BY

ORDER BY $ORDER_BY
 ";

return $sql;
};

function valida_kardex_single($codSuc,$offset,$limit,$ref,$cod,$fechaVenci,$fechaFinKardex)
{
	global $conex,$usar_fecha_vencimiento;
	global $linkPDO;
	
try{
ini_set('memory_limit', '2048M');

$saldo[][][]=0;
$saldo2[][][]=0;
$REF_LIST[]="";

$respuesta['cant']=0;
$respuesta['uni']=0;

$LIMIT_SQL=" LIMIT $offset, $limit";
$sql="SELECT *  FROM inv_inter WHERE nit_scs=$codSuc AND id_pro='$ref' AND id_inter='$cod' AND fecha_vencimiento='$fechaVenci' $LIMIT_SQL";
$rs=$linkPDO->query("$sql");
while($row=$rs->fetch())
{

$ref=$row['id_pro'];
$cod=$row['id_inter'];
$fe=$row['fecha_vencimiento'];
$cantidades=$row["exist"];
$fracciones=$row["unidades_frac"];


$REF_LIST[$ref]=$ref;
$saldo[$ref][$cod][$fe][1]=0;
$saldo[$ref][$cod][$fe][2]=0;

$saldo2[$ref][$cod][$fe][1]=$cantidades;
$saldo2[$ref][$cod][$fe][2]=$fracciones;

}


$iii=0;
$sql="SELECT *  FROM inv_inter WHERE nit_scs=$codSuc AND id_pro='$ref' AND id_inter='$cod' AND fecha_vencimiento='$fechaVenci' $LIMIT_SQL";
//echo "<li>$sql</li>";
//echo "<pre>";
$rsm=$linkPDO->query($sql);
while($rowm=$rsm->fetch())
{
	set_time_limit(60);
	$iii++;
$REF=$rowm["id_pro"];
if(in_array($REF,$REF_LIST,TRUE)){
$COD_BAR=$rowm["id_inter"];
$FE_VEN=$rowm["fecha_vencimiento"];
//if($usar_fecha_vencimiento==0){$FE_VEN="0000-00-00";}
$FRAC=1;
$sql=q_kardexFecha($REF,$COD_BAR,$FE_VEN,$codSuc,$fechaFinKardex);
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);

//if($REF=="10975"){echo "$sql";}

$fuente=array(1=>"Compra","Venta","Remision","ANULADO","Envio Traslado","Recibo Traslado","Devolucion","Ajuste Inventario","Devolucion Venta","ANULA Devolucion Venta","Remision Facturada","Compra ANULADA");
//                12

//               1   2   3   4   5   6   7   8   9   10  11  12
$signo=array(1=>"+","-","-","~","-","+","-","+","+","~","~","~");

while($row=$rs->fetch())
{
	$ref=$row['ref'];

	$num_fac=$row['num'];
	$subTot=money($row['tot']);
	$IVA=money($row['iva']);
	$des=$row['des'];
	$cant=$row['cant']*1;
	$uni=$row["unidades_fraccion"];
	$FRAC=$row["fraccion"];
	if($FRAC<=0){$FRAC=1;}
	$valor=money($row['precio']);

	
	$cod=$row['cod_barras'];
	$fe=$row['fecha_vencimiento'];

	$fecha=$row['fecha'];
	$src=$row['src'];
	if($signo[$src]=="+"){$saldo[$ref][$cod][$fe][1]+=$cant;$saldo[$ref][$cod][$fe][2]+=$uni;}
	else if($signo[$src]=="-"){$saldo[$ref][$cod][$fe][1]-=$cant;$saldo[$ref][$cod][$fe][2]-=$uni;}


}
$saldoFrac=ajuste_frac($saldo[$REF][$COD_BAR][$FE_VEN][1],$saldo[$REF][$COD_BAR][$FE_VEN][2],$FRAC,$REF);

 //echo "<li>$des $ref / $cod / $fe  Inv | kardex Cant:  ".$saldo2[$REF][$COD_BAR][$FE_VEN][1]." | <B>$saldoFrac[1]</B> ".$saldo2[$REF][$COD_BAR][$FE_VEN][2]." | <B>$saldoFrac[2]</B></li>";
$respuesta['cant']=$saldoFrac[1];
$respuesta['uni']=$saldoFrac[2];

if($usar_fecha_vencimiento==0){}

}

}
return $respuesta;

}catch (Throwable $e) {


}
};


function varSesionSistema($nombreSesion)
{
	$session=(isset($_SESSION[$nombreSesion])) ? $_SESSION[$nombreSesion]:'';
	return trim($session);
};