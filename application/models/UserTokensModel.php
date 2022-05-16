<?php

class UserTokensModel extends MY_Model{


    public function __construct(){
        parent::__construct();
        $this->table = 'users_tokens';
    }

    public function getFromToken($token, $mode="ARRAY", $class=null){
        return $this->get('token', $token, $mode, $class);
    }

    public function delete($token)
    {
        $this->db->where('token', $token);
        return $this->db->delete($this->table);
    }


}
