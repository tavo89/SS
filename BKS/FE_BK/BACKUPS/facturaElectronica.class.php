<?php
include_once("../../Conexxx.php");
/*
 *
 *
 *
 */
function consultarCamposDian($urlApi,$token){
	
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $urlApi,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 90,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".$token,
        "Cache-Control: no-cache",
        "Content-Type: application/json"
        ),
    ));
	
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err){
        echo "cURL Error #:" . $err;
    }else {
        return $response;
    }
}



/*
 *
 *
 *
 */
function enviaDocumentoDian($urlApi,$token,$camposFactura, $transformaParams = true){
	global $company_fe,$usuarioDian,$nit_reg_dian;
//	echo "transformaParams : $transformaParams";
	$curl = curl_init();
    $params = ($transformaParams==true) ?json_encode($camposFactura) : $camposFactura;

	if($company_fe!='DATAICO'){
        $header = array(
            "Authorization: Bearer ".$token,
            "Cache-Control: no-cache",
            "Content-Type: application/json"
        );
       /* echo "url: $urlApi <br>";
        echo '<br>Params:';
        print_r($params);

        echo '<br>header:<pre>';
        print_r($header);*/
        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlApi,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 90,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => $header,
        ));
	
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
	}else {
		

	/*echo 'Params:';
	print_r($params);*/
    curl_setopt_array($curl, array(
        CURLOPT_URL => $urlApi,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 90,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $params,
        CURLOPT_HTTPHEADER => array(
        "auth-token:".$token,
        "Content-Type: application/json"
        ),
    ));
	
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);
	
	}
	
    if ($err){
        echo "cURL Error #:" . $err;
    }else {
        return $response;
    }
}


