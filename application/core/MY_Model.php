<?php

class MY_Model extends CI_Model
{
    protected $table;

    public function __construct(){
        parent::__construct();
    }

    public function insert($data){
        if (!isset($data) || !$data)
            return false;
        return $this->db->insert($this->table, $data);
    }

    /**
     * @throws Exception Choose a valid return type!
     */
    public function getById($id, $mode=ReturnType::ARRAY, $class=null){
        if (is_null($id))
            return false;
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            if ($mode == ReturnType::ARRAY)
                return $query->row_array();
            elseif ($mode == ReturnType::OBJECT)
                return $query->row_object();
            elseif ($mode == ReturnType::OBJECTTOCLASS)
                return $query->row(0, $class);
            else
                throw new Exception("Choose a valid return type!");
        }
        return null;
    }

    /**
     * @throws Exception Choose a valid return type!
     */
    public function getAll($sort = 'id', $order = 'asc', $mode=ReturnType::ARRAY, $class=null){
        $this->db->order_by($sort, $order);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            if ($mode == ReturnType::ARRAY)
                return $query->result_array();
            elseif ($mode == ReturnType::OBJECT)
                return $query->result_object();
            elseif ($mode == ReturnType::OBJECTTOCLASS)
                return $query->result($class);
            else
                throw new Exception("Choose a valid return type!");
        }
        return null;
    }

    public function update($id, $data) {
        if (is_null($id) || !isset($data))
            return false;
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id) {
        if (is_null($id))
            return false;

        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}