<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ligne extends Model
{
    use HasFactory;
    protected $fillable = [
        'itineraire',
        'numero',
        'check_point',
        'tarifs'
    ];

}
