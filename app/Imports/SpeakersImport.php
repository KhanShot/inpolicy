<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Speakers;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;


class SpeakersImport implements ToModel, WithStartRow
{
    public function model(array $row){
        return new Speakers([
            "name" => $row[0],
            "surname" => $row[1],
            "testing" => true,
            "fathersname" => $row[2],
            "organization" => $row[3],
            "position" => $row[4],
            "email" => $row[5],
            "appeal" => $row[6],
            "session_name" => $row[7],
            "session_date" => $row[8],
            "session_time_interval" => $row[9],
            "country" => $row[11],
            "city" => $row[12],
            "invitation_date" => $row[13],
            "language" => $row[14],
            "timezone" => $row[15],
            "session_start_time" => now(),
            "session_end_time" => now()->addMinutes(45),
        ]);
    }

    public function startRow(): int
    {
        return 2;

    }
}
