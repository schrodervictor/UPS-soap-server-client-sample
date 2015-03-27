<?php
namespace UPS;

class ShipmentRequest {

    // Mandatory nodes
    public $Request;
    public $Shipment;

    // Optional nodes
    public $LabelSpecification;
    public $ReceiptSpecification;

    public function __construct(
        $Request,
        $Shipment,
        $LabelSpecification = null,
        $ReceiptSpecification = null
    ) {

        $this->Request = new \UPS\Common\Request($Request);
        $this->Shipment;
        $this->LabelSpecification;
        $this->ReceiptSpecification;

    }
}
