<?php
// Basic class to mock the UPS API for shipments
class UpsShipApiMock {
    public function hello() {
        return "Hello";
    }
    public function ProcessShipment() {
        return array(
            'ShipmentResults' => array(
                'BillingWeight' => array(
                    'UnitOfMeasurement' => array(
                        'Code' => '42',
                        'Description' => 'something',
                    ),
                    'Weight' => 'something',
                ),
            ),
            'Response' => array(
                'ResponseStatus' => array(
                    'Code' => '42',
                    'Description' => 'Test',
                ),
            ),
        );
    }
}

// Create a new SOAP server
$server = new SoapServer(__DIR__ . '/../specification/Ship.wsdl', array('soap_version' => SOAP_1_1));

// Attach the API class to the SOAP Server
$server->setClass('UpsShipApiMock');

// Start the SOAP requests handler
$server->handle();