<?php
// Basic class to mock the UPS API for shipments
class UPSShipApiMock {

    protected $_isHeaderPresent = false;
    protected $_isAuthenticated = false;
    protected $_headerErrors;
    protected $_response;

    public function UPSSecurity($header) {

        // If this method is called, the UPSSecurity header is present
        $this->_isHeaderPresent = true;

        // Authenticate against fake credentials
        if(
            'test' === $header->UsernameToken->Username
            &&
            'test123' === $header->UsernameToken->Password
            &&
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

        // If this flag is false, the header method was never called
        // and that means the header was not sent
        if(!$this->_isHeaderPresent) {
            $this->_headerErrors = 10002;
            return false;
        }

        // Authentication control is done in the header method.
        // If the user is not authenticated, the header method has already set
        // the appropriate header error.
        if(!$this->_isAuthenticated) {
            return false;
        }

        // If it passed through all previous conditions, the user is correctly authenticated
        return true;

    }

    public function ProcessShipment($request) {

        if(!$this->_validateHeader()) {
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

    public function ProcessShipConfirm($request) {

        if(!$this->_validateHeader()) {
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

        $errors[10002]['ErrorDetail']['Severity'] = 'Authentication';
        $errors[10002]['ErrorDetail']['PrimaryErrorCode']['Code'] = '10002';
        $errors[10002]['ErrorDetail']['PrimaryErrorCode']['Description'] = 'The XML document is well formed but the document is not valid';
        $errors[10002]['ErrorDetail']['PrimaryErrorCode']['Digest'] = 'Authentication Header not provided.';

        return new SoapFault(
            'Client',
            'An exception has been raised as a result of client data.',
            null,
            $errors[$code],
            'ShipmentError'
        );

    }
}
