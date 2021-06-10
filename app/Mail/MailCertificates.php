<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailCertificates extends Mailable
{
    use Queueable, SerializesModels;
    public $participants;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($participants)
    {
        $this->participants = $participants;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('emails.certificateEmail')->with(["participants"=>$this->participants]);
    }
}
