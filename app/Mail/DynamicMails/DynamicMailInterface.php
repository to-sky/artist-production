<?php

namespace App\Mail\DynamicMails;


interface DynamicMailInterface
{
    /**
     * @return array
     */
    public function getSubject();

    /**
     * @return array
     */
    public function getTo();

    /**
     * @return string
     */
    public function getBody();

    /**
     * @return string
     */
    public function getTemplateTag();

    /**
     * @return bool
     */
    public function canSend();
}