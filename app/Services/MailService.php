<?php

namespace App\Services;


use App\Mail\DynamicMails\DynamicMailInterface;

/**
 * Service to send dynamic messages from "settings.emails"
 * section on admin page
 *
 * Class MailService
 * @package App\Services
 */
class MailService
{
    /**
     * @var string
     */
    protected $_fromMail;

    /**
     * @var \Swift_Mailer
     */
    protected $_mailer;

    public function __construct()
    {
        $this->_fromMail = setting('mail_administrator_email');

        $this->_initMailer();
    }

    protected function _initMailer()
    {
        $transport = new \Swift_SmtpTransport(
            setting('smtp_server'),
            setting('smtp_port')
        );

        $transport
            ->setUsername(setting('smtp_login'))
            ->setPassword(setting('smtp_password'))
        ;

        $this->_mailer = new \Swift_Mailer($transport);
    }

    /**
     * Send a message
     *
     * @param DynamicMailInterface $mail
     * @return bool|int
     */
    public function send(DynamicMailInterface $mail)
    {
        if (!$mail->canSend()) return false;

        $message = new \Swift_Message($mail->getSubject());
        $message
            ->setFrom($this->_prepareFrom())
            ->setTo($mail->getTo())
            ->setBody($mail->getBody(), 'text/html', 'utf-8')
        ;

        return $this->_mailer->send($message);
    }

    /**
     * Compile from serction for message
     *
     * @return array
     */
    protected function _prepareFrom()
    {
        return [
            $this->_fromMail => config('app.name'),
        ];
    }
}