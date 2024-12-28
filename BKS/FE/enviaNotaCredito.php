<?php
include_once("../../Conexxx.php");
include_once("facturaElectronica.class.php");
// datos factura
// DOC PARA ANULAR FACTURAS
$num_fac = r('num_fac');
$prefijo = r('prefijo');
$codSuc  = r('codSuc');
$hash    = r('hash');


 $urlApi= 'https://matias-api.com.co/api/ubl2.1/creditnote';
 $camposNotaCredito =  array(
            "date"     => "2020-12-14",
            "time"     => "2020-12-14 14:49:36",
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
                "response_id"=> "2",
                "description"=> "Factura anulada"
            )),
            "billing_reference"=> json_encode(array(
                                  "number"=> "SETP990000010",
                                  "date"=> "2020-12-12",
                                  "uuid"=> "22a9a2491d7f8844de56403f85f342395a24d7fadbead11f99341a04d3f4facb3264330626d4dc42c5b3dee565bbaed0"
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
            "note_number"=> "990000050",
            "operation_type_id"=> 1,
            "type_document_id"=> 5,
            "tax_totals"=> json_encode(array(
              array(
               "tax_id"=> 1,
                "tax_amount"=> "18089.92",
                "taxable_amount"=> "95210.08",
                "percent"=> "19"
              )
            ))
        );

  //echo json_encode($camposNotaCredito);
$resp = enviaDocumentoDian($urlApi,$tokenDianOperaciones,$camposNotaCredito);
echo $resp;

 

?>