<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailConfiguration extends Model
{
    protected $fillable = [
        'HOST', 'PORT', 'username', 'PASSWORD', 'encryption', 'from_address', 'from_name'
    ];
}