/*
 *
 *
 *
 */
 
 function creaCamposFacElec($num_fac,$prefijo,$codSuc,$hash)
 {
    global $linkPDO,$MODULES,$company_fe,$dataico_account_id,$send_dian;

    $filtroHash=" AND hash='$hash'";
	$prefijoFE = $prefijo=='---' || $prefijo=='----'? '' : $prefijo;
	
    if(empty($hash) || $hash==0)$filtroHash="";
	
    if($company_fe!='DATAICO'){
	$sql="SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' AND nit='$codSuc' $filtroHash";
	$rs=$linkPDO->query($sql);
	//echo "$sql<br>";
	if($row=$rs->fetch()){
		
		/*
		"identity_document_id"
		id/code
		 1/13 = C.C.
	 	 3/31 = NIT
		
		
		"city_id": 
		arauca,arauca = "1048"
		arauca,arauquita = "1049"
		arauca,Cravo norte = "1050"
		arauca,Fortul = "1051"
		arauca,Puerto Rondon = "1052"
		arauca,Saravena = "1053"
		arauca,Tame = "1054"
		
		
		"payment_method_id":
		1 = contado
		2 = credito
		
		
		"means_payment_id": 
		10 = Efectivo
		48 = Tarjeta Crédito
		49 = Tarjeta Débito
		
		*/
		$fecha = explode(' ', $row['fecha']);


        /*Variables FE Dian*/
        $city_id              = 1048;
		$payment_method_id    = 1;
		$means_payment_id     = 10;
		$identity_document_id = $row['coddoc'];
		$type_organization_id = $row['claper'];
		$tax_regime_id        = $row['regtri'];
		$tax_level_id         = $row['regFiscal'];
		if($tax_level_id>5){$tax_level_id=5;}
        $nom_cli = "$row[nom_cli] $row[snombr] $row[apelli]";


        if($row['tipo_venta']=='Contado'){
        	$payment_method_id = 1;
        }else if($row['tipo_venta']=='Credito'){
            $payment_method_id = 2;
        }else if($row['tipo_venta']=='Tarjeta Credito'){
            $payment_method_id = 1;
            $means_payment_id = 48;
        }else if($row['tipo_venta']=='Tarjeta Debito'){
            $payment_method_id = 1;
            $means_payment_id = 49;
        }else {
            $payment_method_id = 1;
            $means_payment_id     = 10;
        }
         // cuando es credito agregar en payment -> 
        $payment_due_date = 'null';
		$payment_term     = 0;
		if($payment_method_id==2){
           $payment_due_date = date('Y-m-d', strtotime($fecha[0]. ' + 30 days'));
		   $payment_term     = 30;
		}
		
		// depurar NIT
        $id_cli = $row['id_cli'];
        $findme ='-';
		$pos = strpos($id_cli, $findme);
        $nitCli = $id_cli;
		if ($pos !== false) {
            $nitCli = explode('-',$id_cli);
            $nitCli = $nitCli[0];

       } else {
             $nitCli = $id_cli;
       }

        $camposFactura =  array(
                   "date"     => "$fecha[0]",
                   "time"     => "$row[fecha]",
                   "payment"  => json_encode(array(
                                     "payment_method_id"  => $payment_method_id,
                                     "means_payment_id"   => $means_payment_id,
                                     "currency_id"        => 272,
									 "payment_due_date"   => "$payment_due_date",
									 "payment_term"       => $payment_term
                   )),
                   "customer" => json_encode(array(
                                     "country_id"          => "45",
                                     "city_id"             => "$city_id",
                                     "identity_document_id"=> "$identity_document_id",
                                     "type_organization_id"=> "$type_organization_id",
                                     "tax_regime_id"       => "$tax_regime_id",
                                     "tax_level_id"        => "$tax_level_id" ,
                                     "name"                => "$nom_cli",
                                     "dni"                 => "$nitCli",
                                     "email"               => "$row[mail]"
                   )));
				   

	}
	

	/*
    codigo Unidad productos
    code: "94"
    id: 70
    name: "unidad"
	*/
    $cont=0;
    $iva_show=0;
    $Iva=0;
    $excentas=0;
    $iva0=1000;
    $infoAdd="";

    $base05=0;
    $iva05=0;

    $base10=0;
    $iva10=0;

    $base16=0;
    $iva16=0;

    $base19=0;
    $iva19=0;
    
    $SUB=0;
    $camposFactura["invoice_lines"]= '[';
	$rs=$linkPDO->query("SELECT *,fecha_vencimiento as feven FROM art_fac_ven  WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' $filtroHash AND  nit=$codSuc ORDER BY orden_in" );
	$numRows=$rs->rowCount();
	$i=0;
    while($row=$rs->fetch()){
    
    $i++;
    $iva = $row["iva"];
    if($iva>$iva_show)$iva_show=$iva;
    $cant = $row["cant"]*1;
    $fracc=$row['fraccion'];
    if($fracc<=0)$fracc=1;
    $uni = $row["unidades_fraccion"]*1;
    $factor=($uni/$fracc)+$cant;

    $pvpE = ($row["precio"]/(1+$iva/100))*$factor;
    

	if($iva==0){
        $excentas+=$row['sub_tot'];
        $iva0=0;
    }

    if($iva==5){
    $base05+=$pvpE;
    $iva05+=$pvpE*($iva/100);
    }


    if($iva==19){
    $base19+=$pvpE;
    $iva19+=$pvpE*($iva/100);
    }


    if($iva==10){
    $base10+=$pvpE;
    $iva10+=$pvpE*($iva/100);
    }

    if($iva==16){
    $base16+=$pvpE;
    $iva16+=$pvpE*($iva/100);
    }
    
    $pvp = $row["precio"]/(1+$iva/100);

	$SUB+=$pvp*$factor;

	$valorIVA= round($pvpE*($iva/100),2);
	$subTotConIVA = round(($pvp*$factor),2);
    
    $comma=",";
    if($i==$numRows){$comma="";}
    $taxTots=',"tax_totals" :[ {
                            "tax_id"         : 1,
                            "tax_amount"     : "'.$valorIVA.'",
                            "taxable_amount" : "'.round($pvpE,2).'",
                            "percent"        : "'.$iva.'"
                            }]';
	if($iva==0){
		$taxTots ='';
	}
    $camposFactura["invoice_lines"].= preg_replace('/\s+/', '','{"invoiced_quantity":"'.$factor.'",
                  "quantity_units_id"             : "70",
                  "line_extension_amount"         : "'.round($pvpE,2).'",
                  "free_of_charge_indicator"      : false,
                  "description"                   : "'.$row["des"].'",
                  "code"                          : "'.$row["cod_barras"].'",
                  "type_item_identifications_id"  : "4",
                  "reference_price_id"            : "1",
                  "price_amount"                  : "'.$subTotConIVA.'" ,
                  "base_quantity"                 : "1"
				  '.$taxTots.'

                    
              }'.$comma.'');



    
}

// servicios

