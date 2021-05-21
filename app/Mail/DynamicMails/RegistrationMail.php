<?php

namespace App\Mail\DynamicMails;


use App\Models\User;

/**
 * Email sent to freshly registered user
 *
 * Class RegistrationMail
 * @package App\Mail\DynamicMails
 */
class RegistrationMail extends AbstractDynamicMail
{
    protected $_password;

    public function __construct(User $user, $password)
    {
        parent::__construct($user);

        $this->_password = $password;
    }

    /**
     * Prepare placeholders data
     *
     * @return array
     */
    protected function _prepareData()
    {
        // @ClientName - client name
        // @SiteUrl - site url
        // @UserProfileURL - client profile link
        // @Login - client login = E-Mail
        // @Password - client password
        $data = [
            '@ClientName' => $this->_user->first_name,
            '@SiteUrl' => url('/'),
            '@UserProfileURL' => route('profile.show'),
            '@Login' => $this->_user->email,
            '@Password' => $this->_password,
        ];

        return $data;
    }

    /**
     * @return array
     */
    public function getSubject()
    {
        return __('Registration information');
    }

    /**
     * @return string
     */
    public function getTemplateTag()
    {
        return 'registration_confirmation';
    }

    /**
     * Get mail attachments list
     *
     * @return array
     */
    protected function _attachmentsList()
    {
        return [];
    }
}