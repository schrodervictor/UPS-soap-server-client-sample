<?php

require_once 'client-config.php';

$mode = array
(
    'soap_version' => SOAP_1_1,  // use soap 1.1 client
    'trace' => 1,
    'location' => UPS_MOCK_ENDPOINT,
);

// initialize soap client
$client = new SoapClient('specification/Ship.wsdl', $mode);


// Create UPS SOAP security header
$UPSSecurity = array();
$UPSSecurity['UsernameToken']['Username'] = UPS_MOCK_USERNAME;
$UPSSecurity['UsernameToken']['Password'] = UPS_MOCK_PASSWORD;
$UPSSecurity['ServiceAccessToken']['AccessLicenseNumber'] = UPS_MOCK_ACCESS_LICENSE_NUMBER;

$header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0',
    'UPSSecurity', $UPSSecurity);

$client->__setSoapHeaders($header);

// Create a simple UPS SOAP request
$request = array();

// Request option
$request['Request']['RequestOption'] = 'nonvalidate';

// Shipment
$request['Shipment']['Description'] = 'Ship WS test';

// Shipper
$request['Shipment']['Shipper']['Name'] = 'ShipperName';
$request['Shipment']['Shipper']['AttentionName'] = 'ShipperZs Attn Name';
$request['Shipment']['Shipper']['TaxIdentificationNumber'] = '123456';
$request['Shipment']['Shipper']['ShipperNumber'] = '';
$request['Shipment']['Shipper']['Address']['AddressLine'] = '2311 York Rd';
$request['Shipment']['Shipper']['Address']['City'] = 'Timonium';
$request['Shipment']['Shipper']['Address']['StateProvinceCode'] = 'MD';
$request['Shipment']['Shipper']['Address']['PostalCode'] = '21093';
$request['Shipment']['Shipper']['Address']['CountryCode'] = 'US';
$request['Shipment']['Shipper']['Phone']['Number'] = '1115554758';
$request['Shipment']['Shipper']['Phone']['Extension'] = '1';

// Ship To

$request['Shipment']['ShipTo']['Name'] = 'Happy Dog Pet Supply';
$request['Shipment']['ShipTo']['AttentionName'] = '1160b_74';
$request['Shipment']['ShipTo']['Address']['AddressLine'] = '123 Main St';
$request['Shipment']['ShipTo']['Address']['City'] = 'Roswell';
$request['Shipment']['ShipTo']['Address']['StateProvinceCode'] = 'GA';
$request['Shipment']['ShipTo']['Address']['PostalCode'] = '30076';
$request['Shipment']['ShipTo']['Address']['CountryCode'] = 'US';
$request['Shipment']['ShipTo']['Address']['Phone']['Number'] = '9225377171';

// Ship From
$request['Shipment']['ShipFrom']['Name'] = 'T and T Designs';
$request['Shipment']['ShipFrom']['AttentionName'] = '1160b_74';
$request['Shipment']['ShipFrom']['Address']['AddressLine'] = '2311 York Rd';
$request['Shipment']['ShipFrom']['Address']['City'] = 'Timonium';
$request['Shipment']['ShipFrom']['Address']['StateProvinceCode'] = 'MD';
$request['Shipment']['ShipFrom']['Address']['PostalCode'] = '21093';
$request['Shipment']['ShipFrom']['Address']['CountryCode'] = 'US';
$request['Shipment']['ShipFrom']['Phone']['Number'] = '1234567890';

// Payment information
$request['Shipment']['PaymentInformation']['ShipmentCharge']['Type'] = '01';
$request['Shipment']['PaymentInformation']['ShipmentCharge']['BillShipper']
    ['CreditCard']['Type'] = '06';
$request['Shipment']['PaymentInformation']['ShipmentCharge']['BillShipper']
    ['CreditCard']['Number'] = '4716995287640625';
$request['Shipment']['PaymentInformation']['ShipmentCharge']['BillShipper']
    ['CreditCard']['SecurityCode'] = '864';
$request['Shipment']['PaymentInformation']['ShipmentCharge']['BillShipper']
    ['CreditCard']['ExpirationDate'] = '12/2013';
$request['Shipment']['PaymentInformation']['ShipmentCharge']['BillShipper']
    ['CreditCard']['Address']['AddressLine'] = '2010 warsaw road';
$request['Shipment']['PaymentInformation']['ShipmentCharge']['BillShipper']
    ['CreditCard']['Address']['City'] = 'Roswell';
$request['Shipment']['PaymentInformation']['ShipmentCharge']['BillShipper']
    ['CreditCard']['Address']['StateProvinceCode'] = 'GA';
$request['Shipment']['PaymentInformation']['ShipmentCharge']['BillShipper']
    ['CreditCard']['Address']['PostalCode'] = '30076';
$request['Shipment']['PaymentInformation']['ShipmentCharge']['BillShipper']
    ['CreditCard']['Address']['CountryCode'] = 'US';

// UPS service
$request['Shipment']['Service']['Code'] = '03';
$request['Shipment']['Service']['Description'] = 'Express';

// Package
$request['Shipment']['Package']['Description'] = '';
$request['Shipment']['Package']['Packaging']['Code'] = '02';
$request['Shipment']['Package']['Packaging']['Description'] = 'Nails';

$request['Shipment']['Package']['Packaging']['Dimensions']['Length'] = '7';
$request['Shipment']['Package']['Packaging']['Dimensions']['Width'] = '5';
$request['Shipment']['Package']['Packaging']['Dimensions']['Height'] = '2';

$request['Shipment']['Package']['Packaging']['Dimensions']
    ['UnitOfMeasurement']['Code'] = 'IN';
$request['Shipment']['Package']['Packaging']['Dimensions']
    ['UnitOfMeasurement']['Description'] = 'Inches';

$request['Shipment']['Package']['PackageWeight']['Weight'] = '10';
$request['Shipment']['Package']['PackageWeight']
    ['UnitOfMeasurement']['Code'] = 'LBS';
$request['Shipment']['Package']['PackageWeight']
    ['UnitOfMeasurement']['Description'] = 'Pounds';

$request['LabelSpecification']['HTTPUserAgent'] = 'Mozilla/4.5';
$request['LabelSpecification']['LabelImageFormat']['Code'] = 'GIF';
$request['LabelSpecification']['LabelImageFormat']['Description'] = 'GIF';

try {
    $return = $client->ProcessShipment($request);
} catch (Exception $e) {

}

//echo "\nReturning value of __soapCall() call:";
//var_dump($return);

echo "\nDumping request headers:\n" . $client->__getLastRequestHeaders();

echo "\nDumping request:\n" . $client->__getLastRequest() ;

echo "\nDumping response headers:\n" . $client->__getLastResponseHeaders();

echo "\nDumping response:\n" . $client->__getLastResponse();