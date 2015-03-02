<?php
class UPSSoapServerMock extends SoapServer {

    /**
     * @var string The WSDL specification for both server and client
     */
    protected $_wsdl = '/../specification/Ship.wsdl';

    /**
     * @var string The name of the class that holds the API methods
     */
    protected $_apiClassName = 'UPSShipApiMock';

    /**
     * @var array Options that will be passed to the SoapServer constructor
     */
    protected $_options = array('soap_version' => SOAP_1_1);

    public function __construct($wsdl = null, $options = null, $apiClassName = null) {

        // The default wsdl path is relative, so we need to prepend the
        // scrip absolute location
        if(is_null($wsdl)) $wsdl = __DIR__ . $this->_wsdl;

        // If the wsdl is passed to the constructor, then it's expected to
        // be a full path or URL
        else $this->_wsdl = $wsdl;

        // Set the default options, if needed
        if(is_null($options)) $options = $this->_options;
        else $this->_options = $options;

        // Set the default classname, if needed
        if(is_null($apiClassName)) $apiClassName = $this->_apiClassName;
        else $this->_apiClassName = $apiClassName;

        // Call the parent's constructor to create the native SoapServer
        parent::__construct($wsdl, $options);

        // Set the API class
        $this->setClass($apiClassName);

        return $this;
    }

    /**
     * Custom method handle
     *
     * Overrides the base method to allow indirect outputs
     * Specially useful for testing and post-processing the output
     *
     * @param mixed $request The SoapRequest, default to the plain POST body
     * @param boolean $directOutput Defines if it'll use SoapServer's default
     *        behavior or if the output should be returned
     *
     * @return void|string
     */
    public function handle($request = null, $directOutput = true) {

        ob_start();
        parent::handle();

        if($directOutput) {
             ob_end_flush();
        } else {
            $output = ob_get_contents();
            ob_end_clean();
            return $output;
        }
    }
}
