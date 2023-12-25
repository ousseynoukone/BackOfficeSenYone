<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectTrajet extends Model
{
    use HasFactory;
    
    protected $table = 'trajet_directs'; 

    protected $fillable = [
        'depart',
        'arrive',
        'departLat',
        'departLon',
        'arriveLat',
        'arriveLon',
        'distance',
        'frequence',
        'tarifs',
        'ligne',
        'user_id',
        'numero',
        'routeInfo',

    ];

    protected $casts = [
        'routeInfo' => 'json',
        'ligne' => 'json',

    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
