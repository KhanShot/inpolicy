<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partners extends Model
{
    use HasFactory;

    protected $fillable = [
        "name", "surname", "fathersname", "email", "email_2", "email_3",
        "testing", "position", "organization", "appeal", "appeal_without_fathersname",
        "language", "city", "country", "invitation_date"
    ];
}
