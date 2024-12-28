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
            "date"     => "2020-10-30",
            "time"     => "2020-10-30 14:49:36",
            "payment"  => json_encode(array(
                              "payment_method_id"  => 1,
                              "means_payment_id"   => 10,
                              "currency_id"        => 272,
					          "payment_due_date"   => "null",
							  "payment_term"       => 0
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
            "discrepancy_response"=> json_encode(array(
                                  "reference_id"=> "SETP990000001",
                                  "response_id"=> "9",
                                  "description"=> "Ejemplo de nota"
            )),
            "billing_reference"=> json_encode(array(
                                  "number"=> "SETP990000100",
                                  "date"=> "2020-10-30",
                                  "uuid"=> "1863f16b5cb4b46203982c13eedc316e81d62f907d40e4fa0415494930240d0a0301ea4868be44e877ca60478dc8fa59"
            )),
            "note_lines"=> json_encode(array(
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
              ),
              array(
                "invoiced_quantity"=> "2",
                "quantity_units_id"=> "1093",
                "line_extension_amount"=> "6722.69",
                "free_of_charge_indicator"=> false,
                "description"=> "CINCEL CORTA FRIO MASSO",
                "code"=> "000000000985",
                "type_item_identifications_id"=> "4",
                "reference_price_id"=> "1",
                "price_amount"=> "4000",
                "base_quantity"=> "2",
                "tax_totals"=> array(array(
                    "tax_id"=> 1,
                    "tax_amount"=> "1277.31",
                    "taxable_amount"=> "6722.69",
                    "percent"=> "19"
                ))
              ),
              array(
                "invoiced_quantity"=> "1",
                "quantity_units_id"=> "1093",
                "line_extension_amount"=> "3781.51",
                "free_of_charge_indicator"=> false,
                "description"=> "DISCO FLAP ZIRCONIO MASSO",
                "code"=> "000000001571",
                "type_item_identifications_id"=> "4",
                "reference_price_id"=> "1",
                "price_amount"=> "4500",
                "base_quantity"=> "1",
                "tax_totals"=> array(array(
                    "tax_id"=> 1,
                    "tax_amount"=> "718.49",
                    "taxable_amount"=> "3781.51",
                    "percent"=> "19"
                  ))
              ),
              array(
                "invoiced_quantity"=> "1",
                "quantity_units_id"=> "1093",
                "line_extension_amount"=> "10084.03",
                "free_of_charge_indicator"=> false,
                "description"=> "LLANA LISA MANGO MADERA ECO",
                "code"=> "000000000350",
                "type_item_identifications_id"=> "4",
                "reference_price_id"=> "1",
                "price_amount"=> "12000",
                "base_quantity"=> "1",
                "tax_totals"=> array(array(
                 
                    "tax_id"=> 1,
                    "tax_amount"=> "1915.97",
                    "taxable_amount"=> "10084.03",
                    "percent"=> "19"
                 
                ))
              ),
              array(
                "invoiced_quantity"=> "1",
                "quantity_units_id"=> "1093",
                "line_extension_amount"=> "115966.39",
                "free_of_charge_indicator"=> false,
                "description"=> "PULIDORA+GAFAS+GUANTES+CAJA PLSTICA BLACK & DECKER",
                "code"=> "000000000714",
                "type_item_identifications_id"=> "4",
                "reference_price_id"=> "1",
                "price_amount"=> "138000",
                "base_quantity"=> "1",
                "tax_totals"=> array(array(
                 
                    "tax_id"=> 1,
                    "tax_amount"=> "22033.61",
                    "taxable_amount"=> "115966.39",
                    "percent"=> "19"
                 
                ))
              )
            )),
            "note_number"=> "990000100",
            "operation_type_id"=> 1,
            "type_document_id"=> 4,
            "allowance_charges"=> json_encode(array(
              array(
                "amount"=> "10000",
                "base_amount"=> "725000",
                "charge_indicator"=> false,
                "discount_id"=> 8,
                "allowance_charge_reason"=> "Total Nota"
              )
            )),
            "tax_totals"=> json_encode(array(
              array(
                "tax_id"=> 1,
                "tax_amount"=> "36004.21",
                "taxable_amount"=> "189495.79",
                "percent"=> "19"
              )
            ))
        );
		

  //echo json_encode($camposNotaDebito);
$resp = enviaDocumentoDian($urlApi,$tokenDianOperaciones,$camposNotaDebito);

 echo $resp;


?>