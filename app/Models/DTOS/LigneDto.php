<?php

namespace App\Models\DTOS;


class LigneDto{

    public int $id;
    public $itineraire = [];
    public $tarifs = [];
    public int $numero ;
    public $check_points = array();
    public int $frequence ;


    public function __construct(int $id, string $numero,  $check_points,$itineraire,$tarifs) {
        $this->id = $id;
        $this->numero = $numero;
        $this->check_points = $check_points;
        $this->itineraire = $itineraire;
        $this->frequence = 15;
        $this->tarifs = $tarifs;
    }

    

    
}



?>