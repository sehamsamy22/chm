<?php

namespace App\Modules\Shipping\Methods;

class Fastlo extends ShippingContract
{
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = '60182dcaaefd87b3b8c88ec56b0441ce05j87at9cyqgchc2k7o3u7xrhm3bksax';
    }


    function fastloCall($method, $data, $apiKey = '')
    {
        if ($apiKey == '') {
            $apiKey = $this->apiKey;
        }
        $url = 'https://fastlo.com/api/v1/' . $method;
        $request = json_encode(array('request' => $data), JSON_UNESCAPED_UNICODE);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('fastlo-api-key: ' . $apiKey, 'Content-Type: application/json; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $result = curl_exec($ch);
        curl_close($ch);

        if ($method == 'label_shipment' && $data['pdf_format'] == 'binary') {
            header('Content-type: application/pdf');
            echo $result;
        } else {
            $result = json_decode($result, true);
            if (!is_array($result) || !isset($result['status_code'])) {
                $result = array();
                $result['status_code'] = 404;
                $result['error'] = 'Not Found Or Server Error';
            }
            return $result;
        }
    }

    /**
     * @param array $senderAddress An array of sender address information
     * @param array $receiverAddress An array of receiver address information
     * @param array $shipmentData An array of shipment options and reference
     * @return array
     */
    function fastloAddShipment($senderAddress, $receiverAddress, $shipmentData, $apiKey = '')
    {
        $method = 'add_shipment';
        $data = array(
            'sender_address' => $senderAddress,
            'receiver_address' => $receiverAddress,
            'shipment_data' => $shipmentData
        );

        $response = $this->fastloCall($method, $data, $apiKey);
        return $response;
    }

    function fastloReadShipment($trackNumber, $apiKey = '')
    {
        $method = 'read_shipment';
        $data = array('tracknumber' => $trackNumber);
        $response = $this->fastloCall($method, $data, $apiKey);
        return $response;
    }

    function fastloGetShipmentLabel($trackNumber, $pdfFormat, $labelSize, $optionalBarcode, $apiKey = '')
    {
        $method = 'label_shipment';
        $data = array(
            'tracknumber' => $trackNumber,
            'pdf_format' => $pdfFormat,
            'label_size' => $labelSize,
            'optional_barcode' => $optionalBarcode
        );
        $response = $this->fastloCall($method, $data, $apiKey);
        return $response;
    }

    function fastloGetShipmentsStatus($trackNumbers, $apiKey = '')
    {
        $method = 'status_shipments';
        if (!is_array($trackNumbers)) {
            $trackNumbers = explode(',', $trackNumbers);
        }
        $data = array('tracknumbers_list' => $trackNumbers);
        $response = $this->fastloCall($method, $data, $apiKey);
        return $response;
    }

    function fastloCanBeCanceled($trackNumber, $apiKey = '')
    {
        $method = 'can_cancel_shipment';
        $data = array('tracknumber' => $trackNumber);
        $response = $this->fastloCall($method, $data, $apiKey);
        return $response;
    }

    function fastloCancelShipment($trackNumber, $apiKey = '')
    {
        $method = 'cancel_shipment';
        $data = array('tracknumber' => $trackNumber);
        $response = $this->fastloCall($method, $data, $apiKey);
        return $response;
    }

    function fastloGetShipmentPrices($apiKey = '')
    {
        $method = 'prices_shipment';
        $data = array(
            'delivery' => 8,
            'shipping' => 18
        );
        $response = $this->fastloCall($method, $data, $apiKey);
        return $response;
    }

    function fastloGetPickupCitiesList($country, $apiKey = '')
    {
        $method = 'pickup_cities';
        $data = array('country' => $country);
        $response = $this->fastloCall($method, $data, $apiKey);
        return $response;
    }

    function fastloGetCODCitiesList($country, $apiKey = '')
    {
        $method = 'cod_cities';
        $data = array('country' => $country);
        $response = $this->fastloCall($method, $data, $apiKey);
        return $response;
    }

}
