<?php

namespace App\Mail\DynamicMails;


interface DynamicMailInterface
{
    /**
     * @return array
     */
    public function getSubject();

    /**
     * @param bool $isCopy
     *
     * @return array
     */
    public function getTo($isCopy);

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

    /**
     * @return bool
     */
    public function shouldSendCopy();
}