<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participants extends Model
{
    use HasFactory;

    protected $fillable = [
      "name", "surname", "email", "participated_date", "certificate_url", "has_sent_certificate"
    ];
}
