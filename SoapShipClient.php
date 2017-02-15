<?php

//Configuration
$access = UPS_ACCESS_LICENSE_NUMBER;
$userid = UPS_USERNAME;
$passwd = UPS_PASSWORD;
$wsdl = "Ship.wsdl";
$operation = "ProcessShipment";
$endpointurl =  UPS_ENDPOINT; //'https://wwwcie.ups.com/webservices/Ship';
$outputFileName = "XOLTResult.xml";

function processShipment() {
    //create soap request
    $requestoption['RequestOption'] = 'nonvalidate';
    $request['Request'] = $requestoption;

    $shipment['Description'] = 'Ship WS test';
    $shipper['Name'] = 'ShipperName';
    $shipper['AttentionName'] = 'ShipperZs Attn Name';
    $shipper['TaxIdentificationNumber'] = '123456';
    $shipper['TaxIDType'] = array('Code' => 1, 'Description' => 'string');
    $shipper['ShipperNumber'] = '';
    $address['AddressLine'] = '2311 York Rd';
    $address['City'] = 'Timonium';
    $address['StateProvinceCode'] = 'MD';
    $address['PostalCode'] = '21093';
    $address['CountryCode'] = 'US';
    $shipper['Address'] = $address;
    $phone['Number'] = '1115554758';
    $phone['Extension'] = '1';
    $shipper['Phone'] = $phone;
    $shipment['Shipper'] = $shipper;

    $shipto['Name'] = 'Happy Dog Pet Supply';
    $shipto['AttentionName'] = '1160b_74';
    $addressTo['AddressLine'] = '123 Main St';
    $addressTo['City'] = 'Roswell';
    $addressTo['StateProvinceCode'] = 'GA';
    $addressTo['PostalCode'] = '30076';
    $addressTo['CountryCode'] = 'US';
    $phone2['Number'] = '9225377171';
    $shipto['Address'] = $addressTo;
    $shipto['Phone'] = $phone2;
    $shipment['ShipTo'] = $shipto;

    $shipfrom['Name'] = 'T and T Designs';
    $shipfrom['AttentionName'] = '1160b_74';
    $addressFrom['AddressLine'] = '2311 York Rd';
    $addressFrom['City'] = 'Timonium';
    $addressFrom['StateProvinceCode'] = 'MD';
    $addressFrom['PostalCode'] = '21093';
    $addressFrom['CountryCode'] = 'US';
    $phone3['Number'] = '1234567890';
    $shipfrom['Address'] = $addressFrom;
    $shipfrom['Phone'] = $phone3;
    $shipment['ShipFrom'] = $shipfrom;

    $shipmentcharge['Type'] = '01';
    /*
    $creditcard['Type'] = '06';
    $creditcard['Number'] = '4716995287640625';
    $creditcard['SecurityCode'] = '864';
    $creditcard['ExpirationDate'] = '12/2013';
    $creditCardAddress['AddressLine'] = '2010 warsaw road';
    $creditCardAddress['City'] = 'Roswell';
    $creditCardAddress['StateProvinceCode'] = 'GA';
    $creditCardAddress['PostalCode'] = '30076';
    $creditCardAddress['CountryCode'] = 'US';
    $creditcard['Address'] = $creditCardAddress;
    $billshipper['CreditCard'] = $creditcard;
    */
    $billshipper['AccountNumber'] = '654654654';
    $shipmentcharge['BillShipper'] = $billshipper;
    $paymentinformation['ShipmentCharge'] = $shipmentcharge;
    $shipment['PaymentInformation'] = $paymentinformation;

    $service['Code'] = '03';
    $service['Description'] = 'Express';
    $shipment['Service'] = $service;

    $package['Description'] = 'Random package';
    $packaging['Code'] = '02';
    $packaging['Description'] = 'Nails';
    $package['Packaging'] = $packaging;
    $unit['Code'] = 'IN';
    $unit['Description'] = 'Inches';
    $dimensions['UnitOfMeasurement'] = $unit;
    $dimensions['Length'] = '7';
    $dimensions['Width'] = '5';
    $dimensions['Height'] = '2';
    $package['Dimensions'] = $dimensions;
    $unit2['Code'] = 'LBS';
    $unit2['Description'] = 'Pounds';
    $packageweight['UnitOfMeasurement'] = $unit2;
    $packageweight['Weight'] = '10';
    $package['PackageWeight'] = $packageweight;
    $shipment['Package'] = $package;

    $labelimageformat['Code'] = 'GIF';
    $labelimageformat['Description'] = 'GIF';
    $labelspecification['LabelImageFormat'] = $labelimageformat;
    $labelspecification['HTTPUserAgent'] = 'Mozilla/4.5';
    $shipment['LabelSpecification'] = $labelspecification;
    $request['Shipment'] = $shipment;

    echo "Request.......\n";
    print_r($request);
    echo "\n\n";
    return $request;
}

function processShipConfirm() {
    //create soap request
}

function processShipAccept() {
    //create soap request
}

try {
    $mode = array(
        'soap_version' => 'SOAP_1_1',  // use soap 1.1 client
        'trace' => 1
    );

    // initialize soap client
    $client = new SoapClient($wsdl , $mode);

    //set endpoint url
    $client->__setLocation($endpointurl);

    //create soap header
    $usernameToken['Username'] = $userid;
    $usernameToken['Password'] = $passwd;
    $serviceAccessLicense['AccessLicenseNumber'] = $access;
    $upss['UsernameToken'] = $usernameToken;
    $upss['ServiceAccessToken'] = $serviceAccessLicense;

    $header = new SoapHeader(
        'http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0',
        'UPSSecurity',
        $upss
    );

    $client->__setSoapHeaders($header);

    if(strcmp($operation,"ProcessShipment") == 0 ) {
        //get response
        $resp = $client->__soapCall('ProcessShipment',array(processShipment()));

        echo '=======RESPONSE =======';

        var_dump($resp);

        //get status
        echo "Response Status: " . $resp->Response->ResponseStatus->Description ."\n";

        //save soap request and response to file
        $fw = fopen($outputFileName , 'w');
        fwrite($fw , "Request: \n" . $client->__getLastRequest() . "\n");
        fwrite($fw , "Response: \n" . $client->__getLastResponse() . "\n");
        fclose($fw);

    } else if (strcmp($operation , "ProcessShipConfirm") == 0) {
        //get response
        $resp = $client->__soapCall('ProcessShipConfirm',array(processShipConfirm()));

        //get status
        echo "Response Status: " . $resp->Response->ResponseStatus->Description ."\n";

        //save soap request and response to file
        $fw = fopen($outputFileName , 'w');
        fwrite($fw , "Request: \n" . $client->__getLastRequest() . "\n");
        fwrite($fw , "Response: \n" . $client->__getLastResponse() . "\n");
        fclose($fw);

    } else {
        $resp = $client->__soapCall('ProcessShipAccept',array(processShipAccept()));

        //get status
        echo "Response Status: " . $resp->Response->ResponseStatus->Description ."\n";

        //save soap request and response to file
        $fw = fopen($outputFileName ,'w');
        fwrite($fw , "Request: \n" . $client->__getLastRequest() . "\n");
        fwrite($fw , "Response: \n" . $client->__getLastResponse() . "\n");
        fclose($fw);
    }

} catch(Exception $ex) {
    print_r ($ex);
}
