<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private $token;
    private $user;
    /**
     * Create a new message instance.
     */
    public function __construct($user,$token)
    {
        # here we recieve the user and token and assign them to private local values so we can use them in the build function
        $this->token = $token;
        $this->user = $user;


        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        # we pass them here to the email template you made
        return $this->markdown('Email.passwordReset',['token' => $this->token,'user'=>$this->user])
            ->subject('Reset Password Mail');
    }
}
