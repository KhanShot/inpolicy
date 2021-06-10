<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\PartnersImport;
use App\Imports\SpeakersImport;
use App\Models\Participants;
use App\Models\Partners;
use App\Models\Speakers;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;
use Barryvdh\DomPDF\PDF;


class ParticipantsController extends Controller
{
    public function participants(){
        $participants = Participants::paginate(25);


        return view("admin.email.participants");
    }


    public function partners(Request $request){
        $invitation_date = Partners::query()->groupBy("invitation_date")->pluck("invitation_date");

        $partners = Partners::query();

        if ($request->has("testing")){
            if ($request->get("testing") != "all")
                $partners->where("testing", $request->get("testing"));
        }

        if ($request->has("certificate")){
            if ($request->get("certificate") == 1){
                $partners->whereNotNull("certificate_url");
            }
            if ($request->get("certificate") == 0){
                $partners->whereNull("certificate_url");
            }
        }
        if ($request->has("language")){
            if ($request->get("language") != "all")
                $partners->where("language", $request->get("language"));
        }

        if ($request->has("session_date")){
            if ($request->get("session_date") != "all")
                $partners->where("invitation_date", $request->get("session_date"));
        }

        $partners->where("name", "like", "%".$request->get("search"). "%");

        $partners_ids = $partners->pluck("id");
        $partners = $partners->get();

        return view("admin.email.partners", compact("partners", "partners_ids","invitation_date"));
//        return view("admin.email.partners");
    }

    public function addParticipants(Request $request){
//        return "hell";
        $data = $request->validate([
            "name:required",
            "surname:required",
            "email:email|required"
        ]);
        return $data->validated();

    }

    public function getParticipants(Request $request){
        $participants = Participants::all();
        return datatables()->of($participants)->make(true);

    }

    public function speakers(Request $request){
        $invitation_date = Speakers::query()->groupBy("invitation_date")->pluck("invitation_date");

        $speakers = Speakers::query();

        if ($request->has("testing")){
            if ($request->get("testing") != "all")
                $speakers->where("testing", $request->get("testing"));
        }

        if ($request->has("certificate")){
            if ($request->get("certificate") == 1){
                $speakers->whereNotNull("certificate_url");
            }
            if ($request->get("certificate") == 0){
                $speakers->whereNull("certificate_url");
            }
        }
        if ($request->has("language")){
            if ($request->get("language") != "all")
                $speakers->where("language", $request->get("language"));
        }

        if ($request->has("session_date")){
            if ($request->get("session_date") != "all")
                $speakers->where("invitation_date", $request->get("session_date"));
        }
//        dd(Speakers::query()->where("session_name", "like", "%".$request->get("session_name"). "%")->get());
        $speakers->where("session_name", "like", "%".$request->get("session_name"). "%");

        $speakers->where("name", "like", "%".$request->get("search"). "%");

        $speakers_ids = $speakers->pluck("id");
        $speakers = $speakers->get();

        return view("admin.email.speakers", compact("speakers", "speakers_ids","invitation_date"));

    }


    public function deleteSpeakers($id){
        $speaker = Speakers::query()->find($id);
        if (!$speaker)
            return back()->with("message", "спикер не найден или уже удален!");
        $speaker->delete();
        return back()->with("message", "спикер успешно удален");
    }

    public function testingSpeakers($id){
        $speaker = Speakers::query()->find($id);
        if (!$speaker)
            return back()->with("message", "спикер не найден или уже удален!");

        if ($speaker->testing == 1){
            $speaker->testing = "0";
            $speaker->save();
            return back()->with("message", "Статус спикера изменена!");

        }
        if ($speaker->testing == 0){
            $speaker->testing = "1";
            $speaker->save();
            return back()->with("message", "Статус спикера изменена!");

        }
        return back()->with("message", "Статус спикера изменена!");

    }

