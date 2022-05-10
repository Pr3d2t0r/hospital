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
            "title" => "Medics",
            "medicos" => $this->MedicosModel->getAll(mode:"OBJECT") ?? []
        ];

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('medicos', $data);
        $this->load->view('commons/footer', $data);
    }

    public function add(){
        $data = [
            "title" => "Add Medic"
        ];

        $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]|max_length[80]');
        $this->form_validation->set_rules('nib', 'Nib', 'required|max_length[25]');
        $this->form_validation->set_rules('nif', 'Nif', 'required|max_length[9]');
        $this->form_validation->set_rules('specialty', 'Specialty', 'required|min_length[5]|max_length[100]');
        $this->form_validation->set_rules('address', 'Address', 'required|min_length[5]');
        $this->form_validation->set_rules('city', 'City', 'required|min_length[5]|max_length[100]');
        $this->form_validation->set_rules('birthday', 'Birthdate', 'required');
        $this->form_validation->set_rules('image', 'Image', 'required');

        if (!$this->form_validation->run()) {
            //validation_errors() -> método responsável por recuperar as mensagens
            $data['formErrors'] = validation_errors();
        } else {
            $data['formErrors'] = null;
            $this->session->set_flashdata("success_msg", "Contact sent with success!");
            $this->MedicosModel->insert($this->input->post());
        }

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('medicos_add', $data);
        $this->load->view('commons/footer', $data);
    }
}
