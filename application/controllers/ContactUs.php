<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ContactUs extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation', 'session'));
        $this->load->model("ContactsModel");

    }

    public function index(){
        $data = [
            "title" => "Contact Us",
        ];

        $this->form_validation->set_rules('name', 'Name', 'required|min_length[3]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', 'Subject', 'required|min_length[5]|max_length[200]');
        $this->form_validation->set_rules('content', 'Body', 'required|min_length[5]');

        if (!$this->form_validation->run()) {
            //validation_errors() -> método responsável por recuperar as mensagens
            $data['formErrors'] = validation_errors();
        } else {
            $data['formErrors'] = null;
            $this->session->set_flashdata("success_msg", "Contact sent with success!");
            $this->ContactsModel->insert($this->input->post());
        }

        $this->load->view('commons/header', $data);
        $this->load->view('commons/menu', $data);
        $this->load->view('contacts', $data);
        $this->load->view('commons/footer', $data);
    }
}
