<?php

namespace App\Jobs;

use App\Mail\PartnersEmailMail;
use App\Mail\SpeakersEmailMail;
use App\Models\Partners;
use App\Models\Speakers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SpeakersEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $speakers_id;
    public $content;
    public $with_certificate;
    public $message_to;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($speakers_id, $content, $with_certificate, $message_to)
    {
        $this->speakers_id = $speakers_id;
        $this->content = $content;
        $this->with_certificate = $with_certificate;
        $this->message_to = $message_to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->message_to == 'speakers'){
            $this->messageToSpeakers();
        }
        if ($this->message_to == 'partners'){
            $this->messageToPartners();
        }
    }
    function messageToPartners(){
        $partner = Partners::query()->find($this->speakers_id);
        $content = $this->content;
        $content = str_replace("[name]", $partner->name, $content);
        $content = str_replace("[name_short]", mb_substr($partner->name, 0,1), $content);
        $content = str_replace("[surname]", $partner->surname, $content);
        $content = str_replace("[appeal]", $partner->appeal, $content);
        $content = str_replace("[fathersname]", $partner->fathersname, $content);
        $content = str_replace("[position]", $partner->position, $content);
        $content = str_replace("[organization]", $partner->organization, $content);
        $content = str_replace("[city]", $partner->city, $content);
        $content = str_replace("[country]", $partner->country, $content);
        $content = str_replace("[email]", $partner->email, $content);
        $content = str_replace("[language]", $partner->language, $content);
        Mail::to("rymkhanov.ali@mail.ru")
            ->send(new PartnersEmailMail($this->speakers_id, $content, $this->with_certificate));
    }

    function messageToSpeakers(){
        $speaker = Speakers::query()->find($this->speakers_id);
        $content = $this->content;
        $content = str_replace("[name]", $speaker->name, $content);
        $content = str_replace("[surname]", $speaker->surname, $content);
        $content = str_replace("[session_name]", $speaker->session_name, $content);
        $content = str_replace("[session_date]", $speaker->session_date, $content);
        $content = str_replace("[session_time_interval]", $speaker->session_time_interval, $content);
        $content = str_replace("[appeal]", $speaker->appeal, $content);
        $content = str_replace("[fathersname]", $speaker->fathersname, $content);
        $content = str_replace("[position]", $speaker->position, $content);
        $content = str_replace("[organization]", $speaker->organization, $content);
        $content = str_replace("[city]", $speaker->city, $content);
        $content = str_replace("[country]", $speaker->country, $content);
        $content = str_replace("[timezone]", $speaker->timezone, $content);
        $content = str_replace("[email]", $speaker->email, $content);
        $content = str_replace("[language]", $speaker->language, $content);

        Mail::to($speaker->email)
            ->send(new SpeakersEmailMail($this->speakers_id, $content, $this->with_certificate));
    }
}
