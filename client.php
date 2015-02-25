<?php
# HelloClient.php
# Copyright (c) 2005 by Dr. Herong Yang
#

require_once 'client-config.php';

$mode = array
(
    'soap_version' => 'SOAP_1_1',  // use soap 1.1 client
    'trace' => 1,
    'location' => UPS_MOCK_ENDPOINT,
);

// initialize soap client
$client = new SoapClient('Ship.wsdl', $mode);


//create soap header
$usernameToken['Username'] = UPS_MOCK_USERNAME;
$usernameToken['Password'] = UPS_MOCK_PASSWORD;
$serviceAccessLicense['AccessLicenseNumber'] = UPS_MOCK_ACCESS_LICENSE_NUMBER;
$upss['UsernameToken'] = $usernameToken;
$upss['ServiceAccessToken'] = $serviceAccessLicense;

$header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0', 'UPSSecurity', $upss);

$client->__setSoapHeaders($header);

//create soap request
$requestoption['RequestOption'] = 'nonvalidate';
$request['Request'] = $requestoption;

$shipment['Description'] = 'Ship WS test';
$shipper['Name'] = 'ShipperName';
$shipper['AttentionName'] = 'ShipperZs Attn Name';
$shipper['TaxIdentificationNumber'] = '123456';
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
$shipmentcharge['BillShipper'] = $billshipper;
$paymentinformation['ShipmentCharge'] = $shipmentcharge;
$shipment['PaymentInformation'] = $paymentinformation;

$service['Code'] = '03';
$service['Description'] = 'Express';
$shipment['Service'] = $service;

$package['Description'] = '';
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
$request['LabelSpecification'] = $labelspecification;
$request['Shipment'] = $shipment;





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