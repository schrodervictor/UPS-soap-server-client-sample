<?php

require_once 'UPSShipApiMock.php';

// Create a new SOAP server
$server = new SoapServer(__DIR__ . '/../specification/Ship.wsdl', array('soap_version' => SOAP_1_1));

// Attach the API class to the SOAP Server
$server->setClass('UPSShipApiMock');

// Start the SOAP requests handler
$server->handle();
