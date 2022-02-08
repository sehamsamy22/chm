<?php

namespace App\Modules\Shipping\Http\Controllers\Admin;

use App\Modules\Order\Entities\Order;
use App\Modules\Shipping\Methods\Fastlo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ShippingController extends Controller
{
    private $fastlo;

    public function __construct(Fastlo $fastlo)
    {
        $this->fastlo = $fastlo;
    }

    public function AddShipment(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        // For senderAddress you can use the default address in Fastlo database
        // $senderAddress = array('use_default_address' => 1);,.
        // or you can send different senderAddress for each new shipment
        $senderAddress = array(
            'use_default_address' => 0,
            'sender_name' => 'Real Sender Name',
            'sender_mobile1' => '0532120000',
            'sender_mobile2' => '',
            'sender_country' => 'SA',
            'sender_city' => 'Riyadh',
            'sender_area' => 'Rabwah',
            'sender_street' => '',
            'sender_additional' => '',
            'sender_latitude' => 0,
            'sender_longitude' => 0
        );
        $receiverAddress = array(
            'receiver_name' => 'Turki',
            'receiver_mobile1' => '0532120000',
            'receiver_mobile2' => '',
            'receiver_country' => 'SA',
            'receiver_city' => 'Riyadh',
            'receiver_area' => 'Alrimal',
            'receiver_street' => '',
            'receiver_additional' => '',
            'receiver_latitude' => 0,
            'receiver_longitude' => 0
        );
        $shipmentData = array(
            'collect_cash_amount' => 500,
            'number_of_pieces' => 1,
            'reference' => '12345' ,           // usually used as order number
            "mode"=> "testing", // add this line if you want to add new shipment for testing
        );
        // Use order_data if you want to send order data to us (Use it only if we have your stock in our warehouse)
        /*$shipmentData['order_data'] = array();
        $shipmentData['order_data']['product_1'] = array(
            'product_quantity' => 1,
            'product_name' => 'iPhone 8',
            'product_options' => '128GB - Black',
            'product_sku' => '4321'
        );
        $shipmentData['order_data']['product_2'] = array(
            'product_quantity' => 1,
            'product_name' => 'iPhone Charger',
            'product_options' => '',
            'product_sku' => '4325'
        );*/
        $response = $this->fastlo->fastloAddShipment($senderAddress, $receiverAddress, $shipmentData);

        if ($response['status_code'] == 200) {
            $output = $response['output'];
            $trackNumber = $output['tracknumber'];
            // save TrackNumber in database
            $writeCode = $output['writecode'];
            // Ask client to write this code in the box (useful to make pickup process faster and more accurate)
            // echo for testing
            echo $trackNumber;
        } else {
            // resolve the issue and retry
            // echo for testing
            echo $response['error'];
        }

    }

    public function GetCODCitiesList(Request $request)
    {
        $response = $this->fastlo->fastloGetCODCitiesList($request['country']);
        if ($response['status_code'] == 200) {
            $output = $response['output'];
            $codCitiesListEn = $output['cities_en'];
            $codCitiesListAr = $output['cities_ar'];
            // echo for testing
            header('Content-Type: text/html; charset=utf-8');
            foreach ($codCitiesListEn as $cityName) {
                echo $cityName . '<br>';
            }
            echo '<br>';
            foreach ($codCitiesListAr as $cityName) {
                echo $cityName . '<br>';
            }
        } else {
            // resolve the issue and retry

            // echo for testing
            echo $response['error'];
        }
    }

    public function GetPickupCitiesList(Request $request)
    {
        $response = $this->fastlo->fastloGetPickupCitiesList($request['country']);
        if ($response['status_code'] == 200) {
            $output = $response['output'];
            $pickupCitiesListEn = $output['cities_en'];
            $pickupCitiesListAr = $output['cities_ar'];
            // echo for testing
            header('Content-Type: text/html; charset=utf-8');
            foreach ($pickupCitiesListEn as $cityName) {
                echo $cityName . '<br>';
            }
            echo '<br>';
            foreach ($pickupCitiesListAr as $cityName) {
                echo $cityName . '<br>';
            }
        } else {
            // resolve the issue and retry

            // echo for testing
            echo $response['error'];
        }
    }

    public function CanBeCanceled(Request $request)
    {
        $trackNumber = '111';
        $response = $this->fastlo->fastloCanBeCanceled($trackNumber);
        if ($response['status_code'] == 200) {
            $output = $response['output'];

            $canBeCanceled = $output['can_be_canceled'];
            if ($canBeCanceled == 1) {
                // Yes, can be canceled
                echo 'Yes';
            } else {
                // No, cannot be canceled
                echo 'No';
            }
        } else {
            // resolve the issue and retry

            // echo for testing
            echo $response['error'];
        }
    }

    public function CancelShipment(Request $request)
    {
        $trackNumber = '111';
        $response = $this->fastlo->fastloCancelShipment($trackNumber);
        if ($response['status_code'] == 200) {
            $output = $response['output'];

            $canceledStatus = $output['canceled_status'];
            if ($canceledStatus == 1) {
                // Canceled
                echo 'Canceled';
            } else {
                // Cannot be canceled
                echo 'Cannot be canceled';
            }
        } else {
            // resolve the issue and retry

            // echo for testing
            echo $response['error'];
        }
    }

    public function GetShipmentLabel(Request $request)
    {
        $trackNumber = '111';
        $pdfFormat = 'base64';
        $labelSize = '4in_2in';
        $optionalBarcode = 'none';
        $response = $this->fastlo->fastloGetShipmentLabel($trackNumber, $pdfFormat, $labelSize, $optionalBarcode);
        if ($response['status_code'] == 200) {
            $output = $response['output'];

            $pdfBase64 = $output['shipment_label'];

            // echo for testing
            $pdfBinary = base64_decode($pdfBase64);
            header('Content-type: application/pdf');
            echo $pdfBinary;
        } else {
            // resolve the issue and retry

            // echo for testing
            echo $response['error'];
        }
    }

    public function GetShipmentPrices(Request $request)
    {
        $response = $this->fastlo->fastloGetShipmentPrices();
        if ($response['status_code'] == 200) {
            $output = $response['output'];

            $deliveryPrice = $output['delivery'];
            $shippingPrice = $output['shipping'];

            // echo for testing
            echo $deliveryPrice . ' - ' . $shippingPrice;
        } else {
            // resolve the issue and retry

            // echo for testing
            echo $response['error'];
        }
    }

    public function GetShipmentsStatus(Request $request)
    {
        //$trackNumbers = '111';			// Get status for one tracknumber
        $trackNumbers = '111,112,113';        // Get status for list of tracknumbers
        $response = $this->fastlo->fastloGetShipmentsStatus($trackNumbers);
        if ($response['status_code'] == 200) {
            $output = $response['output'];
            $statusList = $output['status_list'];
            // echo All for testing
            foreach ($statusList as $trackNumber => $statusCode) {
                echo $trackNumber . ' => ' . $statusCode . '<br>';
            }
            // echo Only One for testing
            //echo $statusList['112'];
        } else {
            // resolve the issue and retry

            // echo for testing
            echo $response['error'];
        }
    }

    public function ReadShipment(Request $request)
    {

        $response = $this->fastlo->fastloReadShipment($request['tracknumber']);
        if ($response['status_code'] == 200) {
            $output = $response['output'];
            $shipmentData = $output['shipment_data'];
            $collectCashAmount = $shipmentData['collect_cash_amount'];
            $reference = $shipmentData['reference'];
            $createdDate = $shipmentData['created_date'];
            $canBeCanceled = $shipmentData['can_be_canceled'];
            $statusCode = $shipmentData['status_code'];
            $statusName = $shipmentData['status_name'];
            //$statusCode and code list:
            // 10 New
            // 20 Pickup In Progress
            // 30 Picked Up
            // 40 In Distribution Center
            // 50 Shipping In Progress
            // 60 Delivery In Progress
            // 70 Canceled
            // 80 Returned
            // 90 Dispatched
            // 100 Delivered
            if ($statusCode == 90 && isset($shipmentData['dispatch_info'])) {
                // if shipment status is Dispatched, it mean we hand it over to another carrier (like Aramex or SMSA Express)
                // use this information to get tracking info
                $dispatchInfo = $shipmentData['dispatch_info'];
                $carrierNameEn = $dispatchInfo['carrier_name_en'];
                $carrierNameAr = $dispatchInfo['carrier_name_ar'];
                $carrierPhone = $dispatchInfo['carrier_phone'];
                $carrierTrackingLink = $dispatchInfo['carrier_tracking_link'];
            }
            return $output;
        } else {
            echo $response['error'];
        }

    }

}
