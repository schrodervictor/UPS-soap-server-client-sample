<?php
require_once 'UPSShipApiMock.php';

require_once 'UPSSoapServerMock.php';

// Create a new UPS SOAP server
$server = new UPSSoapServerMock();

// Start the SOAP requests handler
$server->handle();
