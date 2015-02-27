<?php
// Basic class to mock the UPS API for shipments
class UpsShipApiMock {

    protected $_isAuthenticated = false;
    protected $_headerErrors;
    protected $_response;

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
            $this->_headerErrors = 250003;


        }

    }

    protected function _validateHeader() {

        // Authentication control is done in the header method.
        // If the user is not authenticated, the header method has already set
        // the appropriate header error.
        if(!$this->_isAuthenticated) {
            return false;
        }


    }

    public function ProcessShipment() {

        if(!$this->_validateHeader() || $this->_headerErrors) {
            // Header errors is set inside the UPSSecurity method and/or in _validateHeader
            return $this->deliverError($this->_headerErrors);
        }

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

        return $this->_response;

    }

    protected function deliverError($code) {

        $errors = array();

        $errors[250003]['ErrorDetail']['Severity'] = 'Authentication';
        $errors[250003]['ErrorDetail']['PrimaryErrorCode']['Code'] = '250003';
        $errors[250003]['ErrorDetail']['PrimaryErrorCode']['Description'] = 'Invalid Access License number';

        return new SoapFault(
            'Client',
            'An exception has been raised as a result of client data.',
            null,
            $errors[$code],
            'ShipmentError'
        );

    }
}

// Create a new SOAP server
$server = new SoapServer(__DIR__ . '/../specification/Ship.wsdl', array('soap_version' => SOAP_1_1));

// Attach the API class to the SOAP Server
$server->setClass('UpsShipApiMock');

// Start the SOAP requests handler
$server->handle();