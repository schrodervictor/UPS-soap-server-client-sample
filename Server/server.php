<?php
// Basic class to mock the UPS API for shipments
class UpsShipApiMock {
    public function hello() {
        return "Hello";
    }
    public function ProcessShipment() {

        $baseResponse = new stdClass();

        // Mandatory fields for a Response
        $baseResponse->ShipmentResults->BillingWeight->UnitOfMeasurement->Code = 42;
        $baseResponse->ShipmentResults->BillingWeight->Weight = 'something';
        $baseResponse->Response->ResponseStatus->Code = 42;
        $baseResponse->Response->ResponseStatus->Description = 'Test';

        return $baseResponse;
    }
}

// Create a new SOAP server
$server = new SoapServer(__DIR__ . '/../specification/Ship.wsdl', array('soap_version' => SOAP_1_1));

// Attach the API class to the SOAP Server
$server->setClass('UpsShipApiMock');

// Start the SOAP requests handler
$server->handle();