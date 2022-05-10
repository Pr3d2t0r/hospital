<?php

class ContactsModel extends MY_Model{


    public function __construct(){
        parent::__construct();
        $this->table = 'contacts';
    }

    public function insert($data){
        if (!isset($data) || !$data)
            return false;
        $data['sent_at'] = date("Y-m-d H:i:s");
        return $this->db->insert($this->table, $data);
    }
}
