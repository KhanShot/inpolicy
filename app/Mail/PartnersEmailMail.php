<?php

namespace App\Mail;

use App\Models\Partners;
use App\Models\Speakers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PartnersEmailMail extends Mailable
{
    use Queueable, SerializesModels;
    public $receiver_ids;
    public $content;
    public $with_certificate;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($receiver_ids, $content, $with_certificate)
    {
        $this->receiver_ids = $receiver_ids;
        $this->content = $content;
        $this->with_certificate = $with_certificate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $content = $this->content;
        $partner = Partners::query()->find($this->receiver_ids);
        if ($this->with_certificate == 1)
            return $this->view('emails.mailMain', compact("content"))
                ->subject("Персональное письмо руководителю о III Нобелевском фестивале")
                ->attach(public_path(). "/". $partner->certificate_url)
                ->attach(public_path(). "/certificates/partners_email.pdf", ['mime' => 'application/pdf']);
        return $this->view('emails.mailMain', compact("content"))
            ->subject("Персональное письмо руководителю о III Нобелевском фестивале");
    }

}