if($MODULES['SERVICIOS']==1){
	$rs=$linkPDO->query("SELECT * FROM serv_fac_ven  WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' $filtroHash AND  cod_su=$codSuc" );
	$numRows=$rs->rowCount();
	$i=0;
    while($row=$rs->fetch()){
		 $i++;
    $iva = $row["iva"];
    if($iva>$iva_show)$iva_show=$iva;
    $cant = 1;
    $fracc=1;
    if($fracc<=0)$fracc=1;
    $uni = 0;
    $factor=1;

    $pvpE = ($row["pvp"]/(1+$iva/100))*$factor;
    

	if($iva==0){
        $excentas+=$row['pvp'];
        $iva0=0;
    }

    if($iva==5){
    $base05+=$pvpE;
    $iva05+=$pvpE*($iva/100);
    }


    if($iva==19){
    $base19+=$pvpE;
    $iva19+=$pvpE*($iva/100);
    }


    if($iva==10){
    $base10+=$pvpE;
    $iva10+=$pvpE*($iva/100);
    }

    if($iva==16){
    $base16+=$pvpE;
    $iva16+=$pvpE*($iva/100);
    }
    
    $pvp = $row["pvp"]/(1+$iva/100);

	$SUB+=$pvp*$factor;

	$valorIVA= round($pvpE*($iva/100),2);
	$subTotConIVA = round(($pvp*$factor),2);
    
    $comma=",";
    if($i==$numRows){$comma="";}
    $taxTots=',"tax_totals" :[ {
                            "tax_id"         : 1,
                            "tax_amount"     : "'.$valorIVA.'",
                            "taxable_amount" : "'.round($pvpE,2).'",
                            "percent"        : "'.$iva.'"
                            }]';
	if($iva==0){
		$taxTots ='';
	}
    $camposFactura["invoice_lines"].= preg_replace('/\s+/', '','{"invoiced_quantity":"'.$factor.'",
                  "quantity_units_id"             : "70",
                  "line_extension_amount"         : "'.round($pvpE,2).'",
                  "free_of_charge_indicator"      : false,
                  "description"                   : "'.$row["serv"].'",
                  "code"                          : "'.$row["cod_serv"].'",
                  "type_item_identifications_id"  : "4",
                  "reference_price_id"            : "1",
                  "price_amount"                  : "'.$subTotConIVA.'" ,
                  "base_quantity"                 : "1"
				  '.$taxTots.'

                    
              }'.$comma.'');
	}
}

$camposFactura["invoice_lines"].= ']';

$totalIVA        = round($iva05 + $iva10 + $iva19,2);
$totalBaseIVA    = round($base05 + $base10 + $base19+$excentas,2);
$tax_exclusive_amount = round($base05 + $base10 + $base19,2);
$totalFacturaIVA = $totalBaseIVA + $totalIVA;


$camposFactura["invoice_number"]= "$num_fac";
$camposFactura["operation_type_id"]= 1;
$camposFactura["type_document_id"]= 7;

$camposFactura["legal_monetary_totals"]= json_encode(array(
                  "line_extension_amount"  => "$totalBaseIVA",
                  "tax_exclusive_amount"   => "$tax_exclusive_amount",
                  "tax_inclusive_amount"   => "$totalFacturaIVA",
                  "charge_total_amount"    => "0",
                  "pre_paid_amount"        => "0",
                  "allowance_total_amount" => "0",
                  "payable_amount"         => "".($totalFacturaIVA).""
            ));
/*
$camposFactura["allowance_charges"]= json_encode(array(
              array(
                "amount"=> "0",
                "base_amount"=> "$totalBaseIVA" ,
                "charge_indicator"=> false,
                "discount_id"=> 8,
                "allowance_charge_reason"=> "Total Factura"
              )
            ));*/

    $excentas=redondeo($excentas,2);
    $base05=redondeo($base05,2);
    $iva05=redondeo($iva05,2);

    $base10=redondeo($base10,2);
    $iva10=redondeo($iva10,2);

    $base19=redondeo($base19,2);
    $iva19=redondeo($iva19,2);
    $camposFactura["tax_totals"]= '';
	if($base19>0 && $base10==0 && $base05==0){
        $camposFactura["tax_totals"].= json_encode(array(
              array(
                "tax_id"=> 1,
                "tax_amount"=> "$iva19",
                "taxable_amount"=> "$base19",
                "percent"=> "19"
              )
            ));
    
	} else if($base19==0 && $base05>0){
		 $camposFactura["tax_totals"].= json_encode(array(
              array(
                "tax_id"=> 1,
                "tax_amount"=> "$iva05",
                "taxable_amount"=> "$base05",
                "percent"=> "5"
              )
            ));
		
	}else if($base19>0 && $base05>0){
		$camposFactura["tax_totals"].= json_encode(
		array(  
		       array( "tax_amount"   => "$totalIVA",
		              "tax_subtotal" =>
                               array(
                                   array("tax_id"=> 1,
                                         "tax_amount"=> "$iva19",
                                         "taxable_amount"=> "$base19",
                                         "percent"=> 19
										 ),
								   array(
                                         "tax_id"=> 1,
                                         "tax_amount"=> "$iva05",
                                         "taxable_amount"=> "$base05",
                                         "percent"=> 5
                                         )
                                  )
                   )/*,
			  array(
                      "tax_id"=> 1,
                      "tax_amount"=> "$iva05",
                      "taxable_amount"=> "$base05",
                      "percent"=> 5
                    )*/
			
			)
			);
	}


