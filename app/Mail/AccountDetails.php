<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * New user instance
     *
     * @var User
     */
    public $user;

    /**
     * Unhashed password
     *
     * @var string
     */
    public $password;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $password
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(__('Details of your new account'))
            ->view('emails.new_account')
        ;
    }
}
