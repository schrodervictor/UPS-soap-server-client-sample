<?php

class UPSSoapClient extends SoapClient {

    /**
     * @var string The WSDL specification for both server and client
     */
    protected $_wsdl = '/specification/Ship.wsdl';

    /**
     * @var array Options that will be passed to the SoapClient constructor
     */
    protected $_options = array('soap_version' => SOAP_1_1);

    public function __construct($wsdl = null, $options = array(), $debugMode = false) {

        // The default wsdl path is relative, so we need to prepend the
        // script absolute location
        if(is_null($wsdl)) $wsdl = __DIR__ . $this->_wsdl;

        // If the wsdl is passed to the constructor, then it's expected to
        // be a full path or URL
        else $this->_wsdl = $wsdl;

        // Merge the default options with those passed to the constructor
        $this->_options = $options + $this->_options;

        // Turn the debug mode on if needed
        if($debugMode) {
            $this->_options['trace'] = 1;
        }

        // Call the parent's constructor to create the native SoapClient
        parent::__construct($wsdl, $this->_options);

        return $this;

    }

    public function setHeader($headerArray = array()) {

        if(empty($headerArray)) $headerArray = $this->_getDefaultHeaderArray();

        $header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0',
            'UPSSecurity', $headerArray);

        $this->__setSoapHeaders($header);

        return $this;

    }

    protected function _getDefaultHeaderArray() {

        $UPSSecurity = array();
        $UPSSecurity['UsernameToken']['Username'] = UPS_MOCK_USERNAME;
        $UPSSecurity['UsernameToken']['Password'] = UPS_MOCK_PASSWORD;
        $UPSSecurity['ServiceAccessToken']['AccessLicenseNumber'] = UPS_MOCK_ACCESS_LICENSE_NUMBER;

        return $UPSSecurity;

    }

}
