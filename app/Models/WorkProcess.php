<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkProcess extends Model
{
    use HasFactory;

    /* The attributes that are mass assignable */
    protected $fillable = ['name', 'auto_ecole_id', 'steps'];

    protected $casts = [
        'steps' => 'array',
    ];
}
