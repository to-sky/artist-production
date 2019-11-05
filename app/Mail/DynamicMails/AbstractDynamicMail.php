<?php

namespace App\Mail\DynamicMails;


use App\Models\User;

abstract class AbstractDynamicMail implements DynamicMailInterface
{
    protected $_user;

    protected $_attachments = [];

    protected $_additionalTo = [];

    public function __construct(User $user)
    {
        $this->_user = $user;
    }

    /**
     * Add recipient
     *
     * @param $email
     * @return $this
     */
    public function addTo($email) {
        if ($email) $this->_additionalTo[] = $email;

        return $this;
    }

    /**
     * Get to section for message
     *
     * @param bool $isCopy
     * @return array
     */
    public function getTo($isCopy)
    {
        return array_merge([
            $this->_user->email => $this->_user->full_name,
        ], $this->_additionalTo);
    }

    /**
     * Check if can send email
     *
     * @return bool
     */
    public function canSend()
    {
        $flag = intval(setting("mail_notify_{$this->getTemplateTag()}"));

        return !!$flag;
    }

    /**
     * Get full name of mail template in settings
     *
     * @return string
     */
    protected function _getTemplateName()
    {
       $tag = $this->getTemplateTag();
       $lang = app()->getLocale();
       $fallbackLang = config('app.fallback_locale');

       $name = "mail_{$tag}_{$lang}";

       if (empty(setting($name))) return "mail_{$tag}_{$fallbackLang}";

       return $name;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        $template = setting($this->_getTemplateName());

        foreach ($this->_prepareData() as $placeholder => $value) {
            $template = str_replace($placeholder, $value, $template);
        }

        foreach ($this->_attachmentsList() as $name => $callback) {
            if (strstr($template, "[$name]")) {
                $template = str_replace("[$name]", '', $template);
            }

            $this->_attachments[] = $callback($this);
        }

        return view('layouts.mail', compact('template'))->render();
    }

    /**
     * @return bool
     */
    public function shouldSendCopy()
    {
        return false;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->_attachments;
    }

    /**
     * Prepare placeholders data
     *
     * @return array
     */
    abstract protected function _prepareData();

    /**
     * Get mail attachments list
     *
     * @return array
     */
    abstract protected function _attachmentsList();
}