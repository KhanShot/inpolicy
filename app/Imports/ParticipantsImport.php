<?php

namespace App\Imports;

use App\Models\Participants;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class ParticipantsImport implements ToModel
{

    public function model(array $row)
    {

        return new Participants([
            "name" => $row[0],
            "surname" => "",
            "email" => $row[1],
            "participated_date" => "15.04.21"
        ]);
    }
}
