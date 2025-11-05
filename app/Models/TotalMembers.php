<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalMembers extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'total', // <== PALITAN ITO! Ito ang column name sa database.
        'year',  // Optional, pero panatilihin para sa kalinawan
        'type'   // Optional, pero panatilihin para sa kalinawan
    ];
    // ...
}
