<?php

/**
 * @package ReceiveMoneyMailService
 * @author tehcvillage <support@techvill.org>
 * @contributor Ashraful Rasel <[ashraful.techvill@gmail.com]>
 * @created 20-12-2022
 */

namespace App\Services\Mail;

use Exception;


class ReceiveMoneyMailService extends TechVillageMail
{
    /**
     * The array of status and message whether email sent or not.
     *
     * @var array
     */
    protected $response = [];

    public function __construct()
    {
        parent::__construct();
        $this->response = [
            'status'  => true,
            'message' => __("Transfer amount. An email is sent to the sender.")
        ];
    }

    /**
     * Send email to request creator
     *
     * @param object $transfer
     * @return array
     */
    public function send($transfer)
    {
        try {
            $email = $this->getTemplate(2);
            if ($email['status']) {
                return $email;
            }

            $receiverEmail = $transfer->email;
            $senderName  = optional($transfer->sender)->full_name;
            $receiverName = (!empty($transfer->receiver_id)) ? optional($transfer->receiver)->full_name : $transfer->email;
            $data = [
                '{sender_id}'      => $senderName,
                '{receiver_id}'     => $receiverName,
                '{uuid}'         => $transfer->uuid,
                '{amount}'       => moneyFormat(optional($transfer->currency)->symbol, formatNumber($transfer->amount)),
                '{fee}'         => $transfer->fee,
                '{created_at}'   => dateFormat(now()),
                '{soft_name}'    => settings('name'),
            ];
            $message = str_replace(array_keys($data), $data, $email['template']->body);
            $this->email->sendEmail($receiverEmail, $email['template']->subject, $message);
        } catch (Exception $e) {
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }

}
