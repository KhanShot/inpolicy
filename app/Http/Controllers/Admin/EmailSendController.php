<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Jobs\MailToSpeakers;
use App\Jobs\SendBulkQueueEmail;
use App\Jobs\SpeakersEmailJob;
use App\Models\Partners;
use App\Models\Speakers;
use Monolog\Handler\IFTTTHandler;
use PDF;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ParticipantsImport;
use App\Models\Participants;

class EmailSendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {

        $invitation_date = Speakers::query()->groupBy("invitation_date")->pluck("invitation_date");

        $receiver_ids = Participants::pluck("id");
        if ($request->get("message_to") == "speakers"){
            $speakers = Speakers::query();
            if ($request->has("language"))
              $speakers = Speakers::where("language", $request->get("language", "ru"));

            if ($request->has("testing"))
                $speakers = $speakers->where("testing", $request->get("testing"));
            if ($request->has("certificate")){
                if ($request->get("certificate") == 1)
                    $speakers = $speakers->whereNotNull("certificate_url");
                if ($request->get("certificate") == 0)
                    $speakers = $speakers->whereNull("certificate_url");
            }
            if ($request->has("session_date")){
                if ($request->get("session_date") != "all")
                    $speakers->where("invitation_date", $request->get("session_date"));
            }
            $receiver_ids = $speakers->pluck("id");
        }

        if ($request->get("message_to") == "partners"){
            $partners = Partners::query();
            $receiver_ids = $partners->pluck("id");
        }
        return view("admin.email.index", compact("receiver_ids", "invitation_date"));

    }

    public function sendEmail(Request $request){
        $job = (new SendBulkQueueEmail())->delay(now()->addSecond());
        $this->dispatch($job);
        return "H";
    }

    public function test_email(Request $request){
        $content = $request->get("content");
        $old_content = $content;
        $sids = json_decode($request->receiver_ids);
        if (sizeof($sids) == 0)
            return back()->with("message", "нет получателей!");
        if ($request->has("preview")){
            if ($request->get("message_to") == "partners"){
                $sids = json_decode($request->receiver_ids);
                if (sizeof($sids) >=1)
                    $partner = Partners::whereIn("id", $sids)->inRandomorder()->first();
                else{
                    $partner = Partners::inRandomorder()->first();
                }
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
                $user = $partner;
                return view("admin.email.test_email", compact("content", "user"));
            }
            if ($request->get("message_to") == "speakers"){
                $sids = json_decode($request->receiver_ids);
                if (sizeof($sids) >=1)
                    $speaker = Speakers::whereIn("id", $sids)->inRandomorder()->first();
                else{
                    $speaker = Speakers::inRandomorder()->first();
                }
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
                $user = $speaker;
                return view("admin.email.test_email", compact("content", "user"));
            }
            if ($request->get("message_to") == "participants" ){
                if (sizeof($sids) >=1)
                    $participants = Participants::whereIn("id", $sids)->inRandomorder()->first();
                else{
                    $participants = Participants::inRandomorder()->first();
                }
                $content = str_replace("[name]", $participants->name, $content);
                $content = str_replace("[surname]", $participants->surname, $content);
                $user = $participants;
                return view("admin.email.test_email", compact("content", "user"));
            }
        }
        if ($request->has("send")){
            $message_to = $request->get("message_to", "speakers");
            foreach ($sids as $sid){
                $job = (new SpeakersEmailJob($sid, $content, $request->get("with_certificate"), $message_to))->delay(now()->addSeconds(2));
                $this->dispatch($job);
                break;
            }
        }
        return back()->with("message", "Сообщении обрабатывается");

    }


    public function import(Request $request){

        Excel::import(new ParticipantsImport, $request->file("added_file"));

        return back();

    }

    public function downloadPDF($id, $name, Request $request){
        $surname = "";
        $name_surname = $name . " ";
        if($request->has('download')){
            $pdf = PDF::loadView('admin.email.pdfView', compact('id', "name", "surname", "name_surname"))->setPaper('a4', 'landscape');
//            dd($pdf);
            return $pdf->stream('{$name_$surname}.pdf')->header("charset", "UTF-8");
        }
        return view("admin.email.pdfView", compact('id', "name", "surname", "name_surname"));
    }

    public function downloadPDFTest(Request $request){

        if($request->has('download')){
            $pdf = PDF::loadView("admin.email.pdfView")->setPaper('a4', 'landscape');

            return $pdf->stream("hello.pdf");

        }
        return view("admin.email.pdfView");
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
