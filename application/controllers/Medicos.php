<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Medicos extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'session'));
        $this->load->model("MedicosModel");

    }

    public function index(){
        $data = [
            "title" => "Medicos",
            "medicos" => $this->MedicosModel->getAll(mode:"OBJECT") ?? []
        ];

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('medicos', $data);
        $this->load->view('commons/footer', $data);
    }
}