//echo json_encode( $camposFactura);	
	}// fin IF company
	else {
		/*********   DATAICO   ********/

	$sql="SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' AND nit='$codSuc' $filtroHash";
	$rs=$linkPDO->query($sql);
	$camposFactura='';
	if($row=$rs->fetch()){
		
		$fecha = explode(' ', $row['fecha']);


        /*Variables FE Dian*/
		$DCTO=($row['DESCUENTO_IVA']/$row['sub_tot'])*100;
		$descuentoFactura= $DCTO;
		$perReteFuente = $row['per_fte'];
        $city              = 81001;
		$payment_means_type   = 'DEBITO'; //[ "DEBITO", "CREDITO" ]
		$payment_means        = 'CASH';/*[ "CREDIT_ACH", "DEBIT_ACH", "CASH", "CREDIT_AHORRO", "DEBIT_AHORRO", "CHEQUE", "CREDIT_TRANSFER", 
		"DEBIT_TRANSFER", "BANK_TRANSFER", "MUTUAL_AGREEMENT", "CREDIT_BANK_TRANSFER", "DEBIT_INTERBANK_TRANSFER", "DEBIT_BANK_TRANSFER",
		"CREDIT_CARD", "DEBIT_CARD" ]*/
		$party_identification_type = $row['coddoc'];//[ "CC", "NIT", "PASAPORTE", "RC", "TI", "TE", "CE", "IE", "NIT_OTRO_PAIS" ]
		switch ($row['coddoc']) {
            case '1':
                $party_identification_type ='CC';
                break;
            case '2':
                $party_identification_type ='CE';
                break;
            case '3':
                $party_identification_type ='NIT';
                break;
			case '4':
                $party_identification_type ='NIT_OTRO_PAIS';
                break;
			case '6':
                $party_identification_type ='RC';
                break;
			case '7':
                $party_identification_type ='TI';
                break;
			case '8':
                $party_identification_type ='TE';
                break;
			case '9':
                $party_identification_type ='PASAPORTE';
                break;
			case '10':
                $party_identification_type ='IE';
                break;
			default:
			$party_identification_type ='CC';
        }
		
		$party_type = $row['claper']==1?'PERSONA_JURIDICA':'PERSONA_NATURAL';//[ "PERSONA_JURIDICA", "PERSONA_NATURAL" ]
		$regimen        = $row['regFiscal'];//[ "SIMPLE", "ORDINARIO", "GRAN_CONTRIBUYENTE", "AUTORRETENEDOR", "AGENTE_RETENCION_IVA" ]
		switch ($row['regFiscal']) {
            case '4':
                $regimen ='SIMPLE';
                break;
            case '5':
                $regimen ='ORDINARIO';
                break;
            case '1':
                $regimen ='GRAN_CONTRIBUYENTE';
                break;
			case '2':
                $regimen ='AUTORRETENEDOR';
                break;
			case '3':
                $regimen ='AGENTE_RETENCION_IVA';
                break;
			default:
			$regimen ='SIMPLE';
        }
		
		$tax_level_code= $row['regtri']=='1'?'COMUN':'SIMPLIFICADO';//[ "RESPONSABLE_DE_IVA", "NO_RESPONSABLE_DE_IVA" ]
        $notaFE = !empty($row['nota'])?$row['nota']:'---';

        $first_name = preg_replace('/\s\s+/', ' ', trim("$row[nom_cli] $row[snombr]") );
		$family_name= trim("$row[apelli]");
		
		$rozonSocial = ($party_type=='PERSONA_JURIDICA' && empty($row['razsoc']) )? "$row[nom_cli] $row[snombr]":$row['razsoc'];
		if(empty($family_name)){
			$nombres= explode(' ',$first_name);
			$numNombres = count($nombres);

		switch ($numNombres) {
			case 5:
                $first_name =$nombres[0].' '.$nombres[1].' '.$nombres[2];
				$family_name =$nombres[3].' '.$nombres[4];
                break;
            case 4:
                $first_name =$nombres[0].' '.$nombres[1];
				$family_name =$nombres[2].' '.$nombres[3];
                break;
            case 3:
                $first_name =$nombres[0];
				$family_name =$nombres[1].' '.$nombres[2];
                break;
            case 2:
                $first_name =$nombres[0];
				$family_name =$nombres[1];
                break;
			default:
			$first_name = "$row[nom_cli] $row[snombr]";
		    $family_name= "$row[apelli]";
        }
		
		}


        if($row['tipo_venta']=='Contado'){
        	$payment_means_type = 'DEBITO';
			$payment_means='CASH';
        }else if($row['tipo_venta']=='Credito'){
            $payment_means_type = 'CREDITO';
			$payment_means='CASH';
        }else if($row['tipo_venta']=='Tarjeta Credito'){
            $payment_means_type = 'DEBITO';
			$payment_means='CREDIT_AHORRO';
        }else if($row['tipo_venta']=='Tarjeta Debito'){
            $payment_means_type = 'DEBITO';
			$payment_means='DEBIT_AHORRO';
        }else {
            $payment_means_type = 'DEBITO';
			$payment_means='CASH';
        }
		
		
         // cuando es credito agregar en payment -> 

        $payment_date = fecha(date('Y-m-d', strtotime($fecha[0]. ' + 30 days')), '/').' 17:00:00';
		$payment_term     = 30;
		$issue_date = fecha($fecha[0], '/');
		
		
		$department = substr($row['ciudad'], 0, 2);
		$city =substr($row['ciudad'], 2);
		
        // depurar NIT
        $id_cli = str_replace('.','',$row['id_cli']);
        $findme ='-';
		$pos = strpos($id_cli, $findme);
		$nitCli = $id_cli;
 
		if ($pos !== false) {
            $nitCli = explode('-',$id_cli);
            $nitCli = $nitCli[0];

       } else {
             $nitCli = $id_cli;
       }
		
         $camposFactura='{
                             "actions": {
                                    "send_dian": '.$send_dian.',
                                    "send_email": true
						    },"invoice": {
									"env": "PRODUCCION",
									"dataico_account_id": "'.$dataico_account_id.'",
									"number": '.$num_fac.',
									"issue_date": "'.$issue_date.'",
									"payment_date": "'.$payment_date.'",
									"order_reference": "----",
									"invoice_type_code": "FACTURA_VENTA",
									"payment_means": "'.$payment_means.'",
									"payment_means_type": "'.$payment_means_type.'",
									"numbering": {
										"resolution_number": "'.$row['resolucion'].'",
										"prefix": "'.$prefijoFE.'",
										"flexible": false
									},
									"notes": [
										"'.$notaFE.'"
									],
									"customer": {
										"email": "'.$row['mail'].'",
										"phone": "'.$row['tel_cli'].'",
										"party_identification_type": "'.$party_identification_type.'",
										"party_identification": "'.$nitCli.'",
										"party_type": "'.$party_type.'",
										"tax_level_code": "'.$tax_level_code.'",
										"regimen": "'.$regimen.'",
										"department": "'.$department.'",
										"city": "'.$city.'",
										"address_line": "'.$row['dir'].'",
										"country_code": "CO",
										"company_name": "'.$rozonSocial.'",
										"first_name": "'.$first_name.'",
										"family_name": "'.$family_name.'"
									},';
 
				   

	}



 
    $cont=0;
    $iva_show=0;
    $Iva=0;
    $excentas=0;
    $iva0=1000;
    $infoAdd="";

    $base05=0;
    $iva05=0;

    $base10=0;
    $iva10=0;

    $base16=0;
    $iva16=0;

    $base19=0;
    $iva19=0;
    
    $SUB=0;
    $camposFactura.= '"items": [';
	$rs=$linkPDO->query("SELECT *,fecha_vencimiento as feven FROM art_fac_ven  WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' $filtroHash AND  nit=$codSuc ORDER BY orden_in" );
	$numRows=$rs->rowCount();
	$i=0;
    while($row=$rs->fetch()){
    
    $i++;
    $iva = $row["iva"];
    if($iva>$iva_show)$iva_show=$iva;
    $cant = $row["cant"]*1;
    $fracc=$row['fraccion'];
    if($fracc<=0)$fracc=1;
    $uni = $row["unidades_fraccion"]*1;
    $factor=($uni/$fracc)+$cant;

    $pvpE = ($row["precio"]/(1+$iva/100))*$factor;
    $precioUni=($row["precio"]/(1+$iva/100));
    

	if($iva==0){
        $excentas+=$row['sub_tot'];
        $iva0=0;
    }

    if($iva==5){
    $base05+=$pvpE;
    $iva05+=$pvpE*($iva/100);
    }


    if($iva==19){
    $base19+=$pvpE;
    $iva19+=$pvpE*($iva/100);
    }


    if($iva==10){
    $base10+=$pvpE;
    $iva10+=$pvpE*($iva/100);
    }

    if($iva==16){
    $base16+=$pvpE;
    $iva16+=$pvpE*($iva/100);
    }
    
    $pvp = $row["precio"]/(1+$iva/100);

	$SUB+=$pvp*$factor;

	$valorIVA= round($pvpE*($iva/100),2);
	$subTotConIVA = round(($pvp*$factor),2);
    
    $comma=",";
    if($i==$numRows){$comma="";}
 
	if($iva==0){
		$taxTots ='';
	}
	$retenciones='';
	if($perReteFuente>0){
		$retenciones=',
		                "retentions": [
		                    {
                                "tax_category": "RET_FUENTE",
                                "tax_rate": '.$perReteFuente.'
		                    }
		                ]';
	}
	$descuento='';
	if($descuentoFactura>0){
	    $descuento='"discount_rate": '.$descuentoFactura.',';	
		
		
	}
    $camposFactura.= '{
		                "sku": "'.$row['cod_barras'].'",
		                "quantity": '.$factor.',
		                "description": "'.$row['des'].'", 
		                "price": '.round($precioUni,2).',
		                '.$descuento.'
		                "taxes": [
		                    {
                                "tax_category": "IVA",
                                "tax_rate": '.$row['iva'].'
		                    }
		                ]'.$retenciones.'
		                }'.$comma;



    
}

// servicios

if($MODULES['SERVICIOS']==1){
	$rs=$linkPDO->query("SELECT * FROM serv_fac_ven  WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' $filtroHash AND  cod_su=$codSuc" );
	$numRows=$rs->rowCount();
	$i=0;
    while($row=$rs->fetch()){
		 $i++;
    $iva = $row["iva"];
    if($iva>$iva_show)$iva_show=$iva;
    $cant = 1;
    $fracc=1;
    if($fracc<=0)$fracc=1;
    $uni = 0;
    $factor=1;
	$precioUni=($row["pvp"]/(1+$iva/100));

    $pvpE = ($row["pvp"]/(1+$iva/100))*$factor;
    

	if($iva==0){
        $excentas+=$row['pvp'];
        $iva0=0;
    }

    if($iva==5){
    $base05+=$pvpE;
    $iva05+=$pvpE*($iva/100);
    }


    if($iva==19){
    $base19+=$pvpE;
    $iva19+=$pvpE*($iva/100);
    }


    if($iva==10){
    $base10+=$pvpE;
    $iva10+=$pvpE*($iva/100);
    }

    if($iva==16){
    $base16+=$pvpE;
    $iva16+=$pvpE*($iva/100);
    }
    
    $pvp = $row["pvp"]/(1+$iva/100);

	$SUB+=$pvp*$factor;

	$valorIVA= round($pvpE*($iva/100),2);
	$subTotConIVA = round(($pvp*$factor),2);
    
    $comma=",";
    if($i==$numRows){$comma="";}
    
	if($iva==0){
 
	}
    $retenciones='';
	if($perReteFuente>0){
		$retenciones=',
		                "retentions": [
		                    {
                                "tax_category": "RET_FUENTE",
                                "tax_rate": '.$perReteFuente.'
		                    }
		                ]';
	}
	$descuento='';
	if($descuentoFactura>0){
	    $descuento='"discount_rate": '.$descuentoFactura.',';	
		
		
	}
	$camposFactura.= '{
		                "sku": "'.$row['cod_serv'].'",
		                "quantity": '.$factor.',
		                "description": "'.$row['serv'].'", 
		                "price": '.round($precioUni,2).',
		                '.$descuento.'
		                "taxes": [
		                    {
                                "tax_category": "IVA",
                                "tax_rate": '.$row['iva'].'
		                    }
		                ]'.$retenciones.'
		                }'.$comma;
	}
}


