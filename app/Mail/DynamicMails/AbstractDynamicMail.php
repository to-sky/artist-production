<?php

namespace App\Mail\DynamicMails;


use App\Models\User;

abstract class AbstractDynamicMail implements DynamicMailInterface
{
    protected $_user;

    public function __construct(User $user)
    {
        $this->_user = $user;
    }

    /**
     * Get to section for message
     *
     * @return array
     */
    public function getTo()
    {
        return [
            $this->_user->email => $this->_user->full_name,
        ];
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

        return view('layouts.mail', compact('template'))->render();
    }

    /**
     * Prepare placeholders data
     *
     * @return array
     */
    abstract protected function _prepareData();
}