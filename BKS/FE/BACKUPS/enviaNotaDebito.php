<?php
/*     (DEVOLUCIONES COMPRAS)
La nota de débito es un comprobante que una empresa envía a su cliente, en la que se le notifica haber cargado o debitado en su cuenta una determinada suma o valor, por el concepto que se indica en la misma nota.
*/
include_once("../../Conexxx.php");
include_once("facturaElectronica.class.php");
// datos factura
$num_fac = r('num_fac');
$prefijo = r('prefijo');
$codSuc  = r('codSuc');
$hash    = r('hash');


 $urlApi= 'https://matias-api.com.co/api/ubl2.1/debitnote';
 $camposNotaDebito =  array(
            "date"     => "2021-05-24",
            "time"     => "2021-05-24 01:00:36",
            "payment"  => json_encode(array(
                              "payment_method_id"  => 1,
                              "means_payment_id"   => 10,
                              "currency_id"        => 272,
					          "payment_due_date"   => "null",
							  "payment_term"       => 0
            )),
            "customer" => json_encode(array(
                              "country_id"          => "45",
                              "city_id"             => "1048",
                              "identity_document_id"=> "1",
                              "type_organization_id"=> "2",
                              "tax_regime_id"       => "3",
                              "tax_level_id"        => "2",
                              "name"                => "GUSTAVO ALONSO BOCANEGRA",
                              "dni"                 => "1120359931",
                              "email"               => "gustavo_bocanegra@hotmail.com"
            )),
            "legal_monetary_totals"=> json_encode(array(
              "line_extension_amount"  => "95210.08",
              "tax_exclusive_amount"   => "95210.08",
              "tax_inclusive_amount"   => "113300",
              "charge_total_amount"    => "0",
              "pre_paid_amount"        => "0",
              "allowance_total_amount" => "0",
              "payable_amount"         => "113300"
            )),
            "discrepancy_response"=> json_encode(array(
                                  "reference_id"=> "SETP990000010",
                                  "response_id"=> "9",
                                  "description"=> "Ejemplo de nota"
            )),
            "billing_reference"=> json_encode(array(
                                  "number"=> "SETP990000010",
                                  "date"=> "2021-05-24",
                                  "uuid"=> "465a43a8e990f8f6f4a802c3cd7946c68020a66864ed488b9f833acb8f5987de7b2f705598f17d8f8e5a0463cf9a5a24"
            )),
            "note_lines"=> json_encode(array(
              array(
                "invoiced_quantity"=> "1",
                "quantity_units_id"=> "70",
                "line_extension_amount"=> "95210.08",
                "free_of_charge_indicator"=> false,
                "description"=> "STILOGRAFOLAMYALSTAR025M",
                "code"=> "4014519428527",
                "type_item_identifications_id"=> "4",
                "reference_price_id"=> "1",
                "price_amount"=> "95210.08",
                "base_quantity"=> "1",
                "tax_totals"=> array(array(
                 
                    "tax_id"=> 1,
                    "tax_amount"=> "18089.92",
                    "taxable_amount"=> "95210.08",
                    "percent"=> "19"
                 
                ))
              )
            )),
            "note_number"=> "990000051",
            "operation_type_id"=> 1,
            "type_document_id"=> 4,
            
            "tax_totals"=> json_encode(array(
              array(
                "tax_id"=> 1,
                "tax_amount"=> "18089.92",
                "taxable_amount"=> "95210.08",
                "percent"=> "19"
              )
            ))
        );
		

  //echo json_encode($camposNotaDebito);
$resp = enviaDocumentoDian($urlApi,$tokenDianOperaciones,$camposNotaDebito);

 echo $resp;


?>