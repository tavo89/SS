<?php
require_once("Conexxx.php");


$columns="".tabProductos.".id_pro id_glo,inv_inter.id_inter  id_sede,detalle,id_clase,fraccion,fab,max,min,costo,precio_v,exist,iva,gana,pvp_may";
$sql = "SELECT  $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE  nit_scs=$codSuc ORDER BY detalle";
 $resultado = rs ($sql);
 $registros = mysqli_num_rows ($resultado);




if ($registros > 0) {
   require_once 'PLUG-INS/PHPExcel_1.8.0/Classes/PHPExcel.php';
   $objPHPExcel = new PHPExcel();
   
   //Informacion del excel
   $objPHPExcel->
    getProperties()
        ->setCreator("ingenieroweb.com.co")
        ->setLastModifiedBy("ingenieroweb.com.co")
        ->setTitle("Exportar excel desde mysql")
        ->setSubject("Ejemplo 1")
        ->setDescription("Documento generado con PHPExcel")
        ->setKeywords("ingenieroweb.com.co  con  phpexcel")
        ->setCategory("ciudades");    

   $i = 1;    



while($row=$rs->fetch())
{

		
			
			 $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $row->name);
 
     		 $i++;
}




}
 
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="ejemplo1.xlsx"');
header('Cache-Control: max-age=0');

$objWriter=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');
$objWriter->save('php://output');
exit;
?>
