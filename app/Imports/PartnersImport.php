<?php

namespace App\Imports;

use App\Models\Partners;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PartnersImport implements ToModel, WithStartRow
{

    public function model(array $row)
    {
        return new Partners([
            "testing" => true,
            "name" => $row[0],
            "surname" => $row[1],
            "fathersname" => $row[2],
            "organization" => $row[3],
            "position" => $row[4],
            "email" => trim($row[5]) ?? "has-no_email@example.com",
            "email_2" => $row[6],
            "email_3" => $row[7],
            "appeal" => $row[8],
            "appeal_without_fathersname" => $row[9],
            "invitation_date" => $row[10],
            "language" => $row[11],
            "country" => $row[12],
            "city" => $row[13],
        ]);
    }

    public function startRow(): int
    {
        return 2;
    }
}
