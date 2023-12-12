<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndirectTrajet extends Model
{
    use HasFactory;
    protected $table = 'trajet_indirects'; 

    protected $fillable = [
        'depart',
        'arrive',
        'lignes', 
        'distance',
        'user_id',

    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
