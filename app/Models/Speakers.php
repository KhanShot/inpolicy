<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speakers extends Model
{
    use HasFactory;
    protected $fillable = [
        "email", "name", "surname", "fathersname", "position", "organization",
        "appeal", "invitation_date", "session_name", "session_date", "session_time_interval",
        "session_start_time", "session_end_time", "language", "city", "country", "timezone",
        "certificate_url", "has_sent_certificate"
    ];
}
