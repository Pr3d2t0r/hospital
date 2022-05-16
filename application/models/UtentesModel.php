<?php

class UtentesModel extends MY_Model{


    public function __construct(){
        parent::__construct();
        $this->table = 'patient';
    }

    public function getByNumUtente($n_utente, $mode="ARRAY", $class=null){
        return $this->get("n_utente", $n_utente);
    }
}
