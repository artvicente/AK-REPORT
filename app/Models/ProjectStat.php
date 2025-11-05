<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStat extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'category', // e.g. BKK, AKM, AKO
        'infosheets_received', // <== DAPAT KASAMA LAHAT NG FIELD
        'images_captured',     // <== DAPAT KASAMA LAHAT NG FIELD
        'encoded',             // <== DAPAT KASAMA LAHAT NG FIELD
        'for_review',          // <== DAPAT KASAMA LAHAT NG FIELD
        // ... iba pang fields
    ];
    // ...
}
