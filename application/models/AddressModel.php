<?php

class AddressModel extends MY_Model{


    public function __construct(){
        parent::__construct();
        $this->table = 'address';
    }

    public function _modelar($item)
    {
        $addr = $this->getById($item->address_id);
        if ($addr == null) return $item;
        $item->address = $addr['name'];
        $item->city = $addr['city'];
        return $item;
    }
    public function _modelar_array($array)
    {
        $arr = [];
        foreach ($array as $item)
            $arr[] = $this->_modelar($item);
        return $arr;
    }
}