    /**
     * @throws \PhpOffice\PhpWord\Exception\CopyFileException
     * @throws \PhpOffice\PhpWord\Exception\CreateTemporaryFileException
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function generate_cert_speakers(Request $request){
        if ($request->get("speakers_count") < 1){
            return back()->with("message", "Вы не выбрали ни одного спикера!");
        }
        $speakers_id = json_decode($request->get("speakers_id"));

        $speakers = Speakers::query()->whereIn("id", $speakers_id)->get();


        $docxFile = $request->file("cert_generate_file");
        $path = "certificates";
        $fileName = $docxFile->getClientOriginalName();
        $request->file("cert_generate_file")->move($path, $fileName);

        foreach ($speakers as $speaker) {
            $phpWord = new TemplateProcessor($path . "/" . $fileName);
            $phpWord->setValue("surname", $speaker->surname);
            $phpWord->setValue("name", $speaker->name );
            $phpWord->setValue("position", $speaker->position );
            $phpWord->setValue("organization", $speaker->organization );
            $phpWord->setValue("fathersname", $speaker->fathersname );
            $phpWord->setValue("session_date", $speaker->session_date );
            $phpWord->setValue("session_name", $speaker->session_name );
            $phpWord->setValue("invitation_date", $speaker->invitation_date );
            $phpWord->setValue("session_time_interval", $speaker->session_time_interval );
            $phpWord->setValue("appeal", $speaker->appeal );
            $phpWord->setValue("city", $speaker->city );
            $phpWord->setValue("country", $speaker->country );
            $phpWord->setValue("timezone", $speaker->timezone );
            $phpWord->setValue("email", $speaker->email );
            $phpWord->setValue("language", $speaker->language );
            $cert_path_docx = $path . "/". $speaker->name . "_". $speaker->surname . ".docx";

            $phpWord->saveAs($cert_path_docx);
            $speaker->certificate_url = $cert_path_docx;
            $speaker->save();
        }

        return  back()->with("message", "Сделано!");

//        dd($phpWord);

    }


    public function generate_cert_partners(Request $request){
        if ($request->get("partners_count") < 1){
            return back()->with("message", "Вы не выбрали ни одного партера!");
        }
        $partners_id = json_decode($request->get("partners_id"));

        $partners = Partners::query()->whereIn("id", $partners_id)->get();


        $docxFile = $request->file("cert_generate_file");
        $path = "certificates/partners";
        $fileName = $docxFile->getClientOriginalName();
        $request->file("cert_generate_file")->move($path, $fileName);

        foreach ($partners as $partner) {

            $phpWord = new TemplateProcessor($path . "/" . $fileName);
            
            $phpWord->setValue("surname", $partner->surname);
            $phpWord->setValue("name", $partner->name );
            $phpWord->setValue("position", $partner->position );
            $phpWord->setValue("organization", $partner->organization );
            $phpWord->setValue("fathersname", $partner->fathersname );
            $phpWord->setValue("name_short", mb_substr($partner->name, 0, 1 ));
            $phpWord->setValue("fathers_name_short", mb_substr($partner->fathersname, 0, 1 ) ?? "");
            $phpWord->setValue("invitation_date", $partner->invitation_date );
            $phpWord->setValue("appeal", $partner->appeal );
            $phpWord->setValue("city", $partner->city );
            $phpWord->setValue("country", $partner->country );
            $phpWord->setValue("appeal_without_fathersname", $partner->appeal_without_fathersname );
            $phpWord->setValue("email", $partner->email );
            $phpWord->setValue("language", $partner->language );
            $cert_path_docx = $path . "/". $partner->name . "_". $partner->surname . ".docx";
            $phpWord->saveAs($cert_path_docx);
            $partner->certificate_url = $cert_path_docx;
            $partner->save();
        }

        return  back()->with("message", "Сделано!");

//        dd($phpWord);

    }

    public function importPartners(Request $request){
//        return $request->file("partners_file");

        Excel::import(new PartnersImport, $request->file("partners_file"));

        return back()->with("message", "спикеры успешно импортированы");
    }

    public function importSpeakers(Request $request){
        Excel::import(new SpeakersImport, $request->file("speakers_file"));

        return back()->with("message", "спикеры успешно импортированы");

    }




}
