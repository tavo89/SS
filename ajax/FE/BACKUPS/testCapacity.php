<?php
include_once("../../Conexxx.php");
include_once("facturaElectronica.class.php");
// datos factura
$capacity = '';
$option_capacity = array(
                
                'restApi' => array(
                    'dates' => "2020-11-22,2020-11-23,2020-11-24,2020-11-25,2020-11-26,2020-11-27,2020-11-28",
                    'areas' => 'DCE021',
                    'determineAreaByWorkZone' => 'false',
                    'estimateDuration' => 'false',
                    'estimateTravelTime' => 'false',
                    'determineCategory' => 'true',
                    'activityType' => 'AR',
                    'XA_WorkOrderSubtype' => 'HFCAR',
                    'XA_Red' => 'Bidireccional',
                    'XA_CodigoHash' => 'NARTOC_1',
                    'XA_Idcity' => 'BOG',
                    'Node' => 'SAV'
                )
            );
			
			
///$infoWS = WebservicesModel::findOneByDescriptionMethod('');
$infoWS['URL'] = 'https://amx-res-co.etadirect.com/rest/ofscCapacity/v1/activityBookingOptions/?'. http_build_query($option_capacity['restApi']);

$URL ='https://amx-res-co.etadirect.com/rest/ofscCapacity/v1/activityBookingOptions/?dates=2020-11-22,2020-11-23,2020-11-24,2020-11-25,2020-11-26,2020-11-27,2020-11-28&areas=DCE021&determineAreaByWorkZone=false&estimateDuration=false&estimateTravelTime=false&determineCategory=true&activityType=AR&XA_WorkOrderSubtype=HFCAR&XA_Red=Bidireccional&XA_CodigoHash=NARTOC_1&XA_Idcity=BOG&Node=SAV';
                        /*$capacityRestApi = new CurlClient();

                        $capacityRestApi->setMethod('GET')
                                        ->setServer($infoWS['URL'])
                                        ->setAuthHeader()
                                        ->addData($option_capacity['restApi']);
                        $capacity = json_decode($capacityRestApi->call(),true);*/

        $authBasic = base64_encode('a2744b78301d7584e9dc79b33c3c83557@amx-res-co:7ad3489425f2b7027ee156e57d036a86d8424c79d30a5941c825b81c1e815d11');
		$auth2 ='YTI3NDRiNzgzMDFkNzU4NGU5ZGM3OWIzM2MzYzgzNTU3QGFteC1yZXMtY286N2FkMzQ4OTQyNWYyYjcwMjdlZTE1NmU1N2QwMzZhODZkODQyNGM3OWQzMGE1OTQxYzgyNWI4MWMxZTgxNWQxMQ==';
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $infoWS['URL']);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		                                             'Authorization: Basic '.$authBasic));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        if ($error) {
            echo $error;
        } else {
            $capacity = json_decode($response);
        }

echo 'paramsssssss:<br>';
var_dump($option_capacity['restApi']);

                        echo '<br>respuesta REST:';
                        var_dump($capacity);

?>