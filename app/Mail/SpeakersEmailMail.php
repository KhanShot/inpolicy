<?php

namespace App\Mail;

use App\Models\Speakers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Swift_Attachment;

class SpeakersEmailMail extends Mailable
{
    use Queueable, SerializesModels;
    public $speakers_id;
    public $content;
    public $with_certificate;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($speakers_id, $content, $with_certificate)
    {
        $this->speakers_id = $speakers_id;
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
        $speaker = Speakers::query()->find($this->speakers_id);
        if ($this->with_certificate == 1)
            return $this->view('emails.mailMain', compact("content"))
                ->subject("Invitation to the III Central Asia Nobel Fest, October 2021")
                ->attach(public_path(). "/". $speaker->certificate_url)
                ->attach(public_path(). "/certificates/NobelFestOctoberENG_compressed.pdf", ['mime' => 'application/pdf']);
        return $this->view('emails.mailMain', compact("content"))
            ->subject("Invitation to the III Central Asia Nobel Fest, October 2021");

    }
}