$camposFactura.= ']
		 }
	}';

	
		}
return $camposFactura;
 }



function creaCamposNotaDebito($num_fac,$prefijo,$codSuc)
 {
    global $linkPDO;
	

  $sql="SELECT * FROM fac_dev_venta WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo' AND nit='$codSuc'";
	$rs=$linkPDO->query($sql);
	//echo "$sql<br>";
	if($row=$rs->fetch()){
		
		/*
		"identity_document_id"
		id/code
		 1/13 = C.C.
	 	 3/31 = NIT
		
		
		"city_id": 
		arauca,arauca = "1048"
		arauca,arauquita = "1049"
		arauca,Cravo norte = "1050"
		arauca,Fortul = "1051"
		arauca,Puerto Rondon = "1052"
		arauca,Saravena = "1053"
		arauca,Tame = "1054"
		
		
		"payment_method_id":
		1 = contado
		2 = credito
		
		
		"means_payment_id": 
		10 = Efectivo
		48 = Tarjeta Crédito
		49 = Tarjeta Débito
		
		*/
		$fecha = explode(' ', $row['fecha']);


        /*Variables FE Dian*/
        $city_id              = $row['city'];
		$payment_method_id    = 1;
		$means_payment_id     = 10;
		$identity_document_id = $row['coddoc'];
		$type_organization_id = $row['claper'];
		$tax_regime_id        = $row['regtri'];
		$tax_level_id         = $row['regFiscal'];
        $nom_cli = "$row[nom_cli] $row[snombr] $row[apelli]";


        if($row['tipo_venta']=='Contado'){
        	$payment_method_id = 1;
        }else if($row['tipo_venta']=='Credito'){
            $payment_method_id = 2;
        }else if($row['tipo_venta']=='Tarjeta Credito'){
            $payment_method_id = 1;
            $means_payment_id = 48;
        }else if($row['tipo_venta']=='Tarjeta Debito'){
            $payment_method_id = 1;
            $means_payment_id = 49;
        }else {
            $payment_method_id = 1;
            $means_payment_id     = 10;
        }
         // cuando es credito agregar en payment -> 
        $payment_due_date = 'null';
		$payment_term     = 0;
		if($payment_method_id==2){
           $payment_due_date = date('Y-m-d', strtotime($fecha[0]. ' + 30 days'));
		   $payment_term     = 30;
		}

        		// depurar NIT
        $id_cli = $row['id_cli'];
        $findme ='-';
		$pos = strpos($id_cli, $findme);
		$nitCli = $id_cli;
 
		if ($pos !== false) {
            $nitCli = explode('-',$id_cli);
            $nitCli = $nitCli[0];

       } else {
             $nitCli = $id_cli;
       }
        $camposFactura =  array(
                   "date"     => "$fecha[0]",
                   "time"     => "$row[fecha]",
                   "payment"  => json_encode(array(
                                     "payment_method_id"  => $payment_method_id,
                                     "means_payment_id"   => $means_payment_id,
                                     "currency_id"        => 272,
									 "payment_due_date"   => "$payment_due_date",
									 "payment_term"       => $payment_term
                   )),
                   "customer" => json_encode(array(
                                     "country_id"          => "45",
                                     "city_id"             => "$city_id",
                                     "identity_document_id"=> "$identity_document_id",
                                     "type_organization_id"=> "$type_organization_id",
                                     "tax_regime_id"       => "$tax_regime_id",
                                     "tax_level_id"        => "$tax_level_id" ,
                                     "name"                => "$nom_cli",
                                     "dni"                 => "$nitCli",
                                     "email"               => "$row[mail]"
                   )));
				   

	}
	

	/*
    codigo Unidad productos
    code: "94"
    id: 70
    name: "unidad"
	*/
    $cont=0;
    $iva_show=0;
    $Iva=0;
    $excentas=0;
    $iva0=1000;
    $infoAdd="";

    $base05=0;
    $iva05=0;

    $base10=0;
    $iva10=0;

    $base16=0;
    $iva16=0;

    $base19=0;
    $iva19=0;
    
    $SUB=0;
    $camposFactura["note_lines"]= '[';
	$rs=$linkPDO->query("SELECT *,fecha_vencimiento as feven FROM art_devolucion_venta  WHERE num_fac_ven='$num_fac' AND prefijo='$prefijo'  AND  nit=$codSuc ORDER BY orden_in" );
	$numRows=$rs->rowCount();
	$i=0;
    while($row=$rs->fetch()){
    
    $i++;
    $iva = $row["iva"];
    if($iva>$iva_show)$iva_show=$iva;
    $cant = $row["cant"]*1;
    $fracc=$row['fraccion'];
    if($fracc<=0)$fracc=1;
    $uni = $row["unidades_fraccion"]*1;
    $factor=($uni/$fracc)+$cant;

    $pvpE = ($row["precio"]/(1+$iva/100))*$factor;
    

  if($iva==0){
        $excentas+=$row['sub_tot'];
        $iva0=0;
    }

    if($iva==5){
    $base05+=$pvpE;
    $iva05+=$pvpE*($iva/100);
    }


    if($iva==19){
    $base19+=$pvpE;
    $iva19+=$pvpE*($iva/100);
    }


    if($iva==10){
    $base10+=$pvpE;
    $iva10+=$pvpE*($iva/100);
    }

    if($iva==16){
    $base16+=$pvpE;
    $iva16+=$pvpE*($iva/100);
    }
    
    $pvp = $row["precio"]/(1+$iva/100);

  $SUB+=$pvp*$factor;

  $valorIVA= round($pvpE*($iva/100),2);
  $subTotConIVA = round(($pvp*$factor),2);
    
    $comma=",";
    if($i==$numRows){$comma="";}
    $taxTots=',"tax_totals" :[ {
                            "tax_id"         : 1,
                            "tax_amount"     : "'.$valorIVA.'",
                            "taxable_amount" : "'.round($pvpE,2).'",
                            "percent"        : "'.$iva.'"
                            }]';
  if($iva==0){
    $taxTots ='';
  }
    $camposFactura["note_lines"].= preg_replace('/\s+/', '','{"invoiced_quantity":"'.$factor.'",
                  "quantity_units_id"             : "70",
                  "line_extension_amount"         : "'.round($pvpE,2).'",
                  "free_of_charge_indicator"      : false,
                  "description"                   : "'.$row["des"].'",
                  "code"                          : "'.$row["cod_barras"].'",
                  "type_item_identifications_id"  : "4",
                  "reference_price_id"            : "1",
                  "price_amount"                  : "'.$subTotConIVA.'" ,
                  "base_quantity"                 : "1"
          '.$taxTots.'

                    
              }'.$comma.'');

}

