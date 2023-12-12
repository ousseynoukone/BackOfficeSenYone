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
        'ligne_id',
        'user_id',
        'numero'
    ];


    public function ligne()
    {
        return $this->belongsTo(Ligne::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
