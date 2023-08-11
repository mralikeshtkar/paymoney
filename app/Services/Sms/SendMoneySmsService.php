<?php

/**
 * @package SendMoneySmsService
 * @author tehcvillage <support@techvill.org>
 * @contributor Ashraful Alam <[ashraful.techvill@gmail.com]>
 * @created 20-12-2022
 */

namespace App\Services\Sms;

use Exception;


class SendMoneySmsService extends TechVillageSms
{
    /**
     * The array of status and message whether sms sent or not.
     *
     * @var array
     */
    protected $response = [];

    public function __construct()
    {
        parent::__construct();
        $this->response = [
            'status'  => true,
            'message' => __("Transfer amount to receiver. A sms is sent to the sender.")
        ];
    }

    /**
     * Send sms to request creator
     *
     * @param object $requestPayment
     * @return array
     */
    public function send($requestPayment)
    {
        try {
            $sms = $this->getTemplate(1);
            if ($sms['status']) {
                return $sms;
            }

            $phoneNumber  = optional($requestPayment->sender)->formattedPhone;
            $creatorName  = !empty($requestPayment->sender_id) ? optional($requestPayment->sender)->full_name : $requestPayment->phone;
            $data = [
                '{creator}'      => $creatorName,
                '{amount}'       => moneyFormat(optional($requestPayment->currency)->symbol, formatNumber($requestPayment->amount)),
                '{acceptor}'     => optional($requestPayment->receiver)->full_name,
            ];
            $message = str_replace(array_keys($data), $data, $sms['template']->body);
            sendSMS($phoneNumber, $message);
        } catch (Exception $e) {
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }

}
