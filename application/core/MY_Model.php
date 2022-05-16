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
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * @throws Exception Choose a valid return type!
     */
    public function getById($id, $mode="ARRAY", $class=null){
        if (is_null($id))
            return false;
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            if ($mode == "ARRAY")
                return $query->row_array();
            elseif ($mode == "OBJECT")
                return $query->row_object();
            elseif ($mode == "OBJECTTOCLASS")
                return $query->row(0, $class);
            else
                throw new Exception("Choose a valid return type!");
        }
        return null;
    }

    /**
     * @throws Exception Choose a valid return type!
     */
    public function get($field, $value, $mode="ARRAY", $class=null){
        if (is_null($field) || is_null($value))
            return false;
        $this->db->where($field, $value);
        $query = $this->db->get($this->table);
        $num_rows = $query->num_rows();
        if ($num_rows > 0) {
            if ($mode == "ARRAY")
                return $num_rows > 1 ? $query->result_array() : $query->row_array();
            elseif ($mode == "OBJECT")
                return $num_rows > 1 ? $query->result_object() : $query->row_object();
            elseif ($mode == "OBJECTTOCLASS")
                return $num_rows > 1 ? $query->result($class) : $query->row(0, $class);
            else
                throw new Exception("Choose a valid return type!");
        }
        return null;
    }

    /**
     * @throws Exception Choose a valid return type!
     */
    public function getAll($sort = 'id', $order = 'asc', $mode="ARRAY", $class=null){
        $this->db->order_by($sort, $order);
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
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