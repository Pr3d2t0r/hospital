<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Utentes extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'session'));
        $this->load->model("UtentesModel");

    }

    public function index(){
        $data = [
            "title" => "Utentes",
            "utentes" => $this->UtentesModel->getAll(mode:"OBJECT") ?? []
        ];

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('utentes', $data);
        $this->load->view('commons/footer', $data);
    }
}
