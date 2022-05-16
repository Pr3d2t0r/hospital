<?php

class ConsultationsModel extends MY_Model{


    public function __construct(){
        parent::__construct();
        $this->table = 'consultations';
    }

    public function insert($data){
        if (!isset($data) || !$data)
            return false;
        $data['created_at'] = date("Y-m-d H:i:s");
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data) {
        if (is_null($id) || !isset($data))
            return false;
        $this->db->where('id', $id);
        $data['updated_at'] = date("Y-m-d H:i:s");
        return $this->db->update($this->table, $data);
    }

    public function getForToday($mode="ARRAY", $class=null){
        $this->db->where("date", date("Y-m-d"));
        $query = $this->db->get($this->table);
        $num_rows = $query->num_rows();
        if ($num_rows > 0) {
            if ($mode == "ARRAY")
                return $query->result_array();
            elseif ($mode == "OBJECT")
                return $query->result_object();
            elseif ($mode == "OBJECTTOCLASS")
                return $query->result($class);
            else
                throw new Exception("Choose a valid return type!");
        }
        return null;
    }
}