$camposFactura["note_lines"].= ']';

$totalIVA        = round($iva05 + $iva10 + $iva19,2);
$totalBaseIVA    = round($base05 + $base10 + $base19+$excentas,2);
$tax_exclusive_amount = round($base05 + $base10 + $base19,2);
$totalFacturaIVA = $totalBaseIVA + $totalIVA;


$camposFactura["note_number"]= "$num_fac";
$camposFactura["operation_type_id"]= 1;
$camposFactura["type_document_id"]= 5;

$camposFactura["discrepancy_response"]= json_encode(array(
                "reference_id"=> "SETP990000010",
                "response_id"=> "2",
                "description"=> "Factura anulada"
            ));

$camposFactura["billing_reference"]= json_encode(array(
                   "number"=> "SETP990000010",
                   "date"=> "2020-12-12",
                   "uuid"=> "22a9a2491d7f8844de56403f85f342395a24d7fadbead11f99341a04d3f4facb3264330626d4dc42c5b3dee565bbaed0"
            ));

$camposFactura["legal_monetary_totals"]= json_encode(array(
                  "line_extension_amount"  => "$totalBaseIVA",
                  "tax_exclusive_amount"   => "$tax_exclusive_amount",
                  "tax_inclusive_amount"   => "$totalFacturaIVA",
                  "charge_total_amount"    => "0",
                  "pre_paid_amount"        => "0",
                  "allowance_total_amount" => "0",
                  "payable_amount"         => "".($totalFacturaIVA).""
            ));

    $excentas=redondeo($excentas,2);
    $base05=redondeo($base05,2);
    $iva05=redondeo($iva05,2);

    $base10=redondeo($base10,2);
    $iva10=redondeo($iva10,2);

    $base19=redondeo($base19,2);
    $iva19=redondeo($iva19,2);
    $camposFactura["tax_totals"]= '';
  if($base19>0 && $base10==0 && $base05==0){
        $camposFactura["tax_totals"].= json_encode(array(
              array(
                "tax_id"=> 1,
                "tax_amount"=> "$iva19",
                "taxable_amount"=> "$base19",
                "percent"=> "19"
              )
            ));
    
  } else if($base19==0 && $base05>0){
     $camposFactura["tax_totals"].= json_encode(array(
              array(
                "tax_id"=> 1,
                "tax_amount"=> "$iva05",
                "taxable_amount"=> "$base05",
                "percent"=> "5"
              )
            ));
    
  }else if($base19>0 && $base05>0){
    $camposFactura["tax_totals"].= json_encode(
    array(  
           array( "tax_amount"   => "$totalIVA",
                  "tax_subtotal" =>
                               array(
                                   array("tax_id"=> 1,
                                         "tax_amount"=> "$iva19",
                                         "taxable_amount"=> "$base19",
                                         "percent"=> 19
                     ),
                   array(
                                         "tax_id"=> 1,
                                         "tax_amount"=> "$iva05",
                                         "taxable_amount"=> "$base05",
                                         "percent"=> 5
                                         )
                                  )
                   )
      
      )
      );
  }

//echo json_encode( $camposFactura);	

return $camposFactura;
 }
