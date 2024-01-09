<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Metable\Metable;

class AutoEcole extends Model
{
    use HasFactory, Metable;

    /* Meta table for AutoEcole model */
    protected $metaTable = "auto_ecole_meta";

    /* The attributes that are mass assignable */
    protected $fillable = ['name', 'gerant_id', 'permis_list'];

    protected $casts = [
        "permis_list" => "array",
    ];
}
