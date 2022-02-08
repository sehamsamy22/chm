<?php

namespace App\Modules\Shipping\Methods;

use App\Models\Shipping\OrderPickup;
use App\Models\Shipping\Pickup;
use App\Modules\Order\Entities\Order;
use App\Modules\Shipping\Entities\Shipment;
use App\Modules\Warehouse\Entities\Warehouse;
use App\Payment\PaymentMethods;
use Facades\App\Models\Services\PushService;
use SoapFault;

class Aramex extends ShippingContract
{
    private $accountNumber;
    private $userName;
    private $password;
    private $accountPin;
    private $accountEntity;
    private $accountCountryCode;

    public function __construct()
    {
        $credentials = $this->getCredentials('aramex');
        $this->accountNumber = $credentials["account_number"];
        $this->userName = $credentials["username"];
        $this->password = $credentials["password"];
        $this->accountPin = $credentials["account_pin"];
        $this->accountEntity = $credentials["account_entity"];
        $this->accountCountryCode = $credentials["account_country_code"];
    }

    public function CreatePickup($request)
    {
        $warehouse = Warehouse::findOrFail($request['warehouse_id']);
        $order = Order::findOrFail($request['order_id']);
        $ConsigneeName = $order->user->name;
        $ConsigneePhone = $order->user->phone;
        $ConsigneeEmail = $order->user->email;
        $ConsigneeAddress = $order->user->address();
        $ConsigneeCity = $ConsigneeAddress->area->city->getTranslations('name')['en'] ?? 'Saudi Arabia';
        $warehouse_ar_name = $warehouse->getTranslations('name')['ar'];
        $warehouse_ar_city = $warehouse->getTranslations('city')['en'];
        $soapClient = new \SoapClient("https://ws.dev.aramex.net/ShippingAPI.V2/Shipping/Service_1_0.svc?wsdl");
        echo '<pre>';
        $params = array(
            'Shipments' => array(
                'Shipment' => array(
                    'Shipper' => array(
                        'Reference1' => $warehouse->manager,
                        'Reference2' => 'Ref 222222',
                        'AccountNumber' => '4004636',
                        'PartyAddress' => array(
                            'Line1' => 'Mecca St',
                            'Line2' => '',
                            'Line3' => '',
                            'City' => $warehouse_ar_city,
                            'StateOrProvinceCode' => '',
                            'PostCode' => '',
                            'CountryCode' => 'EG'
                        ),
                        'Contact' => array(
                            'Department' => '',
                            'PersonName' => $warehouse_ar_name,
                            'Title' => '',
                            'CompanyName' => $warehouse_ar_name,
                            'PhoneNumber1' => $warehouse->phone,
                            'PhoneNumber1Ext' => '125',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' => '07777777',
                            'EmailAddress' => 'michael@aramex.com',
                            'Type' => ''
                        ),
                    ),
                    'Consignee' => array(
                        'Reference1' => 'Ref 333333',
                        'Reference2' => 'Ref 444444',
                        'AccountNumber' => '',
                        'PartyAddress' => array(
                            'Line1' => $ConsigneeAddress->address,
                            'Line2' => '',
                            'Line3' => '',
                            'City' => $ConsigneeCity,
                            'StateOrProvinceCode' => '',
                            'PostCode' => '',
                            'CountryCode' => 'AE'
                        ),

                        'Contact' => array(
                            'Department' => '',
                            'PersonName' => $ConsigneeName,
                            'Title' => '',
                            'CompanyName' => 'Aramex',
                            'PhoneNumber1' => '6666666',
                            'PhoneNumber1Ext' => '155',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' => $ConsigneePhone,
                            'EmailAddress' => $ConsigneeEmail,
                            'Type' => ''
                        ),
                    ),
                    'ThirdParty' => array(
                        'Reference1' => '',
                        'Reference2' => '',
                        'AccountNumber' => '',
                        'PartyAddress' => array(
                            'Line1' => '',
                            'Line2' => '',
                            'Line3' => '',
                            'City' => '',
                            'StateOrProvinceCode' => '',
                            'PostCode' => '',
                            'CountryCode' => ''
                        ),
                        'Contact' => array(
                            'Department' => '',
                            'PersonName' => '',
                            'Title' => '',
                            'CompanyName' => '',
                            'PhoneNumber1' => '',
                            'PhoneNumber1Ext' => '',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' => '',
                            'EmailAddress' => '',
                            'Type' => ''
                        ),
                    ),
                    'Reference1' => 'Shpt 0001',
                    'Reference2' => '',
                    'Reference3' => '',
                    'ForeignHAWB' => rand(1, 10000),
                    'TransportType' => 0,
                    'ShippingDateTime' => time(),
                    'DueDate' => time(),
                    'PickupLocation' => 'Reception',
                    'PickupGUID' => '',
                    'Comments' => 'Shpt 0001',
                    'AccountingInstrcutions' => '',
                    'OperationsInstructions' => '',
                    'Details' => array(
                        'Dimensions' => array(
                            'Length' => 10,
                            'Width' => 10,
                            'Height' => 10,
                            'Unit' => 'cm',
                        ),
                        'ActualWeight' => array(
                            'Value' => 0.5,
                            'Unit' => 'Kg'
                        ),
                        'ProductGroup' => 'EXP',
                        'ProductType' => 'PDX',
                        'PaymentType' => 'P',
                        'PaymentOptions' => '',
                        'Services' => '',
                        'NumberOfPieces' => 1,
                        'DescriptionOfGoods' => 'Docs',
                        'GoodsOriginCountry' => 'Jo',

                        'CashOnDeliveryAmount' => array(
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ),

                        'InsuranceAmount' => array(
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ),

                        'CollectAmount' => array(
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ),

                        'CashAdditionalAmount' => array(
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ),
                        'CashAdditionalAmountDescription' => '',
                        'CustomsValueAmount' => array(
                            'Value' => $order->total,
                            'CurrencyCode' => getsetting('defaultCurrency')
                        ),
                        'Items' => array()
                    ),
                ),
            ),

            'ClientInfo' => array(
                'AccountCountryCode' => 'EG',
                'AccountEntity' => 'RUH',
                'AccountNumber' => '4004636',
                'AccountPin' => '432432',
                'UserName' => 'reem@reem.com',
                'Password' => '123456789',
                'Version' => '1.0'
            ),

            'Transaction' => array(
                'Reference1' => '001',
                'Reference2' => '',
                'Reference3' => '',
                'Reference4' => '',
                'Reference5' => '',
            ),
            'LabelInfo' => array(
                'ReportID' => 9201,
                'ReportType' => 'URL',
            ),
        );

        $params['Shipments']['Shipment']['Details']['Items'][] = array(
            'PackageType' => 'Box',
            'Quantity' => $order->products->count(),
            'Weight' => array(
                'Value' => 0.5,
                'Unit' => 'Kg',
            ),
            'Comments' => 'Docs',
            'Reference' => ''
        );

        try {
            $auth_call = $soapClient->CreateShipments($params);
            $response = json_decode(json_encode($auth_call), true);
            if ($auth_call->HasErrors) {
                $shippment_errors = object_get($auth_call, 'Shipments.ProcessedShipment.Notifications.Notification', null);
                if ($shippment_errors != null) {
                    $errors_array = json_decode(json_encode($shippment_errors), true);
                    return back()->withErrors($errors_array);
                }

                if (optional($auth_call->Notifications)->Notification != null) {
                    $errors = $auth_call->Notifications->Notification;
                    //                alert()->error(' حدث خطأ'.$auth_call);
                    //                popup('error',null,);
                    //                dd($errors);
                    $errors_array = json_decode(json_encode($errors), true);
                    return back()->withErrors($errors_array);
                } else {
                    alert()->error('خطا في عمليه الشحن');
                    return back();
                }
            } else {
//                dd($auth_call->Shipments->ProcessedShipment->ShipmentDetails->DescriptionOfGoods);
                $shipment = Shipment::create([
                    "shipment_ID" => $auth_call->Shipments->ProcessedShipment->ID,
                    "label_url" => $auth_call->Shipments->ProcessedShipment->ShipmentLabel->LabelURL,
                    "order_id" => $order->id,
                    "method_id" => $request['shipping_method_id'],
                    "shipment_details" => json_encode($auth_call->Shipments->ProcessedShipment->ShipmentDetails)
                ]);
                return $shipment;
            }
        } catch (SoapFault $fault) {
            popup('error', null, 'حدث خطأ');
            info($fault->faultstring);
            die('Error : ' . $fault->faultstring);
        }

    }
}
