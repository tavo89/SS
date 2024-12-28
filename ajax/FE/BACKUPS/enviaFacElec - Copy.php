<?php
include_once("../../Conexxx.php");
include_once("facturaElectronica.class.php");
// datos factura
$num_fac = r('num_fac');
$prefijo = r('prefijo');
$codSuc  = r('codSuc');
$hash    = r('hash');


$urlApi= 'https://matias-api.com.co/api/ubl2.1/invoice';
 $camposFactura =  array(
            "date"     => "2020-11-06",
            "time"     => "2020-11-06 02:49:36",
            "payment"  => json_encode(array(
                              "payment_method_id"  => 1,
                              "means_payment_id"   => 10,
                              "currency_id"        => 272
            )),
            "customer" => json_encode(array(
                              "country_id"          => "45",
                              "city_id"             => "447",
                              "identity_document_id"=> "1",
                              "type_organization_id"=> "1",
                              "tax_regime_id"       => "1",
                              "tax_level_id"        => "1",
                              "name"                => "HORACIO DE JESUS RESTREPO BRACAMONTE",
                              "dni"                 => "6622761",
                              "email"               => "email@email.com"
            )),
            "legal_monetary_totals"=> json_encode(array(
              "line_extension_amount"  => "189495.79",
              "tax_exclusive_amount"   => "189495.79",
              "tax_inclusive_amount"   => "225500.01",
              "charge_total_amount"    => "0",
              "pre_paid_amount"        => "0",
              "allowance_total_amount" => "0",
              "payable_amount"         => "225500"
            )),
            "invoice_lines"=> json_encode(array(
              array(
                  "invoiced_quantity"             => "1",
                  "quantity_units_id"             => "1093",
                  "line_extension_amount"         => "42857.14",
                  "free_of_charge_indicator"      => false,
                  "description"                   => "AEROGRAFO DE GRAVEDAD F-75 MASSO",
                  "code"                          => "000000001309",
                  "type_item_identifications_id"  => "4",
                  "reference_price_id"            => "1",
                  "price_amount"                  => "51000",
                  "base_quantity"                 => "1",
                  "tax_totals" => array(array(
                                      "tax_id"         => 1,
                                      "tax_amount"     => "8142.86",
                                      "taxable_amount" => "42857.14",
                                      "percent"        => "19"
                    ))
              ),
              array(
                "invoiced_quantity"=> "1",
                "quantity_units_id"=> "1093",
                "line_extension_amount"=> "10084.03",
                "free_of_charge_indicator"=> false,
                "description"=> "CINCEL CORTA FRIO MANGO CAUCHO MASSO",
                "code"=> "000000001312",
                "type_item_identifications_id"=> "4",
                "reference_price_id" => "1",
                "price_amount"=> "12000",
                "base_quantity"=> "1",
                "tax_totals"=> array(array(
                    "tax_id"=> 1,
                    "tax_amount"=> "1915.97",
                    "taxable_amount"=> "10084.03",
                    "percent"=> "19"
                ))
              )

      
         
            )),
            "invoice_number"=> "990000016",
            "operation_type_id"=> 1,
            "type_document_id"=> 7,

            "tax_totals"=> json_encode(array(
              array(
                "tax_id"=> 1,
                "tax_amount"=> "36004.21",
                "taxable_amount"=> "189495.79",
                "percent"=> "19"
              )
            ))
        );

  echo json_encode($camposFactura);
  echo '<br>';
$resp = enviaDocumentoDian($urlApi,$tokenDianOperaciones,$camposFactura);
 echo $resp;
 

?>