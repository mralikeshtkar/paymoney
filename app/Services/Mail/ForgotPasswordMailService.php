<?php

namespace App\Services\Mail;

use App\Exceptions\Api\V2\ForgotPasswordException;
use Exception;


class ForgotPasswordMailService extends TechVillageMail
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
            'message' => __('We have sent you a reset code. Please check your email.')
        ];
    }
    /**
     * Send forgot password code to user email
     * @param object $user
     * @return array $response
     */
    public function send($user)
    {
        try {
            $email = $this->getTemplate(47);
            if ($email['status']) {
                return $email;
            }
            $data = [
                '{user}'  => $user->full_name,
                '{password_reset_code}' => $user->code,
                '{soft_name}'        => settings('name'),
            ];
            $message = str_replace(array_keys($data), $data, $email['template']->body);
            $this->email->sendEmail($user->email, $email['template']->subject, $message);
        } catch (Exception $e) {
            $this->response['message'] = $e->getMessage();
        }
        return $this->response;
    }

}
