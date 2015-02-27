<?php
// Basic class to mock the UPS API for shipments
class UpsShipApiMock {

    protected $_isAuthenticated = false;
    protected $_headerErrors;
    protected $_response;

    public function hello() {
        return "Hello";
    }

    public function UPSSecurity($header) {

        // Authenticate against fake credentials
        if(
            'test' === $header->UsernameToken->Username
            ||
            'test123' === $header->UsernameToken->Password
            ||
            '999' === $header->ServiceAccessToken->AccessLicenseNumber
          )
        {
            $this->_isAuthenticated = true;
        } else {
            // Here I'm only simulating an error for the License Code
            $error = array();
            $error['ErrorDetail']['Severity'] = 'Authentication';
            $error['ErrorDetail']['PrimaryErrorCode']['Code'] = '250003';
            $error['ErrorDetail']['PrimaryErrorCode']['Description'] = 'Invalid Access License number';

            $this->_headerErrors = $error;

        }

    }

    public function ProcessShipment() {

        if($this->_headerErrors) return $this->deliver();

        $baseResponse = array();

        // Mandatory fields for a Response
        $baseResponse['ShipmentResults']['BillingWeight']['UnitOfMeasurement']['Code'] = 42;
        $baseResponse['ShipmentResults']['BillingWeight']['Weight'] = 'something';
        $baseResponse['Response']['ResponseStatus']['Code'] = 42;
        $baseResponse['Response']['ResponseStatus']['Description'] = 'Test';

        $this->_response = $baseResponse;

        return $this->deliver();
    }

    protected function deliver() {

        if ($this->_errors) {

            return new SoapFault(
                'Client',
                'An exception has been raised as a result of client data.',
                null,
                $this->_headerErrors,
                'ShipmentError'
            );
        } else {
            return $this->_response;
        }

    }
}

// Create a new SOAP server
$server = new SoapServer(__DIR__ . '/../specification/Ship.wsdl', array('soap_version' => SOAP_1_1));

// Attach the API class to the SOAP Server
$server->setClass('UpsShipApiMock');

// Start the SOAP requests handler
$server->handle();